<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\Type_documents;
use App\Models\Droit_fichiers;
use App\Models\Documents;
use App\Models\User;
use App\Models\Fichiers_documents;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'GESTION FICHIER')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
    /* =============================================
   DESIGN PREMIUM - VERSION FINALE
   BOUTONS MODERNES & RESPONSIFS
   LIGNES DE TABLEAU RÉDUITES ET ÉQUILIBRÉES
   MESSAGE D'ERREUR/SUCCÈS TOTALEMENT CACHÉ PAR DÉFAUT
   PRISE EN CHARGE DU FORMULAIRE D'ÉDITION
   COLONNE CONTROL : BOUTONS ESPACÉS ET ADAPTÉS
   ============================================= */

/* --- Reset des marges pour occuper tout l'écran --- */
body {
    margin: 0;
    padding: 0;
    background: #f0f4f8;
}

.content .container {
    max-width: 100% !important;
    width: 100%;
    padding: 1rem 2rem !important;
    margin: 0 auto;
    background: #f8fafc;
}

.content .container .row {
    margin-left: 0;
    margin-right: 0;
}

.content .container [class*="col-"] {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

/* --- Variables --- */
:root {
    --bleu-nuit: #0a192f;
    --bleu-nuit-clair: #112240;
    --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
    --rouge-feu: #e31b23;
    --rouge-fonce: #b91c1c;
    --rouge-gradient: linear-gradient(135deg, #dc2626, #b91c1c);
    --vert-succes: #10b981;
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

/* --- Cartes principales --- */
#bloc_1, #bloc_2, #bloc_3 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 2rem 1.8rem !important;
    margin-bottom: 2rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

/* --- En-têtes --- */
h4 {
    font-weight: 700;
    border-left: 6px solid var(--rouge-feu);
    padding-left: 18px;
    margin-bottom: 28px;
    color: var(--bleu-nuit);
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* ========== TABLEAU ÉQUILIBRÉ ========== */
.table-responsive {
    border-radius: var(--border-radius-lg);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 600px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
}

.table thead th {
    background: var(--bleu-nuit-gradient);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 10px !important;
    border-bottom: none;
    white-space: nowrap;
}

.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #eef2f6;
}

.table tbody tr:hover {
    background: #f0f5fe !important;
}

.table tbody td {
    padding: 8px 10px !important;
    vertical-align: middle;
    font-weight: 500;
    font-size: 0.85rem;
    color: #1e2a3e;
    word-break: break-word;
}

/* ========== COLONNE CONTROL : BOUTONS MODERNES ET AÉRÉS ========== */
/* La cellule elle-même peut s'étendre et permettre le retour à la ligne */
.table tbody td:last-child {
    white-space: normal;
    min-width: 240px;  /* un peu d'espace pour que les boutons respirent */
}

/* Style général des boutons dans la colonne Control */
.table tbody td:last-child a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 40px !important;  /* arrondi moderne */
    background: #f1f5f9;
    color: #1e293b;
    transition: all 0.2s ease;
    text-decoration: none;
    margin: 3px;                    /* espacement entre les boutons */
    line-height: 1.2;
}

/* Couleurs spécifiques selon la classe d'origine */
.table tbody td:last-child a.btn-info {
    background: #0ea5e9;
    color: white;
}
.table tbody td:last-child a.btn-dark {
    background: #334155;
    color: white;
}
.table tbody td:last-child a.btn-success {
    background: #10b981;
    color: white;
}
.table tbody td:last-child a.btn-danger {
    background: #ef4444;
    color: white;
}
.table tbody td:last-child a.btn-secondary {
    background: #64748b;
    color: white;
}

/* Icônes dans les boutons */
.table tbody td:last-child a i.zmdi {
    font-size: 1rem;
    margin: 0;
}

/* Effet au survol */
.table tbody td:last-child a:hover {
    transform: translateY(-2px);
    filter: brightness(0.95);
    box-shadow: var(--shadow-light);
}

/* ========== BOUTONS PRINCIPAUX (hors tableau) ========== */
#liste, #add, #print, #add_r, #print_r,
.btn-primary, .btn-primary.btn-sm, a.btn-primary,
.btn-info, .btn-info.btn-sm,
.btn-danger, .btn-danger.btn-sm,
#edit_save, #edit_annuler {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 18px !important;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px !important;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: var(--shadow-light);
    white-space: nowrap;
}

#liste {
    background: linear-gradient(135deg, #0a192f, #1e3a5f) !important;
    color: white !important;
}
#liste:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#add, a#add {
    background: linear-gradient(135deg, #0f4c5f, #1e6f5c) !important;
    color: white !important;
}
#add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
}

