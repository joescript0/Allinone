@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp

<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'UTILISATEURS')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES AUTRES PAGES
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
.btn-primary,
.btn-info,
.btn-danger,
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

#resetFilters {
    background: #64748b !important;
    color: white !important;
}
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
}

/* Boutons désactivés (add_r, save_r, print_r) */
#add_r,
#save_r,
#print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    transform: none !important;
    box-shadow: none !important;
}

/* Bouton d'impression (spécifique à cette page) */
#print {
    background: #3B82F6 !important;
    color: white !important;
}
#print:hover {
    background: #2563eb !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
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

.user-count-badge {
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
#msg,
#edit_msg {
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

#msg:not(:empty),
#edit_msg:not(:empty) {
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

#msg:not(:empty):has(i.zmdi-info),
#edit_msg:not(:empty):has(i.zmdi-info) {
    background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important;
    color: #1e3a8a;
    border-left: 4px solid #3b82f6;
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

/* ========== STYLES POUR L'IMAGE DE PROFIL DANS LE TABLEAU ========== */
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

a[id^="voir_profil_"] {
    display: inline-block;
    vertical-align: middle;
    line-height: 0;
    margin-right: 8px;
    background: transparent !important;
    text-decoration: none !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

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

.table tbody td:has(a[id^="voir_profil_"]) {
    white-space: nowrap;
}

a[id^="voir_profil_"] + * {
    display: inline-block;
    vertical-align: middle;
    line-height: 1.4;
    max-width: calc(100% - 45px);
    white-space: normal;
    word-break: break-word;
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
    #print {
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
    .user-count-badge {
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
    .profile-thumb {
        width: 28px;
        height: 28px;
    }
    a[id^="voir_profil_"] {
        margin-right: 6px;
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
    #print {
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Utilisateurs</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i>
                    Liste
                    <span class="user-count-badge">
                        <i class="zmdi zmdi-view-list"></i> Total utilisateurs : <span id="userCount">0</span>
                    </span>
                </h4>

                <!-- SECTION FILTRES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-label text-danger"></i> Matricule</label>
                        <input type="text" id="filterMatricule" class="form-control" placeholder="Rechercher par matricule...">
                    </div>
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
                        <label><i class="zmdi zmdi-settings text-danger"></i> Rôle</label>
                        <select id="filterRole" class="form-control">
                            <option value="all">Tous les rôles</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-pin text-danger"></i> Poste</label>
                        <select id="filterPoste" class="form-control">
                            <option value="all">Tous les postes</option>
                            @foreach ($postes as $poste)
                                <option value="{{ $poste->id }}">{{ $poste->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-money text-danger"></i> Salaire</label>
                        <input type="number" id="filterSalaire" class="form-control" placeholder="Salaire exact" step="0.01">
                    </div>
                    <!-- ========== FILTRE PAR user_id : visible uniquement pour les admins ========== -->
                    @if(Auth::user()->role == 0)
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-circle text-danger"></i> Utilisateur</label>
                        <select id="filterUserId" class="form-control">
                            <option value="all">Tous les utilisateurs</option>
                            @php $allUsers = \App\Models\User::all(); @endphp
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}">
                                    @if($user->id == Auth::user()->id)
                                        Vous
                                    @else
                                        {{ $user->name }} ({{ $user->matricule ?? 'N/A' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <!-- ========== FIN FILTRE ========== -->
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
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Matricule</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Salaire</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Role / Fonction</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Poste / Lieux</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($utilisateurs as $data)
                                        <!-- ========== data-user-id = $data->user_id ========== -->
                                        <tr id="row_{{ $data->id }}" data-user-id="{{ $data->user_id }}">
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="matricule-cell" data-matricule="{{ $data->matricule }}">{{ $data->matricule }}</td>
                                            <td class="align-middle nom-cell" data-nom="{{ $data->name }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                <a id="voir_profil_<?= $i ?>" href="#">
                                                    <img src="{{ asset($data->image) }}" alt="avatar" class="profile-thumb">
                                                </a> {{ $data->name }}
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="salaire-cell" data-salaire="{{ $data->salaire }}" data-devise="{{ $data->devise }}">
                                                @if (Auth::user()->role == 0)
                                                    @if ($data->devise == 0)
                                                        {{ number_format($data->salaire, 2, ',', ' ') .'USD'; }}
                                                    @else
                                                        {{ number_format($data->salaire, 2, ',', ' ') .'CDF'; }}
                                                    @endif
                                                @else
                                                    @if ($data->devise == 0)
                                                        {{ number_format(0, 2, ',', ' ') .'USD'; }}
                                                    @else
                                                        {{ number_format(0, 2, ',', ' ') .'CDF'; }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="email-cell" data-email="{{ $data->email }}">{{ $data->email }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="role-cell" data-role="{{ $data->role }}">
                                                @if ($groupes->count()!= 0)
                                                    <?= Groupes::where('id', $data->role)->first()["nom"] ?? 'N/A'; ?>
                                                @endif
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="poste-cell" data-poste="{{ $data->poste_id }}">
                                                <?php
                                                    $potess = Postes::where('id', $data->poste_id)->first()
                                                ?>
                                                @if ($data->poste_id == 0)
                                                    <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">{{ 'Aucun' }} </span>
                                                @else
                                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success"><?= $potess["nom"] ?? 'N/A'; ?>, <?= Lieux::where(["id" => $potess["lieuxe_id"] ?? 0])->first()["nom"] ?? 'N/A'; ?>.</span>
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
                                                <?php if ((($edit == 1) && ($data->user_id == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                                                    <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } else { ?>
                                                    <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } ?>
                                                <?php if (($delete == 1 && $data->user_id == Auth::user()->id) || (Auth::user()->role == 0)) { ?>
                                                    <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } else { ?>
                                                    <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } ?>
                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_editutilisateur') }}", {
                                                            user_id: <?= $data->id ?>,
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
                                                    $("#voir_profil_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#nom_profil").html("<?= $data->name ?>");
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        var url = "<?= $data->image ?>";
                                                        $("#contenu_voir_profil").html('<img src="' + url +
                                                            '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                                        );
                                                        $("#btn_voir_profil").trigger("click");
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
                    <p style="color:rgba(0, 0, 0, 0.6);" class="text-center"><a href="#"><img id="user_img_profil" class="user__img" src="{{ asset('storage/images/user/profil_defaut.png') }}" alt="" style="width: 100px; height: 100px; object-fit: cover;"></a></p>
                    <!-- Barre de progression -->
                    <div class="progress-container" style="display:none; margin-top: 10px;">
                        <div class="progress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;"></div>
                        <span class="progress-text" style="font-size:12px;">0%</span>
                    </div>

                    <input type="file" name="input_user_img_profil" id="input_user_img_profil" style="display:none;">
                    <input type="text" name="image" id="image" value="{{ asset('storage/images/user/profil_defaut.png') }}" style="display:none;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)">
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
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Role / Fonction </span></label>
                                <select id="role" name="role" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Role</option>
                                    @foreach ($groupes as $data)
                                    <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Mot de passe </span></label>
                                <input type="password" id="mdp" name="mdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Mot de passe">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Confirmez </span></label>
                                <input type="password" id="cmdp" name="cmdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Confirmez">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Salaire </span></label>
                                <input type="text" id="salaire" name="salaire" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="input-mask form-control" data-mask="00000000000000000000000000000000000000" placeholder="Salaire">
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
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Poste </span></label>
                                <select id="poste_id" name="poste_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option selected class="form-control" value="0">Aucun</option>
                                    @foreach ($postes as $data)
                                        <option class="form-control" value="{{ $data->id }}">Nom : {{ $data->nom }}, Lieux : {{ Lieux::where(["id" => $data->lieuxe_id])->first()["nom"] ?? 'N/A'; }}.</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-toll"></i> Activité </span></label>
                                <select id="activite_id" name="activite_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Aucune</option>
                                    @foreach ($activites as $data)
                                    <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                    @endforeach
                                </select>
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
<button style="display: none;" data-toggle="modal" data-target="#profil_utilisateur" id="btn_voir_profil">Sup</button>
<div class="modal fade" id="profil_utilisateur" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Profil : <span id="nom_profil"></span> </h5>
            </div>
            <div class="modal-body">
                <p id="contenu_voir_profil" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
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
    $("#link_40").addClass("active");

    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    });

    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            filterUsers();
        }, 100);
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
    });

    // ========== MODIFICATION : bouton Imprimer envoie tous les filtres ==========
    $("#print").click(function(e) {
        e.preventDefault();
        // Récupérer les valeurs des filtres
        var params = {
            matricule: $('#filterMatricule').val(),
            nom: $('#filterNom').val(),
            email: $('#filterEmail').val(),
            phone: $('#filterPhone').val(),
            role: $('#filterRole').val(),
            poste: $('#filterPoste').val(),
            salaire: $('#filterSalaire').val()
        };
        // Ajouter user_id seulement si le filtre existe
        if ($('#filterUserId').length) {
            params.userId = $('#filterUserId').val();
        } else {
            params.userId = 'all';
        }
        // Effectuer la requête GET avec les paramètres
        $.get("{{ url('/get_liste_employe') }}", params, function(response) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("") }}' + response);
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
            filterUsers();
        }, 100);
    });

    $("#save").click(function(e) {
        e.preventDefault();
        var nom = $("#nom").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var role = $("#role").val();
        var mdp = $("#mdp").val();
        var cmdp = $("#cmdp").val();
        var salaire = $("#salaire").val();
        var devise = $("#devise").val();
        var data = $("#form_add").serialize();
        if (nom.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            setTimeout(() => { $('#msg').html(""); }, 9000);
        } else {
            if (email.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'adresse e-mail');
                setTimeout(() => { $('#msg').html(""); }, 9000);
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (!regex.test(email)) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/check_email_utilisateur",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette adresse e-mail existe déjà');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else {
                                if (phone.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero de telephone');
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                } else {
                                    if (!Number(phone.trim())) {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez un bon numero de telephone');
                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                    } else {
                                        $.ajax({
                                            type: "POST",
                                            url: "/check_phone_utilisateur",
                                            data: data,
                                            success: function(response) {
                                                if (response == 1) {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Ce numero de telephone existe déjà');
                                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                                } else {
                                                    if (role.trim().length == 0) {
                                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le role');
                                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                                    } else {
                                                        if (mdp.trim().length == 0) {
                                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le mot de passe');
                                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                                        } else {
                                                            if (cmdp.trim().length == 0) {
                                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Confirmez le mot de passe');
                                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                                            } else {
                                                                if (cmdp != mdp) {
                                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Incohérance de deux mot de passe');
                                                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                                                } else {
                                                                    if(salaire.trim().length == 0)
                                                                    {
                                                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le salaire');
                                                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                                                    }
                                                                    else
                                                                    {
                                                                        $("#save").attr("disabled", true);
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "/add_utilisateur",
                                                                            data: data,
                                                                            success: function(response) {
                                                                                $("#save").attr("disabled", false);
                                                                                $("#nom").val("");
                                                                                $("#email").val("");
                                                                                $("#phone").val("");
                                                                                $("#salaire").val("");
                                                                                $("#mdp").val("");
                                                                                $("#cmdp").val("");
                                                                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Utilisateur ajouté avec succès');
                                                                                $("#content_utilisateur").html(response);
                                                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                                                                saveUserFiltersToStorage();
                                                                                setTimeout(function() {
                                                                                    loadUserFiltersFromStorage();
                                                                                    filterUsers();
                                                                                }, 100);
                                                                            }
                                                                        });
                                                                    }
                                                                }
                                                            }
                                                        }
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
            saveUserFiltersToStorage();
            setTimeout(function() {
                loadUserFiltersFromStorage();
                filterUsers();
            }, 100);
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
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('.progress-container').show();
        $('.progress-bar').css('width', '0%');
        $('.progress-text').text('0%');

        $.ajax({
            type: "POST",
            url: "/upload_profil_add",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
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
                $('.progress-bar').css('width', '100%');
                $('.progress-text').text('100%');
                setTimeout(function() {
                    $('.progress-container').hide();
                }, 1000);
                $('#msg').html('Profil teléchargé avec succès');
                $('#user_img_profil').attr('src', response);
                $("#image").val(response);
                setTimeout(() => { $('#msg').html(""); }, 9000);
            },
            error: function(xhr) {
                $('.progress-container').hide();
                $('#msg').html(xhr.responseJSON.message);
                setTimeout(() => { $('#msg').html(""); }, 9000);
            }
        });
    });

    // ========== FONCTIONS DE FILTRAGE AVEC PERSISTANCE ==========

    let userFilterTimeout;

    function saveUserFiltersToStorage() {
        let userId = 'all';
        if ($('#filterUserId').length) {
            userId = $('#filterUserId').val();
        }
        const filters = {
            matricule: $('#filterMatricule').val(),
            nom: $('#filterNom').val(),
            email: $('#filterEmail').val(),
            phone: $('#filterPhone').val(),
            role: $('#filterRole').val(),
            poste: $('#filterPoste').val(),
            salaire: $('#filterSalaire').val(),
            userId: userId
        };
        localStorage.setItem('userFilters', JSON.stringify(filters));
    }

    function loadUserFiltersFromStorage() {
        const savedFilters = localStorage.getItem('userFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#filterMatricule').val(filters.matricule || '');
            $('#filterNom').val(filters.nom || '');
            $('#filterEmail').val(filters.email || '');
            $('#filterPhone').val(filters.phone || '');
            $('#filterRole').val(filters.role || 'all');
            $('#filterPoste').val(filters.poste || 'all');
            $('#filterSalaire').val(filters.salaire || '');
            if ($('#filterUserId').length) {
                $('#filterUserId').val(filters.userId || 'all');
            }
            return true;
        }
        return false;
    }

    function filterUsers() {
        const filterMatricule = $('#filterMatricule').val().toLowerCase();
        const filterNom = $('#filterNom').val().toLowerCase();
        const filterEmail = $('#filterEmail').val().toLowerCase();
        const filterPhone = $('#filterPhone').val().toLowerCase();
        const filterRole = $('#filterRole').val();
        const filterPoste = $('#filterPoste').val();
        const filterSalaire = parseFloat($('#filterSalaire').val());
        const filterUserId = $('#filterUserId').length ? $('#filterUserId').val() : 'all';

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const matriculeValue = ($row.find('.matricule-cell').data('matricule') || '').toLowerCase();
            const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
            const emailValue = ($row.find('.email-cell').data('email') || '').toLowerCase();
            const phoneValue = ($row.find('.phone-cell').data('phone') || '').toLowerCase();
            const roleValue = $row.find('.role-cell').data('role') || '';
            const posteValue = $row.find('.poste-cell').data('poste') || '';
            const salaireValue = parseFloat($row.find('.salaire-cell').data('salaire') || 0);
            const userId = $row.data('userId'); // $data->user_id

            if (filterMatricule && !matriculeValue.includes(filterMatricule)) showRow = false;
            if (showRow && filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterEmail && !emailValue.includes(filterEmail)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;
            if (showRow && filterRole !== 'all' && roleValue != filterRole) showRow = false;
            if (showRow && filterPoste !== 'all' && posteValue != filterPoste) showRow = false;
            if (showRow && !isNaN(filterSalaire) && salaireValue != filterSalaire) showRow = false;
            if (showRow && filterUserId !== 'all' && userId != filterUserId) showRow = false;

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(newIndex);
                newIndex++;
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#userCount').text(visibleCount);

        if (visibleCount === 0 && (filterMatricule || filterNom || filterEmail || filterPhone || filterRole !== 'all' || filterPoste !== 'all' || !isNaN(filterSalaire) || filterUserId !== 'all')) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Aucun utilisateur ne correspond aux critères de recherche');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }
    }

    function resetUserFilters() {
        $('#filterMatricule').val('');
        $('#filterNom').val('');
        $('#filterEmail').val('');
        $('#filterPhone').val('');
        $('#filterRole').val('all');
        $('#filterPoste').val('all');
        $('#filterSalaire').val('');
        if ($('#filterUserId').length) {
            $('#filterUserId').val('all');
        }

        saveUserFiltersToStorage();

        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex);
            newIndex++;
        });
        const totalCount = $('#content_utilisateur tbody tr').length;
        $('#userCount').text(totalCount);

        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
        $('#msg').css('display', 'flex');
        setTimeout(() => {
            $('#msg').html('');
            $('#msg').css('display', 'none');
        }, 3000);
    }

    function debouncedUserFilter() {
        clearTimeout(userFilterTimeout);
        userFilterTimeout = setTimeout(() => {
            filterUsers();
            saveUserFiltersToStorage();
        }, 300);
    }

    $(document).ready(function() {
        const totalUsers = $('#content_utilisateur tbody tr').length;
        $('#userCount').text(totalUsers);

        const hasSavedFilters = loadUserFiltersFromStorage();

        $('#filterMatricule, #filterNom, #filterEmail, #filterPhone, #filterRole, #filterPoste, #filterSalaire').on('input change', function() {
            debouncedUserFilter();
        });
        if ($('#filterUserId').length) {
            $('#filterUserId').on('change', function() {
                debouncedUserFilter();
            });
        }

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetUserFilters();
        });

        if (hasSavedFilters) {
            setTimeout(function() {
                filterUsers();
            }, 100);
        }
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_utilisateur'))) {
            setTimeout(() => {
                const totalUsers = $('#content_utilisateur tbody tr').length;
                $('#userCount').text(totalUsers);
                loadUserFiltersFromStorage();
                filterUsers();
            }, 200);
        }
    });

    window.addEventListener('beforeunload', function() {
        saveUserFiltersToStorage();
    });
</script>
@endsection
@endsection