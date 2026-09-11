@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

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
use App\Models\Entres;
use App\Models\detailpaiessachats;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'SUIVI DE CREDIT')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
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

h4 {
    font-weight: 700;
    border-left: 6px solid #e31b23;
    padding-left: 18px;
    margin-bottom: 16px;
    margin-top: 0;
    color: var(--bleu-nuit);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

h4 .badge-invoice {
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    color: white;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    margin-left: 6px;
    vertical-align: middle;
}

@media (max-width: 480px) {
    h4 .badge-invoice {
        font-size: 0.65rem;
        padding: 2px 10px;
        margin-left: 0;
        white-space: normal;
        word-break: break-word;
    }
    h4 {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}

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

.badge-delay {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    min-width: 34px;
    height: 20px;
    padding: 0 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    color: #ffffff;
    margin-left: 6px;
    vertical-align: middle;
    letter-spacing: 0.3px;
    white-space: nowrap;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
}
.badge-delay.success {
    background: linear-gradient(135deg, #10b981, #059669);
}
.badge-delay.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}
.badge-delay.danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}
.badge-delay i.zmdi {
    font-size: 0.75rem;
    color: #ffffff;
}

.badge-tranches {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}
.badge-tranches:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.paye-cell-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}

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
#save_r,
#print_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    transform: none !important;
    box-shadow: none !important;
}

#print {
    background: #3B82F6 !important;
    color: white !important;
}
#print:hover {
    background: #2563eb !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
}

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

.invoice-badges-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 8px 12px;
    margin-bottom: 15px;
}

.invoice-count-badge {
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    color: white;
}

.invoice-count-badge.usd-badge {
    background: linear-gradient(135deg, #3B82F6, #2563eb);
}
.invoice-count-badge.cdf-badge {
    background: linear-gradient(135deg, #3B82F6, #2563eb);
}

.invoice-count-badge.paye-usd {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
}
.invoice-count-badge.paye-cdf {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
}

.invoice-count-badge.credit-usd {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}
.invoice-count-badge.credit-cdf {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

.invoice-count-badge.benefice-usd {
    background: linear-gradient(135deg, #198754, #146c43);
}
.invoice-count-badge.benefice-cdf {
    background: linear-gradient(135deg, #198754, #146c43);
}

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
    to { opacity: 1; transform: translateY(0); }
}

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
.table tbody td a i.zmdi-money { color: #2c7da0; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a:hover i.zmdi-delete { color: #b91c1c; }
.table tbody td a:hover i.zmdi-eye,
.table tbody td a:hover i.zmdi-money { color: #1e5a7a; }

.table tbody td a i.zmdi-settings {
    color: #17a2b8 !important;
    transition: all 0.2s ease;
}
.table tbody td a:hover i.zmdi-settings {
    color: #0f6674 !important;
}

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
.modal.fade#pdfModal .modal-header .close:hover { opacity: 1; }
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
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
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

#tranchesModal .modal-content {
    border-radius: var(--border-radius-xl);
    border: none;
    box-shadow: var(--shadow-premium);
}
#tranchesModal .modal-header {
    background: var(--bleu-nuit-gradient);
    color: white;
    border-top-left-radius: var(--border-radius-xl);
    border-top-right-radius: var(--border-radius-xl);
    border-bottom: none;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}
#tranchesModal .modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
    margin-left: auto;
}
#tranchesModal .modal-header .close:hover {
    opacity: 1;
}
#tranchesModal .modal-title {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    font-size: 1.05rem;
}
#tranchesModal .badge-info-header {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
}
#tranchesModal .badge-info-header.tranches {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.35);
}
#tranchesModal .badge-info-header.duree-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
}
#tranchesModal .badge-info-header.duree-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
}
#tranchesModal .badge-info-header.duree-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
}
#tranchesModal .badge-info-header.statut-paid {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.35);
}
#tranchesModal .badge-info-header.statut-unpaid {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.35);
}
#tranchesModal .badge-info-header.statut-partial {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.35);
}
#tranchesModal .modal-body {
    background: #f8fafc;
    padding: 1.5rem;
}
#tranchesModal .modal-footer {
    background: white;
    border-top: 1px solid #eef2f6;
    border-bottom-left-radius: var(--border-radius-xl);
    border-bottom-right-radius: var(--border-radius-xl);
}
#tranchesModal .table thead th {
    background: #E7F5FE !important;
    color: var(--bleu-nuit);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 10px 8px !important;
    border-bottom: 2px solid #cbd5e1 !important;
}
#tranchesModal .table tbody td {
    font-size: 0.8rem;
    padding: 8px 8px !important;
    vertical-align: middle;
    color: #1e2a3e;
}
#tranchesModal .badge-devise {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
    display: inline-block;
    min-width: 40px;
    text-align: center;
}
#tranchesModal .badge-devise.usd {
    background: linear-gradient(135deg, #3B82F6, #2563eb);
}
#tranchesModal .badge-devise.cdf {
    background: linear-gradient(135deg, #0a192f, #1e3a5f);
}
#tranchesModal .date-range-item {
    background: white;
    padding: 12px 15px;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    border-left: 4px solid var(--bleu-nuit);
    height: 100%;
}
#tranchesModal .date-range-item .label {
    font-weight: 600;
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    display: block;
    margin-bottom: 4px;
}
#tranchesModal .date-range-item .value {
    font-weight: 700;
    color: var(--bleu-nuit);
    font-size: 0.88rem;
}

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
    #bloc_t { flex-direction: column; gap: 20px; align-items: center; }
    #bloc_2, #bloc_3 { flex: 1 1 100%; width: 100%; min-width: auto; }
}

@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
    #bloc_1, #bloc_2, #bloc_3 { padding: 1rem !important; }
}

@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    #bloc_1, #bloc_2, #bloc_3 { padding: 0.8rem !important; }
    #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters,
    .btn-primary, .btn-info, .btn-danger { padding: 4px 12px !important; font-size: 0.7rem; }
    .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; margin-bottom: 12px; }
    .filter-group { width: 100%; min-width: 100%; }
    .filter-group .form-control { height: 34px !important; }
    .invoice-badges-container { justify-content: flex-start; gap: 6px 8px; }
    .invoice-count-badge { font-size: 0.65rem; padding: 3px 10px; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; letter-spacing: 0.05em; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; line-height: 1.3; }
    #form_add .col-6, #form_edit .col-6 { flex: 0 0 100%; max-width: 100%; }
    .form-group label { font-size: 0.65rem; }
    .form-control, input.form-control, select.form-control, textarea.form-control { height: 34px !important; font-size: 0.75rem; }
    .modal.fade#pdfModal .modal-dialog { width: 95%; margin: 1rem auto; }
    .modal.fade#pdfModal #pdfIframe { height: 40vh; }
    .modal.fade#pdfModal #boite_de_control .col-lg-4 { margin-bottom: 10px; }
    .modal.fade#pdfModal #btn_payer { width: 100%; }
    .modal.fade#pdfModal .modal-footer { padding: 1rem; }
    .modal.fade#pdfModal #montant_recu, .modal.fade#pdfModal #devise_recu { height: 40px; font-size: 0.8rem; }
    .modal.fade#pdfModal #msg_facture { font-size: 0.75rem; padding: 8px 14px; }
    [style*="background-color: rgba(0, 0, 0, 0.1)"] { justify-content: center; gap: 8px; }
    .badge-delay { min-width: 28px; height: 18px; padding: 0 6px; font-size: 0.62rem; margin-left: 4px; }

    #tranchesModal .modal-body { padding: 1rem; }
    #tranchesModal .table thead th { font-size: 0.65rem; padding: 8px 4px !important; }
    #tranchesModal .table tbody td { font-size: 0.7rem; padding: 6px 4px !important; }
    #tranchesModal .modal-title { font-size: 0.85rem; }
    #tranchesModal .badge-info-header { font-size: 0.62rem; padding: 3px 9px; }

    .param-finance-grid { grid-template-columns: repeat(2, 1fr); }
    .param-info-grid    { grid-template-columns: 1fr; }
    .param-nav-tabs .nav-link { padding: 10px 8px; font-size: 0.72rem; }
    #paramFactureModal .modal-dialog { max-width: 100% !important; margin: 0.5rem; }
    .param-statut-card { padding: 12px 14px; }
    .param-statut-card i { font-size: 1.6rem; }
    .param-statut-card .param-statut-value { font-size: 1.1rem; }
    .param-totals-grid { grid-template-columns: repeat(2, 1fr); }
}

.badge-invoice i.zmdi { color: white !important; }

@media (max-width: 480px) {
    .content .container { padding: 0.3rem !important; }
    #bloc_1, #bloc_2, #bloc_3 { padding: 0.6rem !important; }
    h4 { font-size: 1.1rem; margin-bottom: 12px; }
    h4 i { font-size: 24px !important; }
    #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters { padding: 3px 8px !important; font-size: 0.65rem; }
    .table thead th { font-size: 0.62rem; padding: 8px 4px !important; }
    .table tbody td { padding: 6px 8px !important; font-size: 0.7rem; line-height: 1.2; }
    .modal.fade#pdfModal .modal-header { padding: 0.8rem 1rem; }
    .modal.fade#pdfModal .modal-header .modal-title { font-size: 1rem; }
    .modal.fade#pdfModal #pdfIframe { height: 35vh; }
    .modal.fade#pdfModal #btn_payer { padding: 8px 16px; font-size: 0.75rem; }
    .modal.fade#pdfModal .btn-secondary { padding: 6px 16px; font-size: 0.7rem; }
    .modal.fade#pdfModal #msg_facture { font-size: 0.7rem; padding: 6px 12px; }
    #add { display: none !important; }
}

/* ============================================================
   MODALE PARAMÈTRES FACTURE – STYLES COMPLETS
   ============================================================ */
