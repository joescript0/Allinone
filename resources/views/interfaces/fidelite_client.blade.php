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
use Illuminate\Support\Facades\DB;

// ============================================================
// Calcul initial (premier chargement)
// ============================================================
$date_debut = request('date_debut');
$date_fin   = request('date_fin');

$facturesCandidates = DB::table('factureasses')->where('etat', 0)->where('client_id', '!=', 0);
if (!empty($date_debut)) $facturesCandidates->whereDate('created_at', '>=', $date_debut);
if (!empty($date_fin))   $facturesCandidates->whereDate('created_at', '<=', $date_fin);
$facturesCandidates = $facturesCandidates->get(['id', 'client_id', 'devise', 'taux']);
$factureIds = $facturesCandidates->pluck('id')->toArray();

$achatsParFacture = [];
if (!empty($factureIds)) {
    $rowsA = DB::table('achats')->whereIn('facture_id', $factureIds)
        ->select('facture_id', DB::raw('COUNT(*) AS nb'), DB::raw('SUM(total - COALESCE(reduction, 0)) AS montant'))
        ->groupBy('facture_id')->get();
    foreach ($rowsA as $r) $achatsParFacture[$r->facture_id] = ['nb' => (int)$r->nb, 'montant' => (float)$r->montant];
}

$paiementsParFacture = [];
if (!empty($factureIds)) {
    $rowsP = DB::table('detailpaiessachats')->whereIn('facture_id', $factureIds)
        ->get(['facture_id', 'devise_recu', 'montant_recu']);
    foreach ($rowsP as $r) $paiementsParFacture[$r->facture_id][] = $r;
}

$fideliteData = [];
foreach ($facturesCandidates as $fac) {
    if (!isset($achatsParFacture[$fac->id])) continue;
    $nbAchats = $achatsParFacture[$fac->id]['nb'];
    $montantTotal = $achatsParFacture[$fac->id]['montant'];
    if ($nbAchats === 0 || $montantTotal <= 0) continue;

    $taux = ($fac->taux > 0) ? (float)$fac->taux : 1;
    $payeUSD = 0;
    if (isset($paiementsParFacture[$fac->id])) {
        foreach ($paiementsParFacture[$fac->id] as $p) {
            if ((int)$p->devise_recu === 0) $payeUSD += (float)$p->montant_recu;
            else $payeUSD += ($taux > 0) ? ((float)$p->montant_recu / $taux) : 0;
        }
    }
    if ((int)$fac->devise === 0) { $montantUSD = $montantTotal; $montantCDF = $montantTotal * $taux; }
    else { $montantCDF = $montantTotal; $montantUSD = ($taux > 0) ? ($montantTotal / $taux) : 0; }
    if ($payeUSD + 0.01 < $montantUSD) continue;

    $cid = (int)$fac->client_id;
    if (!isset($fideliteData[$cid])) $fideliteData[$cid] = ['points' => 0, 'montant_usd' => 0, 'montant_cdf' => 0];
    $fideliteData[$cid]['points']      += $nbAchats;
    $fideliteData[$cid]['montant_usd'] += $montantUSD;
    $fideliteData[$cid]['montant_cdf'] += $montantCDF;
}

if (!function_exists('fmt_money')) {
    function fmt_money($v) { return number_format((float) $v, 2, ',', ' '); }
}
if (!function_exists('points_class')) {
    function points_class($p) {
        if ($p <= 0) return 'points-danger';
        if ($p < 10) return 'points-warning';
        return 'points-success';
    }
}
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'FIDELITE DES CLIENTS')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />

