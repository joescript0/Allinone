<?php

namespace App\Http\Controllers;

require base_path('vendor/autoload.php');

use App\Models\Contentieurs;
use App\Models\Contrevenants;
use App\Models\Decisions;
use App\Models\Groupes;
use App\Models\Invitations;
use App\Models\Type_frais;
use App\Models\Type_infractions;
use App\Models\Type_documents;
use App\Models\Fichier_documents;
use App\Models\User;
use App\Models\Entres;
use App\Models\Factures;
use App\Models\Postes;
use App\Models\Lieux;
use App\Models\Factureas;
use App\Models\Factureass;
use App\Models\Facturess;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\Annees;
use App\Models\Mois;
use App\Models\Societes;
use App\Models\Soldes;
use App\Models\Articles;
use App\Models\Approvisionnements;
use App\Models\Credits;
use App\Models\Fichierss;
use App\Models\Listespaies;
use App\Models\Rendezvous;
use App\Models\Clients;
use App\Models\Activites;
use App\Models\Alertes;
use App\Models\Depenses;
use Carbon\Carbon;
use App\Models\Listesfactures;
use App\Models\listesdesinvites;
use App\Models\Mesures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Fpdf\Fpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;



class InvitationnumController extends Controller
{

    public function invitation_numerique()
    {
        $data = [];
        return view('interfaces.invitation_numerique', $data);
    }
    
    public function invitation_formulaire()
    {
        $data = [];
        return view('interfaces.invitation_formulaire', $data);
    }
    
    public function invitation_programme()
    {
        $data = [];
        return view('interfaces.invitation_programme', $data);
    }
    
    public function check_qr_code(Request $request)
    {
        $code = $request->query('code');
    
        if (empty($code)) 
        {
            return view('interfaces.check_qr_code', ['error' => 'Aucun code fourni.']);
        }
    
        try {
            $invite = listesdesinvites::where('code_unique', $code)->first();
    
            if (!$invite) {
                return view('interfaces.check_qr_code', [
                    'error' => 'Ce code QR est invalide.',
                    'code'  => $code
                ]);
            }
    
            return view('interfaces.check_qr_code', compact('invite', 'code'));
    
        } catch (\Exception $e) {
            \Log::error('check_qr_code : ' . $e->getMessage());
            return view('interfaces.check_qr_code', [
                'error' => 'Erreur technique, consultez les logs.',
                'code'  => $code
            ]);
        }
    }
}