#print {
    background: linear-gradient(135deg, #4b6e8a, #2c4f6e) !important;
    color: white !important;
}
#print:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(43, 76, 108, 0.3);
}

#add_r, #print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed;
    opacity: 0.7;
    box-shadow: none;
}
#add_r:hover, #print_r:hover {
    transform: none;
    box-shadow: none;
}

#save, #save_r, #annuler, #edit_save, #edit_annuler {
    padding: 8px 24px !important;
    font-weight: 700;
}
#save, #edit_save {
    background: linear-gradient(95deg, #0f4c5f, #0e6b5e) !important;
    color: white;
}
#save:hover, #edit_save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
}
#annuler, #edit_annuler {
    background: #64748b !important;
    color: white;
}
#annuler:hover, #edit_annuler:hover {
    background: #475569 !important;
    transform: translateY(-2px);
}

/* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
#form_add .row, #form_edit .row {
    display: flex;
    flex-wrap: wrap;
}
#form_add .col-6, #form_edit .col-6 {
    margin-bottom: 1rem;
}
.form-group {
    width: 100%;
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-weight: 700;
    color: var(--bleu-nuit);
    margin-bottom: 6px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-group label i {
    color: var(--rouge-feu);
    margin-right: 6px;
}
.form-control,
input.form-control,
select.form-control,
textarea.form-control,
.input-mask {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 10px 14px !important;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    box-sizing: border-box;
    height: 42px !important;
    line-height: 1.4;
}
textarea.form-control {
    resize: vertical;
    height: 42px !important;
}
.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    transform: translateY(-1px);
}
select.form-control {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
}
.input-mask {
    font-family: monospace;
    background: #fff9ef !important;
}

/* ========== MESSAGES MODERNES - TOTALEMENT INVISIBLE PAR DÉFAUT ========== */
#msg, #edit_msg {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    min-height: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}

#msg:not(:empty), #edit_msg:not(:empty) {
    display: inline-flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    height: auto !important;
    margin-top: 16px !important;
    padding: 10px 18px !important;
    background: white !important;
    border-radius: 50px !important;
    box-shadow: var(--shadow-light) !important;
    gap: 10px;
    font-weight: 600;
    font-size: 0.8rem;
    animation: slideInMsg 0.3s ease-out;
}

#msg:not(:empty):has(i.zmdi-check-circle),
#edit_msg:not(:empty):has(i.zmdi-check-circle) {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46;
    border-left: 4px solid var(--vert-succes);
}
#msg:not(:empty):has(i.zmdi-close-circle),
#edit_msg:not(:empty):has(i.zmdi-close-circle) {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b;
    border-left: 4px solid var(--rouge-feu);
}

@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ========== BARRE D'ACTIONS EN HAUT ========== */
[style*="background-color: rgba(0, 0, 0, 0.1)"] {
    background: #eef3fc !important;
    border-radius: 60px;
    padding: 10px 24px !important;
    margin-bottom: 20px;
    display: flex !important;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: flex-start;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .content .container {
        padding: 0.8rem 1rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3 {
        padding: 1.2rem !important;
    }
    #liste, #add, #print, #add_r, #print_r,
    .btn-primary, .btn-info, .btn-danger,
    #edit_save, #edit_annuler {
        padding: 6px 14px !important;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
    }
    #form_add .col-6, #form_edit .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .form-control, input.form-control, select.form-control, textarea.form-control {
        height: 40px !important;
        font-size: 0.8rem;
    }
    .table thead th {
        font-size: 0.7rem;
        padding: 6px 6px !important;
    }
    .table tbody td {
        padding: 6px 6px !important;
        font-size: 0.75rem;
    }
    /* Ajustement colonne Control sur tablette */
    .table tbody td:last-child {
        min-width: 200px;
    }
    .table tbody td:last-child a {
        padding: 5px 10px;
        font-size: 0.7rem;
        gap: 5px;
        margin: 2px;
    }
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.5rem !important;
    }
    .btn, .btn-sm, #liste, #add, #print, #edit_save, #edit_annuler {
        padding: 4px 10px !important;
        font-size: 0.7rem;
    }
    .form-group label {
        font-size: 0.7rem;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        gap: 6px;
        padding: 8px 12px !important;
    }
    .table thead th {
        font-size: 0.6rem;
        padding: 4px 4px !important;
    }
    .table tbody td {
        font-size: 0.7rem;
        padding: 5px 4px !important;
    }
    /* Sur très petit écran, les boutons peuvent prendre plus de hauteur */
    .table tbody td:last-child {
        min-width: 180px;
    }
    .table tbody td:last-child a {
        padding: 4px 8px;
        font-size: 0.65rem;
        gap: 4px;
        margin: 2px;
    }
}

