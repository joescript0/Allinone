@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\User;
use App\Models\Activites;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'SUIVI DE PROSPECT')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<!-- ===== FEUILLE DE STYLE LEAFLET ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- ===== FEUILLE DE STYLE LEAFLET.MARKERCLUSTER ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES PAGES "GESTION ARTICLE",
   "APPROVISIONNEMENT" ET "FACTURES" – ADAPTÉ AUX CLIENTS
   ============================================================ */

/* --- Reset des marges pour occuper tout l'écran --- */
body {
    margin: 0;
    padding: 0;
    background: #f0f4f8;
}

.content .container {
    max-width: 100% !important;
    width: 100%;
    padding: 0.5rem 1.5rem !important;
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

/* --- Variables (identiques aux autres pages) --- */
:root {
    --bleu-nuit: #0a192f;
    --bleu-nuit-clair: #112240;
    --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
    --bleu-secondaire: #2c5282;
    --bleu-secondaire-gradient: linear-gradient(135deg, #2c5282, #1a365d);
    --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --vert-gradient: linear-gradient(135deg, #10b981, #059669);
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

/* --- Cartes principales --- */
#bloc_1,
#bloc_2,
#bloc_3,
#bloc_4 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

/* --- En-têtes --- */
h4 {
    font-weight: 700;
    border-left: 6px solid #e31b23;
    padding-left: 18px;
    margin-bottom: 16px;
    margin-top: 0;
    color: var(--bleu-nuit);
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* ========== TABLEAU : LIGNES AÉRÉES ET VISIBLES ========== */
.table-responsive {
    overflow-x: auto;
    overflow-y: visible;
    border-radius: var(--border-radius-lg);
}

.table {
    width: 100%;
    min-width: 800px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    table-layout: auto;
}

/* En-tête */
.table thead th {
    background: #E7F5FE !important;
    color: #0a192f;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 14px 12px !important;
    border-bottom: 2px solid #cbd5e1 !important;
    border-right: 1px solid #d0e2f2;
    white-space: normal;
    word-break: break-word;
}

/* Lignes du tableau : padding augmenté, rayures et bordures nettes */
.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #e2e8f0;
}

.table tbody tr:nth-child(even) {
    background-color: #f8fafc;
}

.table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

.table tbody tr:hover {
    background: #e6f0ff !important;
    cursor: default;
}

.table tbody td {
    padding: 10px 12px !important;
    vertical-align: middle !important;
    font-weight: 500;
    font-size: 0.85rem;
    color: #1e2a3e;
    word-break: break-word;
    border-bottom: 1px solid #eef2f6;
    line-height: 1.4;
}

.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
}

/* ========== STYLE UNIQUE POUR TOUS LES BOUTONS ========== */
#bloc_1 button,
#bloc_2 button,
#bloc_3 button,
#bloc_4 button,
#liste,
#add,
#add_r,
#save,
#save_r,
#annuler,
#edit_save,
#edit_annuler,
#print,
#print_r,
#mapAll,
#mapAll_r,
.btn-primary,
.btn-info,
.btn-danger,
.btn-success,
.btn-secondary {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 16px !important;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px !important;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: var(--shadow-light);
    white-space: nowrap;
    line-height: 1.5;
}

/* Couleurs spécifiques pour chaque type de bouton */
#liste,
.btn-primary {
    background: #3B82F6 !important;
    color: white !important;
}
#liste:hover,
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    background: #2563eb !important;
}

#add,
.btn-info {
    background: var(--bleu-nuit-gradient) !important;
    color: white !important;
}
#add:hover,
.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#save,
#edit_save {
    background: var(--bleu-secondaire-gradient) !important;
    color: white;
}
#save:hover,
#edit_save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
}

#annuler,
#edit_annuler,
.btn-danger {
    background: var(--rouge-gradient) !important;
    color: white;
}
#annuler:hover,
#edit_annuler:hover,
.btn-danger:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
}

.btn-success {
    background: var(--vert-gradient) !important;
    color: white !important;
}
.btn-success:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #059669, #047857) !important;
    box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
}

#resetFilters {
    background: #64748b !important;
    color: white !important;
}
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
}

/* Boutons désactivés (add_r, save_r, print_r, mapAll_r) */
#add_r,
#save_r,
#print_r,
#mapAll_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    transform: none !important;
    box-shadow: none !important;
}

/* ===== BOUTON "CARTE" (ROUGE) ET BOUTON "IMPRIMER" ===== */
#print {
    background: #3B82F6 !important;
    color: white !important;
}
#print:hover {
    background: #2563eb !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
}

#mapAll {
    background: var(--rouge-gradient) !important;
    color: white !important;
}
#mapAll:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
}

/* ========== FILTRES ========== */
.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    background: white;
    padding: 0.8rem 1.2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--bleu-nuit);
    font-size: 0.7rem;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-group .form-control {
    height: 36px;
}

.client-count-badge {
    background: var(--rouge-gradient);
    color: white;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

/* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
#form_add .row,
#form_edit .row {
    display: flex;
    flex-wrap: wrap;
}

#form_add .col-6,
#form_edit .col-6 {
    margin-bottom: 0.8rem;
}

.form-group {
    width: 100%;
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-weight: 700;
    color: var(--bleu-nuit);
    margin-bottom: 4px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.form-group label i {
    color: #e31b23;
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
    padding: 8px 12px !important;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    box-sizing: border-box;
    height: 38px !important;
    line-height: 1.4;
}

textarea.form-control {
    resize: vertical;
    height: 38px !important;
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

/* ========== MESSAGES STYLISÉS (SUCCÈS / ERREUR / INFO) ========== */
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

/* Succès */
#msg.msg-success, #edit_msg.msg-success {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46 !important;
    border-left: 4px solid #10b981 !important;
}

