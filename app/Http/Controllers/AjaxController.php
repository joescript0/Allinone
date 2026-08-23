<?php

namespace App\Http\Controllers;

require base_path('vendor/autoload.php');
use App\Models\Attaches;
use App\Models\Documents;
use App\Models\Postes;
use App\Models\Prestations;
use App\Models\Lieux;
use Illuminate\Support\Str;
use App\Models\Clients;
use App\Models\Droit_fichiers;
use App\Models\Rendezvous;
use App\Models\Annees;
use App\Models\Contentieurs;
use App\Models\Contrevenants;
use App\Models\Decisions;
use App\Models\Fichiers;
use App\Models\Fichierss;
use App\Models\Frais;
use App\Models\Factures;
use App\Models\Factureas;
use App\Models\Factureass;
use App\Models\Facturess;
use App\Models\Groupes;
use App\Models\Invitations;
use App\Models\numdeclarations;
use App\Models\Payer;
use App\Models\Ressources;
use App\Models\Travailleurs;
use App\Models\Type_frais;
use App\Models\Type_infractions;
use App\Models\User;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\Societes;
use App\Models\Soldes;
use App\Models\Sorties;
use App\Models\Entres;
use App\Models\Mois;
use App\Models\Articles;
use App\Models\Achats;
use App\Models\Approvisionnements;
use App\Models\Credits;
use App\Models\Creditts;
use App\Models\Listespaies;
use App\Models\Paiements;
use App\Models\Paies;
use App\Models\Activites;
use App\Models\affectationstables;
use App\Models\Alertes;
use App\Models\articlestocks;
use App\Models\beneficaires;
use App\Models\beneficiaires;
use App\Models\classes;
use App\Models\communes;
use App\Models\Depenses;
use App\Models\detailpaiessachats;
use App\Models\detailsaffectationstables;
use App\Models\districts;
use App\Models\ecoles;
use App\Models\Remboursements;
use App\Models\Type_documents;
use App\Models\Fichier_documents;
use App\Models\Listesfactures;
use App\Models\Mesures;
use App\Models\Notifications;
use App\Models\Paiementsfactures;
use App\Models\Paiesfactures;
use App\Models\Pointdeventes;
use App\Models\Stocks;
use App\Models\Tables;
use App\Models\transfertstocks;
use App\Models\Typeventes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\New_;
use Illuminate\Support\Facades\Session;
use Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;


use DateTime;


class AjaxController extends Controller
{
    public function check_email(Request $request)
    {
        $email = $request->email_01;
        $user = User::where('email', $email)->first();
        if(($user))
        {
            return response()->json([[1]]);
        }
        else
        {
            return response()->json([[0]]);
        }
    }

    public function check_matricule(Request $request)
    {
        $matricule = $request->matricule;
        $user = User::where('matricule', $matricule)->first();
        if(($user))
        {
            return response()->json([[1]]);
        }
        else
        {
            return response()->json([[0]]);
        }
    }

    public function count_alerte_etat_1(Request $request)
    {
        $nombre_alerte_etat_1 = Alertes::where(['supprimer' => 0, 'etat_1' => 1])->get()->count();
        return response()->json([[$nombre_alerte_etat_1]]);
    }

    public function count_alerte_etat_2(Request $request)
    {
        $nombre_alerte_etat_1 = Alertes::where(['supprimer' => 0, 'etat_2' => 1])->where('user_id_transfert', '!=', 0)->get()->count();
        return response()->json([[$nombre_alerte_etat_1]]);
    }

    public function check_poste(Request $request)
    {
        $poste_code = base64_decode($request->poste_code);
        $matricule = $request->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();
        if($postes)
        {
            if($postes->etat == 0)
            {
                return response()->json([[1]]);
            }
            else if($postes->etat == 2)
            {
                return response()->json([[2]]);
            }
            else if($postes->id != $users->poste_id)
            {
                return response()->json([[3]]);
            }
        }
        else
        {
            return response()->json([[4]]);
        }
    }

    public function check_position_utilisateur_poste(Request $request)
    {
        $poste_code = base64_decode($request->poste_code);
        $matricule = $request->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();
        $latitude_u = $request->latitude;
        $longitude_u = $request->longitude;
        $latitude_p = $postes->latitude;
        $longitude_p = $postes->longitude;

        // --- EXEMPLE D'UTILISATION DE LA FONCTION GET getDistanceMetres ---
        $pointE = ['lat' => $latitude_u, 'lon' => $longitude_u]; // Tour Eiffel
        $pointB = ['lat' =>$latitude_p, 'lon' => $longitude_p]; // Trocadéro

        $distance = $this->getDistanceMetres($pointE['lat'], $pointE['lon'], $pointB['lat'], $pointB['lon']);
        $seuil = 150; // 50 mètres

        // $distance <= $seuil

        if ($distance <= $seuil)
        {
            return response()->json([[1]]);
        }
        else
        {
            // Else // loin du poste
            return response()->json([[1]]);
        }
    }



    public function control(Request $request)
    {
        date_default_timezone_set('Africa/Lubumbashi');
        $poste_id = Auth::user()->poste_id;
        $data = Postes::where('id',  $poste_id)->first();

        $poste_code = $data->code;
        $matricule = Auth::user()->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();
        $moi_id = 0;
        $annee_id = 0;

        $date_arrrive =  date("Y-m-d");

        if(Mois::where(['num' => explode("-", $date_arrrive)[1]])->get()->count() != 0)
        {
            $moi_id = Mois::where(['num' => explode("-", $date_arrrive)[1]])->first()["id"];
        }

        if(Annees::where(['annees' => explode("-", $date_arrrive)[0]])->get()->count() != 0)
        {
            $annee_id = Annees::where(['annees' => explode("-", $date_arrrive)[0]])->first()["id"];
        }


        $details = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first()['details'];
        $prestations = json_decode($details, true);


        $heure = date("H:i");
        $heure = str_replace(':', 'h', $heure);
        $horaire = "";

        $programme = 0;
        $repos = 0;
        $latitude_u = $request->latitude;
        $longitude_u = $request->longitude;
        $latitude_p = $postes->latitude;
        $longitude_p = $postes->longitude;

        $pointE = ['lat' => $latitude_u, 'lon' => $longitude_u];
        $pointB = ['lat' => $latitude_p, 'lon' => $longitude_p];
        $distance = $this->getDistanceMetres($pointE['lat'], $pointE['lon'], $pointB['lat'], $pointB['lon']);
        $seuil = 150;
        $prestations_details_save = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first();
        $valeur_de_retour = 0;

        foreach ($prestations as $ligne)
        {
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id))
            {
                $programme++;
            }
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] == "repos"))
            {
                $repos++;
            }
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] != "repos"))
            {
                $horaire = $ligne['horaire'];
            }
        }

        if($programme == 0)
        {
            $valeur_de_retour = 0;
        }
        else
        {
            if($repos != 0)
            {
                $valeur_de_retour = 0;
            }
            else
            {
                $res = $this->check_times($heure, $horaire);
                if ($res == 0)
                {
                    $valeur_de_retour = 0;
                }
                else
                {
                    // $distance > $seuil
                    if (1 == 2)
                    {
                        $valeur_de_retour = 1;
                    }
                    else
                    {
                        foreach ($prestations as &$ligne)
                        {
                            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id))
                            {

                                $entree = $ligne['pointages']['entree'];
                                $ronde_a_1 = $ligne['pointages']['ronde_a_1'];
                                $ronde_a_2 = $ligne['pointages']['ronde_a_2'];
                                $ronde_a_3 = $ligne['pointages']['ronde_a_3'];
                                $sortie = $ligne['pointages']['sortie'];
                                if($entree["etat"] == 0)
                                {
                                    $valeur_de_retour = 2;
                                }
                                else
                                {
                                    if((strlen(trim($ronde_a_1["heure_debut"])) == 0) && (strlen(trim($ronde_a_1["duree_fin"])) == 0))
                                    {
                                        $valeur_de_retour = 3;
                                    }
                                    else
                                    {
                                        if($this->check_times_1($heure, $ronde_a_1['heure_debut'] . ' - ' . $ronde_a_1['heure_fin']) == 1)
                                        {
                                            $valeur_de_retour = 4;
                                        }
                                        else
                                        {
                                            if($this->check_times_2($heure, $ronde_a_1['heure_debut'] . ' - ' . $ronde_a_1['heure_fin']) == 1 && $ronde_a_1["etat"] == 0)
                                            {
                                                $valeur_de_retour = 5;
                                            }
                                            else
                                            {
                                                if($this->check_times_3($heure, $ronde_a_1['heure_debut'] . ' - ' . $ronde_a_1['heure_fin']) == 1 && $ronde_a_1["etat"] == 0)
                                                {
                                                    $ligne['pointages']['ronde_a_1']['etat'] = 1;
                                                    $ligne['pointages']['ronde_a_1']['longitude'] = $request->longitude;
                                                    $ligne['pointages']['ronde_a_1']['resultat']['image'] = $users->image;
                                                    $ligne['pointages']['ronde_a_1']['capture_1'] = $users->image;
                                                    $ligne['pointages']['ronde_a_1']['capture_2'] = "./storage/images/user/visage_par_defaut.png";
                                                    $ligne['pointages']['ronde_a_1']['heure_recu'] = $ronde_a_1['heure_debut'];
                                                    $ligne['pointages']['ronde_a_1']['heure_reponse'] = $heure;
                                                    $ligne['pointages']['ronde_a_1']['latitude'] = $request->latitude;
                                                    $ligne['pointages']['ronde_a_1']['duree_reponse'] = 0;
                                                    $valeur_de_retour = 6;
                                                }
                                                else
                                                {
                                                    if($ronde_a_1["etat"] == 0)
                                                    {
                                                        $valeur_de_retour = 7;
                                                    }
                                                    else
                                                    {
                                                        // ========== CONTINUATION POUR RONDE_A_2, RONDE_A_3 ET SORTIE ==========
                                                        // Ronde A 2
                                                        if((strlen(trim($ronde_a_2["heure_debut"])) == 0) && (strlen(trim($ronde_a_2["duree_fin"])) == 0))
                                                        {
                                                            $valeur_de_retour = 8;
                                                        }
                                                        else
                                                        {
                                                            if($this->check_times_1($heure, $ronde_a_2['heure_debut'] . ' - ' . $ronde_a_2['heure_fin']) == 1)
                                                            {
                                                                $valeur_de_retour = 9;
                                                            }
                                                            else
                                                            {
                                                                if($this->check_times_2($heure, $ronde_a_2['heure_debut'] . ' - ' . $ronde_a_2['heure_fin']) == 1 && $ronde_a_2["etat"] == 0)
                                                                {
                                                                    $valeur_de_retour = 10;
                                                                }
                                                                else
                                                                {
                                                                    if($this->check_times_3($heure, $ronde_a_2['heure_debut'] . ' - ' . $ronde_a_2['heure_fin']) == 1 && $ronde_a_2["etat"] == 0)
                                                                    {
                                                                        $ligne['pointages']['ronde_a_2']["etat"] = 1;
                                                                        $ligne['pointages']['ronde_a_2']['longitude'] = $request->longitude;
                                                                        $ligne['pointages']['ronde_a_2']['resultat']['image'] = $users->image;
                                                                        $ligne['pointages']['ronde_a_2']['capture_1'] = $users->image;
                                                                        $ligne['pointages']['ronde_a_2']['capture_2'] = "./storage/images/user/visage_par_defaut.png";
                                                                        $ligne['pointages']['ronde_a_2']['heure_recu'] = $ronde_a_2['heure_debut'];
                                                                        $ligne['pointages']['ronde_a_2']['heure_reponse'] = $heure;
                                                                        $ligne['pointages']['ronde_a_2']['duree_fin'] = $this->dureeEnMinutes($ronde_a_2['heure_debut'], $ronde_a_2['heure_fin']);
                                                                        $ligne['pointages']['ronde_a_2']['latitude'] = $request->latitude;
                                                                        $ligne['pointages']['ronde_a_2']['duree_reponse'] = 0;
                                                                        $valeur_de_retour = 11;
                                                                    }
                                                                    else
                                                                    {
                                                                        if($ronde_a_2["etat"] == 0)
                                                                        {
                                                                            $valeur_de_retour = 12;
                                                                        }
                                                                        else
                                                                        {
                                                                            // Ronde A 3
                                                                            if((strlen(trim($ronde_a_3["heure_debut"])) == 0) && (strlen(trim($ronde_a_3["duree_fin"])) == 0))
                                                                            {
                                                                                $valeur_de_retour = 13;
                                                                            }
                                                                            else
                                                                            {
                                                                                if($this->check_times_1($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1)
                                                                                {
                                                                                    $valeur_de_retour = 14;
                                                                                }
                                                                                else
                                                                                {
                                                                                    if($this->check_times_2($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1 && $ronde_a_3["etat"] == 0)
                                                                                    {
                                                                                        $valeur_de_retour = 15;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        if($this->check_times_3($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1 && $ronde_a_3["etat"] == 0)
                                                                                        {
                                                                                            $ligne['pointages']['ronde_a_3']["etat"] = 1;
                                                                                            $ligne['pointages']['ronde_a_3']['longitude'] = $request->longitude;
                                                                                            $ligne['pointages']['ronde_a_3']['resultat']['image'] = $users->image;
                                                                                            $ligne['pointages']['ronde_a_3']['capture_1'] = $users->image;
                                                                                            $ligne['pointages']['ronde_a_3']['capture_2'] = "./storage/images/user/visage_par_defaut.png";
                                                                                            $ligne['pointages']['ronde_a_3']['heure_recu'] = $ronde_a_3['heure_debut'];
                                                                                            $ligne['pointages']['ronde_a_3']['heure_reponse'] = $heure;
                                                                                            $ligne['pointages']['ronde_a_3']['latitude'] = $request->latitude;
                                                                                            $ligne['pointages']['ronde_a_3']['duree_reponse'] = 0;
                                                                                            $valeur_de_retour = 16;
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            if($ronde_a_3["etat"] == 0)
                                                                                            {
                                                                                                $valeur_de_retour = 17;
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                // Sortie
                                                                                                if($this->check_times_4($heure, $horaire) == 1 && $sortie["etat"] == 0)
                                                                                                {
                                                                                                    $valeur_de_retour = 18;
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    $valeur_de_retour = 19;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        unset($ligne);
                    }
                }
            }
        }
        $prestations_details_save->details = json_encode($prestations, JSON_UNESCAPED_UNICODE);
        $prestations_details_save->save();
        return response()->json([[$valeur_de_retour]]);
    }
    public function envoyer_alerte(Request $request)
    {
        date_default_timezone_set('Africa/Lubumbashi');
        $poste_id = Auth::user()->poste_id;
        $data = Postes::where('id',  $poste_id)->first();

        $poste_code = $data->code;
        $matricule = Auth::user()->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();


        $id = Alertes::get()->count() + 1;
        $alerte = new Alertes();
        $alerte->id = $id;
        $alerte->user_id = $users->id;
        $alerte->latitude = $request->latitude;
        $alerte->longitude = $request->longitude;
        $alerte->motif = $request->motif;
        $alerte->poste_id = $postes->id;
        $alerte->etat_1 = 1;
        $alerte->etat_2 = 1;
        $alerte->save();
    }

    public function confirm_presence(Request $request)
    {
        date_default_timezone_set('Africa/Lubumbashi');
        $poste_id = Auth::user()->poste_id;
        $data = Postes::where('id',  $poste_id)->first();

        $poste_code = $data->code;
        $matricule = Auth::user()->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();

        $moi_id = 0;
        $annee_id = 0;

        $date_arrrive =  date("Y-m-d");

        if(Mois::where(['num' => explode("-", $date_arrrive)[1]])->get()->count() != 0)
        {
            $moi_id = Mois::where(['num' => explode("-", $date_arrrive)[1]])->first()["id"];
        }

        if(Annees::where(['annees' => explode("-", $date_arrrive)[0]])->get()->count() != 0)
        {
            $annee_id = Annees::where(['annees' => explode("-", $date_arrrive)[0]])->first()["id"];
        }


        $details = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first()['details'];
        $prestations = json_decode($details, true);

        $heure = date("H:i");
        $heure = str_replace(':', 'h', $heure);
        $horaire = "";

        $programme = 0;
        $repos = 0;
        $latitude_u = $request->latitude;
        $longitude_u = $request->longitude;
        $latitude_p = $postes->latitude;
        $longitude_p = $postes->longitude;

        $pointE = ['lat' => $latitude_u, 'lon' => $longitude_u];
        $pointB = ['lat' => $latitude_p, 'lon' => $longitude_p];
        $distance = $this->getDistanceMetres($pointE['lat'], $pointE['lon'], $pointB['lat'], $pointB['lon']);
        $seuil = 150;
        $prestations_details_save = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first();
        $valeur_de_retour = 0;

        foreach ($prestations as $ligne)
        {
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id))
            {
                $programme++;
            }
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] == "repos"))
            {
                $repos++;
            }
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] != "repos"))
            {
                $horaire = $ligne['horaire'];
            }
        }

        if($programme == 0)
        {
            $valeur_de_retour = 0;
        }
        else
        {
            if($repos != 0)
            {
                $valeur_de_retour = 0;
            }
            else
            {
                $res = $this->check_times($heure, $horaire);
                if ($res == 0)
                {
                    $valeur_de_retour = 0;
                }
                else
                {
                    // $distance > $seuil
                    if (1 == 2)
                    {
                        $valeur_de_retour = 1;
                    }
                    else
                    {
                        foreach ($prestations as &$ligne)
                        {
                            if(($ligne['date'] == $date_arrrive) && ($ligne['user_id'] == $users->id))
                            {
                                $entree = $ligne['pointages']['entree'];
                                $ronde_a_1 = $ligne['pointages']['ronde_a_1'];
                                $ronde_a_2 = $ligne['pointages']['ronde_a_2'];
                                $ronde_a_3 = $ligne['pointages']['ronde_a_3'];
                                $sortie = $ligne['pointages']['sortie'];
                                if($entree["etat"] == 0)
                                {
                                    // Gestion entrée
                                    $ligne['pointages']['entree']['etat'] = 1;
                                    $ligne['pointages']['entree']['heure'] = $heure;
                                    $ligne['pointages']['entree']['resultat']['etat'] = 1;
                                    $ligne['pointages']['entree']['resultat']['image'] = $request->url2;
                                    $ligne['pointages']['entree']['capture_1'] = $request->url1;
                                    $ligne['pointages']['entree']['capture_2'] = $request->url2;
                                    $ligne['pointages']['entree']['longitude'] = $request->longitude;
                                    $ligne['pointages']['entree']['latitude'] = $request->latitude;
                                    $valeur_de_retour = 2;
                                }
                                else
                                {
                                   if((strlen(trim($ronde_a_1["heure_debut"])) == 0) && (strlen(trim($ronde_a_1["duree_fin"])) == 0))
                                    {
                                        $valeur_de_retour = 3;
                                    }
                                    else
                                    {

                                        if($this->check_times_1($heure, $ronde_a_1['heure_debut'] . ' - ' . $ronde_a_1['heure_fin']) == 1 && $ronde_a_1["etat"] == 0)
                                        {
                                            $valeur_de_retour = 4;
                                        }
                                        if(($this->check_times_2($heure, $ronde_a_1['heure_debut'] . ' - ' . $ronde_a_1['heure_fin']) == 1) && ($ronde_a_1["etat"] == 0))
                                        {
                                            // Save ronde 1
                                            $valeur_de_retour = 5;
                                            $ligne['pointages']['ronde_a_1']['etat'] = 1;
                                            $ligne['pointages']['ronde_a_1']['longitude'] = $request->longitude;
                                            $ligne['pointages']['ronde_a_1']['resultat']['etat'] = 1;
                                            $ligne['pointages']['ronde_a_1']['resultat']['image'] = $request->url2;
                                            $ligne['pointages']['ronde_a_1']['capture_1'] = $request->url1;
                                            $ligne['pointages']['ronde_a_1']['capture_2'] = $request->url2;
                                            $ligne['pointages']['ronde_a_1']['heure_recu'] = $ronde_a_1['heure_debut'];
                                            $ligne['pointages']['ronde_a_1']['heure_reponse'] = $heure;
                                            $ligne['pointages']['ronde_a_1']['latitude'] = $request->latitude;
                                            $ligne['pointages']['ronde_a_1']['duree_reponse'] = $this->dureeEnMinutes($ronde_a_1['heure_debut'], $heure);
                                        }
                                        else
                                        {
                                            if($ronde_a_1["etat"] == 0)
                                            {
                                                $valeur_de_retour = 7;
                                            }
                                            else
                                            {
                                                // ========== CONTINUATION POUR RONDE_A_2, RONDE_A_3 ET SORTIE ==========
                                                // Ronde A 2
                                                if((strlen(trim($ronde_a_2["heure_debut"])) == 0) && (strlen(trim($ronde_a_2["duree_fin"])) == 0))
                                                {
                                                    $valeur_de_retour = 8;
                                                }
                                                else
                                                {
                                                    if($this->check_times_1($heure, $ronde_a_2['heure_debut'] . ' - ' . $ronde_a_2['heure_fin']) == 1 && $ronde_a_2["etat"] == 0)
                                                    {
                                                        $valeur_de_retour = 9;
                                                    }
                                                    else
                                                    {
                                                        if($this->check_times_2($heure, $ronde_a_2['heure_debut'] . ' - ' . $ronde_a_2['heure_fin']) == 1 && ($ronde_a_2["etat"] == 0))
                                                        {
                                                            // Save ronde 2
                                                            $valeur_de_retour = 10;
                                                            $ligne['pointages']['ronde_a_2']['etat'] = 1;
                                                            $ligne['pointages']['ronde_a_2']['longitude'] = $request->longitude;
                                                            $ligne['pointages']['ronde_a_2']['resultat']['etat'] = 1;
                                                            $ligne['pointages']['ronde_a_2']['resultat']['image'] = $request->url2;
                                                            $ligne['pointages']['ronde_a_2']['capture_1'] = $request->url1;
                                                            $ligne['pointages']['ronde_a_2']['capture_2'] = $request->url2;
                                                            $ligne['pointages']['ronde_a_2']['heure_recu'] = $ronde_a_2['heure_debut'];
                                                            $ligne['pointages']['ronde_a_2']['heure_reponse'] = $heure;
                                                            $ligne['pointages']['ronde_a_2']['latitude'] = $request->latitude;
                                                            $ligne['pointages']['ronde_a_2']['duree_reponse'] = $this->dureeEnMinutes($ronde_a_2['heure_debut'], $heure);
                                                        }
                                                        else
                                                        {
                                                            if($ronde_a_2["etat"] == 0)
                                                            {
                                                                $valeur_de_retour = 12;
                                                            }
                                                            else
                                                            {
                                                                // Ronde A 3
                                                                if((strlen(trim($ronde_a_3["heure_debut"])) == 0) && (strlen(trim($ronde_a_3["duree_fin"])) == 0))
                                                                {
                                                                    $valeur_de_retour = 13;
                                                                }
                                                                else
                                                                {
                                                                    if($this->check_times_1($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1 && $ronde_a_3["etat"] == 0)
                                                                    {
                                                                        $valeur_de_retour = 14;
                                                                    }
                                                                    else
                                                                    {
                                                                        if($this->check_times_2($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1 && $ronde_a_3["etat"] == 0)
                                                                        {
                                                                            // Save ronde 3
                                                                            $valeur_de_retour = 15;
                                                                            $ligne['pointages']['ronde_a_3']['etat'] = 1;
                                                                            $ligne['pointages']['ronde_a_3']['longitude'] = $request->longitude;
                                                                            $ligne['pointages']['ronde_a_3']['resultat']['etat'] = 1;
                                                                            $ligne['pointages']['ronde_a_3']['resultat']['image'] = $request->url2;
                                                                            $ligne['pointages']['ronde_a_3']['capture_1'] = $request->url1;
                                                                            $ligne['pointages']['ronde_a_3']['capture_2'] = $request->url2;
                                                                            $ligne['pointages']['ronde_a_3']['heure_recu'] = $ronde_a_3['heure_debut'];
                                                                            $ligne['pointages']['ronde_a_3']['heure_reponse'] = $heure;
                                                                            $ligne['pointages']['ronde_a_3']['latitude'] = $request->latitude;
                                                                            $ligne['pointages']['ronde_a_3']['duree_reponse'] = $this->dureeEnMinutes($ronde_a_3['heure_debut'], $heure);
                                                                        }
                                                                        else
                                                                        {
                                                                            if($this->check_times_3($heure, $ronde_a_3['heure_debut'] . ' - ' . $ronde_a_3['heure_fin']) == 1)
                                                                            {
                                                                                $valeur_de_retour = 16;
                                                                            }
                                                                            else
                                                                            {
                                                                                if($ronde_a_3["etat"] == 0)
                                                                                {
                                                                                    $valeur_de_retour = 17;
                                                                                }
                                                                                else
                                                                                {
                                                                                    // Sortie
                                                                                    if($this->check_times_4($heure, $horaire) == 1 && $sortie["etat"] == 0)
                                                                                    {
                                                                                        $valeur_de_retour = 18;
                                                                                        $ligne['pointages']['sortie']['etat'] = 1;
                                                                                        $ligne['pointages']['sortie']['heure'] = $heure;
                                                                                        $ligne['pointages']['sortie']['resultat']['etat'] = 1;
                                                                                        $ligne['pointages']['sortie']['resultat']['image'] = $request->url2;
                                                                                        $ligne['pointages']['sortie']['capture_1'] = $request->url1;
                                                                                        $ligne['pointages']['sortie']['capture_2'] = $request->url2;
                                                                                        $ligne['pointages']['sortie']['longitude'] = $request->longitude;
                                                                                        $ligne['pointages']['sortie']['latitude'] = $request->latitude;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $valeur_de_retour = 19;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        unset($ligne); // Supprime la référence
                    }
                }
            }
        }
        $prestations_details_save->details = json_encode($prestations, JSON_UNESCAPED_UNICODE);
        $prestations_details_save->save();
        return response()->json([[$valeur_de_retour]]);

    }

    public function check_horaire_poste(Request $request)
    {
        // Définir le fuseau horaire de Lubumbashi
        date_default_timezone_set('Africa/Lubumbashi');

        $poste_code = base64_decode($request->poste_code);
        $matricule = $request->matricule;
        $postes = Postes::where('code', $poste_code)->first();
        $users = User::where('matricule', $matricule)->first();
        $latitude_u = $request->latitude;
        $longitude_u = $request->longitude;
        $latitude_p = $postes->latitude;
        $longitude_p = $postes->longitude;
        $moi_id = 0;
        $annee_id = 0;

        $date_arrrive =  date("Y-m-d");

        if(Mois::where(['num' => explode("-", $date_arrrive)[1]])->get()->count() != 0)
        {
            $moi_id = Mois::where(['num' => explode("-", $date_arrrive)[1]])->first()["id"];
        }

        if(Annees::where(['annees' => explode("-", $date_arrrive)[0]])->get()->count() != 0)
        {
            $annee_id = Annees::where(['annees' => explode("-", $date_arrrive)[0]])->first()["id"];
        }

        $details = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first()['details'];
        $prestation = Prestations::where(['poste_id' => $postes->id, "moi_id" => $moi_id, "annee_id" => $annee_id])->first();

        $prestations = json_decode($details, true);


        $heure = date("H:i");
        $heure = str_replace(':', 'h', $heure);
        $horaire = "";

        $programme = 0;
        $repos = 0;


        foreach ($prestations as $ligne)
        {
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id))
            {
                $programme++;
            }

            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] == "repos"))
            {
                $repos++;
            }
            if((($ligne['date']) == ($date_arrrive)) && ($ligne['user_id'] == $users->id) && ($ligne['service'] != "repos"))
            {
                $horaire = $ligne['horaire'];
            }
        }
        if($programme == 0)
        {
            return response()->json([["reponse" => 0, "messaage" => 'Aucun programme trouvé.']]);
        }
        else
        {
            if($repos != 0)
            {
                return response()->json([["reponse" => 0, "messaage" => 'Vous êtes en repos']]);
            }
            else
            {
                $res = $this->verifierArrivee($heure, $horaire);
                if ($res['code'] == 1 || $res['code'] == 2)
                {
                    $rondes = $this->rondeStrategique($heure, trim(explode("-", $horaire)[1]), 15, 3);
                    foreach ($prestations as &$ligne) {
                        if (($ligne['date'] == $date_arrrive) && ($ligne['user_id'] == $users->id)) {
                            // S'assurer que 'pointages' existe
                            if (!isset($ligne['pointages']))
                            {
                                $ligne['pointages'] = [];
                            }
                            if(strlen(trim($ligne['pointages']['ronde_a_1']["heure_debut"])) == 0)
                            {
                                for ($i = 0; $i <= 2; $i++)
                                {
                                    $key = 'ronde_a_' . ($i + 1);
                                    if (!isset($ligne['pointages'][$key]))
                                    {
                                        $ligne['pointages'][$key] = [];
                                    }
                                    $ligne['pointages'][$key]['heure_debut'] = $rondes[$i]["debut"];
                                    $ligne['pointages'][$key]['heure_fin'] = $rondes[$i]["fin"];
                                    $ligne['pointages'][$key]["duree_fin"] = 15;
                                }
                            }
                        }
                    }
                    unset($ligne);

                    $prestation->details = json_encode($prestations, JSON_UNESCAPED_UNICODE);
                    $prestation->save();

                    Auth::login($users);
                    $user = User::where('id',  $users->id)->first();
                    $user->module_connected = 2;
                    $user->save();
                    $request->session()->regenerate();
                    return response()->json([["reponse" => 1, "messaage" => $res['message'], "rondes" => $rondes, "date_arrrive" => $date_arrrive]]);
                } else
                {
                    return response()->json([["reponse" => 0, "messaage" => $res['message']]]);
                }
            }
        }
    }

    public function getDistanceMetres($lat1, $lon1, $lat2, $lon2)
    {
        $rayonTerre = 6371000; // en mètres

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) ** 2 +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $rayonTerre * $c;
    }

    public function verifierArrivee($heureArrivee, $plageTravail)
    {
        // --- Tolérances (à ajuster selon votre politique) ---
        $toleranceAvance = 2880;   // minutes d'avance maximum autorisées
        $toleranceRetard = 2880;   // minutes de retard maximum autorisées (après la fin)

        // --- Convertit les heures en minutes depuis minuit ---
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            // Si le format est "HHMM" (sans séparateur) -> "HH:MM"
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            $parties = explode(':', $heure);
            $h = (int)$parties[0];
            $m = (int)$parties[1];
            return $h * 60 + $m;
        };

        $arrivee = $toMinutes($heureArrivee);
        $plage   = str_replace(' ', '', $plageTravail);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));

        // --- Gestion des plages qui traversent minuit ---
        if ($fin >= $debut) {
            // Plage sur la même journée
            $debutEtendue = $debut;
            $finEtendue   = $fin;
            $arriveeEtendue = $arrivee;
        } else {
            // Plage qui enjambe minuit (ex: 22h00-02h00)
            $debutEtendue = $debut;
            $finEtendue   = $fin + 1440; // +24h
            // Si l'arrivée est avant le début, on la décale au lendemain
            $arriveeEtendue = ($arrivee < $debut) ? $arrivee + 1440 : $arrivee;
        }

        // --- Résultat par défaut ---
        $resultat = [
            'code'    => 0,
            'statut'  => '',
            'ecart'   => 0,
            'message' => ''
        ];

        // ------------------------------------------------------------
        // 1. Vérifier si l'arrivée est trop tardive (après la fin + tolérance)
        // ------------------------------------------------------------
        if ($arriveeEtendue > $finEtendue + $toleranceRetard) {
            $resultat['code'] = 3;
            $resultat['statut'] = 'refuse_trop_tard';
            $resultat['ecart'] = $arriveeEtendue - $finEtendue;
            $resultat['message'] = "Arrivée trop tardive (après la fin de plage + tolérance de {$toleranceRetard} min). Refusé.";
            return $resultat;
        }

        // ------------------------------------------------------------
        // 2. Vérifier si l'arrivée est trop en avance (avant début - tolérance)
        // ------------------------------------------------------------
        if ($arriveeEtendue < $debutEtendue - $toleranceAvance)
        {
            $resultat['code'] = 3;
            $resultat['statut'] = 'refuse_trop_avance';
            $resultat['ecart'] = $debutEtendue - $arriveeEtendue;
            $resultat['message'] = "Arrivée trop en avance (avant le début de plage - tolérance de {$toleranceAvance} min). Refusé.";
            return $resultat;
        }

        // ------------------------------------------------------------
        // 3. Arrivée dans la zone de tolérance → on détaille le statut
        // ------------------------------------------------------------
        $ecartDebut = $arriveeEtendue - $debutEtendue;

        if ($ecartDebut < 0) {
            // Avance (avant le début) mais dans la tolérance
            $resultat['code'] = 1;
            $resultat['statut'] = 'avance_acceptable';
            $resultat['ecart'] = abs($ecartDebut);
            $resultat['message'] = "Avance acceptable de " . abs($ecartDebut) . " minute(s).";
        } else {
            // Arrivée après le début (ou à l'heure)
            if ($arriveeEtendue <= $finEtendue) {
                // Dans la plage horaire (ou juste à la fin)
                $resultat['code'] = 2;
                $resultat['statut'] = 'present';
                $resultat['ecart'] = $ecartDebut;
                $resultat['message'] = $ecartDebut == 0
                    ? "À l'heure. Présent dans la plage."
                    : "Retard acceptable de {$ecartDebut} minute(s) (dans la plage).";
            } else {
                // Arrivée après la fin, mais dans la tolérance de retard
                $resultat['code'] = 2;   // ou 4 si vous voulez un statut distinct
                $resultat['statut'] = 'retard_acceptable_apres_fin';
                $resultat['ecart'] = $arriveeEtendue - $finEtendue;
                $resultat['message'] = "Retard après la fin de plage, mais dans la tolérance de "
                                    . ($arriveeEtendue - $finEtendue) . " min. Accepté.";
            }
        }

        return $resultat;
    }

    function dureeEnMinutes($debut, $fin) {
        // Conversion heure → minutes (0-1440)
        $enMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $debutMin = $enMinutes($debut);
        $finMin   = $enMinutes($fin);

        // Si la plage traverse minuit, on ajoute 1440 à la fin
        if ($finMin <= $debutMin) {
            $finMin += 1440;
        }

        return $finMin - $debutMin;
    }

    public function check_times($heureEncours, $plageTravail)
    {
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            $parties = explode(':', $heure);
            return (int)$parties[0] * 60 + (int)$parties[1];
        };

        $arrivee = $toMinutes($heureEncours);
        $plage   = str_replace(' ', '', $plageTravail);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));

        $toleration = 500; // minutes d'avance ou de retard

        if ($fin >= $debut) {
            // Plage normale (ex: 09h00 - 18h00)
            $estDansPlage = ($arrivee >= $debut - $toleration && $arrivee <= $fin + $toleration);
        } else {
            // Plage traversant minuit (ex: 22h00 - 04h00)
            $estDansPlage = false;

            // Tolérance sur le jour même :
            // - avant minuit (debut - tolérance jusqu'à 24h)
            // - après minuit (0h jusqu'à fin + tolérance)
            if (($arrivee >= max(0, $debut - $toleration) && $arrivee <= 1440) ||
                ($arrivee >= 0 && $arrivee <= $fin + $toleration)) {
                $estDansPlage = true;
            }

            // Tolérance sur le lendemain (arrivée considérée comme +1 jour)
            $arriveeLendemain = $arrivee + 1440;
            if ($arriveeLendemain >= $debut + 1440 - $toleration &&
                $arriveeLendemain <= $fin + 1440 + $toleration) {
                $estDansPlage = true;
            }
        }

        return $estDansPlage ? 1 : 0;
    }

    public function check_times_1($heureEncours, $plageTravail)
    {
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $plage = str_replace(' ', '', $plageTravail);
        $plage = str_replace(',', '-', $plage);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));
        $arrivee = $toMinutes($heureEncours);

        // Cas avec ou sans minuit
        if ($fin <= $debut) {
            // Plage enveloppante : avant = après $fin et avant $debut
            $before = ($arrivee > $fin && $arrivee < $debut);
        } else {
            $before = ($arrivee < $debut);
        }

        return $before ? 1 : 0;
    }

    public function check_times_2($heureEncours, $plageTravail)
    {
        // Fonction de conversion (identique à rondeStrategique)
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $arrivee = $toMinutes($heureEncours);
        $plage   = str_replace(' ', '', $plageTravail);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));

        // Gestion du passage à minuit : si fin <= début, on ajoute 1440 à fin
        if ($fin <= $debut) {
            $fin += 1440;
            // Si l'arrivée est avant le début, on la décale de 1440 pour être sur la même échelle
            if ($arrivee < $debut) {
                $arrivee += 1440;
            }
        }

        // Vérification avec bornes incluses (>= début et <= fin)
        $estDansPlage = ($arrivee >= $debut && $arrivee <= $fin);

        return $estDansPlage ? 1 : 0;
    }

    public function check_times_3($heureEncours, $plageTravail)
    {
        // Fonction de conversion (identique à rondeStrategique)
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $arrivee = $toMinutes($heureEncours);
        $plage   = str_replace(' ', '', $plageTravail);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));

        // Gestion du passage à minuit : on ajoute 1440 à la fin si nécessaire
        if ($fin <= $debut) {
            $fin += 1440;
            // Si l'arrivée est avant le début, on la décale également de 1440
            // pour se placer sur la même échelle (jour J+1)
            if ($arrivee < $debut) {
                $arrivee += 1440;
            }
        }

        // Condition originale : strictement supérieur aux deux bornes
        // Après ajustement, cela équivaut à $arrivee > $fin (car $fin > $debut)
        $estApres = ($arrivee > $debut && $arrivee > $fin);

        return $estApres ? 1 : 0;
    }

    public function check_times_4($heureEncours, $plageTravail)
    {
        // Fonction de conversion en minutes (gère "H", "h", "HHMM", "HH:MM")
        $toMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            // Cas où l'heure est écrite sur 4 chiffres sans séparateur (ex: "1430")
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $arrivee = $toMinutes($heureEncours);

        // Nettoyer et extraire début / fin de la plage
        $plage = str_replace(' ', '', $plageTravail);
        list($debutStr, $finStr) = explode('-', $plage);
        $debut = $toMinutes(trim($debutStr));
        $fin   = $toMinutes(trim($finStr));

        // Gestion des plages qui traversent minuit (ex: "22h00-06h00")
        if ($fin <= $debut) {
            // La fin est en réalité le lendemain
            $fin += 1440; // ajoute 24h en minutes
            // Si l'heure actuelle est avant le début, elle appartient aussi au lendemain
            if ($arrivee < $debut) {
                $arrivee += 1440;
            }
        }

        // Retourne 1 si l'heure actuelle est >= à l'heure de fin
        return ($arrivee >= $fin) ? 1 : 0;
    }

    public function rondeStrategique_version_1($debut, $fin, $duree, $nombre) {
        // Conversion heure → minutes
        $enMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        // Conversion minutes → "HHhMM"
        $enHeure = function($minutes) {
            $h = floor($minutes / 60) % 24;
            $m = $minutes % 60;
            return sprintf("%02dh%02d", $h, $m);
        };

        $debutMin = $enMinutes($debut);
        $finMin   = $enMinutes($fin);

        // Plage traversant minuit ?
        if ($finMin <= $debutMin) {
            $finMin += 1440;
        }

        $dureeTotaleNecessaire = $nombre * $duree;
        $dureeDisponible = $finMin - $debutMin;
        $rondes = [];

        if ($dureeDisponible >= $dureeTotaleNecessaire) {
            // 1. Diviser en $nombre segments de taille égale
            $tailleSegment = floor($dureeDisponible / $nombre);
            $reste = $dureeDisponible % $nombre;

            $segments = [];
            $cumul = $debutMin;
            for ($i = 0; $i < $nombre; $i++) {
                $taille = $tailleSegment + ($i < $reste ? 1 : 0);
                $debutSegment = $cumul;
                $finSegment   = $cumul + $taille;
                $segments[] = ['debut' => $debutSegment, 'fin' => $finSegment];
                $cumul = $finSegment;
            }

            // 2. Placer une ronde aléatoire dans chaque segment
            $rondesTemp = [];
            foreach ($segments as $seg) {
                $debutMax = $seg['fin'] - $duree;
                if ($debutMax >= $seg['debut']) {
                    $debutRonde = rand($seg['debut'], $debutMax);
                    $finRonde   = $debutRonde + $duree;
                    $rondesTemp[] = [
                        'debut' => $enHeure($debutRonde % 1440),
                        'fin'   => $enHeure($finRonde % 1440)
                    ];
                }
            }

            // 3. Tri chronologique garanti
            usort($rondesTemp, function($a, $b) use ($enMinutes) {
                return $enMinutes($a['debut']) - $enMinutes($b['debut']);
            });

            $rondes = $rondesTemp;
        }

        return $rondes;
    }

    public function rondeStrategique_version_2($debut, $fin, $duree, $nombre) {
        // Conversion heure → minutes
        $enMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        // Conversion minutes → "HHhMM"
        $enHeure = function($minutes) {
            $h = floor($minutes / 60) % 24;
            $m = $minutes % 60;
            return sprintf("%02dh%02d", $h, $m);
        };

        $debutMin = $enMinutes($debut);
        $finMin   = $enMinutes($fin);

        // Plage traversant minuit ?
        if ($finMin <= $debutMin) {
            $finMin += 1440;
        }

        $dureeTotaleNecessaire = $nombre * $duree;
        $dureeDisponible = $finMin - $debutMin;
        $rondes = [];

        if ($dureeDisponible >= $dureeTotaleNecessaire) {
            // 1. Diviser en $nombre segments de taille égale
            $tailleSegment = floor($dureeDisponible / $nombre);
            $reste = $dureeDisponible % $nombre;

            $segments = [];
            $cumul = $debutMin;
            for ($i = 0; $i < $nombre; $i++) {
                $taille = $tailleSegment + ($i < $reste ? 1 : 0);
                $debutSegment = $cumul;
                $finSegment   = $cumul + $taille;
                $segments[] = ['debut' => $debutSegment, 'fin' => $finSegment];
                $cumul = $finSegment;
            }

            // Écarts autorisés pour la première ronde (en minutes) – choix aléatoire parmi eux
            $ecartsAutorises = [60, 70, 90, 120, 130, 140, 150];

            // 2. Placer une ronde aléatoire dans chaque segment
            $rondesTemp = [];
            foreach ($segments as $index => $seg) {
                $debutMax = $seg['fin'] - $duree;
                if ($debutMax >= $seg['debut']) {
                    // Si c'est le premier segment, on restreint aux écarts autorisés
                    if ($index === 0) {
                        $debutsPossibles = [];
                        foreach ($ecartsAutorises as $ecart) {
                            $debutAbsolu = $debutMin + $ecart;
                            if ($debutAbsolu >= $seg['debut'] && $debutAbsolu <= $debutMax) {
                                $debutsPossibles[] = $debutAbsolu;
                            }
                        }
                        // S'il y a au moins une valeur autorisée, on pioche aléatoirement dedans
                        if (!empty($debutsPossibles)) {
                            $debutRonde = $debutsPossibles[array_rand($debutsPossibles)];
                        } else {
                            // Fallback : tirage aléatoire normal (cas rare)
                            $debutRonde = rand($seg['debut'], $debutMax);
                        }
                    } else {
                        // Tirage aléatoire classique pour les autres rondes
                        $debutRonde = rand($seg['debut'], $debutMax);
                    }
                    $finRonde = $debutRonde + $duree;
                    $rondesTemp[] = [
                        'debut' => $enHeure($debutRonde % 1440),
                        'fin'   => $enHeure($finRonde % 1440)
                    ];
                }
            }

            // 3. Tri chronologique garanti
            usort($rondesTemp, function($a, $b) use ($enMinutes) {
                return $enMinutes($a['debut']) - $enMinutes($b['debut']);
            });

            $rondes = $rondesTemp;
        }

        return $rondes;
    }


    public function rondeStrategique($debut, $fin, $duree, $nombre)
    {
        // 1. Fonctions de conversion
        $enMinutes = function($heure) {
            $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
            if (strpos($heure, ':') === false && strlen($heure) == 4) {
                $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
            }
            list($h, $m) = explode(':', $heure);
            return ((int)$h * 60) + (int)$m;
        };

        $enHeure = function($minutes) {
            $h = floor(($minutes % 1440) / 60);
            $m = $minutes % 60;
            return sprintf("%02dh%02d", $h, $m);
        };

        // 2. Conversion des bornes en minutes
        $debutMin = $enMinutes($debut);
        $finMin   = $enMinutes($fin);

        // Gestion du passage à minuit
        if ($finMin <= $debutMin) {
            $finMin += 1440;
        }

        $dureeTotaleNecessaire = $nombre * $duree;
        $dureeDisponible = $finMin - $debutMin;
        $rondes = [];

        // 3. Vérifier si la plage est suffisante
        if ($dureeDisponible >= $dureeTotaleNecessaire) {
            // Découpage en segments de taille égale
            $tailleSegment = floor($dureeDisponible / $nombre);
            $reste = $dureeDisponible % $nombre;

            $segments = [];
            $cumul = $debutMin;
            for ($i = 0; $i < $nombre; $i++) {
                $taille = $tailleSegment + ($i < $reste ? 1 : 0);
                $segments[] = [
                    'debut' => $cumul,
                    'fin'   => $cumul + $taille
                ];
                $cumul += $taille;
            }

            // Écarts autorisés pour la première ronde (minutes après $debut)
            $ecartsAutorises = [60, 70, 90, 120, 130, 140, 150];

            $rondesTemp = [];

            foreach ($segments as $index => $seg) {
                $debutMax = $seg['fin'] - $duree;
                if ($debutMax >= $seg['debut']) {
                    if ($index === 0) {
                        // Première ronde : on applique un écart prédéfini
                        $debutsPossibles = [];
                        foreach ($ecartsAutorises as $ecart) {
                            $debutAbsolu = $debutMin + $ecart;
                            if ($debutAbsolu >= $seg['debut'] && $debutAbsolu <= $debutMax) {
                                $debutsPossibles[] = $debutAbsolu;
                            }
                        }
                        if (!empty($debutsPossibles)) {
                            $debutRonde = $debutsPossibles[array_rand($debutsPossibles)];
                        } else {
                            $debutRonde = rand($seg['debut'], $debutMax);
                        }
                    } else {
                        $debutRonde = rand($seg['debut'], $debutMax);
                    }
                    $rondesTemp[] = [
                        'debut_abs' => $debutRonde,
                        'fin_abs'   => $debutRonde + $duree
                    ];
                }
            }

            // Tri chronologique
            usort($rondesTemp, function($a, $b) {
                return $a['debut_abs'] - $b['debut_abs'];
            });

            // Conversion pour l'affichage
            foreach ($rondesTemp as $r) {
                $rondes[] = [
                    'debut' => $enHeure($r['debut_abs']),
                    'fin'   => $enHeure($r['fin_abs'])
                ];
            }
        }

        return $rondes;
    }

    public function analyserArrivee($heureArrivee, $plageTravail)
    {
        // Vérification de base
        $resultat = $this->verifierArrivee($heureArrivee, $plageTravail);

        if ($resultat['code'] == 1 || $resultat['code'] == 2) {
            // Outils de conversion
            $toMinutes = function($heure) {
                $heure = strtoupper(str_replace(['H', 'h'], ':', $heure));
                if (strpos($heure, ':') === false && strlen($heure) == 4) {
                    $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2);
                }
                list($h, $m) = explode(':', $heure);
                return ((int)$h * 60) + (int)$m;
            };

            $enHeure = function($minutes) {
                $h = floor($minutes / 60) % 24;
                $m = $minutes % 60;
                return sprintf("%02dh%02d", $h, $m);
            };

            $plage = str_replace(' ', '', $plageTravail);
            list($debutStr, $finStr) = explode('-', $plage);
            $debut = $toMinutes(trim($debutStr));
            $fin   = $toMinutes(trim($finStr));
            $arrivee = $toMinutes($heureArrivee);

            // Période effective pour les rondes : max(début plage, heure arrivée) → fin plage
            if ($fin >= $debut) {
                $debutEffectif = max($debut, $arrivee);
                $finEffectif   = $fin;
            } else {
                $finEtendue = $fin + 1440;
                $arriveeEtendue = ($arrivee <= $fin) ? $arrivee + 1440 : $arrivee;
                $debutEffectif = max($debut, $arriveeEtendue);
                $finEffectif   = $finEtendue;
            }

            $heureDebutRondes = $enHeure($debutEffectif % 1440);
            $heureFinRondes   = $enHeure($finEffectif % 1440);

            // Génération de 3 rondes de 15 minutes (ordre chronologique)
            $resultat['rondes'] = $this->rondeStrategique($heureDebutRondes, $heureFinRondes, 15, 3);
        } else {
            $resultat['rondes'] = [];
        }

        return $resultat;
    }

    public function exportPdf(Request $request)
    {
        $details = $request->query('details');
        $prestations = json_decode($details, true);

        // Reconstruire $date_date
        $date_date = [];
        foreach ($prestations as $ligne) {
            $date = $ligne['date'];
            if (!in_array($date, $date_date)) {
                $date_date[] = $date;
            }
        }

        $pdf = Pdf::loadView('include.prestations_pdf', compact('prestations', 'date_date'));
        return $pdf->download('prestations_' . date('Y-m-d') . '.pdf');
    }

    public function check_email_utilisateur(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if(($user))
        {
            return response()->json([[1]]);
        }
        else
        {
            return response()->json([[0]]);
        }
    }


    public function check_phone_utilisateur(Request $request)
    {
        $data = User::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->phone) == strtolower($request->phone)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }


    public function check_poste_existe(Request $request)
    {
        $data = Postes::where([
            "client_id" => $request->edit_client_id,
            "lieuxe_id" => $request->edit_lieu_id,
            "nom" => $request->edit_nom,
            "description" => $request->edit_description,
            "latitude" => $request->edit_latitude,
            "longitude" => $request->edit_longitude
        ])->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_ecole_existe(Request $request)
    {
        $data = ecoles::where([
            "nom" => $request->nom,
            "adresse" => $request->adresse,
            "annee_id" => $request->annee_id,
            "moi_id" => $request->moi_id,
        ])->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_eleve_existe(Request $request)
    {
        $data = beneficaires::where([
            "ecole_id" => $request->ecole_id,
            "nom_eleve" => $request->nom_eleve,
            "classe_id" => $request->classe_id,
        ])->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_eleve_existe_1(Request $request)
    {
        $data = beneficaires::where([
            "ecole_id" => $request->edit_ecole_id,
            "nom_eleve" => $request->edit_nom_eleve,
            "classe_id" => $request->edit_classe_id,
        ])->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_poste_existe_1(Request $request)
    {
        $data = Postes::where([
            "client_id" => $request->client_id,
            "lieuxe_id" => $request->lieu_id,
            "nom" => $request->nom,
            "description" => $request->description,
            "latitude" => $request->latitude,
            "longitude" => $request->longitude
        ])->when($request->id, function($query) use ($request) {
            return $query->where('id', '!=', $request->id);
        })->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_ecole_existe_1(Request $request)
    {
        $data = ecoles::where([
            "nom" => $request->edit_nom,
            "adresse" => $request->edit_adresse,
            "annee_id" => $request->edit_annee_id,
            "moi_id" => $request->edit_moi_id,
        ])->when($request->id, function($query) use ($request) {
            return $query->where('id', '!=', $request->id);
        })->get();
        $n = $data->count();
        return response()->json([[$n]]);
    }

    public function check_phone_utilisateur_1(Request $request)
    {
        $data = User::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->phone) == strtolower($request->edit_phone)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_mdpa(Request $request)
    {
        $user = User::where(["id" => Auth::user()->id])->first();
        if(Hash::check($request->mdpa, $user->password))
        {
            return response()->json([[1]]);
        }else
        {
            return response()->json([[0]]);
        }
    }

    public function check_groupe(Request $request)
    {
        $data = Groupes::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_contrevenant(Request $request)
    {
        $data = Contrevenants::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_verbalisateur(Request $request)
    {
        $data = Verbalisateurs::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_type_frais(Request $request)
    {
        $data = Type_frais::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_type_documents(Request $request)
    {
        $data = Type_documents::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_point_vente(Request $request)
    {
        $data = Pointdeventes::where(["user_id" => Auth::user()->id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_stock(Request $request)
    {
        $data = Stocks::where(["user_id" => Auth::user()->id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_tables(Request $request)
    {
        $data = Tables::where(["user_id" => Auth::user()->id, "pointdeventes_id" => $request->point_vente_id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }


    public function check_societe(Request $request)
    {
        $data = Societes::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_nom_article(Request $request)
    {
        $data = Articles::where(["mesure_id" => $request->mesure_id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom_article) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_nom_activiter(Request $request)
    {
        $data = Activites::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_edit_nom_activiter(Request $request)
    {
        $data = Activites::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_solde(Request $request)
    {
        $soldes = Soldes::where(["annee_id" => $request->annee_id, "moi_id" => $request->id])->get();
        return response()->json([[$soldes->count()]]);
    }

    public function check_solde_1(Request $request)
    {
        $soldes = Listespaies::where(["annee_id" => $request->annee_id, "moi_id" => $request->moi_id])->get();
        return response()->json([[$soldes->count()]]);
    }

    public function check_paie_facture(Request $request)
    {
        $facture = Factureass::find($request->facture_id);
        return response()->json([[$facture->payer]]);
    }

    public function get_all_facture(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factureass::get();
        return view('include.refresh_factureass', $data);
    }

    public function get_all_detail_achat_paie(Request $request)
    {
        // Recherche de la facture
        $facture = Factureass::find($request->facture_id);
        if (!$facture)
        {
            return response()->json(['error' => 'Facture non trouvée'], 404);
        }

        // --- 1. Calcul du montant total dû ---
        $achats = Achats::where('facture_id', $facture->id)->get();
        $total_ht = $achats->sum('total');
        $taux = $facture->taux;

        $montant_usd_1 = 0;
        $montant_cdf_1 = 0;
        if ($facture->devise == 0) { // facture en USD
            $montant_usd_1 = $total_ht;
            $montant_cdf_1 = $total_ht * $taux;
        } else { // facture en CDF
            $montant_cdf_1 = $total_ht;
            $montant_usd_1 = $total_ht / $taux;
        }

        // --- 2. Calcul du total déjà payé ---
        $paiements = detailpaiessachats::where('facture_id', $request->facture_id)->get();

        $montant_usd_2 = 0;
        $montant_cdf_2 = 0;

        foreach ($paiements as $paiement) {
            if ($paiement->devise_recu == 0) { // paiement en USD
                $montant_usd_2 += $paiement->montant_recu;
                $montant_cdf_2 += $paiement->montant_recu * $taux;
            } else { // paiement en CDF
                $montant_cdf_2 += $paiement->montant_recu;
                $montant_usd_2 += $paiement->montant_recu / $taux;
            }
        }

        // --- 3. Calcul des soldes restants ---
        $usd_montant_total_a_payer = $montant_usd_1 - $montant_usd_2;
        $cdf_montant_total_a_payer = $montant_cdf_1 - $montant_cdf_2;

        // --- 4. Retour des données ---
        return response()->json([
            'montant_usd_1'          => round($montant_usd_1, 2),
            'montant_cdf_1'          => round($montant_cdf_1, 2),
            'montant_usd_2'          => round($montant_usd_2, 2),
            'montant_cdf_2'          => round($montant_cdf_2, 2),
            'usd_montant_total_a_payer' => round($usd_montant_total_a_payer, 2),
            'cdf_montant_total_a_payer' => round($cdf_montant_total_a_payer, 2),
        ]);
    }

    public function save_paie_facture(Request $request)
    {
        try {
            $facture = Factureass::find($request->facture_id);
            if (!$facture) {
                DB::rollBack();
                return response()->json([0]);
            }

            $taux = $facture->taux;

            // ------------------------------------------------------------
            // 1. Calcul du total dû (depuis les achats) en USD et CDF
            // ------------------------------------------------------------
            $total_du = Achats::where('facture_id', $facture->id)->sum('total');
            // total_du est dans la devise de la facture (0=USD, 1=CDF)
            if ($facture->devise == 0) { // facture en USD
                $total_du_usd = $total_du;
                $total_du_cdf = $total_du * $taux;
            } else { // facture en CDF
                $total_du_cdf = $total_du;
                $total_du_usd = $total_du / $taux;
            }

            // ------------------------------------------------------------
            // 2. Calcul du total déjà payé (depuis les détails) en USD et CDF
            // ------------------------------------------------------------
            $paiements = detailpaiessachats::where('facture_id', $facture->id)->get();
            $total_paye_usd = 0;
            $total_paye_cdf = 0;
            foreach ($paiements as $p) {
                if ($p->devise_recu == 0) { // paiement en USD
                    $total_paye_usd += $p->montant_recu;
                } else { // paiement en CDF
                    $total_paye_cdf += $p->montant_recu;
                }
            }

            // ------------------------------------------------------------
            // 3. Restant dû dans la devise du paiement reçu (pour plafonner)
            // ------------------------------------------------------------
            $devise_paiement = $request->devise_recu; // 0=USD, 1=CDF
            // On calcule le restant dû en USD et CDF
            $reste_du_usd = max($total_du_usd - $total_paye_usd, 0);
            $reste_du_cdf = max($total_du_cdf - $total_paye_cdf, 0);

            if ($reste_du_usd <= 0 && $reste_du_cdf <= 0) {
                // déjà entièrement payé
                DB::rollBack();
                return response()->json([0]); // ou message "facture soldée"
            }

            // Restant dû dans la devise du paiement
            if ($devise_paiement == 0) { // paiement en USD
                $reste_en_devise_paiement = $reste_du_usd;
            } else { // paiement en CDF
                $reste_en_devise_paiement = $reste_du_cdf;
            }

            // ------------------------------------------------------------
            // 4. Plafonnement du montant saisi
            // ------------------------------------------------------------
            $montant_saisi = $request->montant_recu;
            if ($montant_saisi <= 0) {
                DB::rollBack();
                return response()->json([0]);
            }
            $montant_effectif = min($montant_saisi, $reste_en_devise_paiement);
            $monnaie_a_rendre = max($montant_saisi - $montant_effectif, 0);

            // ------------------------------------------------------------
            // 5. Mise à jour de la facture (champs montant_recu et reste)
            // ------------------------------------------------------------
            // On ajoute le montant effectif dans la devise du paiement
            // Mais on doit stocker montant_recu dans la devise de la facture ?
            // Dans votre code original, vous ajoutiez directement $request->montant_recu
            // sans conversion, donc je suppose que montant_recu est dans la devise de la facture.
            // Pour conserver la cohérence, on convertit le montant effectif dans la devise de la facture.
            if ($facture->devise == 0) { // facture en USD
                $montant_a_ajouter = ($devise_paiement == 0) ? $montant_effectif : $montant_effectif / $taux;
            } else { // facture en CDF
                $montant_a_ajouter = ($devise_paiement == 1) ? $montant_effectif : $montant_effectif * $taux;
            }
            $facture->montant_recu += $montant_a_ajouter;
            $facture->devise_recu = $devise_paiement;
            $facture->mode_de_paiement = 1;
            $facture->reste = $monnaie_a_rendre; // monnaie à rendre

            // ------------------------------------------------------------
            // 6. Insertion du détail de paiement avec le montant effectif
            // ------------------------------------------------------------
            $id = detailpaiessachats::max('id') + 1;
            $detail = new detailpaiessachats();
            $detail->id = $id;
            $detail->facture_id = $request->facture_id;
            $detail->montant_effectif = $montant_effectif; // on enregistre le montant effectif (plafonné)
            $detail->devise_recu = $devise_paiement;
            $detail->date_creation = date("d/m/Y à H:i:s");
            $detail->montant_recu = $montant_a_ajouter;
            $detail->mode_de_paiement = 1;
            $detail->reste = $monnaie_a_rendre; // monnaie à rendre
            $detail->save();

            DB::commit();

            // ------------------------------------------------------------
            // 7. Recalcul des totaux payés (après insertion) pour la comparaison stricte
            // ------------------------------------------------------------
            $paiements_apres = detailpaiessachats::where('facture_id', $facture->id)->get();
            $total_paye_usd_apres = 0;
            $total_paye_cdf_apres = 0;
            foreach ($paiements_apres as $p) {
                if ($p->devise_recu == 0) {
                    $total_paye_usd_apres += $p->montant_recu;
                } else {
                    $total_paye_cdf_apres += $p->montant_recu;
                }
            }

            // Comparaison stricte (sans tolérance) entre le dû et le payé
            if (($total_du_usd == $total_paye_usd_apres) && ($total_du_cdf == $total_paye_cdf_apres)) {
                $facture->payer = 1;
            } else {
                $facture->payer = 0;
            }
            $facture->save();

            return response()->json([1]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([0]);
        }
    }

    public function process_payment(Request $request)
    {
        DB::beginTransaction();

        try {
            $facture = Factureass::find($request->facture_id);

            if (!$facture) {
                DB::rollBack();
                return response()->json([0]);
            }

            $facture->payer = 1;
            $facture->montant_recu = $request->montant_recu;
            $facture->devise_recu = $request->devise_recu;
            $facture->mode_de_paiement = $request->mode_de_paiement;
            $facture->reste = 0;
            // $facture->save();

            DB::commit();
            return response()->json([1]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([0]);
        }
    }

    public function check_solde_2(Request $request)
    {
        $soldes = Listesfactures::where(["annee_id" => $request->annee_id, "moi_id" => $request->moi_id])->get();
        return response()->json([[$soldes->count()]]);
    }

    public function check_seuil_maximum(Request $request)
    {
        $achats = Achats::where(["article_id" => $request->article_id])->get();
        $total_pret = 0;
        foreach ($achats as $ee)
        {
            if($ee->type == 2)
            {
                $total_pret = $total_pret + $ee->quantite;
            }
        }
        $stock = Articles::where(["id" => $request->article_id])->first()["stock"];
        $seuil_maximum = Articles::where(["id" => $request->article_id])->first()["seuil_maximum"];
        $check_seuil_maximum = $stock + $request->quantite + $total_pret;
        if($check_seuil_maximum <= $seuil_maximum)
        {
            echo 1 . '__________' . $seuil_maximum . '__________' . ($seuil_maximum - $stock);
        }
        else
        {
            echo 0 . '__________' . $seuil_maximum . '__________' . ($seuil_maximum - $stock);
        }
    }

    public function check_seuil_minimum(Request $request)
    {
        // Récupération du stock via la table
        $stock_id = 0;
        if($request->table_id)
        {
             $table_id = $request->table_id;
            $table = Tables::where('id', $table_id)->first();
            $pointdeventes_id = $table->pointdeventes_id;
            $pointdeventes = pointdeventes::where('id', $pointdeventes_id)->first();
            $stock_id = $pointdeventes->stock_id;
        }

        $article_id = $request->article_id;

        // Récupération de l'article selon le stock
        if ($stock_id == 0)
        {
            $article = Articles::where(['id' => $article_id, 'supprimer' => 0])->first();
        } else {
            $article = articlestocks::where([
                'article_id' => $article_id,
                'stock_id' => $stock_id,
            ])->first();
        }

        // Si l'article n'existe pas, on peut renvoyer une erreur (ou un echo particulier)
        if (!$article) {
            echo "error_article_introuvable";
            return;
        }

        // Calcul des achats (prêts) – inchangé
        $achats = Achats::where(["article_id" => $article_id])->get();
        $total_pret = 0;
        foreach ($achats as $ee) {
            if ($ee->type == 2) {
                $total_pret += $ee->quantite;
            }
        }

        // Utilisation des données de l'article récupéré
        $stock = $article->stock;
        $avoir_stock = $article->avoir_stock;

        $check_seuil_minimum = ($stock + $total_pret) - ($request->quantite * $request->taille_lot);

        if ($stock == 0)
        {
            $seuil_minimum = $article->seuil_minimum;
            echo -1 . '__________' . $seuil_minimum . '__________' . ($stock - $seuil_minimum) . '__________' . $avoir_stock;
        } else
        {
            $seuil_minimum = 0;
            if ($check_seuil_minimum >= $seuil_minimum)
            {
                echo 1 . '__________' . $seuil_minimum . '__________' . ($stock - $seuil_minimum) . '__________' . $avoir_stock;
            } else {
                echo 0 . '__________' . $seuil_minimum . '__________' . ($stock - $seuil_minimum) . '__________' . $avoir_stock;
            }
        }
    }

    public function check_solde_sortie(Request $request)
    {
        $soldes = $soldes = Soldes::where(["etat" => 1])->get();
        if($soldes->count() != 0)
        {
            $total = $request->prix_unitaire * $request->quantite;
            $solde_actuel = Soldes::where(["etat" => 1])->first()["solde_actuel"];
            if($request->devise == 1)
            {
                $total = $total / $request->taux;
            }

            if($total <= $solde_actuel)
            {
                return response()->json([[1]]);
            }
            else
            {
                return response()->json([[0]]);
            }
        }
    }

    public function check_solde_encours(Request $request)
    {
        $soldes = Soldes::where(["etat" => 1])->get();
        if($soldes->count() == 0)
        {
            // return response()->json([[$soldes->count() .'__________' . "Aucun"]]);
            echo $soldes->count() .'__________' . "Aucun";
        }
        else
        {
            $moi_id = Soldes::where(["etat" => 1])->first()["moi_id"];
            $annee_id = Soldes::where(["etat" => 1])->first()["annee_id"];
            // return response()->json([[$soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"]]]);
            echo $soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"];
        }
    }

    public function check_solde_encours_1(Request $request)
    {
        $soldes = Listespaies::where(["etat" => 1])->get();
        if($soldes->count() == 0)
        {
            // return response()->json([[$soldes->count() .'__________' . "Aucun"]]);
            echo $soldes->count() .'__________' . "Aucun";
        }
        else
        {
            $moi_id = Soldes::where(["etat" => 1])->first()["moi_id"];
            $annee_id = Soldes::where(["etat" => 1])->first()["annee_id"];
            // return response()->json([[$soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"]]]);
            echo $soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"];
        }
    }

    public function check_solde_encours_2(Request $request)
    {
        $soldes = Listesfactures::where(["etat" => 1])->get();
        if($soldes->count() == 0)
        {
            // return response()->json([[$soldes->count() .'__________' . "Aucun"]]);
            echo $soldes->count() .'__________' . "Aucun";
        }
        else
        {
            $moi_id = Soldes::where(["etat" => 1])->first()["moi_id"];
            $annee_id = Soldes::where(["etat" => 1])->first()["annee_id"];
            // return response()->json([[$soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"]]]);
            echo $soldes->count() .'__________' . Mois::where(["id" => $moi_id])->first()["nom"] . ' ' . Annees::where(["id" => $annee_id])->first()["annees"];
        }
    }

    public function check_remboursement(Request $request)
    {
        $credit_id = Credits::where(["id" => $request->credit_id])->first()["id"];
        $entree_c = Credits::where(["id" => $request->credit_id])->first()["entree"];
        $remboursements = Remboursements::where(["credit_id" => $credit_id])->get();
        $entree = $request->entree_r;
        if($request->devise_r == 1)
        {
            $entree = $entree / $request->taux_r;
        }
        $t = 0;
        foreach ($remboursements as $e)
        {
            $t = $t + $e->entree;
        }
        $montant_r = $entree_c - $t;
        if($montant_r == 0)
        {
            echo 0 .'__________' . "Aucun";
        }
        else if($entree <= $montant_r)
        {
            $remboursements = new Remboursements();
            $id = Remboursements::get()->count() + 1;
            $remboursements->id = $id;
            $remboursements->credit_id = $request->credit_id;
            $remboursements->entree = round($entree, 2);
            $remboursements->devise_r = $request->devise_r;
            $remboursements->taux_r = $request->taux_r;
            $remboursements->libelle = "";
            $remboursements->nom_r = "";
            $remboursements->date_r = $request->date_r;
            $remboursements->save();
            echo 1 .'__________' . $montant_r;
        }
        else
        {
            echo 2 .'__________' . $montant_r;
        }
    }

    public function check_remboursement_1(Request $request)
    {
        $entree_c = Credits::where(["id" => $request->credit_id])->first()["entree"];
        $entree = $request->entree_r;
        if($request->devise_r == 1)
        {
            $entree = $entree / $request->taux_r;
        }
        $credits = Credits::where(["id" => $request->credit_id])->first();
        $credits->entree = round($entree + $entree_c, 2);
        $credits->save();

        $remboursements = new Creditts();
        $id = Creditts::get()->count() + 1;
        $remboursements->id = $id;
        $remboursements->credit_id = $request->credit_id;
        $remboursements->entree = round($entree, 2);
        $remboursements->devise_credit = $request->devise_r;
        $remboursements->taux_credit = $request->taux_r;
        $remboursements->libelle = $request->libelle_r;
        $remboursements->date_credit = $request->date_r;
        $remboursements->save();
    }

    public function check_type_infractions(Request $request)
    {
        $data = Type_infractions::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->nom)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_code_comptable(Request $request)
    {
        $data = Type_frais::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->code) == strtolower($request->code)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_groupe_1(Request $request)
    {
        $data = Groupes::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_contrevenant_1(Request $request)
    {
        $data = Contrevenants::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_verbalisateur_1(Request $request)
    {
        $data = Verbalisateurs::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_type_frais_1(Request $request)
    {
        $data = Type_frais::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_type_documents_1(Request $request)
    {
        $data = Type_documents::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_point_ventes_1(Request $request)
    {
        $data = Pointdeventes::where(["user_id" => Auth::user()->id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_stocks_1(Request $request)
    {
        $data = Stocks::where(["user_id" => Auth::user()->id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_tables_1(Request $request)
    {
        $data = Tables::where(["user_id" => Auth::user()->id, "pointdeventes_id" => $request->edit_point_vente_id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_societe_1(Request $request)
    {
        $data = Societes::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_nom_article_1(Request $request)
    {
        $data = Articles::where(["mesure_id" => $request->edit_mesure_id])->get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom_article) == strtolower($request->nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_type_infractions_1(Request $request)
    {
        $data = Type_infractions::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->nom) == strtolower($request->edit_nom)) && (strtolower($d->id) != strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_email_utilisateur_1(Request $request)
    {
        $data = User::get();
        $n = 0;
        foreach ($data as $d)
        {
            if((strtolower($d->phone) == strtolower($request->edit_email)) && (strtolower($d->id) == strtolower($request->id)))
            {
                $n++;
            }
        }
        return response()->json([[$n]]);
    }

    public function check_mdp(Request $request)
    {
        $email = $request->email_01;
        $password = $request->mdp_01;
        if(Auth::attempt(['email' => $email, 'password' => $password, 'etat' => 1]))
        {
            $user = User::where('id',  Auth::id())->first();
            $user->module_connected = 1;
            $user->save();
            return response()->json([[1]]);
        }
        else
        {
            return response()->json([[0]]);
        }
    }

    public function deconnexion(Request $request)
    {
        $module_connected = Auth::user()->module_connected;
        if($module_connected == 1)
        {
            Auth::guard('web')->logout();
            return response()->json([["login"]]);
        }else if($module_connected == 2)
        {
            $poste_id = Auth::user()->poste_id;
            $data = Postes::where('id',  $poste_id)->first();
            $key = base64_encode('poste_code');
            $value = base64_encode($data->code);
            $url = route('login') . '?' . http_build_query([$key => $value]);
            Auth::guard('web')->logout();
            return response()->json([["login" . '?' . http_build_query([$key => $value])]]);
        }
        else
        {
            Auth::guard('web')->logout();
            return response()->json([["login"]]);
        }
    }

    public function add_utilisateur(Request $request)
    {
        $id = User::get()->count() + 1;
        $user = new User();
        $user->id = $id;
        $user->name = $request->nom;
        $user->email = $request->email;
        $user->password = Hash::make($request->mdp);
        $user->mdp = $request->mdp;
        $user->phone = $request->phone;
        $user->salaire = $request->salaire;
        $user->devise = $request->devise;
        $user->role = $request->role;
        $user->etat = 1;
        $user->recherche = "";
        $user->image = $request->image;
        $user->poste_id = $request->poste_id;
        $user->activite_id = $request->activite_id;
        $user->save();
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
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        return view('include.refresh_utilisateur', $data);
    }

    public function add_prestation(Request $request)
    {
        // Récupération des utilisateurs du poste
        $users = User::where('poste_id', $request->poste_id)->get();

        // Paramètres
        $type_de_rotation_au_poste = (int) $request->type_de_rotation;
        $type_prestation = (int) $request->type_prestation;
        $dateDebut = DateTime::createFromFormat('d/m/Y', $request->date_debut);
        $nombre_de_jour = (int) $request->nombre_de_jour;
        $dateCreation = date('d-m-Y H:i:s');

        $utilisateurs_jour = [];
        $utilisateurs_nuit = [];
        $utilisateurs_alternes = [];

        foreach ($users as $user) {
            $data = [
                'id'   => $user->id,
                'nom'  => $user->nom,
                'type_prestation' => $type_prestation
            ];
            if ($type_prestation == 1) {
                $utilisateurs_jour[] = $data;
            } elseif ($type_prestation == 2) {
                $utilisateurs_nuit[] = $data;
            } elseif ($type_prestation == 3) {
                $utilisateurs_alternes[] = $data;
            }
        }

        $horaires = [
            'journée' => '06h00 - 18h00',
            'nuit'    => '18h00 - 06h00',
            'repos'   => 'repos'
        ];

        $details = [];

        // -------------------------------------------------------------
        // Type 1 : journée fixe
        // -------------------------------------------------------------
        if ($type_prestation == 1) {
            $pointages = [
                'entree' => [
                    'heure'      => '',
                    'capture_1'  => '',
                    'capture_2'  => '',
                    'resultat'   => ['image' => '', 'etat' => 0],
                    'latitude'   => '',
                    'longitude'  => '',
                    'etat'       => 0
                ],
                'ronde_a_1' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'          => 0
                ],
                'ronde_a_2' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'  => 0
                ],
                'ronde_a_3' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'       => 0
                ],
                'sortie' => [
                    'heure'      => '',
                    'capture_1'  => '',
                    'capture_2'  => '',
                    'resultat'   => ['image' => '', 'etat' => 0],
                    'latitude'   => '',
                    'longitude'  => '',
                    'etat'       => 0
                ]
            ];
            for ($i = 0; $i < $nombre_de_jour; $i++) {
                $dateCourante = clone $dateDebut;
                $dateCourante->modify("+$i days");
                $dateStr = $dateCourante->format('Y-m-d');
                foreach ($utilisateurs_jour as $utilisateur) {
                    $details[] = [
                        'user_id'          => $utilisateur['id'],
                        'date'             => $dateStr,
                        'service'          => 'journée',
                        'horaire'          => '08h00 - 17h00',
                        'type_prestation'  => $utilisateur['type_prestation'],
                        'date_creation'    => $dateCreation,
                        'pointages'        => $pointages
                    ];
                }
            }
        }
        // -------------------------------------------------------------
        // Type 2 : nuit fixe
        // -------------------------------------------------------------
        elseif ($type_prestation == 2) {
            $pointages = [
                'entree' => [
                    'heure'      => '',
                    'capture_1'  => '',
                    'capture_2'  => '',
                    'resultat'   => ['image' => '', 'etat' => 0],
                    'latitude'   => '',
                    'longitude'  => '',
                    'etat'       => 0
                ],
                'ronde_a_1' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'          => 0
                ],
                'ronde_a_2' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'          => 0
                ],
                'ronde_a_3' => [
                    'heure_debut'   => '',
                    'heure_fin'     => '',
                    'duree_fin'     => '',
                    'heure_recu'    => '',
                    'heure_reponse' => '',
                    'duree_reponse' => '',
                    'capture_1'     => '',
                    'capture_2'     => '',
                    'resultat'      => ['image' => '', 'etat' => 0],
                    'latitude'      => '',
                    'longitude'     => '',
                    'etat'          => 0
                ],
                'sortie' => [
                    'heure'      => '',
                    'capture_1'  => '',
                    'capture_2'  => '',
                    'resultat'   => ['image' => '', 'etat' => 0],
                    'latitude'   => '',
                    'longitude'  => '',
                    'etat'          => 0
                ]
            ];
            for ($i = 0; $i < $nombre_de_jour; $i++) {
                $dateCourante = clone $dateDebut;
                $dateCourante->modify("+$i days");
                $dateStr = $dateCourante->format('Y-m-d');
                foreach ($utilisateurs_nuit as $utilisateur) {
                    $details[] = [
                        'user_id'          => $utilisateur['id'],
                        'date'             => $dateStr,
                        'service'          => 'nuit',
                        'horaire'          => '18h00 - 06h00',
                        'type_prestation'  => $utilisateur['type_prestation'],
                        'date_creation'    => $dateCreation,
                        'pointages'        => $pointages
                    ];
                }
            }
        }
        // -------------------------------------------------------------
        // Type 3 : alternes avec rotation par blocs
        // -------------------------------------------------------------
        elseif ($type_prestation == 3 && $type_de_rotation_au_poste > 0) {
            $nb_alternes = count($utilisateurs_alternes);
            if ($nb_alternes % 3 != 0) {
                throw new \Exception("Le nombre d'alternes doit être un multiple de 3.");
            }
            $taille_groupe = $nb_alternes / 3;
            $decalages = [0, $type_de_rotation_au_poste, $type_de_rotation_au_poste * 2];

            $groupes = [];
            for ($g = 0; $g < 3; $g++) {
                $groupes[$g] = array_slice($utilisateurs_alternes, $g * $taille_groupe, $taille_groupe);
            }

            $patternBase = array_merge(
                array_fill(0, $type_de_rotation_au_poste, 'journée'),
                array_fill(0, $type_de_rotation_au_poste, 'nuit'),
                array_fill(0, $type_de_rotation_au_poste, 'repos')
            );
            $longueurCycle = count($patternBase);

            for ($i = 0; $i < $nombre_de_jour; $i++) {
                $dateCourante = clone $dateDebut;
                $dateCourante->modify("+$i days");
                $dateStr = $dateCourante->format('Y-m-d');

                for ($g = 0; $g < 3; $g++) {
                    $position = ($i + $decalages[$g]) % $longueurCycle;
                    $service = $patternBase[$position];

                    if ($service == 'journée' || $service == 'nuit') {
                        $pointages = [
                            'entree' => [
                                'heure'      => '',
                                'capture_1'  => '',
                                'capture_2'  => '',
                                'resultat'   => ['image' => '', 'etat' => 0],
                                'latitude'   => '',
                                'longitude'  => '',
                                     'etat'  => 0
                            ],
                            'ronde_a_1' => [
                                'heure_debut'   => '',
                                'heure_fin'     => '',
                                'duree_fin'     => '',
                                'heure_recu'    => '',
                                'heure_reponse' => '',
                                'duree_reponse' => '',
                                'capture_1'     => '',
                                'capture_2'     => '',
                                'resultat'      => ['image' => '', 'etat' => 0],
                                'latitude'      => '',
                                'longitude'     => '',
                                     'etat'     => 0
                            ],
                            'ronde_a_2' => [
                                'heure_debut'   => '',
                                'heure_fin'     => '',
                                'duree_fin'     => '',
                                'heure_recu'    => '',
                                'heure_reponse' => '',
                                'duree_reponse' => '',
                                'capture_1'     => '',
                                'capture_2'     => '',
                                'resultat'      => ['image' => '', 'etat' => 0],
                                'latitude'      => '',
                                'longitude'     => '',
                                     'etat'     => 0
                            ],
                            'ronde_a_3' => [
                                'heure_debut'   => '',
                                'heure_fin'     => '',
                                'duree_fin'     => '',
                                'heure_recu'    => '',
                                'heure_reponse' => '',
                                'duree_reponse' => '',
                                'capture_1'     => '',
                                'capture_2'     => '',
                                'resultat'      => ['image' => '', 'etat' => 0],
                                'latitude'      => '',
                                'longitude'     => '',
                                     'etat'     => 0
                            ],
                            'sortie' => [
                                'heure'      => '',
                                'capture_1'  => '',
                                'capture_2'  => '',
                                'resultat'   => ['image' => '', 'etat' => 0],
                                'latitude'   => '',
                                'longitude'  => '',
                                     'etat'  => 0
                            ]
                        ];
                    } else {
                        $pointages = [];
                    }

                    foreach ($groupes[$g] as $utilisateur) {
                        $details[] = [
                            'user_id'          => $utilisateur['id'],
                            'date'             => $dateStr,
                            'service'          => $service,
                            'horaire'          => $horaires[$service],
                            'type_prestation'  => $utilisateur['type_prestation'],
                            'date_creation'    => $dateCreation,
                            'pointages'        => $pointages
                        ];
                    }
                }
            }
        }

        // -------------------------------------------------------------
        // Enregistrement en base
        // -------------------------------------------------------------
        $id = Prestations::count() + 1;
        $prestation = new Prestations();
        $prestation->id = $id;
        $prestation->user_id = Auth::user()->id;
        $prestation->annee_id = $request->annee_id;
        $prestation->moi_id = $request->moi_id;
        $prestation->type_prestation = $request->type_prestation;
        $prestation->type_de_rotation = $request->type_de_rotation;
        $prestation->nombre_de_jour = $request->nombre_de_jour;
        $prestation->date_debut = $request->date_debut;
        $prestation->etat = 1;
        $prestation->supprimer = 0;
        $prestation->poste_id = $request->poste_id;
        $prestation->details = json_encode($details, JSON_UNESCAPED_UNICODE);

        $prestation->save();

        return response()->json(['success' => true, 'message' => 'Programme enregistré']);
    }

    public function add_poste(Request $request)
    {
        $id = Postes::get()->count() + 1;
        $code = "";

        do {
            $random = strtoupper(Str::random(8));
            $code = "P" . $id . $random;
        } while (Postes::where('code', $code)->exists());

        $poste = new Postes();
        $poste->id = $id;
        $poste->client_id = $request->client_id;
        $poste->lieuxe_id = $request->lieu_id;
        $poste->nom = $request->nom;
        if(strlen(trim($request->description)) != 0){
             $poste->description = $request->description;
        }else{
            $poste->description = "";
        }
        if(strlen(trim($request->latitude)) != 0){
             $poste->latitude = $request->latitude;
        }else{
            $poste->latitude = 0;
        }
        if(strlen(trim($request->longitude)) != 0){
             $poste->longitude = $request->longitude;
        }else{
            $poste->longitude = 0;
        }
        $poste->code = $code;
        $poste->etat = 0;
        $poste->supprimer = 0;
        $poste->user_id = Auth::user()->id;
        $poste->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        return view('include.refresh_poste', $data);
    }


    public function add_ecole(Request $request)
    {
        $id = ecoles::get()->count() + 1;

        $ecole = new ecoles();
        $ecole->id = $id;
        $ecole->district_id = $request->district_id;
        $ecole->commune_id = $request->commune_id;
        $ecole->nom = $request->nom_ecole;
        $ecole->nom_directeur = $request->nom_directeur;
        $ecole->nombre_eleve = $request->nombre_eleve;
        $ecole->nombre_enseignant = $request->nombre_enseignant;
        $ecole->nombre_classe = $request->nombre_classe;
        $ecole->annee_creation = $request->date_creation;
        $ecole->annee_id = $request->annee_id;
        $ecole->moi_id = $request->mois_id;
        $ecole->telephone = $request->telephone;
        if(strlen(trim($request->adresse)) != 0){
             $ecole->adresse = $request->adresse;
        }else{
            $ecole->adresse = "";
        }
        if(strlen(trim($request->latitude)) != 0){
             $ecole->latitude = $request->latitude;
        }else{
            $ecole->latitude = 0;
        }
        if(strlen(trim($request->longitude)) != 0){
             $ecole->longitude = $request->longitude;
        }else{
            $ecole->longitude = 0;
        }
        $ecole->etat = 1;
        $ecole->supprimer = 0;
        $ecole->user_id = Auth::user()->id;
        $ecole->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["ecoles"] = ecoles::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 20;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_ecole', $data);
    }


    public function add_eleve(Request $request)
    {
        $id = beneficiaires::get()->count() + 1;

        $beneficaires = new beneficiaires();
        $beneficaires->id = $id;
        $beneficaires->ecole_id = $request->ecole_id;
        $beneficaires->nom_eleve = $request->nom_eleve;
        $beneficaires->genre = $request->genre;
        $beneficaires->classe_id = $request->classe_id;
        $beneficaires->nom_parent = $request->nom_parent;
        $beneficaires->telephone = $request->telephone;
        $beneficaires->etat = 1;
        $beneficaires->supprimer = 0;
        $beneficaires->user_id = Auth::user()->id;
        $beneficaires->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 20;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        if(Auth::user()->role == 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        }
        $data["beneficiaires"] = $beneficiaires;
        return view('include.refresh_eleve', $data);
    }


    public function edit_poste(Request $request)
    {
        $poste =  Postes::where(["id" => $request->id])->first();
        $poste->client_id = $request->edit_client_id;
        $poste->lieuxe_id = $request->edit_lieu_id;
        $poste->nom = $request->edit_nom;
        if(strlen(trim($request->edit_description)) != 0){
             $poste->description = $request->edit_description;
        }else{
            $poste->description = "";
        }
        if(strlen(trim($request->edit_latitude)) != 0){
             $poste->latitude = $request->edit_latitude;
        }else{
            $poste->latitude = 0;
        }
        if(strlen(trim($request->edit_longitude)) != 0){
             $poste->longitude = $request->edit_longitude;
        }else{
            $poste->longitude = 0;
        }
        $poste->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        return view('include.refresh_poste', $data);
    }

    public function edit_ecole(Request $request)
    {
        $ecole =  ecoles::where(["id" => $request->id])->first();

        $ecole->district_id = $request->edit_district_id;
        $ecole->commune_id = $request->edit_commune_id;
        $ecole->nom = $request->edit_nom_ecole;
        $ecole->nom_directeur = $request->edit_nom_directeur;
        $ecole->nombre_eleve = $request->edit_nombre_eleve;
        $ecole->nombre_enseignant = $request->edit_nombre_enseignant;
        $ecole->nombre_classe = $request->edit_nombre_classe;
        $ecole->annee_creation = $request->edit_date_creation;
        $ecole->annee_id = $request->edit_annee_id;
        $ecole->moi_id = $request->edit_mois_id;
        $ecole->telephone = $request->edit_telephone;
        if(strlen(trim($request->edit_adresse)) != 0){
             $ecole->adresse = $request->edit_adresse;
        }else{
            $ecole->adresse = "";
        }
        if(strlen(trim($request->edit_latitude)) != 0){
             $ecole->latitude = $request->edit_latitude;
        }else{
            $ecole->latitude = 0;
        }
        if(strlen(trim($request->edit_longitude)) != 0){
             $ecole->longitude = $request->edit_longitude;
        }else{
            $ecole->longitude = 0;
        }
        $ecole->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 20;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        return view('include.refresh_ecole', $data);
    }

    public function edit_eleve(Request $request)
    {
        $beneficaires =  beneficiaires::where(["id" => $request->id])->first();
        $beneficaires->ecole_id = $request->edit_ecole_id;
        $beneficaires->nom_eleve = $request->edit_nom_eleve;
        $beneficaires->genre = $request->edit_genre;
        $beneficaires->classe_id = $request->edit_classe_id;
        $beneficaires->nom_parent = $request->edit_nom_parent;
        $beneficaires->telephone = $request->edit_telephone;
        $beneficaires->etat = 1;
        $beneficaires->supprimer = 0;
        $beneficaires->user_id = Auth::user()->id;
        $beneficaires->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 20;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        if(Auth::user()->role == 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        }
        $data["beneficiaires"] = $beneficiaires;
        return view('include.refresh_eleve', $data);
    }

    public function add_client(Request $request)
    {
        $id = Clients::get()->count() + 1;
        $clients = new Clients();
        $clients->id = $id;
        $clients->name = $request->nom;
        if(strlen(trim($request->email)) == 0)
        {
            $clients->email = "";
        }
        else
        {
            $clients->email = $request->email;
        }

        if(strlen(trim($request->adresse)) == 0)
        {
            $clients->adresse = "";
        }
        else
        {
            $clients->adresse = $request->adresse;
        }
        if(strlen(trim($request->description)) == 0)
        {
            $clients->description = "";
        }
        else
        {
            $clients->description = $request->description;
        }
        if(strlen(trim($request->latitude)) != 0){
             $clients->latitude = $request->latitude;
        }else{
            $clients->latitude = 0;
        }
        if(strlen(trim($request->longitude)) != 0){
             $clients->longitude = $request->longitude;
        }else{
            $clients->longitude = 0;
        }
        $clients->activite_id = $request->activite_id;
        $clients->type = $request->type_client;
        $clients->paiement = $request->paiement;
        $clients->devise = $request->devise;
        $clients->factures = $request->facture;
        $clients->password = Hash::make("12345");
        $clients->mdp = "12345";
        $clients->phone = $request->phone;
        $clients->user_id =  Auth::user()->id;
        $clients->etat = 1;
        $clients->recherche = "";
        $clients->image = 'storage/images/user/profil_defaut.png';
        $clients->save();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 14;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        if(Auth::user()->role == 0)
        {
            $data["clients"] = Clients::where(["etat" => 1])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $data["clients"] = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        }
        $data["activites"] = Activites::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_client', $data);
    }

    public function add_groupe(Request $request)
    {
        $id = Groupes::get()->count() + 1;
        $groupe = new Groupes();
        $groupe->id = $id;
        $groupe->nom = $request->nom;
        $groupe->save();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupes = Groupes::get();
        foreach ($groupes as $g)
        {
           $ressources = Ressources::get();
           foreach ($ressources as $r)
           {
                if(Writes::where(["groupe_id" => $g->id, "ressource_id" => $r->id])->get()->count() == 0)
                {
                    $id = Writes::get()->count() + 1;
                    $write = new Writes();
                    $write->id = $id;
                    $write->groupe_id = $g->id;
                    $write->ressource_id = $r->id;
                    $write->display = false;
                    $write->add = false;
                    $write->edit = false;
                    $write->delete = false;
                    $write->recherche =$g->nom . ' ' . $r->nom;
                    $write->save();
                }
           }
        }
        return view('include.refresh_groupe', $data);
    }

    public function add_contrevenant(Request $request)
    {
        $id = Contrevenants::get()->count() + 1;
        $contrevenant = new Contrevenants();
        $contrevenant->id = $id;
        $contrevenant->nom = $request->nom;
        $contrevenant->recherche = $request->nom;
        $contrevenant->save();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        return view('include.refresh_contrevenant', $data);
    }

    public function add_frais_contentieux(Request $request)
    {
        $frais_link = "";
        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            $frais_link = $frais_link . '____________________' . $f->lien;
        }
        $id = Frais::get()->count() + 1;
        $frais = new Frais();
        $frais->id = $id;
        $frais->contentieur_id = $request->c_id;
        $frais->type_frai_id = $request->frais;
        $frais->montant = $request->montant;
        $frais->devise = $request->_devise;
        $frais->taux = $request->_taux;
        $frais->libelle = $request->lib;
        $frais->date_paye = date("d/m/Y");
        $frais->frais_link = $frais_link;
        $frais->user_id = Auth::user()->id;
        $frais->recherche = "";
        $frais->save();
        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            Fichiers::where('id', $f->id)->delete();
        }
        $data["frais"] = Frais::where(["contentieur_id" => $request->c_id, "user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["frais"] = Frais::where(["contentieur_id" => $request->c_id])->get();
        }
        return view('include.refresh_frais_contentieux', $data);
    }

    public function add_frais_contentieux_(Request $request)
    {
        $id = Travailleurs::get()->count() + 1;
        $frais = new Travailleurs();
        $frais->id = $id;
        $frais->contentieur_id = $request->c_id_;
        $frais->user_id = $request->user;
        $frais->montant = $request->montant_;
        $frais->devise = $request->_devise_;
        $frais->taux = $request->_taux_;
        $frais->libelle = $request->lib_;
        $frais->date_paye = date("d/m/Y");
        $frais->recherche = "";
        $frais->save();
        $data["travailleurs"] = Travailleurs::where(["contentieur_id" => $request->c_id_])->get();
        $data["frais"] = Frais::where(["contentieur_id" => $request->c_id])->get();
        return view('include.refresh_frais_contentieux_', $data);
    }

    public function add_user_in_listespaies(Request $request)
    {
        $id = Paiements::get()->count() + 1;
        $paiments = new Paiements();
        $paiments->id = $id;
        $paiments->moi_id = $request->moi_id;
        $paiments->annee_id = $request->annee_id;
        $paiments->listespaie_id = $request->listespaie_id;
        $paiments->user_id = $request->user;
        $paiments->montant = User::where('id', $request->user)->first()["salaire"];
        $paiments->devise = User::where('id', $request->user)->first()["devise"];
        $paiments->taux = 2800;
        $paiments->date_paye = date("d/m/Y");
        $paiments->save();
        $data["paiements"] = Paiements::where(["listespaie_id" => $request->listespaie_id])->get();
        return view('include.refresh_paiements', $data);
    }

    public function add_client_in_listesfactures(Request $request)
    {
        if($request->client_id_f == 0)
        {
            if(Auth::user()->role == 0)
            {
                $clients = Clients::where(["etat" => 1, "activite_id" => $request->activite_id_f])->get();
            }
            elseif(Auth::user()->role != 0)
            {
                $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id, "activite_id" => $request->activite_id_f])->get();
            }
            foreach ($clients as $data)
            {
                if (Paiementsfactures::where(["client_id" => $data->id, "listesfactures_id" => $request->listesfactures_id])->get()->count() == 0)
                {
                    $id = Paiementsfactures::get()->count() + 1;
                    $paiementsfactures = new Paiementsfactures();
                    $paiementsfactures->id = $id;
                    $paiementsfactures->moi_id = $request->moi_id;
                    $paiementsfactures->annee_id = $request->annee_id;
                    $paiementsfactures->listesfactures_id = $request->listesfactures_id;
                    $paiementsfactures->client_id = $data->id;
                    $paiementsfactures->montant = Clients::where('id', $data->id)->first()["paiement"];
                    $paiementsfactures->devise = Clients::where('id', $data->id)->first()["devise"];
                    $paiementsfactures->taux = 2200;
                    $paiementsfactures->date_paye = date("d/m/Y");
                    $paiementsfactures->save();
                }
            }
            $data["paiementsfactures"] = Paiementsfactures::where(["listesfactures_id" => $request->listesfactures_id])->get();
            $data["listesfactures_id"] = $request->listesfactures_id;
            $data["activite_id"] = $request->activite_id_f;
            $data["nom_activite"] = strtoupper(Activites::where('id', $request->activite_id_f)->first()["nom"]);
            $data["nb_client"] = Clients::where('id', $request->activite_id)->get()->count();
            return view('include.refresh_paiementsfactures', $data);
        }
        else
        {
            $id = Paiementsfactures::get()->count() + 1;
            $paiementsfactures = new Paiementsfactures();
            $paiementsfactures->id = $id;
            $paiementsfactures->moi_id = $request->moi_id;
            $paiementsfactures->annee_id = $request->annee_id;
            $paiementsfactures->listesfactures_id = $request->listesfactures_id;
            $paiementsfactures->client_id = $request->client_id_f;
            $paiementsfactures->montant = Clients::where('id', $request->client_id_f)->first()["paiement"];
            $paiementsfactures->devise = Clients::where('id', $request->client_id_f)->first()["devise"];
            $paiementsfactures->taux = 2200;
            $paiementsfactures->date_paye = date("d/m/Y");
            if (Paiementsfactures::where(["client_id" => $request->client_id_f, "listesfactures_id" => $request->listesfactures_id])->get()->count() == 0)
            {
                $paiementsfactures->save();
            }
            $paiementsfactures->save();
            $data["paiementsfactures"] = Paiementsfactures::where(["listesfactures_id" => $request->listesfactures_id])->get();
            $data["listesfactures_id"] = $request->listesfactures_id;
            $data["activite_id"] = $request->activite_id_f;
            $data["nom_activite"] = strtoupper(Activites::where('id', $request->activite_id_f)->first()["nom"]);
            $data["nb_client"] = Clients::where('id', $request->activite_id)->get()->count();
            return view('include.refresh_paiementsfactures', $data);
        }
    }

    public function get_refresh_paiementsfactures(Request $request)
    {
        $nom_activite = "";
        $activite_id = 0;
        if(strlen(trim($request->activite_id)) != 0)
        {
            $nom_activite = Activites::where('id', $request->activite_id)->first()["nom"];
        }
        if(strlen(trim($request->activite_id)) != 0)
        {
            $activite_id = $request->activite_id;
        }
        $data["nom_activite"] = $nom_activite;
        $data["paiementsfactures"] = Paiementsfactures::where(["listesfactures_id" => $request->listesfactures_id])->get();
        $data["listesfactures_id"] = $request->listesfactures_id;
        $data["activite_id"] = $activite_id;
        $data["nom_activite"] = strtoupper($nom_activite);
        $data["nb_client"] = Clients::where('id', $activite_id)->get()->count();
        return view('include.refresh_paiementsfactures', $data);
    }

    public function get_refresh_programme(Request $request)
    {
        $data["poste_id"] = $request->poste_id;
        $data["nom_poste"] = $request->nom_poste;
        $data["nom_lieu_poste"] = $request->nom_lieu_poste;
        $data["nb_programme"] = Prestations::where(['poste_id' => $request->poste_id, 'supprimer' => 0])->get()->count();
        $data["prestations"] = Prestations::where(['poste_id' => $request->poste_id, 'supprimer' => 0])->get();
        return view('include.get_refresh_programme', $data);
    }

    public function check_user(Request $request)
    {
        $dutilisateurs = Travailleurs::where(["contentieur_id" => $request->c_id_, "user_id" => $request->user])->get();

        return response()->json([[$dutilisateurs->count()]]);
    }
    public function check_user_in_listespaies(Request $request)
    {
        $dutilisateurs = Paiements::where(["moi_id" => $request->moi_id, "annee_id" => $request->annee_id , "listespaie_id" => $request->listespaie_id, "user_id" => $request->user])->get();
        return response()->json([[$dutilisateurs->count()]]);
    }

    public function check_client_in_listesfactures(Request $request)
    {
        $dutilisateurs = Paiementsfactures::where(["moi_id" => $request->moi_id, "annee_id" => $request->annee_id , "listesfactures_id" => $request->listespaie_id, "client_id" => $request->client_id_f])->get();
        return response()->json([[$dutilisateurs->count()]]);
    }

    public function add_verbalisateur(Request $request)
    {
        $id = Verbalisateurs::get()->count() + 1;
        $verbalisateur = new Verbalisateurs();
        $verbalisateur->id = $id;
        $verbalisateur->nom = $request->nom;
        $verbalisateur->recherche = $request->nom;
        $verbalisateur->save();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        return view('include.refresh_verbalisateur', $data);
    }

    public function add_type_frais(Request $request)
    {
        $id = Type_frais::get()->count() + 1;
        $type_frais = new Type_frais();
        $type_frais->id = $id;
        $type_frais->nom = $request->nom;
        $type_frais->code = $request->code;
        $type_frais->description = $request->description;
        $type_frais->recherche = $request->nom;
        $type_frais->save();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        return view('include.refresh_type_frais', $data);
    }


    public function add_type_documents(Request $request)
    {
        $id = Type_documents::get()->count() + 1;
        $type_documents = new Type_documents();
        $type_documents->id = $id;
        $type_documents->nom = $request->nom;
        if(strlen(trim($request->description)) == 0)
        {
            $type_documents->description = "";
        }
        else
        {
            $type_documents->description = $request->description;
        }
        $type_documents->date_creation = date("d/m/Y");
        $type_documents->etat = 1;
        $type_documents->save();
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        return view('include.refresh_type_documents', $data);
    }

    public function add_point_vente(Request $request)
    {
        $id = Pointdeventes::get()->count() + 1;
        $point_vente = new Pointdeventes();
        $point_vente->id = $id;
        $point_vente->nom = $request->nom;
        $point_vente->user_id = Auth::user()->id;
        if(strlen(trim($request->description)) == 0)
        {
            $point_vente->description = "";
        }
        else
        {
            $point_vente->description = $request->description;
        }
        $point_vente->date_creation = date("d/m/Y");
        $point_vente->etat = 1;
        $point_vente->save();
        $data["point_ventes"] = Pointdeventes::where(["etat" => 1])->get();
        return view('include.refresh_point_ventes', $data);
    }


    public function add_stock(Request $request)
    {
        $id = Stocks::get()->count() + 1;
        $stock = new Stocks();
        $stock->id = $id;
        $stock->nom = $request->nom;
        $stock->user_id = Auth::user()->id;
        if(strlen(trim($request->description)) == 0)
        {
            $stock->description = "";
        }
        else
        {
            $stock->description = $request->description;
        }
        $stock->date_creation = date("d/m/Y");
        $stock->etat = 1;
        $stock->save();
        $data["stocks"] = Stocks::where(["etat" => 1])->get();
        return view('include.refresh_stocks', $data);
    }


    public function add_table(Request $request)
    {
        $id = Tables::get()->count() + 1;
        $table = new Tables();
        $table->id = $id;
        $table->pointdeventes_id = $request->point_vente_id;
        $table->nom = $request->nom;
        $table->user_id = Auth::user()->id;
        if(strlen(trim($request->description)) == 0)
        {
            $table->description = "";
        }
        else
        {
            $table->description = $request->description;
        }
        $table->date_creation = date("d/m/Y");
        $table->etat = 1;
        $table->occupee = 0;
        $table->save();
        $data["tables"] = Tables::where(["etat" => 1])->get();
        return view('include.refresh_tables', $data);
    }

    public function add_societe(Request $request)
    {
        $id = Societes::get()->count() + 1;
        $societes = new Societes();
        $societes->id = $id;
        $societes->nom = $request->nom;
        $societes->code = $request->code;
        $societes->description = $request->description;
        $societes->recherche = $request->nom;
        $societes->save();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        return view('include.refresh_societes', $data);
    }

    public function get_all_categorie(Request $request)
    {
        $data["societes"] = Societes::where(["etat" => 1])->get();
        return view('include.refresh_societes', $data);
    }

    public function get_all_stock(Request $request)
    {
        $data["stocks"] = Stocks::where(["etat" => 1])->get();
        return view('include.refresh_stocks', $data);
    }

    public function get_all_table(Request $request)
    {
        $data["tables"] = Tables::where(["etat" => 1])->get();
        return view('include.refresh_tables', $data);
    }

    public function get_all_table_d(Request $request)
    {
        $data["tables"] = Tables::where(["etat" => 1])->get();
        return view('include.refresh_tables_d', $data);
    }

    public function get_all_depense(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 18;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["depenses"] = Depenses::where(["supprimer" => 0, "user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["depense"] = Depenses::get();
        }
        return view('include.refresh_depenses', $data);
    }

    public function get_all_articles(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 8;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["articles"] = Articles::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
        if(Auth::user()->role == 0)
        {
            $data["articles"] = Articles::where(["supprimer" => 0])->get();
        }
        return view('include.refresh_articles', $data);
    }

    public function add_activiter(Request $request)
    {
        $id = Activites::get()->count() + 1;
        $activites = new Activites();
        $activites->id = $id;
        $activites->nom = $request->nom;
        $activites->logo = $request->image;
        $activites->etat = 1;
        $activites->date_creation = date("Y-m-d H:i:s");
        if(strlen(trim($request->description)) == 0)
        {
            $activites->description = "";
        }
        else
        {
            $activites->description = $request->description;
        }
        if(strlen(trim($request->taux_facture)) == 0)
        {
            $activites->taux = 0;
        }
        else
        {
            $activites->taux = $request->taux_facture;
        }
        if(strlen(trim($request->tva)) == 0)
        {
            $activites->tva = 0;
        }
        else
        {
            $activites->tva = $request->tva;
        }
        $activites->save();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        return view('include.refresh_activites', $data);
    }

    public function edit_activiter(Request $request)
    {
        $activites = Activites::where(["id" => $request->id])->first();
        $activites->nom = $request->edit_nom;
        $activites->logo = $request->edit_image;
        if(strlen(trim($request->edit_description)) == 0)
        {
            $activites->description = "";
        }
        else
        {
            $activites->description = $request->edit_description;
        }
        if(strlen(trim($request->edit_taux_facture)) == 0)
        {
            $activites->taux = 0;
        }
        else
        {
            $activites->taux = $request->edit_taux_facture;
        }
        if(strlen(trim($request->edit_tva)) == 0)
        {
            $activites->tva = 0;
        }
        else
        {
            $activites->tva = $request->edit_tva;
        }
        $activites->save();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        return view('include.refresh_activites', $data);
    }

    public function add_solde(Request $request)
    {
        $solde = $request->solde_initial;
        $avance = $request->avance;
        if($request->devise == 1 && $solde != 0)
        {
            $solde = $solde / $request->taux;
        }
        if($request->devise == 1 && $avance != 0)
        {
            $avance = $avance / $request->taux;
        }
        $solde_plus_avance =  $solde + $avance;
        $id = Soldes::get()->count() + 1;
        $soldes = new Soldes();
        $soldes->id = $id;
        $soldes->solde_initial = $solde_plus_avance;
        $soldes->solde_actuel = $solde_plus_avance;
        $soldes->annee_id = $request->annee_id;
        $soldes->moi_id = $request->moi_id;
        $soldes->devise = $request->devise;
        $soldes->taux = $request->taux;
        $soldes->avance = $request->avance;
        if(Soldes::where(["annee_id" => $request->annee_id, "moi_id" => $request->moi_id])->get()->count() == 0)
        {
            $soldes->save();
        }
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        return view('include.refresh_soldes', $data);
    }

    public function add_listespaies(Request $request)
    {
        $id = Listespaies::get()->count() + 1;
        $soldes = new Listespaies();
        $soldes->id = $id;
        $soldes->annee_id = $request->annee_id;
        $soldes->moi_id = $request->moi_id;
        if(Listespaies::where(["annee_id" => $request->annee_id, "moi_id" => $request->moi_id])->get()->count() == 0)
        {
            $soldes->save();
        }
        $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
        return view('include.refresh_listespaies', $data);
    }

    public function add_listesfactures(Request $request)
    {
        $id = Listesfactures::get()->count() + 1;
        $soldes = new Listesfactures();
        $soldes->id = $id;
        $soldes->annee_id = $request->annee_id;
        $soldes->moi_id = $request->moi_id;
        $soldes->etat = 0;
        if(Listesfactures::where(["annee_id" => $request->annee_id, "moi_id" => $request->moi_id])->get()->count() == 0)
        {
            $soldes->save();
        }
        $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
        return view('include.refresh_listesfactures', $data);
    }


    public function add_rendez_vous(Request $request)
    {
        $id = Rendezvous::get()->count() + 1;
        $rendezvous = new Rendezvous();
        $rendezvous->id = $id;
        $rendezvous->nom = $request->nom;
        $rendezvous->motif = $request->motif;
        $rendezvous->heure = $request->heure;
        $rendezvous->date_creation = $request->date;
        $rendezvous->date_cloturer = "";
        $rendezvous->save();
        $data["rendez_vous"] = Listespaies::where(["supprimer" => 0])->get();
        return view('include.refresh_rendez_vous', $data);
    }


    public function add_type_infractions(Request $request)
    {
        $id = Type_infractions::get()->count() + 1;
        $type_infractions = new Type_infractions();
        $type_infractions->id = $id;
        $type_infractions->nom = $request->nom;
        $type_infractions->libelle = $request->nom;
        $type_infractions->code = $request->code;
        $type_infractions->description = $request->description;
        $type_infractions->recherche = $request->nom;
        $type_infractions->save();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        return view('include.refresh_type_infractions', $data);
    }

    public function add_invitations(Request $request)
    {
        $invitation_link = "";
        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            $invitation_link = $invitation_link . '____________________' . $f->lien;
        }
        $id = Invitations::get()->count() + 1;
        $invitations = new Invitations();
        $invitations->id = $id;
        $invitations->user_id = Auth::user()->role;
        $invitations->date_invitation = $request->date_invitation;
        $invitations->heure_invitation = $request->heure_invitation;
        $invitations->date_document = $request->date_document;
        $invitations->verbalisateur_id = $request->verbalisateur;
        $invitations->libelle = $request->libelle;
        $invitations->signer_par = $request->signer;
        if(trim(strlen($request->description)) == 0)
        {
            $invitations->description  = "";
        }
        else
        {
            $invitations->description  = $request->description;
        }
        $invitations->statut = $request->statut;
        $invitations->numero_invitation = $request->numero_invitation;
        $invitations->invitation_link = $invitation_link;
        $invitations->recherche = "";
        $invitations->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            Fichiers::where('id', $f->id)->delete();
        }
        return view('include.refresh_invitations', $data);
    }

    public function add_decisions(Request $request)
    {
        $decisions_link = "";
        $pv_link = "";
        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            $decisions_link = $decisions_link . '____________________' . $f->lien;
        }
        $attaches = Attaches::get();
        foreach ($attaches as $f)
        {
            $pv_link = $pv_link . '____________________' . $f->lien;
        }
        $id = Decisions::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }
        $decisions = new Decisions();
        $decisions->id = $id;
        $decisions->user_id = Auth::user()->role;
        $decisions->contrevenant_id = 1;
        $decisions->numero_decision = "";
        $decisions->date_document = "";
        $decisions->date_reception = "";
        $decisions->numero_pv = "";
        $decisions->date_pv = "";
        $decisions->description_infraction  = "";
        $decisions->decisions_link = $decisions_link;
        $decisions->pv_link = $pv_link;
        $decisions->recherche = "";

        $decisions->num_projet = $nb_annonce;
        $decisions->nom_projet = $request->nom_projet;
        $decisions->date_creation = $request->date_creation;
        $decisions->budget = $request->budget;
        $decisions->nombre_personne = $request->nombre_personne;
        $decisions->debut = $request->debut;
        $decisions->fin = $request->fin;
        $decisions->description = $request->description;

        $decisions->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            Fichiers::where('id', $f->id)->delete();
        }
        $attaches = Attaches::get();
        foreach ($attaches as $f)
        {
            Attaches::where('id', $f->id)->delete();
        }
        return view('include.refresh_decisions', $data);
    }

    public function add_documents(Request $request)
    {
        $fichiers = Fichiers::where('user_id', Auth::user()->id)->get();

        DB::transaction(function () use ($request, $fichiers) {
            $id = Documents::get()->count() + 1;

            $documents = new Documents();
            $documents->id = $id;
            $documents->type_documents_id = $request->type_document_id;
            $documents->description = $request->description;
            $documents->user_id = Auth::user()->id;
            $documents->date_creation = "";
            $documents->etat = 1;
            $documents->save();
            foreach ($fichiers as $frs) {
                $id_f = Fichier_documents::get()->count() + 1;

                $fichier_documents = new Fichier_documents();
                $fichier_documents->id = $id_f;
                $fichier_documents->documents_id = $id;
                $fichier_documents->lien = $frs->lien;
                $fichier_documents->nom = $frs->nom;
                $fichier_documents->date_creation = date("d/m/Y");
                $fichier_documents->etat = 1;
                $fichier_documents->save();
            }
        });
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 13;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        // Suppressions
        Fichiers::where('user_id', Auth::user()->id)->delete();


        $Notifications = Notifications::get();
        foreach ($Notifications as $notifications)
        {
            if(strlen(trim($notifications->email)) != 0)
            {
                if($notifications->email != Auth::user()->email)
                {
                    $mail = new PHPMailer(true); // true active les exceptions

                    $nom_reception = "";

                    $users = User::where('email', $notifications->email)->get();
                    if($users->count() != 0)
                    {
                        $nom_reception = User::where('email',$notifications->email)->first()->name;
                    }
                    else
                    {
                        $nom_reception = "Jonathan";
                    }

                    $nom_send = User::where('email', Auth::user()->email)->first()->name;
                    try {
                        // --- Configuration SMTP ---
                        $mail->isSMTP();
                        $mail->Host       = 'mail83.lwspanel.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'test@africtechapp.com';
                        $mail->Password   = '1@Test12345';
                        $mail->SMTPSecure = 'ssl';   // ou 'tls' selon votre serveur
                        $mail->Port       = 465;     // 587 pour tls, 465 pour ssl

                        // --- Expéditeur et destinataire ---
                        $mail->setFrom('test@africtechapp.com', 'AFRICTECHAPP');
                        $mail->addAddress(trim($notifications->email), trim($notifications->email));
                        // Vous pouvez ajouter d'autres destinataires avec addCC() ou addBCC()

                        // --- Contenu ---
                        $mail->CharSet = 'UTF-8';
                        $mail->Encoding = 'base64';
                        $mail->setLanguage('fr', 'PHPMailer/language/');
                        $mail->isHTML(true);
                        $mail->Subject = 'PARTAGE DE FICHIER';
                        $mail->Body = "<h2>Bonjour</h2><p> Mr/Mme $nom_reception, Mr/Mme $nom_send vient de publier le fichier.</p><p style='color:#FF0000;'>NB : Veuillez vous connecter à l'application pour voir le fichier.</p>";
                        // --- PIÈCE JOINTE ---
                        // Chemin absolu vers le fichier (par exemple dans le dossier public de votre projet)
                        // $filePath = public_path("$nom_fichier"); // Ajustez le chemin
                        // OU si vous utilisez Laravel : $filePath = public_path('LISTE_DE_FACTURE_JANVIER_2025.pdf');

                        // if (file_exists($filePath))
                        // {
                        //     $mail->addAttachment($filePath);
                        // }
                        // --- Envoi ---
                        if($mail->send())
                        {

                        }else
                        {

                        }
                    } catch (Exception $e)
                    {

                    }
                }
            }
        }
        return view('include.refresh_fichier_documents', $data);
    }

    public function add_entre(Request $request)
    {
        $soldes = Soldes::where('etat', 1)->first();
        $solde_actuel = $soldes->solde_actuel;
        $annee_id = $soldes->annee_id;
        $moi_id = $soldes->moi_id;
        $solde_actuel = $soldes->solde_actuel;
        $type_operation = "";
        $entree_sortie = ($request->entree + $request->sortie);
        if($request->entree != 0)
        {
            $type_operation = 0;
        }
        if($request->sortie != 0)
        {
            $type_operation = 1;
        }
        if(Session::get("facture_user_id"))
        {
            // Entres
            $entres = new Entres();
            $id2 = Entres::get()->count() + 1;
            while(Entres::where(["id" => $id2])->count() != 0)
            {
                $id2 = $id2 + 1;
            }
            $entres->id = $id2;
            $entres->user_id = Auth::user()->id;
            $entres->facture_id = Session::get("facture_user_id");
            $entres->type_frai_id = $request->type_sortie;
            $entres->annee_id = $annee_id;
            $entres->moi_id = $moi_id;
            $entres->prix_unitaire = $entree_sortie;
            $entres->quantite = $request->quantite;
            $entres->entree = $request->entree;
            $entres->sortie = $request->sortie;
            $entres->type = $type_operation;
            $entres->n_piece = $request->n_piece;

            $total = $entree_sortie * $request->quantite;

            if($request->devise == 1)
            {
                $total = $total / $request->taux;
            }
            $entres->total = round($total, 2);
            $entres->devise = $request->devise;
            $entres->taux = $request->taux;
            $entres->libelle = $request->libelle;
            $entres->date_creation = $request->date_operation;

            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('numero_sortie', Auth::user()->id)->first()["lien"];
            }
            $entres->preuve_de_sortie = $preuve;
            $entres->save();
            if($type_operation == 0)
            {
                $solde_actuel = $solde_actuel + $total;
            }else
            {
                $solde_actuel = $solde_actuel - $total;
            }
            $soldes->solde_actuel = round($solde_actuel, 2);
            $soldes->save();
            Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        }
        else
        {
            $id = Factures::get()->count() + 1;
            $nb_annonce = $id;
            if($nb_annonce >= 1 && $nb_annonce <= 9)
            {
                $nb_annonce = '000' . $nb_annonce;
            }
            else if($nb_annonce >= 10 && $nb_annonce <= 99)
            {
                $nb_annonce = '00' . $nb_annonce;
            }
            else if($nb_annonce >= 100 && $nb_annonce <= 999)
            {
                $nb_annonce = '0' . $nb_annonce;
            }

            Session::put("facture_user_id", $id);
            // Facture
            $factures = new Factures();
            $factures->id = $id;
            $factures->numero = $nb_annonce;
            $factures->date_creation = date("d/m/Y");
            $factures->user_id = Auth::user()->id;
            $factures->save();

            // Entres
            $entres = new Entres();
            $id2 = Entres::get()->count() + 1;
            while(Entres::where(["id" => $id2])->count() != 0)
            {
                $id2 = $id2 + 1;
            }
            $entres->id = $id2;
            $entres->user_id = Auth::user()->id;
            $entres->facture_id = $id;
            $entres->type_frai_id = $request->type_sortie;
            $entres->annee_id = $annee_id;
            $entres->moi_id = $moi_id;
            $entres->prix_unitaire = $entree_sortie;
            $entres->quantite = $request->quantite;
            $entres->entree = $request->entree;
            $entres->sortie = $request->sortie;
            $entres->type = $type_operation;
            $entres->n_piece = $request->n_piece;

            $total = $entree_sortie * $request->quantite;

            if($request->devise == 1)
            {
                $total = $total / $request->taux;
            }
            $entres->total = round($total, 2);
            $entres->devise = $request->devise;
            $entres->taux = $request->taux;
            $entres->libelle = $request->libelle;
            $entres->date_creation = $request->date_operation;


            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('numero_sortie', Auth::user()->id)->first()["lien"];
            }
            $entres->preuve_de_sortie = $preuve;
            $entres->save();
            if($type_operation == 0)
            {
                $solde_actuel = $solde_actuel + $total;
            }else
            {
                $solde_actuel = $solde_actuel - $total;
            }
            $soldes->solde_actuel = round($solde_actuel);
            $soldes->save();
            Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        }
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["factures"] = Factures::get();
        }
        return view('include.refresh_factures', $data);
    }

    public function add_depense(Request $request)
    {

        // Entres
        $depenses = new Depenses();
        $id = Depenses::get()->count() + 1;
        $depenses->id = $id;
        $depenses->user_id = Auth::user()->id;
        $depenses->montant = $request->montant;
        $depenses->devise = $request->devise;
        $depenses->type_depense_id = $request->type_depense_id;
        $depenses->taux = $request->taux;
        $depenses->libelle = $request->libelle;
        $depenses->date_depense = $request->date_operation;

        if(strlen(trim($request->n_piece)))
        {
            $depenses->n_piece = $request->n_piece;
        }
        else
        {
            $depenses->n_piece = "";

        }
        if(strlen(trim($request->libelle)))
        {
            $depenses->libelle = $request->libelle;
        }
        else
        {
            $depenses->libelle = "";

        }

        $preuve = "";
        $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
        if($nb_file != 0)
        {
            $preuve = Fichierss::where('numero_sortie', Auth::user()->id)->first()["lien"];
        }
        $depenses->preuve_de_sortie = $preuve;
        $depenses->save();
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();

        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 18;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["depense"] = Depenses::where(["supprimer" => 0, "user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["depense"] = Depenses::get();
        }
        return view('include.refresh_depenses', $data);
    }


    public function add_credit(Request $request)
    {
        // Credits
        $credits = new Credits();
        $id = Credits::get()->count() + 1;
        $credits->id = $id;
        $credits->user_id = Auth::user()->id;
        $entree = $request->entree;
        if($request->devise_credit == 1)
        {
            $entree = $entree / $request->taux_credit;
        }
        $credits->entree = round($entree, 2);
        $credits->devise_credit = $request->devise_credit;
        $credits->type = $request->type;
        $credits->nom_credit = $request->nom_credit;
        $credits->taux_credit = $request->taux_credit;
        $credits->libelle = $request->libelle;
        $credits->date_credit = $request->date_credit;
        $credits->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
        $data["credits"] = Credits::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["factures"] = Factures::get();
            $data["credits"] = Credits::get();
        }
        return view('include.refresh_credits', $data);
    }

    public function get_credit(Request $request)
    {
        // Credits
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
        $data["credits"] = Credits::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["factures"] = Factures::get();
            $data["credits"] = Credits::get();
        }
        return view('include.refresh_credits', $data);
    }

    public function get_credit_2(Request $request)
    {
        // Credits
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::where(["user_id" => Auth::user()->id])->get();
        $data["creditts"] = Creditts::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["factures"] = Factures::get();
            $data["creditts"] = Creditts::get();
        }
        return view('include.refresh_credits_2', $data);
    }


    public function get_image_utilisateur(Request $request)
    {
        return response()->json([['https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80']]);
    }

    public function add_app_article(Request $request)
    {
        $articles = Articles::where('id', $request->type_sortie)->first();
        $stock = $articles->stock;
        $devise_article = $articles->devise;
        if(Session::get("facture_user_id"))
        {
            // Approvisionnements
            $approvisionnements = new Approvisionnements();
            $id2 = Approvisionnements::get()->count() + 1;
            $approvisionnements->id = $id2;
            $approvisionnements->user_id = Auth::user()->id;
            $approvisionnements->facture_id = Session::get("facture_user_id");
            $approvisionnements->article_id = $request->type_sortie;
            $approvisionnements->prix_unitaire = $request->prix_unitaire;
            $approvisionnements->quantite = $request->quantite;

            $total = $request->prix_unitaire * $request->quantite;

            $approvisionnements->total = round($total, 2);
            $approvisionnements->devise = $request->devise;
            $approvisionnements->taux = $request->taux;
            $approvisionnements->libelle = $request->libelle;
            $approvisionnements->date_creation = date("d/m/Y");

            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('id', Auth::user()->id)->first()["lien"];
            }

            $approvisionnements->preuve_de_sortie = $preuve;
            $approvisionnements->save();
            $stock = $stock + $request->quantite;
            $articles->stock = round($stock);
            $articles->save();
            Fichierss::where('id', Auth::user()->id)->delete();
        }
        else
        {
            $id = Factureas::get()->count() + 1;
            $nb_annonce = $id;
            if($nb_annonce >= 1 && $nb_annonce <= 9)
            {
                $nb_annonce = '000' . $nb_annonce;
            }
            else if($nb_annonce >= 10 && $nb_annonce <= 99)
            {
                $nb_annonce = '00' . $nb_annonce;
            }
            else if($nb_annonce >= 100 && $nb_annonce <= 999)
            {
                $nb_annonce = '0' . $nb_annonce;
            }

            Session::put("facture_user_id", $id);
            // Facture
            $factures = new Factureas();
            $factures->id = $id;
            $factures->numero = $nb_annonce;
            $factures->date_creation = date("d/m/Y");
            $factures->devise = $devise_article;
            $factures->taux = $request->taux;
            $factures->libelle = $request->libelle;
            $factures->tva = 0;
            $factures->user_id = Auth::user()->id;
            $factures->save();


            // Approvisionnements
            $approvisionnements = new Approvisionnements();
            $id2 = Approvisionnements::get()->count() + 1;
            $approvisionnements->id = $id2;
            $approvisionnements->user_id = Auth::user()->id;
            $approvisionnements->facture_id = $id;
            $approvisionnements->article_id = $request->type_sortie;
            $approvisionnements->prix_unitaire = $request->prix_unitaire;
            $approvisionnements->quantite = $request->quantite;

            $total = $request->prix_unitaire * $request->quantite;


            $approvisionnements->total = round($total, 2);
            $approvisionnements->devise = $request->devise;
            $approvisionnements->taux = $request->taux;
            $approvisionnements->libelle = $request->libelle;
            $approvisionnements->date_creation = date("d/m/Y");

            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('id', Auth::user()->id)->first()["lien"];
            }
            $approvisionnements->preuve_de_sortie = $preuve;
            $approvisionnements->save();
            $stock = $stock + $request->quantite;
            $articles->stock = round($stock);
            $articles->save();
            Fichierss::where('id', Auth::user()->id)->delete();
        }
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factureas::get();
        return view('include.refresh_factureas', $data);
    }

    public function add_achat_article(Request $request)
    {
        // --- 1. Récupération de l'article selon le stock lié à la table ---
        $article_id = $request->type_sortie;
        $table_id = $request->table_id;
        $stock_id = 0;

        if (!empty($table_id))
        {
            $table = Tables::where('id', $table_id)->first();
            if ($table) {
                $pointdeventes = pointdeventes::where('id', $table->pointdeventes_id)->first();
                $stock_id = $pointdeventes ? $pointdeventes->stock_id : 0;
            }
        }

        if ($stock_id == 0)
        {
            $article = Articles::where('id', $article_id)->first();
        } else {
            $article = articlestocks::where([
                'stock_id'   => $stock_id,
                'article_id' => $article_id,
            ])->first();
        }

        if (!$article) {
            return back()->withErrors(['article' => 'Article introuvable pour ce stock.']);
        }

        // --- 2. Calcul des données communes ---
        $dernierApprovisionnement = Approvisionnements::where('article_id', $article_id)->latest('id')->first();

        $stock = $article->stock;
        $devise_article = $article->devise;
        $avoir_stock = $article->avoir_stock;

        // Prix d'achat
        if ($avoir_stock == 1) {
            $prix_achat = $dernierApprovisionnement->prix_unitaire;
            $devise_achat = $dernierApprovisionnement->devise;
        } else {
            $prix_achat = ($request->type_vente_id == 1) ? $article->prix_detail : $article->prix_gros;
            $devise_achat = $article->devise;
        }

        // Prix de vente et taille lot
        if ($request->type_vente_id == 1)
        {
            $taille_lot = $article->taille_piece;
            $prix_unitaire = $article->prix_detail;
        } else { // type_vente_id == 2
            $taille_lot = $article->taille_lot;
            $prix_unitaire = $article->prix_gros;
        }

        // --- 3. Gestion de la facture (création si nécessaire) ---
        $facture_id = Session::get("facture_user_id");

        if (!$facture_id) {
            // === C'est le "else" du if(Session::get("facture_user_id")) ===
            // Création d'une nouvelle facture
            $id = Factureass::get()->count() + 1;
            $nb_annonce = str_pad($id, 4, '0', STR_PAD_LEFT);

            // Taux general et tva des facture
            $activite_id = Articles::where('id', $article_id)->first()["activite_id"];
            $activites = Activites::where('id', $article_id)->first();
            $taux_general = $activites->taux;
            $tva_general = $activites->tva;

            $facture = new Factureass();
            $facture->id = $id;
            $facture->numero = $nb_annonce;
            $facture->date_creation = date("d/m/Y");
            $facture->devise = $devise_article;
            $facture->taux = $taux_general;
            $facture->libelle = $request->libelle;
            $facture->tva = $tva_general;
            $facture->user_id = Auth::user()->id;
            $facture->client_id = (strlen(trim($request->client_id))) ? $request->client_id : 0;
            $facture->table_id = (strlen(trim($request->table_id))) ? $request->table_id : 0;
            $facture->save();

            // Marquage de la table comme occupée (si elle existe)
            if (!empty($table_id))
            {
                $table = Tables::find($table_id);
                if ($table) {
                    $table->occupee = 1;
                    $table->propre = 1;
                    $table->save();
                }
            }

            Session::put("facture_user_id", $id);
            $facture_id = $id;
        }

        // --- 4. Création de l'achat ---
        $achat = new Achats();
        $achat->id = Achats::get()->count() + 1;
        $achat->user_id = Auth::user()->id;
        $achat->facture_id = $facture_id;
        $achat->article_id = $article_id;
        $achat->type = $request->action;
        $achat->prix_unitaire = $prix_unitaire;
        $achat->quantite = $request->quantite;
        $achat->type_vente_id = $request->type_vente_id;
        $achat->taille_lot = $taille_lot;
        $achat->total = round($prix_unitaire * $request->quantite, 2);
        $achat->devise = $devise_article;
        $achat->taux = $request->taux;
        $achat->libelle = $request->libelle;
        $achat->client_id = (strlen(trim($request->client_id))) ? $request->client_id : 0;
        $achat->date_creation = date("d/m/Y");
        $achat->prix_achat = $prix_achat;
        $achat->devise_achat = $devise_achat;

        // Gestion de la preuve (fichier)
        $preuve = "";
        $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
        if ($nb_file != 0) {
            $preuve = Fichierss::where('id', Auth::user()->id)->first()["lien"];
        }
        $achat->preuve_de_sortie = $preuve;
        $achat->save();

        // --- 5. Mise à jour du stock ---
        $stock = $stock - $request->quantite;
        $article->stock = round($stock);
        $article->save();

        // Nettoyage des fichiers temporaires
        Fichierss::where('id', Auth::user()->id)->delete();

        // --- 6. Retour de la vue ---
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factureass::get();

        return view('include.refresh_factureass', $data);
    }

    public function add_article(Request $request)
    {
        // Article
        $articles = new Articles();
        $id = Articles::get()->count() + 1;
        $articles->id = $id;
        $articles->user_id = Auth::user()->id;
        $articles->societe_id = $request->categorie_id;
        $articles->nom_article = $request->nom_article;
        $articles->prix = $request->prix;
        $articles->devise  = $request->devise;
        $articles->seuil_minimum  = $request->seuil_minimum;
        $articles->seuil_maximum  = $request->seuil_maximum;
        $articles->prix_detail  = $request->prix_detail;
        $articles->prix_gros  = $request->prix_gros;
        $articles->taille_lot  = $request->taille_lot;
        $articles->stock  = 0;
        $articles->date_expiration  = $request->date_expiration;
        $articles->date_creation  = date("d/m/Y");
        $articles->description  = $request->libelle;
        $articles->activite_id  = $request->activite_id;
        $articles->mesure_id  = $request->mesure_id;
        $articles->avoir_stock  = $request->avoir_stock;

        $image = "";
        $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
        if($nb_file != 0)
        {
            $image = Fichierss::where('id', Auth::user()->id)->first()["lien"];
        }
        $articles->image = $image;
        $articles->save();
        Fichierss::where('id', Auth::user()->id)->delete();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::get();
        $data["articles"] = Articles::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
        if(Auth::user()->role == 0)
        {
            $data["articles"] = Articles::where(["supprimer" => 0])->get();
        }
        return view('include.refresh_articles', $data);
    }

    public function transfer_article(Request $request)
    {
        try {
            DB::beginTransaction();

            // --------------------------------------------------------------
            // 1. Quantité : toujours un entier >= 0
            // --------------------------------------------------------------
            $quantite = $request->filled('transfert_quantite') ? (int) $request->transfert_quantite : 0;
            if ($quantite < 0) {
                $quantite = 0;
            }

            // --------------------------------------------------------------
            // 2. Récupération de l'article source
            // --------------------------------------------------------------
            $article = Articles::find($request->transfer_article_id);
            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article introuvable.'
                ], 404);
            }

            // --------------------------------------------------------------
            // 3. Détection du mode : transfert depuis un stock spécifique ?
            // --------------------------------------------------------------
            $sourceStockId = $request->input('transfer_source_stock_id', null);
            $isSpecific = $request->has('transfer_source_stock_id');

            // --------------------------------------------------------------
            // 4. Récupération des stocks de destination
            // --------------------------------------------------------------
            $stockIds = $request->input('transfert_stock_id', []);
            if (!is_array($stockIds)) {
                $stockIds = [$stockIds];
            }
            $stockIds = array_filter($stockIds, function($id) {
                return $id !== '' && $id !== null;
            });

            // --- En mode spécifique, on interdit le transfert vers le stock source ---
            if ($isSpecific && in_array($sourceStockId, $stockIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas transférer vers le même stock source.'
                ], 400);
            }

            // --- En mode global, on interdit le transfert vers le stock principal (0) ---
            if (!$isSpecific && in_array(0, $stockIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de transférer vers le stock principal depuis la gestion globale.'
                ], 400);
            }

            if (empty($stockIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez sélectionner au moins un stock de destination.'
                ], 400);
            }

            $nbDest = count($stockIds);

            // --------------------------------------------------------------
            // 5. Validation selon avoir_stock
            // --------------------------------------------------------------
            if ($article->avoir_stock == 1) {
                // La quantité doit être > 0
                if ($quantite <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La quantité doit être supérieure à 0.'
                    ], 400);
                }

                // Calcul du total à déduire du stock source
                if ($isSpecific) {
                    // Mode spécifique : quantité par destination, donc total = quantite * nbDest
                    $totalQuantite = $quantite * $nbDest;
                } else {
                    // Mode global : quantité est le total à répartir
                    $totalQuantite = $quantite;
                }

                // Vérification et déduction du stock source
                if ($isSpecific && $sourceStockId == 0) {
                    // Source = stock principal
                    if ($article->stock < $totalQuantite) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuffisant dans le stock principal.'
                        ], 400);
                    }
                    $article->stock -= $totalQuantite;
                    $article->save();

                } elseif ($isSpecific && $sourceStockId > 0) {
                    // Source = stock secondaire
                    $articlestockSource = articlestocks::where('article_id', $request->transfer_article_id)
                                                        ->where('stock_id', $sourceStockId)
                                                        ->first();
                    if (!$articlestockSource) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cet article n\'existe pas dans le stock source sélectionné.'
                        ], 400);
                    }
                    if ($articlestockSource->stock < $totalQuantite) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuffisant dans le stock source.'
                        ], 400);
                    }
                    $articlestockSource->stock -= $totalQuantite;
                    $articlestockSource->save();

                } else {
                    // Mode global : source = stock principal (0)
                    if ($article->stock < $totalQuantite) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuffisant pour cet article.'
                        ], 400);
                    }
                    $article->stock -= $totalQuantite;
                    $article->save();
                }
            }
            // Si avoir_stock != 1, on ne touche pas au stock de l'article

            // --------------------------------------------------------------
            // 6. Détermination de la quantité par destination
            // --------------------------------------------------------------
            if ($isSpecific) {
                // Chaque destination reçoit exactement la quantité saisie
                $qteParStock = $quantite;
                $reste = 0;
            } else {
                // Répartition équitable du total
                $qteParStock = intdiv($quantite, $nbDest);
                $reste = $quantite - ($qteParStock * $nbDest);
            }

            // --------------------------------------------------------------
            // 7. Mise à jour des stocks de destination
            // --------------------------------------------------------------
            $results = [];
            foreach ($stockIds as $index => $stockId) {
                $qte = $qteParStock + ($index < $reste ? 1 : 0);

                if ($stockId == 0) {
                    // Destination = stock principal
                    $article->stock += $qte;
                    $article->save();
                    $action = 'updated_principal';
                    $articlestocks = null;
                } else {
                    // Destination = stock secondaire
                    $articlestocks = articlestocks::where('article_id', $request->transfer_article_id)
                                                    ->where('stock_id', $stockId)
                                                    ->first();

                    if ($articlestocks) {
                        $articlestocks->stock += $qte;
                        $articlestocks->save();
                        $action = 'updated';
                    } else {
                        $articlestocks = new articlestocks();
                        $articlestocks->id = articlestocks::count() + 1;
                        $articlestocks->user_id = Auth::id();
                        $articlestocks->article_id = $request->transfer_article_id;
                        $articlestocks->devise = $request->transfert_devise_dest;
                        $articlestocks->prix_detail = $request->transfert_prix_detail_dest;
                        $articlestocks->prix_gros = $request->transfert_prix_gros_dest;
                        $articlestocks->taille_lot = $request->transfert_taille_lot_dest;
                        $articlestocks->stock = $qte;
                        $articlestocks->date_creation = now()->format('d/m/Y');
                        $articlestocks->stock_id = $stockId;
                        $articlestocks->avoir_stock = $article->avoir_stock;
                        $articlestocks->save();
                        $action = 'created';
                    }
                }

                // Création du transfert
                $transfert = new transfertstocks();
                $transfert->id = transfertstocks::count() + 1;
                $transfert->user_id = Auth::id();
                $transfert->article_id = $request->transfer_article_id;
                $transfert->commentaire = $request->transfert_commentaire;
                $transfert->qte = $qte;
                $transfert->date_creation = now()->format('d/m/Y');
                $transfert->stock_1 = $isSpecific ? $sourceStockId : 0;
                $transfert->stock_2 = $stockId;
                $transfert->save();

                $results[] = [
                    'stock_id' => $stockId,
                    'quantite' => $qte,
                    'action'   => $action,
                    'articlestocks' => $articlestocks,
                    'transfert' => $transfert
                ];
            }

            DB::commit();

            // --------------------------------------------------------------
            // 8. Réponse JSON
            // --------------------------------------------------------------
            return response()->json([
                'success' => true,
                'message' => 'Transfert effectué vers ' . $nbDest . ' stock(s).',
                'data'    => [
                    'article' => $article,
                    'details' => $results
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }



    public function edit_article(Request $request)
    {
        // Article
        $articles = Articles::where('id', $request->id)->first();
        $articles->user_id = Auth::user()->id;
        $articles->societe_id = $request->edit_categorie_id;
        $articles->nom_article = $request->edit_nom_article;
        $articles->prix = $request->edit_prix;
        $articles->devise  = $request->edit_devise;
        $articles->seuil_minimum  = $request->edit_seuil_minimum;
        $articles->seuil_maximum  = $request->edit_seuil_maximum;
        $articles->prix_detail  = $request->edit_prix_detail;
        $articles->prix_gros  = $request->edit_prix_gros;
        $articles->taille_lot  = $request->edit_taille_lot;
        $articles->date_expiration  = $request->edit_date_expiration;
        $articles->date_creation  = date("d/m/Y");
        $articles->description  = $request->edit_libelle;
        $articles->activite_id  = $request->edit_activite_id;
        $articles->mesure_id  = $request->edit_mesure_id;
        $articles->avoir_stock  = $request->edit_avoir_stock;


        $articles->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Factures::get();
        $data["articles"] = Articles::where(["user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
        if(Auth::user()->role == 0)
        {
            $data["articles"] = Articles::where(["supprimer" => 0])->get();
        }
        return view('include.refresh_articles', $data);
    }

    public function add_sortie(Request $request)
    {
        $soldes = Soldes::where('etat', 1)->first();
        $solde_actuel = $soldes->solde_actuel;
        $annee_id = $soldes->annee_id;
        $moi_id = $soldes->moi_id;
        if(Session::get("facture_user_id"))
        {
            // Sortie
            $sorties = new Sorties();
            $id2 = Sorties::get()->count() + 1;
            $sorties->id = $id2;
            $sorties->user_id = Auth::user()->id;
            $sorties->facture_id = Session::get("facture_user_id");
            $sorties->type_frai_id = $request->type_sortie;
            $sorties->annee_id = $annee_id;
            $sorties->moi_id = $moi_id;
            $sorties->prix_unitaire = $request->prix_unitaire;
            $sorties->quantite = $request->quantite;

            $total = $request->prix_unitaire * $request->quantite;

            if($request->devise == 1)
            {
                $total = $total / $request->taux;
            }
            $sorties->total = round($total, 2);
            $sorties->devise = $request->devise;
            $sorties->taux = $request->taux;
            $sorties->libelle = $request->libelle;
            $sorties->date_creation = date("d/m/Y");

            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('id', Auth::user()->id)->first()["lien"];
            }

            $sorties->preuve_de_sortie = $preuve;
            $sorties->save();
            $solde_actuel = $solde_actuel - $total;
            $soldes->solde_actuel = round($solde_actuel, 2);
            $soldes->save();
            Fichierss::where('id', Auth::user()->id)->delete();
        }
        else
        {
            $id = Facturess::get()->count() + 1;
            $nb_annonce = $id;
            if($nb_annonce >= 1 && $nb_annonce <= 9)
            {
                $nb_annonce = '000' . $nb_annonce;
            }
            else if($nb_annonce >= 10 && $nb_annonce <= 99)
            {
                $nb_annonce = '00' . $nb_annonce;
            }
            else if($nb_annonce >= 100 && $nb_annonce <= 999)
            {
                $nb_annonce = '0' . $nb_annonce;
            }

            Session::put("facture_user_id", $id);
            // Facture
            $factures = new Facturess();
            $factures->id = $id;
            $factures->numero = $nb_annonce;
            $factures->date_creation = date("d/m/Y");
            $factures->user_id = Auth::user()->id;
            $factures->save();


            // Sortie
            $entres = new Sorties();
            $id2 = Sorties::get()->count() + 1;
            $entres->id = $id2;
            $entres->user_id = Auth::user()->id;
            $entres->facture_id = $id;
            $entres->type_frai_id = $request->type_sortie;
            $entres->annee_id = $annee_id;
            $entres->moi_id = $moi_id;
            $entres->prix_unitaire = $request->prix_unitaire;
            $entres->quantite = $request->quantite;

            $total = $request->prix_unitaire * $request->quantite;

            if($request->devise == 1)
            {
                $total = $total / $request->taux;
            }
            $entres->total = round($total, 2);
            $entres->devise = $request->devise;
            $entres->taux = $request->taux;
            $entres->libelle = $request->libelle;
            $entres->date_creation = date("d/m/Y");

            $preuve = "";
            $nb_file = Fichierss::where(["numero_sortie" => Auth::user()->id])->count();
            if($nb_file != 0)
            {
                $preuve = Fichierss::where('id', Auth::user()->id)->first()["lien"];
            }
            $entres->preuve_de_sortie = $preuve;
            $entres->save();
            $solde_actuel = $solde_actuel - $total;
            $soldes->solde_actuel = round($solde_actuel, 2);
            $soldes->save();
            Fichierss::where('id', Auth::user()->id)->delete();
        }
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["factures"] = Facturess::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["factures"] = Facturess::get();
        }
        return view('include.refresh_factures_1', $data);
    }


    public function solde_actif(Request $request)
    {
        $soldes = Soldes::where(["etat" => 1])->get();
        return response()->json([[$soldes->count()]]);
    }

    public function get_entre(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["entres"] = Entres::where(["facture_id" => Session::get("facture_user_id")])->get();
        $data["facture_id"] = Session::get("facture_user_id");
        $Factures = Factures::where('id', Session::get("facture_user_id"))->first();
        $data["numero"] = $Factures["numero"];
        return view('include.refresh_entres', $data);
    }

    public function get_approvisionnement(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["approvisionnements"] = approvisionnements::where(["facture_id" => Session::get("facture_user_id")])->get();
        $Factures = Factureas::where('id', Session::get("facture_user_id"))->first();
        $data["numero"] = $Factures["numero"];
        $data["devise"] = $Factures["devise"];
        $data["factures"] = Factureas::where('id', Session::get("facture_user_id"))->get();
        return view('include.refresh_approvisionnements', $data);
    }

    public function get_achat(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["achats"] = Achats::where(["facture_id" => Session::get("facture_user_id")])->get();
        $Factures = Factureass::where('id', Session::get("facture_user_id"))->first();
        $data["numero"] = $Factures["numero"];
        $data["facture_id"] = $Factures["id"];
        $data["factures"] = $Factures;
        $data["devise"] = $Factures["devise"];
        return view('include.refresh_achats', $data);
    }

    public function get_sortie(Request $request)
    {
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["sorties"] = Sorties::where(["facture_id" => Session::get("facture_user_id")])->get();
        $Factures = Facturess::where('id', Session::get("facture_user_id"))->first();
        $data["numero"] = $Factures["numero"];
        return view('include.refresh_sorties', $data);
    }

    public function add_contentieux(Request $request)
    {
        $invitation_link = "";
        $valeur_infraction = "";
        $i = 1;
        $j = 1;
        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            $invitation_link = $invitation_link . '____________________' . $f->lien;
        }
        $id = Contentieurs::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }
        $contentieurs = new Contentieurs();
        $contentieurs->id = $id;
        $contentieurs->date_reception_invitation = "";
        $contentieurs->heure_reception_invitation = "";
        $contentieurs->contrevenant_id = 0;
        $contentieurs->type_contentieux = "";
        $contentieurs->type_verbalisateur = "";
        $contentieurs->invitation_receptionnee_par = "";
        $contentieurs->identite_contrevenant = "";
        $contentieurs->numero_pv = "";
        $contentieurs->taux = 2000;
        $contentieurs->devise = 0;
        $contentieurs->decision_id = $request->decision;
        $contentieurs->montant = 0;
        $contentieurs->infractions = $valeur_infraction;
        $contentieurs->type_infraction_id  = 1;
        $contentieurs->type_objet = 1;
        if($request->type_objet == 0)
        {
            $contentieurs->date_debut_infraction = "";
            $contentieurs->date_fin_infraction = "";
        }
        $contentieurs->pv = $invitation_link;
        $contentieurs->note_1 = "";
        $contentieurs->note_2 = "";
        $contentieurs->note_3 = "";
        $contentieurs->type_resolution = "";
        $contentieurs->risque = 2000;
        // $contentieurs->invitation_id = $request->i_id;
        $contentieurs->recherche = "";


        $contentieurs->num_projet = $nb_annonce;
        $contentieurs->nom_projet = $request->nom_projet;
        $contentieurs->date_creation = $request->date_creation;
        $contentieurs->budget = $request->budget;
        $contentieurs->nombre_personne = $request->nombre_personne;
        $contentieurs->debut = $request->debut;
        $contentieurs->fin = $request->fin;
        $contentieurs->description = $request->description;

        $contentieurs->save();
        if($request->type_objet == 1)
        {
            $num_id = numdeclarations::get()->count() + 1;
            $numdeclarations = New numdeclarations();
            $numdeclarations->id = $num_id;
            $numdeclarations->numero = $request->numero;
            $numdeclarations->contentieur_id = $id;
            $numdeclarations->date_creation = date("d/m/Y");
            $numdeclarations->save();
        }
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        if(strlen(trim($request->decision_1)) == 0)
        {
            $data["contentieux"] = Contentieurs::where(["etat" => 1])->get();
        }
        else
        {
            $data["contentieux"] = Contentieurs::where(["etat" => 1, "decision_id" => $request->decision_1])->get();
        }
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        $fichiers = Fichiers::get();
        foreach ($fichiers as $f)
        {
            Fichiers::where('id', $f->id)->delete();
        }
        return view('include.refresh_contentieux', $data);
    }


    public function get_contentieux(Request $request)
    {
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        if(strlen(trim($request->decision_1)) == 0)
        {
            $data["contentieux"] = Contentieurs::where(["etat" => 1])->get();
        }
        else
        {
            $data["contentieux"] = Contentieurs::where(["etat" => 1, "decision_id" => $request->decision_1])->get();
        }
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_contentieux', $data);
    }


    public function get_mois(Request $request)
    {
        $data["mois"] = Mois::get();
        $data["annee_id"] = $request->annee_id;
        return view('include.get_mois', $data);
    }

    public function get_mois_1(Request $request)
    {
        $data["mois"] = Mois::get();
        $data["annee_id"] = $request->annee_id;
        return view('include.get_mois_1', $data);
    }

    public function get_mois_2(Request $request)
    {
        $data["mois"] = Mois::get();
        $data["annee_id"] = $request->annee_id;
        return view('include.get_mois_2', $data);
    }

    public function get_client_by_activite(Request $request)
    {
        $data["activite_id"] = $request->activite_id;
        if(Auth::user()->role == 0)
        {
            $clients = Clients::where(["etat" => 1, 'activite_id' => $request->activite_id])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id, 'activite_id' => $request->activite_id])->get();
        }
        $data["clients"] = $clients;
        return view('include.get_client_by_activite', $data);
    }

    public function get_commune_by_district(Request $request)
    {
        $communes = communes::where(['district_id' => $request->district_id])->get();
        $data["communes"] = $communes;
        return view('include.get_commune_by_district', $data);
    }

    public function get_user_where_not_in_listespaies(Request $request)
    {
        $data["listespaie_id"] = $request->listespaie_id;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        return view('include.get_user_where_not_in_listespaies', $data);
    }

    public function get_user_where_not_in_listesfactures(Request $request)
    {
        $activite_id = 0;
        if($request->activite_id != 0)
        {
            $activite_id = $request->activite_id;
        }
        $data["listesfactures_id"] = $request->listesfactures_id;
        if(Auth::user()->role == 0)
        {
             $data["clients"] = Clients::where(["etat" => 1, "activite_id" => $activite_id])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $data["clients"] = Clients::where(["etat" => 1, "activite_id" => $activite_id, "user_id" => Auth::user()->id])->get();
        }
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        return view('include.get_user_where_not_in_listesfactures', $data);
    }

    public function get_mois_where_not_in_prestation(Request $request)
    {
        $annee_id = $request->annee_id;
        $data["mois"] = Mois::get();
        $data["annee_id"] = $annee_id;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        return view('include.get_mois_where_not_in_prestation', $data);
    }

    public function get_numero_facture(Request $request)
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        Session::forget("facture_user_id");
        $id = Factures::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }

        $data["numero_facture"] = $nb_annonce;
        return view('include.get_numero_facture', $data);
    }


    public function get_numero_facture_1(Request $request)
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        Session::forget("facture_user_id");
        $id = Facturess::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }

        $data["numero_facture"] = $nb_annonce;
        return view('include.get_numero_facture_1', $data);
    }

    public function get_delete_fichier(Request $request)
    {
        Fichiers::where('user_id', Auth::user()->id)->delete();
        echo "200";
    }

    public function get_numero_facture_a(Request $request)
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        Session::forget("facture_user_id");
        $id = Factureas::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }

        $data["numero_facture"] = $nb_annonce;
        return view('include.get_numero_facture_a', $data);
    }

    public function get_numero_facture_b(Request $request)
    {
        Fichierss::where('numero_sortie', Auth::user()->id)->delete();
        Session::forget("facture_user_id");
        $id = Factureass::get()->count() + 1;
        $nb_annonce = $id;
        if($nb_annonce >= 1 && $nb_annonce <= 9)
        {
            $nb_annonce = '000' . $nb_annonce;
        }
        else if($nb_annonce >= 10 && $nb_annonce <= 99)
        {
            $nb_annonce = '00' . $nb_annonce;
        }
        else if($nb_annonce >= 100 && $nb_annonce <= 999)
        {
            $nb_annonce = '0' . $nb_annonce;
        }

        $data["numero_facture"] = $nb_annonce;
        return view('include.get_numero_facture_b', $data);
    }

    public function delete_facture_user_id(Request $request)
    {
        Session::forget("facture_user_id");
    }


    public function get_solde_initial(Request $request)
    {
        $data["mois"] = Mois::get();
        $data["annee_id"] = $request->annee_id;
        return view('include.get_solde_initial', $data);
    }



    public function edit_utilisateur(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->name = $request->edit_nom;
        $user->email = $request->edit_email;
        $user->password = Hash::make($request->edit_mdp);
        $user->mdp = $request->edit_mdp;
        $user->phone = $request->edit_phone;
        $user->role = $request->edit_role;
        $user->salaire = $request->edit_salaire;
        $user->devise = $request->edit_devise;
        $user->etat = 1;
        $user->recherche = "";
        $user->image = $request->edit_image;
        $user->poste_id = $request->edit_poste_id;
        $user->activite_id = $request->edit_activite_id;
        $user->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        return view('include.refresh_utilisateur', $data);
    }


    public function edit_utilisateur_profil(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $request->nom;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if((strlen(trim($request->mdp)) != 0 && strlen(trim($request->cmdp)) != 0) && (trim($request->mdp) == trim($request->cmdp)))
        {
            $user->password = Hash::make($request->mdp);
            $user->mdp = $request->mdp;
        }
        $user->save();
        return  strtoupper(User::where('id', Auth::user()->id)->first()["name"]);
    }


    public function edit_client(Request $request)
    {
        $clients = Clients::where('id', $request->id)->first();
        $clients->name = $request->edit_nom;
        $clients->email = $request->edit_email;
        $clients->phone = $request->edit_phone;
        $clients->paiement = $request->edit_paiement;
        $clients->devise = $request->edit_devise;
        $clients->description = $request->edit_description;
        $clients->factures = $request->edit_facture;
        if(strlen(trim($request->edit_email)) == 0)
        {
            $clients->email = "";
        }
        else
        {
            $clients->email = $request->edit_email;
        }

        if(strlen(trim($request->edit_adresse)) == 0)
        {
            $clients->adresse = "";
        }
        else
        {
            $clients->adresse = $request->edit_adresse;
        }

        if(strlen(trim($request->edit_description)) == 0)
        {
            $clients->description = "";
        }
        else
        {
            $clients->description = $request->edit_description;
        }

        if(strlen(trim($request->edit_latitude)) != 0){
             $clients->latitude = $request->edit_latitude;
        }else{
            $clients->latitude = 0;
        }
        if(strlen(trim($request->edit_longitude)) != 0){
             $clients->longitude = $request->edit_longitude;
        }else
        {
            $clients->longitude = 0;
        }
        $clients->activite_id = $request->edit_activite_id;
        $clients->type = $request->edit_type_client;
        $clients->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 14;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["activites"] = Activites::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_client', $data);
    }

    public function cloturer_proces(Request $request)
    {
        $contentieux = Contentieurs::where('id', $request->id)->first();
        $contentieux->type_resolution = $request->resolution;
        $contentieux->note_1 = $request->note_1;
        $contentieux->note_2 = $request->note_2;
        $contentieux->note_3 = $request->note_3;
        $contentieux->cloturer = 1;
        $contentieux->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["contentieux"] = Contentieurs::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_contentieux', $data);
    }

    public function refresh_deleteutilisateur(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_utilisateur', $data);
    }

    public function refresh_deleteposte(Request $request)
    {
        $poste = Postes::where('id', $request->id)->first();
        $poste->supprimer = 1;
        $poste->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_poste', $data);
    }

    public function refresh_deleteecole(Request $request)
    {
        $poste = ecoles::where('id', $request->id)->first();
        $poste->supprimer = 1;
        $poste->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["ecoles"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_ecole', $data);
    }


    public function refresh_deleteeleve(Request $request)
    {
        $poste = beneficiaires::where('id', $request->id)->first();
        $poste->supprimer = 1;
        $poste->etat = 0;
        $poste->save();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["ecoles"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();

        if(Auth::user()->role == 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $beneficiaires = beneficiaires::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        }
        $data["beneficiaires"] = $beneficiaires;
        return view('include.refresh_eleve', $data);
    }


    public function refresh_deleteclient(Request $request)
    {
        $client = Clients::where('id', $request->id)->first();
        $client->etat = 0;
        $client->save();
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["activites"] = Activites::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 14;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_client', $data);
    }



    public function refresh_deleteinvitation(Request $request)
    {
        $invitation = Invitations::where('id', $request->id)->first();
        $invitation->etat = 0;
        $invitation->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_invitations', $data);
    }

    public function refresh_deletedecision(Request $request)
    {
        $decision = Decisions::where('id', $request->id)->first();
        $decision->etat = 0;
        $decision->save();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_decisions', $data);
    }

    public function refresh_editutilisateur(Request $request)
    {
        $data["utilisateurs"] = User::where('id', $request->user_id)->first();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        return view('include.refresh_editutilisateur', $data);
    }


    public function refresh_editactivite(Request $request)
    {
        $data["activites"] = Activites::where('id', $request->activite_id)->first();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        return view('include.refresh_editactivite', $data);
    }

    public function refresh_editposte(Request $request)
    {
        $data["postes"] = Postes::where('id', $request->poste_id)->first();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_editposte', $data);
    }

    public function refresh_editecole(Request $request)
    {
        $data["ecoles"] = ecoles::where('id', $request->ecole_id)->first();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        $data["districts"] = districts::get();
        return view('include.refresh_editecole', $data);
    }

    public function refresh_editeleve(Request $request)
    {
        $data["eleves"] = beneficiaires::where('id', $request->eleve_id)->first();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        $data["districts"] = districts::get();
        $data["ecoles"] = ecoles::get();
        $data["classes"] = classes::get();
        return view('include.refresh_editeleve', $data);
    }

    public function refresh_editclient(Request $request)
    {
        $data["clients"] = Clients::where('id', $request->client_id)->first();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["activites"] = Activites::where(["etat" => 1])->get();
        return view('include.refresh_editclient', $data);
    }


    public function get_prix_article(Request $request)
    {
        $stock_id = 0;
        if($table_id = $request->table_id)
        {
            $table_id = $request->table_id;
            $table = Tables::where('id', $table_id)->first();
            $pointdeventes_id = $table->pointdeventes_id;
            $pointdeventes = pointdeventes::where('id', $pointdeventes_id)->first();
            $stock_id = $pointdeventes->stock_id;
        }

        $article_id = $request->article_id;

        if ($stock_id == 0)
        {
            $article = Articles::where('id', $article_id)->first();
        } else
        {
            // Récupérer l'article depuis la table articlestocks avec le stock_id et article_id
            $article = articlestocks::where(['stock_id' => $stock_id, 'article_id' => $article_id])->first();
            // Si on veut les attributs de l'article, peut-être qu'il faut faire une relation, mais on suppose que articlestocks a les mêmes champs.
        }

        $prix = 0;
        $taille = 0;
        if ($request->type_vente_id == 1)
        {
            $prix = $article->prix_detail;
            $taille = $article->taille_piece;
        } elseif ($request->type_vente_id == 2) {
            $prix = $article->prix_gros;
            $taille = $article->taille_lot;
        }
        return response()->json([[$prix, $taille]]);
    }

    public function get_devise_article(Request $request)
    {
        $articles = Articles::where('id', $request->article_id)->first();
        return response()->json([[$articles->devise]]);
    }

    public function refresh_editarticle(Request $request)
    {
        $data["articles"] = Articles::where('id', $request->user_id)->first();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        $data["mesures"] = Mesures::where(["supprimer" => 0])->get();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $data["typeventes"] = Typeventes::where(["supprimer" => 0])->get();
        return view('include.refresh_editarticle', $data);
    }

    public function refresh_appro(Request $request)
    {
        $data["utilisateurs"] = User::where('id', $request->user_id)->first();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_appro', $data);
    }

    public function edit_invitations(Request $request)
    {
        $invitations = Invitations::where('id', $request->id)->first();
        $invitations->date_invitation = $request->edit_date_invitation;
        $invitations->heure_invitation = $request->edit_heure_invitation;
        $invitations->date_document = $request->edit_date_document;
        $invitations->verbalisateur_id = $request->edit_verbalisateur;
        $invitations->libelle = $request->edit_libelle;
        $invitations->signer_par = $request->edit_signer;
        if(trim(strlen($request->edit_description)) == 0)
        {
            $invitations->description  = "";
        }
        else
        {
            $invitations->description  = $request->edit_description;
        }
        $invitations->statut = $request->edit_statut;
        $invitations->numero_invitation = $request->edit_numero_invitation;
        $invitations->recherche = "";
        $invitations->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_invitations', $data);
    }

    public function edit_decisions(Request $request)
    {
        $decisions = Decisions::where('id', $request->id)->first();
        $decisions->nom_projet = $request->edit_nom_projet;
        $decisions->date_creation = $request->edit_date_creation;
        $decisions->budget = $request->edit_budget;
        $decisions->nombre_personne = $request->edit_nombre_personne;
        $decisions->debut = $request->edit_debut;
        $decisions->fin = $request->edit_fin;
        $decisions->description = $request->edit_description;
        $decisions->save();
        $data["invitations"] = Invitations::where(["etat" => 1])->get();
        $data["decisions"] = Decisions::where(["etat" => 1])->get();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_decisions', $data);
    }

    public function refresh_editgroupe(Request $request)
    {
        $data["groupes"] = Groupes::where('id', $request->groupe_id)->first();
        return view('include.refresh_editgroupe', $data);
    }

    public function refresh_editcontrevenant(Request $request)
    {
        $data["contrevenants"] = Contrevenants::where('id', $request->contrevenant_id)->first();
        return view('include.refresh_editcontrevenant', $data);
    }

    public function refresh_editverbalisateur(Request $request)
    {
        $data["verbalisateurs"] = Verbalisateurs::where('id', $request->verbalisateur_id)->first();
        return view('include.refresh_editverbalisateur', $data);
    }

    public function refresh_edit_type_frais(Request $request)
    {
        $data["type_frais"] = Type_frais::where('id', $request->type_frais_id)->first();
        return view('include.refresh_edit_type_frais', $data);
    }

    public function refresh_edit_type_documents(Request $request)
    {
        $data["type_documents"] = Type_documents::where('id', $request->type_documents_id)->first();
        return view('include.refresh_edit_type_documents', $data);
    }

    public function refresh_edit_point_ventes(Request $request)
    {
        $data["point_ventes"] = Pointdeventes::where('id', $request->point_vente_id)->first();
        return view('include.refresh_edit_point_ventes', $data);
    }

    public function refresh_edit_stocks(Request $request)
    {
        $data["stocks"] = Stocks::where('id', $request->stock_id)->first();
        return view('include.refresh_edit_stocks', $data);
    }

    public function refresh_edit_tables(Request $request)
    {
        $data["tables"] = Tables::where('id', $request->table_id)->first();
        $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        return view('include.refresh_edit_tables', $data);
    }

    public function refresh_table_point_ventes(Request $request)
    {
        $data["point_ventes"] = Pointdeventes::where('id', $request->point_vente_id)->first();
        return view('include.refresh_table_point_ventes', $data);
    }

    public function refresh_edit_societe(Request $request)
    {
        $data["societes"] = Societes::where('id', $request->societe_id)->first();
        return view('include.refresh_edit_societe', $data);
    }

    public function refresh_edit_type_infractions(Request $request)
    {
        $data["type_infractions"] = Type_infractions::where('id', $request->type_infractions_id)->first();
        return view('include.refresh_edit_type_infractions', $data);
    }

    public function refresh_editinvitations(Request $request)
    {
        $data["invitations"] = Invitations::where('id', $request->invitation_id)->first();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_editinvitations', $data);
    }

    public function refresh_editdecisions(Request $request)
    {
        $data["decisions"] = Decisions::where('id', $request->invitation_id)->first();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 10;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_editdecisions', $data);
    }

    public function refresh_detaildecisions(Request $request)
    {
        $data["decisions"] = Decisions::where('id', $request->invitation_id)->first();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 10;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detaildecisions', $data);
    }

    public function refresh_detailfactures(Request $request)
    {
        $Factures = Factures::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["facture_id"] = $facture_id;
        $data["ressource_id_1"] = 5;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["entres"] = Entres::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfactures', $data);
    }

    public function delete_operation(Request $request)
    {
        // Entrées
        $entre = Entres::where('id', $request->operation_id)->first();
        $type_op = $entre["type"];
        $entre_total = $entre["total"];
        $entre_annee_id = $entre["annee_id"];
        $entre_moi_id = $entre["moi_id"];
        // Soldes
        $solde = Soldes::where(['annee_id' => $entre_annee_id, 'moi_id' => $entre_moi_id])->first();
        $solde_actuel = $solde["solde_actuel"];
        if($type_op == 0)
        {
           $solde_actuel = $solde_actuel - $entre_total;
        }
        else
        {
            $solde_actuel = $solde_actuel + $entre_total;
        }
        $solde->solde_actuel = $solde_actuel;
        $solde->save();
        $entre->delete();
        $Factures = Factures::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["facture_id"] = $facture_id;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["entres"] = Entres::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfactures', $data);
    }

    public function delete_operation_2(Request $request)
    {
        // Entrées
        $entre = Entres::where('id', $request->operation_id)->first();
        $type_op = $entre["type"];
        $entre_total = $entre["total"];
        $entre_annee_id = $entre["annee_id"];
        $entre_moi_id = $entre["moi_id"];
        // Soldes
        $solde = Soldes::where(['annee_id' => $entre_annee_id, 'moi_id' => $entre_moi_id])->first();
        $solde_actuel = $solde["solde_actuel"];
        if($type_op == 0)
        {
           $solde_actuel = $solde_actuel - $entre_total;
        }
        else
        {
            $solde_actuel = $solde_actuel + $entre_total;
        }
        $solde->solde_actuel = $solde_actuel;
        $solde->save();
        $entre->delete();
        $Factures = Factures::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["facture_id"] = $facture_id;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["entres"] = Entres::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_entres', $data);
    }


    public function refresh_detailcredits(Request $request)
    {

        $Credits = Credits::where('id', $request->invitation_id)->first();
        $credit_id = Credits::where('id', $request->invitation_id)->first()["id"];
        $Remboursements = Remboursements::where(['credit_id' => $credit_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["credits"] = $Credits;
        $data["remboursements"] = $Remboursements;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detailcredits', $data);
    }


    public function refresh_detailpaies(Request $request)
    {

        $listespaies = Listespaies::where('id', $request->invitation_id)->first();
        $listespaie_id = Listespaies::where('id', $request->invitation_id)->first()["id"];
        $paiements = Paiements::where(['listespaie_id' => $listespaie_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["listespaies"] = $listespaies;
        $data["paiements"] = $paiements;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detailpaies', $data);
    }

    public function refresh_detail_listes_factures(Request $request)
    {

        $listesfactures = Listesfactures::where('id', $request->invitation_id)->first();
        $listesfactures_id = Listesfactures::where('id', $request->invitation_id)->first()["id"];
        $paiementsfactures = Paiementsfactures::where(['listesfactures_id' => $listesfactures_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["listesfactures"] = $listesfactures;
        $data["paiementsfactures"] = $paiementsfactures;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["activites"] = Activites::where(["etat" => 1])->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detail_listes_factures', $data);
    }

    public function refresh_programme(Request $request)
    {

        $postes = Postes::where('id', $request->invitation_id)->first();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["postes"] = $postes;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["activites"] = Activites::where(["etat" => 1])->get();
        $data["annees"] = Annees::get();
        $data["mois"] = Mois::get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_programme', $data);
    }


    public function get_detail_programme(Request $request)
    {
        $data["details"] = Prestations::where(["id" => $request->prestation_id, "annee_id" =>$request->annee_id, "moi_id" =>$request->moi_id])->first()["details"];
        $data["prestation_id"] = Prestations::where(["id" => $request->prestation_id, "annee_id" =>$request->annee_id, "moi_id" =>$request->moi_id])->first()["id"];
        return view('include.get_detail_programme', $data);
    }

    public function update_user_prestation(Request $request)
    {
        // Récupération des paramètres via les propriétés de la requête
        $prestationId = $request->prestation_id;
        $newUserId    = $request->user_id;
        $oldUserId    = $request->old_user_id;
        $date         = $request->date;
        $service      = $request->service;
        $horaire      = $request->horaire;

        // Vérification de base que tout est présent
        if (!$prestationId || !$newUserId || !$oldUserId || !$date || !$service || !$horaire) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètres manquants.'
            ]);
        }

        // Récupération de l'enregistrement Prestations
        $prestation = Prestations::find($prestationId);
        if (!$prestation) {
            return response()->json([
                'success' => false,
                'message' => 'Prestation introuvable.'
            ]);
        }

        // Décodage du champ 'details'
        $details = json_decode($prestation->details, true);
        if (!is_array($details)) {
            return response()->json([
                'success' => false,
                'message' => 'Le format des détails est invalide.'
            ]);
        }

        // Recherche de la ligne à modifier
        $found = false;
        foreach ($details as &$ligne) {
            if ($ligne['user_id'] == $oldUserId &&
                $ligne['date']    == $date &&
                $ligne['service'] == $service &&
                $ligne['horaire'] == $horaire) {

                // Mise à jour de l'utilisateur
                $ligne['user_id'] = $newUserId;
                $found = true;
                break;
            }
        }
        unset($ligne); // Supprimer la référence

        if (!$found) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune ligne correspondante trouvée pour ces critères.'
            ]);
        }

        // Sauvegarde des modifications
        $prestation->details = json_encode($details, JSON_UNESCAPED_UNICODE);
        $prestation->save();

        // Récupérer les informations du nouvel utilisateur
        $user = User::find($newUserId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur introuvable.'
            ]);
        }

        $groupe = Groupes::find($user->role);

        // Retour de la réponse exacte
        return response()->json([
            'success' => true,
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'matricule'  => $user->matricule ?? '',
                'groupe_nom' => $groupe ? $groupe->nom : '',
                'groupe_id'  => $groupe ? $groupe->id : null,
            ]
        ]);
    }

    public function update_ronde(Request $request)
    {
        $prestationId = $request->prestation_id;
        $rondeIndex   = $request->ronde_index;
        $heureDebut   = $request->heure_debut;  // "HH:MM"
        $heureFin     = $request->heure_fin;    // "HH:MM"
        $duree        = $request->duree;
        $userId       = $request->user_id;
        $date         = $request->date;
        $service      = $request->service;
        $horaire      = $request->horaire;

        if (!$prestationId || !$rondeIndex || !$heureDebut || !$heureFin || !$userId || !$date || !$service || !$horaire)
        {
            return response()->json(['success' => false, 'message' => 'Paramètres manquants.']);
        }

        $prestation = Prestations::find($prestationId);
        if (!$prestation)
        {
            return response()->json(['success' => false, 'message' => 'Prestation introuvable.']);
        }

        $details = json_decode($prestation->details, true);
        if (!is_array($details)) {
            return response()->json(['success' => false, 'message' => 'Format des détails invalide.']);
        }

        // Conversion au format HHhMM (petit h)
        $heureDebutStock = str_replace(':', 'h', $heureDebut);
        $heureFinStock   = str_replace(':', 'h', $heureFin);

        $found = false;
        foreach ($details as &$ligne) {
            if ($ligne['user_id'] == $userId &&
                $ligne['date'] == $date &&
                $ligne['service'] == $service &&
                $ligne['horaire'] == $horaire) {

                $key = 'ronde_a_' . $rondeIndex;

                if (!isset($ligne['pointages'])) {
                    $ligne['pointages'] = [
                        'entree' => ['etat' => 0, 'heure' => '', 'longitude' => '', 'latitude' => ''],
                        'ronde_a_1' => ['etat' => 0, 'heure_debut' => '', 'heure_fin' => '', 'duree_fin' => ''],
                        'ronde_a_2' => ['etat' => 0, 'heure_debut' => '', 'heure_fin' => '', 'duree_fin' => ''],
                        'ronde_a_3' => ['etat' => 0, 'heure_debut' => '', 'heure_fin' => '', 'duree_fin' => ''],
                        'sortie' => ['etat' => 0, 'heure' => '']
                    ];
                }

                if (isset($ligne['pointages'][$key])) {
                    $ligne['pointages'][$key]['heure_debut'] = $heureDebutStock;
                    $ligne['pointages'][$key]['heure_fin']   = $heureFinStock;
                    $ligne['pointages'][$key]['duree_fin']   = $duree;
                    $found = true;
                    break;
                }
            }
        }
        unset($ligne);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Aucune ligne correspondante.']);
        }

        $prestation->details = json_encode($details, JSON_UNESCAPED_UNICODE);
        $prestation->save();

        return response()->json(['success' => true, 'message' => 'Ronde mise à jour.']);
    }


    public function refresh_detailcredits_3(Request $request)
    {

        $Credits = Credits::where('id', $request->invitation_id)->first();
        $credit_id = Credits::where('id', $request->invitation_id)->first()["id"];
        $Remboursements = Remboursements::where(['credit_id' => $credit_id])->get();
        $Remboursements_2 = Creditts::where(['credit_id' => $credit_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["credits"] = $Credits;
        $data["remboursements"] = $Remboursements;
        $data["remboursements_2"] = $Remboursements_2;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detailcredits_3', $data);
    }

    public function refresh_detailcredits_2(Request $request)
    {

        $Credits = Credits::where('id', $request->invitation_id)->first();
        $credit_id = Credits::where('id', $request->invitation_id)->first()["id"];
        $Remboursements = Remboursements::where(['credit_id' => $credit_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["credits"] = $Credits;
        $data["remboursements"] = $Remboursements;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detailcredits_2', $data);
    }

    public function refresh_detailcredits_4(Request $request)
    {

        $Credits = Credits::where('id', $request->invitation_id)->first();
        $credit_id = Credits::where('id', $request->invitation_id)->first()["id"];
        $Remboursements = Remboursements::where(['credit_id' => $credit_id])->get();
        $Remboursements_2 = Creditts::where(['credit_id' => $credit_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["credits"] = $Credits;
        $data["remboursements"] = $Remboursements;
        $data["remboursements_2"] = $Remboursements_2;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_detailcredits_4', $data);
    }

    public function refresh_detailfactureas(Request $request)
    {
        $Factures = Factureas::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["devise"] = $Factures["devise"];
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["approvisionnements"] = Approvisionnements::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfactureas', $data);
    }

    public function refresh_detailfactureass(Request $request)
    {
        $Factures = Factureass::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["facture_id"] = $Factures["id"];
        $data["factures"] = $Factures;
        $data["devise"] = $Factures["devise"];
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        Session::put("facture_user_id", $Factures["id"]);
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["achats"] = Achats::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfactureass', $data);
    }

    public function refresh_reprise_article(Request $request)
    {
        // Achats
        $achats = Achats::where('id', $request->id)->first();
        $achats->type = 4;
        $quantite_p = $achats["quantite"];
        $article_id = $achats["article_id"];
        // Articles
        $articles = Articles::where('id', $article_id)->first();
        $quantite_a = $articles["stock"];
        $articles->stock = $quantite_a + $quantite_p;
        $articles->save();
        $achats->save();


        $Factures = Factureass::where('id', $achats["facture_id"])->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["achats"] = Achats::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfactureass', $data);
    }

    public function refresh_detailfacturess(Request $request)
    {
        $Factures = Facturess::where('id', $request->invitation_id)->first();
        $facture_id = $Factures["id"];
        $groupe_user_id = Auth::user()->role;
        $data["numero"] = $Factures["numero"];
        $data["factures"] = $Factures;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["sorties"] = Sorties::where(["facture_id" => $facture_id])->get();
        return view('include.refresh_detailfacturess', $data);
    }

    public function rapp(Request $request)
    {
        $data["decisions"] = Decisions::where('id', $request->invitation_id)->first();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 11;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.rap', $data);
    }


    public function bilan_1(Request $request)
    {
        $dataa["annees"] = Annees::where('id', $request->invitation_id)->first();
        $annees = Annees::where('id', $request->invitation_id)->first();
        $dataa["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $dataa["ressource_id_1"] = 11;
        $dataa["groupe_user_id"] = $groupe_user_id;
        $dataa["acces"] = Writes::where(["ressource_id" => $dataa["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $mois = Mois::get();
        $dd = Entres::where(["annee_id" => $annees->id, "type" => 1])->get();
        $total_general = 0;
        foreach ($dd as $d)
        {
            if($d->type == 0)
            {
                $total_general = $total_general + $d->total;
            }else{
                $total_general = $total_general - $d->total;
            }
        }
        if($total_general <= 0)
        {
            $total_general = $total_general * (-1);
        }
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'BILAN SOCIAL : ' . $annees->annees), 0, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'SORTIES TOTAL : ' . number_format($total_general, 2, ',', ' ') .'$'), 0, 0, 'R');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190, 1, iconv('UTF-8', 'Windows-1252', ''), 0, 1, 'C', true);
        $total_s = 0;
        foreach ($mois as  $m)
        {
            if(Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 1])->get()->count() != 0)
            {
                $t = 0;
                $ddd = Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 1])->get();
                foreach ($ddd as $dd)
                {
                    $t = $t + $dd->total;
                }
                $pdf->Ln(3);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(190, 8, iconv('UTF-8', 'Windows-1252', 'JOURNAL DE CAISSE ' . strtoupper($m->nom)), 0, 0, 'L', true);
                $pdf->Ln(6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'Windows-1252', 'SOLDE INITIAL : ' . number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"], 2, ',', ' ') .'$'), 0, 0, 'L');
                $pdf->SetTextColor(187, 33, 36);
                $pdf->Cell(95, 10, iconv('UTF-8', 'Windows-1252', 'SORTIES FINAL : ' . number_format($t, 2, ',', ' ') . '$'), 0, 0, 'R');
                $pdf->Ln(8);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L');
                $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', 'N°PIECE'), 1, 0, 'L');
                $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', 'LIBELLE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'ENTREE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SORTIE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SOLDE'), 1, 0, 'L');
                $total_s = 0;
                foreach ($ddd as $data)
                {
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', $data->date_creation), 1, 0, 'L');
                    $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', $data->n_piece), 1, 0, 'L');
                    $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', $data->libelle), 1, 0, 'L');
                    if($data->entree == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . '$'), 1, 0, 'C');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . 'Fc'), 1, 0, 'C');
                        }
                    }
                    if($data->sortie == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . 'Fc'), 1, 0, 'R');
                        }
                    }
                    $total_s = $total_s + $data->total;
                    $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($total_s, 2, ',', ' '). '$'), 1, 0, 'R');
                }
                $pdf->Ln(4);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'SOLDE FINAL : ' . number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_actuel"], 2, ',', ' ') . '$'), 0, 0, 'R');
                $pdf->Ln(8);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(190, 1, iconv('UTF-8', 'Windows-1252', ''), 0, 1, 'C', true);
            }
        }
        $nom_fichier =  "sorties_$annees->annees". ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
        // return view('include.bilan_1', $dataa);
    }

    public function bilan_2(Request $request)
    {
        $dataa["annees"] = Annees::where('id', $request->invitation_id)->first();
        $annees = Annees::where('id', $request->invitation_id)->first();
        $dataa["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $dataa["ressource_id_1"] = 11;
        $dataa["groupe_user_id"] = $groupe_user_id;
        $dataa["acces"] = Writes::where(["ressource_id" => $dataa["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $mois = Mois::get();
        $dd = Entres::where(["annee_id" => $annees->id, "type" => 0])->get();
        $total_general = 0;
        foreach ($dd as $d)
        {
            if($d->type == 0)
            {
                $total_general = $total_general + $d->total;
            }else{
                $total_general = $total_general - $d->total;
            }
        }
        if($total_general <= 0)
        {
            $total_general = $total_general * (-1);
        }
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'BILAN SOCIAL : ' . $annees->annees), 0, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'ENTREES TOTAL : ' . number_format($total_general, 2, ',', ' ') .'$'), 0, 0, 'R');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190, 1, iconv('UTF-8', 'Windows-1252', ''), 0, 1, 'C', true);
        foreach ($mois as  $m)
        {
            if(Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 0])->get()->count() != 0)
            {
                $t = 0;
                $ddd = Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 0])->get();
                foreach ($ddd as $dd)
                {
                    $t = $t + $dd->total;
                }
                $pdf->Ln(3);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(190, 8, iconv('UTF-8', 'Windows-1252', 'JOURNAL DE CAISSE ' . strtoupper($m->nom)), 0, 0, 'L', true);
                $pdf->Ln(6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'Windows-1252', 'SOLDE INITIAL : ' . number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"], 2, ',', ' ') .'$'), 0, 0, 'L');
                $pdf->SetTextColor(0, 128, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'Windows-1252', 'ENTREES FINAL : ' . number_format($t, 2, ',', ' ') . '$'), 0, 0, 'R');
                $pdf->Ln(8);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L');
                $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', 'N°PIECE'), 1, 0, 'L');
                $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', 'LIBELLE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'ENTREE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SORTIE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SOLDE'), 1, 0, 'L');
                $total_s = 0;
                foreach ($ddd as $data)
                {
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', $data->date_creation), 1, 0, 'L');
                    $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', $data->n_piece), 1, 0, 'L');
                    $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', $data->libelle), 1, 0, 'L');
                    if($data->entree == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . '$'), 1, 0, 'C');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . 'Fc'), 1, 0, 'C');
                        }
                    }
                    if($data->sortie == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . 'Fc'), 1, 0, 'R');
                        }
                    }
                    $total_s = $total_s + $data->total;
                    $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($total_s, 2, ',', ' '). '$'), 1, 0, 'R');
                }
                $pdf->Ln(4);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'SOLDE FINAL : ' . number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_actuel"], 2, ',', ' ') . '$'), 0, 0, 'R');
                $pdf->Ln(8);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(190, 1, iconv('UTF-8', 'Windows-1252', ''), 0, 1, 'C', true);
            }
        }
        $nom_fichier =  "entrees_$annees->annees" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
        // return view('include.bilan_2', $dataa);
    }

    public function bilan_3(Request $request)
    {
        $dataa["annees"] = Annees::where('id', $request->invitation_id)->first();
        $annees = Annees::where('id', $request->invitation_id)->first();
        $dataa["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $dataa["ressource_id_1"] = 11;
        $dataa["groupe_user_id"] = $groupe_user_id;
        $dataa["acces"] = Writes::where(["ressource_id" => $dataa["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $mois = Mois::get();
        $listes_des_soldes = Soldes::where(["annee_id" => $annees->id])->get();
        $somme_listes_des_soldes = 0;
        foreach ($listes_des_soldes as $v_s)
        {
            $somme_listes_des_soldes = $somme_listes_des_soldes + $v_s->solde_initial;
        }
        $dd = Entres::where(["annee_id" => $annees->id])->get();
        $total_general = $somme_listes_des_soldes;
        foreach ($dd as $d)
        {
            if($d->type == 0)
            {
                $total_general = $total_general + $d->total;
            }else
            {
                $total_general = $total_general - $d->total;
            }
        }
        if($total_general <= 0)
        {
            $total_general = $total_general * (-1);
        }
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'BILAN SOCIAL : ' . $annees->annees), 0, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'TOTAL : ' . number_format($total_general, 2, ',', ' ') .'$'), 0, 0, 'R');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190, 1, iconv('UTF-8', 'Windows-1252', ''), 0, 1, 'C', true);
        $total_s = 0;
        foreach ($mois as  $m)
        {
            if(Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id])->get()->count() != 0)
            {
                $t = Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"];
                $ddd = Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id])->get();
                foreach ($ddd as $dd)
                {
                    if($dd->type == 0)
                    {
                        $t = $t + $dd->total;
                    }else
                    {
                        $t = $t - $dd->total;
                    }
                }
                $pdf->Ln(3);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(190, 8, iconv('UTF-8', 'Windows-1252', 'JOURNAL DE CAISSE ' . strtoupper($m->nom)), 0, 0, 'L', true);
                $pdf->Ln(6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(190, 10, iconv('UTF-8', 'Windows-1252', 'SOLDE INITIAL : ' . number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"], 2, ',', ' ') .'$'), 0, 0, 'L');
                $pdf->Ln(8);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L');
                $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', 'N°PIECE'), 1, 0, 'L');
                $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', 'LIBELLE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'ENTREE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SORTIE'), 1, 0, 'L');
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', 'SOLDE'), 1, 0, 'L');
                $i = 0;
                $sl = Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"];
                $t_e = 0;
                $t_s = 0;
                foreach ($ddd as $data)
                {
                    if($data->type == 0)
                    {
                        $sl = $sl + $data->total;
                        $t_e = $t_e + $data->total;
                    }else
                    {
                        $sl = $sl - $data->total;
                        $t_s = $t_s + $data->total;
                    }
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', $data->date_creation), 1, 0, 'L');
                    $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', $data->n_piece), 1, 0, 'L');
                    $pdf->Cell(71, 5, iconv('UTF-8', 'Windows-1252', $data->libelle), 1, 0, 'L');
                    if($data->entree == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->entree, 2, ',', ' ') . 'Fc'), 1, 0, 'R');
                        }
                    }
                    if($data->sortie == 0)
                    {
                        $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', '-'), 1, 0, 'C');
                    }
                    else
                    {
                        if($data->devise == 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                        else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($data->sortie, 2, ',', ' ') . 'Fc'), 1, 0, 'R');
                        }
                    }
                    if($data->type == 0)
                    {
                        $pdf->SetTextColor(0, 128, 0);
                        if($sl <= 0)
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format((-1) * $sl, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($sl, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                    }
                    else
                    {
                        $pdf->SetTextColor(187, 33, 36);
                        if($sl <= 0){
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format((-1) * $sl, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', number_format($sl, 2, ',', ' ') . '$'), 1, 0, 'R');
                        }
                    }
                    $i++;
                }
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(0, 0, 0);
                $pdf->Cell(106, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'C',true);
                $pdf->SetTextColor(0, 128, 0);
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', (number_format($t_e, 2, ',', ' ') . '$')), 1, 0, 'R');
                $pdf->SetTextColor(187, 33, 36);
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', (number_format($t_s, 2, ',', ' ') . '$')), 1, 0, 'R');
                $pdf->SetTextColor(91, 192, 222);
                $pdf->Cell(28, 5, iconv('UTF-8', 'Windows-1252', (number_format($t, 2, ',', ' ') . '$')), 1, 0, 'R');
                $pdf->Ln(15);
            }
        }
        $nom_fichier =  "entrees_sorties_$annees->annees" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
        // return view('include.bilan_3', $dataa);
    }

    public function get_liste_credit(Request $request)
    {
        $utilisateurs = User::where(["etat" => 1])->get();
        $groupes= Groupes::where(["etat" => 1])->get();
        $invitations = Invitations::where(["etat" => 1])->get();
        $decisions = Decisions::where(["etat" => 1])->get();
        $factures = Factures::where(["user_id" => Auth::user()->id])->get();
        $credits = Credits::where(["user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $factures = Factures::get();
            $credits = Credits::get();
        }
        $credit_total = 0;
        $r_total = 0;
        foreach ($credits as $c_t)
        {
            $rts = Remboursements::where(['credit_id' => $c_t->id])->get();
            $t_entree = 0;
            foreach ($rts as $c_tt)
            {
                $t_entree = $t_entree + $c_tt->entree;
            }
            if($c_t->entree != $t_entree)
            {
                $credit_total = $credit_total + $c_t->entree;
                foreach ($rts as $c_tt)
                {
                    $r_total = $r_total + $c_tt->entree;
                }
            }
        }
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'LISTE DES CREDITS'), 0, 1, 'C', true);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetTextColor(91, 192, 222);
        $pdf->Cell(63.33333333333333, 5, iconv('UTF-8', 'Windows-1252', 'Crédit total : ' . number_format($credit_total , 2, ',', ' ') .'$'), 1, 0, 'L');
        $pdf->SetTextColor(0, 128, 0);
        $pdf->Cell(63.33333333333333, 5, iconv('UTF-8', 'Windows-1252', 'Remboursement total : ' . number_format($r_total , 2, ',', ' ') .'$'), 1, 0, 'L');
        $pdf->SetTextColor(187, 33, 36);
        $pdf->Cell(63.33333333333333, 5, iconv('UTF-8', 'Windows-1252', ' Reste total : ' . number_format($credit_total - $r_total , 2, ',', ' ') .'$'), 1, 0, 'L');
        $pdf->Ln(10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L');
        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT'), 1, 0, 'L');
        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', 'TYPE'), 1, 0, 'L');
        $pdf->Cell(58, 5, iconv('UTF-8', 'Windows-1252', 'LIBELLE'), 1, 0, 'L');
        $pdf->Cell(37, 5, iconv('UTF-8', 'Windows-1252', 'CREDIT'), 1, 0, 'L');
        foreach ($credits as $data)
        {
            $t = 0;
            $cd = Remboursements::where(['credit_id' => $data->id])->get();
            foreach ($cd as $e)
            {
                $t = $t + $e->entree;
            }
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', $data->date_credit), 1, 0, 'L');
            $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', $data->nom_credit), 1, 0, 'L');
            $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', $data->type), 1, 0, 'L');
            $pdf->Cell(58, 5, iconv('UTF-8', 'Windows-1252', $data->libelle), 1, 0, 'L');
            $pdf->Cell(37, 5, iconv('UTF-8', 'Windows-1252',  number_format($t, 2, ',', ' ') .'$' . ' / ' . number_format($data->entree, 2, ',', ' ') .'$'), 1, 0, 'L');
        }
        $nom_fichier =  "Liste_de_credit" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
    }

    public function get_liste_employe(Request $request)
    {
        $utilisateurs = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'LISTE DES UTILISATEURS'), 0, 1, 'C', true);
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', ' Utilisateurs total : ' . $utilisateurs->count()), 0, 0, 'R');
        $pdf->Ln(7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', 'NOM'), 1, 0, 'L');
        $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', 'SALAIRE'), 1, 0, 'L');
        $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', 'EMAIL'), 1, 0, 'L');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', 'TELEPHONE'), 1, 0, 'L');
        $pdf->Cell(55, 5, iconv('UTF-8', 'Windows-1252', 'ROLE / FONCTION'), 1, 0, 'L');
        foreach ($utilisateurs as $data)
        {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', $data->name), 1, 0, 'L');
            if($data->devise == 0)
            {
                $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', number_format($data->salaire, 2, ',', ' ') .'$'), 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(20, 5, iconv('UTF-8', 'Windows-1252', number_format($data->salaire, 2, ',', ' ') .'Fc'), 1, 0, 'L');
            }
            $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', $data->email), 1, 0, 'L');
            $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', $data->phone), 1, 0, 'L');
            if ($groupes->count() != 0)
            {
                $pdf->Cell(55, 5, iconv('UTF-8', 'Windows-1252', Groupes::where('id', $data->role)->first()["nom"]), 1, 0, 'L');
            }
        }
        $nom_fichier =  "Liste_des_utilisateurs" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
    }


    public function get_liste_qr_code(Request $request)
    {
        $postes = Postes::where(["supprimer" => 0])->get();
        $pdf = new FPDF();
        foreach ($postes as $data)
        {
            $key = base64_encode('poste_code');
            $value = base64_encode($data->code);
            $url = route('login') . '?' . http_build_query([$key => $value]);

            // Génération du QR code
            $builder = new Builder(
                writer: new PngWriter(),
                data: $url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 1000,
                margin:15
            );

            $result = $builder->build();

            // Définir le nom et le chemin du fichier
            $fileName = "./storage/images/fichiers/" . 'qrcode_' . time() . '.png';


            $filePath = ($fileName);

            // Sauvegarder l'image
            $result->saveToFile($filePath);

            $pst = Postes::where(["id" => $data->id])->first();
            $pst->qr_code = $filePath;
            $pst->save();


            $pdf->AddPage();
            $pdf->Image($fileName, 30, 60, 150, 150);
            $pdf->SetFont('Arial', 'B', 15);


            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'NOM DU POSTE : ' . strtoupper($data->nom)), 0, 0, 'L');
        }
        $nom_fichier =  "Liste_des_qr_code" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
    }


    public function get_one_qr_code(Request $request)
    {
        $postes = Postes::where(["supprimer" => 0])->get();
        $nom_poste = "";
        $lieu_poste = "";
        $pdf = new FPDF();
        foreach ($postes as $data)
        {
            if($data->id == $request->poste_id)
            {
                $key = base64_encode('poste_code');
                $value = base64_encode($data->code);
                $url = route('login') . '?' . http_build_query([$key => $value]);
                $nom_poste = strtoupper($data->nom);
                $lieu_poste = Lieux::where(["id" => $data->lieuxe_id])->first()["nom"];
                // Génération du QR code
                $builder = new Builder(
                    writer: new PngWriter(),
                    data: $url,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 1000,
                    margin:15
                );
                $result = $builder->build();

                // Définir le nom et le chemin du fichier
                $fileName = "./storage/images/fichiers/" . 'qrcode_' . time() . '.png';


                $filePath = ($fileName);

                // Sauvegarder l'image
                $result->saveToFile($filePath);

                $pst = Postes::where(["id" => $data->id])->first();
                $pst->qr_code = $filePath;
                $pst->save();


                $pdf->AddPage();
                $pdf->Image($fileName, 30, 60, 150, 150);
                $pdf->SetFont('Arial', 'B', 15);


                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'NOM DU POSTE : ' . strtoupper($data->nom)), 0, 0, 'L');
            }
        }
        $nom_fichier =  $nom_poste .' '. $lieu_poste . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
    }

    public function get_liste_client(Request $request)
    {
        if(Auth::user()->role == 0)
        {
             $clients = Clients::where(["etat" => 1])->get();
        }
        elseif(Auth::user()->role != 0)
        {
            $clients = Clients::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        }
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(40);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->Cell(190, 15, iconv('UTF-8', 'Windows-1252', 'LISTE DES CLIENS'), 0, 1, 'C', true);
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', ' Clients total : ' . $clients->count()), 0, 0, 'R');
        $pdf->Ln(7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', 'NOM'), 1, 0, 'L');
        $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', 'EMAIL'), 1, 0, 'L');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', 'TELEPHONE'), 1, 0, 'L');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', 'TYPE'), 1, 0, 'L');
        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', 'ACTIVITE'), 1, 0, 'L');
        foreach ($clients as $data)
        {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', $data->name), 1, 0, 'L');
            $pdf->Cell(45, 5, iconv('UTF-8', 'Windows-1252', $data->email), 1, 0, 'L');
            $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', $data->phone), 1, 0, 'L');
            if ($data->type == 0)
            {
                $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', "Privé"), 1, 0, 'L');
            }
            else
            {
                $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', "Entreprise"), 1, 0, 'L');
            }
            $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', Activites::where('id', $data->activite_id)->first()["nom"]), 1, 0, 'L');
        }
        $nom_fichier =  "Liste_des_clients" . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;
    }

    public function get_print_paie(Request $request)
    {

        $listespaies = Listespaies::where('id', $request->invitation_id)->first();
        $listespaie_id = Listespaies::where('id', $request->invitation_id)->first()["id"];
        $paiements = Paiements::where(['listespaie_id' => $listespaie_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $utilisateurs = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($paiements as $f) {
            if ($f->paye == 1) {
                if ($f->devise == 0) {
                    $total_3 =  $total_3 + $f->paie;
                } else {
                    $total_3 =  ($total_3 + ($f->paie / $f->taux));
                }
            }
        }
        foreach ($paiements as $f) {
            if ($f->paye == 0) {
                if ($f->devise == 0) {
                    $total_4 =  $total_4 + $f->paie;
                } else {
                    $total_4 =  round($total_4 + ($f->paie / $f->taux));
                }
            }
        }
        $total_5 = $total_3 + $total_4;
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->AddPage();
        // $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
        $nn = 1;
        foreach ($paiements as $data)
        {
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'LISTE DE PAIE : ' . strtoupper(Mois::where(["id" => $listespaies["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listespaies["annee_id"]])->first()["annees"]), 1, 0, 'C', true);
            $pdf->Ln(5);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'NOM SOCIETE'), 1, 0, 'L');
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'AFRICTECH CONSTRUCTION'), 1, 0, 'R');
            $pdf->Ln(5);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'NOM'), 1, 0, 'L');
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', User::where('id', $data->user_id)->first()["name"]), 1, 0, 'R');
            $pdf->Ln(5);
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'ROLE / FONCTION'), 1, 0, 'L');
            $role_id = User::where('id', $data->user_id)->first()["role"];
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252',  Groupes::where('id', $role_id)->first()["nom"]), 1, 0, 'R');
            $pdf->Ln(5);
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'SALAIRE'), 1, 0, 'L');
            if($data->devise == 0)
            {
                $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', number_format($data->paie, 2, ',', ' ') . '$' . ' / ' .  number_format($data->montant, 2, ',', ' ') .'$'), 1, 0, 'R');
            }else
            {
                $paies = Paies::where(["user_id" => $data->user_id, "paiement_id" => $data->paiement_id])->get();
                $t_p = 0;
                foreach ($paies as $p)
                {
                    $t_p = $t_p + $p->montant;
                }
                $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', number_format($t_p, 2, ',', ' ') . ' / ' . number_format($data->montant, 2, ',', ' ') . 'Fc'), 1, 0, 'R');
            }
            $pdf->Ln(8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'SIGNATURE EMPLOYEUR'), 0, 0, 'L');
            $pdf->Cell(95, 5, iconv('UTF-8', 'Windows-1252', 'SIGNATURE EMPLOYE'), 0, 0, 'R');

            $pdf->Ln(15);
        }
        $nom_fichier = 'LISTE DE PAIE ' . strtoupper(Mois::where(["id" => $listespaies["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listespaies["annee_id"]])->first()["annees"]  . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;

    }

    /**
        * Convertit une chaîne UTF‑8 vers l'encodage CP437 (imprimante thermique)
    */
    function toPrinterEncoding($string)
    {
        // Convertir les caractères UTF-8 vers CP437
        // TRANSLIT tente de remplacer les caractères manquants par des approximations
        $converted = @iconv('UTF-8', 'CP437//TRANSLIT', $string);
        if ($converted === false) {
            // En cas d'échec, on ignore les caractères problématiques
            $converted = iconv('UTF-8', 'CP437//IGNORE', $string);
        }
        return $converted;
    }

    // public function print_facture(Request $request)
    // {
    //     // ---------- 1. Récupération centralisée ----------
    //     $facture = Factureass::find($request->facture_id);
    //     if (!$facture) return response()->json(['error' => 'Facture introuvable'], 404);

    //     $achats = Achats::where('facture_id', $facture->id)->get();
    //     if ($achats->isEmpty()) return response()->json(['error' => 'Aucun achat'], 404);

    //     $articlesIds = $achats->pluck('article_id')->unique();
    //     $clientsIds = $achats->pluck('client_id')->filter(fn($id) => $id > 0)->unique();
    //     $articles = Articles::whereIn('id', $articlesIds)->get()->keyBy('id');
    //     $activiteId = $articles->first()?->activite_id;
    //     $activite = Activites::find($activiteId);
    //     $clients = Clients::whereIn('id', $clientsIds)->get()->keyBy('id');

    //     $mesuresIds = $articles->pluck('mesure_id')->filter()->unique();
    //     $mesures = Mesures::whereIn('id', $mesuresIds)->get()->keyBy('id');

    //     $taux = $facture->taux;
    //     $tva = $facture->tva;
    //     $montant_recu = $facture->montant_recu;
    //     $devise_recu = $facture->devise_recu;
    //     $devise = $facture->devise;
    //     $payer = $facture->payer;
    //     $date_creation = $facture->created_at;
    //     $date_creation = explode(" ", $date_creation);
    //     $mode_de_paiement = $facture->mode_de_paiement;
    //     $cdf_montant_payer = 0;
    //     $usd_montant_payer = 0;

    //     $lignes = [];
    //     $total_general = 0;
    //     $nom_client_final = '';
    //     $devise_generale = '';

    //     foreach ($achats as $achat)
    //     {
    //         $article = $articles[$achat->article_id] ?? null;
    //         if (!$article) continue;

    //         $total_general += $achat->total;

    //         if ($devise_generale === '') {
    //             $devise_generale = ($achat->devise == 0) ? 'USD' : 'CDF';
    //         }

    //         if ($achat->client_id == 0) {
    //             $nom_client_final = $achat->libelle;
    //         } else {
    //             $nom_client_final = $clients[$achat->client_id]->name ?? $nom_client_final;
    //         }

    //         $lignes[] = [
    //             'nom_article' => $article->nom_article,
    //             'quantite' => $achat->quantite,
    //             'prix_unitaire' => $achat->prix_unitaire,
    //             'total_ligne' => $achat->total,
    //         ];
    //     }

    //     // Précalcul des équivalents pour la différence
    //     if ($devise_generale == 'USD') {
    //         $total_en_usd = $total_general;
    //         $total_en_cdf = $total_general * $taux;
    //     } else {
    //         $total_en_cdf = $total_general;
    //         $total_en_usd = $total_general / $taux;
    //     }

    //     // Calcul des différences
    //     if ($devise_recu == 0) {
    //         $diff_usd = $total_en_usd - $montant_recu;
    //         $diff_cdf = 0;
    //     } else
    //     {
    //         $diff_cdf = $total_en_cdf - $montant_recu;
    //         $diff_usd = 0;
    //     }

    //     $diff_usd_formate = number_format(abs($diff_usd), 2, ',', ' ') . ' USD';
    //     $diff_cdf_formate = number_format(abs($diff_cdf), 2, ',', ' ') . ' CDF';

    //     if($mode_de_paiement == 1) {
    //         $paiement_libelle = 'CASH';
    //     } elseif($mode_de_paiement == 2) {
    //         $paiement_libelle = 'Mobile money';
    //     } else if($mode_de_paiement == 3) {
    //         $paiement_libelle = 'Bank';
    //     } else {
    //         $paiement_libelle = 'CASH';
    //     }

    //     // ========== AJOUT : Calcul anticipé des montants pour le QR code ==========
    //     if ($devise_generale == 'USD')
    //     {
    //         $usd_montant_payer = $total_general;
    //         $cdf_montant_payer = $total_general * $taux;
    //     }
    //     else
    //     {
    //         $cdf_montant_payer = $total_general;
    //         $usd_montant_payer = $total_general / $taux;
    //     }
    //     // ========== FIN DU CALCUL ANTICIPÉ ==========

    //     // ========== GÉNÉRATION DU QR CODE (avant le PDF) ==========
    //     $key_1 = base64_encode('facture_id');
    //     $value_1 = base64_encode($request->facture_id);
    //     $key_2 = base64_encode('cdf_montant');
    //     $value_2 = base64_encode($cdf_montant_payer);
    //     $key_3 = base64_encode('usd_montant');
    //     $value_3 = base64_encode($usd_montant_payer);
    //     $url = route('paiement') . '?' . http_build_query([$key_1 => $value_1, $key_2 => $value_2, $key_3 => $value_3]);

    //     $builder = new Builder(
    //         writer: new PngWriter(),
    //         data: $url,
    //         encoding: new Encoding('UTF-8'),
    //         errorCorrectionLevel: ErrorCorrectionLevel::High,
    //         size: 1000,
    //         margin:15
    //     );
    //     $result = $builder->build();

    //     $fileName = "./storage/images/fichiers/" . 'qrcode_' . time() . '.png';
    //     $filePath = ($fileName);
    //     $result->saveToFile($filePath);
    //     // ========== FIN GÉNÉRATION QR CODE ==========

    //     // ---------- 2. PDF format 72 mm ----------
    //     $pdf = new \FPDF('P', 'mm', [72, 300]);
    //     $pdf->AddPage();
    //     $pdf->SetLeftMargin(3);
    //     $pdf->SetRightMargin(3);
    //     $largeur_utile = 66;
    //     $marge_gauche = 3;

    //     // Logo et QR code sur la même ligne
    //     $y_depart = 3;
    //     $logo_largeur = 15;
    //     $logo_hauteur = 15;
    //     $qr_largeur = 12;
    //     $qr_hauteur = 12;

    //     // Positionnement du logo (à gauche)
    //     $pdf->Image($activite->logo, $marge_gauche, $y_depart, $logo_largeur, $logo_hauteur);
    //     // Positionnement du QR code (à droite, sur la même ligne)
    //     $pdf->Image($fileName, $marge_gauche + $largeur_utile - $qr_largeur, $y_depart, $qr_largeur, $qr_hauteur);

    //     $y_apres_logo = $y_depart + max($logo_hauteur, $qr_hauteur) + 2;

    //     // Activité centrée
    //     $pdf->SetY($y_apres_logo);
    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $nom_activite = $activite ? $activite->nom : '';
    //     $nom_description = $activite ? $activite->description : '';
    //     $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', strtoupper($nom_activite)), 0, 1, 'C');

    //     // Ligne de points sous l'activité
    //     $pdf->SetFont('Arial', '', 7);
    //     $largeur_point = $pdf->GetStringWidth('.');
    //     $nb_points = (int)($largeur_utile / $largeur_point);
    //     $ligne_points = str_repeat('.', $nb_points);
    //     $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', $ligne_points), 0, 1, 'C');

    //     // Texte "Munua vs la katangaise"
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->Cell($largeur_utile, 5, iconv('UTF-8', 'Windows-1252', $nom_description), 0, 1, 'C');

    //     // Date
    //     $pdf->SetFont('Arial', '', 7);
    //     $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', 'Date : ' . explode("-", $date_creation[0])[2] . '/' . explode("-", $date_creation[0])[1] . '/' . explode("-", $date_creation[0])[0] .' à '. $date_creation[1]), 0, 1, 'L');
    //     $pdf->Ln(1);

    //     // Facture / Client
    //     $pdf->SetFont('Arial', 'B', 7);
    //     $col_gauche = 33;
    //     $col_droite = 33;
    //     $pdf->Cell($col_gauche, 4, iconv('UTF-8', 'Windows-1252', 'Facture : ' . strtoupper($facture->numero)), 0, 0, 'L');
    //     $pdf->Cell($col_droite, 4, iconv('UTF-8', 'Windows-1252', 'Client : ' . $nom_client_final), 0, 1, 'R');

    //     // Caissier
    //     $pdf->SetFont('Arial', 'B', 7);
    //     $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', 'Caissier(ère) : ' . User::where('id', $facture->user_id)->first()['name']), 0, 1, 'R');

    //     // Original
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Original'), 0, 1, 'C');

    //     // Espace avant tableau
    //     $pdf->Ln(3);

    //     // --- Tableau ---
    //     $col_article = 28;
    //     $col_qte = 10;
    //     $col_pu = 14;
    //     $col_total = 14;

    //     $pdf->SetLineWidth(0.6);
    //     $y1 = $pdf->GetY();
    //     $pdf->Line($marge_gauche, $y1, $marge_gauche + $largeur_utile, $y1);
    //     $pdf->SetLineWidth(0.2);
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', 'B', 7);
    //     $pdf->Cell($col_article, 5, iconv('UTF-8', 'Windows-1252', 'ITEM'), 0, 0, 'L');
    //     $pdf->Cell($col_qte, 5, iconv('UTF-8', 'Windows-1252', 'QTE'), 0, 0, 'C');
    //     $pdf->Cell($col_pu, 5, iconv('UTF-8', 'Windows-1252', 'PRIX'), 0, 0, 'C');
    //     $pdf->Cell($col_total, 5, iconv('UTF-8', 'Windows-1252', 'MONTANT'), 0, 1, 'C');

    //     $pdf->SetLineWidth(0.6);
    //     $y2 = $pdf->GetY();
    //     $pdf->Line($marge_gauche, $y2, $marge_gauche + $largeur_utile, $y2);
    //     $pdf->SetLineWidth(0.2);
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', '', 7);
    //     foreach ($lignes as $ligne)
    //     {
    //         $nom = $ligne['nom_article'];
    //         if (mb_strlen($nom) > 18) $nom = mb_substr($nom, 0, 16) . '..';
    //         $quantite_str = $ligne['quantite'];
    //         $prix_str = number_format($ligne['prix_unitaire'], 2, ',', ' ');
    //         $total_str = number_format($ligne['total_ligne'], 2, ',', ' ');

    //         $pdf->Cell($col_article, 5, iconv('UTF-8', 'Windows-1252', $nom), 0, 0, 'L');
    //         $pdf->SetFont('Arial', 'B', 7);
    //         $pdf->Cell($col_qte, 5, iconv('UTF-8', 'Windows-1252', $quantite_str), 0, 0, 'C');
    //         $pdf->SetFont('Arial', '', 7);
    //         $pdf->Cell($col_pu, 5, iconv('UTF-8', 'Windows-1252', $prix_str), 0, 0, 'C');
    //         $pdf->Cell($col_total, 5, iconv('UTF-8', 'Windows-1252', $total_str), 0, 1, 'R');
    //     }

    //     $pdf->SetLineWidth(0.6);
    //     $y3 = $pdf->GetY();
    //     $pdf->Line($marge_gauche, $y3, $marge_gauche + $largeur_utile, $y3);
    //     $pdf->SetLineWidth(0.2);
    //     $pdf->Ln(2);

    //     // Montant HT
    //     $pdf->SetFont('Arial', 'B', 7);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $total_formate = number_format($total_general, 2, ',', ' ');
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant HT (' . $devise_generale . ')'), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $total_formate), 0, 1, 'R');

    //     // Première ligne de conversion (gris clair)
    //     $pdf->SetFont('Arial', '', 6);
    //     $pdf->SetTextColor(128, 128, 128);
    //     $taux_formate = number_format($taux, 0, ',', ' ');

    //     // ========== BLOC CONVERSION (sans le QR code) ==========
    //     if ($devise_generale == 'USD') {
    //         $equivalent_cdf = $total_general * $taux;
    //         $equivalent_formate = number_format($equivalent_cdf, 2, ',', ' ');
    //         $texte_equivalent = $equivalent_formate . " CDF = " . number_format($total_general, 2, ',', ' ') . " USD (taux 1 USD = " . $taux_formate . " CDF)";
    //         // Ne pas redéfinir $usd_montant_payer et $cdf_montant_payer (déjà faits)
    //     } else {
    //         $equivalent_usd = $total_general / $taux;
    //         $equivalent_formate = number_format($equivalent_usd, 2, ',', ' ');
    //         $texte_equivalent = $equivalent_formate . " USD = " . number_format($total_general, 2, ',', ' ') . " CDF (taux 1 USD = " . $taux_formate . " CDF)";
    //     }
    //     $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', $texte_equivalent), 2, 'L');
    //     $pdf->Ln(1);

    //     // (L'ancien bloc QR CODE a été supprimé)

    //     $pdf->SetFont('Arial', 'B', 15);

    //     // --- Condition : si montant_recu == 0 OU payer == 0 ---
    //     if ($montant_recu == 0)
    //     {
    //         $nom_activite_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $activite->nom ?? 'activite');
    //         $nom_fichier = 'Facture_' . $nom_activite_clean . '_' . $facture->numero . '.pdf';
    //         $pdf->Output('F', $nom_fichier);
    //         return response()->json([[$nom_fichier, number_format($cdf_montant_payer, 2, ',', ' '), number_format($usd_montant_payer, 2, ',', ' '), $tva, $taux], $payer]);
    //     }

    //     // ---------------------------------------------------------
    //     // Suite uniquement si montant_recu > 0 et < total_general
    //     // ---------------------------------------------------------

    //     // TVA devise d'origine
    //     $montant_tva = $total_general * ($tva / 100);
    //     $montant_tva_formate = number_format($montant_tva, 0, ',', ' ') . ' ' . $devise_generale;
    //     $libelle_tva = 'TVA (' . $devise_generale . ') ' . $tva . '%';
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', $libelle_tva), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_tva_formate), 0, 1, 'R');

    //     // TVA autre devise
    //     $autre_devise = ($devise_generale == 'USD') ? 'CDF' : 'USD';
    //     if ($devise_generale == 'USD') {
    //         $montant_tva_autre = $montant_tva * $taux;
    //         $montant_tva_autre_formate = number_format($montant_tva_autre, 2, ',', ' ') . ' ' . $autre_devise;
    //     } else {
    //         $montant_tva_autre = $montant_tva / $taux;
    //         $montant_tva_autre_formate = number_format($montant_tva_autre, 2, ',', ' ') . ' ' . $autre_devise;
    //     }
    //     $libelle_tva_autre = 'TVA (' . $autre_devise . ') ' . $tva . '%';
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', $libelle_tva_autre), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_tva_autre_formate), 0, 1, 'R');

    //     // Montant reçu (USD)
    //     $montant_recu_usd = ($devise_recu == 0) ? number_format($montant_recu, 2, ',', ' ') . ' USD' : '0 USD';
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant reçu (USD)'), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_recu_usd), 0, 1, 'R');

    //     // Montant reçu (CDF)
    //     $montant_recu_cdf = ($devise_recu == 1) ? number_format($montant_recu, 2, ',', ' ') . ' CDF' : '0 CDF';
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant reçu (CDF)'), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_recu_cdf), 0, 1, 'R');

    //     // Différence USD
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Différence (USD)'), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $diff_usd_formate), 0, 1, 'R');

    //     // Différence CDF
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Différence (CDF)'), 0, 0, 'L');
    //     $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $diff_cdf_formate), 0, 1, 'R');

    //     // --- Lignes pointillées et Montants TTC ---
    //     $pdf->SetFont('Arial', '', 8);
    //     $largeur_point = $pdf->GetStringWidth('.');
    //     $nb_points = (int)($largeur_utile / $largeur_point);
    //     $ligne_points_fin = str_repeat('.', $nb_points);
    //     $hauteur_point = 5;

    //     $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
    //     $pdf->Ln(1);

    //     $ttc = $total_general + ($total_general * $tva / 100);
    //     $ttc_formate = number_format($ttc, 0, ',', ' ');
    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Montant TTC (' . $devise_generale . ') : ' . $ttc_formate), 0, 1, 'R');
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', '', 8);
    //     $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
    //     $pdf->Ln(1);

    //     $autre_devise_ttc = ($devise_generale == 'USD') ? 'CDF' : 'USD';
    //     if ($devise_generale == 'USD') {
    //         $ttc_autre = $ttc * $taux;
    //         $ttc_autre_formate = number_format($ttc_autre, 0, ',', ' ');
    //     } else {
    //         $ttc_autre = $ttc / $taux;
    //         $ttc_autre_formate = number_format($ttc_autre, 2, ',', ' ');
    //     }
    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Montant TTC (' . $autre_devise_ttc . ') : ' . $ttc_autre_formate), 0, 1, 'R');
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', '', 8);
    //     $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
    //     $pdf->Ln(1);

    //     // Deuxième ligne de conversion (dupliquée)
    //     $pdf->SetFont('Arial', '', 6);
    //     $pdf->SetTextColor(128, 128, 128);
    //     if ($devise_generale == 'USD') {
    //         $equivalent_cdf = $total_general * $taux;
    //         $equivalent_formate = number_format($equivalent_cdf, 0, ',', ' ');
    //         $texte_equivalent = $equivalent_formate . " CDF = " . number_format($total_general, 2, ',', ' ') . " USD (taux 1 USD = " . $taux_formate . " CDF)";
    //     } else {
    //         $equivalent_usd = $total_general / $taux;
    //         $equivalent_formate = number_format($equivalent_usd, 2, ',', ' ');
    //         $texte_equivalent = $equivalent_formate . " USD = " . number_format($total_general, 2, ',', ' ') . " CDF (taux 1 USD = " . $taux_formate . " CDF)";
    //     }
    //     $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', $texte_equivalent), 0, 'L');
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'Taux plancher'), 0, 0, 'L');
    //     $pdf->Cell(26, 5, iconv('UTF-8', 'Windows-1252', number_format($taux, 2, ',', ' ') . ' CDF'), 0, 1, 'R');

    //     $montant_recu_dans_devise = ($devise_recu == 0) ? number_format($montant_recu, 2, ',', ' ') . ' USD' : number_format($montant_recu, 2, ',', ' ') . ' CDF';
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'Montant TTC plancher (' . $devise_generale . ')'), 0, 0, 'L');
    //     $pdf->Cell(26, 5, iconv('UTF-8', 'Windows-1252', $montant_recu_dans_devise), 0, 1, 'R');

    //     $pdf->SetFont('Arial', '', 6);
    //     $pdf->SetTextColor(128, 128, 128);
    //     $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', 'Payé par : ' . $paiement_libelle), 0, 'L');
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', '', 8);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->Cell($largeur_utile, 5, iconv('UTF-8', 'Windows-1252', 'Merci pour votre visite'), 0, 1, 'C');
    //     $pdf->Ln(1);

    //     $pdf->SetFont('Arial', '', 7);
    //     $pdf->SetTextColor(0, 0, 0);
    //     $pdf->MultiCell($largeur_utile, 3.5, iconv('UTF-8', 'Windows-1252', 'Les marchandises vendues ne sont pas reprises ni échangées'), 0, 'C');

    //     $nom_activite_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $activite->nom ?? 'activite');
    //     $nom_fichier = 'Facture_' . $nom_activite_clean . '_' . $facture->numero . '.pdf';
    //     $pdf->Output('F', $nom_fichier);
    //     return response()->json([[$nom_fichier, number_format($cdf_montant_payer, 2, ',', ' '), number_format($usd_montant_payer, 2, ',', ' '), $tva, $taux, $payer]]);
    // }

    public function print_facture(Request $request)
    {
        // ---------- 1. Récupération centralisée ----------
        $facture = Factureass::find($request->facture_id);
        if (!$facture) return response()->json(['error' => 'Facture introuvable'], 404);

        $achats = Achats::where('facture_id', $facture->id)->get();
        if ($achats->isEmpty()) return response()->json(['error' => 'Aucun achat'], 404);

        $articlesIds = $achats->pluck('article_id')->unique();
        $clientsIds = $achats->pluck('client_id')->filter(fn($id) => $id > 0)->unique();
        $articles = Articles::whereIn('id', $articlesIds)->get()->keyBy('id');
        $activiteId = $articles->first()?->activite_id;
        $activite = Activites::find($activiteId);
        $clients = Clients::whereIn('id', $clientsIds)->get()->keyBy('id');

        $taux = $facture->taux;
        $tva = $facture->tva;
        $payer = $facture->payer;
        $date_creation = explode(" ", $facture->created_at);
        $mode_de_paiement = $facture->mode_de_paiement;

        // ---------- Récupération des paiements ----------
        $paiements = detailpaiessachats::where('facture_id', $facture->id)
                                    ->orderBy('created_at', 'asc')
                                    ->get();

        // ---------- Construction des lignes d'achats ----------
        $lignes = [];
        $total_general = 0;
        $nom_client_final = '';
        $devise_generale = '';

        foreach ($achats as $achat) {
            $article = $articles[$achat->article_id] ?? null;
            if (!$article) continue;

            $total_general += $achat->total;

            if ($devise_generale === '') {
                $devise_generale = ($achat->devise == 0) ? 'USD' : 'CDF';
            }

            if ($achat->client_id == 0) {
                $nom_client_final = $achat->libelle;
            } else {
                $nom_client_final = $clients[$achat->client_id]->name ?? $nom_client_final;
            }

            $lignes[] = [
                'nom_article'   => $article->nom_article,
                'quantite'      => $achat->quantite,
                'prix_unitaire' => $achat->prix_unitaire,
                'total_ligne'   => $achat->total,
            ];
        }

        // ---------- Calcul du TTC et des totaux dans les deux devises ----------
        $ttc = $total_general + ($total_general * $tva / 100);
        if ($devise_generale == 'USD') {
            $total_ttc_usd = $ttc;
            $total_ttc_cdf = $ttc * $taux;
        } else {
            $total_ttc_cdf = $ttc;
            $total_ttc_usd = $ttc / $taux;
        }

        // ---------- Parcours des paiements pour cumul et détail ----------
        $cumul_usd = 0;
        $cumul_cdf = 0;
        $paiements_detail = [];

        foreach ($paiements as $p) {
            if ($p->devise_recu == 0) { // paiement en USD
                $montant_usd = $p->montant_recu;
                $montant_cdf = $p->montant_recu * $taux;
            } else { // paiement en CDF
                $montant_cdf = $p->montant_recu;
                $montant_usd = $p->montant_recu / $taux;
            }

            $cumul_usd += $montant_usd;
            $cumul_cdf += $montant_cdf;

            // Reste à payer
            $reste_usd = max(0, $total_ttc_usd - $cumul_usd);
            $reste_cdf = max(0, $total_ttc_cdf - $cumul_cdf);

            // Crédit (payé en trop)
            $credit_usd = max(0, $cumul_usd - $total_ttc_usd);
            $credit_cdf = max(0, $cumul_cdf - $total_ttc_cdf);

            $paiements_detail[] = [
                'date'         => $p->created_at,
                'montant'      => $p->montant_recu,
                'devise_recu'  => $p->devise_recu,
                'reste_usd'    => $reste_usd,
                'reste_cdf'    => $reste_cdf,
                'credit_usd'   => $credit_usd,
                'credit_cdf'   => $credit_cdf,
            ];
        }

        // Totaux finaux
        $total_paye_usd = $cumul_usd;
        $total_paye_cdf = $cumul_cdf;
        $diff_usd = max(0, $total_ttc_usd - $total_paye_usd);
        $diff_cdf = max(0, $total_ttc_cdf - $total_paye_cdf);
        $credit_final_usd = max(0, $total_paye_usd - $total_ttc_usd);
        $credit_final_cdf = max(0, $total_paye_cdf - $total_ttc_cdf);

        // Formatage
        $diff_usd_formate = number_format($diff_usd, 2, ',', ' ') . ' USD';
        $diff_cdf_formate = number_format($diff_cdf, 2, ',', ' ') . ' CDF';
        $paiement_libelle = '';
        if ($mode_de_paiement == 1) $paiement_libelle = 'CASH';
        elseif ($mode_de_paiement == 2) $paiement_libelle = 'Mobile money';
        elseif ($mode_de_paiement == 3) $paiement_libelle = 'Bank';
        else $paiement_libelle = 'CASH';

        // ---------- QR code ----------
        if ($devise_generale == 'USD') {
            $usd_montant_payer = $total_general;
            $cdf_montant_payer = $total_general * $taux;
        } else {
            $cdf_montant_payer = $total_general;
            $usd_montant_payer = $total_general / $taux;
        }

        $key_1 = base64_encode('facture_id');
        $value_1 = base64_encode($request->facture_id);
        $key_2 = base64_encode('cdf_montant');
        $value_2 = base64_encode($cdf_montant_payer);
        $key_3 = base64_encode('usd_montant');
        $value_3 = base64_encode($usd_montant_payer);
        $url = route('paiement') . '?' . http_build_query([$key_1 => $value_1, $key_2 => $value_2, $key_3 => $value_3]);

        $builder = new Builder(
            writer: new PngWriter(),
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 1000,
            margin: 15
        );
        $result = $builder->build();
        $fileName = "./storage/images/fichiers/" . 'qrcode_' . time() . '.png';
        $result->saveToFile($fileName);

        // ---------- 2. PDF ----------
        $pdf = new \FPDF('P', 'mm', [72, 380]);
        $pdf->AddPage();
        $pdf->SetLeftMargin(3);
        $pdf->SetRightMargin(3);
        $largeur_utile = 66;
        $marge_gauche = 3;

        // Logo + QR
        $y_depart = 3;
        $logo_largeur = 15;
        $logo_hauteur = 15;
        $qr_largeur = 12;
        $qr_hauteur = 12;
        $pdf->Image($activite->logo, $marge_gauche, $y_depart, $logo_largeur, $logo_hauteur);
        $pdf->Image($fileName, $marge_gauche + $largeur_utile - $qr_largeur, $y_depart, $qr_largeur, $qr_hauteur);

        $y_apres_logo = $y_depart + max($logo_hauteur, $qr_hauteur) + 2;

        // En-tête
        $pdf->SetY($y_apres_logo);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $nom_activite = $activite ? $activite->nom : '';
        $nom_description = $activite ? $activite->description : '';
        $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', strtoupper($nom_activite)), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 7);
        $largeur_point = $pdf->GetStringWidth('.');
        $nb_points = (int)($largeur_utile / $largeur_point);
        $ligne_points = str_repeat('.', $nb_points);
        $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', $ligne_points), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($largeur_utile, 5, iconv('UTF-8', 'Windows-1252', $nom_description), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 7);
        $date_fr = explode("-", $date_creation[0])[2] . '/' . explode("-", $date_creation[0])[1] . '/' . explode("-", $date_creation[0])[0];
        $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', 'Date : ' . $date_fr . ' à ' . $date_creation[1]), 0, 1, 'L');
        $pdf->Ln(1);

        // Facture / Client
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(33, 4, iconv('UTF-8', 'Windows-1252', 'Facture : ' . strtoupper($facture->numero)), 0, 0, 'L');
        $pdf->Cell(33, 4, iconv('UTF-8', 'Windows-1252', 'Client : ' . $nom_client_final), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', 'Caissier(ère) : ' . User::where('id', $facture->user_id)->first()['name']), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Original'), 0, 1, 'C');
        $pdf->Ln(3);

        // --- Tableau des articles ---
        $col_article = 28;
        $col_qte = 10;
        $col_pu = 14;
        $col_total = 14;

        $pdf->SetLineWidth(0.6);
        $y1 = $pdf->GetY();
        $pdf->Line($marge_gauche, $y1, $marge_gauche + $largeur_utile, $y1);
        $pdf->SetLineWidth(0.2);
        $pdf->Ln(1);

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($col_article, 5, iconv('UTF-8', 'Windows-1252', 'ITEM'), 0, 0, 'L');
        $pdf->Cell($col_qte, 5, iconv('UTF-8', 'Windows-1252', 'QTE'), 0, 0, 'C');
        $pdf->Cell($col_pu, 5, iconv('UTF-8', 'Windows-1252', 'PRIX'), 0, 0, 'C');
        $pdf->Cell($col_total, 5, iconv('UTF-8', 'Windows-1252', 'MONTANT'), 0, 1, 'C');

        $pdf->SetLineWidth(0.6);
        $y2 = $pdf->GetY();
        $pdf->Line($marge_gauche, $y2, $marge_gauche + $largeur_utile, $y2);
        $pdf->SetLineWidth(0.2);
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 7);
        foreach ($lignes as $ligne) {
            $nom = $ligne['nom_article'];
            if (mb_strlen($nom) > 18) $nom = mb_substr($nom, 0, 16) . '..';
            $quantite_str = $ligne['quantite'];
            $prix_str = number_format($ligne['prix_unitaire'], 2, ',', ' ');
            $total_str = number_format($ligne['total_ligne'], 2, ',', ' ');

            $pdf->Cell($col_article, 5, iconv('UTF-8', 'Windows-1252', $nom), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell($col_qte, 5, iconv('UTF-8', 'Windows-1252', $quantite_str), 0, 0, 'C');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell($col_pu, 5, iconv('UTF-8', 'Windows-1252', $prix_str), 0, 0, 'C');
            $pdf->Cell($col_total, 5, iconv('UTF-8', 'Windows-1252', $total_str), 0, 1, 'R');
        }

        $pdf->SetLineWidth(0.6);
        $y3 = $pdf->GetY();
        $pdf->Line($marge_gauche, $y3, $marge_gauche + $largeur_utile, $y3);
        $pdf->SetLineWidth(0.2);
        $pdf->Ln(2);

        // ---------- Détail des paiements (6 colonnes, largeur totale 66 mm) ----------
        if ($paiements->count() > 0) {
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell($largeur_utile, 5, iconv('UTF-8', 'Windows-1252', '--- Paiements & soldes ---'), 0, 1, 'L');
            $pdf->Ln(1);

            // En-têtes : Date (8), Montant (18), R (USD) (10), R (CDF) (10), C (USD) (10), C (CDF) (10) → total 66
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(8, 4, iconv('UTF-8', 'Windows-1252', 'Date'), 0, 0, 'L');
            $pdf->Cell(18, 4, iconv('UTF-8', 'Windows-1252', 'Montant'), 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', 'C (USD)'), 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', 'C (CDF)'), 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', 'R (USD)'), 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', 'R (CDF)'), 0, 1, 'R');

            $pdf->SetFont('Arial', '', 6);

            foreach ($paiements_detail as $p) {
                // Date : jj/mm (sans année, sans heure)
                $date_p = explode(" ", $p['date']);
                $parts = explode('-', $date_p[0]);
                $date_courte = $parts[2] . '/' . $parts[1];
                $date_aff = $date_courte;

                // Montant + devise
                $devise = ($p['devise_recu'] == 0) ? 'USD' : 'CDF';
                $montant_str = number_format($p['montant'], 2, ',', ' ') . ' ' . $devise;

                // Restes
                $reste_usd_str = number_format($p['reste_usd'], 2, ',', ' ');
                $reste_cdf_str = number_format($p['reste_cdf'], 2, ',', ' ');

                // Crédits (uniquement si > 0)
                $credit_usd_str = ($p['credit_usd'] > 0) ? number_format($p['credit_usd'], 2, ',', ' ') : '';
                $credit_cdf_str = ($p['credit_cdf'] > 0) ? number_format($p['credit_cdf'], 2, ',', ' ') : '';

                $pdf->Cell(8, 4, iconv('UTF-8', 'Windows-1252', $date_aff), 0, 0, 'L');
                $pdf->Cell(18, 4, iconv('UTF-8', 'Windows-1252', $montant_str), 0, 0, 'R');
                $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', $reste_usd_str), 0, 0, 'R');
                $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', $reste_cdf_str), 0, 0, 'R');
                $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', $credit_usd_str), 0, 0, 'R');
                $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', $credit_cdf_str), 0, 1, 'R');
            }

            $pdf->Ln(1);

            // Total payé récapitulatif
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(8, 4, iconv('UTF-8', 'Windows-1252', 'Total payé'), 0, 0, 'L');
            $pdf->Cell(18, 4, '', 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', number_format($total_paye_usd, 2, ',', ' ')), 0, 0, 'R');
            $pdf->Cell(10, 4, iconv('UTF-8', 'Windows-1252', number_format($total_paye_cdf, 2, ',', ' ')), 0, 0, 'R');
            $pdf->Cell(10, 4, '', 0, 0, 'R');
            $pdf->Cell(10, 4, '', 0, 1, 'R');
            $pdf->Ln(1);
        } else {
            $pdf->SetFont('Arial', 'I', 6);
            $pdf->Cell($largeur_utile, 4, iconv('UTF-8', 'Windows-1252', 'Aucun paiement enregistré'), 0, 1, 'C');
            $pdf->Ln(1);
        }

        // ---------- Montant HT ----------
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetTextColor(0, 0, 0);
        $total_formate = number_format($total_general, 2, ',', ' ');
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant HT (' . $devise_generale . ')'), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $total_formate), 0, 1, 'R');

        // Première ligne de conversion
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetTextColor(128, 128, 128);
        $taux_formate = number_format($taux, 0, ',', ' ');
        if ($devise_generale == 'USD') {
            $equivalent_cdf = $total_general * $taux;
            $equivalent_formate = number_format($equivalent_cdf, 2, ',', ' ');
            $texte_equivalent = $equivalent_formate . " CDF = " . number_format($total_general, 2, ',', ' ') . " USD (taux 1 USD = " . $taux_formate . " CDF)";
        } else {
            $equivalent_usd = $total_general / $taux;
            $equivalent_formate = number_format($equivalent_usd, 2, ',', ' ');
            $texte_equivalent = $equivalent_formate . " USD = " . number_format($total_general, 2, ',', ' ') . " CDF (taux 1 USD = " . $taux_formate . " CDF)";
        }
        $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', $texte_equivalent), 2, 'L');
        $pdf->Ln(1);

        // Si aucun paiement, on sort sans les détails supplémentaires
        if ($total_paye_usd == 0 && $total_paye_cdf == 0) {
            $nom_activite_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $activite->nom ?? 'activite');
            $nom_fichier = 'Facture_' . $nom_activite_clean . '_' . $facture->numero . '.pdf';
            $pdf->Output('F', $nom_fichier);
            return response()->json([[$nom_fichier, number_format($cdf_montant_payer, 2, ',', ' '), number_format($usd_montant_payer, 2, ',', ' '), $tva, $taux], $payer]);
        }

        // ---------- Suite : facture avec paiements ----------
        // TVA
        $montant_tva = $total_general * ($tva / 100);
        $montant_tva_formate = number_format($montant_tva, 2, ',', ' ') . ' ' . $devise_generale;
        $libelle_tva = 'TVA (' . $devise_generale . ') ' . $tva . '%';
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', $libelle_tva), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_tva_formate), 0, 1, 'R');

        // TVA autre devise
        $autre_devise = ($devise_generale == 'USD') ? 'CDF' : 'USD';
        if ($devise_generale == 'USD') {
            $montant_tva_autre = $montant_tva * $taux;
            $montant_tva_autre_formate = number_format($montant_tva_autre, 2, ',', ' ') . ' ' . $autre_devise;
        } else {
            $montant_tva_autre = $montant_tva / $taux;
            $montant_tva_autre_formate = number_format($montant_tva_autre, 2, ',', ' ') . ' ' . $autre_devise;
        }
        $libelle_tva_autre = 'TVA (' . $autre_devise . ') ' . $tva . '%';
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', $libelle_tva_autre), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_tva_autre_formate), 0, 1, 'R');

        // Montant reçu
        $montant_recu_usd = number_format($total_paye_usd, 2, ',', ' ') . ' USD';
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant reçu (USD)'), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_recu_usd), 0, 1, 'R');

        $montant_recu_cdf = number_format($total_paye_cdf, 2, ',', ' ') . ' CDF';
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Montant reçu (CDF)'), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $montant_recu_cdf), 0, 1, 'R');

        // Reste
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Reste (USD)'), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $diff_usd_formate), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Reste (CDF)'), 0, 0, 'L');
        $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $diff_cdf_formate), 0, 1, 'R');

        // Crédit éventuel
        if ($credit_final_usd > 0 || $credit_final_cdf > 0) {
            $credit_aff = '';
            if ($credit_final_usd > 0) $credit_aff .= number_format($credit_final_usd, 2, ',', ' ') . ' USD';
            if ($credit_final_cdf > 0) $credit_aff .= (empty($credit_aff) ? '' : ' / ') . number_format($credit_final_cdf, 2, ',', ' ') . ' CDF';
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(40, 4, iconv('UTF-8', 'Windows-1252', 'Crédit client :'), 0, 0, 'L');
            $pdf->Cell(26, 4, iconv('UTF-8', 'Windows-1252', $credit_aff), 0, 1, 'R');
        }

        // --- Lignes pointillées et TTC ---
        $pdf->SetFont('Arial', '', 8);
        $largeur_point = $pdf->GetStringWidth('.');
        $nb_points = (int)($largeur_utile / $largeur_point);
        $ligne_points_fin = str_repeat('.', $nb_points);
        $hauteur_point = 5;

        $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
        $pdf->Ln(1);

        $ttc_formate = number_format($ttc, 2, ',', ' ');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Montant TTC (' . $devise_generale . ') : ' . $ttc_formate), 0, 1, 'R');
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
        $pdf->Ln(1);

        $autre_devise_ttc = ($devise_generale == 'USD') ? 'CDF' : 'USD';
        if ($devise_generale == 'USD') {
            $ttc_autre = $ttc * $taux;
            $ttc_autre_formate = number_format($ttc_autre, 2, ',', ' ');
        } else {
            $ttc_autre = $ttc / $taux;
            $ttc_autre_formate = number_format($ttc_autre, 2, ',', ' ');
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($largeur_utile, 6, iconv('UTF-8', 'Windows-1252', 'Montant TTC (' . $autre_devise_ttc . ') : ' . $ttc_autre_formate), 0, 1, 'R');
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($largeur_utile, $hauteur_point, iconv('UTF-8', 'Windows-1252', $ligne_points_fin), 0, 1, 'C');
        $pdf->Ln(1);

        // Deuxième ligne de conversion
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetTextColor(128, 128, 128);
        if ($devise_generale == 'USD') {
            $equivalent_cdf = $total_general * $taux;
            $equivalent_formate = number_format($equivalent_cdf, 2, ',', ' ');
            $texte_equivalent = $equivalent_formate . " CDF = " . number_format($total_general, 2, ',', ' ') . " USD (taux 1 USD = " . $taux_formate . " CDF)";
        } else {
            $equivalent_usd = $total_general / $taux;
            $equivalent_formate = number_format($equivalent_usd, 2, ',', ' ');
            $texte_equivalent = $equivalent_formate . " USD = " . number_format($total_general, 2, ',', ' ') . " CDF (taux 1 USD = " . $taux_formate . " CDF)";
        }
        $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', $texte_equivalent), 0, 'L');
        $pdf->Ln(1);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'Taux plancher'), 0, 0, 'L');
        $pdf->Cell(26, 5, iconv('UTF-8', 'Windows-1252', number_format($taux, 2, ',', ' ') . ' CDF'), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'Montant TTC dû (' . $devise_generale . ')'), 0, 0, 'L');
        $pdf->Cell(26, 5, iconv('UTF-8', 'Windows-1252', number_format($ttc, 2, ',', ' ') . ' ' . $devise_generale), 0, 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->SetTextColor(128, 128, 128);
        $pdf->MultiCell($largeur_utile, 3, iconv('UTF-8', 'Windows-1252', 'Payé par : ' . $paiement_libelle), 0, 'L');
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($largeur_utile, 5, iconv('UTF-8', 'Windows-1252', 'Merci pour votre visite'), 0, 1, 'C');
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($largeur_utile, 3.5, iconv('UTF-8', 'Windows-1252', 'Les marchandises vendues ne sont pas reprises ni échangées'), 0, 'C');

        $nom_activite_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $activite->nom ?? 'activite');
        $nom_fichier = 'Facture_' . $nom_activite_clean . '_' . $facture->numero . '.pdf';
        $pdf->Output('F', $nom_fichier);
        return response()->json([[$nom_fichier, number_format($cdf_montant_payer, 2, ',', ' '), number_format($usd_montant_payer, 2, ',', ' '), $tva, $taux, $payer]]);
    }

    public function get_print_listes_factures(Request $request)
    {

        $listesfactures = Listesfactures::where('id', $request->listesfactures_id)->first();
        $listesfactures_id = Listesfactures::where('id', $request->listesfactures_id)->first()["id"];
        $paiementsfactures = Paiementsfactures::where(['listesfactures_id' => $listesfactures_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $utilisateurs = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($paiementsfactures as $f)
            {
            if ($f->paye == 1)
            {
                if ($f->devise == 0) {
                    $total_3 =  $total_3 + $f->paie;
                } else {
                    $total_3 =  ($total_3 + ($f->paie / $f->taux));
                }
            }
        }
        foreach ($paiementsfactures as $f)
        {
            if ($f->paye == 0) {
                if ($f->devise == 0) {
                    $total_4 =  $total_4 + $f->paie;
                } else {
                    $total_4 =  round($total_4 + ($f->paie / $f->taux));
                }
            }
        }
        $total_5 = $total_3 + $total_4;
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->SetFont('Arial', 'B', 10);
        $nn = 1;
        foreach ($paiementsfactures as $data)
        {
            if(Clients::where('id', $data->client_id)->first()['factures'] == 0)
            {
                if((Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id) && (Clients::where('id', $data->client_id)->first()['user_id'] == Auth::user()->id) || (Auth::user()->role == 0))
                {
                    $pdf->AddPage();
                    $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
                    $pdf->Image("./connexion/images/sceau_africtech_2.png", 45, 180, 40, 40);
                    $pdf->Image("./connexion/images/signature_armine_2.png", 50, 220, 30, 20);
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Ln(30);

                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $numero = $data->id;
                    if ($numero >= 1 && $numero <= 9)
                    {
                        $numero = '000' . $numero;
                    } else if ($numero >= 10 && $numero <= 99) {
                        $numero = '00' . $numero;
                    } else if ($numero >= 100 && $numero <= 999) {
                        $numero = '0' . $numero;
                    }
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'AFT' . $numero), 1, 0, 'R');
                    $pdf->Ln(5);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                    $date_a = explode(" ", $data->created_at)[0];
                    $date_b = explode("-", $date_a);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                    // Test normal
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(20);
                    $pdf->Cell(72);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');


                    $pdf->ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                    $pdf->ln(15);
                    $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                    $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                    $pdf->ln(12);
                    $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                    $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(50);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }


                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(15);
                    $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                }
            }
            if(Clients::where('id', $data->client_id)->first()['factures'] == 1)
            {
                if((Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id) && (Clients::where('id', $data->client_id)->first()['user_id'] == Auth::user()->id) || (Auth::user()->role == 0))
                {
                    $pdf->AddPage();
                    $pdf->Image("./connexion/images/fqsmm_2.png", 10, 10, 50, 20);
                    $pdf->Image("./connexion/images/sceau_b.png", 35, 180, 70, 40);
                    $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Ln(30);

                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $numero = $data->id;
                    if ($numero >= 1 && $numero <= 9)
                    {
                        $numero = '000' . $numero;
                    } else if ($numero >= 10 && $numero <= 99) {
                        $numero = '00' . $numero;
                    } else if ($numero >= 100 && $numero <= 999) {
                        $numero = '0' . $numero;
                    }
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                    $pdf->Ln(5);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                    $date_a = explode(" ", $data->created_at)[0];
                    $date_b = explode("-", $date_a);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                    $pdf->Ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'RCCM/19-B-000345'), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1908'), 0, 0, 'L');

                    // Test normal
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(20);
                    $pdf->Cell(72);
                    $pdf->SetTextColor(0, 102, 204);
                    $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                    $pdf->SetTextColor(0, 0, 0);


                    $pdf->ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                    $pdf->ln(15);
                    $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                    $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                    $pdf->ln(12);
                    $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                    $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(50);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }


                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(15);
                    $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                }
            }
            if(Clients::where('id', $data->client_id)->first()['factures'] == 2)
            {
                if((Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id) && (Clients::where('id', $data->client_id)->first()['user_id'] == Auth::user()->id) || (Auth::user()->role == 0))
                {
                    $pdf->AddPage();
                    $pdf->Image("./connexion/images/bf_2.png", 10, 10, 50, 20);
                    $pdf->Image("./connexion/images/beforward_2.png",  45, 190, 50, 20);
                    $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Ln(30);

                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $numero = $data->id;
                    if ($numero >= 1 && $numero <= 9)
                    {
                        $numero = '000' . $numero;
                    } else if ($numero >= 10 && $numero <= 99) {
                        $numero = '00' . $numero;
                    } else if ($numero >= 100 && $numero <= 999) {
                        $numero = '0' . $numero;
                    }
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                    $pdf->Ln(5);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                    $pdf->SetFillColor(211, 211, 211);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                    $date_a = explode(" ", $data->created_at)[0];
                    $date_b = explode("-", $date_a);
                    $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                    $pdf->Ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', "CD/L'SHI/RCCM/16-B-4288"), 0, 0, 'L');
                    // $pdf->Ln(5);
                    // $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1619638P'), 0, 0, 'L');

                    // Test normal
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->ln(20);
                    $pdf->Cell(72);
                    $pdf->SetTextColor(0, 102, 204);
                    $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                    $pdf->SetTextColor(0, 0, 0);


                    $pdf->ln(5);
                    $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                    $pdf->ln(15);
                    $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                    $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                    $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                    $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                    $pdf->ln(12);
                    $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                    $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                    $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(50);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }


                    $pdf->ln(5);
                    $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                    if($data->devise == 0)
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                    }else
                    {
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                    }

                    $pdf->ln(15);
                    $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                    $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                }
            }
        }
        $nom_fichier = 'LISTE DE FACTURE ' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"]  . ".pdf";
        $pdf->Output("F", $nom_fichier);
        echo $nom_fichier;

    }

    public function send_factures_e(Request $request)
    {
        $client_id = $request->client_id;
        $paiementsfactures_id = $request->paiementsfactures_id;
        $_paiementsfactures = Paiementsfactures::where(['id' => $paiementsfactures_id])->first();
        $clients = Clients::where(['id' => $client_id])->first();
        $listesfactures_id = $_paiementsfactures["listesfactures_id"];
        $listesfactures = Listesfactures::where('id', $listesfactures_id)->first();
        $listesfactures_id = Listesfactures::where('id', $listesfactures_id)->first()["id"];
        $paiementsfactures = Paiementsfactures::where(['listesfactures_id' => $listesfactures_id])->get();

        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $utilisateurs = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($paiementsfactures as $f)
            {
            if ($f->paye == 1)
            {
                if ($f->devise == 0) {
                    $total_3 =  $total_3 + $f->paie;
                } else {
                    $total_3 =  ($total_3 + ($f->paie / $f->taux));
                }
            }
        }
        foreach ($paiementsfactures as $f)
        {
            if ($f->paye == 0) {
                if ($f->devise == 0) {
                    $total_4 =  $total_4 + $f->paie;
                } else {
                    $total_4 =  round($total_4 + ($f->paie / $f->taux));
                }
            }
        }
        $total_5 = $total_3 + $total_4;
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->SetFont('Arial', 'B', 10);
        $nn = 1;
        foreach ($paiementsfactures as $data)
        {
            if($client_id == $data->client_id)
            {
                if(Clients::where('id', $data->client_id)->first()['factures'] == 0)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
                        $pdf->Image("./connexion/images/sceau_africtech_2.png", 45, 180, 40, 40);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 50, 220, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'AFT' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
                if(Clients::where('id', $data->client_id)->first()['factures'] == 1)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/fqsmm_2.png", 10, 10, 50, 20);
                        $pdf->Image("./connexion/images/sceau_b.png", 35, 180, 70, 40);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'RCCM/19-B-000345'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1908'), 0, 0, 'L');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 102, 204);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                        $pdf->SetTextColor(0, 0, 0);


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
                if(Clients::where('id', $data->client_id)->first()['factures'] == 2)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/bf_2.png", 10, 10, 50, 20);
                        $pdf->Image("./connexion/images/beforward_2.png",  45, 190, 50, 20);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', "CD/L'SHI/RCCM/16-B-4288"), 0, 0, 'L');
                        // $pdf->Ln(5);
                        // $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1619638P'), 0, 0, 'L');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 102, 204);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                        $pdf->SetTextColor(0, 0, 0);


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
            }
        }
        // ---------- SAUVEGARDE DANS LE DOSSIER public/ ----------
        $nom_fichier = 'FACTURE_' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . '_' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"] . '_' . $clients["name"] . ".pdf";
        // Nettoyer le nom des caractères problématiques (espaces, accents, etc.)
        $nom_fichier = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nom_fichier);

        // Chemin complet dans public/
        $filePath = public_path("$nom_fichier");

        // Sauvegarde
        $pdf->Output('F', $filePath);
        $send_e_plus =  $_paiementsfactures->send_e;

        if(strlen(trim($clients["email"])) != 0)
        {
            $mail = new PHPMailer(true); // true active les exceptions

            try {
                // --- Configuration SMTP ---
                $mail->isSMTP();
                $mail->Host       = 'mail10.lwspanel.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'armine.lwamba@africtech-drc.com';
                $mail->Password   = '1@Armine12345';
                $mail->SMTPSecure = 'ssl';   // ou 'tls' selon votre serveur
                $mail->Port       = 465;     // 587 pour tls, 465 pour ssl

                // --- Expéditeur et destinataire ---
                $mail->setFrom('armine.lwamba@africtech-drc.com', 'Mme ARMINE');
                $mail->addAddress(trim($clients["email"]), trim($clients["email"]));
                // Vous pouvez ajouter d'autres destinataires avec addCC() ou addBCC()

                // --- Contenu ---
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';
                $mail->setLanguage('fr', 'PHPMailer/language/');
                $mail->isHTML(true);
                $mail->Subject = 'FACTURE ' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"];
                $nom_client = $clients['name'];
                $mail->Body = "<h2>Bonjour</h2><p> Mr/Mme/Ent. $nom_client.</p><p style='color:#FF0000;'>NB : Veuillez trouver ci-joint la facture au format PDF.</p>";
                // --- PIÈCE JOINTE ---
                // Chemin absolu vers le fichier (par exemple dans le dossier public de votre projet)
                $filePath = public_path("$nom_fichier"); // Ajustez le chemin
                // OU si vous utilisez Laravel : $filePath = public_path('LISTE_DE_FACTURE_JANVIER_2025.pdf');

                if (file_exists($filePath))
                {
                    $mail->addAttachment($filePath);
                }

                // --- Envoi ---
                if($mail->send())
                {
                    $send_e = $_paiementsfactures->send_e;
                    $send_e_plus = $send_e + 1;
                    $_paiementsfactures->send_e = $send_e_plus;
                    $_paiementsfactures->save();
                    echo 1 .'_____________________' . $send_e_plus;
                }else
                {
                    echo 0 .'_____________________' . $send_e_plus;
                }
            } catch (Exception $e)
            {
                echo 0 .'_____________________' . $send_e_plus;
            }
        }else
        {
            echo 0 .'_____________________' . $send_e_plus;
        }


    }

    public function send_factures_w(Request $request)
    {
        $client_id = $request->client_id;
        $paiementsfactures_id = $request->paiementsfactures_id;
        $_paiementsfactures = Paiementsfactures::where(['id' => $paiementsfactures_id])->first();
        $clients = Clients::where(['id' => $client_id])->first();
        $listesfactures_id = $_paiementsfactures["listesfactures_id"];
        $listesfactures = Listesfactures::where('id', $listesfactures_id)->first();
        $listesfactures_id = Listesfactures::where('id', $listesfactures_id)->first()["id"];
        $paiementsfactures = Paiementsfactures::where(['listesfactures_id' => $listesfactures_id])->get();

        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $utilisateurs = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($paiementsfactures as $f)
            {
            if ($f->paye == 1)
            {
                if ($f->devise == 0) {
                    $total_3 =  $total_3 + $f->paie;
                } else {
                    $total_3 =  ($total_3 + ($f->paie / $f->taux));
                }
            }
        }
        foreach ($paiementsfactures as $f)
        {
            if ($f->paye == 0) {
                if ($f->devise == 0) {
                    $total_4 =  $total_4 + $f->paie;
                } else {
                    $total_4 =  round($total_4 + ($f->paie / $f->taux));
                }
            }
        }
        $total_5 = $total_3 + $total_4;
        $groupes = Groupes::where(["etat" => 1])->get();
        $pdf = new FPDF();
        $pdf->SetFont('Arial', 'B', 10);
        $nn = 1;
        foreach ($paiementsfactures as $data)
        {
            if($client_id == $data->client_id)
            {
                if(Clients::where('id', $data->client_id)->first()['factures'] == 0)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/logo_africtech.jpg", 10, 10, 70, 30);
                        $pdf->Image("./connexion/images/sceau_africtech_2.png", 45, 180, 40, 40);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 50, 220, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'AFT' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
                if(Clients::where('id', $data->client_id)->first()['factures'] == 1)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/fqsmm_2.png", 10, 10, 50, 20);
                        $pdf->Image("./connexion/images/sceau_b.png", 35, 180, 70, 40);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'RCCM/19-B-000345'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1908'), 0, 0, 'L');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 102, 204);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                        $pdf->SetTextColor(0, 0, 0);


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
                if(Clients::where('id', $data->client_id)->first()['factures'] == 2)
                {
                    if(Clients::where('id', $data->client_id)->first()['activite_id'] == $request->activite_id)
                    {
                        $pdf->AddPage();
                        $pdf->Image("./connexion/images/bf_2.png", 10, 10, 50, 20);
                        $pdf->Image("./connexion/images/beforward_2.png",  45, 190, 50, 20);
                        $pdf->Image("./connexion/images/signature_armine_2.png", 55, 210, 30, 20);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Ln(30);

                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $numero = $data->id;
                        if ($numero >= 1 && $numero <= 9)
                        {
                            $numero = '000' . $numero;
                        } else if ($numero >= 10 && $numero <= 99) {
                            $numero = '00' . $numero;
                        } else if ($numero >= 100 && $numero <= 999) {
                            $numero = '0' . $numero;
                        }
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'N° FACTURE'), 1, 0, 'L', true);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'FQS' . $numero), 1, 0, 'R');
                        $pdf->Ln(5);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'R');
                        $pdf->SetFillColor(211, 211, 211);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', 'DATE'), 1, 0, 'L', true);
                        $date_a = explode(" ", $data->created_at)[0];
                        $date_b = explode("-", $date_a);
                        $pdf->Cell(47, 5, iconv('UTF-8', 'Windows-1252', $date_b[2] . '-' .$date_b[1] . '-' . $date_b[0]), 1, 0, 'R');

                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', "CD/L'SHI/RCCM/16-B-4288"), 0, 0, 'L');
                        // $pdf->Ln(5);
                        // $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'ID.NAT/6-27N48119H'), 0, 0, 'L');
                        $pdf->Ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'IMPOT/A1619638P'), 0, 0, 'L');

                        // Test normal
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->ln(20);
                        $pdf->Cell(72);
                        $pdf->SetTextColor(0, 102, 204);
                        $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', 'FACTURE MENSUELLE'), 'Bottom', 1, 'C');
                        $pdf->SetTextColor(0, 0, 0);


                        $pdf->ln(5);
                        $pdf->Cell(190, 5, iconv('UTF-8', 'Windows-1252', 'CLIENT : '. Clients::where('id', $data->client_id)->first()["name"]), 0, 0, 'L');


                        $pdf->ln(15);
                        $pdf->Cell(50, 12, iconv('UTF-8', 'Windows-1252', 'DESCRIPTION'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'MOIS'), 1, 0, 'L', true);
                        $pdf->Cell(18, 12, iconv('UTF-8', 'Windows-1252', 'ANNEE'), 1, 0, 'L', true);
                        $pdf->Cell(24, 12, iconv('UTF-8', 'Windows-1252', 'QUANTITE'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'P.U'), 1, 0, 'L', true);
                        $pdf->Cell(40, 12, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);


                        $pdf->ln(12);
                        $pdf->Cell(50, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["description"]), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"])), 1, 0, 'L');
                        $pdf->Cell(18, 50, iconv('UTF-8', 'Windows-1252', strtoupper(Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"])), 1, 0, 'L');
                        $pdf->Cell(24, 50, iconv('UTF-8', 'Windows-1252', Clients::where('id', $data->client_id)->first()["quantite"]), 1, 0, 'C');
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 50, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(50);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'SOUS TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }

                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TVA'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252',number_format(0, 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format(0, 0, ',', ' ') .'CDF'), 1, 0, 'R');
                        }


                        $pdf->ln(5);
                        $pdf->Cell(50, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(30, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', 'TOTAL'), 1, 0, 'L', true);
                        if($data->devise == 0)
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }else
                        {
                            $pdf->Cell(40, 5, iconv('UTF-8', 'Windows-1252', number_format($data->montant * Clients::where('id', $data->client_id)->first()["quantite"], 0, ',', ' ') .'USD'), 1, 0, 'R');
                        }

                        $pdf->ln(15);
                        $pdf->Cell(100, 5, iconv('UTF-8', 'Windows-1252', ''), 0, 0, 'L');
                        $pdf->Cell(90, 5, iconv('UTF-8', 'Windows-1252', 'TEL : +243858003089'), 0, 0, 'L');
                    }
                }
            }
        }
        // ---------- SAUVEGARDE DANS LE DOSSIER public/ ----------
        $nom_fichier = 'FACTURE_' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . '_' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"] . '_' . $clients["name"] . ".pdf";
        // Nettoyer le nom des caractères problématiques (espaces, accents, etc.)
        $nom_fichier = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nom_fichier);

        // Sauvegarde
        $pdf->Output('F', "./public/$nom_fichier");
        $send_w = $_paiementsfactures->send_w;
        $send_w_plus = $send_w + 1;
        $_paiementsfactures->send_w = $send_w_plus;
        $_paiementsfactures->save();
        $filePath = asset("/public/$nom_fichier");

        echo 1 .'_____________________' . $send_w_plus  .'_____________________' . $clients["phone"] .'_____________________' . $clients["name"] . '_____________________' . $filePath . '_____________________' . 'FACTURE ' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"]  .' '. $clients["name"] .'_____________________' . $nom_fichier .'_____________________' . 'FACTURE ' . strtoupper(Mois::where(["id" => $listesfactures["moi_id"]])->first()["nom"]) . ' ' . Annees::where(["id" => $listesfactures["annee_id"]])->first()["annees"]  .' '. $clients["name"];
    }

    public function refresh_editcontentieux(Request $request)
    {
        $data["contentieux"] = Contentieurs::where('id', $request->dossier_contentieux_id)->first();
        $data["decisions"] = Decisions::where('id', $request->decision_id)->first();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        $data["frais"] = Frais::where(["contentieur_id" => $request->dossier_contentieux_id, "user_id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["frais"] = Frais::where(["contentieur_id" => $request->dossier_contentieux_id])->get();
        }
        $data["travailleurs"] = Travailleurs::where(["contentieur_id" => $request->dossier_contentieux_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        return view('include.refresh_editcontentieux', $data);
    }

    public function refresh_editcontentieux_2(Request $request)
    {
        $data["contentieux"] = Contentieurs::where('id', $request->dossier_contentieux_id)->first();
        $data["decisions"] = Decisions::where('id', $request->decision_id)->first();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        $data["frais"] = Frais::where(["contentieur_id" => $request->dossier_contentieux_id])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_editcontentieux_2', $data);
    }

    public function refresh_editcontentieux_3(Request $request)
    {
        $data["invitations"] = Invitations::where('id', $request->invitation_id)->first();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_editcontentieux_3', $data);
    }

    public function edit_groupe(Request $request)
    {
        $groupe = Groupes::where('id', $request->id)->first();
        $groupe->nom = $request->edit_nom;
        $groupe->save();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_groupe', $data);
    }

    public function edit_contrevenant(Request $request)
    {
        $contrevenant = Contrevenants::where('id', $request->id)->first();
        $contrevenant->nom = $request->edit_nom;
        $contrevenant->recherche = $request->edit_nom;
        $contrevenant->save();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        return view('include.refresh_contrevenant', $data);
    }

    public function edit_verbalisateur(Request $request)
    {
        $verbalisateur = Verbalisateurs::where('id', $request->id)->first();
        $verbalisateur->nom = $request->edit_nom;
        $verbalisateur->recherche = $request->edit_nom;
        $verbalisateur->save();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        return view('include.refresh_verbalisateur', $data);
    }

    public function edit_type_frais(Request $request)
    {
        $type_frais = Type_frais::where('id', $request->id)->first();
        $type_frais->nom = $request->edit_nom;
        $type_frais->code = $request->edit_code;
        $type_frais->description = $request->edit_description;
        $type_frais->recherche = $request->edit_nom;
        $type_frais->save();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        return view('include.refresh_type_frais', $data);
    }

    public function edit_type_documents(Request $request)
    {
        $type_documents = Type_documents::where('id', $request->id)->first();
        $type_documents->nom = $request->edit_nom;
        if(strlen(trim($request->edit_description)) == 0)
        {
            $type_documents->description = "";
        }
        else
        {
            $type_documents->description = $request->edit_description;
        }
        $type_documents->save();
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        return view('include.refresh_type_documents', $data);
    }

    public function edit_point_ventes(Request $request)
    {
        $point_ventes = Pointdeventes::where('id', $request->id)->first();
        $point_ventes->nom = $request->edit_nom;
        if(strlen(trim($request->edit_description)) == 0)
        {
            $point_ventes->description = "";
        }
        else
        {
            $point_ventes->description = $request->edit_description;
        }
        $point_ventes->save();
        $data["point_ventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        return view('include.refresh_point_ventes', $data);
    }

    public function edit_stocks(Request $request)
    {
        $stock = Stocks::where('id', $request->id)->first();
        $stock->nom = $request->edit_nom;
        if(strlen(trim($request->edit_description)) == 0)
        {
            $stock->description = "";
        }
        else
        {
            $stock->description = $request->edit_description;
        }
        $stock->save();
        $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        return view('include.refresh_stocks', $data);
    }


    public function edit_tables(Request $request)
    {
        $tables = Tables::where('id', $request->id)->first();
        $tables->nom = $request->edit_nom;
        $tables->pointdeventes_id = $request->edit_point_vente_id;
        if(strlen(trim($request->edit_description)) == 0)
        {
            $tables->description = "";
        }
        else
        {
            $tables->description = $request->edit_description;
        }
        $tables->save();
        $data["tables"] = Tables::where(["etat" => 1, "user_id" => Auth::user()->id])->get();
        return view('include.refresh_tables', $data);
    }

    public function edit_societe(Request $request)
    {
        $societes = Societes::where('id', $request->id)->first();
        $societes->nom = $request->edit_nom;
        $societes->code = $request->edit_code;
        $societes->description = $request->edit_description;
        $societes->recherche = $request->edit_nom;
        $societes->save();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        return view('include.refresh_societes', $data);
    }

    public function edit_type_infractions(Request $request)
    {
        $type_infractions = Type_infractions::where('id', $request->id)->first();
        $type_infractions->nom = $request->edit_nom;
        $type_infractions->libelle = $request->edit_nom;
        $type_infractions->code = $request->edit_code;
        $type_infractions->description = $request->edit_description;
        $type_infractions->recherche = $request->edit_nom;
        $type_infractions->save();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        return view('include.refresh_type_infractions', $data);
    }

    public function refresh_deletegroupe(Request $request)
    {
        $user = Groupes::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_groupe', $data);
    }

    public function refresh_deletecontrevenant(Request $request)
    {
        $user = Contrevenants::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["contrevenants"] = Contrevenants::where(["etat" => 1])->get();
        return view('include.refresh_contrevenant', $data);
    }

    public function refresh_deleteverbalisateur(Request $request)
    {
        $user = Verbalisateurs::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["verbalisateurs"] = Verbalisateurs::where(["etat" => 1])->get();
        return view('include.refresh_verbalisateur', $data);
    }

    public function refresh_approuver_frais(Request $request)
    {
        $frais = Frais::where('id', $request->id)->first();
        $frais->paye = 1;
        $frais->date_paye_valider = date("d/m/Y");
        $frais->save();
        $data["frais"] = Frais::where(["contentieur_id" => $request->dossier_contentieux_id])->get();
        return view('include.refresh_frais_contentieux', $data);
    }

    public function refresh_delete_type_frais(Request $request)
    {
        $user = Type_frais::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["type_frais"] = Type_frais::where(["etat" => 1])->get();
        return view('include.refresh_type_frais', $data);
    }

    public function refresh_delete_type_documents(Request $request)
    {
        $user = Type_documents::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        return view('include.refresh_type_documents', $data);
    }

    public function refresh_delete_point_ventes(Request $request)
    {
        $user = Pointdeventes::where('id', $request->id)->first();
        $user->etat = 0;
        $user->Supprimer = 1;
        $user->save();
        $data["point_ventes"] = Pointdeventes::where(["user_id" => Auth::user()->id, "etat" => 1, "Supprimer" => 0])->get();
        return view('include.refresh_point_ventes', $data);
    }


    public function refresh_delete_stocks(Request $request)
    {
        $user = Stocks::where('id', $request->id)->first();
        $user->etat = 0;
        $user->Supprimer = 1;
        $user->save();
        $data["stocks"] = Stocks::where(["user_id" => Auth::user()->id, "etat" => 1, "Supprimer" => 0])->get();
        return view('include.refresh_stocks', $data);
    }


    public function refresh_delete_tables(Request $request)
    {
        $user = Tables::where('id', $request->id)->first();
        $user->etat = 0;
        $user->Supprimer = 1;
        $user->save();
        $data["tables"] = Tables::where(["user_id" => Auth::user()->id, "etat" => 1, "Supprimer" => 0])->get();
        return view('include.refresh_tables', $data);
    }

    public function refresh_delete_fichier_documents(Request $request)
    {
        $user = Fichier_documents::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["type_documents"] = Type_documents::where(["etat" => 1])->get();
        $data["fichier_documents"] = Fichier_documents::where(["etat" => 1])->get();
        return view('include.refresh_fichier_documents', $data);
    }

    public function refresh_delete_societe(Request $request)
    {
        $user = Societes::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        return view('include.refresh_societes', $data);
    }

    public function refresh_deletearticle(Request $request)
    {
        $user = Articles::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["articles"] = Articles::where(["supprimer" => 0])->get();
        return view('include.refresh_articles', $data);
    }

    public function refresh_activer_solde(Request $request)
    {
        $user = Soldes::where('id', $request->id)->first();
        $user->etat = 1;
        $user->save();
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        return view('include.refresh_soldes', $data);
    }

    public function refresh_activer_paie(Request $request)
    {
        $user = Listespaies::where('id', $request->id)->first();
        $user->etat = 1;
        $user->save();
        $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
        return view('include.refresh_listespaies', $data);
    }

    public function refresh_activer_listesfactures(Request $request)
    {
        $user = Listesfactures::where('id', $request->id)->first();
        $user->etat = 1;
        $user->save();
        $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
        return view('include.refresh_listesfactures', $data);
    }

    public function refresh_desactiver_alerte_centrale(Request $request)
    {
        $date_arrrive =  date("d/m/Y");
        // Définir le fuseau horaire de Lubumbashi
        date_default_timezone_set('Africa/Lubumbashi');

        $heure = date("H:i");

        $alerte = Alertes::where('id', $request->id)->first();
        $alerte->etat_1 = 0;
        $alerte->user_id_desactiver_etat_1 = Auth::user()->id;
        $alerte->date_desactiver_etat_1 = $date_arrrive . ' ' . $heure;
        $alerte->save();
        $data["alertes"] = Alertes::where(["supprimer" => 0])->get();
        return view('include.refresh_alerte_centrale', $data);
    }

    public function refresh_desactiver_alerte_centrale_1(Request $request)
    {
        $date_arrrive =  date("d/m/Y");
        // Définir le fuseau horaire de Lubumbashi
        date_default_timezone_set('Africa/Lubumbashi');

        $heure = date("H:i");

        $alerte = Alertes::where('id', $request->id)->first();
        $alerte->etat_2 = 0;
        $alerte->user_id_desactiver_etat_2 = Auth::user()->id;
        $alerte->date_desactiver_etat_2 = $date_arrrive . ' ' . $heure;
        $alerte->save();
        $data["alertes"] = Alertes::where('supprimer', 0)->where('user_id_transfert', '!=', 0)->get();
        return view('include.refresh_alerte_centrale_1', $data);
    }

    public function refresh_alerte_centrale(Request $request)
    {
        $data["alertes"] = Alertes::where(["supprimer" => 0])->get();
        return view('include.refresh_alerte_centrale', $data);
    }

     public function refresh_alerte_centrale_1(Request $request)
    {
        $data["alertes"] = Alertes::where(['supprimer' => 0])->where('user_id_transfert', '!=', 0)->get();
        return view('include.refresh_alerte_centrale_1', $data);
    }

    public function refresh_transfert_alerte_centrale(Request $request)
    {
        $date_arrrive =  date("d/m/Y");
        // Définir le fuseau horaire de Lubumbashi
        date_default_timezone_set('Africa/Lubumbashi');

        $heure = date("H:i");

        $alerte = Alertes::where('id', $request->id)->first();
        $alerte->user_id_transfert = Auth::user()->id;
        $alerte->etat_2 = 1;
        $alerte->date_transfert = $date_arrrive . ' ' . $heure;
        $alerte->save();
        $data["alertes"] = Alertes::where(["supprimer" => 0])->get();
        return view('include.refresh_alerte_centrale', $data);
    }

    public function refresh_activer_poste(Request $request)
    {
        $user = Postes::where('id', $request->id)->first();
        $user->etat = 1;
        $user->save();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        return view('include.refresh_poste', $data);
    }

    public function refresh_activer_rendez_vous(Request $request)
    {
        $user = Rendezvous::where('id', $request->id)->first();
        $user->etat = 1;
        $user->save();
        $data["rendez_vous"] = Rendezvous::where(["supprimer" => 0])->get();
        return view('include.refresh_rendez_vous', $data);
    }

    public function refresh_cloturer_solde(Request $request)
    {
        $user = Soldes::where('id', $request->id)->first();
        $user->etat = 2;
        $user->save();
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        return view('include.refresh_soldes', $data);
    }

    public function refresh_cloturer_paie(Request $request)
    {
        $user = Listespaies::where('id', $request->id)->first();
        $user->etat = 2;
        $user->save();
        $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
        return view('include.refresh_listespaies', $data);
    }

    public function refresh_cloturer_listesfactures(Request $request)
    {
        $user = Listesfactures::where('id', $request->id)->first();
        $user->etat = 2;
        $user->save();
        $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
        return view('include.refresh_listesfactures', $data);
    }

    public function refresh_cloturer_poste(Request $request)
    {
        $user = Postes::where('id', $request->id)->first();
        $user->etat = 2;
        $user->save();
        $data["postes"] = Postes::where(["supprimer" => 0])->get();
        $data["lieux"] = Lieux::where(["etat" => 1])->get();
        $data["clients"] = Clients::where(["etat" => 1])->get();
        return view('include.refresh_poste', $data);
    }

    public function refresh_cloturer_rendez_vous(Request $request)
    {
        $user = Rendezvous::where('id', $request->id)->first();
        $user->etat = 2;
        $user->date_cloturer = date("d/m/y à h:i");
        $user->save();
        $data["rendez_vous"] = Rendezvous::where(["supprimer" => 0])->get();
        return view('include.refresh_rendez_vous', $data);
    }

    public function refresh_delete_solde(Request $request)
    {
        $user = Soldes::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $data["soldes"] = Soldes::where(["supprimer" => 0])->get();
        return view('include.refresh_soldes', $data);
    }

    public function refresh_delete_activites(Request $request)
    {
        $user = Activites::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        return view('include.refresh_activites', $data);
    }


    public function refresh_delete_paie(Request $request)
    {
        $user = Listespaies::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $data["listespaies"] = Listespaies::where(["supprimer" => 0])->get();
        return view('include.refresh_listespaies', $data);
    }

    public function refresh_delete_listesfactures(Request $request)
    {
        $user = Listesfactures::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $data["listesfactures"] = Listesfactures::where(["supprimer" => 0])->get();
        return view('include.refresh_listesfactures', $data);
    }

    public function refresh_delete_rendez_vous(Request $request)
    {
        $user = Rendezvous::where('id', $request->id)->first();
        $user->supprimer = 1;
        $user->save();
        $data["rendez_vous"] = Rendezvous::where(["supprimer" => 0])->get();
        return view('include.refresh_rendez_vous', $data);
    }

    public function refresh_delete_type_infractions(Request $request)
    {
        $user = Type_infractions::where('id', $request->id)->first();
        $user->etat = 0;
        $user->save();
        $data["type_infractions"] = Type_infractions::where(["etat" => 1])->get();
        return view('include.refresh_Type_infractions', $data);
    }

    public function refresh_write(Request $request)
    {
        $groupes = Groupes::get();
        foreach ($groupes as $g)
        {
           $ressources = Ressources::get();
           foreach ($ressources as $r)
           {
                if(Writes::where(["groupe_id" => $g->id, "ressource_id" => $r->id])->get()->count() == 0)
                {
                    $id = Writes::get()->count() + 1;
                    $write = new Writes();
                    $write->id = $id;
                    $write->groupe_id = $g->id;
                    $write->ressource_id = $r->id;
                    $write->display = false;
                    $write->add = false;
                    $write->edit = false;
                    $write->delete = false;
                    $write->recherche =$g->nom . ' ' . $r->nom;
                    $write->save();
                }
           }
        }
        $data["writes"] = Writes::where(['groupe_id' => $request->groupe_id])->get();
        $data["groupe_id"] = $request->groupe_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_write', $data);
    }

    public function refresh_affectation_stock_vente(Request $request)
    {
        if(Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0, "id" => $request->stock_id])->first())
        {
            $stock = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0, "id" => $request->stock_id])->first();
            $data["nom"] = $stock->nom;
        }
        else
        {
            $data["nom"] = "Stock principal";
        }
        $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["pointdeventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["stock_id"] = $request->stock_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_affectation_stock_vente', $data);
    }

    public function refresh_affectation_table_utilisateur(Request $request)
    {
        $table = Tables::where(["etat" => 1, "supprimer" => 0, "id" => $request->table_id])->first();
        $data["nom"] = $table->nom;
        $data["nom_point_vente"] = Pointdeventes::where(["etat" => 1, "id" => $table->pointdeventes_id , "supprimer" => 0])->first()["nom"];
        $data["tables"] = Tables::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["pointdeventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        $data["table_id"] = $request->table_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        return view('include.refresh_affectation_table_utilisateur', $data);
    }

    public function refresh_article_stock(Request $request)
    {
        if($request->stock_id != 0)
        {
            $stock = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0, "id" => $request->stock_id])->first();
            $data["nom"] = $stock->nom;
        }
        else
        {
            $data["nom"] = "Stock principal";
        }
        if($request->stock_id == 0)
        {
            $data["articles"] = Articles::where(["supprimer" => 0])->get();
        }
        else
        {
            $data["articles"] = articlestocks::where(["supprimer" => 0, "stock_id" => $request->stock_id])->get();
        }
        $data["stocks"] = Stocks::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["pointdeventes"] = Pointdeventes::where(["etat" => 1, "user_id" => Auth::user()->id, "supprimer" => 0])->get();
        $data["stock_id"] = $request->stock_id;
        $data["groupes"] = Groupes::where(["etat" => 1])->get();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["societes"] = Societes::where(["etat" => 1])->get();
        $data["activites"] = Activites::where(["supprimer" => 0])->get();
        $data["utilisateurs"] = User::where(["etat" => 1,"id" => Auth::user()->id])->get();
        if(Auth::user()->role == 0)
        {
            $data["utilisateurs"] = User::where(["etat" => 1])->get();
        }
        return view('include.refresh_article_stock', $data);
    }

    public function refresh_partager_fichier(Request $request)
    {
        $data["fichier_document_id"] = $request->fichier_document_id;
        $data["groupe_id"] = $request->groupe_id;
        $data["utilisateurs"] = User::where(["etat" => 1])->get();
        $data["droit_fichiers"] = Droit_fichiers::where(["etat" => 1])->get();
        return view('include.refresh_partager_fichier', $data);
    }

    public function etat_display(Request $request)
    {
        $write = Writes::where('id', $request->write_id)->first();
        if($write->display == 1)
        {
            $write->display = 0;
            $write->save();
            return response()->json([[0]]);
        }
        else
        {
            $write->display = 1;
            $write->save();
            return response()->json([[1]]);
        }
    }

    public function etat_affectation_pointdeventes(Request $request)
    {
        $pointdeventes = pointdeventes::where('id', $request->pointdeventes_id)->first();
        if($pointdeventes->stock_id != -1)
        {
            $pointdeventes->stock_id = -1;
            $pointdeventes->save();
            return response()->json([[0]]);
        }
        else
        {
            $pointdeventes->stock_id = $request->stock_id;
            $pointdeventes->save();
            return response()->json([[1]]);
        }
    }

    public function etat_affectation_table_utilisateur(Request $request)
    {
        $tableId = $request->table_id;
        $userId = $request->user_id;

        $affectation = affectationstables::where([
            'table_id' => $tableId,
            'user_id'  => $userId
        ])->first();

        if ($affectation)
        {
            $affectation->delete();
            return response()->json([0]);
        }
        else
        {
            $newaffectation = new affectationstables();
            $id = affectationstables::get()->count() + 1;
            $newaffectation->id = $id;
            $newaffectation->table_id = $tableId;
            $newaffectation->user_id = $userId;
            // Ajoutez ici d'autres champs si nécessaire
            $newaffectation->save();

            $detailsaffectationstables = new detailsaffectationstables();
            $id2 = detailsaffectationstables::get()->count() + 1;
            $detailsaffectationstables->id = $id2;
            $detailsaffectationstables->table_id = $tableId;
            $detailsaffectationstables->user_id = $userId;
            // Ajoutez ici d'autres champs si nécessaire
            $detailsaffectationstables->save();

            return response()->json([1]);
        }
    }

    public function permission_fichier(Request $request)
    {
        $droits = Droit_fichiers::where(['user_id' => $request->user_id, 'fichier_documents_id' => $request->fichier_document_id, 'numero_permission' => $request->numero_permission])->get()->count();
        if($droits > 0)
        {
            $write = Droit_fichiers::where(['user_id' => $request->user_id, 'fichier_documents_id' => $request->fichier_document_id, 'numero_permission' => $request->numero_permission])->first();
            $write->delete();
            return response()->json([[0]]);
        }
        else
        {
            $droit_fichiers = new Droit_fichiers();
            $droit_fichiers->user_id = $request->user_id;
            $droit_fichiers->fichier_documents_id = $request->fichier_document_id;
            $droit_fichiers->numero_permission = $request->numero_permission;
            $droit_fichiers->etat = 1;
            $droit_fichiers->save();
            return response()->json([[1]]);
        }
    }

    public function etat_add(Request $request)
    {
        $write = Writes::where('id', $request->write_id)->first();
        if($write->add == 1)
        {
            $write->add = 0;
            $write->save();
            return response()->json([[0]]);
        }
        else
        {
            $write->add = 1;
            $write->save();
            return response()->json([[1]]);
        }
    }

    public function etat_edit(Request $request)
    {
        $write = Writes::where('id', $request->write_id)->first();
        if($write->edit == 1)
        {
            $write->edit = 0;
            $write->save();
            return response()->json([[0]]);
        }
        else
        {
            $write->edit = 1;
            $write->save();
            return response()->json([[1]]);
        }
    }

    public function etat_delete(Request $request)
    {
        $write = Writes::where('id', $request->write_id)->first();
        if($write->delete == 1)
        {
            $write->delete = 0;
            $write->save();
            return response()->json([[0]]);
        }
        else
        {
            $write->delete = 1;
            $write->save();
            return response()->json([[1]]);
        }
    }

    public function upload(Request $request)
    {
        $target_dir = "./storage/images/fichiers/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);


        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$_FILES['file']['name']))
        {
            $id = Fichiers::get()->count() + 1;
            $fichier = new Fichiers();
            $fichier->id = $id;
            $fichier->lien = $target_dir.$_FILES['file']['name'];
            $fichier->nom = $_FILES['file']['name'];
            $fichier->user_id = Auth::user()->id;
            $fichier->save();
            $status = 1;
        }

        return response()->json([[0]]);
    }



    public function upload_fichier_sortie(Request $request)
    {

        $target_dir = "./storage/images/fichiers/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $lien_unique = uniqid(time());
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$_FILES['file']['name']))
        {
            $id = Fichierss::get()->count() + 1;
            $fichier = new Fichierss();
            $fichier->id = $id;
            $fichier->lien = $target_dir.$_FILES['file']['name'];
            $fichier->nom = $_FILES['file']['name'];
            $fichier->numero_sortie = Auth::user()->id;
            $fichier->save();
            $status = 1;
        }

        echo Auth::user()->id;
    }

    public function upload_2(Request $request)
    {

        $target_dir = "./storage/images/fichiers/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);


        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$_FILES['file']['name']))
        {
            $id = Attaches::get()->count() + 1;
            $fichier = new Attaches();
            $fichier->id = $id;
            $fichier->lien = $target_dir.$_FILES['file']['name'];
            $fichier->nom = $_FILES['file']['name'];
            $fichier->save();
            $status = 1;
        }

        return response()->json([[0]]);
    }

    public function get_detail_p(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $travailleur = Travailleurs::where(["user_id" => $request->user_id, "contentieur_id" => $request->contentieux_id])->first();
        $payers = Payer::where(["user_id" => $request->user_id, "contentieur_id" => $request->contentieux_id])->get();
        $paie = 0;
        foreach ($payers as $p)
        {
            $paie = $paie + $p->montant;
        }
        $name =  $user['name'];
        $devise =  $travailleur['devise'];
        $total_p =  $travailleur['montant'];
        $role =  Groupes::where('id', $user['role'])->first()['nom'];
        return $name . '______________________________' . $role . '______________________________' . $devise . '______________________________' . $paie . '______________________________' . $total_p;
    }

    public function get_detail_p_1(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $paiements = Paiements::where(["user_id" => $request->user_id, "id" => $request->id])->first();
        $paies = Paies::where(["user_id" => $request->user_id, "paiement_id" => $paiements->id])->get();
        $paie = 0;
        foreach ($paies as $p)
        {
            $paie = $paie + $p->montant;
        }
        $name =  $user['name'];
        $devise =  $paiements['devise'];
        $total_p =  $paiements['montant'];
        $role =  Groupes::where('id', $user['role'])->first()['nom'];
        return $name . '______________________________' . $role . '______________________________' . $devise . '______________________________' . $paie . '______________________________' . $total_p;
    }

    public function get_detail_p_2(Request $request)
    {
        $client = Clients::where('id', $request->client_id)->first();
        $paiementsfactures = Paiementsfactures::where(["client_id" => $request->client_id, "id" => $request->id])->first();
        $paiesfactures = Paiesfactures::where(["client_id" => $request->client_id, "paiementsfactures_id" => $paiementsfactures->id])->get();
        $paie = 0;
        foreach ($paiesfactures as $p)
        {
            $paie = $paie + $p->montant;
        }
        $name =  $client['name'];
        $devise =  $paiementsfactures['devise'];
        $total_p =  $paiementsfactures['montant'];
        $role =  $client['adresse'];
        return $name . '______________________________' . $role . '______________________________' . $devise . '______________________________' . $paie . '______________________________' . $total_p;
    }


    public function save_p(Request $request)
    {
        $travailleur = Travailleurs::where(["id" => $request->id])->first();
        $user_id =  $travailleur['user_id'];
        $devise =  $travailleur['devise'];
        $contentieur_id =  $travailleur['contentieur_id'];
        $id = Payer::get()->count() + 1;
        $pa = new Payer();
        $pa->id = $id;
        $pa->user_id = $user_id;
        $pa->contentieur_id = $contentieur_id;
        $pa->montant = $request->montant_p;
        $pa->devise = $devise;
        $pa->taux = 2700;
        $pa->libelle = "Libelle";
        $pa->paye = 1;
        $pa->recherche = "Recherche";
        $pa->date_paye = date("d/m/Y");
        $pa->date_paye_valider = date("d/m/Y");
        $pa->save();
        $payers = Payer::where(["user_id" => $user_id, "contentieur_id" => $contentieur_id])->get();
        $paie = 0;
        foreach ($payers as $p)
        {
            $paie = $paie + $p->montant;
        }
        $travailleur->paie = $paie;
        $travailleur->save();
        $data["travailleurs"] = Travailleurs::where(["contentieur_id" => $contentieur_id])->get();
        $data["frais"] = Frais::where(["contentieur_id" => $contentieur_id])->get();
        return view('include.refresh_frais_contentieux_', $data);
    }

    public function save_p_1(Request $request)
    {
        $paiement = Paiements::where(["id" => $request->id])->first();
        $user_id =  $paiement['user_id'];
        $devise =  $paiement['devise'];
        $paiement_id =  $paiement['id'];
        $listespaie_id =  $paiement['listespaie_id'];
        $id = Paies::get()->count() + 1;
        $pa = new Paies();
        $pa->id = $id;
        $pa->user_id = $user_id;
        $pa->paiement_id = $paiement_id;
        $pa->montant = $request->montant_p;
        $pa->devise = $devise;
        $pa->taux = $request->taux_p;
        $pa->libelle = "Libelle";
        $pa->paye = 1;
        $pa->recherche = "Recherche";
        $pa->date_paye = date("d/m/Y");
        $pa->date_paye_valider = date("d/m/Y");
        $pa->save();
        $payers = Paies::where(["user_id" => $user_id, "paiement_id" => $paiement_id])->get();
        $paie = 0;
        foreach ($payers as $p)
        {
            $paie = $paie + $p->montant;
        }
        $paiement->paie = $paie;
        $paiement->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 2;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["paiements"] = Paiements::where(["listespaie_id" => $listespaie_id])->get();
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        return view('include.refresh_paiements', $data);
    }

    public function save_p_2(Request $request)
    {
        $paiementsfactures = Paiementsfactures::where(["id" => $request->id])->first();
        $client_id =  $paiementsfactures['client_id'];
        $devise =  $paiementsfactures['devise'];
        $paiementsfactures_id =  $paiementsfactures['id'];
        $listesfactures_id =  $paiementsfactures['listesfactures_id'];
        $id = Paiesfactures::get()->count() + 1;
        $pa = new Paiesfactures();
        $pa->id = $id;
        $pa->client_id = $client_id;
        $pa->user_id = Auth::user()->id;
        $pa->paiementsfactures_id = $paiementsfactures_id;
        $pa->montant = $request->montant_p;
        $pa->devise = $devise;
        $pa->taux = $request->taux_p;
        $pa->libelle = "Libelle";
        $pa->paye = 1;
        $pa->recherche = "Recherche";
        $pa->date_paye = date("d/m/Y");
        $pa->date_paye_valider = date("d/m/Y");
        $pa->save();
        $payers = Paiesfactures::where(["client_id" => $client_id, "paiementsfactures_id" => $paiementsfactures_id])->get();
        $paie = 0;
        foreach ($payers as $p)
        {
            $paie = $paie + $p->montant;
        }
        $paiementsfactures->paie = $paie;
        $paiementsfactures->save();
        $groupe_user_id = Auth::user()->role;
        $data["ressource_id_1"] = 15;
        $data["groupe_user_id"] = $groupe_user_id;
        $data["utilisateurs"] = User::where(function($query){
            $query->where('role', '<>', 0);
        })->where(function($query){
            $query->where('etat', '=', 1);
        })->get();
        $data["acces"] = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get();
        $data["paiementsfactures"] = Paiementsfactures::where(["listesfactures_id" => $listesfactures_id])->get();
        $data["listesfactures_id"] = $request->listesfactures_id;
        $data["activite_id"] = $request->activite_id_f;
        $data["nom_activite"] = strtoupper(Activites::where('id', $request->activite_id_f)->first()["nom"]);
        $data["nb_client"] = Clients::where('id', $request->activite_id)->get()->count();
        return view('include.refresh_paiementsfactures', $data);
    }

    public function print_bilan()
    {
        $pdf = new FPDF();
        $pdf->AddPage("L");
        $pdf->Image("./connexion/images/logo_mahuwaproduction.png", 10, 10, 20, 30);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Ln(20);
        $pdf->Cell(277, 10, iconv('UTF-8', 'Windows-1252', 'List Product'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln(15);
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Prod number'), 1, 0, 'C');
        $pdf->Cell(32, 10, iconv('UTF-8', 'Windows-1252', 'Castomer reference'), 1, 0, 'C');
        $pdf->Cell(80, 10, iconv('UTF-8', 'Windows-1252', 'Prod name'), 1, 0, 'C');
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Type prod'), 1, 0, 'C');
        $pdf->Cell(15, 10, iconv('UTF-8', 'Windows-1252', 'Uom'), 1, 0, 'C');
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Part number'), 1, 0, 'C');
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Prod Brand'), 1, 0, 'C');
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Created'), 1, 0, 'C');
        $pdf->Cell(25, 10, iconv('UTF-8', 'Windows-1252', 'Status'), 1, 0, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(144, 151, 196);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ""), 1, 0, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(32, 5, iconv('UTF-8', 'Windows-1252', 'RF123'), 1, 0, 'C');
        $pdf->Cell(80, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(15, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Cell(25, 5, iconv('UTF-8', 'Windows-1252', ''), 1, 0, 'C');
        $pdf->Ln(5);
        $pdf->Output("F", "list.pdf");
    }

    public function upload_profil(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('input_user_img_profil')) {
            $file = $request->file('input_user_img_profil');

            // Vérifier si c'est une image
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeTypes))
            {
                return response()->json(['message' => 'Seules les images sont autorisées'], 400);
            }


            $target_dir = "./storage/images/user/";
            $target_file = $target_dir . basename($_FILES["input_user_img_profil"]["name"]);
            if (move_uploaded_file($_FILES["input_user_img_profil"]["tmp_name"], $target_dir.$_FILES['input_user_img_profil']['name']))
            {
                $user->image = $target_dir.$_FILES['input_user_img_profil']['name'];
                $user->save();
            }
        }

        return response()->json([$target_dir.$_FILES['input_user_img_profil']['name']]);
    }

    public function upload_profil_add(Request $request)
    {
        if ($request->hasFile('input_user_img_profil')) {
            $file = $request->file('input_user_img_profil');

            // Vérifier si c'est une image
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeTypes))
            {
                return response()->json(['message' => 'Seules les images sont autorisées'], 400);
            }


            $target_dir = "./storage/images/user/";
            $target_file = $target_dir . basename($_FILES["input_user_img_profil"]["name"]);
            if (move_uploaded_file($_FILES["input_user_img_profil"]["tmp_name"], $target_dir.$_FILES['input_user_img_profil']['name']))
            {
                return response()->json([$target_dir.$_FILES['input_user_img_profil']['name']]);
            }
        }
    }

    public function upload_logo_add(Request $request)
    {
        if ($request->hasFile('input_user_img_profil')) {
            $file = $request->file('input_user_img_profil');

            // Vérifier si c'est une image
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeTypes))
            {
                return response()->json(['message' => 'Seules les images sont autorisées'], 400);
            }


            $target_dir = "./storage/images/activiter/";
            $target_file = $target_dir . basename($_FILES["input_user_img_profil"]["name"]);
            if (move_uploaded_file($_FILES["input_user_img_profil"]["tmp_name"], $target_dir.$_FILES['input_user_img_profil']['name']))
            {
                return response()->json([$target_dir.$_FILES['input_user_img_profil']['name']]);
            }
        }
    }

    public function upload_profil_edit(Request $request)
    {
        if ($request->hasFile('edit_input_user_img_profil')) {
            $file = $request->file('edit_input_user_img_profil');

            // Vérifier si c'est une image
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeTypes))
            {
                return response()->json(['message' => 'Seules les images sont autorisées'], 400);
            }


            $target_dir = "./storage/images/user/";
            $target_file = $target_dir . basename($_FILES["edit_input_user_img_profil"]["name"]);
            if (move_uploaded_file($_FILES["edit_input_user_img_profil"]["tmp_name"], $target_dir.$_FILES['edit_input_user_img_profil']['name']))
            {
                return response()->json([$target_dir.$_FILES['edit_input_user_img_profil']['name']]);
            }
        }
    }

    public function liberer_table(Request $request)
    {
        $table_id = $request->table_id;
        $table = Tables::find($table_id);
        if ($table)
        {
            $table->occupee = 0;
            $table->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function nettoyer_table(Request $request)
    {
        $table_id = $request->table_id;
        $table = Tables::find($table_id);
        if ($table)
        {
            $table->propre = 0;
            $table->save();
            $data["tables"] = Tables::where(["etat" => 1])->get();
            return view('include.refresh_tables_d', $data);
        }
        return response()->json(['success' => false], 404);
    }


    public function get_tables_select(Request $request)
    {
        $user = Auth::user();
        $tables = Tables::all();
        $html = '<option selected value="">Selectionnez une table</option>';

        foreach ($tables as $data)
        {
            $affecte = affectationstables::where('user_id', $user->id)
                                        ->where('table_id', $data->id)
                                        ->exists();

            // Récupération du nom du point de vente
            $pointDeVente = \App\Models\pointdeventes::where('id', $data->pointdeventes_id)->first();
            $nomPointDeVente = $pointDeVente ? $pointDeVente->nom : 'N/A';

            // Déterminer la valeur de data-occupee
            $occupee = $data->occupee ? 1 : 0;

            if ($user->role == 0)
            {
                // Administrateur : voit toutes les tables
                $icone = ($occupee == 0) ? '🟢' : '🔴';
                $html .= '<option value="'.$data->id.'" data-occupee="'.$occupee.'">'.$icone.' '.e($data->nom).' ('.$nomPointDeVente.')</option>';
            }
            else
            {
                // Serveur : voit uniquement les tables affectées
                if ($affecte)
                {
                    $icone = ($occupee == 0) ? '🟢' : '🔴';
                    $html .= '<option value="'.$data->id.'" data-occupee="'.$occupee.'">'.$icone.' '.e($data->nom).' ('.$nomPointDeVente.')</option>';
                }
            }
        }

        return $html;
    }

    public function upload_logo_edit(Request $request)
    {
        if ($request->hasFile('edit_input_user_img_profil')) {
            $file = $request->file('edit_input_user_img_profil');

            // Vérifier si c'est une image
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeTypes))
            {
                return response()->json(['message' => 'Seules les images sont autorisées'], 400);
            }


            $target_dir = "./storage/images/activiter/";
            $target_file = $target_dir . basename($_FILES["edit_input_user_img_profil"]["name"]);
            if (move_uploaded_file($_FILES["edit_input_user_img_profil"]["tmp_name"], $target_dir.$_FILES['edit_input_user_img_profil']['name']))
            {
                return response()->json([$target_dir.$_FILES['edit_input_user_img_profil']['name']]);
            }
        }
    }

    public function submitfilters(Request $request)
    {
        $filters = $request->only(['encodeur', 'date', 'eleve', 'genre', 'classe', 'parent']);
        // Exemple : sauvegarde en session ou traitement personnalisé
        session(['beneficiaire_filters' => $filters]);
        return response()->json(['success' => true]);
    }

    public function import_excel_categorie(Request $request)
    {
        if (!$request->hasFile('excel_file'))
        {
            return response()->json(['message' => 'Aucun fichier envoyé'], 400);
        }

        $file = $request->file('excel_file');
        $allowedExtensions = ['xls', 'xlsx', 'xlsm', 'xlsb', 'csv'];

        if (!in_array($file->getClientOriginalExtension(), $allowedExtensions))
            {
            return response()->json(['message' => 'Seuls les fichiers Excel sont autorisés'], 400);
        }

        // Dossier public à la racine (pas de sous-dossier)
        $targetDir = public_path();

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $originalName . '_' . time() . '.' . $file->getClientOriginalExtension();

        try
        {

            $file->move($targetDir, $safeName);

            $spreadsheet = IOFactory::load($safeName);

            $worksheet = $spreadsheet->getActiveSheet();

            $highestRow = $worksheet->getHighestRow();

            $nb_importation = 0;

            $categories = Societes::where(["etat" => 1])->get();

            for ($row = 2; $row <= $highestRow; $row++)
            {
                $n = 0;
                if(strlen(trim($worksheet->getCell('B' . $row)->getValue())) != 0)
                {
                    $numero = $worksheet->getCell('A' . $row)->getValue();
                    $nom = $worksheet->getCell('B' . $row)->getValue();
                    $code = "Code";
                    if(strlen(trim($worksheet->getCell('C' . $row)->getValue())) != 0)
                    {
                        $description = $worksheet->getCell('C' . $row)->getValue();
                    }
                    else
                    {
                        $description = "";
                    }

                    $recherche = $worksheet->getCell('B' . $row)->getValue();

                    foreach ($categories as $cat)
                    {
                        if(strtolower($cat->nom) == strtolower($nom))
                        {
                            $n++;
                        }
                    }

                    if(($n == 0))
                    {
                        // Définir le fuseau horaire de Lubumbashi
                        date_default_timezone_set('Africa/Lubumbashi');

                        $id = Societes::get()->count() + 1;

                        $categorie = new Societes();
                        $categorie->id = $id;
                        $categorie->nom = $nom;
                        $categorie->code = $code;
                        $categorie->description = $description;
                        $categorie->etat = 1;
                        $categorie->recherche = $recherche;
                        $categorie->save();
                        $nb_importation++;
                    }
                }
            }
            if($nb_importation != 0)
            {
                return response()->json([
                    'message' => $nb_importation . ' importation(s) effectuée(s) avec succès',
                    'path' => $safeName
                ], 200);
            }
            else
            {
                return response()->json([
                    'message' => $nb_importation . ' importation(s) effectuée(s) avec succès',
                    'path' => $safeName
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }

    public function export_excel_categorie()
    {
        try {
            // 1. Nouveau classeur
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 2. En-têtes (3 colonnes)
            $sheet->setCellValue('A1', 'N°');
            $sheet->setCellValue('B1', 'NOM');
            $sheet->setCellValue('C1', 'DESCRIPTION');

            // 3. Style des en-têtes
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ];
            $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

            // 4. Récupérer les catégories actives
            $categories = Societes::where('etat', 1)->orderBy('id')->get();

            $row = 2;
            foreach ($categories as $categorie) {
                $sheet->setCellValue('A' . $row, $categorie->id);          // N°
                $sheet->setCellValue('B' . $row, $categorie->nom);        // NOM
                $sheet->setCellValue('C' . $row, $categorie->description); // DESCRIPTION
                $row++;
            }

            // 5. Ajuster les colonnes
            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // 6. Bordures pour toutes les cellules remplies
            $highestRow = $row - 1;
            if ($highestRow >= 1) {
                $sheet->getStyle('A1:C' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            }

            // 7. Générer et télécharger
            $writer = new Xlsx($spreadsheet);
            $fileName = 'export_categories_' . date('Y-m-d_H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'exportation : ' . $e->getMessage()
            ], 500);
        }
    }

    public function export_categories_pdf()
    {
        // 1) Récupérer toutes les catégories actives (ou toutes selon besoin)
        $categories = Societes::where('etat', 1)->orderBy('id')->get();

        // 2) Initialiser le PDF
        date_default_timezone_set('Africa/Lubumbashi');
        $pdf = new FPDF('L', 'mm', 'A4'); // paysage, A4
        $pdf->AddPage();

        // ========== EN-TÊTE ==========
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(30, 30, 80);
        $pdf->Cell(0, 10, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'LISTE DES CATÉGORIES'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 6, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'Catégories actives'), 0, 1, 'C');
        $pdf->Ln(4);

        // Date d'export
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 4, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'Exporté le ' . date('d/m/Y H:i:s')), 0, 1, 'R');
        $pdf->Ln(2);

        // ========== TABLEAU ==========
        $margeGauche = 10;
        $largeurDisponible = 297 - (2 * $margeGauche); // 277 mm

        // Proportions des colonnes (N°, NOM, DESCRIPTION)
        $proportions = [
            'num'  => 8,   // 8%
            'nom'  => 35,  // 35%
            'desc' => 57   // 57%
        ];

        // Calcul des largeurs en mm
        $largeurs = [];
        foreach ($proportions as $key => $pct) {
            $largeurs[$key] = round(($pct / 100) * $largeurDisponible, 1);
        }
        // Ajustement pour que la somme soit exacte
        $somme = array_sum($largeurs);
        if ($somme != $largeurDisponible) {
            $derniereCle = array_key_last($largeurs);
            $largeurs[$derniereCle] += ($largeurDisponible - $somme);
        }

        $pdf->SetLeftMargin($margeGauche);
        $pdf->SetX($margeGauche);

        // Style de l'en‑tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(50, 50, 100);
        $pdf->SetDrawColor(0, 0, 0);

        $pdf->Cell($largeurs['num'], 9, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'N°'), 1, 0, 'C', true);
        $pdf->Cell($largeurs['nom'], 9, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'NOM'), 1, 0, 'C', true);
        $pdf->Cell($largeurs['desc'], 9, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'DESCRIPTION'), 1, 1, 'C', true);

        // Corps du tableau
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        if ($categories->isEmpty()) {
            $pdf->SetX($margeGauche);
            $pdf->Cell($largeurDisponible, 10, iconv('UTF-8', 'Windows-1252//TRANSLIT', 'Aucune catégorie trouvée'), 1, 1, 'C');
        } else {
            $numero = 1;
            $fill = false;
            foreach ($categories as $cat) {
                $pdf->SetX($margeGauche);
                $pdf->Cell($largeurs['num'], 8, $numero, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['nom'], 8, iconv('UTF-8', 'Windows-1252//TRANSLIT', $cat->nom), 1, 0, 'L', $fill);
                // Troncature de la description si trop longue
                $desc = $cat->description;
                if (strlen($desc) > 60) {
                    $desc = substr($desc, 0, 57) . '...';
                }
                $pdf->Cell($largeurs['desc'], 8, iconv('UTF-8', 'Windows-1252//TRANSLIT', $desc), 1, 1, 'L', $fill);
                $numero++;
                $fill = !$fill;
            }
        }

        // ========== NOM DU FICHIER ==========
        $nom_fichier = 'Categories_' . date('Ymd_His') . '.pdf';

        $pdf->Output('F', $nom_fichier);

        return response()->download($nom_fichier, $nom_fichier, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function import_excel_article(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez être connecté.'], 401);
        }

        if (!$request->hasFile('excel_file')) {
            return response()->json(['message' => 'Aucun fichier envoyé'], 400);
        }

        $file = $request->file('excel_file');
        $allowedExtensions = ['xls', 'xlsx', 'xlsm', 'xlsb', 'csv'];
        if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
            return response()->json(['message' => 'Seuls les fichiers Excel sont autorisés'], 400);
        }

        $targetDir = public_path();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $originalName . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $safeName);

        try {
            $spreadsheet = IOFactory::load($safeName);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            $nb_importation = 0;
            $nb_erreurs = 0;
            $erreurs = [];

            // Récupération des référentiels
            $categories = Societes::where('etat', 1)->get()->keyBy('id');
            $activites  = Activites::where('etat', 1)->get()->keyBy('id');
            $mesures    = Mesures::where('etat', 1)->get()->keyBy('id');

            // ------------------------------------------------------------
            // 1. Construction d'un index des articles existants par mesure_id
            //    (nom en minuscule)
            // ------------------------------------------------------------
            $existingByMeasure = [];
            $allArticles = Articles::all(['mesure_id', 'nom_article']);
            foreach ($allArticles as $art) {
                $mid = $art->mesure_id;
                if (!isset($existingByMeasure[$mid])) {
                    $existingByMeasure[$mid] = [];
                }
                $existingByMeasure[$mid][] = strtolower(trim($art->nom_article));
            }

            // Pour les articles déjà importés dans cette session (éviter doublons entre lignes)
            $importedByMeasure = [];

            DB::beginTransaction();
            date_default_timezone_set('Africa/Lubumbashi');

            // Fonction de normalisation pour les types de stockage (accent, casse)
            $normalizeType = function($str) {
                $str = trim($str);
                $accents = [
                    'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A',
                    'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
                    'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O',
                    'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o',
                    'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E',
                    'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
                    'Ç'=>'C','ç'=>'c',
                    'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I',
                    'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
                    'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U',
                    'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
                    'ÿ'=>'y','Ÿ'=>'Y'
                ];
                return strtolower(strtr($str, $accents));
            };

            // Fonction de normalisation pour les mesures : supprime les espaces, met en minuscule
            $normalizeMeasure = function($str) {
                return strtolower(preg_replace('/\s+/', '', trim($str)));
            };

            for ($row = 2; $row <= $highestRow; $row++) {
                $valide = true;
                $ligneErreurs = [];

                try {
                    $nomArticle = trim($worksheet->getCell('C' . $row)->getValue() ?? '');
                    $categorieNom = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                    $prixDetailRaw = $worksheet->getCell('D' . $row)->getValue();
                    $prixGrosRaw = $worksheet->getCell('E' . $row)->getValue();
                    $tailleLotRaw = $worksheet->getCell('F' . $row)->getValue();
                    $deviseRaw = trim($worksheet->getCell('G' . $row)->getValue() ?? '');
                    $mesureNom = trim($worksheet->getCell('H' . $row)->getValue() ?? '');
                    $typeStockageRaw = trim($worksheet->getCell('I' . $row)->getValue() ?? '');
                    $seuilMinRaw = $worksheet->getCell('J' . $row)->getValue();
                    $seuilMaxRaw = $worksheet->getCell('K' . $row)->getValue();
                    $dateExpirationRaw = $worksheet->getCell('L' . $row)->getValue();
                    $description = trim($worksheet->getCell('M' . $row)->getValue() ?? '');
                    $activiteNom = trim($worksheet->getCell('N' . $row)->getValue() ?? '');

                    // --- Validations obligatoires ---
                    if (empty($nomArticle)) { $ligneErreurs[] = "Nom vide"; $valide = false; }
                    if (empty($categorieNom)) { $ligneErreurs[] = "Catégorie vide"; $valide = false; }
                    if (empty($prixDetailRaw) || !is_numeric(str_replace(',', '.', trim($prixDetailRaw)))) {
                        $ligneErreurs[] = "Prix détail invalide"; $valide = false;
                    }
                    if (empty($prixGrosRaw) || !is_numeric(str_replace(',', '.', trim($prixGrosRaw)))) {
                        $ligneErreurs[] = "Prix gros invalide"; $valide = false;
                    }
                    if (empty($tailleLotRaw) || !is_numeric($tailleLotRaw)) {
                        $ligneErreurs[] = "Taille lot invalide"; $valide = false;
                    }
                    if (empty($deviseRaw)) { $ligneErreurs[] = "Devise vide"; $valide = false; }
                    if (empty($mesureNom)) { $ligneErreurs[] = "Mesure vide"; $valide = false; }
                    if (empty($typeStockageRaw)) { $ligneErreurs[] = "Type stockage vide"; $valide = false; }
                    // La date n'est plus obligatoire

                    // --- Catégorie ---
                    $societeId = 0;
                    $cat = $categories->first(fn($c) => strtolower($c->nom) == strtolower($categorieNom));
                    if ($cat) {
                        $societeId = $cat->id;
                    } else {
                        $ligneErreurs[] = "Catégorie '$categorieNom' introuvable"; $valide = false;
                    }

                    // --- Activité (optionnelle) ---
                    $activiteId = 0;
                    if (!empty($activiteNom)) {
                        $act = $activites->first(fn($a) => strtolower($a->nom) == strtolower($activiteNom));
                        if ($act) $activiteId = $act->id;
                    }

                    // --- Mesure ---
                    $mesureId = 0;
                    if (!empty($mesureNom)) {
                        $normalizedInput = $normalizeMeasure($mesureNom);
                        $mes = $mesures->first(function($m) use ($normalizedInput, $normalizeMeasure) {
                            return $normalizeMeasure($m->nom) == $normalizedInput;
                        });
                        if ($mes) {
                            $mesureId = $mes->id;
                        } else {
                            $ligneErreurs[] = "Mesure '$mesureNom' introuvable (vérifiez l'orthographe et les espaces)";
                            $valide = false;
                        }
                    }

                    // ------------------------------------------------------------
                    // 2. Vérification des doublons par (mesure_id, nom)
                    //    (si mesure_id est 0, on ignore car la mesure est invalide)
                    // ------------------------------------------------------------
                    if ($valide && $mesureId > 0) {
                        $nomLower = strtolower(trim($nomArticle));

                        // Vérifier dans les existants en base
                        $existsInDb = isset($existingByMeasure[$mesureId])
                                    && in_array($nomLower, $existingByMeasure[$mesureId]);

                        // Vérifier dans les importés lors de cette session
                        $existsInSession = isset($importedByMeasure[$mesureId])
                                        && in_array($nomLower, $importedByMeasure[$mesureId]);

                        if ($existsInDb || $existsInSession) {
                            $ligneErreurs[] = "Nom '$nomArticle' déjà utilisé pour cette mesure (ID $mesureId)";
                            $valide = false;
                        }
                    }

                    // --- Parsing des nombres ---
                    $prixDetail = floatval(str_replace(',', '.', trim($prixDetailRaw)));
                    $prixGros = floatval(str_replace(',', '.', trim($prixGrosRaw)));
                    $tailleLot = intval($tailleLotRaw);
                    if ($prixDetail <= 0) { $ligneErreurs[] = "Prix détail doit être > 0"; $valide = false; }
                    if ($prixGros <= 0) { $ligneErreurs[] = "Prix gros doit être > 0"; $valide = false; }
                    if ($tailleLot <= 0) { $ligneErreurs[] = "Taille lot doit être > 0"; $valide = false; }
                    $prix = $prixDetail;

                    // --- Devise ---
                    $devise = 0;
                    $deviseUpper = strtoupper(trim($deviseRaw));
                    if ($deviseUpper == 'CDF') { $devise = 1; }
                    elseif ($deviseUpper == 'USD') { $devise = 0; }
                    else { $ligneErreurs[] = "Devise '$deviseRaw' invalide (utilisez CDF ou USD)"; $valide = false; }

                    // --- Type de stockage ---
                    $typeNormalized = $normalizeType($typeStockageRaw);
                    $determineTerms = ['determine', 'déterminé', 'oui', 'yes', '1', 'true', 'vrai'];
                    $avoirStock = in_array($typeNormalized, $determineTerms) ? 1 : 0;

                    // --- Seuils (conditionnels) ---
                    $seuilMin = 0;
                    $seuilMax = 0;
                    if ($avoirStock == 1) {
                        if (empty($seuilMinRaw) || !is_numeric(str_replace(',', '.', trim($seuilMinRaw)))) {
                            $ligneErreurs[] = "Seuil minimum requis pour stock déterminé"; $valide = false;
                        } else {
                            $seuilMin = floatval(str_replace(',', '.', trim($seuilMinRaw)));
                            if ($seuilMin < 0) { $ligneErreurs[] = "Seuil min ne peut être négatif"; $valide = false; }
                        }
                        if (empty($seuilMaxRaw) || !is_numeric(str_replace(',', '.', trim($seuilMaxRaw)))) {
                            $ligneErreurs[] = "Seuil maximum requis pour stock déterminé"; $valide = false;
                        } else {
                            $seuilMax = floatval(str_replace(',', '.', trim($seuilMaxRaw)));
                            if ($seuilMax < 0) { $ligneErreurs[] = "Seuil max ne peut être négatif"; $valide = false; }
                        }
                        if (isset($seuilMin) && isset($seuilMax) && $seuilMin > $seuilMax) {
                            $ligneErreurs[] = "Seuil min ($seuilMin) > seuil max ($seuilMax)"; $valide = false;
                        }
                    }

                    // --- Date d'expiration (avec gestion 00/00/0000) ---
                    $dateExpiration = null;
                    $dateRaw = trim($dateExpirationRaw ?? '');
                    if ($dateRaw === '' || $dateRaw === '00/00/0000') {
                        $dateExpiration = '00/00/0000';
                    } elseif (is_numeric($dateRaw)) {
                        try {
                            $dt = Date::excelToDateTimeObject($dateRaw);
                            $dateExpiration = $dt->format('d/m/Y');
                        } catch (\Exception $e) {
                            $ligneErreurs[] = "Date expiration Excel invalide (série)";
                            $valide = false;
                        }
                    } else {
                        $dt = \DateTime::createFromFormat('d/m/Y', $dateRaw);
                        if ($dt && $dt->format('d/m/Y') === $dateRaw) {
                            $dateExpiration = $dt->format('d/m/Y');
                        } else {
                            $ligneErreurs[] = "Date expiration invalide (format JJ/MM/AAAA attendu)";
                            $valide = false;
                        }
                    }

                    // --- Enregistrement ---
                    if ($valide)
                    {
                        $id = Articles::get()->count() + 1;
                        $article = new Articles();
                        $article->id = $id;
                        $article->user_id = Auth::id();
                        $article->societe_id = $societeId;
                        $article->nom_article = $nomArticle;
                        $article->prix = $prix;
                        $article->devise = $devise;
                        $article->seuil_minimum = $seuilMin;
                        $article->seuil_maximum = $seuilMax;
                        $article->prix_detail = $prixDetail;
                        $article->prix_gros = $prixGros;
                        $article->taille_lot = $tailleLot;
                        $article->stock = 0;
                        $article->date_expiration = $dateExpiration;
                        $article->date_creation = date("d/m/Y");
                        $article->description = $description;
                        $article->activite_id = $activiteId;
                        $article->mesure_id = $mesureId;
                        $article->avoir_stock = $avoirStock;
                        $article->image = '';
                        $article->save();

                        // Ajouter ce nom dans la liste des importés (pour éviter doublons entre lignes)
                        if ($mesureId > 0) {
                            if (!isset($importedByMeasure[$mesureId])) {
                                $importedByMeasure[$mesureId] = [];
                            }
                            $importedByMeasure[$mesureId][] = strtolower(trim($nomArticle));
                        }

                        $nb_importation++;
                    } else {
                        $erreurs[] = "Ligne $row : " . implode('; ', $ligneErreurs);
                        $nb_erreurs++;
                    }

                } catch (\Exception $e) {
                    $erreurs[] = "Ligne $row : Exception - " . $e->getMessage();
                    $nb_erreurs++;
                }
            }

            DB::commit();

            $message = $nb_importation . ' article(s) importé(s) avec succès.';
            if ($nb_erreurs > 0) {
                $message .= ' ' . $nb_erreurs . ' ligne(s) en erreur (non importées).';
            }

            return response()->json([
                'message' => $message,
                'details' => $erreurs,
                'nb_importes' => $nb_importation,
                'nb_erreurs' => $nb_erreurs,
                'path' => $safeName
            ], $nb_importation > 0 ? 200 : 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur critique : ' . $e->getMessage()], 500);
        }
    }

    public function export_excel_article(Request $request)
    {
        // 1) Récupérer les filtres
        $filterNom      = $request->input('nom', '');
        $filterCategorie = $request->input('categorie', 'all');
        $filterActivite  = $request->input('activite', 'all');
        $filterUser      = $request->input('user', 'all');

        // 2) Construire la requête avec les filtres (identique au PDF)
        $query = Articles::orderBy('id');
        if (!empty($filterNom)) {
            $query->where('nom_article', 'like', '%' . $filterNom . '%');
        }
        if ($filterCategorie !== 'all' && str_starts_with($filterCategorie, 'cat_')) {
            $categorieId = substr($filterCategorie, 4);
            $query->where('societe_id', $categorieId);
        }
        if ($filterActivite !== 'all') {
            if ($filterActivite === 'none') {
                $query->where(function($q) {
                    $q->where('activite_id', 0)->orWhereNull('activite_id');
                });
            } elseif (str_starts_with($filterActivite, 'act_')) {
                $activiteId = substr($filterActivite, 4);
                $query->where('activite_id', $activiteId);
            }
        }
        if ($filterUser !== 'all') {
            $query->where('user_id', $filterUser);
        }

        $articles = $query->get();

        // Charger les collections pour les libellés
        $categories = Societes::all()->keyBy('id');
        $mesures    = Mesures::all()->keyBy('id');
        $users      = User::all()->keyBy('id');

        // 3) Création du classeur
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ========== TITRE ET SOUS-TITRE ==========
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'LISTE DES ARTICLES');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => '0A192F'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->setCellValue('A2', 'Inventaire complet');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '505050'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Filtres
        $filtresActifs = [];
        if (!empty($filterNom)) {
            $filtresActifs[] = 'Nom : "' . $filterNom . '"';
        }
        if ($filterCategorie !== 'all' && str_starts_with($filterCategorie, 'cat_')) {
            $catId = substr($filterCategorie, 4);
            $catNom = $categories[$catId]->nom ?? 'Catégorie inconnue';
            $filtresActifs[] = 'Catégorie : ' . $catNom;
        }
        if ($filterActivite === 'none') {
            $filtresActifs[] = 'Sans activité';
        } elseif ($filterActivite !== 'all' && str_starts_with($filterActivite, 'act_')) {
            $actId = substr($filterActivite, 4);
            $actNom = Activites::find($actId)->nom ?? 'Activité inconnue';
            $filtresActifs[] = 'Activité : ' . $actNom;
        }
        if ($filterUser !== 'all') {
            $userName = $users[$filterUser]->name ?? 'Utilisateur inconnu';
            $filtresActifs[] = 'Utilisateur : ' . $userName;
        }

        $rowActuelle = 3;
        if (!empty($filtresActifs)) {
            $sheet->setCellValue('A' . $rowActuelle, 'Filtres : ' . implode('  |  ', $filtresActifs));
            $sheet->mergeCells('A' . $rowActuelle . ':L' . $rowActuelle);
            $sheet->getStyle('A' . $rowActuelle)->applyFromArray([
                'font' => [
                    'italic' => true,
                    'size' => 9,
                    'color' => ['rgb' => '808080'],
                    'name' => 'Arial'
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
            $rowActuelle++;
        }

        // Date d'export
        $sheet->setCellValue('A' . $rowActuelle, 'Exporté le ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $rowActuelle . ':L' . $rowActuelle);
        $sheet->getStyle('A' . $rowActuelle)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 8,
                'color' => ['rgb' => '969696'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT
            ]
        ]);
        $rowActuelle++;
        $rowActuelle++;

        // ========== EN-TÊTES (12 colonnes) ==========
        $headers = [
            'N°',
            'CATÉGORIE',
            'NOM',
            'PRIX DÉTAIL',
            'PRIX GROS',
            'LOT',
            'MESURE',
            'STOCKAGE',
            'SEUIL MIN',
            'SEUIL MAX',
            'DATE D\'EXPIRATION',
            'DESCRIPTION'
        ];

        $col = 'A';
        $headerRow = $rowActuelle;
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }

        $headerRange = 'A' . $headerRow . ':L' . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0A192F']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'B0B0B0']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // ========== REMPLIR LES DONNÉES ==========
        $row = $headerRow + 1;
        if ($articles->isEmpty()) {
            $sheet->mergeCells('A' . $row . ':L' . $row);
            $sheet->setCellValue('A' . $row, 'Aucun article trouvé avec ces filtres');
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => 'E31B23']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        } else {
            $fill = false;
            foreach ($articles as $index => $art) {
                $categorieNom = $categories[$art->societe_id]->nom ?? '';
                $mesureNom    = $mesures[$art->mesure_id]->nom   ?? '';
                $devise = ($art->devise == 0) ? 'USD' : 'CDF';

                $prixDetail = number_format($art->prix_detail, 0, ',', ' ') . ' (' . $devise . ')';
                $prixGros   = number_format($art->prix_gros, 0, ',', ' ') . ' (' . $devise . ')';
                $stockage   = ($art->avoir_stock == 1) ? 'DET' : 'INT';

                // === DATE D'EXPIRATION : exactement comme le PDF (brute) ===
                $dateExp = (string)$art->date_expiration;

                $data = [
                    $index + 1,
                    $categorieNom,
                    $art->nom_article,
                    $prixDetail,
                    $prixGros,
                    $art->taille_lot,
                    $mesureNom,
                    $stockage,
                    $art->seuil_minimum,
                    $art->seuil_maximum,
                    $dateExp,
                    $art->description
                ];

                $col = 'A';
                foreach ($data as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }

                // Alternance de couleurs
                $fillColor = $fill ? 'F5F5F5' : 'FFFFFF';
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $fillColor]
                    ],
                    'font' => [
                        'size' => 8,
                        'name' => 'Arial'
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D0D0']
                        ]
                    ]
                ]);

                $fill = !$fill;
                $row++;
            }

            // Ajuster automatiquement la largeur des colonnes
            foreach (range('A', 'L') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // ========== PIED DE PAGE ==========
        $rowActuelFinal = $row + 1;
        $sheet->setCellValue('A' . $rowActuelFinal, 'Document généré automatiquement');
        $sheet->mergeCells('A' . $rowActuelFinal . ':L' . $rowActuelFinal);
        $sheet->getStyle('A' . $rowActuelFinal)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 7,
                'color' => ['rgb' => 'B0B0B0'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // ========== GÉNÉRATION ==========
        $fileName = 'articles_export_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function export_article_pdf()
    {
        // Filtres
        $filterNom      = request()->input('nom', '');
        $filterCategorie = request()->input('categorie', 'all');
        $filterActivite  = request()->input('activite', 'all');
        $filterUser      = request()->input('user', 'all');

        $query = Articles::orderBy('id');
        if (!empty($filterNom)) {
            $query->where('nom_article', 'like', '%' . $filterNom . '%');
        }
        if ($filterCategorie !== 'all' && str_starts_with($filterCategorie, 'cat_')) {
            $categorieId = substr($filterCategorie, 4);
            $query->where('societe_id', $categorieId);
        }
        if ($filterActivite !== 'all') {
            if ($filterActivite === 'none') {
                $query->where(function($q) {
                    $q->where('activite_id', 0)->orWhereNull('activite_id');
                });
            } elseif (str_starts_with($filterActivite, 'act_')) {
                $activiteId = substr($filterActivite, 4);
                $query->where('activite_id', $activiteId);
            }
        }
        if ($filterUser !== 'all') {
            $query->where('user_id', $filterUser);
        }

        $articles = $query->get();

        $societes = Societes::all()->keyBy('id');
        $mesures  = Mesures::all()->keyBy('id');

        date_default_timezone_set('Africa/Lubumbashi');

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Titre
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->SetTextColor(10, 25, 47);
        $pdf->Cell(0, 12, mb_convert_encoding('LISTE DES ARTICLES', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(2);

        // Sous-titre + filtres
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 6, mb_convert_encoding('Inventaire complet', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $filtresActifs = [];
        if (!empty($filterNom)) {
            $filtresActifs[] = 'Nom : "' . $filterNom . '"';
        }
        if ($filterCategorie !== 'all' && str_starts_with($filterCategorie, 'cat_')) {
            $catId = substr($filterCategorie, 4);
            $catNom = $societes[$catId]->nom ?? 'Catégorie inconnue';
            $filtresActifs[] = 'Catégorie : ' . $catNom;
        }
        if ($filterActivite === 'none') {
            $filtresActifs[] = 'Sans activité';
        } elseif ($filterActivite !== 'all' && str_starts_with($filterActivite, 'act_')) {
            $actId = substr($filterActivite, 4);
            $actNom = Activites::find($actId)->nom ?? 'Activité inconnue';
            $filtresActifs[] = 'Activité : ' . $actNom;
        }
        if ($filterUser !== 'all') {
            $userName = User::find($filterUser)->name ?? 'Utilisateur inconnu';
            $filtresActifs[] = 'Utilisateur : ' . $userName;
        }
        if (!empty($filtresActifs)) {
            $pdf->SetFont('Helvetica', 'I', 9);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell(0, 6, mb_convert_encoding('Filtres : ' . implode('  |  ', $filtresActifs), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        }
        $pdf->Ln(4);

        // Date
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 4, mb_convert_encoding('Exporté le ' . date('d/m/Y H:i:s'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(2);

        // Tableau (colonnes identiques)
        $margeGauche = 12;
        $largeurDisponible = 297 - 2 * $margeGauche;
        $colonnes = [
            'num'        => 4,
            'categorie'  => 10,
            'nom'        => 16,
            'prixDetail' => 10.5,
            'prixGros'   => 10.5,
            'lot'        => 6,
            'mesure'     => 7,
            'stockage'   => 8,
            'seuilMin'   => 6,
            'seuilMax'   => 6,
            'dateExp'    => 8,
            'description'=> 8
        ];

        $largeurs = [];
        foreach ($colonnes as $k => $pct) {
            $largeurs[$k] = round($pct / 100 * $largeurDisponible, 1);
        }
        $somme = array_sum($largeurs);
        if ($somme != $largeurDisponible) {
            $derniere = array_key_last($largeurs);
            $largeurs[$derniere] += $largeurDisponible - $somme;
        }

        $pdf->SetLeftMargin($margeGauche);
        $pdf->SetX($margeGauche);

        $aligns = [
            'num' => 'C', 'categorie' => 'L', 'nom' => 'L',
            'prixDetail' => 'R', 'prixGros' => 'R', 'lot' => 'C',
            'mesure' => 'L', 'stockage' => 'C', 'seuilMin' => 'C',
            'seuilMax' => 'C', 'dateExp' => 'C', 'description' => 'L'
        ];

        // En-tête
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(10, 25, 47);
        $pdf->SetDrawColor(180, 180, 180);

        $entetes = [
            'num' => 'N°',
            'categorie' => 'Catégorie',
            'nom' => 'Nom',
            'prixDetail' => 'Prix détail',
            'prixGros' => 'Prix gros',
            'lot' => 'Lot',
            'mesure' => 'Mesure',
            'stockage' => 'Stockage',
            'seuilMin' => 'Seuil min',
            'seuilMax' => 'Seuil max',
            'dateExp' => 'Expiration',
            'description' => 'Desc.'
        ];
        foreach ($entetes as $k => $label) {
            $pdf->Cell($largeurs[$k], 8, mb_convert_encoding($label, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Corps (hauteur fixe = 7 mm, une seule ligne)
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetDrawColor(200, 200, 200);

        if ($articles->isEmpty()) {
            $pdf->Cell($largeurDisponible, 7, mb_convert_encoding('Aucun article trouvé', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
        } else {
            $fill = false;
            foreach ($articles as $index => $art) {
                $nomSociete = $societes[$art->societe_id]->nom ?? '';
                $nomMesure  = $mesures[$art->mesure_id]->nom   ?? '';
                $devise = ($art->devise == 0) ? 'USD' : 'CDF';
                $prixDetail = number_format($art->prix_detail, 0, ',', ' ') . ' (' . $devise . ')';
                $prixGros   = number_format($art->prix_gros, 0, ',', ' ') . ' (' . $devise . ')';
                $stock = ($art->avoir_stock == 1) ? 'DET' : 'INT';

                // Troncature des textes pour éviter le débordement (on limite en caractères)
                $nomTronque = mb_substr($art->nom_article, 0, 20, 'UTF-8');
                if (mb_strlen($art->nom_article, 'UTF-8') > 20) $nomTronque .= '.';
                $catTronque = mb_substr($nomSociete, 0, 15, 'UTF-8');
                if (mb_strlen($nomSociete, 'UTF-8') > 15) $catTronque .= '.';
                $mesTronque = mb_substr($nomMesure, 0, 10, 'UTF-8');
                if (mb_strlen($nomMesure, 'UTF-8') > 10) $mesTronque .= '.';
                $descTronque = mb_substr($art->description, 0, 15, 'UTF-8');
                if (mb_strlen($art->description, 'UTF-8') > 15) $descTronque .= '.';

                $pdf->SetX($margeGauche);
                $pdf->Cell($largeurs['num'], 7, $index + 1, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['categorie'], 7, mb_convert_encoding($catTronque, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fill);
                $pdf->Cell($largeurs['nom'], 7, mb_convert_encoding($nomTronque, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fill);
                $pdf->Cell($largeurs['prixDetail'], 7, mb_convert_encoding($prixDetail, 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', $fill);
                $pdf->Cell($largeurs['prixGros'], 7, mb_convert_encoding($prixGros, 'ISO-8859-1', 'UTF-8'), 1, 0, 'R', $fill);
                $pdf->Cell($largeurs['lot'], 7, $art->taille_lot, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['mesure'], 7, mb_convert_encoding($mesTronque, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fill);
                $pdf->Cell($largeurs['stockage'], 7, $stock, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['seuilMin'], 7, $art->seuil_minimum, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['seuilMax'], 7, $art->seuil_maximum, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['dateExp'], 7, (string)$art->date_expiration, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['description'], 7, mb_convert_encoding($descTronque, 'ISO-8859-1', 'UTF-8'), 1, 1, 'L', $fill);

                $fill = !$fill;
            }
        }

        // Pied de page
        $pdf->SetY(-15);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(180, 180, 180);
        $pdf->Cell(0, 5, mb_convert_encoding('Document généré automatiquement - Page ' . $pdf->PageNo(), 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

        $nom_fichier = 'Articles_' . date('Ymd_His') . '.pdf';
        $pdf->Output('F', $nom_fichier);
        return response()->download($nom_fichier, $nom_fichier, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function import_excel_depense(Request $request)
    {
        // Authentification
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez être connecté.'], 401);
        }

        // Vérification du fichier
        if (!$request->hasFile('excel_file')) {
            return response()->json(['message' => 'Aucun fichier envoyé'], 400);
        }

        $file = $request->file('excel_file');
        $allowedExtensions = ['xls', 'xlsx', 'xlsm', 'xlsb', 'csv'];
        if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
            return response()->json(['message' => 'Seuls les fichiers Excel sont autorisés'], 400);
        }

        $targetDir = public_path();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $originalName . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $safeName);

        try {
            $spreadsheet = IOFactory::load($safeName);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            if ($highestRow < 2) {
                return response()->json([
                    'message' => 'Le fichier ne contient aucune ligne de données (seule l\'en-tête est présente).',
                    'nb_importes' => 0,
                    'nb_erreurs' => 0
                ], 200);
            }

            $nb_importation = 0;
            $nb_erreurs = 0;
            $erreurs = [];

            // Types de dépense actifs
            $typesDepense = Type_frais::where('etat', 1)->get()->keyBy('id');

            DB::beginTransaction();
            date_default_timezone_set('Africa/Lubumbashi');

            $normalizeDevise = function($str) {
                $str = trim(strtoupper($str));
                if (in_array($str, ['CDF', 'FC', 'CD'])) return 'CDF';
                if (in_array($str, ['USD', 'US', '$'])) return 'USD';
                return $str;
            };

            $deviseMapping = ['USD' => 0, 'CDF' => 1];

            for ($row = 2; $row <= $highestRow; $row++) {
                $valide = true;
                $ligneErreurs = [];

                try {
                    // Lecture des colonnes
                    $dateRaw = $worksheet->getCell('B' . $row)->getValue();
                    $nPiece = trim($worksheet->getCell('C' . $row)->getValue() ?? '');
                    $montantRaw = $worksheet->getCell('D' . $row)->getValue();
                    $deviseRaw = trim($worksheet->getCell('E' . $row)->getValue() ?? '');
                    $tauxRaw = $worksheet->getCell('F' . $row)->getValue();
                    $typeDepenseNom = trim($worksheet->getCell('G' . $row)->getValue() ?? '');
                    $libelle = trim($worksheet->getCell('H' . $row)->getValue() ?? '');

                    // --- DATE (logique robuste reprise de import_excel_article) ---
                    $dateOperation = null;
                    if ($dateRaw === null || $dateRaw === '' || trim($dateRaw) === '' || trim($dateRaw) === '00/00/0000') {
                        $ligneErreurs[] = "Date d'opération vide ou invalide (00/00/0000)";
                        $valide = false;
                    } elseif (is_numeric($dateRaw)) {
                        try {
                            $dt = ExcelDate::excelToDateTimeObject($dateRaw);
                            $dateOperation = $dt->format('d/m/Y');
                        } catch (\Exception $e) {
                            $timestamp = intval($dateRaw);
                            if ($timestamp > 0) {
                                $dt = new \DateTime('@' . $timestamp);
                                $dateOperation = $dt->format('d/m/Y');
                            } else {
                                $ligneErreurs[] = "Date Excel invalide (série numérique)";
                                $valide = false;
                            }
                        }
                    } elseif ($dateRaw instanceof \DateTime || $dateRaw instanceof \DateTimeInterface) {
                        $dateOperation = $dateRaw->format('d/m/Y');
                    } else {
                        $dateStr = trim((string)$dateRaw);
                        $dt = \DateTime::createFromFormat('d/m/Y', $dateStr);
                        if ($dt && $dt->format('d/m/Y') === $dateStr) {
                            $dateOperation = $dt->format('d/m/Y');
                        } else {
                            $formats = ['Y-m-d', 'Y-m-d H:i:s', 'm/d/Y', 'd-m-Y', 'Y/m/d'];
                            foreach ($formats as $format) {
                                $dt = \DateTime::createFromFormat($format, $dateStr);
                                if ($dt && $dt->format($format) === $dateStr) {
                                    $dateOperation = $dt->format('d/m/Y');
                                    break;
                                }
                            }
                        }
                        if ($dateOperation === null) {
                            $ligneErreurs[] = "Date invalide (utilisez JJ/MM/AAAA ou une date Excel)";
                            $valide = false;
                        }
                    }

                    // --- Montant ---
                    if (empty($montantRaw) || !is_numeric(str_replace(',', '.', trim($montantRaw)))) {
                        $ligneErreurs[] = "Montant invalide";
                        $valide = false;
                    } else {
                        $montant = floatval(str_replace(',', '.', trim($montantRaw)));
                        if ($montant <= 0) {
                            $ligneErreurs[] = "Montant doit être > 0";
                            $valide = false;
                        }
                    }

                    // --- Devise ---
                    if (empty($deviseRaw)) {
                        $ligneErreurs[] = "Devise vide";
                        $valide = false;
                    } else {
                        $deviseStr = $normalizeDevise($deviseRaw);
                        if (!in_array($deviseStr, ['CDF', 'USD'])) {
                            $ligneErreurs[] = "Devise '$deviseRaw' invalide (CDF ou USD)";
                            $valide = false;
                        } else {
                            $deviseInt = $deviseMapping[$deviseStr];
                        }
                    }

                    // --- Taux ---
                    if (empty($tauxRaw) || !is_numeric(str_replace(',', '.', trim($tauxRaw)))) {
                        $ligneErreurs[] = "Taux invalide (nombre requis)";
                        $valide = false;
                    } else {
                        $taux = floatval(str_replace(',', '.', trim($tauxRaw)));
                        if ($taux <= 0) {
                            $ligneErreurs[] = "Taux doit être > 0";
                            $valide = false;
                        }
                    }

                    // --- Gestion du type et du libellé (sans concaténation) ---
                    $typeDepenseId = 0; // par défaut

                    if (!empty($typeDepenseNom)) {
                        // Recherche par nom (insensible à la casse)
                        $type = $typesDepense->first(function($t) use ($typeDepenseNom) {
                            return strtolower($t->nom) == strtolower($typeDepenseNom);
                        });
                        if ($type) {
                            $typeDepenseId = $type->id;
                        } else {
                            // Tentative en tant qu'ID numérique
                            if (is_numeric($typeDepenseNom)) {
                                $type = $typesDepense->find((int)$typeDepenseNom);
                                if ($type) {
                                    $typeDepenseId = $type->id;
                                }
                            }
                            // Si toujours 0, on garde le libellé tel quel (pas de concaténation)
                        }
                    }

                    // Vérification : soit typeDepenseId != 0, soit libellé non vide
                    if ($typeDepenseId == 0 && empty($libelle)) {
                        $ligneErreurs[] = "Type de dépense ou libellé obligatoire (aucun renseigné)";
                        $valide = false;
                    }

                    // --- Enregistrement (plus de contrôle de doublons) ---
                    if ($valide) {
                        $id = Depenses::get()->count() + 1;
                        $depense = new Depenses();
                        $depense->id = $id;
                        $depense->user_id = Auth::id();
                        $depense->montant = $montant;
                        $depense->devise = $deviseInt;
                        $depense->type_depense_id = $typeDepenseId; // 0 ou ID existant
                        $depense->taux = $taux;
                        $depense->libelle = $libelle; // libelle inchangé
                        $depense->date_depense = $dateOperation;
                        $depense->n_piece = $nPiece ?: '';
                        $depense->preuve_de_sortie = '';
                        $depense->save();

                        $nb_importation++;
                    } else {
                        $erreurs[] = "Ligne $row : " . implode('; ', $ligneErreurs);
                        $nb_erreurs++;
                    }

                } catch (\Exception $e) {
                    $erreurs[] = "Ligne $row : Exception - " . $e->getMessage();
                    $nb_erreurs++;
                }
            }

            DB::commit();

            $message = $nb_importation . ' dépense(s) importée(s) avec succès.';
            if ($nb_erreurs > 0) {
                $message .= ' ' . $nb_erreurs . ' ligne(s) en erreur (non importées).';
            }

            return response()->json([
                'message' => $message,
                'details' => $erreurs,
                'nb_importes' => $nb_importation,
                'nb_erreurs' => $nb_erreurs,
                'path' => $safeName
            ], $nb_importation > 0 ? 200 : 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur critique : ' . $e->getMessage()], 500);
        }
    }

    public function export_depense_pdf()
    {
        // ========== 1) Récupération des filtres ==========
        $filterUser   = request()->input('user', 'all');
        $filterType   = request()->input('type', 'all');
        $filterDate   = request()->input('dateRange', '');
        $filterSearch = trim(request()->input('search', ''));
        $filterMontant = request()->input('montant', '');

        // ========== 2) Construction de la requête ==========
        $query = Depenses::where('supprimer', 0);

        if ($filterUser !== 'all') {
            $query->where('user_id', $filterUser);
        }

        if ($filterType !== 'all') {
            if ($filterType === 'none') {
                $query->where(function($q) {
                    $q->where('type_depense_id', 0)
                    ->orWhereNull('type_depense_id');
                });
            } elseif (str_starts_with($filterType, 'type_')) {
                $typeId = substr($filterType, 5);
                $query->where('type_depense_id', $typeId);
            }
        }

        if (!empty($filterDate)) {
            $parts = explode(' - ', $filterDate);
            if (count($parts) === 2) {
                $convert = function($d) {
                    if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $d)) {
                        $dt = \DateTime::createFromFormat('d/m/Y', $d);
                        return $dt ? $dt->format('Y-m-d') : null;
                    }
                    return null;
                };
                $debut = $convert($parts[0]);
                $fin   = $convert($parts[1]);
                if ($debut && $fin) {
                    $query->whereRaw("STR_TO_DATE(date_depense, '%d/%m/%Y') BETWEEN ? AND ?", [$debut, $fin]);
                }
            }
        }

        if (!empty($filterSearch)) {
            $search = '%' . $filterSearch . '%';
            $query->where(function($q) use ($search) {
                $q->where('n_piece', 'like', $search)
                ->orWhere('libelle', 'like', $search)
                ->orWhereExists(function($sub) use ($search) {
                    $sub->select(\DB::raw(1))
                        ->from('type_frais')
                        ->whereRaw('type_frais.id = depenses.type_depense_id')
                        ->where('type_frais.nom', 'like', $search);
                });
            });
        }

        if (is_numeric($filterMontant) && $filterMontant > 0) {
            $query->where('montant', '>=', (float) $filterMontant);
        }

        $depenses = $query->orderBy('date_depense', 'desc')->get();

        // ========== 3) Chargement des relations ==========
        $typesDepense = Type_frais::all()->keyBy('id');
        $users        = User::all()->keyBy('id');

        // ========== 4) Calcul des totaux USD et CDF ==========
        $totalUSD = 0;
        $totalCDF = 0;
        foreach ($depenses as $dep) {
            $taux = $dep->taux ?? 1;
            if ($dep->devise == 0) {
                $totalUSD += $dep->montant;
                $totalCDF += $dep->montant * $taux;
            } else {
                $totalCDF += $dep->montant;
                $totalUSD += $dep->montant / $taux;
            }
        }

        date_default_timezone_set('Africa/Lubumbashi');

        // ========== 5) Création du PDF ==========
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // ---------- Titre ----------
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->SetTextColor(10, 25, 47);
        $pdf->Cell(0, 12, mb_convert_encoding('LISTE DES DÉPENSES', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(2);

        // ---------- Sous‑titre ----------
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 6, mb_convert_encoding('Relevé des dépenses', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        // ---------- Filtres actifs ----------
        $filtresActifs = [];
        if ($filterUser !== 'all') {
            $userName = $users[$filterUser]->name ?? 'Utilisateur inconnu';
            $filtresActifs[] = 'Utilisateur : ' . $userName;
        }
        if ($filterType !== 'all') {
            if ($filterType === 'none') {
                $filtresActifs[] = 'Type : Aucun (libellé libre)';
            } elseif (str_starts_with($filterType, 'type_')) {
                $typeId = substr($filterType, 5);
                $typeNom = $typesDepense[$typeId]->nom ?? 'Type inconnu';
                $filtresActifs[] = 'Type : ' . $typeNom;
            }
        }
        if (!empty($filterDate)) {
            $filtresActifs[] = 'Période : ' . $filterDate;
        }
        if (!empty($filterSearch)) {
            $filtresActifs[] = 'Recherche : "' . $filterSearch . '"';
        }
        if (is_numeric($filterMontant) && $filterMontant > 0) {
            $filtresActifs[] = 'Montant ≥ ' . number_format($filterMontant, 0, ',', ' ');
        }

        if (!empty($filtresActifs)) {
            $pdf->SetFont('Helvetica', 'I', 9);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell(0, 6, mb_convert_encoding('Filtres : ' . implode('  |  ', $filtresActifs), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        }
        $pdf->Ln(4);

        // ---------- Date d'export ----------
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 4, mb_convert_encoding('Exporté le ' . date('d/m/Y H:i:s'), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
        $pdf->Ln(2);

        // ========== 6) Définition des colonnes (8 colonnes) ==========
        $margeGauche = 12;
        $largeurDisponible = 297 - 2 * $margeGauche;
        $colonnes = [
            'num'     => 4,
            'date'    => 8,
            'nPiece'  => 8,
            'montant' => 10,
            'devise'  => 5,
            'taux'    => 7,
            'type'    => 25,
            'user'    => 10
        ];

        $largeurs = [];
        foreach ($colonnes as $k => $pct) {
            $largeurs[$k] = round($pct / 100 * $largeurDisponible, 1);
        }
        $somme = array_sum($largeurs);
        if ($somme != $largeurDisponible) {
            $derniere = array_key_last($largeurs);
            $largeurs[$derniere] += $largeurDisponible - $somme;
        }

        $pdf->SetLeftMargin($margeGauche);
        $pdf->SetX($margeGauche);

        // ---------- En‑tête du tableau ----------
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(10, 25, 47);
        $pdf->SetDrawColor(180, 180, 180);

        $entetes = [
            'num'     => 'N°',
            'date'    => 'Date',
            'nPiece'  => 'N° Pièce',
            'montant' => 'Montant',
            'devise'  => 'Dev.',
            'taux'    => 'Taux',
            'type'    => 'Type de dépense',
            'user'    => 'Utilisateur'
        ];
        foreach ($entetes as $k => $label) {
            $pdf->Cell($largeurs[$k], 8, mb_convert_encoding($label, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        }
        $pdf->Ln();

        // ---------- Corps du tableau ----------
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetDrawColor(200, 200, 200);

        if ($depenses->isEmpty()) {
            $pdf->Cell($largeurDisponible, 7, mb_convert_encoding('Aucune dépense trouvée avec ces filtres', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
        } else {
            $fill = false;
            foreach ($depenses as $index => $dep) {
                // Colonne "Type de dépense" (fusionnée)
                if ($dep->type_depense_id != 0 && $dep->type_depense_id != null) {
                    $typeOuLibelle = $typesDepense[$dep->type_depense_id]->nom ?? 'N/A';
                } else {
                    $typeOuLibelle = !empty($dep->libelle) ? $dep->libelle : 'Sans type';
                }

                $userNom = $users[$dep->user_id]->name ?? 'Inconnu';
                $deviseLibelle = ($dep->devise == 0) ? 'USD' : 'CDF';

                $montantFormate = number_format($dep->montant, 0, ',', ' ');
                $tauxFormate    = number_format($dep->taux, 2, ',', ' ');

                $typeTronque = mb_substr($typeOuLibelle, 0, 30, 'UTF-8');
                if (mb_strlen($typeOuLibelle, 'UTF-8') > 30) $typeTronque .= '.';
                $userTronque = mb_substr($userNom, 0, 15, 'UTF-8');
                if (mb_strlen($userNom, 'UTF-8') > 15) $userTronque .= '.';

                $pdf->SetX($margeGauche);
                $pdf->Cell($largeurs['num'], 7, $index + 1, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['date'], 7, $dep->date_depense, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['nPiece'], 7, $dep->n_piece ?: '-', 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['montant'], 7, $montantFormate, 1, 0, 'R', $fill);
                $pdf->Cell($largeurs['devise'], 7, $deviseLibelle, 1, 0, 'C', $fill);
                $pdf->Cell($largeurs['taux'], 7, $tauxFormate, 1, 0, 'R', $fill);
                $pdf->Cell($largeurs['type'], 7, mb_convert_encoding($typeTronque, 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', $fill);
                $pdf->Cell($largeurs['user'], 7, mb_convert_encoding($userTronque, 'ISO-8859-1', 'UTF-8'), 1, 1, 'L', $fill);

                $fill = !$fill;
            }

            // ========== LIGNE DE TOTAUX (sur une seule ligne) ==========
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(230, 240, 255);
            $pdf->SetDrawColor(180, 180, 180);

            // On fusionne les colonnes pour afficher les trois informations
            $pdf->SetX($margeGauche);
            $pdf->Cell($largeurDisponible, 8,
                mb_convert_encoding(
                    'Dépenses : ' . $depenses->count() .
                    '    Total USD : ' . number_format($totalUSD, 2, ',', ' ') . ' $' .
                    '    Total CDF : ' . number_format($totalCDF, 2, ',', ' ') . ' Fc',
                    'ISO-8859-1',
                    'UTF-8'
                ),
                1, 1, 'C', true
            );
        }

        // ---------- Pied de page ----------
        $pdf->SetY(-15);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(180, 180, 180);
        $pdf->Cell(0, 5, mb_convert_encoding('Document généré automatiquement - Page ' . $pdf->PageNo(), 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

        // ========== 7) Génération du fichier ==========
        $nom_fichier = 'Depenses_' . date('Ymd_His') . '.pdf';
        $pdf->Output('F', $nom_fichier);
        return response()->download($nom_fichier, $nom_fichier, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
    public function export_excel_depense(Request $request)
    {
        // 1) Récupérer les filtres
        $filterUser   = $request->input('user', 'all');
        $filterType   = $request->input('type', 'all');
        $filterDate   = $request->input('dateRange', '');
        $filterSearch = trim($request->input('search', ''));
        $filterMontant = $request->input('montant', '');

        // 2) Construire la requête
        $query = Depenses::where('supprimer', 0);

        if ($filterUser !== 'all') {
            $query->where('user_id', $filterUser);
        }

        if ($filterType !== 'all') {
            if ($filterType === 'none') {
                $query->where(function($q) {
                    $q->where('type_depense_id', 0)->orWhereNull('type_depense_id');
                });
            } elseif (str_starts_with($filterType, 'type_')) {
                $typeId = substr($filterType, 5);
                $query->where('type_depense_id', $typeId);
            }
        }

        if (!empty($filterDate)) {
            $parts = explode(' - ', $filterDate);
            if (count($parts) === 2) {
                $convert = function($d) {
                    if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $d)) {
                        $dt = \DateTime::createFromFormat('d/m/Y', $d);
                        return $dt ? $dt->format('Y-m-d') : null;
                    }
                    return null;
                };
                $debut = $convert($parts[0]);
                $fin   = $convert($parts[1]);
                if ($debut && $fin) {
                    $query->whereRaw("STR_TO_DATE(date_depense, '%d/%m/%Y') BETWEEN ? AND ?", [$debut, $fin]);
                }
            }
        }

        if (!empty($filterSearch)) {
            $search = '%' . $filterSearch . '%';
            $query->where(function($q) use ($search) {
                $q->where('n_piece', 'like', $search)
                ->orWhere('libelle', 'like', $search)
                ->orWhereExists(function($sub) use ($search) {
                    $sub->select(\DB::raw(1))
                        ->from('type_frais')
                        ->whereRaw('type_frais.id = depenses.type_depense_id')
                        ->where('type_frais.nom', 'like', $search);
                });
            });
        }

        if (is_numeric($filterMontant) && $filterMontant > 0) {
            $query->where('montant', '>=', (float) $filterMontant);
        }

        $depenses = $query->orderBy('date_depense', 'desc')->get();

        $typesDepense = Type_frais::all()->keyBy('id');
        $users        = User::all()->keyBy('id');

        // ===== Calcul des totaux USD et CDF =====
        $totalUSD = 0;
        $totalCDF = 0;
        foreach ($depenses as $dep) {
            $taux = $dep->taux ?? 1;
            if ($dep->devise == 0) {
                $totalUSD += $dep->montant;
                $totalCDF += $dep->montant * $taux;
            } else {
                $totalCDF += $dep->montant;
                $totalUSD += $dep->montant / $taux;
            }
        }

        // 3) Création du classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Titre et sous-titre
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'LISTE DES DÉPENSES');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => '0A192F'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->setCellValue('A2', 'Relevé des dépenses');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '505050'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Filtres actifs
        $filtresActifs = [];
        if ($filterUser !== 'all') {
            $userName = $users[$filterUser]->name ?? 'Utilisateur inconnu';
            $filtresActifs[] = 'Utilisateur : ' . $userName;
        }
        if ($filterType !== 'all') {
            if ($filterType === 'none') {
                $filtresActifs[] = 'Type : Aucun (libellé libre)';
            } elseif (str_starts_with($filterType, 'type_')) {
                $typeId = substr($filterType, 5);
                $typeNom = $typesDepense[$typeId]->nom ?? 'Type inconnu';
                $filtresActifs[] = 'Type : ' . $typeNom;
            }
        }
        if (!empty($filterDate)) {
            $filtresActifs[] = 'Période : ' . $filterDate;
        }
        if (!empty($filterSearch)) {
            $filtresActifs[] = 'Recherche : "' . $filterSearch . '"';
        }
        if (is_numeric($filterMontant) && $filterMontant > 0) {
            $filtresActifs[] = 'Montant ≥ ' . number_format($filterMontant, 0, ',', ' ');
        }

        $rowActuelle = 3;
        if (!empty($filtresActifs)) {
            $sheet->setCellValue('A' . $rowActuelle, 'Filtres : ' . implode('  |  ', $filtresActifs));
            $sheet->mergeCells('A' . $rowActuelle . ':I' . $rowActuelle);
            $sheet->getStyle('A' . $rowActuelle)->applyFromArray([
                'font' => [
                    'italic' => true,
                    'size' => 9,
                    'color' => ['rgb' => '808080'],
                    'name' => 'Arial'
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
            $rowActuelle++;
        }

        $sheet->setCellValue('A' . $rowActuelle, 'Exporté le ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $rowActuelle . ':I' . $rowActuelle);
        $sheet->getStyle('A' . $rowActuelle)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 8,
                'color' => ['rgb' => '969696'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT
            ]
        ]);
        $rowActuelle++;
        $rowActuelle++;

        // En-têtes (8 colonnes)
        $headers = ['N°', 'Date', 'N° Pièce', 'Montant', 'Devise', 'Taux', 'Type de dépense', 'Utilisateur'];
        $col = 'A';
        $headerRow = $rowActuelle;
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }

        $headerRange = 'A' . $headerRow . ':H' . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0A192F']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'B0B0B0']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Données
        $row = $headerRow + 1;
        if ($depenses->isEmpty()) {
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->setCellValue('A' . $row, 'Aucune dépense trouvée avec ces filtres');
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => 'E31B23']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        } else {
            $fill = false;
            foreach ($depenses as $index => $dep) {
                if ($dep->type_depense_id != 0 && $dep->type_depense_id != null) {
                    $typeOuLibelle = $typesDepense[$dep->type_depense_id]->nom ?? 'N/A';
                } else {
                    $typeOuLibelle = !empty($dep->libelle) ? $dep->libelle : 'Sans type';
                }

                $userNom = $users[$dep->user_id]->name ?? 'Inconnu';
                $deviseLibelle = ($dep->devise == 0) ? 'USD' : 'CDF';

                $montantFormate = number_format($dep->montant, 0, ',', ' ');
                $tauxFormate    = number_format($dep->taux, 2, ',', ' ');

                $data = [
                    $index + 1,
                    $dep->date_depense,
                    $dep->n_piece ?: '-',
                    $montantFormate,
                    $deviseLibelle,
                    $tauxFormate,
                    $typeOuLibelle,
                    $userNom
                ];

                $col = 'A';
                foreach ($data as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }

                $fillColor = $fill ? 'F5F5F5' : 'FFFFFF';
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $fillColor]
                    ],
                    'font' => [
                        'size' => 8,
                        'name' => 'Arial'
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D0D0D0']
                        ]
                    ]
                ]);

                $fill = !$fill;
                $row++;
            }

            // ========== LIGNE DE TOTAUX (une seule ligne) ==========
            $row++; // on saute une ligne
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->setCellValue('A' . $row,
                'Dépenses : ' . $depenses->count() .
                '    Total USD : ' . number_format($totalUSD, 2, ',', ' ') . ' $' .
                '    Total CDF : ' . number_format($totalCDF, 2, ',', ' ') . ' Fc'
            );
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'color' => ['rgb' => '000000'],
                    'name' => 'Arial'
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F0FE']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '808080']
                    ]
                ]
            ]);

            // Ajuster la largeur des colonnes
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // Pied de page
        $rowActuelFinal = $row + 1;
        $sheet->setCellValue('A' . $rowActuelFinal, 'Document généré automatiquement');
        $sheet->mergeCells('A' . $rowActuelFinal . ':H' . $rowActuelFinal);
        $sheet->getStyle('A' . $rowActuelFinal)->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 7,
                'color' => ['rgb' => 'B0B0B0'],
                'name' => 'Arial'
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Génération du fichier
        $fileName = 'depenses_export_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel') . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    public function get_articles_select(Request $request)
    {
        $table_id = $request->input('table_id');
        $table = Tables::where('id', $table_id)->first();
        $pointdeventes_id = $table->pointdeventes_id;
        $pointdeventes = pointdeventes::where('id', $pointdeventes_id)->first();
        $stock_id = $pointdeventes->stock_id;

        $html = '<option selected value="">Sélectionnez un article</option>';

        if ($stock_id == 0) {
            // Cas sans stock : on ne vérifie que l'activité
            $articles = Articles::where('supprimer', 0)->get();
            foreach ($articles as $article) {
                $nomMesure = Mesures::where('id', $article->mesure_id)->first()['nom'] ?? 'N/A';
                $nomSociete = Societes::where('id', $article->societe_id)->first()['nom'] ?? 'N/A';
                $label = $article->nom_article . ' ' . $nomMesure . ' (' . $nomSociete . ')';
                $disabled = ($article->activite_id == 0) ? 'disabled' : '';
                $icon = ($article->activite_id != 0) ? '🟢' : '🔴';
                $message = $disabled ? ' : Activité non définie' : '';
                $html .= '<option value="' . $article->id . '" ' . $disabled . '>'
                    . $icon . ' ' . e($label) . $message
                    . '</option>';
            }
        } else {
            // Cas avec stock : on vérifie activité ET stock
            $articlestocks = articlestocks::where(['supprimer' => 0, 'stock_id' => $stock_id])->get();
            foreach ($articlestocks as $articlestock) {
                $article = Articles::find($articlestock->article_id);
                if (!$article) {
                    continue;
                }
                $nomMesure = Mesures::where('id', $article->mesure_id)->first()['nom'] ?? 'N/A';
                $nomSociete = Societes::where('id', $article->societe_id)->first()['nom'] ?? 'N/A';
                $label = $article->nom_article . ' ' . $nomMesure . ' (' . $nomSociete . ')';

                // Vérifications
                $activiteOk = ($article->activite_id != 0);
                $stockOk = ($articlestock->stock > $articlestock->seuil_minimum); // supposé existant

                $disabled = false;
                $messages = [];
                if (!$activiteOk) {
                    $messages[] = 'Activité non définie';
                }
                if (!$stockOk) {
                    $messages[] = 'Stock insuffisant';
                }
                if (!empty($messages)) {
                    $disabled = true;
                    $message = ' : ' . implode(' et ', $messages);
                } else {
                    $message = '';
                }

                $icon = ($activiteOk && $stockOk) ? '🟢' : '🔴';
                $html .= '<option value="' . $articlestock->id . '" ' . ($disabled ? 'disabled' : '') . '>'
                    . $icon . ' ' . e($label) . $message
                    . '</option>';
            }
        }

        return $html;
    }
}
