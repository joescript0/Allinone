@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php

use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'PROJETS')
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
    #bloc_1, #bloc_2, #bloc_3 {
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
    .invitation-count-badge {
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
    #upload,
    .btn-primary,
    .btn-info,
    .btn-danger,
    .btn-secondary,
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

    #upload {
        background: var(--bleu-nuit-gradient) !important;
        color: white !important;
    }
    #upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
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
        align-items: center;
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
        #bloc_1, #bloc_2, #bloc_3 {
            padding: 1rem !important;
        }
    }

    @media (max-width: 768px) {
        .content .container {
            padding: 0.4rem 0.6rem !important;
        }
        #bloc_1, #bloc_2, #bloc_3 {
            padding: 0.8rem !important;
        }
        #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters, #upload,
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
        .invitation-count-badge {
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
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .content .container {
            padding: 0.3rem !important;
        }
        #bloc_1, #bloc_2, #bloc_3 {
            padding: 0.6rem !important;
        }
        h4 {
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
        h4 i {
            font-size: 24px !important;
        }
        #liste, #add, #save, #edit_save, #annuler, #edit_annuler, #resetFilters, #upload {
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

    /* ========== DROPZONE ========== */
    .dropzone {
        background: transparent !important;
        border: 4px dashed rgba(0, 0, 0, 0.2) !important;
        border-radius: 10px !important;
        min-height: 150px;
        padding: 20px;
    }
    .dropzone .dz-message {
        font-weight: 600;
        color: var(--bleu-nuit);
        font-size: 0.9rem;
    }
    .dropzone .dz-preview .dz-image {
        border-radius: 10px;
    }
</style>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 10px;padding-bottom: 10px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                                <a class="btn-primary btn-sm" id="liste" href="">
                                    <i class="zmdi zmdi-email-open"></i> Liste
                                </a>
                                &nbsp;
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                    <?php
                                    $add = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                        $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                    <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
                                        <a id="add" class="btn-primary btn-sm" href="">
                                            <i class="zmdi zmdi-email"></i> Ajouter
                                        </a>
                                    <?php } else { ?>
                                        <a id="add_r" class="btn-secondary btn-sm" href="" style="background: #cbd5e1 !important; color: #475569 !important; cursor: not-allowed; opacity: 0.7;">
                                            <i class="zmdi zmdi-email"></i> Ajouter
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                                <button id="upload" type="button" class="btn btn-primary btn-sm">
                                    <i class="zmdi zmdi-download"></i> Télécharger
                                </button>
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Projets</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i>
                    Liste
                    <span class="invitation-count-badge">
                        <i class="zmdi zmdi-view-list"></i> <span id="invitationCount">0</span>
                    </span>
                </h4>

                <!-- FILTRES -->
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-label text-danger"></i> N° Invitation</label>
                        <input type="text" id="filterNumero" class="form-control" placeholder="Rechercher par numéro...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account text-danger"></i> Verbalisateur</label>
                        <input type="text" id="filterVerbalisateur" class="form-control" placeholder="Rechercher par verbalisateur...">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Date invitation</label>
                        <input type="text" id="filterDate" class="form-control input-mask" data-mask="00/00/0000" placeholder="JJ/MM/AAAA">
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
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Numero de l'invitation</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date invitation</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Heure invitation</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date document</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Verbalisateur</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody id="invitationsTableBody">
                                    {{! $i = 1; }}
                                    @foreach ($invitations as $data)
                                    <tr id="row_{{ $data->id }}">
                                        <td class="numero-cell" data-numero="{{ $data->numero_invitation }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->numero_invitation }}</td>
                                        <td class="date-cell" data-date="{{ $data->date_invitation }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_invitation }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->heure_invitation }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_document }}</td>
                                        <td class="verbalisateur-cell" data-verbalisateur="<?= Verbalisateurs::where('id', $data->verbalisateur_id)->first()["nom"] ?? '' ?>" style="padding-top: 5px;padding-bottom: 5px;">
                                            <?= Verbalisateurs::where('id', $data->verbalisateur_id)->first()["nom"] ?? 'N/A'; ?>
                                        </td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                <?php
                                                $edit = 0;
                                                $delete = 0;
                                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                }
                                                ?>
                                            <?php } ?>
                                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <?php } else { ?>
                                                <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <?php } ?>
                                            <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                                <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                            <?php } else { ?>
                                                <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                            <?php } ?>
                                            <script>
                                                $("#edit_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $.get("{{ url('/refresh_editinvitations') }}", {
                                                        invitation_id: <?= $data->id ?>,
                                                    }, function(refresh_editinvitations) {
                                                        $("#bloc_1").hide();
                                                        $("#bloc_2").hide();
                                                        $("#bloc_3").show();
                                                        $("#bloc_3").html(refresh_editinvitations);
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
                                                    $("#element").html("<?= $data->libelle ?>");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_sup").trigger("click");
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                    <!-- Ligne aucun résultat -->
                                    <tr id="noResultRow" style="display: none;">
                                        <td colspan="6">
                                            <i class="zmdi zmdi-info-outline"></i> Aucune invitation ne correspond à vos critères.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bloc_2" style="margin-top: 12px;display: none;padding-bottom: 100px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email text-info"></i> Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date invitation </span></label>
                                <input id="date_invitation" name="date_invitation" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date invitation (Ex : <?= date("d/m/Y"); ?>)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-alarm"></i> Heure invitation </span></label>
                                <input id="heure_invitation" name="heure_invitation" type="text" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control input-mask" data-mask="00:00:00" placeholder="Heure invitation (Ex : <?= date("h:m:s"); ?>)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date document </span></label>
                                <input id="date_document" name="date_document" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date du document (Ex : <?= date("d/m/Y"); ?>)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Verbalisateur </span></label>
                                <select id="verbalisateur" name="verbalisateur" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Verbalisateur</option>
                                    @foreach ($verbalisateurs as $data)
                                    <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Objet </span></label>
                                <input type="text" id="libelle" name="libelle" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Objet (Ex : mgm)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-edit"></i> Signer par </span></label>
                                <input type="text" id="signer" name="signer" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Signer par">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-library"></i> Statut </span></label>
                                <input type="text" id="statut" name="statut" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Statut">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Numero de l'invitation </span></label>
                                <input type="text" id="numero_invitation" name="numero_invitation" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Numero de l'invitation">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Description </span></label>
                                <textarea id="description" name="description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description" cols="2" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
                <div style="margin-top: 20px;">
                    <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info text-danger"></i> Déposez vos fichiers ici</span></label>
                    <form method="post" action="{{ route('upload') }}" class="dropzone" id="dropzonewidget">
                        @csrf
                    </form>
                </div>
                <form action="">
                    <div style="margin-top: 20px;" class="row">
                        <div class="col-12">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $edit = 0;
                                $delete = 0;
                                $add = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                            <?php } ?>
                            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                <button id="save" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r" class="btn btn-secondary btn-sm" style="background: #cbd5e1 !important; color: #475569 !important; cursor: not-allowed; opacity: 0.7;">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="msg"></span>
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
        // 1. PERSISTANCE DES FILTRES (numéro, verbalisateur, date)
        // =================================================================
        const STORAGE_KEY = 'invitations_filters';

        function saveFilters() {
            const filters = {
                filterNumero: $('#filterNumero').val() || '',
                filterVerbalisateur: $('#filterVerbalisateur').val() || '',
                filterDate: $('#filterDate').val() || ''
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(filters));
        }

        function loadFilters() {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (!stored) return false;
            try {
                const filters = JSON.parse(stored);
                if (filters.filterNumero !== undefined) $('#filterNumero').val(filters.filterNumero);
                if (filters.filterVerbalisateur !== undefined) $('#filterVerbalisateur').val(filters.filterVerbalisateur);
                if (filters.filterDate !== undefined) $('#filterDate').val(filters.filterDate);
                return true;
            } catch (e) {
                return false;
            }
        }

        // =================================================================
        // 2. FONCTION DE FILTRAGE + COMPTEUR
        // =================================================================
        function filterInvitations() {
            const filterNumero = String($('#filterNumero').val() || '').toLowerCase().trim();
            const filterVerbalisateur = String($('#filterVerbalisateur').val() || '').toLowerCase().trim();
            const filterDate = String($('#filterDate').val() || '').trim();

            let visibleCount = 0;
            let newIndex = 1;

            $('#noResultRow').hide();

            $('#invitationsTableBody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                const numeroValue = String($row.find('.numero-cell').data('numero') || '').toLowerCase();
                const verbalisateurValue = String($row.find('.verbalisateur-cell').data('verbalisateur') || '').toLowerCase();
                const dateValue = String($row.find('.date-cell').data('date') || '').trim();

                let showRow = true;

                if (filterNumero && !numeroValue.includes(filterNumero)) {
                    showRow = false;
                }

                if (showRow && filterVerbalisateur && !verbalisateurValue.includes(filterVerbalisateur)) {
                    showRow = false;
                }

                if (showRow && filterDate && dateValue !== filterDate) {
                    showRow = false;
                }

                if (showRow) {
                    $row.show();
                    // Pas de numéro de ligne, on ne modifie pas l'affichage
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            $('#invitationCount').text(visibleCount);

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        // =================================================================
        // 3. ÉVÉNEMENTS SUR LES FILTRES
        // =================================================================
        $('#filterNumero, #filterVerbalisateur, #filterDate').on('keyup change', function() {
            saveFilters();
            filterInvitations();
        });

        $('#resetFilters').on('click', function() {
            localStorage.removeItem(STORAGE_KEY);
            $('#filterNumero').val('');
            $('#filterVerbalisateur').val('');
            $('#filterDate').val('');
            saveFilters();
            filterInvitations();

            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        });

        // =================================================================
        // 4. CHARGEMENT DES FILTRES PERSISTANTS
        // =================================================================
        loadFilters();
        filterInvitations();

        // =================================================================
        // 5. SCRIPTS EXISTANTS (conservés et adaptés)
        // =================================================================
        $("#link_2").addClass("active");

        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzonewidget").trigger("click");
        });

        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            filterInvitations();
        });

        $("#add").click(function(e) {
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
            filterInvitations();
        });

        $("#save").click(function(e) {
            e.preventDefault();
            var date_invitation = $("#date_invitation").val();
            var heure_invitation = $("#heure_invitation").val();
            var date_document = $("#date_document").val();
            var verbalisateur = $("#verbalisateur").val();
            var libelle = $("#libelle").val();
            var description = $("#description").val();
            var signer = $("#signer").val();
            var statut = $("#statut").val();
            var numero_invitation = $("#numero_invitation").val();
            var data = $("#form_add").serialize();

            if (date_invitation.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date de l\'invitation');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (heure_invitation.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'heure de l\'invitation');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (date_document.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date du document');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (verbalisateur.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le verbalisateur');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (libelle.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le libelle');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (signer.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez la signature');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (statut.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le status');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else if (numero_invitation.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le numero de l\'invitation');
                $('#msg').css('display', 'flex');
                setTimeout(() => {
                    $('#msg').html('');
                    $('#msg').css('display', 'none');
                }, 9000);
            } else {
                $("#save").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "/add_invitations",
                    data: data,
                    success: function(response) {
                        $("#save").attr("disabled", false);
                        Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                        $("#date_invitation").val("");
                        $("#heure_invitation").val("");
                        $("#date_document").val("");
                        $("#verbalisateur").val("");
                        $("#libelle").val("");
                        $("#description").val("");
                        $("#signer").val("");
                        $("#statut").val("");
                        $("#numero_invitation").val("");
                        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Invitation ajoutée avec succès');
                        $('#msg').css('display', 'flex');
                        $("#content_utilisateur").html(response);
                        filterInvitations();
                        setTimeout(() => {
                            $('#msg').html('');
                            $('#msg').css('display', 'none');
                        }, 9000);
                    }
                });
            }
        });

        $("#oui").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_deleteinvitation') }}", {
                id: id,
            }, function(refresh_editutilisateur) {
                $("#content_utilisateur").html(refresh_editutilisateur);
                filterInvitations();
                $("#non").trigger("click");
            });
        });

        // ========== DROPZONE ==========
        if (typeof Dropzone !== 'undefined') {
            $(".dropzone").dropzone({
                addRemoveLinks: true,
                removedfile: function(file) {
                    var name = file.name;
                    $.ajax({
                        type: 'POST',
                        url: '/upload',
                        data: {
                            name: name,
                            request: 2
                        },
                        success: function(data) {
                            console.log('success: ' + data);
                        }
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            });
        }

    }); // fin document ready
</script>
@endsection
@endsection
