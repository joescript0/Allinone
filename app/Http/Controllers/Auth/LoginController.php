<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\appnames;
use App\Models\Clients;
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
        // $this->orange_api(3, 'tel:+243859161908');
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

                // Vérifier et envoyer SMS si le compteur est < 5
                if ($client->sms_initial < 5) 
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
}
