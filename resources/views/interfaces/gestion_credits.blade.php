<?php

use App\Models\Contrevenants;
use App\Models\Remboursements;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Entres;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'GESTION CREDIT')
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
   + SURVOL DISCRET SUR LES BOUTONS DE LA COLONNE CONTROL
   + CHAMP ENTREE ET DEVISE SUR LA MÊME LIGNE (NOUVEAU CRÉDIT)
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

/* ========== CORRECTION SPÉCIFIQUE POUR LA COLONNE "Control" ========== */
/* La cellule elle-même doit contenir son contenu sans débordement */
.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
    overflow: visible;
}

/* Boutons de la colonne Control : toujours à l'intérieur de la cellule */
.table tbody td:last-child a {
    display: inline-flex !important;
    align-items: center;
    gap: 6px;
    width: auto !important;
    height: auto !important;
    padding: 6px 14px !important;
    border-radius: 40px !important;
    background: #f1f5f9;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    margin: 4px 6px 4px 0;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    white-space: nowrap;
}

/* Icônes dans ces boutons */
.table tbody td:last-child a i.zmdi {
    font-size: 1rem;
    margin: 0;
}

/* Couleurs de fond et texte selon la classe (Entrée, Sortie, Entrée & sortie) */
.table tbody td:last-child a.text-success {
    background: #e0f2e9;
    color: #0a5c3e;
}
.table tbody td:last-child a.text-danger {
    background: #fee2e2;
    color: #b91c1c;
}
.table tbody td:last-child a.text-info {
    background: #e0f2fe;
    color: #0c4e6e;
}

/* ========== SURVOL RÉDUIT ET DISCRET ========== */
.table tbody td:last-child a:hover {
    filter: brightness(0.96);
    transform: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.table tbody td:last-child a.text-success:hover {
    background: #cce8df;
}
.table tbody td:last-child a.text-danger:hover {
    background: #f5d0d0;
}
.table tbody td:last-child a.text-info:hover {
    background: #cde5f5;
}

/* Pour les très petits écrans : les boutons peuvent passer à la ligne (reste dans le td) */
@media (max-width: 640px) {
    .table tbody td:last-child {
        display: table-cell;
        text-align: center;
    }
    .table tbody td:last-child a {
        margin: 2px;
        padding: 5px 10px !important;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    @media (max-width: 480px) {
        .table tbody td:last-child a {
            white-space: normal;
            word-break: keep-all;
        }
    }
}

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

/* ========== NOUVEAU : CHAMP ENTREE + DEVISE SUR LA MÊME LIGNE (FORMULAIRE CRÉDIT) ========== */
/* On cible la cellule contenant le champ montant (5e colonne) dans le formulaire "Nouveau crédit" */
#bloc_2 table tbody tr td:nth-child(5) {
    white-space: nowrap;
}
#bloc_2 table tbody tr td:nth-child(5) input#entree,
#bloc_2 table tbody tr td:nth-child(5) select#devise_credit {
    display: inline-block !important;
    width: auto !important;
    vertical-align: middle;
    margin-right: 6px;
}
#bloc_2 table tbody tr td:nth-child(5) input#entree {
    width: 120px !important;
}
#bloc_2 table tbody tr td:nth-child(5) select#devise_credit {
    width: 85px !important;
    margin-left: 4px;
    padding: 8px 8px 8px 12px !important;
    background-position: right 8px center;
}
/* Ajustement de la hauteur pour un alignement parfait */
#bloc_2 table tbody tr td:nth-child(5) input#entree,
#bloc_2 table tbody tr td:nth-child(5) select#devise_credit {
    height: 40px !important;
}

