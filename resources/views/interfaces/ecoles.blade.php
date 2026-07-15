<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\User;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\districts;
use App\Models\communes;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'GESTION ECOLE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* =============================================
   DESIGN PREMIUM - VERSION FINALE
   BOUTONS MODERNES & RESPONSIFS
   LIGNES DE TABLEAU RÉDUITES ET ÉQUILIBRÉES
   MESSAGE D'ERREUR/SUCCÈS TOTALEMENT CACHÉ PAR DÉFAUT
   PRISE EN CHARGE DU FORMULAIRE D'ÉDITION
   + Adaptation pour le formulaire add_programmme
   + FILTRES POUR LA GESTION ÉCOLE
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
    padding: 1rem 2rem !important;
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
    --rouge-feu: #e31b23;
    --rouge-fonce: #b91c1c;
    --rouge-gradient: linear-gradient(135deg, #dc2626, #b91c1c);
    --vert-succes: #10b981;
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

/* --- Cartes principales --- */
#bloc_1, #bloc_2, #bloc_3 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 2rem 1.8rem !important;
    margin-bottom: 2rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

/* --- En-têtes --- */
h4 {
    font-weight: 700;
    border-left: 6px solid var(--rouge-feu);
    padding-left: 18px;
    margin-bottom: 28px;
    color: var(--bleu-nuit);
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

/* ========== TABLEAU ÉQUILIBRÉ ========== */
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
    background: var(--bleu-nuit-gradient);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 10px !important;
    border-bottom: none;
    white-space: nowrap;
}

.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #eef2f6;
}

.table tbody tr:hover {
    background: #f0f5fe !important;
}

.table tbody td {
    padding: 8px 10px !important;
    vertical-align: middle;
    font-weight: 500;
    font-size: 0.85rem;
    color: #1e2a3e;
    word-break: break-word;
}

/* ========== BOUTONS RONDS POUR LA COLONNE CONTROL ========== */
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

.table tbody td a i.zmdi-edit { color: #2c7da0; }
.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a i.zmdi-delete { color: var(--rouge-feu); }
.table tbody td a:hover i.zmdi-delete { color: var(--rouge-fonce); }
.table tbody td a:hover { background: #ffe5e5; }

/* ========== BOUTONS PRINCIPAUX ========== */
#liste, #add, #print, #add_r, #print_r,
.btn-primary, .btn-primary.btn-sm, a.btn-primary,
.btn-info, .btn-info.btn-sm,
.btn-danger, .btn-danger.btn-sm,
#edit_save, #edit_annuler,
#save_t {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 18px !important;
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

#liste {
    background: linear-gradient(135deg, #0a192f, #1e3a5f) !important;
    color: white !important;
}
#liste:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#add, a#add {
    background: linear-gradient(135deg, #0f4c5f, #1e6f5c) !important;
    color: white !important;
}
#add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
}

#print {
    background: linear-gradient(135deg, #4b6e8a, #2c4f6e) !important;
    color: white !important;
}
#print:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(43, 76, 108, 0.3);
}

#add_r, #print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed;
    opacity: 0.7;
    box-shadow: none;
}
#add_r:hover, #print_r:hover {
    transform: none;
    box-shadow: none;
}

#save, #save_r, #annuler, #edit_save, #edit_annuler,
#save_t {
    padding: 8px 24px !important;
    font-weight: 700;
}
#save, #edit_save, #save_t {
    background: linear-gradient(95deg, #0f4c5f, #0e6b5e) !important;
    color: white;
}
#save:hover, #edit_save:hover, #save_t:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
}
#annuler, #edit_annuler {
    background: #64748b !important;
    color: white;
}
#annuler:hover, #edit_annuler:hover {
    background: #475569 !important;
    transform: translateY(-2px);
}

/* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
#form_add .row, #form_edit .row,
#add_programmme .row {
    display: flex;
    flex-wrap: wrap;
}
#form_add .col-6, #form_edit .col-6,
#add_programmme .col-lg-6, #add_programmme .col-sm-6 {
    margin-bottom: 1rem;
}
.form-group {
    width: 100%;
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-weight: 700;
    color: var(--bleu-nuit);
    margin-bottom: 6px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.form-group label i {
    color: var(--rouge-feu);
    margin-right: 6px;
}
.form-control,
input.form-control,
select.form-control,
textarea.form-control,
.input-mask,
#add_programmme select,
#add_programmme input,
#add_programmme textarea {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 10px 14px !important;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    box-sizing: border-box;
    height: 42px !important;
    line-height: 1.4;
}
textarea.form-control {
    resize: vertical;
    height: 42px !important;
}
.form-control:focus,
select.form-control:focus,
textarea.form-control:focus,
#add_programmme select:focus,
#add_programmme input:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    transform: translateY(-1px);
}
select.form-control,
#add_programmme select {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
}
.input-mask {
    font-family: monospace;
    background: #fff9ef !important;
}

/* ========== MESSAGES MODERNES - TOTALEMENT INVISIBLE PAR DÉFAUT ========== */
#msg, #edit_msg, #msg_r {
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

#msg:not(:empty), #edit_msg:not(:empty),
#msg_r:not(:empty) {
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
#edit_msg:not(:empty):has(i.zmdi-check-circle),
#msg_r:not(:empty):has(i.zmdi-check-circle) {
    background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
    color: #065f46;
    border-left: 4px solid var(--vert-succes);
}
#msg:not(:empty):has(i.zmdi-close-circle),
#edit_msg:not(:empty):has(i.zmdi-close-circle),
#msg_r:not(:empty):has(i.zmdi-close-circle) {
    background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
    color: #991b1b;
    border-left: 4px solid var(--rouge-feu);
}

