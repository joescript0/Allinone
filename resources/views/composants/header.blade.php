<?php
use App\Models\appnames;

// Récupération du nom de l'application (identique à votre première version)
$nom_app = appnames::where('etat', 1)->first()["nom"] ?? 'APPLICATION';
?>

<style>
    /* Styles issus de la deuxième page */
    .header {
        padding: 0 15px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #3B82F6 !important;
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .header__logo h1 {
        margin: 0;
        font-size: 1.3rem;
        color: #0F172A;
    }
    .header__logo p {
        font-size: 9px;
        color: #64748B;
        margin: 0;
    }

    .user-dropdown .user-dropdown-trigger {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 30px;
        background-color: rgba(0, 0, 0, 0.05);
        transition: 0.2s;
    }
    .user-dropdown .user-dropdown-trigger:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    .user-avatar {
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .user-avatar i {
        font-size: 16px;
        color: #1E293B;
    }
    .user-name {
        font-size: 12px;
        color: #1E293B;
    }
    .online-dot {
        width: 8px;
        height: 8px;
        background-color: #2ecc71;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(46,204,113,0.5); }
        70% { box-shadow: 0 0 0 3px rgba(46,204,113,0); }
        100% { box-shadow: 0 0 0 0 rgba(46,204,113,0); }
    }
    .deconnexion-link, .deconnexion-link i {
        color: #1E293B !important;
        text-decoration: none;
    }
    .dropdown-menu {
        background-color: white !important;
        border: 1px solid #e2e8f0;
    }
    .dropdown-arrow {
        color: #1E293B !important;
    }
    @media (max-width: 768px) {
        .user-name, .online-dot { display: none; }
    }
    #btn_quitter {
        color: white !important;
    }
    #btn_quitter_texte {
        color: white !important;
    }
</style>

<header class="header">
    <!-- Déclencheur du menu latéral (identique) -->
    <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>

    <!-- Logo / nom de l'application (dynamique) -->
    <div class="header__logo">
        <h1 class="text-white" style="font-weight: bold;">
            <i class="fas fa-cubes"></i> <?= htmlspecialchars($nom_app) ?>
        </h1>
        <p><strong>ALL IN ONE</strong></p>
    </div>

    <!-- Éléments de navigation (cachés par défaut dans votre version) -->
    <ul class="top-nav">
        <li style="display: none;" class="hidden-xl-up">
            <a href="#" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a>
        </li>
        <li style="display: none;" class="dropdown hidden-xs-down">
            <a href="#" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-item theme-switch">
                    Theme Switch
                    <div class="btn-group btn-group-toggle btn-group--colors" data-toggle="buttons">
                        <label class="btn bg-green active"><input type="radio" value="green" autocomplete="off" checked></label>
                        <label class="btn bg-blue"><input type="radio" value="blue" autocomplete="off"></label>
                        <label class="btn bg-red"><input type="radio" value="red" autocomplete="off"></label>
                        <label class="btn bg-orange"><input type="radio" value="orange" autocomplete="off"></label>
                        <label class="btn bg-teal"><input type="radio" value="teal" autocomplete="off"></label>
                        <div class="clearfix mt-2"></div>
                        <label class="btn bg-cyan"><input type="radio" value="cyan" autocomplete="off"></label>
                        <label class="btn bg-blue-grey"><input type="radio" value="blue-grey" autocomplete="off"></label>
                        <label class="btn bg-purple"><input type="radio" value="purple" autocomplete="off"></label>
                        <label class="btn bg-indigo"><input type="radio" value="indigo" autocomplete="off"></label>
                        <label class="btn bg-brown"><input type="radio" value="brown" autocomplete="off"></label>
                    </div>
                </div>
                <a href="#" class="dropdown-item">Fullscreen</a>
                <a href="#" class="dropdown-item">Clear Local Storage</a>
            </div>
        </li>
    </ul>

    <!-- Zone utilisateur (design de la deuxième page) -->
    <div class="dropdown user-dropdown">
        <a href="#" class="user-dropdown-trigger" data-toggle="dropdown">
            <div class="user-avatar">
                <i class="zmdi zmdi-account"></i>
            </div>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars(Auth::user()->name ?? 'Invité') ?></span>
                <span class="online-dot"></span>
                <i class="zmdi zmdi-chevron-down dropdown-arrow" style="color: #1E293B !important;"></i>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a id="btn_quitter" data-toggle="modal" data-target="#deconnexion" href="#" class="deconnexion-link">
                <i id="btn_quitter_texte" class="zmdi zmdi-power"></i> Quitter
            </a>
        </div>
    </div>
</header>
