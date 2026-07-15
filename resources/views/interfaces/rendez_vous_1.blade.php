<?php

use App\Models\Mois;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Listespaies;
?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'RENDEZ-VOUS')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 20px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-1">
                                <a id="liste" href="">
                                    <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-money" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                    <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Liste</p>
                                </a>
                            </div>
                            <div id="add" class="col-1">
                                <a href="">
                                    <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-money-box" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                    <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Ajouter</p>
                                </a>
                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div id="" class="col-1">

                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <input style="border-color: transparent;background-color: white;" type="text" class="form-control" placeholder="Rechercher un utilisateur">
                                    <div class="input-group-prepend">
                                        <span style="background-color: rgb(33, 150, 243);" class="input-group-text"><i class="zmdi zmdi-search" style="color: white;"></i></span>
                                    </div>
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Rendez-vous</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Liste</h4>
                <div id="content_groupe" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Motif</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date debut</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">date de cloture</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($rendez_vous as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->motif }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">Le {{ $data->date_creation }} à {{ $data->heure }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            @if ($data->etat == 0)
                                                <i class="zmdi zmdi-block text-danger"></i> <span class="text-danger">En attente </span>
                                            @endif
                                            @if ($data->etat == 1)
                                                <i class="zmdi zmdi-block text-info"></i> <span class="text-info">Entrée </span>
                                            @endif
                                            @if ($data->etat == 2)
                                                <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Cloturé </span>
                                            @endif
                                        </td>
                                        @if (strlen(trim($data->date_cloturer)) == 0)
                                                <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
                                                    -
                                                </td>
                                            @else
                                                <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
                                                    Le {{ $data->date_cloturer }}
                                                </td>
                                            @endif
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            @if (Listespaies::where(["etat" => 1])->get()->count() != 0)
                                                @if ($data->etat == 0)
                                                    <a id="attente_<?= $i ?>" href="#"><i class="zmdi zmdi-settings text-success"></i> <span class="text-danger"></span></a>
                                                @endif
                                                @if ($data->etat == 1)
                                                    <a id="cloturer_<?= $i ?>" title="Activé" href="#"><i class="zmdi zmdi-settings text-success"></i> <span class="text-warning"></span></a>
                                                @endif
                                                @if ($data->etat == 2)
                                                <a id="cloturerr_<?= $i ?>" href="#"><i class="zmdi zmdi-lock text-success"></i> <span class="text-success"></span></a>
                                                @endif
                                            @else
                                                @if ($data->etat == 0)
                                                    <a id="activer_<?= $i ?>" href="#"><i class="zmdi zmdi-settings text-success"></i> <span class="text-danger"></span></a>
                                                @endif
                                                @if ($data->etat == 1)
                                                    <a id="cloturer_<?= $i ?>" title="Activé" href="#"><i class="zmdi zmdi-settings text-success"></i> <span class="text-warning"></span></a>
                                                @endif
                                                @if ($data->etat == 2)
                                                    <a id="cloturerr_<?= $i ?>" href="#"><i class="zmdi zmdi-lock text-success"></i> <span class="text-success"></span></a>
                                                @endif
                                            @endif
                                            &nbsp;&nbsp;
                                            <a style="display: none;" id="listespaies_<?= $i ?>" href="#"><i class="zmdi zmdi-comment text-info"></i>&nbsp;&nbsp;</a>
                                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>

                                            <script>
                                                $("#delete_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element").html("{{ $data->nom }}");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_sup").trigger("click");
                                                });
                                                $("#listespaies_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $.get("{{ url('/refresh_detailpaies') }}", {
                                                            invitation_id: <?= $data->id ?>,
                                                        }, function(refresh_editinvitations) {
                                                            $("#bloc_1").hide();
                                                            $("#bloc_2").hide();
                                                            $("#bloc_3").show();
                                                            $("#bloc_3").html(refresh_editinvitations);
                                                        });
                                                    });
                                                $("#activer_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element_1").html("{{ $data->nom }}");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_ac").trigger("click");
                                                });
                                                $("#cloturer_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element_2").html("{{ $data->nom }}");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_cl").trigger("click");
                                                });
                                                $("#cloturerr_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element_3").html("{{ $data->nom }}");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_cll").trigger("click");
                                                });
                                                $("#attente_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#element_4").html("{{ $data->nom }}");
                                                    $("#data_id").html("<?= $data->id ?>");
                                                    $("#btn_att").trigger("click");
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Nouveau rendez-vous</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom </span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" class="form-control" placeholder="Nom (Ex : Jonathan)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Motif </span></label>
                                <input type="text" id="motif" name="motif" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" class="form-control" placeholder="Motif (Ex : Recupeter colie)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Heure </span></label>
                                <input type="text" id="heure" name="heure" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" class="form-control input-mask" data-mask="00:00" placeholder="Heure (Ex : 00:00)" value="<?= date("h:i") ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date </span></label>
                                <input type="text" id="date" name="date" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" class="form-control input-mask"data-mask="00/00/0000" placeholder="Date (Ex : 00/00/0000)" value="<?= date("d/m/Y") ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button id="save" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button> <button id="annuler" class="btn btn-danger">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
