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

{{-- ===================== SELECT2 CSS ===================== --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- ===================== FLATPICKR CSS (Date picker) ===================== --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES AUTRES PAGES
   ============================================================ */

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

#bloc_1, #bloc_2, #bloc_3, #bloc_4 {
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
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* ========== TABLEAU ========== */
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

.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #e2e8f0;
}

.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd)  { background-color: #ffffff; }
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

.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
}

/* ========== BOUTONS ========== */
#bloc_1 button,
#bloc_2 button,
#bloc_3 button,
#bloc_4 button,
#liste,
#add,
#add_r,
#save,
#save_r,
#annuler,
#edit_save,
#edit_annuler,
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

.filter-group { flex: 1; min-width: 150px; }
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
.filter-group .form-control { height: 36px; }

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

/* ========== FORMULAIRES ========== */
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
    position: relative;
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
    min-height: 38px !important;
    line-height: 1.4;
    padding-top: 8px;
    padding-bottom: 8px;
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

/* ========== SELECT2 ========== */
.select2-container {
    width: 100% !important;
    max-width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 38px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    background: #ffffff !important;
    padding: 0 !important;
    transition: all 0.2s;
    position: relative;
    display: flex !important;
    align-items: center;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    color: #1e293b !important;
    font-weight: 500;
    font-size: 0.85rem;
    text-align: left !important;
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 14px !important;
    padding-right: 40px !important;
    box-sizing: border-box;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    display: flex !important;
    align-items: center;
    height: 100%;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #94a3b8;
    font-weight: 500;
    text-align: left !important;
    display: flex !important;
    align-items: center;
    height: 100%;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    right: 12px;
    top: 0;
    position: absolute;
    display: flex !important;
    align-items: center;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #e31b23 transparent transparent transparent !important;
    margin-top: 0 !important;
    top: 50% !important;
    transform: translateY(-50%);
    position: absolute;
}

.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #0a192f !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
}

.select2-dropdown {
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
    z-index: 9999;
    max-width: 100% !important;
    box-sizing: border-box;
}

.select2-container--open .select2-dropdown--below,
.select2-container--open .select2-dropdown--above {
    max-width: 100% !important;
}

.select2-search--dropdown {
    padding: 8px;
    background: #f8fafc;
    box-sizing: border-box;
    width: 100%;
}
.select2-search--dropdown .select2-search__field {
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 6px 10px !important;
    outline: none;
    font-size: 0.82rem;
    text-align: left !important;
    width: 100% !important;
    box-sizing: border-box;
}
.select2-search--dropdown .select2-search__field:focus {
    border-color: #3B82F6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
}

.select2-results {
    max-height: 260px;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    box-sizing: border-box;
}

.select2-results__options {
    max-height: 260px;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    box-sizing: border-box;
}

.select2-results__option {
    padding: 9px 14px;
    font-size: 0.85rem;
    text-align: left !important;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    word-break: break-word;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #3B82F6 !important;
    color: white !important;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #dbeafe !important;
    color: #1e40af !important;
}