/* Erreur */
#msg.msg-error, #edit_msg.msg-error {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b !important;
    border-left: 4px solid #ef4444 !important;
}

/* Information */
#msg.msg-info, #edit_msg.msg-info {
    background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important;
    color: #1e3a8a !important;
    border-left: 4px solid #3b82f6 !important;
}

@keyframes slideInMsg {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ========== BOUTONS DE CONTRÔLE DANS LE TABLEAU ========== */
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
.table tbody td a i.zmdi-edit {
    color: #10b981;
}
.table tbody td a i.zmdi-delete {
    color: #ef4444;
}
.table tbody td a i.zmdi-pin {
    color: #3b82f6;
}
.table tbody td a i.zmdi-save {
    /* couleur gérée dynamiquement via les classes text-danger / text-success */
}
.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a:hover i.zmdi-delete {
    color: #b91c1c;
}
.table tbody td a:hover i.zmdi-edit {
    color: #059669;
}
.table tbody td a:hover i.zmdi-pin {
    color: #1d4ed8;
}
.table tbody td a:hover i.zmdi-save.text-danger {
    color: #b91c1c;
}
.table tbody td a:hover i.zmdi-save.text-success {
    color: #059669;
}

/* ========== BARRE D'ACTIONS (EN TÊTE) ========== */
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

/* ========== STYLES POUR LA CARTE (formulaire) ========== */
.map-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0 12px 0;
}
.map-toolbar button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    font-weight: 600;
    font-size: 0.8rem;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--bleu-nuit-gradient);
    color: white;
    box-shadow: var(--shadow-light);
}
.map-toolbar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}
/* On surcharge pour que le bouton success garde son style vert */
.map-toolbar button.btn-success {
    background: var(--vert-gradient) !important;
}
.map-toolbar button.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
}

#map-container {
    position: relative;
}
#map {
    width: 100%;
    height: 350px;
    border-radius: 16px;
    margin-bottom: 15px;
    z-index: 1;
    background: #e9ecef;
}
.map-overlay-buttons {
    position: absolute;
    bottom: 25px;
    right: 25px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    padding: 8px;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
.map-overlay-buttons .map-btn {
    background: white;
    border: none;
    border-radius: 40px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.2rem;
    color: #0a192f;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.map-overlay-buttons .map-btn:hover {
    transform: scale(1.05);
    background: #f0f0f0;
}
@media (max-width: 768px) {
    .map-overlay-buttons {
        bottom: 15px;
        right: 15px;
        gap: 5px;
    }
    .map-overlay-buttons .map-btn {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}

/* ========== STYLES POUR LA CARTE DANS LE MODAL ========== */
#mapModalMap,
#mapAllModalMap {
    width: 100%;
    height: 400px;
    border-radius: 12px;
    background: #e9ecef;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container {
        padding: 0.5rem 1rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3,
    #bloc_4 {
        padding: 1rem !important;
    }
}

