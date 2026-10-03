<?php

namespace App\Http\Controllers;

use App\Models\Achats;
use App\Models\detailpaiessachats;
use App\Models\Factureass;
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

            $allowed = ['id', 'detail_facture_id', 'detail_montant_effectif', 'detail_devise_recu', 'detail_date_creation', 'detail_montant_recu', 'detail_mode_de_paiement', 'detail_reste'];
            $extra   = array_intersect_key($extra, array_flip($allowed));

            if ($mode_abonnement == 1)
            {
                $extra["id"] = detailpaiessachats::max('id') + 1;
                DB::table('detailpaiessachats')->insert($extra);
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
        $facture = Factureass::find($request->facture_id);
        if (!$facture) {
            DB::rollBack();
            return response()->json([0]);
        }

        $taux = $facture->taux;
        if ($taux <= 0) $taux = 1;

        $achats = Achats::where('facture_id', $facture->id)->get();

        // ------------------------------------------------------------
        // 0. Application des frais de crédit si conditions remplies
        // ------------------------------------------------------------
        // ⭐ Calcul du total original = Σ (total − réduction) par achat,
        //    dans SA devise d'achat, puis converti vers la devise facture.
        //    → sert à déterminer correctement si la facture est IMPAYÉE.
        $total_original = 0;
        foreach ($achats as $a)
        {
            $devise_achat_orig = $a->devise_achat ?? $facture->devise;
            $reduction_orig    = (isset($a->reduction) && $a->reduction > 0) ? $a->reduction : 0;
            $net_orig          = $a->total - $reduction_orig;

            if ($devise_achat_orig == $facture->devise) {
                $total_original += $net_orig;
            } elseif ($facture->devise == 0) {
                $total_original += ($taux > 0) ? ($net_orig / $taux) : 0;
            } else {
                $total_original += $net_orig * $taux;
            }
        }
        if ($facture->devise == 0)
        {
            $total_original_usd = $total_original;
            $total_original_cdf = $total_original * $taux;
        } else {
            $total_original_cdf = $total_original;
            $total_original_usd = ($taux > 0) ? ($total_original / $taux) : 0;
        }

        // Récupération des paiements déjà effectués
        $paiements = detailpaiessachats::where('facture_id', $facture->id)->get();
        $paye_usd = 0;
        $paye_cdf = 0;
        foreach ($paiements as $p) {
            if ($p->devise_recu == 0) {
                $paye_usd += $p->montant_recu;
                $paye_cdf += $p->montant_recu * $taux;
            } else {
                $paye_cdf += $p->montant_recu;
                $paye_usd += ($taux > 0) ? ($p->montant_recu / $taux) : 0;
            }
        }
        $est_impayee = ($paye_usd < $total_original_usd) || ($paye_cdf < $total_original_cdf);

        // Vérification du délai d'1 heure
        $date_creation = strtotime($facture->created_at);
        $delai_1h = 3600;
        $delai_depasse = (time() - $date_creation) > $delai_1h;

        // Application des frais de crédit (5%) sur chaque achat si conditions remplies
        // (frais calculés sur le total BRUT — inchangé)
        foreach ($achats as $achat) {
            if (($achat->frais_credit == 0 || $achat->frais_credit === null) && $est_impayee && $delai_depasse) {
                $frais = $achat->total * 0.05;
                $achat->frais_credit = $frais;
                $achat->save();
            }
        }

        // ------------------------------------------------------------
        // 1. ⭐ Calcul du total dû = Σ (total − réduction + frais_credit) par achat
        //    Chaque montant dans SA devise d'achat, puis converti.
        // ------------------------------------------------------------
        $total_du = 0;
        foreach ($achats as $a) {
            $devise_achat    = $a->devise_achat ?? $facture->devise;
            $reduction_achat = (isset($a->reduction) && $a->reduction > 0) ? $a->reduction : 0;
            $frais_achat     = $a->frais_credit ?? 0;

            $net_achat_devise = $a->total - $reduction_achat + $frais_achat;

            if ($devise_achat == $facture->devise) {
                $total_du += $net_achat_devise;
            } elseif ($facture->devise == 0) {
                $total_du += ($taux > 0) ? ($net_achat_devise / $taux) : 0;
            } else {
                $total_du += $net_achat_devise * $taux;
            }
        }

        if ($facture->devise == 0) {
            $total_du_usd = $total_du;
            $total_du_cdf = $total_du * $taux;
        } else {
            $total_du_cdf = $total_du;
            $total_du_usd = ($taux > 0) ? ($total_du / $taux) : 0;
        }

        // ------------------------------------------------------------
        // 2. Calcul du total déjà payé (depuis les détails) en USD et CDF
        // ------------------------------------------------------------
        $total_paye_usd = 0;
        $total_paye_cdf = 0;
        foreach ($paiements as $p) {
            if ($p->devise_recu == 0) {
                $total_paye_usd += $p->montant_recu;
                $total_paye_cdf += $p->montant_recu * $taux;
            } else {
                $total_paye_cdf += $p->montant_recu;
                $total_paye_usd += ($taux > 0) ? ($p->montant_recu / $taux) : 0;
            }
        }

        // ------------------------------------------------------------
        // 3. Restant dû dans la devise du paiement reçu (pour plafonner)
        // ------------------------------------------------------------
        $devise_paiement = $request->devise_recu;
        $reste_du_usd = max($total_du_usd - $total_paye_usd, 0);
        $reste_du_cdf = max($total_du_cdf - $total_paye_cdf, 0);

        if ($reste_du_usd <= 0 && $reste_du_cdf <= 0) {
            DB::rollBack();
            return response()->json([0]);
        }

        if ($devise_paiement == 0) {
            $reste_en_devise_paiement = $reste_du_usd;
        } else {
            $reste_en_devise_paiement = $reste_du_cdf;
        }

        // ------------------------------------------------------------
        // 4. Plafonnement du montant saisi
        // ------------------------------------------------------------
        $montant_saisi = $request->montant_recu;
        if ($montant_saisi <= 0)
        {
            DB::rollBack();
            return response()->json([0]);
        }
        $montant_effectif = min($montant_saisi, $reste_en_devise_paiement);
        $monnaie_a_rendre = max($montant_saisi - $montant_effectif, 0);

        // ------------------------------------------------------------
        // 5. Mise à jour de la facture (champs montant_recu et reste)
        // ------------------------------------------------------------
        if ($facture->devise == 0)
        {
            $montant_a_ajouter = ($devise_paiement == 0) ? $montant_effectif : $montant_effectif / $taux;
        } else {
            $montant_a_ajouter = ($devise_paiement == 1) ? $montant_effectif : $montant_effectif * $taux;
        }
        $facture->montant_recu += $montant_a_ajouter;
        $facture->devise_recu = $devise_paiement;
        $facture->mode_de_paiement = 1;
        $facture->reste = $monnaie_a_rendre;


        $devise          = $request->devise_recu;
        $mobile_money    = $request->mobile_money;
        $montant_recu    = (float) $request->montant_recu;

        $myref = 'myref' . time() . rand(10000, 90000);

        $data = [
            "detail_facture_id"     => $request->facture_id,
            "detail_montant_effectif" => $montant_effectif,
            "detail_devise_recu"   => $devise_paiement,
            "detail_date_creation"   => date("d/m/Y à H:i:s"),
            "detail_montant_recu"   => $montant_a_ajouter,
            "detail_mode_de_paiement"   => 2,
            "detail_reste"   => $monnaie_a_rendre,
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
            if ($issaved !== 1)
            {
                $paydata = json_decode($trans->paydata);
                $this->saveData($paydata, $trans, $mode_abonnement);
                $ok = true;
                DB::table('gopay')->where('id', $trans->id)->update(['isfailed' => 0]);
            }
        } elseif ($status === 'failed') {
            DB::table('gopay')->where('id', $trans->id)->update(['isfailed' => 1]);
        }

        if ($ok || $issaved === 1 || (int) $trans->issaved === 1)
            {
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
