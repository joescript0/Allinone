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



class PaiementController extends Controller
{

    public function paiement(request $request)
    {
        if($request->has('' . base64_encode('facture_id') .''))
        {
            $data["facture_id"] = $request->query('' . base64_encode('facture_id') .'');
            $data["cdf_montant"] = $request->query('' . base64_encode('cdf_montant') .'');
            $data["usd_montant"] = $request->query('' . base64_encode('usd_montant') .'');
            return view('interfaces.paiement', $data);
        }
        else
        {
            $data["facture_id"] = 0;
            $data["cdf_montant"] = "0.0";
            $data["usd_montant"] = "0.0";
            return view('interfaces.paiement', $data);
        }
    }
}
