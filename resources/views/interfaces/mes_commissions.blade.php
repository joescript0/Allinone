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
use App\Models\Achats;
use App\Models\Factureass;
use Illuminate\Support\Facades\Auth;

// ===== 🔥 Récupération optimisée des commissions dont la facture liée a etat = 0 =====
$achats_valides_ids = Achats::whereIn('facture_id', function ($query) {
        $query->select('id')
              ->from('factureasses') // ⚠️ adapte le nom exact de la table Factureass
              ->where('etat', 0);
    })->pluck('id');

$commisionsagents_filtrees = $commisionsagents->filter(function ($c) use ($achats_valides_ids) {
    return $achats_valides_ids->contains($c->achat_id);
});
?>

@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'MES COMMISSIONS')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES AUTRES PAGES
   ============================================================ */

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

#bloc_1 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

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

.table-responsive {
    overflow-x: auto;
    overflow-y: visible;
    border-radius: var(--border-radius-lg);
}

.table {
    width: 100%;
    min-width: 900px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    table-layout: auto;
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

.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd)  { background-color: #ffffff; }

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

#no-results-row td { color: #dc2626 !important; }
#no-results-row td i { color: #dc2626 !important; }

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

.filter-group { flex: 1; min-width: 150px; }

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

textarea.form-control { resize: vertical; height: 38px !important; }

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
    cursor: pointer;
}

.filters-container .filter-group .form-control {
    height: 36px !important;
    border-radius: 12px !important;
}

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
.badges-container .badge:hover { transform: translateY(-2px); }

