@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Societes;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'CATEGORIE D\'ARTICLE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
    /* ============================================================
   DESIGN PREMIUM – UNIFIÉ AVEC LES PAGES PRÉCÉDENTES
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
    #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
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

    /* Badge de compteur */
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

    .table tbody td a i.zmdi-edit {
        color: #10b981;
    }

    .table tbody td a i.zmdi-delete {
        color: #ef4444;
    }

    .table tbody td a:hover {
        background: #e0f2fe;
        transform: translateY(-2px);
    }

    .table tbody td a:hover i.zmdi-delete {
        color: #b91c1c;
    }

    .table tbody td a:hover i.zmdi-edit {
        color: #059669;
    }

    /* ========== BOUTONS PRINCIPAUX (UNIFIÉS) ========== */
    #bloc_1 button,
    #bloc_2 button,
    #bloc_3 button,
    .filters-container button,
    #liste,
    #add,
    #save,
    #annuler,
    #resetFilters,
    #importer,
    #exporter,
    .btn-primary,
    .btn-info,
    .btn-danger,
    .btn-secondary,
    .btn-dark,
    #edit_save,
    #edit_annuler {
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

    #importer {
        background: var(--rouge-gradient) !important;
        color: white !important;
    }
    #importer:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
    }

    #exporter,
    .btn-dark {
        background: #1e293b !important;
        color: white !important;
    }
    #exporter:hover,
    .btn-dark:hover {
        transform: translateY(-2px);
        background: #0f172a !important;
        box-shadow: 0 8px 18px rgba(30, 41, 59, 0.3);
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

    /* ========== MESSAGE AUCUN RÉSULTAT ========== */
    #noResultRow td {
        text-align: center;
        padding: 30px 0 !important;
        font-style: italic;
        color: #e31b23;
        font-size: 1rem;
        font-weight: bold;
    }
    #noResultRow td i {
        font-size: 28px;
        vertical-align: middle;
        margin-right: 8px;
    }

    /* ========== RESPONSIVE ========== */
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
        #liste,
        #add,
        #save,
        #edit_save,
        #annuler,
        #edit_annuler,
        #resetFilters,
        #importer,
        #exporter,
        .btn-primary,
        .btn-info,
        .btn-danger,
        .btn-dark {
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
        .category-count-badge {
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
        #liste,
        #add,
        #save,
        #edit_save,
        #annuler,
        #edit_annuler,
        #resetFilters,
        #importer,
        #exporter {
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
    }

    /* ========== MODALES IMPORT / EXPORT ========== */
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

    .modal.fade .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
    }

    .modal.fade .modal-header .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
    }

    .modal.fade .modal-header .close:hover {
        opacity: 1;
    }

    .modal.fade .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #eef2f6;
        padding: 1rem 1.5rem;
    }

    .modal.fade .modal-footer .btn {
        border-radius: 40px !important;
        padding: 6px 18px !important;
        font-weight: 600;
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
                                <a id="liste" href="" class="btn-primary btn-sm">
                                    <i class="zmdi zmdi-money"></i> Liste
                                </a>
                                &nbsp;
                                <a id="add" href="" class="btn-primary btn-sm">
                                    <i class="zmdi zmdi-money-box"></i> Ajouter
                                </a>
                                &nbsp;
                                <a id="importer" class="btn-danger btn-sm" href="">
                                    <i class="zmdi zmdi-download"></i> Importer
                                </a>
                                &nbsp;
                                <a id="exporter" class="btn-dark btn-sm" href="">
                                    <i class="zmdi zmdi zmdi-upload"></i> Exporter
                                </a>
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Catégorie d'article</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i>
                    Liste
                    <span class="category-count-badge" id="categoryCountBadge">
                        <i class="zmdi zmdi-view-list"></i> <span id="categoryCount">0</span>
                    </span>
                </h4>

                <!-- ===== FILTRES : NOM et DESCRIPTION ===== -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-search text-danger"></i> Rechercher par nom</label>
                        <input type="text" id="filterNom" class="form-control" placeholder="Nom de la catégorie...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-search text-danger"></i> Rechercher par description</label>
                        <input type="text" id="filterDescription" class="form-control" placeholder="Description...">
                    </div>
                    <div class="filter-group" style="flex: 0 0 auto; display: flex; align-items: flex-end;">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="height: 42px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <div id="content_groupe" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="display: none;" style="padding-top: 5px;padding-bottom: 5px;">Adresse</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody id="categoriesTableBody">
                                    {{! $i = 1; }}
                                    @foreach ($societes as $data)
                                    <tr id="row_{{ $data->id }}">
                                        <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                        <td class="nom-cell" data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                        <td style="display: none;" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->code }}</td>
                                        <td class="description-cell" data-description="{{ $data->description }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                            <script>
                                                $("#edit_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $.get("{{ url('/refresh_edit_societe') }}", {
                                                        societe_id : <?= $data->id ?>,
                                                    }, function(refresh_edit_societe) {
                                                        $("#bloc_1").hide();
                                                        $("#bloc_2").hide();
                                                        $("#bloc_3").show();
                                                        $("#bloc_3").html(refresh_edit_societe);
                                                    });
                                                });
                                                $("#delete_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element").html("<?= $data->nom ?>");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_sup").trigger("click");
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                    <!-- Ligne pour aucun résultat -->
                                    <tr id="noResultRow" style="display: none;">
                                        <td colspan="5">
                                            <i class="zmdi zmdi-info-outline"></i> Aucune catégorie ne correspond à vos critères.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-home text-info"></i> Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom </span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Eau)">
                            </div>
                        </div>
                         <div style="display: none;" class="col-6">
                            <div class="form-group" style="display: none;">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-map"></i> Adresse </span></label>
                                <input value="code" type="text" id="code" name="code" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Adresse (Ex : Q. lido, Av. ruwé, N° 20)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Description </span></label>
                                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description" name="description" id="description" cols="10" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button id="save" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <button id="annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg">
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
            <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
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
<!-- Modales Import / Export -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="zmdi zmdi-download"></i> Importer des catégories (Excel)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group text-right">
                    <button type="button" id="downloadModelBtn" class="btn btn-danger btn-sm" style="border-radius: 40px;">
                        <i class="zmdi zmdi-download"></i> Télécharger le modèle Excel
                    </button>
                </div>
                <form style="margin-top: 10px;" id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label><i class="zmdi zmdi-file text-danger"></i> Fichier Excel (.xlsx, .xls)</label>
                        <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx, .xls" required>
                    </div>
                </form>
                <div id="importMessage" style="display: none;" class="alert mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmImportBtn" class="btn btn-success btn-sm"><i class="zmdi zmdi-upload"></i> Importer</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Annuler</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="zmdi zmdi-upload"></i> Exporter des catégories</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <button id="exportPdfBtn" class="btn btn-primary btn-lg btn-block mb-3" style="border-radius: 40px;">
                    <i class="zmdi zmdi-file-pdf"></i> Exporter en PDF
                </button>
                <button id="exportExcelBtn" class="btn btn-success btn-lg btn-block" style="border-radius: 40px;">
                    <i class="zmdi zmdi-file-excel"></i> Exporter en Excel
                </button>
                <div style="display: none;" id="exportMessage" class="alert mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fermer</button>
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
    $(document).ready(function() {

        // =================================================================
        // 1. PERSISTANCE DES FILTRES (deux champs)
        // =================================================================
        const STORAGE_KEY = 'categories_filters';

        function saveFilters() {
            const filters = {
                filterNom: $('#filterNom').val() || '',
                filterDescription: $('#filterDescription').val() || ''
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(filters));
        }

        function loadFilters() {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (!stored) return false;
            try {
                const filters = JSON.parse(stored);
                if (filters.filterNom !== undefined) $('#filterNom').val(filters.filterNom);
                if (filters.filterDescription !== undefined) $('#filterDescription').val(filters.filterDescription);
                return true;
            } catch (e) {
                return false;
            }
        }

        // =================================================================
        // 2. FONCTION DE FILTRAGE (nom ET description) + compteur
        // =================================================================
        function filterCategories() {
            const filterNom = String($('#filterNom').val() || '').toLowerCase().trim();
            const filterDescription = String($('#filterDescription').val() || '').toLowerCase().trim();

            let visibleCount = 0;
            let newIndex = 1;

            $('#noResultRow').hide();

            $('#categoriesTableBody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                const nomValue = String($row.find('.nom-cell').data('nom') || '').toLowerCase();
                const descriptionValue = String($row.find('.description-cell').data('description') || '').toLowerCase();

                let showRow = true;

                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                if (showRow && filterDescription && !descriptionValue.includes(filterDescription)) {
                    showRow = false;
                }

                if (showRow) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            $('#categoryCount').text(visibleCount);

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        // =================================================================
        // 3. ÉVÉNEMENTS
        // =================================================================
        $('#filterNom, #filterDescription').on('keyup', function() {
            saveFilters();
            filterCategories();
        });

        $('#resetFilters').on('click', function() {
            localStorage.removeItem(STORAGE_KEY);
            $('#filterNom').val('');
            $('#filterDescription').val('');
            saveFilters();
            filterCategories();

            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        });

        // =================================================================
        // 4. CHARGEMENT DES FILTRES PERSISTANTS ET APPLICATION
        // =================================================================
        loadFilters();
        filterCategories();

        // =================================================================
        // 5. AUTRES SCRIPTS EXISTANTS (conservés et adaptés)
        // =================================================================
        $("#link_20").addClass("active");

        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzone-upload").trigger("click");
        });

        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
            filterCategories();
        });

        $("#add").click(function(e) {
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
        });

        $("#annuler").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
            filterCategories();
        });

        $("#save").click(function(e) {
            e.preventDefault();
            var nom = $("#nom").val();
            var code = $("#code").val();
            var data = $("#form_add").serialize();
            if (nom.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de la catégorie');
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                $.ajax({
                    type: "POST",
                    url: "/check_societe",
                    data: data,
                    success: function(response) {
                        if (response == 1) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette catégorie existe déjà');
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        } else {
                            $("#save").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "/add_societe",
                                data: data,
                                success: function(response) {
                                    $("#save").attr("disabled", false);
                                    $("#nom").val("");
                                    $("#description").val("");
                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Catégorie ajoutée avec succès');
                                    $("#content_groupe").html(response);
                                    filterCategories();
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                }
                            });
                        }
                    }
                });
            }
        });

        $("#oui").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_delete_societe') }}", {
                id: id,
            }, function(refresh_editverbalisateur) {
                $("#content_groupe").html(refresh_editverbalisateur);
                filterCategories();
                $("#non").trigger("click");
            });
        });

        // ===== GESTIONNAIRES IMPORT/EXPORT =====
        $("#importer").click(function(e) {
            e.preventDefault();
            $("#importModal").modal('show');
        });
        $("#exporter").click(function(e) {
            e.preventDefault();
            $("#exportModal").modal('show');
        });
        $("#downloadModelBtn").click(function() {
            window.location.href = "{{ url('') }}" + "/Modele d'importation des catégories.xlsx";
        });

        // ========== EXPORT PDF ==========
        $("#exportPdfBtn").click(function(e) {
            e.preventDefault();
            var $btn = $(this);
            var originalHtml = $btn.html();

            var filters = {
                _token: '{{ csrf_token() }}'
            };

            var iframeId = 'iframe_download_' + Date.now();
            var $iframe = $('<iframe>', {
                name: iframeId,
                style: 'display: none'
            }).appendTo('body');

            var $form = $('<form>', {
                method: 'GET',
                action: "{{ url('/export_categories_pdf') }}",
                target: iframeId,
                style: 'display: none'
            }).appendTo('body');

            $.each(filters, function(key, value) {
                if (value !== null && value !== undefined && value !== '') {
                    $('<input>', {
                        type: 'hidden',
                        name: key,
                        value: value
                    }).appendTo($form);
                }
            });

            $btn.prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Téléchargement...');
            $form.submit();

            setTimeout(function() {
                $form.remove();
                $iframe.remove();
                $btn.prop('disabled', false).html(originalHtml);
            }, 3000);
        });

        // ========== EXPORT EXCEL ==========
        $("#exportExcelBtn").click(function(e) {
            e.preventDefault();
            var $btn = $(this);
            var originalHtml = $btn.html();
            var $msgContainer = $("#exportMessage");
            $msgContainer.removeClass('alert-success alert-danger').html('').hide();

            var filters = {
                _token: '{{ csrf_token() }}'
            };

            $btn.prop('disabled', true).html(
                '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Génération de l\'Excel...');

            $.ajax({
                url: "{{ url('/export_excel_categorie') }}",
                type: "GET",
                data: filters,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob, status, xhr) {
                    var contentType = xhr.getResponseHeader('content-type');
                    if (contentType && contentType.includes(
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        )) {
                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);
                        link.href = url;
                        link.download = 'Mes catégories.xlsx';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);
                    } else {
                        var reader = new FileReader();
                        reader.onload = function() {
                            var errorMsg = reader.result ||
                                "Erreur lors de la génération du fichier Excel";
                            $msgContainer.removeClass('alert-success').addClass('alert-danger')
                                .html('<i class="zmdi zmdi-close-circle"></i> ' + errorMsg)
                                .show();
                        };
                        reader.readAsText(blob);
                    }
                },
                error: function(xhr) {
                    var errorMessage = "Erreur lors de l'export Excel.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }
                    $msgContainer.removeClass('alert-success').addClass('alert-danger')
                        .html('<i class="zmdi zmdi-close-circle"></i> ' + errorMessage).show();
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        $("#confirmImportBtn").click(function() {
            var formData = new FormData($("#importForm")[0]);
            $.ajax({
                url: "{{ url('/import_excel_categorie') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#confirmImportBtn").prop('disabled', true).html(
                        '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Importation...');
                },
                success: function(response) {
                    $.get("{{ url('/get_all_categorie') }}", {}, function(
                    refresh_editutilisateur) {
                        $("#content_groupe").html(refresh_editutilisateur);
                        filterCategories();
                    });
                    $("#importMessage").removeClass('alert-danger').addClass('alert-success')
                        .html(
                            '<i class="zmdi zmdi-check-circle"></i> ' + response["message"])
                        .show();
                    $("#confirmImportBtn").prop('disabled', false).html(
                        '<i class="zmdi zmdi-download"></i> Importer');
                    setTimeout(function() {
                        $("#importModal").modal('hide');
                        $("#importMessage").hide();
                    }, 15000);
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Erreur lors de l\'import';
                    $("#importMessage").removeClass('alert-success').addClass('alert-danger')
                        .html('<i class="zmdi zmdi-close-circle"></i> ' + errorMsg).show();
                    $("#confirmImportBtn").prop('disabled', false).html(
                        '<i class="zmdi zmdi-upload"></i> Importer');
                }
            });
        });

    }); // fin document ready
</script>
@endsection
@endsection
