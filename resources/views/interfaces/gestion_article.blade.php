<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Typeventes;
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Mesures;
use App\Models\Activites;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'GESTION ARTICLE')
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
        #bloc_1,
        #bloc_2,
        #bloc_3 {
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

        /* BADGE DU NOMBRE D'ARTICLES */
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

        /* ========== TABLEAU ÉQUILIBRÉ ========== */
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

        .table tbody td a i.zmdi-edit {
            color: #2c7da0;
        }

        .table tbody td a:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }

        .table tbody td a i.zmdi-delete {
            color: var(--rouge-feu);
        }

        .table tbody td a:hover i.zmdi-delete {
            color: var(--rouge-fonce);
        }

        .table tbody td a:hover {
            background: #ffe5e5;
        }

        /* ========== BOUTONS PRINCIPAUX ========== */
        #liste,
        #add,
        #print,
        #add_r,
        #print_r,
        .btn-primary,
        .btn-primary.btn-sm,
        .btn-info,
        .btn-info.btn-sm,
        .btn-danger,
        .btn-danger.btn-sm,
        .btn-dark,
        .btn-dark.btn-sm,
        #importer,
        #exporter,
        #edit_save,
        #edit_annuler {
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

        #add,
        a#add {
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

        #add_r,
        #print_r {
            background: #cbd5e1 !important;
            color: #475569 !important;
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        #add_r:hover,
        #print_r:hover {
            transform: none;
            box-shadow: none;
        }

        #importer {
            background: var(--rouge-gradient) !important;
            color: white !important;
        }

        #importer:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(220, 38, 38, 0.3);
        }

        #exporter {
            background: linear-gradient(135deg, #2d3748, #1a202c) !important;
            color: white !important;
        }

        #exporter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(26, 32, 44, 0.3);
        }

        #save,
        #save_r,
        #annuler,
        #edit_save,
        #edit_annuler {
            padding: 8px 24px !important;
            font-weight: 700;
        }

        #save,
        #edit_save {
            background: linear-gradient(95deg, #0f4c5f, #0e6b5e) !important;
            color: white;
        }

        #save:hover,
        #edit_save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
        }

        #annuler,
        #edit_annuler {
            background: #64748b !important;
            color: white;
        }

        #annuler:hover,
        #edit_annuler:hover {
            background: #475569 !important;
            transform: translateY(-2px);
        }

        /* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
        #form_add .row,
        #form_edit .row {
            display: flex;
            flex-wrap: wrap;
        }

        #form_add .col-6,
        #form_edit .col-6 {
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
        .input-mask {
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

        /* ========== MESSAGES MODERNES ========== */
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
            border-left: 4px solid var(--vert-succes);
        }

        #msg:not(:empty):has(i.zmdi-close-circle),
        #edit_msg:not(:empty):has(i.zmdi-close-circle) {
            background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
            color: #991b1b;
            border-left: 4px solid var(--rouge-feu);
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

        /* ========== STYLES DES FILTRES ========== */
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
            min-width: 180px;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--bleu-nuit);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .filter-group .form-control {
            height: 42px;
        }

        /* ========== MESSAGE AUCUN RÉSULTAT (ROUGE) ========== */
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
        @media (max-width: 768px) {
            .content .container {
                padding: 0.8rem 1rem !important;
            }

            #bloc_1,
            #bloc_2,
            #bloc_3 {
                padding: 1.2rem !important;
            }

            #liste,
            #add,
            #print,
            #add_r,
            #print_r,
            .btn-primary,
            .btn-info,
            .btn-danger,
            .btn-dark,
            #importer,
            #exporter,
            #edit_save,
            #edit_annuler {
                padding: 6px 14px !important;
                font-size: 0.75rem;
                white-space: nowrap;
            }

            [style*="background-color: rgba(0, 0, 0, 0.1)"] {
                justify-content: center;
                gap: 8px;
            }

            #form_add .col-6,
            #form_edit .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .form-control,
            input.form-control,
            select.form-control,
            textarea.form-control {
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

            .btn,
            .btn-sm,
            #liste,
            #add,
            #print,
            #importer,
            #exporter,
            #edit_save,
            #edit_annuler {
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

            #noResultRow td {
                font-size: 0.85rem;
                padding: 20px 0 !important;
            }
        }

        /* ========== ANIMATIONS & DÉTAILS ========== */
        @keyframes glow {
            0% {
                box-shadow: 0 0 0 0 rgba(227, 27, 35, 0.2);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(227, 27, 35, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(227, 27, 35, 0);
            }
        }

        .btn-danger:active {
            animation: glow 0.3s ease-out;
        }

        .modal-header {
            background: var(--bleu-nuit-gradient);
        }

        input[required],
        select[required],
        textarea[required] {
            border-left: 3px solid var(--rouge-feu) !important;
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
                                    <a id="importer" class="btn-primary btn-danger btn-sm" href="">
                                        <i class="zmdi zmdi-download"></i> Importer
                                    </a>
                                    &nbsp;
                                    <a id="exporter" class="btn-primary btn-dark btn-sm" href="">
                                        <i class="zmdi zmdi zmdi-upload"></i> Exporter
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion article</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-book text-info"></i>
                        Liste
                        <span class="article-count-badge" id="articleCountBadge">
                            <i class="zmdi zmdi-view-list"></i> <span id="articleCount">0</span>
                        </span>
                    </h4>

                    <!-- SECTION FILTRES SIMPLIFIÉE -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> Nom de l'article</label>
                            <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-folder text-danger"></i> Catégorie</label>
                            <select id="filterCategorie" class="form-control">
                                <option value="all">Toutes les catégories</option>
                                @foreach ($societes as $categorie)
                                    <option value="cat_{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-chart text-danger"></i> Activité</label>
                            <select id="filterActivite" class="form-control">
                                <option value="all">Toutes les activités</option>
                                <option value="none">Aucune activité</option>
                                @foreach ($activites as $activite)
                                    @if (Auth::user()->role == 0)
                                        <option value="act_{{ $activite->id }}">{{ $activite->nom }}</option>
                                    @else
                                        @if ($activite->id == Auth::user()->activite_id)
                                            <option value="act_{{ $activite->id }}" selected>{{ $activite->nom }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-accounts text-danger"></i> Utilisateur</label>
                            <select id="filterUser" class="form-control">
                                <option value="all">Tous les utilisateurs</option>
                                @php
                                    $uniqueUsers = [];
                                @endphp
                                @foreach ($utilisateurs as $data)
                                    @if(!in_array($data->id, $uniqueUsers))
                                        @php
                                            $uniqueUsers[] = $data->id;
                                            $userName = User::where('id', $data->id)->first()['name'] ?? 'N/A';
                                        @endphp
                                        @if ($data->id == Auth::user()->id)
                                            <option value="{{ $data->id }}" selected>(Vous)</option>
                                        @else
                                            <option value="{{ $data->id }}">{{ $userName }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="content_utilisateur" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="articlesTable">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Catégorie</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Activité</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Prix</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Stock</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Seuils</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date d'expiration</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody id="articlesTableBody">
                                        {{ !($i = 1) }}
                                        @foreach ($articles as $data)
                                        <tr id="row_{{ $data->id }}">
                                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                            <td class="nom-cell" data-nom="{{ $data->nom_article }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ $data->nom_article }} ({{ Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A' }})
                                            </td>
                                            <td class="categorie-cell" data-categorie-id="{{ $data->societe_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }}
                                            </td>
                                            <td class="activite-cell" data-activite-id="{{ $data->activite_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if ($data->activite_id == 0 || $data->activite_id == '0')
                                                    Aucune
                                                @else
                                                    {{ Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune' }}
                                                @endif
                                            </td>
                                            <td class="prix-cell" data-prix="{{ $data->prix }}" data-devise="{{ $data->devise }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                <?php
                                                if ($data->devise == 0) {
                                                    echo '<span class="text-success">D : </span>'. number_format($data->prix_detail, 2, ',', ' ') .  '(USD), <span class="text-success">G : </span> '  . number_format($data->prix_gros, 2, ',', ' ')  . 'USD';
                                                } else {
                                                    echo '<span class="text-success">D : </span>'. number_format($data->prix_detail, 2, ',', ' ') . '(CDF), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . '(CDF)';
                                                }
                                                ?>
                                            </td>
                                            <td class="stock-cell" data-stock="{{ $data->stock }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                @if ($data->avoir_stock == 1)
                                                    <?php if($data->stock <= $data->seuil_minimum){ ?>
                                                        <span class="text-danger">{{ $data->stock }}</span>
                                                    <?php } ?>
                                                    <?php if($data->stock > $data->seuil_minimum){ ?>
                                                        <span>{{ $data->stock }}</span>
                                                    <?php } ?>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="seuil-cell" data-seuil-min="{{ $data->seuil_minimum }}" data-seuil-max="{{ $data->seuil_maximum }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ $data->seuil_minimum . ' - ' . $data->seuil_maximum }}
                                            </td>
                                            <td class="user-cell" data-user-id="{{ $data->user_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                            </td>
                                            <td class="date-cell" data-date-expiration="{{ $data->date_expiration }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                <?php if($data->date_expiration  == "00/00/0000"){ ?>
                                                <span class="text-info">{{ $data->date_expiration }} (N'expire pas)</span>
                                                <?php }else{ ?>
                                                <?php
                                                $target = 0;
                                                $semaine = ['Dimanche', 'Lundi', ' Mardi ', 'Mercredi ', 'Jeudi', 'Vendredi', 'Samedi'];
                                                $mois = [1 => 'Janvier', 'Février ', 'Mars ', 'Avril ', 'Mai ', 'Juin', 'Juillet', 'Août ', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                                $__d1 = date('d');
                                                $__m1 = date('m');
                                                $__y1 = date('Y');
                                                $__d2 = explode('/', $data->date_expiration)[0];
                                                $__m2 = explode('/', $data->date_expiration)[1];
                                                $__y2 = explode('/', $data->date_expiration)[2];

                                                $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                                                $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                                                while (strtotime($date_1) <= strtotime($date_2)) {
                                                    $jours = 1;
                                                    $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                                                    if ($semaine[date('w', $valeur_date)] != '') {
                                                        $target++;
                                                    }
                                                    $datedd = date('m/d/Y', strtotime(date('' . explode('/', $date_1)[0] . '/' . explode('/', $date_1)[1] . '/' . explode('/', $date_1)[2] . '') . ' + ' . $jours . ' days'));
                                                    $date_1 = explode('/', $datedd)[1] . '/' . explode('/', $datedd)[0] . '/' . explode('/', $datedd)[2];
                                                    $date_1 = explode('/', $datedd)[0] . '/' . explode('/', $datedd)[1] . '/' . explode('/', $datedd)[2];
                                                }
                                                if ($target == 0) {
                                                    echo "<span class='text-danger'>Expiré depuis $data->date_expiration </span>";
                                                } else {
                                                    echo "<span class='text-success'>$data->date_expiration (Dans $target jours) </span>";
                                                }
                                                ?>
                                                <?php } ?>
                                            </td>
                                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                <?php
                                                $edit = 0;
                                                $delete = 0;
                                                if (
                                                    Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                                        ->get()
                                                        ->count() != 0
                                                ) {
                                                    $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                                    $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                                }
                                                ?>
                                                <?php } ?>
                                                <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } else { ?>
                                                <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                <?php } ?>
                                                <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                                <?php } else { ?>
                                                <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                                <?php } ?>
                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_editarticle') }}", {
                                                            user_id: <?= $data->id ?>,
                                                        }, function(refresh_editarticle) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editarticle);
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
                                                        $("#element").html(
                                                            "<?= $data->nom_article . '(' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>"
                                                        );
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        $("#btn_sup").trigger("click");
                                                    });
                                                </script>
                                            </td>
                                        </tr>
                                        {{ !$i++ }}
                                        @endforeach
                                        <!-- Ligne pour aucun résultat -->
                                        <tr id="noResultRow" style="display: none;">
                                            <td colspan="10">
                                                <i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de recherche.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bloc_2" style="margin-top: 12px;display: none;padding-bottom: 100px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-book text-info"></i>
                        Ajouter</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <!-- LIGNE 1 : Catégorie et Nom -->
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-info"></i> Il s'agit de quelle catégorie ?
                                    </label>
                                    <select id="categorie_id" name="categorie_id" class="form-control"
                                        data-placeholder="Selectionnez une catégorie">
                                        <option selected value="">Selectionnez une catégorie</option>
                                        @foreach ($societes as $data)
                                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-info"></i> Nom
                                    </label>
                                    <input id="nom_article" name="nom_article" type="text"
                                        class="form-control input-mask"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Nom (Ex : Eau pure)">
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 2 : Prix détail et Prix gros -->
                        <div style="margin-top: -20px;" class="row">
                            <div style="display: none;" class="col-3">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-money"></i> Prix
                                    </label>
                                    <input id="prix" name="prix" type="text" class="form-control input-mask" value="10"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Prix (Ex : 10)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-money"></i> Prix de détail
                                    </label>
                                    <input id="prix_detail" name="prix_detail" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Prix de détail (Ex : 10)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-money"></i> Prix de gros
                                    </label>
                                    <input id="prix_gros" name="prix_gros" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Prix de gros (Ex : 10)">
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 3 : Taille lot et Devise -->
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-storage"></i> Taille du lot
                                    </label>
                                    <input id="taille_lot" name="taille_lot" type="text" class="form-control input-mask"
                                        data-mask="00000000000000000000000000000000000000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Taille du lot (Ex : 6, 12, 24)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-money"></i> devise
                                    </label>
                                    <select id="devise" name="devise" class="form-control"
                                        data-placeholder="Selectionnez une devise">
                                        <option selected class="form-control" value="">Selectionnez une devise
                                        </option>
                                        <option class="form-control" value="0"> USD</option>
                                        <option class="form-control" value="1"> CDF</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 4 : Unité de Mesure | Type de stockage -->
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-info"></i> Unité de Mesure
                                    </label>
                                    <select id="mesure_id" name="mesure_id" class="form-control"
                                        data-placeholder="Selectionnez une mesure">
                                        <option selected class="form-control" value="">Aucune</option>
                                        @foreach ($mesures as $data)
                                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-storage"></i> Type de stockage
                                    </label>
                                    <select id="avoir_stock" name="avoir_stock" class="form-control"
                                        data-placeholder="Selectionnez un type de stockage">
                                        <option selected class="form-control" value="">Aucun</option>
                                        <option class="form-control" value="1">Déterminé</option>
                                        <option class="form-control" value="0">Indeterminé</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 5 : Seuils (conditionnel) -->
                        <div id="seuilsGroup" style="display: none; margin-top: -20px;">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-money"></i> Seuil minimum
                                        </label>
                                        <input id="seuil_minimum" name="seuil_minimum" type="text"
                                            class="form-control input-mask"
                                            data-mask="00000000000000000000000000000000000000"
                                            style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                            placeholder="Seuil minimum (Ex : 10)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-money"></i> Seuil maximum
                                        </label>
                                        <input id="seuil_maximum" name="seuil_maximum" type="text"
                                            class="form-control input-mask"
                                            data-mask="00000000000000000000000000000000000000"
                                            style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                            placeholder="Seuil maximum (Ex : 100)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 6 : Date expiration et Description -->
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-calendar"></i> Date d'expiration
                                    </label>
                                    <input id="date_expiration" name="date_expiration" type="text"
                                        class="form-control input-mask" data-mask="00/00/0000"
                                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                        placeholder="Date d'expiration (Ex : 00/00/0000)"
                                        value="00/00/0000">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-comment"></i> Description
                                    </label>
                                    <textarea id="libelle" name="libelle"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Description" cols="2" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- LIGNE 7 : Activités (seul, avec une colonne vide) -->
                        <div style="margin-top: -20px;" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                        <i class="zmdi zmdi-toll"></i> Activites
                                    </label>
                                    <select id="activite_id" name="activite_id" class="form-control"
                                        data-placeholder="Selectionnez une activité">
                                        <option selected class="form-control" value="0">Aucune</option>
                                        @foreach ($activites as $data)
                                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- colonne vide pour équilibre -->
                            </div>
                        </div>
                    </form>
                    <div style="margin-top: -2px;" class="row">
                        <div class="col-12">
                            <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez
                                l'image de l'article</span></label>
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
                                <span style="font-weight: bold;" id="msg">
                                </span>
                            </div>
                        </div>
                    </form>
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
    <!-- Modales Import / Export -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                    <h5 class="modal-title text-white"><i class="zmdi zmdi-download"></i> Importer des articles
                        (Excel)</h5>
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
                    <h5 class="modal-title text-white"><i class="zmdi zmdi-upload"></i> Exporter les articles</h5>
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
    <script>
        // Gestion du menu
        $("#link_25").css("border-left", "1px solid rgb(33, 150, 243)");
        $("#text_25").addClass("text-info");

        // ========== FONCTION DE FILTRAGE CORRIGÉE ==========
        function filterArticles() {
            const filterNom = $('#filterNom').val().toLowerCase().trim();
            const filterCategorie = $('#filterCategorie').val();
            const filterActivite = $('#filterActivite').val();
            const filterUser = $('#filterUser').val();

            let visibleCount = 0;
            let newIndex = 1;

            // Masquer la ligne "aucun résultat" par défaut
            $('#noResultRow').hide();

            // Parcourir toutes les lignes SAUF la ligne "noResultRow"
            $('#articlesTableBody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                let showRow = true;

                // Récupération des données (en chaînes)
                const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                const categorieId = $row.find('.categorie-cell').data('categorie-id');
                const activiteId = $row.find('.activite-cell').data('activite-id');
                const userId = $row.find('.user-cell').data('user-id');

                // Filtrer par nom
                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                // Filtrer par catégorie
                if (showRow && filterCategorie !== 'all') {
                    if (filterCategorie.startsWith('cat_')) {
                        const target = filterCategorie.replace('cat_', '');
                        const current = categorieId != null ? String(categorieId) : '';
                        if (current !== target) showRow = false;
                    }
                }

                // Filtrer par activité
                if (showRow && filterActivite !== 'all') {
                    const current = activiteId != null ? String(activiteId) : '';
                    if (filterActivite === 'none') {
                        // Ne garder que les articles sans activité (ID 0 ou vide)
                        if (current !== '0' && current !== '') {
                            showRow = false;
                        }
                    } else if (filterActivite.startsWith('act_')) {
                        const target = filterActivite.replace('act_', '');
                        if (current !== target) showRow = false;
                    }
                }

                // Filtrer par utilisateur
                if (showRow && filterUser !== 'all') {
                    const current = userId != null ? String(userId) : '';
                    if (current !== filterUser) showRow = false;
                }

                // Affichage / masquage et mise à jour du numéro
                if (showRow) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            // Mettre à jour le compteur
            $('#articleCount').text(visibleCount);

            // Si aucune ligne visible, afficher le message "Aucun résultat"
            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        /**
         * Met à jour le badge sans refaire le filtrage complet
         * (utilisé après des opérations qui ne modifient pas le tableau).
         */
        function updateArticleCount() {
            const visibleRows = $('#articlesTableBody tr:visible').length;
            $('#articleCount').text(visibleRows);
        }

        // ========== GESTION DE L'AFFICHAGE CONDITIONNEL DES SEUILS ==========
        function toggleSeuils() {
            var type = $('#avoir_stock').val();
            if (type === '1') { // Déterminé
                $('#seuilsGroup').slideDown(300);
                $('#seuil_minimum').prop('disabled', false);
                $('#seuil_maximum').prop('disabled', false);
            } else { // Indéterminé ou vide
                $('#seuilsGroup').slideUp(300);
                $('#seuil_minimum').prop('disabled', true);
                $('#seuil_maximum').prop('disabled', true);
                // On vide les champs pour éviter des valeurs inutiles
                $('#seuil_minimum').val('');
                $('#seuil_maximum').val('');
            }
        }

        // ========== INITIALISATION AU CHARGEMENT ==========
        $(document).ready(function() {

            // 1. Appliquer le filtre immédiatement (selon les valeurs par défaut des filtres)
            filterArticles();

            // 2. État initial des seuils
            toggleSeuils();

            // 3. Événements sur les filtres (mise à jour en temps réel)
            $('#filterNom, #filterCategorie, #filterActivite, #filterUser').on('change keyup', function() {
                filterArticles();
            });

            // 4. Événement sur le type de stockage pour afficher/masquer les seuils
            $(document).on('change', '#avoir_stock', function() {
                toggleSeuils();
            });

            // ========== BOUTONS DE NAVIGATION ==========
            $("#upload").click(function(e) {
                e.preventDefault();
                $("#dropzonewidget").trigger("click");
            });

            $("#liste").click(function(e) {
                e.preventDefault();
                $("#bloc_1").show();
                $("#bloc_2").hide();
                $("#bloc_3").hide();
                // Le tableau est déjà visible, on réapplique le filtre au cas où
                filterArticles();
            });

            $("#add").click(function(e) {
                e.preventDefault();
                $("#bloc_1").hide();
                $("#bloc_2").show();
                $("#bloc_3").hide();
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
                window.location.href = "{{ url('') }}" + "/Modele d'importation des articles.xlsx";
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
                filterArticles();
            });

            // ========== SAUVEGARDE AVEC VALIDATION CONDITIONNELLE ==========
            $("#save").click(function(e) {
                e.preventDefault();
                var categorie_id = $("#categorie_id").val();
                var nom_article = $("#nom_article").val();
                var prix = $("#prix").val();
                var devise = $("#devise").val();
                var seuil_minimum = $("#seuil_minimum").val();
                var seuil_maximum = $("#seuil_maximum").val();
                var date_expiration = $("#date_expiration").val();
                var libelle = $("#libelle").val();
                var prix_detail = $("#prix_detail").val();
                var prix_gros = $("#prix_gros").val();
                var taille_lot = $("#taille_lot").val();
                var mesure_id = $("#mesure_id").val();      // Unité de mesure (obligatoire)
                var avoir_stock = $("#avoir_stock").val();  // Type de stockage (obligatoire)

                // ===== VÉRIFICATIONS OBLIGATOIRES =====
                if (categorie_id.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la catégorie de l\'article');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else if (nom_article.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    $.get("{{ url('/check_nom_article') }}", { nom: nom_article }, function(rep_nom) {
                        if (rep_nom != 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le nom de cette article existe deja');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (prix.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de l\'article');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (prix_detail.trim().length == 0){
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de détail de l\'article');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (prix_gros.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de gros de l\'article');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (taille_lot.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la taille du lot de l\'article');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (devise.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez la devise de cette article');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        }
                        else if (mesure_id.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une mesure');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        }
                        else if (avoir_stock.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un type de stockage');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else if (date_expiration.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date d\'expiration');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        }
                        // ===== VALIDATION CONDITIONNELLE DES SEUILS =====
                        else if (avoir_stock == '1') {
                            if (seuil_minimum.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil minimum');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else if (seuil_minimum <= 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil minimum doit être supérieur à 0.');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else if (seuil_maximum.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil maximum');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else if (seuil_maximum <= 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil maximum doit être supérieur à 0.');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else {
                                // Tout est bon, on envoie
                                $("#save").attr("disabled", true);
                                var data = $("#form_add").serialize();
                                $.ajax({
                                    type: "POST",
                                    url: "/add_article",
                                    data: data,
                                    success: function(response) {
                                        $("#save").attr("disabled", false);
                                        Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                        $("#prix_detail").val("");
                                        $("#prix_gros").val("");
                                        $("#taille_lot").val("");
                                        $("#nom_article").val("");
                                        $("#libelle").val("");
                                        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Article ajoutée avec succès');
                                        $("#content_utilisateur").html(response);
                                        // 🔥 Réappliquer le filtre après mise à jour
                                        filterArticles();
                                        setTimeout(() => { $('#msg').html(""); }, 9000);
                                        // Réinitialiser le type de stockage et cacher les seuils
                                        $('#avoir_stock').val('').trigger('change');
                                    }
                                });
                            }
                        } else {
                            // Type = Indéterminé : on force les seuils à 0 et on envoie
                            $('#seuil_minimum').val(0);
                            $('#seuil_maximum').val(0);
                            $("#save").attr("disabled", true);
                            var data = $("#form_add").serialize();
                            $.ajax({
                                type: "POST",
                                url: "/add_article",
                                data: data,
                                success: function(response) {
                                    $("#save").attr("disabled", false);
                                    Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                    $("#prix_detail").val("");
                                    $("#prix_gros").val("");
                                    $("#taille_lot").val("");
                                    $("#nom_article").val("");
                                    $("#libelle").val("");
                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Article ajoutée avec succès');
                                    $("#content_utilisateur").html(response);
                                    // 🔥 Réappliquer le filtre après mise à jour
                                    filterArticles();
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                    // Réinitialiser le type de stockage et cacher les seuils
                                    $('#avoir_stock').val('').trigger('change');
                                }
                            });
                        }
                    });
                }
            });

            // ========== DROPZONE ==========
            $(".dropzone").dropzone({
                addRemoveLinks: true,
                removedfile: function(file) {
                    $.ajax({
                        type: 'POST',
                        url: '/upload_fichier_sortie',
                        data: { name: name, request: 2 },
                        success: function(data) { console.log('success: ' + data); }
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
                        success: function(data) { console.log('success: ' + data); }
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            });
        });

        // ========== SUPPRESSION ==========
        $("#oui").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_deletearticle') }}", { id: id }, function(refresh_deletearticle) {
                $("#content_utilisateur").html(refresh_deletearticle);
                // 🔥 Réappliquer le filtre après suppression
                filterArticles();
                $("#non").trigger("click");
            });
        });

        $("#confirmImportBtn").click(function() {
            var formData = new FormData($("#importForm")[0]);
            $.ajax({
                url: "{{ url('/import_excel_article') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#confirmImportBtn").prop('disabled', true).html(
                        '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Importation...');
                },
                success: function(response) {
                    $.get("{{ url('/get_all_articles') }}", {}, function(
                    refresh_editutilisateur) {
                        $("#content_utilisateur").html(refresh_editutilisateur);
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

        // ========== EXPORT PDF ==========
        $("#exportPdfBtn").click(function(e) {
            e.preventDefault();
            // alert("L'export PDF est en cours de développement. Veuillez patienter.");
            var $btn = $(this);
            var originalHtml = $btn.html();

            var filters = {
                _token: '{{ csrf_token() }}',
                nom: $('#filterNom').val(),
                categorie: $('#filterCategorie').val(),
                activite: $('#filterActivite').val(),
                user: $('#filterUser').val()
            };

            var iframeId = 'iframe_download_' + Date.now();
            var $iframe = $('<iframe>', {
                name: iframeId,
                style: 'display: none'
            }).appendTo('body');

            var $form = $('<form>', {
                method: 'GET',
                action: "{{ url('/export_article_pdf') }}",
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
            var originalHtml = $btn.html(); // 👈 Sauvegarde du texte original
            var $msgContainer = $("#exportMessage");
            $msgContainer.removeClass('alert-success alert-danger').html('').hide();

            var filters = {
                _token: '{{ csrf_token() }}',
                nom: $('#filterNom').val(),
                categorie: $('#filterCategorie').val(),
                activite: $('#filterActivite').val(),
                user: $('#filterUser').val()
            };

            $btn.prop('disabled', true).html(
                '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Génération de l\'Excel...');

            $.ajax({
                url: "{{ url('/export_excel_article') }}",
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
                        link.download = 'Mes articles.xlsx';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);
                        // Le bouton sera remis en état dans le callback complete
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
                    // Restauration du bouton
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    </script>
@endsection
@endsection
