@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Mois;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Facturesnormalisees;
use App\Models\Writes;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'CHARGER FACTURE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (CATÉGORIE + FACTURE)
   ============================================================ */

body { margin: 0; padding: 0; background: #f0f4f8; }

.content .container {
    max-width: 100% !important;
    width: 100%;
    padding: 0.5rem 1.5rem !important;
    margin: 0 auto;
    background: #f8fafc;
}
.content .container .row { margin-left: 0; margin-right: 0; }
.content .container [class*="col-"] { padding-left: 0.75rem; padding-right: 0.75rem; }

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

#bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 {
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
    gap: 15px;
    flex-wrap: wrap;
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

.category-count-badge {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    color: white;
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: var(--shadow-light);
    margin-left: 10px;
}

/* ========== TABLEAU ========== */
.table-responsive {
    border-radius: var(--border-radius-lg);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table {
    width: 100%;
    min-width: 600px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
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
.table tbody tr { transition: all 0.15s ease; border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd) { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; cursor: default; }
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
.table tbody td:last-child { text-align: center; vertical-align: middle; }

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
.table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
.table tbody td a i.zmdi-edit { color: #10b981; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a i.zmdi-eye.text-info { color: #3b82f6 !important; }
.table tbody td a i.zmdi-settings.text-success { color: #10b981 !important; }
.table tbody td a:hover { background: #e0f2fe; transform: translateY(-2px); }
.table tbody td a:hover i.zmdi-delete { color: #b91c1c; }
.table tbody td a:hover i.zmdi-eye.text-info { color: #2563eb !important; }
.table tbody td a:hover i.zmdi-settings.text-success { color: #059669 !important; }

/* ========== BOUTONS ========== */
#liste, #add, #print, #add_r, #print_r,
#save, #annuler, #edit_save, #edit_annuler,
#resetFilters, #importer, #exporter,
.btn-primary, .btn-info, .btn-danger, .btn-secondary, .btn-dark {
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
#liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
#liste:hover, .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    background: #2563eb !important;
}
#add, #print, .btn-info { background: var(--bleu-nuit-gradient) !important; color: white !important; }
#add:hover, #print:hover, .btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}
#add_r, #print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed;
    opacity: 0.7;
    box-shadow: none;
}
#save, #edit_save { background: var(--bleu-secondaire-gradient) !important; color: white; }
#save:hover, #edit_save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
}
#annuler, #edit_annuler, .btn-danger { background: var(--rouge-gradient) !important; color: white; }
#annuler:hover, #edit_annuler:hover, .btn-danger:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
}
#resetFilters { background: #64748b !important; color: white !important; }
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
}
#importer { background: var(--rouge-gradient) !important; color: white !important; }
#exporter, .btn-dark { background: #1e293b !important; color: white !important; }

/* ========== FILTRES + BADGE ========== */
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

.invoice-count-badge {
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    color: white;
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    box-shadow: var(--shadow-light);
    margin-left: 10px;
}

/* ========== FORMULAIRES ========== */
#form_add .row, #form_edit .row { display: flex; flex-wrap: wrap; }
#form_add .col-6, #form_edit .col-6 { margin-bottom: 0.8rem; }
.form-group { width: 100%; margin-bottom: 0; }
.form-group label {
    display: block;
    font-weight: 700;
    color: var(--bleu-nuit);
    margin-bottom: 4px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-group label i { color: #e31b23; margin-right: 6px; }

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
.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    transform: translateY(-1px);
}
.input-mask { font-family: monospace; background: #fff9ef !important; }

#form_add .form-control,
#form_add select.form-control,
#form_add input.form-control,
#form_add input[type="file"].form-control {
    border: 2px solid #94a3b8 !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    height: 42px !important;
    padding: 8px 14px !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    box-shadow: none !important;
    width: 100% !important;
}
#form_add .form-control:hover,
#form_add select.form-control:hover,
#form_add input.form-control:hover,
#form_add input[type="file"].form-control:hover {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
#form_add .form-control:focus,
#form_add select.form-control:focus,
#form_add input.form-control:focus,
#form_add input[type="file"].form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 4px rgba(10, 25, 47, 0.15) !important;
    outline: none !important;
}

/* ====== SELECT2 ====== */
#form_add .select2-container { width: 100% !important; }
#form_add .select2-container--default .select2-selection--single {
    border: 2px solid #94a3b8 !important;
    border-radius: 12px !important;
    background: #ffffff !important;
    height: 42px !important;
    padding: 6px 14px !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
}
#form_add .select2-container--default .select2-selection--single:hover {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
#form_add .select2-container--default.select2-container--focus .select2-selection--single,
#form_add .select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 4px rgba(10, 25, 47, 0.15) !important;
    outline: none !important;
}
#form_add .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e2a3e !important;
    line-height: 28px !important;
    padding-left: 0 !important;
    padding-right: 25px !important;
    font-weight: 500 !important;
}
#form_add .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #94a3b8 !important; }
#form_add .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
}
#form_add .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #e31b23 transparent transparent transparent !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--bleu-nuit) !important;
    color: white !important;
}

