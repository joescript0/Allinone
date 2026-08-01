@php
    use App\Models\appnames;
    use App\models\affectationstables;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php
    use App\Models\Pointdeventes;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'DEBARRASSEUR(SE)')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* ============================================================
           DESIGN PREMIUM – UNIFIÉ (GESTION DE TABLE)
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

        /* ========== TABLEAU ========== */
        .table-responsive {
            border-radius: var(--border-radius-lg);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            min-width: 700px;
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

        /* ========== LIENS D'ACTION DANS LE TABLEAU (seulement nettoyage) ========== */
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

        .table tbody td a i.zmdi-check-circle {
            color: #10b981;
        }
        .table tbody td a i.zmdi-close-circle {
            color: #f59e0b;
        }

        .table tbody td a:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }
        .table tbody td a:hover i.zmdi-check-circle {
            color: #047857;
        }
        .table tbody td a:hover i.zmdi-close-circle {
            color: #d97706;
        }

        .table tbody td a.disabled-link {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        .table tbody td a.disabled-link:hover {
            background: #f1f5f9;
            transform: none;
        }

        /* ========== BADGES DE PROPRETÉ ========== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .status-badge.propre {
            background: #d1fae5;
            color: #065f46;
        }
        .status-badge.sale {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ========== BOUTONS PRINCIPAUX (UNIFIÉS) ========== */
        #liste, #add, #print, #add_r, #print_r,
        #save, #annuler, #edit_save, #edit_annuler,
        .btn-primary, .btn-info, .btn-danger {
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

        #liste, .btn-primary {
            background: #3B82F6 !important;
            color: white !important;
        }
        #liste:hover, .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
            background: #2563eb !important;
        }

        #add, .btn-info {
            background: var(--bleu-nuit-gradient) !important;
            color: white !important;
        }
        #add:hover, .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
        }

        #save, #edit_save {
            background: var(--bleu-secondaire-gradient) !important;
            color: white;
        }
        #save:hover, #edit_save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
        }

        #annuler, #edit_annuler, .btn-danger {
            background: var(--rouge-gradient) !important;
            color: white;
        }
        #annuler:hover, #edit_annuler:hover, .btn-danger:hover {
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

        /* ========== FORMULAIRES ========== */
        #form_add .row, #form_edit .row {
            display: flex;
            flex-wrap: wrap;
        }

        #form_add .col-6, #form_edit .col-6 {
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
        #msg, #edit_msg {
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

        #msg:not(:empty), #edit_msg:not(:empty) {
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
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
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
            #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
                padding: 1rem !important;
            }
        }

        @media (max-width: 768px) {
            .content .container {
                padding: 0.4rem 0.6rem !important;
            }
            #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
                padding: 0.8rem !important;
            }
            #liste, #add, #print, #add_r, #print_r,
            #save, #annuler, #edit_save, #edit_annuler,
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
            .filter-group {
                width: 100%;
                min-width: 100%;
            }
            .filter-group .form-control {
                height: 34px !important;
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
            #form_add .col-6, #form_edit .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .form-group label {
                font-size: 0.65rem;
            }
            .form-control, input.form-control, select.form-control, textarea.form-control {
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
            #bloc_1, #bloc_2, #bloc_3, #bloc_4 {
                padding: 0.6rem !important;
            }
            h4 {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            h4 i {
                font-size: 24px !important;
            }
            #liste, #add, #print, #add_r, #print_r,
            #save, #annuler, #edit_save, #edit_annuler {
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

        /* ========== MODALES ========== */
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
        .modal-header.nettoyer-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }
        .modal-header.nettoyer-error {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        }
        .modal-header.nettoyer-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
        }
    </style>
    <section class="content">
        <div style="margin-top: 30px;" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Débarrasseur(se)</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;padding-bottom: 50px" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                        Liste
                        <span class="article-count-badge" id="tableCountBadge">
                            <i class="zmdi zmdi-view-list"></i> <span id="tableCount">0</span>
                        </span>
                    </h4>

                    <!-- SECTION FILTRES AVEC PERSISTANCE -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> Nom de la table</label>
                            <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-comment text-danger"></i> Description</label>
                            <input type="text" id="filterDescription" class="form-control" placeholder="Rechercher par description...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-store text-danger"></i> Point de vente</label>
                            <select id="filterPointVente" class="form-control">
                                <option value="all">Tous les points de vente</option>
                                @foreach ($point_ventes as $pv)
                                    <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-check-circle text-danger"></i> État</label>
                            <select id="filterEtat" class="form-control">
                                <option value="all">Tous</option>
                                <option value="0">Libre</option>
                                <option value="1">Occupée</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-spa text-danger"></i> Propreté</label>
                            <select id="filterPropre" class="form-control">
                                <option value="all">Tous</option>
                                <option value="1">Sale</option>
                                <option value="0">Propre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
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
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Propreté</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $user = Auth::user();
                                            $i = 1;
                                        @endphp
                                        @foreach ($tables as $data)
                                            @php
                                                $affecte = false;
                                                if ($user->role != 0) {
                                                    $affecte = affectationstables::where('user_id', $user->id)
                                                                ->where('table_id', $data->id)
                                                                ->exists();
                                                }
                                            @endphp
                                            @if ($user->role == 0 || $affecte)
                                                <tr id="table_row_{{ $data->id }}"
                                                    data-propre="{{ $data->propre }}"
                                                    data-nom="{{ $data->nom }}"
                                                    data-desc="{{ $data->description }}"
                                                    data-pointvente="{{ $data->pointdeventes_id }}">
                                                    <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                                    <td class="nom-cell" data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                                    <td class="desc-cell" data-desc="{{ $data->description }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                                                    <td class="pointvente-cell" data-pointvente-id="{{ $data->pointdeventes_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                        {{ $data->pointdeventes_id != null ? \App\Models\Pointdeventes::where('id', $data->pointdeventes_id)->first()->nom : 'Aucun point de vente' }}
                                                    </td>
                                                    <td class="propre-cell" data-propre="{{ $data->propre }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data->propre == 1)
                                                            <span class="status-badge sale">
                                                                <i class="zmdi zmdi-close-circle"></i> Sale
                                                            </span>
                                                        @else
                                                            <span class="status-badge propre">
                                                                <i class="zmdi zmdi-check-circle"></i> Propre
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                        @if ($data->propre == 1)
                                                            <a href="#" class="clean-link" data-table-id="{{ $data->id }}" data-propre="1">
                                                                <i class="zmdi zmdi-close-circle"></i>
                                                            </a>
                                                        @else
                                                            <a href="#" class="clean-link disabled-link" data-table-id="{{ $data->id }}" data-propre="0">
                                                                <i class="zmdi zmdi-check-circle"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $i++; @endphp
                                            @endif
                                        @endforeach
                                        <tr id="noResultRow" style="display: none;">
                                            <td colspan="6">
                                                <i class="zmdi zmdi-info-outline"></i> Aucune table ne correspond à vos critères.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                            class="zmdi zmdi-plus-circle text-info"></i> Ajouter une table</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Nom </span></label>
                                    <input type="text" id="nom" name="nom"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Nom (Ex : Table 1)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-storage text-danger"></i> Point de vente </span></label>
                                    <select id="point_vente_id" name="point_vente_id"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control">
                                        <option class="form-control" value="">Table</option>
                                        @foreach ($point_ventes as $data)
                                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-comment"></i> Description </span></label>
                                    <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Description" name="description" id="description" cols="2"
                                        rows="1"></textarea>
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
            </div>
        </div>
    </section>

    <!-- ====== MODALE POUR NETTOYER LA TABLE (CONFIRMATION) ====== -->
    <div class="modal fade" id="nettoyerTableModal" tabindex="-1" role="dialog" aria-labelledby="nettoyerTableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header nettoyer-success">
                    <h5 class="modal-title" id="nettoyerTableModalLabel">
                        <i class="zmdi zmdi-check-circle"></i> Nettoyer la table
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="nettoyerModalBody">
                    <p style="font-size: 1.1rem; font-weight: 500;">
                        <i class="zmdi zmdi-alert-triangle" style="color: #f59e0b; font-size: 2.5rem;"></i>
                    </p>
                    <p style="font-size: 1.1rem; font-weight: 500;">
                        Voulez-vous vraiment nettoyer cette table ?
                    </p>
                    <p class="text-muted small" id="tableNameDisplay"></p>
                </div>
                <div class="modal-footer" id="nettoyerModalFooter" style="justify-content: center; border-top: none;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="zmdi zmdi-close"></i> Non
                    </button>
                    <button type="button" class="btn btn-success" id="confirmNettoyerTable">
                        <i class="zmdi zmdi-check"></i> Oui, nettoyer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'information pour table déjà propre -->
    <div class="modal fade" id="tableDejaPropreModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header nettoyer-info">
                    <h5 class="modal-title">
                        <i class="zmdi zmdi-info"></i> Table propre
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p><i class="zmdi zmdi-check-circle" style="color: #10b981; font-size: 3rem;"></i></p>
                    <p style="font-size: 1.1rem; font-weight: 500;">Cette table est déjà propre.</p>
                </div>
                <div class="modal-footer" style="justify-content: center; border-top: none;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
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
        $("#link_45").addClass("active");

        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzone-upload").trigger("click");
        })
        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
            filterTables();
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
            filterTables();
        });
        $("#save").click(function(e) {
            e.preventDefault();
            var nom = $("#nom").val();
            var point_vente_id = $("#point_vente_id").val();
            var description = $("#description").val();
            var data = $("#form_add").serialize();
            if (nom.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de la table');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if(point_vente_id.trim().length == 0){
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez le point de vente');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                }else{
                        $.ajax({
                        type: "POST",
                        url: "/check_tables",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                $('#msg').html(
                                    '<i class="zmdi zmdi-close-circle"></i> Cette table existe déjà'
                                );
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            } else
                            {
                                $("#save").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "/add_table",
                                    data: data,
                                    success: function(response) {
                                        $("#save").attr("disabled", false);
                                        $("#nom").val("");
                                        $("#description").val("");
                                        $('#msg').html(
                                            '<i class="zmdi zmdi-check-circle"></i> Table ajoutée avec succès'
                                        );
                                        $('#msg').css("color", '#32c787');
                                        $("#content_groupe").html(response);
                                        filterTables();
                                        attachCleanLinks();
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });

        // ========== GESTION DES FILTRES AVEC PERSISTANCE ==========
        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                nom: $('#filterNom').val(),
                description: $('#filterDescription').val(),
                pointvente: $('#filterPointVente').val(),
                etat: $('#filterEtat').val(),
                propre: $('#filterPropre').val()
            };
            localStorage.setItem('tableFilters', JSON.stringify(filters));
        }

        function loadFiltersFromStorage() {
            const saved = localStorage.getItem('tableFilters');
            if (saved) {
                const filters = JSON.parse(saved);
                $('#filterNom').val(filters.nom || '');
                $('#filterDescription').val(filters.desc || '');
                $('#filterPointVente').val(filters.pointvente || 'all');
                $('#filterEtat').val(filters.etat || 'all');
                $('#filterPropre').val(filters.propre || 'all');
                return true;
            }
            return false;
        }

        function resetFilters() {
            $('#filterNom').val('');
            $('#filterDescription').val('');
            $('#filterPointVente').val('all');
            $('#filterEtat').val('all');
            $('#filterPropre').val('all');
            saveFiltersToStorage();
            filterTables();
            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }

        function filterTables() {
            const filterNom = $('#filterNom').val().toLowerCase().trim();
            const filterDesc = $('#filterDescription').val().toLowerCase().trim();
            const filterPointVente = $('#filterPointVente').val();
            const filterEtat = $('#filterEtat').val();
            const filterPropre = $('#filterPropre').val();

            let visibleCount = 0;
            let newIndex = 1;

            $('#noResultRow').hide();

            $('#content_groupe tbody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                let showRow = true;

                const nom = $row.find('.nom-cell').data('nom')?.toLowerCase() || '';
                const desc = $row.find('.desc-cell').data('desc')?.toLowerCase() || '';
                const pointventeId = $row.find('.pointvente-cell').data('pointvente-id') !== undefined ? String($row.find('.pointvente-cell').data('pointvente-id')) : '';
                const occupee = $row.data('occupee') !== undefined ? String($row.data('occupee')) : '';
                const propre = $row.find('.propre-cell').data('propre') !== undefined ? String($row.find('.propre-cell').data('propre')) : '';

                if (filterNom && !nom.includes(filterNom)) showRow = false;
                if (showRow && filterDesc && !desc.includes(filterDesc)) showRow = false;
                if (showRow && filterPointVente !== 'all' && pointventeId !== filterPointVente) showRow = false;
                if (showRow && filterEtat !== 'all' && occupee !== filterEtat) showRow = false;
                if (showRow && filterPropre !== 'all' && propre !== filterPropre) showRow = false;

                if (showRow) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            $('#tableCount').text(visibleCount);

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        function debouncedFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                filterTables();
                saveFiltersToStorage();
            }, 300);
        }

        // ========== GESTION DU NETTOYAGE DES TABLES ==========

        function updateTableAndFilters(html) {
            $('#content_groupe').html(html);
            filterTables();
            attachCleanLinks();
        }

        function resetNettoyerModal() {
            $('#nettoyerModalBody').html(`
                <p style="font-size: 1.1rem; font-weight: 500;">
                    <i class="zmdi zmdi-alert-triangle" style="color: #f59e0b; font-size: 2.5rem;"></i>
                </p>
                <p style="font-size: 1.1rem; font-weight: 500;">
                    Voulez-vous vraiment nettoyer cette table ?
                </p>
                <p class="text-muted small" id="tableNameDisplay"></p>
            `);
            $('#nettoyerTableModalLabel').html('<i class="zmdi zmdi-check-circle"></i> Nettoyer la table');
            $('#nettoyerModalFooter').html(`
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i> Non
                </button>
                <button type="button" class="btn btn-success" id="confirmNettoyerTable">
                    <i class="zmdi zmdi-check"></i> Oui, nettoyer
                </button>
            `);
            $('#nettoyerTableModal .modal-header').removeClass('nettoyer-success nettoyer-error');
        }

        function showLoadingInModal() {
            $('#nettoyerModalBody').html(`
                <div class="text-center">
                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p style="font-size: 1.1rem; font-weight: 500; margin-top: 15px;">
                        <i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Nettoyage en cours...
                    </p>
                </div>
            `);
            $('#nettoyerTableModalLabel').html('<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Nettoyage en cours...');
            $('#nettoyerModalFooter').html(`<div class="text-center w-100"><span class="text-muted">Veuillez patienter...</span></div>`);
            $('#nettoyerTableModal .modal-header').removeClass('nettoyer-success nettoyer-error');
        }

        function showSuccessInModal(message) {
            $('#nettoyerModalBody').html(`
                <p style="font-size: 1.1rem; font-weight: 500; color: #10b981;">
                    <i class="zmdi zmdi-check-circle" style="font-size: 3rem;"></i>
                </p>
                <p style="font-size: 1.1rem; font-weight: 500;">${message}</p>
            `);
            $('#nettoyerTableModalLabel').html('<i class="zmdi zmdi-check-circle" style="color: #10b981;"></i> Succès');
            $('#nettoyerModalFooter').html(`<div class="text-center w-100"><span class="text-muted">Fermeture automatique...</span></div>`);
            $('#nettoyerTableModal .modal-header').addClass('nettoyer-success').removeClass('nettoyer-error');
        }

        function showErrorInModal(message) {
            $('#nettoyerModalBody').html(`
                <p style="font-size: 1.1rem; font-weight: 500; color: #ef4444;">
                    <i class="zmdi zmdi-close-circle" style="font-size: 3rem;"></i>
                </p>
                <p style="font-size: 1.1rem; font-weight: 500;">${message}</p>
            `);
            $('#nettoyerTableModalLabel').html('<i class="zmdi zmdi-close-circle" style="color: #ef4444;"></i> Erreur');
            $('#nettoyerModalFooter').html(`
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i> Fermer
                </button>
            `);
            $('#nettoyerTableModal .modal-header').addClass('nettoyer-error').removeClass('nettoyer-success');
        }

        function attachCleanLinks() {
            $('.clean-link:not(.disabled-link)').off('click').on('click', function(e) {
                e.preventDefault();
                const $link = $(this);
                const tableId = $link.data('table-id');
                const propre = parseInt($link.data('propre'));

                if (propre === 0) {
                    $('#tableDejaPropreModal').modal('show');
                    return;
                }

                const tableName = $('#table_row_' + tableId).find('.nom-cell').text();
                $('#tableNameDisplay').text('Table : ' + tableName);
                $('#nettoyerTableModal').data('table-id', tableId);
                resetNettoyerModal();
                $('#confirmNettoyerTable').off('click').on('click', function() {
                    handleConfirmNettoyer();
                });
                $('#nettoyerTableModal').modal('show');
            });
        }

        function handleConfirmNettoyer() {
            var table_id = $('#nettoyerTableModal').data('table-id');
            if (!table_id) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Identifiant de table manquant');
                setTimeout(() => { $('#msg').html(""); }, 9000);
                $('#nettoyerTableModal').modal('hide');
                return;
            }

            showLoadingInModal();
            var btn = $('#confirmNettoyerTable');
            btn.prop('disabled', true);

            $.get("{{ url('/nettoyer_table') }}", { table_id: table_id })
                .done(function(response) {
                    updateTableAndFilters(response);
                    showSuccessInModal('Table nettoyée avec succès !');
                    setTimeout(function() {
                        $('#nettoyerTableModal').modal('hide');
                        resetNettoyerModal();
                    }, 1500);
                })
                .fail(function() {
                    showErrorInModal('Erreur lors du nettoyage. Veuillez réessayer.');
                    setTimeout(function() {
                        resetNettoyerModal();
                        $('#confirmNettoyerTable').off('click').on('click', function() {
                            handleConfirmNettoyer();
                        });
                    }, 2000);
                });
        }

        // Initialisation
        $(document).ready(function() {
            const hasSaved = loadFiltersFromStorage();
            filterTables();
            attachCleanLinks();

            $('#filterNom, #filterDescription, #filterPointVente, #filterEtat, #filterPropre').on('change keyup', function() {
                debouncedFilter();
            });

            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetFilters();
            });
        });
    </script>
@endsection
@endsection
