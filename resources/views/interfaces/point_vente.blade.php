@php
    use App\Models\appnames;
    use App\Models\Stocks;
    use App\Models\tables;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
    // Récupération des stocks pour le filtre (si non passés par le contrôleur)
    $stocks = Stocks::where('supprimer', 0)->get(); // ou ->where('etat', 1) selon votre logique
@endphp
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'POINT DE VENTE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (POINT DE VENTE)
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

#resetFilters {
    background: #64748b !important;
    color: white !important;
}
#resetFilters:hover {
    transform: translateY(-2px);
    background: #475569 !important;
    box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3);
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
                                        <i class="zmdi zmdi-money"></i> Liste
                                    </a>
                                    &nbsp;
                                    <a class="btn-primary btn-sm" id="add" href="">
                                        <i class="zmdi zmdi-plus-circle"></i> Ajouter
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
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion des points de vente</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;padding-bottom: 50px" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                        Liste
                        <span class="article-count-badge" id="pointCountBadge">
                            <i class="zmdi zmdi-view-list"></i> <span id="pointCount">0</span>
                        </span>
                    </h4>

                    <!-- SECTION FILTRES AVEC PERSISTANCE -->
                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> Nom du point de vente</label>
                            <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-comment text-danger"></i> Description</label>
                            <input type="text" id="filterDescription" class="form-control" placeholder="Rechercher par description...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-storage text-danger"></i> Stock utilisé</label>
                            <select id="filterStock" class="form-control">
                                <option value="all">Tous les stocks</option>
                                <option value="-1">Aucun</option>
                                <option value="0">Principal</option>
                                @foreach ($stocks as $stock)
                                    <option value="{{ $stock->id }}">{{ $stock->nom }}</option>
                                @endforeach
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
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Stock utilise</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($point_ventes as $data)
                                            <tr>
                                                <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                                <td class="nom-cell" data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                                <td class="desc-cell" data-desc="{{ $data->description }}" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                                                <td class="stock-cell" data-stock-id="{{ $data->stock_id }}" style="padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data->stock_id == -1)
                                                        <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">{{ 'Aucun' }} </span>
                                                    @elseif ($data->stock_id == 0)
                                                        <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success"> Principal</span>
                                                    @else
                                                        <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success"> {{ Stocks::where('id', $data->stock_id)->first()['nom'] ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                    <a id="edit_<?= $i ?>" href="#"><i
                                                            class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                    <a id="delete_<?= $i ?>" href="#"><i
                                                            class="zmdi zmdi-delete text-danger"></i></a>
                                                    <script>
                                                        $("#edit_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $.get("{{ url('/refresh_edit_point_ventes') }}", {
                                                                point_vente_id: <?= $data->id ?>,
                                                            }, function(refresh_edit_point_ventes) {
                                                                $("#bloc_1").hide();
                                                                $("#bloc_2").hide();
                                                                $("#bloc_3").show();
                                                                $("#bloc_3").html(refresh_edit_point_ventes);
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
                                            {{ !$i++ }}
                                        @endforeach
                                        <!-- Ligne pour aucun résultat -->
                                        <tr id="noResultRow" style="display: none;">
                                            <td colspan="6">
                                                <i class="zmdi zmdi-info-outline"></i> Aucun point de vente ne correspond à vos critères.
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
                            class="zmdi zmdi-plus-circle text-info"></i> Ajouter</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Nom </span></label>
                                    <input type="text" id="nom" name="nom"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Nom (Ex : Point de vente 1)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-comment"></i> Description </span></label>
                                    <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Description" name="description" id="description" cols="2" rows="1"></textarea>
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
    <span id="data_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous supprimez ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element" style="text-align: center;">

                    </p>
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
        $("#link_37").addClass("active");

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
            // On réapplique les filtres quand on revient à la liste
            filterPoints();
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
            filterPoints();
        });
        $("#save").click(function(e) {
            e.preventDefault();
            var nom = $("#nom").val();
            var description = $("#description").val();
            var data = $("#form_add").serialize();
            if (nom.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom du point de vente');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                $.ajax({
                    type: "POST",
                    url: "/check_point_vente",
                    data: data,
                    success: function(response) {
                        if (response == 1) {
                            $('#msg').html(
                                '<i class="zmdi zmdi-close-circle"></i> Ce point de vente existe déjà'
                                );
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        } else {
                            $("#save").attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: "/add_point_vente",
                                data: data,
                                success: function(response) {
                                    $("#save").attr("disabled", false);
                                    $("#nom").val("");
                                    $("#description").val("");
                                    $('#msg').html(
                                        '<i class="zmdi zmdi-check-circle"></i> Point de vente ajouté avec succès'
                                        );
                                    $('#msg').css("color", '#32c787');
                                    $("#content_groupe").html(response);
                                    // Réapplique les filtres après mise à jour
                                    filterPoints();
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
            $.get("{{ url('/refresh_delete_point_ventes') }}", {
                id: id,
            }, function(refresh_editverbalisateur) {
                $("#content_groupe").html(refresh_editverbalisateur);
                filterPoints();
                $("#non").trigger("click");
            });
        });

        // ========== GESTION DES FILTRES AVEC PERSISTANCE ==========
        let filterTimeout;

        function saveFiltersToStorage() {
            const filters = {
                nom: $('#filterNom').val(),
                description: $('#filterDescription').val(),
                stock: $('#filterStock').val()
            };
            localStorage.setItem('pointVenteFilters', JSON.stringify(filters));
        }

        function loadFiltersFromStorage() {
            const saved = localStorage.getItem('pointVenteFilters');
            if (saved) {
                const filters = JSON.parse(saved);
                $('#filterNom').val(filters.nom || '');
                $('#filterDescription').val(filters.description || '');
                $('#filterStock').val(filters.stock || 'all');
                return true;
            }
            return false;
        }

        function resetFilters() {
            $('#filterNom').val('');
            $('#filterDescription').val('');
            $('#filterStock').val('all');
            saveFiltersToStorage();
            filterPoints();
            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
            $('#msg').css('display', 'flex');
            setTimeout(() => {
                $('#msg').html('');
                $('#msg').css('display', 'none');
            }, 3000);
        }

        function filterPoints() {
            const filterNom = $('#filterNom').val().toLowerCase().trim();
            const filterDesc = $('#filterDescription').val().toLowerCase().trim();
            const filterStock = $('#filterStock').val();

            let visibleCount = 0;
            let newIndex = 1;

            $('#noResultRow').hide();

            $('#content_groupe tbody tr:not(#noResultRow)').each(function() {
                const $row = $(this);
                let showRow = true;

                // Récupération des données depuis les attributs data-* ou le texte des cellules
                const nom = $row.find('.nom-cell').data('nom')?.toLowerCase() || '';
                const desc = $row.find('.desc-cell').data('desc')?.toLowerCase() || '';
                const stockId = $row.find('.stock-cell').data('stock-id') !== undefined ? String($row.find('.stock-cell').data('stock-id')) : '';

                // Filtre nom
                if (filterNom && !nom.includes(filterNom)) {
                    showRow = false;
                }

                // Filtre description
                if (showRow && filterDesc && !desc.includes(filterDesc)) {
                    showRow = false;
                }

                // Filtre stock
                if (showRow && filterStock !== 'all') {
                    if (stockId !== filterStock) {
                        showRow = false;
                    }
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

            $('#pointCount').text(visibleCount);

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        function debouncedFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                filterPoints();
                saveFiltersToStorage();
            }, 300);
        }

        // Initialisation au chargement de la page
        $(document).ready(function() {
            // Charger les filtres depuis localStorage
            const hasSaved = loadFiltersFromStorage();

            // Appliquer les filtres immédiatement
            filterPoints();

            // Écouteurs d'événements
            $('#filterNom, #filterDescription, #filterStock').on('change keyup', function() {
                debouncedFilter();
            });

            // Réinitialisation
            $('#resetFilters').click(function(e) {
                e.preventDefault();
                resetFilters();
            });
        });
    </script>
@endsection
@endsection
