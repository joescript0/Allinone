@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Mois;
use App\Models\Annees;
use App\Models\Articles;
use App\Models\Postes;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Utilisateurs;
use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Paiesfactures;
use App\Models\Paiementsfactures;
use App\Models\Clients;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'RAPPORT POINTAGE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (RAPPORT POINTAGE)
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
#bloc_1, #bloc_2, #bloc_3, #bloc_4 {
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
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* ========== TABLEAU ========== */
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

/* ========== LIENS D'ACTION DANS LE TABLEAU ========== */
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

.table tbody td a i.zmdi-eye {
    color: #3b82f6;
}
.table tbody td a i.zmdi-eye.text-success {
    color: #10b981 !important;
}
.table tbody td a i.zmdi-eye.text-dark {
    color: #475569 !important;
}

.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a:hover i.zmdi-eye.text-success {
    color: #059669 !important;
}
.table tbody td a:hover i.zmdi-eye.text-dark {
    color: #1e293b !important;
}

/* ========== BOUTONS PRINCIPAUX (UNIFIÉS) ========== */
#liste, #add, #print, #add_r, #print_r,
#save, #annuler, #edit_save, #edit_annuler,
.btn-primary, .btn-info, .btn-danger,
#save_t, #resetFilters {
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

#liste, .btn-primary {
    background: #3B82F6 !important;
    color: white !important;
}
#liste:hover, .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    background: #2563eb !important;
}

#add, .btn-info {
    background: var(--bleu-nuit-gradient) !important;
    color: white !important;
}
#add:hover, .btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#save, #edit_save, #save_t {
    background: var(--bleu-secondaire-gradient) !important;
    color: white;
}
#save:hover, #edit_save:hover, #save_t:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
}

#annuler, #edit_annuler, .btn-danger {
    background: var(--rouge-gradient) !important;
    color: white;
}
#annuler:hover, #edit_annuler:hover, .btn-danger:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
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

#resetFilters {
    background: #64748b !important;
    color: white !important;
}
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
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

/* ========== BADGE COMPTEUR ========== */
.student-count-badge {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    color: white;
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

/* ========== FORMULAIRES ========== */
#form_add .row, #form_edit .row {
    display: flex;
    flex-wrap: wrap;
}

#form_add .col-6, #form_edit .col-6 {
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

/* ========== MESSAGES STYLISÉS ========== */
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
    border-left: 4px solid #10b981;
}

#msg:not(:empty):has(i.zmdi-close-circle),
#edit_msg:not(:empty):has(i.zmdi-close-circle) {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
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

/* ========== MODAL DÉTAIL ========== */
#modalDetailEcole .modal-content {
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.3);
}
#modalDetailEcole .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    padding: 1.2rem;
    border-bottom: none;
}
#modalDetailEcole .modal-header h5 {
    font-weight: 700;
    font-size: 1.3rem;
    letter-spacing: -0.3px;
    color: white;
}
#modalDetailEcole .modal-body {
    padding: 1.5rem 1.2rem;
    background: #f9fafc;
}
#modalDetailEcole .detail-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
#modalDetailEcole .detail-item {
    background: white;
    border-radius: 20px;
    padding: 0.9rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
    border: 1px solid #eef2f6;
}
#modalDetailEcole .detail-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.05);
}
#modalDetailEcole .detail-icon {
    width: 44px;
    height: 44px;
    background: #f0f4fe;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #0a192f;
    flex-shrink: 0;
}
#modalDetailEcole .detail-content {
    flex: 1;
}
#modalDetailEcole .detail-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #5b6e8c;
    margin-bottom: 4px;
}
#modalDetailEcole .detail-value {
    font-size: 1rem;
    font-weight: 700;
    color: #0a192f;
    word-break: break-word;
}
#modalDetailEcole .modal-footer {
    border-top: none;
    justify-content: center;
    padding: 1rem 1.2rem 1.5rem;
    background: #f9fafc;
}
#modalDetailEcole .btn-fermer {
    background: var(--rouge-gradient) !important;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.85rem;
    color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
}
#modalDetailEcole .btn-fermer:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
}
@media (max-width: 480px) {
    #modalDetailEcole .detail-item {
        padding: 0.7rem 0.8rem;
        gap: 0.8rem;
    }
    #modalDetailEcole .detail-icon {
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
    }
    #modalDetailEcole .detail-value {
        font-size: 0.9rem;
    }
    #modalDetailEcole .detail-label {
        font-size: 0.65rem;
    }
}

