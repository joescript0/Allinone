@extends('layouts.main')
@section('title', 'CONTROLAPP')
@section('name', 'TABLEAU DE BORD SCANNER')
@section('body')

@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<style>
/* =============================================
   DESIGN PREMIUM - VERSION FINALE (STYLE PAGE 1)
   BOUTONS MODERNES & UNIFORMISÉS
   LIGNES DE TABLEAU RÉDUITES (version compacte)
   MESSAGE D'ERREUR/SUCCÈS TOTALEMENT CACHÉ PAR DÉFAUT
   PRISE EN CHARGE DU FORMULAIRE D'ÉDITION
   + FILTRES MODERNES AVEC PERSISTANCE
   + RECHERCHE PAR MATRICULE, TELEPHONE, SALAIRE
   + RESPONSIVE AVANCÉE (MOBILE, TABLETTE)
   + TOUS LES BOUTONS AU MÊME STYLE (LISTE, AJOUTER, ENREGISTRER, MODIFIER, ANNULER, RÉINITIALISER...)
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

/* ========== TABLEAU COMPACT : LIGNES RÉDUITES ========== */
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

/* ========== EN-TÊTE AVEC COULEUR D'ORIGINE ========== */
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

.table thead th:last-child {
    border-right: none;
}

/* --- Lignes du tableau : padding réduit --- */
.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #eef2f6;
}

.table tbody tr:hover {
    background: #f0f5fe !important;
}

.table tbody td {
    padding: 6px 8px !important;
    vertical-align: middle !important;
    font-weight: 500;
    font-size: 0.8rem;
    color: #1e2a3e;
    word-break: break-word;
    border-bottom: 1px solid #eef2f6;
    line-height: 1.3;
}

.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
}

/* Alignement image + texte */
.table tbody td .profile-thumb {
    vertical-align: middle;
    margin-right: 8px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.table tbody td .profile-thumb:hover {
    transform: scale(1.1);
}

/* ========== STYLE UNIQUE POUR TOUS LES BOUTONS (MODERNE, ARRONDI, OMBRE) ========== */
/* Applique le style de base à tous les boutons des sections gestion utilisateurs */
#bloc_1 button,
#bloc_2 button,
#bloc_3 button,
#bloc_4 button,
.action-buttons-group a,
.action-buttons-group button,
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

/* ========== MENU DÉROULANT FLOTTANT ========== */
.control-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-trigger {
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.dropdown-trigger:hover {
    background: #e0e7ff;
    transform: scale(1.05);
}

.dropdown-trigger i {
    font-size: 1.2rem;
    color: #475569;
}

.floating-dropdown-menu {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    min-width: 160px;
    z-index: 99999 !important;
    overflow: hidden;
    margin: 0;
    padding: 0;
    display: none;
}

.floating-dropdown-menu.show {
    display: block;
    animation: dropdownFadeIn 0.2s ease-out;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.floating-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    text-decoration: none;
    color: #1e293b;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    background: white;
    width: 100%;
    text-align: left;
}

.floating-dropdown-item:hover {
    background: #f1f5f9;
}

.floating-dropdown-item i {
    font-size: 1.1rem;
}

.floating-dropdown-item i.zmdi-edit {
    color: #2c7da0;
}

.floating-dropdown-item i.zmdi-delete {
    color: #e31b23;
}

/* ========== POPUP MODERN POUR PROFIL - IMAGE TAILLE NORMALE ========== */
.profile-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 100000;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.profile-modal.show {
    display: flex;
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.profile-modal-content {
    background: white;
    border-radius: 28px;
    max-width: 90vw;
    max-height: 90vh;
    width: auto;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    overflow: auto;
    animation: modalSlideUp 0.3s ease;
}

@keyframes modalSlideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.profile-modal-header {
    background: #3B82F6 !important;
    padding: 18px 20px;
    color: white !important;
    border-radius: 28px 28px 0 0;
}

.profile-modal-header h3 {
    margin: 0;
    font-weight: 700;
    font-size: 1.3rem;
    color: white !important;
    letter-spacing: 0.5px;
}

.profile-modal-body {
    padding: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8fafc;
}

.profile-modal-body img {
    max-width: 100%;
    max-height: 60vh;
    width: auto;
    height: auto;
    border-radius: 20px;
    object-fit: contain;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.profile-modal-footer {
    padding: 20px 25px 28px;
    background: white;
    border-radius: 0 0 28px 28px;
}

/* ========== BOUTON FERMER MODERNE ========== */
.profile-modal-footer button {
    background: var(--rouge-gradient);
    border: none;
    padding: 12px 32px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    position: relative;
    overflow: hidden;
}

.profile-modal-footer button:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.profile-modal-footer button:hover:before {
    width: 300px;
    height: 300px;
}

.profile-modal-footer button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

.profile-modal-footer button:active {
    transform: translateY(1px);
    transition: all 0.05s;
}

.profile-modal-footer button i {
    font-size: 1.2rem;
    transition: transform 0.2s;
}

.profile-modal-footer button:hover i {
    transform: rotate(90deg);
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

/* ========== HEADER ET TITRE ========== */
.header-with-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 8px;
    padding-bottom: 6px;
    border-bottom: 2px solid #eef2f6;
}

.header-with-buttons h6 {
    margin: 0;
    color: rgba(0, 0, 0, 0.6);
    font-size: 0.9rem;
}

.header-with-buttons .action-buttons-group {
    margin: 0;
}

/* --- Alignements image + texte --- */
.table tbody td {
    vertical-align: middle !important;
}

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

.profile-thumb:hover,
.profile-thumb:focus,
.profile-thumb:active {
    transform: scale(1.1);
    opacity: 0.9;
    cursor: pointer;
}

/* ========== RESPONSIVE : adaptation compacte sur mobile ========== */
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

    .header-with-buttons {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .action-buttons-group {
        width: 100%;
        justify-content: flex-start;
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
        padding: 4px 6px !important;
        font-size: 0.7rem;
        line-height: 1.3;
    }

    .dropdown-trigger {
        width: 26px;
        height: 26px;
    }

    .dropdown-trigger i {
        font-size: 0.9rem;
    }

    .floating-dropdown-menu {
        min-width: 130px;
    }

    .floating-dropdown-item {
        padding: 6px 10px;
        font-size: 0.7rem;
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

    .profile-modal-header h3 {
        font-size: 1.1rem;
    }

    .profile-modal-body {
        padding: 20px;
    }

    .profile-modal-footer button {
        padding: 10px 24px;
        font-size: 0.9rem;
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

    .header-with-buttons h6 {
        font-size: 0.8rem;
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
        padding: 4px 4px !important;
        font-size: 0.65rem;
        line-height: 1.2;
    }

    .profile-thumb {
        width: 20px;
        height: 20px;
    }

    .floating-dropdown-menu {
        min-width: 120px;
    }

    .floating-dropdown-item {
        padding: 5px 8px;
        font-size: 0.65rem;
    }

    .profile-modal-header h3 {
        font-size: 1rem;
    }

    .profile-modal-footer button {
        padding: 8px 20px;
        font-size: 0.85rem;
    }
}

/* ========== ANIMATIONS & DÉTAILS ========== */
@keyframes glow {
    0% {
        box-shadow: 0 0 0 0 rgba(227, 27, 35, 0.2);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(227, 27, 35, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(227, 27, 35, 0);
    }
}

.modal-header {
    background: var(--bleu-nuit-gradient);
}

input[required],
select[required],
textarea[required] {
    border-left: 3px solid #e31b23 !important;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr.highlight {
    background-color: #fff3cd !important;
    animation: highlightFlash 1s ease;
}

@keyframes highlightFlash {
    0% {
        background-color: #fff3cd;
    }
    100% {
        background-color: transparent;
    }
}

/* ========== MESSAGES STYLISÉS (SUCCÈS / ERREUR / INFO) ========== */
#msg,
#edit_msg {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 12px 24px;
    border-radius: 60px;
    font-weight: 600;
    font-size: 0.9rem;
    margin: 16px auto 0;
    width: fit-content;
    max-width: 90%;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 5px solid;
    background: white;
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Icône succès */
#msg .zmdi-check-circle,
#edit_msg .zmdi-check-circle {
    color: #10b981;
    font-size: 1.3rem;
}
#msg:has(.zmdi-check-circle),
#edit_msg:has(.zmdi-check-circle) {
    background: #ecfdf5;
    border-left-color: #10b981;
    color: #065f46;
}

/* Icône erreur */
#msg .zmdi-close-circle,
#edit_msg .zmdi-close-circle {
    color: #ef4444;
    font-size: 1.3rem;
}
#msg:has(.zmdi-close-circle),
#edit_msg:has(.zmdi-close-circle) {
    background: #fef2f2;
    border-left-color: #ef4444;
    color: #991b1b;
}

/* Icône info */
#msg .zmdi-info,
#edit_msg .zmdi-info {
    color: #3b82f6;
    font-size: 1.3rem;
}
#msg:has(.zmdi-info),
#edit_msg:has(.zmdi-info) {
    background: #eff6ff;
    border-left-color: #3b82f6;
    color: #1e3a8a;
}

/* Animation de disparition */
#msg.fade-out,
#edit_msg.fade-out {
    animation: fadeOut 0.3s ease forwards;
}
@keyframes fadeOut {
    to {
        opacity: 0;
        transform: translateY(-5px);
        visibility: hidden;
    }
}

