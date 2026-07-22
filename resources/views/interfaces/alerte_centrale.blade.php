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
@section('name', 'ALERTE EQUIPE MOBILE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* =============================================
           DESIGN PREMIUM - PAGE ALERTE CENTRALE
           (identique à la page 1)
        ============================================= */

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
            --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius-xl: 20px;
            --border-radius-lg: 16px;
        }

        #bloc_1,
        #bloc_2,
        #bloc_3,
        #bloc_4 {
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

        .table thead th:last-child {
            border-right: none;
        }

        .table tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #eef2f6;
        }

        .table tbody tr:hover {
            background: #f0f5fe !important;
        }

        .table tbody td {
            padding: 6px 8px !important;
            vertical-align: middle !important;
            font-weight: 500;
            font-size: 0.8rem;
            color: #1e2a3e;
            word-break: break-word;
            border-bottom: 1px solid #eef2f6;
            line-height: 1.3;
        }

        .table tbody td:last-child {
            text-align: center;
            vertical-align: middle;
        }

        /* ========== BOUTONS PRINCIPAUX ========== */
        #liste,
        #add,
        #print,
        #add_r,
        #print_r,
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

        #liste {
            background: #3B82F6 !important;
            color: white !important;
        }
        #liste:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
            background: #2563eb !important;
        }

        #add,
        a#add {
            background: linear-gradient(135deg, #0a192f, #1e3a5f) !important;
            color: white !important;
        }
        #add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
        }

        #print {
            background: linear-gradient(135deg, #4b6e8a, #2c4f6e) !important;
            color: white !important;
        }
        #print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(43, 76, 108, 0.3);
        }

        #save,
        #edit_save {
            background: linear-gradient(135deg, #2c5282, #1a365d) !important;
            color: white;
        }
        #save:hover,
        #edit_save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3);
        }

        #annuler,
        #edit_annuler {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white;
        }
        #annuler:hover,
        #edit_annuler:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
        }

        #add_r,
        #print_r,
        #save_r {
            background: #cbd5e1 !important;
            color: #475569 !important;
            cursor: not-allowed !important;
            opacity: 0.7;
            transform: none !important;
            box-shadow: none !important;
        }

        /* ========== MENU 3 POINTS ========== */
        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-three-dots {
            background: #f1f5f9;
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-three-dots:hover {
            background: #e0e7ff;
            transform: scale(1.05);
        }

        .btn-three-dots i {
            font-size: 1.2rem;
            color: #475569;
        }

        .custom-dropdown-menu {
            display: none; /* sera géré par JS en fixed */
            position: fixed; /* sera fixe par rapport à la fenêtre */
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            min-width: 170px;
            z-index: 9999;
            overflow: hidden;
            /* top, left seront définis par JS */
        }

        .custom-dropdown-menu.show {
            display: block;
            animation: dropdownFade 0.15s ease-out;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #1e293b;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background: white;
            width: 100%;
            text-align: left;
            transition: background 0.2s;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }

        .dropdown-item i {
            font-size: 1rem;
            width: 20px;
        }

        .dropdown-divider {
            height: 1px;
            background: #eef2f6;
            margin: 4px 0;
        }

        .dropdown-item:disabled,
        .dropdown-item[disabled] {
            opacity: 0.6;
            cursor: not-allowed !important;
        }

        /* ========== CLOCHE ALERTE ========== */
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
            background: #ecfdf5 !important;
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        #msg:not(:empty):has(i.zmdi-close-circle),
        #edit_msg:not(:empty):has(i.zmdi-close-circle) {
            background: #fef2f2 !important;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        @keyframes slideInMsg {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== MODALS ========== */
        .modal-header {
            background: linear-gradient(135deg, #0a192f, #1e3a5f);
            color: white;
            border-radius: 16px 16px 0 0;
        }
        .modal-header .close {
            color: white;
            opacity: 0.8;
        }
        .modal-content {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
        }
        .modal-footer .btn {
            border-radius: 40px !important;
            padding: 6px 16px !important;
        }

        #mapPreview {
            border-radius: 0;
            background: #f0f2f5;
            height: 450px;
            width: 100%;
        }
        .leaflet-popup-content {
            font-size: 0.85rem;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .content .container {
                padding: 0.4rem 0.6rem !important;
            }
            .custom-dropdown-menu {
                min-width: 150px;
            }
        }
    </style>

    <section class="content">
        <div style="margin-top: 30px; padding-bottom: 50px;" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Alerte equipe mobile</h6>
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
                                            <th>N°</th>
                                            <th>Poste</th>
                                            <th>Officier</th>
                                            <th>Motif</th>
                                            <th>Alerte</th>
                                            <th>Transférer par</th>
                                            <th>Etat</th>
                                            <th>Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($alertes as $data)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ Postes::where(['id' => $data->poste_id])->first()['nom'] ?? '' }}</td>
                                                <td>{{ User::where(['id' => $data->user_id])->first()['name'] ?? '' }}</td>
                                                <td>{{ $data->motif }}</td>
                                                <td style="text-align: center;">
                                                    @if ($data->etat_1 == 1)
                                                        <i class="zmdi zmdi-notifications-active alert-bell-active" title="Alerte activée"></i>
                                                    @else
                                                        <i class="zmdi zmdi-notifications-off alert-bell-inactive" title="Alerte désactivée"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($data->user_id_transfert == 0)
                                                        <i class="zmdi zmdi-account text-danger"></i> <span class="text-danger">Aucune personne</span>
                                                    @else
                                                        @if ($data->user_id_transfert == Auth::user()->id)
                                                            <i class="zmdi zmdi-account text-success"></i> <span class="text-success">Vous</span>
                                                        @else
                                                            <i class="zmdi zmdi-account text-success"></i> <span class="text-success">{{ User::where(['id' => $data->user_id_transfert])->first()['name'] ?? '' }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($data->etat_1 == 0)
                                                        <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Désactivé</span>
                                                    @endif
                                                    @if ($data->etat_1 == 1)
                                                        <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Activé</span>
                                                    @endif
                                                </td>
                                                <!-- ========== COLONNE CONTROL AVEC MENU 3 POINTS ========== -->
                                                <td style="text-align: center; padding-top: 5px; padding-bottom: 5px;">
                                                    <div class="dropdown-wrapper">
                                                        <button class="btn-three-dots" data-target="menu-{{ $i }}">
                                                            <i class="zmdi zmdi-more-vert"></i>
                                                        </button>
                                                        <div id="menu-{{ $i }}" class="custom-dropdown-menu">
                                                            <!-- Option Activer / Désactiver selon etat_1 -->
                                                            @if ($data->etat_1 == 1)
                                                                <button class="dropdown-item alert-action"
                                                                        data-id="{{ $data->id }}"
                                                                        data-nom="{{ User::where(['id' => $data->user_id])->first()['name'] ?? '' }}"
                                                                        data-modal-trigger="btn_ac"
                                                                        data-element-id="element_1">
                                                                    <i class="zmdi zmdi-settings text-danger"></i> Désactiver
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item alert-action"
                                                                        data-id="{{ $data->id }}"
                                                                        data-nom="{{ User::where(['id' => $data->user_id])->first()['name'] ?? '' }}"
                                                                        data-modal-trigger="btn_cll"
                                                                        data-element-id="element_3">
                                                                    <i class="zmdi zmdi-settings text-muted"></i> Déjà désactivé
                                                                </button>
                                                            @endif

                                                            <div class="dropdown-divider"></div>

                                                            <!-- Option Transférer selon user_id_transfert -->
                                                            @if ($data->user_id_transfert == 0)
                                                                <button class="dropdown-item transfer-action"
                                                                        data-id="{{ $data->id }}"
                                                                        data-nom="{{ User::where(['id' => $data->user_id])->first()['name'] ?? '' }}"
                                                                        data-modal-trigger="btn_tra"
                                                                        data-element-id="element_transfert">
                                                                    <i class="zmdi zmdi-mail-send text-warning"></i> Transférer
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item transfer-action"
                                                                        data-id="{{ $data->id }}"
                                                                        data-nom="{{ User::where(['id' => $data->user_id])->first()['name'] ?? '' }}"
                                                                        data-modal-trigger="btn_tra_e"
                                                                        data-element-id="element_transfert_e">
                                                                    <i class="zmdi zmdi-mail-send text-success"></i> Déjà transféré
                                                                </button>
                                                            @endif

                                                            <div class="dropdown-divider"></div>

                                                            <!-- Option Carte (toujours présente) -->
                                                            <button class="dropdown-item map-alert"
                                                                    data-lat="{{ $data->latitude }}"
                                                                    data-lng="{{ $data->longitude }}"
                                                                    data-motif="{{ addslashes($data->motif) }}"
                                                                    data-poste="{{ addslashes( Postes::where(['id' => $data->poste_id])->first()['nom']  ?? '') }}"
                                                                    data-description="{{addslashes( Postes::where(['id' => $data->poste_id])->first()['description']  ?? '') }}">
                                                                <i class="zmdi zmdi-map text-info"></i> Voir sur la carte
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bloc 2, 3, 4, 5 (inchangés) -->
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
                                            <option value="{{ $data->id }}">{{ $data->annees }}</option>
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
                                            <option value="{{ $data->id }}">{{ $data->nom }}</option>
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
                <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
                <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
                <div id="bloc_5" style="margin-top: 12px;display: none;" class="col-lg-12">
                    <iframe style="width: 100%;height: 1500px;" id="data_liste" src="" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Éléments cachés pour les modals -->
    <span id="listesfactures_id" style="display: none;"></span>
    <span id="data_id" style="display: none;"></span>
    <span id="alerte_active" style="display: none;">{{ Alertes::where(["supprimer" => 0, "etat_1" => 1])->get()->count(); }}</span>
    <span id="data_frais_id" style="display: none;"></span>
    <span id="devise_paie_id" style="display: none;"></span>

    <!-- Boutons déclencheurs cachés pour les modals -->
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#activation" id="btn_ac">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#cloture" id="btn_cl">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#cloturee" id="btn_cll">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#attendre" id="btn_att">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#transfert" id="btn_tra">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#transfert_e" id="btn_tra_e">Sup</button>

    <!-- ========== MODALS ========== -->
    <!-- Modal Suppression -->
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        supprimez cette paie ?</h5>
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

    <!-- Modal Activation / Désactivation -->
    <div class="modal fade" id="activation" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        désactiver cette alerte ?</h5>
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

    <!-- Modal Transfert -->
    <div class="modal fade" id="transfert" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        transmettre cette alerte ?</h5>
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

    <!-- Modal Déjà désactivée -->
    <div class="modal fade" id="cloturee" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Cette alerte
                        est déjà désactivée</h5>
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

    <!-- Modal Déjà transférée -->
    <div class="modal fade" id="transfert_e" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Cette alerte
                        est déjà transmise</h5>
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

    <!-- Modal Carte -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #0a192f, #1e3a5f);">
                    <h5 class="modal-title text-white" style="font-weight: bold;">
                        <i class="zmdi zmdi-map"></i> Localisation de l'alerte
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div id="mapPreview"></div>
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

    <!-- Autres modals (inchangés) -->
    <button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#modal-centered" id="btn_sup_">Vertically centered</button>
    <div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="modal-centered" data-backdrop="false" tabindex="-1">
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
                            <input type="text" id="montant_p" name="montant_p" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le montant">
                        </div>
                        <div style="margin-top: 20px;" class="col-12">
                            <input type="text" id="taux_p" name="taux_p" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" placeholder="Entrez le taux" value="">
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
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info-circle"></i> Voulez-vous la liste de facture de quelle activité ?</span></label>
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
    <!-- Leaflet CSS et JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
        $(document).ready(function() {
            // ==================== SURVEILLANCE DES ALERTES ACTIVES ====================
            let currentAudio = null;

            function arreterSonAlerte() {
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                    currentAudio = null;
                }
            }

            function jouerSonAlerte() {
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
                    jouerSonAlerte();
                } else {
                    arreterSonAlerte();
                }
            }

            checkAndTriggerAlert();

            // ========== GESTION DU MENU 3 POINTS AVEC POSITIONNEMENT FIXE ==========
            $('.btn-three-dots').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var targetId = $(this).data('target');
                var $menu = $('#' + targetId);
                var $btn = $(this);

                // Fermer tous les autres menus
                $('.custom-dropdown-menu').not($menu).removeClass('show');

                if ($menu.hasClass('show')) {
                    $menu.removeClass('show');
                    return;
                }

                // Positionnement en fixed par rapport à la fenêtre
                var rect = $btn[0].getBoundingClientRect();
                var menuWidth = $menu.outerWidth();
                var menuHeight = $menu.outerHeight();
                var windowWidth = window.innerWidth;
                var windowHeight = window.innerHeight;

                // Calcul horizontal : aligné à droite du bouton
                var left = rect.right - menuWidth;
                if (left < 10) left = 10; // marge gauche
                if (left + menuWidth > windowWidth - 10) left = windowWidth - menuWidth - 10;

                // Calcul vertical : en bas si possible, sinon en haut
                var top = rect.bottom + 8; // en dessous
                if (top + menuHeight > windowHeight - 10) {
                    // pas assez de place en bas → on met au-dessus
                    top = rect.top - menuHeight - 8;
                    if (top < 10) top = 10; // si même en haut ça dépasse, on le met en bas avec scroll
                }

                $menu.css({
                    position: 'fixed',
                    top: top + 'px',
                    left: left + 'px',
                    bottom: 'auto',
                    right: 'auto',
                    margin: '0'
                });

                $menu.addClass('show');
            });

            // Fermer les menus en cliquant ailleurs
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.btn-three-dots').length && !$(e.target).closest('.custom-dropdown-menu').length) {
                    $('.custom-dropdown-menu').removeClass('show');
                }
            });

            // ========== ACTIONS DU MENU ==========

            // 1. Action sur l'alerte (Activer/Désactiver) - respecte la logique originale
            $(document).on('click', '.alert-action', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var nom = $(this).data('nom');
                var triggerId = $(this).data('modal-trigger');   // 'btn_ac' ou 'btn_cll'
                var elementId = $(this).data('element-id');       // 'element_1' ou 'element_3'

                $('#' + elementId).html(nom);
                $('#data_id').html(id);
                $('#' + triggerId).trigger('click');
                $('.custom-dropdown-menu').removeClass('show');
            });

            // 2. Action sur le transfert (Transférer / Déjà transféré)
            $(document).on('click', '.transfer-action', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var nom = $(this).data('nom');
                var triggerId = $(this).data('modal-trigger');   // 'btn_tra' ou 'btn_tra_e'
                var elementId = $(this).data('element-id');       // 'element_transfert' ou 'element_transfert_e'

                $('#' + elementId).html(nom);
                $('#data_id').html(id);
                $('#' + triggerId).trigger('click');
                $('.custom-dropdown-menu').removeClass('show');
            });

            // 3. Voir sur la carte
            $(document).on('click', '.map-alert', function(e) {
                e.preventDefault();
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                var motif = $(this).data('motif');
                var poste = $(this).data('poste'); // On récupère le nom du poste
                var description = $(this).data('description'); // On récupère la description

                if (!lat || !lng || lat == 0 || lng == 0) {
                    $('#mapError').show();
                    $('#mapPreview').hide();
                    $('#mapModal').modal('show');
                    return;
                }

                $('#mapError').hide();
                $('#mapPreview').show();

                $('#mapModal').one('shown.bs.modal', function() {
                    if (window.alertMapInstance) {
                        window.alertMapInstance.remove();
                    }
                    var map = L.map('mapPreview').setView([lat, lng], 15);
                    window.alertMapInstance = map;

                    // ========== MODIFICATION ICI : remplacement par OSM standard ==========
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
                    // ========== FIN MODIFICATION ==========

                    var marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup(`
                        <b>Alerte :</b> ${motif}<br>
                        <b>Poste :</b> ${poste}<br>
                        <b>Description :</b> ${description}<br
                    `).openPopup();
                    L.circle([lat, lng], {
                        color: '#ff4444',
                        fillColor: '#ff8888',
                        fillOpacity: 0.4,
                        radius: 50
                    }).addTo(map);
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 200);
                });
                $('#mapModal').modal('show');
                $('.custom-dropdown-menu').removeClass('show');
            });

            // ========== MODALS EXISTANTS (actions originales) ==========
            // Désactiver (oui_1)
            $("#oui_1").click(function(e) {
                e.preventDefault();
                var id = $("#data_id").html();
                $.get("{{ url('/refresh_desactiver_alerte_centrale') }}", { id: id }, function(refresh) {
                    $("#content_groupe").html(refresh);
                    $("#non_1").trigger("click");
                    checkAndTriggerAlert();
                });
            });

            // Transférer (oui_transfert)
            $("#oui_transfert").click(function(e) {
                e.preventDefault();
                var id = $("#data_id").html();
                $.get("{{ url('/refresh_transfert_alerte_centrale') }}", { id: id }, function(refresh) {
                    $("#content_groupe").html(refresh);
                    $("#non_transfert").trigger("click");
                    checkAndTriggerAlert();
                });
            });

            // ========== SURVEILLANCE AUTOMATIQUE TOUTES LES 15 SECONDES ==========
            setInterval(function() {
                var alerte_active = parseInt($("#alerte_active").html());
                $.get("{{ url('/count_alerte_etat_1') }}", {}, function(nombre) {
                    if (nombre != alerte_active) {
                        jouerSonAlerte();
                        $("#alerte_active").html(nombre);
                        $.get("{{ url('/refresh_alerte_centrale') }}", {}, function(refresh) {
                            $("#content_groupe").html(refresh);
                            checkAndTriggerAlert();
                        });
                    } else {
                        checkAndTriggerAlert();
                    }
                });
            }, 15000);

            // ========== AUTRES FONCTIONS (inchangées) ==========
            $("#link_33").addClass("active");

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
                $.get("{{ url('/refresh_delete_listesfactures') }}", { id: id }, function(refresh) {
                    $("#content_groupe").html(refresh);
                    $("#non").trigger("click");
                });
            });

            $("#oui_2").click(function(e) {
                e.preventDefault();
                var id = $("#data_id").html();
                $.get("{{ url('/refresh_cloturer_listesfactures') }}", { id: id }, function(refresh) {
                    $("#content_groupe").html(refresh);
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
        });
    </script>
    @endsection
@endsection
