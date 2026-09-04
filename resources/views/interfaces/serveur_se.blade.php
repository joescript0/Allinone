@php
    use App\Models\Appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php
use App\Models\affectationstables;
use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Tables;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Approvisionnements;
use App\Models\Achats;
use App\Models\Societes;
use App\Models\Clients;
use App\Models\Mesures;
use App\Models\Pointdeventes;
use App\Models\Entres;
use App\Models\detailpaiessachats;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'SERVEUR(SE)')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES PAGES "GESTION ARTICLE"
   ET "APPROVISIONNEMENT" – ADAPTÉ AUX FACTURES
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
.btn-secondary,
#liberer_table {
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

/* Bouton Libérer la table - VERT */
#liberer_table,
.btn-success {
    background: var(--vert-gradient) !important;
    color: white !important;
}
#liberer_table:hover,
.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
    background: linear-gradient(135deg, #059669, #047857) !important;
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

/* Boutons désactivés (add_r, save_r, print_r) */
#add_r,
#save_r,
#print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    transform: none !important;
    box-shadow: none !important;
}

/* Bouton d'impression (spécifique à cette page) */
#print {
    background: #3B82F6 !important;
    color: white !important;
}
#print:hover {
    background: #2563eb !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
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

/* ========== BADGE DANS LE TITRE ========== */
.badge-invoice {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    color: white;
    border-radius: 50px;
    padding: 4px 16px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-left: 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(227, 27, 35, 0.3);
}

/* ========== BADGES PAR CATÉGORIE (couleurs selon votre demande) ========== */
.invoice-badges-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
    justify-content: flex-end;
}

.invoice-count-badge {
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.75rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Total : bleu #3B82F6 (comme le bouton primaire) */
.badge-total {
    background: linear-gradient(135deg, #3B82F6, #2563eb);
}

/* Payé : bleu de nuit */
.badge-paye {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
}

/* Crédit : rouge */
.badge-credit {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

/* Bénéfice : vert succès */
.badge-benefice {
    background: linear-gradient(135deg, #198754, #146c43);
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

/* ========== MESSAGES STYLISÉS ========== */
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
.table tbody td a i.zmdi-eye,
.table tbody td a i.zmdi-money {
    color: #2c7da0;
}
.table tbody td a i.zmdi-delete {
    color: #ef4444;
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
.table tbody td a:hover i.zmdi-eye,
.table tbody td a:hover i.zmdi-money {
    color: #1e5a7a;
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

/* ========== MODALE PDF (PAIEMENT) ========== */
.modal.fade#pdfModal .modal-dialog {
    max-width: 100%;
    width: 60%;
    margin: 1.75rem auto;
}

.modal.fade#pdfModal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: var(--shadow-premium);
    overflow: hidden;
}

.modal.fade#pdfModal .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    border-bottom: none;
    padding: 1.2rem 1.5rem;
}

.modal.fade#pdfModal .modal-header .modal-title {
    font-weight: 700;
    font-size: 1.2rem;
    color: white;
}

.modal.fade#pdfModal .modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
}

.modal.fade#pdfModal .modal-header .close:hover {
    opacity: 1;
}

.modal.fade#pdfModal .modal-body {
    padding: 0;
    background: #f8fafc;
}

.modal.fade#pdfModal .modal-footer {
    background: white;
    border-top: 1px solid #eef2f6;
    padding: 1.2rem 1.5rem;
}

.modal.fade#pdfModal #pdfIframe {
    width: 100%;
    height: 50vh;
    border: none;
    background: white;
}

.modal.fade#pdfModal #montant_recu,
.modal.fade#pdfModal #devise_recu {
    width: 100%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 40px !important;
    padding: 10px 14px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    height: 44px;
    box-sizing: border-box;
}

.modal.fade#pdfModal #montant_recu:focus,
.modal.fade#pdfModal #devise_recu:focus {
    border-color: var(--bleu-nuit);
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15);
    outline: none;
}

.modal.fade#pdfModal #devise_recu {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    cursor: pointer;
}

.modal.fade#pdfModal #btn_payer {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none;
    border-radius: 40px !important;
    padding: 10px 24px;
    font-weight: 700;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    width: 100%;
    color: white;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modal.fade#pdfModal #btn_payer:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(16, 185, 129, 0.3);
}

.modal.fade#pdfModal #msg_facture {
    display: inline-block !important;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 10px 18px;
    border-radius: 50px;
    background: #f1f5f9;
    color: #1e2a3e;
    margin-top: 12px;
    margin-bottom: 0;
    text-align: center;
    animation: fadeInMsg 0.3s ease-out;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    max-width: 100%;
    word-wrap: break-word;
    line-height: 1.5;
}

.modal.fade#pdfModal #msg_facture:has(i.zmdi-close-circle),
.modal.fade#pdfModal #msg_facture[style*="color: #dc3545"] {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b !important;
    border-left: 4px solid #dc2626 !important;
}

.modal.fade#pdfModal #msg_facture:has(i.zmdi-check-circle),
.modal.fade#pdfModal #msg_facture[style*="color: #28a745"] {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46 !important;
    border-left: 4px solid #10b981 !important;
}

.modal.fade#pdfModal #msg_facture:has(i.zmdi-alert),
.modal.fade#pdfModal #msg_facture[style*="color: #ffc107"] {
    background: linear-gradient(95deg, #fed7aa, #ffedcc) !important;
    color: #9b4d00 !important;
    border-left: 4px solid #f59e0b !important;
}

@keyframes fadeInMsg {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal.fade#pdfModal .btn-secondary {
    background: #64748b;
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    color: white;
}

.modal.fade#pdfModal .btn-secondary:hover {
    background: #475569;
    transform: translateY(-2px);
}

.modal.fade#pdfModal hr {
    margin: 15px 0;
    border: 0;
    border-top: 1px solid #eef2f6;
}

/* ========== LAYOUT SPÉCIFIQUE POUR BLOC_2 ET BLOC_3 CÔTE À CÔTE ========== */
#bloc_t {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    gap: 20px;
    width: 100%;
    margin: 0;
}

#bloc_2,
#bloc_3 {
    flex: 1 1 calc(50% - 20px);
    min-width: 280px;
    margin: 0 !important;
}

@media (max-width: 768px) {
    #bloc_t {
        flex-direction: column;
        gap: 20px;
        align-items: center;
    }
    #bloc_2, #bloc_3 {
        flex: 1 1 100%;
        width: 100%;
        min-width: auto;
    }
}

