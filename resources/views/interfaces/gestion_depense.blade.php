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
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Mesures;
use App\Models\Activites;
use App\Models\Type_frais;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'GESTION DEPENSE')
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

        .article-count-badge {
            background-color: #e31b23;
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

        /* --- Badges de totalisation --- */
        .invoice-count-badge {
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
            box-shadow: var(--shadow-light);
        }

        /* ========== TABLEAU ========== */
        .table-responsive {
            border-radius: var(--border-radius-lg);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            min-width: 800px;
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

        .table tbody td a i.zmdi-file-text {
            color: #10b981;
        }

        .table tbody td a i.zmdi-close-circle {
            color: #ef4444;
        }

        .table tbody td a:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }

        /* ========== BOUTONS PRINCIPAUX (UNIFIÉS) ========== */
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
        #importer,
        #exporter,
        .btn-primary,
        .btn-info,
        .btn-danger,
        .btn-secondary,
        .btn-dark {
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

        /* ========== BOUTON EXPORTER EN VERT SUCCÈS ========== */
        #exporter {
            background: var(--vert-gradient) !important;
            color: white !important;
        }
        #exporter:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #059669, #047857) !important;
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
        }

        .btn-dark {
            background: #1e293b !important;
            color: white !important;
        }
        .btn-dark:hover {
            transform: translateY(-2px);
            background: #0f172a !important;
            box-shadow: 0 8px 18px rgba(30, 41, 59, 0.3);
        }

        /* Boutons désactivés */
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

        /* ========== RESPONSIVE ========== */
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
            .article-count-badge,
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
                                    if (
                                        Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                            ->get()
                                            ->count() != 0
                                    ) {
                                        $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                    <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
                                    <a id="add" class="btn-primary btn-sm" href="">
                                        <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                    </a>
                                    &nbsp;
                                    <a id="importer" class="btn-danger btn-sm" href="">
                                        <i class="zmdi zmdi-download"></i> Importer
                                    </a>
                                    &nbsp;
                                    <a id="exporter" class="btn-sm" href="">
                                        <i class="zmdi zmdi-upload"></i> Exporter
                                    </a>
                                    <?php } else { ?>
                                    <a id="add_r" href="">
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion dépense</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-book text-info"></i>
                        Liste
                        <span class="article-count-badge" id="articleCountBadge">
                            <i class="zmdi zmdi-view-list"></i> <span id="articleCount">0</span>
                        </span>
                    </h4>

                    <!-- Badges de totalisation USD / CDF -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0a192f, #1e3a5f);">
                            <i class="zmdi zmdi-view-list"></i> Dépenses : <span id="depenseCount">0</span>
                        </span>
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0f4c5f, #1e6f5c);">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsdDepense">0,00</span> $
                        </span>
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdfDepense">0,00</span> Fc
                        </span>
                    </div>

                    <!-- SECTION FILTRES AVEC DATE RANGE -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                            <select id="filterUser" class="form-control">
                                <option value="all">Tous les utilisateurs</option>
                                @php
                                    $uniqueUsers = [];
                                @endphp
                                @foreach ($depenses->unique('user_id') as $dep)
                                    @php
                                        $uid = $dep->user_id;
                                        if (!in_array($uid, $uniqueUsers)) {
                                            $uniqueUsers[] = $uid;
                                            $userName = ($uid == Auth::user()->id) ? 'Vous' : (User::find($uid)->name ?? 'N/A');
                                        }
                                    @endphp
                                    <option value="{{ $uid }}">{{ $userName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> Type de dépense</label>
                            <select id="filterTypeDepense" class="form-control">
                                <option value="all">Tous les types</option>
                                @foreach ($type_frais as $type)
                                    <option value="type_{{ $type->id }}">{{ $type->nom }}</option>
                                @endforeach
                                <option value="none">Aucun type (libellé libre)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                            <input type="text" id="filterDateRange" class="form-control"
                                   placeholder="Sélectionner une période" value="">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-search text-danger"></i> N° PIECE Jointe</label>
                            <input type="text" id="filterSearch" class="form-control" placeholder="N° pièce, libellé...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-money text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="≥ montant" step="0.01" min="0">
                        </div>
                        <div class="filter-group" style="flex: 0 0 auto; display: flex; align-items: flex-end;">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="height: 42px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <div id="content_utilisateur" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="depensesTable">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Type de dépense</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N ° pièce</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody id="depensesTableBody">
                                        {{ !($i = 1) }}
                                        @foreach ($depenses as $data)
                                        @php
                                            $montant = $data->montant;
                                            $taux = $data->taux ?? 1;
                                            $devise = $data->devise;
                                            if ($devise == 0) {
                                                $montant_usd = $montant;
                                                $montant_cdf = $montant * $taux;
                                            } else {
                                                $montant_cdf = $montant;
                                                $montant_usd = $montant / $taux;
                                            }
                                        @endphp
                                        <tr data-montant-usd="{{ $montant_usd }}" data-montant-cdf="{{ $montant_cdf }}"
                                            data-libelle="{{ $data->type_depense_id ? (Type_frais::find($data->type_depense_id)->nom ?? '') : ($data->libelle ?? '') }}">
                                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                            <td class="user-cell" data-user-id="{{ $data->user_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if ($data->user_id == Auth::user()->id)
                                                    Vous
                                                @else
                                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td class="type-cell" data-type-id="{{ $data->type_depense_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if ($data->type_depense_id != 0 && $data->type_depense_id != null)
                                                    {{ Type_frais::where('id', $data->type_depense_id)->first()['nom'] ?? 'N/A' }}
                                                @else
                                                    {{ $data->libelle ?: 'Sans type' }}
                                                @endif
                                            </td>
                                            <td class="piece-cell" data-n-piece="{{ $data->n_piece }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ $data->n_piece ?: '-' }}
                                            </td>
                                            <td class="montant-cell" data-montant="{{ $data->montant }}" data-devise="{{ $data->devise }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                <?php
                                                if ($data->devise == 0) {
                                                    echo number_format($data->montant, 2, ',', ' ') . 'USD';
                                                } else {
                                                    echo number_format($data->montant, 2, ',', ' ') . 'CDF';
                                                }
                                                ?>
                                            </td>
                                            <td class="date-cell"
                                                data-date="{{ \Carbon\Carbon::createFromFormat('d/m/Y', $data->date_depense)->format('Y-m-d') }}"
                                                style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ $data->date_depense }}
                                            </td>
                                            <td class="text-center" style="padding-top: 5px;padding-bottom: 5px;">
                                                <?php if(strlen(trim($data->preuve_de_sortie)) > 0){ ?>
                                                    <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-file-text text-success"></i></a> &nbsp;
                                                <?php }else{ ?>
                                                    <a id="#" href="#"><i class="zmdi zmdi-close-circle text-danger"></i></a> &nbsp;
                                                <?php }?>
                                            </td>
                                            <script>
                                                $("#edit_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    var url = "<?= $data->preuve_de_sortie ?>";
                                                    const extension = url.split('.').pop().toLowerCase();
                                                    const typeMap = {
                                                        'png': 'image', 'jpg': 'image', 'jpeg': 'image', 'gif': 'image', 'bmp': 'image', 'webp': 'image', 'svg': 'image',
                                                        'txt': 'texte', 'pdf': 'pdf', 'doc': 'word', 'docx': 'word', 'odt': 'texte',
                                                        'xls': 'excel', 'xlsx': 'excel', 'ods': 'tableur',
                                                        'ppt': 'powerpoint', 'pptx': 'powerpoint', 'odp': 'présentation',
                                                        'zip': 'archive', 'rar': 'archive', '7z': 'archive',
                                                        'mp3': 'audio', 'wav': 'audio', 'mp4': 'vidéo', 'avi': 'vidéo', 'mov': 'vidéo',
                                                        'html': 'page web', 'css': 'feuille de style', 'js': 'script JavaScript', 'json': 'données JSON', 'xml': 'données XML'
                                                    };
                                                    var type = typeMap[extension] || 'type inconnu';
                                                    if (type == "image") {
                                                        $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop());
                                                        $("#fichier_content").html('<img src="' + url + '" class="img-fluid" style="max-height:100%;width: 100%;" />');
                                                        $("#btn_detail_fichier").trigger("click");
                                                    }
                                                    if (type == "excel") {
                                                        var fileUrl = "{{ asset('') }}" + url;
                                                        fileUrl = fileUrl.replace(/([^:]\/)\/+/g, "$1");
                                                        $("#excelModalTitle").html("Visualisation : " + url.split('/').pop());
                                                        $("#modalExcelViewer").modal({ backdrop: 'static', keyboard: false, show: true });
                                                        var container = document.getElementById("excelViewerContainer");
                                                        container.innerHTML = '<div style="text-align:center; padding:50px;"><i class="zmdi zmdi-spinner zmdi-hc-spin" style="font-size: 40px;"></i><br>Chargement du fichier Excel...</div>';
                                                        fetch(fileUrl)
                                                            .then(response => { if (!response.ok) throw new Error("HTTP " + response.status); return response.arrayBuffer(); })
                                                            .then(arrayBuffer => {
                                                                var workbook = XLSX.read(arrayBuffer, { type: 'array' });
                                                                container.innerHTML = '';
                                                                new ExcelViewer("#excelViewerContainer", workbook, url.split('/').pop());
                                                            })
                                                            .catch(err => {
                                                                container.innerHTML = `<div class="alert alert-danger" style="margin: 20px;"><i class="zmdi zmdi-alert-circle"></i> Erreur de chargement : ${err.message}<br>Vérifiez que le fichier existe et est accessible.</div>`;
                                                                console.error(err);
                                                            });
                                                        class ExcelViewer {
                                                            constructor(container, workbook, title = "Classeur") {
                                                                this.container = typeof container === 'string' ? document.querySelector(container) : container;
                                                                if (!this.container) throw new Error("Conteneur introuvable");
                                                                this.workbook = workbook;
                                                                this.title = title;
                                                                this.currentSheetName = null;
                                                                this.buildUI();
                                                                this.initSheets();
                                                            }
                                                            buildUI() {
                                                                this.container.innerHTML = `<div class="excel-viewer" style="padding: 10px;"><h5 style="color: #1e466e; margin-bottom: 15px;display:none;"><i class="zmdi zmdi-chart"></i> ${this.title}</h5><div class="excel-sheet-selector" style="display: none; margin-bottom: 10px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px;"><label style="margin-right: 10px;padding-top:8px;color:black;"><i class="zmdi zmdi-tab"></i> Feuille :</label><select class="excel-sheet-select" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc;"></select></div><div class="excel-info" style="background: #e3f2fd; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; font-size: 13px;"></div><div class="excel-search-bar" style="display: flex; gap: 8px; margin-bottom: 10px;"><input type="text" class="excel-search-input" placeholder="Rechercher dans le tableau..." style="flex: 2; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;"><button class="excel-search-btn btn btn-info btn-sm" style="background: #17a2b8; border: none;"><i class="zmdi zmdi-search"></i> Rechercher</button><button class="excel-clear-btn btn btn-secondary btn-sm" style="background: #6c757d; border: none;"><i class="zmdi zmdi-close"></i> Effacer</button></div><div class="excel-table-wrapper" style="overflow: auto; max-height: 500px; border: 1px solid #dee2e6; border-radius: 8px; background: white;"></div><div class="excel-selected-cell" style="margin-top: 12px; padding: 10px; background: #f1f5f9; border-radius: 8px; display: flex; gap: 10px; align-items: center;"><label style="margin: 0; font-weight: bold; color: #1e466e;"><i class="zmdi zmdi-info"></i> Cellule sélectionnée :</label><input type="text" class="excel-selected-value" readonly style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; font-family: monospace;"></div></div>`;
                                                                this.infoDiv = this.container.querySelector('.excel-info');
                                                                this.sheetSelectorDiv = this.container.querySelector('.excel-sheet-selector');
                                                                this.sheetSelect = this.container.querySelector('.excel-sheet-select');
                                                                this.searchInput = this.container.querySelector('.excel-search-input');
                                                                this.searchBtn = this.container.querySelector('.excel-search-btn');
                                                                this.clearBtn = this.container.querySelector('.excel-clear-btn');
                                                                this.tableWrapper = this.container.querySelector('.excel-table-wrapper');
                                                                this.selectedCellInput = this.container.querySelector('.excel-selected-value');
                                                                this.searchBtn.addEventListener('click', () => this.highlightSearch());
                                                                this.clearBtn.addEventListener('click', () => this.clearHighlights());
                                                                this.sheetSelect.addEventListener('change', (e) => { this.currentSheetName = e.target.value; this.renderCurrentSheet(); });
                                                            }
                                                            initSheets() {
                                                                const sheetNames = this.workbook.SheetNames;
                                                                if (sheetNames.length === 0) { this.showMessage("Aucune feuille trouvée", true); return; }
                                                                if (sheetNames.length > 1) { this.sheetSelectorDiv.style.display = 'flex'; this.sheetSelect.innerHTML = sheetNames.map(n => `<option value="${n}">${n}</option>`).join(''); }
                                                                this.currentSheetName = sheetNames[0];
                                                                this.renderCurrentSheet();
                                                            }
                                                            renderCurrentSheet() {
                                                                if (!this.workbook || !this.currentSheetName) return;
                                                                const sheet = this.workbook.Sheets[this.currentSheetName];
                                                                const html = XLSX.utils.sheet_to_html(sheet, { editable: false });
                                                                this.tableWrapper.innerHTML = `<div style="overflow-x: auto;">${html}</div>`;
                                                                const table = this.tableWrapper.querySelector('table');
                                                                if (table) {
                                                                    table.style.width = '100%';
                                                                    table.style.borderCollapse = 'collapse';
                                                                    table.style.fontSize = '13px';
                                                                    table.querySelectorAll('th').forEach(th => { th.style.background = '#2c7da0'; th.style.color = 'white'; th.style.padding = '8px'; th.style.position = 'sticky'; th.style.top = '0'; th.style.zIndex = '10'; });
                                                                    table.querySelectorAll('td').forEach(td => { td.style.border = '1px solid #d4dce6'; td.style.padding = '6px 8px'; td.style.cursor = 'pointer'; td.addEventListener('click', (e) => { e.stopPropagation(); this.onCellClick(td); }); });
                                                                    table.querySelectorAll('tr:nth-child(even) td').forEach(td => { td.style.backgroundColor = '#fafcff'; });
                                                                }
                                                                this.selectedCellInput.value = '';
                                                                this.showMessage(`📄 Feuille : ${this.currentSheetName}`);
                                                            }
                                                            onCellClick(cell) { if (cell.classList.contains('cell-highlight')) { cell.classList.remove('cell-highlight'); } else { cell.classList.remove('cell-search'); cell.classList.add('cell-highlight'); } const value = cell.innerText || cell.textContent; this.selectedCellInput.value = value; this.showMessage(`📌 Contenu : "${value.substring(0, 80)}${value.length > 80 ? '…' : ''}"`); }
                                                            clearHighlights() { const cells = this.tableWrapper.querySelectorAll('td, th'); cells.forEach(cell => cell.classList.remove('cell-highlight', 'cell-search')); this.showMessage("✅ Entourages effacés"); }
                                                            highlightSearch() { const text = this.searchInput.value.trim(); if (!text) { this.showMessage("⚠️ Entrez un texte à rechercher", true); return; } this.clearHighlights(); const cells = this.tableWrapper.querySelectorAll('td'); let found = []; cells.forEach(cell => { if (cell.innerText.toLowerCase().includes(text.toLowerCase())) { cell.classList.add('cell-search'); found.push(cell); } }); if (found.length === 0) { this.showMessage(`❌ Aucune cellule ne contient "${text}"`, true); } else { this.showMessage(`🔍 ${found.length} cellule(s) trouvée(s) pour "${text}".`); this.scrollToCell(found[0]); } }
                                                            scrollToCell(cell) { const wrapper = this.tableWrapper; const cellRect = cell.getBoundingClientRect(); const wrapperRect = wrapper.getBoundingClientRect(); const scrollTop = wrapper.scrollTop + cellRect.top - wrapperRect.top - wrapperRect.height / 2 + cellRect.height / 2; wrapper.scrollTo({ top: scrollTop, behavior: 'smooth' }); cell.classList.add('first-found'); setTimeout(() => cell.classList.remove('first-found'), 800); }
                                                            showMessage(msg, isError = false) { this.infoDiv.innerHTML = msg; this.infoDiv.style.backgroundColor = isError ? '#f8d7da' : '#e3f2fd'; this.infoDiv.style.color = isError ? '#721c24' : '#0c5460'; if (!isError) { setTimeout(() => { if (this.infoDiv.style.backgroundColor !== '#f8d7da') { this.infoDiv.style.backgroundColor = '#e3f2fd'; this.infoDiv.style.color = '#0c5460'; } }, 3000); } }
                                                        }
                                                    }
                                                    if (type == "word") { var all_url = "<?= asset('') ?>" + url; $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop()); document.getElementById("fichier_content").innerHTML = '<iframe src="https://docs.google.com/viewer/viewer?url=' + encodeURIComponent(all_url) + '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>'; $("#btn_detail_fichier").trigger("click"); }
                                                    if (type == "pdf") { var all_url = "<?= asset('') ?>" + url; $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop()); document.getElementById("fichier_content").innerHTML = '<iframe src="https://docs.google.com/viewer/viewer?url=' + encodeURIComponent(all_url) + '&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>'; $("#btn_detail_fichier").trigger("click"); }
                                                    if (type == "texte") { var all_url = "<?= asset('') ?>" + url; $("#titre_modal_fichier").html("Visualisation : " + url.split('/').pop()); document.getElementById("fichier_content").innerHTML = '<iframe src="' + all_url + '" style="width:100%; height:100%;" frameborder="0"></iframe>'; $("#btn_detail_fichier").trigger("click"); }
                                                });
                                            </script>
                                        </tr>
                                            {{ !$i++ }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bloc_2" style="margin-top: 12px;display: none;padding-bottom: 100px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                            class="zmdi zmdi-plus-circle text-info"></i>
                        Nouvelle dépense</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <div id="content_utilisateur" class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">DATE</th>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">N°PIECES</th>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Montant</th>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Taux</th>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Type de dépense</th>
                                                <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Libellé</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    <input id="date_operation" name="date_operation" type="text"
                                                        class="input-mask" data-mask="00/00/0000"
                                                        style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;"
                                                        placeholder="" value="<?= date('d/m/Y') ?>">
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    <input class="form-control" id="n_piece" name="n_piece"
                                                        type="text"
                                                        style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;"
                                                        placeholder="N° pièce" value="">
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px; white-space: nowrap;">
                                                    <div style="display: flex; flex-wrap: nowrap; align-items: center;">
                                                        <input id="montant" name="montant" type="text"
                                                            class="input-mask"
                                                            data-mask="00000000000000000000000000000000000000"
                                                            style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 120px; height: 35px; border-radius: 8px 0 0 8px; text-align: right; margin: 0;"
                                                            placeholder="0" value="0">
                                                        <select class="form-control" id="devise" name="devise"
                                                            style="height: 35px; width: 70px; border-radius: 0 8px 8px 0; margin: 0; border-left: none; background: #f5f5f5; cursor: pointer;">
                                                            <option value="">Devise</option>
                                                            <option value="0">USD</option>
                                                            <option value="1">CDF</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px; white-space: nowrap;">
                                                    <div style="display: flex; flex-wrap: nowrap; align-items: center;">
                                                        <input id="taux" name="taux" type="text"
                                                            class="input-mask"
                                                            data-mask="00000000000000000000000000000000000000"
                                                            style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 120px; height: 35px; border-radius: 8px 0 0 8px; text-align: right; margin: 0;"
                                                            placeholder="0" value="0">
                                                    </div>
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px; white-space: nowrap;">
                                                    <div style="display: flex; flex-wrap: nowrap; align-items: center;">
                                                        <select class="form-control" id="type_depense_id"
                                                            name="type_depense_id"
                                                            style="height: 35px; width: 70px; border-radius: 0 8px 8px 0; margin: 0; border-left: none; background: #f5f5f5; cursor: pointer;">
                                                            <option value="">Aucune</option>
                                                            @foreach ($type_frais as $data)
                                                                <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                    <input class="form-control" id="libelle" name="libelle"
                                                        type="text"
                                                        style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 250px;"
                                                        placeholder="Libellé" value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div style="display: none;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Numero operaton</span></label>
                                    <select id="numero_facture" name="numero_facture" class="select2"
                                        data-placeholder="Selectionnez un type de sortie">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Il s'agit de quelle entré ?</span></label>
                                    <select id="type_sortie" name="type_sortie" class="select2"
                                        data-placeholder="Selectionnez un type de d'entré">
                                        <option selected value="">Selectionnez un type de sortie</option>
                                        @foreach ($type_frais as $data)
                                            <option selected value="{{ $data->id }}"><?= $data->nom ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;display: none;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Prix unitaire </span></label>
                                    <input id="prix_unitaire" name="prix_unitaire" type="text"
                                        class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Prix unitaire (Ex : 10)" value="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Quantité </span></label>
                                    <input id="quantite" name="quantite" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Quantité (Ex : 10)" value="1">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;display: none;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> devise </span></label>
                                    <select id="devise" name="devise" class="select2"
                                        data-placeholder="Selectionnez une devise">
                                        <option selected class="form-control" value="">Selectionnez une devise</option>
                                        <option selected class="form-control" value="0"> $</option>
                                        <option class="form-control" value="1"> Fc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-money"></i> Taux</span></label>
                                    <input id="taux" name="taux" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Taux (Ex : 10)" value="2800">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6"></div>
                        </div>
                    </form>
                    <div style="margin-top: 30px;" class="row">
                        <div class="col-12">
                            <label class="text-info" style="font-weight: bold;"><i
                                    class="zmdi zmdi-info text-danger"></i> Déposez
                                votre attache de dépense ici</span></label>
                            <form method="post"
                                style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;"
                                action="{{ route('upload_fichier_sortie') }}" class="dropzone" id="dropzonewidget">
                                @csrf
                                <input type="hidden" id="n_s" name="n_s" value="">
                            </form>
                        </div>
                    </div>
                    <br>
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
                                <button id="save" class="btn btn-info btn-sm">Enregistrer <i
                                        class="zmdi zmdi-save"></i></button>
                                <?php } else { ?>
                                <button id="save_r" class="btn btn-info btn-sm">Enregistrer <i
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
                <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
            </div>
        </div>
    </section>

    <span id="data_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        supprimez ? </h5>
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

    <span id="data_frais_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#c_frais" id="btn_frais">Sup</button>
    <div class="modal fade" id="c_frais" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        approuvez ? </h5>
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

    <!-- ========== MODALES IMPORT / EXPORT ========== -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                    <h5 class="modal-title text-white"><i class="zmdi zmdi-download"></i> Importer des dépenses (Excel)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-right">
                        <button type="button" id="downloadModelBtn" class="btn btn-danger btn-sm"
                            style="border-radius: 40px;">
                            <i class="zmdi zmdi-download"></i> Télécharger le modèle Excel
                        </button>
                    </div>
                    <form style="margin-top: 10px;" id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><i class="zmdi zmdi-file text-danger"></i> Fichier Excel (.xlsx, .xls)</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control"
                                accept=".xlsx, .xls" required>
                        </div>
                    </form>
                    <div id="importMessage" style="display: none;" class="alert mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmImportBtn" class="btn btn-success btn-sm"><i
                            class="zmdi zmdi-upload"></i> Importer</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="zmdi zmdi-close-circle"></i> Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                    <h5 class="modal-title text-white"><i class="zmdi zmdi-upload"></i> Exporter les dépenses</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span
                            aria-hidden="true">&times;</span></button>
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

    <!-- Librairies pour le date range picker -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {

            // =================================================================
            // 1. INITIALISATION DU DATE RANGE PICKER
            // =================================================================
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
                saveFilters();
                filterDepenses();
            });

            $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                saveFilters();
                filterDepenses();
            });

            // =================================================================
            // 2. PERSISTANCE DES FILTRES (localStorage) - SAUF LA PÉRIODE
            // =================================================================
            const STORAGE_KEY = 'depenses_filters';

            function saveFilters() {
                const filters = {
                    filterUser: $('#filterUser').val() || 'all',
                    filterTypeDepense: $('#filterTypeDepense').val() || 'all',
                    filterSearch: $('#filterSearch').val() || '',
                    filterMontant: $('#filterMontant').val() || ''
                };
                localStorage.setItem(STORAGE_KEY, JSON.stringify(filters));
            }

            function loadFilters() {
                const stored = localStorage.getItem(STORAGE_KEY);
                if (!stored) return false;
                try {
                    const filters = JSON.parse(stored);
                    if (filters.filterUser !== undefined) $('#filterUser').val(filters.filterUser);
                    if (filters.filterTypeDepense !== undefined) $('#filterTypeDepense').val(filters.filterTypeDepense);
                    if (filters.filterSearch !== undefined) $('#filterSearch').val(filters.filterSearch);
                    if (filters.filterMontant !== undefined) $('#filterMontant').val(filters.filterMontant);
                    return true;
                } catch (e) {
                    console.warn('Erreur chargement filtres :', e);
                    return false;
                }
            }

            // =================================================================
            // 3. FONCTION DE FILTRAGE (MISE À JOUR AVEC TOTAUX)
            // =================================================================
            let filterTimeout = null;

            function filterDepenses() {
                const filterUser = $('#filterUser').val() || 'all';
                const filterTypeDepense = $('#filterTypeDepense').val() || 'all';
                const filterSearch = String($('#filterSearch').val() || '').toLowerCase().trim();
                const filterMontant = parseFloat($('#filterMontant').val()) || null;

                const dateRange = $('#filterDateRange').val() || '';
                let dateDebut = null, dateFin = null;
                if (dateRange) {
                    const parts = dateRange.split(' - ');
                    if (parts.length === 2) {
                        const parseDMY = (str) => {
                            if (!str) return null;
                            const parts = str.split('/');
                            if (parts.length === 3) {
                                const day = parts[0];
                                const month = parts[1];
                                const year = parts[2];
                                if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
                                    return year + '-' + month + '-' + day;
                                }
                            }
                            return null;
                        };
                        dateDebut = parseDMY(parts[0]);
                        dateFin = parseDMY(parts[1]);
                    }
                }

                let visibleCount = 0;
                let newIndex = 1;
                let totalUSD = 0, totalCDF = 0;

                $('#depensesTableBody tr').each(function() {
                    const $row = $(this);
                    let showRow = true;

                    const userId = $row.find('.user-cell').data('user-id');
                    const typeId = $row.find('.type-cell').data('type-id');
                    const dateValue = $row.find('.date-cell').data('date') || '';
                    const montantValue = parseFloat($row.find('.montant-cell').data('montant')) || 0;
                    const nPiece = String($row.find('.piece-cell').data('n-piece') || '').toLowerCase();
                    const libelle = String($row.data('libelle') || '').toLowerCase();
                    const typeDepenseNom = String($row.find('.type-cell').text() || '').toLowerCase();

                    if (filterUser !== 'all' && userId && String(userId) !== filterUser) showRow = false;
                    if (showRow && filterTypeDepense !== 'all') {
                        if (filterTypeDepense === 'none') {
                            if (typeId !== 0 && typeId !== null && typeId !== '') showRow = false;
                        } else if (filterTypeDepense.startsWith('type_')) {
                            const targetTypeId = filterTypeDepense.replace('type_', '');
                            if (typeId && String(typeId) !== targetTypeId) showRow = false;
                        }
                    }
                    if (showRow && dateDebut && dateValue < dateDebut) showRow = false;
                    if (showRow && dateFin && dateValue > dateFin) showRow = false;
                    if (showRow && filterSearch) {
                        const searchIn = (nPiece + ' ' + libelle + ' ' + typeDepenseNom);
                        if (!searchIn.includes(filterSearch)) showRow = false;
                    }
                    if (showRow && filterMontant !== null && !isNaN(filterMontant) && montantValue < filterMontant) showRow = false;

                    if (showRow) {
                        $row.show();
                        $row.find('.row-num').text(newIndex);
                        newIndex++;
                        visibleCount++;
                        totalUSD += parseFloat($row.data('montant-usd')) || 0;
                        totalCDF += parseFloat($row.data('montant-cdf')) || 0;
                    } else {
                        $row.hide();
                    }
                });

                // Mise à jour des compteurs et totaux
                $('#articleCount').text(visibleCount);
                $('#depenseCount').text(visibleCount);
                $('#totalUsdDepense').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
                $('#totalCdfDepense').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            }

            function debounceFilter() {
                if (filterTimeout) clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    saveFilters();
                    filterDepenses();
                }, 300);
            }

            // =================================================================
            // 4. ÉVÉNEMENTS SUR LES FILTRES
            // =================================================================
            $('#filterUser, #filterTypeDepense, #filterMontant').on('change', function() {
                saveFilters();
                filterDepenses();
            });

            $('#filterSearch').on('keyup', function() {
                debounceFilter();
            });

            $('#filterDateRange').on('change', function() {
                if ($(this).val() === '') {
                    saveFilters();
                    filterDepenses();
                }
            });

            // =================================================================
            // 5. BOUTON RÉINITIALISER
            // =================================================================
            $('#resetFilters').on('click', function() {
                localStorage.removeItem(STORAGE_KEY);
                $('#filterUser').val('all');
                $('#filterTypeDepense').val('all');
                $('#filterDateRange').val('');
                $('#filterSearch').val('');
                $('#filterMontant').val('');
                saveFilters();
                filterDepenses();
            });

            // =================================================================
            // 6. CHARGEMENT DES FILTRES PERSISTANTS ET DÉFINITION DE LA DATE PAR DÉFAUT = AUJOURD'HUI
            // =================================================================
            loadFilters();

            var today = moment();
            $('#filterDateRange').data('daterangepicker').setStartDate(today);
            $('#filterDateRange').data('daterangepicker').setEndDate(today);
            $('#filterDateRange').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));

            filterDepenses();

            // =================================================================
            // 7. GESTION DES MODALES IMPORT / EXPORT
            // =================================================================

            $("#importer").click(function(e) {
                e.preventDefault();
                $("#importModal").modal('show');
            });
            $("#exporter").click(function(e) {
                e.preventDefault();
                $("#exportModal").modal('show');
            });

            $("#downloadModelBtn").click(function() {
                window.location.href = "{{ url('') }}" + "/Modele d'importation des depenses.xlsx";
            });

            $("#confirmImportBtn").click(function() {
                var formData = new FormData($("#importForm")[0]);
                $.ajax({
                    url: "{{ url('/import_excel_depense') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#confirmImportBtn").prop('disabled', true).html(
                            '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Importation...');
                    },
                    success: function(response) {
                        $.get("{{ url('/get_all_depense') }}", {}, function(
                            refresh_editutilisateur) {
                            $("#content_utilisateur").html(refresh_editutilisateur);
                            loadFilters();
                            filterDepenses();
                        });
                        $("#importMessage").removeClass('alert-danger').addClass('alert-success')
                            .html('<i class="zmdi zmdi-check-circle"></i> ' + response["message"])
                            .show();
                        $("#confirmImportBtn").prop('disabled', false).html(
                            '<i class="zmdi zmdi-upload"></i> Importer');
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

            // ========== EXPORT PDF ==========
            $("#exportPdfBtn").click(function(e) {
                e.preventDefault();
                var $btn = $(this);
                var originalHtml = $btn.html();

                var filters = {
                    _token: '{{ csrf_token() }}',
                    user: $('#filterUser').val(),
                    type: $('#filterTypeDepense').val(),
                    dateRange: $('#filterDateRange').val(),
                    search: $('#filterSearch').val(),
                    montant: $('#filterMontant').val()
                };

                var iframeId = 'iframe_download_' + Date.now();
                var $iframe = $('<iframe>', {
                    name: iframeId,
                    style: 'display: none'
                }).appendTo('body');

                var $form = $('<form>', {
                    method: 'GET',
                    action: "{{ url('/export_depense_pdf') }}",
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
                    _token: '{{ csrf_token() }}',
                    user: $('#filterUser').val(),
                    type: $('#filterTypeDepense').val(),
                    dateRange: $('#filterDateRange').val(),
                    search: $('#filterSearch').val(),
                    montant: $('#filterMontant').val()
                };

                $btn.prop('disabled', true).html(
                    '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Génération de l\'Excel...');

                $.ajax({
                    url: "{{ url('/export_excel_depense') }}",
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
                            link.download = 'Mes depenses.xlsx';
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

            // =================================================================
            // 8. AUTRES SCRIPTS EXISTANTS
            // =================================================================
            $("#link_36").addClass("active");

            $("#upload").click(function(e) {
                e.preventDefault();
                $("#dropzonewidget").trigger("click");
            });

            $("#liste").click(function(e) {
                e.preventDefault();
                $("#bloc_1").show();
                $("#bloc_2").hide();
                $("#bloc_3").hide();
                filterDepenses();
            });

            $("#add").click(function(e) {
                $.get("{{ url('/get_numero_facture') }}", {}, function(response) {
                    $("#numero_facture").html(response);
                });
                e.preventDefault();
                $("#bloc_1").hide();
                $("#bloc_2").show();
                $("#bloc_3").hide();
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
                filterDepenses();
            });

            $("#entree").keyup(function(e) {
                e.preventDefault();
                var entree = $("#entree").val();
                var sortie = $("#sortie").val();
                var solde = 0;
                if ((Number(entree) == entree) && (Number(sortie) == sortie)) {
                    solde = Number(entree) + Number(sortie);
                    $("#solde").val(solde);
                }
            });

            $("#sortie").keyup(function(e) {
                e.preventDefault();
                var entree = $("#entree").val();
                var sortie = $("#sortie").val();
                var solde = 0;
                if ((Number(entree) == entree) && (Number(sortie) == sortie)) {
                    solde = Number(entree) + Number(sortie);
                    $("#solde").val(solde);
                }
            });

            $("#save").click(function(e) {
                e.preventDefault();
                var date_operation = $("#date_operation").val();
                var n_piece = $("#n_piece").val();
                var libelle = $("#libelle").val();
                var montant = $("#montant").val();
                var devise = $("#devise").val();
                var taux = $("#taux").val();
                var type_depense_id = $("#type_depense_id").val();
                var data = $("#form_add").serialize();

                if (date_operation.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date de l\'opération');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    if (montant.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant');
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                    } else {
                        if (montant == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le montant doit être supérieur à 0');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else {
                            if (devise.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else {
                                if (taux.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                } else {
                                    if ((type_depense_id.trim().length == 0) && (libelle.trim().length == 0)) {
                                        $('#msg').html(
                                            '<i class="zmdi zmdi-close-circle"></i> Completez le type de dépense ou le libellé de la dépense'
                                            );
                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                    } else {
                                        $.post("{{ url('/add_depense') }}", data, function(response) {
                                            if (response == "success") {
                                                $('#msg').html(
                                                    '<i class="zmdi zmdi-check-circle"></i> Dépense ajoutée avec succès'
                                                    );
                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                                $("#date_operation").val("<?= date('d/m/Y') ?>");
                                                $("#n_piece").val("");
                                                $("#libelle").val("");
                                                $("#montant").val("0");
                                                $("#devise").val("");
                                                $("#taux").val("0");
                                                $("#type_depense_id").val("");
                                                location.reload();
                                            } else {
                                                $('#msg').html(
                                                    '<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'ajout de la dépense'
                                                    );
                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                            }
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
                $.get("{{ url('/refresh_deletedecision') }}", { id: id }, function(refresh_editutilisateur) {
                    $("#content_utilisateur").html(refresh_editutilisateur);
                    $("#non").trigger("click");
                });
            });

            $("#oui_op").click(function(e) {
                e.preventDefault();
                var id = $("#data_id").html();
                var facture_id = $("#facture_id").html();
                $.get("{{ url('/delete_operation') }}", { invitation_id: facture_id, operation_id: id },
                    function(refresh_editinvitations) {
                        $("#bloc_1").hide();
                        $("#bloc_2").hide();
                        $("#bloc_3").show();
                        $("#non_op").trigger("click");
                        $("#bloc_3").html(refresh_editinvitations);
                    });
            });

            $("#oui_op_2").click(function(e) {
                e.preventDefault();
                var id = $("#data_id").html();
                var facture_id = $("#facture_id").html();
                $.get("{{ url('/delete_operation_2') }}", { invitation_id: facture_id, operation_id: id },
                    function(refresh_editinvitations) {
                        $("#non_op_2").trigger("click");
                        $("#content_sortie").html(refresh_editinvitations);
                    });
            });

            if (typeof Dropzone !== 'undefined') {
                $(".dropzone").dropzone({
                    addRemoveLinks: true,
                    removedfile: function(file) {
                        var name = file.name;
                        $.ajax({
                            type: 'POST',
                            url: '/upload_fichier_sortie',
                            data: { name: name, request: 2 },
                            success: function(data) {
                                console.log('success: ' + data);
                            }
                        });
                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                            .previewElement) : void 0;
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
                            success: function(data) {
                                console.log('success: ' + data);
                            }
                        });
                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                            .previewElement) : void 0;
                    }
                });
            }

            $.get("{{ url('/get_numero_facture') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });

        });
    </script>
@endsection
@endsection