<span id="data_frais_id" style="display: none;"></span>
<span id="devise_paie_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#activation" id="btn_ac">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloture" id="btn_cl">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#cloturee" id="btn_cll">Sup</button>
<button style="display: none;" data-toggle="modal" data-target="#attendre" id="btn_att">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ce rendez-vous ? </h5>
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
<div class="modal fade" id="activation" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous activez ce rendez-vous ? </h5>
            </div>
            <div class="modal-body">
                <p id="element_1" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui_1" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_1" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloture" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous cloturez ce rendez-vous ? </h5>
            </div>
            <div class="modal-body">
                <p id="element_2" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui_2" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_2" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloturee" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce rendez-vous est deja cloturé </h5>
            </div>
            <div class="modal-body">
                <p id="element_3" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" id="non_3" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord</button>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="attendre" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Ce solde est en attente</h5>
            </div>
            <div class="modal-body">
                <p id="element_4" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <button style="font-weight: bold;" id="non_4" class="btn btn-danger btn-sm" data-dismiss="modal">D'accord merci</button>
                </p>
            </div>
        </div>
    </div>
</div>
<button style="display: none;" class="btn btn-light" data-toggle="modal" data-target="#modal-centered" id="btn_sup_">Vertically centered</button>
<!-- Vertically centered -->
<div style="background-color: rgba(0, 0, 0, 0.3);" class="modal fade" id="modal-centered" data-backdrop="false" tabindex="-1" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
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
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom </th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"><span id="nom_p"></span></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Role </th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"><span id="role_p"></span></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Montant </th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"><span id="reste_p">0</span>/<span id="total_p" style="font-weight: bold;">100</span><span id="devise_p">$</span></th>
                                    </tr>
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
                <button type="button" id="save_paie" class="btn btn-info">Enregistrer <i class="zmdi zmdi-save"></i></button>
                <button type="button" id="annuler_paie" class="btn btn-danger" data-dismiss="modal">Close <i class="zmdi zmdi-close-circle"></i></button>
            </div>
            <p style="text-align: center;font-weight: bold;" id="m_paie"></p>
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
    $("#link_28").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_28").addClass("text-info");
    $("#icone_27").css("color", "rgb(33, 150, 243)");
    $("#upload").click(function(e) {
        e.preventDefault();
        $("#dropzone-upload").trigger("click");
    })
    $("#liste").click(function(e)
    {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
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
    });
    $("#save").click(function(e) {
        e.preventDefault();
        var nom = $("#nom").val();
        var motif = $("#motif").val();
        var heure = $("#heure").val();
        var date = $("#date").val();
        var data = $("#form_add").serialize();
        if (nom.trim().length == 0)
        {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if(motif.trim().length == 0)
            {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le motif');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            }
            else
            {
                if(heure.trim().length == 0){
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'heure');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                }
                else{
                    if(date.trim().length == 0){
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    }else{
                        $("#save").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "/add_rendez_vous",
                            data: data,
                            success: function(response) {
                                $("#save").attr("disabled", false);
                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> rendez-vous ajouté avec succès');
                                $('#msg').css("color", '#32c787');
                                $("#content_groupe").html(response);
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            }
                        });
                    }
                }
            }
        }
    });
    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_delete_rendez_vous') }}", {
            id: id,
        }, function(refresh_editverbalisateur) {
            $("#content_groupe").html(refresh_editverbalisateur);
            $("#non").trigger("click");
        });
    });
    $("#oui_1").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_activer_rendez_vous') }}", {
            id: id,
        }, function(refresh_editverbalisateur) {
            $("#content_groupe").html(refresh_editverbalisateur);
            $("#non_1").trigger("click");
        });
    });
    $("#oui_2").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_cloturer_rendez_vous') }}", {
            id: id,
        }, function(refresh_editverbalisateur) {
            $("#content_groupe").html(refresh_editverbalisateur);
            $("#non_2").trigger("click");
        });
    });
    $("#annee_id").change(function(e){
        e.preventDefault();
        var annee_id = $("#annee_id").val();
        $.get("{{ url('/get_mois_1') }}", {
            annee_id : annee_id
        }, function(response)
        {
            $("#moi_id").html(response);
        });
    });
    var annee_id = $("#annee_id").val();
    if(annee_id.trim().length == 0)
    {
        $.get("{{ url('/get_mois_1') }}", {
            annee_id : annee_id
        }, function(response)
        {
            $("#moi_id").html(response);
        });
    }
</script>
@endsection
@endsection
