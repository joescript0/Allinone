@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\User;
use App\Models\Activites;
use App\Models\Tables;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'LISTE DES INVITES')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

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
#bloc_3 button,
#bloc_4 button,
#liste,
#print,
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

/* ===== BOUTON IMPRIMER (VERT) ===== */
#print {
    background: var(--vert-gradient) !important;
    color: white !important;
}
#print:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
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

/* ===== BORDURE 1px SOLID #e2e8f0 SUR TOUS LES CHAMPS ===== */
.filter-group .form-control,
.filter-group input.form-control,
.filter-group select.form-control {
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    height: 36px !important;
    padding: 8px 12px !important;
    font-weight: 500;
    font-size: 0.85rem;
    background: #ffffff !important;
    transition: all 0.2s;
    box-sizing: border-box;
    width: 100% !important;
    line-height: 1.4;
    box-shadow: none !important;
    appearance: auto;
}

.filter-group .form-control:focus,
.filter-group select.form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    transform: translateY(-1px);
}

.filter-group select.form-control {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
    padding-right: 36px !important;
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

/* ========== MESSAGES STYLISÉS ========== */
#msg {
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

#msg:not(:empty) {
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

#msg.msg-success {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46 !important;
    border-left: 4px solid #10b981 !important;
}
#msg.msg-error {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b !important;
    border-left: 4px solid #ef4444 !important;
}
#msg.msg-info {
    background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important;
    color: #1e3a8a !important;
    border-left: 4px solid #3b82f6 !important;
}

