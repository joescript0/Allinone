@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Approvisionnements;
use App\Models\Societes;
use App\Models\Entres;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'APPROVISIONNEMENT D\'ARTICLE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* =============================================
   DESIGN PREMIUM - VERSION FINALE (copié de la page Gestion article)
   BOUTONS MODERNES & UNIFORMISÉS
   LIGNES DE TABLEAU RÉDUITES
   FILTRES MODERNES
   RESPONSIVE AVANCÉE
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

/* --- Variables --- */
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
#bloc_3 {
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

/* ========== TABLEAU : LIGNES PLUS AÉRÉES ET VISIBLES ========== */
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
    border-bottom: 1px solid #e2e8f0; /* bordure plus visible */
}

/* Rayures (zebra) pour une meilleure lisibilité */
.table tbody tr:nth-child(even) {
    background-color: #f8fafc; /* fond très clair pour les lignes paires */
}

.table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

/* Survol */
.table tbody tr:hover {
    background: #e6f0ff !important; /* bleu clair plus prononcé */
    cursor: default;
}

/* Cellules : espacement augmenté */
.table tbody td {
    padding: 10px 12px !important;  /* plus d'espace pour la lisibilité */
    vertical-align: middle !important;
    font-weight: 500;
    font-size: 0.85rem; /* légèrement plus grand */
    color: #1e2a3e;
    word-break: break-word;
    border-bottom: 1px solid #eef2f6;
    line-height: 1.4;
}

/* Dernière cellule (contrôle) centrée */
.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
}

/* ========== STYLE UNIQUE POUR TOUS LES BOUTONS (MODERNE, ARRONDI, OMBRE) ========== */
#bloc_1 button,
#bloc_2 button,
#bloc_3 button,
.filters-container button,
#liste,
#add,
#add_r,
#save,
#save_r,
#annuler,
#edit_save,
#edit_annuler,
#resetFilters,
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

/* Bouton désactivé (ex: add_r, save_r) */
#add_r,
#save_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    transform: none !important;
    box-shadow: none !important;
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

