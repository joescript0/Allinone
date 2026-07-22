@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
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
@section('title', $nom_app)
@section('name', 'ALERTE CENTRALE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (ALERTE CENTRALE)
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
#bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 {
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

/* ========== LIENS D'ACTION DANS LE TABLEAU ========== */
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

/* Couleurs spécifiques pour les icônes de la colonne Control */
.table tbody td a i.zmdi-settings.text-success {
    color: #10b981 !important;
}
.table tbody td a i.zmdi-settings.text-danger {
    color: #ef4444 !important;
}
.table tbody td a i.zmdi-mail-send.text-success {
    color: #10b981 !important;
}
.table tbody td a i.zmdi-mail-send.text-danger {
    color: #ef4444 !important;
}
.table tbody td a i.zmdi-map.text-info {
    color: #3b82f6 !important;
}

.table tbody td a:hover {
    background: #e0f2fe;
    transform: translateY(-2px);
}
.table tbody td a:hover i.zmdi-settings.text-success {
    color: #059669 !important;
}
.table tbody td a:hover i.zmdi-settings.text-danger {
    color: #b91c1c !important;
}
.table tbody td a:hover i.zmdi-mail-send.text-success {
    color: #059669 !important;
}
.table tbody td a:hover i.zmdi-mail-send.text-danger {
    color: #b91c1c !important;
}
.table tbody td a:hover i.zmdi-map.text-info {
    color: #2563eb !important;
}

/* ========== BOUTONS PRINCIPAUX (UNIFIÉS) ========== */
#liste, #add, #print, #add_r, #print_r,
#save, #annuler, #edit_save, #edit_annuler,
#resetFilters, #importer, #exporter,
.btn-primary, .btn-info, .btn-danger, .btn-secondary, .btn-dark {
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

#add, #print, .btn-info {
    background: var(--bleu-nuit-gradient) !important;
    color: white !important;
}
#add:hover, #print:hover, .btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
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

/* ========== STYLES SPÉCIFIQUES AUX ALERTES ========== */
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

/* Carte (modal) */
#mapPreview {
    border-radius: 0 0 12px 12px;
    background: #f0f2f5;
}

.leaflet-popup-content {
    font-size: 0.85rem;
    line-height: 1.4;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
    .content .container {
        padding: 0.5rem 1rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 {
        padding: 1rem !important;
    }
}

@media (max-width: 768px) {
    .content .container {
        padding: 0.4rem 0.6rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 {
        padding: 0.8rem !important;
    }
    #liste, #add, #print, #add_r, #print_r,
    #save, #annuler, #edit_save, #edit_annuler,
    #resetFilters, #importer, #exporter,
    .btn-primary, .btn-info, .btn-danger, .btn-dark {
        padding: 4px 12px !important;
        font-size: 0.7rem;
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
    .alert-bell-active, .alert-bell-inactive {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .content .container {
        padding: 0.3rem !important;
    }
    #bloc_1, #bloc_2, #bloc_3, #bloc_4, #bloc_5 {
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
    #save, #annuler, #edit_save, #edit_annuler,
    #resetFilters, #importer, #exporter {
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
    .alert-bell-active, .alert-bell-inactive {
        font-size: 1rem;
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