/* ========== RESPONSIVE GLOBAL ========== */
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
    .btn-danger,
    #liberer_table {
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
    .invoice-count-badge {
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

    /* Modale PDF responsive */
    .modal.fade#pdfModal .modal-dialog {
        width: 95%;
        margin: 1rem auto;
    }
    .modal.fade#pdfModal #pdfIframe {
        height: 40vh;
    }
    .modal.fade#pdfModal #boite_de_control .col-lg-4 {
        margin-bottom: 10px;
    }
    .modal.fade#pdfModal #btn_payer {
        width: 100%;
    }
    .modal.fade#pdfModal .modal-footer {
        padding: 1rem;
    }
    .modal.fade#pdfModal #montant_recu,
    .modal.fade#pdfModal #devise_recu {
        height: 40px;
        font-size: 0.8rem;
    }
    .modal.fade#pdfModal #msg_facture {
        font-size: 0.75rem;
        padding: 8px 14px;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
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
    #resetFilters,
    #liberer_table {
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
    .modal.fade#pdfModal .modal-header {
        padding: 0.8rem 1rem;
    }
    .modal.fade#pdfModal .modal-header .modal-title {
        font-size: 1rem;
    }
    .modal.fade#pdfModal #pdfIframe {
        height: 35vh;
    }
    .modal.fade#pdfModal #btn_payer {
        padding: 8px 16px;
        font-size: 0.75rem;
    }
    .modal.fade#pdfModal .btn-secondary {
        padding: 6px 16px;
        font-size: 0.7rem;
    }
    .modal.fade#pdfModal #msg_facture {
        font-size: 0.7rem;
        padding: 6px 12px;
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
                                        <i class="zmdi zmdi-email-open"></i> Liste
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
                                    <?php if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0))) { ?>
                                    <a class="btn-primary btn-sm" id="add" href="">
                                        <i class="zmdi zmdi-email"></i> Ajouter
                                    </a>
                                    <?php } else { ?>
                                    <a class="btn-primary btn-sm" id="add_r" href="">
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Serveur(se)</h6>
                </div>

                <!-- ============================================================ -->
                <!-- NOUVEAU BLOC_1 AVEC BADGE, TOTAUX ÉTENDUS, COLONNES PAYÉ/CRÉDIT -->
                <!-- ============================================================ -->
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <!-- TITRE AVEC BADGE INTÉGRÉ -->
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste
                        <span class="badge-invoice">
                            <i class="zmdi zmdi-view-list" style="color: white;"></i> Factures : <span id="invoiceCount">0</span>
                        </span>
                    </h4>

                    <!-- SECTION FILTRES AVEC DATE RANGE PICKER -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> N° Facture</label>
                            <input type="text" id="filterNumero" class="form-control" placeholder="Rechercher par numéro...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-accounts text-danger"></i> Client / Libellé</label>
                            <input type="text" id="filterClient" class="form-control" placeholder="Rechercher par client ou libellé...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                            <input type="text" id="filterUser" class="form-control" placeholder="Rechercher par utilisateur...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-money text-danger"></i> Statut</label>
                            <select id="filterStatut" class="form-control">
                                <option value="all">Tous</option>
                                <option value="paid">Payées</option>
                                <option value="unpaid">Impayées</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-table text-danger"></i> Table</label>
                            <input type="text" id="filterTable" class="form-control" placeholder="Rechercher par table...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                            <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-chart text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="Montant exact" step="0.01">
                        </div>
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- BADGES DE TOTAUX AVEC COULEURS DEMANDÉES -->
                    <div class="invoice-badges-container">
                        <span class="invoice-count-badge badge-total">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge badge-total">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge badge-paye">
                            <i class="zmdi zmdi-money"></i> Payé USD : <span id="totalPaidUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge badge-paye">
                            <i class="zmdi zmdi-money-box"></i> Payé CDF : <span id="totalPaidCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge badge-credit">
                            <i class="zmdi zmdi-time"></i> Crédit USD : <span id="totalCreditUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge badge-credit">
                            <i class="zmdi zmdi-time"></i> Crédit CDF : <span id="totalCreditCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge badge-benefice">
                            <i class="zmdi zmdi-trending-up"></i> Bénéfice USD : <span id="totalBeneficeUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge badge-benefice">
                            <i class="zmdi zmdi-trending-up"></i> Bénéfice CDF : <span id="totalBeneficeCdf">0,00</span> Fc
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
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Libelle / Client</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Table</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Payé</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Mode de paiement</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($factures as $data)
                                            @php
                                                $taux = $data->taux;

                                                // 1. Récupération des achats
                                                $ent = Achats::where('facture_id', $data->id)->get();

                                                // 2. Calcul du total original (sans frais)
                                                $total_original = 0;
                                                foreach ($ent as $e) {
                                                    $total_original += $e->total;
                                                }

                                                // 3. Récupération des paiements déjà effectués
                                                $paiements = detailpaiessachats::where('facture_id', $data->id)->get();
                                                $montant_usd_paye = 0;
                                                $montant_cdf_paye = 0;
                                                foreach ($paiements as $paiement) {
                                                    if ($paiement->devise_recu == 0) { // paiement en USD
                                                        $montant_usd_paye += $paiement->montant_recu;
                                                        $montant_cdf_paye += $paiement->montant_recu * $taux;
                                                    } else { // paiement en CDF
                                                        $montant_cdf_paye += $paiement->montant_recu;
                                                        $montant_usd_paye += $paiement->montant_recu / $taux;
                                                    }
                                                }

                                                // 4. Conversion du total original en USD / CDF selon devise de la facture
                                                if ($data->devise == 0) {
                                                    $total_original_usd = $total_original;
                                                    $total_original_cdf = $total_original * $taux;
                                                } else {
                                                    $total_original_cdf = $total_original;
                                                    $total_original_usd = $total_original / $taux;
                                                }

                                                // 5. Déterminer si la facture est impayée (sans tolérance)
                                                $est_impayee = ($montant_usd_paye < $total_original_usd) || ($montant_cdf_paye < $total_original_cdf);

                                                // 6. Vérifier le délai d'1 heure depuis la création de la facture
                                                $date_creation_facture = strtotime($data->created_at);
                                                $delai_1h = 3600; // 1 heure en secondes
                                                $delai_depasse = (time() - $date_creation_facture) > $delai_1h;

                                                // 7. Application des frais de crédit (5%) sur chaque achat si conditions remplies
                                                foreach ($ent as $e) {
                                                    if (($e->frais_credit == 0 || $e->frais_credit === null) && $est_impayee && $delai_depasse) {
                                                        $frais = $e->total * 0.05;
                                                        $e->frais_credit = $frais;
                                                        $e->save();
                                                    }
                                                }

                                                // 8. Recalcul du total avec frais et des coûts d'achat
                                                $total = 0;
                                                $achat_total_usd = 0;
                                                $achat_total_cdf = 0;
                                                foreach ($ent as $e) {
                                                    $total += $e->total + ($e->frais_credit ?? 0);
                                                    // Coût d'achat (prix unitaire × quantité)
                                                    $prix_achat = $e->prix_achat ?? 0;
                                                    $quantite = $e->quantite ?? 1;
                                                    $prix_achat_total = $prix_achat * $quantite;
                                                    $devise_achat = $e->devise_achat ?? $data->devise;
                                                    if ($devise_achat == 0) { // USD
                                                        $achat_total_usd += $prix_achat_total;
                                                        $achat_total_cdf += $prix_achat_total * $taux;
                                                    } else { // CDF
                                                        $achat_total_cdf += $prix_achat_total;
                                                        $achat_total_usd += $prix_achat_total / $taux;
                                                    }
                                                }

                                                // 9. Conversion et affichage des montants
                                                if ($data->devise == 0) {
                                                    $montant_usd = $total;
                                                    $montant_cdf = $total * $taux;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                                                } else {
                                                    $montant_cdf = $total;
                                                    $montant_usd = $total / $taux;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                                                }

                                                // 10. Bénéfices (avec frais)
                                                $benefice_usd = $montant_usd - $achat_total_usd;
                                                $benefice_cdf = $montant_cdf - $achat_total_cdf;

                                                // 11. Crédit restant (avec frais)
                                                $reste_usd = $montant_usd - $montant_usd_paye;
                                                $reste_cdf = $montant_cdf - $montant_cdf_paye;

                                                // 12. Formatage pour l'affichage
                                                $paye_affichage = number_format($montant_usd_paye, 2, ',', ' ') . ' USD (' . number_format($montant_cdf_paye, 2, ',', ' ') . ' CDF)';
                                                $reste_affichage = number_format($reste_usd, 2, ',', ' ') . ' USD (' . number_format($reste_cdf, 2, ',', ' ') . ' CDF)';
                                                $statut_text = $reste_usd > 0 ? 'Impayé' : 'Payé';
                                                $client_name = $data->client_id == 0 ? $data->libelle : (Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A');
                                            @endphp
                                            <tr id="row_{{ $data->id }}"
                                                data-montant-usd="{{ $montant_usd }}"
                                                data-montant-cdf="{{ $montant_cdf }}"
                                                data-paye-usd="{{ $montant_usd_paye }}"
                                                data-paye-cdf="{{ $montant_cdf_paye }}"
                                                data-credit-usd="{{ $reste_usd }}"
                                                data-credit-cdf="{{ $reste_cdf }}"
                                                data-benefice-usd="{{ $benefice_usd }}"
                                                data-benefice-cdf="{{ $benefice_cdf }}">
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell" data-client="{{ $client_name }}">
                                                    @if ($data->client_id == 0)
                                                        {{ $data->libelle }}
                                                    @else
                                                        {{ Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="table-cell" data-table="{{ $data->table_id == 0 ? 'Aucune' : (Tables::where('id', $data->table_id)->first()['nom'] ?? 'N/A') }}">
                                                    @if ($data->table_id == 0)
                                                        Aucune
                                                    @else
                                                        @php $table = Tables::where('id', $data->table_id)->first(); @endphp
                                                        @if ($table && $table->occupee == 1)
                                                            <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">{{ $table->nom }}</span>
                                                        @elseif ($table && $table->occupee == 0)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">{{ $table->nom }}</span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="{{ $total }}">
                                                    {{ $montant_affichage }}
                                                </td>
                                                <td class="paye-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $paye_affichage }}
                                                </td>
                                                <td class="reste-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $reste_affichage }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ $data->created_at }}">
                                                    @php
                                                        $date = $data->created_at;
                                                        $date_1 = explode(' ', $date);
                                                        echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                                    @endphp
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell" data-statut="{{ $reste_usd > 0 ? 'unpaid' : 'paid' }}">
                                                    @if ($reste_usd > 0)
                                                        @if ($montant_usd_paye > 0)
                                                            <i class="zmdi zmdi-time text-warning"></i> <span class="text-warning">Partiel</span>
                                                        @else
                                                            <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Impayé</span>
                                                        @endif
                                                    @else
                                                        @if ($data->mode_de_paiement == 1)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">CASH</span>
                                                        @elseif ($data->mode_de_paiement == 2)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Mobile money</span>
                                                        @elseif ($data->mode_de_paiement == 3)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Bank</span>
                                                        @else
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Payé</span>
                                                        @endif
                                                    @endif
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
                                                        @if ($reste_usd > 0)
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                                        @else
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        @endif
                                                    <?php } else { ?>
                                                        @if ($reste_usd > 0)
                                                            <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                                        @else
                                                            <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        @endif
                                                    <?php } ?>
                                                    <!-- AJOUT : ICÔNE DE SUPPRESSION AVEC MODALE DE CONFIRMATION -->
                                                    <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (Auth::user()->role == 0)) { ?>
                                                        <a href="#" class="delete-facture-btn"
                                                           data-id="{{ $data->id }}"
                                                           data-numero="{{ $data->numero }}"
                                                           data-client="{{ $client_name }}"
                                                           data-montant="{{ $montant_affichage }}"
                                                           data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                                                           data-statut="{{ $statut_text }}"
                                                           title="Supprimer cette facture">
                                                            <i class="zmdi zmdi-delete text-danger"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <script>
                                                        $("#detail_{{ $i }}").click(function(e) {
                                                            e.preventDefault();
                                                            var payer = "<?= $data->payer ?>";
                                                            if(payer == 0)
                                                            {
                                                                $.get("{{ url('/refresh_detailfactureass') }}", {
                                                                    invitation_id: <?= $data->id ?>,
                                                                }, function(refresh_editinvitations) {
                                                                    $("#bloc_1").hide();
                                                                    $("#bloc_2").show();
                                                                    $("#bloc_3").show();
                                                                    $("#bloc_t").show();
                                                                    $("#bloc_3").html(refresh_editinvitations);
                                                                });
                                                            }
                                                            if(payer == 1)
                                                            {
                                                                $("#n_fac").html("<?= $data->numero ?>");
                                                                $("#id_fac").val("<?= $data->id ?>");
                                                                var pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";
                                                                if (pdfUrl && pdfUrl !== '') {
                                                                    currentPdfUrl = pdfUrl;
                                                                    $("#pdfIframe").attr("src", pdfUrl);
                                                                    $("#pdfModal").modal("show");
                                                                } else {
                                                                    $.get("{{ url('/print_facture') }}", {
                                                                        "facture_id": "<?= $data->id ?>"
                                                                    }, function(response) {
                                                                        if (response && response[0][0]) {
                                                                            currentPdfUrl = response[0][0];
                                                                            $("#pdfIframe").attr("src", response[0][0]);
                                                                            $("#cdf_montant_payer").val(response[0][1]);
                                                                            $("#usd_montant_payer").val(response[0][2]);
                                                                            $("#payer").val(response[0][5]);
                                                                            $("#pdfModal").modal("show");
                                                                        } else if (response && typeof response[0][0] === 'string') {
                                                                            currentPdfUrl = response[0][0];
                                                                            $("#pdfIframe").attr("src", response[0][0]);
                                                                            $("#cdf_montant_payer").val(response[0][1]);
                                                                            $("#usd_montant_payer").val(response[0][2]);
                                                                            $("#payer").val(response[0][5]);
                                                                            $("#pdfModal").modal("show");
                                                                        } else {
                                                                            alert("Aucun PDF disponible pour cette facture.");
                                                                        }
                                                                    }).fail(function() {
                                                                        alert("Erreur lors de la récupération du PDF.");
                                                                    });
                                                                }
                                                            }
                                                        });
                                                        $("#detail_r{{ $i }}").click(function(e) {
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
                <!-- FIN NOUVEAU BLOC_1 -->

                <!-- LIGNE POUR BLOC_2 ET BLOC_3 CÔTE À CÔTE -->
                <div id="bloc_t" class="row" style="width: 100%; margin: 0;">
                    <!-- Bloc 2 à gauche -->
                    <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-5">
                        <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-plus-circle text-info"></i>
                            Ajouter un article</h4>
                        <form id="form_add" action="#" method="post">
                            @csrf
                            <div class="row">
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Numero facture</span></label>
                                        <select id="numero_facture" name="numero_facture" class="select2"
                                            data-placeholder="Selectionnez un type de sortie">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Pour quelle table ?</span></label>
                                        <select id="table_id" name="table_id" class="select2"
                                            data-placeholder="Selectionnez une table">
                                            <option selected value="">Selectionnez une table</option>
                                            @foreach ($tables as $data)
                                                @php
                                                    $affecte = affectationstables::where(["user_id" => Auth::user()->id, "table_id" => $data->id])->count() != 0 ? 1 : 0;
                                                    $nomPointDeVente = pointdeventes::where('id', $data->pointdeventes_id)->first()['nom'] ?? 'N/A';
                                                @endphp
                                                @if (Auth::user()->role == 0)
                                                    @if ($data->occupee == 0)
                                                        <option value="{{ $data->id }}" data-occupee="0">
                                                            🟢 {{ $data->nom }} ({{ $nomPointDeVente }})
                                                        </option>
                                                    @else
                                                        <option value="{{ $data->id }}" data-occupee="1">
                                                            🔴 {{ $data->nom }} ({{ $nomPointDeVente }})
                                                        </option>
                                                    @endif
                                                @else
                                                    @if (($data->occupee == 0) && ($affecte == 1))
                                                        <option value="{{ $data->id }}" data-occupee="0">
                                                            🟢 {{ $data->nom }} ({{ $nomPointDeVente }})
                                                        </option>
                                                    @endif
                                                    @if (($data->occupee == 1) && ($affecte == 1))
                                                        <option value="{{ $data->id }}" data-occupee="1">
                                                            🔴 {{ $data->nom }} ({{ $nomPointDeVente }})
                                                        </option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Il s'agit de quel article ?</span></label>
                                        <select id="type_sortie" name="type_sortie" class="select2"
                                            data-placeholder="Selectionnez un article">
                                            <option selected value="">Selectionnez un article</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="row">
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Il s'agit de quelle action ?</span></label>
                                        <select id="action" name="action" class="form-control"
                                            data-placeholder="Selectionnez une action">
                                            <option style="display: none;" selected value="">Selectionnez une action</option>
                                            <option selected value="1">Une vente</option>
                                            @if (Auth::user()->role == 0)
                                                <option style="display: none;" value="2">Un prêt</option>
                                                <option style="display: none;" value="3">Une perte</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Type de vente ?</span></label>
                                        <select id="type_vente_id" name="type_vente_id" class="form-control"
                                            data-placeholder="Selectionnez un type de vente">
                                            <option style="display: none;" selected value="">Selectionnez un type de vente</option>
                                           @foreach ($typeventes as $data)
                                                <option value="{{ $data->id }}">
                                                    <?= $data->nom ?>
                                                </option>
                                            @endforeach
                                        </select>
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
                            <div style="margin-top: -8px;" class="row">
                                <div class="col-6" style="display: none;">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-money"></i> devise </span></label>
                                        <select id="devise" name="devise" class="select2"
                                            data-placeholder="Selectionnez une devise">
                                            <option selected class="form-control" value="">Selectionnez une devise
                                            </option>
                                            <option class="form-control" value="0"> $</option>
                                            <option class="form-control" value="1"> Fc</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-money"></i> Taux</span></label>
                                        <input id="taux" name="taux" type="text" class="form-control input-mask" value="2200"
                                            data-mask="00000000000000000000000000000000000000"
                                            style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                            placeholder="Taux (Ex : 10)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-comment"></i> Libelle </span></label>
                                        <textarea id="libelle" name="libelle"
                                            style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                            class="form-control" placeholder="Libellé" cols="2" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-accounts"></i> Clients </span></label>
                                        <select id="client_id" name="client_id" class="form-control"
                                            data-placeholder="Selectionnez un client">
                                            <option selected class="form-control" value="">Selectionnez un client</option>
                                            @foreach ($clients as $data)
                                                <option value="{{ $data->id }}"><?= $data->name ?></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            </div>
                        </form>
                        <div style="margin-top: 15px;display: none;" class="row">
                            <div class="col-12">
                                <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez
                                    votre de sortie d'article</span></label>
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
                                    <!-- ⬇️ BOUTON LIBÉRER EN VERT -->
                                    <button id="liberer_table" class="btn btn-success btn-sm" style="display:none;">
                                        Libérer la table <i class="zmdi zmdi-check-circle"></i>
                                    </button>
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

                    <!-- Bloc 3 à droite -->
                    <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-7">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <span id="data_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#pret" id="btn_pret">Sup</button>
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous supprimez ? </h5>
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
    <div class="modal fade" id="pret" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        confirmez la remise de ce prêt ?</h5>
                </div>
                <div class="modal-body">
                    <p id="element_pret" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_p" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_p" class="btn btn-danger btn-sm"
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
                        vous approuvez ? </h5>
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

    <!-- AJOUT : MODALE DE SUPPRESSION DE FACTURE AVEC DÉTAILS -->
    <div class="modal fade" id="deleteFactureModal" tabindex="-1" role="dialog" aria-labelledby="deleteFactureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold;font-size: 16px;">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer la facture ci-dessous ? Cette action est irréversible.</p>
                    <div style="background: #f8f9fa; padding: 12px 15px; border-radius: 8px; margin-top: 10px;">
                        <table style="width:100%; font-size:0.9rem; border-collapse:collapse;">
                            <tr><td style="padding:5px 0; font-weight:600;">N° facture :</td><td style="padding:5px 0;" id="delete_facture_numero">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Client / Libellé :</td><td style="padding:5px 0;" id="delete_facture_client">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Montant :</td><td style="padding:5px 0;" id="delete_facture_montant">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Date :</td><td style="padding:5px 0;" id="delete_facture_date">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Statut :</td><td style="padding:5px 0;" id="delete_facture_statut">-</td></tr>
                        </table>
                    </div>
                </div>
                <div style="font-weight: bold;text-align: center; padding-bottom: 15px;">
                    <button id="confirm_delete_facture" class="btn btn-info btn-sm" style="margin-right: 8px;">Oui, supprimer</button>
                    <button class="btn btn-danger btn-sm" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== MODAL POUR LIBÉRER LA TABLE (CONFIRMATION) ====== -->
    <div class="modal fade" id="libererTableModal" tabindex="-1" role="dialog" aria-labelledby="libererTableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                    <h5 class="modal-title" id="libererTableModalLabel">
                        <i class="zmdi zmdi-check-circle"></i> Libérer la table
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p style="font-size: 1.1rem; font-weight: 500;">
                        <i class="zmdi zmdi-alert-triangle" style="color: #f59e0b; font-size: 2.5rem;"></i>
                    </p>
                    <p style="font-size: 1.1rem; font-weight: 500;">
                        Voulez-vous vraiment libérer cette table ?
                    </p>
                    <p class="text-muted small" id="tableNameDisplay"></p>
                </div>
                <div class="modal-footer" style="justify-content: center; border-top: none;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="zmdi zmdi-close"></i> Non
                    </button>
                    <button type="button" class="btn btn-success" id="confirmLibererTable">
                        <i class="zmdi zmdi-check"></i> Oui, libérer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal/Fenêtre modale pour afficher le PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%; width: 60%;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #007bff; color: white;">
                    <h5 class="modal-title" id="pdfModalLabel">
                        <i class="zmdi zmdi-file-pdf"></i> Aperçu facture : <span id="n_fac"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <iframe id="pdfIframe" src="" style="width: 100%; height: 50vh; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <div class="container-fluid">
                        <div id="boite_de_control" class="row w-100" style="margin: 0;">
                            <div class="col-lg-4">
                                <input type="number" id="montant_recu" class="form-control" placeholder="Montant reçu"
                                    step="0.01" style="border-radius: 8px;">
                                <input type="hidden" id="cdf_montant_payer" name="cdf_montant_payer">
                                <input type="hidden" id="usd_montant_payer" name="usd_montant_payer">
                                <input type="hidden" id="payer" name="payer">
                                <input type="hidden" id="id_fac" name="id_fac">
                            </div>
                            <div class="col-lg-4">
                                <select id="devise_recu" class="form-control" style="border-radius: 8px;">
                                    <option value="">Sellectionnez une devise</option>
                                    <option value="0">USD</option>
                                    <option value="1">CDF</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" id="btn_payer" class="btn btn-success"
                                    style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 8px;">
                                    <i class="zmdi zmdi-money"></i> Payer
                                </button>
                            </div>
                        </div>
                        <div id="boite_de_message" class="row w-100" style="margin: 0;">
                            <div class="col-lg-12" style="text-align: center;">
                                <span style="font-weight: bold;" id="msg_facture"></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row w-100 mt-2">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    style="border-radius: 8px;">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('js-code')
    {{-- Ajout des dépendances pour le Date Range Picker (comme dans rapport) --}}
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
        $("#link_44").addClass("active");

        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzonewidget").trigger("click");
        });

        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_t").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_t").hide();
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {
                // bien
            });
        });

        $("#add").click(function(e) {
            $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {
                // bien
            });
            $.get("{{ url('/get_tables_select') }}", {}, function(response) {
                $("#table_id").html(response);
                var table_id = $("#table_id").val();
                 $.get("{{ url('/get_articles_select') }}", { table_id : tableId })
                    .done(function(response) {
                        // Remplacer le contenu du select #type_sortie par la réponse HTML (options)
                        $("#type_sortie").html(response);

                        // Si un article était déjà sélectionné, on déclenche son événement change
                        // pour que l'écouteur existant sur #type_sortie mette à jour les détails
                        var currentArticle = $("#type_sortie").val();
                        if (currentArticle && currentArticle.trim() !== '') {
                            $("#type_sortie").trigger('change');
                        }
                    })
                    .fail(function() {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors du chargement des articles pour cette table');
                        setTimeout(function() {
                            $('#msg').html('');
                        }, 9000);
                    });
            });
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").show();
            $("#bloc_3").html('<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="fa fa-info-circle text-info"></i> Détails facture</h4>');
            $("#bloc_t").show();
            // Cacher le bouton libérer au départ
            $("#liberer_table").hide();
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
            $("#liberer_table").hide();
        });

        // ========== GESTION DU BOUTON LIBÉRER LA TABLE (AVEC VÉRIFICATION) ==========
        // Afficher/cacher le bouton selon la sélection de la table
        // Modification : le bouton n'apparaît que si la table est occupée (data-occupee=1)
        $("#table_id").on('change', function() {
            var selected = $(this).find('option:selected');
            var occupee = selected.data('occupee');
            if ($(this).val() && $(this).val().trim() !== '' && occupee == 1) {
                $("#liberer_table").show();
            } else {
                $("#liberer_table").hide();
            }
        });

        // Au chargement, cacher par défaut
        $("#liberer_table").hide();

        // Clic sur le bouton "Libérer la table"
        $("#liberer_table").click(function(e) {
            e.preventDefault();
            var table_id = $("#table_id").val();
            if (!table_id || table_id.trim().length === 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez sélectionner une table à libérer');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                return;
            }

            // Vérifier si la table est déjà libre (data-occupee=0)
            var occupee = $("#table_id option:selected").data('occupee');
            if (occupee == 0) {
                // Table déjà libre → afficher un message d'erreur
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette table n\'est pas occupée.');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                return;
            }

            // Sinon, la table est occupée → ouvrir le modal de confirmation
            var tableName = $("#table_id option:selected").text();
            $("#tableNameDisplay").text('Table : ' + tableName);

            // Stocker l'ID dans un attribut data du modal
            $("#libererTableModal").data('table-id', table_id);
            $("#libererTableModal").modal('show');
        });

        // Gestion du clic sur "Oui, libérer" dans le modal
        $("#confirmLibererTable").click(function() {
            var table_id = $("#libererTableModal").data('table-id');
            if (!table_id) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Identifiant de table manquant');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                $("#libererTableModal").modal('hide');
                return;
            }

            // Désactiver le bouton le temps de l'opération
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Chargement...');

            $.get("{{ url('/liberer_table') }}", { table_id: table_id })
                .done(function(response) {
                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Table libérée avec succès');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                    // Rafraîchir la liste des tables
                    $.get("{{ url('/get_tables_select') }}", function(html) {
                        $("#table_id").html(html);
                        $("#liberer_table").hide();
                    });
                    $("#libererTableModal").modal('hide');
                })
                .fail(function() {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de la libération');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                    $("#libererTableModal").modal('hide');
                })
                .always(function() {
                    btn.prop('disabled', false).html('<i class="zmdi zmdi-check"></i> Oui, libérer');
                });
        });

        // ========== SAUVEGARDE AVEC VÉRIFICATION DE LA TABLE ET INDICATEUR DE CHARGEMENT ==========
        $("#save").click(function(e) {
            e.preventDefault();
            var btn = $(this);
            // --- AJOUT : désactiver et afficher le spinner ---
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');

            // Fonction pour réinitialiser le bouton en cas d'erreur ou de validation échouée
            function resetButton() {
                btn.prop('disabled', false).html('Enregister <i class="zmdi zmdi-save"></i>');
            }

            var numero_facture = $("#numero_facture").val();
            var table_id = $("#table_id").val();
            var type_sortie = $("#type_sortie").val();
            var action = $("#action").val();
            var quantite = $("#quantite").val();
            var devise = $("#devise").val();
            var taux = $("#taux").val();
            var libelle = $("#libelle").val();
            var client = $("#client_id").val();
            var type_vente_id = $("#type_vente_id").val();
            var data = $("#form_add").serialize();

            // 1️⃣ Vérification de la table
            if (table_id.trim().length === 0)
            {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez sélectionner une table');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }

            if (numero_facture.trim().length == 0)
            {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero d\'entré');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            } else {
                if (type_sortie.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                    resetButton();
                    return;
                } else {
                    if (action.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une action');
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                        resetButton();
                        return;
                    } else {
                        if(type_vente_id.trim().length == 0){
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez le type de vente detail ou gros');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                            resetButton();
                            return;
                        } else {
                            if (quantite.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                resetButton();
                                return;
                            } else {
                                if(quantite.trim() <= 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être supérieur à 0');
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                    resetButton();
                                    return;
                                } else {
                                    $.get("{{ url('/get_prix_article') }}", {
                                        article_id: $("#type_sortie").val(),
                                        type_vente_id: $("#type_vente_id").val(),
                                        table_id: $("#table_id").val(),
                                    }, function(get_prix_article) {
                                        $.get("{{ url('/check_seuil_minimum') }}", {
                                            article_id: type_sortie,
                                            devise: devise,
                                            quantite: quantite,
                                            taille_lot: get_prix_article[0][1],
                                            prix_unitaire: get_prix_article[0][0],
                                            devise: $("#devise").val(),
                                            taux: taux,
                                            table_id: $("#table_id").val(),
                                        }, function(repp) {
                                            var data_rep = repp.split("__________")
                                            if ((data_rep[0] == -1) && (data_rep[3] == 1))
                                            {
                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le stock de cette article est vide');
                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                                resetButton();
                                                return;
                                            } else {
                                                if(client.trim().length == 0 && libelle.trim().length == 0)
                                                {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le client ou le libellé');
                                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                                    resetButton();
                                                    return;
                                                } else {
                                                    // Envoi de l'ajout
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "/add_achat_article",
                                                        data: data,
                                                        success: function(response) {
                                                            // Succès : réinitialiser le bouton
                                                            resetButton();
                                                            $("#quantite").val("");
                                                            Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                                            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Achat effectué avec succès');
                                                            $("#content_utilisateur").html(response);
                                                            $.get("{{ url('/get_achat') }}", {}, function(response) {
                                                                $("#bloc_3").html(response);
                                                            });
                                                            // 👇 AJOUT : Rafraîchir la liste des tables
                                                            $.get("{{ url('/get_tables_select') }}", function(response) {
                                                                $("#table_id").html(response);
                                                                // Cacher le bouton libérer car la table devient occupée
                                                                $("#liberer_table").hide();
                                                            });
                                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                                            // Sauvegarder et réappliquer les filtres
                                                            saveFiltersToStorage();
                                                            setTimeout(function() {
                                                                loadFiltersFromStorage();
                                                                filterInvoices();
                                                            }, 100);
                                                        },
                                                        error: function(xhr, status, error) {
                                                            resetButton();
                                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'enregistrement');
                                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                                            console.error(error);
                                                        }
                                                    });
                                                }
                                            }
                                        }).fail(function() {
                                            resetButton();
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de la vérification du seuil');
                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                        });
                                    }).fail(function() {
                                        resetButton();
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de la récupération du prix');
                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                    });
                                }
                            }
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
                saveFiltersToStorage();
                setTimeout(function() {
                    loadFiltersFromStorage();
                    filterInvoices();
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

        $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) {
            $("#numero_facture").html(response);
        });

        $("#oui_p").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_reprise_article') }}", {
                id: id,
            }, function(refresh_reprise_article) {
                $("#bloc_3").html(refresh_reprise_article);
                $("#non_p").trigger("click");
            });
        });

        // ========== FONCTIONS DE FILTRAGE AVEC DATE RANGE PICKER (MISE À JOUR) ==========
        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                numero: $('#filterNumero').val(),
                client: $('#filterClient').val(),
                user: $('#filterUser').val(),
                statut: $('#filterStatut').val(),
                dateRange: $('#filterDateRange').val(),
                montant: $('#filterMontant').val(),
                table: $('#filterTable').val()
            };
            localStorage.setItem('invoiceFilters', JSON.stringify(filters));
        }

        function loadFiltersFromStorage() {
            const savedFilters = localStorage.getItem('invoiceFilters');
            if (savedFilters) {
                const filters = JSON.parse(savedFilters);
                $('#filterNumero').val(filters.numero || '');
                $('#filterClient').val(filters.client || '');
                $('#filterUser').val(filters.user || '');
                $('#filterStatut').val(filters.statut || 'all');
                $('#filterDateRange').val(filters.dateRange || '');
                $('#filterMontant').val(filters.montant || '');
                $('#filterTable').val(filters.table || '');
                return true;
            }
            return false;
        }

        function filterInvoices() {
            const filterNumero = $('#filterNumero').val().toLowerCase();
            const filterClient = $('#filterClient').val().toLowerCase();
            const filterUser = $('#filterUser').val().toLowerCase();
            const filterStatut = $('#filterStatut').val();
            const filterMontant = parseFloat($('#filterMontant').val());
            const filterTable = $('#filterTable').val().toLowerCase();

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
            let totalPaidUSD = 0, totalPaidCDF = 0;
            let totalCreditUSD = 0, totalCreditCDF = 0;
            let totalBeneficeUSD = 0, totalBeneficeCDF = 0;

            $('#content_utilisateur tbody tr').each(function() {
                const $row = $(this);
                let showRow = true;

                const numeroValue = $row.find('.numero-cell').data('numero')?.toLowerCase() || '';
                const clientValue = $row.find('.client-cell').data('client')?.toLowerCase() || '';
                const userValue = $row.find('.user-cell').data('user')?.toLowerCase() || '';
                const statutValue = $row.find('.statut-cell').data('statut') || '';
                const montantRaw = parseFloat($row.find('.montant-cell').data('montant')) || 0;
                const tableValue = $row.find('.table-cell').data('table')?.toLowerCase() || '';

                if (filterNumero && !numeroValue.includes(filterNumero)) showRow = false;
                if (showRow && filterClient && !clientValue.includes(filterClient)) showRow = false;
                if (showRow && filterUser && !userValue.includes(filterUser)) showRow = false;
                if (showRow && filterStatut !== 'all' && statutValue !== filterStatut) showRow = false;
                if (showRow && !isNaN(filterMontant) && Math.abs(montantRaw - filterMontant) > 0.009) showRow = false;
                if (showRow && filterTable && !tableValue.includes(filterTable)) showRow = false;

                if (showRow && dateDebut && dateFin) {
                    var dateText = $row.find('.date-cell').text().trim();
                    var cellDate = null;
                    if (dateText) {
                        var datePart = dateText.split(' à ')[0];
                        if (datePart) {
                            var partsDate = datePart.split('/');
                            if (partsDate.length === 3) {
                                var d = partsDate[0];
                                var m = partsDate[1];
                                var y = partsDate[2];
                                if (d && m && y && d.length === 2 && m.length === 2 && y.length === 4) {
                                    cellDate = y + '-' + m + '-' + d;
                                }
                            }
                        }
                    }
                    if (cellDate) {
                        if (cellDate < dateDebut || cellDate > dateFin) {
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
                    totalPaidUSD += parseFloat($row.data('paye-usd')) || 0;
                    totalPaidCDF += parseFloat($row.data('paye-cdf')) || 0;
                    totalCreditUSD += parseFloat($row.data('credit-usd')) || 0;
                    totalCreditCDF += parseFloat($row.data('credit-cdf')) || 0;
                    totalBeneficeUSD += parseFloat($row.data('benefice-usd')) || 0;
                    totalBeneficeCDF += parseFloat($row.data('benefice-cdf')) || 0;
                } else {
                    $row.hide();
                }
            });

            // Mise à jour des badges
            $('#invoiceCount').text(visibleCount);
            $('#totalUsd').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdf').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidUsd').text(totalPaidUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidCdf').text(totalPaidCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditUsd').text(totalCreditUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditCdf').text(totalCreditCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeUsd').text(totalBeneficeUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeCdf').text(totalBeneficeCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            if (visibleCount === 0 && (filterNumero || filterClient || filterUser || filterStatut !== 'all' || dateRange || !isNaN(filterMontant) || filterTable)) {
                $('#msg').html('<i class="zmdi zmdi-info"></i> Aucune facture ne correspond aux critères de recherche');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 3000);
            }
        }

        function resetAllFilters() {
            var today = moment();
            var todayStr = today.format('DD/MM/YYYY');
            $('#filterDateRange').val(todayStr + ' - ' + todayStr);
            if ($('#filterDateRange').data('daterangepicker')) {
                $('#filterDateRange').data('daterangepicker').setStartDate(today);
                $('#filterDateRange').data('daterangepicker').setEndDate(today);
            }

            $('#filterNumero').val('');
            $('#filterClient').val('');
            $('#filterUser').val('');
            $('#filterStatut').val('all');
            $('#filterMontant').val('');
            $('#filterTable').val('');

            saveFiltersToStorage();
            filterInvoices();

            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }

        function debouncedFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                filterInvoices();
                saveFiltersToStorage();
            }, 300);
        }

        // ========== INITIALISATION (COMME DANS RAPPORT) ==========
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
                filterInvoices();
                saveFiltersToStorage();
            });

            $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                filterInvoices();
                saveFiltersToStorage();
            });

            // Nombre total de factures initial
            const totalInvoices = $('#content_utilisateur tbody tr').length;
            $('#invoiceCount').text(totalInvoices);

            // Charger les filtres sauvegardés (s'ils existent, écrase la valeur par défaut)
            const hasSaved = loadFiltersFromStorage();
            if (!hasSaved) {
                // déjà initialisée
            }
            filterInvoices();

            // Événements des autres filtres
            $('#filterNumero, #filterClient, #filterUser, #filterStatut, #filterMontant, #filterTable').on('input change', function() {
                debouncedFilter();
            });

            // Réinitialisation
            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetAllFilters();
            });

            // ========== AJOUT : GESTION DE LA SUPPRESSION DES FACTURES AVEC DÉTAILS ==========
            $(document).on('click', '.delete-facture-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var numero = $(this).data('numero');
                var client = $(this).data('client');
                var montant = $(this).data('montant');
                var date = $(this).data('date');
                var statut = $(this).data('statut');

                $('#delete_facture_numero').text(numero);
                $('#delete_facture_client').text(client);
                $('#delete_facture_montant').text(montant);
                $('#delete_facture_date').text(date);
                $('#delete_facture_statut').text(statut);

                $('#deleteFactureModal').data('facture-id', id);
                $('#deleteFactureModal').modal('show');
            });

            $(document).on('click', '#confirm_delete_facture', function(e) {
                e.preventDefault();
                var btn = $(this);
                var factureId = $('#deleteFactureModal').data('facture-id');
                if (!factureId) {
                    alert('Identifiant de facture manquant.');
                    return;
                }

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Suppression...');

                $.ajax({
                    type: 'POST',
                    url: '{{ url("/delete_facture") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: factureId
                    },
                    success: function(response) {
                        $.get('{{ url("/get_all_facture") }}', function(html) {
                            $('#content_utilisateur').html(html);
                            saveFiltersToStorage();
                            setTimeout(function() {
                                loadFiltersFromStorage();
                                filterInvoices();
                            }, 100);
                        }).fail(function() {
                            alert('Erreur lors du rechargement du tableau.');
                        });

                        $('#deleteFactureModal').modal('hide');
                        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Facture supprimée avec succès');
                        $('#msg').css('display', 'flex');
                        setTimeout(() => {
                            $('#msg').html('');
                            $('#msg').css('display', 'none');
                        }, 3000);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur de suppression :', error);
                        alert('Une erreur est survenue lors de la suppression. Veuillez réessayer.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Oui, supprimer');
                    }
                });
            });
        });

        // Sauvegarde automatique avant de quitter
        window.addEventListener('beforeunload', function() {
            saveFiltersToStorage();
        });

        // ===== GESTION DU PAIEMENT PDF (inchangée) =====
        var currentPdfUrl = "";
        var cdf_montant_payer = $("#cdf_montant_payer").val();
        var usd_montant_payer = $("#usd_montant_payer").val();
        var payer = $("#payer").val();

        function convertirEnNombre(valeur) {
            if (!valeur && valeur !== 0) return NaN;
            let str = String(valeur).trim();
            str = str.replace(/\s/g, '');
            str = str.replace(',', '.');
            return parseFloat(str);
        }

        document.getElementById('btn_payer').addEventListener('click', async function() {
            const btnPayer = this;
            btnPayer.disabled = true;
            const msgFacture = document.getElementById('msg_facture');
            const originalBtnText = btnPayer.innerHTML;
            btnPayer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Chargement...';
            msgFacture.innerHTML = '⏳ Traitement en cours...';
            msgFacture.style.color = '#17a2b8';

            try {
                const factureId = $("#id_fac").val();
                if (!factureId || factureId === "") {
                    msgFacture.innerHTML = '❌ Erreur: Identifiant de facture manquant';
                    msgFacture.style.color = '#dc3545';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                // 1. Vérifier si déjà payée
                const checkPaie = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
                if (checkPaie == 1) {
                    msgFacture.innerHTML = '⚠️ Facture déjà payée';
                    msgFacture.style.color = '#ffc107';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                // 2. Récupérer les montants restants
                const detailData = await $.get("{{ url('/get_all_detail_achat_paie') }}", { facture_id: factureId });
                const {
                    montant_usd_1,
                    montant_cdf_1,
                    montant_usd_2,
                    montant_cdf_2,
                    usd_montant_total_a_payer,
                    cdf_montant_total_a_payer
                } = detailData;

                $("#usd_montant_payer").val(usd_montant_total_a_payer);
                $("#cdf_montant_payer").val(cdf_montant_total_a_payer);

                const montantRecuRaw = document.getElementById('montant_recu').value;
                const montantRecu = convertirEnNombre(montantRecuRaw);
                const deviseRecu = document.getElementById('devise_recu').value;

                if (isNaN(montantRecu) || montantRecu <= 0) {
                    msgFacture.innerHTML = '❌ Veuillez entrer un montant valide';
                    msgFacture.style.color = '#dc3545';
                    document.getElementById('montant_recu').focus();
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                if (!deviseRecu || deviseRecu === "") {
                    msgFacture.innerHTML = '❌ Veuillez sélectionner une devise';
                    msgFacture.style.color = '#dc3545';
                    document.getElementById('devise_recu').focus();
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                const deviseLabel = deviseRecu === "0" ? "USD" : "CDF";
                let montantRestant;
                if (deviseRecu === "0") {
                    montantRestant = usd_montant_total_a_payer;
                } else {
                    montantRestant = cdf_montant_total_a_payer;
                }

                if (isNaN(montantRestant) || montantRestant <= 0) {
                    msgFacture.innerHTML = '❌ Facture déjà payée ou aucun montant restant à payer dans cette devise';
                    msgFacture.style.color = '#dc3545';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 15000);
                    return;
                }

                let montantPaye, monnaie;
                if (montantRecu >= montantRestant) {
                    montantPaye = montantRestant;
                    monnaie = montantRecu - montantRestant;
                } else {
                    montantPaye = montantRecu;
                    monnaie = 0;
                }

                // 3. Sauvegarder le paiement
                const saveResponse = await $.post("{{ url('/save_paie_facture') }}", {
                    _token: "{{ csrf_token() }}",
                    facture_id: factureId,
                    montant_recu: montantRecu,
                    devise_recu: deviseRecu,
                    montant_paye: montantPaye,
                    monnaie: monnaie,
                });

                if (saveResponse.success || saveResponse == 1) {
                    const msg = `✅ PAIEMENT ${montantPaye === montantRestant ? 'TOTAL' : 'PARTIEL'} RÉUSSI !<br>
                                📄 Reste avant paiement : ${montantRestant.toFixed(2)} ${deviseLabel}<br>
                                💵 Montant reçu : ${montantRecu.toFixed(2)} ${deviseLabel}<br>
                                💰 Montant imputé : ${montantPaye.toFixed(2)} ${deviseLabel}<br>
                                ${monnaie > 0 ? `🔄 Monnaie rendue : ${monnaie.toFixed(2)} ${deviseLabel}` : ''}
                                ${montantPaye < montantRestant ? `📌 Nouveau reste : ${(montantRestant - montantPaye).toFixed(2)} ${deviseLabel}` : '✅ Facture soldée'}`;
                    msgFacture.innerHTML = msg;
                    msgFacture.style.color = '#28a745';
                    document.getElementById('montant_recu').value = '';

                    // Rafraîchir le PDF si nécessaire
                    const pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";
                    if (pdfUrl && pdfUrl !== '') {
                        currentPdfUrl = pdfUrl;
                        $("#pdfIframe").attr("src", pdfUrl);
                    } else {
                        const pdfResponse = await $.get("{{ url('/print_facture') }}", { facture_id: factureId });
                        if (pdfResponse && pdfResponse[0] && pdfResponse[0][0]) {
                            currentPdfUrl = pdfResponse[0][0];
                            $("#pdfIframe").attr("src", pdfResponse[0][0]);
                            $("#cdf_montant_payer").val(pdfResponse[0][1] || 0);
                            $("#usd_montant_payer").val(pdfResponse[0][2] || 0);
                            $("#payer").val(pdfResponse[0][5] || '');
                        } else {
                            alert("Aucun PDF disponible pour cette facture.");
                        }
                    }

                    // Mettre à jour le tableau des factures
                    await $.get("{{ url('/get_all_facture') }}", {}, function(response) {
                        $("#content_utilisateur").html(response);
                    });

                    // Réappliquer les filtres
                    saveFiltersToStorage();
                    setTimeout(function() {
                        loadFiltersFromStorage();
                        filterInvoices();
                    }, 200);
                } else {
                    msgFacture.innerHTML = '❌ Erreur lors de l\'enregistrement du paiement';
                    msgFacture.style.color = '#dc3545';
                }
                setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);

            } catch (error) {
                console.error("Erreur API:", error);
                msgFacture.innerHTML = '❌ Erreur de connexion à l\'API';
                msgFacture.style.color = '#dc3545';
                setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
            } finally {
                btnPayer.disabled = false;
                btnPayer.innerHTML = originalBtnText;
                if (msgFacture.innerHTML === '⏳ Traitement en cours...') {
                    msgFacture.innerHTML = '';
                }
            }
        });

        $("#pdfModal").on("hidden.bs.modal", function() {
            $("#pdfIframe").attr("src", "");
        });

        // ================================================================
        // GESTION DU CHARGEMENT DES ARTICLES SELON LA TABLE
        // ================================================================

        // Fonction pour charger les articles disponibles pour une table donnée
        function loadArticlesForTable(tableId) {
            // Si aucun ID de table ou vide, on vide le select des articles
            if (!tableId || tableId.trim() === '') {
                $("#type_sortie").html('<option value="">Selectionnez un article</option>');
                return;
            }

            // Appel AJAX à la route get_articles_by_table (à créer côté Laravel)
            $.get("{{ url('/get_articles_select') }}", { table_id : tableId })
                .done(function(response) {
                    // Remplacer le contenu du select #type_sortie par la réponse HTML (options)
                    $("#type_sortie").html(response);

                    // Si un article était déjà sélectionné, on déclenche son événement change
                    // pour que l'écouteur existant sur #type_sortie mette à jour les détails
                    var currentArticle = $("#type_sortie").val();
                    if (currentArticle && currentArticle.trim() !== '') {
                        $("#type_sortie").trigger('change');
                    }
                })
                .fail(function() {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors du chargement des articles pour cette table');
                    setTimeout(function() {
                        $('#msg').html('');
                    }, 9000);
                });
        }

        // Écouteur sur le changement de la table
        $("#table_id").on('change', function() {
            var tableId = $(this).val();
            loadArticlesForTable(tableId);
        });

        // Initialisation au chargement de la page
        $(document).ready(function() {
            // ... vos autres initialisations (datepicker, filtres, etc.) ...

            // Si une table est déjà sélectionnée dans le select, on charge ses articles
            var initialTableId = $("#table_id").val();
            if (initialTableId && initialTableId.trim() !== '') {
                loadArticlesForTable(initialTableId);
            }
        });
    </script>
@endsection
@endsection