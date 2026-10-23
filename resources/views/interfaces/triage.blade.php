<?php
    use App\Models\appnames;
    use App\Models\Writes;
    use App\Models\Postes;
    use App\Models\Mois;
    use App\Models\Groupes;
    use App\Models\Clients;
    use App\Models\Lieux;
    use App\Models\Patients;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';

    $liste_signes_vitaux = DB::table('signesvitauxes')
        ->where('etat', 1)
        ->orderBy('id', 'asc')
        ->get();
?>

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

.top-actions-bar {
    background: #eef3fc;
    border-radius: 60px;
    padding: 12px 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .top-actions-bar { border-radius: 20px; padding: 10px; justify-content: center; }
    .top-actions-bar a { flex: 1 1 auto; justify-content: center; }
}

.table-responsive { overflow-x: auto; overflow-y: visible; border-radius: var(--border-radius-lg); }
.table { width: 100%; min-width: 800px; background: white; border-collapse: collapse; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-light); table-layout: auto; }
.table thead th { background: #E7F5FE !important; color: #0a192f; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; border-right: 1px solid #d0e2f2; }
.table tbody tr { transition: all 0.15s ease; border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd)  { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; }
.table tbody td { padding: 10px 12px !important; vertical-align: middle !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; border-bottom: 1px solid #eef2f6; line-height: 1.4; }
.table tbody td:last-child { text-align: center; vertical-align: middle; }

.code-badge { display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); border: 1px solid #c7d2fe; border-radius: 12px; font-weight: 700; font-family: 'Courier New', monospace; font-size: 0.82rem; color: #1e40af; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.12); letter-spacing: 0.3px; white-space: nowrap; }
.code-badge i { color: #3B82F6; font-size: 1rem; }

.badge-nature { display: inline-flex; align-items: center; gap: 7px; padding: 7px 16px; border-radius: 12px; font-weight: 700; font-size: 0.82rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); white-space: nowrap; letter-spacing: 0.2px; }
.badge-prive { background: rgba(59, 130, 246, 0.12); border: 1.5px solid rgba(59, 130, 246, 0.4); color: #1e40af; }
.badge-prive i { color: #3B82F6; font-size: 1rem; }
.badge-entreprise { background: rgba(251, 146, 60, 0.18); border: 1.5px solid rgba(251, 146, 60, 0.5); color: #c2410c; }
.badge-entreprise i { color: #f97316; font-size: 1rem; }

.numero-cell { font-weight: 700; color: #0a192f; font-family: 'Courier New', monospace; font-size: 0.82rem; letter-spacing: 0.3px; }

.phone-cell-display { font-weight: 600; color: #0a192f; font-size: 0.82rem; white-space: nowrap; }
.phone-cell-display i { color: #10b981; margin-right: 4px; font-size: 0.9rem; }

.phone-call-link { display: inline-flex !important; align-items: center; gap: 8px; padding: 6px 14px; background: linear-gradient(135deg, #10b981, #059669); color: white !important; border-radius: 40px; font-weight: 700; font-size: 0.85rem; text-decoration: none !important; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); white-space: nowrap; cursor: pointer; }
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

#form_add { width: 100%; }
#form_add .form-row-custom { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px; align-items: stretch; }
#form_add .form-group { display: flex; flex-direction: column; height: 100%; width: 100%; }
#form_add .form-group > label { min-height: 22px; display: flex; align-items: center; margin-bottom: 6px; flex-shrink: 0; }
#form_add .form-group > .form-control,
#form_add .form-group > .select2-container,
#form_add .form-group > .flatpickr-input,
#form_add .form-group > input[type="text"] { flex: 1 1 auto; min-height: 38px; width: 100%; }
#form_add .form-col { flex: 1 1 calc(50% - 8px); min-width: 260px; max-width: 100%; display: flex; }
#form_add .form-col-full { flex: 1 1 100%; min-width: 100%; display: flex; }
#form_add .form-col-half { flex: 0 0 calc(50% - 8px); max-width: calc(50% - 8px); display: flex; }
#form_add .form-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; justify-content: flex-start; }

.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; background: #ffffff !important; padding: 0 !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px !important; padding: 0 40px 0 14px !important; font-weight: 500; font-size: 0.85rem; color: #1e293b; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; top: 0 !important; right: 8px !important; }
.select2-dropdown { border: 1px solid #e2e8f0 !important; border-radius: 14px !important; }

.flatpickr-input, input.flatpickr-input + input.form-control { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 8px 12px !important; font-weight: 600 !important; font-size: 0.85rem !important; height: 38px !important; color: #1e293b !important; }
.flatpickr-calendar { border-radius: 14px !important; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18) !important; }
.flatpickr-calendar .flatpickr-months { background: linear-gradient(135deg, #0a192f, #1e3a5f); border-radius: 10px 10px 0 0; color: white; }
.flatpickr-calendar .flatpickr-day.selected { background: #3B82F6 !important; border-color: #3B82F6 !important; }

#msg, #edit_msg { display: none !important; }
#msg:not(:empty), #edit_msg:not(:empty) { display: inline-flex !important; margin-top: 16px !important; padding: 10px 18px !important; background: white !important; border-radius: 50px !important; box-shadow: var(--shadow-light) !important; gap: 10px; font-weight: 600; font-size: 0.8rem; }
#msg:not(:empty):has(i.zmdi-check-circle) { background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important; color: #065f46; border-left: 4px solid #10b981; }
#msg:not(:empty):has(i.zmdi-close-circle) { background: linear-gradient(95deg, #fee2e2, #fecaca) !important; color: #991b1b; border-left: 4px solid #ef4444; }

.table tbody td a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50% !important; background: #f1f5f9; transition: all 0.2s ease; text-decoration: none; margin: 0 2px; cursor: pointer; }
.table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a i.zmdi-edit { color: #10b981; }
.table tbody td a i.zmdi-file-text { color: #3B82F6; }
.table tbody td a:hover { transform: translateY(-2px); }
.table tbody td a:hover i.zmdi-delete { color: #b91c1c; background: #fee2e2; }
.table tbody td a.btn-fiche:hover { background: #dbeafe; }
.table tbody td a.btn-fiche:hover i.zmdi-file-text { color: #1e40af; }

.table tbody td .phone-call-link { width: auto !important; height: auto !important; border-radius: 40px !important; background: linear-gradient(135deg, #10b981, #059669) !important; margin: 0 !important; }
.table tbody td .phone-call-link:hover { background: linear-gradient(135deg, #059669, #047857) !important; }

[style*="background-color: rgba(0, 0, 0, 0.1)"] { background: transparent !important; padding: 0 !important; margin-bottom: 0 !important; }

.profile-thumb { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; display: inline-block; vertical-align: middle; border: 2px solid #ffffff; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); transition: all 0.2s ease; }
a[id^="voir_profil_"] { display: inline-block; vertical-align: middle; line-height: 0; margin-right: 8px; background: transparent !important; text-decoration: none !important; cursor: pointer; pointer-events: auto; transition: transform 0.2s ease; }
a[id^="voir_profil_"]:hover { transform: scale(1.1); }
a[id^="voir_profil_"]:hover .profile-thumb { box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4); border-color: #3B82F6; }
.table tbody td:has(a[id^="voir_profil_"]) { white-space: nowrap; }
a[id^="voir_profil_"] + * { display: inline-block; vertical-align: middle; line-height: 1.4; max-width: calc(100% - 45px); }

/* ============ FICHE PATIENT ============ */
#bloc_fiche { background: transparent !important; padding: 0 !important; box-shadow: none !important; margin-bottom: 1rem; animation: ficheSlideIn 0.35s ease-out; }
@keyframes ficheSlideIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
.fiche-patient-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 60px -15px rgba(10, 25, 47, 0.3); }
.fiche-patient-hero { position: relative; background: linear-gradient(135deg, #0a192f 0%, #1e3a5f 55%, #2c5282 100%); padding: 24px 40px 32px; color: white; display: flex; flex-direction: column; gap: 24px; overflow: visible; }
.fiche-patient-hero::before { content: ''; position: absolute; top: -100px; right: -80px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(59,130,246,0.45), transparent 70%); border-radius: 50%; pointer-events: none; }
.fiche-patient-hero::after { content: ''; position: absolute; bottom: -80px; left: 30%; width: 220px; height: 220px; background: radial-gradient(circle, rgba(16,185,129,0.22), transparent 70%); border-radius: 50%; pointer-events: none; }

.fiche-hero-topbar {
    position: relative;
    z-index: 3;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.fiche-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 44px;
    padding: 0 20px;
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    color: white !important;
    border: none;
    border-radius: 40px;
    font-weight: 700;
    font-size: 0.8rem;
    cursor: pointer;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4);
    letter-spacing: 0.2px;
}
.fiche-back-btn:hover { background: linear-gradient(135deg, #dc2626, #b91c1c) !important; transform: translateX(-3px) translateY(-1px); box-shadow: 0 8px 22px rgba(239, 68, 68, 0.55); color: white !important; }
.fiche-back-btn i { font-size: 1.1rem; color: white !important; }

.fiche-title-badge {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    height: 56px;
    padding: 0 36px;
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.45);
    border-radius: 40px;
    color: white;
    font-weight: 800;
    font-size: 0.95rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    white-space: nowrap;
}
.fiche-title-badge i { font-size: 1.5rem; color: #ffffff !important; }

.fiche-hero-body { position: relative; z-index: 1; display: flex; align-items: center; gap: 28px; }
.fiche-avatar-large { position: relative; z-index: 1; width: 110px; height: 110px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.4); background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 2.2rem; color: white; overflow: hidden; flex-shrink: 0; box-shadow: 0 12px 32px rgba(0,0,0,0.35), inset 0 0 0 2px rgba(255,255,255,0.1); letter-spacing: -1px; }
.fiche-avatar-large img { width: 100%; height: 100%; object-fit: cover; }
.fiche-identity { position: relative; z-index: 1; flex: 1; min-width: 0; }
.fiche-name { font-size: 1.7rem; font-weight: 700; color: white; margin: 0 0 14px 0; line-height: 1.15; letter-spacing: -0.5px; word-break: break-word; }
.fiche-badges { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.fiche-badges .code-badge { background: rgba(255,255,255,0.18) !important; border: 1px solid rgba(255,255,255,0.4) !important; color: white !important; }
.fiche-badges .code-badge i { color: white !important; }
.fiche-badges .badge-nature { background: rgba(255,255,255,0.18) !important; border: 1px solid rgba(255,255,255,0.4) !important; color: white !important; }
.fiche-badges .badge-nature i { color: white !important; }

.fiche-body { padding: 28px 32px 32px; background: #f8fafc; }
.fiche-section { background: white; border-radius: 16px; padding: 22px 26px; margin-bottom: 18px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #eef2f6; }
.fiche-section:last-child { margin-bottom: 0; }
.fiche-section-title { color: #0a192f; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1.1px; margin: 0 0 18px 0; padding-left: 14px; border-left: 4px solid #3B82F6; display: flex; align-items: center; gap: 8px; }
.fiche-section-title i { color: #3B82F6; font-size: 1.15rem; }
.fiche-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px; }
.fiche-info-item { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0; transition: all 0.2s ease; }
.fiche-info-item:hover { border-color: #3B82F6; background: #eff6ff; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59,130,246,0.12); }
.fiche-info-icon { width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.fiche-info-icon i { color: #1e40af; font-size: 1.2rem; }
.fiche-info-icon.green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.fiche-info-icon.green i { color: #065f46; }
.fiche-info-icon.orange { background: linear-gradient(135deg, #fed7aa, #fdba74); }
.fiche-info-icon.orange i { color: #9a3412; }
.fiche-info-icon.purple { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); }
.fiche-info-icon.purple i { color: #6b21a8; }
.fiche-info-icon.red { background: linear-gradient(135deg, #fecaca, #fca5a5); }
.fiche-info-icon.red i { color: #991b1b; }
.fiche-info-content { flex: 1; min-width: 0; }
.fiche-info-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.7px; font-weight: 700; color: #64748b; margin-bottom: 4px; }
.fiche-info-value { font-size: 0.95rem; font-weight: 600; color: #0a192f; word-break: break-word; line-height: 1.35; }
.fiche-info-value.empty { color: #94a3b8; font-style: italic; font-weight: 500; }
.fiche-age-inline { color: #9f1239; font-weight: 700; font-size: 0.85rem; margin-left: 4px; }

/* ============ MODAL SUPPRESSION ============ */
#suppression .modal-dialog { max-width: 520px; width: 92%; margin: 1.8rem auto; }
#suppression .modal-content { border-radius: 20px !important; border: none !important; overflow: hidden; box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35) !important; }
#suppression .modal-header { background: linear-gradient(135deg, #ef4444, #b91c1c) !important; color: white !important; border-bottom: none !important; padding: 18px 24px !important; position: relative; }
#suppression .modal-header h5 { font-weight: 700; font-size: 1.05rem; color: white !important; margin: 0; display: flex; align-items: center; gap: 10px; }
#suppression .modal-header .close { color: white !important; opacity: 0.9; font-size: 1.7rem; outline: none !important; text-shadow: none; position: absolute; right: 14px; top: 12px; cursor: pointer; }
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

#suppression #non { border-radius: 40px !important; padding: 9px 24px !important; font-weight: 600; background: #64748b !important; color: white !important; border: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; cursor: pointer; }
#suppression #non:hover { background: #475569 !important; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3); }
#suppression #oui { border-radius: 40px !important; padding: 9px 24px !important; font-weight: 700; background: linear-gradient(135deg, #ef4444, #dc2626) !important; color: white !important; text-decoration: none !important; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3); transition: all 0.2s ease; border: none; cursor: pointer; }
#suppression #oui:hover { background: linear-gradient(135deg, #dc2626, #b91c1c) !important; transform: translateY(-2px); box-shadow: 0 10px 22px rgba(239, 68, 68, 0.45); color: white !important; }
#suppression #oui i, #suppression #non i { color: #ffffff !important; }

/* ============ MODAL PROFIL PREMIUM ============ */
#profil_utilisateur .modal-dialog { max-width: 420px; width: 92%; margin: 1.8rem auto; }
#profil_utilisateur .modal-content {
    border-radius: 28px !important; border: none !important; overflow: hidden;
    box-shadow: 0 30px 80px -10px rgba(10, 25, 47, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05) inset !important;
    background: linear-gradient(160deg, #ffffff 0%, #eff6ff 60%, #dbeafe 100%);
    position: relative; isolation: isolate;
}
#profil_utilisateur.show .modal-content { animation: profilPopIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1); }
@keyframes profilPopIn { from { opacity: 0; transform: scale(0.8) translateY(30px); } to { opacity: 1; transform: scale(1) translateY(0); } }

#profil_utilisateur .profil-decor { position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none; z-index: 0; }
#profil_utilisateur .profil-decor-1 { width: 220px; height: 220px; top: -80px; right: -60px; background: radial-gradient(circle, rgba(59, 130, 246, 0.5), transparent 70%); }
#profil_utilisateur .profil-decor-2 { width: 200px; height: 200px; bottom: -70px; left: -60px; background: radial-gradient(circle, rgba(16, 185, 129, 0.4), transparent 70%); }

#profil_utilisateur .profil-close-btn {
    position: absolute; top: 14px; right: 14px;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(148, 163, 184, 0.3);
    color: #475569 !important;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 10;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 0;
}
#profil_utilisateur .profil-close-btn i { font-size: 1.2rem; color: inherit !important; }
#profil_utilisateur .profil-close-btn:hover { background: linear-gradient(135deg, #ef4444, #dc2626); color: white !important; transform: rotate(90deg) scale(1.05); box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4); }

#profil_utilisateur .profil-modal-body { position: relative; z-index: 2; padding: 42px 32px 32px; display: flex; flex-direction: column; align-items: center; text-align: center; }
#profil_utilisateur .profil-avatar-wrapper { position: relative; width: 220px; height: 220px; margin-bottom: 22px; display: flex; align-items: center; justify-content: center; }
#profil_utilisateur .profil-avatar-wrapper::before {
    content: ''; position: absolute; inset: -12px; border-radius: 50%;
    background: conic-gradient(from 0deg, #3B82F6, #10b981, #f59e0b, #ef4444, #8b5cf6, #3B82F6);
    animation: profilSpin 6s linear infinite;
    z-index: 0; filter: blur(2px); opacity: 0.85;
}
@keyframes profilSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
#profil_utilisateur .profil-avatar-wrapper::after { content: ''; position: absolute; inset: -4px; border-radius: 50%; background: linear-gradient(160deg, #ffffff, #f1f5f9); z-index: 1; }
#profil_utilisateur .profil-avatar-inner {
    position: relative; z-index: 2; width: 220px; height: 220px; border-radius: 50%; overflow: hidden;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 20px 40px -10px rgba(10, 25, 47, 0.35), inset 0 0 0 2px rgba(255, 255, 255, 0.5);
}
#profil_utilisateur .profil-avatar-inner img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease; }
#profil_utilisateur .profil-avatar-inner:hover img { transform: scale(1.06); }
#profil_utilisateur .profil-avatar-inner i.zmdi { font-size: 5rem; color: #3B82F6; opacity: 0.6; }

#profil_utilisateur .profil-name { font-size: 1.25rem; font-weight: 800; color: #0a192f; margin: 0 0 6px 0; letter-spacing: -0.3px; word-break: break-word; max-width: 100%; line-height: 1.25; }
#profil_utilisateur .profil-subtitle { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(37, 99, 235, 0.18)); color: #1e40af; border-radius: 40px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 22px; }
#profil_utilisateur .profil-subtitle i { font-size: 0.9rem; color: #3B82F6 !important; }

#profil_utilisateur .profil-footer { position: relative; z-index: 2; padding: 0 32px 28px; text-align: center; }
#profil_utilisateur .profil-footer .btn { background: linear-gradient(135deg, #0a192f, #1e3a5f) !important; color: white !important; border-radius: 40px !important; padding: 10px 32px !important; font-weight: 700 !important; font-size: 0.85rem; border: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 22px rgba(10, 25, 47, 0.25); transition: all 0.2s ease; cursor: pointer; }
#profil_utilisateur .profil-footer .btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(10, 25, 47, 0.4); background: linear-gradient(135deg, #1e3a5f, #2c5282) !important; }
#profil_utilisateur .profil-footer .btn i { color: white !important; font-size: 1.1rem; }

#profil_utilisateur.modal.show { backdrop-filter: blur(4px); }

/* ============ BOUTON "ACTIONS" (fiche patient) ============ */
.fiche-hero-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.fiche-menu-container { position: relative; z-index: 20; height: 44px; display: flex; align-items: center; }
.fiche-actions-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    height: 44px; padding: 0 20px 0 16px;
    background: linear-gradient(135deg, #3B82F6, #1d4ed8);
    color: white !important; border: 2px solid rgba(255, 255, 255, 0.45);
    border-radius: 40px; font-weight: 800; font-size: 0.78rem;
    letter-spacing: 0.8px; text-transform: uppercase; cursor: pointer;
    transition: all 0.25s ease; box-shadow: 0 8px 22px rgba(59, 130, 246, 0.6);
}
.fiche-actions-btn i { font-size: 1.2rem; color: white !important; transition: transform 0.25s ease; }
.fiche-actions-btn:hover { background: linear-gradient(135deg, #2563eb, #1e40af); transform: translateY(-2px); box-shadow: 0 12px 28px rgba(59, 130, 246, 0.85); }
.fiche-actions-btn.active { background: linear-gradient(135deg, #2563eb, #1e40af); box-shadow: 0 12px 28px rgba(59, 130, 246, 0.85); }
.fiche-actions-btn.active i { transform: rotate(180deg); }

.fiche-menu-dropdown {
    position: absolute; top: calc(100% + 12px); right: 0;
    min-width: 260px; background: #ffffff; border-radius: 16px; padding: 8px;
    box-shadow: 0 20px 50px -12px rgba(10, 25, 47, 0.5), 0 0 0 1px rgba(148, 163, 184, 0.15);
    opacity: 0; visibility: hidden;
    transform: translateY(-10px) scale(0.92);
    transform-origin: top right;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 30;
}
.fiche-menu-dropdown.show { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
.fiche-menu-dropdown::before { content: ''; position: absolute; top: -6px; right: 32px; width: 12px; height: 12px; background: #ffffff; transform: rotate(45deg); border-radius: 2px; }

.fiche-menu-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 11px; color: #1e2a3e !important; text-decoration: none !important; font-weight: 600; font-size: 0.86rem; transition: all 0.2s ease; cursor: pointer; }
.fiche-menu-item:not(:last-child) { margin-bottom: 2px; }
.fiche-menu-item i { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; transition: all 0.2s ease; }
.fiche-menu-item span { flex: 1; line-height: 1.3; }
.fiche-menu-item.item-vitals i { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
.fiche-menu-item.item-vitals:hover { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #065f46 !important; }
.fiche-menu-item.item-vitals:hover i { transform: scale(1.1) rotate(-6deg); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
.fiche-menu-item.item-transfer i { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
.fiche-menu-item.item-transfer:hover { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1e40af !important; }
.fiche-menu-item.item-transfer:hover i { transform: scale(1.1) rotate(6deg); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
.fiche-menu-item:active { transform: scale(0.97); }

/* ============================================================
   ✨ MODAL : SIGNES VITAUX (AGRANDI)
   ============================================================ */
#modal_signes_vitaux .modal-dialog { max-width: 1050px; width: 92%; margin: 1.8rem auto; }
#modal_signes_vitaux .modal-content { border-radius: 24px !important; border: none !important; overflow: hidden; box-shadow: 0 30px 80px -10px rgba(10, 25, 47, 0.45) !important; background: #ffffff; }
#modal_signes_vitaux .modal-header { background: linear-gradient(135deg, #0a192f, #1e3a5f) !important; color: white !important; border-bottom: none !important; padding: 20px 28px !important; position: relative; }
#modal_signes_vitaux .modal-header h5 { font-weight: 800; font-size: 1.1rem; color: white !important; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: 0.5px; }
#modal_signes_vitaux .modal-header .close { color: white !important; opacity: 0.8; font-size: 1.8rem; outline: none !important; text-shadow: none; position: absolute; right: 16px; top: 14px; transition: all 0.2s; cursor: pointer; }
#modal_signes_vitaux .modal-header .close:hover { opacity: 1; transform: rotate(90deg); }

#modal_signes_vitaux .modal-body { background: #f8fafc; padding: 28px !important; max-height: 65vh; overflow-y: auto; }
#modal_signes_vitaux .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 16px 24px !important; justify-content: flex-end; gap: 12px; }

.signes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
.signes-item { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
.signes-item:focus-within { border-color: #3B82F6; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15); }
.signes-label { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
.signes-label i { color: #3B82F6; font-size: 1.2rem; }
.signes-label .mesure { color: #64748b; font-size: 0.75rem; font-weight: 600; }

.signes-item input { height: 42px !important; background: #f8fafc !important; border: 1px solid #cbd5e1 !important; font-size: 0.95rem !important; font-weight: 700 !important; color: #0a192f !important; text-align: left !important; padding-left: 15px !important; }
.signes-item input:focus { background: #ffffff !important; }

.signes-item select.form-control { height: 42px !important; background: #f8fafc !important; border: 1px solid #cbd5e1 !important; font-size: 0.9rem !important; font-weight: 700 !important; color: #0a192f !important; padding-left: 12px !important; }
.signes-item select.form-control:focus { background: #ffffff !important; }

/* ✅ Motif & Service : exactement 50/50 */
.signes-grid.signes-grid-2 { grid-template-columns: 1fr 1fr; }

/* ✅ Select2 dans le modal signes vitaux (look aligné sur les autres champs) */
#modal_signes_vitaux .select2-container { width: 100% !important; }
#modal_signes_vitaux .select2-container--default .select2-selection--single {
    height: 42px !important;
    background: #f8fafc !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 0 !important;
}
#modal_signes_vitaux .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding: 0 40px 0 14px !important;
    font-weight: 700;
    font-size: 0.9rem;
    color: #0a192f;
}
#modal_signes_vitaux .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #64748b;
    font-weight: 600;
}
#modal_signes_vitaux .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    top: 0 !important;
    right: 8px !important;
}
/* ✅ CORRECTION CENTRAGE DU X (SIGNES VITAUX) */
#modal_signes_vitaux .select2-container--default .select2-selection--single .select2-selection__clear {
    color: #ef4444;
    font-weight: 900;
    font-size: 1.2rem;
    right: 30px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    margin: 0;
    padding: 0;
    line-height: 1;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
#modal_signes_vitaux .select2-dropdown {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    box-shadow: 0 12px 30px rgba(10, 25, 47, 0.2) !important;
    z-index: 10000;
    overflow: hidden;
}
#modal_signes_vitaux .select2-search__field {
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    font-size: 0.85rem !important;
    outline: none !important;
    margin: 6px !important;
    width: calc(100% - 12px) !important;
    box-sizing: border-box !important;
}
#modal_signes_vitaux .select2-search__field:focus {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
}
#modal_signes_vitaux .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: #3B82F6 !important;
    color: white !important;
}
#modal_signes_vitaux .select2-container--default .select2-results__option--selected {
    background: #dbeafe !important;
    color: #1e40af !important;
    font-weight: 700;
}
#modal_signes_vitaux .select2-results__option { padding: 8px 12px; font-size: 0.88rem; }

/* ✅ AJOUT DU CURSEUR POINTEUR SUR LES BOUTONS DU MODAL SIGNES VITAUX */
#modal_signes_vitaux .btn-save-vitals { background: linear-gradient(135deg, #10b981, #059669) !important; color: white !important; border-radius: 40px !important; padding: 10px 28px !important; font-weight: 800 !important; font-size: 0.85rem; border: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 22px rgba(16, 185, 129, 0.3); transition: background 0.2s; cursor: pointer; }
#modal_signes_vitaux .btn-save-vitals:hover { background: linear-gradient(135deg, #059669, #047857) !important; }
#modal_signes_vitaux .btn-save-vitals:disabled { cursor: wait !important; opacity: 0.85; }
#modal_signes_vitaux .btn-cancel-vitals { background: #e2e8f0 !important; color: #475569 !important; border-radius: 40px !important; padding: 10px 24px !important; font-weight: 700 !important; font-size: 0.85rem; border: none; transition: background 0.2s; cursor: pointer; }
#modal_signes_vitaux .btn-cancel-vitals:hover { background: #cbd5e1 !important; color: #1e293b !important; }

#vitals_alert {
    display: none;
    margin: 16px auto 20px auto !important;
    padding: 10px 18px !important;
    background: #ffffff !important;
    border-radius: 50px !important;
    box-shadow: var(--shadow-light) !important;
    gap: 10px;
    font-weight: 600;
    font-size: 0.8rem;
    align-items: center;
    justify-content: center;
    width: fit-content;
    width: -moz-fit-content;
    max-width: calc(100% - 48px);
    text-align: left;
    border: none;
}
#vitals_alert.success { background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important; color: #065f46 !important; border-left: 4px solid #10b981 !important; }
#vitals_alert.error { background: linear-gradient(95deg, #fee2e2, #fecaca) !important; color: #991b1b !important; border-left: 4px solid #ef4444 !important; }
#vitals_alert i { font-size: 1.15rem; line-height: 1; flex-shrink: 0; }

.required-mark { color: #ef4444; font-weight: 900; font-size: 1.2rem; margin-left: 4px; line-height: 1; }

.signes-item.has-error { border-color: #ef4444 !important; background: #fef2f2 !important; animation: shakeError 0.3s ease; }
.signes-item.has-error .signes-label { color: #991b1b; }
.signes-item.has-error input,
.signes-item.has-error select { border-color: #ef4444 !important; background: #fff5f5 !important; }
@keyframes shakeError {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

#fiche_toast {
    display: none;
    position: fixed;
    top: 85px;
    right: 24px;
    z-index: 99999;
    max-width: 440px;
}
#fiche_toast_inner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 14px 40px rgba(10, 25, 47, 0.22), 0 0 0 1px rgba(148, 163, 184, 0.15);
    border-left: 5px solid #3B82F6;
    font-weight: 600;
    font-size: 0.9rem;
    color: #0a192f;
    line-height: 1.4;
}
#fiche_toast_inner i { font-size: 1.4rem; flex-shrink: 0; }

/* ============================================================
   ✨ NOUVEAU MODAL : TRANSFÉRER LA FICHE
   ============================================================ */
#modal_transferer_fiche .modal-dialog { max-width: 750px; width: 92%; margin: 1.8rem auto; }
#modal_transferer_fiche .modal-content { border-radius: 24px !important; border: none !important; overflow: hidden; box-shadow: 0 30px 80px -10px rgba(10, 25, 47, 0.45) !important; background: #ffffff; }
#modal_transferer_fiche .modal-header { background: linear-gradient(135deg, #0a192f, #1e3a5f) !important; color: white !important; border-bottom: none !important; padding: 20px 28px !important; position: relative; }
#modal_transferer_fiche .modal-header h5 { font-weight: 800; font-size: 1.1rem; color: white !important; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: 0.5px; }
#modal_transferer_fiche .modal-header .close { color: white !important; opacity: 0.8; font-size: 1.8rem; outline: none !important; text-shadow: none; position: absolute; right: 16px; top: 14px; transition: all 0.2s; cursor: pointer; }
#modal_transferer_fiche .modal-header .close:hover { opacity: 1; transform: rotate(90deg); }
#modal_transferer_fiche .modal-body { background: #f8fafc; padding: 28px !important; max-height: 65vh; overflow-y: auto; }
#modal_transferer_fiche .modal-footer { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 16px 24px !important; justify-content: flex-end; gap: 12px; }

/* ✅ AJOUT DU CURSEUR POINTEUR SUR LES BOUTONS DU MODAL TRANSFÉRER */
#modal_transferer_fiche .btn-save-vitals { background: linear-gradient(135deg, #10b981, #059669) !important; color: white !important; border-radius: 40px !important; padding: 10px 28px !important; font-weight: 800 !important; font-size: 0.85rem; border: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 22px rgba(16, 185, 129, 0.3); transition: background 0.2s; cursor: pointer; }
#modal_transferer_fiche .btn-save-vitals:hover { background: linear-gradient(135deg, #059669, #047857) !important; }
#modal_transferer_fiche .btn-save-vitals:disabled { opacity: 0.85; cursor: wait !important; }
#modal_transferer_fiche .btn-cancel-vitals { background: #e2e8f0 !important; color: #475569 !important; border-radius: 40px !important; padding: 10px 24px !important; font-weight: 700 !important; font-size: 0.85rem; border: none; transition: background 0.2s; cursor: pointer; }
#modal_transferer_fiche .btn-cancel-vitals:hover { background: #cbd5e1 !important; color: #1e293b !important; }

/* Select2 partagé dans le modal transfert */
#modal_transferer_fiche .select2-container { width: 100% !important; }
#modal_transferer_fiche .select2-container--default .select2-selection--single {
    height: 42px !important;
    background: #f8fafc !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 0 !important;
}
#modal_transferer_fiche .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
    padding: 0 40px 0 14px !important;
    font-weight: 700;
    font-size: 0.9rem;
    color: #0a192f;
}
#modal_transferer_fiche .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #64748b; font-weight: 600; }
#modal_transferer_fiche .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; top: 0 !important; right: 8px !important; }
/* ✅ CORRECTION CENTRAGE DU X (TRANSFÉRER FICHE) */
#modal_transferer_fiche .select2-container--default .select2-selection--single .select2-selection__clear {
    color: #ef4444;
    font-weight: 900;
    font-size: 1.2rem;
    right: 30px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    margin: 0;
    padding: 0;
    line-height: 1;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
#modal_transferer_fiche .select2-dropdown {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    box-shadow: 0 12px 30px rgba(10, 25, 47, 0.2) !important;
    z-index: 10000;
    overflow: hidden;
}
#modal_transferer_fiche .select2-search__field {
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    font-size: 0.85rem !important;
    outline: none !important;
    margin: 6px !important;
    width: calc(100% - 12px) !important;
    box-sizing: border-box !important;
}
#modal_transferer_fiche .select2-search__field:focus { border-color: #3B82F6 !important; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important; }
#modal_transferer_fiche .select2-container--default .select2-results__option--highlighted[aria-selected] { background: #3B82F6 !important; color: white !important; }
#modal_transferer_fiche .select2-container--default .select2-results__option--selected { background: #dbeafe !important; color: #1e40af !important; font-weight: 700; }
#modal_transferer_fiche .select2-results__option { padding: 8px 12px; font-size: 0.88rem; cursor: pointer; }

@media (max-width: 768px) {
    #fiche_toast { top: auto; bottom: 20px; right: 12px; left: 12px; max-width: none; }
}

@media (max-width: 768px) {
    #modal_signes_vitaux .modal-dialog { width: 95%; }
    #modal_signes_vitaux .modal-body { padding: 18px !important; }
    #modal_transferer_fiche .modal-dialog { width: 95%; }
    #modal_transferer_fiche .modal-body { padding: 18px !important; }
    .signes-grid { grid-template-columns: repeat(auto-fill, minmax(100%, 1fr)); gap: 14px; }
    .signes-grid.signes-grid-2 { grid-template-columns: 1fr; }
    #modal_signes_vitaux .modal-footer { flex-direction: column; }
    #modal_signes_vitaux .modal-footer button { width: 100%; justify-content: center; }
    #modal_transferer_fiche .modal-footer { flex-direction: column; }
    #modal_transferer_fiche .modal-footer button { width: 100%; justify-content: center; }
    #vitals_alert { padding: 10px 14px !important; font-size: 0.78rem; max-width: calc(100% - 28px); }
}

@media (max-width: 768px) {
    .fiche-patient-hero { gap: 20px; padding: 20px 20px 28px; }
    .fiche-hero-topbar { display: flex; flex-direction: column; align-items: center; gap: 12px; }
    .fiche-back-btn { width: 100%; max-width: 320px; margin: 0 auto; }
    .fiche-title-badge { position: relative; top: auto; left: auto; transform: none; width: auto; max-width: 100%; margin: 0 auto 15px auto; order: -1; height: 46px; font-size: 0.78rem; letter-spacing: 1.5px; padding: 0 20px; white-space: nowrap; }
    .fiche-title-badge i { font-size: 1.15rem; }
    .fiche-hero-right { width: 100%; justify-content: center; }
    .fiche-menu-container { width: 100%; max-width: 320px; justify-content: center; margin: 0 auto; }
    .fiche-actions-btn { width: 100%; }
}

@media (max-width: 480px) {
    #profil_utilisateur .modal-dialog { max-width: 92%; }
    #profil_utilisateur .profil-modal-body { padding: 40px 22px 24px; }
    #profil_utilisateur .profil-avatar-wrapper { width: 180px; height: 180px; }
    #profil_utilisateur .profil-avatar-inner { width: 180px; height: 180px; }
    #profil_utilisateur .profil-name { font-size: 1.1rem; }
    #profil_utilisateur .profil-footer { padding: 0 22px 22px; }
    .fiche-menu-dropdown { min-width: 230px; right: -10px; }
    .fiche-actions-btn { padding: 0 16px; font-size: 0.72rem; }
    .fiche-actions-btn i { font-size: 1.1rem; }
    .fiche-title-badge { height: 40px; font-size: 0.7rem; letter-spacing: 1px; padding: 0 15px; }
    .fiche-title-badge i { font-size: 1rem; }
}

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.8rem !important; }
    #liste, #add, #save, #annuler, #resetFilters, .btn-primary, .btn-info, .btn-danger { padding: 6px 14px !important; font-size: 0.78rem; }
    .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; }
    .filter-group { width: 100%; min-width: 100%; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .code-badge { font-size: 0.72rem; padding: 5px 10px; }
    .badge-nature { font-size: 0.72rem; padding: 5px 12px; }
    #form_add .form-col, #form_add .form-col-full, #form_add .form-col-half { flex: 1 1 100%; min-width: 100%; max-width: 100%; }
    #form_add .form-row-custom { gap: 12px; margin-bottom: 12px; }
    #form_add .form-actions { flex-direction: column; gap: 10px; }
    #form_add .form-actions button, #form_add .form-actions a { width: 100%; justify-content: center; }
    .sup-row .sup-label { min-width: 90px; }
    .sup-row .sup-label span { font-size: 0.62rem; }
    .sup-row .sup-value { font-size: 0.82rem; }
    .fiche-hero-body { flex-direction: column; text-align: center; gap: 16px; }
    .fiche-name { font-size: 1.35rem; }
    .fiche-body { padding: 20px 14px; }
    .fiche-section { padding: 18px 18px; }
    .fiche-avatar-large { width: 88px; height: 88px; font-size: 1.8rem; }
    .fiche-grid { grid-template-columns: 1fr; }
}

@media (max-width: 480px) {
    .user-count-badge { font-size: 0.68rem; padding: 3px 10px; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.6rem !important; border-radius: 14px; }
    h4 { font-size: 1rem; padding-left: 14px; border-left-width: 5px; }
    #form_add .form-group > label { font-size: 0.68rem; }
    .fiche-hero-topbar { justify-content: center; }
}
</style>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-actions-bar">
                    <a class="btn-primary btn-sm" id="liste" href="">
                        <i class="zmdi zmdi-accounts"></i> Liste
                    </a>

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
                            <a id="print" class="btn-primary btn-sm" href="">
                                <i class="zmdi zmdi-print"></i> Imprimer
                            </a>
                        <?php } else { ?>
                            <a id="add_r" href=""><i class="zmdi zmdi-accounts-add"></i> Ajouter</a>
                            <a id="print_r" href=""><i class="zmdi zmdi-print"></i> Imprimer</a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top: 10px;" class="container">
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
                                        @endphp
                                        <tr id="row_{{ $data->id }}"
                                            data-patient-id="{{ $data->id }}"
                                            data-patient-code="{{ $data->code ?? '' }}"
                                            data-patient-name="{{ $data->name ?? '' }}"
                                            data-patient-phone="{{ $data->phone ?? '' }}"
                                            data-patient-type="{{ $data->type ?? 0 }}"
                                            data-patient-image="{{ !empty($data->image) ? asset($data->image) : '' }}"
                                            data-patient-genre="{{ $data->genre ?? 0 }}"
                                            data-patient-email="{{ $data->email ?? '' }}"
                                            data-patient-date="{{ $data->date_de_naissance ?? '' }}"
                                            data-patient-address="{{ $data->adresse ?? '' }}">

                                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>

                                            <td class="code-cell" data-code="{{ $data->code ?? '' }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(!empty($data->code))
                                                    <span class="code-badge"><i class="zmdi zmdi-label"></i> {{ $data->code }}</span>
                                                @else
                                                    <span style="color:#94a3b8;font-style:italic;">—</span>
                                                @endif
                                            </td>

                                            <td class="align-middle nom-cell" data-nom="{{ $data->name }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if(!empty($data->image))
                                                    <a id="voir_profil_<?= $i ?>" href="#" data-img="{{ asset($data->image) }}" data-name="{{ $data->name }}" title="Agrandir la photo">
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
                                                    <a id="edit_<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit"></i></a>
                                                <?php } else { ?>
                                                    <a id="edit_r<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit"></i></a>
                                                <?php } ?>

                                                <a class="btn-fiche" href="#" data-fiche-id="<?= $data->id ?>" title="Voir la fiche du patient">
                                                    <i class="zmdi zmdi-file-text"></i>
                                                </a>

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
                                                        if(genre == 0) { $("#sup_genre").html('<span style="color:#94a3b8;font-style:italic;">Non précisé</span>'); }
                                                        else if(genre == 1) { $("#sup_genre").html('<span>Homme</span>'); }
                                                        else if(genre == 2) { $("#sup_genre").html('<span>Femme</span>'); }

                                                        $("#sup_nom").text(nom);

                                                        var phone = <?= json_encode($data->phone ?: '') ?>;
                                                        if (phone && phone.trim() !== '') {
                                                            $("#sup_phone").html('<a href="tel:' + phone.replace(/[^\d+]/g, '') + '" class="phone-call-link" style="padding:3px 10px;font-size:0.75rem;"><i class="zmdi zmdi-phone-in-talk"></i> ' + phone + '</a>');
                                                        } else {
                                                            $("#sup_phone").html('<span style="color:#94a3b8;font-style:italic;">Non renseigné</span>');
                                                        }

                                                        var adresse = <?= json_encode($data->adresse ?? '') ?>;
                                                        if (adresse && adresse.trim() !== '') {
                                                            $("#sup_adresse").text(adresse);
                                                        } else {
                                                            $("#sup_adresse").html('<span style="color:#94a3b8;font-style:italic;">Non renseigné</span>');
                                                        }

                                                        var nature = <?= json_encode($natureLabel) ?>;
                                                        if (nature === 'Privé') {
                                                            $("#sup_nature").html('<span class="badge-nature badge-prive"><i class="zmdi zmdi-account"></i> Privé</span>');
                                                        } else {
                                                            $("#sup_nature").html('<span class="badge-nature badge-entreprise"><i class="zmdi zmdi-city-alt"></i> Entreprise</span>');
                                                        }

                                                        $("#btn_sup").trigger("click");
                                                    });
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

                    <div class="form-row-custom">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-calendar-note"></i> Date de naissance</label>
                                <input type="hidden" id="date_naissance" name="date_naissance">
                                <input type="text" id="date_naissance_picker" class="form-control flatpickr-input" placeholder="Sélectionner la date de naissance" readonly>
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

                    <div class="form-row-custom">
                        <div class="form-col form-col-half">
                            <div class="form-group">
                                <label class="text-info"><i class="zmdi zmdi-pin"></i> Adresse</label>
                                <input type="text" id="adresse" name="adresse" class="form-control"
                                       placeholder="Adresse (Ex : Avenue Kasa-Vubu, Kinshasa)">
                            </div>
                        </div>
                    </div>

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

            <div id="bloc_fiche" style="margin-top: 12px; display: none;" class="col-lg-12">
                <div class="fiche-patient-card">
                    <div class="fiche-patient-hero">
                        <div class="fiche-title-badge">
                            <i class="zmdi zmdi-file-text"></i> Fiche patient
                        </div>

                        <div class="fiche-hero-topbar">
                            <button type="button" class="fiche-back-btn" id="fiche_back">
                                <i class="zmdi zmdi-arrow-left"></i> Retour à la liste
                            </button>

                            <div class="fiche-hero-right">
                                <div class="fiche-menu-container">
                                    <button type="button" class="fiche-actions-btn" id="fiche_menu_btn" aria-label="Plus d'options">
                                        <i class="zmdi zmdi-more-vert"></i>
                                        <span>Actions</span>
                                    </button>

                                    <div class="fiche-menu-dropdown" id="fiche_menu_dropdown" role="menu">
                                        <a href="#" class="fiche-menu-item item-vitals" id="fiche_menu_signes" role="menuitem">
                                            <i class="zmdi zmdi-chart"></i>
                                            <span>Ajouter les signes vitaux</span>
                                        </a>
                                        <a href="#" class="fiche-menu-item item-transfer" id="fiche_menu_transferer" role="menuitem">
                                            <i class="zmdi zmdi-swap"></i>
                                            <span>Transférer la fiche</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fiche-hero-body">
                            <div class="fiche-avatar-large" id="fiche_avatar">
                                <span id="fiche_avatar_initials">?</span>
                            </div>
                            <div class="fiche-identity">
                                <h2 class="fiche-name" id="fiche_nom">—</h2>
                                <div class="fiche-badges">
                                    <span class="code-badge" id="fiche_code">
                                        <i class="zmdi zmdi-label"></i>
                                        <span>—</span>
                                    </span>
                                    <span class="badge-nature badge-prive" id="fiche_nature">
                                        <i class="zmdi zmdi-account"></i>
                                        <span>Privé</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fiche-body">
                        <div class="fiche-section">
                            <h3 class="fiche-section-title">
                                <i class="zmdi zmdi-file-text"></i> Informations personnelles
                            </h3>
                            <div class="fiche-grid">
                                <div class="fiche-info-item">
                                    <div class="fiche-info-icon green"><i class="zmdi zmdi-phone"></i></div>
                                    <div class="fiche-info-content">
                                        <div class="fiche-info-label">Téléphone</div>
                                        <div class="fiche-info-value" id="fiche_phone">—</div>
                                    </div>
                                </div>
                                <div class="fiche-info-item">
                                    <div class="fiche-info-icon"><i class="zmdi zmdi-email"></i></div>
                                    <div class="fiche-info-content">
                                        <div class="fiche-info-label">E-mail</div>
                                        <div class="fiche-info-value" id="fiche_email">—</div>
                                    </div>
                                </div>
                                <div class="fiche-info-item">
                                    <div class="fiche-info-icon orange"><i class="zmdi zmdi-calendar-note"></i></div>
                                    <div class="fiche-info-content">
                                        <div class="fiche-info-label">Date de naissance</div>
                                        <div class="fiche-info-value" id="fiche_date">—</div>
                                    </div>
                                </div>
                                <div class="fiche-info-item">
                                    <div class="fiche-info-icon purple"><i class="zmdi zmdi-male-female"></i></div>
                                    <div class="fiche-info-content">
                                        <div class="fiche-info-label">Genre</div>
                                        <div class="fiche-info-value" id="fiche_genre">—</div>
                                    </div>
                                </div>
                                <div class="fiche-info-item">
                                    <div class="fiche-info-icon red"><i class="zmdi zmdi-pin"></i></div>
                                    <div class="fiche-info-content">
                                        <div class="fiche-info-label">Adresse</div>
                                        <div class="fiche-info-value" id="fiche_adresse">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<span id="data_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>

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
                        Vous êtes sur le point de supprimer définitivement ce patient.
                    </div>
                </div>
                <div class="sup-card">
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-label" style="color: #3b82f6;"></i><span>Code</span></div>
                        <div class="sup-value" id="sup_code">—</div>
                    </div>
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-account" style="color: #3b82f6;"></i><span>Nom</span></div>
                        <div class="sup-value" id="sup_nom">—</div>
                    </div>
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-male-female" style="color: #3b82f6;"></i><span>Genre</span></div>
                        <div class="sup-value" id="sup_genre">—</div>
                    </div>
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-pin" style="color: #ef4444;"></i><span>Adresse</span></div>
                        <div class="sup-value" id="sup_adresse">—</div>
                    </div>
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-phone" style="color: #10b981;"></i><span>Téléphone</span></div>
                        <div class="sup-value" id="sup_phone">—</div>
                    </div>
                    <div class="sup-row">
                        <div class="sup-label"><i class="zmdi zmdi-city-alt" style="color: #f59e0b;"></i><span>Nature</span></div>
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

<button style="display: none;" data-toggle="modal" data-target="#profil_utilisateur" id="btn_voir_profil">Sup</button>
<div class="modal fade" id="profil_utilisateur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="profil-decor profil-decor-1"></div>
            <div class="profil-decor profil-decor-2"></div>

            <button type="button" class="profil-close-btn" data-dismiss="modal" aria-label="Fermer">
                <i class="zmdi zmdi-close"></i>
            </button>

            <div class="profil-modal-body">
                <div class="profil-avatar-wrapper">
                    <div class="profil-avatar-inner" id="profil_avatar_container">
                        <i class="zmdi zmdi-account"></i>
                    </div>
                </div>
                <h4 class="profil-name" id="nom_profil">Photo</h4>
                <span class="profil-subtitle">
                    <i class="zmdi zmdi-camera"></i> Photo du patient
                </span>
            </div>

            <div class="profil-footer">
                <button type="button" class="btn" data-dismiss="modal">
                    <i class="zmdi zmdi-check"></i> D'accord
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== ✨ MODAL : AJOUTER SIGNES VITAUX ✨ ==================== -->
<div class="modal fade" id="modal_signes_vitaux" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="zmdi zmdi-chart"></i>
                    Signes vitaux : <span id="vitals_patient_name" style="color: #3B82F6; margin-left: 5px;"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form_signes_vitaux">
                @csrf
                <input type="hidden" id="vitals_patient_id"  name="patient_id"  value="">
                <input type="hidden" id="vitals_registre_id" name="registre_id" value="">

                <div class="modal-body">

                    <!-- ✅ Motif & Service : 50/50 + recherche Select2 -->
                    <div class="signes-grid signes-grid-2" style="margin-bottom: 20px;">
                        <div class="signes-item">
                            <label class="signes-label" for="vitals_motif">
                                <i class="zmdi zmdi-comment-text"></i>
                                Motif
                                <span class="required-mark">*</span>
                            </label>
                            <select id="vitals_motif" name="motif_id" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="signes-item">
                            <label class="signes-label" for="vitals_service">
                                <i class="zmdi zmdi-hospital"></i>
                                Service
                                <span class="required-mark">*</span>
                            </label>
                            <select id="vitals_service" name="service_id" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="signes-grid">
                        @foreach($liste_signes_vitaux as $signe)
                            @php
                                $isRequired = (isset($signe->obligatoire) && $signe->obligatoire == 0);
                            @endphp
                            <div class="signes-item {{ $isRequired ? 'is-required' : '' }}" data-obligatoire="{{ $isRequired ? '1' : '0' }}">
                                <label class="signes-label" for="signe_{{ $signe->id }}">
                                    <i class="zmdi {{ !empty($signe->icone) ? $signe->icone : 'zmdi-chart' }}"></i>
                                    {{ $signe->nom }}
                                    <span class="mesure">({{ $signe->mesure }})</span>
                                    @if($isRequired)
                                        <span class="required-mark" title="Champ obligatoire">*</span>
                                    @endif
                                </label>

                                @if($signe->type === 'number')
                                    <input type="number"
                                           step="any"
                                           inputmode="decimal"
                                           class="form-control vitals-number-input"
                                           id="signe_{{ $signe->id }}"
                                           name="signes[{{ $signe->id }}]"
                                           data-required="{{ $isRequired ? '1' : '0' }}"
                                           placeholder="Saisir une valeur...{{ $isRequired ? ' (obligatoire)' : '' }}">
                                @else
                                    <input type="text"
                                           class="form-control"
                                           id="signe_{{ $signe->id }}"
                                           name="signes[{{ $signe->id }}]"
                                           data-required="{{ $isRequired ? '1' : '0' }}"
                                           placeholder="Saisir...{{ $isRequired ? ' (obligatoire)' : '' }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel-vitals" data-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn-save-vitals" id="btn_save_vitals">
                        Enregistrer <i class="zmdi zmdi-check-circle"></i>
                    </button>
                </div>

                <div style="height:70px; margin-bottom: 20px;" id="vitals_alert"></div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== ✨ NOUVEAU MODAL : TRANSFÉRER LA FICHE ✨ ==================== -->
<div class="modal fade" id="modal_transferer_fiche" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="zmdi zmdi-swap"></i>
                    Transférer la fiche : <span id="transfert_patient_name" style="color: #3B82F6; margin-left: 5px;"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form_transferer_fiche">
                @csrf
                <input type="hidden" id="transfert_patient_id" name="patient_id" value="">

                <div class="modal-body">
                    <!-- Motif & Service (50/50) -->
                    <div class="signes-grid signes-grid-2" style="margin-bottom: 20px;">
                        <div class="signes-item">
                            <label class="signes-label" for="transfert_motif">
                                <i class="zmdi zmdi-comment-text"></i>
                                Motif
                                <span class="required-mark">*</span>
                            </label>
                            <select id="transfert_motif" name="motif_id" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="signes-item">
                            <label class="signes-label" for="transfert_service">
                                <i class="zmdi zmdi-hospital"></i>
                                Service
                                <span class="required-mark">*</span>
                            </label>
                            <select id="transfert_service" name="service_id" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <!-- Utilisateur destinataire -->
                    <div class="signes-item">
                        <label class="signes-label" for="transfert_user">
                            <i class="zmdi zmdi-account-circle"></i>
                            Transférer à (destinataire)
                            <span class="required-mark">*</span>
                        </label>
                        <select id="transfert_user" name="user_id" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel-vitals" data-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn-save-vitals" id="btn_transferer">
                        Transférer <i class="zmdi zmdi-swap"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ Toast global pour feedback (vérification registre) -->
<div id="fiche_toast">
    <div id="fiche_toast_inner">
        <i class="zmdi zmdi-info" style="color:#3B82F6;"></i>
        <span id="fiche_toast_msg"></span>
    </div>
</div>

@section('js-code')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    console.log('✅ SCRIPT TRIAGE CHARGÉ');

    $("#link_61").addClass("active");

    let currentPatientFullData = null;
    let vitalsAlertTimeout;

    /* ============================================================
       🔔 Toast flottant
       ============================================================ */
    function showPatientToast(message, type) {
        type = type || 'info';
        var $toast = $('#fiche_toast');
        var $inner = $('#fiche_toast_inner');
        var $icon  = $inner.find('i.zmdi');

        $('#fiche_toast_msg').text(message);

        var colors = { info: '#3B82F6', success: '#10b981', error: '#ef4444' };
        var icons  = { info: 'zmdi-info', success: 'zmdi-check-circle', error: 'zmdi-alert-circle' };

        $inner.css('border-left-color', colors[type]);
        $icon.attr('class', 'zmdi ' + icons[type]).css('color', colors[type]);

        $toast.stop(true, true).fadeIn(200);
        clearTimeout(window._ficheToastT);
        window._ficheToastT = setTimeout(function() { $toast.fadeOut(300); }, 5500);
    }

    /* ============================================================
       🔍 INIT SELECT2 sur Motif & Service (avec recherche intégrée)
       ============================================================ */
    function initVitalsSelect2() {
        if (typeof $().select2 === 'undefined') return;

        if ($('#vitals_motif').hasClass('select2-hidden-accessible')) {
            $('#vitals_motif').select2('destroy');
        }
        if ($('#vitals_service').hasClass('select2-hidden-accessible')) {
            $('#vitals_service').select2('destroy');
        }

        $('#vitals_motif').select2({
            dropdownParent: $('#modal_signes_vitaux'),
            placeholder: 'Rechercher un motif...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun motif trouvé'; },
                searching: function() { return 'Recherche...'; },
                inputTooShort: function() { return 'Tapez pour rechercher...'; }
            }
        });

        $('#vitals_service').select2({
            dropdownParent: $('#modal_signes_vitaux'),
            placeholder: 'Rechercher un service...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun service trouvé'; },
                searching: function() { return 'Recherche...'; },
                inputTooShort: function() { return 'Tapez pour rechercher...'; }
            }
        });
    }

    $('#modal_signes_vitaux').on('shown.bs.modal', function() {
        initVitalsSelect2();
    });

    /* ============================================================
       ✨ INIT SELECT2 sur le modal de TRANSFERT
       ============================================================ */
    function initTransfertSelect2() {
        if (typeof $().select2 === 'undefined') return;

        ['#transfert_motif', '#transfert_service', '#transfert_user'].forEach(function(sel) {
            if ($(sel).hasClass('select2-hidden-accessible')) {
                $(sel).select2('destroy');
            }
        });

        $('#transfert_motif').select2({
            dropdownParent: $('#modal_transferer_fiche'),
            placeholder: 'Rechercher un motif...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun motif trouvé'; },
                searching: function() { return 'Recherche...'; }
            }
        });

        $('#transfert_service').select2({
            dropdownParent: $('#modal_transferer_fiche'),
            placeholder: 'Rechercher un service...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun service trouvé'; },
                searching: function() { return 'Recherche...'; }
            }
        });

        $('#transfert_user').select2({
            dropdownParent: $('#modal_transferer_fiche'),
            placeholder: 'Rechercher un utilisateur...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return 'Aucun utilisateur trouvé'; },
                searching: function() { return 'Recherche...'; }
            }
        });
    }

    $('#modal_transferer_fiche').on('shown.bs.modal', function() {
        initTransfertSelect2();
    });

    /* ========== FLATPICKR ========== */
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

    /* ========== NAVIGATION ========== */
    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2, #bloc_3, #bloc_4, #bloc_fiche").hide();
        setTimeout(function() { filterPatients(); }, 100);
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1, #bloc_3, #bloc_4, #bloc_fiche").hide();
        $("#bloc_2").show();
        setTimeout(function() { initDateNaissancePicker(); }, 100);
    });

    $("#add_r, #save_r, #print_r").click(function(e) {
        e.preventDefault();
        $("#btn_refus").trigger("click");
    });

    $("#annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_2, #bloc_3, #bloc_4, #bloc_fiche").hide();
        $("#bloc_1").show();
        setTimeout(function() { filterPatients(); }, 100);
    });

    /* ========== IMPRESSION ========== */
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
            $("#bloc_1, #bloc_2, #bloc_3, #bloc_fiche").hide();
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
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur upload');
                setTimeout(() => { $('#msg').html(""); }, 5000);
            }
        });
    });

    /* ========== AJOUT PATIENT ========== */
    $("#save").click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.data('loading')) return;

        var nom    = $("#nom").val().trim();
        var email  = $("#email").val().trim();
        var page   = "<?= $ressource_id_1 ?>";
        var formData = $("#form_add").serialize() + "&page=" + encodeURIComponent(page);

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

        var originalHtml = $btn.html();
        $btn.data('loading', true)
            .attr("disabled", true)
            .css({ 'pointer-events': 'none', 'opacity': '0.85' })
            .html('<span class="spinner-border spinner-border-sm" style="width:1rem;height:1rem;border-width:.15em;margin-right:6px;vertical-align:middle;"></span> Enregistrement...');

        function resetSaveButton() {
            $btn.data('loading', false).attr("disabled", false)
                .css({ 'pointer-events': 'auto', 'opacity': '1' }).html(originalHtml);
        }

        $.ajax({
            type: "POST",
            url: "/add_triage",
            data: formData,
            success: function(response) {
                resetSaveButton();
                $("#nom, #email, #phone, #adresse").val("");
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

        btn.data('loading', true).css('pointer-events', 'none')
           .html('<span class="spinner-border spinner-border-sm" style="width:.9rem;height:.9rem;border-width:.15em;"></span> Suppression...');
        $("#non").css('pointer-events', 'none').css('opacity', '.6');

        var id   = $("#data_id").html();
        var page = "<?= $ressource_id_1 ?>";

        function resetBtn() {
            btn.data('loading', false).css('pointer-events', 'auto').html(originalHtml);
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
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de la suppression');
                setTimeout(function() { $('#msg').html(""); }, 6000);
            }
        });
    });

    $('#suppression').on('hidden.bs.modal', function() {
        $('#sup_code, #sup_phone, #sup_nature, #sup_adresse').html('—');
        $('#sup_nom').text('—');
        $('#data_id').html('');
        var $oui = $('#oui');
        if ($oui.data('loading')) {
            $oui.data('loading', false).css('pointer-events', 'auto')
                .html('<i class="zmdi zmdi-delete"></i> Oui, supprimer');
            $('#non').css('pointer-events', 'auto').css('opacity', '1');
        }
    });

    /* ========== CALCUL ÂGE ========== */
    function calculateAge(dateStr) {
        if (!dateStr) return null;
        var parts = String(dateStr).split('-');
        if (parts.length !== 3) return null;
        var year  = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day   = parseInt(parts[2], 10);
        if (isNaN(year) || isNaN(month) || isNaN(day)) return null;
        var today = new Date();
        var birth = new Date(year, month - 1, day);
        if (isNaN(birth.getTime())) return null;
        var age = today.getFullYear() - birth.getFullYear();
        var m   = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        return age >= 0 ? age : null;
    }

    function formatDateFrench(dateStr) {
        if (!dateStr) return null;
        var parts = String(dateStr).split('-');
        if (parts.length !== 3) return null;
        var year  = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day   = parseInt(parts[2], 10);
        if (isNaN(year) || isNaN(month) || isNaN(day)) return null;
        var date = new Date(year, month - 1, day);
        if (isNaN(date.getTime())) return null;
        try {
            return date.toLocaleDateString('fr-FR', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        } catch (e) { return null; }
    }

    /* ========== FICHE PATIENT VIRTUELLE ========== */
    function openPatientFiche($row) {
        var $avatar = $('#fiche_avatar');
        var patient = {
            code:    $row.attr('data-patient-code')    || $row.find('.code-cell').data('code') || '',
            name:    $row.attr('data-patient-name')    || $row.find('.nom-cell').data('nom')  || $.trim($row.find('.nom-cell').text()) || '',
            phone:   $row.attr('data-patient-phone')   || $row.find('.phone-cell-display').data('phone') || '',
            type:    $row.attr('data-patient-type')    !== undefined ? $row.attr('data-patient-type') : $row.find('.nature-cell').data('nature'),
            image:   $row.attr('data-patient-image')   || ($row.find('.profile-thumb').length ? $row.find('.profile-thumb').attr('src') : ''),
            genre:   $row.attr('data-patient-genre'),
            email:   $row.attr('data-patient-email')   || '',
            date:    $row.attr('data-patient-date')    || '',
            adresse: $row.attr('data-patient-address') || ''
        };

        if (patient.image) {
            $avatar.html('<img src="' + patient.image + '" alt="avatar">');
        } else {
            var initials = (patient.name || '?').trim().split(/\s+/)
                .map(function(w){ return w[0]; }).slice(0, 2).join('').toUpperCase() || '?';
            $avatar.html('<span>' + initials + '</span>');
        }

        $('#fiche_nom').text(patient.name || 'Non renseigné');

        if (patient.code) {
            $('#fiche_code').show();
            $('#fiche_code span').text(patient.code);
        } else {
            $('#fiche_code').hide();
        }

        var isEntreprise = (patient.type == 1);
        $('#fiche_nature')
            .removeClass('badge-prive badge-entreprise')
            .addClass(isEntreprise ? 'badge-entreprise' : 'badge-prive');
        $('#fiche_nature i').attr('class', 'zmdi ' + (isEntreprise ? 'zmdi-city-alt' : 'zmdi-account'));
        $('#fiche_nature span').text(isEntreprise ? 'Entreprise' : 'Privé');

        if (patient.phone) {
            var clean = patient.phone.replace(/[^\d+]/g, '');
            $('#fiche_phone').removeClass('empty')
                .html('<a href="tel:' + clean + '" class="phone-call-link" style="padding:4px 12px;font-size:0.8rem;"><i class="zmdi zmdi-phone-in-talk"></i> ' + patient.phone + '</a>');
        } else {
            $('#fiche_phone').addClass('empty').text('Non renseigné');
        }

        if (patient.email) {
            $('#fiche_email').removeClass('empty').text(patient.email);
        } else {
            $('#fiche_email').addClass('empty').text('Non renseigné');
        }

        if (patient.date) {
            var formatted = formatDateFrench(patient.date);
            if (formatted) {
                var age = calculateAge(patient.date);
                var ageStr = '';
                if (age !== null) {
                    ageStr = ' <span class="fiche-age-inline">(' + age + ' an' + (age > 1 ? 's' : '') + ')</span>';
                }
                $('#fiche_date').removeClass('empty').html('Né(e) le ' + formatted + ageStr);
            } else {
                $('#fiche_date').addClass('empty').text('Non renseigné');
            }
        } else {
            $('#fiche_date').addClass('empty').text('Non renseigné');
        }

        var genreText = (patient.genre == 1) ? 'Homme' : (patient.genre == 2) ? 'Femme' : 'Non précisé';
        $('#fiche_genre').text(genreText).removeClass('empty');

        if (patient.adresse && patient.adresse.trim() !== '') {
            $('#fiche_adresse').removeClass('empty').text(patient.adresse);
        } else {
            $('#fiche_adresse').addClass('empty').text('Non renseigné');
        }

        $('#bloc_fiche')
            .data('patient-id',    $row.attr('data-patient-id')   || '')
            .data('patient-name',  patient.name)
            .data('patient-code',  patient.code);

        $('#fiche_menu_dropdown').removeClass('show');
        $('#fiche_menu_btn').removeClass('active');

        $('#bloc_1, #bloc_2, #bloc_3, #bloc_4').hide();
        $('#bloc_fiche').show();
        $('html, body').animate({ scrollTop: 0 }, 250);

        var patientId = $row.attr('data-patient-id');
        if (patientId) get_detail_patient(patientId);
    }

    function get_detail_patient(id) {
        if (!id) return;
        currentPatientFullData = null;
        $.get("{{ route('get_detail_patient') }}", { id: id }, function(response) {
            if (response && response.success) {
                currentPatientFullData = response.data;
                console.log('✅ Détails patient :', currentPatientFullData);
            }
        }).fail(function(xhr) {
            console.warn('⚠️ Erreur get_detail_patient (HTTP ' + xhr.status + ')');
        });
    }

    /* ============================================================
       ✨ CLIC SUR L'ICÔNE PROFIL → MODAL PREMIUM
       ============================================================ */
    $('#content_utilisateur').on('click', 'a[id^="voir_profil_"]', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $link = $(this);
        var $row  = $link.closest('tr');

        var image = $link.attr('data-img')
                    || $link.find('img').attr('src')
                    || $row.attr('data-patient-image')
                    || '';
        var name  = $link.attr('data-name')
                    || $row.attr('data-patient-name')
                    || $row.find('.nom-cell').data('nom')
                    || 'Patient';

        if (!image) return;

        $('#profil_avatar_container').html('<img src="' + image + '" alt="' + name + '">');
        $('#nom_profil').text(name);

        $('#btn_voir_profil').trigger('click');
    });

    $('#profil_utilisateur').on('hidden.bs.modal', function() {
        $('#profil_avatar_container').html('<i class="zmdi zmdi-account"></i>');
        $('#nom_profil').text('Photo');
    });

    /* ============================================================
       ✨ BOUTON "ACTIONS" - FICHE PATIENT
       ============================================================ */
    $(document).on('click', '#fiche_menu_btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var $menu = $('#fiche_menu_dropdown');
        if ($menu.hasClass('show')) {
            $menu.removeClass('show');
            $btn.removeClass('active');
        } else {
            $menu.addClass('show');
            $btn.addClass('active');
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.fiche-menu-container').length) {
            $('#fiche_menu_dropdown').removeClass('show');
            $('#fiche_menu_btn').removeClass('active');
        }
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#fiche_menu_dropdown').removeClass('show');
            $('#fiche_menu_btn').removeClass('active');
        }
    });

    /* ============================================================
       🩺 Ouvre le modal signes vitaux + pré-remplit Motif & Service
       ============================================================ */
    function ouvrirModalSignesVitaux(patientId, patientName, context) {
        if (!patientId) return;
        context = context || {};

        if ($('#vitals_motif').hasClass('select2-hidden-accessible')) {
            $('#vitals_motif').select2('destroy');
        }
        if ($('#vitals_service').hasClass('select2-hidden-accessible')) {
            $('#vitals_service').select2('destroy');
        }

        $('#form_signes_vitaux')[0].reset();
        $('#vitals_patient_id').val(patientId);
        $('#vitals_patient_name').text(patientName);
        $('#vitals_registre_id').val(context.registre_id || '');

        function getLabel(item) {
            return item.nom || item.libelle || item.label || item.name || ('#' + item.id);
        }

        var $motif = $('#vitals_motif');
        $motif.empty().append('<option value=""></option>');
        if (Array.isArray(context.motifs) && context.motifs.length > 0) {
            context.motifs.forEach(function(m) {
                $motif.append('<option value="' + m.id + '">' + getLabel(m) + '</option>');
            });
        }
        if (context.motif_selected_id !== null && context.motif_selected_id !== undefined) {
            $motif.val(String(context.motif_selected_id));
        }

        var $service = $('#vitals_service');
        $service.empty().append('<option value=""></option>');
        if (Array.isArray(context.services) && context.services.length > 0) {
            context.services.forEach(function(s) {
                $service.append('<option value="' + s.id + '">' + getLabel(s) + '</option>');
            });
        }
        if (context.service_selected_id !== null && context.service_selected_id !== undefined) {
            $service.val(String(context.service_selected_id));
        }

        $('.signes-item').removeClass('has-error');
        $('#vitals_alert').stop(true, true).hide().text('').removeClass('success error').css({ display: 'none', opacity: '' });
        clearTimeout(vitalsAlertTimeout);
        $('#modal_signes_vitaux').modal('show');
    }

    /* ============================================================
       🩺 Clic "Ajouter les signes vitaux" → le modal s'ouvre TOUJOURS
       ============================================================ */
    $(document).on('click', '#fiche_menu_signes', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $fiche      = $('#bloc_fiche');
        var patientId   = $fiche.data('patient-id');
        var patientName = $fiche.data('patient-name') || '';

        $('#fiche_menu_dropdown').removeClass('show');
        $('#fiche_menu_btn').removeClass('active');

        if (!patientId) return;

        var $menuItem    = $(this);
        var originalText = $menuItem.find('span').text();

        $menuItem.css({ 'pointer-events': 'none', 'opacity': '0.6' });
        $menuItem.find('span').text('Vérification...');

        $.ajax({
            type: "GET",
            url: "{{ route('check_patient_registre') }}",
            data: { patient_id: patientId },
            success: function(response) {
                $menuItem.css({ 'pointer-events': 'auto', 'opacity': '1' });
                $menuItem.find('span').text(originalText);

                if (response.ok) {
                    ouvrirModalSignesVitaux(patientId, patientName, response);

                    if (!response.found_today) {
                        showPatientToast(
                            "Patient non enregistré aujourd'hui. Complétez le motif et le service.",
                            'info'
                        );
                    }
                } else {
                    showPatientToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                $menuItem.css({ 'pointer-events': 'auto', 'opacity': '1' });
                $menuItem.find('span').text(originalText);

                var msg = "Impossible de vérifier l'enregistrement du patient.";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showPatientToast(msg, 'error');
            }
        });
    });

    /* ============================================================
       🔒 SÉCURITÉ DE SAISIE : CHIFFRES ET DÉCIMALES UNIQUEMENT
       ============================================================ */
    $(document).on('keydown', '.vitals-number-input', function(e) {
        if (e.ctrlKey || e.metaKey || e.altKey) return;
        const allowedKeys = ['0','1','2','3','4','5','6','7','8','9', '.', ',', 'Backspace', 'Tab', 'Escape', 'Enter', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
        if (allowedKeys.indexOf(e.key) !== -1) return;
        e.preventDefault();
    });

    $(document).on('input', '.vitals-number-input', function() {
        let val = $(this).val();
        if (val.includes(',')) {
            val = val.replace(',', '.');
            $(this).val(val);
        }
        if ((val.match(/\./g) || []).length > 1) {
            val = val.slice(0, -1);
            $(this).val(val);
        }
    });

    $(document).on('input change', '.signes-item input[data-required="1"]', function() {
        var val = ($(this).val() || '').trim();
        if (val !== '') {
            $(this).closest('.signes-item').removeClass('has-error');
        }
    });

    /* ============================================================
       💾 SOUMISSION DU FORMULAIRE DES SIGNES VITAUX
       ============================================================ */
    $('#form_signes_vitaux').submit(function(e) {
        e.preventDefault();
        var $btn = $('#btn_save_vitals');
        var originalHtml = $btn.html();
        var $alert = $('#vitals_alert');

        clearTimeout(vitalsAlertTimeout);

        if (!$('#vitals_motif').val()) {
            showPatientToast('Veuillez sélectionner un motif.', 'error');
            $('#vitals_motif').select2('open');
            return;
        }
        if (!$('#vitals_service').val()) {
            showPatientToast('Veuillez sélectionner un service.', 'error');
            $('#vitals_service').select2('open');
            return;
        }

        var $firstInvalid = null;
        var hasError = false;

        $('.signes-item input[data-required="1"]').each(function() {
            var $input = $(this);
            var val = ($input.val() || '').trim();
            if (val === '') {
                $input.closest('.signes-item').addClass('has-error');
                if (!$firstInvalid) $firstInvalid = $input;
                hasError = true;
            } else {
                $input.closest('.signes-item').removeClass('has-error');
            }
        });

        if (hasError) {
            if ($firstInvalid) {
                var $item = $firstInvalid.closest('.signes-item');
                $item[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(function() { $firstInvalid.focus(); }, 300);
            }

            $alert.stop(true, true)
                  .removeClass('success')
                  .addClass('error')
                  .html('<i class="zmdi zmdi-alert-circle"></i> Veuillez remplir tous les champs obligatoires marqués d\'un <strong style="margin-left:4px;">*</strong>')
                  .css({ display: 'flex', opacity: 0 })
                  .animate({ opacity: 1 }, 150);

            vitalsAlertTimeout = setTimeout(function() {
                $alert.animate({ opacity: 0 }, 400, function() {
                    $(this).css({ display: 'none' });
                });
            }, 4000);

            return;
        }

        $btn.prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Enregistrement...');
        $alert.stop(true, true).hide().removeClass('success error').css({ display: 'none', opacity: '' });

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "/add_signes_vitaux",
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);
                $alert.stop(true, true)
                      .removeClass('error')
                      .addClass('success')
                      .html('<i class="zmdi zmdi-check-circle"></i> Signes vitaux enregistrés avec succès !')
                      .css({ display: 'flex', opacity: 0 })
                      .animate({ opacity: 1 }, 150);
                setTimeout(function() {
                    $('#modal_signes_vitaux').modal('hide');
                }, 1500);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                var msg = 'Erreur lors de l\'enregistrement.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;

                $alert.stop(true, true)
                      .removeClass('success')
                      .addClass('error')
                      .html('<i class="zmdi zmdi-close-circle"></i> ' + msg)
                      .css({ display: 'flex', opacity: 0 })
                      .animate({ opacity: 1 }, 150);

                vitalsAlertTimeout = setTimeout(function() {
                    $alert.animate({ opacity: 0 }, 400, function() {
                        $(this).css({ display: 'none' });
                    });
                }, 4000);

                console.error(xhr.responseText);
            }
        });
    });

    $('#modal_signes_vitaux').on('hidden.bs.modal', function() {
        clearTimeout(vitalsAlertTimeout);
        $('.signes-item').removeClass('has-error');
        $('#vitals_alert').stop(true, true).hide().text('').removeClass('success error').css({ display: 'none', opacity: '' });
        $('#btn_save_vitals').prop('disabled', false).html('Enregistrer <i class="zmdi zmdi-check-circle"></i>');
    });

    /* ============================================================
       🔄 ACTION : OUVRIR LE MODAL DE TRANSFERT (remplace l'ancien alert)
       ============================================================ */
    $(document).on('click', '#fiche_menu_transferer', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $fiche = $('#bloc_fiche');
        var patientId = $fiche.data('patient-id');
        var patientName = $fiche.data('patient-name') || '';

        $('#fiche_menu_dropdown').removeClass('show');
        $('#fiche_menu_btn').removeClass('active');

        if (!patientId) return;

        // Reset select2
        ['#transfert_motif', '#transfert_service', '#transfert_user'].forEach(function(sel) {
            if ($(sel).hasClass('select2-hidden-accessible')) {
                $(sel).select2('destroy');
            }
        });

        $('#form_transferer_fiche')[0].reset();
        $('#transfert_patient_id').val(patientId);
        $('#transfert_patient_name').text(patientName);

        // Ouvrir le modal tout de suite (même si les données tardent)
        $('#modal_transferer_fiche').modal('show');

        // Charger les données
        $.ajax({
            type: "GET",
            url: "{{ route('get_transfert_data') }}",
            data: { patient_id: patientId },
            success: function(response) {
                if (response.ok) {
                    var getLabel = function(i) { return i.nom || i.libelle || i.label || i.name || ('#' + i.id); };

                    // Motifs
                    var $m = $('#transfert_motif');
                    $m.empty().append('<option value=""></option>');
                    response.motifs.forEach(function(x) {
                        $m.append('<option value="' + x.id + '">' + getLabel(x) + '</option>');
                    });
                    if (response.motif_selected_id) $m.val(String(response.motif_selected_id));

                    // Services
                    var $s = $('#transfert_service');
                    $s.empty().append('<option value=""></option>');
                    response.services.forEach(function(x) {
                        $s.append('<option value="' + x.id + '">' + getLabel(x) + '</option>');
                    });
                    if (response.service_selected_id) $s.val(String(response.service_selected_id));

                    // Users
                    var $u = $('#transfert_user');
                    $u.empty().append('<option value=""></option>');
                    response.users.forEach(function(x) {
                        var label = (x.name || '') + (x.email ? ' — ' + x.email : '');
                        $u.append('<option value="' + x.id + '">' + label + '</option>');
                    });

                    // Re-init select2
                    initTransfertSelect2();
                } else {
                    showPatientToast(response.message || 'Erreur de chargement.', 'error');
                    $('#modal_transferer_fiche').modal('hide');
                }
            },
            error: function(xhr) {
                var m = 'Impossible de charger les données de transfert.';
                if (xhr.responseJSON && xhr.responseJSON.message) m = xhr.responseJSON.message;
                showPatientToast(m, 'error');
                $('#modal_transferer_fiche').modal('hide');
            }
        });
    });

    /* ============================================================
       💾 SOUMISSION DU FORMULAIRE DE TRANSFERT
       ============================================================ */
    $('#form_transferer_fiche').submit(function(e) {
        e.preventDefault();
        var $btn = $('#btn_transferer');
        var originalHtml = $btn.html();

        if (!$('#transfert_motif').val()) {
            showPatientToast('Veuillez sélectionner un motif.', 'error');
            $('#transfert_motif').select2('open');
            return;
        }
        if (!$('#transfert_service').val()) {
            showPatientToast('Veuillez sélectionner un service.', 'error');
            $('#transfert_service').select2('open');
            return;
        }
        if (!$('#transfert_user').val()) {
            showPatientToast('Veuillez sélectionner un utilisateur destinataire.', 'error');
            $('#transfert_user').select2('open');
            return;
        }

        $btn.prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Transfert...');

        $.ajax({
            type: "POST",
            url: "{{ route('transferer_fiche') }}",
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);
                if (response.ok) {
                    showPatientToast(response.message || 'Fiche transférée avec succès !', 'success');
                    $('#modal_transferer_fiche').modal('hide');
                } else {
                    showPatientToast(response.message || 'Erreur lors du transfert.', 'error');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                var m = 'Erreur lors du transfert.';
                if (xhr.responseJSON && xhr.responseJSON.message) m = xhr.responseJSON.message;
                showPatientToast(m, 'error');
            }
        });
    });

    $('#modal_transferer_fiche').on('hidden.bs.modal', function() {
        $('#form_transferer_fiche')[0].reset();
        $('#transfert_patient_id').val('');
        $('#transfert_patient_name').text('');
        $('#btn_transferer').prop('disabled', false).html('Transférer <i class="zmdi zmdi-swap"></i>');
    });

    /* ============================================================
       📄 CLIC SUR L'ICÔNE FICHE → FICHE PATIENT
       ============================================================ */
    $('#content_utilisateur').on('click', 'a.btn-fiche', function(e) {
        e.preventDefault();
        e.stopPropagation();
        openPatientFiche($(this).closest('tr'));
    });

    $('#fiche_back').on('click', function(e) {
        e.preventDefault();
        $('#fiche_menu_dropdown').removeClass('show');
        $('#fiche_menu_btn').removeClass('active');
        $('#bloc_fiche')
            .hide()
            .removeData('patient-id')
            .removeData('patient-name')
            .removeData('patient-code');
        $('#bloc_1').show();
        setTimeout(function(){ filterPatients(); }, 50);
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
    }

    function resetPatientFilters() {
        $('#filterCode, #filterNom, #filterPhone').val('');
        $('#filterNature').val('all');
        savePatientFiltersToStorage();
        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex++);
        });
        $('#userCount').text($('#content_utilisateur tbody tr').length);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés').css('display', 'flex');
        setTimeout(() => { $('#msg').html('').css('display', 'none'); }, 3000);
    }

    function debouncedPatientFilter() {
        clearTimeout(patientFilterTimeout);
        patientFilterTimeout = setTimeout(() => {
            filterPatients();
            savePatientFiltersToStorage();
        }, 300);
    }

    $(document).ready(function() {
        $('#userCount').text($('#content_utilisateur tbody tr').length);
        const hasSavedFilters = loadPatientFiltersFromStorage();
        $('#filterCode, #filterNom, #filterPhone, #filterNature').on('input change', debouncedPatientFilter);
        $('#resetFilters').click(function(e) { e.preventDefault(); resetPatientFilters(); });
        if (hasSavedFilters) setTimeout(filterPatients, 100);
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