/* ====== INPUT FILE ====== */
#form_add input[type="file"].form-control {
    padding: 6px 12px !important;
    cursor: pointer;
    background: #f8fafc !important;
    color: #1e2a3e;
    display: flex !important;
    align-items: center !important;
}
#form_add input[type="file"].form-control::file-selector-button {
    background: var(--bleu-nuit-gradient);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 14px;
    margin-right: 12px;
    font-weight: 600;
    font-size: 0.78rem;
    cursor: pointer;
    transition: all 0.2s ease;
    height: 30px;
}
#form_add input[type="file"].form-control::file-selector-button:hover {
    background: linear-gradient(135deg, #1e3a5f, #0a192f);
    transform: translateY(-1px);
}

#form_add .form-group label {
    font-size: 0.78rem;
    margin-bottom: 6px;
    color: #0a192f;
    display: block;
}
#form_add .form-group label i { color: #e31b23; }

/* ========== MESSAGES ========== */
#msg, #edit_msg {
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
#msg:not(:empty), #edit_msg:not(:empty) {
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
@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ========== BARRE D'ACTIONS ========== */
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

/* ========== MODALES ========== */
.modal.fade .modal-content {
    border-radius: var(--border-radius-lg);
    border: none;
    box-shadow: var(--shadow-premium);
    overflow: hidden;
}
.modal.fade .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    border-bottom: none;
    padding: 1rem 1.5rem;
}
.modal.fade .modal-header .modal-title { font-weight: 700; font-size: 1.1rem; color: white; }
.modal.fade .modal-header .close { color: white; opacity: 0.8; text-shadow: none; }
.modal.fade .modal-header .close:hover { opacity: 1; }
.modal.fade .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
    padding: 1rem 1.5rem;
}
.modal.fade .modal-footer .btn { border-radius: 40px !important; padding: 6px 18px !important; font-weight: 600; }

/* ========== SPINNER DE CHARGEMENT SUR LE BOUTON ========== */
#save.loading,
#save:disabled {
    background: var(--bleu-secondaire-gradient) !important;
    cursor: not-allowed !important;
    opacity: 0.85;
    pointer-events: none;
}
#save .spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spinSave 0.7s linear infinite;
    margin-left: 6px;
    vertical-align: middle;
}
@keyframes spinSave {
    to { transform: rotate(360deg); }
}

/* ========== APERÇU PDF (IFRAME) – Formulaire ========== */
#pdf_preview_container {
    display: none;
    margin-top: 25px;
    margin-bottom: 20px;
    padding: 16px;
    background: linear-gradient(135deg, #f8fafc, #eff6ff);
    border: 2px dashed #3b82f6;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    animation: slideInMsg 0.4s ease-out;
    position: relative;
}
#pdf_preview_container.show { display: block !important; }

#pdf_preview_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dbeafe;
}
#pdf_preview_header .pdf-title {
    font-weight: 700;
    color: var(--bleu-nuit);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
#pdf_preview_header .pdf-title i {
    color: #e31b23;
    font-size: 1.4rem;
}
#pdf_preview_header .pdf-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.25);
}
#pdf_preview_close {
    background: #fee2e2;
    color: #b91c1c;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    font-weight: bold;
}
#pdf_preview_close:hover {
    background: #fecaca;
    transform: rotate(90deg);
}
#pdf_preview_frame {
    width: 100%;
    height: 600px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    background: white;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* ========== MODALE APERÇU FICHIER (Tableau) ========== */