/* ========== MODALES GÉNÉRALES ========== */
.modal.fade .modal-content {
    border-radius: var(--border-radius-lg);
    border: none;
    box-shadow: var(--shadow-premium);
    overflow: hidden;
}

.modal.fade .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    border-bottom: none;
    padding: 1rem 1.5rem;
}

.modal.fade .modal-header .modal-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: white;
}

.modal.fade .modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
}

.modal.fade .modal-header .close:hover {
    opacity: 1;
}

.modal.fade .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
    padding: 1rem 1.5rem;
}

.modal.fade .modal-footer .btn {
    border-radius: 40px !important;
    padding: 6px 18px !important;
    font-weight: 600;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container {
        padding: 0.5rem 1rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
        padding: 1rem !important;
    }
}

@media (max-width: 768px) {
    .content .container {
        padding: 0.4rem 0.6rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
        padding: 0.8rem !important;
    }
    #liste, #add, #print, #add_r, #print_r,
    #save, #annuler, #edit_save, #edit_annuler,
    #resetFilters, .btn-primary, .btn-info, .btn-danger,
    #save_t {
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
    .student-count-badge {
        font-size: 0.65rem;
        padding: 4px 12px;
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
    #form_add .col-6, #form_edit .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .form-group label {
        font-size: 0.65rem;
    }
    .form-control, input.form-control, select.form-control, textarea.form-control {
        height: 34px !important;
        font-size: 0.75rem;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.3rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
        padding: 0.6rem !important;
    }
    h4 {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    h4 i {
        font-size: 24px !important;
    }
    #liste, #add, #print, #add_r, #print_r,
    #save, #annuler, #edit_save, #edit_annuler,
    #resetFilters, #save_t {
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Rapport de pointage</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Liste</h4>

                <!-- SECTION FILTRES ADAPTÉS AUX COLONNES RÉELLES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Date</label>
                        <input type="text" id="filterDate" class="form-control" placeholder="jj/mm/aaaa ...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-box text-danger"></i> Nom</label>
                        <input type="text" id="filterEleve" class="form-control" placeholder="Nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-briefcase text-danger"></i> Poste</label>
                        <input type="text" id="filterPoste" class="form-control" placeholder="Poste...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-info text-danger"></i> Préstation</label>
                        <select id="filterPrestation" class="form-control">
                            <option value="">Toutes</option>
                            <option value="journée">Journée</option>
                            <option value="nuit">Nuit</option>
                            <option value="repos">Repos</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-time text-danger"></i> Horaire</label>
                        <select id="filterHoraire" class="form-control">
                            <option value="">Tous</option>
                            <option value="06h00 - 18h00">06h00 - 18h00</option>
                            <option value="18h00 - 06h00">18h00 - 06h00</option>
                            <option value="repos">Repos</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Badge compteur prestations -->
                <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                    <span class="student-count-badge">
                        <i class="zmdi zmdi-view-calendar"></i> Prestations trouvées : <span id="studentCount">0</span>
                    </span>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Matricule</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Fonction / Rôle</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Poste</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Préstation</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Horaire</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Pointage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($data_prestations as $data_p)
                                        <?php  $prestations = json_decode($data_p->details, true); ?>
                                        @foreach ($prestations as $data)
                                            <?php $users = User::where(['id' => $data['user_id']])->first(); ?>
                                            <tr>
                                                @if ($data['date'] == date("Y-m-d"))
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-success">{{ $users->matricule ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-success">{{ $users->name ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-success">{{ Groupes::where(["id" => $users->role])->first()["nom"] ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-success">
                                                        {{ Postes::where(["id" => $data_p->poste_id])->first()["nom"] ?? '' }}
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data['service'] == "journée")
                                                            <i class="zmdi zmdi-info text-success"></i> <span class="text-success">{{ $data['service'] }} </span>
                                                        @endif
                                                        @if ($data['service'] == "nuit")
                                                            <i class="zmdi zmdi-info text-info"></i> <span class="text-info">{{ $data['service'] }} </span>
                                                        @endif
                                                        @if ($data['service'] == "repos")
                                                            <i class="zmdi zmdi-info text-danger"></i> <span class="text-danger">{{ $data['service'] }} </span>
                                                        @endif
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-success">
                                                        @if ($data['horaire'] == "06h00 - 18h00")
                                                            <i class="zmdi zmdi-time text-success"></i> <span class="text-success">{{  ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "18h00 - 06h00")
                                                            <i class="zmdi zmdi-time text-info"></i> <span class="text-info">{{ ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "repos")
                                                            <i class="zmdi zmdi-time text-danger"></i> <span class="text-danger">{{ ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data['horaire'] == "06h00 - 18h00")
                                                            <i class="zmdi zmdi-time text-success"></i> <span class="text-success">{{ date("d/m/Y") }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "18h00 - 06h00")
                                                            <i class="zmdi zmdi-time text-info"></i> <span class="text-info">{{ date("d/m/Y") }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "repos")
                                                            <i class="zmdi zmdi-time text-danger"></i> <span class="text-danger">{{ date("d/m/Y") }}</span>
                                                        @endif
                                                      </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                                                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                        <?php
                                                        $edit = 0;
                                                        $delete = 0;
                                                        $display = 0;
                                                        if (
                                                            Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                                                ->get()
                                                                ->count() != 0
                                                        ) {
                                                            $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                                            $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                                            $display = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->display;
                                                        }
                                                        ?>
                                                        <?php } ?>
                                                        <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        <?php } else { ?>
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        <?php } ?>
                                                      </td>
                                                    <script>
                                                        $("#detail_{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            var $row = $(this).closest('tr');
                                                            var nom   = "{{ $users->name ?? '' }}";
                                                            $('#detail_nom').text(nom);
                                                            $('#modalDetailEcole').modal('show');
                                                        });
                                                    </script>
                                                @else
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $users->matricule ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $users->name ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ Groupes::where(["id" => $users->role])->first()["nom"] ?? '' }}</td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        {{ Postes::where(["id" => $data_p->poste_id])->first()["nom"] ?? '' }}
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data['service'] == "journée")
                                                            <i class="zmdi zmdi-info"></i> <span>{{ $data['service'] }} </span>
                                                        @endif
                                                        @if ($data['service'] == "nuit")
                                                            <i class="zmdi zmdi-info"></i> <span>{{ $data['service'] }} </span>
                                                        @endif
                                                        @if ($data['service'] == "repos")
                                                            <i class="zmdi zmdi-info"></i> <span>{{ $data['service'] }} </span>
                                                        @endif
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data['horaire'] == "06h00 - 18h00")
                                                            <i class="zmdi zmdi-time"></i> <span>{{  ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "18h00 - 06h00")
                                                            <i class="zmdi zmdi-time"></i> <span>{{ ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                        @if ($data['horaire'] == "repos")
                                                            <i class="zmdi zmdi-time"></i> <span>{{ ucfirst($data['horaire']) }} </span>
                                                        @endif
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                                        <?= explode("-", $data['date'])[2] . '/' . explode("-", $data['date'])[1] . '/'. explode("-", $data['date'])[0];?>
                                                    </td>
                                                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                                                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                        <?php
                                                        $edit = 0;
                                                        $delete = 0;
                                                        $display = 0;
                                                        if (
                                                            Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                                                ->get()
                                                                ->count() != 0
                                                        ) {
                                                            $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                                            $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                                            $display = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->display;
                                                        }
                                                        ?>
                                                        <?php } ?>
                                                        <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-dark"></i></a> &nbsp;
                                                        <?php } else { ?>
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-dark"></i></a> &nbsp;
                                                        <?php } ?>
                                                        <script>
                                                            $("#detail_{{ $i }}").click(function(e) {
                                                                e.preventDefault();
                                                                var $row = $(this).closest('tr');
                                                                var nom   = "{{ $users->name ?? '' }}";
                                                                $('#detail_nom').text(nom);
                                                                $('#modalDetailEcole').modal('show');
                                                            });
                                                        </script>
                                                    </td>
                                                @endif
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</section>
<span id="listesfactures_id" style="display: none;"></span>
<span id="data_id" style="display: none;"></span>
<span id="data_frais_id" style="display: none;"></span>
<span id="devise_paie_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#activation" id="btn_ac">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloture" id="btn_cl">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloturee" id="btn_cll">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#attendre" id="btn_att">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez cette élève ?</h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="activation" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous activez ce poste?</h5>
            </div>
            <div class="modal-body">
                <p id="element_1" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui_1" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_1" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloture" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous vous cloturez ce poste?</h5>
            </div>
            <div class="modal-body">
                <p id="element_2" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui_2" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_2" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloturee" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce poste est deja cloturé</h5>
            </div>
            <div class="modal-body">
                <p id="element_3" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <button style="font-weight: bold;" id="non_3" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="attendre" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce solde est en attente</h5>
            </div>
            <div class="modal-body">
                <p id="element_4" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <button style="font-weight: bold;" id="non_4" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord merci</button>
                </p>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#modal-centered" id="btn_sup_">Vertically centered</button>
<div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="modal-centered" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div style="border: 1px solid black;" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" style="color: black;font-weight: bold;">Paiement</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead><tr><th>Nom</th><th style="text-align: right;"><span id="nom_p"></span></th></tr></thead>
                                <thead><tr><th>Adresse</th><th style="text-align: right;"><span id="role_p"></span></th></tr></thead>
                                <thead><tr><th>Paiement</th><th style="text-align: right;"><span id="reste_p">0</span>/<span id="total_p" style="font-weight: bold;">100</span><span id="devise_p">$</span></th></tr></thead>
                            </table>
                        </div>
                    </div>
                    <div style="margin-top: 20px;" class="col-12">
                        <input type="text" id="montant_p" name="montant_p" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le montant">
                    </div>
                    <div style="margin-top: 20px;" class="col-12">
                        <input type="text" id="taux_p" name="taux_p" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le taux" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_paie" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                <button type="button" id="annuler_paie" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
            <p style="text-align: center;font-weight: bold;" id="m_paie"></p>
        </div>
    </div>
</div>
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                <h5 class="modal-title text-white" style="font-weight: bold;"><i class="zmdi zmdi-map"></i> Localisation de l'école</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div id="mapPreview" style="height: 450px; width: 100%; border-radius: 0 0 12px 12px;"></div>
                <div id="mapError" class="text-center text-danger p-3" style="display: none;"><i class="zmdi zmdi-alert-circle"></i> Coordonnées non disponibles pour cette alerte.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Détail Élève -->
<div class="modal fade" id="modalDetailEcole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                <h5 class="modal-title text-white"><i class="fa fa-info-circle"></i> Détails du pointage de : <span id="detail_nom"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-calendar text-danger"></i></div><div class="detail-content"><div class="detail-label">Date </div><div class="detail-value" id="detail_date"></div></div></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-fermer" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#activite" id="btn_activite">Vertically centered</button>
<div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="activite" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div style="border: 1px solid black;" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" style="color: black;font-weight: bold;">Activité</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="font-weight: bold;color:black;">
                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info-circle"></i> Voulez-vous la liste de facture de quelle activité ?</label>
                        <select style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" id="activite_id_a" name="activite_id_a" class="form-control" data-placeholder="Selectionnez une année">
                            <option selected value="">Selectionnez une activite</option>
                            @foreach ($activites as $data)
                                <option value="{{ $data->id }}">{{ $data->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_print" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                <button type="button" id="annuler_print" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
            <p style="text-align: center;font-weight: bold;" id="msg_p"></p>
        </div>
    </div>
</div>
@section('js-code')
<script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.orderbars/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/bar.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/dynamic.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/pie.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/chart-tooltips.js') }}"></script>
<script>
    // ========== GESTION DES ONGLETS ET FONCTIONS EXISTANTES ==========
    $("#link_43").addClass("active");

    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    })

    $("#district_id").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_commune_by_district') }}", {
            district_id: $(this).val()
        }, function(response)
        {
            $("#commune_id").html(response);
        });
    });

    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() { filterTable(); }, 100);
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
        $.get("{{ url('/get_liste_qr_code') }}", {
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
        setTimeout(function() {
            filterTable();
        }, 100);
    });
    $("#save").click(function(e) {
        e.preventDefault();
        var ecole = $("#ecole_id").val();
        var eleve = $("#nom_eleve").val();
        var classe = $("#classe_id").val();
        var genre = $("#genre").val();
        var nom_parent = $("#nom_parent").val();
        var telephone = $("#telephone").val();
        var data = $("#form_add").serialize();
        if (ecole.trim().length == 0)
        {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une école');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (eleve.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom complet de l\'élève');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if (classe.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une classe');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else
                {
                    if (genre.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le genre de l\'élève');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        if (nom_parent.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom du parent');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        }
                        else
                        {
                            if(telephone.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numéro de téléphone du parent');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            }else{
                                $("#save").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "/check_eleve_existe",
                                    data: data,
                                    success: function(response) {
                                        $("#save").attr("disabled", false);
                                        if (response == 1)
                                        {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cet élève est déjà enregistré');
                                            $('#msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#msg').html("");
                                            }, 9000);
                                        } else {
                                            $("#save").attr("disabled", true);
                                            $.ajax({
                                                type: "POST",
                                                url: "/add_eleve",
                                                data: data,
                                                success: function(response) {
                                                    $("#save").attr("disabled", false);
                                                    $("#nom_eleve").val("")
                                                    $("#nom_parent").val("");
                                                    $("#telephone").val("");
                                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Élève ajouté avec succès');
                                                    $('#msg').css("color", '#32c787');
                                                    $("#content_utilisateur").html(response);
                                                    setTimeout(() => {
                                                        $('#msg').html("");
                                                        filterTable();
                                                    }, 9000);
                                                }
                                            });
                                        }
                                    }
                                });
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
        $.get("{{ url('/refresh_deleteeleve') }}", {
            id: id,
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
            filterTable();
        });
    });

    $("#oui_1").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_activer_poste') }}", {
            id: id,
        }, function(refresh_editposte) {
            $("#content_utilisateur").html(refresh_editposte);
            $("#non_1").trigger("click");
            filterTable();
        });
    });

    $("#oui_2").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_cloturer_poste') }}", {
            id: id,
        }, function(refresh_editposte) {
            $("#content_utilisateur").html(refresh_editposte);
            $("#non_2").trigger("click");
            filterTable();
        });
    });

    // ========== FILTRAGE CLIENT (CORRIGÉ) ==========
    function filterTable() {
        let dateVal     = $('#filterDate').val().toLowerCase().trim();
        let nomVal      = $('#filterEleve').val().toLowerCase().trim(); // renommé pour clarté
        let posteVal    = $('#filterPoste').val().toLowerCase().trim();
        let prestationVal = $('#filterPrestation').val();
        let horaireVal  = $('#filterHoraire').val();

        let visibleCount = 0;

        $('#content_utilisateur tbody tr').each(function() {
            let $row = $(this);
            // Récupération du texte des colonnes (index)
            let dateCell     = $row.find('td:eq(7)').text().toLowerCase().trim();   // colonne Date
            let nomCell      = $row.find('td:eq(2)').text().toLowerCase().trim();   // colonne Nom (élève)
            let posteCell    = $row.find('td:eq(4)').text().toLowerCase().trim();   // colonne Poste
            let prestationCell = $row.find('td:eq(5)').text().toLowerCase().trim(); // colonne Préstation
            let horaireCell  = $row.find('td:eq(6)').text().toLowerCase().trim();   // colonne Horaire

            let match = true;
            if (dateVal && !dateCell.includes(dateVal)) match = false;
            if (nomVal && !nomCell.includes(nomVal)) match = false;
            if (posteVal && !posteCell.includes(posteVal)) match = false;
            if (prestationVal && prestationCell !== prestationVal) match = false;
            if (horaireVal && horaireCell !== horaireVal) match = false;

            if (match) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#studentCount').text(visibleCount);
    }

    // Écouteurs sur tous les champs de filtre
    $('#filterDate, #filterEleve, #filterPoste, #filterPrestation, #filterHoraire').on('input change', function() {
        filterTable();
    });

    // Bouton Réinitialiser
    $('#resetFilters').click(function(e) {
        e.preventDefault();
        $('#filterDate, #filterEleve, #filterPoste, #filterPrestation, #filterHoraire').val('');
        $('#filterPrestation').val('');
        $('#filterHoraire').val('');
        filterTable();
    });

    // Initialisation au chargement
    $(document).ready(function() {
        filterTable();
    });
</script>
@endsection
@endsection
