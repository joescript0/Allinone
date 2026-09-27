<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Achats;
use App\Models\appnames;
use App\Models\Clients;
use App\Models\commisionsagents;
use App\Models\Factureass;
use App\Models\Facturess;
use App\Models\prospects;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use function Safe\base64_decode;
require base_path('vendor/autoload.php');


use \Osms\Osms;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
    }

    public function showLoginForm(Request $request)
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
        // (Utilisation du modèle Factures comme dans votre fichier)
        $factures = Factureass::where('etat', 0)
            ->where('client_id', '!=', 0)
            ->get();

        foreach ($factures as $facture) {

            // Récupère les achats liés à cette facture (comme ligne 360 de votre fichier)
            $achats = Achats::where('facture_id', $facture->id)->get();

            foreach ($achats as $achat) {

                // Vérifie si une commission existe déjà pour cet achat
                $existe = commisionsagents::where('achat_id', $achat->id)->exists();

                if (!$existe) {
                    // Création d'une nouvelle commission
                    $commission = new commisionsagents();
                    $commission->achat_id   = $achat->id;
                    $commission->article_id = $achat->article_id;
                    $commission->client_id  = $facture->client_id;
                    $commission->devise     = $achat->devise_achat ?? $facture->devise;

                    // Taux et User_id récupérés depuis la Facture
                    $commission->taux = $facture->taux;
                    $commission->user_id = $facture->user_id;

                    // Montant = total de l'achat
                    $commission->montant = $achat->total;

                    // Commission = 2% du montant de l'achat
                    $commission->commision = $achat->total * 0.02;

                    // Date de création au format d/m/Y
                    $commission->date_creation = date('d/m/Y');

                    // État (1 = actif, 0 = inactif)
                    $commission->etat = 1;

                    $commission->save();
                }
            }
        }
    }
}