#modal_view_file .modal-dialog {
    max-width: 90%;
    height: 90%;
    margin: 1.75rem auto;
}
#modal_view_file .modal-content {
    height: 90vh;
    display: flex;
    flex-direction: column;
}
#modal_view_file .modal-body {
    flex: 1;
    padding: 0;
    background: #f1f5f9;
    overflow: hidden;
}
#modal_view_file iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: white;
}
#modal_view_file .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
#modal_view_file .modal-header .file-name {
    color: #cbd5e1;
    font-size: 0.8rem;
    font-weight: 500;
    margin-left: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ---- bandeau de détails dans la modale d'aperçu ---- */
#modal_view_file #view_file_details {
    flex-shrink: 0;
    padding: 10px 18px;
    background: linear-gradient(135deg, #eff6ff, #e0f2fe);
    border-bottom: 1px solid #dbeafe;
    display: flex;
    flex-wrap: wrap;
    gap: 8px 22px;
    font-size: 0.82rem;
    color: #0a192f;
    font-weight: 600;
}
#modal_view_file #view_file_details .detail-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
#modal_view_file #view_file_details .detail-item i {
    color: #e31b23;
    font-size: 1.05rem;
}

/* ---- détails dans la modale de suppression ---- */
#suppression #element {
    text-align: center;
}
#suppression #element .del-title {
    font-weight: 700;
    color: #0a192f;
    font-size: 0.95rem;
    margin-bottom: 8px;
}
#suppression #element .del-line {
    font-size: 0.82rem;
    color: #475569;
    margin-top: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
#suppression #element .del-line i {
    color: #e31b23;
}