/* Responsive : sur très petit écran, on autorise le retour à la ligne si nécessaire */
@media (max-width: 640px) {
    #bloc_2 table tbody tr td:nth-child(5) {
        white-space: normal;
    }
    #bloc_2 table tbody tr td:nth-child(5) input#entree,
    #bloc_2 table tbody tr td:nth-child(5) select#devise_credit {
        margin-bottom: 4px;
    }
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
                                        &nbsp;
                                        <a id="print" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-print"></i> Imprimer
                                        </a>
                                    <?php } else { ?>
                                        <a id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
                                        <a id="print_r" href="">
                                            <i class="zmdi zmdi-print"></i> Imprimer
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de crédit</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste</h4>

                <div id="content_utilisateur" style="margin-bottom: 50px;" class="row">
                    <div class="col-12">
                        <?php
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

                        ?>
                        <h6 style="color:rgba(0, 0, 0, 0.6);text-align: right;font-weight: bold;"><span class="text-info">Crédit total : {{ number_format($credit_total, 2, ',', ' ') .'$'; }}</span>,  <span class="text-success">Remboursement total : {{ number_format($r_total, 2, ',', ' ') .'$'; }}</span>,  <span class="text-danger">Reste total : {{ number_format($credit_total - $r_total, 2, ',', ' ') .'$'; }}</span></h6>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date crédit</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Type</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($credits as $data)
                                    <tr>
                                        <?php
                                            $t = 0;
                                            $cd = Remboursements::where(['credit_id' => $data->id])->get();
                                            foreach ($cd as $e)
                                            {
                                                $t = $t + $e->entree;
                                            }
                                        ?>
                                        <?php if(true){ ?>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_credit }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_credit }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ $data->type }}
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ number_format($t, 2, ',', ' ') .'$'; }} / {{ number_format($data->entree, 2, ',', ' ') .'$'; }}
                                            </td>
                                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                    <?php
                                                    $edit = 0;
                                                    $delete = 0;
                                                    $display = 0;
                                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                        $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                                        $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                        $display = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->display;
                                                    }
                                                    ?>
                                                <?php } ?>
                                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                    <a class="text-success" id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-success"></i> Rembourser</a> &nbsp;
                                                <?php } else { ?>
                                                    <a class="text-success" id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-success"></i> Rembourser</a> &nbsp;
                                                <?php } ?>
                                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                    <a class="text-danger" id="detaill_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-danger"></i> Créditer</a> &nbsp;
                                                <?php } else { ?>
                                                    <a class="text-danger" id="detail_rr<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-danger"></i> Créditer</a> &nbsp;
                                                <?php } ?>
                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_editdecisions') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#edit_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#detail_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_detailcredits') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#detaill_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_detailcredits_3') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#detail_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#detail_rr<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#delete_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#delete_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#element").html("<?= $data->numero_decision ?>");
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        $("#btn_sup").trigger("click");
                                                    });
                                                </script>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bloc_2" style="margin-top: 12px;display: none;padding-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email text-info"></i> Nouveau crédit</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">DATE</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">NOM</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">TYPE(E ou C)</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Libellé</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Montant($)</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Taux(CDF)</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Sortie($)</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Solde($)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;w">
                                            <input id="date_credit" name="date_credit" type="text" class="input-mask" data-mask="00/00/0000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="<?= date("d/m/Y") ?>">
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            <input class="form-control" id="nom_credit" name="nom_credit" type="text" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="Nom" value="">
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <input class="form-control" id="type" name="type" type="text" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="TYPE(E ou C)" value="">
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <input  class="form-control" id="libelle" name="libelle" type="text" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 140px;" placeholder="Libellé" value="">
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <input id="entree" name="entree" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="0">
                                            <select class="form-control" id="devise_credit" name="devise_credit">
                                                <option value="0">USD</option>
                                                <option value="1">CDF</option>
                                            </select>
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <input id="taux_credit" name="taux_credit" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 50px;" placeholder="" value="2800">
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;display: none;">
                                            <input id="sortie" name="sortie" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="0">
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;display: none;">
                                            <input id="solde" name="solde" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    <div style="display: none;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Numero operaton</span></label>
                                <select id="numero_facture" name="numero_facture" class="select2" data-placeholder="Selectionnez un type de sortie">
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Il s'agit de quelle entré ?</span></label>
                                <select id="type_sortie" name="type_sortie" class="select2" data-placeholder="Selectionnez un type de d'entré">
                                    <option selected value="">Selectionnez un type de sortie</option>
                                    @foreach ($type_frais as $data)
                                        <option selected value="{{ $data->id }}"><?=  $data->nom ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;display: none;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Prix unitaire </span></label>
                                <input id="prix_unitaire" name="prix_unitaire" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Prix unitaire (Ex : 10)" value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Quantité </span></label>
                                <input id="quantite" name="quantite" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Quantité (Ex : 10)" value="1">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;display: none;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> devise </span></label>
                                <select id="devise" name="devise" class="select2" data-placeholder="Selectionnez une devise">
                                    <option selected class="form-control" value="">Selectionnez une devise</option>
                                    <option selected class="form-control" value="0"> $</option>
                                    <option class="form-control" value="1"> Fc</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Taux</span></label>
                                <input id="taux" name="taux" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Taux (Ex : 10)" value="2800">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">

                        </div>
                    </div>
                </form>
                <div style="margin-top: -2px;display: none;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre attache d'entré</span></label>
                        <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;" action="{{ route('upload_fichier_sortie') }}" class="dropzone" id="dropzonewidget">
                            @csrf
                            <input type="hidden" id="n_s" name="n_s" value="">
                        </form>
                    </div>
                </div>
                <br>
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
                                <button id="save" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg">
                            </span>
                        </div>
                    </div>
                </form>
                <br>
                <div class="row" id="content_sortie">

                </div>
                <br>
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
<span id="data_frais_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#c_frais" id="btn_frais">Sup</button>
<div class="modal fade" id="c_frais" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous approuvez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element_1" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui_frais" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_frais" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
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
    $("#link_26").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_26").addClass("text-info");
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
        $.get("{{ url('/get_numero_facture') }}", {
        }, function(response)
        {
            $("#numero_facture").html(response);
        });
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
    });
    $("#print").click(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_liste_credit') }}", {
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
    $("#entree").keyup(function(e){
        e.preventDefault();
        var entree = $("#entree").val();
        var sortie = $("#sortie").val();
        var solde = 0;
        if((Number(entree) == entree) && (Number(sortie) == sortie))
        {
            solde = Number(entree) + Number(sortie);
            $("#solde").val(solde);
        }
    });
    $("#sortie").keyup(function(e){
        e.preventDefault();
        var entree = $("#entree").val();
        var sortie = $("#sortie").val();
        var solde = 0;
        if((Number(entree) == entree) && (Number(sortie) == sortie))
        {
            solde = Number(entree) + Number(sortie);
            $("#solde").val(solde);
        }
    });
    $("#save").click(function(e) {
        e.preventDefault();
        var date_credit = $("#date_credit").val();
        var nom_credit = $("#nom_credit").val();
        var type = $("#type").val();
        var libelle = $("#libelle").val();
        var entree = $("#entree").val();
        var sortie = $("#sortie").val();
        var solde = $("#solde").val();
        var numero_facture = $("#numero_facture").val();
        var type_sortie = $("#type_sortie").val();
        var prix_unitaire = $("#prix_unitaire").val();
        var quantite = $("#quantite").val();
        var devise = $("#devise").val();
        var taux_credit = $("#taux_credit").val();
        var taux = $("#taux").val();
        var libelle = $("#libelle").val();
        var data = $("#form_add").serialize();
        if(date_credit.trim().length == 0)
        {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date du crédit');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        }
        else
        {
            if(nom_credit.trim().length == 0){
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom du client');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            }else
            {
                if(type.trim().length == 0){
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le type de client (Entreprise ou Client)');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                }else{
                    if(libelle.trim().length == 0)
                    {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    }
                    else
                    {
                        if(entree.trim().length == 0)
                        {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        }
                        else
                        {
                            if(entree.trim() <= 0)
                            {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le montant dois être supétieur à 0');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            }else
                            {
                                if((entree.trim().length == 0) && (sortie.trim().length == 0))
                                {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez soit l\'entrée ou la sortie');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                }
                                else
                                {
                                    if((entree.trim() > 0) && (sortie.trim() > 0))
                                    {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez soit l\'entrée ou la sortie');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    }
                                    else
                                    {
                                        if(taux_credit.trim().length == 0)
                                        {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez taux du crédit');
                                            $('#msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#msg').html("");
                                            }, 9000);
                                        }else
                                        {
                                            if (numero_facture.trim().length == 0)
                                            {
                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero d\'entré');
                                                $('#msg').css('color', "#ff6b68");
                                                setTimeout(() => {
                                                    $('#msg').html("");
                                                }, 9000);
                                            } else {
                                                if (type_sortie.trim().length == 0)
                                                {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le type de d\'entré');
                                                    $('#msg').css('color', "#ff6b68");
                                                    setTimeout(() => {
                                                        $('#msg').html("");
                                                    }, 9000);
                                                } else
                                                {
                                                    if (prix_unitaire.trim().length == 0)
                                                    {
                                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix unitaire');
                                                        $('#msg').css('color', "#ff6b68");
                                                        setTimeout(() => {
                                                            $('#msg').html("");
                                                        }, 9000);
                                                    } else {
                                                        if (quantite.trim().length == 0) {
                                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                                                            $('#msg').css('color', "#ff6b68");
                                                            setTimeout(() => {
                                                                $('#msg').html("");
                                                            }, 9000);
                                                        } else
                                                        {
                                                            if (devise.trim().length == 0)
                                                            {
                                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                                                                $('#msg').css('color', "#ff6b68");
                                                                setTimeout(() => {
                                                                    $('#msg').html("");
                                                                }, 9000);
                                                            }else
                                                            {
                                                                if (taux.trim().length == 0)
                                                                {
                                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                                                                    $('#msg').css('color', "#ff6b68");
                                                                    setTimeout(() => {
                                                                        $('#msg').html("");
                                                                    }, 9000);
                                                                }
                                                                else
                                                                {
                                                                    if((entree.trim() > 0))
                                                                    {
                                                                        $("#save").attr("disabled", true);
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "/add_credit",
                                                                            data: data,
                                                                            success: function(response) {
                                                                                $("#libelle").val("");
                                                                                $("#nom_credit").val("");
                                                                                $("#type").val("");
                                                                                $("#entree").val(0);
                                                                                $("#sortie").val(0);
                                                                                $("#solde").val(0);
                                                                                $("#save").attr("disabled", false);
                                                                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> crédit ajouté avec succès');
                                                                                $('#msg').css("color", '#32c787');
                                                                                $("#content_utilisateur").html(response);
                                                                                setTimeout(() => {
                                                                                    $('#msg').html("");
                                                                                }, 9000);
                                                                            }
                                                                        });
                                                                    }
                                                                    if((sortie.trim() > 0))
                                                                    {
                                                                        $("#save").attr("disabled", true);
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "/add_credit",
                                                                            data: data,
                                                                            success: function(response) {
                                                                                $("#save").attr("disabled", false);
                                                                                $("#libelle").val("");
                                                                                $("#nom_credit").val("");
                                                                                $("#type").val("");
                                                                                $("#entree").val(0);
                                                                                $("#sortie").val(0);
                                                                                $("#solde").val(0);
                                                                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> crédit ajouté avec succès');
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
    });

    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_deletedecision') }}", {
            id: id,
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
        });
    });
    $(".dropzone").dropzone({
        addRemoveLinks: true,
        removedfile: function(file) {
            $.ajax({
                type: 'POST',
                url: '/upload_fichier_sortie',
                data: {
                    name: name,
                    request: 2
                },
                sucess: function(data)
                {
                    console.log('success: ' + data);
                }
            });
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
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
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });
    $.get("{{ url('/get_numero_facture') }}", {
    }, function(response)
    {
        $("#numero_facture").html(response);
    });
    // $('.commodites').selectMultiple();
</script>
@endsection
@endsection
