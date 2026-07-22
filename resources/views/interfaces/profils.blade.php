@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Groupes;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'PROFILS')
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

/* ========== BOUTONS RONDS POUR LA COLONNE CONTROL ========== */
.table tbody td a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50% !important;
    background: #f1f5f9;
    transition: all 0.2s ease;
    text-decoration: none;
    margin: 0 2px;
}

.table tbody td a i.zmdi {
    font-size: 1.1rem;
    margin: 0;
}

.table tbody td a i.zmdi-edit { color: #2c7da0; }
.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a i.zmdi-delete { color: var(--rouge-feu); }
.table tbody td a:hover i.zmdi-delete { color: var(--rouge-fonce); }
.table tbody td a:hover { background: #ffe5e5; }

/* ========== BOUTONS PRINCIPAUX ========== */
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

/* ========== MESSAGES MODERNES - TOTALEMENT INVISIBLE PAR DÉFAUT (ajout & édition) ========== */
#msg, #edit_msg {
    display: none !important;      /* Caché quoi qu'il arrive */
    visibility: hidden !important; /* Invisible même s'il prend de la place */
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
    .table tbody td a {
        width: 28px;
        height: 28px;
    }
    .table tbody td a i.zmdi {
        font-size: 1rem;
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


/* =============================================
   CSS COMPLET POUR TABLEAU UTILISATEURS
   Centrage vertical & suppression survol
   ============================================= */

/* --- Alignement vertical des cellules --- */
.table tbody td {
    vertical-align: middle !important;
    padding: 8px 10px !important;
}

/* --- Conteneur de l’image + texte (sans modifier le HTML) --- */
.table tbody td:has(a[id^="voir_profil_"]) {
    /* On cible la cellule qui contient le lien profil */
    white-space: nowrap; /* Empêche le retour à la ligne intempestif */
}

/* Lien contenant l’image */
a[id^="voir_profil_"] {
    display: inline-block;
    vertical-align: middle;
    line-height: 0;
    margin-right: 8px;
    /* Suppression de tout effet visuel */
    background: transparent !important;
    text-decoration: none !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

/* Image de profil */
.profile-thumb {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    display: inline-block;
    vertical-align: middle;
    border: none;
    background: transparent;
    box-shadow: none;
    transition: none;
}

/* Texte du nom (directement dans la cellule, après le lien) */
.table tbody td a[id^="voir_profil_"] + * {
    display: inline-block;
    vertical-align: middle;
    line-height: 1.4;
    max-width: calc(100% - 45px);
    white-space: normal;
    word-break: break-word;
}

/* --- SUPPRESSION TOTALE DE TOUT EFFET AU SURVOL --- */
a[id^="voir_profil_"]:hover,
a[id^="voir_profil_"]:focus,
a[id^="voir_profil_"]:active {
    background: transparent !important;
    color: inherit !important;
    transform: none !important;
    box-shadow: none !important;
    border: none !important;
    outline: none !important;
    opacity: 1 !important;
    filter: none !important;
}

.profile-thumb:hover,
.profile-thumb:focus,
.profile-thumb:active {
    transform: none;
    opacity: 1;
    filter: none;
    background: transparent;
    box-shadow: none;
    border: none;
    outline: none;
}

/* Évite un éventuel soulignement ou changement de couleur sur le texte */
a[id^="voir_profil_"]:hover + * {
    color: inherit !important;
    background: transparent !important;
}

/* Pour les écrans mobiles : on garde le centrage mais on réduit la marge */
@media (max-width: 768px) {
    .profile-thumb {
        width: 28px;
        height: 28px;
    }
    a[id^="voir_profil_"] {
        margin-right: 6px;
    }
}
</style>
<section class="content">
    <div style="margin-top: 30px;" class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Profils</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <form id="form_add" action="#" method="post">
                    @csrf
                    <h4 style="color:rgba(0, 0, 0, 0.6);" class="text-center"><a href="#"><img id="user_img_profil" class="user__img" src="{{ asset( Auth::user()->image ) }}" alt="" style="width: 100px; height: 100px; object-fit: cover;"></a></h4>
                    <!-- Barre de progression -->
                    <div class="progress-container" style="display:none; margin-top: 10px;">
                        <div class="progress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;"></div>
                        <span class="progress-text" style="font-size:12px;">0%</span>
                    </div>

                    <input type="file" name="input_user_img_profil" id="input_user_img_profil" style="display:none;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="{{ $users->name }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i> E-mail </span></label>
                                <input type="text" id="email" name="email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Email (Ex : mgm@gmail.com)" value="{{ $users->email }}">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i> Telephone </span></label>
                                <input type="text" id="phone" name="phone" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Telephone (Ex : +243974743675)" value="{{ $users->phone }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Ancien mot de passe </span></label>
                                <input type="text" id="mdpa" name="mdpa" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Ancien mot de passe" value="">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Mot de passe </span></label>
                                <input type="text" id="mdp" name="mdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Mot de passe">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Confirmez </span></label>
                                <input type="text" id="cmdp" name="cmdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Confirmez">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button id="save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button>
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
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
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
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
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
    $("#link_10").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_10").addClass("text-info");
    $("#icone_10").css("color", "rgb(33, 150, 243)");
    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
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
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
    });
    $("#print").click(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_liste_employe') }}", {
        }, function(response)
        {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("")  }}' + response);
        });
    });
    $("#add_r").click(function(e) {
        e.preventDefault();
        $("#btn_refus").trigger("click");
    });
    $("#print_r").click(function(e) {
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
        $("#bloc_4").hide();
    });

    $("#save").click(function(e) {
        e.preventDefault();
        var nom = $("#nom").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var mdpa = $("#mdpa").val();
        var mdp = $("#mdp").val();
        var cmdp = $("#cmdp").val();
        var data = $("#form_add").serialize();
        if (nom.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (email.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'adresse e-mail');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (!regex.test(email)) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/check_email_utilisateur_1",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette adresse e-mail existe déjà');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            } else {
                                if (phone.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero de telephone');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                } else {
                                    if (!Number(phone.trim()))
                                    {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez un bon numero de telephone');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    } else {
                                        $.ajax({
                                            type: "POST",
                                            url: "/check_phone_utilisateur_1",
                                            data: data,
                                            success: function(response) {
                                                if (response == 1) {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Ce numero de telephone existe déjà');
                                                    $('#msg').css('color', "#ff6b68");
                                                    setTimeout(() => {
                                                        $('#msg').html("");
                                                    }, 9000);
                                                } else {
                                                    if((mdp.trim().length != 0) || (cmdp.trim().length != 0))
                                                    {
                                                        if (mdpa.trim().length == 0)
                                                        {
                                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l`\ancien mot de passe');
                                                            $('#msg').css('color', "#ff6b68");
                                                            setTimeout(() => {
                                                                $('#msg').html("");
                                                            }, 9000);
                                                        } else
                                                        {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "/check_mdpa",
                                                                data: data,
                                                                success: function(response)
                                                                {
                                                                    if (response == 0)
                                                                    {
                                                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'ancien mot de passe est incorrect');
                                                                        $('#msg').css('color', "#ff6b68");
                                                                        setTimeout(() => {
                                                                            $('#msg').html("");
                                                                        }, 9000);
                                                                    } else {
                                                                        if (mdp.trim().length == 0) {
                                                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le mot de passe');
                                                                            $('#msg').css('color', "#ff6b68");
                                                                            setTimeout(() => {
                                                                                $('#msg').html("");
                                                                            }, 9000);
                                                                        } else {
                                                                            if (cmdp.trim().length == 0) {
                                                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Confirmez le mot de passe');
                                                                                $('#msg').css('color', "#ff6b68");
                                                                                setTimeout(() => {
                                                                                    $('#msg').html("");
                                                                                }, 9000);
                                                                            } else {
                                                                                if (cmdp != mdp) {
                                                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Incohérance de deux mot de passe');
                                                                                    $('#msg').css('color', "#ff6b68");
                                                                                    setTimeout(() => {
                                                                                        $('#msg').html("");
                                                                                    }, 9000);
                                                                                } else
                                                                                {
                                                                                    $("#save").attr("disabled", true);
                                                                                    $.ajax({
                                                                                        type: "POST",
                                                                                        url: "/edit_utilisateur_profil",
                                                                                        data: data,
                                                                                        success: function(response) {
                                                                                            $("#save").attr("disabled", false);
                                                                                            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Profil modifié avec succès');
                                                                                            $('#msg').css("color", '#32c787');
                                                                                            $("#content_utilisateur").html(response);
                                                                                            setTimeout(() => {
                                                                                                $('#msg').html("");
                                                                                            }, 9000);
                                                                                        }
                                                                                    });
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $("#save").attr("disabled", true);
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "/edit_utilisateur_profil",
                                                            data: data,
                                                            success: function(response) {
                                                                $("#save").attr("disabled", false);
                                                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Profil modifié avec succès');
                                                                $('#msg').css("color", '#32c787');
                                                                $(".user__email").html(response);
                                                                $("#user__email_1").html(response);
                                                                setTimeout(() => {
                                                                    $('#msg').html("");
                                                                }, 9000);
                                                            }
                                                        });
                                                    }

                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    });
    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_deleteutilisateur') }}", {
            id: id,
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
        });
    });
    $("#user_img_profil").click(function(e){
         e.preventDefault();
         $("#input_user_img_profil").trigger("click");
    });

    $("#input_user_img_profil").change(function(e){
        e.preventDefault();
        var formData = new FormData();
        formData.append('input_user_img_profil', $('#input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Ajout du token
        // $("#save").attr("disabled", true);
        // Afficher la barre de progression avant l'upload
        $('.progress-container').show();
        $('.progress-bar').css('width', '0%');
        $('.progress-text').text('0%');

        $.ajax({
            type: "POST",
            url: "/upload_profil",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                // Suivre la progression
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('.progress-bar').css('width', percentComplete + '%');
                        $('.progress-text').text(percentComplete + '%');
                    }
                }, false);

                return xhr;
            },
            success: function(response) {
                // $("#save").attr("disabled", false);

                // Barre à 100%
                $('.progress-bar').css('width', '100%');
                $('.progress-text').text('100%');

                setTimeout(function() {
                    $('.progress-container').hide();
                }, 1000);

                $('#msg').html('Profil modifié avec succès');
                $('#msg').css("color", '#32c787');
                $('#user_img_profil').attr('src', response);
                $('#user__img').attr('src', response);
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            },
            error: function(xhr) {
                // $("#save").attr("disabled", false);
                $('.progress-container').hide();
                $('#msg').html(xhr.responseJSON.message);
                $('#msg').css("color", 'red');
            }
        });
    })

</script>
@endsection
@endsection