@media (max-width: 768px) {
    #pdf_preview_frame { height: 400px; }
    #pdf_preview_header .pdf-title { font-size: 0.8rem; }
    #modal_view_file .modal-dialog { max-width: 98%; }
    #modal_view_file .modal-content { height: 85vh; }
    #modal_view_file #view_file_details { font-size: 0.75rem; padding: 8px 12px; }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 { padding: 1rem !important; }
}
@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 { padding: 0.8rem !important; }
    #liste, #add, #print, #add_r, #print_r,
    #save, #annuler, #edit_save, #edit_annuler,
    #resetFilters, #importer, #exporter,
    .btn-primary, .btn-info, .btn-danger, .btn-dark {
        padding: 4px 12px !important;
        font-size: 0.7rem;
    }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    #form_add .col-6, #form_edit .col-6 { flex: 0 0 100%; max-width: 100%; }
    .form-group label { font-size: 0.65rem; }
    .form-control, input.form-control, select.form-control, textarea.form-control {
        height: 34px !important;
        font-size: 0.75rem;
    }
    .filters-container {
        flex-direction: column;
        gap: 8px;
        padding: 0.6rem 0.8rem;
    }
    .filter-group {
        width: 100%;
        min-width: 100%;
    }
    .filter-group .form-control {
        height: 34px !important;
    }
    .invoice-count-badge {
        font-size: 0.7rem;
        padding: 3px 10px;
        margin-left: 0;
    }
}
@media (max-width: 480px) {
    .content .container { padding: 0.3rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 { padding: 0.6rem !important; }
    h4 { font-size: 1.1rem; margin-bottom: 12px; }
    h4 i { font-size: 24px !important; }
}
</style>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
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
    <div style="margin-top: 30px;padding-bottom: 50px;" class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                        class="zmdi zmdi-chevron-right"></i> &nbsp; Chargement de facture</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                    Liste
                    <span class="invoice-count-badge">
                        <i class="zmdi zmdi-view-list" style="color:white;"></i>
                        Factures : <span id="invoiceCount">0</span>
                    </span>
                </h4>

                {{-- ================= FILTRES ================= --}}
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Client</label>
                        <input type="text" id="filterClient" class="form-control" placeholder="Rechercher par client...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Mois</label>
                        <input type="text" id="filterMois" class="form-control" placeholder="Rechercher par mois...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar-note text-danger"></i> Année</label>
                        <input type="text" id="filterAnnee" class="form-control" placeholder="Rechercher par année...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-collection-pdf text-danger"></i> Fichier</label>
                        <input type="text" id="filterFichier" class="form-control" placeholder="Rechercher par fichier...">
                    </div>
                    <div class="filter-group" style="flex: 0 0 auto;">
                        <button id="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <div id="content_groupe" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="facturesTable">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Mois</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Fichier</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{ !($i = 1) }}
                                    @foreach ($facturesnormalisees as $data)
                                    @php
                                        /* === Récupération du nom du client pour cette ligne === */
                                        $clientNom = '';
                                        if (!empty($data->client_id)) {
                                            foreach ($clients as $c) {
                                                if ($c->id == $data->client_id) { $clientNom = $c->name; break; }
                                            }
                                        }
                                        /* === Récupération du libellé mois/année === */
                                        $moisNom  = Mois::where(['id' => $data->moi_id])->first()['nom'] ?? '';
                                        $anneeNom = Annees::where(['id' => $data->annee_id])->first()['annees'] ?? '';
                                    @endphp
                                    <tr class="facture-row"
                                        data-client="{{ strtolower($clientNom) }}"
                                        data-mois="{{ strtolower($moisNom) }}"
                                        data-annee="{{ strtolower($anneeNom) }}"
                                        data-fichier="{{ strtolower($data->fichier_original) }}">
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ $i }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell">
                                            {{ $clientNom !== '' ? $clientNom : '—' }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="mois-cell">
                                            {{ $moisNom }}
                                            {{ $anneeNom }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="fichier-cell">
                                            <i class="zmdi zmdi-collection-pdf text-danger"></i>
                                            {{ $data->fichier_original }}
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            {{-- ===== ICÔNE 1 : VOIR LE FICHIER (OEIL) ===== --}}
                                            <a id="view_<?= $i ?>" href="#" title="Voir le fichier">
                                                <i class="zmdi zmdi-eye text-info"></i>
                                            </a>
                                            &nbsp;&nbsp;
                                            {{-- ===== ICÔNE 2 : VOIR LE Modifier (OEIL) ===== --}}
                                            <a id="edit_<?= $i ?>" href="#" title="Modifier">
                                                <i class="zmdi zmdi-edit text-success"></i>
                                            </a>
                                            &nbsp;&nbsp;
                                            {{-- ===== ICÔNE 3 : SUPPRIMER (ROUGE) ===== --}}
                                            <a id="delete_<?= $i ?>" href="#" title="Supprimer">
                                                <i class="zmdi zmdi-delete text-danger"></i>
                                            </a>

                                            <script>
                                            /* ===== VOIR LE FICHIER DANS LA MODALE (avec détails) ===== */
                                            $("#view_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                var fileUrl = "{{ asset($data->lien) }}";
                                                var fileName = "{{ $data->fichier_original }}";
                                                var moisNom = "<?= $moisNom ?>";
                                                var anneeNom = "<?= $anneeNom ?>";
                                                var clientNom = "<?= $clientNom ?>";

                                                $("#modal_view_file .file-name").html(
                                                    '<i class="zmdi zmdi-collection-pdf"></i> ' + fileName
                                                );

                                                /* ==== Entête détails de la ligne ==== */
                                                $("#view_file_details").html(
                                                    '<span class="detail-item"><i class="zmdi zmdi-calendar"></i> Mois : ' + (moisNom || '—') + ' ' + (anneeNom || '') + '</span>' +
                                                    '<span class="detail-item"><i class="zmdi zmdi-account"></i> Client : ' + (clientNom || '—') + '</span>' +
                                                    '<span class="detail-item"><i class="zmdi zmdi-collection-pdf"></i> Fichier : ' + (fileName || '—') + '</span>'
                                                );

                                                $("#modal_view_file iframe").attr("src", fileUrl);
                                                $("#modal_view_file").modal("show");
                                            });

                                            /* ===== MODIFIER ===== */
                                            $("#edit_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $.get("{{ url('/refresh_editfacturesnormalisees') }}", {
                                                    facturesnormalise_id: <?= $data->id ?>,
                                                }, function(refresh_editutilisateur) {
                                                    $("#bloc_1").hide();
                                                    $("#bloc_2").hide();
                                                    $("#bloc_3").show();
                                                    $("#bloc_3").html(refresh_editutilisateur);
                                                });
                                            });

                                            /* ===== SUPPRIMER (avec détails) ===== */
                                            $("#delete_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $("#element").html(
                                                    '<div class="del-title"><?= $moisNom ?> <?= $anneeNom ?></div>' +
                                                    '<div class="del-line"><i class="zmdi zmdi-account"></i> Client : <?= $clientNom !== '' ? $clientNom : '—' ?></div>' +
                                                    '<div class="del-line"><i class="zmdi zmdi-collection-pdf"></i> <?= $data->fichier_original ?></div>'
                                                );
                                                $("#data_id").html("<?= $data->id ?>");
                                                $("#btn_sup").trigger("click");
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

            <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                    Ajouter</h4>
                <form id="form_add" action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                    <i class="zmdi zmdi-calendar"></i> Année
                                </label>
                                <select id="annee_id" name="annee_id" class="select2 form-control"
                                    data-placeholder="Selectionnez une année">
                                    <option selected value="">Selectionnez une année</option>
                                    @foreach ($annees as $data)
                                    <option value="{{ $data->id }}"><?= $data->annees ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                    <i class="zmdi zmdi-calendar"></i> Mois
                                </label>
                                <select id="moi_id" name="moi_id" class="select2 form-control"
                                    data-placeholder="Selectionnez un mois">
                                    <option selected value="">Selectionnez un mois</option>
                                    @foreach ($mois as $data)
                                    <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                    <i class="zmdi zmdi-account"></i> Client
                                </label>
                                <select id="client_id" name="client_id" class="select2 form-control"
                                    data-placeholder="Selectionnez un client">
                                    <option selected value="">Selectionnez un client</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"><?= $client->name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                    <i class="zmdi zmdi-upload"></i> Fichier à charger (PDF uniquement)
                                </label>
                                <input type="file" id="fichier" name="fichier" class="form-control"
                                    accept=".pdf,application/pdf">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button id="save" class="btn btn-info btn-sm">
                                <span class="btn-text">Enregister</span>
                                <i class="zmdi zmdi-save btn-icon"></i>
                            </button>
                            <button id="annuler" type="button" class="btn btn-danger btn-sm">
                                Annuler <i class="zmdi zmdi-close-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg">
                            </span>
                        </div>
                    </div>

                    {{-- ================= APERÇU PDF (APRÈS LES BOUTONS) ================= --}}
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-12">
                            <div id="pdf_preview_container">
                                <div id="pdf_preview_header">
                                    <div class="pdf-title">
                                        <i class="zmdi zmdi-collection-pdf"></i>
                                        Aperçu du document PDF
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span class="pdf-badge">
                                            <i class="zmdi zmdi-check-circle"></i> Prêt à soumettre
                                        </span>
                                        <button type="button" id="pdf_preview_close" title="Fermer l'aperçu">
                                            ×
                                        </button>
                                    </div>
                                </div>
                                <iframe id="pdf_preview_frame" src=""></iframe>
                            </div>
                        </div>
                    </div>
                    {{-- ================= FIN APERÇU PDF ================= --}}
                </form>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">

            </div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">

            </div>
            <div id="bloc_5" style="margin-top: 12px;display: none;" class="col-lg-12">
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</section>
<span id="listesfactures_id" style="display: none;"></span>
<span id="data_id" style="display: none;"></span>
<span id="data_frais_id" style="display: none;"></span>
<span id="devise_paie_id" style="display: none;"></span>

