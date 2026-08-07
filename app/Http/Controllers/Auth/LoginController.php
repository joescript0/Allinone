<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\appnames;
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
    public function orange_api()
    {
        $config = array(
            'clientId'     => 'oqlLT3dmjxVkxKPwj8vtAmKGaxDDaIji',
            'clientSecret' => 'wpTonBIb7HytohJvPG0cxKUHUruv3u4oEpyUo0BKv94e'
            // Basic b3FsTFQzZG1qeFZreEtQd2o4dnRBbUtHYXhERGFJamk6d3BUb25CSWI3SHl0b2hKdlBHMGN4S1VIVXJ1djN1NG9FcHlVbzBCS3Y5NGU=
        );

        $osms = new Osms($config);

        // Récupération automatique du token
        $response = $osms->getTokenFromConsumerKey();

        if (!empty($response['access_token'])) {
            $senderAddress   = 'tel:+243891470750';   // Votre numéro d'expéditeur
            $receiverAddress = 'tel:+243831957983';   // Numéro du destinataire
            $message         = 'Bonjour, ceci est un test !';
            $senderName      = 'DIVACHOU';               // Nom de l'expéditeur (optionnel)

            $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
            echo "SMS envoyé avec succès !";
        } else {
            echo "Erreur : impossible d'obtenir le token.";
        }
    }
}