@media (max-width: 768px) {
    .content .container {
        padding: 0.4rem 0.6rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3,
    #bloc_4 {
        padding: 0.8rem !important;
    }
    #liste,
    #add,
    #save,
    #edit_save,
    #annuler,
    #edit_annuler,
    #resetFilters,
    .btn-primary,
    .btn-info,
    .btn-danger,
    .btn-success,
    #print,
    #mapAll {
        padding: 4px 12px !important;
        font-size: 0.7rem;
    }
    .filters-container {
        flex-direction: column;
        gap: 8px;
        padding: 0.6rem 0.8rem;
        margin-bottom: 12px;
    }
    .filter-group {
        width: 100%;
        min-width: 100%;
    }
    .filter-group .form-control {
        height: 34px !important;
    }
    .client-count-badge {
        font-size: 0.65rem;
        padding: 3px 10px;
    }
    .table thead th {
        font-size: 0.72rem;
        padding: 10px 6px !important;
        letter-spacing: 0.05em;
    }
    .table tbody td {
        padding: 8px 10px !important;
        font-size: 0.75rem;
        line-height: 1.3;
    }
    #form_add .col-6,
    #form_edit .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .form-group label {
        font-size: 0.65rem;
    }
    .form-control,
    input.form-control,
    select.form-control,
    textarea.form-control {
        height: 34px !important;
        font-size: 0.75rem;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
    }
    .map-toolbar button {
        padding: 4px 10px;
        font-size: 0.7rem;
    }
    .map-overlay-buttons {
        bottom: 15px;
        right: 15px;
        gap: 5px;
    }
    .map-overlay-buttons .map-btn {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    #mapModalMap,
    #mapAllModalMap {
        height: 280px;
    }
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.3rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3,
    #bloc_4 {
        padding: 0.6rem !important;
    }
    h4 {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    h4 i {
        font-size: 24px !important;
    }
    #liste,
    #add,
    #save,
    #edit_save,
    #annuler,
    #edit_annuler,
    #resetFilters,
    #print,
    #mapAll {
        padding: 3px 8px !important;
        font-size: 0.65rem;
    }
    .table thead th {
        font-size: 0.62rem;
        padding: 8px 4px !important;
    }
    .table tbody td {
        padding: 6px 8px !important;
        font-size: 0.7rem;
        line-height: 1.2;
    }
    .map-overlay-buttons .map-btn {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
    }
    #mapModalMap,
    #mapAllModalMap {
        height: 220px;
    }
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
                                        &nbsp;
                                        <!-- ===== BOUTON CARTE (ROUGE) ===== -->
                                        <a id="mapAll" class="btn-sm" href="#">
                                            <i class="zmdi zmdi-pin"></i> Carte
                                        </a>
                                    <?php } else { ?>
                                        <a id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
                                        <a id="print_r" href="">
                                            <i class="zmdi zmdi-print"></i> Imprimer
                                        </a>
                                        &nbsp;
                                        <a id="mapAll_r" href="">
                                            <i class="zmdi zmdi-pin"></i> Carte
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
    <div style="margin-top: 30px;padding-bottom: 100px;" class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Prospects</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i>
                    Liste
                    <span class="client-count-badge">
                        <i class="zmdi zmdi-view-list"></i> <span id="clientCount">0</span>
                    </span>
                </h4>

                <!-- ========== SECTION FILTRES ========== -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Nom</label>
                        <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-email text-danger"></i> Email</label>
                        <input type="text" id="filterEmail" class="form-control" placeholder="Rechercher par email...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-phone text-danger"></i> Téléphone</label>
                        <input type="text" id="filterPhone" class="form-control" placeholder="Rechercher par téléphone...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-settings text-danger"></i> Type</label>
                        <select id="filterType" class="form-control">
                            <option value="all">Tous les types</option>
                            <option value="0">Privé</option>
                            <option value="1">Entreprise</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-toll text-danger"></i> Activité</label>
                        <select id="filterActivite" class="form-control">
                            <option value="all">Toutes les activités</option>
                            @foreach ($activites as $activite)
                                <option value="{{ $activite->id }}">{{ $activite->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-map text-danger"></i> Adresse</label>
                        <input type="text" id="filterAdresse" class="form-control" placeholder="Rechercher par adresse...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                        <input type="text" id="filterUser" class="form-control" placeholder="Rechercher par utilisateur...">
                    </div>
                    <div class="filter-group">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Type</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Activité</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Adresse</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                        <!-- ===== COLONNE CLIENT AVEC BADGES ===== -->
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($prospects as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="nom-cell" data-nom="{{ $data->name }}">{{ $data->name }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="email-cell" data-email="{{ $data->email }}">{{ $data->email }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="type-cell" data-type="{{ $data->type }}">
                                           @if ($data->type == 0)
                                                Privé
                                            @else
                                                Entreprise
                                            @endif
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="activite-cell" data-activite="{{ $data->activite_id }}">
                                            <?= Activites::where('id', $data->activite_id)->first()["nom"]; ?>
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="adresse-cell" data-adresse="{{ $data->adresse }}">{{ $data->adresse }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ $data->user_id }}">
                                            @if (Auth::user()->id == $data->user_id)
                                                Vous
                                            @else
                                                {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <!-- ===== CELLULE CLIENT AVEC BADGE ===== -->
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell" data-client-id="{{ $data->client_id }}">
                                            @if ($data->client_id == 0)
                                                <span class="badge badge-danger">Non</span>
                                            @else
                                                <span class="badge badge-success">Oui</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                <?php
                                                $edit = 0;
                                                $delete = 0;
                                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                }
                                                ?>
                                            <?php } ?>
                                            <!-- ===== ÉDITION ===== -->
                                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <?php } else { ?>
                                                <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <?php } ?>
                                            <!-- ===== SUPPRESSION ===== -->
                                            <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                            <?php } else { ?>
                                                <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                            <?php } ?>
                                            <!-- ===== CARTE ===== -->
                                            <a id="map_<?= $i ?>" href="#"
                                               data-id="<?= $data->id ?>"
                                               data-lat="<?= $data->latitude ?? '' ?>"
                                               data-lng="<?= $data->longitude ?? '' ?>"
                                               data-nom="<?= htmlspecialchars($data->name) ?>"
                                               data-adresse="<?= htmlspecialchars($data->adresse ?? '') ?>"
                                               data-phone="<?= htmlspecialchars($data->phone ?? '') ?>"
                                               data-email="<?= htmlspecialchars($data->email ?? '') ?>">
                                                <i class="zmdi zmdi-pin"></i>
                                            </a> &nbsp;
                                            <!-- ===== TRANSFORMATION (après la carte) ===== -->
                                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="transform_<?= $i ?>" href="#"
                                                   data-id="<?= $data->id ?>"
                                                   data-nom="<?= htmlspecialchars($data->name) ?>"
                                                   data-client-id="<?= $data->client_id ?>">
                                                    <i class="zmdi zmdi-save <?= $data->client_id == 0 ? 'text-danger' : 'text-success' ?>"></i>
                                                </a> &nbsp;
                                            <?php } else { ?>
                                                <a id="transform_r<?= $i ?>" href="#">
                                                    <i class="zmdi zmdi-save text-muted"></i>
                                                </a> &nbsp;
                                            <?php } ?>
                                            <script>
                                                $("#edit_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $.get("{{ url('/refresh_editprospectsuivi') }}", {
                                                        prospect_id: <?= $data->id ?>,
                                                    }, function(refresh_editutilisateur) {
                                                        $("#bloc_1").hide();
                                                        $("#bloc_2").hide();
                                                        $("#bloc_3").show();
                                                        $("#bloc_3").html(refresh_editutilisateur);
                                                    });
                                                });
                                                $("#edit_r<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#btn_refus").trigger("click");
                                                });
                                                $("#delete_r<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#btn_refus").trigger("click");
                                                });
                                                $("#delete_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element").html("<?= $data->name ?>");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_sup").trigger("click");
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bloc_2" style="margin-top: 12px;display: none;margin-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts-add text-info"></i> Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom <span class="text-danger">*</span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mr ILUNGA KASONGO Heritier, Kamoa etc...)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i> E-mail </span></label>
                                <input type="text" id="email" name="email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Email (Ex : mgm@gmail.com)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i> Telephone </span></label>
                                <input type="text" id="phone" name="phone" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Telephone (Ex : +243974743675)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Type de client </span></label>
                                <select id="type_client" name="type_client" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option selected class="form-control" value="0">Privé</option>
                                    <option class="form-control" value="1">Entreprise</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Paiement </span></label>
                                <input type="text" id="paiement" name="paiement" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="input-mask form-control" data-mask="00000000000000000000000000000000000000" placeholder="Paiement">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Devise </span></label>
                                <select id="devise" name="devise" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="0">USD</option>
                                    <option class="form-control" value="1">CDF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Activité </span></label>
                                <select id="activite_id" name="activite_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Activité</option>
                                    @foreach ($activites as $data)
                                        <?php if($data->id == 1){  ?>
                                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                        <?php } else{ ?>
                                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                        class="zmdi zmdi-map"></i> Adresse </span></label>
                                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                    class="form-control" placeholder="Adresse" name="adresse" id="adresse" cols="2" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Description </span></label>
                                <input type="text" id="description" name="description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : VIDAGE POUBELLE)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Modele de facture </span></label>
                                <select id="facture" name="facture" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="0">Africtech</option>
                                    <option class="form-control" value="1">Fqsmm</option>
                                    <option class="form-control" value="2">Beforward</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ===== CHAMPS : LATITUDE / LONGITUDE ===== -->
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Latitude </span></label>
                                <input type="text" id="latitude" name="latitude" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Latitude (Ex : -4.4419)" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Longitude </span></label>
                                <input type="text" id="longitude" name="longitude" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Longitude (Ex : 15.2663)" value="">
                            </div>
                        </div>
                    </div>

                    <!-- ===== BARRE D'OUTILS : POSITION ACTUELLE + CHERCHER PAR ADRESSE ===== -->
                    <div class="map-toolbar">
                        <button type="button" id="btnCurrentLocation">
                            <i class="zmdi zmdi-my-location"></i> Position actuelle
                        </button>
                        <!-- BOUTON CHERCHER PAR ADRESSE – STYLE SUCCESS -->
                        <button type="button" id="btnSearchAddress" class="btn btn-success btn-sm">
                            <i class="zmdi zmdi-search"></i> Chercher par adresse
                        </button>
                    </div>

                    <!-- ===== CARTE INTERACTIVE AVEC BOUTONS OVERLAY ===== -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 0;">
                                    <i class="zmdi zmdi-pin-drop"></i> Cliquez sur la carte pour choisir une position
                                </label>
                                <div id="map-container" style="position: relative;">
                                    <div id="map"></div>
                                    <div class="map-overlay-buttons">
                                        <button type="button" id="btnClassic" class="map-btn" title="Classique">
                                            <i class="zmdi zmdi-map"></i>
                                        </button>
                                        <button type="button" id="btnSatellite" class="map-btn" title="Satellite">
                                            <i class="zmdi zmdi-satellite"></i>
                                        </button>
                                        <button type="button" id="btnResetView" class="map-btn" title="Réinitialiser">
                                            <i class="zmdi zmdi-undo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                <button id="save" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</section>

<!-- ===== MODAL DE CONFIRMATION SUPPRESSION ===== -->
<span id="data_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;"></p>
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

<!-- ===== MODAL DE CONFIRMATION TRANSFORMATION PROSPECT -> CLIENT ===== -->
<button style="display: none;" data-toggle="modal" data-target="#transformModal" id="btn_transform">Transform</button>
<div class="modal fade" id="transformModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold;font-size: 16px;">
                    <i class="zmdi zmdi-check-circle text-success"></i> Transformer en client ?
                </h5>
            </div>
            <div class="modal-body">
                <p id="transformElement" style="text-align: center; font-weight: 500;"></p>
                <p class="text-muted text-center" style="font-size: 0.9rem;">Cette action est irréversible.</p>
            </div>
            <div style="font-weight: bold;text-align: center; padding-bottom: 15px;">
                <a id="transformOui" href="#" class="btn btn-success btn-sm">
                    <i class="zmdi zmdi-check"></i> Oui, transformer
                </a>
                <button id="transformNon" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i> Non
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL POUR LA CARTE D'UN CLIENT (individuel) AVEC BOUTON PARTAGER ===== -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 28px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <!-- ========== EN-TÊTE STYLE PREMIUM ========== -->
            <div class="modal-header" style="flex-shrink: 0; background: linear-gradient(135deg, #1e3a5f, #0a192f); padding: 20px 25px; color: white !important; border-radius: 28px 28px 0 0; border-bottom: none; position: relative;">
                <h3 style="margin:0; font-weight:700; font-size:1.4rem; color:white !important; display:flex; align-items:center; gap:12px; width:100%;">
                    <i class="zmdi zmdi-pin" style="font-size:1.8rem; color: #e31b23;"></i>
                    LOCALISATION DU PROSPECT
                    <span style="font-size:0.75rem; background:rgba(255,255,255,0.2); padding:4px 12px; border-radius:50px; margin-left:auto;">
                        <i class="zmdi zmdi-pin"></i> Position
                    </span>
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8; position: absolute; right: 20px; top: 20px; font-size: 1.8rem; line-height: 1; background: none; border: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 20px 25px;">
                <div id="clientInfos" style="margin-bottom: 15px; font-weight: 500; background: #f8fafc; padding: 15px; border-radius: 16px; border: 1px solid #eef2f6;">
                    <p style="margin: 5px 0;"><strong>Nom :</strong> <span id="modalClientNom"></span></p>
                    <p style="margin: 5px 0;"><strong>Adresse :</strong> <span id="modalClientAdresse"></span></p>
                    <p style="margin: 5px 0;"><strong>Téléphone :</strong> <span id="modalClientPhone"></span></p>
                    <p style="margin: 5px 0;"><strong>Email :</strong> <span id="modalClientEmail"></span></p>
                    <p style="margin: 5px 0;"><strong>Coordonnées :</strong> <span id="modalClientCoords"></span></p>
                </div>
                <div id="mapModalMap" style="width: 100%; height: 400px; border-radius: 16px; border: 1px solid #eef2f6; background: #eef2f6;"></div>
            </div>

            <div class="modal-footer" style="padding: 15px 25px; border-top: 1px solid #eef2f6; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" id="btnShareLocation" class="btn btn-success" style="border-radius: 40px; padding: 8px 24px; font-weight: 600;">
                    <i class="zmdi zmdi-share"></i> Partager
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 40px; padding: 8px 24px; font-weight: 600;">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL POUR LA CARTE DE TOUS LES CLIENTS (AVEC CLUSTERING) ===== -->
<div class="modal fade" id="mapAllModal" tabindex="-1" role="dialog" aria-labelledby="mapAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapAllModalLabel">
                    <i class="zmdi zmdi-pin text-danger"></i> Tous les prospects sur la carte
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 10px; font-weight: 500;">
                    <i class="zmdi zmdi-info text-info"></i> Cliquez sur un marqueur pour voir les détails du client.
                    Les groupes de marqueurs sont automatiquement regroupés.
                </p>
                <div id="mapAllModalMap"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL D'INFORMATION ===== -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                <h5 class="modal-title" id="infoModalLabel" style="font-weight: 700; color: #0a192f;">
                    <i class="zmdi zmdi-info text-info" style="font-size: 24px; margin-right: 8px;"></i>
                    Information
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px 24px; font-size: 1rem; font-weight: 500; color: #1e2a3e;"></div>
            <div class="modal-footer" style="border-top: none; padding-top: 0;">
                <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 40px; padding: 8px 28px; font-weight: 600;">
                    <i class="zmdi zmdi-check"></i> OK
                </button>
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

<!-- ===== LEAFLET JS ===== -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- ===== LEAFLET.MARKERCLUSTER JS ===== -->
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Fonction utilitaire pour afficher un message avec la bonne classe
    function showMsg(type, html, duration) {
        var $msg = $('#msg');
        $msg.removeClass('msg-success msg-error msg-info');
        $msg.html(html);
        if (type === 'success') $msg.addClass('msg-success');
        else if (type === 'error') $msg.addClass('msg-error');
        else if (type === 'info') $msg.addClass('msg-info');
        if (duration) {
            setTimeout(function() {
                $msg.html('').removeClass('msg-success msg-error msg-info');
            }, duration);
        }
    }

    function showEditMsg(type, html, duration) {
        var $msg = $('#edit_msg');
        $msg.removeClass('msg-success msg-error msg-info');
        $msg.html(html);
        if (type === 'success') $msg.addClass('msg-success');
        else if (type === 'error') $msg.addClass('msg-error');
        else if (type === 'info') $msg.addClass('msg-info');
        if (duration) {
            setTimeout(function() {
                $msg.html('').removeClass('msg-success msg-error msg-info');
            }, duration);
        }
    }

    $("#link_50").addClass("active");

    // ========== FILTRES AVEC PERSISTANCE ==========
    let clientFilterTimeout;

    function saveClientFiltersToStorage() {
        const filters = {
            nom: $('#filterNom').val(),
            email: $('#filterEmail').val(),
            phone: $('#filterPhone').val(),
            type: $('#filterType').val(),
            activite: $('#filterActivite').val(),
            adresse: $('#filterAdresse').val(),
            user: $('#filterUser').val()
        };
        localStorage.setItem('clientFilters', JSON.stringify(filters));
    }

    function loadClientFiltersFromStorage() {
        const savedFilters = localStorage.getItem('clientFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#filterNom').val(filters.nom || '');
            $('#filterEmail').val(filters.email || '');
            $('#filterPhone').val(filters.phone || '');
            $('#filterType').val(filters.type || 'all');
            $('#filterActivite').val(filters.activite || 'all');
            $('#filterAdresse').val(filters.adresse || '');
            $('#filterUser').val(filters.user || '');
            return true;
        }
        return false;
    }

    function filterClients() {
        const filterNom = $('#filterNom').val().toLowerCase().trim();
        const filterEmail = $('#filterEmail').val().toLowerCase().trim();
        const filterPhone = $('#filterPhone').val().toLowerCase().trim();
        const filterType = $('#filterType').val();
        const filterActivite = $('#filterActivite').val();
        const filterAdresse = $('#filterAdresse').val().toLowerCase().trim();
        const filterUser = $('#filterUser').val().toLowerCase().trim();

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
            const emailValue = ($row.find('.email-cell').data('email') || '').toLowerCase();
            const phoneValue = ($row.find('.phone-cell').data('phone') || '').toLowerCase();
            const typeValue = $row.find('.type-cell').data('type') + '';
            const activiteValue = $row.find('.activite-cell').data('activite') + '';
            const adresseValue = ($row.find('.adresse-cell').data('adresse') || '').toLowerCase();
            const userText = ($row.find('.user-cell').text() || '').toLowerCase();

            if (filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterEmail && !emailValue.includes(filterEmail)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;
            if (showRow && filterType !== 'all' && typeValue !== filterType) showRow = false;
            if (showRow && filterActivite !== 'all' && activiteValue !== filterActivite) showRow = false;
            if (showRow && filterAdresse && !adresseValue.includes(filterAdresse)) showRow = false;
            if (showRow && filterUser && !userText.includes(filterUser)) showRow = false;

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(newIndex);
                newIndex++;
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#clientCount').text(visibleCount);

        if (visibleCount === 0 && (filterNom || filterEmail || filterPhone || filterType !== 'all' || filterActivite !== 'all' || filterAdresse || filterUser)) {
            $('#infoModal .modal-body').html(
                '<i class="zmdi zmdi-search text-warning" style="font-size: 20px; margin-right: 10px;"></i> ' +
                'Aucun client ne correspond aux critères de recherche.'
            );
            $('#infoModal').modal('show');
        }
    }

    function resetClientFilters() {
        $('#filterNom').val('');
        $('#filterEmail').val('');
        $('#filterPhone').val('');
        $('#filterType').val('all');
        $('#filterActivite').val('all');
        $('#filterAdresse').val('');
        $('#filterUser').val('');

        saveClientFiltersToStorage();

        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex);
            newIndex++;
        });
        const totalCount = $('#content_utilisateur tbody tr').length;
        $('#clientCount').text(totalCount);

        showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés', 3000);
    }

    function debouncedClientFilter() {
        clearTimeout(clientFilterTimeout);
        clientFilterTimeout = setTimeout(() => {
            filterClients();
            saveClientFiltersToStorage();
        }, 300);
    }

    $(document).ready(function() {
        const totalClients = $('#content_utilisateur tbody tr').length;
        $('#clientCount').text(totalClients);

        const hasSavedFilters = loadClientFiltersFromStorage();

        $('#filterNom, #filterEmail, #filterPhone, #filterType, #filterActivite, #filterAdresse, #filterUser').on('input change', function() {
            debouncedClientFilter();
        });

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetClientFilters();
        });

        if (hasSavedFilters) {
            setTimeout(function() {
                filterClients();
            }, 100);
        }
    });

    // ========== NAVIGATION ==========
    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            filterClients();
        }, 100);
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            if (typeof map !== 'undefined' && map) {
                map.invalidateSize();
            }
        }, 200);
    });

    $("#print").click(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_liste_client') }}", {}, function(response) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("")  }}' + response);
        });
    });

    $("#annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            filterClients();
        }, 100);
    });

    // ===== BOUTON "CARTE" (tous les clients) AVEC CLUSTERING =====
    $("#mapAll").click(function(e) {
        e.preventDefault();
        var clientsData = [];
        $('#content_utilisateur tbody tr').each(function() {
            var $row = $(this);
            var $mapLink = $row.find('a[id^="map_"]');
            if ($mapLink.length) {
                var lat = $mapLink.data('lat');
                var lng = $mapLink.data('lng');
                var nom = $mapLink.data('nom') || 'N/A';
                var adresse = $mapLink.data('adresse') || 'Non renseignée';
                if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                    clientsData.push({
                        lat: parseFloat(lat),
                        lng: parseFloat(lng),
                        nom: nom,
                        adresse: adresse
                    });
                }
            }
        });

        if (clientsData.length === 0) {
            $('#infoModal .modal-body').html(
                '<i class="zmdi zmdi-alert-triangle text-danger" style="font-size: 20px; margin-right: 10px;"></i> ' +
                'Aucun client avec des coordonnées géographiques n\'a été trouvé.'
            );
            $('#infoModal').modal('show');
            return;
        }

        $('#mapAllModal').modal('show');
        setTimeout(function() {
            initMapAll(clientsData);
        }, 300);
    });

    var mapAll = null;
    var markerCluster = null;

    function initMapAll(clients) {
        var container = document.getElementById('mapAllModalMap');
        if (!container) return;

        if (!mapAll) {
            mapAll = L.map('mapAllModalMap').setView([clients[0].lat, clients[0].lng], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(mapAll);
        }

        if (markerCluster) {
            mapAll.removeLayer(markerCluster);
            markerCluster = null;
        }

        markerCluster = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        clients.forEach(function(client) {
            var popupContent = '<strong>' + client.nom + '</strong><br>' +
                               'Adresse: ' + client.adresse + '<br>' +
                               'Coordonnées: ' + client.lat + ', ' + client.lng;
            var marker = L.marker([client.lat, client.lng])
                .bindPopup(popupContent);
            markerCluster.addLayer(marker);
        });

        mapAll.addLayer(markerCluster);

        if (clients.length > 1) {
            var group = new L.featureGroup(markerCluster.getLayers());
            mapAll.fitBounds(group.getBounds().pad(0.1));
        }

        setTimeout(function() {
            if (mapAll) mapAll.invalidateSize();
        }, 200);
    }

    // ===== AJOUT CLIENT =====
    $("#save").click(function(e) {
        e.preventDefault();
        var nom = $("#nom").val();
        if (nom.trim().length == 0) {
            showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Veuillez compléter le nom du prospect', 9000);
        } else {
            // === CONCATÉNATION DU PARAMÈTRE "page=25" ===
            var serialized = $('#form_add').serialize();
            var dataWithExtra = serialized + '&page=29';

            $("#save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/add_prospect",
                data: dataWithExtra, // on envoie la nouvelle chaîne
                success: function(response) {
                    $("#save").attr("disabled", false);
                    // ... votre traitement (vidage des champs, mise à jour du tableau, etc.)
                    $("#nom").val("");
                    $("#email").val("");
                    $("#phone").val("");
                    $("#adresse").val("");
                    $("#description").val("");
                    showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Prospect ajouté avec succès', 9000);
                    $("#content_utilisateur").html(response);
                    saveClientFiltersToStorage();
                    setTimeout(function() {
                        loadClientFiltersFromStorage();
                        filterClients();
                    }, 100);
                },
                error: function() {
                    $("#save").attr("disabled", false);
                    showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Une erreur est survenue', 5000);
                }
            });
        }
    });

    // ===== SUPPRESSION =====
    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_deleteprospect') }}", {
            id: id,
            page: 29,
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
            saveClientFiltersToStorage();
            setTimeout(function() {
                loadClientFiltersFromStorage();
                filterClients();
            }, 100);
        });
    });

    // ===== TRANSFORMATION PROSPECT -> CLIENT (avec gestion client_id) =====
    $(document).on('click', 'a[id^="transform_"]', function(e) {
        e.preventDefault();
        // Si le lien est un "transform_r" (pas de droits)
        if ($(this).attr('id').startsWith('transform_r')) {
            $('#infoModal .modal-body').html(
                '<i class="zmdi zmdi-alert-triangle text-danger"></i> Vous n\'avez pas les droits pour effectuer cette action.'
            );
            $('#infoModal').modal('show');
            return;
        }

        // Récupération des données
        var clientId = $(this).data('client-id');
        var id = $(this).data('id');
        var nom = $(this).data('nom');

        // Si déjà transformé (client_id > 0)
        if (clientId > 0) {
            $('#infoModal .modal-body').html(
                '<i class="zmdi zmdi-check-circle text-success" style="font-size: 20px; margin-right: 10px;"></i> ' +
                'Ce prospect a déjà été transformé en client.'
            );
            $('#infoModal').modal('show');
            return;
        }

        // Sinon, on affiche la confirmation de transformation
        $('#transformElement').html('Voulez-vous transformer <strong>' + nom + '</strong> en client ?');
        $('#transformModal').data('id', id);
        $('#btn_transform').click();
    });

    // Action "Oui" dans le modal de transformation
    $('#transformOui').click(function(e) {
        e.preventDefault();
        var id = $('#transformModal').data('id');
        if (!id) {
            $('#infoModal .modal-body').html('Erreur : aucun identifiant fourni.');
            $('#infoModal').modal('show');
            return;
        }
        $(this).prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Transformation...');
        $.ajax({
            type: "POST",
            url: "{{ url('/transform_prospect') }}",
            data: { id: id, _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#transformNon').click(); // ferme le modal
                // Mettre à jour la liste
                $('#content_utilisateur').html(response);
                saveClientFiltersToStorage();
                setTimeout(function() {
                    loadClientFiltersFromStorage();
                    filterClients();
                }, 100);
                showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Prospect transformé en client avec succès.', 4000);
            },
            error: function(xhr) {
                $('#transformNon').click();
                var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur lors de la transformation.';
                showMsg('error', '<i class="zmdi zmdi-close-circle"></i> ' + errorMsg, 5000);
            },
            complete: function() {
                $('#transformOui').prop('disabled', false).html('<i class="zmdi zmdi-check"></i> Oui, transformer');
            }
        });
    });

    $('#transformModal').on('hidden.bs.modal', function() {
        $('#transformModal').data('id', null);
        $('#transformOui').prop('disabled', false).html('<i class="zmdi zmdi-check"></i> Oui, transformer');
    });

    window.addEventListener('beforeunload', function() {
        saveClientFiltersToStorage();
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_client') || settings.url.includes('deleteclient') || settings.url.includes('transform_prospect'))) {
            setTimeout(() => {
                const totalClients = $('#content_utilisateur tbody tr').length;
                $('#clientCount').text(totalClients);
                loadClientFiltersFromStorage();
                filterClients();
            }, 200);
        }
    });

    // ========== CARTE AJOUT (avec bouton chercher par adresse en SUCCESS) ==========
    (function() {
        var defaultLat = -4.4419;
        var defaultLng = 15.2663;
        var defaultZoom = 13;

        var map = null;
        var currentTileLayer = null;
        var marker = null;

        function initMap() {
            if (map) {
                map.invalidateSize();
                return;
            }

            map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

            var tileLayerClassic = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            });
            var tileLayerSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri'
            });

            currentTileLayer = tileLayerClassic.addTo(map);

            function setTileLayer(layer) {
                if (currentTileLayer) map.removeLayer(currentTileLayer);
                currentTileLayer = layer.addTo(map);
            }

            function updateLocation(lat, lng) {
                $('#latitude').val(lat.toFixed(6));
                $('#longitude').val(lng.toFixed(6));
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                map.setView([lat, lng], 15);
            }

            map.on('click', function(e) {
                updateLocation(e.latlng.lat, e.latlng.lng);
                showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Position choisie sur la carte', 3000);
            });

            $('#latitude, #longitude').on('input', function() {
                var lat = parseFloat($('#latitude').val());
                var lng = parseFloat($('#longitude').val());
                if (!isNaN(lat) && !isNaN(lng)) {
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                    map.setView([lat, lng], 15);
                }
            });

            $("#btnCurrentLocation").off('click').on('click', function(e) {
                e.preventDefault();
                if (!navigator.geolocation) {
                    showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Géolocalisation non supportée', 5000);
                    return;
                }
                showMsg('info', '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Récupération...', 10000);
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        updateLocation(lat, lng);
                        showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Position actuelle enregistrée', 4000);
                    },
                    function(error) {
                        var errMsg = "";
                        switch(error.code) {
                            case error.PERMISSION_DENIED: errMsg = "Permission refusée."; break;
                            case error.POSITION_UNAVAILABLE: errMsg = "Position indisponible."; break;
                            case error.TIMEOUT: errMsg = "Délai dépassé."; break;
                            default: errMsg = "Erreur inconnue.";
                        }
                        showMsg('error', '<i class="zmdi zmdi-close-circle"></i> ' + errMsg, 5000);
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            });

            // ===== BOUTON CHERCHER PAR ADRESSE (AJOUT) – STYLE SUCCESS =====
            $("#btnSearchAddress").off('click').on('click', function(e) {
                e.preventDefault();
                var adresse = $("#adresse").val().trim();
                if (adresse === '') {
                    showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Veuillez saisir une adresse dans le champ adresse', 5000);
                    return;
                }
                showMsg('info', '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Recherche de l\'adresse...', 10000);
                var url = 'https://nominatim.openstreetmap.org/search?q=' + encodeURIComponent(adresse) + '&format=json&limit=1';
                fetch(url, {
                    headers: {
                        'User-Agent': 'ControlApp/1.0 (votre-email@domaine.com)'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        var lat = parseFloat(data[0].lat);
                        var lng = parseFloat(data[0].lon);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            updateLocation(lat, lng);
                            showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Adresse trouvée : ' + data[0].display_name, 5000);
                        } else {
                            showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Coordonnées invalides', 5000);
                        }
                    } else {
                        showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Aucune adresse trouvée', 5000);
                    }
                })
                .catch(error => {
                    showMsg('error', '<i class="zmdi zmdi-close-circle"></i> Erreur de recherche : ' + error.message, 5000);
                });
            });

            $("#btnClassic").off('click').on('click', function() {
                setTileLayer(tileLayerClassic);
                showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Mode classique activé', 2000);
            });
            $("#btnSatellite").off('click').on('click', function() {
                setTileLayer(tileLayerSatellite);
                showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Mode satellite activé', 2000);
            });
            $("#btnResetView").off('click').on('click', function() {
                map.setView([defaultLat, defaultLng], defaultZoom);
                showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Vue réinitialisée', 2000);
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        map.setView([lat, lng], 15);
                        showMsg('success', '<i class="zmdi zmdi-check-circle"></i> Carte centrée sur votre position', 4000);
                    },
                    function(error) {
                        map.setView([defaultLat, defaultLng], defaultZoom);
                        showMsg('info', '<i class="zmdi zmdi-info"></i> Position par défaut (Kinshasa)', 4000);
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            } else {
                map.setView([defaultLat, defaultLng], defaultZoom);
                showMsg('info', '<i class="zmdi zmdi-info"></i> Position par défaut (Kinshasa)', 4000);
            }

            setTimeout(function() { if (map) map.invalidateSize(); }, 300);
        }

        var checkInterval = setInterval(function() {
            var container = document.getElementById('map');
            if (container && container.getBoundingClientRect().height > 0) {
                initMap();
                clearInterval(checkInterval);
            }
        }, 200);

        $(document).ready(function() {
            var container = document.getElementById('map');
            if (container && container.getBoundingClientRect().height > 0) {
                clearInterval(checkInterval);
                initMap();
            }
        });

        window.invalidateMap = function() {
            if (map) map.invalidateSize();
        };
    })();

    // ========== CARTE MODAL INDIVIDUEL ==========
    (function() {
        var mapModal = null;
        var markerModal = null;
        var currentClientData = null;

        function initModalMap(lat, lng) {
            var container = document.getElementById('mapModalMap');
            if (!container) return;

            if (!mapModal) {
                mapModal = L.map('mapModalMap').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(mapModal);
            } else {
                mapModal.setView([lat, lng], 15);
            }

            if (markerModal) {
                markerModal.setLatLng([lat, lng]);
            } else {
                markerModal = L.marker([lat, lng]).addTo(mapModal);
            }

            setTimeout(function() {
                if (mapModal) mapModal.invalidateSize();
            }, 300);
        }

        $('#content_utilisateur').on('click', 'a[id^="map_"]', function(e) {
            e.preventDefault();

            var lat = $(this).data('lat');
            var lng = $(this).data('lng');
            var nom = $(this).data('nom') || 'Non renseigné';
            var adresse = $(this).data('adresse') || 'Non renseignée';
            var phone = $(this).data('phone') || 'Non renseigné';
            var email = $(this).data('email') || 'Non renseigné';

            if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
                $('#infoModal .modal-body').html(
                    '<i class="zmdi zmdi-alert-triangle text-danger" style="font-size: 20px; margin-right: 10px;"></i> ' +
                    'Ce prospect n\'a pas de coordonnées géographiques enregistrées.'
                );
                $('#infoModal').modal('show');
                return;
            }

            currentClientData = {
                lat: lat,
                lng: lng,
                nom: nom,
                adresse: adresse,
                phone: phone,
                email: email
            };

            $('#modalClientNom').text(nom);
            $('#modalClientAdresse').text(adresse);
            $('#modalClientPhone').text(phone);
            $('#modalClientEmail').text(email);
            $('#modalClientCoords').text(lat + ', ' + lng);

            $('#mapModal').modal('show');
            initModalMap(parseFloat(lat), parseFloat(lng));
        });

        // ===== BOUTON PARTAGER =====
        $('#btnShareLocation').on('click', function() {
            if (!currentClientData) {
                alert('Aucune donnée client à partager.');
                return;
            }

            var data = currentClientData;

            function utf8ToBase64(str) {
                const encoder = new TextEncoder();
                const uint8Array = encoder.encode(str);
                let binaryString = '';
                for (let i = 0; i < uint8Array.length; i++) {
                    binaryString += String.fromCharCode(uint8Array[i]);
                }
                return btoa(binaryString);
            }

            var jsonString = JSON.stringify({
                nom: data.nom,
                adresse: data.adresse,
                phone: data.phone,
                email: data.email,
                lat: data.lat,
                lng: data.lng
            });

            var encodedData = utf8ToBase64(jsonString);
            var shareUrl = "{{ route('client_partager') }}?data=" + encodeURIComponent(encodedData);

            var message = '*Position du prospect*\n\n' +
                        'Nom: ' + data.nom + '\n' +
                        'Adresse: ' + data.adresse + '\n' +
                        'Telephone: ' + data.phone + '\n' +
                        'Email: ' + data.email + '\n' +
                        'Coordonnées: ' + data.lat + ', ' + data.lng + '\n\n' +
                        'Voir sur la carte: ' + shareUrl;

            var whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(message);
            window.open(whatsappUrl, '_blank');
        });

        $('#mapModal').on('hidden.bs.modal', function() {
            currentClientData = null;
        });

    })();
</script>
@endsection
@endsection