/* ========== FLATPICKR ========== */
.flatpickr-input,
.flatpickr-alt-input,
input.flatpickr-input.form-control[readonly],
input.flatpickr-input + input.form-control {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 8px 12px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    height: 38px !important;
    cursor: pointer;
    color: #1e293b !important;
    text-align: left !important;
}
.flatpickr-input:focus,
.flatpickr-alt-input:focus,
input.flatpickr-input + input.form-control:focus {
    border-color: #0a192f !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    outline: none;
}
.flatpickr-calendar {
    border-radius: 14px !important;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18) !important;
    border: 1px solid #e2e8f0 !important;
    font-family: 'Segoe UI', Roboto, sans-serif !important;
    padding: 8px;
}
.flatpickr-calendar .flatpickr-months {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
    border-radius: 10px 10px 0 0;
    color: white;
    padding: 6px 0;
}
.flatpickr-calendar .flatpickr-months .flatpickr-month,
.flatpickr-calendar .flatpickr-months .flatpickr-prev-month,
.flatpickr-calendar .flatpickr-months .flatpickr-next-month {
    color: white;
    fill: white;
}
.flatpickr-calendar .flatpickr-current-month {
    font-weight: 700;
    font-size: 1rem;
}
.flatpickr-calendar .flatpickr-weekday {
    color: #e31b23 !important;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
}
.flatpickr-calendar .flatpickr-day {
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.82rem;
}
.flatpickr-calendar .flatpickr-day:hover {
    background: #dbeafe;
    border-color: #dbeafe;
    color: #1e40af;
}
.flatpickr-calendar .flatpickr-day.selected,
.flatpickr-calendar .flatpickr-day.selected:hover {
    background: #3B82F6 !important;
    border-color: #3B82F6 !important;
    color: white !important;
    font-weight: 700;
}
.flatpickr-calendar .flatpickr-day.today {
    border-color: #e31b23;
    color: #e31b23;
    font-weight: 700;
}
.flatpickr-calendar .flatpickr-day.today.selected {
    color: white !important;
}
.flatpickr-time {
    border-top: 1px solid #e2e8f0;
}
.flatpickr-time input {
    font-weight: 700 !important;
    color: #0a192f !important;
    font-size: 0.95rem !important;
}
.flatpickr-time .flatpickr-am-pm {
    font-weight: 700;
    color: #0a192f;
}
.flatpickr-time .numInputWrapper span.arrowUp:after {
    border-bottom-color: #e31b23;
}
.flatpickr-time .numInputWrapper span.arrowDown:after {
    border-top-color: #e31b23;
}

/* ========== SIGNATURE ========== */
.signature-section {
    margin-top: 28px;
    padding-top: 18px;
    border-top: 1px dashed #e2e8f0;
}

.signature-wrap {
    position: relative;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    background: #f8fafc;
    height: 340px;
    min-height: 340px;
    overflow: hidden;
    width: 100%;
    margin-top: 8px;
}

#signatureCanvas {
    width: 100%;
    height: 100%;
    display: block;
    cursor: crosshair;
    touch-action: none;
}

.signature-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
    font-style: italic;
}

.signature-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

