@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php
    use App\Models\Contrevenants;
    use App\Models\Groupes;
    use App\Models\Verbalisateurs;
    use App\Models\Writes;
    use App\Models\Entres;
    use App\Models\Mois;
    use App\Models\Annees;
    use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'BILAN SOCIALE')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* ============================================================
   DESIGN PREMIUM – UNIFIÉ (BILAN SOCIALE)
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

/* ========== BOUTONS DE LA COLONNE CONTROL (SPÉCIFIQUES À BILAN SOCIALE) ========== */
/* Ils doivent rester en "pills" avec couleurs, mais s'intégrer au design */
.table tbody td:last-child a {
    display: inline-flex !important;
    align-items: center;
    gap: 6px;
    padding: 6px 14px !important;
    border-radius: 40px !important;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    margin: 4px 6px 4px 0;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    white-space: nowrap;
    background: #f1f5f9;
    color: #1e293b;
}

.table tbody td:last-child a i.zmdi {
    font-size: 1rem;
    margin: 0;
}

/* Classes spécifiques pour les trois types */
.table tbody td:last-child a.text-success {
    background: #e0f2e9;
    color: #0a5c3e;
}
.table tbody td:last-child a.text-success i.zmdi {
    color: #0a5c3e;
}

.table tbody td:last-child a.text-danger {
    background: #fee2e2;
    color: #b91c1c;
}
.table tbody td:last-child a.text-danger i.zmdi {
    color: #b91c1c;
}

.table tbody td:last-child a.text-info {
    background: #e0f2fe;
    color: #0c4e6e;
}
.table tbody td:last-child a.text-info i.zmdi {
    color: #0c4e6e;
}

