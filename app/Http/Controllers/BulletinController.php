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



class BulletinController extends Controller
{

    public function bulletin()
    {
        // --------------------------------------------------------------
        // TABLEAU DES 10 EMPLOYÉS - Aucun calcul, seul le salaire compte
        // --------------------------------------------------------------
        $employees = [
            // 1. ANSELME KALENGA TSHOMBA - SITE MANAGER - 600
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'ANSELME KALENGA TSHOMBA',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$600.00',
                    'Fonction'       => 'SITE MANAGER',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$600.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$600.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$600.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$600.00']
                ]
            ],
            // 2. BUKOME BARAKA DOXA - SUPPERVISEUR - 400
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'BUKOME BARAKA DOXA',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$400.00',
                    'Fonction'       => 'SUPPERVISEUR',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$400.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ]
            ],
            // 3. IRUNG RUBUZ THARCISSE - TOUT RAVAUX - 300
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'IRUNG RUBUZ THARCISSE',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$300.00',
                    'Fonction'       => 'TOUT RAVAUX',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$300.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ]
            ],
            // 4. KABANGE KAZADI PETER - TOUT RAVAUX - 250
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KABANGE KAZADI PETER',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$250.00',
                    'Fonction'       => 'TOUT RAVAUX',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$250.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$250.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$250.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$250.00']
                ]
            ],
            // 5. KALAMB NGUEB NGUEB CHRISTELLE - CUISINIERE - 200
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KALAMB NGUEB NGUEB CHRISTELLE',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$200.00',
                    'Fonction'       => 'CUISINIERE',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$200.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ]
            ],
            // 6. KAZADI KAZADI PLACIDE - TOUT RAVAUX - 300
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KAZADI KAZADI PLACIDE',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$300.00',
                    'Fonction'       => 'TOUT RAVAUX',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$300.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ]
            ],
            // 7. KIOMBA NGOIE ODELIE - MAGASINIERE - 200
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KIOMBA NGOIE ODELIE',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$200.00',
                    'Fonction'       => 'MAGASINIERE',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$200.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ]
            ],
            // 8. KISIMBA TWITE MICHAEL - TOUT RAVAUX - 300
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KISIMBA TWITE MICHAEL',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$300.00',
                    'Fonction'       => 'TOUT RAVAUX',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$300.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$300.00']
                ]
            ],
            // 9. KUNDA MUSUMALI DONAT - TOUT RAVAUX - 200
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'KUNDA MUSUMALI DONAT',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$200.00',
                    'Fonction'       => 'TOUT RAVAUX',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$200.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$200.00']
                ]
            ],
            // 10. MAKITU MATONDO CYNTHIA - SUPPERVISEUR - 400
            [
                'info' => [
                    'Matricule'      => '',
                    'NumeroCNSS'     => '',
                    'Nom'            => 'MAKITU MATONDO CYNTHIA',
                    'NbEnfants'      => '',
                    'DateEngagement' => '',
                    'SalaireBase'    => '$400.00',
                    'Fonction'       => 'SUPPERVISEUR',
                    'JoursPrestation'=> '26',
                    'Categorie'      => '',
                    'Departement'    => '',
                    'Site'           => ''
                ],
                'remun' => [
                    ['label' => 'Jours Prestés', 'taux' => '100%', 'jours' => '26', 'montant' => '$400.00'],
                    ['label' => 'Jours Maladie', 'taux' => '67%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Allocation Congé', 'taux' => '100%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 130%', 'taux' => '130%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires 160%', 'taux' => '160%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Heures Supplémentaires - 200%', 'taux' => '200%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'Prime de nuit', 'taux' => '25%', 'jours' => '0', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL TAXABLE', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ],
                'avantages' => [
                    ['label' => 'TRANSPORT', 'taux' => '$0.00', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'LOGEMENT', 'taux' => '0%', 'jours' => '26', 'montant' => '$0.00'],
                    ['label' => 'SOUS-TOTAL Avantages Sociaux', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'SALAIRE BRUTE', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ],
                'deductions' => [
                    ['label' => 'CNSS', 'taux' => '0%', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'IPR', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'CREDIT', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'TOTAL DEDUCTIONS', 'taux' => '', 'jours' => '', 'montant' => '$0.00'],
                    ['label' => 'NET A PAYER', 'taux' => '', 'jours' => '', 'montant' => '$400.00']
                ]
            ]
        ];
    
        // --------------------------------------------------------------
        // GÉNÉRATION DU PDF (inchangée)
        // --------------------------------------------------------------
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetFont('Arial', 'B', 8);
    
        $x = 10;
        $y = 10;
        $w = 190;
        $h = 55;
    
        $imagePath = './entete_africtech.png'; // À adapter
        $imgWidth = 150;
        $imgHeight = $imgWidth * (492 / 1774);
        $imgX = $x + ($w - $imgWidth) / 2;
        $imgY = $y + 5;
    
        $drawHeader = function($pdf) use ($x, $y, $w, $h, $imagePath, $imgX, $imgY, $imgWidth, $imgHeight) {
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.2);
            $pdf->Rect($x, $y, $w, $h);
            $pdf->Image($imagePath, $imgX, $imgY, $imgWidth, $imgHeight);
            $titleY = $y + $h - 9;
            $pdf->SetY($titleY);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(0, 0, 0);
            $txt = 'BULLETIN DE PAIE DU MOIS D\'AOÛT 2026';
            $txtWidth = $pdf->GetStringWidth($txt);
            $xTxt = $x + ($w - $txtWidth) / 2;
            $pdf->SetX($xTxt);
            $pdf->Cell($txtWidth, 5, iconv('UTF-8', 'windows-1252', $txt), 'B', 1, 'C');
        };
    
        foreach ($employees as $index => $emp) {
            if ($index == 0) {
                $pdf->AddPage();
            } else {
                $pdf->AddPage();
            }
    
            $drawHeader($pdf);
            $pdf->SetY($y + $h);
    
            $col1 = 63.33; $col2 = 63.33; $col3 = 31.67; $col4 = 31.67;
            $info = $emp['info'];
    
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Matricule'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Matricule']), 1, 0, 'L');
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Numéro CNSS'), 1, 0, 'L');
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $info['NumeroCNSS']), 1, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Nom'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Nom']), 1, 0, 'L');
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', "Nombre d'enfants"), 1, 0, 'L');
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $info['NbEnfants']), 1, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', "Date d'engagement"), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['DateEngagement']), 1, 0, 'L');
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Salaire de base'), 1, 0, 'L');
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $info['SalaireBase']), 1, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Fonction'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Fonction']), 1, 0, 'L');
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Jours de prestation'), 1, 0, 'L');
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $info['JoursPrestation']), 1, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Catégorie'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Categorie']), 1, 0, 'L');
            $pdf->Cell($col3, 5, '', 1, 0, 'C');
            $pdf->Cell($col4, 5, '', 1, 0, 'C');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Département'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Departement']), 1, 0, 'L');
            $pdf->Cell($col3, 5, '', 1, 0, 'C');
            $pdf->Cell($col4, 5, '', 1, 0, 'C');
            $pdf->Ln(5);
    
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'Site'), 1, 0, 'L');
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $info['Site']), 1, 0, 'L');
            $pdf->Cell($col3, 5, '', 1, 0, 'C');
            $pdf->Cell($col4, 5, '', 1, 0, 'C');
            $pdf->Ln(5);
    
            $pdf->Cell(190, 5, '', 1, 0, 'R');
            $pdf->Ln(5);
    
            // Rémunération
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(191, 191, 191);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'REMUNERATION'), 1, 0, 'L', true);
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', 'Taux'), 1, 0, 'C', true);
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Jrs / Hrs'), 1, 0, 'C', true);
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', 'Montant'), 1, 0, 'C', true);
            $pdf->Ln(5);
    
            foreach ($emp['remun'] as $line) {
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', $line['label']), 1, 0, 'L');
                $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $line['taux']), 1, 0, 'R');
                $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', $line['jours']), 1, 0, 'R');
                $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $line['montant']), 1, 0, 'R');
                $pdf->Ln(5);
            }
            $pdf->Cell(190, 5, '', 1, 0, 'R');
            $pdf->Ln(5);
    
            // Avantages
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(191, 191, 191);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'AVANTAGES SOCIAUX'), 1, 0, 'L', true);
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', 'Taux'), 1, 0, 'C', true);
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Jrs / Hrs'), 1, 0, 'C', true);
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', 'Montant'), 1, 0, 'C', true);
            $pdf->Ln(5);
    
            foreach ($emp['avantages'] as $line) {
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', $line['label']), 1, 0, 'L');
                $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $line['taux']), 1, 0, 'R');
                $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', $line['jours']), 1, 0, 'R');
                $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $line['montant']), 1, 0, 'R');
                $pdf->Ln(5);
            }
            $pdf->Cell(190, 5, '', 1, 0, 'R');
            $pdf->Ln(5);
    
            // Déductions
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(191, 191, 191);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', 'DEDUCTION'), 1, 0, 'L', true);
            $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', 'Taux'), 1, 0, 'C', true);
            $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', 'Jrs / Hrs'), 1, 0, 'C', true);
            $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', 'Montant'), 1, 0, 'C', true);
            $pdf->Ln(5);
    
            foreach ($emp['deductions'] as $line) {
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($col1, 5, iconv('UTF-8', 'windows-1252', $line['label']), 1, 0, 'L');
                $pdf->Cell($col2, 5, iconv('UTF-8', 'windows-1252', $line['taux']), 1, 0, 'R');
                $pdf->Cell($col3, 5, iconv('UTF-8', 'windows-1252', $line['jours']), 1, 0, 'R');
                $pdf->Cell($col4, 5, iconv('UTF-8', 'windows-1252', $line['montant']), 1, 0, 'R');
                $pdf->Ln(5);
            }
    
            // Pied de page (EMPLOYÉ / EMPLOYEUR)
            $yStart = $pdf->GetY();
            $pdf->Cell(190, 15, '', 1, 0, 'C');
            $pdf->SetXY($x, $yStart);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', 'EMPLOYÉ'), 'B', 0, 'L');
            $pdf->SetX($x + 190 - 80);
            $pdf->Cell(80, 15, iconv('UTF-8', 'windows-1252', 'EMPLOYEUR'), 'B', 0, 'R');
            $pdf->Ln(15);
        }
    
        $pdf->Output('bulletins_paie_aout2026.pdf', 'I');
    }
}