/* ========== ANIMATIONS & DÉTAILS ========== */
@keyframes glow {
    0% { box-shadow: 0 0 0 0 rgba(227, 27, 35, 0.2); }
    70% { box-shadow: 0 0 0 6px rgba(227, 27, 35, 0); }
    100% { box-shadow: 0 0 0 0 rgba(227, 27, 35, 0); }
}
.btn-danger:active {
    animation: glow 0.3s ease-out;
}
.modal-header {
    background: var(--bleu-nuit-gradient);
}
input[required], select[required], textarea[required] {
    border-left: 3px solid var(--rouge-feu) !important;
}
/* ========== AMÉLIORATION UNIQUEMENT DES ICÔNES DE PERMISSIONS ========== */
/* Ne cible que les icônes dans les cellules des permissions (colonnes 3 à 6) */
.table tbody td:nth-child(n+3):nth-child(-n+6) i {
    font-size: 1.3rem !important;
    transition: all 0.2s ease;
}

/* Icône cochée (check-square) - vert */
.table tbody td:nth-child(n+3):nth-child(-n+6) i.zmdi-check-square {
    color: #10b981 !important;
}

/* Icône non cochée (square-o) - gris plus visible */
.table tbody td:nth-child(n+3):nth-child(-n+6) i.zmdi-square-o {
    color: #94a3b8 !important;
}

/* Effet au survol - uniquement sur l'icône */
.table tbody td:nth-child(n+3):nth-child(-n+6) a:hover i {
    transform: scale(1.1);
}

/* Effet au survol sur icône cochée */
.table tbody td:nth-child(n+3):nth-child(-n+6) a:hover i.zmdi-check-square {
    color: #059669 !important;
}

