@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp

<?php
use App\Models\Writes;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Articles;
use App\Models\User;
use App\Models\commisionsagents;
use Illuminate\Support\Facades\Auth;
?>

@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'GESTION DES COMMISSIONS')
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
#bloc_1 {
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
    flex-wrap: wrap;
    gap: 8px;
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

/* ========== LIGNE "AUCUN RÉSULTAT" – SEUL LE TEXTE EST ROUGE ========== */
#no-results-row td {
    color: #dc2626 !important;
}
#no-results-row td i {
    color: #dc2626 !important;
}

/* ========== STYLE DES CHAMPS DE FILTRES – IDENTIQUE À LA PAGE FACTURES ========== */
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

/* ===== STYLES GLOBAUX POUR TOUS LES CHAMPS (identiques à Factures) ===== */
.form-control,
input.form-control,
select.form-control,
textarea.form-control {
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

/* Style spécifique pour les select avec flèche personnalisée */
select.form-control {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    cursor: pointer;
}

/* ===== SURCHARGE POUR LES CHAMPS DANS LE CONTENEUR DE FILTRES ===== */
.filters-container .filter-group .form-control {
    height: 36px !important;
    border-radius: 12px !important;
}

/* Bouton réinitialiser – harmonisé avec la page achats */
#resetFilters {
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
    background: #64748b !important;
    color: white !important;
}
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
}

/* ========== BADGES ========== */
.badges-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
    justify-content: flex-end;
    width: 100%;
}

.badges-container .badge {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow-light);
    transition: transform 0.15s;
}
.badges-container .badge:hover {
    transform: translateY(-2px);
}

/* Badge bleu nuit (pour le total commissions) */
.badge-dark {
    background: var(--bleu-nuit-gradient) !important;
    color: white !important;
}