@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
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
.table tbody td a i.zmdi-check-circle {
    color: #10b981;
}
.table tbody td a i.zmdi-close-circle {
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
.table tbody td a:hover i.zmdi-check-circle {
    color: #059669;
}
.table tbody td a:hover i.zmdi-close-circle {
    color: #b91c1c;
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

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
    #bloc_1, #bloc_3, #bloc_4 { padding: 1rem !important; }
}
@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_3, #bloc_4 { padding: 0.8rem !important; }
    #liste, #print, #resetFilters { padding: 4px 12px !important; font-size: 0.7rem; }
    .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; }
    .filter-group { width: 100%; min-width: 100%; }
    .filter-group .form-control { height: 34px !important; }
    .client-count-badge { font-size: 0.65rem; padding: 3px 10px; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
}
@media (max-width: 480px) {
    .content .container { padding: 0.3rem !important; }
    #bloc_1, #bloc_3, #bloc_4 { padding: 0.6rem !important; }
    h4 { font-size: 1.1rem; }
    #liste, #print, #resetFilters { padding: 3px 8px !important; font-size: 0.65rem; }
    .table thead th { font-size: 0.62rem; padding: 8px 4px !important; }
    .table tbody td { padding: 6px 8px !important; font-size: 0.7rem; }
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
                                <a id="print" class="btn-sm" href="">
                                    <i class="zmdi zmdi-print"></i> Imprimer
                                </a>
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Liste des invités</h6>
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
                        <input type="text" id="filterNom" class="form-control" placeholder="Nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-phone text-danger"></i> Téléphone</label>
                        <input type="text" id="filterPhone" class="form-control" placeholder="Téléphone...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Présence</label>
                        <select id="filterPresence" class="form-control">
                            <option value="all">Tous</option>
                            <option value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-view-list text-danger"></i> Table</label>
                        <input type="text" id="filterTable" class="form-control" placeholder="Nom de la table...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-link text-danger"></i> Relation</label>
                        <input type="text" id="filterRelation" class="form-control" placeholder="Relation...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-info text-danger"></i> Statut</label>
                        <select id="filterStatut" class="form-control">
                            <option value="all">Tous</option>
                            <option value="1">En attente</option>
                            <option value="2">Confirmé</option>
                            <option value="3">Refusé</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-home text-danger"></i> Dans la salle</label>
                        <select id="filterSalle" class="form-control">
                            <option value="all">Tous</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="filter-group" style="flex: 0.3;">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px; width: 100%;">
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
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Téléphone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Présence</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Table</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Relation</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Relation Autre</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Statut</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Dans la salle</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $canEdit = false;
                                        $canDelete = false;
                                        $isAdmin = false;
                                    
                                        if (Auth::check()) {
                                            $writes = Writes::where([
                                                'ressource_id' => $ressource_id_1 ?? 0,
                                                'groupe_id'    => $groupe_user_id ?? 0
                                            ])->get();
                                    
                                            if ($writes->count() > 0) {
                                                $canEdit   = (bool) $writes[0]->edit;
                                                $canDelete = (bool) $writes[0]->delete;
                                            }
                                    
                                            if (Auth::user()->role == 0) {
                                                $canEdit = true;
                                                $canDelete = true;
                                                $isAdmin = true;
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach ($listesdesinvites as $data)
                                        @php $i = $loop->iteration; @endphp
                                        <tr>
                                            <td style="padding-top:5px;padding-bottom:5px;" class="row-num">{{ $i }}</td>
                                            <td style="padding-top:5px;padding-bottom:5px;" class="nom-cell" data-nom="{{ $data->name }}">{{ $data->name }}</td>
                                            <td style="padding-top:5px;padding-bottom:5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                                    
                                            <td style="padding-top:5px;padding-bottom:5px;" class="presence-cell" data-presence="{{ $data->presence ?? '' }}">
                                                @if($data->presence == 'oui')
                                                    <span class="badge badge-success" style="background:#10b981; color:white; padding:4px 10px; border-radius:20px;">Oui</span>
                                                @elseif($data->presence == 'non')
                                                    <span class="badge badge-danger" style="background:#ef4444; color:white; padding:4px 10px; border-radius:20px;">Non</span>
                                                @else
                                                    <span class="badge badge-secondary" style="background:#6c757d; color:white; padding:4px 10px; border-radius:20px;">-</span>
                                                @endif
                                            </td>
                                    
                                            <td style="padding-top:5px;padding-bottom:5px;" class="table-cell">
                                                @php
                                                    $nomTable = 'Aucune';
                                                    if ($data->table_id != 0) {
                                                        $table = Tables::find($data->table_id);
                                                        $nomTable = $table->nom ?? 'Aucune';
                                                    }
                                                @endphp
                                                {{ $nomTable }}
                                            </td>
                                    
                                            <td style="padding-top:5px;padding-bottom:5px;" class="relation-cell" data-relation="{{ $data->relation ?? '' }}">{{ $data->relation ?: $data->relation_autre }}</td>
                                            <td style="padding-top:5px;padding-bottom:5px;" class="relation_autre-cell" data-relation_autre="{{ $data->relation_autre ?? '' }}">{{ $data->relation_autre ?? '' }}</td>
                                    
                                            <td style="padding-top:5px;padding-bottom:5px;" class="statut-cell" data-reponse="{{ $data->reponse ?? 1 }}">
                                                @php
                                                    $reponse = $data->reponse ?? 1;
                                                    $badgeColor = '#6c757d';
                                                    $label = 'En attente';
                                                    if ($reponse == 1) {
                                                        $badgeColor = '#f59e0b';
                                                        $label = 'En attente';
                                                    } elseif ($reponse == 2) {
                                                        $badgeColor = '#10b981';
                                                        $label = 'Confirmé';
                                                    } elseif ($reponse == 3) {
                                                        $badgeColor = '#ef4444';
                                                        $label = 'Refusé';
                                                    }
                                                @endphp
                                                <span class="badge" style="background:{{ $badgeColor }}; color:white; padding:4px 10px; border-radius:20px;">{{ $label }}</span>
                                            </td>
                                    
                                            <td style="padding-top:5px;padding-bottom:5px;" class="salle-cell" data-salle="{{ $data->dans_la_salle ?? 0 }}">
                                                @if(($data->dans_la_salle ?? 0) == 1)
                                                    <span class="badge badge-success" style="background:#10b981; color:white; padding:4px 10px; border-radius:20px;">Oui</span>
                                                @else
                                                    <span class="badge badge-danger" style="background:#ef4444; color:white; padding:4px 10px; border-radius:20px;">Non</span>
                                                @endif
                                            </td>
                                    
                                            <td style="text-align:center; padding-top:5px; padding-bottom:5px;">
                                                @auth
                                                    @if ($canEdit)
                                                        <a id="edit_{{ $i }}" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                    @else
                                                        <a id="edit_r{{ $i }}" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                    @endif
                                    
                                                    @if ($canDelete)
                                                        <a id="delete_{{ $i }}" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                    @else
                                                        <a id="delete_r{{ $i }}" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                    @endif
                                                    &nbsp;
                                    
                                                    @php
                                                        $canConfirm = ($reponse != 2 && $reponse != 3);
                                                    @endphp
                                                    <a id="confirm_{{ $i }}" href="#"
                                                       data-id="{{ $data->id }}"
                                                       data-reponse="{{ $reponse }}"
                                                       class="confirm-btn {{ $canConfirm ? '' : 'disabled-link' }}"
                                                       style="{{ $canConfirm ? '' : 'pointer-events:none; opacity:0.5;' }}"
                                                       title="{{ $canConfirm ? 'Confirmer / Refuser' : 'Déjà traité' }}">
                                                        <i class="zmdi zmdi-check-circle"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endauth
                                            </td>
                                        </tr>
                                    
                                        @auth
                                            <script>
                                                (function() {
                                                    @if ($canEdit)
                                                        $("#edit_{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            $.get("{{ url('/refresh_editinvite') }}", {
                                                                listesdesinvites_id: {{ $data->id }}
                                                            }, function(refresh_editutilisateur) {
                                                                $("#bloc_1").hide();
                                                                $("#bloc_3").show();
                                                                $("#bloc_3").html(refresh_editutilisateur);
                                                                $("#bloc_4").hide();
                                                            });
                                                        });
                                                    @else
                                                        $("#edit_r{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            $("#btn_refus").trigger("click");
                                                        });
                                                    @endif
                                    
                                                    @if ($canDelete)
                                                        $("#delete_{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element").html("{{ $data->name }}");
                                                            $("#data_id").html("{{ $data->id }}");
                                                            $("#btn_sup").trigger("click");
                                                        });
                                                    @else
                                                        $("#delete_r{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            $("#btn_refus").trigger("click");
                                                        });
                                                    @endif
                                    
                                                    $("#confirm_{{ $i }}").click(function(e) {
                                                        e.preventDefault();
                                                        var reponse = {{ $reponse }};
                                                        if (reponse == 2 || reponse == 3) {
                                                            showMsg('info', '<i class="zmdi zmdi-info"></i> Cette invitation a déjà été traitée.', 4000);
                                                            return;
                                                        }
                                                        $("#confirm_invite_id").val({{ $data->id }});
                                                        $("#confirmationModal").modal('show');
                                                    });
                                                })();
                                            </script>
                                        @endauth
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

