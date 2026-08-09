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
use App\Models\Stocks;
use App\Models\Tables;
use App\Models\Typeventes;
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



class SiteController extends Controller
{

    public function menu()
    {
        // Chargement de toutes les données (sans aucune restriction)
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        $data["mesures"] = Mesures::where(["supprimer" => 0])->get();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["factures"] = Factures::get();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
        $data["tables"] = Tables::where(["etat" => 1, "supprimer" => 0])->get();

        // Tous les articles (plus de filtrage par user_id)
        $data["articles"] = Articles::where(["supprimer" => 0])->get();

        // Tous les stocks (plus de filtrage par user_id)
        $data["stocks"] = Stocks::where(["etat" => 1, "supprimer" => 0])->get();

        // Tous les utilisateurs actifs (plus de filtre sur l'ID courant)
        $data["utilisateurs"] = User::where(["etat" => 1])->get();

        // Vue retournée sans condition
        return view('interfaces.menu', $data);
    }
}