/* ========== STYLES SPÉCIFIQUES POUR L'ÉDITION (BARRE DE PROGRESSION) ========== */
.editprogress-container {
    display: none;
    margin-top: 8px;
    background: #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    height: 6px;
    position: relative;
}
.editprogress-bar {
    width: 0%;
    height: 100%;
    background-color: #32c787;
    transition: width 0.3s ease;
}
.editprogress-text {
    font-size: 11px;
    color: #2d3748;
    margin-top: 4px;
    display: inline-block;
    font-weight: 600;
}

/* ========== BOUTON RÉINITIALISER DANS LES FILTRES ========== */
#resetFilters {
    margin-top: 22px; /* pour aligner avec les autres champs */
}
@media (max-width: 768px) {
    #resetFilters {
        margin-top: 0;
    }
}

/* =======================================================
   MODIFICATIONS DEMANDÉES : COULEUR #3B82F6 POUR 3 BOUTONS
   (Valider, Envoyer, Déconnexion flottant)
   ======================================================= */
#compareBtn,
.btn-confirm,
.floating-btn.secondary {
    background: #3B82F6 !important;
    color: white !important;
}
#compareBtn:hover,
.btn-confirm:hover,
.floating-btn.secondary:hover {
    background: #2563eb !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
}
/* S'assurer que les icônes sont blanches */
#compareBtn i,
.btn-confirm i,
.floating-btn.secondary i {
    color: white !important;
}

/* ========== STYLES PREMIUM - BOUTONS & COULEUR #800020 (STYLE PAGE 2) ========== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* Variables avec le bordeaux #800020 */
:root {
    --bordeaux: #800020;
    --bordeaux-fonce: #5a0015;
    --bordeaux-gradient: linear-gradient(135deg, #800020, #5a0015);
    --bleu-nuit: #0a192f;
    --bleu-nuit-clair: #112240;
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

body {
    background: #e9ecef;
    margin: 0;
    padding: 0;
}

.custom-container {
    background: #f8f9fa;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-light);
    border: 1px solid #eef2f6;
    transition: all 0.3s ease;
    margin-top: 20px;
    padding: 1.2rem 1rem !important;
}

h2,
.custom-container h2 {
    color: var(--bleu-nuit);
    font-weight: 700;
    letter-spacing: -0.02em;
    border-left: 6px solid var(--bordeaux);
    padding-left: 18px;
    margin-bottom: 0.75rem !important;
}

h2 i {
    color: var(--bordeaux);
}

.card {
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: none;
    border: 1px solid #eef2f6;
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    overflow: hidden;
    margin-bottom: 0;
}

.card-body {
    overflow-x: hidden;
    width: 100%;
    box-sizing: border-box;
    padding: 0.75rem !important;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-premium);
}

.card-title i {
    color: var(--bordeaux);
}

.card-title {
    margin-bottom: 0.75rem !important;
}

.upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 20px;
    padding: 15px 10px !important;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f8fafc;
    margin-bottom: 15px !important;
}

.upload-area:hover {
    border-color: var(--bordeaux);
    background: rgba(128, 0, 32, 0.05);
    transform: scale(1.02);
}

.upload-area i {
    font-size: 42px;
    color: var(--bordeaux);
    margin-bottom: 10px;
    transition: transform 0.2s;
}

.upload-area:hover i {
    transform: translateY(-5px);
}

.image-preview {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 16px;
    margin-top: 12px;
    display: none;
    border: 1px solid #e2e8f0;
    background: #f1f5f9;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.detection-status {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    max-width: 100%;
    white-space: normal;
    word-break: break-word;
    overflow-wrap: break-word;
    box-sizing: border-box;
    font-size: 0.8rem;
    margin-top: 12px;
    padding: 8px 12px;
    border-radius: 50px;
    font-weight: 600;
    text-align: center;
    line-height: 1.3;
}

.success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid var(--bordeaux);
}

.info {
    background: #e0f2fe;
    color: #075985;
    border-left: 4px solid #0ea5e9;
}

.url-container {
    margin-top: 12px;
    display: flex;
    align-items: center;
    background: #f8fafc;
    border-radius: 60px;
    border: 1px solid #e2e8f0;
    padding: 4px 12px;
    transition: all 0.2s;
    width: 100%;
    box-sizing: border-box;
}

.url-container:hover {
    border-color: var(--bordeaux);
    background: white;
}

.url-input {
    flex: 1;
    border: none;
    background: transparent;
    font-family: 'Inter', sans-serif;
    font-size: 0.8rem;
    color: #1e2a3e;
    padding: 6px 0;
    outline: none;
    min-width: 0;
}

.btn,
.btn-danger,
.btn-outline-secondary,
.btn-lg,
.btn-sm,
#compareBtn,
#resetCompareBtn,
#chronoStart,
#chronoPause,
#chronoReset,
.floating-btn,
.modal-buttons button,
#captureBtn,
#closeCameraBtn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 18px !important;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px !important;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: var(--shadow-light);
    white-space: nowrap;
}

/* Couleurs par défaut (pour les boutons qui ne sont pas modifiés) */
.btn-danger,
#chronoStart,
#captureBtn,
.floating-btn:first-child {
    background: var(--bordeaux-gradient) !important;
    color: white !important;
}

.btn-danger:hover,
#chronoStart:hover,
#captureBtn:hover,
.floating-btn:first-child:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(128, 0, 32, 0.4);
    background: linear-gradient(135deg, #9a0027, #6e001b) !important;
}

/* Les règles pour #compareBtn, .btn-confirm et .floating-btn.secondary sont
   déjà définies plus haut (couleur #3B82F6) et prennent le pas. */

.btn-outline-secondary,
#resetCompareBtn,
#chronoPause,
#chronoReset,
#closeCameraBtn,
.btn-cancel {
    background: #64748b !important;
    color: white !important;
    border: none;
}

.btn-outline-secondary:hover,
#resetCompareBtn:hover,
#chronoPause:hover,
#chronoReset:hover,
#closeCameraBtn:hover,
.btn-cancel:hover {
    background: #475569 !important;
    transform: translateY(-2px);
}

.btn:disabled,
.btn-danger:disabled,
#compareBtn:disabled {
    opacity: 0.6;
    transform: none;
    cursor: not-allowed;
}

.input-panel {
    background: #f1f5f9;
    border-radius: 60px;
    padding: 15px 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
}

.time-input-group {
    background: white;
    border-radius: 40px;
    padding: 4px 12px;
    box-shadow: var(--shadow-light);
}

.time-input-group label {
    color: var(--bordeaux);
    font-weight: 700;
    margin-right: 6px;
}

.time-input {
    width: 60px;
    text-align: center;
    font-weight: 600;
    border: none;
    background: transparent;
    color: var(--bleu-nuit);
    font-size: 1.1rem;
}

.timer-canvas-wrapper {
    position: relative;
    width: 260px;
    height: 260px;
    margin: 15px auto;
}

.digital-display {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--bleu-nuit);
    background: rgba(255, 255, 255, 0.9);
    padding: 6px 12px;
    border-radius: 50px;
    white-space: nowrap;
    box-shadow: var(--shadow-light);
}

.pause-field {
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 40px;
    border: 1px solid #e2e8f0;
}

.pause-field label {
    color: var(--bordeaux);
    font-weight: 600;
}

.pause-field input {
    background: white;
    border: 1px solid #cbd5e1;
    border-radius: 30px;
    padding: 4px 10px;
    font-weight: 600;
    font-size: 0.8rem;
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: var(--border-radius-xl);
    max-width: 450px;
    width: 90%;
    box-shadow: var(--shadow-premium);
    overflow: hidden;
}

.modal-header {
    background: var(--bordeaux-gradient);
    color: white;
    padding: 12px 20px;
}

