@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'TABLEAU DE BORD')
@section('body')

@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<style>
    /* --- GOOGLE FONTS --- */
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, .content, .card, .quick-stats__item {
        font-family: 'Inter', sans-serif;
    }

    /* Fond sobre et clair (style seconde page) */
    .content {
        background: #f1f5f9;
        min-height: 100vh;
        padding: 1.5rem 0;
        position: relative;
    }
    .content::before {
        display: none; /* on retire le grain de la première version */
    }

    .container-fluid {
        position: relative;
        z-index: 1;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    /* --- CARTES STATS : carrées, bordures colorées (structure inchangée) --- */
    .quick-stats__item {
        background: #fefefe;
        border-radius: 0px;
        padding: 1.8rem 1rem;
        transition: all 0.25s ease;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        position: relative;
        border-left: 6px solid;
        /* on garde le overflow: hidden pour la barre de progression */
        overflow: hidden;
    }
    .quick-stats__item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.2);
    }

    /* Les couleurs sont attribuées via les classes bg-* */
    .quick-stats__item.bg-blue {
        border-left-color: #2563eb;
        background: linear-gradient(135deg, #ffffff, #eef2ff);
    }
    .quick-stats__item.bg-amber {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, #ffffff, #fffbeb);
    }
    .quick-stats__item.bg-purple {
        border-left-color: #8b5cf6;
        background: linear-gradient(135deg, #ffffff, #f5f3ff);
    }
    .quick-stats__item.bg-red {
        border-left-color: #dc2626;
        background: linear-gradient(135deg, #ffffff, #fef2f2);
    }

    /* Icônes (au-dessus du titre, structure conservée) */
    .quick-stats__info i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: inline-block;
        transition: transform 0.2s;
    }
    .quick-stats__item:hover i {
        transform: scale(1.05);
    }
    .bg-blue i { color: #2563eb; }
    .bg-amber i { color: #f59e0b; }
    .bg-purple i { color: #8b5cf6; }
    .bg-red i { color: #dc2626; }

    .quick-stats__info h2 {
        font-weight: 800;
        font-size: 2.5rem;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .quick-stats__info small {
        color: #000000;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 5px;
        background: transparent;
        padding: 0;
    }

    /* Barre de progression (structure conservée) */
    .progress-bar-glow {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(0,0,0,0.08);
    }
    .progress-bar-glow span {
        display: block;
        height: 100%;
        width: 0%;
        border-radius: 0 4px 4px 0;
        transition: width 1.2s ease-out;
    }
    .bg-blue .progress-bar-glow span { background: #2563eb; }
    .bg-amber .progress-bar-glow span { background: #f59e0b; }
    .bg-purple .progress-bar-glow span { background: #8b5cf6; }
    .bg-red .progress-bar-glow span { background: #dc2626; }

    /* --- CARTES GRAPHIQUES (style seconde page) --- */
    .card {
        background: #ffffff;
        border-radius: 0px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 12px 24px -12px rgba(0, 0, 0, 0.15);
        transition: 0.25s ease;
        margin-bottom: 2rem;
        overflow: hidden;
        position: relative;
    }
    .card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: #3B82F6;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 36px -16px rgba(0, 0, 0, 0.25);
    }

    .card-body {
        padding: 1.6rem !important;
    }

    .card-title {
        font-weight: 800;
        font-size: 1.3rem;
        color: #0f172a;
        margin: 0 0 0.25rem 0 !important;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .card-title i {
        font-size: 1.5rem;
        color: #3b82f6;
    }

    .card-subtitle {
        color: #334155;
        border-left: 3px solid #3b82f6;
        padding-left: 0.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0 0 1.5rem 0 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .flot-chart {
        height: 260px !important;
        width: 100%;
        margin: 0.5rem 0;
    }

    .flot-chart-legends {
        text-align: left;
        margin-top: 1rem;
        background: #f1f5f9;
        border-radius: 30px;
        padding: 0.3rem 1.2rem;
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 600;
        color: #1e293b;
        border: 1px solid #cbd5e1;
    }

    /* Animations d’apparition (structure inchangée) */
    .quick-stats__item, .card {
        opacity: 0;
        animation: fadeSlideUp 0.4s ease forwards;
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .quick-stats__item:nth-child(1) { animation-delay: 0.05s; }
    .quick-stats__item:nth-child(2) { animation-delay: 0.1s; }
    .quick-stats__item:nth-child(3) { animation-delay: 0.15s; }
    .quick-stats__item:nth-child(4) { animation-delay: 0.2s; }
    .card:first-child { animation-delay: 0.25s; }
    .card:last-child { animation-delay: 0.3s; }

    /* Responsive */
    @media (max-width: 768px) {
        .quick-stats__item { padding: 1.2rem 0.5rem; }
        .quick-stats__info i { font-size: 2rem; }
        .quick-stats__info h2 { font-size: 1.8rem; }
        .flot-chart { height: 200px !important; }
        .card-title { font-size: 1.1rem; }
    }
    @media (max-width: 576px) {
        .quick-stats__info h2 { font-size: 1.5rem; }
        .quick-stats__info i { font-size: 1.6rem; }
        .quick-stats__info small { font-size: 0.7rem; }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Quick stats (structure HTML strictement conservée) -->
                <div class="row quick-stats">
                    <div class="col-sm-6 col-md-3">
                        <div class="quick-stats__item bg-blue">
                            <div class="quick-stats__info">
                                <i class="fas fa-box"></i>
                                <h2 class="counter" data-target="{{ $articles->count() }}">{{ $articles->count() }}</h2>
                                {{-- <small>Sites</small> --}}
                                <small>Articles</small>
                            </div>
                            <div class="progress-bar-glow"><span></span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="quick-stats__item bg-amber">
                            <div class="quick-stats__info">
                                <i class="fas fa-users"></i>
                                <h2 class="counter" data-target="{{ $utilisateurs->count() }}">{{ $utilisateurs->count() }}</h2>
                                <small>Utilisateurs</small>
                            </div>
                            <div class="progress-bar-glow"><span></span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="quick-stats__item bg-purple">
                            <div class="quick-stats__info">
                                <i class="fas fa-sitemap"></i>
                                <h2 class="counter" data-target="{{ $categories->count() }}">{{ $categories->count() }}</h2>
                                <small>Categories</small>
                            </div>
                            <div class="progress-bar-glow"><span></span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="quick-stats__item bg-red">
                            <div class="quick-stats__info">
                                <i class="fas fa-users"></i>
                                <h2 class="counter" data-target="{{ $clients->count() }}">{{ $clients->count() }}</h2>
                                <small>Clients</small>
                            </div>
                            <div class="progress-bar-glow"><span></span></div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques (structure strictement conservée) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><i class="fas fa-bell me-2"></i>Alerte</h4>
                                <h6 class="card-subtitle">Évolution mensuelle des alertes (Jan → Déc)</h6>
                                <div class="flot-chart flot-bar-chart"></div>
                                <div class="flot-chart-legends flot-chart-legends--bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><i class="fas fa-user-check me-2"></i>Affectation</h4>
                                <h6 class="card-subtitle">Évolution mensuelle des affectations (Jan → Déc)</h6>
                                <div class="flot-chart flot-line"></div>
                                <div class="flot-chart-legends flot-chart-legends--line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js-code')
<script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ asset('assets/vendors/flot.orderbars/jquery.flot.orderBars.js') }}"></script>
<script>
$(document).ready(function() {
    // Mois
    var moisComplets = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    var moisAbrev = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

    // Données (les mêmes que votre première version)
    var alertes = @json($alertes_par_mois);
    var affectations = @json($affectations_par_mois);

    var alertesPoints = alertes.map((val, idx) => [idx, val]);
    var affectationsPoints = affectations.map((val, idx) => [idx, val]);

    // Graphique à barres pour Alertes (style seconde page : rouge)
    $.plot('.flot-bar-chart', [{
        label: 'Alertes',
        data: alertesPoints,
        bars: {
            show: true,
            barWidth: 0.6,
            align: 'center',
            fillColor: '#dc2626',
            lineWidth: 0
        }
    }], {
        xaxis: {
            ticks: moisAbrev.map((m,i) => [i,m]),
            tickLength: 0,
            font: { color: '#334155', size: 11, weight: '500' }
        },
        yaxis: {
            min: 0,
            tickDecimals: 0,
            font: { color: '#334155', size: 10 }
        },
        grid: {
            borderWidth: 0,
            color: '#cbd5e1',
            hoverable: true,
            backgroundColor: '#ffffff'
        },
        colors: ['#dc2626']
    });

    // Graphique en lignes pour Affectations (style seconde page : bleu)
    $.plot('.flot-line', [{
        label: 'Affectations',
        data: affectationsPoints,
        lines: {
            show: true,
            fill: 0.2,
            lineWidth: 3,
            fillColor: 'rgba(37,99,235,0.1)'
        },
        points: {
            show: true,
            radius: 6,
            fillColor: '#ffffff',
            lineWidth: 2.5
        }
    }], {
        colors: ['#2563eb'],
        xaxis: {
            ticks: moisAbrev.map((m,i) => [i,m]),
            tickLength: 0,
            font: { color: '#334155', size: 11 }
        },
        yaxis: {
            min: 0,
            tickDecimals: 0,
            font: { color: '#334155', size: 10 }
        },
        grid: {
            borderWidth: 0,
            color: '#cbd5e1',
            hoverable: true,
            backgroundColor: '#ffffff'
        }
    });

    // --- Tooltip (style épuré seconde page) ---
    $('body').append('<div id="custom-tooltip" style="position:absolute;display:none;background:#0f172a;color:#f8fafc;padding:6px 14px;border-radius:30px;font-size:12px;font-weight:600;font-family:Inter;pointer-events:none;z-index:1000;box-shadow:0 10px 15px -3px rgba(0,0,0,0.3);border-left:3px solid #3b82f6;"></div>');
    var tooltip = $('#custom-tooltip');
    function showTooltip(x, y, text) { tooltip.css({ top: y-30, left: x+12, display:'block' }).html(text); }
    function hideTooltip() { tooltip.hide(); }

    $('.flot-bar-chart, .flot-line').bind('plothover', function(e, pos, item) {
        if(item && item.datapoint) {
            var idx = Math.round(item.datapoint[0]);
            if(idx>=0 && idx<moisComplets.length)
                showTooltip(pos.pageX, pos.pageY, `${moisComplets[idx]} : ${item.datapoint[1]}`);
            else hideTooltip();
        } else hideTooltip();
    }).on('mouseleave', hideTooltip);

    // Légendes (avec totaux et max)
    var totalAlertes = alertes.reduce((a,b)=>a+b,0);
    var maxAlertes = Math.max(...alertes);
    var totalAffect = affectations.reduce((a,b)=>a+b,0);
    var maxAffect = Math.max(...affectations);
    $('.flot-chart-legends--bar').html('<i class="fas fa-chart-bar"></i> 🔴 Alertes · Total: ' + totalAlertes + ' · Max: ' + maxAlertes);
    $('.flot-chart-legends--line').html('<i class="fas fa-chart-line"></i> 📈 Affectations · Total: ' + totalAffect + ' · Max: ' + maxAffect);

    // --- Animation des compteurs (structure conservée, correction de $decisions) ---
    function animateCountersAndBars() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            if (isNaN(target)) return;
            let current = 0;
            const increment = target / 60;
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.innerText = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });

        // Barres de progression (utilise les vraies valeurs de vos stats)
        const progressBars = document.querySelectorAll('.progress-bar-glow span');
        // On remplace l'ancien tableau ($decisions inexistant) par les vraies données
        const values = [
            {{ $articles->count() }},
            {{ $utilisateurs->count() }},
            {{ $categories->count() }},
            {{ $clients->count() }}
        ];
        const maxVal = Math.max(...values, 100000);
        progressBars.forEach((bar, idx) => {
            let targetPercent = (values[idx] / maxVal) * 100;
            targetPercent = Math.min(targetPercent, 100);
            setTimeout(() => { bar.style.width = targetPercent + '%'; }, 200 + idx * 100);
        });
    }
    document.addEventListener('DOMContentLoaded', animateCountersAndBars);

    // Activation du lien dans la sidebar (inchangé)
    $("#link_1").addClass("active");
    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    });
});
</script>
@endsection
@endsection