@keyframes slideInMsg {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ========== BARRE D'ACTIONS EN HAUT ========== */
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

/* ========== STYLES DES FILTRES (ajoutés pour écoles) ========== */
.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 160px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--bleu-nuit);
    font-size: 0.75rem;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-group .form-control {
    height: 42px;
}

.school-count-badge {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    color: white;
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .content .container {
        padding: 0.8rem 1rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3 {
        padding: 1.2rem !important;
    }
    #liste, #add, #print, #add_r, #print_r,
    .btn-primary, .btn-info, .btn-danger,
    #edit_save, #edit_annuler,
    #save_t {
        padding: 6px 14px !important;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        justify-content: center;
        gap: 8px;
    }
    #form_add .col-6, #form_edit .col-6,
    #add_programmme .col-lg-6, #add_programmme .col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .form-control, input.form-control, select.form-control, textarea.form-control,
    #add_programmme select, #add_programmme input {
        height: 40px !important;
        font-size: 0.8rem;
    }
    .table thead th {
        font-size: 0.7rem;
        padding: 6px 6px !important;
    }
    .table tbody td {
        padding: 6px 6px !important;
        font-size: 0.75rem;
    }
    .table tbody td a {
        width: 28px;
        height: 28px;
    }
    .table tbody td a i.zmdi {
        font-size: 1rem;
    }
    .filters-container {
        flex-direction: column;
        align-items: stretch;
    }
    .filter-group {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.5rem !important;
    }
    .btn, .btn-sm, #liste, #add, #print, #edit_save, #edit_annuler, #save_t {
        padding: 4px 10px !important;
        font-size: 0.7rem;
    }
    .form-group label {
        font-size: 0.7rem;
    }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] {
        gap: 6px;
        padding: 8px 12px !important;
    }
    .table thead th {
        font-size: 0.6rem;
        padding: 4px 4px !important;
    }
    .table tbody td {
        font-size: 0.7rem;
        padding: 5px 4px !important;
    }
}

/* ========== ANIMATIONS & DÉTAILS ========== */
@keyframes glow {
    0% { box-shadow: 0 0 0 0 rgba(227, 27, 35, 0.2); }
    70% { box-shadow: 0 0 0 6px rgba(227, 27, 35, 0); }
    100% { box-shadow: 0 0 0 0 rgba(227, 27, 35, 0); }
}
.btn-danger:active {
    animation: glow 0.3s ease-out;
}
.modal-header {
    background: var(--bleu-nuit-gradient);
}
input[required], select[required], textarea[required] {
    border-left: 3px solid var(--rouge-feu) !important;
}

/* ========== STYLES POUR LA CARTE ET LES BOUTONS OVERLAY ========== */
.map-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0 12px 0;
}
.map-toolbar button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    font-weight: 600;
    font-size: 0.8rem;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
    color: white;
    box-shadow: var(--shadow-light);
}
.map-toolbar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#map-container {
    position: relative;
}
#map {
    width: 100%;
    height: 350px;
    border-radius: 16px;
    margin-bottom: 15px;
    z-index: 1;
}
.map-overlay-buttons {
    position: absolute;
    bottom: 25px;
    right: 25px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    padding: 8px;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
.map-overlay-buttons .map-btn {
    background: white;
    border: none;
    border-radius: 40px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.2rem;
    color: #0a192f;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.map-overlay-buttons .map-btn:hover {
    transform: scale(1.05);
    background: #f0f0f0;
}
@media (max-width: 768px) {
    .map-overlay-buttons {
        bottom: 15px;
        right: 15px;
        gap: 5px;
    }
    .map-overlay-buttons .map-btn {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}
/* ========== STYLES POUR LA CARTE D'ÉDITION ========== */
.edit-map-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0 12px 0;
}
.edit-map-toolbar button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    font-weight: 600;
    font-size: 0.8rem;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
    color: white;
    box-shadow: var(--shadow-light);
}
.edit-map-toolbar button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
}

#edit-map-container {
    position: relative;
}
#editmap {
    width: 100%;
    height: 350px;
    border-radius: 16px;
    margin-bottom: 15px;
    z-index: 1;
}
#edit-map-container .map-overlay-buttons {
    position: absolute;
    bottom: 25px;
    right: 25px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    padding: 8px;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}
#edit-map-container .map-overlay-buttons .map-btn {
    background: white;
    border: none;
    border-radius: 40px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.2rem;
    color: #0a192f;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
#edit-map-container .map-overlay-buttons .map-btn:hover {
    transform: scale(1.05);
    background: #f0f0f0;
}

@media (max-width: 768px) {
    #edit-map-container .map-overlay-buttons {
        bottom: 15px;
        right: 15px;
        gap: 5px;
    }
    #edit-map-container .map-overlay-buttons .map-btn {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    .edit-map-toolbar button {
        padding: 4px 12px;
        font-size: 0.7rem;
    }
}
/* =============================================
   STYLE SPÉCIFIQUE - PRESTATIONS CALENDAR
   ============================================= */