.modal-header h3 {
    margin: 0;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: white;
    opacity: 0.8;
}

.modal-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 20px;
}

.modal-body textarea {
    width: 100%;
    border-radius: 16px;
    border: 1px solid #cbd5e1;
    padding: 10px;
    font-family: inherit;
    margin: 15px 0;
}

.camera-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: center;
    z-index: 1100;
}

.camera-modal.active {
    display: flex;
}

.camera-content {
    background: white;
    border-radius: var(--border-radius-xl);
    padding: 20px;
    max-width: 700px;
    width: 90%;
}

.video-container video {
    width: 100%;
    border-radius: 16px;
}

.result-match {
    font-size: 1.4rem;
    font-weight: 700;
    color: #10b981;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    text-align: center;
}

.result-no-match {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--bordeaux);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    text-align: center;
}

.result-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px;
    border-radius: 16px;
    border-left: 4px solid var(--bordeaux);
    word-break: break-word;
}

.confidence {
    font-size: 0.85rem;
    color: #334155;
    margin-top: 12px;
    word-break: break-word;
}

.model-status {
    background: var(--bleu-nuit);
    color: white;
    border-radius: 40px;
    padding: 6px 16px;
    display: inline-block;
    margin-top: 15px;
}

.loading {
    display: none;
    text-align: center;
    margin: 20px 0;
}

.loading.active {
    display: block;
}

.spinner {
    border: 4px solid #e2e8f0;
    border-top: 4px solid var(--bordeaux);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 12px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 768px) {
    .custom-container {
        padding: 1rem !important;
        margin-top: 15px;
    }

    .btn,
    .btn-danger {
        padding: 5px 12px !important;
        font-size: 0.7rem;
    }

    .digital-display {
        font-size: 1.1rem;
    }

    .time-input {
        width: 45px;
        font-size: 1rem;
    }

    .floating-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .detection-status {
        font-size: 0.7rem;
        padding: 6px 10px;
    }

    .result-match,
    .result-no-match {
        font-size: 0.95rem !important;
    }

    .timer-canvas-wrapper {
        width: 200px;
        height: 200px;
    }

    .card-body {
        padding: 0.75rem !important;
    }

    .upload-area {
        padding: 15px 12px;
    }
}

@media (max-width: 480px) {
    .custom-container h2 {
        font-size: 1.3rem;
    }

    .detection-status {
        font-size: 0.65rem;
        padding: 5px 8px;
    }

    .result-match,
    .result-no-match {
        font-size: 0.9rem;
    }
}

/* ===== RÉDUCTION DES ESPACES EXCESSIFS ===== */
.row.g-3 {
    --bs-gutter-x: 1rem;
    --bs-gutter-y: 0.75rem;
}

.mt-5,
.my-5 {
    margin-top: 1rem !important;
}

.mb-5,
.my-5 {
    margin-bottom: 0.75rem !important;
}

.mt-4,
.my-4 {
    margin-top: 0.75rem !important;
}