<style>
body { margin: 0; padding: 0; background: #f0f4f8; }
.content .container { max-width: 100% !important; width: 100%; padding: 0.5rem 1.5rem !important; margin: 0 auto; background: #f8fafc; }
.content .container .row { margin-left: 0; margin-right: 0; }
.content .container [class*="col-"] { padding-left: 0.75rem; padding-right: 0.75rem; }

:root {
    --bleu-nuit: #0a192f;
    --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
    --bleu-secondaire-gradient: linear-gradient(135deg, #2c5282, #1a365d);
    --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --vert-gradient: linear-gradient(135deg, #10b981, #059669);
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

#bloc_1, #bloc_3 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
}

h4 { font-weight: 700; border-left: 6px solid #e31b23; padding-left: 18px; margin-bottom: 16px; margin-top: 0; color: var(--bleu-nuit); }
h4 i.zmdi { background: var(--bleu-nuit-gradient); background-clip: text; -webkit-background-clip: text; color: transparent !important; }

.table-responsive { overflow-x: auto; overflow-y: visible; border-radius: var(--border-radius-lg); }
.table { width: 100%; min-width: 1100px; background: white; border-collapse: collapse; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-light); table-layout: auto; }
.table thead th { background: #E7F5FE !important; color: #0a192f; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; border-right: 1px solid #d0e2f2; white-space: normal; word-break: break-word; }
.table tbody tr { transition: all 0.15s ease; border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd) { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; cursor: default; }
.table tbody td { padding: 10px 12px !important; vertical-align: middle !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; word-break: break-word; border-bottom: 1px solid #eef2f6; line-height: 1.4; }

.points-cell { text-align: center; vertical-align: middle !important; }
.points-badge {
    display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px;
    border-radius: 50px; font-weight: 700; font-size: 0.85rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); transition: transform 0.15s ease;
}
.points-badge:hover { transform: translateY(-1px); }
.points-badge i.zmdi { font-size: 1.1rem; }
.points-badge.points-danger  { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #ef4444; }
.points-badge.points-warning { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #9a3412; border: 1px solid #f59e0b; }
.points-badge.points-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border: 1px solid #10b981; }

.montant-total-cell { text-align: right; vertical-align: middle !important; font-weight: 700; font-size: 0.85rem; white-space: nowrap; }
.montant-total-cell .m-amount-success { color: #059669; }
.montant-total-cell .m-amount-danger  { color: #dc2626; }

#bloc_1 button, #bloc_3 button, #liste, #communiquer, #resetFilters, .btn-primary, .btn-info, .btn-danger, .btn-success, .btn-secondary {
    display: inline-flex !important; align-items: center; justify-content: center; gap: 8px;
    padding: 6px 16px !important; font-weight: 600; font-size: 0.85rem; border-radius: 40px !important;
    transition: all 0.25s ease; border: none; cursor: pointer; text-decoration: none;
    box-shadow: var(--shadow-light); white-space: nowrap; line-height: 1.5;
}
#liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
#liste:hover, .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3); background: #2563eb !important; }
#communiquer, .btn-success { background: var(--vert-gradient) !important; color: white !important; }
#communiquer:hover, .btn-success:hover { transform: translateY(-2px); background: linear-gradient(135deg, #059669, #047857) !important; box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35); color: white !important; }
#resetFilters { background: #64748b !important; color: white !important; }
#resetFilters:hover { transform: translateY(-2px); background: #475569 !important; box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3); }

.filters-container {
    display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;
    background: white; padding: 0.8rem 1.2rem;
    border-radius: var(--border-radius-lg); box-shadow: var(--shadow-light); align-items: flex-end;
}
.filter-group { flex: 1; min-width: 150px; }
.filter-group label { font-weight: 600; margin-bottom: 4px; color: var(--bleu-nuit); font-size: 0.7rem; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
.filter-group .form-control { height: 36px; }

#filterDateRange {
    width: 100%; height: 36px; border-radius: 14px !important;
    border: 1px solid #e2e8f0 !important; padding: 8px 12px;
    font-weight: 500; font-size: 0.85rem; background: #fff; color: #1e2a3e;
}
#filterDateRange:focus { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important; outline: none; }
.daterangepicker { z-index: 10050 !important; }

.client-badges-container { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px 12px; margin-bottom: 15px; }
.client-count-badge { border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; color: white; }
.client-count-badge.count-badge { background: var(--rouge-gradient); }
.client-count-badge.points-badge { background: linear-gradient(135deg, #f59e0b, #d97706); }
.client-count-badge.montant-badge { background: linear-gradient(135deg, #10b981, #059669); }
.client-count-badge.periode-badge { background: linear-gradient(135deg, #2c5282, #1a365d); }

.form-control, input.form-control, select.form-control, textarea.form-control {
    width: 100% !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important; padding: 8px 12px !important; font-weight: 500; font-size: 0.85rem;
    transition: all 0.2s; box-sizing: border-box; height: 38px !important; line-height: 1.4;
}

[style*="background-color: rgba(0, 0, 0, 0.1)"] {
    background: #eef3fc !important; border-radius: 60px; padding: 10px 24px !important;
    margin-bottom: 20px; display: flex !important; flex-wrap: wrap; gap: 12px;
}

#communiquerModal .modal-content { border-radius: 20px; border: none; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.25); overflow: hidden; }
#communiquerModal .modal-header { background: linear-gradient(135deg, #10b981, #059669); color: white; border-bottom: none; padding: 1.1rem 1.5rem; }
#communiquerModal .modal-header .close { color: white; opacity: 0.9; text-shadow: none; }
#communiquerModal .modal-header .modal-title { font-weight: 700; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
#communiquerModal .badge-invoice { background: rgba(255,255,255,0.25); color: white; border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 6px; font-weight: bold; }
#communiquerModal .modal-body { background: #f8fafc; padding: 1.2rem 1.5rem; max-height: 78vh; overflow-y: auto; }
#communiquerModal .modal-footer { background: white; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }

.communiquer-filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding: 12px 14px; background: white; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); align-items: flex-end; }
.communiquer-filters .filter-group { flex: 1; min-width: 140px; }
.communiquer-filters .filter-group label { font-weight: 600; margin-bottom: 4px; color: #0a192f; font-size: 0.68rem; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
.communiquer-filters .filter-group label i { color: #10b981; }
.communiquer-filters .filter-group .form-control { height: 34px; font-size: 0.8rem; border-radius: 10px; border: 1px solid #e2e8f0; }
.communiquer-reset-btn { background: #64748b; color: white; border: none; border-radius: 40px; padding: 7px 16px; font-weight: 600; font-size: 0.78rem; cursor: pointer; }

#communiquer_table_wrapper { background: white; border-radius: var(--border-radius-lg); box-shadow: var(--shadow-light); overflow: hidden; margin-bottom: 16px; }
#communiquer_table { width: 100%; min-width: 600px; border-collapse: collapse; }
#communiquer_table thead th { background: #E7F5FE !important; color: #0a192f !important; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; text-align: left; }
#communiquer_table tbody td { padding: 10px 12px !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; border-bottom: 1px solid #eef2f6; text-align: left; }
#communiquer_table tbody tr:nth-child(even) { background-color: #f8fafc; }
#communiquer_table tbody tr:hover { background: #e6f0ff !important; }
#communiquer_table tbody td:first-child { text-align: center; }
#communiquer_table thead th:first-child { text-align: center; }

.communiquer-message-box { background: white; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 14px 16px; margin-bottom: 14px; }
.communiquer-message-box label { font-weight: 700; color: #0a192f; font-size: 0.8rem; text-transform: uppercase; display: block; margin-bottom: 8px; }
.communiquer-message-box textarea { width: 100%; min-height: 110px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 12px; font-size: 0.85rem; resize: vertical; }
.communiquer-count-selected { font-weight: 700; color: #10b981; font-size: 0.85rem; }
.communiquer-empty { text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 0.85rem; }
.communiquer-empty i { font-size: 2.5rem; display: block; margin-bottom: 8px; color: #cbd5e1; }
#communiquer_send_btn { background: linear-gradient(135deg, #10b981, #059669) !important; color: white !important; border: none; border-radius: 40px !important; padding: 8px 26px !important; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }

#communiquer_msg_zone { display: none; margin-top: 12px; padding: 10px 16px; border-radius: 12px; font-weight: 600; font-size: 0.82rem; align-items: center; gap: 8px; }
#communiquer_msg_zone.show { display: flex; }
#communiquer_msg_zone.info { background: #dbeafe; color: #1e3a8a; }
#communiquer_msg_zone.success { background: #d1fae5; color: #065f46; }
#communiquer_msg_zone.error { background: #fee2e2; color: #991b1b; }

.loading-fidelite { opacity: 0.6; transition: opacity 0.2s; }

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1 { padding: 0.8rem !important; }
    #liste, #communiquer, #resetFilters { padding: 4px 12px !important; font-size: 0.7rem; }
    .filters-container { flex-direction: column; gap: 8px; }
    .filter-group { width: 100%; min-width: 100%; }
    .table thead th, .table tbody td { font-size: 0.72rem; padding: 8px 6px !important; }
    .points-badge { padding: 4px 10px; font-size: 0.75rem; }
}
@media (max-width: 480px) {
    .points-badge { padding: 3px 8px; font-size: 0.7rem; gap: 4px; }
    .points-badge i.zmdi { font-size: 0.9rem; }
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
                                <a class="btn-success btn-sm" id="communiquer" href="#">
                                    <i class="zmdi zmdi-comment-text"></i> Communiquer
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; fidelité des Clients</h6>
            </div>

            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i>
                    Liste
                    <span class="client-count-badge count-badge" style="margin-left: 8px;">
                        <i class="zmdi zmdi-view-list"></i> <span id="clientCount">{{ $clients->count() }}</span>
                    </span>
                </h4>

                <!-- Filtres -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Nom</label>
                        <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
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
                        <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                        <input type="text" id="filterUser" class="form-control" placeholder="Rechercher par utilisateur...">
                    </div>

                    <div class="filter-group" style="min-width: 260px;">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Période (fidélité)</label>
                        <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période" readonly>
                    </div>

                    <div class="filter-group" style="flex: 0 0 auto; min-width: auto;">
                        <button type="button" id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Badges totaux -->
                @php
                    $sumPoints = 0; $sumUsd = 0; $sumCdf = 0;
                    foreach ($clients as $c) {
                        if (isset($fideliteData[$c->id])) {
                            $sumPoints += $fideliteData[$c->id]['points'];
                            $sumUsd    += $fideliteData[$c->id]['montant_usd'];
                            $sumCdf    += $fideliteData[$c->id]['montant_cdf'];
                        }
                    }
                @endphp
                <div class="client-badges-container">
                    <span class="client-count-badge periode-badge">
                        <i class="zmdi zmdi-calendar"></i>
                        Période :
                        <span id="periodeLabel">
                        @if($date_debut || $date_fin)
                            {{ $date_debut ?: 'début' }} → {{ $date_fin ?: 'aujourd\'hui' }}
                        @else
                            Toutes les dates
                        @endif
                        </span>
                    </span>
                    <span class="client-count-badge points-badge">
                        <i class="zmdi zmdi-card-giftcard"></i> Total points : <span id="totalPointsAffiches">{{ $sumPoints }}</span>
                    </span>
                    <span class="client-count-badge montant-badge">
                        <i class="zmdi zmdi-money"></i> Total USD : <span id="totalMontantUsdAffiche">{{ fmt_money($sumUsd) }}</span> $
                    </span>
                    <span class="client-count-badge montant-badge">
                        <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalMontantCdfAffiche">{{ fmt_money($sumCdf) }}</span> CDF
                    </span>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Type</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Activité</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Point de fidélité</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Montant total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($clients as $data)
                                    @php
                                        $activiteModele = \App\Models\Activites::find($data->activite_id);
                                        $activiteNom = $activiteModele ? $activiteModele->nom : 'Non renseignée';
                                        $points    = $fideliteData[$data->id]['points']      ?? 0;
                                        $montantUsd= $fideliteData[$data->id]['montant_usd'] ?? 0;
                                        $montantCdf= $fideliteData[$data->id]['montant_cdf'] ?? 0;
                                        $montantPositif = ($montantUsd > 0 || $montantCdf > 0);
                                        $pClass = points_class($points);
                                    @endphp
                                    <tr data-client-id="{{ $data->id }}">
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="nom-cell" data-nom="{{ $data->name }}">{{ $data->name }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="type-cell" data-type="{{ $data->type }}">
                                           @if ($data->type == 0) Privé @else Entreprise @endif
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="activite-cell"
                                            data-activite="{{ $data->activite_id }}"
                                            data-activite-nom="{{ $activiteNom }}">
                                            {{ $activiteNom }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ $data->user_id }}">
                                            @if (Auth::user()->id == $data->user_id) Vous
                                            @else {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }} @endif
                                        </td>

                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="points-cell" data-points="{{ $points }}">
                                            <span class="points-badge {{ $pClass }}">
                                                <i class="zmdi zmdi-card-giftcard"></i> {{ $points }}
                                            </span>
                                        </td>

                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-total-cell"
                                            data-montant-usd="{{ $montantUsd }}"
                                            data-montant-cdf="{{ $montantCdf }}">
                                            <span class="{{ $montantPositif ? 'm-amount-success' : 'm-amount-danger' }}">
                                                {{ fmt_money($montantCdf) }} CDF ({{ fmt_money($montantUsd) }} USD)
                                            </span>
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
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
        </div>
    </div>
</section>

<!-- Modales -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                <h5 class="modal-title" style="font-weight: 700; color: #0a192f;">
                    <i class="zmdi zmdi-info text-info" style="font-size: 24px; margin-right: 8px;"></i> Information
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

<div class="modal fade" id="communiquerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="max-width: 1100px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="zmdi zmdi-comment-text"></i> Communiquer avec les clients
                    <span class="badge-invoice"><i class="zmdi zmdi-view-list"></i> <span id="communiquer_total_count">0</span> client(s)</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="communiquer-filters">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account"></i> Nom</label>
                        <input type="text" id="communiquer_filterNom" class="form-control" placeholder="Nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-phone"></i> Téléphone</label>
                        <input type="text" id="communiquer_filterPhone" class="form-control" placeholder="Téléphone...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-settings"></i> Type</label>
                        <select id="communiquer_filterType" class="form-control">
                            <option value="all">Tous les types</option>
                            <option value="0">Privé</option>
                            <option value="1">Entreprise</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-toll"></i> Activité</label>
                        <select id="communiquer_filterActivite" class="form-control">
                            <option value="all">Toutes les activités</option>
                            @foreach ($activites as $activite)
                                <option value="{{ $activite->id }}">{{ $activite->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account"></i> Utilisateur</label>
                        <input type="text" id="communiquer_filterUser" class="form-control" placeholder="Utilisateur...">
                    </div>
                    <div class="filter-group" style="flex: 0 0 auto; min-width: auto;">
                        <button type="button" id="communiquer_resetFilters" class="communiquer-reset-btn">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>
                <div id="communiquer_table_wrapper">
                    <div style="max-height: 380px; overflow-y: auto; overflow-x: auto;">
                        <table id="communiquer_table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" id="communiquer_check_all"></th>
                                    <th>Nom</th><th>Téléphone</th><th>Type</th><th>Activité</th><th>Points</th><th>Montant</th>
                                </tr>
                            </thead>
                            <tbody id="communiquer_table_body">
                                <tr><td colspan="7" class="communiquer-empty"><i class="zmdi zmdi-info-outline"></i> Chargement...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="communiquer-message-box">
                    <label><i class="zmdi zmdi-comment-text" style="color: #10b981;"></i> Message à envoyer</label>
                    <textarea id="communiquer_message" placeholder="Completez le texte à envoyer"></textarea>
                </div>
                <div style="height:40px;" id="communiquer_msg_zone"></div>
            </div>
            <div class="modal-footer">
                <span class="communiquer-count-selected"><i class="zmdi zmdi-check-square"></i> <span id="communiquer_selected_count">0</span> sélectionné(s)</span>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 40px; padding: 8px 22px;">
                        <i class="zmdi zmdi-close"></i> Fermer
                    </button>
                    <button type="button" id="communiquer_send_btn" class="btn btn-success"><i class="zmdi zmdi-mail-send"></i> Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js-code')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
    // ============================================================
    // Fonctions utilitaires
    // ============================================================
    function formatMoney(v) {
        return (parseFloat(v) || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
    function pointsClassJS(p) {
        if (p <= 0) return 'points-danger';
        if (p < 10) return 'points-warning';
        return 'points-success';
    }
    // ✅ Normalise une chaîne pour la recherche :
    //   - Convertit en string
    //   - Met en minuscules
    //   - Supprime les accents
    //   - Remplace espaces multiples par un seul
    //   - Trim
    function normalize(str) {
        return String(str || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    $("#link_60").addClass("active");

    var currentDateDebut = "{{ $date_debut }}";
    var currentDateFin   = "{{ $date_fin }}";

    // ============================================================
    // Mise à jour des badges totaux (basé sur lignes visibles)
    // ============================================================
    function updateBadgesTotaux() {
        var totalPoints = 0, totalUsd = 0, totalCdf = 0;
        var visibleCount = 0;

        $('#content_utilisateur tbody tr').each(function() {
            var $row = $(this);
            // ✅ Détection robuste du masquage
            if ($row.css('display') === 'none') return;

            totalPoints += parseInt($row.find('.points-cell').attr('data-points')) || 0;
            totalUsd    += parseFloat($row.find('.montant-total-cell').attr('data-montant-usd')) || 0;
            totalCdf    += parseFloat($row.find('.montant-total-cell').attr('data-montant-cdf')) || 0;
            visibleCount++;
        });

        $('#clientCount').text(visibleCount);
        $('#totalPointsAffiches').text(totalPoints);
        $('#totalMontantUsdAffiche').text(formatMoney(totalUsd));
        $('#totalMontantCdfAffiche').text(formatMoney(totalCdf));
    }

    function updatePeriodeBadge(d1Fr, d2Fr) {
        if (d1Fr && d2Fr) {
            $('#periodeLabel').text(d1Fr + ' → ' + d2Fr);
        } else {
            $('#periodeLabel').text('Toutes les dates');
        }
    }

    // ============================================================
    // AJAX
    // ============================================================
    function loadFideliteAJAX(dateDebut, dateFin, d1Fr, d2Fr) {
        var $container = $('#content_utilisateur');
        $container.addClass('loading-fidelite');

        $.get("{{ route('get_fidelite_data') }}", {
            date_debut: dateDebut,
            date_fin: dateFin
        }, function(res) {
            $container.removeClass('loading-fidelite');

            $('#content_utilisateur tbody tr').each(function() {
                var $row = $(this);
                var cid = String($row.attr('data-client-id') || '');
                var d = res[cid] || { points: 0, montant_usd: 0, montant_cdf: 0 };

                var points     = parseInt(d.points) || 0;
                var montantUsd = parseFloat(d.montant_usd) || 0;
                var montantCdf = parseFloat(d.montant_cdf) || 0;

                var pClass = pointsClassJS(points);
                $row.find('.points-cell')
                    .attr('data-points', points)
                    .html('<span class="points-badge ' + pClass + '"><i class="zmdi zmdi-card-giftcard"></i> ' + points + '</span>');

                var positif = (montantUsd > 0 || montantCdf > 0);
                var mClass  = positif ? 'm-amount-success' : 'm-amount-danger';
                $row.find('.montant-total-cell')
                    .attr('data-montant-usd', montantUsd)
                    .attr('data-montant-cdf', montantCdf)
                    .html('<span class="' + mClass + '">' + formatMoney(montantCdf) + ' CDF (' + formatMoney(montantUsd) + ' USD)</span>');
            });

            updatePeriodeBadge(d1Fr, d2Fr);
            filterClients(); // recalcule les badges selon les filtres actuels
        }).fail(function() {
            $container.removeClass('loading-fidelite');
            $('#infoModal .modal-body').html('<i class="zmdi zmdi-close-circle text-danger"></i> Erreur lors du chargement des données.');
            $('#infoModal').modal('show');
        });
    }

    // ============================================================
    // DATERANGEPICKER
    // ============================================================
    $(document).ready(function() {
        var start = currentDateDebut ? moment(currentDateDebut, 'YYYY-MM-DD') : moment().subtract(1, 'year');
        var end   = currentDateFin   ? moment(currentDateFin,   'YYYY-MM-DD') : moment();

        $('#filterDateRange').daterangepicker({
            autoUpdateInput: false,
            startDate: start,
            endDate: end,
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
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                             'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
            },
            opens: 'left',
            ranges: {
                'Aujourd\'hui': [moment(), moment()],
                'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
                'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Cette année': [moment().startOf('year'), moment().endOf('year')],
                'Tout': [moment().subtract(10, 'years'), moment()]
            }
        });

        if (currentDateDebut && currentDateFin) {
            var d1Fr = moment(currentDateDebut).format('DD/MM/YYYY');
            var d2Fr = moment(currentDateFin).format('DD/MM/YYYY');
            $('#filterDateRange').val(d1Fr + ' - ' + d2Fr);
            updatePeriodeBadge(d1Fr, d2Fr);
        }

        $('#filterDateRange').on('apply.daterangepicker', function(ev, picker) {
            var d1Fr  = picker.startDate.format('DD/MM/YYYY');
            var d2Fr  = picker.endDate.format('DD/MM/YYYY');
            var d1Iso = picker.startDate.format('YYYY-MM-DD');
            var d2Iso = picker.endDate.format('YYYY-MM-DD');

            $(this).val(d1Fr + ' - ' + d2Fr);
            currentDateDebut = d1Iso;
            currentDateFin   = d2Iso;

            loadFideliteAJAX(d1Iso, d2Iso, d1Fr, d2Fr);
        });

        $('#filterDateRange').on('cancel.daterangepicker', function() {
            $(this).val('');
            currentDateDebut = '';
            currentDateFin   = '';
            loadFideliteAJAX('', '', '', '');
        });

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            $('#filterNom').val('');
            $('#filterPhone').val('');
            $('#filterType').val('all');
            $('#filterActivite').val('all');
            $('#filterUser').val('');
            $('#filterDateRange').val('');
            currentDateDebut = '';
            currentDateFin   = '';

            loadFideliteAJAX('', '', '', '');
        });
    });

    // ============================================================
    // ✅ FILTRES VISUELS — Version corrigée
    // ============================================================
    let clientFilterTimeout;

    function filterClients() {
        // ✅ Normalise chaque filtre
        const filterNom      = normalize($('#filterNom').val());
        const filterPhone    = normalize($('#filterPhone').val());
        const filterType     = $('#filterType').val();
        const filterActivite = $('#filterActivite').val();
        const filterUser     = normalize($('#filterUser').val());

        let visibleCount = 0;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            // ✅ Utilise .attr() au lieu de .data() → pas de cache jQuery
            const nomValue      = normalize($row.find('.nom-cell').attr('data-nom'));
            const phoneValue    = normalize($row.find('.phone-cell').attr('data-phone'));
            const typeValue     = String($row.find('.type-cell').attr('data-type') || '');
            const activiteValue = String($row.find('.activite-cell').attr('data-activite') || '');
            const userText      = normalize($row.find('.user-cell').text());

            // Filtre Nom
            if (filterNom && nomValue.indexOf(filterNom) === -1) showRow = false;

            // Filtre Téléphone
            if (showRow && filterPhone && phoneValue.indexOf(filterPhone) === -1) showRow = false;

            // Filtre Type
            if (showRow && filterType !== 'all' && typeValue !== filterType) showRow = false;

            // Filtre Activité
            if (showRow && filterActivite !== 'all' && activiteValue !== filterActivite) showRow = false;

            // Filtre Utilisateur
            if (showRow && filterUser && userText.indexOf(filterUser) === -1) showRow = false;

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(++visibleCount);
            } else {
                $row.hide();
            }
        });

        updateBadgesTotaux();
    }

    function debouncedClientFilter() {
        clearTimeout(clientFilterTimeout);
        clientFilterTimeout = setTimeout(filterClients, 200);
    }

    $(document).ready(function() {
        // ✅ Écoute "input" ET "change" + "keyup" pour saisie clavier
        $('#filterNom, #filterPhone, #filterUser').on('input keyup', function() {
            debouncedClientFilter();
        });
        $('#filterType, #filterActivite').on('change', function() {
            filterClients();
        });

        filterClients();
    });

    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_3").hide();
        setTimeout(filterClients, 100);
    });

    // ============================================================
    // MODALE COMMUNIQUER
    // ============================================================
    function showCommuniquerMsg(type, icon, text) {
        var $zone = $('#communiquer_msg_zone');
        $zone.removeClass('show info success error').addClass('show ' + type);
        $zone.html('<i class="zmdi ' + icon + '"></i> <span>' + text + '</span>');
        clearTimeout(window.__communiquerMsgTimeout);
        window.__communiquerMsgTimeout = setTimeout(function() { $zone.removeClass('show'); }, 6000);
    }

    function getCommuniquerAllRows() {
        var rows = [];
        $('#content_utilisateur tbody tr').each(function() {
            var $r = $(this);
            rows.push({
                id: String($r.attr('data-client-id') || ''),
                nom: String($r.find('.nom-cell').attr('data-nom') || ''),
                phone: String($r.find('.phone-cell').attr('data-phone') || ''),
                type: String($r.find('.type-cell').attr('data-type') || ''),
                activite: String($r.find('.activite-cell').attr('data-activite') || ''),
                activiteNom: String($r.find('.activite-cell').attr('data-activite-nom') || ''),
                user: String($r.find('.user-cell').attr('data-user') || ''),
                points: parseInt($r.find('.points-cell').attr('data-points')) || 0,
                montantUsd: parseFloat($r.find('.montant-total-cell').attr('data-montant-usd')) || 0,
                montantCdf: parseFloat($r.find('.montant-total-cell').attr('data-montant-cdf')) || 0
            });
        });
        return rows;
    }

    function getCommuniquerFilters() {
        return {
            nom: $('#communiquer_filterNom').val() || '',
            phone: $('#communiquer_filterPhone').val() || '',
            type: $('#communiquer_filterType').val() || 'all',
            activite: $('#communiquer_filterActivite').val() || 'all',
            user: $('#communiquer_filterUser').val() || '',
            _raw_nom: normalize($('#communiquer_filterNom').val()),
            _raw_phone: normalize($('#communiquer_filterPhone').val()),
            _raw_type: $('#communiquer_filterType').val(),
            _raw_activite: $('#communiquer_filterActivite').val(),
            _raw_user: normalize($('#communiquer_filterUser').val())
        };
    }

    function communiquerApplyFilters(rows, f) {
        return rows.filter(function(r) {
            if (f._raw_nom && normalize(r.nom).indexOf(f._raw_nom) === -1) return false;
            if (f._raw_phone && normalize(r.phone).indexOf(f._raw_phone) === -1) return false;
            if (f._raw_type && f._raw_type !== 'all' && String(r.type) !== String(f._raw_type)) return false;
            if (f._raw_activite && f._raw_activite !== 'all') {
                if (String(r.activite) !== String(f._raw_activite)
                    && normalize(r.activiteNom) !== normalize(f._raw_activite)) return false;
            }
            if (f._raw_user && normalize(r.user).indexOf(f._raw_user) === -1) return false;
            return true;
        });
    }

    function renderCommuniquerTable() {
        var all = getCommuniquerAllRows();
        var f = getCommuniquerFilters();
        var filtered = communiquerApplyFilters(all, f);

        $('#communiquer_total_count').text(filtered.length);

        var html = '';
        if (filtered.length === 0) {
            html = '<tr><td colspan="7" class="communiquer-empty"><i class="zmdi zmdi-info-outline"></i> Aucun client ne correspond aux filtres</td></tr>';
        } else {
            filtered.forEach(function(r) {
                var typeLabel = (String(r.type) === '0') ? 'Privé' : 'Entreprise';
                var pClass = pointsClassJS(r.points);
                var pointsHtml = '<span class="points-badge ' + pClass + '"><i class="zmdi zmdi-card-giftcard"></i> ' + r.points + '</span>';

                var positif = (r.montantUsd > 0 || r.montantCdf > 0);
                var mClass = positif ? 'm-amount-success' : 'm-amount-danger';
                var montantHtml = '<span class="' + mClass + '">' + formatMoney(r.montantCdf) + ' CDF (' + formatMoney(r.montantUsd) + ' USD)</span>';

                html += '<tr data-client-id="' + r.id + '">';
                html += '<td style="text-align:center;"><input type="checkbox" class="communiquer-row-check"></td>';
                html += '<td><b>' + r.nom + '</b></td>';
                html += '<td>' + r.phone + '</td>';
                html += '<td>' + typeLabel + '</td>';
                html += '<td>' + (r.activiteNom || 'Non renseignée') + '</td>';
                html += '<td style="text-align:center;">' + pointsHtml + '</td>';
                html += '<td style="text-align:right;font-weight:700;white-space:nowrap;">' + montantHtml + '</td>';
                html += '</tr>';
            });
        }
        $('#communiquer_table_body').html(html);
        $('#communiquer_check_all').prop('checked', false);
        updateCommuniquerSelectedCount();
    }

    function updateCommuniquerSelectedCount() {
        var n = $('#communiquer_table_body .communiquer-row-check:checked').length;
        $('#communiquer_selected_count').text(n);
    }

    $('#communiquer').on('click', function(e) {
        e.preventDefault();
        $('#communiquer_filterNom').val('');
        $('#communiquer_filterPhone').val('');
        $('#communiquer_filterType').val('all');
        $('#communiquer_filterActivite').val('all');
        $('#communiquer_filterUser').val('');
        $('#communiquer_message').val('');
        $('#communiquer_msg_zone').removeClass('show');
        renderCommuniquerTable();
        $('#communiquerModal').modal('show');
    });

    $(document).on('input keyup', '#communiquer_filterNom, #communiquer_filterPhone, #communiquer_filterUser', function() {
        renderCommuniquerTable();
    });
    $(document).on('change', '#communiquer_filterType, #communiquer_filterActivite', function() {
        renderCommuniquerTable();
    });
    $(document).on('click', '#communiquer_resetFilters', function() {
        $('#communiquer_filterNom').val('');
        $('#communiquer_filterPhone').val('');
        $('#communiquer_filterType').val('all');
        $('#communiquer_filterActivite').val('all');
        $('#communiquer_filterUser').val('');
        renderCommuniquerTable();
    });
    $(document).on('change', '#communiquer_check_all', function() {
        var checked = $(this).prop('checked');
        $('#communiquer_table_body .communiquer-row-check').prop('checked', checked);
        updateCommuniquerSelectedCount();
    });
    $(document).on('change', '.communiquer-row-check', function() {
        updateCommuniquerSelectedCount();
    });

    $(document).on('click', '#communiquer_send_btn', function() {
        var message = $('#communiquer_message').val().trim();
        if (!message) { showCommuniquerMsg('error', 'zmdi-close-circle', 'Veuillez saisir un message.'); return; }

        var filters = getCommuniquerFilters();
        var all = getCommuniquerAllRows();
        var filtered = communiquerApplyFilters(all, filters);
        if (filtered.length === 0) { showCommuniquerMsg('error', 'zmdi-close-circle', 'Aucun client pour ces filtres.'); return; }

        var checkedIds = [];
        $('#communiquer_table_body .communiquer-row-check:checked').each(function() {
            checkedIds.push($(this).closest('tr').data('client-id'));
        });

        var targets = filtered, mode = 'all';
        if (checkedIds.length > 0) {
            targets = filtered.filter(function(r) { return checkedIds.indexOf(r.id) !== -1; });
            mode = 'selected';
        }
        if (targets.length === 0) { showCommuniquerMsg('error', 'zmdi-close-circle', 'Aucun client à qui envoyer.'); return; }

        var messages = targets.map(function(r) {
            return { client_id: r.id, nom: r.nom, phone: r.phone, message: message };
        });

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Envoi...');
        showCommuniquerMsg('info', 'zmdi-time', 'Envoi en cours...');

        $.ajax({
            url: "{{ url('/send_communication_client') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", mode: mode, message: message,
                messages: JSON.stringify(messages),
                filters: JSON.stringify({
                    nom: filters.nom, phone: filters.phone, type: filters.type,
                    activite: filters.activite, user: filters.user
                })
            },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="zmdi zmdi-mail-send"></i> Envoyer');
                if (res && res.success) {
                    showCommuniquerMsg('success', 'zmdi-check-circle', res.message || (messages.length + ' message(s) envoyé(s)'));
                    setTimeout(function() {
                        $('#communiquerModal').modal('hide');
                        $('#communiquer_msg_zone').removeClass('show');
                    }, 2500);
                } else {
                    showCommuniquerMsg('error', 'zmdi-close-circle', 'Erreur : ' + (res && res.message ? res.message : 'inconnue'));
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="zmdi zmdi-mail-send"></i> Envoyer');
                showCommuniquerMsg('error', 'zmdi-close-circle', 'Erreur lors de l\'envoi.');
            }
        });
    });
</script>
@endsection
@endsection