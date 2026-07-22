<!-- Vendor styles -->
<link rel="stylesheet"  href="{{ asset('assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('assets/vendors/animate.css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/fullcalendar/fullcalendar.min.css') }}">
<link rel="icon" type="image/png" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233B82F6'%3E%3Cpath d='M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z'/%3E%3C/svg%3E" />
<meta name="description" content="ControlApp : Gestion de suivie des officiers au poste." />
<meta property="og:image" content="{{ asset('controlapp_1.png') }}" />
<meta property="og:description" content="ControlApp : Gestion de suivie des officiers au poste." />
<meta property="og:url" content="{{ url("") }}" />
<meta property="og:title" content="ControlApp : Gestion de suivie des officiers au poste." />
<link rel="stylesheet" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/nouislider/nouislider.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendors/rateyo/jquery.rateyo.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/fontawesome-free-7.2.0-web/css/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/fontawesome-free-7.2.0-web/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('connexion/vendor/animate/animate.css') }}">
<!-- App styles -->
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select-multiple.css') }}">
<link rel="stylesheet" href="{{ asset('assets/demo/css/demo.css') }}">
<!-- Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
<style>
#footer {
    width: 100%;
    position: absolute;
    bottom: 0;
}

* {
    font-family: Arial, Helvetica, sans-serif;
}

/* ========================
   Styles pour le visualiseur Excel
   ======================== */

/* Entourage rouge (clic manuel) */
.cell-highlight {
    box-shadow: 0 0 0 2px #d32f2f;
    border: 2px solid #d32f2f !important;
    background-color: #ffeaea !important;
}

/* Entourage orange (recherche) */
.cell-search {
    box-shadow: 0 0 0 2px #f57c00;
    border: 2px solid #f57c00 !important;
    background-color: #fff0db !important;
}

/* Animation pour la première cellule trouvée */
.first-found {
    animation: pulse 0.8s ease-in-out 2;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        background-color: #fff0db;
    }

    50% {
        transform: scale(1.02);
        background-color: #ffcc80;
    }

    100% {
        transform: scale(1);
        background-color: #fff0db;
    }
}

/* Survol des cellules */
.excel-table-wrapper td:hover {
    background-color: #fff3cd !important;
    cursor: pointer;
}

/* ========== SIDEBAR BLEU NUIT – SURVOL AVEC BACKGROUND ========== */
:root {
    --navy: #0a192f;
    --navy-light: #112240;
    --navy-border: #1e2f47;
    --red-dark: #7f1a1a;
    --red: #991b1b;
    --text-light: #e2e8f0;
    --text-muted: #94a3b8;
    --hover-bg: #1a2f4e;
    /* Fond visible au survol */
    --hover-color: var(--red-dark);
    /* Couleur du texte/icône au survol */
    --border-light: #2d3a4f;
    --transition: all 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

/* Sidebar principale – fond bleu nuit */
.sidebar {
    background: var(--navy) !important;
    border-right: 1px solid var(--navy-border) !important;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    width: 260px;
}

/* Scrollbar discrète */
.scrollbar-inner::-webkit-scrollbar {
    width: 3px;
}

.scrollbar-inner::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-inner::-webkit-scrollbar-thumb {
    background: #2d3a4f;
    border-radius: 10px;
}

/* ===== UTILISATEUR ===== */
.user {
    padding: 1rem 0.75rem 0.25rem 0.75rem;
}

.user__info {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--navy-light);
    border-radius: 28px;
    padding: 6px 12px !important;
    cursor: pointer;
    transition: var(--transition);
    border: 1px solid var(--navy-border);
}

.user__info:hover {
    background: #1a2f4e;
    transform: translateY(-1px);
}

.user__img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid var(--red-dark);
}

.user__name {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: white !important;
}

.user__email {
    font-size: 10px !important;
    font-weight: 500 !important;
    color: #a0aec0 !important;
}

/* ===== DROPDOWN ===== */
.dropdown-menu {
    border: none;
    border-radius: 24px;
    background: white;
    box-shadow: 0 10px 20px -8px rgba(0, 0, 0, 0.2);
    padding: 6px;
    margin-top: 8px;
    min-width: 190px;
}

.dropdown-item {
    border-radius: 16px;
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 500;
    color: #1e293b;
    transition: var(--transition);
}

.dropdown-item:hover {
    background: #f1f5f9;
    transform: translateX(3px);
}

.dropdown-item i {
    margin-right: 10px;
    font-size: 16px;
}

/* ===== NAVIGATION – SURVOL AVEC BACKGROUND VISIBLE ===== */
.navigation {
    list-style: none;
    padding: 0 8px;
    margin: 16px 0 0;
}

.navigation li {
    margin: 2px 0;
}

.navigation li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 12px !important;
    border-radius: 16px;
    font-size: 12px !important;
    font-weight: 500;
    color: var(--text-light) !important;
    transition: var(--transition);
    text-decoration: none;
}

/* Effet de survol avec background visible */
.navigation li a:hover {
    background: var(--hover-bg) !important;
    /* Fond bleu nuit clair */
    color: var(--hover-color) !important;
    /* Texte rouge sombre */
    transform: translateX(4px);
}

.navigation li a i {
    font-size: 18px;
    width: 24px;
    color: var(--text-muted);
    transition: var(--transition);
}

