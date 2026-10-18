@php
    use App\Models\appnames;
    use App\Models\Writes;
    use App\Models\Postes;
    use App\Models\Mois;
    use App\Models\Groupes;
    use App\Models\Clients;
    use App\Models\Lieux;
    use App\Models\Patients;
    use Illuminate\Support\Facades\Auth;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp

@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'TRIAGE')
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

/* ============ CARTES : COLONNE CODE ============ */
.code-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border: 1px solid #c7d2fe;
    border-radius: 12px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    font-size: 0.82rem;
    color: #1e40af;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.12);
    letter-spacing: 0.3px;
    white-space: nowrap;
}
.code-badge i { color: #3B82F6; font-size: 1rem; }

/* ============ CARTES : COLONNE NATURE ============ */
.badge-nature {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 16px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.82rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    white-space: nowrap;
    letter-spacing: 0.2px;
}

/* Privé : bleu clair transparent */
.badge-prive {
    background: rgba(59, 130, 246, 0.12);
    border: 1.5px solid rgba(59, 130, 246, 0.4);
    color: #1e40af;
}
.badge-prive i { color: #3B82F6; font-size: 1rem; }

/* Entreprise : orange clair */
.badge-entreprise {
    background: rgba(251, 146, 60, 0.18);
    border: 1.5px solid rgba(251, 146, 60, 0.5);
    color: #c2410c;
}
.badge-entreprise i { color: #f97316; font-size: 1rem; }

.numero-cell { font-weight: 700; color: #0a192f; font-family: 'Courier New', monospace; font-size: 0.82rem; letter-spacing: 0.3px; }

.phone-cell-display { font-weight: 600; color: #0a192f; font-size: 0.82rem; white-space: nowrap; }
.phone-cell-display i { color: #10b981; margin-right: 4px; font-size: 0.9rem; }

.phone-call-link { display: inline-flex !important; align-items: center; gap: 8px; padding: 6px 14px; background: linear-gradient(135deg, #10b981, #059669); color: white !important; border-radius: 40px; font-weight: 700; font-size: 0.85rem; text-decoration: none !important; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); white-space: nowrap; }
.phone-call-link:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(16, 185, 129, 0.4); background: linear-gradient(135deg, #059669, #047857); color: white !important; }
.phone-call-link i { font-size: 1.05rem; }
.phone-call-link .phone-num { font-family: 'Courier New', monospace; letter-spacing: 0.4px; }
.phone-call-link i, .phone-call-link i.zmdi, .phone-call-link i.zmdi-phone, .phone-call-link i.zmdi-phone-in-talk { color: #ffffff !important; }
.phone-call-link:hover i, .phone-call-link:hover i.zmdi, .phone-call-link:hover i.zmdi-phone, .phone-call-link:hover i.zmdi-phone-in-talk { color: #ffffff !important; }

.table .phone-cell-display .phone-call-link { padding: 4px 10px; font-size: 0.78rem; gap: 5px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2); }
.table .phone-cell-display .phone-call-link i { font-size: 0.95rem; }
.table .phone-cell-display .phone-call-link:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35); }

.btn-call-modal { display: inline-flex !important; align-items: center; gap: 8px; padding: 8px 22px; background: linear-gradient(135deg, #10b981, #059669); color: white !important; border-radius: 40px; font-weight: 700; font-size: 0.85rem; text-decoration: none !important; border: none; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3); }
.btn-call-modal i, .btn-call-modal i.zmdi { color: #ffffff !important; }
.btn-call-modal:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(16, 185, 129, 0.45); background: linear-gradient(135deg, #059669, #047857); color: white !important; }
.btn-call-modal.disabled { background: #cbd5e1 !important; color: #64748b !important; cursor: not-allowed !important; box-shadow: none !important; transform: none !important; pointer-events: none; }
.btn-call-modal.disabled i, .btn-call-modal.disabled i.zmdi { color: #64748b !important; }

.sortie-manquante { color: #dc2626 !important; font-weight: 700 !important; font-size: 0.78rem; font-style: italic; display: inline-flex; align-items: center; gap: 5px; background: #fee2e2; padding: 4px 10px; border-radius: 40px; white-space: nowrap; }
.sortie-manquante i { font-size: 1rem; }
.sortie-ok { color: #065f46; font-weight: 600; font-size: 0.82rem; }

.sortie-non-renseignee { color: #dc2626 !important; font-weight: 700 !important; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 40px; background: #fee2e2; transition: all 0.2s; border: 1px solid #fca5a5; font-size: 0.85rem; }
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
#save:disabled, #save[disabled] { opacity: 0.85; cursor: wait !important; transform: none !important; box-shadow: var(--shadow-light) !important; }
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
.form-control, input.form-control, select.form-control, textarea.form-control { width: 100% !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 8px 12px !important; font-weight: 500; font-size: 0.85rem; transition: all 0.2s; box-sizing: border-box; height: 38px !important; line-height: 1.4; }
textarea.form-control { resize: vertical; height: 38px !important; min-height: 38px !important; }
.form-control:focus, select.form-control:focus, textarea.form-control:focus { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important; }

/* ============ FORMULAIRE AJOUT : RESPONSIVE ============ */
#form_add { width: 100%; }

#form_add .form-row-custom {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
    align-items: stretch;
}

#form_add .form-group {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}
#form_add .form-group > label {
    min-height: 22px;
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    flex-shrink: 0;
}
#form_add .form-group > .form-control,
#form_add .form-group > .select2-container,
#form_add .form-group > .flatpickr-input,
#form_add .form-group > input[type="text"] {
    flex: 1 1 auto;
    min-height: 38px;
    width: 100%;
}

/* Colonnes responsives : 2 par ligne sur desktop, 1 par ligne sur mobile */
#form_add .form-col {
    flex: 1 1 calc(50% - 8px);
    min-width: 260px;
    max-width: 100%;
    display: flex;
}

#form_add .form-col-full {
    flex: 1 1 100%;
    min-width: 100%;
    display: flex;
}

/* Champs image + boutons : alignement */
#form_add .form-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
    justify-content: flex-start;
}

.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; background: #ffffff !important; padding: 0 !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px !important; padding: 0 40px 0 14px !important; font-weight: 500; font-size: 0.85rem; color: #1e293b; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; top: 0 !important; right: 8px !important; }
.select2-container--default .select2-selection--single .select2-selection__placeholder,
.select2-container--default .select2-selection--single .select2-selection__clear { line-height: 36px !important; }
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
#signatureCanvas { width: 100%; height: 100%; display: block; cursor: crosshair; touch-action: none; background: #f8fafc; }
.signature-placeholder { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 15px; pointer-events: none; font-style: italic; }
.signature-actions { display: flex; justify-content: flex-end; margin-top: 10px; }
.btn-clear-sig { background: #fff; border: 1px solid #cbd5e1; color: #475569; padding: 6px 14px; border-radius: 40px; font-size: 0.75rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
.btn-clear-sig:hover { background: #f8fafc; color: #0a192f; border-color: #94a3b8; }

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

.table tbody td .phone-call-link { width: auto !important; height: auto !important; border-radius: 40px !important; background: linear-gradient(135deg, #10b981, #059669) !important; margin: 0 !important; }
.table tbody td .phone-call-link:hover { background: linear-gradient(135deg, #059669, #047857) !important; }

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

/* ============================================================
   MODAL SUPPRESSION – DESIGN PREMIUM
   ============================================================ */
#suppression .modal-dialog { max-width: 520px; width: 92%; margin: 1.8rem auto; }
#suppression .modal-content { border-radius: 20px !important; border: none !important; overflow: hidden; box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35) !important; }
#suppression .modal-header { background: linear-gradient(135deg, #ef4444, #b91c1c) !important; color: white !important; border-bottom: none !important; padding: 18px 24px !important; position: relative; }
#suppression .modal-header h5 { font-weight: 700; font-size: 1.05rem; color: white !important; margin: 0; display: flex; align-items: center; gap: 10px; }
#suppression .modal-header .close { color: white !important; opacity: 0.9; font-size: 1.7rem; outline: none !important; text-shadow: none; position: absolute; right: 14px; top: 12px; }
#suppression .modal-body { background: #f8fafc; padding: 22px 24px !important; }
#suppression .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 20px !important; justify-content: center; gap: 12px; }

.sup-warning { display: flex; align-items: flex-start; gap: 12px; background: linear-gradient(135deg, #fef2f2, #fee2e2); border-left: 4px solid #ef4444; border-radius: 12px; padding: 12px 16px; margin-bottom: 18px; }
.sup-warning i { color: #dc2626; font-size: 1.5rem; flex-shrink: 0; margin-top: 2px; }
.sup-warning .sup-warning-text { font-size: 0.85rem; color: #7f1d1d; line-height: 1.4; }
.sup-warning .sup-warning-text strong { display: block; font-weight: 700; margin-bottom: 2px; color: #991b1b; }

.sup-card { background: #ffffff; border-radius: 14px; padding: 8px 18px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #eef2f6; }
.sup-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px dashed #e2e8f0; }
.sup-row:last-child { border-bottom: none; }
.sup-row .sup-label { display: flex; align-items: center; gap: 8px; min-width: 105px; flex-shrink: 0; }
.sup-row .sup-label i { font-size: 1.15rem; }
.sup-row .sup-label span { color: #64748b; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.6px; font-weight: 700; }
.sup-row .sup-value { flex: 1; color: #0a192f; font-weight: 600; font-size: 0.9rem; text-align: right; word-break: break-word; line-height: 1.4; }
.sup-row .sup-value.empty { color: #94a3b8; font-style: italic; font-weight: 500; }
.sup-row .sup-value .code-badge { padding: 3px 10px; font-size: 0.75rem; }

#suppression #non {
    border-radius: 40px !important; padding: 9px 24px !important; font-weight: 600;
    background: #64748b !important; color: white !important; border: none;
    display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;
}
#suppression #non:hover { background: #475569 !important; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3); }

#suppression #oui {
    border-radius: 40px !important; padding: 9px 24px !important; font-weight: 700;
    background: linear-gradient(135deg, #ef4444, #dc2626) !important; color: white !important;
    text-decoration: none !important; display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3); transition: all 0.2s ease; border: none;
}
#suppression #oui:hover { background: linear-gradient(135deg, #dc2626, #b91c1c) !important; transform: translateY(-2px); box-shadow: 0 10px 22px rgba(239, 68, 68, 0.45); color: white !important; }
#suppression #oui i, #suppression #non i { color: #ffffff !important; }

#suppression #oui .spinner-border {
    border-color: #ffffff;
    border-right-color: transparent;
    vertical-align: middle;
    margin-right: 4px;
}

/* Modale profil – style Utilisateurs */
#profil_utilisateur .modal-dialog { max-width: 300px; }
#profil_utilisateur .modal-header h5 { font-weight: bold; font-size: 16px; }

/* ============================================================
   RESPONSIVE GLOBAL
   ============================================================ */
@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.8rem !important; }
    #liste, #add, #save, #annuler, #resetFilters, .btn-primary, .btn-info, .btn-danger { padding: 6px 14px !important; font-size: 0.78rem; }
    .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; }
    .filter-group { width: 100%; min-width: 100%; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .sortie-manquante { font-size: 0.68rem; padding: 3px 8px; }
    .table .phone-cell-display .phone-call-link { font-size: 0.7rem; padding: 3px 8px; }
    .code-badge { font-size: 0.72rem; padding: 5px 10px; }
    .badge-nature { font-size: 0.72rem; padding: 5px 12px; }
    #modal_details .fiche-row .fiche-label { min-width: 100px; }
    #modal_details .fiche-row .fiche-label span { font-size: 0.62rem; }
    #modal_details .fiche-row .fiche-value { font-size: 0.8rem; }
    #modal_details .fiche-body { padding: 16px; }

    /* --- FORMULAIRE AJOUT : 1 colonne sur mobile --- */
    #form_add .form-col,
    #form_add .form-col-full {
        flex: 1 1 100%;
        min-width: 100%;
        max-width: 100%;
    }
    #form_add .form-row-custom { gap: 12px; margin-bottom: 12px; }
    #form_add .form-actions { flex-direction: column; gap: 10px; }
    #form_add .form-actions button,
    #form_add .form-actions a {
        width: 100%;
        justify-content: center;
    }
    #form_add h4 { font-size: 1rem; }

    .sup-row .sup-label { min-width: 90px; }
    .sup-row .sup-label span { font-size: 0.62rem; }
    .sup-row .sup-value { font-size: 0.82rem; }
    #suppression .modal-body { padding: 16px !important; }
}

@media (max-width: 480px) {
    .user-count-badge { font-size: 0.68rem; padding: 3px 10px; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.6rem !important; border-radius: 14px; }
    h4 { font-size: 1rem; padding-left: 14px; border-left-width: 5px; }
    #form_add .form-group > label { font-size: 0.68rem; }
    #form_add .form-control,
    #form_add .select2-container--default .select2-selection--single { height: 40px !important; }
    #form_add .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }
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
                                    <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                        <a id="add" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
                                        <a id="print" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-print"></i> Imprimer
                                        </a>
                                    <?php } else { ?>
                                        <a id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a>
                                        &nbsp;
                                        <a id="print_r" href="">
                                            <i class="zmdi zmdi-print"></i> Imprimer
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Triage</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i>
                    Liste des patients
                    <span class="user-count-badge">
                        <i class="zmdi zmdi-view-list"></i> Total patients : <span id="userCount">0</span>
                    </span>
                </h4>

                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-label text-danger"></i> Code</label>
                        <input type="text" id="filterCode" class="form-control" placeholder="Rechercher par code...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Nom</label>
                        <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-phone text-danger"></i> Téléphone</label>
                        <input type="text" id="filterPhone" class="form-control" placeholder="Rechercher par téléphone...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-city-alt text-danger"></i> Nature</label>
                        <select id="filterNature" class="form-control">
                            <option value="all">Toutes les natures</option>
                            <option value="0">Privé</option>
                            <option value="1">Entreprise</option>
                        </select>
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
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Code</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Téléphone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nature</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($patients as $data)
                                        @php
                                            $natureLabel = (($data->type ?? null) === 0) ? 'Privé' : 'Entreprise';
                                            $imageUrl = !empty($data->image) ? asset($data->image) : '';
                                        @endphp
                                        <tr id="row_{{ $data->id }}" data-user-id="{{ $data->user_id ?? '' }}">
                                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>

                                            <td class="code-cell" data-code="{{ $data->code ?? '' }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(!empty($data->code))
                                                    <span class="code-badge">
                                                        <i class="zmdi zmdi-label"></i>
                                                        {{ $data->code }}
                                                    </span>
                                                @else
                                                    <span style="color:#94a3b8;font-style:italic;">—</span>
                                                @endif
                                            </td>

                                            <td class="align-middle nom-cell" data-nom="{{ $data->name }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(!empty($data->image))
                                                    <a id="voir_profil_<?= $i ?>" href="#">
                                                        <img src="{{ asset($data->image) }}" alt="avatar" class="profile-thumb">
                                                    </a>
                                                @else
                                                    <a id="voir_profil_<?= $i ?>" href="#" style="display:none;"></a>
                                                @endif
                                                {{ $data->name }}
                                            </td>

                                            <td class="phone-cell-display" data-phone="{{ $data->phone }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(!empty($data->phone))
                                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $data->phone) }}"
                                                       class="phone-call-link" title="Appeler {{ $data->phone }}">
                                                        <i class="zmdi zmdi-phone-in-talk"></i>
                                                        <span class="phone-num">{{ $data->phone }}</span>
                                                    </a>
                                                @else
                                                    <span style="color:#94a3b8;font-style:italic;">Non renseigné</span>
                                                @endif
                                            </td>

                                            <td class="nature-cell" data-nature="{{ $data->type ?? '' }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(($data->type ?? null) === 0)
                                                    <span class="badge-nature badge-prive"><i class="zmdi zmdi-account"></i> Privé</span>
                                                @else
                                                    <span class="badge-nature badge-entreprise"><i class="zmdi zmdi-city-alt"></i> Entreprise</span>
                                                @endif
                                            </td>

                                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                    <?php
                                                    $edit = 0;
                                                    $delete = 0;
                                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                        $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                                        $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                    }
                                                    ?>
                                                <?php } ?>

                                                <?php if ((($edit == 1) && (($data->user_id ?? null) == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                                                    <a id="edit_<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit text-success"></i></a>
                                                <?php } else { ?>
                                                    <a id="edit_r<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit text-success"></i></a>
                                                <?php } ?>

                                                <?php if (($delete == 1 && (($data->user_id ?? null) == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                                                    <a id="delete_<?= $i ?>" href="#" title="Supprimer"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } else { ?>
                                                    <a id="delete_r<?= $i ?>" href="#" title="Supprimer"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } ?>

                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_edittriage') }}", {
                                                            user_id: <?= $data->id ?>,
                                                            page: <?= $ressource_id_1 ?>,
                                                        }, function(refresh_edittriage) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_edittriage);
                                                        });
                                                    });
                                                    $("#edit_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#delete_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });

                                                    $("#delete_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();

                                                        $("#data_id").html("<?= $data->id ?>");

                                                        $("#sup_code").html(<?= json_encode(!empty($data->code) ? '<span class="code-badge"><i class="zmdi zmdi-label"></i> ' . $data->code . '</span>' : '<span class="empty">Non renseigné</span>') ?>);

                                                        var nom = <?= json_encode($data->name ?: 'Non renseigné') ?>;
                                                        var genre = <?= json_encode($data->genre) ?>;
                                                        var labelgenre = "";
                                                        if(genre == 0)
                                                        {
                                                            labelgenre = "Non précisé"
                                                        }else if(genre == 1)
                                                        {
                                                            labelgenre = "Homme"
                                                        }
                                                        else if(genre == 2)
                                                        {
                                                            labelgenre = "Femme"
                                                        }

                                                        $("#sup_nom").text(nom);
                                                        $("#sup_genre").text(labelgenre);

                                                        var phone = <?= json_encode($data->phone ?: '') ?>;
                                                        if (phone && phone.trim() !== '') {
                                                            $("#sup_phone").html('<a href="tel:' + phone.replace(/[^\d+]/g, '') + '" class="phone-call-link" style="padding:3px 10px;font-size:0.75rem;"><i class="zmdi zmdi-phone-in-talk"></i> ' + phone + '</a>');
                                                        } else {
                                                            $("#sup_phone").html('<span style="color:#94a3b8;font-style:italic;">Non renseigné</span>');
                                                        }

                                                        var nature = <?= json_encode($natureLabel) ?>;
                                                        if (nature === 'Privé') {
                                                            $("#sup_nature").html('<span class="badge-nature badge-prive"><i class="zmdi zmdi-account"></i> Privé</span>');
                                                        } else {
                                                            $("#sup_nature").html('<span class="badge-nature badge-entreprise"><i class="zmdi zmdi-city-alt"></i> Entreprise</span>');
                                                        }

                                                        $("#btn_sup").trigger("click");
                                                    });

                                                    @if(!empty($data->image))
                                                    $("#voir_profil_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#nom_profil").html(<?= json_encode($data->name) ?>);
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        var url = "<?= $imageUrl ?>";
                                                        $("#contenu_voir_profil").html('<img src="' + url + '" class="img-fluid" style="max-height:100%;width:100%;" />');
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

            <!-- ============ BLOC AJOUT PATIENT (RESPONSIVE) ============ -->
            <div id="bloc_2" style="margin-top: 12px;display: none;margin-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-accounts-add text-info"></i> Ajouter un patient
                </h4>
                <form id="form_add" action="#" method="post">
                    @csrf

                    <p style="color:rgba(0, 0, 0, 0.6); margin-bottom: 24px;" class="text-center">
                        <a href="#">
                            <img id="user_img_profil" class="user__img"
                                 src="{{ asset('storage/images/user/profil_defaut.png') }}" alt="Photo de profil"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </a>
                    </p>
                    <div class="progress-container" style="display:none; margin-top: 10px;">
                        <div class="progress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;"></div>
                        <span class="progress-text" style="font-size:12px;">0%</span>
                    </div>

                    <input type="file" name="input_user_img_profil" id="input_user_img_profil" style="display:none;">
                    <input type="text" name="image" id="image" value="{{ asset('storage/images/user/profil_defaut.png') }}" style="display:none;">

                    <input type="hidden" id="mdp"  name="mdp"  value="12345">
                    <input type="hidden" id="cmdp" name="cmdp" value="12345">

                    <!-- Ligne 1 : Nom + Email -->
                    <div class="form-row-custom">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-account"></i> Nom complet</label>
                                <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom (Ex : Jean Kabila)">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-email"></i> E-mail</label>
                                <input type="text" id="email" name="email" class="form-control" placeholder="Email (Ex : patient@gmail.com)">
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 2 : Téléphone + Nature -->
                    <div class="form-row-custom">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info">
                                    <i class="zmdi zmdi-phone"></i> Téléphone
                                    <span style="color:#94a3b8; font-weight:500; text-transform:none; font-size:0.7rem;">(optionnel)</span>
                                </label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Téléphone (Ex : +243974743675)">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-city-alt"></i> Nature</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="0">Privé</option>
                                    <option value="1" selected>Entreprise</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 3 : Date de naissance + Genre -->
                    <div class="form-row-custom">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-calendar-note"></i> Date de naissance</label>
                                <input type="hidden" id="date_naissance" name="date_naissance">
                                <input type="text" id="date_naissance_picker" class="form-control flatpickr-input"
                                       placeholder="Sélectionner la date de naissance" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-male-female"></i> Genre</label>
                                <select id="genre" name="genre" class="form-control">
                                    <option value="0" selected>Non précisé</option>
                                    <option value="1">Homme</option>
                                    <option value="2">Femme</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="form-row-custom">
                        <div class="form-col-full">
                            <div class="form-actions">
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                    <?php
                                    $add = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                        $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                <?php } ?>
                                <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                    <button id="save" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                                <?php } else { ?>
                                    <button id="save_r" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                                <?php } ?>
                                <button id="annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                            </div>
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

<!-- ==================== MODAL SUPPRESSION – DESIGN PREMIUM ==================== -->
<div class="modal fade" id="suppression" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="zmdi zmdi-alert-triangle" style="font-size: 1.5rem;"></i>
                    Confirmation de suppression
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="sup-warning">
                    <i class="zmdi zmdi-alert-circle"></i>
                    <div class="sup-warning-text">
                        <strong>Attention, action irréversible !</strong>
                        Vous êtes sur le point de supprimer définitivement ce patient dans l'application.
                    </div>
                </div>

                <div class="sup-card">
                    <div class="sup-row">
                        <div class="sup-label">
                            <i class="zmdi zmdi-label" style="color: #3b82f6;"></i>
                            <span>Code</span>
                        </div>
                        <div class="sup-value" id="sup_code">—</div>
                    </div>

                    <div class="sup-row">
                        <div class="sup-label">
                            <i class="zmdi zmdi-account" style="color: #3b82f6;"></i>
                            <span>Nom</span>
                        </div>
                        <div class="sup-value" id="sup_nom">—</div>
                    </div>

                    <div class="sup-row">
                        <div class="sup-label">
                            <i class="zmdi zmdi-male-female" style="color: #3b82f6;"></i>
                            <span>Genre</span>
                        </div>
                        <div class="sup-value" id="sup_genre">—</div>
                    </div>

                    <div class="sup-row">
                        <div class="sup-label">
                            <i class="zmdi zmdi-phone" style="color: #10b981;"></i>
                            <span>Téléphone</span>
                        </div>
                        <div class="sup-value" id="sup_phone">—</div>
                    </div>

                    <div class="sup-row">
                        <div class="sup-label">
                            <i class="zmdi zmdi-city-alt" style="color: #f59e0b;"></i>
                            <span>Nature</span>
                        </div>
                        <div class="sup-value" id="sup_nature">—</div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button id="non" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i> Annuler
                </button>
                <a id="oui" href="#">
                    <i class="zmdi zmdi-delete"></i> Oui, supprimer
                </a>
            </div>

        </div>
    </div>
</div>

<!-- MODAL PROFIL -->
<button style="display: none;" data-toggle="modal" data-target="#profil_utilisateur" id="btn_voir_profil">Sup</button>
<div class="modal fade" id="profil_utilisateur" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Profil : <span id="nom_profil"></span></h5>
            </div>
            <div class="modal-body">
                <p id="contenu_voir_profil" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
            </div>
        </div>
    </div>
</div>

@section('js-code')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
    $("#link_61").addClass("active");

    /* ========== FLATPICKR : DATE DE NAISSANCE ========== */
    var dateNaissancePicker = null;

    function initDateNaissancePicker() {
        if (dateNaissancePicker) return;
        if (typeof flatpickr === 'undefined') return;
        if (flatpickr.l10ns && flatpickr.l10ns.fr) flatpickr.localize(flatpickr.l10ns.fr);
        dateNaissancePicker = flatpickr("#date_naissance_picker", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            maxDate: "today",
            disableMobile: true,
            allowInput: false,
            onChange: function(d, s) { $("#date_naissance").val(s); }
        });
    }

    /* ========== NAVIGATION ENTRE BLOCS ========== */
    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() { filterPatients(); }, 100);
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() { initDateNaissancePicker(); }, 100);
    });

    $("#add_r, #save_r, #print_r").click(function(e) {
        e.preventDefault();
        $("#btn_refus").trigger("click");
    });

    $("#annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() { filterPatients(); }, 100);
    });

    /* ========== IMPRESSION AVEC FILTRES ========== */
    $("#print").click(function(e) {
        e.preventDefault();
        var params = {
            code: $('#filterCode').val(),
            nom: $('#filterNom').val(),
            phone: $('#filterPhone').val(),
            nature: $('#filterNature').val(),
            page: "<?= $ressource_id_1 ?>"
        };
        $.get("{{ url('/get_liste_patient') }}", params, function(response) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("") }}' + response);
        });
    });

    /* ========== UPLOAD IMAGE ========== */
    $("#user_img_profil").click(function(e){
        e.preventDefault();
        $("#input_user_img_profil").trigger("click");
    });

    $("#input_user_img_profil").change(function(e){
        e.preventDefault();
        var formData = new FormData();
        formData.append('input_user_img_profil', $('#input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('.progress-container').show();
        $('.progress-bar').css('width', '0%');
        $('.progress-text').text('0%');

        $.ajax({
            type: "POST",
            url: "/upload_profil_add",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('.progress-bar').css('width', percentComplete + '%');
                        $('.progress-text').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                $('.progress-bar').css('width', '100%');
                $('.progress-text').text('100%');
                setTimeout(function() { $('.progress-container').hide(); }, 1000);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Photo téléchargée avec succès');
                $('#user_img_profil').attr('src', response);
                $("#image").val(response);
                setTimeout(() => { $('#msg').html(""); }, 5000);
            },
            error: function(xhr) {
                $('.progress-container').hide();
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur upload'));
                setTimeout(() => { $('#msg').html(""); }, 5000);
            }
        });
    });

    /* ========== AJOUT PATIENT (avec état de chargement) ========== */
    $("#save").click(function(e) {
        e.preventDefault();

        var $btn = $(this);

        // Éviter double-clic pendant le traitement
        if ($btn.data('loading')) return;

        var nom    = $("#nom").val().trim();
        var email  = $("#email").val().trim();
        var phone  = $("#phone").val().trim();
        var page   = "<?= $ressource_id_1 ?>";

        var formData = $("#form_add").serialize() + "&page=" + encodeURIComponent(page);

        // ---- Validations ----
        if (nom.length === 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Complétez le nom');
            setTimeout(() => $('#msg').html(""), 5000);
            return;
        }
        if (email.length > 0) {
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!regex.test(email)) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
                setTimeout(() => $('#msg').html(""), 5000);
                return;
            }
        }

        // ---- État de chargement ----
        var originalHtml = $btn.html();
        $btn.data('loading', true)
            .attr("disabled", true)
            .css({ 'pointer-events': 'none', 'opacity': '0.85' })
            .html('<span class="spinner-border spinner-border-sm" style="width:1rem;height:1rem;border-width:.15em;margin-right:6px;vertical-align:middle;"></span> Enregistrement...');

        function resetSaveButton() {
            $btn.data('loading', false)
                .attr("disabled", false)
                .css({ 'pointer-events': 'auto', 'opacity': '1' })
                .html(originalHtml);
        }

        $.ajax({
            type: "POST",
            url: "/add_triage",
            data: formData,
            success: function(response) {
                resetSaveButton();

                $("#nom").val("");
                $("#email").val("");
                $("#phone").val("");
                $("#genre").val("0");
                if (dateNaissancePicker) dateNaissancePicker.clear();
                $("#date_naissance").val("");

                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Patient ajouté avec succès');
                $("#content_utilisateur").html(response);
                setTimeout(() => $('#msg').html(""), 5000);

                savePatientFiltersToStorage();
                setTimeout(function() {
                    loadPatientFiltersFromStorage();
                    filterPatients();
                }, 100);
            },
            error: function(xhr) {
                resetSaveButton();

                var msg = 'Erreur lors de l\'ajout';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + msg);
                setTimeout(() => $('#msg').html(""), 5000);
            }
        });
    });

    /* ========== SUPPRESSION ========== */
    $("#oui").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var originalHtml = '<i class="zmdi zmdi-delete"></i> Oui, supprimer';

        if (btn.data('loading')) return;

        btn.data('loading', true)
           .css('pointer-events', 'none')
           .html('<span class="spinner-border spinner-border-sm" style="width:.9rem;height:.9rem;border-width:.15em;"></span> Suppression...');

        $("#non").css('pointer-events', 'none').css('opacity', '.6');

        var id   = $("#data_id").html();
        var page = "<?= $ressource_id_1 ?>";

        function resetBtn() {
            btn.data('loading', false)
               .css('pointer-events', 'auto')
               .html(originalHtml);
            $("#non").css('pointer-events', 'auto').css('opacity', '1');
        }

        $.ajax({
            type: "GET",
            url: "{{ url('/refresh_deletetriage') }}",
            data: { id: id, page: page },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(refresh_deletetriage) {
                resetBtn();
                $("#content_utilisateur").html(refresh_deletetriage);
                $("#non").trigger("click");
                savePatientFiltersToStorage();
                setTimeout(function() {
                    loadPatientFiltersFromStorage();
                    filterPatients();
                }, 100);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Patient supprimé avec succès');
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

    $('#suppression').on('hidden.bs.modal', function() {
        $('#sup_code').html('—');
        $('#sup_nom').text('—');
        $('#sup_phone').html('—');
        $('#sup_nature').html('—');
        $('#data_id').html('');

        var $oui = $('#oui');
        if ($oui.data('loading')) {
            $oui.data('loading', false)
                .css('pointer-events', 'auto')
                .html('<i class="zmdi zmdi-delete"></i> Oui, supprimer');
            $('#non').css('pointer-events', 'auto').css('opacity', '1');
        }
    });

    /* ========== FILTRAGE ========== */
    let patientFilterTimeout;

    function savePatientFiltersToStorage() {
        localStorage.setItem('patientFilters', JSON.stringify({
            code: $('#filterCode').val() || '',
            nom: $('#filterNom').val() || '',
            phone: $('#filterPhone').val() || '',
            nature: $('#filterNature').val() || 'all'
        }));
    }

    function loadPatientFiltersFromStorage() {
        const saved = localStorage.getItem('patientFilters');
        if (saved) {
            const f = JSON.parse(saved);
            $('#filterCode').val(f.code || '');
            $('#filterNom').val(f.nom || '');
            $('#filterPhone').val(f.phone || '');
            $('#filterNature').val(f.nature || 'all');
            return true;
        }
        return false;
    }

    function filterPatients() {
        const filterCode   = $('#filterCode').val().toLowerCase();
        const filterNom    = $('#filterNom').val().toLowerCase();
        const filterPhone  = $('#filterPhone').val().toLowerCase();
        const filterNature = $('#filterNature').val();

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const codeValue   = ($row.find('.code-cell').data('code') || '').toString().toLowerCase();
            const nomValue    = ($row.find('.nom-cell').data('nom') || '').toString().toLowerCase();
            const phoneValue  = ($row.find('.phone-cell-display').data('phone') || '').toString().toLowerCase();
            const natureValue = $row.find('.nature-cell').data('nature');

            if (filterCode && !codeValue.includes(filterCode)) showRow = false;
            if (showRow && filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;
            if (showRow && filterNature !== 'all' && String(natureValue) !== String(filterNature)) showRow = false;

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(newIndex);
                newIndex++;
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#userCount').text(visibleCount);

        if (visibleCount === 0 && (filterCode || filterNom || filterPhone || filterNature !== 'all')) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Aucun patient ne correspond aux critères de recherche');
            $('#msg').css('display', 'flex');
            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 3000);
        }
    }

    function resetPatientFilters() {
        $('#filterCode').val('');
        $('#filterNom').val('');
        $('#filterPhone').val('');
        $('#filterNature').val('all');

        savePatientFiltersToStorage();

        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex);
            newIndex++;
        });
        $('#userCount').text($('#content_utilisateur tbody tr').length);

        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
        $('#msg').css('display', 'flex');
        setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 3000);
    }

    function debouncedPatientFilter() {
        clearTimeout(patientFilterTimeout);
        patientFilterTimeout = setTimeout(() => {
            filterPatients();
            savePatientFiltersToStorage();
        }, 300);
    }

    $(document).ready(function() {
        const totalPatients = $('#content_utilisateur tbody tr').length;
        $('#userCount').text(totalPatients);

        const hasSavedFilters = loadPatientFiltersFromStorage();

        $('#filterCode, #filterNom, #filterPhone, #filterNature').on('input change', function() {
            debouncedPatientFilter();
        });

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetPatientFilters();
        });

        if (hasSavedFilters) {
            setTimeout(function() { filterPatients(); }, 100);
        }
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_triage') || settings.url.includes('deletetriage') || settings.url.includes('edittriage'))) {
            setTimeout(() => {
                $('#userCount').text($('#content_utilisateur tbody tr').length);
                loadPatientFiltersFromStorage();
                filterPatients();
            }, 200);
        }
    });

    window.addEventListener('beforeunload', function() {
        savePatientFiltersToStorage();
    });
</script>
@endsection
@endsection
