<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
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
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'FACTURES')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* =============================================
        DESIGN PREMIUM - VERSION FACTURES COMPLÈTE
        UNE SEULE COLONNE MONTANT + CONVERSION
        BADGES TOTAUX USD / CDF
        FILTRE DATE TEXTE LIBRE
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

        #form_add .col-12,
        #form_edit .col-12 {
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
            margin-bottom: 6px;
            font-size: 0.75rem;
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
            padding: 8px 12px !important;
            font-weight: 500;
            font-size: 0.8rem;
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
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filter-group .form-control {
            height: 42px;
        }

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
        }

        /* Animation pour les lignes filtrées */
        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr.highlight {
            background-color: #fff3cd !important;
            animation: highlightFlash 1s ease;
        }

        @keyframes highlightFlash {
            0% { background-color: #fff3cd; }
            100% { background-color: transparent; }
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

        /* ========== STYLES EXCLUSIFS POUR LA MODALE DE PAIEMENT PDF ========== */
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

        .modal.fade#pdfModal .modal-header .close:hover {
            opacity: 1;
        }

        .modal.fade#pdfModal .modal-body {
            padding: 0;
            background: #f8fafc;
        }

        .modal.fade#pdfModal .modal-footer {
            background: white;
            border-top: 1px solid #eef2f6;
            padding: 1.2rem 1.5rem;
        }

        /* IFRAME PDF */
        .modal.fade#pdfModal #pdfIframe {
            width: 100%;
            height: 50vh;
            border: none;
            background: white;
        }

        /* Zone de contrôle (montant + devise + bouton) */
        .modal.fade#pdfModal #boite_de_control {
            margin: 0 !important;
        }

        .modal.fade#pdfModal #boite_de_control .col-lg-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
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

        /* Bouton payer */
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

        .modal.fade#pdfModal #btn_payer:active {
            transform: translateY(0);
        }

        /* ========== MESSAGE D'ERREUR / SUCCÈS POUR LA MODALE (#msg_facture) ========== */
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

        /* Message d'erreur (rouge) */
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-close-circle),
        .modal.fade#pdfModal #msg_facture[style*="color: #dc3545"] {
            background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
            color: #991b1b !important;
            border-left: 4px solid #dc2626 !important;
        }

        /* Message de succès (vert) */
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-check-circle),
        .modal.fade#pdfModal #msg_facture[style*="color: #28a745"] {
            background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
            color: #065f46 !important;
            border-left: 4px solid #10b981 !important;
        }

        /* Message d'attention (orange) */
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-alert),
        .modal.fade#pdfModal #msg_facture[style*="color: #ffc107"] {
            background: linear-gradient(95deg, #fed7aa, #ffedcc) !important;
            color: #9b4d00 !important;
            border-left: 4px solid #f59e0b !important;
        }

        /* Animation du message */
        @keyframes fadeInMsg {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bouton fermer */
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

        /* Séparateur */
        .modal.fade#pdfModal hr {
            margin: 15px 0;
            border: 0;
            border-top: 1px solid #eef2f6;
        }

        /* ========== RESPONSIVE POUR LA MODALE ========== */
        @media (max-width: 992px) {
            .modal.fade#pdfModal .modal-dialog {
                width: 80%;
            }
        }

        @media (max-width: 768px) {
            .modal.fade#pdfModal .modal-dialog {
                width: 95%;
                margin: 1rem auto;
            }

            .modal.fade#pdfModal #pdfIframe {
                height: 40vh;
            }

            .modal.fade#pdfModal #boite_de_control .col-lg-4 {
                margin-bottom: 10px;
            }

            .modal.fade#pdfModal #btn_payer {
                width: 100%;
            }

            .modal.fade#pdfModal .modal-footer {
                padding: 1rem;
            }

            .modal.fade#pdfModal #montant_recu,
            .modal.fade#pdfModal #devise_recu {
                height: 40px;
                font-size: 0.8rem;
            }

            .modal.fade#pdfModal #msg_facture {
                font-size: 0.75rem;
                padding: 8px 14px;
            }
        }

        @media (max-width: 480px) {
            .modal.fade#pdfModal .modal-header {
                padding: 0.8rem 1rem;
            }

            .modal.fade#pdfModal .modal-header .modal-title {
                font-size: 1rem;
            }

            .modal.fade#pdfModal #pdfIframe {
                height: 35vh;
            }

            .modal.fade#pdfModal #btn_payer {
                padding: 8px 16px;
                font-size: 0.75rem;
            }

            .modal.fade#pdfModal .btn-secondary {
                padding: 6px 16px;
                font-size: 0.7rem;
            }

            .modal.fade#pdfModal #msg_facture {
                font-size: 0.7rem;
                padding: 6px 12px;
            }
        }

        /* ========== RESPONSIVE GLOBAL ========== */
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

        /* Styles pour le bloc_3 modification */
        #bloc_3 .form-group {
            margin-bottom: 0.8rem;
        }

        #bloc_3 h4 i.zmdi-edit {
            background: var(--bleu-nuit-gradient);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent !important;
        }


        /* ========== BLOCS 2 ET 3 : MÊME TAILLE SUR TOUS LES APPAREILS ========== */
        .row:has(#bloc_2, #bloc_3) {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: stretch !important;
            gap: 25px !important;
            width: 100% !important;
        }

        #bloc_2,
        #bloc_3 {
            flex: 1 1 calc(50% - 25px) !important;
            width: auto !important;
            min-width: 280px !important;
            margin: 0 !important;
        }

        @media (max-width: 992px) and (min-width: 769px) {
            .row:has(#bloc_2, #bloc_3) {
                gap: 20px !important;
            }
            #bloc_2, #bloc_3 {
                flex: 1 1 calc(50% - 20px) !important;
                min-width: 250px !important;
            }
        }

        @media (max-width: 768px) {
            .row:has(#bloc_2, #bloc_3) {
                flex-direction: column !important;
                gap: 20px !important;
                align-items: center !important;
            }
            #bloc_2, #bloc_3 {
                flex: 1 1 100% !important;
                width: 100% !important;
                min-width: auto !important;
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
                                    <a class="btn-primary btn-sm" id="add" href="">
                                        <i class="zmdi zmdi-email"></i> Ajouter
                                    </a>
                                    <?php } else { ?>
                                    <a class="btn-primary btn-sm" id="add_r" href="">
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
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de facture</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                            class="zmdi zmdi-email-open text-info"></i> Liste</h4>

                    <!-- SECTION FILTRES MODIFIÉE -->
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
                            <label><i class="zmdi zmdi-money text-danger"></i> Statut</label>
                            <select id="filterStatut" class="form-control">
                                <option value="all">Tous</option>
                                <option value="paid">Payées</option>
                                <option value="unpaid">Impayées</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Date</label>
                            <input type="text" id="filterDate" class="form-control" placeholder="jj/mm/aaaa ...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-chart text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="Montant exact" step="0.01">
                        </div>
                        <div style="display: none;" class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Badge compteur et totaux convertis -->
                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0a192f, #1e3a5f);">
                            <i class="zmdi zmdi-view-list"></i> Factures : <span id="invoiceCount">0</span>
                        </span>
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0f4c5f, #1e6f5c);">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsd">0,00</span> $
                        </span>
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdf">0,00</span> Fc
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
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Mode de paiement</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($factures as $data)
                                            @php
                                                $taux = $data->taux;
                                                $total = 0;
                                                $ent = Achats::where('facture_id', $data->id)->get();
                                                foreach ($ent as $e) {
                                                    $total += $e->total;
                                                }
                                                if ($data->devise == 0) {
                                                    $montant_usd = $total;
                                                    $montant_cdf = $total * $taux;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                                                } else {
                                                    $montant_cdf = $total;
                                                    $montant_usd = $total / $taux;
                                                    $montant_affichage = number_format($total, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                                                }
                                            @endphp
                                            <tr id="row_{{ $data->id }}"
                                                data-montant-usd="{{ $montant_usd }}"
                                                data-montant-cdf="{{ $montant_cdf }}">
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell" data-client="{{ $data->client_id == 0 ? $data->libelle : (Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A') }}">
                                                    @if ($data->client_id == 0)
                                                        {{ $data->libelle }}
                                                    @else
                                                        <?= Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' ?>
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="{{ $total }}">
                                                    {{ $montant_affichage }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ $data->created_at }}">
                                                    <?php
                                                        $date = $data->created_at;
                                                        $date_1 = explode(' ', $date);
                                                        echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                                    ?>
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell" data-statut="{{ $data->payer == 0 ? 'unpaid' : 'paid' }}">
                                                    @if ($data->payer == 0)
                                                        <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                                            class="text-danger">{{ 'Aucun' }} </span>
                                                    @else
                                                        @if ($data->mode_de_paiement == 1)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span
                                                                class="text-success">CASH</span>
                                                        @endif
                                                        @if ($data->mode_de_paiement == 2)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span
                                                                class="text-success">Mobile money</span>
                                                        @endif
                                                        @if ($data->mode_de_paiement == 3)
                                                            <i class="zmdi zmdi-check-circle text-success"></i> <span
                                                                class="text-success">Bank</span>
                                                        @endif
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
                                                        @if ($data->payer == 0)
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                                        @else
                                                            <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        @endif
                                                    <?php } else { ?>
                                                        @if ($data->payer == 0)
                                                            <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                                        @else
                                                            <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                                        @endif
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
                                            {{ !$i++ }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LIGNE POUR BLOC_2 ET BLOC_3 CÔTE À CÔTE -->
                <div id="bloc_t" class="row" style="width: 100%; margin: 0;">
                    <!-- Bloc 2 à gauche -->
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
                                                @if ($data->activite_id != 0)
                                                    <option value="{{ $data->id }}">
                                                        🟢 <?= $data->nom_article .' ' . (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') . ' (' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>
                                                    </option>
                                                @else
                                                    <option disabled value="{{ $data->id }}">
                                                        🔴 <?= $data->nom_article .' ' . (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') . ' (' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?> :  Activité non defini
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: -15px;" class="row">
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
                                            <option selected class="form-control" value="">Selectionnez une devise
                                            </option>
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
                            <div class="row">
                            </div>
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
                                    <span style="font-weight: bold;" id="msg">
                                    </span>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row" id="content_sortie">
                        </div>
                        <br>
                    </div>

                    <!-- Bloc 3 à droite -->
                    <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-7">
                    </div>
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
    <!-- Modal/Fenêtre modale pour afficher le PDF -->
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
        $("#link_24").css("border-left", "1px solid rgb(33, 150, 243)");
        $("#text_24").addClass("text-info");

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
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {
                // bien
            });
        });

        $("#add").click(function(e) {
            $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {
                // bien
            });
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
            } else {
                if (type_sortie.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
                    setTimeout(() => { $('#msg').html(""); }, 9000);
                } else {
                    if (action.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une action');
                        setTimeout(() => { $('#msg').html(""); }, 9000);
                    } else {
                        if(type_vente_id.trim().length == 0){
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez le type de vente detail ou gros');
                            setTimeout(() => { $('#msg').html(""); }, 9000);
                        } else {
                            if (quantite.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            } else {
                                if(quantite.trim() <= 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être supérieur à 0');
                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                } else {
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
                                            if ((data_rep[0] == 0) && (data_rep[3] == 1))
                                            {
                                                $('#msg').html(
                                                    '<i class="zmdi zmdi-close-circle"></i> Le seuil minimum de cette article est de : ' +
                                                    data_rep[1] + ', sortie disponible : ' + data_rep[2]);
                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                            } else if ((data_rep[0] == -1) && (data_rep[3] == 1)) {
                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le stock de cette article est vide');
                                                setTimeout(() => { $('#msg').html(""); }, 9000);
                                            } else {
                                                if(client.trim().length == 0 && libelle.trim().length == 0) {
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le client ou le libellé');
                                                    setTimeout(() => { $('#msg').html(""); }, 9000);
                                                    return;
                                                } else {
                                                    $("#save").attr("disabled", true);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "/add_achat_article",
                                                        data: data,
                                                        success: function(response) {
                                                            $("#save").attr("disabled", false);
                                                            $("#quantite").val("");
                                                            Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                                            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Achat effectué avec succès');
                                                            $("#content_utilisateur").html(response);
                                                            $.get("{{ url('/get_achat') }}", {}, function(response) {
                                                                $("#bloc_3").html(response);
                                                            });
                                                            setTimeout(() => { $('#msg').html(""); }, 9000);
                                                            // Sauvegarder et réappliquer les filtres
                                                            saveFiltersToStorage();
                                                            setTimeout(function() {
                                                                loadFiltersFromStorage();
                                                                filterInvoices();
                                                            }, 100);
                                                        }
                                                    });
                                                }
                                            }
                                        });
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
                    data: {
                        name: name,
                        request: 2
                    },
                    sucess: function(data) {
                        console.log('success: ' + data);
                    }
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
                    data: {
                        name: name,
                        request: 2
                    },
                    sucess: function(data) {
                        console.log('success: ' + data);
                    }
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

        // ========== FONCTIONS DE FILTRAGE AVEC PERSISTANCE ==========
        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                numero: $('#filterNumero').val(),
                client: $('#filterClient').val(),
                user: $('#filterUser').val(),
                statut: $('#filterStatut').val(),
                date: $('#filterDate').val(),
                montant: $('#filterMontant').val()
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
                $('#filterStatut').val(filters.statut || 'all');
                $('#filterDate').val(filters.date || '');
                $('#filterMontant').val(filters.montant || '');
                return true;
            }
            return false;
        }

        function filterInvoices() {
            const filterNumero = $('#filterNumero').val().toLowerCase();
            const filterClient = $('#filterClient').val().toLowerCase();
            const filterUser = $('#filterUser').val().toLowerCase();
            const filterStatut = $('#filterStatut').val();
            const filterDate = $('#filterDate').val().toLowerCase().trim();
            const filterMontant = parseFloat($('#filterMontant').val());

            let visibleCount = 0;
            let totalUSD = 0, totalCDF = 0;

            $('#content_utilisateur tbody tr').each(function() {
                const $row = $(this);
                let showRow = true;

                const numeroValue = $row.find('.numero-cell').data('numero')?.toLowerCase() || '';
                const clientValue = $row.find('.client-cell').data('client')?.toLowerCase() || '';
                const userValue = $row.find('.user-cell').data('user')?.toLowerCase() || '';
                const statutValue = $row.find('.statut-cell').data('statut') || '';
                const dateDisplayed = $row.find('.date-cell').text().toLowerCase();
                const montantRaw = parseFloat($row.find('.montant-cell').data('montant')) || 0;

                if (filterNumero && !numeroValue.includes(filterNumero)) showRow = false;
                if (showRow && filterClient && !clientValue.includes(filterClient)) showRow = false;
                if (showRow && filterUser && !userValue.includes(filterUser)) showRow = false;
                if (showRow && filterStatut !== 'all' && statutValue !== filterStatut) showRow = false;
                if (showRow && filterDate && !dateDisplayed.includes(filterDate)) showRow = false;
                if (showRow && !isNaN(filterMontant) && Math.abs(montantRaw - filterMontant) > 0.009) showRow = false;

                if (showRow) {
                    $row.show();
                    visibleCount++;
                    totalUSD += parseFloat($row.data('montant-usd')) || 0;
                    totalCDF += parseFloat($row.data('montant-cdf')) || 0;
                } else {
                    $row.hide();
                }
            });

            $('#invoiceCount').text(visibleCount);
            $('#totalUsd').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdf').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            if (visibleCount === 0 && (filterNumero || filterClient || filterUser || filterStatut !== 'all' || filterDate || !isNaN(filterMontant))) {
                $('#msg').html('<i class="zmdi zmdi-info"></i> Aucune facture ne correspond aux critères de recherche');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 3000);
            }
        }

        function resetAllFilters() {
            $('#filterNumero').val('');
            $('#filterClient').val('');
            $('#filterUser').val('');
            $('#filterStatut').val('all');
            $('#filterDate').val('');
            $('#filterMontant').val('');

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

        // Initialisation
        $(document).ready(function() {
            const totalInvoices = $('#content_utilisateur tbody tr').length;
            $('#invoiceCount').text(totalInvoices);
            loadFiltersFromStorage();
            filterInvoices();

            $('#filterNumero, #filterClient, #filterUser, #filterStatut, #filterDate, #filterMontant').on('input change', function() {
                debouncedFilter();
            });

            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetAllFilters();
            });
        });

        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url && (settings.url.includes('get_all_facture') || settings.url.includes('refresh_') || settings.url.includes('add_achat_article'))) {
                setTimeout(() => {
                    loadFiltersFromStorage();
                    filterInvoices();
                }, 200);
            }
        });

        window.addEventListener('beforeunload', function() {
            saveFiltersToStorage();
        });
    </script>

    <script>
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
            cdf_montant_payer = $("#cdf_montant_payer").val();
            usd_montant_payer = $("#usd_montant_payer").val();
            payer = $("#payer").val();
            const btnPayer = this;
            btnPayer.disabled = true;
            const msgFacture = document.getElementById('msg_facture');
            msgFacture.innerHTML = '⏳ Traitement en cours...';
            msgFacture.style.color = '#17a2b8';
            const originalBtnText = btnPayer.innerHTML;
            btnPayer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Chargement...';

            try {
                const factureId = $("#id_fac").val();
                if (!factureId || factureId === "") {
                    msgFacture.innerHTML = '❌ Erreur: Identifiant de facture manquant';
                    msgFacture.style.color = '#dc3545';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                    return;
                }

                try {
                    const response = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
                    if (response == 1) {
                        msgFacture.innerHTML = '⚠️ Facture déjà payée';
                        msgFacture.style.color = '#ffc107';
                        setTimeout(() => { msgFacture.innerHTML = ''; }, 5000);
                        return;
                    }

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
                    let montantAPayer;
                    if (deviseRecu === "0") {
                        montantAPayer = convertirEnNombre(usd_montant_payer);
                    } else {
                        montantAPayer = convertirEnNombre(cdf_montant_payer);
                    }

                    if (isNaN(montantAPayer) || montantAPayer <= 0) {
                        msgFacture.innerHTML = '❌ Erreur: Montant à payer invalide';
                        msgFacture.style.color = '#dc3545';
                        setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
                        return;
                    }

                    if (montantRecu < montantAPayer) {
                        const reste = montantAPayer - montantRecu;
                        msgFacture.innerHTML = `❌ MONTANT INSUFFISANT !<br>📄 Montant à payer : ${montantAPayer.toFixed(2)} ${deviseLabel}<br>`;
                        msgFacture.style.color = '#dc3545';
                        document.getElementById('montant_recu').focus();
                        setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
                        return;
                    }

                    const monnaie = montantRecu - montantAPayer;
                    const saveResponse = await $.post("{{ url('/save_paie_facture') }}", {
                        _token: "{{ csrf_token() }}",
                        facture_id: factureId,
                        montant_recu: montantRecu,
                        devise_recu: deviseRecu,
                        montant_paye: montantAPayer,
                        monnaie: monnaie
                    });

                    if (saveResponse.success || saveResponse == 1) {
                        msgFacture.innerHTML = `✅ PAIEMENT RÉUSSI !<br>📄 Montant à payer : ${montantAPayer.toFixed(2)} ${deviseLabel}<br>💵 Montant reçu : ${montantRecu.toFixed(2)} ${deviseLabel}<br>💰 Reste (monnaie) : ${monnaie.toFixed(2)} ${deviseLabel}`;
                        msgFacture.style.color = '#28a745';
                        document.getElementById('montant_recu').value = '';

                        var pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";
                        if (pdfUrl && pdfUrl !== '') {
                            currentPdfUrl = pdfUrl;
                            $("#pdfIframe").attr("src", pdfUrl);
                        } else {
                            await $.get("{{ url('/print_facture') }}", { "facture_id": factureId }, function(response) {
                                if (response && response[0][0]) {
                                    currentPdfUrl = response[0][0];
                                    $("#pdfIframe").attr("src", response[0][0]);
                                    $("#cdf_montant_payer").val(response[0][1]);
                                    $("#usd_montant_payer").val(response[0][2]);
                                    $("#payer").val(response[0][5]);
                                } else if (response && typeof response[0][0] === 'string') {
                                    currentPdfUrl = response[0][0];
                                    $("#pdfIframe").attr("src", response[0][0]);
                                    $("#cdf_montant_payer").val(response[0][1]);
                                    $("#usd_montant_payer").val(response[0][2]);
                                    $("#payer").val(response[0][5]);
                                } else {
                                    alert("Aucun PDF disponible pour cette facture.");
                                }
                            }).fail(function() {
                                alert("Erreur lors de la récupération du PDF.");
                            });
                        }

                        await $.get("{{ url('/get_all_facture') }}", {}, function(response) {
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
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 15000);
                } catch (error) {
                    console.error("Erreur API:", error);
                    msgFacture.innerHTML = '❌ Erreur de connexion à l\'API';
                    msgFacture.style.color = '#dc3545';
                    setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
                }
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