{{-- ================= MODALE SUPPRESSION ================= --}}
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                    supprimez cette facture ? </h5>
            </div>
            <div class="modal-body">
                {{-- #element contient désormais les détails (mois/année, client, fichier) --}}
                <div id="element"></div>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm"
                        data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODALE APERÇU FICHIER (OEIL) ================= --}}
<div class="modal fade" id="modal_view_file" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <h5 class="modal-title" style="font-weight: bold;">
                        <i class="zmdi zmdi-collection-pdf"></i> Aperçu du fichier
                    </h5>
                    <span class="file-name"></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"
                    style="color: white; opacity: 1; font-size: 1.8rem; line-height: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- ====== petit bandeau avec les détails de la ligne ====== --}}
            <div id="view_file_details"></div>
            <div class="modal-body">
                <iframe id="view_file_frame" src="" frameborder="0"></iframe>
            </div>
            {{-- ====== pied de modale avec bouton Fermer ====== --}}
            <div class="modal-footer" style="justify-content: flex-end;">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    Fermer <i class="zmdi zmdi-close-circle"></i>
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
$("#link_56").addClass("active");

/* ============================================================
   APERÇU PDF DANS IFRAME (Formulaire d'ajout)
   ============================================================ */
var currentPdfObjectUrl = null;

