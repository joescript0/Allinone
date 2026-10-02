<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Achats;
use App\Models\appnames;
use App\Models\Clients;
use App\Models\commisionsagents;
use App\Models\Factureass;
use App\Models\Facturess;
use App\Models\prospects;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function Safe\base64_decode;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Order;
use Gopay\GopayUi\DTO\PaymentFormData;
use Gopay\GopayUi\DTO\PaymentInsertAction;
use Gopay\GopayUi\DTO\PaymentUpdateAction;
use Gopay\GopayUi\Enums\PaymentSuccessAction;
use Gopay\GopayUi\GopayUI;


use \Osms\Osms;

class AuthenticatedSessionController extends Controller
{
    public function __construct()
    {
        $appnames = appnames::all();
        $nom_app = ["AFRICTECHAPP", "ILAINAPP", "CONTROLAPP", "EDIPASERVICE", "LES300HOMMES", "ALLINONE"];
        if($appnames->count() == 0)
        {
            $n = 1;
            foreach ($nom_app as $key => $value)
            {
                $id = $n;
                $appn = new appnames();
                $appn->id = $id;
                $appn->nom = $value;
                $appn->client = "";
                $appn->etat = 0;
                $appn->save();
                $n++;
            }
            $activename = appnames::where('id',  6)->first();
            $activename->etat = 1;
            $activename->save();
        }
        $this->middleware('guest')->except('logout');
        // $this->send_sms_clients();
        $this->client_to_prospect();
        $this->calculer_commission();
        // $this->mobile_money(10, 'USD', '993093010');
        // $this->envoyer_sms("+243831957983", "Mon amour ça ira t'inquite je recherche juste un d'argent pour finir avec ta dette des 60.000 mon bébé je juste fait un faux calcul amour pais ça ira ma cherie stp on fait le devis pour pour qu'on nous donnes la moitié my amor ecoute ça ira je regles ton problème bientot amour.");
    }
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if($request->has('' . base64_encode('poste_code') .''))
        {
            $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
            $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
            $data["poste_code"] = $request->query('' . base64_encode('poste_code') .'');
            return view('auth.login_qrcode', $data);
        }
        else
        {
            return view('auth.login_normal');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function orange_api($m, $receiverAddress)
    {
        $config = array(
            'clientId'     => 'oqlLT3dmjxVkxKPwj8vtAmKGaxDDaIji',
            'clientSecret' => 'wpTonBIb7HytohJvPG0cxKUHUruv3u4oEpyUo0BKv94e'
        );

        $osms = new Osms($config);

        // Sélection du message en fonction de $m
        $texte = '';
        if ($m == 1) {
            $texte = 'Bonjour cher client, les 300 hommes vous disent merci pour votre confiance et votre fidélité.';
        } elseif ($m == 2) {
            $texte = 'Bonjour cher client, dernier rappel : votre dette auprès des 300 hommes doit être réglée aujourd\'hui. Merci de votre compréhension.';
        }
        elseif ($m == 3)
        {
            $texte = 'Bon dimanche ! Les 300 hommes vous disent merci pour votre confiance. Passez une excellente journée !';
        }
        else {
            // Message par défaut si $m n'est ni 1 ni 2
            $texte = 'Bonjour cher client, ceci est un message automatique des 300 hommes.';
        }

        // Récupération automatique du token
        $response = $osms->getTokenFromConsumerKey();

        if (!empty($response['access_token'])) {
            $senderAddress   = 'tel:+243891470750';   // Votre numéro d'expéditeur (fixe)
            // $receiverAddress est passé en paramètre
            $message         = $texte;
            $senderName      = 'LES300HG';            // Nom de l'expéditeur

            $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
            echo "SMS envoyé avec succès !";
        } else {
            echo "Erreur : impossible d'obtenir le token.";
        }
    }

    public function send_sms_clients()
    {
        // Récupérer tous les clients (vous pouvez ajouter des conditions si besoin)
        $clients = Clients::all(); // ou Clients::where(...)->get()

        foreach ($clients as $client) {
            // Normaliser le téléphone : extraire uniquement les chiffres
            $phone = $client->phone;
            $digits = preg_replace('/\D/', '', $phone);

            // Vérifier que le nombre de chiffres est strictement supérieur à 9
            if (strlen($digits) > 9) {
                $last9 = substr($digits, -9);
                $client->phone = '+243' . $last9;

                // Vérifier et envoyer SMS si le compteur est < 1
                if ($client->sms_initial < 1)
                {
                    // Appeler l'API avec le préfixe 'tel:'
                    $this->orange_api(3, 'tel:' . $client->phone);
                    $client->sms_initial = $client->sms_initial + 1;
                }

                // Sauvegarder les modifications (phone normalisé et/ou compteur incrémenté)
                $client->save();
            }
            // Optionnel : si le numéro a 9 chiffres ou moins, vous pouvez logger ou ignorer
        }
    }

    public function client_to_prospect()
    {
        date_default_timezone_set('Africa/Lubumbashi');
        // Récupère les clients actifs SANS prospect
        $clients = Clients::where(["etat" => 1])
            ->whereNotIn('id', prospects::pluck('client_id'))
            ->get();

        foreach ($clients as $client)
        {
            // Créer un nouveau prospect à partir du client
            $id = prospects::get()->count() + 1;

            $prospect = new prospects();
            $prospect->id = $id;
            $prospect->name = $client->name;

            // Email
            if (strlen(trim($client->email)) == 0)
            {
                $prospect->email = 'prospect' . $id . '@gmail.com';
            } else {
                $prospect->email = $client->email;
            }

            // Adresse
            $prospect->adresse = strlen(trim($client->adresse)) == 0 ? "" : $client->adresse;

            // Description
            $prospect->description = strlen(trim($client->description)) == 0 ? "" : $client->description;

            // Latitude / Longitude
            $prospect->latitude  = strlen(trim($client->latitude))  != 0 ? $client->latitude  : 0;
            $prospect->longitude = strlen(trim($client->longitude)) != 0 ? $client->longitude : 0;

            // Copie des autres champs
            $prospect->activite_id = $client->activite_id;
            $prospect->type        = $client->type;
            $prospect->paiement    = $client->paiement;
            $prospect->devise      = $client->devise;
            $prospect->factures    = $client->factures;
            $prospect->phone       = $client->phone;
            $prospect->user_id     = $client->user_id;
            $prospect->etat        = 1;
            $prospect->recherche   = "";
            $prospect->image       = 'storage/images/user/profil_defaut.png';

            // Lien vers le client
            $prospect->client_id   = $client->id;
            $prospect->date_client = date("d/m/Y");

            $prospect->save();
        }

        // ============================================
        // Après conversion : afficher ceux SANS prospect
        // ============================================
        // $sans_prospect = Clients::where(["etat" => 1])
        //     ->whereNotIn('id', prospects::pluck('client_id'))
        //     ->get();

        // echo "=== Clients encore sans prospect : " . $sans_prospect->count() . " ===<br>";

        // foreach ($sans_prospect as $client)
        // {
        //     echo $client->name . " → Aucun prospect (ID: " . $client->id . ")<br>";
        // }
    }

    public function calculer_commission()
    {
        date_default_timezone_set('Africa/Lubumbashi');

        // Récupère les factures dont l'état = 0 et client_id != 0
        $factures = Factureass::where('etat', 0)
            ->where('client_id', '!=', 0)
            ->get();

        foreach ($factures as $facture) {

            // Récupère les achats liés à cette facture
            $achats = Achats::where('facture_id', $facture->id)->get();

            foreach ($achats as $achat) {

                // Vérifie si une commission existe déjà pour cet achat
                $existe = commisionsagents::where('achat_id', $achat->id)->exists();

                if (!$existe) {

                    // Création d'une nouvelle commission
                    $commission = new commisionsagents();

                    $commission->id   = commisionsagents::all()->count() + 1; // ID basé sur le nombre total d'achats
                    $commission->achat_id   = $achat->id;
                    $commission->article_id = $achat->article_id;
                    $commission->client_id  = $facture->client_id;
                    $commission->devise     = $achat->devise_achat ?? $facture->devise;

                    // Taux et User_id récupérés depuis la Facture
                    $commission->taux    = $facture->taux;
                    $commission->user_id = Clients::where('id', $facture->client_id)->first()["user_id"];

                    // Montant = total de l'achat
                    $commission->montant = $achat->total;

                    // Commission = 2% du montant de l'achat
                    $commission->commision = $achat->total * 0.02;

                    // 🔥 Date de création = created_at de la Facture au format d/m/Y
                    $commission->date_creation = \Carbon\Carbon::parse($facture->created_at)->format('d/m/Y');

                    // État (1 = actif, 0 = inactif)
                    $commission->etat = 1;

                    // 🔥 Synchronisation des timestamps avec la facture
                    $commission->timestamps = false;
                    $commission->created_at = $facture->created_at;
                    $commission->updated_at = $facture->updated_at;

                    $commission->save();
                }
            }
        }
    }

    public function envoyer_sms($telephone, $msg)
    {
        $sender = 'DIGITIZE';
        $telephone = substr($telephone, -9);
        $telephone = '243' . $telephone;
        $message = urlencode($msg);
        $api_url = 'https://api2.dream-digital.info/api/SendSMS?api_id=API25912858645&api_password=qaU7x5b7sm&sms_type=T&encoding=T&sender_id=DIVACHOU&phonenumber=' . $telephone . '&textmessage=' . $message;
        $response = file_get_contents($api_url);
    }

    public function mobile_money($montant, $devise, $telephone, $reference)
    {
        $form = new PaymentFormData(

            amount: $montant,

            currency: $devise,

            phone: $telephone,

            onSuccess: PaymentSuccessAction::GO_TO_URL,

            redirectUrl: '/payment/success?reference={reference}&amount={amount}&currency={currency}',

            formColor: '#262626',

            payBtnLabel: 'Payer maintenant',

            insertActions: [

                new PaymentInsertAction(
                    model: Order::class,
                    data: [
                        'reference' => '{reference}',
                        'amount' => '{amount}',
                        'currency' => '{currency}',
                        'name' => 'Paiement GoPay'
                    ]
                )

            ],

            updateActions: [

                new PaymentUpdateAction(
                    model: Order::class,
                    where: [
                        'reference' => '{reference}'
                    ],
                    data: [
                        'status' => 'PAID'
                    ]
                )

            ]

        );

        echo GoPayUI::renderForm($form);
    }
}
