<?php

use App\Models\Mois;
use App\Models\Postes;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Listespaies;
use App\Models\Writes;
use App\Models\Listesfactures;
use App\Models\User;
use App\Models\Alertes;

?>
@extends('layouts.main')
@section('title', 'CONTROLAPP')
@section('name', 'ALERTE CENTRALE')
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
                + CORRECTION COLONNE CONTROL (icônes bien centrées)
                + STYLES CARTE
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

        /* Alignement vertical centré pour TOUTES les cellules */
        .table tbody td,
        .table tbody th {
            padding: 8px 10px !important;
            vertical-align: middle !important;
            font-weight: 500;
            font-size: 0.85rem;
            color: #1e2a3e;
            word-break: break-word;
        }

        /* ========== LIENS DANS LES CELLULES (version de base) ========== */
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

        /* --- Couleurs spécifiques pour les icônes d'action --- */
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

        /* ========== CORRECTION SPÉCIFIQUE POUR LA COLONNE CONTROL ========== */
        /* Cible TOUS les liens de la dernière colonne (Control) */
        #bloc_1 .table tbody td:last-child a {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 32px !important;
            height: 32px !important;
            min-width: 32px;
            border-radius: 50% !important;
            background: #f1f5f9;
            padding: 0 !important;
            margin: 0 2px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        /* Supprimer les spans vides qui pourraient décaler l'icône */
        #bloc_1 .table tbody td:last-child a span:empty {
            display: none !important;
        }

        /* Centrage parfait de l'icône à l'intérieur du lien */
        #bloc_1 .table tbody td:last-child a i.zmdi {
            margin: 0;
            font-size: 1.2rem;
            line-height: 1;
        }

        /* Couleurs personnalisées pour les icônes de la colonne Control */
        #bloc_1 .table tbody td:last-child a i.zmdi-settings.text-success,
        #bloc_1 .table tbody td:last-child a i.zmdi-mail-send.text-success {
            color: #28a745 !important;
        }

        #bloc_1 .table tbody td:last-child a i.zmdi-settings.text-danger,
        #bloc_1 .table tbody td:last-child a i.zmdi-mail-send.text-danger {
            color: #dc3545 !important;
        }

        /* Effet au survol */
        #bloc_1 .table tbody td:last-child a:hover {
            transform: translateY(-2px);
            background: #e0f2fe;
        }

        /* ========== GARDE LES LIENS TEXTE (EMAIL, WHATSAPP, TÉLÉPHONE) INTACTS ========== */
        .table tbody td a:has(i.zmdi-email, i.zmdi-whatsapp, i.zmdi-phone) {
            width: auto !important;
            height: auto !important;
            border-radius: 40px !important;
            background: #f8fafc !important;
            padding: 5px 12px !important;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table tbody td a:has(i.zmdi-email, i.zmdi-whatsapp, i.zmdi-phone):hover {
            background: #ffffff !important;
            border-color: var(--bleu-nuit);
            transform: translateY(-1px);
            box-shadow: var(--shadow-light);
        }

        .table tbody td a i.zmdi-email {
            color: #3b82f6;
        }

        .table tbody td a i.zmdi-whatsapp {
            color: #25D366;
        }

        .table tbody td a i.zmdi-phone {
            color: #10b981;
        }

        /* Lien "valider paiement" (croix rouge) – reste rond */
        .table tbody td a:has(i.zmdi-close-circle):not(:has(span)) {
            width: 32px;
            height: 32px;
            border-radius: 50% !important;
            background: #f1f5f9;
            padding: 0 !important;
        }

        .table tbody td a:has(i.zmdi-close-circle):not(:has(span)):hover {
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
        a.btn-primary,
        .btn-info,
        .btn-info.btn-sm,
        .btn-danger,
        .btn-danger.btn-sm,
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

        /* ========== MESSAGES MODERNES - TOTALEMENT INVISIBLE PAR DÉFAUT ========== */
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

        /* ========== STYLES POUR LES MONTANTS ET TOTAUX ========== */
        .table tbody td span.text-danger,
        .table tbody td span.text-success,
        .table tbody th span.text-danger,
        .table tbody th span.text-success {
            font-weight: 600;
            font-size: 0.85rem;
            background: rgba(0, 0, 0, 0.02);
            padding: 4px 8px;
            border-radius: 20px;
            display: inline-block;
        }

        h6[style*="text-align: right"] {
            text-align: right !important;
            display: block;
            width: 100%;
            background: white;
            padding: 6px 18px;
            border-radius: 40px;
            box-shadow: var(--shadow-light);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            box-sizing: border-box;
        }

        /* ========== STYLES CARTE ========== */
        #mapPreview {
            border-radius: 0 0 12px 12px;
            background: #f0f2f5;
        }

        .leaflet-popup-content {
            font-size: 0.85rem;
            line-height: 1.4;
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

            .table tbody td,
            .table tbody th {
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

            .table tbody td a:has(i.zmdi-email, i.zmdi-whatsapp, i.zmdi-phone) {
                padding: 3px 8px !important;
                font-size: 0.65rem;
                white-space: normal;
                word-break: keep-all;
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

            .table tbody td,
            .table tbody th {
                font-size: 0.7rem;
                padding: 5px 4px !important;
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

        /* --- AJOUT : Cloche d'alerte --- */
        .alert-bell-active {
            color: #e31b23 !important;
            font-size: 1.6rem;
            animation: bellBlink 1s infinite ease-in-out;
            display: inline-block;
        }

        .alert-bell-inactive {
            color: #a0aec0 !important;
            font-size: 1.6rem;
            display: inline-block;
        }

        @keyframes bellBlink {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
    <section class="content">
        <div style="margin-top: 30px;padding-bottom: 50px;" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Alerte centralisées</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                        Liste</h4>
                    <div id="content_groupe" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Poste</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Officier</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Motif</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Alerte</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Transferer par</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($alertes as $data)
                                            <tr>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    {{ $i }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    {{ Postes::where(['id' => $data->poste_id])->first()['nom'] }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    {{ User::where(['id' => $data->user_id])->first()['name'] }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    {{ $data->motif }}
                                                </td>
                                                <td style="text-align: center; padding-top: 5px; padding-bottom: 5px;">
                                                    @if ($data->etat_1 == 1)
                                                        <i class="zmdi zmdi-notifications-active alert-bell-active" title="Alerte activée"></i>
                                                    @else
                                                        <i class="zmdi zmdi-notifications-off alert-bell-inactive" title="Alerte désactivée"></i>
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data->user_id_transfert == 0)
                                                        <i class="zmdi zmdi-account text-danger"></i> <span
                                                            class="text-danger">Aucune personne
                                                        </span>
                                                    @else
                                                        @if ($data->user_id_transfert == Auth::user()->id)
                                                            <i class="zmdi zmdi-account text-success"></i> <span
                                                                class="text-success">Vous</span>
                                                        @else
                                                            <i class="zmdi zmdi-account text-success"></i> <span
                                                                class="text-success">{{ User::where(['id' => $data->user_id_transfert])->first()['name'] }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data->etat_1 == 0)
                                                        <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                                            class="text-danger">Désactivé </span>
                                                    @endif
                                                    @if ($data->etat_1 == 1)
                                                        <i class="zmdi zmdi-check-circle text-success"></i> <span
                                                            class="text-success">Activé
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data->etat_1 == 1)
                                                        <a id="activer_<?= $i ?>" title="Activé" href="#"><i
                                                                class="zmdi zmdi-settings text-success"></i> <span
                                                                class="text-warning"></span></a>
                                                    @endif
                                                    @if ($data->etat_1 == 0)
                                                        <a id="activer__<?= $i ?>" title="Désactivé" href="#"><i
                                                                class="zmdi zmdi-settings text-danger"></i> <span
                                                                class="text-danger"></span></a>
                                                    @endif
                                                    @if ($data->user_id_transfert == 0)
                                                        <a id="transferer_<?= $i ?>" title="Envoyer" href="#"><i
                                                                class="zmdi zmdi-mail-send text-danger"></i> <span
                                                                class="text-warning"></span></a>
                                                    @endif
                                                    @if ($data->user_id_transfert != 0)
                                                        <a id="transferer__<?= $i ?>" title="Deja envoyé" href="#"><i
                                                                class="zmdi zmdi-mail-send text-success"></i> <span
                                                                class="text-danger"></span></a>
                                                    @endif
                                                    <a id="map_<?= $i ?>" title="Voir sur la carte" href="#"><i
                                                            class="zmdi zmdi-map text-info"></i> <span
                                                            class="text-danger"></span></a>
                                                    <script>
                                                        $("#activer_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element_1").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                                            $("#data_id").html("<?= $data->id ?>");
                                                            $("#btn_ac").trigger("click");
                                                        });
                                                        $("#activer__<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element_3").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                                            $("#data_id").html("<?= $data->id ?>");
                                                            $("#btn_cll").trigger("click");
                                                        });
                                                        $("#transferer_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element_transfert").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                                            $("#data_id").html("<?= $data->id ?>");
                                                            $("#btn_tra").trigger("click");
                                                        });
                                                        $("#transferer__<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element_transfert_e").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                                            $("#data_id").html("<?= $data->id ?>");
                                                            $("#btn_tra_e").trigger("click");
                                                        });
                                                        $("#map_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            var latitude = "{{ $data->latitude }}";
                                                            var longitude = "{{ $data->longitude }}";
                                                            var titre = "{{ addslashes($data->motif) }}";

                                                            // Vérifier si les coordonnées sont valides
                                                            if (!latitude || !longitude || latitude == 0 || longitude == 0) {
                                                                $("#mapError").show();
                                                                $("#mapPreview").hide();
                                                                $("#mapModal").modal('show');
                                                                return;
                                                            }

                                                            $("#mapError").hide();
                                                            $("#mapPreview").show();

                                                            // Attendre que le modal soit ouvert pour initialiser la carte
                                                            $("#mapModal").one('shown.bs.modal', function() {
                                                                // Si une carte existe déjà, on la détruit
                                                                if (window.alertMapInstance) {
                                                                    window.alertMapInstance.remove();
                                                                }

                                                                // Créer une nouvelle carte
                                                                var map = L.map('mapPreview').setView([latitude, longitude], 15);
                                                                window.alertMapInstance = map;

                                                                // Ajouter le fond de carte (OpenStreetMap avec style CartoDB)
                                                                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                                                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB',
                                                                    subdomains: 'abcd',
                                                                    maxZoom: 19,
                                                                    minZoom: 3
                                                                }).addTo(map);

                                                                // Ajouter un marqueur
                                                                var marker = L.marker([latitude, longitude]).addTo(map);
                                                                marker.bindPopup(`
                                                                    <b>Alerte :</b> ${titre}<br>
                                                                    <b>Coordonnées :</b><br>
                                                                    Lat: ${latitude}<br>
                                                                    Lng: ${longitude}
                                                                `).openPopup();

                                                                // Ajouter un cercle rouge clair d'un rayon de 10 mètres
                                                                L.circle([latitude, longitude], {
                                                                    color: '#ff4444',
                                                                    fillColor: '#ff8888',
                                                                    fillOpacity: 0.4,
                                                                    radius: 50
                                                                }).addTo(map);

                                                                // Redimensionner la carte après ouverture (correction d'affichage)
                                                                setTimeout(function() {
                                                                    map.invalidateSize();
                                                                }, 200);
                                                            });

                                                            $("#mapModal").modal('show');
                                                        });
                                                    </script>
                                                </td>
                                            </tr>
                                            {{ !$i++ }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                        Ajouter</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-calendar"></i> Année </span></label>
                                    <select id="annee_id" name="annee_id" class="select2"
                                        data-placeholder="Selectionnez une année">
                                        <option selected value="">Selectionnez une année</option>
                                        @foreach ($annees as $data)
                                            <option value="{{ $data->id }}"><?= $data->annees ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-calendar"></i> Mois </span></label>
                                    <select id="moi_id" name="moi_id" class="select2"
                                        data-placeholder="Selectionnez un mois">
                                        <option selected value="">Selectionnez un mois</option>
                                        @foreach ($mois as $data)
                                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button id="save" class="btn btn-info btn-sm">Enregister <i
                                        class="zmdi zmdi-save"></i></button> <button id="annuler"
                                    class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
                <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">

                </div>
                <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">

                </div>
                <div id="bloc_5" style="margin-top: 12px;display: none;" class="col-lg-12">
                    <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </section>
    <span id="listesfactures_id" style="display: none;"></span>
    <span id="data_id" style="display: none;"></span>
    <span id="alerte_active" style="display: none;" style="">{{ Alertes::where(["supprimer" => 0, "etat_1" => 1])->get()->count(); }}</span>
    <span id="data_frais_id" style="display: none;"></span>
    <span id="devise_paie_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#activation" id="btn_ac">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#cloture" id="btn_cl">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#cloturee" id="btn_cll">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#attendre" id="btn_att">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#transfert" id="btn_tra">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#transfert_e" id="btn_tra_e">Sup</button>

    <!-- Modals -->
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        supprimez cette paie ? </h5>
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

    <div class="modal fade" id="activation" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        désactivez cette alerte ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element_1" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_1" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_1" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transfert" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        transmettre cette alerte ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element_transfert" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_transfert" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_transfert" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cloture" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous cloturez cette liste de facture ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element_2" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_2" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_2" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cloturee" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Cette alerte
                        est deja desactivée </h5>
                </div>
                <div class="modal-body">
                    <p id="element_3" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <button style="font-weight: bold;" id="non_3" class="btn btn-danger btn-sm"
                            data-dismiss="modal">D'accord</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transfert_e" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Cette alerte
                        est deja transmise </h5>
                </div>
                <div class="modal-body">
                    <p id="element_transfert_e" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <button style="font-weight: bold;" id="non_transfert_e" class="btn btn-danger btn-sm"
                            data-dismiss="modal">D'accord</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendre" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce solde est
                        en attente</h5>
                </div>
                <div class="modal-body">
                    <p id="element_4" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <button style="font-weight: bold;" id="non_4" class="btn btn-danger btn-sm"
                            data-dismiss="modal">D'accord merci</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la carte -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--bleu-nuit-gradient);">
                    <h5 class="modal-title text-white" style="font-weight: bold;">
                        <i class="zmdi zmdi-map"></i> Localisation de l'alerte
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div id="mapPreview" style="height: 450px; width: 100%; border-radius: 0 0 12px 12px;"></div>
                    <div id="mapError" class="text-center text-danger p-3" style="display: none;">
                        <i class="zmdi zmdi-alert-circle"></i> Coordonnées non disponibles pour cette alerte.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="zmdi zmdi-close-circle"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#modal-centered"
        id="btn_sup_">Vertically centered</button>
    <div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="modal-centered" data-backdrop="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:40%;">
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
                                        <tr>
                                            <th>Nom</th>
                                            <th style="text-align: right;"><span id="nom_p"></span></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Adresse</th>
                                            <th style="text-align: right;"><span id="role_p"></span></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Paiement</th>
                                            <th style="text-align: right;"><span id="reste_p">0</span>/<span
                                                    id="total_p" style="font-weight: bold;">100</span><span
                                                    id="devise_p">$</span></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div style="margin-top: 20px;" class="col-12">
                            <input type="text" id="montant_p" name="montant_p"
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                                placeholder="Entrez le montant">
                        </div>
                        <div style="margin-top: 20px;" class="col-12">
                            <input type="text" id="taux_p" name="taux_p"
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                                placeholder="Entrez le taux" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_paie" class="btn btn-info btn-sm">Enregistrer <i
                            class="zmdi zmdi-save"></i></button>
                    <button type="button" id="annuler_paie" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer
                        <i class="zmdi zmdi-close-circle"></i></button>
                </div>
                <p style="text-align: center;font-weight: bold;" id="m_paie"></p>
            </div>
        </div>
    </div>

    <button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#activite"
        id="btn_activite">Vertically centered</button>
    <div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="activite" data-backdrop="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div style="border: 1px solid black;" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left" style="color: black;font-weight: bold;">Activité</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" style="font-weight: bold;color:black;">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-info-circle"></i> Voulez-vous la liste de facture de quelle activité ?
                                </span></label>
                            <select
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                id="activite_id_a" name="activite_id_a" class="form-control"
                                data-placeholder="Selectionnez une année">
                                <option selected value="">Selectionnez une activite</option>
                                @foreach ($activites as $data)
                                    <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save_print" class="btn btn-info btn-sm">Enregistrer <i
                            class="zmdi zmdi-save"></i></button>
                    <button type="button" id="annuler_print" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer
                        <i class="zmdi zmdi-close-circle"></i></button>
                </div>
                <p style="text-align: center;font-weight: bold;" id="msg_p"></p>
            </div>
        </div>
    </div>

@section('js-code')
    <!-- Leaflet CSS et JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
    // ==================== SURVEILLANCE DES ALERTES ACTIVES ====================
    let currentAudio = null;           // Référence vers le son en cours

    function arreterSonAlerte() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }
    }

    function jouerSonAlerte() {
        // Arrête le précédent pour relancer un nouveau son (évite la superposition)
        arreterSonAlerte();

        const urlSon = "{{ asset('connexion/images/son_alerte_1.mp3') }}";
        const son = new Audio(urlSon);
        son.play().catch(error => {
            console.warn("Lecture automatique impossible, attente d'une interaction", error);
        });

        son.onended = function() {
            if (currentAudio === son) currentAudio = null;
        };

        currentAudio = son;
    }

    function checkAndTriggerAlert() {
        const rows = document.querySelectorAll('#bloc_1 .table tbody tr');
        let hasActiveAlert = false;

        rows.forEach(row => {
            const etatCell = row.cells[6];
            if (etatCell) {
                const icon = etatCell.querySelector('.zmdi-check-circle.text-success');
                const span = etatCell.querySelector('span.text-success');
                if (icon && span && span.textContent.trim() === 'Activé') {
                    hasActiveAlert = true;
                }
            }
        });

        if (hasActiveAlert) {
            // On joue le son à chaque détection d'alerte active (même si déjà joué)
            jouerSonAlerte();
        } else {
            // Plus aucune alerte → on coupe le son
            arreterSonAlerte();
        }
    }

    // ==================== ACTIONS PRINCIPALES ====================
    $(document).ready(function() {
        checkAndTriggerAlert();

        $("#oui_1").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_desactiver_alerte_centrale') }}", {
                id: id,
            }, function(refresh_editverbalisateur) {
                $("#content_groupe").html(refresh_editverbalisateur);
                $("#non_1").trigger("click");
                checkAndTriggerAlert();
            });
        });

        $("#oui_transfert").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_transfert_alerte_centrale') }}", {
                id: id,
            }, function(refresh_editverbalisateur) {
                $("#content_groupe").html(refresh_editverbalisateur);
                $("#non_transfert").trigger("click");
                checkAndTriggerAlert();
            });
        });

        // ========== SURVEILLANCE AUTOMATIQUE TOUTES LES 15 SECONDES ==========
        setInterval(function() {
            var alerte_active = parseInt($("#alerte_active").html());
            $.get("{{ url('/count_alerte_etat_1') }}", {}, function(nombre_alerte_etat_1) {
                if (nombre_alerte_etat_1 != alerte_active) {
                    // Changement détecté → on joue le son immédiatement
                    jouerSonAlerte();

                    $("#alerte_active").html(nombre_alerte_etat_1);
                    $.get("{{ url('/refresh_alerte_centrale') }}", {}, function(refresh_alerte_centrale) {
                        $("#content_groupe").html(refresh_alerte_centrale);
                        // Après le refresh, on refait une vérification complète
                        checkAndTriggerAlert();
                    });
                } else {
                    // Pas de changement, mais on vérifie quand même (cela relancera le son si alerte active)
                    checkAndTriggerAlert();
                }
            });
        }, 15000);
    });

    // ==================== GESTION DES ONGLETS ET FORMULAIRES ====================
    // (toutes les autres fonctions restent strictement identiques à votre code original)
    $("#link_33").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_33").addClass("text-info");
    $("#icone_33").css("color", "rgb(33, 150, 243)");

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
        $("#bloc_5").hide();
    });

    $("#add").click(function(e) {
        e.preventDefault();
        $("#bloc_1").hide();
        $("#bloc_2").show();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        $("#bloc_5").hide();
    });

    $("#annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        $("#bloc_5").hide();
    });

    $("#save").click(function(e) {
        e.preventDefault();
        var annee_id = $("#annee_id").val();
        var moi_id = $("#moi_id").val();
        var data = $("#form_add").serialize();
        if (annee_id.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une année');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => { $('#msg').html(""); }, 9000);
        } else if (moi_id.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un mois');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => { $('#msg').html(""); }, 9000);
        } else {
            $.get("{{ url('/check_solde_2') }}", { annee_id: annee_id, moi_id: moi_id }, function(rep) {
                if (rep != 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Cette liste de facture existe');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    $.get("{{ url('/check_solde_encours_2') }}", {}, function(resp_solde) {
                        var rr = resp_solde.split("__________");
                        if (rr[0] != 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La facture de ' + rr[1] + ' est activé.');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else {
                            $("#save").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "/add_listesfactures",
                                data: data,
                                success: function(response) {
                                    $("#save").attr("disabled", false);
                                    $.get("{{ url('/get_mois_2') }}", { annee_id: annee_id }, function(response) {
                                        $("#moi_id").html(response);
                                    });
                                    $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Liste de facture ajoutée avec succès');
                                    $('#msg').css("color", '#32c787');
                                    $("#content_groupe").html(response);
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                }
                            });
                        }
                    });
                }
            });
        }
    });

    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_delete_listesfactures') }}", { id: id }, function(refresh_editverbalisateur) {
            $("#content_groupe").html(refresh_editverbalisateur);
            $("#non").trigger("click");
        });
    });

    $("#oui_2").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_cloturer_listesfactures') }}", { id: id }, function(refresh_editverbalisateur) {
            $("#content_groupe").html(refresh_editverbalisateur);
            $("#non_2").trigger("click");
        });
    });

    $("#annee_id").change(function(e) {
        e.preventDefault();
        var annee_id = $("#annee_id").val();
        $.get("{{ url('/get_mois_2') }}", { annee_id: annee_id }, function(response) {
            $("#moi_id").html(response);
        });
    });

    var annee_id = $("#annee_id").val();
    if (annee_id.trim().length == 0) {
        $.get("{{ url('/get_mois_2') }}", { annee_id: annee_id }, function(response) {
            $("#moi_id").html(response);
        });
    }

    $("#save_print").click(function(e) {
        e.preventDefault();
        var activite_id_a = $("#activite_id_a").val();
        var listesfactures_id = $("#listesfactures_id").html();
        if (activite_id_a.trim().length == 0) {
            $('#msg_p').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une activité');
            $('#msg_p').css('color', "#ff6b68");
            setTimeout(() => { $('#msg_p').html(""); }, 9000);
        } else {
            $.get("{{ url('/get_print_listes_factures') }}", {
                listesfactures_id: listesfactures_id,
                activite_id: activite_id_a,
            }, function(response) {
                $("#annuler_print").trigger("click");
                $("#bloc_1").hide();
                $("#bloc_2").hide();
                $("#bloc_3").hide();
                $("#bloc_4").hide();
                $("#bloc_5").show();
                $("#data_liste").attr('src', '{{ asset('') }}' + response);
            });
        }
    });
</script>
@endsection
@endsection