.prestations-calendar {
    --vert-presta: #10b981;
    --rouge-presta: #e31b23;
    --info-presta: #0ea5e9;
    --danger-presta: #ef4444;
    --bordure-table: #e2e8f0;
}
.prestations-calendar .col-12 > h4 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 1.5rem 0 1.2rem 0;
    padding-left: 18px;
    border-left: 6px solid;
    border-bottom: 2px solid var(--bordure-table);
    display: flex;
    align-items: center;
    gap: 12px;
}
.prestations-calendar .col-12 > h4.text-success,
.prestations-calendar .col-12 > h4.text-success i.zmdi-calendar {
    color: var(--vert-presta) !important;
}
.prestations-calendar .col-12 > h4:not(.text-success),
.prestations-calendar .col-12 > h4:not(.text-success) i.zmdi-calendar {
    color: var(--rouge-presta) !important;
}
.prestations-calendar .table-responsive {
    border-radius: 16px;
    overflow-x: auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    background: #ffffff;
}
.prestations-calendar .table {
    background-color: white;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
}
.prestations-calendar .table thead th {
    background: linear-gradient(135deg, #1e3a5f, #2c5282);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 12px !important;
    border-bottom: none;
    white-space: nowrap;
}
.prestations-calendar .table tbody tr {
    transition: background 0.15s ease;
    border-bottom: 1px solid #eef2f6;
}
.prestations-calendar .table tbody tr:hover {
    background: #fef9e6 !important;
}
.prestations-calendar .table tbody td {
    padding: 8px 12px !important;
    vertical-align: middle;
    font-size: 0.85rem;
    color: #1e2a3e;
    border-color: #eef2f6;
}
.prestations-calendar .table tbody td:first-child {
    font-weight: 700;
    color: #0a192f;
    text-align: center;
}
.prestations-calendar .table i.zmdi-info.text-success,
.prestations-calendar .table span.text-success {
    color: var(--vert-presta) !important;
    font-weight: 600;
}
.prestations-calendar .table i.zmdi-info.text-info,
.prestations-calendar .table span.text-info {
    color: var(--info-presta) !important;
    font-weight: 600;
}
.prestations-calendar .table i.zmdi-info.text-danger,
.prestations-calendar .table span.text-danger {
    color: var(--danger-presta) !important;
    font-weight: 600;
}
.prestations-calendar .table i.zmdi-time {
    margin-right: 6px;
}
@media (max-width: 768px) {
    .prestations-calendar .col-12 > h4 {
        font-size: 1.1rem;
        margin: 1rem 0 0.8rem 0;
        padding-left: 14px;
        gap: 8px;
    }
    .prestations-calendar .col-12 > h4 i.zmdi-calendar {
        font-size: 26px;
    }
    .prestations-calendar .table thead th {
        font-size: 0.7rem;
        padding: 6px 8px !important;
    }
    .prestations-calendar .table tbody td {
        padding: 6px 8px !important;
        font-size: 0.75rem;
    }
}
@media (max-width: 480px) {
    .prestations-calendar .table thead th {
        font-size: 0.6rem;
        padding: 4px 6px !important;
    }
    .prestations-calendar .table tbody td {
        font-size: 0.7rem;
        padding: 4px 6px !important;
    }
    .prestations-calendar .table i.zmdi {
        font-size: 0.9rem;
    }
}
@media (min-width: 1200px) {
    .prestations-calendar .table tbody td {
        padding: 10px 16px !important;
    }
}
.prestations-calendar .filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 2rem;
    background: white;
    padding: 15px 20px;
    border-radius: 60px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.prestations-calendar .filter-group {
    flex: 1;
    min-width: 180px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.prestations-calendar .filter-group label {
    font-weight: 700;
    color: #0a192f;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    white-space: nowrap;
}
.prestations-calendar .filter-group label i {
    color: var(--rouge-presta);
    font-size: 1.1rem;
}
.prestations-calendar .filter-group select {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 40px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #1e2a3e;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
}
.prestations-calendar .filter-group select:focus {
    outline: none;
    border-color: var(--rouge-presta);
    box-shadow: 0 0 0 3px rgba(227, 27, 35, 0.1);
}
.prestations-calendar .reset-filters {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
    border: none;
    border-radius: 40px;
    padding: 6px 18px;
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.prestations-calendar .reset-filters:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.15);
}
.prestations-calendar .no-results {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 20px;
    color: #64748b;
    font-weight: 500;
}
@media (max-width: 768px) {
    .prestations-calendar .filters-container {
        flex-direction: column;
        border-radius: 30px;
        gap: 10px;
    }
    .prestations-calendar .filter-group {
        width: 100%;
    }
}
@media (max-width: 480px) {
    .prestations-calendar .table thead th {
        font-size: 0.6rem;
        padding: 4px 6px !important;
    }
    .prestations-calendar .table tbody td {
        font-size: 0.7rem;
        padding: 4px 6px !important;
    }
}

/* =============================================
   MODAL DÉTAIL ÉCOLE – DESIGN MODERNE & RESPONSIVE
   ============================================= */
#modalDetailEcole .modal-content {
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.3);
}
#modalDetailEcole .modal-header {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
    padding: 1.2rem;
    border-bottom: none;
}
#modalDetailEcole .modal-header h5 {
    font-weight: 700;
    font-size: 1.3rem;
    letter-spacing: -0.3px;
}
#modalDetailEcole .modal-body {
    padding: 1.5rem 1.2rem;
    background: #f9fafc;
}
#modalDetailEcole .detail-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
#modalDetailEcole .detail-item {
    background: white;
    border-radius: 20px;
    padding: 0.9rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
    border: 1px solid #eef2f6;
}
#modalDetailEcole .detail-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.05);
}
#modalDetailEcole .detail-icon {
    width: 44px;
    height: 44px;
    background: #f0f4fe;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #0a192f;
    flex-shrink: 0;
}
#modalDetailEcole .detail-content {
    flex: 1;
}
#modalDetailEcole .detail-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #5b6e8c;
    margin-bottom: 4px;
}
#modalDetailEcole .detail-value {
    font-size: 1rem;
    font-weight: 700;
    color: #0a192f;
    word-break: break-word;
}
#modalDetailEcole .modal-footer {
    border-top: none;
    justify-content: center;
    padding: 1rem 1.2rem 1.5rem;
    background: #f9fafc;
}
#modalDetailEcole .btn-fermer {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.85rem;
    color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
}
#modalDetailEcole .btn-fermer:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(227, 27, 35, 0.3);
}
@media (max-width: 480px) {
    #modalDetailEcole .detail-item {
        padding: 0.7rem 0.8rem;
        gap: 0.8rem;
    }
    #modalDetailEcole .detail-icon {
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
    }
    #modalDetailEcole .detail-value {
        font-size: 0.9rem;
    }
    #modalDetailEcole .detail-label {
        font-size: 0.65rem;
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion école</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Liste</h4>

                <!-- SECTION FILTRES ÉCOLES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-home text-danger"></i> Nom école</label>
                        <input type="text" id="filterNomEcole" class="form-control" placeholder="Rechercher par nom...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Directeur</label>
                        <input type="text" id="filterDirecteur" class="form-control" placeholder="Rechercher par directeur...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-pin-drop text-danger"></i> District</label>
                        <input type="text" id="filterDistrict" class="form-control" placeholder="Rechercher par district...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-city text-danger"></i> Commune</label>
                        <input type="text" id="filterCommune" class="form-control" placeholder="Rechercher par commune...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-accounts text-danger"></i> Élèves</label>
                        <input type="number" id="filterEleves" class="form-control" placeholder="Nombre exact d'élèves">
                    </div>
                    <div class="filter-group">
                        <button id="resetSchoolFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Badge compteur écoles -->
                <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                    <span class="school-count-badge">
                        <i class="zmdi zmdi-view-list"></i> Total écoles : <span id="schoolCount">0</span>
                    </span>
                </div>

                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Ecole</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Directeur</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Classes</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Eleves</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Enseignants</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">District</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Commune</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($ecoles as $data)
                                    <tr id="row_{{ $data->id }}">
                                        <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                        <td class="ecole-nom" data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                        <td class="directeur-nom" data-directeur="{{ $data->nom_directeur }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_directeur }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">{{ $data->nombre_classe }}</td>
                                        <td class="eleves-nb" data-eleves="{{ $data->nombre_eleve }}" style="padding-top: 5px;padding-bottom: 5px;" class="text-center">{{ $data->nombre_eleve }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">{{ $data->nombre_enseignant }}</td>
                                        <td class="district-nom" data-district="{{ districts::where(["id" => $data->district_id])->first()["nom"]; }}" style="padding-top: 5px;padding-bottom: 5px;">{{ districts::where(["id" => $data->district_id])->first()["nom"]; }}</td>
                                        <td class="commune-nom" data-commune="{{ communes::where(["id" => $data->commune_id])->first()["nom"]; }}" style="padding-top: 5px;padding-bottom: 5px;">{{ communes::where(["id" => $data->commune_id])->first()["nom"]; }}</td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;&nbsp;
                                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;&nbsp;
                                            <a id="map_<?= $i ?>" title="Voir sur la carte" href="#"><i class="zmdi zmdi-map text-info"></i></a> &nbsp;&nbsp;
                                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;&nbsp;    
                                        </td>
                                        <script>
                                            $("#delete_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $("#element").html(
                                                    "<span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Directeur : </span>{{ $data->nom_directeur }}."
                                                );
                                                $("#data_id").html("<?= $data->id ?>");
                                                $("#btn_sup").trigger("click");
                                            });
                                            $("#map_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                var latitude = "{{ $data->latitude }}";
                                                var longitude = "{{ $data->longitude }}";
                                                var titre = "{{ addslashes($data->nom) }}";
                                                if (!latitude || !longitude || latitude == 0 || longitude == 0) {
                                                    $("#mapError").show();
                                                    $("#mapPreview").hide();
                                                    $("#mapModal").modal('show');
                                                    return;
                                                }
                                                $("#mapError").hide();
                                                $("#mapPreview").show();
                                                $("#mapModal").one('shown.bs.modal', function() {
                                                    if (window.alertMapInstance) {
                                                        window.alertMapInstance.remove();
                                                    }
                                                    var map = L.map('mapPreview').setView([latitude, longitude], 15);
                                                    window.alertMapInstance = map;
                                                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                                                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB',
                                                        subdomains: 'abcd',
                                                        maxZoom: 19,
                                                        minZoom: 3
                                                    }).addTo(map);
                                                    var marker = L.marker([latitude, longitude]).addTo(map);
                                                    marker.bindPopup(`<b>Ecole :</b> ${titre}<br><b>Coordonnées :</b><br>Lat: ${latitude}<br>Lng: ${longitude}`).openPopup();
                                                    L.circle([latitude, longitude], {
                                                        color: '#ff4444',
                                                        fillColor: '#ff8888',
                                                        fillOpacity: 0.4,
                                                        radius: 50
                                                    }).addTo(map);
                                                    setTimeout(function() { map.invalidateSize(); }, 200);
                                                });
                                                $("#mapModal").modal('show');
                                            });
                                            $("#edit_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                $.get("{{ url('/refresh_editecole') }}", {
                                                    ecole_id: <?= $data->id ?>,
                                                }, function(refresh_editecole) {
                                                    $("#bloc_1").hide();
                                                    $("#bloc_2").hide();
                                                    $("#bloc_3").show();
                                                    $("#bloc_3").html(refresh_editecole);
                                                });
                                            });
                                            $("#detail_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                var $row = $(this).closest('tr');
                                                var nom        = "{{ $data->nom }}";
                                                var directeur  = "{{ $data->nom_directeur }}";
                                                var classes    = "{{ $data->nombre_classe }}";
                                                var eleves     = "{{ $data->nombre_eleve }}";
                                                var enseignants= "{{ $data->nombre_enseignant }}";
                                                var district   = "{{ districts::where(['id' => $data->district_id])->first()['nom']; }}";
                                                var commune    = "{{ communes::where(['id' => $data->commune_id])->first()['nom']; }}";
                                                var adresse    = "{{ $data->adresse }}";
                                                var telephone    = "{{ $data->telephone }}";
                                                $('#detail_nom').text(nom);
                                                $('#detail_directeur').text(directeur);
                                                $('#detail_classes').text(classes);
                                                $('#detail_eleves').text(eleves);
                                                $('#detail_enseignants').text(enseignants);
                                                $('#detail_district').text(district);
                                                $('#detail_commune').text(commune);
                                                $('#detail_adresse').text(adresse);
                                                $('#detail_telephone').text(telephone);
                                                $('#modalDetailEcole').modal('show');
                                            });
                                        </script>
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
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Ajouter une école</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> District </label>
                                <select id="district_id" name="district_id" class="form-control">
                                    <option value="">Selectionnez une district</option>
                                    @foreach ($districts as $data)
                                        <option value="{{ $data->id }}">{{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Commune </label>
                                <select id="commune_id" name="commune_id" class="form-control">
                                    <option value="">Selectionnez une commune</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom de l'école </label>
                                <input type="text" id="nom_ecole" name="nom_ecole" class="form-control" placeholder="Nom (Ex : Mike alfa)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Adresse </label>
                                <textarea class="form-control" placeholder="Adresse (Ex : Quartier, Av, N° etc....)" name="adresse" id="adresse" cols="2" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom du directeur </label>
                                <input type="text" id="nom_directeur" name="nom_directeur" class="form-control" placeholder="Nom directeur(Ex : Jean paul)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nombre d'élèves </label>
                               <input type="text" id="nombre_eleve" name="nombre_eleve" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Nombre d'élèves (Ex : 100)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nombre d'enseignants </label>
                                <input type="text" id="nombre_enseignant" name="nombre_enseignant" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Nombre d'enseignants (Ex : 10)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nombre de classe </label>
                               <input type="text" id="nombre_classe" name="nombre_classe" class="form-control" placeholder="Nombre de classe (Ex : 10)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Téléphone </label>
                               <input type="text" id="telephone" name="telephone" class="form-control" placeholder="Numéro de téléphone (Ex : 0123456789)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Annnée </label>
                               <select id="annee_id" name="annee_id" class="form-control">
                                    <option value="">Année</option>
                                    @foreach ($annees as $data)
                                        <option value="{{ $data->id }}">{{ $data->annees }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Mois </label>
                                <select id="mois_id" name="mois_id" class="form-control">
                                    <option value="">Mois</option>
                                    @foreach ($mois as $data)
                                        <option value="{{ $data->id }}">{{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Date de création </label>
                                <input type="text" id="date_creation" name="date_creation" class="form-control" placeholder="Date de création (Ex : 00/00/0000)">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Latitude </label>
                                <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Latitude (Ex : 48.8566)" value="">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Longitude </label>
                                <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Longitude (Ex : 2.3522)" value="">
                            </div>
                        </div>
                    </div>
                    <div class="map-toolbar">
                        <button type="button" id="btnCurrentLocation">
                            <i class="zmdi zmdi-my-location"></i> Position actuelle
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 0;">
                                    <i class="zmdi zmdi-pin-drop"></i> Cliquez sur la carte pour choisir une position
                                </label>
                                <div id="map-container" style="position: relative;">
                                    <div id="map"></div>
                                    <div class="map-overlay-buttons">
                                        <button type="button" id="btnClassic" class="map-btn" title="Classique">
                                            <i class="zmdi zmdi-map"></i>
                                        </button>
                                        <button type="button" id="btnSatellite" class="map-btn" title="Satellite">
                                            <i class="zmdi zmdi-satellite"></i>
                                        </button>
                                        <button type="button" id="btnResetView" class="map-btn" title="Réinitialiser">
                                            <i class="zmdi zmdi-undo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
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
<span id="listesfactures_id" style="display: none;"></span>
<span id="data_id" style="display: none;"></span>
<span id="data_frais_id" style="display: none;"></span>
<span id="devise_paie_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#activation" id="btn_ac">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloture" id="btn_cl">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloturee" id="btn_cll">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#attendre" id="btn_att">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez cette école ?</h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="activation" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous activez ce poste?</h5>
            </div>
            <div class="modal-body">
                <p id="element_1" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui_1" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_1" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloture" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous vous cloturez ce poste?</h5>
            </div>
            <div class="modal-body">
                <p id="element_2" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <a style="color: white;font-weight: bold;" id="oui_2" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_2" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloturee" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce poste est deja cloturé</h5>
            </div>
            <div class="modal-body">
                <p id="element_3" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <button style="font-weight: bold;" id="non_3" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="attendre" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce solde est en attente</h5>
            </div>
            <div class="modal-body">
                <p id="element_4" style="text-align: center;"></p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center">
                    <button style="font-weight: bold;" id="non_4" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord merci</button>
                </p>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#modal-centered" id="btn_sup_">Vertically centered</button>
<div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="modal-centered" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div style="border: 1px solid black;" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" style="color: black;font-weight: bold;">Paiement</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr><th>Nom</th><th style="text-align: right;"><span id="nom_p"></span></th></tr>
                                </thead>
                                <thead>
                                    <tr><th>Adresse</th><th style="text-align: right;"><span id="role_p"></span></th></tr>
                                </thead>
                                <thead>
                                    <tr><th>Paiement</th><th style="text-align: right;"><span id="reste_p">0</span>/<span id="total_p" style="font-weight: bold;">100</span><span id="devise_p">$</span></th></tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div style="margin-top: 20px;" class="col-12">
                        <input type="text" id="montant_p" name="montant_p" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le montant">
                    </div>
                    <div style="margin-top: 20px;" class="col-12">
                        <input type="text" id="taux_p" name="taux_p" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le taux" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_paie" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                <button type="button" id="annuler_paie" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
            <p style="text-align: center;font-weight: bold;" id="m_paie"></p>
        </div>
    </div>
</div>
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                <h5 class="modal-title text-white" style="font-weight: bold;"><i class="zmdi zmdi-map"></i> Localisation de l'école</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div id="mapPreview" style="height: 450px; width: 100%; border-radius: 0 0 12px 12px;"></div>
                <div id="mapError" class="text-center text-danger p-3" style="display: none;"><i class="zmdi zmdi-alert-circle"></i> Coordonnées non disponibles pour cette alerte.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Détail École (version améliorée) -->
<div class="modal fade" id="modalDetailEcole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                <h5 class="modal-title text-white"><i class="fa fa-info-circle"></i> Détails de l'école : <span id="detail_nom"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-account-box text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Directeur</div>
                            <div class="detail-value" id="detail_directeur"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-home text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Classes</div>
                            <div class="detail-value" id="detail_classes"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-accounts text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Élèves</div>
                            <div class="detail-value" id="detail_eleves"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-male-female text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Enseignants</div>
                            <div class="detail-value" id="detail_enseignants"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-pin-drop text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">District</div>
                            <div class="detail-value" id="detail_district"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-city text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Commune</div>
                            <div class="detail-value" id="detail_commune"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-map text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Adresse</div>
                            <div class="detail-value" id="detail_adresse"></div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="zmdi zmdi-phone text-danger"></i></div>
                        <div class="detail-content">
                            <div class="detail-label">Télephone</div>
                            <div class="detail-value" id="detail_telephone"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-fermer" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#activite" id="btn_activite">Vertically centered</button>
<div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="activite" data-backdrop="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div style="border: 1px solid black;" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" style="color: black;font-weight: bold;">Activité</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="font-weight: bold;color:black;">
                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info-circle"></i> Voulez-vous la liste de facture de quelle activité ?</label>
                        <select style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" id="activite_id_a" name="activite_id_a" class="form-control" data-placeholder="Selectionnez une année">
                            <option selected value="">Selectionnez une activite</option>
                            @foreach ($activites as $data)
                                <option value="{{ $data->id }}">{{ $data->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="save_print" class="btn btn-info btn-sm">Enregistrer <i class="zmdi zmdi-save"></i></button>
                <button type="button" id="annuler_print" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
            <p style="text-align: center;font-weight: bold;" id="msg_p"></p>
        </div>
    </div>
</div>
@section('js-code')
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
<script>
    // ========== GESTION DES ONGLETS ET FONCTIONS EXISTANTES ==========
    $("#link_41").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_41").addClass("text-info");
    $("#icone_41").css("color", "rgb(33, 150, 243)");
    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    })

    $("#district_id").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_commune_by_district') }}", {
            district_id: $(this).val()
        }, function(response)
        {
            $("#commune_id").html(response);
        });
    });

    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        setTimeout(function() {
            filterSchools();
        }, 100);
    });
    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
    });
    $("#print").click(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_liste_qr_code') }}", {
        }, function(response)
        {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").show();
            $("#data_liste").attr('src', '{{ asset("")  }}' + response);
        });
    });
    $("#add_r").click(function(e) {
        e.preventDefault();
        $("#btn_refus").trigger("click");
    });
    $("#print_r").click(function(e) {
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
        setTimeout(function() {
            filterSchools();
        }, 100);
    });
    $("#save").click(function(e) {
        e.preventDefault();
        var district_id = $("#district_id").val();
        var communes_id = $("#commune_id").val();
        var nom = $("#nom_ecole").val();
        var nombre_eleve = $("#nombre_eleve").val();
        var nombre_enseignant = $("#nombre_enseignant").val();
        var nombre_classe = $("#nombre_classe").val();
        var telephone = $("#telephone").val();
        var annee = $("#annee_id").val();
        var mois = $("#mois_id").val();
        var data = $("#form_add").serialize();
        if (district_id.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez la district');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (communes_id.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez la commune');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if (nom.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'école');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else 
                {
                    if (nombre_eleve.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nombre d\'élèves');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        if (nombre_enseignant.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nombre d\'enseignants');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        } 
                        else 
                        {
                            if(nombre_classe.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nombre de classe');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            }else{
                                if (annee.trim().length == 0) 
                                {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'année');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                }
                                else 
                                {
                                    if (mois.trim().length == 0) 
                                    {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le mois');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    }
                                    else
                                    {
                                        $("#save").attr("disabled", true);
                                        $.ajax({
                                            type: "POST",
                                            url: "/check_ecole_existe",
                                            data: data,
                                            success: function(response) {
                                                $("#save").attr("disabled", false);
                                                if (response == 1)
                                                {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette école est deja enregistrée');
                                                    $('#msg').css('color', "#ff6b68");
                                                    setTimeout(() => {
                                                        $('#msg').html("");
                                                    }, 9000);
                                                } else {
                                                    $("#save").attr("disabled", true);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "/add_ecole",
                                                        data: data,
                                                        success: function(response) {
                                                            $("#save").attr("disabled", false);
                                                            $("#nom_ecole").val("");
                                                            $("#adresse").val("");
                                                            $("#nombre_ecole").val("");
                                                            $("#nombre_enseignant").val("");
                                                            $("#nom_directeur").val("");
                                                            $("#telephone").val("");
                                                            $("#date_creation").val("");
                                                            $("#latitude").val("");
                                                            $("#longitude").val("");
                                                            $("#nombre_classe").val("");
                                                            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Ecole ajoutée avec succès');
                                                            $('#msg').css("color", '#32c787');
                                                            $("#content_utilisateur").html(response);
                                                            setTimeout(() => {
                                                                $('#msg').html("");
                                                            }, 9000);
                                                            saveSchoolFiltersToStorage();
                                                            setTimeout(function() {
                                                                loadSchoolFiltersFromStorage();
                                                                filterSchools();
                                                            }, 100);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
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
        $.get("{{ url('/refresh_deleteecole') }}", {
            id: id,
        }, function(refresh_editutilisateur) {
            $("#content_utilisateur").html(refresh_editutilisateur);
            $("#non").trigger("click");
            saveSchoolFiltersToStorage();
            setTimeout(function() {
                loadSchoolFiltersFromStorage();
                filterSchools();
            }, 100);
        });
    });

    $("#oui_1").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_activer_poste') }}", {
            id: id,
        }, function(refresh_editposte) {
            $("#content_utilisateur").html(refresh_editposte);
            $("#non_1").trigger("click");
            saveSchoolFiltersToStorage();
            setTimeout(function() {
                loadSchoolFiltersFromStorage();
                filterSchools();
            }, 100);
        });
    });

    $("#oui_2").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_cloturer_poste') }}", {
            id: id,
        }, function(refresh_editposte) {
            $("#content_utilisateur").html(refresh_editposte);
            $("#non_2").trigger("click");
            saveSchoolFiltersToStorage();
            setTimeout(function() {
                loadSchoolFiltersFromStorage();
                filterSchools();
            }, 100);
        });
    });

    // ========== CODE POUR LA CARTE (géolocalisation) ==========
    var defaultLat = -4.4419;
    var defaultLng = 15.2663;
    var defaultZoom = 13;

    var map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
    var currentTileLayer;
    var marker = null;

    var tileLayerClassic = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB'
    });
    var tileLayerSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    function setTileLayer(layer) {
        if (currentTileLayer) map.removeLayer(currentTileLayer);
        currentTileLayer = layer.addTo(map);
    }
    setTileLayer(tileLayerClassic);

    function updateLocation(lat, lng) {
        $('#latitude').val(lat.toFixed(6));
        $('#longitude').val(lng.toFixed(6));
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        map.setView([lat, lng], 15);
    }

    map.on('click', function(e) {
        updateLocation(e.latlng.lat, e.latlng.lng);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Position choisie sur la carte');
        $('#msg').css('color', '#32c787');
        setTimeout(() => $('#msg').html(''), 3000);
    });

    $('#latitude, #longitude').on('input', function() {
        var lat = parseFloat($('#latitude').val());
        var lng = parseFloat($('#longitude').val());
        if (!isNaN(lat) && !isNaN(lng)) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
            map.setView([lat, lng], 15);
        }
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Carte centrée sur votre secteur');
                $('#msg').css('color', '#32c787');
                setTimeout(() => $('#msg').html(''), 4000);
            },
            function(error) {
                console.log("Géolocalisation auto non disponible");
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    $("#btnCurrentLocation").click(function(e) {
        e.preventDefault();
        if (!navigator.geolocation) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Géolocalisation non supportée');
            $('#msg').css('color', '#ff6b68');
            setTimeout(() => $('#msg').html(''), 5000);
            return;
        }
        $('#msg').html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Récupération...');
        $('#msg').css('color', '#2196f3');
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                updateLocation(lat, lng);
                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Position actuelle enregistrée');
                $('#msg').css('color', '#32c787');
                setTimeout(() => $('#msg').html(''), 4000);
            },
            function(error) {
                var errMsg = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED: errMsg = "Permission refusée."; break;
                    case error.POSITION_UNAVAILABLE: errMsg = "Position indisponible."; break;
                    case error.TIMEOUT: errMsg = "Délai dépassé."; break;
                    default: errMsg = "Erreur inconnue.";
                }
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> ' + errMsg);
                $('#msg').css('color', '#ff6b68');
                setTimeout(() => $('#msg').html(''), 5000);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    $("#btnClassic").click(function() {
        setTileLayer(tileLayerClassic);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Mode classique activé');
        $('#msg').css('color', '#32c787');
        setTimeout(() => $('#msg').html(''), 2000);
    });

    $("#btnSatellite").click(function() {
        setTileLayer(tileLayerSatellite);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Mode satellite activé');
        $('#msg').css('color', '#32c787');
        setTimeout(() => $('#msg').html(''), 2000);
    });

    $("#btnResetView").click(function() {
        map.setView([defaultLat, defaultLng], defaultZoom);
        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Vue réinitialisée');
        $('#msg').css('color', '#32c787');
        setTimeout(() => $('#msg').html(''), 2000);
    });

    (function() {
        var lat = $('#latitude').val();
        var lng = $('#longitude').val();
        if (lat && lng && lat.trim() !== "" && lng.trim() !== "" && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng))) {
            updateLocation(parseFloat(lat), parseFloat(lng));
        }
    })();

    // ========== FONCTIONS DE FILTRAGE POUR LES ÉCOLES AVEC PERSISTANCE ==========

    let schoolFilterTimeout;

    function saveSchoolFiltersToStorage() {
        const filters = {
            nom: $('#filterNomEcole').val(),
            directeur: $('#filterDirecteur').val(),
            district: $('#filterDistrict').val(),
            commune: $('#filterCommune').val(),
            eleves: $('#filterEleves').val()
        };
        localStorage.setItem('schoolFilters', JSON.stringify(filters));
    }

    function loadSchoolFiltersFromStorage() {
        const savedFilters = localStorage.getItem('schoolFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#filterNomEcole').val(filters.nom || '');
            $('#filterDirecteur').val(filters.directeur || '');
            $('#filterDistrict').val(filters.district || '');
            $('#filterCommune').val(filters.commune || '');
            $('#filterEleves').val(filters.eleves || '');
            return true;
        }
        return false;
    }

    function filterSchools() {
        const filterNom = $('#filterNomEcole').val().toLowerCase();
        const filterDirecteur = $('#filterDirecteur').val().toLowerCase();
        const filterDistrict = $('#filterDistrict').val().toLowerCase();
        const filterCommune = $('#filterCommune').val().toLowerCase();
        const filterEleves = parseInt($('#filterEleves').val());

        let visibleCount = 0;
        let newIndex = 1;

        $('#content_utilisateur tbody tr').each(function() {
            const $row = $(this);
            let showRow = true;

            const nomValue = ($row.find('.ecole-nom').data('nom') || '').toLowerCase();
            const directeurValue = ($row.find('.directeur-nom').data('directeur') || '').toLowerCase();
            const districtValue = ($row.find('.district-nom').data('district') || '').toLowerCase();
            const communeValue = ($row.find('.commune-nom').data('commune') || '').toLowerCase();
            const elevesValue = parseInt($row.find('.eleves-nb').data('eleves') || 0);

            if (filterNom && !nomValue.includes(filterNom)) showRow = false;
            if (showRow && filterDirecteur && !directeurValue.includes(filterDirecteur)) showRow = false;
            if (showRow && filterDistrict && !districtValue.includes(filterDistrict)) showRow = false;
            if (showRow && filterCommune && !communeValue.includes(filterCommune)) showRow = false;
            if (showRow && !isNaN(filterEleves) && elevesValue != filterEleves) showRow = false;

            if (showRow) {
                $row.show();
                $row.find('.row-num').text(newIndex);
                newIndex++;
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#schoolCount').text(visibleCount);

        if (visibleCount === 0 && (filterNom || filterDirecteur || filterDistrict || filterCommune || !isNaN(filterEleves))) {
            $('#msg').html('<i class="zmdi zmdi-info"></i> Aucune école ne correspond aux critères de recherche');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }
    }

    function resetSchoolFilters() {
        $('#filterNomEcole').val('');
        $('#filterDirecteur').val('');
        $('#filterDistrict').val('');
        $('#filterCommune').val('');
        $('#filterEleves').val('');

        saveSchoolFiltersToStorage();

        $('#content_utilisateur tbody tr').show();
        let newIndex = 1;
        $('#content_utilisateur tbody tr:visible').each(function() {
            $(this).find('.row-num').text(newIndex);
            newIndex++;
        });
        const totalCount = $('#content_utilisateur tbody tr').length;
        $('#schoolCount').text(totalCount);

        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
        $('#msg').css('display', 'flex');
        setTimeout(() => {
            $('#msg').html('');
            $('#msg').css('display', 'none');
        }, 3000);
    }

    function debouncedSchoolFilter() {
        clearTimeout(schoolFilterTimeout);
        schoolFilterTimeout = setTimeout(() => {
            filterSchools();
            saveSchoolFiltersToStorage();
        }, 300);
    }

    // Initialisation des événements de filtrage
    $(document).ready(function() {
        const totalSchools = $('#content_utilisateur tbody tr').length;
        $('#schoolCount').text(totalSchools);

        const hasSavedFilters = loadSchoolFiltersFromStorage();

        $('#filterNomEcole, #filterDirecteur, #filterDistrict, #filterCommune, #filterEleves').on('input change', function() {
            debouncedSchoolFilter();
        });

        $('#resetSchoolFilters').click(function(e) {
            e.preventDefault();
            resetSchoolFilters();
        });

        if (hasSavedFilters) {
            setTimeout(function() {
                filterSchools();
            }, 100);
        }
    });

    // Réappliquer les filtres après chaque chargement AJAX
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (settings.url.includes('refresh_') || settings.url.includes('add_ecole') || settings.url.includes('deleteecole'))) {
            setTimeout(() => {
                const totalSchools = $('#content_utilisateur tbody tr').length;
                $('#schoolCount').text(totalSchools);
                loadSchoolFiltersFromStorage();
                filterSchools();
            }, 200);
        }
    });

    // Sauvegarder les filtres avant de quitter
    window.addEventListener('beforeunload', function() {
        saveSchoolFiltersToStorage();
    });
</script>
@endsection
@endsection