.mb-4,
.my-4 {
    margin-bottom: 0.5rem !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.gap-3 {
    gap: 0.75rem !important;
}

.mt-3 {
    margin-top: 0.5rem !important;
}

.mb-3 {
    margin-bottom: 0.5rem !important;
}

.p-4 {
    padding: 1rem !important;
}

.py-4 {
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
}

.footer {
    margin-top: 0.75rem !important;
    margin-bottom: 0.75rem !important;
}

.model-status {
    margin-top: 12px;
    margin-bottom: 0;
}

.section-separator {
    display: none;
}

.custom-container+.custom-container {
    margin-top: 0.5rem;
}

.bg-white {
    background-color: #fefefe !important;
}

.result-container {
    background: #f8f9fa !important;
}

/* ========== AJOUTS RESPONSIVES ========== */
/* Pour les très petits écrans (≤ 576px) */
@media (max-width: 576px) {
    .input-panel {
        padding: 12px 10px;
        gap: 8px;
        border-radius: 30px;
    }
    .time-input-group {
        padding: 2px 8px;
    }
    .time-input {
        width: 40px;
        font-size: 0.9rem;
    }
    .time-input-group label {
        font-size: 0.8rem;
        margin-right: 4px;
    }
    .timer-canvas-wrapper {
        width: 180px;
        height: 180px;
    }
    .digital-display {
        font-size: 0.9rem;
        padding: 4px 8px;
        white-space: nowrap;
    }
    .btn, .btn-danger, .btn-outline-secondary, .btn-lg {
        padding: 4px 12px !important;
        font-size: 0.7rem;
        gap: 4px;
    }
    .floating-buttons {
        bottom: 15px;
        left: 15px;
        right: 15px;
    }
    .floating-btn {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    .camera-content {
        padding: 15px;
        width: 95%;
    }
    .modal-content {
        width: 95%;
        margin: 10px;
    }
    .modal-header h3 {
        font-size: 1.2rem;
    }
    .modal-body {
        padding: 15px;
    }
    .card-title {
        font-size: 1.1rem;
    }
    .upload-area i {
        font-size: 32px;
    }
    .upload-area p {
        font-size: 0.8rem;
    }
    .image-preview {
        height: 160px;
    }
    .url-input {
        font-size: 0.7rem;
    }
    .result-match, .result-no-match {
        font-size: 1rem;
    }
    .confidence {
        font-size: 0.75rem;
    }

    /* --- RESPONSIVE POUR LES 3 BOUTONS DU CHRONOMÈTRE --- */
    .d-flex.justify-content-center.gap-2.mt-3.mb-3 {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
    .d-flex.justify-content-center.gap-2.mt-3.mb-3 .btn {
        width: 100% !important;
        white-space: nowrap;
    }
}

/* Ajustement pour les écrans très étroits (≤ 400px) */
@media (max-width: 400px) {
    .digital-display {
        font-size: 0.75rem;
        padding: 2px 6px;
    }
    .timer-canvas-wrapper {
        width: 150px;
        height: 150px;
    }
    .btn, .btn-danger, .btn-outline-secondary {
        padding: 3px 8px !important;
        font-size: 0.65rem;
    }
    .floating-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    .custom-container h2 {
        font-size: 1.1rem;
        padding-left: 12px;
    }
    .detection-status {
        font-size: 0.6rem;
        padding: 4px 6px;
    }
    .pause-field label, .pause-field input {
        font-size: 0.7rem;
    }
    .url-container i {
        font-size: 0.8rem;
    }
}

/* Correction pour les boutons qui pourraient déborder sur mobile */
@media (max-width: 768px) {
    .d-flex.flex-md-row.justify-content-center.gap-2 {
        flex-direction: column !important;
        align-items: stretch;
    }
    .d-flex.flex-md-row.justify-content-center.gap-2 .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        white-space: normal;
        word-break: keep-all;
    }
    .input-panel {
        flex-wrap: wrap;
        justify-content: center;
    }
    .pause-info {
        flex-direction: column;
        align-items: center;
    }
    .pause-field {
        width: 90%;
        text-align: center;
    }
    .camera-content .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    .camera-content .d-flex .btn {
        width: 100%;
    }
}

/* Pour que le canvas du timer ne déborde pas sur mobile */
.timer-canvas-wrapper canvas {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

/* Ajustement des modales pour qu'elles soient bien scrollables sur petit écran */
@media (max-height: 500px) {
    .modal-content, .camera-content {
        max-height: 90vh;
        overflow-y: auto;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<section class="content">
    <div class="container-fluid">
        <form id="form_add" action="#" method="post">
            @csrf
                <div class="row">
                <div class="col-lg-12">
                    <!-- SECTION COMPARATEUR DE VISAGES -->
                    <div class="custom-container p-3 p-md-4 mb-3">
                        <h2 class="mb-3"><i class="fas fa-sliders-h me-2"></i> Control <span id="message_du_poste"></span></h2>
                        <p style="display:none;" class="text-muted mb-3">Vérification d'identité par reconnaissance faciale</p>

                        <!-- Ligne des deux cartes avec mb-4 pour espacer des boutons -->
                        <div class="row g-3 mb-4">
                            <!-- Visage 1 -->
                            <div style="display:none;" class="col-md-12">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h3 style="font-weight: bold;" class="card-title text-center mb-3"><i class="fas fa-user me-2"></i> Visage de l'officier déjà enregistré
                                        </h3>
                                        <div class="upload-area" id="uploadArea1">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Cliquez ou déposez une image</p>
                                        </div>
                                        <div style="display:none;">
                                            <button class="btn btn-danger w-100 mb-3" id="cameraBtn1"><i class="fas fa-camera me-2"></i>Prendre une photo</button>
                                        </div>
                                        <input type="file" id="fileInput1" accept="image/*" style="display: none;">
                                        <div class="image-preview" id="preview1"></div>
                                        <div style="height:36px;" class="detection-status" id="status1"></div>
                                        <div class="url-container mt-3">
                                            <i class="fas fa-link"></i>
                                            <input type="text" class="url-input" id="url1" name="url1"
                                                placeholder="URL de l'image (modifiable)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Visage 2 : toute la carte est cliquable pour ouvrir la caméra -->
                            <div class="col-md-12">
                                <div class="card h-100" id="cardVisage2" style="cursor: pointer;">
                                    <div class="card-body">
                                        <h3 style="font-weight: bold;" class="card-title text-center mb-3"><i class="fas fa-camera me-2 text-info"></i> Visage de vérification
                                        </h3>
                                        <div style="display:none;" class="upload-area" id="uploadArea2">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Cliquez ou déposez une image</p>
                                        </div>
                                        <div style="display:none;">
                                            <button class="btn btn-danger w-100 mb-3" id="cameraBtn2"><i class="fas fa-camera me-2"></i>Prendre une photo</button>
                                        </div>
                                        <input type="file" id="fileInput2" accept="image/*" style="display: none;">
                                        <div style="height: 260px;" class="image-preview" id="preview2"></div>
                                        <div style="height:36px;" class="detection-status" id="status2"></div>
                                        <div style="display:none;" class="url-container mt-3">
                                            <i class="fas fa-link"></i>
                                            <input type="text" class="url-input" id="url2" name="url2"
                                                placeholder="URL de l'image (modifiable)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons : passage de mt-3 à mt-4 pour plus d'espace -->
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-2 mt-5">
                            <div id="content_btn_valider">
                                <button class="btn btn-danger btn-lg" id="compareBtn" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Pointer
                                </button>
                            </div>
                            <div style="display: none;">
                                <button class="btn btn-outline-secondary btn-lg" id="resetCompareBtn">
                                    <i class="fas fa-redo-alt me-2"></i>Réinitialiser
                                </button>
                            </div>
                        </div>

                        <div class="loading text-center mt-5" id="loading">
                            <div class="spinner"></div>
                            <p class="text-muted">Analyse des visages en cours...</p>
                        </div>

                        <div class="result-container mt-5 p-3 bg-light rounded-4 shadow-sm hidden" id="resultContainer">
                            <div id="resultMessage"></div>
                            <div class="confidence" id="confidence"></div>
                        </div>

                        <div style="display:none;" class="model-status text-center mx-auto mt-5" id="modelStatus">
                            <i class="fas fa-spinner fa-pulse"></i> Chargement des modèles...
                        </div>
                    </div>

                    <div class="section-separator"></div>

                    <!-- SECTION CHRONOMÈTRE CIRCULAIRE -->
                    <div style="display:none;" class="custom-container p-3 p-md-4">
                        <h2 class="mb-3"><i class="fas fa-hourglass-half me-2"></i>Chronomètre circulaire</h2>
                        <p class="text-muted mb-3">Saisissez le temps et lancez le compte à rebours</p>

                        <div class="input-panel">
                            <div class="time-input-group"><label>H</label><input type="number" id="hours" class="time-input"
                                    value="0" min="0" max="23"></div>
                            <div class="time-input-group"><label>M</label><input type="number" id="minutes"
                                    class="time-input" value="1" min="0" max="59"></div>
                            <div class="time-input-group"><label>S</label><input type="number" id="seconds"
                                    class="time-input" value="0" min="0" max="59"></div>
                            <div class="time-input-group"><label>C</label><input type="number" id="centiseconds"
                                    class="time-input" value="0" min="0" max="99"></div>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-3 mb-3">
                            <button class="btn btn-danger btn-lg" id="chronoStart"><i
                                    class="fas fa-play me-2"></i>Lancer</button>
                            <button class="btn btn-outline-secondary btn-lg" id="chronoPause" disabled><i
                                    class="fas fa-pause me-2"></i>Pause</button>
                            <button class="btn btn-outline-secondary btn-lg" id="chronoReset"><i
                                    class="fas fa-undo-alt me-2"></i>Reset</button>
                        </div>

                        <div class="timer-canvas-wrapper">
                            <canvas id="timerCanvas" width="260" height="260"></canvas>
                            <div class="digital-display" id="digitalTimer">00:00:00:00</div>
                        </div>

                        <div class="pause-info d-flex flex-wrap justify-content-center gap-2 mt-2">
                            <div class="pause-field"><label>Temps de pause :</label><input type="text" id="pauseTime"
                                    placeholder="--:--:--:--" readonly></div>
                            <div class="pause-field"><label>Couleur :</label><input type="text" id="pauseColor"
                                    placeholder="Couleur" readonly></div>
                        </div>
                    </div>

                    <div style="display:none;" class="footer text-center mt-3 text-muted">
                        Propulsé par <a href="https://github.com/justadudewhohacks/face-api.js" target="_blank"
                            style="color: var(--bordeaux);">face-api.js</a> | Design Premium
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modals -->
<div class="camera-modal" id="cameraModal">
    <div class="camera-content">
        <h3 class="text-center mb-4">Prendre une photo</h3>
        <div class="video-container bg-dark rounded-3 overflow-hidden mb-3">
            <video id="cameraVideo" autoplay playsinline></video>
        </div>
        <div class="d-flex gap-3 justify-content-center">
            <button class="btn btn-danger" id="captureBtn"><i class="fas fa-camera me-2"></i>Capturer</button>
            <button class="btn btn-outline-secondary" id="closeCameraBtn"><i
                    class="fas fa-times me-2"></i>Fermer</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="logoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-white">Confirmation de déconnexion</h3>
            <button class="modal-close" id="logoutModalClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
            <div class="modal-buttons d-flex gap-3 justify-content-center mt-3">
                <button class="btn-confirm" id="logoutConfirm">Oui</button>
                <button class="btn-cancel" id="logoutCancel">Non</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="alertModal">
    <div class="modal-content">
        <div class="modal-header text-white">
            <h3 class="text-white"><i class="fas fa-exclamation-triangle"></i> Confirmation d'alerte</h3>
            <button class="modal-close" id="alertModalClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color:black;">Voulez-vous vraiment déclencher une alerte ?</p>
            <label for="alertReason" class="fw-bold" style="color:black;"><i style="color:#5a0015;" class="fas fa-comment me-1"></i> Motif de l'alerte</label>
            <textarea id="alertReason" placeholder="Saisissez le motif (optionnel)" rows="3"></textarea>
            <div class="modal-buttons d-flex gap-3 justify-content-center">
                <button class="btn-confirm" id="alertConfirm">Envoyer <i class="fas fa-paper-plane"></i></button>
                <button class="btn-cancel" id="alertCancel">Annuler <i class="fas fa-times-circle"></i></button>
            </div>
            <p id="message_alerte"></p>
        </div>
    </div>
</div>

<div class="floating-buttons"
    style="position: fixed; bottom: 20px; left: 20px; right: 20px; display: flex; justify-content: space-between; z-index: 1050; pointer-events: none;">
    <button class="floating-btn" id="alertBtn" title="Alerte"
        style="pointer-events: auto; width: 50px; height: 50px; border-radius: 50%;"><i
            class="fas fa-exclamation-triangle"></i></button>
    <button class="floating-btn secondary" data-toggle="modal" data-target="#deconnexion"
        style="pointer-events: auto; width: 50px; height: 50px; border-radius: 50%;"><i
            class="fas fa-sign-out-alt text-white"></i></button>
</div>

@section('js-code')
<!-- Scripts Flot -->
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

<!-- face-api.js -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    // ==================== SURVEILLANCE DES ALERTES ACTIVES ====================
    let currentAudio = null;           // Référence vers le son en cours

    function arreterSonAlerte() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }
    }

    function jouerSonAlerte() {
        // Arrête le précédent pour relancer un nouveau son (évite la superposition)
        arreterSonAlerte();

        const urlSon = "{{ asset('connexion/images/son_alerte_1.mp3') }}";
        const son = new Audio(urlSon);
        son.play().catch(error => {
            console.warn("Lecture automatique impossible, attente d'une interaction", error);
        });

        son.onended = function() {
            if (currentAudio === son) currentAudio = null;
        };

        currentAudio = son;
    }
    function confirmPresence()
    {
        getUserPosition()
            .then(pos => {
                var data = $("#form_add").serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                data += "&latitude=" + encodeURIComponent(pos.latitude);
                data += "&longitude=" + encodeURIComponent(pos.longitude);
                $.ajax({
                    type: "POST",
                    url: "{{ url('/confirm_presence') }}",
                    data: data,
                    success: function(response) {
                        $('#message_du_poste').html(': <i class="zmdi zmdi-check-circle"></i>');
                        $('#message_du_poste').css('color', "#10b981");
                        $('#message_du_poste').addClass('show');
                        $("#content_btn_valider").hide();
                        setTimeout(() => {
                            $('#message_du_poste').html("");
                        }, 9000)
                    }
                });
            })
            .catch(err => {
                // Erreur de géolocalisation
                console.error(err.message);
            });
    }
    function getUserPosition()
    {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error("La géolocalisation n'est pas supportée par votre navigateur."));
            } else {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        let message = "";
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                message = "Vous avez refusé la géolocalisation.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message = "Position indisponible.";
                                break;
                            case error.TIMEOUT:
                                message = "Délai dépassé pour obtenir la position.";
                                break;
                            default:
                                message = "Erreur de géolocalisation.";
                        }
                        reject(new Error(message));
                    }
                );
            }
        });
    }
    // ==================== COMPARATEUR DE VISAGES ====================
    $(function() {

        const DEFAULT_IMAGE_URL_1 = '{{ asset( Auth::user()->image ) }}';
        const DEFAULT_IMAGE_URL_2 = "{{ asset('./storage/images/user/visage_par_defaut.png') }}";
        // const DEFAULT_IMAGE_URL_2 = "{{ asset('./storage/images/user/divine_capture.jpg') }}";

        let faceDescriptor1 = null;
        let faceDescriptor2 = null;
        let modelsLoaded = false;
        let pendingImages = [];
        let lastUrl1 = '';
        let lastUrl2 = '';

        const uploadArea1 = $('#uploadArea1');
        const uploadArea2 = $('#uploadArea2');
        const fileInput1 = $('#fileInput1');
        const fileInput2 = $('#fileInput2');
        const preview1 = $('#preview1');
        const preview2 = $('#preview2');
        const status1 = $('#status1');
        const status2 = $('#status2');
        const urlInput1 = $('#url1');
        const urlInput2 = $('#url2');
        const compareBtn = $('#compareBtn');
        const resetCompareBtn = $('#resetCompareBtn');
        const loading = $('#loading');
        const resultContainer = $('#resultContainer');
        const resultMessage = $('#resultMessage');
        const confidence = $('#confidence');
        const modelStatus = $('#modelStatus');

        const cameraModal = $('#cameraModal');
        const cameraVideo = $('#cameraVideo')[0];
        const captureBtn = $('#captureBtn');
        const closeCameraBtn = $('#closeCameraBtn');
        const cameraBtn1 = $('#cameraBtn1');
        const cameraBtn2 = $('#cameraBtn2');
        let currentImageNumber = 1;
        let mediaStream = null;

        function setPreviewLoading(preview) {
            preview.css('display', 'block');
            preview.html(
                `<div class="preview-spinner text-center p-4"><i class="fas fa-spinner fa-pulse fa-2x"></i><p class="mt-2">Chargement...</p></div>`
                );
        }

        function setPreviewPlaceholder(preview, type) {
            preview.css('display', 'block');
            if (type === 'empty') {
                preview.html(
                    `<div class="preview-placeholder text-center p-4"><i class="fas fa-image fa-3x text-muted"></i><p>Aucune image</p></div>`
                    );
            } else if (type === 'error') {
                preview.html(
                    `<div class="preview-placeholder text-center p-4"><i class="fas fa-exclamation-triangle fa-3x text-danger"></i><p>Erreur de chargement</p></div>`
                    );
            }
        }
        async function resizeImage(img, maxWidth = 800) {
            return new Promise((resolve) => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                let width = img.width;
                let height = img.height;
                if (width > maxWidth) {
                    height = (maxWidth / width) * height;
                    width = maxWidth;
                }
                if (height > maxWidth) {
                    width = (maxWidth / height) * width;
                    height = maxWidth;
                }
                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);
                resolve(canvas);
            });
        }

        function setDetectionError(status, imageNumber, message) {
            status.html(`<i class="fas fa-times-circle"></i> ${message}`);
            status.removeClass('success info').addClass('error');
            if (imageNumber === 1) faceDescriptor1 = null;
            else faceDescriptor2 = null;
        }
        async function detectFaceFromCanvas(canvas, status, imageNumber) {
            status.html('<i class="fas fa-search"></i> Recherche du visage...');
            const options = new faceapi.TinyFaceDetectorOptions({
                inputSize: 416,
                scoreThreshold: 0.2
            });
            try {
                const detection = await faceapi
                    .detectSingleFace(canvas, options)
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                if (!detection) {
                    setDetectionError(status, imageNumber, 'Aucun visage détecté.');
                } else {
                    const descriptor = detection.descriptor;
                    if (descriptor) {
                        status.html(
                            `<i class="fas fa-check-circle"></i> Visage détecté ${detection.score?.toFixed(3) ?? ''}`
                            );
                        status.removeClass('error info').addClass('success');
                        if (imageNumber === 1) faceDescriptor1 = descriptor;
                        else faceDescriptor2 = descriptor;
                    } else {
                        setDetectionError(status, imageNumber, 'Descripteur manquant.');
                    }
                }
            } catch (error) {
                setDetectionError(status, imageNumber, 'Erreur lors de la détection.');
            }
        }
        async function processImage(file, preview, status, urlInput, imageNumber) {
            setPreviewLoading(preview);
            try {
                status.html('<i class="fas fa-spinner fa-pulse"></i> Chargement...');
                status.removeClass('success error').addClass('info');
                const dataUrl = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
                urlInput.val(dataUrl);
                const img = new Image();
                await new Promise((resolve, reject) => {
                    img.onload = resolve;
                    img.onerror = reject;
                    img.src = dataUrl;
                });
                const canvas = await resizeImage(img, 800);
                preview.html(`<img src="${canvas.toDataURL()}" alt="Preview">`);
                preview.css('display', 'block');
                await detectFaceFromCanvas(canvas, status, imageNumber);
            } catch (error) {
                console.error(error);
                setDetectionError(status, imageNumber, 'Erreur technique: ' + error.message);
                setPreviewPlaceholder(preview, 'error');
            }
        }
        async function processImageFromUrl(url, preview, status, urlInput, imageNumber) {
            setPreviewLoading(preview);
            try {
                if (!url.trim()) {
                    setDetectionError(status, imageNumber, 'URL vide.');
                    setPreviewPlaceholder(preview, 'empty');
                    return;
                }
                status.html('<i class="fas fa-spinner fa-pulse"></i> Chargement depuis URL...');
                status.removeClass('success error').addClass('info');
                const img = new Image();
                img.crossOrigin = 'anonymous';
                await new Promise((resolve, reject) => {
                    img.onload = resolve;
                    img.onerror = reject;
                    img.src = url;
                });
                const canvas = await resizeImage(img, 800);
                preview.html(`<img src="${canvas.toDataURL()}" alt="Preview">`);
                preview.css('display', 'block');
                await detectFaceFromCanvas(canvas, status, imageNumber);
            } catch (error)
            {
                console.error(error);
                setDetectionError(status, imageNumber, 'Erreur de chargement (URL invalide ou CORS).');
                setPreviewPlaceholder(preview, 'error');
            }
        }
        async function reloadIfNeeded(imageNumber) {
            if (!modelsLoaded) return false;
            if (imageNumber === 1) {
                const currentUrl = urlInput1.val();
                if (currentUrl !== lastUrl1) {
                    lastUrl1 = currentUrl;
                    await processImageFromUrl(currentUrl, preview1, status1, urlInput1, 1);
                    return true;
                }
            } else {
                const currentUrl = urlInput2.val();
                if (currentUrl !== lastUrl2) {
                    lastUrl2 = currentUrl;
                    await processImageFromUrl(currentUrl, preview2, status2, urlInput2, 2);
                    return true;
                }
            }
            return false;
        }
        function onUrlChange(imageNumber) {
            if (!modelsLoaded) return;
            if (imageNumber === 1) {
                lastUrl1 = urlInput1.val();
                processImageFromUrl(urlInput1.val(), preview1, status1, urlInput1, 1);
            } else {
                lastUrl2 = urlInput2.val();
                processImageFromUrl(urlInput2.val(), preview2, status2, urlInput2, 2);
            }
        }
        let timeout1, timeout2;

        function onUrlInput(imageNumber) {
            if (!modelsLoaded) return;
            if (imageNumber === 1) {
                clearTimeout(timeout1);
                timeout1 = setTimeout(() => {
                    lastUrl1 = urlInput1.val();
                    processImageFromUrl(urlInput1.val(), preview1, status1, urlInput1, 1);
                }, 500);
            } else {
                clearTimeout(timeout2);
                timeout2 = setTimeout(() => {
                    lastUrl2 = urlInput2.val();
                    processImageFromUrl(urlInput2.val(), preview2, status2, urlInput2, 2);
                }, 500);
            }
        }

        function handleImage(file, preview, status, urlInput, imageNumber) {
            if (!file.type.startsWith('image/')) {
                setDetectionError(status, imageNumber, 'Fichier non valide (image requise)');
                setPreviewPlaceholder(preview, 'error');
                return;
            }
            const objectUrl = URL.createObjectURL(file);
            preview.html(`<img src="${objectUrl}" alt="Preview">`);
            preview.css('display', 'block');
            if (modelsLoaded) {
                processImage(file, preview, status, urlInput, imageNumber);
            } else {
                status.html('<i class="fas fa-hourglass-half"></i> Modèles en chargement...');
                status.removeClass('success error').addClass('info');
                pendingImages.push({
                    file,
                    preview,
                    status,
                    urlInput,
                    imageNumber
                });
            }
        }

        function setupUploadArea(uploadArea, fileInput, preview, status, urlInput, imageNumber) {
            uploadArea.click(function(e) {
                e.preventDefault();
                fileInput.click();
            });
            uploadArea.on('dragover', function(e) {
                e.preventDefault();
                uploadArea.css('border-color', '#800020');
                uploadArea.css('background', 'rgba(128, 0, 32, 0.05)');
            });
            uploadArea.on('dragleave', function(e) {
                e.preventDefault();
                uploadArea.css('border-color', '#cbd5e1');
                uploadArea.css('background', '#f8fafc');
            });
            uploadArea.on('drop', function(e) {
                e.preventDefault();
                uploadArea.css('border-color', '#cbd5e1');
                uploadArea.css('background', '#f8fafc');
                const file = e.originalEvent.dataTransfer.files[0];
                if (file) handleImage(file, preview, status, urlInput, imageNumber);
            });
            fileInput.change(function(e) {
                const file = e.target.files[0];
                if (file) handleImage(file, preview, status, urlInput, imageNumber);
            });
        }
        setupUploadArea(uploadArea1, fileInput1, preview1, status1, urlInput1, 1);
        setupUploadArea(uploadArea2, fileInput2, preview2, status2, urlInput2, 2);

        urlInput1.change(function() {
            onUrlChange(1);
        });
        urlInput2.change(function() {
            onUrlChange(2);
        });
        urlInput1.on('input', function() {
            onUrlInput(1);
        });
        urlInput2.on('input', function() {
            onUrlInput(2);
        });

        function loadDefaultImages() {
            if (!modelsLoaded) return;
            urlInput1.val(DEFAULT_IMAGE_URL_1);
            urlInput2.val(DEFAULT_IMAGE_URL_2);
            lastUrl1 = DEFAULT_IMAGE_URL_1;
            lastUrl2 = DEFAULT_IMAGE_URL_2;
            processImageFromUrl(DEFAULT_IMAGE_URL_1, preview1, status1, urlInput1, 1);
            processImageFromUrl(DEFAULT_IMAGE_URL_2, preview2, status2, urlInput2, 2);
        }

        function processPendingImages() {
            if (!modelsLoaded) return;
            while (pendingImages.length > 0) {
                const p = pendingImages.shift();
                processImage(p.file, p.preview, p.status, p.urlInput, p.imageNumber);
            }
        }
        async function loadModels() {
            try {
                modelStatus.html('<i class="fas fa-spinner fa-pulse"></i> Chargement des modèles...');
                const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models';
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                modelsLoaded = true;
                modelStatus.html('<i class="fas fa-check-circle"></i> Modèles prêts');
                loadDefaultImages();
                processPendingImages();
                updateCompareButton();
            } catch (error) {
                console.error('Erreur chargement modèles:', error);
                modelStatus.html('<i class="fas fa-exclamation-triangle"></i> Erreur: ' + error.message);
            }
        }
        loadModels();

        function updateCompareButton() {
            compareBtn.prop('disabled', !modelsLoaded);
        }

        async function openCamera(imageNumber) {
            currentImageNumber = imageNumber;
            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                cameraVideo.srcObject = mediaStream;
                cameraModal.addClass('active');
            } catch (error) {
                alert('Impossible d\'accéder à la caméra.');
            }
        }

        function closeCamera() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                mediaStream = null;
            }
            cameraModal.removeClass('active');
        }

        function capturePhoto() {
            if (!mediaStream) return;
            const canvas = document.createElement('canvas');
            canvas.width = cameraVideo.videoWidth;
            canvas.height = cameraVideo.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(cameraVideo, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg');
            closeCamera();
            if (currentImageNumber === 1) {
                urlInput1.val(dataUrl);
                lastUrl1 = dataUrl;
                processImageFromUrl(dataUrl, preview1, status1, urlInput1, 1);
            } else {
                urlInput2.val(dataUrl);
                lastUrl2 = dataUrl;
                processImageFromUrl(dataUrl, preview2, status2, urlInput2, 2);
            }
        }
        cameraBtn1.click(function(e) {
            e.preventDefault();
            openCamera(1);
        });
        cameraBtn2.click(function(e) {
            e.preventDefault();
            openCamera(2);
        });
        captureBtn.click(function(e) {
            e.preventDefault();
            capturePhoto();
        });
        closeCameraBtn.click(function(e) {
            e.preventDefault();
            closeCamera();
        });

        // ========== AJOUT : rendre toute la carte du visage 2 cliquable pour ouvrir la caméra ==========
        const cardVisage2 = $('#cardVisage2');
        if (cardVisage2.length) {
            cardVisage2.on('click', function(e) {
                // Ne pas déclencher si on clique sur le bouton "Prendre une photo"
                if ($(e.target).closest('#cameraBtn2').length) return;
                e.preventDefault();
                openCamera(2);
            });
        }

        // ============================================
        // FONCTION compareFaces MODIFIÉE (désactivation/réactivation du bouton)
        // ============================================
        async function compareFaces()
        {
            // --- DÉSACTIVER LE BOUTON immédiatement ---
            compareBtn.prop('disabled', true);
            loading.addClass('active');
            resultContainer.addClass('hidden');

            try {
                // On recharge les images si nécessaire (en parallèle)
                const reloaded1 = await reloadIfNeeded(1);
                const reloaded2 = await reloadIfNeeded(2);

                // On exécute la comparaison après un petit délai pour laisser les traitements se terminer
                setTimeout(() => {
                    const url1 = urlInput1.val().trim();
                    const url2 = urlInput2.val().trim();
                    if (!url1 || !url2) {
                        loading.removeClass('active');
                        resultContainer.removeClass('hidden');
                        let message = '';
                        if (!url1 && !url2) message = 'Les deux URLs sont vides.';
                        else if (!url1) message = 'L\'URL du visage 1 est vide.';
                        else message = 'L\'URL du visage 2 est vide.';
                        resultMessage.html(
                            `<div class="result-error"><i class="fas fa-exclamation-triangle"></i> ${message}</div>`
                        );
                        confidence.html('');
                        // --- RÉACTIVER LE BOUTON ---
                        compareBtn.prop('disabled', false);
                        // Faire disparaître le message après 15 secondes
                        setTimeout(() => {
                            resultMessage.html('');
                        }, 15000);
                        return;
                    }
                    if (status1.hasClass('error') || status2.hasClass('error')) {
                        loading.removeClass('active');
                        resultContainer.removeClass('hidden');
                        let errorMsg = '';
                        if (status1.hasClass('error') && status2.hasClass('error')) errorMsg = 'Les deux images sont invalides.';
                        else if (status1.hasClass('error')) errorMsg = 'Le visage 1 est invalide.';
                        else errorMsg = 'Votre visage est invalide.';
                        resultMessage.html(`<div class="result-error"><i class="fas fa-exclamation-triangle"></i> ${errorMsg}</div>`);
                        confidence.html('');
                        // --- RÉACTIVER LE BOUTON ---
                        compareBtn.prop('disabled', false);
                        setTimeout(() => {
                            resultMessage.html('');
                        }, 15000);
                        return;
                    }
                    if (!faceDescriptor1 || !faceDescriptor2) {
                        loading.removeClass('active');
                        resultContainer.removeClass('hidden');
                        resultMessage.html(
                            '<div class="result-error"><i class="fas fa-exclamation-triangle"></i> Visages non détectés correctement.</div>'
                        );
                        setTimeout(() => {
                            resultMessage.html('');
                        }, 9000);
                        confidence.html('');
                        // --- RÉACTIVER LE BOUTON ---
                        compareBtn.prop('disabled', false);
                        return;
                    }
                    try {
                        const distance = faceapi.euclideanDistance(faceDescriptor1, faceDescriptor2);
                        const threshold = 0.6;
                        const isMatch = distance < threshold;
                        const similarity = Math.max(0, Math.min(100, (1 - distance) * 100));
                        resultContainer.removeClass('hidden');
                        if (isMatch && similarity > 50) {
                            resultMessage.html('<div class="result-match"><i class="fas fa-check-circle"></i> Pointage effectué avec succès !</div>');
                            confirmPresence();
                            arreterSonAlerte();
                            // Le message disparaît après 9 secondes
                            setTimeout(() => {
                                resultMessage.html('');
                            }, 9000);
                        } else {
                            resultMessage.html('<div class="result-no-match"><i class="fas fa-times-circle"></i> Pointage refusé, Visage non reconnu !</div>');
                            setTimeout(() => {
                                resultMessage.html('');
                            }, 9000);
                        }
                        confidence.html(`<span>Confiance :</span> ${similarity.toFixed(1)}%`);
                        setTimeout(() => {
                            confidence.html('');
                        }, 9000);
                    } catch (error) {
                        resultContainer.removeClass('hidden');
                        resultMessage.html('<div class="result-error"><i class="fas fa-exclamation-triangle"></i> Erreur lors de la comparaison</div>');
                        confidence.html(error.message);
                        setTimeout(() => {
                            resultMessage.html('');
                        }, 9000);
                    } finally {
                        loading.removeClass('active');
                        // --- RÉACTIVER LE BOUTON ---
                        compareBtn.prop('disabled', false);
                    }
                }, (reloaded1 || reloaded2) ? 1000 : 500);
            } catch (error) {
                // En cas d'erreur inattendue (ex: problème de chargement)
                loading.removeClass('active');
                resultContainer.removeClass('hidden');
                resultMessage.html(`<div class="result-error"><i class="fas fa-exclamation-triangle"></i> ${error.message}</div>`);
                confidence.html('');
                // --- RÉACTIVER LE BOUTON ---
                compareBtn.prop('disabled', false);
                setTimeout(() => {
                    resultMessage.html('');
                }, 9000);
            }
        }

        resetCompareBtn.click(function(e) {
            e.preventDefault();
            resetCompare();
        });
        setInterval(updateCompareButton, 500);

        // On attache l'événement click à compareBtn
        compareBtn.click(function(e) {
            e.preventDefault();
            compareFaces();
        });

        // Fonction reset (déjà définie plus haut dans le code original, on la garde)
        function resetCompare() {
            faceDescriptor1 = null;
            faceDescriptor2 = null;
            fileInput1.val('');
            fileInput2.val('');
            preview1.html('');
            preview1.css('display', 'none');
            preview2.html('');
            preview2.css('display', 'none');
            pendingImages = [];
            resultContainer.addClass('hidden');
            if (modelsLoaded) loadDefaultImages();
            else {
                status1.html('<i class="fas fa-spinner fa-pulse"></i> Modèles en chargement...');
                status1.removeClass('success error').addClass('info');
                status2.html('<i class="fas fa-spinner fa-pulse"></i> Modèles en chargement...');
                status2.removeClass('success error').addClass('info');
            }
        }
    });

    // ==================== CHRONOMÈTRE CIRCULAIRE ====================
    $(function() {
        const hoursInput = $('#hours');
        const minutesInput = $('#minutes');
        const secondsInput = $('#seconds');
        const centisecondsInput = $('#centiseconds');
        const startBtn = $('#chronoStart');
        const pauseBtn = $('#chronoPause');
        const resetBtn = $('#chronoReset');
        const digitalDisplay = $('#digitalTimer');
        const canvas = $('#timerCanvas')[0];
        const ctx = canvas.getContext('2d');
        const pauseTimeInput = $('#pauseTime');
        const pauseColorInput = $('#pauseColor');

        let totalTime = 0;
        let remainingTime = 0;
        let timerInterval = null;
        let isRunning = false;
        let isPaused = false;

        function drawCircle(progress) {
            ctx.clearRect(0, 0, 260, 260);
            ctx.beginPath();
            ctx.arc(130, 130, 100, 0, 2 * Math.PI);
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 18;
            ctx.stroke();

            let color;
            if (progress > 0.75) color = '#10b981';
            else if (progress > 0.5) color = '#0ea5e9';
            else if (progress > 0.25) color = '#f59e0b';
            else color = '#800020';

            const startAngle = -Math.PI / 2;
            const endAngle = startAngle + (2 * Math.PI * progress);
            ctx.beginPath();
            ctx.arc(130, 130, 100, startAngle, endAngle);
            ctx.strokeStyle = color;
            ctx.lineWidth = 18;
            ctx.lineCap = 'round';
            ctx.stroke();
        }

        function updateDisplay() {
            let remaining = remainingTime;
            const cs = remaining % 100;
            remaining = Math.floor(remaining / 100);
            const s = remaining % 60;
            remaining = Math.floor(remaining / 60);
            const m = remaining % 60;
            remaining = Math.floor(remaining / 60);
            const h = remaining;
            digitalDisplay.text(
                `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}:${cs.toString().padStart(2, '0')}`
                );
        }

        function getTotalFromInputs() {
            const h = parseInt(hoursInput.val()) || 0;
            const m = parseInt(minutesInput.val()) || 0;
            const s = parseInt(secondsInput.val()) || 0;
            const cs = parseInt(centisecondsInput.val()) || 0;
            return (h * 3600 + m * 60 + s) * 100 + cs;
        }

        function getColorFromProgress(progress) {
            if (progress > 0.75) return 'Vert';
            if (progress > 0.5) return 'Bleu';
            if (progress > 0.25) return 'Orange';
            return 'Rouge (#800020)';
        }

        function startTimer() {
            if (isRunning && !isPaused) return;
            if (isPaused) {
                isPaused = false;
                pauseBtn.prop('disabled', false);
                startBtn.prop('disabled', true);
            } else {
                totalTime = getTotalFromInputs();
                if (totalTime <= 0) {
                    alert('Veuillez saisir un temps positif.');
                    return;
                }
                remainingTime = totalTime;
                updateDisplay();
                drawCircle(1);
                isPaused = false;
                pauseBtn.prop('disabled', false);
                startBtn.prop('disabled', true);
            }

            if (timerInterval) clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                if (!isRunning || isPaused) return;
                remainingTime -= 1;
                if (remainingTime < 0) remainingTime = 0;
                updateDisplay();
                const progress = remainingTime / totalTime;
                drawCircle(progress);
                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    isRunning = false;
                    isPaused = false;
                    startBtn.prop('disabled', false);
                    pauseBtn.prop('disabled', true);
                    drawCircle(0);
                }
            }, 10);
            isRunning = true;
        }

        function pauseTimer() {
            if (!isRunning || isPaused) return;
            isPaused = true;
            pauseBtn.prop('disabled', true);
            startBtn.prop('disabled', false);
            pauseTimeInput.val(digitalDisplay.text());
            const progress = remainingTime / totalTime;
            pauseColorInput.val(getColorFromProgress(progress));
        }

        function resetTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            isRunning = false;
            isPaused = false;
            startBtn.prop('disabled', false);
            pauseBtn.prop('disabled', true);
            hoursInput.val(0);
            minutesInput.val(1);
            secondsInput.val(0);
            centisecondsInput.val(0);
            totalTime = getTotalFromInputs();
            remainingTime = totalTime;
            updateDisplay();
            drawCircle(1);
            pauseTimeInput.val('');
            pauseColorInput.val('');
        }

        function validateInputs() {
            if (hoursInput.val() < 0) hoursInput.val(0);
            if (minutesInput.val() < 0) minutesInput.val(0);
            if (minutesInput.val() > 59) minutesInput.val(59);
            if (secondsInput.val() < 0) secondsInput.val(0);
            if (secondsInput.val() > 59) secondsInput.val(59);
            if (centisecondsInput.val() < 0) centisecondsInput.val(0);
            if (centisecondsInput.val() > 99) centisecondsInput.val(99);
        }

        startBtn.click(function(e) {
            e.preventDefault();
            startTimer();
        });
        pauseBtn.click(function(e) {
            e.preventDefault();
            pauseTimer();
        });
        resetBtn.click(function(e) {
            e.preventDefault();
            resetTimer();
        });
        hoursInput.change(validateInputs);
        minutesInput.change(validateInputs);
        secondsInput.change(validateInputs);
        centisecondsInput.change(validateInputs);
        hoursInput.on('input', validateInputs);
        minutesInput.on('input', validateInputs);
        secondsInput.on('input', validateInputs);
        centisecondsInput.on('input', validateInputs);
        resetTimer();
    });

    // ==================== MODALS ====================
    $(function() {
        const logoutBtn = $('#logoutBtn');
        const alertBtn = $('#alertBtn');
        const logoutModal = $('#logoutModal');
        const alertModal = $('#alertModal');
        const logoutConfirm = $('#logoutConfirm');
        const logoutCancel = $('#logoutCancel');
        const alertConfirm = $('#alertConfirm');
        const alertCancel = $('#alertCancel');
        const alertReason = $('#alertReason');
        const logoutModalClose = $('#logoutModalClose');
        const alertModalClose = $('#alertModalClose');

        logoutModalClose.click(function(e) {
            e.preventDefault();
            logoutModal.removeClass('active');
        });
        logoutCancel.click(function(e) {
            e.preventDefault();
            logoutModal.removeClass('active');
        });
        logoutConfirm.click(function(e) {
            e.preventDefault();
            logoutModal.removeClass('active');
            alert('Déconnexion (simulation).');
        });

        alertBtn.click(function(e) {
            e.preventDefault();
            alertModal.addClass('active');
            alertReason.val('');
        });
        alertModalClose.click(function(e) {
            e.preventDefault();
            alertModal.removeClass('active');
        });
        alertCancel.click(function(e) {
            e.preventDefault();
            alertModal.removeClass('active');
        });
        alertConfirm.click(function(e) {
            e.preventDefault();
            $("#alertConfirm").html("<i class='fas fa-spin fa-spinner'></i>");
            const reason = alertReason.val().trim();
            getUserPosition()
                .then(pos => {
                    $.post("{{ url('/envoyer_alerte') }}", {
                        motif: reason,
                        latitude : pos.latitude,
                        longitude : pos.longitude,
                        _token: "{{ csrf_token() }}"
                    })
                    .done(function() {
                        $("#alertConfirm").html("<i class='fas fa-check-circle'></i>");
                        $("#message_alerte").html('<i class="zmdi zmdi-check-circle"></i> Alerte envoyée avec succès');
                        $("#message_alerte").css('color', "#10b981");
                        $("#message_alerte").show();
                        setTimeout(() => {
                            alertReason.val("");
                            alertModal.removeClass('active');
                            $("#alertConfirm").html("Oui");
                            $("#message_alerte").hide();
                        }, 5000);
                    })
                    .fail(function() {
                        $("#alertConfirm").html("<i class='fas fa-times-circle' style='color:red'></i> Échec");
                        $("#message_alerte").html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'envoi de l\'alerte');
                        $("#message_alerte").css('color', "#800020");
                        $("#message_alerte").show();
                        setTimeout(() => {
                            alertModal.removeClass('active');
                            $("#message_alerte").hide();
                            $("#alertConfirm").html("Oui");
                        }, 5000);
                    });
                })
                .catch(err => {
                    // Erreur de géolocalisation
                    console.error(err.message);
                });
        });

        const intervalId = setInterval(() => {
            getUserPosition()
                .then(pos => {
                    $.get("{{ url('/control') }}", { latitude : pos.latitude, longitude : pos.longitude })
                        .done(function(rep) {
                            if(rep == 0)
                            {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "{{ url('/deconnexion') }}",
                                    data: {},
                                    success:function(response)
                                    {
                                        window.location.replace('/' + response);
                                    }
                                })
                            }else
                            {
                                if(rep == 1)
                                {
                                    arreterSonAlerte();
                                    $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Vous etês loin du poste');
                                    $('#message_du_poste').css('color', "#800020");
                                    $('#message_du_poste').addClass('show');
                                    $("#content_btn_valider").hide();
                                    setTimeout(() => {
                                        $('#message_du_poste').html("");
                                    }, 9000);
                                }
                                else
                                {
                                    if(rep == 2)
                                    {
                                        $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Entrée');
                                        $('#message_du_poste').css('color', "#800020");
                                        $('#message_du_poste').addClass('show');
                                        $("#content_btn_valider").show();
                                        setTimeout(() => {
                                            $('#message_du_poste').html("");
                                        }, 9000);
                                    }
                                    else
                                    {
                                        if(rep == 3)
                                        {
                                            arreterSonAlerte();
                                            $("#content_btn_valider").hide();
                                        }
                                        else
                                        {
                                            if(rep == 4)
                                            {
                                                arreterSonAlerte();
                                                $("#content_btn_valider").hide();
                                            }
                                            else
                                            {
                                                if(rep == 5)
                                                {
                                                    jouerSonAlerte();
                                                    $("#content_btn_valider").show();
                                                    $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Ronde 1 en cours...');
                                                    $('#message_du_poste').css('color', "#800020");
                                                    $('#message_du_poste').addClass('show');
                                                    setTimeout(() => {
                                                        $('#message_du_poste').html("");
                                                    }, 9000);
                                                }
                                                else
                                                {
                                                    if(rep == 6)
                                                    {
                                                        arreterSonAlerte();
                                                        $("#content_btn_valider").hide();
                                                    }
                                                    else
                                                    {
                                                        if(rep == 7)
                                                        {
                                                            arreterSonAlerte();
                                                            $("#content_btn_valider").hide();
                                                        }
                                                        else
                                                        {
                                                            if(rep == 8)
                                                            {
                                                                arreterSonAlerte();
                                                                $("#content_btn_valider").hide();
                                                            }
                                                            else
                                                            {
                                                                if(rep == 9)
                                                                {
                                                                    arreterSonAlerte();
                                                                    $("#content_btn_valider").hide();
                                                                }
                                                                else
                                                                {
                                                                    if(rep == 10)
                                                                    {
                                                                        jouerSonAlerte();
                                                                        $("#content_btn_valider").show();
                                                                        $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Ronde 2 en cours encoure...');
                                                                        $('#message_du_poste').css('color', "#800020");
                                                                        $('#message_du_poste').addClass('show');
                                                                        setTimeout(() => {
                                                                            $('#message_du_poste').html("");
                                                                        }, 9000);
                                                                    }
                                                                    else
                                                                    {
                                                                        if(rep == 11)
                                                                        {
                                                                            arreterSonAlerte();
                                                                            $("#content_btn_valider").hide();
                                                                        }
                                                                        else
                                                                        {
                                                                            if(rep == 12)
                                                                            {
                                                                                arreterSonAlerte();
                                                                                $("#content_btn_valider").hide();
                                                                            }
                                                                            else
                                                                            {
                                                                                if(rep == 13)
                                                                                {
                                                                                    arreterSonAlerte();
                                                                                    $("#content_btn_valider").hide();
                                                                                }
                                                                                else
                                                                                {
                                                                                    if(rep == 14)
                                                                                    {
                                                                                        arreterSonAlerte();
                                                                                        $("#content_btn_valider").hide();
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        if(rep == 15)
                                                                                        {
                                                                                            jouerSonAlerte();
                                                                                            $("#content_btn_valider").show();
                                                                                            $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Ronde 3 en cours en cours');
                                                                                            $('#message_du_poste').css('color', "#800020");
                                                                                            $('#message_du_poste').addClass('show');
                                                                                            setTimeout(() => {
                                                                                                $('#message_du_poste').html("");
                                                                                            }, 9000);
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            if(rep == 16)
                                                                                            {
                                                                                                arreterSonAlerte();
                                                                                                $("#content_btn_valider").hide();
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                if(rep == 17)
                                                                                                {
                                                                                                    arreterSonAlerte();
                                                                                                    $("#content_btn_valider").hide();
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    if(rep == 18)
                                                                                                    {
                                                                                                        // jouerSonAlerte();
                                                                                                        $('#message_du_poste').html(': <i class="zmdi zmdi-close-circle"></i> Sortie');
                                                                                                        $('#message_du_poste').css('color', "#800020");
                                                                                                        $('#message_du_poste').addClass('show');
                                                                                                        $("#content_btn_valider").show();
                                                                                                        setTimeout(() => {
                                                                                                            $('#message_du_poste').html("");
                                                                                                        }, 9000);
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        arreterSonAlerte();
                                                                                                        $("#content_btn_valider").hide();
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            console.error("Erreur :", textStatus, errorThrown);
                        });
                })
                .catch(err => {
                    // Erreur de géolocalisation
                    console.error(err.message);
                });
        }, 15000);
    });
</script>
@endsection
@endsection