.badge-dark { background: var(--bleu-nuit-gradient) !important; color: white !important; }
.badge-info { background: linear-gradient(135deg, #3B82F6, #2563eb) !important; color: white !important; }
.badge-danger { background: var(--rouge-gradient) !important; color: white !important; }
.badge-success { background: var(--vert-gradient) !important; color: white !important; }

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
    .filter-group { width: 100%; min-width: 100%; }
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Mes commissions</h6>
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

                <!-- ========== BADGES ========== -->
                <div class="badges-container">
                    <span class="badge badge-dark">
                        <i class="zmdi zmdi-view-list"></i> Total commissions : <span id="totalCommissionCount">0</span>
                    </span>
                    <span class="badge badge-info">
                        <i class="zmdi zmdi-money"></i> Total montants USD : <span id="totalMontantUsd">0,00</span> $
                    </span>
                    <span class="badge badge-info">
                        <i class="zmdi zmdi-money-box"></i> Total montants CDF : <span id="totalMontantCdf">0,00</span> CDF
                    </span>
                    <span class="badge badge-danger">
                        <i class="zmdi zmdi-money"></i> Total commissions USD : <span id="totalCommissionUsd">0,00</span> $
                    </span>
                    <span class="badge badge-danger">
                        <i class="zmdi zmdi-money-box"></i> Total commissions CDF : <span id="totalCommissionCdf">0,00</span> CDF
                    </span>
                    <span class="badge badge-success">
                        <i class="zmdi zmdi-money"></i> Bonus USD : <span id="bonusUsd">0,00</span> $
                    </span>
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
                                        {{-- 🔥 NOUVELLE COLONNE RÉDUCTION --}}
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Réduction</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Commission</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="no-results-row" style="display:none;">
                                        {{-- 🔥 colspan = 8 maintenant --}}
                                        <td colspan="8">
                                            <i class="zmdi zmdi-alert-circle"></i> Aucune commission trouvée
                                        </td>
                                    </tr>

                                    @php $i = 1; @endphp
                                    {{-- 🔥 On itère sur la collection FILTRÉE (facture etat = 0) --}}
                                    @foreach($commisionsagents_filtrees as $data)
                                        @php
                                            $clientNom  = Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A';
                                            $articleNom = Articles::where('id', $data->article_id)->first()['nom_article'] ?? 'N/A';
                                            $agentNom   = User::where('id', $data->user_id)->first()['name'] ?? 'N/A';

                                            // ===== 🔥 RÉCUPÉRATION DE L'ACHAT POUR PRENDRE LA RÉDUCTION =====
                                            $achat_lie = Achats::where('id', $data->achat_id)->first();
                                            $reduction = $achat_lie->reduction ?? 0;

                                            // Montant brut de base
                                            $montant_brut = $data->montant;

                                            // 🔥 Montant net = montant brut - réduction
                                            $montant_net = $montant_brut - $reduction;

                                            // 🔥 Commission recalculée sur le montant net (2%)
                                            $commission_net = $montant_net * 0.02;

                                            // ===== CONVERSION AVEC LE TAUX PROPRE À LA COMMISSION =====
                                            $taux_commission   = $data->taux ?? 2200;
                                            $devise_commission = $data->devise;

                                            if ($devise_commission == 0) {
                                                // Devise principale = USD
                                                $montant_aff    = number_format($montant_net, 2, ',', ' ') . ' USD (' . number_format($montant_net * $taux_commission, 2, ',', ' ') . ' CDF)';
                                                $commission_aff = number_format($commission_net, 2, ',', ' ') . ' USD (' . number_format($commission_net * $taux_commission, 2, ',', ' ') . ' CDF)';
                                                // 🔥 RÉDUCTION
                                                $reduction_aff  = number_format($reduction, 2, ',', ' ') . ' USD (' . number_format($reduction * $taux_commission, 2, ',', ' ') . ' CDF)';
                                            } else {
                                                // Devise principale = CDF
                                                $montant_aff    = number_format($montant_net, 2, ',', ' ') . ' CDF (' . number_format($montant_net / $taux_commission, 2, ',', ' ') . ' USD)';
                                                $commission_aff = number_format($commission_net, 2, ',', ' ') . ' CDF (' . number_format($commission_net / $taux_commission, 2, ',', ' ') . ' USD)';
                                                // 🔥 RÉDUCTION
                                                $reduction_aff  = number_format($reduction, 2, ',', ' ') . ' CDF (' . number_format($reduction / $taux_commission, 2, ',', ' ') . ' USD)';
                                            }

                                            // ===== DATE =====
                                            $date     = $data->created_at;
                                            $date_1   = explode(' ', $date);
                                            $date_aff = explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                        @endphp
                                        <tr id="row_{{ $data->id }}" data-commission-id="{{ $data->id }}"
                                            data-client="{{ $data->client_id }}"
                                            data-article="{{ $data->article_id }}"
                                            data-montant="{{ $montant_net }}"
                                            data-reduction="{{ $reduction }}"
                                            data-commission="{{ $commission_net }}"
                                            data-devise="{{ $data->devise }}"
                                            data-taux="{{ $data->taux ?? 2200 }}"
                                            data-date="{{ $data->created_at }}"
                                            data-userid="{{ $data->user_id }}">
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $clientNom }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $articleNom }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $montant_aff }}</td>
                                            {{-- 🔥 CELLULE RÉDUCTION --}}
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $reduction_aff }}</td>
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
        $("#link_55").addClass("active");

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
        // 🔥 Clé différente pour ne pas mélanger avec la page GESTION DES COMMISSIONS
        function saveCommissionFiltersToStorage() {
            var filters = {
                client: $('#filterClient').val(),
                article: $('#filterArticle').val(),
                montant_min: $('#filterMontantMin').val(),
                montant_max: $('#filterMontantMax').val(),
                dateRange: $('#filterDateRange').val(),
                userId: $('#filterUserId').val()
            };
            localStorage.setItem('mesCommissionFilters', JSON.stringify(filters));
        }

        function loadCommissionFiltersFromStorage() {
            var saved = localStorage.getItem('mesCommissionFilters');
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterClient').val(filters.client || 'all');
                $('#filterArticle').val(filters.article || 'all');
                $('#filterMontantMin').val(filters.montant_min || '');
                $('#filterMontantMax').val(filters.montant_max || '');
                $('#filterDateRange').val(filters.dateRange || '');
                $('#filterUserId').val(filters.userId || 'all');

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

            var dateRange = $('#filterDateRange').val() || '';
            var dateDebut = null, dateFin = null;
            if (dateRange) {
                var parts = dateRange.split(' - ');
                if (parts.length === 2) {
                    function parseDMY(str) {
                        if (!str) return null;
                        var p = str.split('/');
                        if (p.length === 3) {
                            var day = p[0], month = p[1], year = p[2];
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

            $('#content_commission tbody tr:not(#no-results-row)').each(function() {
                var $row = $(this);
                var show = true;

                var rowClient = $row.data('client');
                var rowArticle = $row.data('article');
                var rowMontant = parseFloat($row.data('montant') || 0);
                var rowDate = $row.data('date');
                var rowUserId = $row.data('userid');

                if (client !== 'all' && rowClient != client) show = false;
                if (show && article !== 'all' && rowArticle != article) show = false;
                if (show && !isNaN(montant_min) && rowMontant < montant_min) show = false;
                if (show && !isNaN(montant_max) && rowMontant > montant_max) show = false;
                if (show && userId !== 'all' && rowUserId != userId) show = false;

                if (show && dateDebut && dateFin) {
                    var cellDate = null;
                    if (rowDate) {
                        var datePart = rowDate.split(' ')[0];
                        if (datePart) cellDate = datePart;
                    }
                    if (cellDate) {
                        if (cellDate < dateDebut || cellDate > dateFin) show = false;
                    } else {
                        show = false;
                    }
                }

                if (show) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;

                    var montant = parseFloat($row.data('montant')) || 0;
                    var commission = parseFloat($row.data('commission')) || 0;
                    var devise = parseInt($row.data('devise')) || 0;
                    var taux = parseFloat($row.data('taux')) || 2200;
                    if (taux <= 0) taux = 2200;

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

            var seuilUsd = 500000;
            var tauxBonus = (totalCommissionUsd >= seuilUsd) ? 0.05 : 0.04;
            var bonusUsd = totalCommissionUsd * tauxBonus;
            var bonusCdf = totalCommissionCdf * tauxBonus;

            $('#totalCommissionCount').text(visibleCount);
            $('#totalMontantUsd').text(totalMontantUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalMontantCdf').text(totalMontantCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCommissionUsd').text(totalCommissionUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCommissionCdf').text(totalCommissionCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#bonusUsd').text(bonusUsd.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#bonusCdf').text(bonusCdf.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

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

            $('#content_commission tbody tr:not(#no-results-row)').show();
            var idx = 1;
            $('#content_commission tbody tr:not(#no-results-row):visible').each(function() {
                $(this).find('.row-num').text(idx);
                idx++;
            });
            $('#no-results-row').hide();
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

        $('#filterClient, #filterArticle, #filterMontantMin, #filterMontantMax, #filterUserId').on('input change', function() {
            debouncedFilter();
        });

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetCommissionFilters();
        });

        loadCommissionFiltersFromStorage();
        setTimeout(function() { filterCommissions(); }, 100);

        window.addEventListener('beforeunload', function() {
            saveCommissionFiltersToStorage();
        });
    });
</script>
@endsection
@endsection