.btn-clear-sig {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #475569;
    padding: 6px 14px;
    border-radius: 40px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-clear-sig:hover {
    background: #f1f5f9;
    color: #dc2626;
    border-color: #fca5a5;
}

/* ========== MESSAGES ========== */
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
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ========== ACTIONS TABLEAU ========== */
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
.table tbody td a:hover { background: #e0f2fe; transform: translateY(-2px); }
.table tbody td a:hover i.zmdi-delete { color: #b91c1c; }
.table tbody td a:hover i.zmdi-edit { color: #059669; }

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

/* ========== IMAGE PROFIL ========== */
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

a[id^="voir_profil_"] {
    display: inline-block;
    vertical-align: middle;
    line-height: 0;
    margin-right: 8px;
    background: transparent !important;
    text-decoration: none !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

a[id^="voir_profil_"]:hover,
a[id^="voir_profil_"]:focus,
a[id^="voir_profil_"]:active {
    background: transparent !important;
    color: inherit !important;
    transform: none !important;
    box-shadow: none !important;
    border: none !important;
    outline: none !important;
    opacity: 1 !important;
    filter: none !important;
}

.profile-thumb:hover,
.profile-thumb:focus,
.profile-thumb:active {
    transform: none;
    opacity: 1;
    filter: none;
    background: transparent;
    box-shadow: none;
    border: none;
    outline: none;
}

.table tbody td:has(a[id^="voir_profil_"]) { white-space: nowrap; }

a[id^="voir_profil_"] + * {
    display: inline-block;
    vertical-align: middle;
    line-height: 1.4;
    max-width: calc(100% - 45px);
    white-space: normal;
    word-break: break-word;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 1rem !important; }
}

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.8rem !important; }
    #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters,
    .btn-primary, .btn-info, .btn-danger {
        padding: 4px 12px !important;
        font-size: 0.7rem;
    }
    .filters-container {
        flex-direction: column;
        gap: 8px;
        padding: 0.6rem 0.8rem;
        margin-bottom: 12px;
    }
    .filter-group { width: 100%; min-width: 100%; }
    .filter-group .form-control { height: 34px !important; }
    .user-count-badge { font-size: 0.65rem; padding: 3px 10px; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; letter-spacing: 0.05em; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; line-height: 1.3; }
    #form_add .col-6, #form_edit .col-6 { flex: 0 0 100%; max-width: 100%; }
    .form-group label { font-size: 0.65rem; }
    .form-control, input.form-control, select.form-control, textarea.form-control {
        height: 34px !important;
        font-size: 0.75rem;
    }
    textarea.form-control { height: 34px !important; min-height: 34px !important; }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] { justify-content: center; gap: 8px; }
    .profile-thumb { width: 28px; height: 28px; }
    a[id^="voir_profil_"] { margin-right: 6px; }
    .signature-wrap { height: 260px; min-height: 260px; }
    .select2-container--default .select2-selection--single { height: 34px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { font-size: 0.75rem; padding-left: 12px !important; padding-right: 36px !important; }
    .flatpickr-input, input.flatpickr-input + input.form-control { height: 34px !important; font-size: 0.75rem !important; }
}

@media (max-width: 480px) {
    .content .container { padding: 0.3rem !important; }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 { padding: 0.6rem !important; }
    h4 { font-size: 1.1rem; margin-bottom: 12px; }
    h4 i { font-size: 24px !important; }
    #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters {
        padding: 3px 8px !important;
        font-size: 0.65rem;
    }
    .table thead th { font-size: 0.62rem; padding: 8px 4px !important; }
    .table tbody td { padding: 6px 8px !important; font-size: 0.7rem; line-height: 1.2; }
    .signature-wrap { height: 220px; min-height: 220px; }
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
                        <i class="zmdi zmdi-view-list"></i> Total utilisateurs : <span id="userCount">0</span>
                    </span>
                </h4>

                <!-- SECTION FILTRES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-label text-danger"></i> Matricule</label>
                        <input type="text" id="filterMatricule" class="form-control" placeholder="Rechercher par matricule...">
                    </div>
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
                        <label><i class="zmdi zmdi-settings text-danger"></i> Rôle</label>
                        <select id="filterRole" class="form-control">
                            <option value="all">Tous les rôles</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-pin text-danger"></i> Poste</label>
                        <select id="filterPoste" class="form-control">
                            <option value="all">Tous les postes</option>
                            @foreach ($postes as $poste)
                                <option value="{{ $poste->id }}">{{ $poste->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-money text-danger"></i> Salaire</label>
                        <input type="number" id="filterSalaire" class="form-control" placeholder="Salaire exact" step="0.01">
                    </div>
                    @if(Auth::user()->role == 0)
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-circle text-danger"></i> Utilisateur</label>
                        <select id="filterUserId" class="form-control">
                            <option value="all">Tous les utilisateurs</option>
                            @php $allUsers = \App\Models\User::all(); @endphp
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}">
                                    @if($user->id == Auth::user()->id)
                                        Vous
                                    @else
                                        {{ $user->name }} ({{ $user->matricule ?? 'N/A' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
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
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Matricule</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Salaire</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Role / Fonction</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Poste / Lieux</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($utilisateurs as $data)
                                        <tr id="row_{{ $data->id }}" data-user-id="{{ $data->user_id }}">
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="matricule-cell" data-matricule="{{ $data->matricule }}">{{ $data->matricule }}</td>
                                            <td class="align-middle nom-cell" data-nom="{{ $data->name }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                <a id="voir_profil_<?= $i ?>" href="#">
                                                    <img src="{{ asset($data->image) }}" alt="avatar" class="profile-thumb">
                                                </a> {{ $data->name }}
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="salaire-cell" data-salaire="{{ $data->salaire }}" data-devise="{{ $data->devise }}">
                                                @if (Auth::user()->role == 0)
                                                    @if ($data->devise == 0)
                                                        {{ number_format($data->salaire, 2, ',', ' ') .'USD'; }}
                                                    @else
                                                        {{ number_format($data->salaire, 2, ',', ' ') .'CDF'; }}
                                                    @endif
                                                @else
                                                    @if ($data->devise == 0)
                                                        {{ number_format(0, 2, ',', ' ') .'USD'; }}
                                                    @else
                                                        {{ number_format(0, 2, ',', ' ') .'CDF'; }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="email-cell" data-email="{{ $data->email }}">{{ $data->email }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="role-cell" data-role="{{ $data->role }}">
                                                @if ($groupes->count()!= 0)
                                                    <?= Groupes::where('id', $data->role)->first()["nom"] ?? 'N/A'; ?>
                                                @endif
                                            </td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;" class="poste-cell" data-poste="{{ $data->poste_id }}">
                                                <?php
                                                    $potess = Postes::where('id', $data->poste_id)->first()
                                                ?>
                                                @if ($data->poste_id == 0)
                                                    <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">{{ 'Aucun' }} </span>
                                                @else
                                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success"><?= $potess["nom"] ?? 'N/A'; ?>, <?= Lieux::where(["id" => $potess["lieuxe_id"] ?? 0])->first()["nom"] ?? 'N/A'; ?>.</span>
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
                                                <?php if ((($edit == 1) && ($data->user_id == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                                                    <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } else { ?>
                                                    <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } ?>
                                                <?php if (($delete == 1 && $data->user_id == Auth::user()->id) || (Auth::user()->role == 0)) { ?>
                                                    <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } else { ?>
                                                    <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                                <?php } ?>
                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_editutilisateur') }}", {
                                                            user_id: <?= $data->id ?>,
                                                            page: <?= $ressource_id_1 ?>,
                                                        }, function(refresh_editutilisateur) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editutilisateur);
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
                                                        $("#element").html("<?= $data->name ?>");
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        $("#btn_sup").trigger("click");
                                                    });
                                                    $("#voir_profil_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#nom_profil").html("<?= $data->name ?>");
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        var url = "<?= $data->image ?>";
                                                        $("#contenu_voir_profil").html('<img src="' + url +
                                                            '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                                        );
                                                        $("#btn_voir_profil").trigger("click");
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
                        <div class="progress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;"></div>
                        <span class="progress-text" style="font-size:12px;">0%</span>
                    </div>

                    <input type="file" name="input_user_img_profil" id="input_user_img_profil" style="display:none;">
                    <input type="text" name="image" id="image" value="{{ asset('storage/images/user/profil_defaut.png') }}" style="display:none;">

                    {{-- ========== LIGNE 1 : Personne + Type de personne ========== --}}
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account-box"></i> Personne</label>
                                <select id="personne" name="personne" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option value=""></option>
                                    {{-- ✅ Vide : sera rempli par AJAX via get_personnes_by_type --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts-list"></i> Type de personne</label>
                                <select id="type_personne" name="type_personne" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Utilisateurs</option>
                                    <option value="1">Client</option>
                                    <option value="2">Patient</option>
                                    <option value="3">Visiteurs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ========== LIGNE 2 : Nature + Nom ========== --}}
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-city-alt"></i> Nature</label>
                                <select id="nature" name="nature" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option value=""></option>
                                    <option value="0">Privé</option>
                                    <option value="1">Entreprise</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom</label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)">
                            </div>
                        </div>
                    </div>

                    {{-- ========== LIGNE 3 : E-mail + Téléphone ========== --}}
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i> E-mail</label>
                                <input type="text" id="email" name="email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Email (Ex : mgm@gmail.com)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i> Telephone</label>
                                <input type="text" id="phone" name="phone" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Telephone (Ex : +243974743675)">
                            </div>
                        </div>
                    </div>

                    {{-- ========== LIGNE 4 : Motif + Service ========== --}}
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-tag"></i> Motif</label>
                                <select id="motif" name="motif" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
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
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-balance"></i> Service</label>
                                <select id="service" name="service" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
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

                    {{-- ========== LIGNE 5 : Date et heure + Note ========== --}}
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date et heure d'entrée</label>
                                <input type="hidden" id="heure" name="heure">
                                <input type="text" id="heure_picker" class="form-control flatpickr-input" placeholder="Sélectionner la date et l'heure" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment-text"></i> Note</label>
                                <textarea id="note" name="note" class="form-control" rows="1" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Remarque éventuelle..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== ZONE SIGNATURE ===================== --}}
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
                                $edit = 0;
                                $delete = 0;
                                $add = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
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

            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">
            </div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">
                <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</section>
<span id="data_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" data-toggle="modal" data-target="#profil_utilisateur" id="btn_voir_profil">Sup</button>
<div class="modal fade" id="profil_utilisateur" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Profil : <span id="nom_profil"></span> </h5>
            </div>
            <div class="modal-body">
                <p id="contenu_voir_profil" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
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

{{-- ===================== SELECT2 JS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- ===================== FLATPICKR JS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    $("#link_59").addClass("active");

    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    });

    /* ===================== SELECT2 ===================== */
    var select2Inited = false;

    function initSelect2() {
        if (select2Inited) return;
        if (typeof $.fn.select2 === 'undefined') return;

        // Personne
        $('#personne').select2({
            placeholder: '-- Sélectionner une personne --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#personne').closest('.form-group'),
            language: {
                noResults: function() { return "Aucun résultat trouvé"; },
                searching: function() { return "Recherche..."; },
                inputTooShort: function() { return "Saisissez un mot-clé"; }
            }
        });

        // Type de personne
        $('#type_personne').select2({
            placeholder: '-- Sélectionner un type --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#type_personne').closest('.form-group'),
            language: {
                noResults: function() { return "Aucun résultat trouvé"; },
                searching: function() { return "Recherche..."; },
                inputTooShort: function() { return "Saisissez un mot-clé"; }
            }
        });

        // Nature
        $('#nature').select2({
            placeholder: '-- Sélectionner une nature --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#nature').closest('.form-group'),
            language: {
                noResults: function() { return "Aucun résultat trouvé"; },
                searching: function() { return "Recherche..."; },
                inputTooShort: function() { return "Saisissez un mot-clé"; }
            }
        });

        // Motif
        $('#motif').select2({
            placeholder: '-- Sélectionner un motif --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#motif').closest('.form-group'),
            language: {
                noResults: function() { return "Aucun résultat trouvé"; },
                searching: function() { return "Recherche..."; },
                inputTooShort: function() { return "Saisissez un mot-clé"; }
            }
        });

        // Service
        $('#service').select2({
            placeholder: '-- Sélectionner un service --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#service').closest('.form-group'),
            language: {
                noResults: function() { return "Aucun résultat trouvé"; },
                searching: function() { return "Recherche..."; },
                inputTooShort: function() { return "Saisissez un mot-clé"; }
            }
        });

        select2Inited = true;
    }

    /* ============================================================
       ✅ PRÉCHARGEMENT DE TOUTES LES PERSONNES AU LANCEMENT DE LA PAGE
       Appel unique à /get_personnes_by_type sans paramètre → renvoie tout
       Les options sont ensuite filtrées côté client selon le type choisi.
    ============================================================ */
    function preloadPersonnes() {
        $.ajax({
            type: "GET",
            url: "{{ url('/get_personnes_by_type') }}",
            dataType: "json",
            success: function(data) {
                // Construire toutes les options avec data-type
                var options = '<option value=""></option>';
                $.each(data, function(i, item) {
                    options += '<option value="' + item.id + '" data-type="' + item.type + '">' + item.label + '</option>';
                });

                $('#personne').html(options);
                // Sauvegarder toutes les options dans le cache
                $('#personne').data('allOptions', $('#personne').find('option').clone());
            },
            error: function() {
                $('#personne').html('<option value="">Erreur de chargement</option>');
            }
        });
    }

    /* ===================== FILTRE : Type → filtre la liste Personne côté client ===================== */
    $(document).on('change', '#type_personne', function() {
        var type = $(this).val();
        var $personne = $('#personne');

        // Détruire select2 temporairement pour manipuler les options
        if ($personne.data('select2')) {
            $personne.select2('destroy');
        }

        // Récupérer toutes les options cachées
        if (!$personne.data('allOptions')) {
            $personne.data('allOptions', $personne.find('option').clone());
        }
        var $allOptions = $personne.data('allOptions');

        // Reset le select
        $personne.empty().append('<option value=""></option>');

        if (type !== null && type !== '') {
            // Filtrer par type
            $allOptions.each(function() {
                var $opt = $(this);
                if ($opt.data('type') == type) {
                    $personne.append($opt.clone());
                }
            });
        } else {
            // Aucun type → remettre toutes les options
            $personne.append($allOptions.clone());
        }

        $personne.val('');

        // Ré-init select2
        select2Inited = false;
        initSelect2();
    });

    /* ===================== FLATPICKR ===================== */
    var heurePicker = null;

    function initFlatpickr() {
        if (heurePicker) return;
        if (typeof flatpickr === 'undefined') return;

        if (flatpickr.l10ns && flatpickr.l10ns.fr) {
            flatpickr.localize(flatpickr.l10ns.fr);
        }

        var now = new Date();

        heurePicker = flatpickr("#heure_picker", {
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "d/m/Y H:i",
            defaultDate: now,
            minuteIncrement: 1,
            allowInput: false,
            disableMobile: true,
            onChange: function(selectedDates, dateStr) {
                $("#heure").val(dateStr);
            }
        });

        $("#heure").val(heurePicker.formatDate(now, "Y-m-d H:i"));
    }

    /* ===================== SIGNATURE PAD ===================== */
    var signatureInited = false;
    var canvas, ctx, placeholder, drawing = false, hasSignature = false;

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
        ctx.lineWidth = 2.4;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#1e293b';

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

    function sigStart(e) {
        e.preventDefault();
        drawing = true;
        hasSignature = true;
        if (placeholder) placeholder.style.display = 'none';
        var p = sigGetPos(e);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
    }
    function sigMove(e) {
        if (!drawing) return;
        e.preventDefault();
        var p = sigGetPos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    }
    function sigStop() { drawing = false; }

    window.addEventListener('resize', function() {
        if (!canvas) return;
        var data = hasSignature ? canvas.toDataURL() : null;
        initSignatureCanvas();
        if (data) {
            var img = new Image();
            img.onload = function() {
                var ratio = window.devicePixelRatio || 1;
                ctx.drawImage(img, 0, 0, canvas.width / ratio, canvas.height / ratio);
            };
            img.src = data;
        }
    });

    /* ===================== NAVIGATION BLOCS ===================== */
    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() { filterUsers(); }, 100);
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            initSignatureCanvas();
            initSelect2();
            initFlatpickr();
        }, 100);
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
        $("#bloc_4").hide();
        setTimeout(function() { filterUsers(); }, 100);
    });

    /* ===================== ENREGISTREMENT ===================== */
    $("#save").click(function(e) {
        e.preventDefault();

        var personne     = $("#personne").val();
        var typePersonne = $("#type_personne").val();
        var nature       = $("#nature").val();
        var nom          = $("#nom").val();
        var email        = $("#email").val();
        var phone        = $("#phone").val();
        var motif        = $("#motif").val();
        var service      = $("#service").val();
        var heure        = $("#heure").val();
        var note         = $("#note").val();
        var page         = "<?= $ressource_id_1 ?>";

        if (personne === null || personne.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez une personne');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (typePersonne === null || typePersonne.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez un type de personne');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (nature === null || nature.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez une nature');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (motif === null || motif.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez un motif');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (service === null || service.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez un service');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }

        if (!hasSignature) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez signer avant d\'enregistrer');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }

        var signature = canvas.toDataURL('image/png');

        var formData = $("#form_add").serialize() + "&page=" + encodeURIComponent(page) + "&signature=" + encodeURIComponent(signature);
        var data = $("#form_add").serialize();

        if (nom.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (email.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'adresse e-mail');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!regex.test(email)) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (phone.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero de telephone');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (!Number(phone.trim())) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez un bon numero de telephone');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }
        if (heure === null || heure.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez la date et l\'heure d\'entrée');
            setTimeout(function() { $('#msg').html(""); }, 9000);
            return;
        }

        $.ajax({
            type: "POST",
            url: "/check_email_utilisateur",
            data: data,
            success: function(response) {
                if (response == 1) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette adresse e-mail existe déjà');
                    setTimeout(function() { $('#msg').html(""); }, 9000);
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "/check_phone_utilisateur",
                    data: data,
                    success: function(response2) {
                        if (response2 == 1) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Ce numero de telephone existe déjà');
                            setTimeout(function() { $('#msg').html(""); }, 9000);
                            return;
                        }
                        $("#save").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "/add_utilisateur",
                            data: formData,
                            success: function(resp) {
                                $("#save").attr("disabled", false);
                                $("#nom").val("");
                                $("#email").val("");
                                $("#phone").val("");
                                if (typeof $.fn.select2 !== 'undefined') {
                                    $("#personne").val(null).trigger("change");
                                    $("#type_personne").val(null).trigger("change");
                                    $("#nature").val(null).trigger("change");
                                    $("#motif").val(null).trigger("change");
                                    $("#service").val(null).trigger("change");
                                } else {
                                    $("#personne").val("");
                                    $("#type_personne").val("");
                                    $("#nature").val("");
                                    $("#motif").val("");
                                    $("#service").val("");
                                }
                                if (heurePicker) {
                                    var now = new Date();
                                    heurePicker.setDate(now, true);
                                    $("#heure").val(heurePicker.formatDate(now, "Y-m-d H:i"));
                                } else {
                                    $("#heure").val("");
                                }
                                $("#note").val("");
                                if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
                                hasSignature = false;
                                if (placeholder) placeholder.style.display = 'flex';

                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Enregistrement effectué avec succès');
                                $("#content_utilisateur").html(resp);
                                setTimeout(function() { $('#msg').html(""); }, 9000);
                                saveUserFiltersToStorage();
                                setTimeout(function() {
                                    loadUserFiltersFromStorage();
                                    filterUsers();
                                }, 100);
                            }
                        });
                    }
                });
            }
        });
    });

    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        var page = "<?= $ressource_id_1 ?>";
        $.get("{{ url('/refresh_deleteutilisateur') }}", {
            id: id,
            page: page
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
            saveUserFiltersToStorage();
            setTimeout(function() {
                loadUserFiltersFromStorage();
                filterUsers();
            }, 100);
        });
    });

    $("#user_img_profil").click(function(e) {
        e.preventDefault();
        $("#input_user_img_profil").trigger("click");
    });

    $("#input_user_img_profil").change(function(e) {
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
        let userId = 'all';
        if ($('#filterUserId').length) {
            userId = $('#filterUserId').val();
        }
        const filters = {
            matricule: $('#filterMatricule').val(),
            nom: $('#filterNom').val(),
            email: $('#filterEmail').val(),
            phone: $('#filterPhone').val(),
            role: $('#filterRole').val(),
            poste: $('#filterPoste').val(),
            salaire: $('#filterSalaire').val(),
            userId: userId
        };
        localStorage.setItem('userFilters', JSON.stringify(filters));
    }

    function loadUserFiltersFromStorage() {
        const savedFilters = localStorage.getItem('userFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#filterMatricule').val(filters.matricule || '');
            $('#filterNom').val(filters.nom || '');
            $('#filterEmail').val(filters.email || '');
            $('#filterPhone').val(filters.phone || '');
            $('#filterRole').val(filters.role || 'all');
            $('#filterPoste').val(filters.poste || 'all');
            $('#filterSalaire').val(filters.salaire || '');
            if ($('#filterUserId').length) {
                $('#filterUserId').val(filters.userId || 'all');
            }
            return true;
        }
        return false;
    }

    function filterUsers() {
        const filterMatricule = $('#filterMatricule').val().toLowerCase();
        const filterNom = $('#filterNom').val().toLowerCase();
        const filterEmail = $('#filterEmail').val().toLowerCase();
        const filterPhone = $('#filterPhone').val().toLowerCase();
        const filterRole = $('#filterRole').val();
        const filterPoste = $('#filterPoste').val();
        const filterSalaire = parseFloat($('#filterSalaire').val());
        const filterUserId = $('#filterUserId').length ? $('#filterUserId').val() : 'all';

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const matriculeValue = ($row.find('.matricule-cell').data('matricule') || '').toLowerCase();
            const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
            const emailValue = ($row.find('.email-cell').data('email') || '').toLowerCase();
            const phoneValue = ($row.find('.phone-cell').data('phone') || '').toLowerCase();
            const roleValue = $row.find('.role-cell').data('role') || '';
            const posteValue = $row.find('.poste-cell').data('poste') || '';
            const salaireValue = parseFloat($row.find('.salaire-cell').data('salaire') || 0);
            const userId = $row.data('userId');

            if (filterMatricule && !matriculeValue.includes(filterMatricule)) showRow = false;
            if (showRow && filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterEmail && !emailValue.includes(filterEmail)) showRow = false;
            if (showRow && filterPhone && !phoneValue.includes(filterPhone)) showRow = false;
            if (showRow && filterRole !== 'all' && roleValue != filterRole) showRow = false;
            if (showRow && filterPoste !== 'all' && posteValue != filterPoste) showRow = false;
            if (showRow && !isNaN(filterSalaire) && salaireValue != filterSalaire) showRow = false;
            if (showRow && filterUserId !== 'all' && userId != filterUserId) showRow = false;

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

        if (visibleCount === 0 && (filterMatricule || filterNom || filterEmail || filterPhone || filterRole !== 'all' || filterPoste !== 'all' || !isNaN(filterSalaire) || filterUserId !== 'all')) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Aucun utilisateur ne correspond aux critères de recherche');
            $('#msg').css('display', 'flex');
            setTimeout(function() {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }
    }

    function resetUserFilters() {
        $('#filterMatricule').val('');
        $('#filterNom').val('');
        $('#filterEmail').val('');
        $('#filterPhone').val('');
        $('#filterRole').val('all');
        $('#filterPoste').val('all');
        $('#filterSalaire').val('');
        if ($('#filterUserId').length) {
            $('#filterUserId').val('all');
        }

        saveUserFiltersToStorage();

        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex);
            newIndex++;
        });
        const totalCount = $('#content_utilisateur tbody tr').length;
        $('#userCount').text(totalCount);

        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
        $('#msg').css('display', 'flex');
        setTimeout(function() {
            $('#msg').html('');
            $('#msg').css('display', 'none');
        }, 3000);
    }

    function debouncedUserFilter() {
        clearTimeout(userFilterTimeout);
        userFilterTimeout = setTimeout(function() {
            filterUsers();
            saveUserFiltersToStorage();
        }, 300);
    }

    $(document).ready(function() {
        // ✅ PRÉCHARGEMENT de toutes les personnes dès le chargement de la page
        preloadPersonnes();

        const totalUsers = $('#content_utilisateur tbody tr').length;
        $('#userCount').text(totalUsers);

        const hasSavedFilters = loadUserFiltersFromStorage();

        $('#filterMatricule, #filterNom, #filterEmail, #filterPhone, #filterRole, #filterPoste, #filterSalaire').on('input change', function() {
            debouncedUserFilter();
        });
        if ($('#filterUserId').length) {
            $('#filterUserId').on('change', function() {
                debouncedUserFilter();
            });
        }

        $('#resetFilters').click(function(e) {
            e.preventDefault();
            resetUserFilters();
        });

        if (hasSavedFilters) {
            setTimeout(function() { filterUsers(); }, 100);
        }
    });

    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_utilisateur'))) {
            setTimeout(function() {
                const totalUsers = $('#content_utilisateur tbody tr').length;
                $('#userCount').text(totalUsers);
                loadUserFiltersFromStorage();
                filterUsers();
            }, 200);
        }
    });

    window.addEventListener('beforeunload', function() {
        saveUserFiltersToStorage();
    });
</script>
@endsection
@endsection