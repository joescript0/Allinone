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
use App\Models\Typeventes;
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Mesures;
use App\Models\Activites;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'GESTION ARTICLE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* =============================================
   DESIGN PREMIUM - VERSION UNIFIÉE (copié de Approvisionnement)
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

        /* --- Variables --- */
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
        }

        h4 i.zmdi {
            background: var(--bleu-nuit-gradient);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent !important;
        }

        /* ========== TABLEAU : LIGNES PLUS AÉRÉES ET VISIBLES ========== */
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

        /* En-tête */
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

        /* Lignes du tableau : padding augmenté, rayures et bordures nettes */
        .table tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Rayures (zebra) pour une meilleure lisibilité */
        .table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Survol */
        .table tbody tr:hover {
            background: #e6f0ff !important;
            cursor: default;
        }

        /* Cellules : espacement augmenté */
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

        /* Dernière cellule (contrôle) centrée */
        .table tbody td:last-child {
            text-align: center;
            vertical-align: middle;
        }

        /* ========== STYLE UNIQUE POUR TOUS LES BOUTONS (MODERNE, ARRONDI, OMBRE) ========== */
        /* Ajout de .btn-success pour harmoniser Importer/Exporter */
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
        .btn-secondary,
        .btn-success {
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

        /* Couleurs spécifiques pour chaque type de bouton */
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

        /* --- BOUTON EXPORTER EN VERT (nouvelle règle) --- */
        #exporter,
        .btn-success {
            background: var(--vert-gradient) !important;
            color: white !important;
        }
        #exporter:hover,
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, #059669, #047857) !important;
        }

        /* Bouton désactivé (ex: add_r, save_r) */
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

        /* Badge spécifique aux articles */
        .article-count-badge {
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

        /* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
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
            .btn-primary,
            .btn-info,
            .btn-danger,
            .btn-success {
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
            .user-count-badge {
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
            .btn-primary,
            .btn-info,
            .btn-danger,
            .btn-success {
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

        /* ========== MESSAGES STYLISÉS (SUCCÈS / ERREUR / INFO) ========== */
        #msg,
        #edit_msg,
        #transfer_msg {
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
        #edit_msg:not(:empty),
        #transfer_msg:not(:empty) {
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
        #transfer_msg:not(:empty):has(i.zmdi-check-circle) {
            background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        #msg:not(:empty):has(i.zmdi-close-circle),
        #edit_msg:not(:empty):has(i.zmdi-close-circle),
        #transfer_msg:not(:empty):has(i.zmdi-close-circle) {
            background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        #msg:not(:empty):has(i.zmdi-info),
        #edit_msg:not(:empty):has(i.zmdi-info),
        #transfer_msg:not(:empty):has(i.zmdi-info) {
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

        /* ========== AJOUTS POUR LES ÉLÉMENTS PROPRES À CETTE PAGE ========== */
        /* Boutons de contrôle dans le tableau (édit, delete, transfer) */
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
        /* Icône transfert en noir */
        .table tbody td a i.zmdi-swap {
            color: #333 !important;
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
        .table tbody td a:hover i.zmdi-swap {
            color: #000 !important;
        }
        /* Désactivé */
        .table tbody td a.transfer-disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        .table tbody td a.transfer-disabled:hover {
            background: #f1f5f9;
            transform: none;
        }
        .table tbody td a.transfer-disabled i.zmdi-swap {
            color: #999 !important;
        }

        /* Correction du select - survol sans débordement */
        #transfert_stock_id:hover,
        #transfert_stock_id:focus {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        /* Ajustement pour le conteneur d'actions (barre des boutons) */
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

        /* Alignement des colonnes de contrôle dans le tableau */
        .table tbody td:last-child {
            text-align: center;
        }

        /* Responsive fine */
        @media (max-width: 768px) {
            [style*="background-color: rgba(0, 0, 0, 0.1)"] {
                justify-content: center;
                gap: 8px;
            }
        }

        /* ===== STYLES POUR LE DROPDOWN À CHECKBOXES (stock) ===== */
        .stock-dropdown {
            width: 100%;
        }
        .stock-dropdown .dropdown-toggle {
            height: 46px !important;
            border-radius: 14px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 10px 36px 10px 16px !important;
            font-size: .95rem;
            background: #fff;
            transition: all .2s;
            box-shadow: 0 2px 8px rgba(0,0,0,.02);
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            color: #1e2a3e;
            font-weight: 500;
            position: relative;
        }
        .stock-dropdown .dropdown-toggle:focus {
            border-color: var(--bleu-nuit) !important;
            box-shadow: 0 0 0 4px rgba(10,25,47,.1) !important;
            transform: translateY(-2px);
        }
        .stock-dropdown .dropdown-toggle span {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .stock-dropdown .dropdown-toggle .caret {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: .8rem;
            color: #94a3b8;
            flex-shrink: 0;
        }
        .stock-dropdown .dropdown-toggle::after {
            display: none;
        }
        .stock-dropdown .dropdown-menu {
            width: 100%;
            border-radius: 14px;
            box-shadow: var(--shadow-premium);
            border: 1px solid #e2e8f0;
            margin-top: 5px;
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
        }
        .stock-dropdown .dropdown-menu .checkbox {
            padding: 5px 0;
        }
        .stock-dropdown .dropdown-menu .checkbox label {
            font-weight: 500;
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .9rem;
        }
        .stock-dropdown .dropdown-menu .checkbox input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
            accent-color: #e31b23;
            flex-shrink: 0;
        }

        /* ===== UNIFORMISATION DES CHAMPS DANS LE MODAL DE TRANSFERT ===== */
        #transferModal .form-control,
        #transferModal input.form-control,
        #transferModal select.form-control,
        #transferModal textarea.form-control {
            height: 46px !important;
            padding: 10px 16px !important;
            font-size: 0.95rem;
            border-radius: 14px !important;
        }
        #transferModal textarea.form-control {
            height: 46px !important;
            resize: vertical;
        }
        #transferModal .stock-dropdown .dropdown-toggle {
            height: 46px !important;
            padding: 10px 36px 10px 16px !important;
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
                                    <!-- ===== CONTENEUR FLEX RESPONSIF ===== -->
                                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                                        <a class="btn-primary btn-sm" id="liste" href="">
                                            <i class="zmdi zmdi-accounts"></i> Liste
                                        </a>

                                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)): ?>
                                            <?php
                                            $add = 0;
                                            if (Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()->count() != 0) {
                                                $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
                                            }
                                            ?>
                                            <?php if (($add == 1) || (Auth::user()->role == 0)): ?>
                                                <a id="add" class="btn-primary btn-sm" href="">
                                                    <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                                </a>
                                                <a id="importer" class="btn-primary btn-danger btn-sm" href="">
                                                    <i class="zmdi zmdi-download"></i> Importer
                                                </a>
                                                <a id="exporter" class="btn-primary btn-success btn-sm" href="">
                                                    <i class="zmdi zmdi-upload"></i> Exporter
                                                </a>
                                            <?php else: ?>
                                                <a id="add_r" href="">
                                                    <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
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

                    <!-- SECTION FILTRES AVEC PERSISTANCE -->
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
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
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
                                            <td class="stock-cell" data-stock="{{ $data->stock }}" style="padding-top: 5px;padding-bottom: 5px;text-align:center;">
                                                @if ($data->avoir_stock == 1)
                                                    <?php if($data->stock <= $data->seuil_minimum){ ?>
                                                        <span class="text-danger">{{ $data->stock }}</span>
                                                    <?php } ?>
                                                    <?php if($data->stock > $data->seuil_minimum){ ?>
                                                        <span>{{ $data->stock }}</span>
                                                    <?php } ?>
                                                @else
                                                    <i class="zmdi zmdi-close-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="seuil-cell" data-seuil-min="{{ $data->seuil_minimum }}" data-seuil-max="{{ $data->seuil_maximum }}" style="padding-top: 5px;padding-bottom: 5px;text-align:center;">
                                                @if (($data->seuil_minimum) && ($data->seuil_maximum))
                                                    {{ $data->seuil_minimum . ' - ' . $data->seuil_maximum }}
                                                @else
                                                    <i class="zmdi zmdi-close-circle text-danger"></i>
                                                @endif
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

                                                <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="transfer_<?= $i ?>" href="#"
                                                   data-id="<?= $data->id ?>"
                                                   data-article='<?= json_encode([
                                                       'id' => $data->id,
                                                       'nom_article' => $data->nom_article,
                                                       'categorie_nom' => Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A',
                                                       'prix_detail' => $data->prix_detail,
                                                       'prix_gros' => $data->prix_gros,
                                                       'devise' => $data->devise,
                                                       'stock' => $data->stock,
                                                       'seuil_minimum' => $data->seuil_minimum,
                                                       'taille_lot' => $data->taille_lot,
                                                       'activite_id' => $data->activite_id,
                                                       'avoir_stock' => $data->avoir_stock,
                                                       'activite_nom' => ($data->activite_id == 0 || $data->activite_id == '0') ? 'Aucune' : (Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune'),
                                                       'user_id' => $data->user_id,
                                                       'user_nom' => User::where('id', $data->user_id)->first()['name'] ?? 'N/A',
                                                   ]) ?>'
                                                   class="transfer-btn">
                                                    <i class="zmdi zmdi-swap" style="color:#333;"></i>
                                                </a> &nbsp;
                                                <?php } else { ?>
                                                <a id="transfer_r<?= $i ?>" href="#" class="transfer-disabled">
                                                    <i class="zmdi zmdi-swap" style="color:#999;"></i>
                                                </a> &nbsp;
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

    <!-- ========== MODAL TRANSFERT AVEC MULTI-SELECT (CHECKBOXES) ET CHAMPS UNIFORMISÉS ========== -->
    <div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: var(--border-radius-xl); box-shadow: var(--shadow-premium);">
                <div class="modal-header" style="background: var(--bleu-nuit-gradient); border-radius: var(--border-radius-xl) var(--border-radius-xl) 0 0;">
                    <h5 class="modal-title text-white">
                        <i class="zmdi zmdi-swap"></i> Transférer l'article
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <form id="form_transfert" action="#" method="post">
                        @csrf
                        <input type="hidden" id="transfer_article_id" name="transfer_article_id" value="">

                        <!-- EN-TÊTE -->
                        <div style="background: #f7faff; padding: 18px; border-radius: var(--border-radius-lg); margin-bottom: 25px; border-left: 6px solid #0a192f; border: 1px solid #e2e8f0;">
                            <div style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #0a192f; letter-spacing: 0.5px; margin-bottom: 15px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                                <i class="zmdi zmdi-info text-info"></i> INFORMATIONS DE L'ARTICLE (non modifiables)
                            </div>
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-label text-danger"></i> Nom :</span>
                                    <span id="transfer_nom" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-store text-danger"></i> Catégorie :</span>
                                    <span id="transfer_categorie" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-toll text-danger"></i> Activité actuelle :</span>
                                    <span id="transfer_activite_actuelle" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-money text-danger"></i> Prix détail :</span>
                                    <span id="transfer_prix_detail" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-money text-danger"></i> Prix gros :</span>
                                    <span id="transfer_prix_gros" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                                <div class="col-md-4">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-storage text-danger"></i> Stock actuel :</span>
                                    <span id="transfer_stock" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 0;">
                                <div class="col-md-12">
                                    <span style="font-weight: 600; color: #2d3748; font-size: 0.8rem;"><i class="zmdi zmdi-accounts text-danger"></i> Utilisateur actuel :</span>
                                    <span id="transfer_user_actuel" style="font-weight: 500; color: #0a192f; margin-left: 8px;">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- DESTINATION -->
                        <div style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #0a192f; letter-spacing: 0.5px; margin-bottom: 15px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                            <i class="zmdi zmdi-swap text-warning"></i> DESTINATION DU TRANSFERT
                        </div>

                        <!-- Ligne 1 : Liste de stock (multi-select) + Quantité -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-view-list text-danger"></i> Liste(s) de stock <span class="text-danger">*</span>
                                    </label>
                                    <div class="dropdown stock-dropdown">
                                        <button class="form-control dropdown-toggle" type="button" id="dropdownStock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: left; background: white; border: 1px solid #e2e8f0; border-radius: 14px; height: 46px; display: flex; align-items: center; justify-content: space-between;">
                                            <span id="selectedStockText">Aucun stock sélectionné</span>
                                            <span class="caret">▼</span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownStock" style="width: 100%; padding: 10px; max-height: 200px; overflow-y: auto;">
                                            <div class="checkbox" style="padding: 5px 0;">
                                                <label><input type="checkbox" class="stock-checkbox" value="none" checked> Aucun</label>
                                            </div>
                                            @foreach ($stocks as $st)
                                                <div class="checkbox" style="padding: 5px 0;">
                                                    <label><input type="checkbox" class="stock-checkbox" value="{{ $st->id }}"> {{ $st->nom }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Conteneur pour les champs cachés (tableau) -->
                                    <div id="selectedStockContainer"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-storage text-danger"></i> Quantité à transférer
                                    </label>
                                    <input type="number" id="transfert_quantite" name="transfert_quantite" class="form-control" value="1" step="1" placeholder="Optionnel si stock indéterminé">
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 2 : Motif + Prix détail -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-comment text-danger"></i> Motif du transfert <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="transfert_commentaire" name="transfert_commentaire" class="form-control" rows="1" placeholder="Raison du transfert..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-money text-danger"></i> Prix de détail <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="1" id="transfert_prix_detail_dest" name="transfert_prix_detail_dest" class="form-control" placeholder="Ex: 500">
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 3 : Prix de gros + Taille du lot -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-money text-danger"></i> Prix de gros <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="1" id="transfert_prix_gros_dest" name="transfert_prix_gros_dest" class="form-control" placeholder="Ex: 300">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-storage text-danger"></i> Taille du lot <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="1" id="transfert_taille_lot_dest" name="transfert_taille_lot_dest" class="form-control" placeholder="Ex: 12">
                                </div>
                            </div>
                        </div>

                        <!-- Ligne 4 : Devise seule -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: #2d3748;">
                                        <i class="zmdi zmdi-money text-danger"></i> Devise <span class="text-danger">*</span>
                                    </label>
                                    <select id="transfert_devise_dest" name="transfert_devise_dest" class="form-control">
                                        <option value="">Sélectionnez une devise</option>
                                        <option value="0">USD</option>
                                        <option value="1">CDF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6"></div> <!-- vide -->
                        </div>

                        <!-- Message -->
                        <div class="row">
                            <div class="col-lg-12" style="text-align: center;">
                                <span style="font-weight: bold;" id="transfer_msg"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button id="transfer_annuler" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="zmdi zmdi-close-circle"></i> Annuler
                    </button>
                    <button id="transfer_submit" class="btn btn-info btn-sm">
                        <i class="zmdi zmdi-swap"></i> Transférer
                    </button>
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
        $("#link_25").addClass("active");

        // ========== FONCTION DE FILTRAGE AVEC PERSISTANCE ==========
        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                nom: $('#filterNom').val(),
                categorie: $('#filterCategorie').val(),
                activite: $('#filterActivite').val(),
                user: $('#filterUser').val()
            };
            localStorage.setItem('articleFilters', JSON.stringify(filters));
        }

        function loadFiltersFromStorage() {
            const savedFilters = localStorage.getItem('articleFilters');
            if (savedFilters) {
                const filters = JSON.parse(savedFilters);
                $('#filterNom').val(filters.nom || '');
                $('#filterCategorie').val(filters.categorie || 'all');
                $('#filterActivite').val(filters.activite || 'all');
                $('#filterUser').val(filters.user || 'all');
                return true;
            }
            return false;
        }

        function resetFilters() {
            $('#filterNom').val('');
            $('#filterCategorie').val('all');
            $('#filterActivite').val('all');
            $('#filterUser').val('all');
            saveFiltersToStorage();
            filterArticles();
            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }

        function filterArticles() {
            const filterNom = $('#filterNom').val().toLowerCase().trim();
            const filterCategorie = $('#filterCategorie').val();
            const filterActivite = $('#filterActivite').val();
            const filterUser = $('#filterUser').val();

            let visibleCount = 0;
            let newIndex = 1;

            $('#noResultRow').hide();

            $('#articlesTableBody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                let showRow = true;

                const nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                const categorieId = $row.find('.categorie-cell').data('categorie-id');
                const activiteId = $row.find('.activite-cell').data('activite-id');
                const userId = $row.find('.user-cell').data('user-id');

                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                if (showRow && filterCategorie !== 'all') {
                    if (filterCategorie.startsWith('cat_')) {
                        const target = filterCategorie.replace('cat_', '');
                        const current = categorieId != null ? String(categorieId) : '';
                        if (current !== target) showRow = false;
                    }
                }

                if (showRow && filterActivite !== 'all') {
                    const current = activiteId != null ? String(activiteId) : '';
                    if (filterActivite === 'none') {
                        if (current !== '0' && current !== '') {
                            showRow = false;
                        }
                    } else if (filterActivite.startsWith('act_')) {
                        const target = filterActivite.replace('act_', '');
                        if (current !== target) showRow = false;
                    }
                }

                if (showRow && filterUser !== 'all') {
                    const current = userId != null ? String(userId) : '';
                    if (current !== filterUser) showRow = false;
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

            $('#articleCount').text(visibleCount);

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        function debouncedFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                filterArticles();
                saveFiltersToStorage();
            }, 300);
        }

        // ========== GESTION DE L'AFFICHAGE CONDITIONNEL DES SEUILS ==========
        function toggleSeuils() {
            var type = $('#avoir_stock').val();
            if (type === '1') {
                $('#seuilsGroup').slideDown(300);
                $('#seuil_minimum').prop('disabled', false);
                $('#seuil_maximum').prop('disabled', false);
            } else {
                $('#seuilsGroup').slideUp(300);
                $('#seuil_minimum').prop('disabled', true);
                $('#seuil_maximum').prop('disabled', true);
                $('#seuil_minimum').val('');
                $('#seuil_maximum').val('');
            }
        }

        // ========== INITIALISATION AU CHARGEMENT ==========
        $(document).ready(function() {

            // Chargement des filtres depuis le localStorage
            const hasSavedFilters = loadFiltersFromStorage();

            // Appliquer les filtres immédiatement
            filterArticles();

            // État initial des seuils
            toggleSeuils();

            // Événements sur les filtres (mise à jour en temps réel)
            $('#filterNom, #filterCategorie, #filterActivite, #filterUser').on('change keyup', function() {
                debouncedFilter();
            });

            // Réinitialisation des filtres
            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetFilters();
            });

            // Événement sur le type de stockage pour afficher/masquer les seuils
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
                var mesure_id = $("#mesure_id").val();
                var avoir_stock = $("#avoir_stock").val();

                if (categorie_id.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la catégorie de l\'article');
                    $('#msg').css('display', 'flex');
                    setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                } else if (nom_article.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                    $('#msg').css('display', 'flex');
                    setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                } else {
                    $.get("{{ url('/check_nom_article') }}", { nom: nom_article }, function(rep_nom) {
                        if (rep_nom != 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le nom de cette article existe deja');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (prix.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de l\'article');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (prix_detail.trim().length == 0){
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de détail de l\'article');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (prix_gros.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de gros de l\'article');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (taille_lot.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la taille du lot de l\'article');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (devise.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez la devise de cette article');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        }
                        else if (mesure_id.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une mesure');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        }
                        else if (avoir_stock.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un type de stockage');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        } else if (date_expiration.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date d\'expiration');
                            $('#msg').css('display', 'flex');
                            setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                        }
                        else if (avoir_stock == '1') {
                            if (seuil_minimum.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil minimum');
                                $('#msg').css('display', 'flex');
                                setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                            } else if (seuil_minimum <= 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil minimum doit être supérieur à 0.');
                                $('#msg').css('display', 'flex');
                                setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                            } else if (seuil_maximum.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil maximum');
                                $('#msg').css('display', 'flex');
                                setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                            } else if (seuil_maximum <= 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil maximum doit être supérieur à 0.');
                                $('#msg').css('display', 'flex');
                                setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                            } else {
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
                                        $('#msg').css('display', 'flex');
                                        $("#content_utilisateur").html(response);
                                        filterArticles();
                                        setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
                                        $('#avoir_stock').val('').trigger('change');
                                    }
                                });
                            }
                        } else {
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
                                    $('#msg').css('display', 'flex');
                                    $("#content_utilisateur").html(response);
                                    filterArticles();
                                    setTimeout(() => { $('#msg').html(''); $('#msg').css('display', 'none'); }, 9000);
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
                filterArticles();
                $("#non").trigger("click");
            });
        });

        // =====================================================
        // GESTION DU TRANSFERT D'ARTICLE (avec multi-sélection stock)
        // =====================================================

        var stockActuelGlobal = 0;
        var seuilMinimumGlobal = 0;
        var avoirStockGlobal = 0;

        // ---- Fonctions pour le multi-select ----
        function updateStockSelection() {
            var checked = $('.stock-checkbox:not([value="none"]):checked');
            var noneChecked = $('.stock-checkbox[value="none"]').prop('checked');

            if (noneChecked) {
                $('.stock-checkbox:not([value="none"])').prop('checked', false);
                $('#selectedStockText').text('Aucun stock sélectionné');
                $('#selectedStockContainer').empty();
                return;
            }

            var names = [];
            var ids = [];
            checked.each(function() {
                var $cb = $(this);
                var label = $cb.closest('label').text().trim();
                names.push(label);
                ids.push($cb.val());
            });

            if (ids.length === 0) {
                $('#selectedStockText').text('Aucun stock sélectionné');
                $('.stock-checkbox[value="none"]').prop('checked', true);
            } else {
                $('#selectedStockText').text(names.join(', '));
                $('.stock-checkbox[value="none"]').prop('checked', false);
            }

            $('#selectedStockContainer').empty();
            ids.forEach(function(id) {
                $('<input>', {
                    type: 'hidden',
                    name: 'transfert_stock_id[]',
                    value: id
                }).appendTo('#selectedStockContainer');
            });
        }

        $(document).on('change', '.stock-checkbox', function() {
            var $this = $(this);
            var val = $this.val();

            if (val === 'none') {
                if ($this.prop('checked')) {
                    $('.stock-checkbox:not([value="none"])').prop('checked', false);
                }
            } else {
                if ($this.prop('checked')) {
                    $('.stock-checkbox[value="none"]').prop('checked', false);
                }
                if ($('.stock-checkbox:not([value="none"]):checked').length === 0) {
                    $('.stock-checkbox[value="none"]').prop('checked', true);
                }
            }
            updateStockSelection();
        });

        // ---- Ouverture du modal ----
        $(document).on('click', '.transfer-btn', function(e) {
            e.preventDefault();
            var articleData = $(this).data('article');
            if (!articleData) {
                alert('Données de l\'article manquantes');
                return;
            }

            stockActuelGlobal = articleData.stock;
            seuilMinimumGlobal = articleData.seuil_minimum;
            avoirStockGlobal = articleData.avoir_stock;

            // En-tête
            $('#transfer_nom').text(articleData.nom_article);
            $('#transfer_categorie').text(articleData.categorie_nom);
            var deviseLabel = (articleData.devise == 0) ? 'USD' : 'CDF';
            $('#transfer_prix_detail').text(articleData.prix_detail + ' ' + deviseLabel);
            $('#transfer_prix_gros').text(articleData.prix_gros + ' ' + deviseLabel);
            $('#transfer_stock').text(articleData.stock);
            $('#transfer_activite_actuelle').text(articleData.activite_nom);
            $('#transfer_user_actuel').text(articleData.user_nom);

            $('#transfer_article_id').val(articleData.id);

            // Réinitialiser le multi-select à "Aucun"
            $('.stock-checkbox[value="none"]').prop('checked', true);
            $('.stock-checkbox:not([value="none"])').prop('checked', false);
            updateStockSelection();

            $('#transfert_quantite').val(1);
            $('#transfert_prix_detail_dest').val(articleData.prix_detail);
            $('#transfert_prix_gros_dest').val(articleData.prix_gros);
            $('#transfert_taille_lot_dest').val(articleData.taille_lot);
            $('#transfert_devise_dest').val(articleData.devise);
            $('#transfert_commentaire').val('');

            $('#transfer_msg').html('').css('display', 'none');
            $('#transferModal').modal('show');
        });

        // ==========================================================
        // ---- SOUMISSION DU TRANSFERT (MODIFICATION ICI) ----
        // ==========================================================
        $(document).on('click', '#transfer_submit', function(e) {
            e.preventDefault();
            $('#transfer_msg').html('').css('display', 'none');

            // 1. Récupérer les stocks sélectionnés
            var stockIds = [];
            $('input[name="transfert_stock_id[]"]').each(function() {
                stockIds.push($(this).val());
            });
            var nbStocks = stockIds.length;

            if (nbStocks === 0) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez au moins un stock.');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // 2. Motif
            var commentaire = $('#transfert_commentaire').val().trim();
            if (commentaire == '' || commentaire.length < 3) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez saisir un motif de transfert (minimum 3 caractères).');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // 3. Vérification du stock (seulement si avoir_stock = 1)
            if (avoirStockGlobal == 1) {
                var qte = parseInt($('#transfert_quantite').val());
                if (isNaN(qte) || qte < 1) {
                    $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être un nombre entier positif (car le stock est déterminé).');
                    $('#transfer_msg').css('display', 'flex');
                    setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                    return;
                }

                // --- NOUVEAU CALCUL : total = quantité × nombre de stocks ---
                var totalTransfert = qte * nbStocks;
                var maxTransferable = stockActuelGlobal - seuilMinimumGlobal;

                if (totalTransfert > maxTransferable) {
                    $('#transfer_msg').html(
                        '<i class="zmdi zmdi-close-circle"></i> ' +
                        'La quantité totale à transférer (' + totalTransfert + ') ' +
                        'dépasse le stock disponible après seuil (' + maxTransferable + ').'
                    );
                    $('#transfer_msg').css('display', 'flex');
                    setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                    return;
                }
                // --- FIN NOUVEAU CALCUL ---
            } else {
                // Si avoir_stock = 0, la quantité n'est pas obligatoire (on met 0 si invalide)
                var qte = parseInt($('#transfert_quantite').val());
                if (isNaN(qte) || qte < 0) {
                    $('#transfert_quantite').val(0);
                }
            }

            // 4. Prix détail
            var prix_detail = parseInt($('#transfert_prix_detail_dest').val());
            if (isNaN(prix_detail) || prix_detail < 0) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Le prix de détail doit être un nombre entier positif.');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // 5. Prix gros
            var prix_gros = parseInt($('#transfert_prix_gros_dest').val());
            if (isNaN(prix_gros) || prix_gros < 0) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Le prix de gros doit être un nombre entier positif.');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // 6. Taille lot
            var taille_lot = parseInt($('#transfert_taille_lot_dest').val());
            if (isNaN(taille_lot) || taille_lot < 1) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La taille du lot doit être un nombre entier positif.');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // 7. Devise
            var devise = $('#transfert_devise_dest').val();
            if (devise == '' || devise == null) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez sélectionner une devise.');
                $('#transfer_msg').css('display', 'flex');
                setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                return;
            }

            // Tout est valide → soumettre
            var $btn = $(this);
            var originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Transfert en cours...');

            var formData = $('#form_transfert').serialize();

            $.ajax({
                url: "{{ url('/transfer_article') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#transfer_msg').html('<i class="zmdi zmdi-check-circle"></i> ' + response.message);
                        $('#transfer_msg').css('display', 'flex');
                        $.get("{{ url('/get_all_articles') }}", function(html) {
                            $("#content_utilisateur").html(html);
                            filterArticles();
                        });
                        setTimeout(function() {
                            $('#transfer_msg').html('');
                            $('#transfer_msg').css('display', 'none');
                            $('#transferModal').modal('hide');
                        }, 3000);
                    } else {
                        $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + response.message);
                        $('#transfer_msg').css('display', 'flex');
                        setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                    }
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON?.message || 'Erreur lors du transfert.';
                    $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + msg);
                    $('#transfer_msg').css('display', 'flex');
                    setTimeout(() => { $('#transfer_msg').html(''); $('#transfer_msg').css('display', 'none'); }, 9000);
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });
        // ==========================================================
        // FIN DE LA SECTION MODIFIÉE
        // ==========================================================

        // ---- Réinitialisation à la fermeture ----
        $('#transferModal').on('hidden.bs.modal', function () {
            $('#transfer_msg').html('').css('display', 'none');
            // Réinitialiser le multi-select à "Aucun"
            $('.stock-checkbox[value="none"]').prop('checked', true);
            $('.stock-checkbox:not([value="none"])').prop('checked', false);
            updateStockSelection();
            $('#transfer_submit').prop('disabled', false).html('<i class="zmdi zmdi-swap"></i> Transférer');
            $('#form_transfert')[0].reset();
        });

        // ---- Import / Export ----
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
                        filterArticles();
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

        // Export PDF
        $("#exportPdfBtn").click(function(e) {
            e.preventDefault();
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

        // Export Excel
        $("#exportExcelBtn").click(function(e) {
            e.preventDefault();
            var $btn = $(this);
            var originalHtml = $btn.html();
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
    </script>
@endsection
@endsection