/* Effet au survol sur icône non cochée */
.table tbody td:nth-child(n+3):nth-child(-n+6) a:hover i.zmdi-square-o {
    color: #64748b !important;
}
</style>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 10px;padding-bottom: 10px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <a class="btn-primary btn-sm" id="liste" href="">
                                    <i class="zmdi zmdi-accounts"></i> Liste
                                </a>
                                &nbsp;
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                    <?php
                                    $add = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                        $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                    <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
                                        <a id="add" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                    <?php } else { ?>
                                        <a id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top: 30px;" class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                        class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de fichier</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;padding-bottom: 50px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                        class="zmdi zmdi-email-open text-info"></i> Liste</h4>
                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Type de document</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateurs</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($fichier_documents as $data)
                                    <?php
                                            $documents = Documents::where('id', $data->documents_id)->first();
                                            $type_documents_id = $documents["type_documents_id"];
                                            $type_document = Type_documents::where('id', $type_documents_id)->first();
                                            $ut = User::where('id', $documents["user_id"])->first();
                                        ?>
                                    <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 1])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>

                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $type_document["nom"] }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ $documents["description"] }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            @if (Auth::user()->id == $documents["user_id"])
                                            Vous
                                            @else
                                            {{ $ut['name'] }}
                                            @endif
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                                            <?=  $data->fichier_documents_id ?>
                                            <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 1])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                                            <a id="voir_<?= $i ?>" class="btn btn-info btn-sm" href="">
                                                <i class="zmdi zmdi-eye"></i> Voir
                                            </a>&nbsp;
                                            <?php } ?>
                                            <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 2])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                                            <a id="telecharger_<?= $i ?>" class="btn btn-dark btn-sm" href="">
                                                <i class="zmdi zmdi-download"></i> Telecharger
                                            </a>&nbsp;
                                            <?php } ?>
                                            <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 3])->get()->count() > 0) || ((Auth::user()->id ==$documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                                            <a style="dispaly:none;" id="edit_<?= $i ?>" class="btn btn-success btn-sm"
                                                href="">
                                                <i class="zmdi zmdi-edit"></i> Modifier
                                            </a>&nbsp;
                                            <?php } ?>
                                            <?php if((Droit_fichiers::where(['user_id' => Auth::user()->id, 'fichier_documents_id' => $data->id, 'numero_permission' => 4])->get()->count() > 0) || ((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                                            <a id="delete_<?= $i ?>" class="btn btn-danger btn-sm" href="">
                                                <i class="zmdi zmdi-delete"></i> Supprimer
                                            </a>&nbsp;
                                            <?php } ?>
                                            <?php if(((Auth::user()->id == $documents["user_id"]) || (Auth::user()->role == 0))){ ?>
                                            <a id="partager_<?= $i ?>" class="btn btn-secondary btn-sm" href="">
                                                <span id="spin_t"><i class="zmdi zmdi-share"></i></span> Partager
                                            </a>
                                            <?php } ?>
                                            <script>
                                            $("#edit_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $.get("{{ url('/refresh_edit_fichier_document_id') }}", {
                                                    fichier_documents_id: <?= $data->id ?>,
                                                }, function(refresh_edit_type_documents) {
                                                    $("#bloc_1").hide();
                                                    $("#bloc_2").hide();
                                                    $("#bloc_3").show();
                                                    $("#bloc_3").html(refresh_edit_type_documents);
                                                });
                                            });
                                            $("#delete_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $("#element").html("<?= $data->nom ?>");
                                                $("#data_id").html("<?= $data->id ?>");
                                                $("#btn_sup").trigger("click");
                                            });
                                            $("#voir_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                var url = "<?= $data->lien ?>";
                                                // 1. Extraire l'extension (en minuscules pour éviter la casse)
                                                const extension = url.split('.').pop().toLowerCase();

                                                // 2. Définir une correspondance extension -> type de document
                                                const typeMap = {
                                                    // Images
                                                    'png': 'image',
                                                    'jpg': 'image',
                                                    'jpeg': 'image',
                                                    'gif': 'image',
                                                    'bmp': 'image',
                                                    'webp': 'image',
                                                    'svg': 'image',
                                                    // Documents texte
                                                    'txt': 'texte',
                                                    'pdf': 'pdf',
                                                    'doc': 'word',
                                                    'docx': 'word',
                                                    'odt': 'texte',
                                                    // Tableurs
                                                    'xls': 'excel',
                                                    'xlsx': 'excel',
                                                    'ods': 'tableur',
                                                    // Présentations
                                                    'ppt': 'powerpoint',
                                                    'pptx': 'powerpoint',
                                                    'odp': 'présentation',
                                                    // Archives
                                                    'zip': 'archive',
                                                    'rar': 'archive',
                                                    '7z': 'archive',
                                                    // Audio / Vidéo
                                                    'mp3': 'audio',
                                                    'wav': 'audio',
                                                    'mp4': 'vidéo',
                                                    'avi': 'vidéo',
                                                    'mov': 'vidéo',
                                                    // Autres
                                                    'html': 'page web',
                                                    'css': 'feuille de style',
                                                    'js': 'script JavaScript',
                                                    'json': 'données JSON',
                                                    'xml': 'données XML'
                                                };

                                                var type = typeMap[extension] || 'type inconnu';
                                                if (type == "image") {
                                                    $("#titre_modal_fichier").html("Visualisation : " + url
                                                        .split('/').pop());
                                                    $("#fichier_content").html('<img src="' + url +
                                                        '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                                    );
                                                    $("#btn_detail_fichier").trigger("click");
                                                }
                                                if (type == "excel") {
                                                    // Récupérer l'URL complète du fichier (sur le même serveur)
                                                    var fileUrl = "{{ asset('') }}" + url;
                                                    // Nettoyer les doubles slashes
                                                    fileUrl = fileUrl.replace(/([^:]\/)\/+/g, "$1");

                                                    // Titre de la modale
                                                    $("#excelModalTitle").html("Visualisation : " + url.split(
                                                            '/')
                                                        .pop());

                                                    // Ouvrir la modale
                                                    $("#modalExcelViewer").modal({
                                                        backdrop: 'static',
                                                        keyboard: false,
                                                        show: true
                                                    });

                                                    // Conteneur pour le visualiseur
                                                    var container = document.getElementById(
                                                        "excelViewerContainer");
                                                    container.innerHTML =
                                                        '<div style="text-align:center; padding:50px;"><i class="zmdi zmdi-spinner zmdi-hc-spin" style="font-size: 40px;"></i><br>Chargement du fichier Excel...</div>';

                                                    // Charger le fichier depuis le serveur (pas de proxy CORS car même serveur)
                                                    fetch(fileUrl)
                                                        .then(response => {
                                                            if (!response.ok) throw new Error("HTTP " +
                                                                response.status);
                                                            return response.arrayBuffer();
                                                        })
                                                        .then(arrayBuffer => {
                                                            // Charger le workbook avec SheetJS
                                                            var workbook = XLSX.read(arrayBuffer, {
                                                                type: 'array'
                                                            });
                                                            // Nettoyer le conteneur
                                                            container.innerHTML = '';
                                                            // Instancier notre visualiseur Excel
                                                            new ExcelViewer("#excelViewerContainer",
                                                                workbook, url.split('/').pop());
                                                        })
                                                        .catch(err => {
                                                            container.innerHTML = `
                                                                <div class="alert alert-danger" style="margin: 20px;">
                                                                    <i class="zmdi zmdi-alert-circle"></i> 
                                                                    Erreur de chargement : ${err.message}<br>
                                                                    Vérifiez que le fichier existe et est accessible.
                                                                </div>
                                                            `;
                                                            console.error(err);
                                                        });
                                                    // ========================
                                                    // Classe ExcelViewer - Visualiseur Excel avancé
                                                    // ========================
                                                    class ExcelViewer {
                                                        constructor(container, workbook, title = "Classeur") {
                                                            this.container = typeof container === 'string' ?
                                                                document.querySelector(container) :
                                                                container;
                                                            if (!this.container) throw new Error(
                                                                "Conteneur introuvable");
                                                            this.workbook = workbook;
                                                            this.title = title;
                                                            this.currentSheetName = null;
                                                            this.buildUI();
                                                            this.initSheets();
                                                        }

                                                        buildUI() {
                                                            this.container.innerHTML = `
                                                                <div class="excel-viewer" style="padding: 10px;">
                                                                    <h5 style="color: #1e466e; margin-bottom: 15px;display:none;">
                                                                        <i class="zmdi zmdi-chart"></i> ${this.title}
                                                                    </h5>
                                                                    <div class="excel-sheet-selector" style="display: none; margin-bottom: 10px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px;">
                                                                        <label style="margin-right: 10px;padding-top:8px;color:black;"><i class="zmdi zmdi-tab"></i> Feuille :</label>
                                                                        <select class="excel-sheet-select" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc;"></select>
                                                                    </div>
                                                                    <div class="excel-info" style="background: #e3f2fd; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;"></div>
                                                                    <div class="excel-search-bar" style="display: flex; gap: 8px; margin-bottom: 10px;">
                                                                        <input type="text" class="excel-search-input" placeholder="Rechercher dans le tableau..." style="flex: 2; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;">
                                                                        <button class="excel-search-btn btn btn-info btn-sm" style="background: #17a2b8; border: none;">
                                                                            <i class="zmdi zmdi-search"></i> Rechercher
                                                                        </button>
                                                                        <button class="excel-clear-btn btn btn-secondary btn-sm" style="background: #6c757d; border: none;">
                                                                            <i class="zmdi zmdi-close"></i> Effacer
                                                                        </button>
                                                                    </div>
                                                                    <div class="excel-table-wrapper" style="overflow: auto; max-height: 500px; border: 1px solid #dee2e6; border-radius: 8px; background: white;"></div>
                                                                    <div class="excel-selected-cell" style="margin-top: 12px; padding: 10px; background: #f1f5f9; border-radius: 8px; display: flex; gap: 10px; align-items: center;">
                                                                        <label style="margin: 0; font-weight: bold; color: #1e466e;"><i class="zmdi zmdi-info"></i> Cellule sélectionnée :</label>
                                                                        <input type="text" class="excel-selected-value" readonly style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; font-family: monospace;">
                                                                    </div>
                                                                </div>
                                                            `;

                                                            this.infoDiv = this.container.querySelector(
                                                                '.excel-info');
                                                            this.sheetSelectorDiv = this.container
                                                                .querySelector('.excel-sheet-selector');
                                                            this.sheetSelect = this.container.querySelector(
                                                                '.excel-sheet-select');
                                                            this.searchInput = this.container.querySelector(
                                                                '.excel-search-input');
                                                            this.searchBtn = this.container.querySelector(
                                                                '.excel-search-btn');
                                                            this.clearBtn = this.container.querySelector(
                                                                '.excel-clear-btn');
                                                            this.tableWrapper = this.container
                                                                .querySelector('.excel-table-wrapper');
                                                            this.selectedCellInput = this.container
                                                                .querySelector('.excel-selected-value');

                                                            this.searchBtn.addEventListener('click', () =>
                                                                this.highlightSearch());
                                                            this.clearBtn.addEventListener('click', () =>
                                                                this.clearHighlights());
                                                            this.sheetSelect.addEventListener('change', (
                                                                e) => {
                                                                this.currentSheetName = e.target
                                                                    .value;
                                                                this.renderCurrentSheet();
                                                            });
                                                        }

                                                        initSheets() {
                                                            const sheetNames = this.workbook.SheetNames;
                                                            if (sheetNames.length === 0) {
                                                                this.showMessage("Aucune feuille trouvée",
                                                                    true);
                                                                return;
                                                            }
                                                            if (sheetNames.length > 1) {
                                                                this.sheetSelectorDiv.style.display =
                                                                    'flex';
                                                                this.sheetSelect.innerHTML = sheetNames.map(
                                                                    n =>
                                                                    `<option value="${n}">${n}</option>`
                                                                ).join('');
                                                            }
                                                            this.currentSheetName = sheetNames[0];
                                                            this.renderCurrentSheet();
                                                        }

                                                        renderCurrentSheet() {
                                                            if (!this.workbook || !this.currentSheetName)
                                                                return;
                                                            const sheet = this.workbook.Sheets[this
                                                                .currentSheetName];
                                                            const html = XLSX.utils.sheet_to_html(sheet, {
                                                                editable: false
                                                            });
                                                            this.tableWrapper.innerHTML =
                                                                `<div style="overflow-x: auto;">${html}</div>`;
                                                            const table = this.tableWrapper.querySelector(
                                                                'table');
                                                            if (table) {
                                                                table.style.width = '100%';
                                                                table.style.borderCollapse = 'collapse';
                                                                table.style.fontSize = '13px';
                                                                table.querySelectorAll('th').forEach(th => {
                                                                    th.style.background = '#2c7da0';
                                                                    th.style.color = 'white';
                                                                    th.style.padding = '8px';
                                                                    th.style.position = 'sticky';
                                                                    th.style.top = '0';
                                                                    th.style.zIndex = '10';
                                                                });
                                                                table.querySelectorAll('td').forEach(td => {
                                                                    td.style.border =
                                                                        '1px solid #d4dce6';
                                                                    td.style.padding = '6px 8px';
                                                                    td.style.cursor = 'pointer';
                                                                    td.addEventListener('click', (
                                                                        e) => {
                                                                        e.stopPropagation();
                                                                        this.onCellClick(
                                                                            td);
                                                                    });
                                                                });
                                                                table.querySelectorAll(
                                                                    'tr:nth-child(even) td').forEach(
                                                                    td => {
                                                                        td.style.backgroundColor =
                                                                            '#fafcff';
                                                                    });
                                                            }
                                                            this.selectedCellInput.value = '';
                                                            this.showMessage(
                                                                `📄 Feuille : ${this.currentSheetName}`);
                                                        }

                                                        onCellClick(cell) {
                                                            if (cell.classList.contains('cell-highlight')) {
                                                                cell.classList.remove('cell-highlight');
                                                            } else {
                                                                cell.classList.remove('cell-search');
                                                                cell.classList.add('cell-highlight');
                                                            }
                                                            const value = cell.innerText || cell
                                                                .textContent;
                                                            this.selectedCellInput.value = value;
                                                            this.showMessage(
                                                                `📌 Contenu : "${value.substring(0, 80)}${value.length > 80 ? '…' : ''}"`
                                                            );
                                                        }

                                                        clearHighlights() {
                                                            const cells = this.tableWrapper
                                                                .querySelectorAll('td, th');
                                                            cells.forEach(cell => cell.classList.remove(
                                                                'cell-highlight', 'cell-search'));
                                                            this.showMessage("✅ Entourages effacés");
                                                        }

                                                        highlightSearch() {
                                                            const text = this.searchInput.value.trim();
                                                            if (!text) {
                                                                this.showMessage(
                                                                    "⚠️ Entrez un texte à rechercher",
                                                                    true);
                                                                return;
                                                            }
                                                            this.clearHighlights();
                                                            const cells = this.tableWrapper
                                                                .querySelectorAll('td');
                                                            let found = [];
                                                            cells.forEach(cell => {
                                                                if (cell.innerText.toLowerCase()
                                                                    .includes(text.toLowerCase())) {
                                                                    cell.classList.add(
                                                                        'cell-search');
                                                                    found.push(cell);
                                                                }
                                                            });
                                                            if (found.length === 0) {
                                                                this.showMessage(
                                                                    `❌ Aucune cellule ne contient "${text}"`,
                                                                    true);
                                                            } else {
                                                                this.showMessage(
                                                                    `🔍 ${found.length} cellule(s) trouvée(s) pour "${text}".`
                                                                );
                                                                this.scrollToCell(found[0]);
                                                            }
                                                        }

                                                        scrollToCell(cell) {
                                                            const wrapper = this.tableWrapper;
                                                            const cellRect = cell.getBoundingClientRect();
                                                            const wrapperRect = wrapper
                                                                .getBoundingClientRect();
                                                            const scrollTop = wrapper.scrollTop + cellRect
                                                                .top - wrapperRect.top - wrapperRect
                                                                .height / 2 + cellRect.height / 2;
                                                            wrapper.scrollTo({
                                                                top: scrollTop,
                                                                behavior: 'smooth'
                                                            });
                                                            cell.classList.add('first-found');
                                                            setTimeout(() => cell.classList.remove(
                                                                'first-found'), 800);
                                                        }

                                                        showMessage(msg, isError = false) {
                                                            this.infoDiv.innerHTML = msg;
                                                            this.infoDiv.style.backgroundColor = isError ?
                                                                '#f8d7da' : '#e3f2fd';
                                                            this.infoDiv.style.color = isError ? '#721c24' :
                                                                '#0c5460';
                                                            if (!isError) {
                                                                setTimeout(() => {
                                                                    if (this.infoDiv.style
                                                                        .backgroundColor !==
                                                                        '#f8d7da') {
                                                                        this.infoDiv.style
                                                                            .backgroundColor =
                                                                            '#e3f2fd';
                                                                        this.infoDiv.style.color =
                                                                            '#0c5460';
                                                                    }
                                                                }, 3000);
                                                            }
                                                        }
                                                    }
                                                }
                                                if (type == "word") {
                                                    var all_url = "<?= asset(""); ?>" + url;
                                                    $("#titre_modal_fichier").html("Visualisation : " + url
                                                        .split('/').pop());
                                                    // var all_url = "https://www.africtechapp.com/public/Introduction.docx";
                                                    document.getElementById("fichier_content").innerHTML =
                                                        '<iframe src="https://docs.google.com/viewer/viewer?url=' +
                                                        encodeURIComponent(all_url) +
                                                        '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                                    $("#btn_detail_fichier").trigger("click");
                                                }
                                                if (type == "pdf") {
                                                    var all_url = "<?= asset(""); ?>" + url;
                                                    $("#titre_modal_fichier").html("Visualisation : " + url
                                                        .split('/').pop());
                                                    // var all_url = "https://www.africtechapp.com/public/FACTURE_MARS_2026_Hall_de_l_____toile.pdf";
                                                    document.getElementById("fichier_content").innerHTML =
                                                        '<iframe src="https://docs.google.com/viewer/viewer?url=' +
                                                        encodeURIComponent(all_url) +
                                                        '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                                                    $("#btn_detail_fichier").trigger("click");
                                                }
                                                if (type == "texte") {
                                                    var all_url = "<?= asset(""); ?>" + url;
                                                    $("#titre_modal_fichier").html("Visualisation : " + url
                                                        .split('/').pop());
                                                    // var all_url = "https://www.africtechapp.com/public/Login_mmg.txt";
                                                    document.getElementById("fichier_content").innerHTML =
                                                        '<iframe src="' + all_url +
                                                        '" style="width:100%; height:100%;" frameborder="0"></iframe>';
                                                    $("#btn_detail_fichier").trigger("click");
                                                }
                                                if (type == "powerpoint") {
                                                    console.log("Powerpoint");
                                                }
                                                if (type == "audio") {
                                                    console.log("audio");
                                                }
                                                if (type == "vidéo") {
                                                    console.log("vidéo");
                                                }
                                            });
                                            $("#telecharger_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                // Récupération directe de la variable PHP (échappement pour sécurité)
                                                var url = "<?= $data->lien ?>";

                                                // Téléchargement via l'attribut download (même origine)
                                                var link = document.createElement('a');
                                                link.href = url;
                                                link.download =
                                                    ""; // Laissez vide pour garder le nom du serveur
                                                document.body.appendChild(link);
                                                link.click();
                                                document.body.removeChild(link);
                                            });
                                            $("#partager_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $.get("{{ url('/refresh_partager_fichier') }}", {
                                                    fichier_document_id: <?= $data->id ?>,
                                                }, function(partager_fichier) {
                                                    $("#bloc_1").hide();
                                                    $("#bloc_2").hide();
                                                    $("#bloc_3").hide();
                                                    $("#bloc_4").show();
                                                    $("#bloc_4").html(partager_fichier);
                                                });
                                            });
                                            </script>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bloc_2" style="margin-top: 12px;display: none;padding-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email text-info"></i>
                    Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                        class="zmdi zmdi-info"></i> Type de document <span class="text-danger">*</span>
                                    </span></label>
                                <select id="type_document_id" name="type_document_id" class="select2"
                                    data-placeholder="Selectionnez une catégorie">
                                    <option selected value="">Selectionnez un type de documents</option>
                                    @foreach ($type_documents as $data)
                                    <option value="{{ $data->id }}"><?=  $data->nom ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                        class="zmdi zmdi-comment"></i> Description </span></label>
                                <textarea id="description" name="description"
                                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                    class="form-control" placeholder="Ecrirez une petite description du fichier"
                                    cols="2" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
                <div style="margin-top: -2px;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre
                            fichier ici </span></label>
                        <form method="post"
                            style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;"
                            action="{{ route('upload') }}" class="dropzone" id="dropzonewidget">
                            @csrf
                        </form>
                    </div>
                </div>
                <div style="margin-top: 22px;display: none;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre
                            document </span></label>
                        <form method="post"
                            style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;"
                            action="{{ route('upload_2') }}" class="dropzone" id="dropzonewidget_1">
                            @csrf
                        </form>
                    </div>

                </div>
                <form action="">
                    <div style="margin-top: 20px;" class="row">
                        <div class="col-12">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                            <?php
                                $edit = 0;
                                $delete = 0;
                                $add = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                            <?php } ?>
                            <?php if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0))) { ?>
                            <button id="save" class="btn btn-info btn-sm">Enregister <i
                                    class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                            <button id="save_r" class="btn btn-info btn-sm">Enregister <i
                                    class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger btn-sm">Annuler <i
                                    class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg">
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">

            </div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">

            </div>
        </div>
    </div>
</section>
<span id="data_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                    supprimez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm"
                        data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
@section('js-code')
<script src="{{ asset('assets/vendors/flot/jquery.flot.js') }} "></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.orderbars/jquery.flot.orderBars.js') }} "></script>
<script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/bar.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/dynamic.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/pie.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/chart-tooltips.js') }}"></script>
<script>
get_delete_fichier();
$("#link_29").css("border-left", "1px solid rgb(33, 150, 243)");
$("#text_29").addClass("text-info");
$("#upload").click(function(e) {
    e.preventDefault();
    $("#dropzonewidget").trigger("click");
})
$("#liste").click(function(e) {
    e.preventDefault();
    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide();
    $("#bloc_4").hide();
});
$("#add").click(function(e) {
    e.preventDefault();
    get_delete_fichier();
    $("#bloc_1").hide();
    $("#bloc_2").show();
    $("#bloc_3").hide();
    $("#bloc_4").hide();
});
$("#add_r").click(function(e) {
    e.preventDefault();
    $("#btn_refus").trigger("click");
});
$("#save_r").click(function(e) {
    e.preventDefault();
    $("#btn_refus").trigger("click");
});
$("#annuler").click(function(e) {
    e.preventDefault();
    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide();
});
$("#save").click(function(e) {
    e.preventDefault();
    var type_document_id = $("#type_document_id").val();
    var description = $("#description").val();
    var data = $("#form_add").serialize();
    if (type_document_id.trim().length == 0) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectioinnez le type de fichier');
        $('#msg').css('color', "#ff6b68");
        setTimeout(() => {
            $('#msg').html("");
        }, 9000);
    } else {
        $("#save").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "/add_documents",
            data: data,
            success: function(response) {
                $("#save").attr("disabled", false);
                Dropzone.forElement('#dropzonewidget').removeAllFiles(true)
                Dropzone.forElement('#dropzonewidget_1').removeAllFiles(true)
                $("#description").val("");
                $('#msg').html(
                    '<i class="zmdi zmdi-check-circle"></i> Document ajouté avec succès');
                $('#msg').css("color", '#32c787');
                $("#content_utilisateur").html(response);
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            }
        });
    }
});