$("#fichier").on("change", function () {
    var file = this.files[0];

    if (currentPdfObjectUrl) {
        URL.revokeObjectURL(currentPdfObjectUrl);
        currentPdfObjectUrl = null;
    }

    if (!file) {
        $("#pdf_preview_container").removeClass("show");
        $("#pdf_preview_frame").attr("src", "");
        return;
    }

    var nomFichier = file.name.toLowerCase();
    var extension = nomFichier.split('.').pop();
    if (extension !== 'pdf') {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Seuls les fichiers PDF sont autorisés');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        $(this).val("");
        $("#pdf_preview_container").removeClass("show");
        return;
    }

    if (file.size > 10 * 1024 * 1024) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Fichier trop volumineux (max 10 Mo)');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        $(this).val("");
        $("#pdf_preview_container").removeClass("show");
        return;
    }

    currentPdfObjectUrl = URL.createObjectURL(file);
    $("#pdf_preview_frame").attr("src", currentPdfObjectUrl + "#toolbar=1&navpanes=0&scrollbar=1");
    $("#pdf_preview_container").addClass("show");

    setTimeout(function () {
        $('html, body').animate({
            scrollTop: $("#pdf_preview_container").offset().top - 100
        }, 400);
    }, 150);
});

$("#pdf_preview_close").on("click", function () {
    $("#pdf_preview_container").removeClass("show");
    $("#pdf_preview_frame").attr("src", "");
    if (currentPdfObjectUrl) {
        URL.revokeObjectURL(currentPdfObjectUrl);
        currentPdfObjectUrl = null;
    }
});

/* ============================================================
   VIDER L'IFRAME DE LA MODALE À LA FERMETURE (libère la mémoire)
   ============================================================ */
$('#modal_view_file').on('hidden.bs.modal', function () {
    $("#modal_view_file iframe").attr("src", "");
    $("#modal_view_file .file-name").html("");
    $("#view_file_details").html(""); // on vide aussi l'entête détails
});

/* ============================================================
   NAVIGATION ENTRE BLOCS
   ============================================================ */
$("#upload").click(function (e) {
    e.preventDefault();
    $("#dropzone-upload").trigger("click");
});

$("#liste").click(function (e) {
    e.preventDefault();
    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide();
    $("#bloc_4").hide();
    $("#bloc_5").hide();
});

$("#add").click(function (e) {
    e.preventDefault();
    $("#bloc_1").hide();
    $("#bloc_2").show();
    $("#bloc_3").hide();
    $("#bloc_4").hide();
    $("#bloc_5").hide();
});

$("#annuler").click(function (e) {
    e.preventDefault();
    $("#form_add")[0].reset();
    $("#pdf_preview_container").removeClass("show");
    $("#pdf_preview_frame").attr("src", "");
    if (currentPdfObjectUrl) {
        URL.revokeObjectURL(currentPdfObjectUrl);
        currentPdfObjectUrl = null;
    }
    $('#msg').html("");

    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide();
    $("#bloc_4").hide();
    $("#bloc_5").hide();
});

/* ============================================================
   ENREGISTREMENT AVEC SPINNER
   ============================================================ */
function setLoading(active) {
    if (active) {
        $("#save").prop("disabled", true).addClass("loading");
        $("#save .btn-text").text("Enregistrement...");
        $("#save .btn-icon").removeClass("zmdi-save").addClass("spinner");
    } else {
        $("#save").prop("disabled", false).removeClass("loading");
        $("#save .btn-text").text("Enregister");
        $("#save .btn-icon").removeClass("spinner").addClass("zmdi-save");
    }
}