/* Survol discret : assombrissement léger */
.table tbody td:last-child a.text-success:hover {
    background: #cce8df;
    transform: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.table tbody td:last-child a.text-danger:hover {
    background: #f5d0d0;
    transform: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.table tbody td:last-child a.text-info:hover {
    background: #cde5f5;
    transform: none;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

/* Responsive pour la colonne Control */
@media (max-width: 640px) {
    .table tbody td:last-child a {
        margin: 2px;
        padding: 5px 10px !important;
        font-size: 0.7rem;
        white-space: nowrap;
    }
}
@media (max-width: 480px) {
    .table tbody td:last-child a {
        white-space: normal;
        word-break: keep-all;
    }
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

/* Boutons désactivés */
#add_r, #print_r, #save_r {
    background: #cbd5e1 !important;
    color: #475569 !important;
    cursor: not-allowed;
    opacity: 0.7;
    box-shadow: none;
}
#add_r:hover, #print_r:hover, #save_r:hover {
    transform: none;
    box-shadow: none;
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

/* Dropzone (pour les fichiers) – on garde le style existant en l'intégrant */
.dropzone {
    background-color: transparent;
    border: 4px dashed rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    min-height: 150px;
    padding: 20px;
}
.dropzone .dz-message {
    font-weight: 500;
    color: #64748b;
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
                                    <i class="zmdi zmdi-accounts"></i> Liste
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Bilan sociale</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste</h4>
                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Annees</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($annees as $data)
                                        @if (Entres::where(["annee_id" => $data->id])->get()->count() != 0)
                                        <tr>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->annees }}</td>
                                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                                    <?php
                                                    $edit = 0;
                                                    $delete = 0;
                                                    $display = 0;
                                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                                        $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                                        $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                                        $display = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->display;
                                                    }
                                                    ?>
                                                <?php } ?>
                                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                    <a id="detail2_<?= $i ?>" class="text-success" href="#"><i class="zmdi zmdi-folder text-success"></i> Entrée</a> &nbsp;
                                                <?php } else { ?>
                                                    <a id="detail_rr<?= $i ?>" class="text-success" href="#"><i class="zmdi zmdi-folder text-success"></i> Entrée</a> &nbsp;
                                                <?php } ?>
                                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                    <a id="detail_<?= $i ?>" class="text-danger" href="#"><i class="zmdi zmdi-folder text-danger"></i> Sortie</a> &nbsp;
                                                <?php } else { ?>
                                                    <a id="detail_r<?= $i ?>" class="text-danger" href="#"><i class="zmdi zmdi-folder text-danger"></i> Sortie</a> &nbsp;
                                                <?php } ?>
                                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                                    <a id="detail3_<?= $i ?>" class="text-info" href="#"><i class="zmdi zmdi-folder text-info"></i> Entrée et sortie</a> &nbsp;
                                                <?php } else { ?>
                                                    <a id="detail_rrr<?= $i ?>" class="text-info" href="#"><i class="zmdi zmdi-folder text-info"></i> Entrée et sortie</a> &nbsp;
                                                <?php } ?>
                                                <script>
                                                    $("#edit_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_editdecisions') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bilan").attr('src', '{{ asset("" )  }}' + refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#edit_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#detail_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/bilan_1') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bilan").attr('src', '{{ asset("" )  }}' + refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#detail_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });



                                                    $("#detail2_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/bilan_2') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bilan").attr('src', '{{ asset("" )  }}' + refresh_editinvitations);
                                                        });
                                                    });
                                                    $("#detail_rr<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });

                                                    $("#detail3_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/bilan_3') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bilan").attr('src', '{{ asset("")  }}' + refresh_editinvitations);
                                                        });
                                                    });

                                                    $("#detail_rrr<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });


                                                    $("#delete_r<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#btn_refus").trigger("click");
                                                    });
                                                    $("#delete_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#element").html("<?= $data->numero_decision ?>");
                                                        $("#data_id").html("<?= $data->id ?>");
                                                        $("#btn_sup").trigger("click");
                                                    });
                                                </script>
                                            </td>
                                        </tr>
                                        {{! $i++; }}
                                        @endif
                                    @endforeach
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
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom du projet </span></label>
                                <input id="nom_projet" name="nom_projet" type="text" class="form-control" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom du projet (Ex : Construction école)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date de création </span></label>
                                <input id="date_creation" name="date_creation" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)" value="<?= date("d/m/Y") ?>">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Budget </span></label>
                                <input id="budget" name="budget" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Budget (Ex : 10000)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nombre de personne </span></label>
                                <input id="nombre_personne" name="nombre_personne" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nombre de personne (Ex : 10)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Début </span></label>
                                <input id="debut" name="debut" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)" value="<?= date("d/m/Y") ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Fin</span></label>
                                <input id="fin" name="fin" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Déscription du projet </span></label>
                                <textarea id="description" name="description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description du projet" cols="2" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
                <div style="margin-top: -2px;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre attache du projet ici </span></label>
                        <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;" action="{{ route('upload') }}" class="dropzone" id="dropzonewidget">
                            @csrf
                        </form>
                    </div>
                </div>
                <div style="margin-top: 22px;display: none;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre document </span></label>
                        <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;" action="{{ route('upload_2') }}" class="dropzone" id="dropzonewidget_1">
                            @csrf
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
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                            <?php } ?>
                            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                                <button id="save" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                            <button id="annuler" class="btn btn-danger">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
                <iframe style="width: 100%;height: 1500px;" id="bilan" src="" frameborder="0"></iframe>
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
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element" style="text-align: center;">

                </p>
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
    $("#link_22").addClass("active");
    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzonewidget").trigger("click");
    })
    $("#liste").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
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
    });
    $("#save").click(function(e) {
        e.preventDefault();
        var nom_projet = $("#nom_projet").val();
        var date_creation = $("#date_creation").val();
        var budget = $("#budget").val();
        var nombre_personne = $("#nombre_personne").val();
        var debut = $("#debut").val();
        var fin = $("#fin").val();
        var description = $("#description").val();
        var data = $("#form_add").serialize();
        if (nom_projet.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom du projet');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (date_creation.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date de création du projet');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if (budget.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le budget du projet');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else {
                    if (budget <= 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du budget de ce projet');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        if (nombre_personne.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nombre de personne qui sont sur ce projet');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        } else {
                            if (nombre_personne <= 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du nombre de personne qui sont sur ce projet');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            } else {
                                if (debut.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le début de ce projet');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                } else {
                                    if (fin.trim().length == 0) {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la fin de ce projet');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    } else {
                                        if (fin <= debut) {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La date de fin dois être superieur à date de début');
                                            $('#msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#msg').html("");
                                            }, 9000);
                                        } else {
                                            if (description.trim().length == 0) {
                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la description de ce projet');
                                                $('#msg').css('color', "#ff6b68");
                                                setTimeout(() => {
                                                    $('#msg').html("");
                                                }, 9000);
                                            } else {
                                                $("#save").attr("disabled", true);
                                                $.ajax({
                                                    type: "POST",
                                                    url: "/add_decisions",
                                                    data: data,
                                                    success: function(response) {
                                                        $("#save").attr("disabled", false);
                                                        Dropzone.forElement('#dropzonewidget').removeAllFiles(true)
                                                        Dropzone.forElement('#dropzonewidget_1').removeAllFiles(true)
                                                        $("#nom_projet").val("");
                                                        $("#date_creation").val("");
                                                        $("#budget").val("");
                                                        $("#nombre_personne").val("");
                                                        $("#debut").val("");
                                                        $("#fin").val("");
                                                        $("#description").val("");
                                                        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> projet ajouté avec succès');
                                                        $('#msg').css("color", '#32c787');
                                                        $("#content_utilisateur").html(response);
                                                        setTimeout(() => {
                                                            $('#msg').html("");
                                                        }, 9000);
                                                    }
                                                });
                                            }
                                        }
                                    }
                                }
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
        });
    });
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
    // $('.commodites').selectMultiple();
</script>
@endsection
@endsection
