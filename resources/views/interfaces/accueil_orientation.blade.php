@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp

<?php
use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'ACCUEIL ORIENTATION')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
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
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

#bloc_1, #bloc_2, #bloc_3, #bloc_4 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
}

h4 { font-weight: 700; border-left: 6px solid #e31b23; padding-left: 18px; margin-bottom: 16px; margin-top: 0; color: var(--bleu-nuit); }
h4 i.zmdi { background: var(--bleu-nuit-gradient); background-clip: text; -webkit-background-clip: text; color: transparent !important; }

.table-responsive { overflow-x: auto; overflow-y: visible; border-radius: var(--border-radius-lg); }
.table { width: 100%; min-width: 800px; background: white; border-collapse: collapse; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-light); table-layout: auto; }
.table thead th { background: #E7F5FE !important; color: #0a192f; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; border-right: 1px solid #d0e2f2; }
.table tbody tr { transition: all 0.15s ease; border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd)  { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; }
.table tbody td { padding: 10px 12px !important; vertical-align: middle !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; border-bottom: 1px solid #eef2f6; line-height: 1.4; }
.table tbody td:last-child { text-align: center; vertical-align: middle; }

.numero-cell {
    font-weight: 700;
    color: #0a192f;
    font-family: 'Courier New', monospace;
    font-size: 0.82rem;
    letter-spacing: 0.3px;
}

.phone-cell-display {
    font-weight: 600;
    color: #0a192f;
    font-size: 0.82rem;
    white-space: nowrap;
}
.phone-cell-display i {
    color: #10b981;
    margin-right: 4px;
    font-size: 0.9rem;
}

/* ========== BOUTON D'APPEL (tableau + modal) ========== */
.phone-call-link {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white !important;
    border-radius: 40px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    white-space: nowrap;
}
.phone-call-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(16, 185, 129, 0.4);
    background: linear-gradient(135deg, #059669, #047857);
    color: white !important;
}
.phone-call-link i { font-size: 1.05rem; }
.phone-call-link .phone-num { font-family: 'Courier New', monospace; letter-spacing: 0.4px; }

/* ===== ICÔNE TÉLÉPHONE EN BLANC PARTOUT (tableau + modal) ===== */
.phone-call-link i,
.phone-call-link i.zmdi,
.phone-call-link i.zmdi-phone,
.phone-call-link i.zmdi-phone-in-talk {
    color: #ffffff !important;
}
.phone-call-link:hover i,
.phone-call-link:hover i.zmdi,
.phone-call-link:hover i.zmdi-phone,
.phone-call-link:hover i.zmdi-phone-in-talk {
    color: #ffffff !important;
}

/* Version compacte dans le tableau */
.table .phone-cell-display .phone-call-link {
    padding: 4px 10px;
    font-size: 0.78rem;
    gap: 5px;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
}
.table .phone-cell-display .phone-call-link i {
    font-size: 0.95rem;
}
.table .phone-cell-display .phone-call-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
}

/* Icône téléphone en blanc dans le tableau (override du vert par défaut) */
.table .phone-cell-display .phone-call-link i,
.table .phone-cell-display .phone-call-link i.zmdi,
.table .phone-cell-display .phone-call-link i.zmdi-phone-in-talk {
    color: #ffffff !important;
}
.table .phone-cell-display .phone-call-link:hover i,
.table .phone-cell-display .phone-call-link:hover i.zmdi,
.table .phone-cell-display .phone-call-link:hover i.zmdi-phone-in-talk {
    color: #ffffff !important;
}

/* Bouton Appeler du modal */
.btn-call-modal {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    padding: 8px 22px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white !important;
    border-radius: 40px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none !important;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
}
.btn-call-modal i,
.btn-call-modal i.zmdi {
    color: #ffffff !important;
}
.btn-call-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(16, 185, 129, 0.45);
    background: linear-gradient(135deg, #059669, #047857);
    color: white !important;
}
.btn-call-modal.disabled {
    background: #cbd5e1 !important;
    color: #64748b !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    transform: none !important;
    pointer-events: none;
}
.btn-call-modal.disabled i,
.btn-call-modal.disabled i.zmdi {
    color: #64748b !important;
}

.sortie-manquante {
    color: #dc2626 !important; font-weight: 700 !important; font-size: 0.78rem;
    font-style: italic; display: inline-flex; align-items: center; gap: 5px;
    background: #fee2e2; padding: 4px 10px; border-radius: 40px; white-space: nowrap;
}
.sortie-manquante i { font-size: 1rem; }
.sortie-ok { color: #065f46; font-weight: 600; font-size: 0.82rem; }

.sortie-non-renseignee {
    color: #dc2626 !important; font-weight: 700 !important; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
    border-radius: 40px; background: #fee2e2; transition: all 0.2s;
    border: 1px solid #fca5a5; font-size: 0.85rem;
}
.sortie-non-renseignee:hover { background: #fecaca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25); color: #b91c1c !important; }

#bloc_1 button, #bloc_2 button, #bloc_3 button, #bloc_4 button,
#liste, #add, #add_r, #save, #save_r, #annuler,
.btn-primary, .btn-info, .btn-danger, .btn-secondary {
    display: inline-flex !important; align-items: center; justify-content: center;
    gap: 8px; padding: 6px 16px !important; font-weight: 600; font-size: 0.85rem;
    border-radius: 40px !important; transition: all 0.25s ease;
    border: none; cursor: pointer; text-decoration: none;
    box-shadow: var(--shadow-light); white-space: nowrap; line-height: 1.5;
}
#liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
#liste:hover, .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3); background: #2563eb !important; }
#add, .btn-info { background: var(--bleu-nuit-gradient) !important; color: white !important; }
#add:hover, .btn-info:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3); }
#save { background: var(--bleu-secondaire-gradient) !important; color: white; }
#save:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3); }
#annuler, .btn-danger { background: var(--rouge-gradient) !important; color: white; }
#annuler:hover, .btn-danger:hover { transform: translateY(-2px); background: linear-gradient(135deg, #dc2626, #b91c1c) !important; box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3); }
#resetFilters { background: #64748b !important; color: white !important; }
#resetFilters:hover { transform: translateY(-2px); background: #475569 !important; }
#add_r, #save_r { background: #cbd5e1 !important; color: #475569 !important; cursor: not-allowed !important; opacity: 0.7; transform: none !important; box-shadow: none !important; }

.filters-container { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; background: white; padding: 0.8rem 1.2rem; border-radius: var(--border-radius-lg); box-shadow: var(--shadow-light); align-items: flex-end; }
.filter-group { flex: 1; min-width: 150px; }
.filter-group label { font-weight: 600; margin-bottom: 4px; color: var(--bleu-nuit); font-size: 0.7rem; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
.filter-group .form-control { height: 36px; }
.user-count-badge { background: var(--rouge-gradient); color: white; border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; }

.form-group { width: 100%; margin-bottom: 0; position: relative; }
.form-group label { display: block; font-weight: 700; color: var(--bleu-nuit); margin-bottom: 4px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.4px; }
.form-group label i { color: #e31b23; margin-right: 6px; }
.form-control, input.form-control, select.form-control, textarea.form-control {
    width: 100% !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important; padding: 8px 12px !important; font-weight: 500; font-size: 0.85rem;
    transition: all 0.2s; box-sizing: border-box; height: 38px !important; line-height: 1.4;
}
textarea.form-control { resize: vertical; height: 38px !important; min-height: 38px !important; }
.form-control:focus, select.form-control:focus, textarea.form-control:focus { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important; }

#form_add .form-row-custom { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px; }
#form_add .form-row-custom > [class*="col-"] { flex: 1 1 calc(50% - 8px); min-width: 260px; max-width: 100%; padding: 0; margin: 0; }

.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; background: #ffffff !important; display: flex !important; align-items: center; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: normal !important; padding-left: 14px !important; padding-right: 40px !important; font-weight: 500; font-size: 0.85rem; }
.select2-dropdown { border: 1px solid #e2e8f0 !important; border-radius: 14px !important; }

.flatpickr-input, input.flatpickr-input + input.form-control { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 8px 12px !important; font-weight: 600 !important; font-size: 0.85rem !important; height: 38px !important; color: #1e293b !important; }
.flatpickr-calendar { border-radius: 14px !important; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18) !important; }
.flatpickr-calendar .flatpickr-months { background: linear-gradient(135deg, #0a192f, #1e3a5f); border-radius: 10px 10px 0 0; color: white; }
.flatpickr-calendar .flatpickr-day.selected { background: #3B82F6 !important; border-color: #3B82F6 !important; }

.daterangepicker { z-index: 10050 !important; border-radius: 14px !important; box-shadow: 0 20px 45px rgba(0,0,0,0.18) !important; border: 1px solid #e2e8f0 !important; }
.daterangepicker .ranges li.active { background: #3B82F6 !important; color: #fff !important; }
.daterangepicker td.active, .daterangepicker td.active:hover { background: #3B82F6 !important; }
.daterangepicker .drp-buttons .btn { border-radius: 40px !important; padding: 6px 16px !important; font-weight: 600 !important; font-size: 0.8rem !important; }
.daterangepicker .drp-buttons .btn-primary { background: #3B82F6 !important; border-color: #3B82F6 !important; }
.daterangepicker .drp-buttons .btn-default { background: #64748b !important; color: white !important; border-color: #64748b !important; }

.signature-section { margin-top: 28px; padding-top: 18px; border-top: 1px dashed #e2e8f0; }
.signature-wrap { position: relative; border: 2px dashed #cbd5e1; border-radius: 14px; background: #f8fafc; height: 340px; min-height: 340px; overflow: hidden; width: 100%; margin-top: 8px; }
#signatureCanvas { width: 100%; height: 100%; display: block; cursor: crosshair; touch-action: none; }
.signature-placeholder { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 15px; pointer-events: none; font-style: italic; }
.signature-actions { display: flex; justify-content: flex-end; margin-top: 10px; }
.btn-clear-sig { background: #fff; border: 1px solid #cbd5e1; color: #475569; padding: 6px 14px; border-radius: 40px; font-size: 0.75rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }

#msg, #edit_msg { display: none !important; }
#msg:not(:empty), #edit_msg:not(:empty) { display: inline-flex !important; margin-top: 16px !important; padding: 10px 18px !important; background: white !important; border-radius: 50px !important; box-shadow: var(--shadow-light) !important; gap: 10px; font-weight: 600; font-size: 0.8rem; }
#msg:not(:empty):has(i.zmdi-check-circle) { background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important; color: #065f46; border-left: 4px solid #10b981; }
#msg:not(:empty):has(i.zmdi-close-circle) { background: linear-gradient(95deg, #fee2e2, #fecaca) !important; color: #991b1b; border-left: 4px solid #ef4444; }
#msg:not(:empty):has(i.zmdi-info) { background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important; color: #1e3a8a; border-left: 4px solid #3b82f6; }

.table tbody td a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50% !important; background: #f1f5f9; transition: all 0.2s ease; text-decoration: none; margin: 0 2px; }
.table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a i.zmdi-eye { color: #3B82F6; }
.table tbody td a:hover { transform: translateY(-2px); }
.table tbody td a:hover i.zmdi-delete { color: #b91c1c; background: #fee2e2; }
.table tbody td a.btn-details:hover { background: #dbeafe; }

/* Exception : le lien téléphone garde son style propre (pas le style rond gris) */
.table tbody td .phone-call-link {
    width: auto !important;
    height: auto !important;
    border-radius: 40px !important;
    background: linear-gradient(135deg, #10b981, #059669) !important;
    margin: 0 !important;
}
.table tbody td .phone-call-link:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
}

[style*="background-color: rgba(0, 0, 0, 0.1)"] { background: #eef3fc !important; border-radius: 60px; padding: 10px 24px !important; margin-bottom: 20px; display: flex !important; flex-wrap: wrap; gap: 12px; justify-content: flex-start; }

.profile-thumb { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; display: inline-block; vertical-align: middle; border: none; }
a[id^="voir_profil_"] { display: inline-block; vertical-align: middle; line-height: 0; margin-right: 8px; background: transparent !important; text-decoration: none !important; }
.table tbody td:has(a[id^="voir_profil_"]) { white-space: nowrap; }
a[id^="voir_profil_"] + * { display: inline-block; vertical-align: middle; line-height: 1.4; max-width: calc(100% - 45px); }

#modal_signature .modal-dialog { max-width: 90% !important; width: 90%; margin: 2rem auto; }
#modal_signature .modal-content { border-radius: 20px !important; border: none !important; overflow: hidden; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35) !important; }
#modal_signature .modal-header { background: linear-gradient(135deg, #0a192f, #1e3a5f) !important; color: white !important; border-bottom: none !important; padding: 14px 22px !important; }
#modal_signature .modal-header h5 { font-weight: 700; font-size: 1.05rem; color: white !important; margin: 0; display: flex; align-items: center; gap: 8px; }
#modal_signature .modal-header .close { color: white !important; opacity: 0.9; font-size: 1.6rem; outline: none !important; }
#modal_signature .modal-body { background: #f8fafc; padding: 20px !important; text-align: center; }
#signature_modal_img { max-width: 100%; max-height: 75vh; border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0; }
#modal_signature .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 12px 20px !important; justify-content: center; }
#signature_download_btn { background: var(--bleu-nuit-gradient) !important; color: white !important; border-radius: 40px !important; padding: 8px 22px !important; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }

#suppression .modal-content { border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25); }
#suppression .modal-header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border-bottom: none; padding: 16px 22px; }
#suppression .modal-header h5 { font-weight: 700; font-size: 1rem; color: white; display: flex; align-items: center; gap: 8px; margin: 0; }
#suppression .modal-header .close { color: white; opacity: .9; font-size: 1.6rem; outline: none; }
#suppression .modal-body { padding: 22px; background: #f8fafc; }
#suppression .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 20px; justify-content: center; gap: 12px; }

#modal_details .modal-dialog { max-width: 720px; width: 92%; margin: 1.8rem auto; }
#modal_details .modal-content { border-radius: 22px !important; border: none !important; overflow: hidden; box-shadow: 0 30px 70px rgba(0,0,0,.35) !important; }
#modal_details .fiche-header { background: linear-gradient(135deg, #0a192f, #1e3a5f); color: white; padding: 22px 26px; position: relative; overflow: hidden; }
#modal_details .fiche-header::after { content: ''; position: absolute; right: -60px; top: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(59,130,246,.35), transparent 70%); border-radius: 50%; pointer-events: none; }
#modal_details .fiche-header .close { position: absolute; top: 12px; right: 16px; color: white; opacity: .9; font-size: 1.7rem; outline: none; z-index: 2; background: transparent; border: none; }
#modal_details .fiche-avatar { width: 62px; height: 62px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,.35); background: rgba(255,255,255,.1); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.35rem; color: white; flex-shrink: 0; }
#modal_details .fiche-name { font-weight: 700; font-size: 1.15rem; color: white; margin: 0 0 6px 0; line-height: 1.2; }
#modal_details .fiche-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.18); color: white; padding: 3px 12px; border-radius: 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; }
#modal_details .fiche-body { background: #f8fafc; padding: 22px 26px; max-height: 68vh; overflow-y: auto; }
#modal_details .fiche-section-title { color: #0a192f; font-weight: 700; font-size: 0.74rem; text-transform: uppercase; letter-spacing: .9px; margin: 0 0 10px 0; padding-left: 12px; border-left: 3px solid #3B82F6; display: flex; align-items: center; gap: 8px; }
#modal_details .fiche-section-title.visit { border-color: #10b981; }
#modal_details .fiche-section-title.sig   { border-color: #ef4444; }
#modal_details .fiche-card { background: #ffffff; border-radius: 14px; padding: 4px 16px; box-shadow: 0 2px 8px rgba(0,0,0,.04); border: 1px solid #eef2f6; margin-bottom: 20px; }
#modal_details .fiche-row { display: flex; align-items: center; gap: 10px; padding: 11px 0; border-bottom: 1px dashed #e2e8f0; }
#modal_details .fiche-row:last-child { border-bottom: none; }
#modal_details .fiche-row .fiche-label { display: flex; align-items: center; gap: 7px; min-width: 125px; flex-shrink: 0; }
#modal_details .fiche-row .fiche-label i { font-size: 1.05rem; }
#modal_details .fiche-row .fiche-label span { color: #64748b; font-size: 0.7rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 700; }
#modal_details .fiche-row .fiche-value { flex: 1; color: #0a192f; font-weight: 600; font-size: 0.88rem; text-align: right; word-break: break-word; line-height: 1.4; }
#modal_details .fiche-row .fiche-value.empty { color: #94a3b8; font-style: italic; font-weight: 500; }
#modal_details .fiche-signature-box { background: #ffffff; border-radius: 14px; padding: 16px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.04); border: 1px solid #eef2f6; min-height: 140px; display: flex; align-items: center; justify-content: center; }
#modal_details .fiche-signature-box img { max-width: 100%; max-height: 220px; border-radius: 8px; }
#modal_details .fiche-signature-box .no-sig { color: #94a3b8; font-style: italic; font-size: 0.85rem; display: flex; flex-direction: column; align-items: center; gap: 6px; }
#modal_details .fiche-signature-box .no-sig i { font-size: 2rem; opacity: .5; }
#modal_details .fiche-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 22px; display: flex; justify-content: center; gap: 12px; }

#modal_sortie .modal-dialog { max-width: 480px; width: 92%; }
#modal_sortie .modal-content { border-radius: 20px !important; border: none !important; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3) !important; }
#modal_sortie .modal-header { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-bottom: none; padding: 16px 22px; }
#modal_sortie .modal-header h5 { font-weight: 700; font-size: 1rem; color: white; display: flex; align-items: center; gap: 8px; margin: 0; }
#modal_sortie .modal-header .close { color: white; opacity: .9; font-size: 1.6rem; outline: none; background: transparent; border: none; }
#modal_sortie .modal-body { padding: 22px; background: #f8fafc; }
#modal_sortie .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 20px; justify-content: center; gap: 12px; }
#save_sortie { border-radius: 40px; padding: 8px 22px; font-weight: 600; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s; }
#save_sortie:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(245, 158, 11, 0.35); }
#save_sortie:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.8rem !important; }
    #liste, #add, #save, #annuler, #resetFilters, .btn-primary, .btn-info, .btn-danger { padding: 4px 12px !important; font-size: 0.7rem; }
    .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; }
    .filter-group { width: 100%; min-width: 100%; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .sortie-manquante { font-size: 0.68rem; padding: 3px 8px; }
    .table .phone-cell-display .phone-call-link { font-size: 0.7rem; padding: 3px 8px; }
    #modal_details .fiche-row .fiche-label { min-width: 100px; }
    #modal_details .fiche-row .fiche-label span { font-size: 0.62rem; }
    #modal_details .fiche-row .fiche-value { font-size: 0.8rem; }
    #modal_details .fiche-body { padding: 16px; }
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
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                    <?php
                                    $add = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                        $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                    <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
                                        <a id="add" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
                                    <?php } else { ?>
                                        <a id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Accueil orientation</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i>
                    Liste
                    <span class="user-count-badge">
                        <i class="zmdi zmdi-view-list"></i> Total personne : <span id="userCount">0</span>
                    </span>
                </h4>

                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Nom</label>
                        <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-email text-danger"></i> Email</label>
                        <input type="text" id="filterEmail" class="form-control" placeholder="Rechercher par email...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-phone text-danger"></i> Téléphone</label>
                        <input type="text" id="filterPhone" class="form-control" placeholder="Rechercher par téléphone...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Période d'entrée</label>
                        <input type="text" id="filterEntreeRange" class="form-control" placeholder="Sélectionner une période (ou Tout)">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar-check text-danger"></i> Période de sortie</label>
                        <input type="text" id="filterSortieRange" class="form-control" placeholder="Sélectionner une période (ou Tout)">
                    </div>
                    <div class="filter-group">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nom</th>
                                        <th>Contact</th>
                                        <th>Type</th>
                                        <th>Nature</th>
                                        <th>Motif</th>
                                        <th>Service</th>
                                        <th>Entrée</th>
                                        <th>Sortie</th>
                                        <th>Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($registreaccueils as $data)
                                        @php
                                            $personneRec = \App\Models\Personnes::find($data->personne_id);
                                            $typeNumerique = $personneRec->type ?? null;

                                            $typeLabels = [0 => 'Utilisateur', 1 => 'Client', 2 => 'Patient', 3 => 'Visiteur'];
                                            $typeLabel = $typeLabels[$typeNumerique] ?? '—';

                                            $nom = ''; $email = ''; $phone = ''; $image = ''; $nature = null;

                                            if ($personneRec && $personneRec->user_id) {
                                                $p = null;
                                                switch ($typeNumerique) {
                                                    case 0: $p = \App\Models\User::find($personneRec->user_id); break;
                                                    case 1: $p = \App\Models\Clients::find($personneRec->user_id); break;
                                                    case 2: $p = \App\Models\Patients::find($personneRec->user_id); break;
                                                    case 3: $p = \App\Models\Visiteurs::find($personneRec->user_id); break;
                                                }
                                                if ($p) {
                                                    $nom    = $p->name  ?? '';
                                                    $email  = $p->email ?? '';
                                                    $phone  = $p->phone ?? '';
                                                    $image  = $p->image ?? '';
                                                    $nature = $p->type  ?? null;
                                                }
                                            }

                                            if (!empty($data->motif_id) && $data->motif_id != 0) {
                                                $motifNom = optional(\App\Models\Motifs::find($data->motif_id))->nom ?? '';
                                                if ($motifNom === '') $motifNom = 'Aucun motif';
                                            } else { $motifNom = 'Aucun motif'; }

                                            if (!empty($data->service_id) && $data->service_id != 0) {
                                                $serviceNom = optional(\App\Models\Services::find($data->service_id))->nom ?? '';
                                                if ($serviceNom === '') $serviceNom = 'Aucun service';
                                            } else { $serviceNom = 'Aucun service'; }

                                            $natureLabels = [0 => 'Privé', 1 => 'Entreprise'];
                                            $natureLabel = ($nature !== null && isset($natureLabels[$nature])) ? $natureLabels[$nature] : 'Entreprise';

                                            $numeroAffiche = !empty($data->numero) ? $data->numero : ('ACC-' . str_pad($data->id, 5, '0', STR_PAD_LEFT));

                                            $heureFormatee = '';
                                            $entreeYmd = '';
                                            if (!empty($data->heure_entree) && $data->heure_entree !== '0000/00/00 00:00' && $data->heure_entree !== '0000-00-00 00:00:00') {
                                                $dt = \Carbon\Carbon::parse($data->heure_entree);
                                                $heureFormatee = $dt->format('d/m/Y') . ' à ' . $dt->format('H:i');
                                                $entreeYmd = $dt->format('Y-m-d');
                                            }

                                            $heureSortieFormatee = '';
                                            $aSorti = false;
                                            $sortieYmd = '';
                                            if (!empty($data->heure_sortie)
                                                && $data->heure_sortie !== '0000/00/00 00:00'
                                                && $data->heure_sortie !== '0000-00-00 00:00:00') {
                                                $dtS = \Carbon\Carbon::parse($data->heure_sortie);
                                                $heureSortieFormatee = $dtS->format('d/m/Y') . ' à ' . $dtS->format('H:i');
                                                $sortieYmd = $dtS->format('Y-m-d');
                                                $aSorti = true;
                                            }

                                            $signatureUrl = !empty($data->signature)
                                                ? asset('storage/images/fichiers/' . $data->signature)
                                                : '';
                                        @endphp

                                        <tr id="row_{{ $data->id }}"
                                            data-user-id="{{ $data->user_id ?? '' }}"
                                            data-personne-id="{{ $data->personne_id ?? '' }}"
                                            data-numero="{{ $numeroAffiche }}"
                                            data-nom="{{ $nom }}"
                                            data-email="{{ $email }}"
                                            data-phone="{{ $phone }}"
                                            data-entree-ymd="{{ $entreeYmd }}"
                                            data-sortie-ymd="{{ $sortieYmd }}"
                                            data-sortie-ok="{{ $aSorti ? '1' : '0' }}">

                                            <td class="row-num">{{ $i }}</td>

                                            {{-- Nom --}}
                                            <td class="nom-cell align-middle">
                                                @if(!empty($image))
                                                    <a id="voir_profil_<?= $i ?>" href="#">
                                                        <img src="{{ asset($image) }}" alt="avatar" class="profile-thumb">
                                                    </a>
                                                @endif
                                                {{ $nom }}
                                            </td>

                                            {{-- Contact (cliquable pour appel) --}}
                                            <td class="phone-cell-display">
                                                @if(!empty($phone))
                                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}"
                                                       class="phone-call-link"
                                                       title="Appeler {{ $phone }}">
                                                        <i class="zmdi zmdi-phone-in-talk"></i>
                                                        <span class="phone-num">{{ $phone }}</span>
                                                    </a>
                                                @else
                                                    <span style="color:#94a3b8;font-style:italic;">Non renseigné</span>
                                                @endif
                                            </td>

                                            <td>{{ $typeLabel }}</td>
                                            <td>{{ $natureLabel }}</td>

                                            <td>
                                                @if($motifNom === 'Aucun motif')
                                                    <span style="color:#94a3b8;font-style:italic;">{{ $motifNom }}</span>
                                                @else
                                                    {{ $motifNom }}
                                                @endif
                                            </td>

                                            <td>
                                                @if($serviceNom === 'Aucun service')
                                                    <span style="color:#94a3b8;font-style:italic;">{{ $serviceNom }}</span>
                                                @else
                                                    {{ $serviceNom }}
                                                @endif
                                            </td>

                                            <td>{{ $heureFormatee }}</td>

                                            <td class="sortie-cell">
                                                @if($aSorti)
                                                    <span class="sortie-ok">{{ $heureSortieFormatee }}</span>
                                                @else
                                                    <span class="sortie-manquante" title="La sortie n'a pas encore été mentionnée">
                                                        <i class="zmdi zmdi-alert-circle"></i>
                                                        Non renseignée
                                                    </span>
                                                @endif
                                            </td>

                                            <td style="text-align: center; white-space: nowrap;">
                                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                    <?php
                                                    $delete = 0;
                                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                        $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                    }
                                                    ?>
                                                <?php } ?>

                                                <a href="#"
                                                   class="btn-details"
                                                   data-id="{{ $data->id }}"
                                                   data-numero="{{ $numeroAffiche }}"
                                                   data-nom="{{ $nom }}"
                                                   data-email="{{ $email }}"
                                                   data-phone="{{ $phone }}"
                                                   data-image="{{ !empty($image) ? asset($image) : '' }}"
                                                   data-type="{{ $typeLabel }}"
                                                   data-nature="{{ $natureLabel }}"
                                                   data-motif="{{ $motifNom }}"
                                                   data-service="{{ $serviceNom }}"
                                                   data-entree="{{ $heureFormatee }}"
                                                   data-sortie="{{ $heureSortieFormatee }}"
                                                   data-a-sorti="{{ $aSorti ? '1' : '0' }}"
                                                   data-note="{{ $data->note ?? '' }}"
                                                   data-signature="{{ $signatureUrl }}"
                                                   title="Voir les détails">
                                                    <i class="zmdi zmdi-eye"></i>
                                                </a>

                                                <?php if (($delete == 1 && $data->user_id == Auth::user()->id) || (Auth::user()->role == 0)) { ?>
                                                    <a id="delete_<?= $i ?>" href="#" title="Supprimer">
                                                        <i class="zmdi zmdi-delete text-danger"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a id="delete_r<?= $i ?>" href="#" title="Supprimer">
                                                        <i class="zmdi zmdi-delete text-danger"></i>
                                                    </a>
                                                <?php } ?>

                                                <script>
                                                    $("#delete_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#delete_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#sup_nom").text({!! json_encode($nom ?: 'Non renseigné') !!});
                                                        $("#sup_phone").text({!! json_encode($phone ?: 'Non renseigné') !!});
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        $("#btn_sup").trigger("click");
                                                    });
                                                    @if(!empty($image))
                                                    $("#voir_profil_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#nom_profil").html("<?= $nom ?>");
                                                        var url = "<?= asset($image) ?>";
                                                        $("#contenu_voir_profil").html('<img src="' + url + '" class="img-fluid" style="max-height:100%;width: 100%;" />');
                                                        $("#btn_voir_profil").trigger("click");
                                                    });
                                                    @endif
                                                </script>
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

            {{-- ===================== BLOC AJOUTER ===================== --}}
            <div id="bloc_2" style="margin-top: 12px;display: none;margin-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts-add text-info"></i> Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <p style="color:rgba(0, 0, 0, 0.6);" class="text-center">
                        <a href="#">
                            <img id="user_img_profil" class="user__img" src="{{ asset('storage/images/user/profil_defaut.png') }}" alt="" style="width: 100px; height: 100px; object-fit: cover;">
                        </a>
                    </p>
                    <div class="progress-container" style="display:none; margin-top: 10px;">
                        <div class="progress-bar" style="width:0%; height:5px; background-color:#32c787;"></div>
                        <span class="progress-text" style="font-size:12px;">0%</span>
                    </div>

                    <input type="file" name="input_user_img_profil" id="input_user_img_profil" style="display:none;">
                    <input type="text" name="image" id="image" value="{{ asset('storage/images/user/profil_defaut.png') }}" style="display:none;">

                    <div class="form-row-custom">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-account-box"></i> Personne</label>
                                <select id="personne" name="personne" class="form-control"><option value=""></option></select>
                            </div>
                        </div>
                        <div class="col-6" id="wrapper_type_personne">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-accounts-list"></i> Type de personne</label>
                                <select id="type_personne" name="type_personne" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Utilisateurs</option>
                                    <option value="1">Client</option>
                                    <option value="2">Patient</option>
                                    <option value="3">Visiteurs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row-custom" id="row_nature_nom">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-city-alt"></i> Nature</label>
                                <select id="nature" name="nature" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Privé</option>
                                    <option value="1">Entreprise</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-account"></i> Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom (Ex : Mgm congo)">
                            </div>
                        </div>
                    </div>

                    <div class="form-row-custom" id="row_email_phone">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-email"></i> E-mail</label>
                                <input type="text" id="email" name="email" class="form-control" placeholder="Email (Ex : mgm@gmail.com)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-phone"></i> Telephone</label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Telephone (Ex : +243974743675)">
                            </div>
                        </div>
                    </div>

                    <div class="form-row-custom">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-tag"></i> Motif</label>
                                <select id="motif" name="motif" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Aucun motif</option>
                                    @isset($motifs)
                                        @foreach ($motifs as $motif)
                                            <option value="{{ $motif->id }}">{{ $motif->nom }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-balance"></i> Service</label>
                                <select id="service" name="service" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Aucun service</option>
                                    @isset($services)
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->nom }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row-custom">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-calendar"></i> Date et heure d'entrée</label>
                                <input type="hidden" id="heure" name="heure">
                                <input type="text" id="heure_picker" class="form-control flatpickr-input" placeholder="Sélectionner la date et l'heure" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-comment-text"></i> Note</label>
                                <textarea id="note" name="note" class="form-control" rows="1" placeholder="Remarque éventuelle..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="signature-section">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-edit"></i> Signature</label>
                                    <div class="signature-wrap">
                                        <canvas id="signatureCanvas"></canvas>
                                        <span class="signature-placeholder" id="sigPlaceholder">Signez ici avec la souris ou le doigt</span>
                                    </div>
                                    <div class="signature-actions">
                                        <button type="button" class="btn-clear-sig" id="clearSig">
                                            <i class="zmdi zmdi-refresh"></i> Effacer la signature
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-12">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $add = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                            <?php } ?>
                            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                <button id="save" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg"></span>
                        </div>
                    </div>
                </form>
            </div>

            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</section>

<span id="data_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>

{{-- ===================== MODAL SUPPRESSION ===================== --}}
<div class="modal fade" id="suppression" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-alert-triangle" style="font-size: 1.4rem;"></i> Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p style="text-align: center; font-weight: 600; color: #334155; margin: 0 0 16px 0; font-size: 0.9rem;">
                    Voulez-vous vraiment supprimer cette entrée d'accueil&nbsp;?
                </p>
                <div style="background: #ffffff; border-radius: 12px; padding: 14px 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-left: 4px solid #ef4444;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px dashed #e2e8f0;">
                        <i class="zmdi zmdi-account-circle" style="color: #3B82F6; font-size: 1.15rem;"></i>
                        <span style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700; min-width: 85px;">Nom</span>
                        <span id="sup_nom" style="font-weight: 700; color: #0a192f; font-size: 0.9rem; flex: 1; text-align: right;"></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 8px 0;">
                        <i class="zmdi zmdi-phone" style="color: #10b981; font-size: 1.15rem;"></i>
                        <span style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700; min-width: 85px;">Téléphone</span>
                        <span id="sup_phone" style="font-weight: 700; color: #0a192f; font-size: 0.9rem; flex: 1; text-align: right;"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="non" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 40px; padding: 8px 22px; font-weight: 600; background: #64748b !important; color: white !important;">
                    <i class="zmdi zmdi-close"></i> Annuler
                </button>
                <a id="oui" href="#" style="border-radius: 40px; padding: 8px 22px; font-weight: 600; background: linear-gradient(135deg, #ef4444, #dc2626); color: white !important; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="zmdi zmdi-delete"></i> Oui, supprimer
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DÉTAILS ===================== --}}
<div class="modal fade" id="modal_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="fiche-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                <div style="display: flex; align-items: center; gap: 16px; position: relative; z-index: 1;">
                    <div id="details_avatar" class="fiche-avatar"></div>
                    <div style="flex: 1; min-width: 0;">
                        <h5 id="details_nom" class="fiche-name"></h5>
                        <span id="details_type_badge" class="fiche-badge">
                            <i class="zmdi zmdi-account-box"></i>
                            <span id="details_type_text"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="fiche-body">
                <div class="fiche-section-title"><i class="zmdi zmdi-account" style="color: #3B82F6;"></i> Informations personnelles</div>
                <div class="fiche-card">
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-label" style="color: #3B82F6;"></i><span>Numéro</span></div><div class="fiche-value" id="d_numero"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-account" style="color: #3B82F6;"></i><span>Nom</span></div><div class="fiche-value" id="d_nom"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-phone" style="color: #3B82F6;"></i><span>Contact</span></div><div class="fiche-value" id="d_phone"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-email" style="color: #3B82F6;"></i><span>Email</span></div><div class="fiche-value" id="d_email"></div></div>
                </div>

                <div class="fiche-section-title visit"><i class="zmdi zmdi-calendar-note" style="color: #10b981;"></i> Détails de la visite</div>
                <div class="fiche-card">
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-city-alt" style="color: #10b981;"></i><span>Nature</span></div><div class="fiche-value" id="d_nature"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-tag" style="color: #10b981;"></i><span>Motif</span></div><div class="fiche-value" id="d_motif"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-balance" style="color: #10b981;"></i><span>Service</span></div><div class="fiche-value" id="d_service"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-time" style="color: #10b981;"></i><span>Entrée</span></div><div class="fiche-value" id="d_entree"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-time-restore" style="color: #10b981;"></i><span>Sortie</span></div><div class="fiche-value" id="d_sortie_fiche"></div></div>
                    <div class="fiche-row"><div class="fiche-label"><i class="zmdi zmdi-comment-text" style="color: #10b981;"></i><span>Note</span></div><div class="fiche-value" id="d_note"></div></div>
                </div>

                <div class="fiche-section-title sig"><i class="zmdi zmdi-edit" style="color: #ef4444;"></i> Signature</div>
                <div class="fiche-signature-box" id="d_signature_box"></div>
            </div>

            <div class="fiche-footer">
                <a href="#" id="details_call_btn" class="btn-call-modal disabled" target="_self">
                    <i class="zmdi zmdi-phone-in-talk"></i> Appeler
                </a>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" style="border-radius: 40px; padding: 8px 22px; font-weight: 600;">
                    <i class="zmdi zmdi-close"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL SORTIE ===================== --}}
<div class="modal fade" id="modal_sortie" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-time-restore" style="font-size: 1.3rem;"></i> Compléter l'heure de sortie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sortie_data_id">
                <p style="font-weight: 600; color: #334155; margin: 0 0 16px 0; font-size: 0.88rem; background: white; padding: 10px 14px; border-radius: 12px; border-left: 4px solid #3B82F6;">
                    <i class="zmdi zmdi-account" style="color: #3B82F6;"></i>
                    Personne : <span id="sortie_personne_nom" style="color: #0a192f; font-weight: 700;"></span>
                </p>
                <div class="form-group">
                    <label style="display: block; font-weight: 700; color: #0a192f; margin-bottom: 6px; font-size: 0.75rem; text-transform: uppercase;">
                        <i class="zmdi zmdi-calendar" style="color: #e31b23; margin-right: 6px;"></i> Date et heure de sortie
                    </label>
                    <input type="hidden" id="sortie_heure_hidden">
                    <input type="text" id="sortie_picker" class="form-control flatpickr-input" placeholder="Sélectionner la date et l'heure" readonly>
                </div>
                <p style="font-size: 0.75rem; color: #94a3b8; margin: 10px 0 0 0; font-style: italic;">
                    <i class="zmdi zmdi-info-outline"></i> L'heure actuelle est proposée par défaut.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="border-radius: 40px; padding: 8px 22px; font-weight: 600; background: #64748b !important; color: white !important;">
                    <i class="zmdi zmdi-close"></i> Annuler
                </button>
                <button type="button" id="save_sortie"><i class="zmdi zmdi-check"></i> Valider</button>
            </div>
        </div>
    </div>
</div>

<button style="display: none;" data-toggle="modal" data-target="#profil_utilisateur" id="btn_voir_profil">Sup</button>
<div class="modal fade" id="profil_utilisateur" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Profil : <span id="nom_profil"></span> </h5></div>
            <div class="modal-body"><p id="contenu_voir_profil" style="text-align: center;"></p></div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL SIGNATURE ===================== --}}
<div class="modal fade" id="modal_signature" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-image"></i> Signature — <span id="signature_nom_label"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"><img id="signature_modal_img" src="" alt="Signature"></div>
            <div class="modal-footer">
                <a id="signature_download_btn" href="" download="signature.png"><i class="zmdi zmdi-download"></i> Télécharger</a>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" style="border-radius: 40px; padding: 8px 22px;"><i class="zmdi zmdi-close"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>

@section('js-code')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    $("#link_59").addClass("active");
    $("#upload").click(function(e) { e.preventDefault(); $("#dropzone-upload").trigger("click"); });

    function getInitials(name) {
        if (!name || !name.trim()) return '?';
        return name.trim().split(/\s+/).map(function(w) { return w[0]; }).slice(0, 2).join('').toUpperCase();
    }

    function fillValue($el, value) {
        if (value === null || value === undefined || String(value).trim() === '' ||
            value === 'Aucun motif' || value === 'Aucun service') {
            $el.addClass('empty').text(value && value !== 'Non renseigné' ? value : 'Non renseigné');
        } else {
            $el.removeClass('empty').text(value);
        }
    }

    function parseDMY_to_ISO(str) {
        if (!str) return null;
        var p = String(str).trim().split('/');
        if (p.length === 3 && p[0].length === 2 && p[1].length === 2 && p[2].length === 4) {
            return p[2] + '-' + p[1] + '-' + p[0];
        }
        return null;
    }
    function parseRangeValue(val) {
        if (!val) return { start: null, end: null };
        var parts = String(val).split(' - ');
        if (parts.length !== 2) return { start: null, end: null };
        return { start: parseDMY_to_ISO(parts[0]), end: parseDMY_to_ISO(parts[1]) };
    }

    var DRP_CONFIG = {
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY', separator: ' - ',
            applyLabel: 'Appliquer', cancelLabel: 'Annuler',
            fromLabel: 'Du', toLabel: 'Au', customRangeLabel: 'Personnalisé',
            weekLabel: 'S',
            daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
        },
        opens: 'left',
        ranges: {
            'Tout':              [moment('2000-01-01'), moment('2100-12-31')],
            'Aujourd\'hui':      [moment(), moment()],
            'Hier':              [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 derniers jours':  [moment().subtract(6, 'days'), moment()],
            '30 derniers jours': [moment().subtract(29, 'days'), moment()],
            'Ce mois-ci':        [moment().startOf('month'), moment().endOf('month')],
            'Mois dernier':      [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Cette année':       [moment().startOf('year'), moment().endOf('year')]
        }
    };

    $(document).ready(function() {
        $('#filterEntreeRange').daterangepicker(DRP_CONFIG, function(start, end, label) {
            $('#filterEntreeRange').val(label === 'Tout' ? '' : start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            filterUsers(); saveUserFiltersToStorage();
        });
        $('#filterEntreeRange').on('cancel.daterangepicker', function() { $(this).val(''); filterUsers(); saveUserFiltersToStorage(); });

        $('#filterSortieRange').daterangepicker(DRP_CONFIG, function(start, end, label) {
            $('#filterSortieRange').val(label === 'Tout' ? '' : start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            filterUsers(); saveUserFiltersToStorage();
        });
        $('#filterSortieRange').on('cancel.daterangepicker', function() { $(this).val(''); filterUsers(); saveUserFiltersToStorage(); });
    });

    /* ============ FICHE DÉTAILS ============ */
    $(document).on('click', '.btn-details', function(e) {
        e.preventDefault();
        var $b = $(this);

        var id        = $b.data('id') || '';
        var numero    = $b.data('numero') || '';
        var nom       = $b.data('nom') || '';
        var email     = $b.data('email') || '';
        var phone     = $b.data('phone') || '';
        var image     = $b.data('image') || '';
        var type      = $b.data('type') || '';
        var nature    = $b.data('nature') || '';
        var motif     = $b.data('motif') || '';
        var service   = $b.data('service') || '';
        var entree    = $b.data('entree') || '';
        var sortie    = $b.data('sortie') || '';
        var aSorti    = String($b.data('a-sorti')) === '1';
        var note      = $b.data('note') || '';
        var signature = $b.data('signature') || '';

        $('#details_nom').text(nom || 'Non renseigné');
        $('#details_type_text').text(type || 'Type inconnu');
        if (image) {
            $('#details_avatar').html('<img src="' + image + '" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">');
        } else {
            $('#details_avatar').text(getInitials(nom));
        }

        fillValue($('#d_numero'), numero);
        fillValue($('#d_nom'), nom);
        fillValue($('#d_email'), email);
        fillValue($('#d_nature'), nature);
        fillValue($('#d_motif'), motif);
        fillValue($('#d_service'), service);
        fillValue($('#d_entree'), entree);
        fillValue($('#d_note'), note);

        // ========== TÉLÉPHONE CLIQUABLE (APPEL) DANS LE MODAL ==========
        (function() {
            var $p = $('#d_phone');
            if (phone && String(phone).trim() !== '' && phone !== 'Non renseigné') {
                var cleanPhone = String(phone).replace(/[^\d+]/g, '');
                $p.removeClass('empty').html(
                    '<a href="tel:' + cleanPhone + '" class="phone-call-link" title="Appeler ' + phone + '">' +
                        '<i class="zmdi zmdi-phone-in-talk"></i>' +
                        '<span class="phone-num">' + phone + '</span>' +
                    '</a>'
                );
            } else {
                $p.addClass('empty').text('Non renseigné');
            }
        })();

        // ========== BOUTON APPELER DU FOOTER ==========
        var $callBtn = $('#details_call_btn');
        if (phone && String(phone).trim() !== '' && phone !== 'Non renseigné') {
            var cleanPhoneFooter = String(phone).replace(/[^\d+]/g, '');
            $callBtn.attr('href', 'tel:' + cleanPhoneFooter)
                    .removeClass('disabled')
                    .attr('title', 'Appeler ' + phone);
        } else {
            $callBtn.attr('href', '#')
                    .addClass('disabled')
                    .attr('title', 'Aucun numéro disponible');
        }

        var $s = $('#d_sortie_fiche');
        if (aSorti && sortie) {
            $s.removeClass('empty').html('<span style="color:#065f46;font-weight:600;">' + sortie + '</span>');
        } else {
            $s.addClass('empty').html(
                '<span class="sortie-non-renseignee" data-id="' + id + '" data-nom="' + (nom || '').replace(/"/g, '&quot;') + '" title="Cliquez pour compléter l\'heure de sortie">' +
                    '<i class="zmdi zmdi-alert-circle"></i> Non renseignée — Compléter' +
                '</span>'
            );
        }

        if (signature) {
            $('#d_signature_box').html('<img src="' + signature + '" alt="Signature">');
        } else {
            $('#d_signature_box').html('<div class="no-sig"><i class="zmdi zmdi-edit"></i><span>Aucune signature disponible</span></div>');
        }

        $('#modal_details').modal('show');
    });

    $('#modal_details').on('hidden.bs.modal', function() {
        $('#d_signature_box').html('');
        $('#details_avatar').html('');
        $('#details_call_btn').attr('href', '#').addClass('disabled').attr('title', '');
    });

    /* ============ MODAL SORTIE ============ */
    var sortiePicker = null;
    function initSortiePicker() {
        if (sortiePicker) return;
        if (typeof flatpickr === 'undefined') return;
        if (flatpickr.l10ns && flatpickr.l10ns.fr) flatpickr.localize(flatpickr.l10ns.fr);
        var now = new Date();
        sortiePicker = flatpickr("#sortie_picker", {
            enableTime: true, time_24hr: true, dateFormat: "Y-m-d H:i",
            altInput: true, altFormat: "d/m/Y H:i", defaultDate: now,
            minuteIncrement: 1, allowInput: false, disableMobile: true,
            onChange: function(d, s) { $("#sortie_heure_hidden").val(s); }
        });
        $("#sortie_heure_hidden").val(sortiePicker.formatDate(now, "Y-m-d H:i"));
    }

    $(document).on('click', '.sortie-non-renseignee', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var id  = $(this).data('id');
        var nom = $(this).data('nom');
        $('#modal_details').modal('hide');
        setTimeout(function() {
            $('#sortie_data_id').val(id);
            $('#sortie_personne_nom').text(nom || 'Non renseigné');
            initSortiePicker();
            var now = new Date();
            if (sortiePicker) {
                sortiePicker.setDate(now, true);
                $('#sortie_heure_hidden').val(sortiePicker.formatDate(now, "Y-m-d H:i"));
            }
            $('#modal_sortie').modal('show');
        }, 350);
    });

    $('#modal_sortie').on('hidden.bs.modal', function() {
        $('#sortie_data_id').val('');
        $('#sortie_personne_nom').text('');
    });

    $("#save_sortie").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var originalHtml = '<i class="zmdi zmdi-check"></i> Valider';
        if (btn.data('loading')) return;

        var id    = $("#sortie_data_id").val();
        var heure = $("#sortie_heure_hidden").val();
        var page  = "<?= $ressource_id_1 ?>";

        if (!id || !heure) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Veuillez sélectionner une date et une heure');
            setTimeout(function() { $('#msg').html(""); }, 5000);
            return;
        }

        btn.data('loading', true).prop('disabled', true)
           .html('<span class="spinner-border spinner-border-sm" style="width:.9rem;height:.9rem;border-width:.15em;"></span> Enregistrement...');

        function resetBtn() { btn.data('loading', false).prop('disabled', false).html(originalHtml); }

        $.ajax({
            type: "GET",
            url: "{{ url('/refresh_updatesortie') }}",
            data: { id: id, heure_sortie: heure, page: page },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp) {
                resetBtn();
                $('#modal_sortie').modal('hide');
                $("#content_utilisateur").html(resp);
                saveUserFiltersToStorage();
                setTimeout(function() { loadUserFiltersFromStorage(); filterUsers(); }, 100);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Heure de sortie enregistrée avec succès');
                setTimeout(function() { $('#msg').html(""); }, 4000);
            },
            error: function(xhr) {
                resetBtn();
                var message = 'Erreur lors de l\'enregistrement';
                if (xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + message);
                setTimeout(function() { $('#msg').html(""); }, 6000);
            }
        });
    });

    /* ============ SIGNATURE MODAL ============ */
    $(document).on('click', '.btn-signature-modal', function(e) {
        e.preventDefault();
        var url = $(this).data('sig-url');
        var nom = $(this).data('sig-nom') || '';
        if (!url) return;
        $('#signature_nom_label').text(nom);
        $('#signature_modal_img').attr('src', url);
        $('#signature_download_btn').attr('href', url);
        $('#signature_download_btn').attr('download', 'signature_' + (nom || 'personne') + '.png');
        $('#modal_signature').modal('show');
    });

    $('#modal_signature').on('hidden.bs.modal', function() {
        $('#signature_modal_img').attr('src', '');
        $('#signature_nom_label').text('');
        $('#signature_download_btn').attr('href', '');
    });

    /* ============ TOGGLE ============ */
    function togglePersonFields() {
        var val = $('#personne').val();
        var has = (val !== null && val !== '' && val !== undefined && parseInt(val, 10) > 0);
        if (has) { $('#wrapper_type_personne').hide(); $('#row_nature_nom').hide(); $('#row_email_phone').hide(); }
        else { $('#wrapper_type_personne').show(); $('#row_nature_nom').show(); $('#row_email_phone').show(); }
        return has;
    }

    var select2Inited = false;
    function initSelect2() {
        if (select2Inited) return;
        if (typeof $.fn.select2 === 'undefined') return;
        var cfg = function(ph) { return { placeholder: ph, allowClear: true, width: '100%',
            language: { noResults: function() { return "Aucun résultat"; }, searching: function() { return "Recherche..."; } } }; };
        $('#personne').select2($.extend(cfg('-- Sélectionner une personne --'), { dropdownParent: $('#personne').closest('.form-group') }));
        $('#type_personne').select2($.extend(cfg('-- Sélectionner un type --'), { dropdownParent: $('#type_personne').closest('.form-group') }));
        $('#nature').select2($.extend(cfg('-- Sélectionner une nature --'), { dropdownParent: $('#nature').closest('.form-group') }));
        $('#motif').select2($.extend(cfg('-- Sélectionner un motif --'), { dropdownParent: $('#motif').closest('.form-group') }));
        $('#service').select2($.extend(cfg('-- Sélectionner un service --'), { dropdownParent: $('#service').closest('.form-group') }));
        select2Inited = true;
    }

    function preloadPersonnes() {
        $.ajax({
            type: "GET", url: "{{ url('/get_personnes_by_type') }}", dataType: "json",
            success: function(data) {
                var options = '<option value=""></option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.id + '" data-type="' + item.type + '">' + item.label + '</option>';
                });
                $('#personne').html(options);
                togglePersonFields();
            },
            error: function() { $('#personne').html('<option value="">Erreur</option>'); }
        });
    }

    $(document).on('change', '#personne', function() {
        var has = togglePersonFields();
        var val = $(this).val();
        if (val === null || val === '' || val === undefined) return;
        var type = $(this).find('option:selected').data('type');
        if (val == 0 || type === -1 || type === undefined || type === null) {
            $('#type_personne').val(null).trigger('change');
            $('#msg').html('<i class="zmdi zmdi-info"></i> Veuillez sélectionner un type de personne');
            setTimeout(function() { $('#msg').html(""); }, 6000);
            return;
        }
        if (has) $('#type_personne').val(type).trigger('change');
    });

    var heurePicker = null;
    function initFlatpickr() {
        if (heurePicker) return;
        if (typeof flatpickr === 'undefined') return;
        if (flatpickr.l10ns && flatpickr.l10ns.fr) flatpickr.localize(flatpickr.l10ns.fr);
        var now = new Date();
        heurePicker = flatpickr("#heure_picker", {
            enableTime: true, time_24hr: true, dateFormat: "Y-m-d H:i",
            altInput: true, altFormat: "d/m/Y H:i", defaultDate: now,
            minuteIncrement: 1, disableMobile: true,
            onChange: function(d, s) { $("#heure").val(s); }
        });
        $("#heure").val(heurePicker.formatDate(now, "Y-m-d H:i"));
    }

    var signatureInited = false, canvas, ctx, placeholder, drawing = false, hasSignature = false;
    function initSignatureCanvas() {
        canvas = document.getElementById('signatureCanvas');
        if (!canvas) return;
        placeholder = document.getElementById('sigPlaceholder');
        ctx = canvas.getContext('2d');
        var ratio = window.devicePixelRatio || 1;
        var rect = canvas.getBoundingClientRect();
        if (rect.width === 0) return;
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2.4; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#1e293b';
        if (!signatureInited) {
            canvas.addEventListener('mousedown', sigStart);
            canvas.addEventListener('mousemove', sigMove);
            canvas.addEventListener('mouseup', sigStop);
            canvas.addEventListener('mouseleave', sigStop);
            canvas.addEventListener('touchstart', sigStart, { passive: false });
            canvas.addEventListener('touchmove', sigMove, { passive: false });
            canvas.addEventListener('touchend', sigStop);
            document.getElementById('clearSig').addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false;
                if (placeholder) placeholder.style.display = 'flex';
            });
            signatureInited = true;
        }
    }
    function sigGetPos(e) {
        var rect = canvas.getBoundingClientRect();
        var cx = e.touches ? e.touches[0].clientX : e.clientX;
        var cy = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: cx - rect.left, y: cy - rect.top };
    }
    function sigStart(e) { e.preventDefault(); drawing = true; hasSignature = true; if (placeholder) placeholder.style.display = 'none'; var p = sigGetPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
    function sigMove(e) { if (!drawing) return; e.preventDefault(); var p = sigGetPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }
    function sigStop() { drawing = false; }

    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show(); $("#bloc_2").hide(); $("#bloc_3").hide(); $("#bloc_4").hide();
        setTimeout(function() { filterUsers(); }, 100);
    });
    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide(); $("#bloc_2").show(); $("#bloc_3").hide(); $("#bloc_4").hide();
        setTimeout(function() { initSignatureCanvas(); initSelect2(); initFlatpickr(); togglePersonFields(); }, 100);
    });
    $("#add_r").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
    $("#save_r").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
    $("#annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show(); $("#bloc_2").hide(); $("#bloc_3").hide(); $("#bloc_4").hide();
        setTimeout(function() { filterUsers(); }, 100);
    });

    $("#save").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var originalBtnHtml = 'Enregister <i class="zmdi zmdi-save"></i>';
        if (!canvas || typeof canvas.toDataURL !== 'function') initSignatureCanvas();
        if (!canvas || typeof canvas.toDataURL !== 'function') {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Canvas signature introuvable');
            setTimeout(function() { $('#msg').html(""); }, 6000); return;
        }
        var signature = '';
        try { if (hasSignature) signature = canvas.toDataURL('image/png'); } catch (err) {}
        if (!signature || signature.length < 500) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Veuillez signer avant d\'enregistrer');
            setTimeout(function() { $('#msg').html(""); }, 6000); return;
        }
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enregistrement...');
        function resetButton() { btn.prop('disabled', false).html(originalBtnHtml); }
        var page = "<?= $ressource_id_1 ?>";
        $('#msg').html("");
        var formData = $("#form_add").serializeArray();
        formData.push({ name: 'page', value: page });
        formData.push({ name: 'signature', value: signature });
        $.ajax({
            type: "POST", url: "/add_personne", data: $.param(formData),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp) {
                resetButton();
                $("#motif").val(null).trigger("change");
                $("#service").val(null).trigger("change");
                $("#note").val(""); $("#nom").val(""); $("#email").val(""); $("#phone").val("");
                if (heurePicker) { var now = new Date(); heurePicker.setDate(now, true); $("#heure").val(heurePicker.formatDate(now, "Y-m-d H:i")); }
                if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasSignature = false; if (placeholder) placeholder.style.display = 'flex';
                if (typeof $.fn.select2 !== 'undefined') {
                    $("#personne").val(null).trigger("change");
                    $("#type_personne").val(null).trigger("change");
                    $("#nature").val(null).trigger("change");
                }
                togglePersonFields();
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Enregistrement effectué avec succès');
                setTimeout(function() { $('#msg').html(""); }, 9000);
                if (typeof resp === 'string') $("#content_utilisateur").html(resp);
                saveUserFiltersToStorage();
                setTimeout(function() { loadUserFiltersFromStorage(); filterUsers(); }, 100);
            },
            error: function(xhr) {
                resetButton();
                var msg = 'Erreur lors de l\'enregistrement';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    msg = errors[Object.keys(errors)[0]][0];
                }
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + msg);
                setTimeout(function() { $('#msg').html(""); }, 9000);
            },
            complete: function() { resetButton(); }
        });
    });

    $("#oui").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var originalHtml = '<i class="zmdi zmdi-delete"></i> Oui, supprimer';
        if (btn.data('loading')) return;
        btn.data('loading', true).css('pointer-events', 'none')
           .html('<span class="spinner-border spinner-border-sm" style="width:.9rem;height:.9rem;border-width:.15em;"></span> Suppression...');
        $("#non").css('pointer-events', 'none').css('opacity', '.6');
        var id = $("#data_id").html();
        var page = "<?= $ressource_id_1 ?>";
        function resetBtn() {
            btn.data('loading', false).css('pointer-events', 'auto').html(originalHtml);
            $("#non").css('pointer-events', 'auto').css('opacity', '1');
        }
        $.ajax({
            type: "GET", url: "{{ url('/refresh_deleteaccueil') }}",
            data: { id: id, page: page },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp) {
                resetBtn();
                $("#content_utilisateur").html(resp);
                $("#non").trigger("click");
                saveUserFiltersToStorage();
                setTimeout(function() { loadUserFiltersFromStorage(); filterUsers(); }, 100);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Entrée supprimée avec succès');
                setTimeout(function() { $('#msg').html(""); }, 4000);
            },
            error: function(xhr) {
                resetBtn();
                $("#non").trigger("click");
                var message = 'Erreur lors de la suppression';
                if (xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + message);
                setTimeout(function() { $('#msg').html(""); }, 6000);
            }
        });
    });

    $("#user_img_profil").click(function(e) { e.preventDefault(); $("#input_user_img_profil").trigger("click"); });
    $("#input_user_img_profil").change(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('input_user_img_profil', $('#input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('.progress-container').show();
        $.ajax({
            type: "POST", url: "/upload_profil_add",
            data: formData, processData: false, contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var pc = Math.round((evt.loaded / evt.total) * 100);
                        $('.progress-bar').css('width', pc + '%');
                        $('.progress-text').text(pc + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                setTimeout(function() { $('.progress-container').hide(); }, 1000);
                $('#msg').html('Profil teléchargé avec succès');
                $('#user_img_profil').attr('src', response);
                $("#image").val(response);
                setTimeout(function() { $('#msg').html(""); }, 9000);
            },
            error: function(xhr) {
                $('.progress-container').hide();
                $('#msg').html(xhr.responseJSON.message);
                setTimeout(function() { $('#msg').html(""); }, 9000);
            }
        });
    });

    /* ===================== FILTRES ===================== */
    let userFilterTimeout;

    function saveUserFiltersToStorage() {
        localStorage.setItem('userFilters', JSON.stringify({
            nom: $('#filterNom').val() || '',
            email: $('#filterEmail').val() || '',
            phone: $('#filterPhone').val() || '',
            entreeRange: $('#filterEntreeRange').val() || '',
            sortieRange: $('#filterSortieRange').val() || ''
        }));
    }

    function loadUserFiltersFromStorage() {
        const s = localStorage.getItem('userFilters');
        if (s) {
            const f = JSON.parse(s);
            $('#filterNom').val(f.nom || '');
            $('#filterEmail').val(f.email || '');
            $('#filterPhone').val(f.phone || '');
            $('#filterEntreeRange').val(f.entreeRange || '');
            $('#filterSortieRange').val(f.sortieRange || '');
            return true;
        }
        return false;
    }

    function filterUsers() {
        const filterNom   = $('#filterNom').val().toLowerCase();
        const filterEmail = $('#filterEmail').val().toLowerCase();
        const filterPhone = $('#filterPhone').val().toLowerCase();

        var entreeRange = parseRangeValue($('#filterEntreeRange').val());
        var sortieRange = parseRangeValue($('#filterSortieRange').val());

        let visibleCount = 0, newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const nomValue   = ($row.data('nom') || '').toString().toLowerCase();
            const emailValue = ($row.data('email') || '').toString().toLowerCase();
            const phoneValue = ($row.data('phone') || '').toString().toLowerCase();
            const entreeYmd  = ($row.data('entree-ymd') || '').toString();
            const sortieYmd  = ($row.data('sortie-ymd') || '').toString();

            if (filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterEmail && !emailValue.includes(filterEmail)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;

            if (showRow && entreeRange.start && entreeRange.end) {
                if (!entreeYmd || entreeYmd < entreeRange.start || entreeYmd > entreeRange.end) showRow = false;
            }
            if (showRow && sortieRange.start && sortieRange.end) {
                if (!sortieYmd || sortieYmd < sortieRange.start || sortieYmd > sortieRange.end) showRow = false;
            }

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(newIndex);
                newIndex++; visibleCount++;
            } else { $row.hide(); }
        });
        $('#userCount').text(visibleCount);

        if (visibleCount === 0 && (filterNom || filterEmail || filterPhone || entreeRange.start || sortieRange.start)) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Aucune personne ne correspond aux critères de recherche');
            $('#msg').css('display', 'flex');
            setTimeout(function() { $('#msg').html(''); $('#msg').css('display', 'none'); }, 3000);
        }
    }

    function resetUserFilters() {
        $('#filterNom').val(''); $('#filterEmail').val(''); $('#filterPhone').val('');
        $('#filterEntreeRange').val(''); $('#filterSortieRange').val('');

        if ($('#filterEntreeRange').data('daterangepicker')) {
            $('#filterEntreeRange').data('daterangepicker').setStartDate(moment());
            $('#filterEntreeRange').data('daterangepicker').setEndDate(moment());
        }
        if ($('#filterSortieRange').data('daterangepicker')) {
            $('#filterSortieRange').data('daterangepicker').setStartDate(moment());
            $('#filterSortieRange').data('daterangepicker').setEndDate(moment());
        }

        saveUserFiltersToStorage();
        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex); newIndex++;
        });
        $('#userCount').text($('#content_utilisateur tbody tr').length);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
        setTimeout(function() { $('#msg').html(""); }, 3000);
    }

    function debouncedUserFilter() {
        clearTimeout(userFilterTimeout);
        userFilterTimeout = setTimeout(function() { filterUsers(); saveUserFiltersToStorage(); }, 300);
    }

    $(document).ready(function() {
        preloadPersonnes();
        togglePersonFields();
        $('#userCount').text($('#content_utilisateur tbody tr').length);
        const hasSavedFilters = loadUserFiltersFromStorage();
        $('#filterNom, #filterEmail, #filterPhone').on('input change', function() { debouncedUserFilter(); });
        $('#resetFilters').click(function(e) { e.preventDefault(); resetUserFilters(); });
        if (hasSavedFilters) setTimeout(function() { filterUsers(); }, 100);
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_personne'))) {
            setTimeout(function() {
                $('#userCount').text($('#content_utilisateur tbody tr').length);
                loadUserFiltersFromStorage();
                filterUsers();
            }, 200);
        }
    });

    window.addEventListener('beforeunload', function() { saveUserFiltersToStorage(); });
</script>
@endsection
@endsection
