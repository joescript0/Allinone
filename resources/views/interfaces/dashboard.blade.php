@extends('layouts.main')
@section('title', 'CONTROLAPP')
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

    /* Fond dynamique avec grain */
    .content {
        background: radial-gradient(circle at 10% 20%, #f0f4fc 0%, #e2e8f2 100%);
        min-height: 100vh;
        padding: 1.5rem 0;
        position: relative;
    }
    .content::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48cGF0aCBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDMiIGQ9Ik0wIDBoNDB2NDBIMHoiLz48L3N2Zz4=');
        pointer-events: none;
        opacity: 0.5;
        z-index: 0;
    }
    .container-fluid {
        position: relative;
        z-index: 1;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    /* --- CARTES STATS --- */
    .quick-stats__item {
        backdrop-filter: blur(12px);
        border-radius: 32px;
        padding: 1.8rem 1rem;
        transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        overflow: hidden;
    }
    .quick-stats__item::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(25deg);
        transition: all 0.6s;
        opacity: 0;
    }
    .quick-stats__item:hover::before {
        opacity: 0.6;
        transform: rotate(0deg);
    }
    .quick-stats__item:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 45px rgba(0,0,0,0.2);
    }
    .quick-stats__info i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        transition: transform 0.3s;
        animation: softPulse 2s infinite;
    }
    .quick-stats__item:hover i {
        transform: scale(1.1) translateY(-3px);
    }
    .quick-stats__info h2 {
        font-weight: 800;
        font-size: 2.4rem;
        background: linear-gradient(135deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .quick-stats__info small {
        font-weight: 600;
        letter-spacing: 2px;
        font-size: 0.7rem;
        background: rgba(0,0,0,0.3);
        padding: 0.2rem 0.8rem;
        border-radius: 30px;
        backdrop-filter: blur(4px);
    }
    .bg-blue { background: linear-gradient(145deg, #1e88e5, #0d47a1, #0a2e6e); background-size: 200% 200%; animation: gradientShift 8s ease infinite; }
    .bg-amber { background: linear-gradient(145deg, #ffb300, #ff8f00, #e65c00); background-size: 200% 200%; animation: gradientShift 8s ease infinite; }
    .bg-purple { background: linear-gradient(145deg, #8e24aa, #4a148c, #311b92); background-size: 200% 200%; animation: gradientShift 8s ease infinite; }
    .bg-red { background: linear-gradient(145deg, #e53935, #b71c1c, #8b0000); background-size: 200% 200%; animation: gradientShift 8s ease infinite; }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes softPulse {
        0% { text-shadow: 0 0 0px rgba(255,255,255,0); }
        50% { text-shadow: 0 0 8px rgba(255,255,255,0.6); }
        100% { text-shadow: 0 0 0px rgba(255,255,255,0); }
    }

    /* --- CARTES GRAPHIQUES --- */
    .card {
        background: #475569;
        border-radius: 48px;
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 25px 45px -12px rgba(0,0,0,0.2);
        transition: all 0.4s cubic-bezier(0.2, 0.8, 0.4, 1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 35px 50px -15px rgba(0,0,0,0.3);
        border-color: rgba(255,255,255,0.6);
    }
    .card::before {
        content: "";
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, rgba(33,150,243,0.6), rgba(156,39,176,0.6), rgba(33,150,243,0.6));
        border-radius: 50px;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.5s;
    }
    .card:hover::before {
        opacity: 1;
    }
    .card::after {
        content: "";
        position: absolute;
        top: -20%;
        right: -10%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(33,150,243,0.15), rgba(156,39,176,0));
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .card .card-body {
        position: relative;
        z-index: 2;
        padding: 2rem !important;
    }
    .card-title {
        font-weight: 800;
        font-size: 1.6rem;
        background: linear-gradient(120deg, #ffffff, #e0e7ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin: 0 0 0.5rem 0 !important;
        letter-spacing: -0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .card-subtitle {
        color: rgba(255,255,255,0.95);
        font-weight: 500;
        margin: 0 0 1.8rem 0 !important;
        border-left: 3px solid #2196f3;
        padding-left: 1rem;
        font-size: 0.85rem;
        backdrop-filter: blur(4px);
        letter-spacing: 0.2px;
    }
    .flot-chart {
        height: 260px !important;
        width: 100%;
        margin: 0.5rem 0 0.5rem 0 !important;
        filter: drop-shadow(0 8px 12px rgba(0,0,0,0.1));
        transition: all 0.3s;
    }
    .card:hover .flot-chart {
        filter: drop-shadow(0 12px 18px rgba(0,0,0,0.2));
    }
    .flot-chart-legends {
        text-align: center;
        margin-top: 1rem;
        color: rgba(255,255,255,0.9);
        font-size: 0.75rem;
        font-weight: 500;
    }
    .flot-chart canvas {
        border-radius: 24px;
        transition: transform 0.3s;
    }
    .card:hover .flot-chart canvas {
        transform: scale(1.01);
    }

    .quick-stats__item .progress-bar-glow {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(255,255,255,0.3);
    }
    .quick-stats__item .progress-bar-glow span {
        display: block;
        height: 100%;
        width: 0%;
        background: white;
        border-radius: 0 4px 4px 0;
        transition: width 1.2s ease-out;
    }

    /* Animations */
    .quick-stats__item, .card {
        opacity: 0;
        animation: floatIn 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards;
    }
    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(40px); filter: blur(5px); }
        100% { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    .quick-stats__item:nth-child(1) { animation-delay: 0.05s; }
    .quick-stats__item:nth-child(2) { animation-delay: 0.1s; }
    .quick-stats__item:nth-child(3) { animation-delay: 0.15s; }
    .quick-stats__item:nth-child(4) { animation-delay: 0.2s; }
    .card:first-child { animation-delay: 0.25s; }
    .card:last-child { animation-delay: 0.35s; }

    @media (max-width: 768px) {
        .quick-stats__item { padding: 1.2rem 0.5rem; margin-bottom: 1rem; }
        .card-body { padding: 1.2rem !important; }
        .flot-chart { height: 200px !important; }
        .card-title { font-size: 1.3rem; }
        .card-subtitle { margin-bottom: 1.2rem !important; }
    }
    @media (max-width: 576px) {
        .quick-stats__info h2 { font-size: 1.8rem; }
        .quick-stats__info i { font-size: 1.8rem; }
        .card-title { font-size: 1.1rem; }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Quick stats -->
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

                <!-- Graphiques -->
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

    // Données
    var alertes = @json($alertes_par_mois);
    var affectations = @json($affectations_par_mois);

    // Points
    var alertesPoints = alertes.map((val, idx) => [idx, val]);
    var affectationsPoints = affectations.map((val, idx) => [idx, val]);

    // Série en barres pour Alertes (en ROUGE)
    var serieBarres = [{
        label: 'Alertes',
        data: alertesPoints,
        color: '#e53935',        // contour rouge
        bars: {
            show: true,
            barWidth: 0.6,
            align: 'center',
            lineWidth: 1,
            fillColor: '#c62828'  // remplissage rouge foncé
        }
    }];

    // Série en lignes avec points pour Affectations
    var serieLignes = [{
        label: 'Affectations',
        data: affectationsPoints,
        color: '#4fc3f7',
        lines: { show: true, fill: 0.2, lineWidth: 3 },
        points: {
            show: true,
            radius: 6,
            fillColor: '#03a9f4',
            lineWidth: 2,
            symbol: 'circle'
        }
    }];

    // Options communes
    var optionsBase = {
        xaxis: {
            ticks: moisAbrev.map((m, i) => [i, m]),
            tickLength: 0,
            font: { color: '#fff', size: 11 }
        },
        yaxis: {
            min: 0,
            tickDecimals: 0,
            font: { color: '#fff', size: 11 }
        },
        grid: {
            backgroundColor: { colors: ['rgba(0,0,0,0.2)', 'rgba(0,0,0,0.1)'] },
            borderWidth: 0,
            color: 'rgba(255,255,255,0.3)',
            hoverable: true,
            clickable: true
        }
    };

    // Tracé du graphique à barres
    $.plot('.flot-bar-chart', serieBarres, optionsBase);

    // Tracé du graphique en lignes
    $.plot('.flot-line', serieLignes, optionsBase);

    // --- Tooltip personnalisé (uniquement sur les barres ou les points) ---
    $('body').append('<div id="custom-tooltip" style="position:absolute;display:none;background:rgba(0,0,0,0.85);color:#fff;padding:6px 12px;border-radius:8px;font-size:13px;font-family:Inter,sans-serif;pointer-events:none;z-index:1000;box-shadow:0 2px 10px rgba(0,0,0,0.3);backdrop-filter:blur(4px);border-left: 3px solid #e53935;"></div>');
    var tooltip = $('#custom-tooltip');

    function showTooltip(x, y, content) {
        tooltip.css({ top: y - 35, left: x + 12, display: 'block' }).html(content);
    }
    function hideTooltip() { tooltip.css('display', 'none'); }

    // Graphique Alertes (barres)
    $('.flot-bar-chart').bind('plothover', function(event, pos, item) {
        if (item && item.datapoint) {
            var idx = Math.round(item.datapoint[0]);
            if (idx >= 0 && idx < moisComplets.length) {
                showTooltip(pos.pageX, pos.pageY, moisComplets[idx] + ' : ' + item.datapoint[1]);
            } else { hideTooltip(); }
        } else { hideTooltip(); }
    }).on('mouseleave', hideTooltip);

    // Graphique Affectations (lignes + points)
    $('.flot-line').bind('plothover', function(event, pos, item) {
        if (item && item.datapoint) {
            var idx = Math.round(item.datapoint[0]);
            if (idx >= 0 && idx < moisComplets.length) {
                showTooltip(pos.pageX, pos.pageY, moisComplets[idx] + ' : ' + item.datapoint[1]);
            } else { hideTooltip(); }
        } else { hideTooltip(); }
    }).on('mouseleave', hideTooltip);

    // Légendes
    var totalAlertes = alertes.reduce((a,b) => a + b, 0);
    var maxAlertes = Math.max(...alertes);
    var totalAffectations = affectations.reduce((a,b) => a + b, 0);
    var maxAffectations = Math.max(...affectations);

    $('.flot-chart-legends--bar').html('<i class="fas fa-chart-bar"></i> 🔴 Alertes · Max: ' + maxAlertes + ' (Déc) · Total: ' + totalAlertes);
    $('.flot-chart-legends--line').html('<i class="fas fa-chart-line"></i> 📈 Affectations · Max: ' + maxAffectations + ' (Déc) · Total: ' + totalAffectations);
});

// Animation des compteurs
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
    const progressBars = document.querySelectorAll('.progress-bar-glow span');
    const values = [{{ $decisions->count() }}, {{ $articles->count() }}, 58778, 214];
    const maxVal = Math.max(...values, 100000);
    progressBars.forEach((bar, idx) => {
        let targetPercent = (values[idx] / maxVal) * 100;
        targetPercent = Math.min(targetPercent, 100);
        setTimeout(() => { bar.style.width = targetPercent + '%'; }, 200 + idx * 100);
    });
}
document.addEventListener('DOMContentLoaded', animateCountersAndBars);

$("#link_1").css("border-left", "1px solid rgb(33, 150, 243)");
$("#text_1").addClass("text-info");
$("#upload").click(function(e) {
    e.preventDefault();
    $("#dropzone-upload").trigger("click");
});
</script>
@endsection
@endsection
