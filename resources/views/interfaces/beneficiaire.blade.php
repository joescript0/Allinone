@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\User;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\districts;
use App\Models\classes;
use App\Models\communes;
use App\Models\Lieux;
use App\Models\ecoles;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'BENEFICIAIRE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (BENEFICIAIRE)
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

.table tbody td a i.zmdi-edit {
    color: #10b981;
}
.table tbody td a i.zmdi-delete {
    color: #ef4444;
}
.table tbody td a i.zmdi-eye {
    color: #3b82f6;
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
.table tbody td a:hover i.zmdi-eye {
    color: #2563eb;
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

/* ========== MODAL DÉTAIL ÉLÈVE ========== */
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
                                    <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
                                        <a id="add" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de beneficiaire</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Liste</h4>

                <!-- SECTION FILTRES BÉNÉFICIAIRES AVEC SOUMISSION AJAX -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Encodeur</label>
                        <input type="text" id="filterEncodeur" class="form-control" placeholder="Nom encodeur...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Date</label>
                        <input type="text" id="filterDate" class="form-control" placeholder="jj/mm/aaaa ...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-box text-danger"></i> Élève</label>
                        <input type="text" id="filterEleve" class="form-control" placeholder="Nom élève...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-male-female text-danger"></i> Genre</label>
                        <select id="filterGenre" class="form-control">
                            <option value="">Tous</option>
                            <option value="F">F</option>
                            <option value="M">M</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-home text-danger"></i> Classe</label>
                        <input type="text" id="filterClasse" class="form-control" placeholder="Classe...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-accounts text-danger"></i> Parent</label>
                        <input type="text" id="filterParent" class="form-control" placeholder="Nom parent...">
                    </div>
                    <div class="filter-group">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Badge compteur élèves -->
                <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                    <span class="student-count-badge">
                        <i class="zmdi zmdi-view-list"></i> Élèves trouvés : <span id="studentCount">0</span>
                    </span>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Encodeur</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Eleve</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Genre / Sexe</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Classe</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Parent</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($beneficiaires as $data)
                                    @php
                                        $encodeur = (Auth::user()->id == $data->user_id) ? 'Vous' : (User::find($data->user_id)->name ?? '');
                                        $dateFormatee = \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i:s');
                                        $classeNom = classes::find($data->classe_id)->nom ?? '';
                                        $genreTexte = ($data->genre == 0) ? 'F' : 'M';
                                        $ecole = ecoles::find($data->ecole_id);
                                        $directeur = $ecole->directeur ?? '';
                                        $district = districts::find($ecole->district_id ?? 0);
                                        $commune = communes::find($ecole->commune_id ?? 0);
                                    @endphp
                                    <tr id="row_{{ $data->id }}"
                                        data-encodeur="{{ addslashes($encodeur) }}"
                                        data-date="{{ addslashes($dateFormatee) }}"
                                        data-eleve="{{ addslashes($data->nom_eleve) }}"
                                        data-genre="{{ $genreTexte }}"
                                        data-classe="{{ addslashes($classeNom) }}"
                                        data-parent="{{ addslashes($data->nom_parent) }}">
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $encodeur }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $dateFormatee }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_eleve }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">{{ $genreTexte }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $classeNom }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_parent }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->telephone }}</td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;&nbsp;
                                            <a id="edit_{{ $i }}" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;&nbsp;
                                            <a id="delete_{{ $i }}" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;&nbsp;
                                        </td>
                                        <script>
                                            $("#delete_{{ $i }}").click(function(e) {
                                                e.preventDefault();
                                                $("#element").html(
                                                    "<span style='color:black;'>Nom : </span>{{ $data->nom_eleve }}, <span style='color:black;'>Ecole : </span>{{ ecoles::where('id', $data->ecole_id)->first()['nom'] }}, <span style='color:black;'>District école : </span>{{ districts::where('id', ecoles::where('id', $data->ecole_id)->first()['district_id'])->first()['nom'] }}, <span style='color:black;'>Commune école : </span>{{ communes::where('id', ecoles::where('id', $data->ecole_id)->first()['commune_id'])->first()['nom'] }}, <span style='color:black;'>Adresse école : </span>{{ ecoles::where('id', $data->ecole_id)->first()['adresse'] }}."
                                                );
                                                $("#data_id").html("<?= $data->id ?>");
                                                $("#btn_sup").trigger("click");
                                            });
                                            $("#edit_{{ $i }}").click(function(e) {
                                                e.preventDefault();
                                                $.get("{{ url('/refresh_editeleve') }}", {
                                                    eleve_id: <?= $data->id ?>,
                                                }, function(refresh_editeleve) {
                                                    $("#bloc_1").hide();
                                                    $("#bloc_2").hide();
                                                    $("#bloc_3").show();
                                                    $("#bloc_3").html(refresh_editeleve);
                                                });
                                            });
                                            $("#detail_{{ $i }}").click(function(e) {
                                                e.preventDefault();
                                                var $row = $(this).closest('tr');
                                                $('#modalDetailEcole').modal('show');
                                                var nom_eleve   = "{{ $data->nom_eleve }}";
                                                <?php if(Auth::user()->id == $data->user_id){?>
                                                    var nom_encadreur   = "Vous";
                                                <?php }else{ ?>
                                                    var nom_encadreur   = "{{ User::where('id', $data->user_id)->first()['name'] }}"
                                                <?php } ?>
                                                <?php if($data->genre == 0){?>
                                                    var genre   = "F";
                                                <?php }else{ ?>
                                                    var genre   = "M"
                                                <?php } ?>
                                                var date   = "<?php $date = $data->created_at;$date_1 = explode(' ', $date);echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1]; ?>";
                                                var classe   = "{{ classes::where('id', $data->classe_id)->first()['nom'] }}";
                                                var parent   = "{{ $data->nom_parent }}";
                                                var telephone   = "{{ $data->telephone }}";
                                                var ecole   = "{{ ecoles::where('id', $data->ecole_id)->first()['nom'] }}";
                                                var district   = "{{ districts::where('id', ecoles::where('id', $data->ecole_id)->first()['district_id'])->first()['nom'] }}";
                                                var commune   = "{{ communes::where('id', ecoles::where('id', $data->ecole_id)->first()['commune_id'])->first()['nom'] }}";
                                                var adresse   = "{{ ecoles::where('id', $data->ecole_id)->first()['adresse'] }}";
                                                $('#detail_nom_eleve').text(nom_eleve);
                                                $('#detail_encodeur').text(nom_encadreur + ", Le " + date);
                                                $('#detail_genre').text(genre);
                                                $('#detail_classe').text(classe);
                                                $('#detail_parent').text(parent);
                                                $('#detail_telephone').text(telephone);
                                                $('#detail_ecole').text(ecole);
                                                $('#detail_district').text(district);
                                                $('#detail_commune').text(commune);
                                                $('#detail_adresse').text(adresse);
                                            });
                                        </script>
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
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Ajouter un beneficiaire</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-home"></i> Ecole </label>
                                <select id="ecole_id" name="ecole_id" class="form-control select2">
                                    <option value="">Selectionnez une école</option>
                                    @foreach ($ecoles as $data)
                                        <option value="{{ $data->id }}">Nom : {{ $data->nom }}, District : {{ districts::where(["id" => $data->district_id])->first()["nom"]; }}, Commune : {{ communes::where(["id" => $data->commune_id])->first()["nom"]; }}.</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom de l'élève </label>
                                <input type="text" id="nom_eleve" name="nom_eleve" class="form-control" placeholder="Nom (Ex : KALENGA KALALA Helène)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-home"></i> Classe </label>
                                <select id="classe_id" name="classe_id" class="form-control">
                                    <option value="">Selectionnez une classe</option>
                                    @foreach ($classes as $data)
                                        <option value="{{ $data->id }}">{{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-male-female"></i> Genre / Sexe </label>
                                <select id="genre" name="genre" class="form-control">
                                    <option value="">Selectionnez un genre</option>
                                    <option value="0">F</option>
                                    <option value="1">M</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nom parent </label>
                                <input type="text" id="nom_parent" name="nom_parent" class="form-control" placeholder="Nom du parent (Ex : KOKO léonce)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Téléphone </label>
                               <input type="text" id="telephone" name="telephone" class="form-control" placeholder="Numéro de téléphone (Ex : 0123456789)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 20px;" class="row">
                        <div class="col-12">
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
                <h5 class="modal-title text-white"><i class="fa fa-info-circle"></i> Détails de l'élève : <span id="detail_nom_eleve"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-account text-danger"></i></div><div class="detail-content"><div class="detail-label">Nom encodeur</div><div class="detail-value" id="detail_encodeur"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-city text-danger"></i></div><div class="detail-content"><div class="detail-label">Nom ecole</div><div class="detail-value" id="detail_ecole"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-pin-drop text-danger"></i></div><div class="detail-content"><div class="detail-label">District école</div><div class="detail-value" id="detail_district"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-city text-danger"></i></div><div class="detail-content"><div class="detail-label">Commune école</div><div class="detail-value" id="detail_commune"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-map text-danger"></i></div><div class="detail-content"><div class="detail-label">Adresse école</div><div class="detail-value" id="detail_adresse"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-info text-danger"></i></div><div class="detail-content"><div class="detail-label">Genre / sexe eleve</div><div class="detail-value" id="detail_genre"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-home text-danger"></i></div><div class="detail-content"><div class="detail-label">Classe eleve</div><div class="detail-value" id="detail_classe"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-male-female text-danger"></i></div><div class="detail-content"><div class="detail-label">Nom parent eleve</div><div class="detail-value" id="detail_parent"></div></div></div>
                    <div class="detail-item"><div class="detail-icon"><i class="zmdi zmdi-phone text-danger"></i></div><div class="detail-content"><div class="detail-label">Téléphone parent eleve</div><div class="detail-value" id="detail_telephone"></div></div></div>
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
    $("#link_42").addClass("active");

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

    // ========== FILTRES INSTANTANÉS CÔTÉ CLIENT (sans téléphone) ==========
    function filterTable() {
        let encodeur = $('#filterEncodeur').val().toLowerCase().trim();
        let date     = $('#filterDate').val().toLowerCase().trim();
        let eleve    = $('#filterEleve').val().toLowerCase().trim();
        let genre    = $('#filterGenre').val();
        let classe   = $('#filterClasse').val().toLowerCase().trim();
        let parent   = $('#filterParent').val().toLowerCase().trim();

        let visibleCount = 0;

        $('#content_utilisateur tbody tr').each(function() {
            let $row = $(this);
            let encodeurVal = $row.data('encodeur')?.toLowerCase() || '';
            let dateVal     = $row.data('date')?.toLowerCase() || '';
            let eleveVal    = $row.data('eleve')?.toLowerCase() || '';
            let genreVal    = $row.data('genre') || '';
            let classeVal   = $row.data('classe')?.toLowerCase() || '';
            let parentVal   = $row.data('parent')?.toLowerCase() || '';

            let match = true;
            if (encodeur && !encodeurVal.includes(encodeur)) match = false;
            if (date && !dateVal.includes(date)) match = false;
            if (eleve && !eleveVal.includes(eleve)) match = false;
            if (genre && genreVal !== genre) match = false;
            if (classe && !classeVal.includes(classe)) match = false;
            if (parent && !parentVal.includes(parent)) match = false;

            if (match) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#studentCount').text(visibleCount);
    }

    // Écouteurs sur les champs de filtre pour le filtrage instantané
    $('#filterEncodeur, #filterDate, #filterEleve, #filterGenre, #filterClasse, #filterParent').on('input change', function() {
        filterTable();
    });

    // Bouton Réinitialiser (filtres clients)
    $('#resetFilters').click(function(e) {
        e.preventDefault();
        $('#filterEncodeur, #filterDate, #filterEleve, #filterGenre, #filterClasse, #filterParent').val('');
        $('#filterGenre').val('');
        filterTable();
    });

    // ========== SOUMISSION DES FILTRES AU SERVEUR VIA AJAX ==========
    $('#submitFilters').click(function(e) {
        e.preventDefault();
        let filters = {
            encodeur: $('#filterEncodeur').val(),
            date: $('#filterDate').val(),
            eleve: $('#filterEleve').val(),
            genre: $('#filterGenre').val(),
            classe: $('#filterClasse').val(),
            parent: $('#filterParent').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '/submit-filters',
            type: 'POST',
            data: filters,
            success: function(response) {
                // Notification flottante de succès
                let msg = $('<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: #10b981; color: white; border-radius: 50px; padding: 10px 20px;">' +
                            '<i class="zmdi zmdi-check-circle"></i> Filtres soumis avec succès !' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button></div>');
                $('body').append(msg);
                setTimeout(() => msg.fadeOut(500, function() { $(this).remove(); }), 3000);
            },
            error: function(xhr) {
                let errorMsg = 'Erreur lors de la soumission.';
                if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                let msg = $('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: #e31b23; color: white; border-radius: 50px; padding: 10px 20px;">' +
                            '<i class="zmdi zmdi-close-circle"></i> ' + errorMsg +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button></div>');
                $('body').append(msg);
                setTimeout(() => msg.fadeOut(500, function() { $(this).remove(); }), 3000);
            }
        });
    });

    // Initialisation au chargement
    $(document).ready(function() {
        filterTable();
    });
</script>
@endsection
@endsection