/* Badge spécifique aux approvisionnements */
.appro-count-badge {
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

/* Badge Total USD / CDF (ajoutés) */
.appro-count-badge.usd-badge {
    background: linear-gradient(135deg, #0f4c5f, #1e6f5c);
}
.appro-count-badge.cdf-badge {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
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

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container {
        padding: 0.5rem 1rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3 {
        padding: 1rem !important;
    }
}

@media (max-width: 768px) {
    .content .container {
        padding: 0.4rem 0.6rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3 {
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
    .btn-danger {
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
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.3rem !important;
    }
    #bloc_1,
    #bloc_2,
    #bloc_3 {
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
    #resetFilters {
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

/* ========== AJOUTS POUR LES ÉLÉMENTS PROPRES À CETTE PAGE ========== */
/* Boutons de contrôle dans le tableau (œil, etc.) */
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
    color: #2c7da0;
}
.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a i.zmdi-delete {
    color: #ef4444;
}
.table tbody td a:hover i.zmdi-delete {
    color: #b91c1c;
}
.table tbody td a:hover {
    background: #ffe5e5;
}

/* Ajustement pour le conteneur d'actions (barre des boutons) */
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

/* Alignement des colonnes de contrôle dans le tableau */
.table tbody td:last-child {
    text-align: center;
}

/* Responsive fine */
@media (max-width: 768px) {
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
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
                                    if (
                                        Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                            ->get()
                                            ->count() != 0
                                    ) {
                                        $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Approvisionnement d'article</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                            class="zmdi zmdi-email-open text-info"></i> Liste</h4>

                    <!-- SECTION FILTRES AVEC DATE RANGE PICKER -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> N° Facture</label>
                            <input type="text" id="filterNumero" class="form-control" placeholder="Rechercher par numéro...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                            <input type="text" id="filterUser" class="form-control" placeholder="Rechercher par utilisateur...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-money text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="Montant exact" step="0.01">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                            <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période">
                        </div>
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Badges compteur et totaux -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span class="appro-count-badge" style="background: linear-gradient(135deg, #0a192f, #1e3a5f);">
                            <i class="zmdi zmdi-view-list"></i> Total approvisionnements : <span id="approCount">0</span>
                        </span>
                        <span class="appro-count-badge usd-badge">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsd">0,00</span> $
                        </span>
                        <span class="appro-count-badge cdf-badge">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdf">0,00</span> Fc
                        </span>
                    </div>

                    <div id="content_utilisateur" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N° Facture</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date d'entré</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($factures as $data)
                                            @php
                                                $t = 0;
                                                $ent = Approvisionnements::where('facture_id', $data->id)->get();
                                                foreach ($ent as $e) 
                                                {
                                                    $t = $t + $e->total;
                                                    
                                                    // Taux de la facture (si disponible, sinon 1)
                                                    $tauxFacture = $e->taux ?? 1;
                                                    if ($data->devise == 0) 
                                                    {
                                                        $montant_usd = $t;
                                                        $montant_cdf = $t * $tauxFacture;
                                                    } else 
                                                    {
                                                        $montant_cdf = $t;
                                                        $montant_usd = $t / $tauxFacture;
                                                    }
                                                }
                                            @endphp
                                            <tr id="row_{{ $data->id }}"
                                                data-montant-usd="{{ $montant_usd }}"
                                                data-montant-cdf="{{ $montant_cdf }}">
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="<?php echo $t; ?>">
                                                    <?php
                                                        if ($data->devise == 0)
                                                        {
                                                            echo number_format($t, 2, ',', ' ') .  '(USD)';
                                                        } else {
                                                            echo number_format($t, 2, ',', ' ') . '(CDF)';
                                                        }
                                                    ?>
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ date('Y-m-d', strtotime($data->date_creation)) }}">
                                                    {{ date('d/m/Y', strtotime($data->date_creation)) }}
                                                </td>
                                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
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
                                                    <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                                                    <?php } else { ?>
                                                    <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                                                    <?php } ?>
                                                    <script>
                                                        $("#detail_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $.get("{{ url('/refresh_detailfactureas') }}", {
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
                                                    </script>
                                                </td>
                                            </tr>
                                            {{ !$i++ }}
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
                                            class="zmdi zmdi-info"></i> Numero facture</span></label>
                                    <select id="numero_facture" name="numero_facture" class="select2"
                                        data-placeholder="Selectionnez un type de sortie">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Il s'agit de quel article ?</span></label>
                                    <select id="type_sortie" name="type_sortie" class="select2"
                                        data-placeholder="Selectionnez un article">
                                        <option selected value="">Selectionnez un article</option>
                                        @foreach ($articles as $data)
                                            <option value="{{ $data->id }}">
                                                <?= $data->nom_article . ' (' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Prix d'achat </span></label>
                                    <input id="prix_unitaire" name="prix_unitaire" type="text"
                                        class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Prix d'achat (Ex : 10)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Quantité </span></label>
                                    <input id="quantite" name="quantite" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Quantité (Ex : 10)">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> devise </span></label>
                                    <select id="devise" name="devise" class="form-control"
                                        data-placeholder="Selectionnez une devise">
                                        <option selected class="form-control" value="">Selectionnez une devise
                                        </option>
                                        <option class="form-control" value="0"> USD</option>
                                        <option class="form-control" value="1"> CDF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Taux</span></label>
                                    <input id="taux" name="taux" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Taux (Ex : 10)">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-comment"></i> Libelle </span></label>
                                    <textarea id="libelle" name="libelle"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Libellé" cols="2" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div style="margin-top: 10px;" class="row">
                        <div class="col-12">
                            <label class="text-info" style="font-weight: bold;"><i
                                    class="zmdi zmdi-info text-danger"></i> Déposez votre attache
                                d'approvisionnement</span></label>
                            <form method="post"
                                style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;"
                                action="{{ route('upload_fichier_sortie') }}" class="dropzone" id="dropzonewidget">
                                @csrf
                                <input type="hidden" id="n_s" name="n_s" value="">
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
                                if (
                                    Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                        ->get()
                                        ->count() != 0
                                ) {
                                    $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                    $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
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
                    <br>
                    <div class="row" id="content_sortie">
                    </div>
                    <br>
                </div>
                <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">
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
                    <p id="element" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
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
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        approuvez ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element_1" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_frais" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_frais" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
@section('js-code')
    {{-- Ajout des dépendances pour le Date Range Picker (identique aux factures) --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

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
        $("#link_23").addClass("active");

        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzonewidget").trigger("click");
        });

        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            setTimeout(function() {
                filterApprovisionnements();
            }, 100);
        });

        $("#add").click(function(e) {
            $.get("{{ url('/get_numero_facture_a') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").hide();
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
            setTimeout(function() {
                filterApprovisionnements();
            }, 100);
        });

        $("#save").click(function(e) {
            e.preventDefault();
            var numero_facture = $("#numero_facture").val();
            var type_sortie = $("#type_sortie").val();
            var prix_unitaire = $("#prix_unitaire").val();
            var quantite = $("#quantite").val();
            var devise = $("#devise").val();
            var taux = $("#taux").val();
            var libelle = $("#libelle").val();
            var data = $("#form_add").serialize();

            if (numero_facture.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero d\'entré');
                setTimeout(() => { $('#msg').html(""); }, 9000);
            } else {
                if (type_sortie.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    if (prix_unitaire.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix d\'achat');
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                    } else {
                        if (quantite.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else {
                            $.get("{{ url('/check_seuil_maximum') }}", {
                                article_id: type_sortie,
                                devise: devise,
                                quantite: quantite,
                                prix_unitaire: prix_unitaire,
                                taux: taux,
                            }, function(repp) {
                                var data_rep = repp.split("__________")
                                if (data_rep[0] == 0) {
                                    $('#msg').html(
                                        '<i class="zmdi zmdi-close-circle"></i> Le seuil maximum de cette article est de : ' +
                                        data_rep[1] + ', stock disponible : ' + data_rep[2]);
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                } else {
                                    if (devise.trim().length == 0) {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                    } else {
                                        if (taux.trim().length == 0) {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                        } else {
                                            $("#save").attr("disabled", true);
                                            $.ajax({
                                                type: "POST",
                                                url: "/add_app_article",
                                                data: data,
                                                success: function(response) {
                                                    Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                                    $("#save").attr("disabled", false);
                                                    $("#prix_unitaire").val("");
                                                    $("#quantite").val("");
                                                    $("#taux").val("");
                                                    $("#libelle").val("");
                                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Approvisionnement ajouté avec succès');
                                                    $("#content_utilisateur").html(response);
                                                    $.get("{{ url('/get_approvisionnement') }}", {}, function(response) {
                                                        $("#content_sortie").html(response);
                                                    });
                                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                                    saveApproFiltersToStorage();
                                                    setTimeout(function() {
                                                        loadApproFiltersFromStorage();
                                                        filterApprovisionnements();
                                                    }, 100);
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

        $("#oui").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_deletedecision') }}", {
                id: id,
            }, function(refresh_editutilisateur) {
                $("#content_utilisateur").html(refresh_editutilisateur);
                $("#non").trigger("click");
                saveApproFiltersToStorage();
                setTimeout(function() {
                    loadApproFiltersFromStorage();
                    filterApprovisionnements();
                }, 100);
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
                    sucess: function(data) {
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

        $.get("{{ url('/get_numero_facture_a') }}", {}, function(response) {
            $("#numero_facture").html(response);
        });

        // ========== FONCTIONS DE FILTRAGE AVEC DATE RANGE PICKER (IDENTIQUES AUX FACTURES) ==========

        let approFilterTimeout;

        function saveApproFiltersToStorage() {
            const filters = {
                numero: $('#filterNumero').val(),
                user: $('#filterUser').val(),
                montant: $('#filterMontant').val(),
                dateRange: $('#filterDateRange').val()
            };
            localStorage.setItem('approFilters', JSON.stringify(filters));
        }

        function loadApproFiltersFromStorage() {
            const savedFilters = localStorage.getItem('approFilters');
            if (savedFilters) {
                const filters = JSON.parse(savedFilters);
                $('#filterNumero').val(filters.numero || '');
                $('#filterUser').val(filters.user || '');
                $('#filterMontant').val(filters.montant || '');
                $('#filterDateRange').val(filters.dateRange || '');
                // Mettre à jour le daterangepicker si une valeur est présente
                if (filters.dateRange) {
                    const parts = filters.dateRange.split(' - ');
                    if (parts.length === 2) {
                        const start = moment(parts[0], 'DD/MM/YYYY');
                        const end = moment(parts[1], 'DD/MM/YYYY');
                        if (start.isValid() && end.isValid()) {
                            $('#filterDateRange').data('daterangepicker').setStartDate(start);
                            $('#filterDateRange').data('daterangepicker').setEndDate(end);
                        }
                    }
                }
                return true;
            }
            return false;
        }

        function filterApprovisionnements() {
            const filterNumero = $('#filterNumero').val().toLowerCase();
            const filterUser = $('#filterUser').val().toLowerCase();
            const filterMontant = parseFloat($('#filterMontant').val());

            // Récupération de la plage de dates
            var dateRange = $('#filterDateRange').val() || '';
            var dateDebut = null, dateFin = null;
            if (dateRange) {
                var parts = dateRange.split(' - ');
                if (parts.length === 2) {
                    function parseDMY(str) {
                        if (!str) return null;
                        var p = str.split('/');
                        if (p.length === 3) {
                            var day = p[0];
                            var month = p[1];
                            var year = p[2];
                            if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
                                return year + '-' + month + '-' + day;
                            }
                        }
                        return null;
                    }
                    dateDebut = parseDMY(parts[0]);
                    dateFin = parseDMY(parts[1]);
                }
            }

            let visibleCount = 0;
            let totalUSD = 0, totalCDF = 0;

            $('#content_utilisateur tbody tr').each(function() {
                const $row = $(this);
                let showRow = true;

                const numeroValue = ($row.find('.numero-cell').data('numero') || '').toLowerCase();
                const userValue = ($row.find('.user-cell').data('user') || '').toLowerCase();
                const montantValue = parseFloat($row.find('.montant-cell').data('montant') || 0);
                // Utiliser data-date qui est en YYYY-MM-DD
                const dateValue = $row.find('.date-cell').data('date') || '';

                if (filterNumero && !numeroValue.includes(filterNumero)) showRow = false;
                if (showRow && filterUser && !userValue.includes(filterUser)) showRow = false;
                if (showRow && !isNaN(filterMontant) && Math.abs(montantValue - filterMontant) > 0.009) showRow = false;

                // Filtre par plage de dates
                if (showRow && dateDebut && dateFin) {
                    if (dateValue) {
                        if (dateValue < dateDebut || dateValue > dateFin) {
                            showRow = false;
                        }
                    } else {
                        showRow = false;
                    }
                }

                if (showRow) {
                    $row.show();
                    visibleCount++;
                    totalUSD += parseFloat($row.data('montant-usd')) || 0;
                    totalCDF += parseFloat($row.data('montant-cdf')) || 0;
                } else {
                    $row.hide();
                }
            });

            $('#approCount').text(visibleCount);
            $('#totalUsd').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdf').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            if (visibleCount === 0 && (filterNumero || filterUser || !isNaN(filterMontant) || dateRange)) {
                $('#msg').html('<i class="zmdi zmdi-info"></i> Aucun approvisionnement ne correspond aux critères de recherche');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 3000);
            }
        }

        function resetApproFilters() {
            // Remettre la date par défaut (aujourd'hui) comme dans les factures
            var today = moment();
            var todayStr = today.format('DD/MM/YYYY');
            $('#filterDateRange').val(todayStr + ' - ' + todayStr);
            // Mettre à jour le picker
            if ($('#filterDateRange').data('daterangepicker')) {
                $('#filterDateRange').data('daterangepicker').setStartDate(today);
                $('#filterDateRange').data('daterangepicker').setEndDate(today);
            }

            $('#filterNumero').val('');
            $('#filterUser').val('');
            $('#filterMontant').val('');

            saveApproFiltersToStorage();

            // Réafficher toutes les lignes
            $('#content_utilisateur tbody tr').show();
            const totalCount = $('#content_utilisateur tbody tr').length;
            $('#approCount').text(totalCount);

            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }

        function debouncedApproFilter() {
            clearTimeout(approFilterTimeout);
            approFilterTimeout = setTimeout(() => {
                filterApprovisionnements();
                saveApproFiltersToStorage();
            }, 300);
        }

        // Initialisation des événements de filtrage et du date range picker
        $(document).ready(function() {
            // Initialisation du Date Range Picker avec la date du jour par défaut
            var today = moment();
            var todayStr = today.format('DD/MM/YYYY');
            $('#filterDateRange').val(todayStr + ' - ' + todayStr);

            $('#filterDateRange').daterangepicker({
                autoUpdateInput: false,
                startDate: today,
                endDate: today,
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Appliquer',
                    cancelLabel: 'Annuler',
                    fromLabel: 'Du',
                    toLabel: 'Au',
                    customRangeLabel: 'Personnalisé',
                    weekLabel: 'S',
                    daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                },
                opens: 'left',
                ranges: {
                    'Aujourd\'hui': [moment(), moment()],
                    'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                    '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                    'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
                    'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Cette année': [moment().startOf('year'), moment().endOf('year')]
                }
            }, function(start, end, label) {
                var startStr = start.format('DD/MM/YYYY');
                var endStr = end.format('DD/MM/YYYY');
                $('#filterDateRange').val(startStr + ' - ' + endStr);
                filterApprovisionnements();
                saveApproFiltersToStorage();
            });

            $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                filterApprovisionnements();
                saveApproFiltersToStorage();
            });

            // Nombre total d'approvisionnements initial
            const totalAppro = $('#content_utilisateur tbody tr').length;
            $('#approCount').text(totalAppro);
            // Initialiser les totaux
            filterApprovisionnements(); // Cette fonction va calculer les totaux

            // Charger les filtres sauvegardés (s'ils existent, écrase la valeur par défaut)
            const hasSaved = loadApproFiltersFromStorage();
            if (!hasSaved) {
                // déjà initialisée avec aujourd'hui
            }
            // Appliquer les filtres (pour afficher uniquement la plage aujourd'hui par défaut)
            filterApprovisionnements();

            // Événements des autres filtres
            $('#filterNumero, #filterUser, #filterMontant').on('input change', function() {
                debouncedApproFilter();
            });

            // Réinitialisation
            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetApproFilters();
            });
        });

        // Sauvegarde automatique avant de quitter
        window.addEventListener('beforeunload', function() {
            saveApproFiltersToStorage();
        });

        // Réappliquer les filtres après chaque chargement AJAX (pour conserver l'état)
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_app_article'))) {
                setTimeout(() => {
                    const totalAppro = $('#content_utilisateur tbody tr').length;
                    $('#approCount').text(totalAppro);
                    loadApproFiltersFromStorage();
                    filterApprovisionnements();
                }, 200);
            }
        });
    </script>
@endsection
@endsection