.badge-info {
    background: linear-gradient(135deg, #3B82F6, #2563eb) !important;
    color: white !important;
}

.badge-danger {
    background: var(--rouge-gradient) !important;
    color: white !important;
}

.badge-success {
    background: var(--vert-gradient) !important;
    color: white !important;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
    #bloc_1 { padding: 1rem !important; }
}

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1 { padding: 0.8rem !important; }
    #resetFilters { padding: 4px 12px !important; font-size: 0.7rem; }
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
    .filter-group .form-control { height: 34px !important; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .badges-container { gap: 8px; }
    .badges-container .badge { font-size: 0.75rem; padding: 6px 12px; }
}

@media (max-width: 480px) {
    .content .container { padding: 0.3rem !important; }
    #bloc_1 { padding: 0.6rem !important; }
    h4 { font-size: 1.1rem; margin-bottom: 12px; }
    h4 i { font-size: 24px !important; }
    #resetFilters { padding: 3px 8px !important; font-size: 0.65rem; }
    .table thead th { font-size: 0.62rem; padding: 8px 4px !important; }
    .table tbody td { padding: 6px 8px !important; font-size: 0.7rem; }
    .badges-container .badge { font-size: 0.65rem; padding: 4px 10px; }
}
</style>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion des commissions</h6>
            </div>

            <!-- ======== BLOC UNIQUE : LISTE ======== -->
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-money-box text-info"></i>
                    Liste des commissions
                </h4>

                <!-- FILTRES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Client</label>
                        <select id="filterClient" class="form-control">
                            <option value="all">Tous les clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-shopping-cart text-danger"></i> Article</label>
                        <select id="filterArticle" class="form-control">
                            <option value="all">Tous les articles</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}">{{ $article->nom_article }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-money text-danger"></i> Montant min</label>
                        <input type="number" id="filterMontantMin" class="form-control" placeholder="Min" step="0.01">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-money text-danger"></i> Montant max</label>
                        <input type="number" id="filterMontantMax" class="form-control" placeholder="Max" step="0.01">
                    </div>
                    <!-- ===== CHAMP PÉRIODE UNIQUE (DATE RANGE PICKER) ===== -->
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                        <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-circle text-danger"></i> Utilisateur</label>
                        <select id="filterUserId" class="form-control">
                            <option value="all">Tous les utilisateurs</option>
                            @php $allUsers = \App\Models\User::all(); @endphp
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->matricule ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <button id="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- ========== BADGES (7 badges) ========== -->
                <div class="badges-container">
                    <!-- 1. Total commissions (bleu nuit) -->
                    <span class="badge badge-dark">
                        <i class="zmdi zmdi-view-list"></i> Total commissions : <span id="totalCommissionCount">0</span>
                    </span>
                    <!-- 2. Total montants USD (info) -->
                    <span class="badge badge-info">
                        <i class="zmdi zmdi-money"></i> Total montants USD : <span id="totalMontantUsd">0,00</span> $
                    </span>
                    <!-- 3. Total montants CDF (info) -->
                    <span class="badge badge-info">
                        <i class="zmdi zmdi-money-box"></i> Total montants CDF : <span id="totalMontantCdf">0,00</span> CDF
                    </span>
                    <!-- 4. Total commissions USD (danger) -->
                    <span class="badge badge-danger">
                        <i class="zmdi zmdi-money"></i> Total commissions USD : <span id="totalCommissionUsd">0,00</span> $
                    </span>
                    <!-- 5. Total commissions CDF (danger) -->
                    <span class="badge badge-danger">
                        <i class="zmdi zmdi-money-box"></i> Total commissions CDF : <span id="totalCommissionCdf">0,00</span> CDF
                    </span>
                    <!-- 6. Bonus USD (success) -->
                    <span class="badge badge-success">
                        <i class="zmdi zmdi-money"></i> Bonus USD : <span id="bonusUsd">0,00</span> $
                    </span>
                    <!-- 7. Bonus CDF (success) -->
                    <span class="badge badge-success">
                        <i class="zmdi zmdi-money-box"></i> Bonus CDF : <span id="bonusCdf">0,00</span> CDF
                    </span>
                </div>

                <!-- TABLEAU -->
                <div id="content_commission" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Article</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Commission</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ligne affichée quand aucun résultat – seul le texte est rouge -->
                                    <tr id="no-results-row" style="display:none;">
                                        <td colspan="7">
                                            <i class="zmdi zmdi-alert-circle"></i> Aucune commission trouvée
                                        </td>
                                    </tr>

                                    @php $i = 1; @endphp
                                    @foreach($commisionsagents as $data)
                                        @php
                                            $clientNom = Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A';
                                            $articleNom = Articles::where('id', $data->article_id)->first()['nom_article'] ?? 'N/A';
                                            $agentNom = User::where('id', $data->user_id)->first()['name'] ?? 'N/A';

                                            // ===== CONVERSION AVEC LE TAUX PROPRE À LA COMMISSION =====
                                            $taux_commission = $data->taux ?? 2200;
                                            $devise_commission = $data->devise; // 0 = USD, 1 = CDF
                                            $montant = $data->montant;
                                            $commission = $data->commision; // ← utilisation de $data->commision

                                            if ($devise_commission == 0) {
                                                // Devise principale = USD
                                                $montant_usd = $montant;
                                                $montant_cdf = $montant * $taux_commission;
                                                $commission_usd = $commission;
                                                $commission_cdf = $commission * $taux_commission;
                                                $montant_aff = number_format($montant_usd, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                                                $commission_aff = number_format($commission_usd, 2, ',', ' ') . ' USD (' . number_format($commission_cdf, 2, ',', ' ') . ' CDF)';
                                            } else {
                                                // Devise principale = CDF
                                                $montant_cdf = $montant;
                                                $montant_usd = $montant / $taux_commission;
                                                $commission_cdf = $commission;
                                                $commission_usd = $commission / $taux_commission;
                                                $montant_aff = number_format($montant_cdf, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                                                $commission_aff = number_format($commission_cdf, 2, ',', ' ') . ' CDF (' . number_format($commission_usd, 2, ',', ' ') . ' USD)';
                                            }

                                            // ===== LOGIQUE DE DATE IDENTIQUE À LA PAGE ACHATS =====
                                            $date = $data->created_at;
                                            $date_1 = explode(' ', $date);
                                            $date_aff = explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                        @endphp
                                        <tr id="row_{{ $data->id }}" data-commission-id="{{ $data->id }}"
                                            data-client="{{ $data->client_id }}"
                                            data-article="{{ $data->article_id }}"
                                            data-montant="{{ $data->montant }}"
                                            data-commission="{{ $data->commision }}"
                                            data-devise="{{ $data->devise }}"
                                            data-taux="{{ $data->taux ?? 2200 }}"
                                            data-date="{{ $data->created_at }}"
                                            data-userid="{{ $data->user_id }}">
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $clientNom }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $articleNom }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $montant_aff }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $commission_aff }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $date_aff }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $agentNom }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js-code')
{{-- Ajout des dépendances pour le Date Range Picker (comme dans la page Achats) --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

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
    $(document).ready(function() {
        // Activation du menu
        $("#link_54").addClass("active");

        // ======== INITIALISATION DU DATE RANGE PICKER (comme dans la page Achats) ========
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
                "Aujourd'hui": [moment(), moment()],
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
            filterCommissions();
            saveCommissionFiltersToStorage();
        });

        $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            filterCommissions();
            saveCommissionFiltersToStorage();
        });

        // ======== FILTRES (avec persistance localStorage) ========
        function saveCommissionFiltersToStorage() {
            var filters = {
                client: $('#filterClient').val(),
                article: $('#filterArticle').val(),
                montant_min: $('#filterMontantMin').val(),
                montant_max: $('#filterMontantMax').val(),
                dateRange: $('#filterDateRange').val(),
                userId: $('#filterUserId').val()
            };
            localStorage.setItem('commissionFilters', JSON.stringify(filters));
        }

        function loadCommissionFiltersFromStorage() {
            var saved = localStorage.getItem('commissionFilters');
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterClient').val(filters.client || 'all');
                $('#filterArticle').val(filters.article || 'all');
                $('#filterMontantMin').val(filters.montant_min || '');
                $('#filterMontantMax').val(filters.montant_max || '');
                $('#filterDateRange').val(filters.dateRange || '');
                $('#filterUserId').val(filters.userId || 'all');

                // Si une période est chargée, on met à jour le picker
                if (filters.dateRange) {
                    var parts = filters.dateRange.split(' - ');
                    if (parts.length === 2) {
                        var start = moment(parts[0], 'DD/MM/YYYY');
                        var end = moment(parts[1], 'DD/MM/YYYY');
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

        function filterCommissions() {
            var client = $('#filterClient').val();
            var article = $('#filterArticle').val();
            var montant_min = parseFloat($('#filterMontantMin').val());
            var montant_max = parseFloat($('#filterMontantMax').val());
            var userId = $('#filterUserId').val();

            // ===== GESTION DE LA PÉRIODE (comme dans la page Achats) =====
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

            var visibleCount = 0;
            var newIndex = 1;
            var totalMontantUsd = 0, totalMontantCdf = 0;
            var totalCommissionUsd = 0, totalCommissionCdf = 0;

            // Parcourir toutes les lignes de données (exclure la ligne "no-results")
            $('#content_commission tbody tr:not(#no-results-row)').each(function() {
                var $row = $(this);
                var show = true;

                var rowClient = $row.data('client');
                var rowArticle = $row.data('article');
                var rowMontant = parseFloat($row.data('montant') || 0);
                var rowDate = $row.data('date'); // format YYYY-MM-DD HH:ii:ss
                var rowUserId = $row.data('userid');

                if (client !== 'all' && rowClient != client) show = false;
                if (show && article !== 'all' && rowArticle != article) show = false;
                if (show && !isNaN(montant_min) && rowMontant < montant_min) show = false;
                if (show && !isNaN(montant_max) && rowMontant > montant_max) show = false;
                if (show && userId !== 'all' && rowUserId != userId) show = false;

                // ===== FILTRE PAR PÉRIODE (comme dans la page Achats) =====
                if (show && dateDebut && dateFin) {
                    var cellDate = null;
                    if (rowDate) {
                        var datePart = rowDate.split(' ')[0];
                        if (datePart) {
                            cellDate = datePart; // déjà au format YYYY-MM-DD
                        }
                    }
                    if (cellDate) {
                        if (cellDate < dateDebut || cellDate > dateFin) {
                            show = false;
                        }
                    } else {
                        show = false;
                    }
                }

                if (show) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;

                    // ===== RÉCUPÉRATION DES DONNÉES POUR LES TOTAUX =====
                    var montant = parseFloat($row.data('montant')) || 0;
                    var commission = parseFloat($row.data('commission')) || 0;
                    var devise = parseInt($row.data('devise')) || 0;
                    var taux = parseFloat($row.data('taux')) || 2200;
                    if (taux <= 0) taux = 2200;

                    // Conversion montant
                    if (devise === 0) {
                        totalMontantUsd += montant;
                        totalMontantCdf += montant * taux;
                        totalCommissionUsd += commission;
                        totalCommissionCdf += commission * taux;
                    } else {
                        totalMontantCdf += montant;
                        totalMontantUsd += montant / taux;
                        totalCommissionCdf += commission;
                        totalCommissionUsd += commission / taux;
                    }
                } else {
                    $row.hide();
                }
            });

            // Calcul du bonus
            var seuilUsd = 500000;
            var tauxBonus = (totalCommissionUsd >= seuilUsd) ? 0.05 : 0.04;
            var bonusUsd = totalCommissionUsd * tauxBonus;
            var bonusCdf = totalCommissionCdf * tauxBonus;

            // Mise à jour des badges
            $('#totalCommissionCount').text(visibleCount);
            $('#totalMontantUsd').text(totalMontantUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalMontantCdf').text(totalMontantCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCommissionUsd').text(totalCommissionUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCommissionCdf').text(totalCommissionCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#bonusUsd').text(bonusUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#bonusCdf').text(bonusCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            // Gestion de la ligne "aucun résultat"
            if (visibleCount === 0) {
                $('#no-results-row').show();
            } else {
                $('#no-results-row').hide();
            }
        }

        function resetCommissionFilters() {
            var today = moment();
            var todayStr = today.format('DD/MM/YYYY');
            $('#filterDateRange').val(todayStr + ' - ' + todayStr);
            if ($('#filterDateRange').data('daterangepicker')) {
                $('#filterDateRange').data('daterangepicker').setStartDate(today);
                $('#filterDateRange').data('daterangepicker').setEndDate(today);
            }

            $('#filterClient').val('all');
            $('#filterArticle').val('all');
            $('#filterMontantMin').val('');
            $('#filterMontantMax').val('');
            $('#filterUserId').val('all');

            saveCommissionFiltersToStorage();
            // Réafficher toutes les lignes
            $('#content_commission tbody tr:not(#no-results-row)').show();
            var total = $('#content_commission tbody tr:not(#no-results-row)').length;
            // Remettre les numéros
            var idx = 1;
            $('#content_commission tbody tr:not(#no-results-row):visible').each(function() {
                $(this).find('.row-num').text(idx);
                idx++;
            });
            // Cacher la ligne "aucun résultat"
            $('#no-results-row').hide();

            // Recalculer les totaux (appel filterCommissions pour mettre à jour les badges)
            filterCommissions();
        }

        var filterTimeout;
        function debouncedFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                filterCommissions();
                saveCommissionFiltersToStorage();
            }, 300);
        }

        // Événements des filtres (sauf le date range picker qui a ses propres événements)
        $('#filterClient, #filterArticle, #filterMontantMin, #filterMontantMax, #filterUserId').on('input change', function() {
            debouncedFilter();
        });

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetCommissionFilters();
        });

        // Initialisation
        var total = $('#content_commission tbody tr:not(#no-results-row)').length;
        var hasSaved = loadCommissionFiltersFromStorage();
        if (hasSaved) {
            setTimeout(function() { filterCommissions(); }, 100);
        } else {
            // Si pas de filtre sauvegardé, on filtre avec la période du jour
            setTimeout(function() { filterCommissions(); }, 100);
        }

        // Sauvegarde avant de quitter
        window.addEventListener('beforeunload', function() {
            saveCommissionFiltersToStorage();
        });
    });
</script>
@endsection
@endsection
