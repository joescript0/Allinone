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
use App\Models\appnames;
use App\Models\beneficiaires;
use App\Models\classes;
use App\Models\Depenses;
use App\Models\districts;
use App\Models\ecoles;
use Carbon\Carbon;
use App\Models\Listesfactures;
use App\Models\Prestations;
use App\Models\Mesures;
use App\Models\Pointdeventes;
use App\Models\prospects;
use App\Models\listesdesinvites;
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
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $annee =  date("Y");
        $annees = Annees::where(["annees" => $annee])->get();
        if($annees->count() == 0)
        {
            $a =  new Annees();
            $a->annees = $annee;
            $a->save();
        }
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
        $n_app  = appnames::where('etat',  1)->first()["nom"];
        Config::set('app.name', $n_app);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        if((Auth::user()->module_connected == 1) || (Auth::user()->role == 0))
        {
            $data["categories"] = Societes::where(["etat" => 1])->get();
            $data["decisions"] = Decisions::where(["etat" => 1])->get();
            $data["alertes_par_mois"] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data["affectations_par_mois"] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data["decisions"] = Decisions::where(["etat" => 1])->get();
            $data["articles"] = Articles::where(["supprimer" => 0])->get();
            $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
            if(Auth::user()->role == 0)
            {
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
            }

            $data["utilisateurs"] = User::where(["etat" => 1,"id" => Auth::user()->id])->get();
            if(Auth::user()->role == 0)
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
            }
            if(Auth::user()->role == 0)
            {
                $clients = Clients::where(["etat" => 1])->get();
            }
            elseif(Auth::user()->role != 0)
            {
                $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
            }
            $data["clients"] = $clients;
            $data["postes"] = Postes::where(["supprimer" => 0, "etat" => 1])->get();
            $data["utilisateurs"] = User::where('role', '!=', 0)->where(["etat" => 1])->get();
            $data["clients"] = Clients::where(["etat" => 1])->get();
            $data["alertes"] = Alertes::where(["supprimer" => 0])->get();
            $data["prestations"] = Prestations::where(["supprimer" => 0])->get();
            $groupe_user_id = Auth::user()->role;
            $data["ressource_id_1"] = 1;
            $data["groupe_user_id"] = $groupe_user_id;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
            {
                $display = 0;
                if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
                {
                    $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
                if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
                {
                    return view('interfaces.dashboard', $data);
                }
                else
                {

                    Auth::guard('web')->logout();
                    return redirect('/');
                }
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }else if((Auth::user()->module_connected == 2) || (Auth::user()->role == 0))
        {
            $data = [];
            return view('interfaces.dashboard_scanner', $data);
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function generateAndSave()
    {
        $data = 'https://mon-site.com/profil/123';

        // Utilisation du Builder pour plus de contrôle
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(250)
            ->build();

        // Définir le nom et le chemin du fichier
        $fileName = 'qrcode_' . time() . '.png';
        $filePath = public_path('qrcodes/' . $fileName);

        // Assurez-vous que le dossier existe
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        // Sauvegarder l'image
        $result->saveToFile($filePath);

        return "QR code sauvegardé : " . asset('qrcodes/' . $fileName);
    }


    public function profils()
    {
        $data["users"] = User::where(["id" =>  Auth::user()->id])->first();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('interfaces.profils', $data);
    }

    public function utilisateurs()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 19;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["activites"] = Activites::where(["supprimer" => 0])->get();
                $nombre = 1;
                $matricule = "";
                foreach (User::get() as $ut)
                {
                    if(strlen(trim($ut->matricule)) == 0)
                    {
                        $user = User::where(["id" => $ut->id])->first();
                        $matricule = 'CAC' . str_pad($nombre, 4, '0', STR_PAD_LEFT);
                        $user->matricule = $matricule;
                        $user->save();
                    }
                    $nombre++;
                }
                return view('interfaces.utilisateurs', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function postes()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 4;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["lieux"] = Lieux::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                return view('interfaces.postes', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function gestion_ecole()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 20;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["lieux"] = Lieux::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["ecoles"] = ecoles::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                $data["districts"] = districts::get();
                return view('interfaces.ecoles', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function gestion_beneficiaire()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 21;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["lieux"] = Lieux::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["ecoles"] = ecoles::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                $data["districts"] = districts::get();
                $data["classes"] = classes::get();
                if(Auth::user()->role == 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $data["beneficiaires"] = $beneficiaires;
                return view('interfaces.beneficiaire', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function rapport_pointage()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 22;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["lieux"] = Lieux::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["ecoles"] = ecoles::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                $data["districts"] = districts::get();
                $data["all_alertes"] = Alertes::get();
                $data["classes"] = classes::get();
                $data["data_prestations"] = Prestations::where(["supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $data["beneficiaires"] = $beneficiaires;
                return view('interfaces.rapport_pointage', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function consulter_rapport()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 23;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["postes"] = Postes::where(["supprimer" => 0])->get();
                $data["lieux"] = Lieux::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["ecoles"] = ecoles::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                $data["districts"] = districts::get();
                $data["all_alertes"] = Alertes::get();
                $data["classes"] = classes::get();
                $data["data_prestations"] = Prestations::where(["supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $data["beneficiaires"] = $beneficiaires;
                return view('interfaces.consulter_rapport', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function clients()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 14;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                if(Auth::user()->role == 0)
                {
                    $clients = Clients::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $data["clients"] = $clients;
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                return view('interfaces.clients', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function prospects()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 25;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                if(Auth::user()->role == 0)
                {
                    $clients = Clients::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                if(Auth::user()->role == 0)
                {
                    $prospects = prospects::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $prospects = prospects::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $data["clients"] = $clients;
                $data["prospects"] = $prospects;
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                return view('interfaces.prospects', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }
    
    public function listesdesinvites()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 28;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(($display ==  1) || (Auth::user()->role == 0))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                if(Auth::user()->role == 0)
                {
                    $clients = Clients::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                if(Auth::user()->role == 0)
                {
                    $prospects = prospects::where(["etat" => 1])->get();
                }
                elseif(Auth::user()->role != 0)
                {
                    $prospects = prospects::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
                }
                $listesdesinvites = listesdesinvites::where(["etat" => 1])->get();
                $data["clients"] = $clients;
                $data["prospects"] = $prospects;
                $data["listesdesinvites"] = $listesdesinvites;
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                return view('interfaces.listesdesinvites', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function droits()
    {
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('interfaces.droits', $data);
    }

    public function invitations()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if((((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0))))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                return view('interfaces.invitations', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function projets()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                return view('interfaces.projets', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function decisions()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                return view('interfaces.decisions', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function rapports()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 4;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                return view('interfaces.rapports', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }


    public function bilan_sociale()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 4;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                return view('interfaces.bilan_sociale', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function dossier_contentieux()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 3;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if((((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0))))
            {
                $data["utilisateurs"] = User::where(function($query){
                    $query->where('role', '<>', 0);
                })->where(function($query){
                    $query->where('etat', '=', 1);
                })->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["contentieux"] = Contentieurs::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                return view('interfaces.dossier_contentieux', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function entres()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factures::get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.entres', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function gestion_credit()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
                $data["credits"] = Credits::where(["user_id" => Auth::user()->id])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factures::get();
                    $data["credits"] = Credits::get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.gestion_credits', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function app_article()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 9;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["factures"] = Factureas::get();
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.app_article', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function achat_article()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 10;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["factures"] = Factureass::where(["user_id" => Auth::user()->id, "etat" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factureass::where(["etat" => 0])->get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.achat_article', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function suivi_credit()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 26;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["factures"] = Factureass::where(["etat" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factureass::where(["etat" => 0])->get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.suivi_credit', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function facture_point_vente()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 27;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["tables"] = Tables::where(["etat" => 1, "supprimer" => 0])->get();
                $data["factures"] = Factureass::where(["user_id" => Auth::user()->id, "etat" => 0])->get();
                $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factureass::where(["etat" => 0])->get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.facture_point_vente', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function serveur_se()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 24;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["factures"] = Factureass::where(["user_id" => Auth::user()->id, "etat" => 0])->get();
                $data["tables"] = Tables::where(["etat" => 1, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factureass::where(["etat" => 0])->get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.serveur_se', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function debarrasseur_se()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 24;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["clients"] = Clients::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["articles"] = Articles::where(["supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["factures"] = Factureass::where(["user_id" => Auth::user()->id])->get();
                $data["tables"] = Tables::where(["etat" => 1, "supprimer" => 0])->get();
                $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Factureass::get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.debarrasseur_se', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function gestion_article()
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 8;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
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
                $data["articles"] = Articles::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
                $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
                $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
                $data["tables"] = Tables::where(["etat" => 1, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["articles"] = Articles::where(["supprimer" => 0])->get();
                }

                $data["utilisateurs"] = User::where(["etat" => 1,"id" => Auth::user()->id])->get();
                if(Auth::user()->role == 0)
                {
                    $data["utilisateurs"] = User::where(["etat" => 1])->get();
                }
                return view('interfaces.gestion_article', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }


    public function gestion_depense()
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 18;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
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
                $data["articles"] = Articles::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["articles"] = Articles::where(["supprimer" => 0])->get();
                }
                $data["depenses"] = Depenses::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
                if(Auth::user()->role == 0)
                {
                    $data["depenses"] = Depenses::where(["supprimer" => 0])->get();
                }
                return view('interfaces.gestion_depense', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function sorties()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["factures"] = Facturess::where(["user_id" => Auth::user()->id])->get();
                if(Auth::user()->role == 0)
                {
                    $data["factures"] = Facturess::get();
                }
                $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
                return view('interfaces.sorties', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function contrevenants()
    {
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        return view('interfaces.contrevenants', $data);
    }

    public function verbalisateurs()
    {
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        return view('interfaces.verbalisateurs', $data);
    }

    public function type_frais()
    {
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        return view('interfaces.type_frais', $data);
    }

    public function type_documents()
    {
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        return view('interfaces.type_documents', $data);
    }

    public function point_vente()
    {
        $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        return view('interfaces.point_vente', $data);
    }

    public function gestion_stock()
    {
        $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        return view('interfaces.gestion_stock', $data);
    }

    public function gestion_table()
    {
        $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["tables"] = Tables::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        return view('interfaces.gestion_table', $data);
    }

    public function type_infractions()
    {
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        return view('interfaces.type_infractions', $data);
    }

    public function gestion_societe()
    {
        $data["societes"] = Societes::where(["etat" => 1])->get();
        return view('interfaces.gestion_societe', $data);
    }

    public function gestion_solde()
    {
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        return view('interfaces.gestion_solde', $data);
    }

    public function gestion_activiter()
    {
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        return view('interfaces.gestion_activiter', $data);
    }

    public function paie()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 12;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
                $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                return view('interfaces.paie', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function listesfactures()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
                $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                return view('interfaces.listesfactures', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function alerte_centrale()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["alertes"] = Alertes::where(["supprimer" => 0])->get();
                $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
                $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                return view('interfaces.alerte_centrale', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function alerte_mobile()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["alertes"] = Alertes::where('supprimer', 0)->where('user_id_transfert', '!=', 0)->get();
                $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
                $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                $data["activites"] = Activites::where(["etat" => 1])->get();
                $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
                $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
                $data["annees"] = Annees::get();
                $data["mois"] = Mois::get();
                return view('interfaces.alerte_mobile', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }

    public function gestion_fichier()
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 13;
        $data["groupe_user_id"] = $groupe_user_id;
        if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0))
        {
            $display = 0;
            if((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0))
            {
                $display = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
            }
            $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
            if(((($display ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display ==  0) && (Auth::user()->role == 0)))
            {
                $data["utilisateurs"] = User::where(["etat" => 1])->get();
                $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
                $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
                $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
                $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
                $data["groupes"] = Groupes::where(["etat" => 1])->get();
                $data["invitations"] = Invitations::where(["etat" => 1])->get();
                $data["decisions"] = Decisions::where(["etat" => 1])->get();
                return view('interfaces.gestion_fichier', $data);
            }
            else
            {
                Auth::guard('web')->logout();
                return redirect('/');
            }
        }
        else
        {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }


    public function rendez_vous()
    {
        $data["rendez_vous"] = Rendezvous::where(["supprimer" => 0])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        return view('interfaces.rendez_vous_1', $data);
    }
}
