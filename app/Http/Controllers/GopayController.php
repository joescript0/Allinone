<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GopayController extends Controller
{
    /**
     * Configuration GoPay
     */
    protected string $apiBase;
    protected string $apiKey;
    protected string $secretKey;
    protected int    $taux = 2300;
    protected string $environment;

    public function __construct()
    {
        $this->apiBase     = rtrim(env('GOPAY_API_BASE', 'https://gopay.gooomart.com'), '/');
        $this->apiKey      = env('GOPAY_API_KEY', '');
        $this->secretKey   = env('GOPAY_SECRET_KEY', '');
        $this->environment = env('GOPAY_ENV', 'production');
    }

    /* =========================================================
     |  SIGNATURE HMAC
     |  message = endpoint . method . http_build_query(params) . nonce . timestamp
     |  signature = hash_hmac('sha256', message, secret)
     * ========================================================= */

    protected function buildSignature(string $method, string $path, array $payload = []): array
    {
        $nonce     = bin2hex(random_bytes(16));
        $timestamp = time();
        $params    = http_build_query($payload);

        $message = $path . $method . $params . $nonce . $timestamp;

        $signature = hash_hmac('sha256', $message, $this->secretKey);

        return [
            'signature' => $signature,
            'nonce'     => $nonce,
            'timestamp' => $timestamp,
        ];
    }

    protected function signedHeaders(string $method, string $path, array $payload = []): array
    {
        $sig = $this->buildSignature($method, $path, $payload);

        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'x-api-key'    => $this->apiKey,
            'x-signature'  => $sig['signature'],
            'x-timestamp'  => $sig['timestamp'],
            'x-nonce'      => $sig['nonce'],
        ];
    }

    /* =========================================================
     |  ÉTAPE 1 : POST /api/v3/payment/init
     * ========================================================= */

    protected function gopay_init_payment($amount, $devise, $telephone, $myref)
    {
        $rep = [
            'success'    => false,
            'message'    => null,
            'data'       => null,
            'error_code' => null,
        ];

        $payload = [
            'amount'    => $amount,
            'devise'    => $devise,
            'telephone' => '+' . (float) $telephone,
            'myref'     => $myref,
        ];

        $path = '/api/v3/payment/init';

        try {
            $response = Http::withHeaders($this->signedHeaders('POST', $path, $payload))
                ->timeout(120)
                ->post($this->apiBase . $path, $payload);

            $json = $response->json() ?? [];

            $rep['success']    = $json['success']    ?? false;
            $rep['message']    = $json['message']    ?? null;
            $rep['data']       = $json['data']       ?? null;
            $rep['error_code'] = $json['error_code'] ?? null;

            if (!$rep['success'] && !$rep['message']) {
                $rep['message'] = "Erreur, veuillez reessayer.";
            }
        } catch (\Throwable $e) {
            $rep['message'] = "Erreur réseau, veuillez reessayer.";
        }

        return (object) $rep;
    }

    /* =========================================================
     |  ÉTAPE 2 : GET /api/v3/payment/check/{REF}
     * ========================================================= */

    protected function transaction_status($ref)
    {
        $path = '/api/v3/payment/check/' . $ref;

        try {
            $response = Http::withHeaders($this->signedHeaders('GET', $path, []))
                ->timeout(120)
                ->get($this->apiBase . $path);

            $json = $response->json();

            return $json['transaction'] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /* =========================================================
     |  PERSISTANCE APRÈS PAIEMENT RÉUSSI
     * ========================================================= */

    protected function saveData($paydata, $trans, $mode_abonnement)
    {
        DB::transaction(function () use ($paydata, $trans, $mode_abonnement) {
            $extra = (array) ($paydata->extra ?? []);

            unset($extra['ref']);

            $allowed = ['facture_id', 'numero_facture', 'mobile_money', 'duree', 'montant_recu'];
            $extra   = array_intersect_key($extra, array_flip($allowed));

            if ($mode_abonnement == 3) {
                DB::table('visites')->insert($extra);
            } else {
                DB::table('abonnements')->insert($extra);
            }

            DB::table('gopay')->where('id', $trans->id)->update([
                'issaved'  => 1,
                'isfailed' => 0,
            ]);
        });
    }

    /* =========================================================
     |  CRON : validations en arrière-plan
     * ========================================================= */

    public function completeTrans()
    {
        $pendingPayments = DB::table('gopay')
            ->where('issaved', 0)
            ->where('isfailed', 0)
            ->get();

        foreach ($pendingPayments as $trans) {
            $paydata = json_decode($trans->paydata);
            $myref   = $trans->myref;

            $t      = $this->transaction_status($trans->ref ?: $myref);
            $status = $t['status'] ?? null;

            if ($status === 'success') {
                $this->saveData($paydata, $trans, 1);
            } elseif ($status === 'failed') {
                DB::table('gopay')->where('id', $trans->id)->update(['isfailed' => 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    /* =========================================================
     |  ACTION PUBLIQUE : paiement de facture
     * ========================================================= */

    public function save_paiement_facture_1(Request $request)
    {
        $devise          = $request->input("devise_recu");
        $facture_id      = $request->input("facture_id");
        $numero_facture  = $request->input("numero_facture");
        $mobile_money    = $request->input("mobile_money");
        $montant_recu    = (float) $request->input("montant_recu");
        $duree           = 10;
        $date_debut      = date("d/m/Y");

        // Conversion CDF → devise de paiement (division par le taux)
        if ($devise === "CDF") {
            $montant_recu = $montant_recu / $this->taux;
        }

        $date_fin = Carbon::createFromFormat('d/m/Y', $date_debut)
            ->addDays((int) $duree)
            ->format('d/m/Y');

        $myref = 'myref' . time() . rand(10000, 90000);

        $data = [
            "facture_id"     => $facture_id,
            "numero_facture" => $numero_facture,
            "mobile_money"   => $mobile_money,
            "duree"          => $duree,
            "montant_recu"   => $montant_recu,
        ];

        // ✅ "environment" est une colonne SQL, pas une clé du JSON
        $insert_id = DB::table('gopay')->insertGetId([
            'issaved'     => 0,
            'isfailed'    => 0,
            'myref'       => $myref,
            'environment' => $this->environment,
            'paydata'     => json_encode([
                'devise'    => $devise,
                'amount'    => $montant_recu,
                'telephone' => $mobile_money,
                'extra'     => $data,
            ]),
        ]);

        $r = $this->gopay_init_payment($montant_recu, $devise, $mobile_money, $myref);

        if ($r->success && isset($r->data->ref)) {
            DB::table('gopay')->where('id', $insert_id)->update([
                'ref' => $r->data->ref,
            ]);
        }

        return response()->json([
            'success'    => $r->success,
            'message'    => $r->message,
            'error_code' => $r->error_code,
            'myref'      => $myref,
        ]);
    }

    /* =========================================================
     |  ACTION PUBLIQUE : vérification (polling client)
     * ========================================================= */

    public function check_payment(Request $request)
    {
        $myref           = $request->query("myref");
        $mode_abonnement = $request->query("mode_abonnement");
        $ok              = false;
        $issaved         = 0;

        $trans = DB::table("gopay")->where('myref', $myref)->get();

        if ($trans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Invalid ref",
            ]);
        }

        $trans = $trans->last();

        $ref = $trans->ref ?: $myref;

        $t      = $this->transaction_status($ref);
        $status = $t['status'] ?? null;

        if ($status === 'success') {
            $issaved = (int) $trans->issaved;
            if ($issaved !== 1) {
                $paydata = json_decode($trans->paydata);
                $this->saveData($paydata, $trans, $mode_abonnement);
                $ok = true;
                DB::table('gopay')->where('id', $trans->id)->update(['isfailed' => 0]);
            }
        } elseif ($status === 'failed') {
            DB::table('gopay')->where('id', $trans->id)->update(['isfailed' => 1]);
        }

        if ($ok || $issaved === 1 || (int) $trans->issaved === 1) {
            return response()->json([
                'success'     => true,
                'message'     => 'Votre paiement est effectué avec succès.',
                'transaction' => $t,
            ]);
        }

        return response()->json([
            'success'     => false,
            'message'     => "Aucun paiement trouvé.",
            'transaction' => $t,
        ]);
    }
}