.param-nav-tabs .nav-link {
    border: none;
    color: #64748b;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 14px 16px;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    background: transparent;
}
.param-nav-tabs .nav-link i {
    margin-right: 4px;
}
.param-nav-tabs .nav-link:hover {
    color: #0a192f;
    border-bottom-color: #cbd5e1;
}
.param-nav-tabs .nav-link.active {
    color: #17a2b8;
    border-bottom-color: #17a2b8;
    background: transparent;
}

.param-section-title {
    font-weight: 700;
    color: #0a192f;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-left: 12px;
    border-left: 4px solid #17a2b8;
    margin-bottom: 14px;
}

.param-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.param-info-item {
    background: white;
    padding: 10px 14px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.param-info-label {
    font-size: 0.7rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.3px;
}
.param-info-label i { color: #17a2b8; margin-right: 4px; }

.param-info-value {
    font-size: 0.88rem;
    color: #0a192f;
    font-weight: 700;
}

.param-finance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
}

.param-finance-card {
    padding: 14px 16px;
    border-radius: 14px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transition: transform 0.2s;
}
.param-finance-card:hover { transform: translateY(-2px); }

.param-finance-card.usd-card     { background: linear-gradient(135deg, #3B82F6, #2563eb); }
.param-finance-card.paye-card    { background: linear-gradient(135deg, #0a192f, #1e3a5f); }
.param-finance-card.credit-card  { background: linear-gradient(135deg, #dc3545, #b02a37); }
.param-finance-card.benefice-card{ background: linear-gradient(135deg, #198754, #146c43); }

.param-statut-card {
    padding: 16px 20px;
    border-radius: 14px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    transition: transform 0.2s;
}
.param-statut-card:hover { transform: translateY(-2px); }
.param-statut-card.paid    { background: linear-gradient(135deg, #198754, #146c43); }
.param-statut-card.unpaid  { background: linear-gradient(135deg, #dc3545, #b02a37); }
.param-statut-card.partiel { background: linear-gradient(135deg, #f59e0b, #d97706); }
.param-statut-card i {
    font-size: 2rem;
    opacity: 0.95;
}
.param-statut-card .param-statut-txt {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.param-statut-card .param-statut-title {
    font-size: 0.72rem;
    text-transform: uppercase;
    opacity: 0.9;
    letter-spacing: 0.4px;
}
.param-statut-card .param-statut-value {
    font-size: 1.3rem;
    font-weight: 800;
}
.param-statut-card .param-statut-sub {
    font-size: 0.78rem;
    opacity: 0.9;
    font-weight: 500;
}

.param-action-box {
    background: white;
    padding: 14px 16px;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    height: 100%;
}

.param-action-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #0a192f;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: block;
    margin-bottom: 8px;
}

#paramFactureModal .modal-body::-webkit-scrollbar,
#paramFactureModal .tab-content::-webkit-scrollbar {
    width: 8px;
}
#paramFactureModal .modal-body::-webkit-scrollbar-thumb,
#paramFactureModal .tab-content::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

#param_articles_table tbody tr:hover,
#param_paiements_table tbody tr:hover {
    background: #e6f0ff !important;
}

.param-inline-input {
    width: 100% !important;
    min-width: 90px;
    height: 34px !important;
    font-size: 0.8rem !important;
    padding: 4px 8px !important;
    border-radius: 8px !important;
    text-align: right;
    font-weight: 600;
}
.param-inline-input.frais-input {
    border-color: #f59e0b !important;
    background: #fffbeb !important;
    color: #92400e;
}
.param-inline-input.reduction-input {
    border-color: #dc2626 !important;
    background: #fef2f2 !important;
    color: #991b1b;
}

.dual-currency b {
    display: block;
    font-size: 0.82rem;
}
.dual-currency small {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 500;
}

.param-totals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-top: 10px;
}

.param-total-item {
    background: white;
    padding: 12px 14px;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
    border: 1px solid #eef2f6;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.param-total-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.param-total-label {
    font-size: 0.65rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.4px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.param-total-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

.param-total-value {
    font-size: 0.92rem;
    font-weight: 800;
    line-height: 1.2;
}

.param-total-sub {
    font-size: 0.72rem;
    font-weight: 600;
    margin-top: 3px;
    color: #64748b;
    line-height: 1.2;
}

.param-total-item.total-qte    .param-total-value { color: #17a2b8; }
.param-total-item.total-qte    .param-total-dot   { background: #17a2b8; }

.param-total-item.total-pv     .param-total-value { color: #3B82F6; }
.param-total-item .total-pv     .param-total-dot   { background: #3B82F6; }

.param-total-item.total-gen    .param-total-value { color: #0a192f; }
.param-total-item.total-gen    .param-total-dot   { background: #0a192f; }
.param-total-item.total-gen    .param-total-sub   { color: #3B82F6; }

.param-total-item.total-frais  .param-total-value { color: #f59e0b; }
.param-total-item.total-frais  .param-total-dot   { background: #f59e0b; }
.param-total-item.total-frais  .param-total-sub   { color: #d97706; }

.param-total-item.total-red    .param-total-value { color: #dc2626; }
.param-total-item.total-red    .param-total-dot   { background: #dc2626; }
.param-total-item.total-red    .param-total-sub   { color: #b91c1c; }

.param-total-item.total-benef  .param-total-value { color: #198754; }
.param-total-item.total-benef  .param-total-dot   { background: #198754; }
.param-total-item.total-benef  .param-total-sub   { color: #146c43; }
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
                                        {{-- <a style="display: none;" class="btn-primary btn-sm" id="add" href="">
                                            <i class="zmdi zmdi-email"></i> Ajouter
                                        </a> --}}
                                    <?php } else { ?>
                                        {{-- <a style="display: none;" class="btn-primary btn-sm" id="add_r" href="">
                                            <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                        </a> --}}
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de facture</h6>
                </div>

                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Suivi des factures
                        <span class="badge-invoice">
                            <i class="zmdi zmdi-view-list" style="color: white;"></i> Factures : <span id="invoiceCount">0</span>
                        </span>
                    </h4>

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
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Période</label>
                            <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période (ou Tout)">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar-check text-danger"></i> Période de paie</label>
                            <input type="text" id="filterDatePaieRange" class="form-control" placeholder="Sélectionner une période de paie (ou Tout)">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-chart text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="Montant exact" step="0.01">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-balance-wallet text-danger"></i> Statut de paiement</label>
                            <select id="filterStatut" class="form-control">
                                <option value="">Toutes</option>
                                <option value="paid">Payées</option>
                                <option value="unpaid">Impayées</option>
                                <option value="partial">Partielles</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-alarm text-danger"></i> Nombre de jours</label>
                            <input type="number" id="filterJour" class="form-control" placeholder="Ex : 1, 2, 3..." min="0" step="1">
                        </div>
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <div class="invoice-badges-container">
                        <span class="invoice-count-badge usd-badge">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge cdf-badge">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge paye-usd">
                            <i class="zmdi zmdi-money"></i> Payée USD : <span id="totalPaidUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge paye-cdf">
                            <i class="zmdi zmdi-money-box"></i> Payée CDF : <span id="totalPaidCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge credit-usd">
                            <i class="zmdi zmdi-time"></i> Crédit USD : <span id="totalCreditUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge credit-cdf">
                            <i class="zmdi zmdi-time"></i> Crédit CDF : <span id="totalCreditCdf">0,00</span> Fc
                        </span>
                        <span class="invoice-count-badge benefice-usd">
                            <i class="zmdi zmdi-trending-up"></i> Bénéfice USD : <span id="totalBeneficeUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge benefice-cdf">
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
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Payée</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date de paie</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Mode de paiement</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($factures as $data)
                                            @php
                                                $taux = $data->taux;
                                                if ($taux <= 0) $taux = 1;

                                                $ent = Achats::where('facture_id', $data->id)->get();
                                                $total_frais_credit = 0;

                                                /* ============================================================
                                                   MONTANT DÛ RÉEL = Σ (total − réduction) par achat
                                                   (réduction retranchée par achat, dans SA devise)
                                                   ============================================================ */
                                                $total_original = 0;
                                                foreach ($ent as $e)
                                                {
                                                    $devise_achat_orig = $e->devise_achat ?? $data->devise;
                                                    $reduction_orig = (isset($e->reduction) && $e->reduction > 0) ? $e->reduction : 0;
                                                    $net_orig = $e->total - $reduction_orig;

                                                    if ($devise_achat_orig == $data->devise) {
                                                        $total_original += $net_orig;
                                                    } elseif ($data->devise == 0) {
                                                        $total_original += ($taux > 0) ? ($net_orig / $taux) : 0;
                                                    } else {
                                                        $total_original += $net_orig * $taux;
                                                    }

                                                    if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                                        $total_frais_credit += $e->frais_credit;
                                                    }
                                                }

                                                $paiements = detailpaiessachats::where('facture_id', $data->id)->get();
                                                $montant_usd_paye = 0;
                                                $montant_cdf_paye = 0;
                                                foreach ($paiements as $paiement)
                                                {
                                                    if ($paiement->devise_recu == 0) {
                                                        $montant_usd_paye += $paiement->montant_recu;
                                                        $montant_cdf_paye += $paiement->montant_recu * $taux;
                                                    } else {
                                                        $montant_cdf_paye += $paiement->montant_recu;
                                                        $montant_usd_paye += ($taux > 0) ? ($paiement->montant_recu / $taux) : 0;
                                                    }
                                                }

                                                $dernier_paiement = detailpaiessachats::where('facture_id', $data->id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();

                                                if ($dernier_paiement && $dernier_paiement->created_at) {
                                                    $date_paie = date('d/m/Y à H:i', strtotime($dernier_paiement->created_at));
                                                } else {
                                                    $date_paie = 'Aucune';
                                                }

                                                $date_paie_ymd = ($dernier_paiement && $dernier_paiement->created_at)
                                                    ? date('Y-m-d', strtotime($dernier_paiement->created_at))
                                                    : '';

                                                if ($data->devise == 0)
                                                {
                                                    $total_original_usd = $total_original;
                                                    $total_original_cdf = $total_original * $taux;
                                                }
                                                else
                                                {
                                                    $total_original_cdf = $total_original;
                                                    $total_original_usd = ($taux > 0) ? ($total_original / $taux) : 0;
                                                }

                                                $est_impayee = ($montant_usd_paye < $total_original_usd) || ($montant_cdf_paye < $total_original_cdf);

                                                $date_creation_facture = strtotime($data->created_at);
                                                $delai_1h = 3600;
                                                $delai_depasse = (time() - $date_creation_facture) > $delai_1h;

                                                /* ============================================================
                                                   TOTAL FINAL = Σ (total − réduction + frais_credit) par achat
                                                   (chaque montant dans SA devise, puis converti)
                                                   ============================================================ */
                                                $total = 0;
                                                $achat_total_usd = 0;
                                                $achat_total_cdf = 0;

                                                foreach ($ent as $e)
                                                {
                                                    $devise_achat = $e->devise_achat ?? $data->devise;
                                                    $reduction_achat = (isset($e->reduction) && $e->reduction > 0) ? $e->reduction : 0;

                                                    $frais_credit_achat = 0;
                                                    if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                                        $frais_credit_achat = $e->frais_credit;
                                                    } else {
                                                        if ($est_impayee && $delai_depasse) {
                                                            $frais_credit_achat = $e->total * 0.05;
                                                            $e->frais_credit = $frais_credit_achat;
                                                            $e->save();
                                                        }
                                                    }

                                                    // Net de CET achat = total − réduction + frais (devise achat)
                                                    $net_achat_devise = $e->total - $reduction_achat + $frais_credit_achat;

                                                    if ($devise_achat == $data->devise) {
                                                        $total += $net_achat_devise;
                                                    } elseif ($data->devise == 0) {
                                                        $total += ($taux > 0) ? ($net_achat_devise / $taux) : 0;
                                                    } else {
                                                        $total += $net_achat_devise * $taux;
                                                    }

                                                    $prix_achat = $e->prix_achat ?? 0;
                                                    $quantite = $e->quantite ?? 1;
                                                    $prix_achat_total = $prix_achat * $quantite;

                                                    if ($devise_achat == 0) {
                                                        $achat_total_usd += $prix_achat_total;
                                                        $achat_total_cdf += $prix_achat_total * $taux;
                                                    } else {
                                                        $achat_total_cdf += $prix_achat_total;
                                                        $achat_total_usd += ($taux > 0) ? ($prix_achat_total / $taux) : 0;
                                                    }
                                                }

                                                $total_frais_credit_final = 0;
                                                foreach ($ent as $e) {
                                                    if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                                        $total_frais_credit_final += $e->frais_credit;
                                                    }
                                                }
                                                $a_frais_credit = ($total_frais_credit_final > 0);

                                                if ($data->devise == 0)
                                                {
                                                    $montant_usd = $total;
                                                    $montant_cdf = $total * $taux;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                                                } else {
                                                    $montant_cdf = $total;
                                                    $montant_usd = ($taux > 0) ? ($total / $taux) : 0;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                                                }

                                                $benefice_usd = $montant_usd - $achat_total_usd;
                                                $benefice_cdf = $montant_cdf - $achat_total_cdf;

                                                $reste_usd = $montant_usd - $montant_usd_paye;
                                                $reste_cdf = $montant_cdf - $montant_cdf_paye;

                                                $paye_affichage = number_format($montant_usd_paye, 2, ',', ' ') . ' USD (' . number_format($montant_cdf_paye, 2, ',', ' ') . ' CDF)';
                                                $reste_affichage = number_format($reste_usd, 2, ',', ' ') . ' USD (' . number_format($reste_cdf, 2, ',', ' ') . ' CDF)';
                                                $statut_text = $reste_usd > 0 ? 'Impayée' : 'Payée';
                                                $client_name = $data->client_id == 0 ? $data->libelle : (Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A');

                                                $est_partiel = ($reste_usd > 0 && $montant_usd_paye > 0);
                                                $est_solde = ($reste_usd <= 0);

                                                if ($est_partiel) {
                                                    $statut_data = 'partial';
                                                } elseif ($est_solde) {
                                                    $statut_data = 'paid';
                                                } else {
                                                    $statut_data = 'unpaid';
                                                }

                                                $date_creation_date = date('Y-m-d', strtotime($data->created_at));
                                                $date_aujourdhui   = date('Y-m-d');

                                                if ($est_solde && $a_frais_credit && $dernier_paiement) {
                                                    $date_comparaison = date('Y-m-d', strtotime($dernier_paiement->created_at));
                                                } else {
                                                    $date_comparaison = $date_aujourdhui;
                                                }

                                                $nb_jours_retard = (int) ((strtotime($date_comparaison) - strtotime($date_creation_date)) / 86400);
                                                if ($nb_jours_retard < 0) {
                                                    $nb_jours_retard = 0;
                                                }

                                                $show_delay_badge = false;

                                                if (!$dernier_paiement) {
                                                    $show_delay_badge = true;
                                                } elseif ($est_partiel) {
                                                    $show_delay_badge = true;
                                                } elseif ($est_solde && $a_frais_credit && $dernier_paiement) {
                                                    $show_delay_badge = true;
                                                }

                                                $delay_badge_type = 'success';
                                                if ($nb_jours_retard >= 7 && $nb_jours_retard <= 15) {
                                                    $delay_badge_type = 'warning';
                                                } elseif ($nb_jours_retard > 15) {
                                                    $delay_badge_type = 'danger';
                                                }

                                                $nb_tranches = $paiements->count();
                                                $tranches_details = [];
                                                foreach ($paiements as $p) {
                                                    $tranches_details[] = [
                                                        'date' => date('d/m/Y à H:i', strtotime($p->created_at)),
                                                        'montant_recu' => number_format($p->montant_recu, 2, ',', ' '),
                                                        'devise' => $p->devise_recu == 0 ? 'USD' : 'CDF',
                                                        'mode' => $p->mode_de_paiement == 1 ? 'CASH' : ($p->mode_de_paiement == 2 ? 'Mobile Money' : 'Bank'),
                                                        'taux' => number_format($p->taux, 2, ',', ' '),
                                                        'montant_effectif' => number_format($p->montant_effectif, 2, ',', ' '),
                                                        'reste' => number_format($p->reste, 2, ',', ' ')
                                                    ];
                                                }
                                                $tranches_json = json_encode($tranches_details, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP);

                                                /* ===== DONNÉES POUR MODALE PARAMÈTRES ===== */
                                                $modeLabels = [1 => 'CASH', 2 => 'Mobile money', 3 => 'Bank'];

                                                $articles_json = [];
                                                foreach ($ent as $e) {
                                                    $nom_article_aff = $e->nom_article
                                                                    ?? $e->nom
                                                                    ?? $e->name
                                                                    ?? ('Article #' . $e->id);

                                                    $pa = $e->prix_achat ?? 0;
                                                    $qt = $e->quantite ?? 1;
                                                    $prix_unit = $e->prix_vente ?? ($qt > 0 ? ($e->total / $qt) : $e->total);

                                                    $devise_achat_json = $e->devise_achat ?? $data->devise;
                                                    $reduction_ligne   = $e->reduction ?? 0;

                                                    $articles_json[] = [
                                                        'id'               => $e->id,
                                                        'nom'              => $nom_article_aff,
                                                        'quantite'         => $qt,
                                                        'prix_unitaire'    => $prix_unit,
                                                        'prix_achat'       => $pa,
                                                        'prix_achat_total' => $pa * $qt,
                                                        'total'            => $e->total,
                                                        'total_net'        => $e->total - $reduction_ligne,
                                                        'frais_credit'     => $e->frais_credit ?? 0,
                                                        'reduction'        => $reduction_ligne,
                                                        'devise_achat'     => $devise_achat_json,
                                                    ];
                                                }

                                                $paiements_json = [];
                                                foreach ($paiements as $p) {
                                                    $isUSD = ($p->devise_recu == 0);
                                                    $paiements_json[] = [
                                                        'id'               => $p->id,
                                                        'date'             => date('d/m/Y à H:i', strtotime($p->created_at)),
                                                        'payer'            => $p->payer,
                                                        'montant_recu'     => $p->montant_recu,
                                                        'devise_label'     => $isUSD ? 'USD' : 'CDF',
                                                        'mode_de_paiement' => $p->mode_de_paiement,
                                                        'mode_label'       => $modeLabels[$p->mode_de_paiement] ?? 'N/A',
                                                        'taux'             => $p->taux,
                                                        'reste'            => $p->reste,
                                                        'montant_effectif' => $p->montant_effectif,
                                                    ];
                                                }

                                                $statut_html_param = $reste_usd > 0
                                                    ? '<span class="text-danger"><i class="zmdi zmdi-close-circle"></i> Impayé</span>'
                                                    : '<span class="text-success"><i class="zmdi zmdi-check-circle"></i> Payé</span>';

                                                $mode_paiement_label = $modeLabels[$data->mode_de_paiement] ?? 'N/A';

                                                $table_name_param = 'Aucune';
                                                if (isset($data->table_id) && $data->table_id != 0) {
                                                    $tbl = Tables::where('id', $data->table_id)->first();
                                                    if ($tbl) { $table_name_param = $tbl->nom; }
                                                }
                                            @endphp

                                            @if ($reste_usd > 0 || $total_frais_credit_final > 0)
                                            <tr id="row_{{ $data->id }}"
                                                data-paie-date-ymd="{{ $date_paie_ymd }}"
                                                data-montant-usd="{{ $montant_usd }}"
                                                data-montant-cdf="{{ $montant_cdf }}"
                                                data-paye-usd="{{ $montant_usd_paye }}"
                                                data-paye-cdf="{{ $montant_cdf_paye }}"
                                                data-credit-usd="{{ $reste_usd }}"
                                                data-credit-cdf="{{ $reste_cdf }}"
                                                data-benefice-usd="{{ $benefice_usd }}"
                                                data-benefice-cdf="{{ $benefice_cdf }}"
                                                data-jours-retard="{{ $nb_jours_retard }}"
                                                data-facture-id="{{ $data->id }}"
                                                data-numero="{{ $data->numero }}"
                                                data-client="{{ $client_name }}"
                                                data-client-id="{{ $data->client_id }}"
                                                data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}"
                                                data-table="{{ $table_name_param }}"
                                                data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                                                data-devise="{{ $data->devise }}"
                                                data-devise-label="{{ $data->devise == 0 ? 'USD' : 'CDF' }}"
                                                data-taux="{{ $taux }}"
                                                data-mode-paiement="{{ $mode_paiement_label }}"
                                                data-statut-html="{{ $statut_html_param }}"
                                                data-frais-credit-total="{{ $total_frais_credit_final }}"
                                                data-pdf-url="{{ $data->lien ?? '' }}"
                                                data-articles='@json($articles_json)'
                                                data-paiements='@json($paiements_json)'>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell" data-client="{{ $client_name }}">
                                                    @if ($data->client_id == 0)
                                                        {{ $data->libelle }}
                                                    @else
                                                        <?= Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' ?>
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="{{ $total }}">
                                                    {{ $montant_affichage }}
                                                </td>

                                                <td class="paye-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                                                    <div class="paye-cell-content">
                                                        <span>{{ $paye_affichage }}</span>
                                                        <span class="badge-delay {{ $delay_badge_type }} badge-tranches"
                                                              data-tranches="{{ $tranches_json }}"
                                                              data-numero="{{ $data->numero }}"
                                                              data-client="{{ $client_name }}"
                                                              data-total="{{ $montant_affichage }}"
                                                              data-paye="{{ $paye_affichage }}"
                                                              data-reste="{{ $reste_affichage }}"
                                                              data-nb-tranches="{{ $nb_tranches }}"
                                                              data-date-debut="{{ $data->created_at }}"
                                                              data-date-fin="{{ $dernier_paiement ? $dernier_paiement->created_at : date('Y-m-d H:i:s') }}"
                                                              data-jours-retard="{{ $nb_jours_retard }}"
                                                              data-statut="{{ $statut_data }}"
                                                              title="Cliquez pour voir les détails des tranches">
                                                            <i class="zmdi zmdi-layers"></i> {{ $nb_tranches }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="reste-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $reste_affichage }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ $data->created_at }}">
                                                    <?php
                                                        $date = $data->created_at;
                                                        $date_1 = explode(' ', $date);
                                                        echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                                    ?>
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="date-paie-cell" data-date-paie="{{ $date_paie }}">
                                                    @if (!$dernier_paiement)
                                                        <span class="text-danger"><i class="zmdi zmdi-time-restore"></i> {{ $date_paie }}</span>
                                                    @elseif ($est_partiel)
                                                        <span class="text-warning"><i class="zmdi zmdi-time"></i> {{ $date_paie }}</span>
                                                    @else
                                                        <span class="text-success"><i class="zmdi zmdi-check-circle"></i> {{ $date_paie }}</span>
                                                    @endif

                                                    @if ($show_delay_badge)
                                                        <span class="badge-delay {{ $delay_badge_type }}" title="Nombre de jours">
                                                            <i class="zmdi zmdi-alarm"></i> {{ $nb_jours_retard }}j
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell" data-statut="{{ $statut_data }}">
                                                    @if ($est_partiel)
                                                        <i class="zmdi zmdi-time text-warning"></i> <span class="text-warning">Partielle</span>
                                                    @elseif ($est_solde)
                                                        @if ($data->mode_de_paiement == 1)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">CASH</span>
                                                        @endif
                                                        @if ($data->mode_de_paiement == 2)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Mobile money</span>
                                                        @endif
                                                        @if ($data->mode_de_paiement == 3)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Bank</span>
                                                        @endif
                                                    @else
                                                        <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Impayée</span>
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

                                                    <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($delete == 0) && (Auth::user()->role == 0))) { ?>
                                                        <a href="#" class="param-facture-btn"
                                                           data-id="{{ $data->id }}"
                                                           title="Paramètres de la facture">
                                                            <i class="zmdi zmdi-settings text-info"></i>
                                                        </a>
                                                    <?php } ?>

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
                                            @endif
                                            {{ !$i++ }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bloc_t" class="row" style="width: 100%; margin: 0;">
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
                                                class="zmdi zmdi-info"></i> Il s'agit de quel article ?</span></label>
                                        <select id="type_sortie" name="type_sortie" class="select2"
                                            data-placeholder="Selectionnez un article">
                                            <option selected value="">Selectionnez un article</option>
                                            @foreach ($articles as $data)
                                                @if (($data->activite_id != 0) && ($data->stock > $data->seuil_minimum))
                                                    <option value="{{ $data->id }}">
                                                        🟢 {{ $data->nom_article }}
                                                        {{ (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') }}
                                                        ({{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }})
                                                    </option>
                                                @else
                                                    @php
                                                        $erreurs = [];
                                                        if ($data->activite_id == 0) { $erreurs[] = 'Activité non définie'; }
                                                        if ($data->stock <= $data->seuil_minimum) { $erreurs[] = 'Stock insuffisant'; }
                                                        $message = implode(' et ', $erreurs);
                                                    @endphp
                                                    <option disabled value="{{ $data->id }}">
                                                        🔴 {{ $data->nom_article }}
                                                        {{ (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') }}
                                                        ({{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }})
                                                        : {{ $message }}
                                                    </option>
                                                @endif
                                            @endforeach
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
                                            <option selected class="form-control" value="">Selectionnez une devise</option>
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
                            <div class="row"></div>
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" style="text-align: center;">
                                    <span style="font-weight: bold;" id="msg"></span>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row" id="content_sortie"></div>
                        <br>
                    </div>

                    <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-7"></div>
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

    <div class="modal fade" id="tranchesModal" tabindex="-1" role="dialog" aria-labelledby="tranchesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tranchesModalLabel">
                        <i class="zmdi zmdi-layers"></i> Facture <span id="modal_numero" style="color: #fca5a5; font-weight: 800;"></span>
                        <span class="badge-info-header statut-unpaid" id="modal_statut_header">
                            <i class="zmdi zmdi-close-circle"></i> <span id="modal_statut_val">-</span>
                        </span>
                        <span class="badge-info-header tranches" id="modal_nb_tranches_header">
                            <i class="zmdi zmdi-layers"></i> <span id="modal_nb_tranches_val">0</span> tranche(s)
                        </span>
                        <span class="badge-info-header duree-success" id="modal_duree_header">
                            <i class="zmdi zmdi-time"></i> <span id="modal_duree_val">0</span> jour(s)
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="background: white; padding: 15px; border-radius: 12px; box-shadow: var(--shadow-light); margin: 0 0 15px 0;">
                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <small class="text-muted d-block" style="font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Client / Libellé</small>
                            <span id="modal_client" style="font-weight: 700; color: var(--bleu-nuit); font-size: 0.9rem;"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <small class="text-muted d-block" style="font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Montant Total</small>
                            <span id="modal_total" style="font-weight: 700; color: var(--bleu-nuit); font-size: 0.9rem;"></span>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <small class="text-muted d-block" style="font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Payée / Reste</small>
                            <span style="font-weight: 700; font-size: 0.9rem;">
                                <span id="modal_paye" class="text-success"></span> <span style="color: #cbd5e1;">|</span> <span id="modal_reste" class="text-danger"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                            <div class="date-range-item">
                                <span class="label"><i class="zmdi zmdi-calendar-note"></i> Date début (Création)</span>
                                <span class="value" id="modal_date_debut">-</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                            <div class="date-range-item" style="border-left-color: #10b981;">
                                <span class="label"><i class="zmdi zmdi-check-circle"></i> Date fin (Dernier paiement)</span>
                                <span class="value" id="modal_date_fin">-</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="date-range-item" style="border-left-color: #f59e0b;">
                                <span class="label"><i class="zmdi zmdi-time"></i> Durée totale</span>
                                <span class="value" id="modal_duree_texte">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-light);">
                        <table class="table table-bordered mb-0" style="min-width: 100%; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 40px;">#</th>
                                    <th>Date</th>
                                    <th>Montant Reçu</th>
                                    <th>Devise</th>
                                    <th>Mode</th>
                                    <th>Montant Effectif</th>
                                    <th>Reste</th>
                                </tr>
                            </thead>
                            <tbody id="modal_tranches_body">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

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

    {{-- ========== MODALE PARAMÈTRES FACTURE ========== --}}
    <div class="modal fade" id="paramFactureModal" tabindex="-1" role="dialog" aria-labelledby="paramFactureLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1200px;">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">

                <div class="modal-header" style="background: linear-gradient(135deg, #0a192f, #1e3a5f); color: white; border-bottom: none; padding: 1.1rem 1.5rem;">
                    <h5 class="modal-title" id="paramFactureLabel" style="font-weight: 700;">
                        <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
                        Paramètres de la facture : <span id="param_numero" class="text-warning">-</span>
                    </h5>
                    <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
                        <button type="button" id="param_refresh_btn"
                                style="background: rgba(255,255,255,0.15); border: none; color: white; width: 34px; height: 34px; border-radius: 50%; cursor: pointer;"
                                title="Actualiser">
                            <i class="zmdi zmdi-refresh"></i>
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9; margin: 0; padding: 0 0 0 8px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="background: #f8fafc; padding: 0; max-height: 78vh; overflow: hidden;">

                    <ul class="nav nav-tabs param-nav-tabs" id="paramTabs" role="tablist" style="border-bottom: 1px solid #e2e8f0; background: white; padding: 0 1.5rem;">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-info-tab" data-toggle="tab" href="#tab-info" role="tab">
                                <i class="zmdi zmdi-info"></i> Informations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-articles-tab" data-toggle="tab" href="#tab-articles" role="tab">
                                <i class="zmdi zmdi-shopping-cart"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-paiements-tab" data-toggle="tab" href="#tab-paiements" role="tab">
                                <i class="zmdi zmdi-money"></i> Paiements
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="padding: 1.5rem; max-height: calc(78vh - 60px); overflow-y: auto;">

                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">

                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-check-circle text-info"></i> Statut de paiement
                            </h6>
                            <div id="param_statut_card_wrapper" style="margin-bottom: 22px;">
                                <div class="param-statut-card paid" id="param_statut_card">
                                    <i class="zmdi zmdi-check-circle" id="param_statut_icon"></i>
                                    <div class="param-statut-txt">
                                        <span class="param-statut-title">Statut actuel de la facture</span>
                                        <span class="param-statut-value" id="param_statut_label">-</span>
                                        <span class="param-statut-sub" id="param_statut_sub">-</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-info text-info"></i> Informations générales
                            </h6>
                            <div class="param-info-grid">
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account"></i> Client / Libellé</span>
                                    <span class="param-info-value" id="param_client">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account-circle"></i> Utilisateur</span>
                                    <span class="param-info-value" id="param_user">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-calendar"></i> Date</span>
                                    <span class="param-info-value" id="param_date">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-table"></i> Table</span>
                                    <span class="param-info-value" id="param_table">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-money"></i> Devise</span>
                                    <span class="param-info-value" id="param_devise">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-chart"></i> Taux</span>
                                    <span class="param-info-value" id="param_taux">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-money-box"></i> Mode paiement</span>
                                    <span class="param-info-value" id="param_mode">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account-box"></i> N° Client ID</span>
                                    <span class="param-info-value" id="param_client_id">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-edit"></i> ID Facture</span>
                                    <span class="param-info-value" id="param_facture_id">-</span>
                                </div>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-balance-wallet text-info"></i> Résumé financier
                            </h6>
                            <div class="param-finance-grid">
                                <div class="param-finance-card usd-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-money"></i> Montant total</div>
                                    <div class="param-finance-value" id="param_montant_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_montant_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card paye-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-check"></i> Déjà payé</div>
                                    <div class="param-finance-value" id="param_paye_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_paye_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card credit-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-time"></i> Crédit restant</div>
                                    <div class="param-finance-value" id="param_credit_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_credit_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card benefice-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-trending-up"></i> Bénéfice</div>
                                    <div class="param-finance-value" id="param_benefice_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_benefice_cdf">0.00 CDF</div>
                                </div>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-alert-circle text-info"></i> Frais & Réductions appliqués
                            </h6>
                            <div class="param-info-grid">
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-alert-circle"></i> Frais de crédit total</span>
                                    <span class="param-info-value text-warning" id="param_frais_credit_show">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-minus-circle"></i> Réduction totale</span>
                                    <span class="param-info-value text-danger" id="param_reduction_show">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-shopping-basket"></i> Montant brut</span>
                                    <span class="param-info-value" id="param_brut_show">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-receipt"></i> Nombre d'articles</span>
                                    <span class="param-info-value" id="param_nb_articles">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-articles" role="tabpanel">
                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-shopping-cart text-info"></i> Détails des articles
                                <span style="font-size:0.72rem; text-transform:none; font-weight:500; color:#64748b; margin-left:8px;">
                                    (Montants en USD et CDF — Frais crédit et Réduction sont modifiables)
                                </span>
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <table class="table table-sm mb-0" id="param_articles_table">
                                    <thead style="background: #E7F5FE;">
                                        <tr>
                                            <th style="font-size: 0.72rem;">#</th>
                                            <th style="font-size: 0.72rem;">Article</th>
                                            <th style="font-size: 0.72rem;">Qté</th>
                                            <th style="font-size: 0.72rem;">Prix vente</th>
                                            <th style="font-size: 0.72rem;">Total</th>
                                            <th style="font-size: 0.72rem;">Frais crédit</th>
                                            <th style="font-size: 0.72rem;">Réduction</th>
                                            <th style="font-size: 0.72rem;">Bénéfice</th>
                                        </tr>
                                    </thead>
                                    <tbody id="param_articles_body">
                                        <tr><td colspan="8" class="text-center text-muted">Chargement...</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-chart text-info"></i> Totaux généraux
                            </h6>
                            <div class="param-totals-grid" id="param_totals_grid">
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <button type="button" class="btn btn-info btn-sm" id="param_btn_save_lines"
                                        style="border-radius: 40px; padding: 10px 26px;">
                                    <i class="zmdi zmdi-save"></i> Enregistrer les modifications
                                </button>
                                <br><br>
                                <span id="param_lines_msg" style="font-weight: 600; font-size: 0.85rem;"></span>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-paiements" role="tabpanel">
                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-money text-info"></i> Détail des tranches de paiement
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <table class="table table-sm mb-0" id="param_paiements_table">
                                    <thead style="background: #E7F5FE;">
                                        <tr>
                                            <th style="font-size: 0.72rem;">#</th>
                                            <th style="font-size: 0.72rem;">Date</th>
                                            <th style="font-size: 0.72rem;">Mode</th>
                                            <th style="font-size: 0.72rem;">Devise</th>
                                            <th style="font-size: 0.72rem;">Reçu</th>
                                            <th style="font-size: 0.72rem;">Reste après</th>
                                        </tr>
                                    </thead>
                                    <tbody id="param_paiements_body">
                                        <tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>
                                    </tbody>
                                    <tfoot id="param_paiements_foot" style="background: #f1f5f9; font-weight: 700;"></tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer" style="background: white; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                            style="border-radius: 40px; padding: 8px 22px;">
                        <i class="zmdi zmdi-close"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

@section('js-code')
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
        $("#link_47").addClass("active");

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
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {});
        });

        $("#add").click(function(e) {
            $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {});
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").show();
            $("#bloc_3").html('<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="fa fa-info-circle text-info"></i> Détails facture</h4>');
            $("#bloc_t").show();
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
        });

        $("#save").click(function(e) {
            e.preventDefault();
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');

            function resetButton() {
                btn.prop('disabled', false).html('Enregister <i class="zmdi zmdi-save"></i>');
            }

            var numero_facture = $("#numero_facture").val();
            var type_sortie = $("#type_sortie").val();
            var action = $("#action").val();
            var quantite = $("#quantite").val();
            var devise = $("#devise").val();
            var taux = $("#taux").val();
            var libelle = $("#libelle").val();
            var client = $("#client_id").val();
            var type_vente_id = $("#type_vente_id").val();
            var data = $("#form_add").serialize();

            if (numero_facture.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero d\'entré');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }
            if (type_sortie.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }
            if (action.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une action');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }
            if (type_vente_id.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez le type de vente detail ou gros');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }
            if (quantite.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }
            if (quantite.trim() <= 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être supérieur à 0');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                resetButton();
                return;
            }

            $.get("{{ url('/get_prix_article') }}", {
                article_id: $("#type_sortie").val(),
                type_vente_id: $("#type_vente_id").val(),
            }, function(get_prix_article) {
                $.get("{{ url('/check_seuil_minimum') }}", {
                    article_id: type_sortie,
                    devise: devise,
                    quantite: quantite,
                    taille_lot: get_prix_article[0][1],
                    prix_unitaire: get_prix_article[0][0],
                    devise: $("#devise").val(),
                    taux: taux,
                }, function(repp) {
                    var data_rep = repp.split("__________")
                    if ((data_rep[0] == 0) && (data_rep[3] == 1)) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil minimum de cette article est de : ' +
                            data_rep[1] + ', sortie disponible : ' + data_rep[2]);
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                        resetButton();
                        return;
                    } else if ((data_rep[0] == -1) && (data_rep[3] == 1)) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le stock de cette article est vide');
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                        resetButton();
                        return;
                    } else {
                        if (client.trim().length == 0 && libelle.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le client ou le libellé');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                            resetButton();
                            return;
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "/add_achat_article",
                                data: data,
                                success: function(response) {
                                    resetButton();
                                    $("#quantite").val("");
                                    Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Achat effectué avec succès');
                                    $("#content_utilisateur").html(response);
                                    $.get("{{ url('/get_achat') }}", {}, function(response) {
                                        $("#bloc_3").html(response);
                                    });
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
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
                    data: { name: name, request: 2 },
                    sucess: function(data) { console.log('success: ' + data); }
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
                    data: { name: name, request: 2 },
                    sucess: function(data) { console.log('success: ' + data); }
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

        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                numero: $('#filterNumero').val(),
                client: $('#filterClient').val(),
                user: $('#filterUser').val(),
                dateRange: $('#filterDateRange').val(),
                datePaieRange: $('#filterDatePaieRange').val(),
                montant: $('#filterMontant').val(),
                statut: $('#filterStatut').val(),
                jour: $('#filterJour').val()
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
                $('#filterDateRange').val(filters.dateRange || '');
                $('#filterDatePaieRange').val(filters.datePaieRange || '');
                $('#filterMontant').val(filters.montant || '');
                $('#filterStatut').val(filters.statut || '');
                $('#filterJour').val(filters.jour || '');
                return true;
            }
            return false;
        }

        function filterInvoices() {
            const filterNumero = $('#filterNumero').val().toLowerCase();
            const filterClient = $('#filterClient').val().toLowerCase();
            const filterUser = $('#filterUser').val().toLowerCase();
            const filterMontant = parseFloat($('#filterMontant').val());
            const filterStatut = $('#filterStatut').val();
            const filterJourRaw = $('#filterJour').val();
            const filterJour = (filterJourRaw !== '' && filterJourRaw !== null) ? parseInt(filterJourRaw, 10) : null;

            var dateRange = $('#filterDateRange').val() || '';
            var dateDebut = null, dateFin = null;
            if (dateRange) {
                var parts = dateRange.split(' - ');
                if (parts.length === 2) {
                    function parseDMY(str) {
                        if (!str) return null;
                        var p = str.split('/');
                        if (p.length === 3) {
                            var day = p[0], month = p[1], year = p[2];
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

            var datePaieRange = $('#filterDatePaieRange').val() || '';
            var paieDateDebut = null, paieDateFin = null;
            if (datePaieRange) {
                var partsP = datePaieRange.split(' - ');
                if (partsP.length === 2) {
                    function parseDMYp(str) {
                        if (!str) return null;
                        var p = str.split('/');
                        if (p.length === 3) {
                            var day = p[0], month = p[1], year = p[2];
                            if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
                                return year + '-' + month + '-' + day;
                            }
                        }
                        return null;
                    }
                    paieDateDebut = parseDMYp(partsP[0]);
                    paieDateFin = parseDMYp(partsP[1]);
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
                const montantRaw = parseFloat($row.find('.montant-cell').data('montant')) || 0;
                const statutValue = ($row.find('.statut-cell').data('statut') || '').toString();
                const joursRetardValue = parseInt($row.data('jours-retard'), 10);

                if (filterNumero && !numeroValue.includes(filterNumero)) showRow = false;
                if (showRow && filterClient && !clientValue.includes(filterClient)) showRow = false;
                if (showRow && filterUser && !userValue.includes(filterUser)) showRow = false;
                if (showRow && !isNaN(filterMontant) && Math.abs(montantRaw - filterMontant) > 0.009) showRow = false;

                if (showRow && filterStatut && filterStatut !== '') {
                    if (filterStatut === 'paid' && statutValue !== 'paid') showRow = false;
                    if (filterStatut === 'unpaid' && statutValue !== 'unpaid') showRow = false;
                    if (filterStatut === 'partial' && statutValue !== 'partial') showRow = false;
                }

                if (showRow && filterJour !== null && !isNaN(filterJour)) {
                    if (joursRetardValue !== filterJour) showRow = false;
                }

                if (showRow && dateDebut && dateFin) {
                    var dateText = $row.find('.date-cell').text().trim();
                    var cellDate = null;
                    if (dateText) {
                        var datePart = dateText.split(' à ')[0];
                        if (datePart) {
                            var partsDate = datePart.split('/');
                            if (partsDate.length === 3) {
                                var d = partsDate[0], m = partsDate[1], y = partsDate[2];
                                if (d && m && y && d.length === 2 && m.length === 2 && y.length === 4) {
                                    cellDate = y + '-' + m + '-' + d;
                                }
                            }
                        }
                    }
                    if (cellDate) {
                        if (cellDate < dateDebut || cellDate > dateFin) showRow = false;
                    } else {
                        showRow = false;
                    }
                }

                if (showRow && paieDateDebut && paieDateFin) {
                    var paieDateValue = $row.attr('data-paie-date-ymd') ? String($row.attr('data-paie-date-ymd')) : '';
                    if (!paieDateValue) {
                        showRow = false;
                    } else {
                        if (paieDateValue < paieDateDebut || paieDateValue > paieDateFin) {
                            showRow = false;
                        }
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

            $('#invoiceCount').text(visibleCount);
            $('#totalUsd').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdf').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidUsd').text(totalPaidUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidCdf').text(totalPaidCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditUsd').text(totalCreditUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditCdf').text(totalCreditCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeUsd').text(totalBeneficeUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeCdf').text(totalBeneficeCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            if (visibleCount === 0 && (filterNumero || filterClient || filterUser || dateRange || datePaieRange || !isNaN(filterMontant) || filterStatut || (filterJour !== null && !isNaN(filterJour)))) {
                $('#msg').html('<i class="zmdi zmdi-info"></i> Aucune facture ne correspond aux critères de recherche');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 3000);
            }
        }

        function resetAllFilters() {
            $('#filterDateRange').val('');
            if ($('#filterDateRange').data('daterangepicker')) {
                $('#filterDateRange').data('daterangepicker').setStartDate(moment());
                $('#filterDateRange').data('daterangepicker').setEndDate(moment());
            }

            $('#filterDatePaieRange').val('');
            if ($('#filterDatePaieRange').data('daterangepicker')) {
                $('#filterDatePaieRange').data('daterangepicker').setStartDate(moment());
                $('#filterDatePaieRange').data('daterangepicker').setEndDate(moment());
            }

            $('#filterNumero').val('');
            $('#filterClient').val('');
            $('#filterUser').val('');
            $('#filterMontant').val('');
            $('#filterStatut').val('');
            $('#filterJour').val('');

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

        // ============================================================
        // HELPERS POUR LA MODALE PARAMÈTRES
        // ============================================================
        function formatMoney(val) {
            if (val === null || val === undefined) return '0.00';
            var n = parseFloat(val) || 0;
            return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }

        function safeParseJSON(val, fallback) {
            fallback = fallback || [];
            if (!val) return fallback;
            if (typeof val === 'object') return val;
            if (typeof val === 'string') {
                var trimmed = val.trim();
                if (trimmed === '' || trimmed === 'null' || trimmed === 'undefined') return fallback;
                try {
                    var parsed = JSON.parse(trimmed);
                    return parsed || fallback;
                } catch (e) {
                    console.error('❌ Erreur parsing JSON:', e, 'Valeur:', val);
                    return fallback;
                }
            }
            return fallback;
        }

        var currentParamFactureId = null;
        var currentParamRow = null;

        function openParamModalFromRow($row) {
            currentParamRow = $row;
            currentParamFactureId = $row.data('facture-id');

            // === INFOS GÉNÉRALES ===
            $('#param_numero').text($row.data('numero'));
            $('#param_client').text($row.data('client'));
            $('#param_user').text($row.data('user'));
            $('#param_date').text($row.data('date'));
            $('#param_table').text($row.data('table'));
            $('#param_devise').text($row.data('devise-label'));
            $('#param_taux').text($row.data('taux'));
            $('#param_mode').text($row.data('mode-paiement'));
            $('#param_client_id').text($row.data('client-id') || '-');
            $('#param_facture_id').text($row.data('facture-id'));

            // === STATUT DÉTAILLÉ ===
            var montantUSD = parseFloat($row.data('montant-usd')) || 0;
            var montantCDF = parseFloat($row.data('montant-cdf')) || 0;
            var payeUSD = parseFloat($row.data('paye-usd')) || 0;
            var payeCDF = parseFloat($row.data('paye-cdf')) || 0;
            var creditUSD = parseFloat($row.data('credit-usd')) || 0;
            var creditCDF = parseFloat($row.data('credit-cdf')) || 0;

            var statutKey, statutLabel, statutIcon, statutSub;
            if (creditUSD <= 0) {
                statutKey    = 'paid';
                statutLabel  = 'PAYÉE';
                statutIcon   = 'zmdi-check-circle';
                statutSub    = 'Facture entièrement réglée — ' + formatMoney(montantUSD) + ' USD / ' + formatMoney(montantCDF) + ' CDF';
            } else if (payeUSD > 0) {
                statutKey    = 'partiel';
                statutLabel  = 'PARTIELLE';
                statutIcon   = 'zmdi-time';
                statutSub    = 'Payé : ' + formatMoney(payeUSD) + ' USD / ' + formatMoney(payeCDF) + ' CDF  •  Reste : ' + formatMoney(creditUSD) + ' USD / ' + formatMoney(creditCDF) + ' CDF';
            } else {
                statutKey    = 'unpaid';
                statutLabel  = 'IMPAYÉE';
                statutIcon   = 'zmdi-close-circle';
                statutSub    = 'Aucun paiement enregistré — Reste dû : ' + formatMoney(creditUSD) + ' USD / ' + formatMoney(creditCDF) + ' CDF';
            }

            $('#param_statut_card').removeClass('paid unpaid partiel').addClass(statutKey);
            $('#param_statut_icon').attr('class', 'zmdi ' + statutIcon);
            $('#param_statut_label').text(statutLabel);
            $('#param_statut_sub').text(statutSub);

            // === RÉSUMÉ FINANCIER ===
            $('#param_montant_usd').text(formatMoney(montantUSD) + ' USD');
            $('#param_montant_cdf').text(formatMoney(montantCDF) + ' CDF');
            $('#param_paye_usd').text(formatMoney(payeUSD) + ' USD');
            $('#param_paye_cdf').text(formatMoney(payeCDF) + ' CDF');
            $('#param_credit_usd').text(formatMoney(creditUSD) + ' USD');
            $('#param_credit_cdf').text(formatMoney(creditCDF) + ' CDF');
            $('#param_benefice_usd').text(formatMoney($row.data('benefice-usd')) + ' USD');
            $('#param_benefice_cdf').text(formatMoney($row.data('benefice-cdf')) + ' CDF');

            // === ARTICLES ===
            var articles = safeParseJSON($row.attr('data-articles'));
            console.log('📦 Articles récupérés:', articles.length, articles);

            var tauxFacture = parseFloat($row.data('taux')) || 0;
            if (tauxFacture <= 0) tauxFacture = 1;

            var deviseFacture = $row.data('devise-label');
            var isFactureUSD = (deviseFacture === 'USD');

            // formatBoth : accepte devise propre à chaque achat
            function formatBoth(val, deviseAchat) {
                var v = parseFloat(val) || 0;
                if (deviseAchat === undefined || deviseAchat === null) deviseAchat = isFactureUSD ? 0 : 1;
                var usd, cdf;
                if (deviseAchat == 0) {
                    usd = v;
                    cdf = v * tauxFacture;
                } else {
                    cdf = v;
                    usd = (tauxFacture > 0) ? (v / tauxFacture) : 0;
                }
                return '<div class="dual-currency"><b>' + formatMoney(usd) + ' USD</b><small>' + formatMoney(cdf) + ' CDF</small></div>';
            }

            function formatBothShort(val, deviseAchat) {
                var v = parseFloat(val) || 0;
                if (deviseAchat === undefined || deviseAchat === null) deviseAchat = isFactureUSD ? 0 : 1;
                if (deviseAchat == 0) {
                    return formatMoney(v) + ' USD (' + formatMoney(v * tauxFacture) + ' CDF)';
                } else {
                    var usd = (tauxFacture > 0) ? (v / tauxFacture) : 0;
                    return formatMoney(v) + ' CDF (' + formatMoney(usd) + ' USD)';
                }
            }

            function splitBoth(val, deviseAchat) {
                var v = parseFloat(val) || 0;
                if (deviseAchat === undefined || deviseAchat === null) deviseAchat = isFactureUSD ? 0 : 1;
                if (deviseAchat == 0) {
                    return { usd: v, cdf: v * tauxFacture };
                } else {
                    return { usd: (tauxFacture > 0) ? (v / tauxFacture) : 0, cdf: v };
                }
            }

            var rowsHtml = '';
            var totalQte = 0;
            var totalPrixVente = 0;
            var totalGeneral = 0, totalAchat = 0, totalBenef = 0, totalFrais = 0, totalReduction = 0;

            if (articles.length > 0) {
                articles.forEach(function(a, idx) {
                    var deviseAchat = (a.devise_achat !== undefined && a.devise_achat !== null)
                                        ? parseInt(a.devise_achat)
                                        : (isFactureUSD ? 0 : 1);

                    // BÉNÉFICE = (total − réduction) − prix_achat_total (tous dans devise achat)
                    var totalBrutAchat = parseFloat(a.total) || 0;
                    var reductionAchat = parseFloat(a.reduction) || 0;
                    var totalNetAchat  = totalBrutAchat - reductionAchat;
                    var benef          = totalNetAchat - (parseFloat(a.prix_achat_total) || 0);

                    var sPV  = splitBoth(a.prix_unitaire, deviseAchat);
                    var sGen = splitBoth(a.total, deviseAchat);
                    var sFra = splitBoth(a.frais_credit, deviseAchat);
                    var sRed = splitBoth(a.reduction, deviseAchat);
                    var sBen = splitBoth(benef, deviseAchat);

                    totalQte       += parseFloat(a.quantite) || 0;
                    totalPrixVente += sPV.usd;
                    totalGeneral   += sGen.usd;
                    totalFrais     += sFra.usd;
                    totalReduction += sRed.usd;
                    totalBenef     += sBen.usd;

                    var fraisVal = parseFloat(a.frais_credit) || 0;
                    var redVal   = reductionAchat;

                    rowsHtml += '<tr data-achat-id="' + a.id + '" data-devise-achat="' + deviseAchat + '">';
                    rowsHtml += '<td>' + (idx + 1) + '</td>';
                    rowsHtml += '<td><b>' + a.nom + '</b> <small class="text-muted">(' + (deviseAchat == 0 ? 'USD' : 'CDF') + ')</small></td>';
                    rowsHtml += '<td>' + a.quantite + '</td>';
                    rowsHtml += '<td>' + formatBoth(a.prix_unitaire, deviseAchat) + '</td>';
                    rowsHtml += '<td>' + formatBoth(a.total, deviseAchat) + '</td>';
                    rowsHtml += '<td><input type="number" step="0.01" min="0" class="form-control param-inline-input frais-input" value="' + fraisVal.toFixed(2) + '"></td>';
                    rowsHtml += '<td><input type="number" step="0.01" min="0" class="form-control param-inline-input reduction-input" value="' + redVal.toFixed(2) + '"></td>';
                    rowsHtml += '<td class="' + (benef >= 0 ? 'text-success' : 'text-danger') + '">' + formatBoth(benef, deviseAchat) + '</td>';
                    rowsHtml += '</tr>';
                });

                function buildCard(cssClass, icon, label, value, sub) {
                    var html = '<div class="param-total-item ' + cssClass + '">';
                    html += '<div class="param-total-label">';
                    html += '<span class="param-total-dot"></span>';
                    html += '<i class="zmdi ' + icon + '"></i> ' + label;
                    html += '</div>';
                    html += '<div class="param-total-value">' + value + '</div>';
                    if (sub) {
                        html += '<div class="param-total-sub">' + sub + '</div>';
                    }
                    html += '</div>';
                    return html;
                }

                var tPV    = splitBoth(totalPrixVente, 0);
                var tGen   = splitBoth(totalGeneral, 0);
                var tFrais = splitBoth(totalFrais, 0);
                var tRed   = splitBoth(totalReduction, 0);
                var tBenef = splitBoth(totalBenef, 0);

                var cardsHtml = '';
                cardsHtml += buildCard('total-qte',   'zmdi-format-list-numbered', 'Total Qté',           totalQte, null);
                cardsHtml += buildCard('total-pv',    'zmdi-money',                'Total Prix vente',
                                        formatMoney(tPV.usd) + ' USD',
                                        formatMoney(tPV.cdf) + ' CDF');
                cardsHtml += buildCard('total-gen',   'zmdi-balance-wallet',       'Total Général',
                                        formatMoney(tGen.usd) + ' USD',
                                        formatMoney(tGen.cdf) + ' CDF');
                cardsHtml += buildCard('total-frais', 'zmdi-alert-circle',         'Total Frais crédit',
                                        formatMoney(tFrais.usd) + ' USD',
                                        formatMoney(tFrais.cdf) + ' CDF');
                cardsHtml += buildCard('total-red',   'zmdi-minus-circle',         'Total Réduction',
                                        formatMoney(tRed.usd) + ' USD',
                                        formatMoney(tRed.cdf) + ' CDF');
                cardsHtml += buildCard('total-benef', 'zmdi-trending-up',          'Total Bénéfice',
                                        formatMoney(tBenef.usd) + ' USD',
                                        formatMoney(tBenef.cdf) + ' CDF');

                $('#param_totals_grid').html(cardsHtml);
            } else {
                rowsHtml = '<tr><td colspan="8" class="text-center text-muted">Aucun article</td></tr>';
                $('#param_totals_grid').html('');
            }
            $('#param_articles_body').html(rowsHtml);
            $('#param_nb_articles').text(articles.length);

            $('#param_frais_credit_show').text(formatBothShort(totalFrais, 0));
            $('#param_reduction_show').text(formatBothShort(totalReduction, 0));
            $('#param_brut_show').text(formatBothShort(montantUSD, 0));

            $('#param_lines_msg').html('');

            // === PAIEMENTS ===
            var paiements = safeParseJSON($row.attr('data-paiements'));
            console.log('💳 Paiements récupérés:', paiements.length, paiements);

            var paiHtml = '';
            var totalRecuUSD = 0, totalRecuCDF = 0;
            var totalResteUSD = 0, totalResteCDF = 0;

            if (paiements.length > 0) {
                paiements.forEach(function(p, idx) {
                    var isUSD = (p.devise_label === 'USD');
                    var montantRecu = parseFloat(p.montant_recu) || 0;
                    var reste = parseFloat(p.reste) || 0;

                    var recuUSD, recuCDF, resteUSD, resteCDF;
                    if (isUSD) {
                        recuUSD = montantRecu;
                        recuCDF = montantRecu * tauxFacture;
                        resteUSD = reste;
                        resteCDF = reste * tauxFacture;
                    } else {
                        recuCDF = montantRecu;
                        recuUSD = (tauxFacture > 0) ? (montantRecu / tauxFacture) : 0;
                        resteCDF = reste;
                        resteUSD = (tauxFacture > 0) ? (reste / tauxFacture) : 0;
                    }

                    totalRecuUSD  += recuUSD;
                    totalRecuCDF  += recuCDF;
                    totalResteUSD += resteUSD;
                    totalResteCDF += resteCDF;

                    paiHtml += '<tr>';
                    paiHtml += '<td>' + (idx + 1) + '</td>';
                    paiHtml += '<td style="font-size:0.78rem;">' + (p.date || '-') + '</td>';
                    paiHtml += '<td><span class="badge badge-info">' + (p.mode_label || 'N/A') + '</span></td>';
                    paiHtml += '<td><span class="badge badge-' + (isUSD ? 'primary' : 'warning') + '">' + (p.devise_label || '-') + '</span></td>';
                    paiHtml += '<td><div class="dual-currency"><b class="text-primary">' + formatMoney(recuUSD) + ' USD</b><small class="text-primary">' + formatMoney(recuCDF) + ' CDF</small></div></td>';
                    paiHtml += '<td><div class="dual-currency"><b class="text-danger">' + formatMoney(resteUSD) + ' USD</b><small class="text-danger">' + formatMoney(resteCDF) + ' CDF</small></div></td>';
                    paiHtml += '</tr>';
                });

                $('#param_paiements_foot').html(
                    '<tr>' +
                        '<td colspan="4" class="text-right">TOTAUX (' + paiements.length + ' tranche' + (paiements.length > 1 ? 's' : '') + ') :</td>' +
                        '<td class="text-primary">' + formatMoney(totalRecuUSD) + ' USD (' + formatMoney(totalRecuCDF) + ' CDF)</td>' +
                        '<td class="text-danger">' + formatMoney(totalResteUSD) + ' USD (' + formatMoney(totalResteCDF) + ' CDF)</td>' +
                    '</tr>'
                );
            } else {
                paiHtml = '<tr><td colspan="6" class="text-center text-muted">Aucun paiement enregistré</td></tr>';
                $('#param_paiements_foot').html('');
            }
            $('#param_paiements_body').html(paiHtml);
        }

        $(document).ready(function() {
            $('#filterDateRange').daterangepicker({
                autoUpdateInput: false,
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
                    'Tout':              [moment('2000-01-01'), moment('2100-12-31')],
                    'Aujourd\'hui':      [moment(), moment()],
                    'Hier':              [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 derniers jours':  [moment().subtract(6, 'days'), moment()],
                    '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                    'Ce mois-ci':        [moment().startOf('month'), moment().endOf('month')],
                    'Mois dernier':      [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Cette année':       [moment().startOf('year'), moment().endOf('year')]
                }
            }, function(start, end, label) {
                if (label === 'Tout') {
                    $('#filterDateRange').val('');
                } else {
                    $('#filterDateRange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
                filterInvoices();
                saveFiltersToStorage();
            });

            $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                filterInvoices();
                saveFiltersToStorage();
            });

            $('#filterDatePaieRange').daterangepicker({
                autoUpdateInput: false,
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
                    'Tout':              [moment('2000-01-01'), moment('2100-12-31')],
                    'Aujourd\'hui':      [moment(), moment()],
                    'Hier':              [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 derniers jours':  [moment().subtract(6, 'days'), moment()],
                    '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                    'Ce mois-ci':        [moment().startOf('month'), moment().endOf('month')],
                    'Mois dernier':      [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Cette année':       [moment().startOf('year'), moment().endOf('year')]
                }
            }, function(start, end, label) {
                if (label === 'Tout') {
                    $('#filterDatePaieRange').val('');
                } else {
                    $('#filterDatePaieRange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
                filterInvoices();
                saveFiltersToStorage();
            });

            $('#filterDatePaieRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                filterInvoices();
                saveFiltersToStorage();
            });

            const totalInvoices = $('#content_utilisateur tbody tr').length;
            $('#invoiceCount').text(totalInvoices);

            const hasSaved = loadFiltersFromStorage();
            filterInvoices();

            $('#filterNumero, #filterClient, #filterUser, #filterMontant, #filterJour').on('input change', function() {
                debouncedFilter();
            });

            $('#filterStatut').on('change', function() {
                filterInvoices();
                saveFiltersToStorage();
            });

            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetAllFilters();
            });

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
                        $.get('{{ url("/get_all_facture_suivi") }}', function(html) {
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

            $(document).on('click', '.badge-tranches', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var tranchesJson = $(this).attr('data-tranches') || $(this).data('tranches');
                var numero = $(this).attr('data-numero');
                var client = $(this).attr('data-client');
                var total = $(this).attr('data-total');
                var paye = $(this).attr('data-paye');
                var reste = $(this).attr('data-reste');
                var nbTranches = $(this).attr('data-nb-tranches') || 0;
                var dateDebut = $(this).attr('data-date-debut');
                var dateFin = $(this).attr('data-date-fin');
                var joursRetard = parseInt($(this).attr('data-jours-retard')) || 0;
                var statut = $(this).attr('data-statut') || 'unpaid';

                var dureeClass = 'duree-success';
                if (joursRetard >= 7 && joursRetard <= 15) {
                    dureeClass = 'duree-warning';
                } else if (joursRetard > 15) {
                    dureeClass = 'duree-danger';
                }

                $('#modal_numero').text(numero);
                $('#modal_client').text(client);
                $('#modal_total').text(total);
                $('#modal_paye').text(paye);
                $('#modal_reste').text(reste);

                $('#modal_nb_tranches_val').text(nbTranches);

                var headerDuree = $('#modal_duree_header');
                headerDuree.removeClass('duree-success duree-warning duree-danger').addClass(dureeClass);
                $('#modal_duree_val').text(joursRetard);

                var statutHeader = $('#modal_statut_header');
                var statutVal = $('#modal_statut_val');
                var statutIcon = statutHeader.find('i');
                statutHeader.removeClass('statut-paid statut-unpaid statut-partial');

                if (statut === 'paid') {
                    statutHeader.addClass('statut-paid');
                    statutIcon.removeClass('zmdi-close-circle zmdi-time').addClass('zmdi-check-circle');
                    statutVal.text('Payée');
                } else if (statut === 'partial') {
                    statutHeader.addClass('statut-partial');
                    statutIcon.removeClass('zmdi-close-circle zmdi-check-circle').addClass('zmdi-time');
                    statutVal.text('Partielle');
                } else {
                    statutHeader.addClass('statut-unpaid');
                    statutIcon.removeClass('zmdi-time zmdi-check-circle').addClass('zmdi-close-circle');
                    statutVal.text('Impayée');
                }

                function formatDateFr(dateStr) {
                    if (!dateStr) return '-';
                    try {
                        var parts = dateStr.split(' ');
                        if (parts.length >= 1) {
                            var d = parts[0].split('-');
                            var heure = parts[1] ? parts[1].substring(0, 5) : '';
                            if (d.length === 3) {
                                return d[2] + '/' + d[1] + '/' + d[0] + (heure ? ' à ' + heure : '');
                            }
                        }
                        return dateStr;
                    } catch (err) {
                        return dateStr;
                    }
                }

                $('#modal_date_debut').text(formatDateFr(dateDebut));
                $('#modal_date_fin').text(formatDateFr(dateFin));
                $('#modal_duree_texte').html('<i class="zmdi zmdi-time"></i> ' + joursRetard + ' jour(s)');

                var tbody = $('#modal_tranches_body');
                tbody.empty();

                try {
                    var tranches = JSON.parse(tranchesJson);
                    if (tranches && tranches.length > 0) {
                        $.each(tranches, function(index, t) {
                            var tr = $('<tr>');
                            tr.append('<td style="font-weight: 600; text-align: center;">' + (index + 1) + '</td>');
                            tr.append('<td>' + t.date + '</td>');
                            tr.append('<td style="font-weight: 700; color: #10b981;">' + t.montant_recu + '</td>');
                            tr.append('<td><span class="badge-devise ' + (t.devise === 'USD' ? 'usd' : 'cdf') + '">' + t.devise + '</span></td>');
                            tr.append('<td>' + t.mode + '</td>');
                            tr.append('<td>' + (t.montant_effectif || '-') + '</td>');
                            tr.append('<td style="color: #dc2626; font-weight: 600;">' + (t.reste || '-') + '</td>');
                            tbody.append(tr);
                        });
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-center text-muted" style="padding: 20px;">Aucune tranche enregistrée pour cette facture.</td></tr>');
                    }
                } catch (err) {
                    console.error("Erreur de parsing JSON:", err);
                    console.error("Contenu brut reçu:", tranchesJson);
                    tbody.append('<tr><td colspan="7" class="text-center text-danger" style="padding: 20px;">Erreur lors du chargement des détails.</td></tr>');
                }

                $('#tranchesModal').modal('show');
            });

            // ============================================================
            // MODALE PARAMÈTRES FACTURE – OUVERTURE
            // ============================================================
            $(document).on('click', '.param-facture-btn', function(e) {
                e.preventDefault();
                var $row = $(this).closest('tr');
                $('#paramTabs a[href="#tab-info"]').tab('show');
                $('#paramFactureModal').modal('show');
                openParamModalFromRow($row);
            });

            $(document).on('click', '#param_refresh_btn', function(e) {
                e.preventDefault();
                if (currentParamRow) {
                    openParamModalFromRow(currentParamRow);
                    var btn = $(this);
                    btn.find('i').addClass('zmdi-hc-spin');
                    setTimeout(function() { btn.find('i').removeClass('zmdi-hc-spin'); }, 1000);
                }
            });

            // ============================================================
            // SAUVEGARDE DES LIGNES (FRAIS CRÉDIT + RÉDUCTION)
            // ============================================================
            $(document).on('click', '#param_btn_save_lines', function(e) {
                e.preventDefault();

                if (!currentParamFactureId) {
                    alert('Identifiant facture manquant');
                    return;
                }

                var lignes = [];
                var erreur = null;

                $('#param_articles_body tr').each(function() {
                    var $tr = $(this);
                    var achatId = $tr.data('achat-id');
                    if (!achatId) return;

                    var frais = parseFloat($tr.find('.frais-input').val()) || 0;
                    var reduction = parseFloat($tr.find('.reduction-input').val()) || 0;

                    if (frais < 0 || reduction < 0) {
                        erreur = 'Les valeurs doivent être positives';
                        return false;
                    }

                    lignes.push({
                        id: achatId,
                        frais_credit: frais,
                        reduction: reduction
                    });
                });

                if (erreur) {
                    $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> ' + erreur);
                    setTimeout(function() { $('#param_lines_msg').html(''); }, 4000);
                    return;
                }

                if (lignes.length === 0) {
                    $('#param_lines_msg').html('<i class="zmdi zmdi-info text-warning"></i> Aucune ligne à enregistrer');
                    setTimeout(function() { $('#param_lines_msg').html(''); }, 3000);
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enregistrement...');
                $('#param_lines_msg').html('<i class="zmdi zmdi-time text-info"></i> Enregistrement en cours...');

                $.ajax({
                    url: "{{ url('/apply_param_facture') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        facture_id: currentParamFactureId,
                        lignes: JSON.stringify(lignes)
                    },
                    dataType: 'json',
                    success: function(res) {
                        btn.prop('disabled', false).html('<i class="zmdi zmdi-save"></i> Enregistrer les modifications');

                        if (res.success) {
                            $('#param_lines_msg').html('<i class="zmdi zmdi-check-circle text-success"></i> ' + res.message);

                            $.get("{{ url('/get_all_facture_suivi') }}", function(html) {
                                $('#content_utilisateur').html(html);
                                saveFiltersToStorage();
                                setTimeout(function() {
                                    loadFiltersFromStorage();
                                    filterInvoices();
                                }, 100);
                            });

                            setTimeout(function() {
                                var newRow = $('#content_utilisateur tr[data-facture-id="' + currentParamFactureId + '"]');
                                if (newRow.length) {
                                    openParamModalFromRow(newRow);
                                }
                            }, 800);
                        } else {
                            $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> ' + (res.message || 'Erreur'));
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="zmdi zmdi-save"></i> Enregistrer les modifications');
                        console.error(xhr);
                        $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> Erreur de connexion');
                    }
                });
            });
        });

        window.addEventListener('beforeunload', function() {
            saveFiltersToStorage();
        });

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

                const checkPaie = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
                if (checkPaie == 1) {
                    msgFacture.innerHTML = '⚠️ Facture déjà payée';
                    msgFacture.style.color = '#ffc107';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                const detailData = await $.get("{{ url('/get_all_detail_achat_paie') }}", { facture_id: factureId });
                const {
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

                    await $.get("{{ url('/get_all_facture_suivi') }}", {}, function(response) {
                        $("#content_utilisateur").html(response);
                    });

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
    </script>
@endsection
@endsection