$("#save").click(function (e) {
    e.preventDefault();
    var annee_id = $("#annee_id").val();
    var moi_id = $("#moi_id").val();
    var client_id = $("#client_id").val();
    var fichier = $("#fichier")[0].files[0];

    if (annee_id.trim().length == 0) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une année');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }
    if (moi_id.trim().length == 0) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un mois');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }
    if (client_id.trim().length == 0) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un client');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }
    if (!fichier) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez charger un fichier');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }

    var nomFichier = fichier.name.toLowerCase();
    var extension = nomFichier.split('.').pop();
    if (extension !== 'pdf') {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Seuls les fichiers PDF sont autorisés');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }
    if (fichier.size > 10 * 1024 * 1024) {
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Fichier trop volumineux (max 10 Mo)');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
        return;
    }

    setLoading(true);

    $.get("{{ url('/check_solde_3') }}", {
        annee_id: annee_id,
        moi_id: moi_id,
        client_id: client_id
    }, function (rep) {
        if (rep != 0) {
            setLoading(false);
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette facture existe');
            $('#msg').css('color', "#ff6b68");
            setTimeout(function () { $('#msg').html(""); }, 9000);
        } else {
            var formData = new FormData($("#form_add")[0]);
            formData.append('client_id', client_id);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                type: "POST",
                url: "/add_charger_facture",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    setLoading(false);
                    $.get("{{ url('/get_mois_3') }}", {
                        annee_id: annee_id
                    }, function (response2) {
                        $("#moi_id").html(response2);
                    });
                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Facture ajoutée avec succès');
                    $('#msg').css("color", '#32c787');
                    $("#content_groupe").html(response);

                    $("#form_add")[0].reset();
                    $("#pdf_preview_container").removeClass("show");
                    $("#pdf_preview_frame").attr("src", "");
                    if (currentPdfObjectUrl) {
                        URL.revokeObjectURL(currentPdfObjectUrl);
                        currentPdfObjectUrl = null;
                    }

                    setTimeout(function () { $('#msg').html(""); }, 9000);

                    // Recalcul du compteur après ajout
                    filterFactures();
                },
                error: function (xhr) {
                    setLoading(false);
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'enregistrement');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(function () { $('#msg').html(""); }, 9000);
                }
            });
        }
    }).fail(function () {
        setLoading(false);
        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur de communication avec le serveur');
        $('#msg').css('color', "#ff6b68");
        setTimeout(function () { $('#msg').html(""); }, 9000);
    });
});

/* ============================================================
   SUPPRESSION
   ============================================================ */
$("#oui").click(function (e) {
    e.preventDefault();
    var id = $("#data_id").html();
    $.get("{{ url('/refresh_deletefacturesnormalisees') }}", {
        id: id,
    }, function (refresh_editverbalisateur) {
        $("#content_groupe").html(refresh_editverbalisateur);
        $("#non").trigger("click");
        // Recalcul du compteur après suppression
        setTimeout(function() { filterFactures(); }, 100);
    });
});

/* ============================================================
   CHANGEMENT ANNÉE → MOIS
   ============================================================ */
$("#annee_id").change(function (e) {
    e.preventDefault();
    var annee_id = $("#annee_id").val();
    $.get("{{ url('/get_mois_3') }}", {
        annee_id: annee_id
    }, function (response) {
        $("#moi_id").html(response);
    });
});

var annee_id = $("#annee_id").val();
if (annee_id.trim().length == 0) {
    $.get("{{ url('/get_mois_3') }}", {
        annee_id: annee_id
    }, function (response) {
        $("#moi_id").html(response);
    });
}

/* ============================================================
   FILTRES DU TABLEAU DES FACTURES CHARGÉES
   ============================================================ */
let factFilterTimeout;

function filterFactures() {
    const fClient  = ($('#filterClient').val() || '').toLowerCase().trim();
    const fMois    = ($('#filterMois').val() || '').toLowerCase().trim();
    const fAnnee   = ($('#filterAnnee').val() || '').toLowerCase().trim();
    const fFichier = ($('#filterFichier').val() || '').toLowerCase().trim();

    let visibleCount = 0;

    $('#facturesTable tbody tr.facture-row').each(function () {
        const $row = $(this);
        let show = true;

        if (fClient  && !String($row.data('client')).includes(fClient))   show = false;
        if (show && fMois   && !String($row.data('mois')).includes(fMois))     show = false;
        if (show && fAnnee  && !String($row.data('annee')).includes(fAnnee))   show = false;
        if (show && fFichier && !String($row.data('fichier')).includes(fFichier)) show = false;

        if (show) {
            $row.show();
            visibleCount++;
        } else {
            $row.hide();
        }
    });

    $('#invoiceCount').text(visibleCount);
}

function debouncedFilterFactures() {
    clearTimeout(factFilterTimeout);
    factFilterTimeout = setTimeout(filterFactures, 250);
}

$(document).on('input change', '#filterClient, #filterMois, #filterAnnee, #filterFichier', debouncedFilterFactures);

$(document).on('click', '#resetFilters', function (e) {
    e.preventDefault();
    $('#filterClient, #filterMois, #filterAnnee, #filterFichier').val('');
    filterFactures();
});

// Initialisation du compteur au chargement
$(document).ready(function () {
    filterFactures();
});
</script>
@endsection
@endsection