.navigation li a:hover i {
    color: var(--hover-color);
    /* Icône rouge sombre */
}

/* ===== SECTION ADMIN ===== */
.admin-tools-title {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--red-dark);
    margin: 20px 12px 8px 12px;
    padding-top: 12px;
    border-top: 1.5px solid var(--red-dark);
}

/* ========== MODALES MODERNISÉES ========== */
.modal .modal-content {
    border: none;
    border-radius: 32px;
    background: white;
    box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal .modal-header {
    padding: 20px 24px;
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
    border-bottom: 3px solid var(--red-dark);
}

.modal .modal-title {
    font-weight: 700;
    font-size: 1.2rem;
    color: white !important;
}

.modal .modal-title i {
    margin-right: 8px;
    color: var(--red-dark);
}

.modal .modal-body {
    padding: 24px;
    background: #fefefe;
}

.modal .modal-footer {
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid #eef2ff;
}

.modal .btn {
    border-radius: 40px;
    padding: 8px 24px;
    font-weight: 600;
    font-size: 12px;
    transition: var(--transition);
    border: none;
}

.modal .btn-info {
    background: linear-gradient(95deg, var(--navy), var(--navy-light));
    color: white;
}

.modal .btn-info:hover {
    background: var(--navy-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(10, 25, 47, 0.3);
}

.modal .btn-danger {
    background: var(--red-dark);
    color: white;
}

.modal .btn-danger:hover {
    background: var(--red);
    transform: translateY(-2px);
}

.modal iframe {
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modal .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
    font-size: 28px;
}

.modal .close:hover {
    opacity: 1;
    color: var(--red-dark);
}

/* ===== HEADER PRINCIPAL ===== */
.header {
    /* background-color: #800020 !important; */
    /* background-color: #004D00 !important; */
    /* border-bottom: 3px solid #6c757d !important; */
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 0 20px;
    min-height: 70px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* ===== LOGO ===== */
.header__logo {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.2;
}

.header__logo h1 {
    margin: 0;
    font-size: 1.2rem;
}

.header__logo h1 a {
    color: white !important;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.header__logo h1 a i {
    color: white !important;
}

.header__logo p {
    color: rgba(255, 255, 255, 0.9) !important;
    font-size: 0.65rem;
    margin: 0;
}

/* ===== MENU BURGER ===== */
.navigation-trigger {
    display: flex;
    align-items: center;
}

.navigation-trigger__line {
    background-color: white !important;
}

/* ===== TOP NAVIGATION ===== */
.top-nav {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
}

.top-nav li {
    margin-left: 20px;
}

.hidden-xl-up a {
    color: white !important;
}

/* ===== DROPDOWN UTILISATEUR (fond bleu nuit, icône rouge, texte blanc) ===== */
.user-dropdown {
    margin-left: auto;
    display: flex;
    align-items: center;
}

.user-dropdown>a {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #0a192f;
    /* Bleu nuit plein */
    border-radius: 40px;
    padding: 6px 16px;
    color: white !important;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.user-dropdown>a:hover {
    background: #0d1f3a;
    border-color: rgba(255, 255, 255, 0.3);
}

.user-dropdown>a span {
    font-size: 1rem;
    font-weight: 500;
    color: white !important;
}

.user-dropdown>a i {
    font-size: 1.3rem;
    color: #e74c3c !important;
    /* Icône rouge */
}

/* ===== DROPDOWN MENU - BOUTON QUITTER EN BLEU NUIT CLAIR ===== */
.user-dropdown .dropdown-menu {
    min-width: 120px;
    padding: 6px 0;
    border-radius: 12px;
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: none;
    margin-top: 8px;
}

.deconnexion-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background-color: #2c5a8c;
    /* Bleu nuit clair */
    color: white !important;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border-radius: 8px;
    margin: 4px 8px;
}

.deconnexion-link:hover {
    background-color: #1e3f62;
    /* Bleu légèrement plus foncé au survol */
    color: white !important;
}

.deconnexion-link i {
    font-size: 1rem;
    color: white !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .header {
        min-height: 60px;
        padding: 0 15px;
    }

    .header__logo h1 {
        font-size: 1rem;
    }

    .user-dropdown>a {
        padding: 4px 12px;
    }

    .user-dropdown>a span {
        font-size: 0.9rem;
    }

    .user-dropdown>a i {
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .header {
        min-height: 55px;
        padding: 0 12px;
    }

    .user-dropdown>a span {
        display: none;
    }

    .user-dropdown>a {
        padding: 4px 10px;
    }

    .user-dropdown>a i {
        font-size: 1.2rem;
        margin: 0;
    }

    .top-nav li {
        margin-left: 12px;
    }
}

/* Classes utilitaires */
.hidden-xl-up {
    display: none;
}

@media (max-width: 1199.98px) {
    .hidden-xl-up {
        display: block;
    }
}

.hidden-sm-down {
    display: block;
}

@media (max-width: 767.98px) {
    .hidden-sm-down {
        display: none;
    }
}


.progress-container {
    width: 100%;
    background-color: #f0f0f0;
    border-radius: 5px;
    overflow: hidden;
    margin-top: 10px;
}

.progress-bar {
    height: 5px;
    background-color: #32c787;
    width: 0%;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 12px;
    margin-left: 10px;
    color: #666;
}
</style>