$("#oui").click(function(e) {
    e.preventDefault();
    var id = $("#data_id").html();
    $.get("{{ url('/refresh_delete_fichier_documents') }}", {
        id: id,
    }, function(refresh_editutilisateur) {
        $("#content_utilisateur").html(refresh_editutilisateur);
        $("#non").trigger("click");
    });
});
$(".dropzone").dropzone({
    addRemoveLinks: true,
    removedfile: function(file) {
        var name = file.name;

        $.ajax({
            type: 'POST',
            url: '/upload',
            data: {
                name: name,
                request: 2
            },
            sucess: function(data) {
                console.log('success: ' + data);
            }
        });
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) :
            void 0;
    }
});
$(".dropzone_2").dropzone({
    addRemoveLinks: true,
    removedfile: function(file) {
        var name = file.name;

        $.ajax({
            type: 'POST',
            url: '/upload_2',
            data: {
                name: name,
                request: 2
            },
            sucess: function(data) {
                console.log('success: ' + data);
            }
        });
        var _ref;
        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) :
            void 0;
    }
});
// $('.commodites').selectMultiple();
function get_delete_fichier() {
    $.get("{{ url('/get_delete_fichier') }}", {}, function(response) {
        console.log(response);
    });
}
</script>
@endsection
@endsection