<!-- ===== MODAL DE CONFIRMATION INVITATION ===== -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmer l'invitation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Choisissez l'action à effectuer pour cet invité :</p>
                <input type="hidden" id="confirm_invite_id" value="">
                <div class="text-center">
                    <button type="button" id="btnConfirmer" class="btn btn-success btn-block" style="margin-bottom:10px;">
                        <i class="zmdi zmdi-check"></i> Confirmer (accès autorisé)
                    </button>
                    <button type="button" id="btnRefuser" class="btn btn-danger btn-block">
                        <i class="zmdi zmdi-close"></i> Refuser (accès refusé)
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
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

<script>
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

    $("#link_49").addClass("active");

    // ========== FILTRES AVEC PERSISTANCE ==========
    let clientFilterTimeout;

    function saveClientFiltersToStorage() {
        const filters = {
            nom: $('#filterNom').val(),
            phone: $('#filterPhone').val(),
            presence: $('#filterPresence').val(),
            table: $('#filterTable').val(),
            relation: $('#filterRelation').val(),
            statut: $('#filterStatut').val(),
            salle: $('#filterSalle').val()
        };
        localStorage.setItem('clientFilters', JSON.stringify(filters));
    }

    function loadClientFiltersFromStorage() {
        const savedFilters = localStorage.getItem('clientFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#filterNom').val(filters.nom || '');
            $('#filterPhone').val(filters.phone || '');
            $('#filterPresence').val(filters.presence || 'all');
            $('#filterTable').val(filters.table || '');
            $('#filterRelation').val(filters.relation || '');
            $('#filterStatut').val(filters.statut || 'all');
            $('#filterSalle').val(filters.salle || 'all');
            return true;
        }
        return false;
    }

    function filterClients() {
        const filterNom = $('#filterNom').val().toLowerCase().trim();
        const filterPhone = $('#filterPhone').val().toLowerCase().trim();
        const filterPresence = $('#filterPresence').val();
        const filterTable = $('#filterTable').val().toLowerCase().trim();
        const filterRelation = $('#filterRelation').val().toLowerCase().trim();
        const filterStatut = $('#filterStatut').val();
        const filterSalle = $('#filterSalle').val();

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
            const phoneValue = ($row.find('.phone-cell').data('phone') || '').toLowerCase();
            const presenceValue = ($row.find('.presence-cell').data('presence') || '').toLowerCase();
            const tableValue = $row.find('.table-cell').text().toLowerCase().trim();
            const relationValue = ($row.find('.relation-cell').data('relation') || '').toLowerCase();
            const statutValue = $row.find('.statut-cell').data('reponse') + '';
            const salleValue = $row.find('.salle-cell').data('salle') + '';

            if (filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;
            if (showRow && filterPresence !== 'all' && presenceValue !== filterPresence) showRow = false;
            if (showRow && filterTable && !tableValue.includes(filterTable)) showRow = false;
            if (showRow && filterRelation && !relationValue.includes(filterRelation)) showRow = false;
            if (showRow && filterStatut !== 'all' && statutValue !== filterStatut) showRow = false;
            if (showRow && filterSalle !== 'all' && salleValue !== filterSalle) showRow = false;

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
        // La modale n'est plus affichée
    }

    function resetClientFilters() {
        $('#filterNom').val('');
        $('#filterPhone').val('');
        $('#filterPresence').val('all');
        $('#filterTable').val('');
        $('#filterRelation').val('');
        $('#filterStatut').val('all');
        $('#filterSalle').val('all');

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

        $('#filterNom, #filterPhone, #filterPresence, #filterTable, #filterRelation, #filterStatut, #filterSalle').on('input change', function() {
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
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            filterClients();
        }, 100);
    });

    // ========== IMPRESSION AVEC FILTRES ==========
    $("#print").click(function(e) {
        e.preventDefault();
        var params = {
            nom: $('#filterNom').val(),
            phone: $('#filterPhone').val(),
            presence: $('#filterPresence').val(),
            table: $('#filterTable').val(),
            relation: $('#filterRelation').val(),
            statut: $('#filterStatut').val(),
            salle: $('#filterSalle').val()
        };
        $.get("{{ url('/get_liste_invite') }}", params, function(response) {
            $("#bloc_1").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("")  }}' + response);
        });
    });

    // ===== SUPPRESSION =====
    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_deleteinvite') }}", {
            id: id,
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

    window.addEventListener('beforeunload', function() {
        saveClientFiltersToStorage();
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('deleteclient') || settings.url.includes('confirm_invite'))) {
            setTimeout(() => {
                const totalClients = $('#content_utilisateur tbody tr').length;
                $('#clientCount').text(totalClients);
                loadClientFiltersFromStorage();
                filterClients();
            }, 200);
        }
    });

    // ========== CONFIRMATION INVITATION ==========
    function traiterInvitation(action) {
        var id = $('#confirm_invite_id').val();
        if (!id) return;

        $.post("{{ url('/confirm_invite') }}", {
            id: id,
            action: action,
            _token: '{{ csrf_token() }}'
        }, function(html) {
            $('#confirmationModal').modal('hide');
            $('#content_utilisateur').html(html);

            saveClientFiltersToStorage();
            setTimeout(function() {
                loadClientFiltersFromStorage();
                filterClients();
            }, 100);
        }).fail(function() {
            showMsg('error', 'Erreur lors du traitement.', 4000);
            $('#confirmationModal').modal('hide');
        });
    }

    $('#btnConfirmer').click(function(e) {
        e.preventDefault();
        traiterInvitation('confirmer');
    });

    $('#btnRefuser').click(function(e) {
        e.preventDefault();
        traiterInvitation('refuser');
    });
</script>
@endsection
@endsection