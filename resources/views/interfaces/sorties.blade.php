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
use App\Models\Factures;
use App\Models\Facturess;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'SORTIES')
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
                                    <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-email-open" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                    <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Liste</p>
                                </a>
                            </div>
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $add = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0))
                                {
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                                <?php if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0))) { ?>
                                    <div id="add" class="col-1">
                                        <a href="">
                                            <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-email" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                            <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Ajouter</p>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div id="add_r" class="col-1">
                                        <a href="">
                                            <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-accounts-add" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                            <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Ajouter</p>
                                        </a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
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
                            <div id="" class="col-1">

                            </div>
                            <div class="col-2">

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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Sorties</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste</h4>
                <div id="content_utilisateur" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">N° Facture</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Date de sortie</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($factures as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->numero }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ User::where('id', $data->user_id)->first()["name"]; }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            <?php
                                                $t = 0;
                                                $ent = Sorties::where('facture_id', $data->id)->get();
                                                foreach ($ent as $e)
                                                {
                                                    $t = $t + $e->total;
                                                }
                                                echo number_format($t, 2, ',', ' ') .'$';
                                            ?>
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ $data->date_creation }}
                                        </td>
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
                                                <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                                            <?php } else { ?>
                                                <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
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
                                                        $("#bloc_3").html(refresh_editinvitations);
                                                    });
                                                });
                                                $("#edit_r<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#btn_refus").trigger("click");
                                                });
                                                $("#detail_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $.get("{{ url('/refresh_detailfacturess') }}", {
                                                        invitation_id: <?= $data->id ?>,
                                                    }, function(refresh_editinvitations) {
                                                        $("#bloc_1").hide();
                                                        $("#bloc_2").hide();
                                                        $("#bloc_3").show();
                                                        $("#bloc_3").html(refresh_editinvitations);
                                                    });
                                                });
                                                $("#detail_r<?= $i ?>").click(function(e) {
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
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Numero facture</span></label>
                                <select id="numero_facture" name="numero_facture" class="select2" data-placeholder="Selectionnez un type de sortie">
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Il s'agit de quelle sortie ?</span></label>
                                <select id="type_sortie" name="type_sortie" class="select2" data-placeholder="Selectionnez un type de sortie">
                                    <option selected value="">Selectionnez un type de sortie</option>
                                    @foreach ($type_frais as $data)
                                        <option value="{{ $data->id }}"><?=  $data->nom ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Prix unitaire </span></label>
                                <input id="prix_unitaire" name="prix_unitaire" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Prix unitaire (Ex : 10)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Quantité </span></label>
                                <input id="quantite" name="quantite" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Quantité (Ex : 10)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> devise </span></label>
                                <select id="devise" name="devise" class="select2" data-placeholder="Selectionnez une devise">
                                    <option selected class="form-control" value="">Selectionnez une devise</option>
                                    <option class="form-control" value="0"> $</option>
                                    <option class="form-control" value="1"> Fc</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Taux</span></label>
                                <input id="taux" name="taux" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);" placeholder="Taux (Ex : 10)">
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: -20px;" class="row">
                        <div class="col-6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Libelle </span></label>
                                <textarea id="libelle" name="libelle" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libellé" cols="2" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
                <div style="margin-top: -2px;" class="row">
                    <div class="col-12">
                        <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez votre attache de la sortie</span></label>
                        <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;" action="{{ route('upload_fichier_sortie') }}" class="dropzone" id="dropzonewidget">
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
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                            <?php } ?>
                            <?php if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0))) { ?>
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
                <br>
                <div class="row" id="content_sortie">

                </div>
                <br>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">

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
<span id="data_frais_id" style="display: none;"></span>
<button style="display: none;" data-toggle="modal" data-target="#c_frais" id="btn_frais">Sup</button>
<div class="modal fade" id="c_frais" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous approuvez ? </h5>
            </div>
            <div class="modal-body">
                <p id="element_1" style="text-align: center;">

                </p>
            </div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;text-align: center;">
                    <a style="color: white;font-weight: bold;" id="oui_frais" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non_frais" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
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
    $("#link_19").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_19").addClass("text-info");
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
        $.get("{{ url('/get_numero_facture_1') }}", {
        }, function(response)
        {
            $("#numero_facture").html(response);
        });
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
        var numero_facture = $("#numero_facture").val();
        var type_sortie = $("#type_sortie").val();
        var prix_unitaire = $("#prix_unitaire").val();
        var quantite = $("#quantite").val();
        var devise = $("#devise").val();
        var taux = $("#taux").val();
        var libelle = $("#libelle").val();
        var data = $("#form_add").serialize();
        if (numero_facture.trim().length == 0)
        {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero de sortie');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (type_sortie.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le type de sortie');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else
            {
                if (prix_unitaire.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix unitaire');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else {
                    if (quantite.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la quantité');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        $.get("{{ url('/solde_actif') }}", {
                        }, function(solde)
                        {
                            if(solde == 0)
                            {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Aucaun solde actif');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            }
                            else
                            {
                                if (devise.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                }else
                                {
                                    if (taux.trim().length == 0)
                                    {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    }
                                    else{
                                        if (libelle.trim().length == 0)
                                        {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle de la sortie');
                                            $('#msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#msg').html("");
                                            }, 9000);
                                        } else {
                                            $.get("{{ url('/check_solde_sortie') }}", {
                                                devise : devise,
                                                quantite : quantite,
                                                prix_unitaire : prix_unitaire,
                                                taux : taux,
                                            }, function(rep)
                                            {
                                                if(rep == 0){
                                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le solde est insuffisant');
                                                    $('#msg').css('color', "#ff6b68");
                                                    setTimeout(() => {
                                                        $('#msg').html("");
                                                    }, 9000);
                                                }else{
                                                    $("#save").attr("disabled", true);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "/add_sortie",
                                                        data: data,
                                                        success: function(response) {
                                                            $("#save").attr("disabled", false);
                                                            Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                                            // $("#type_sortie").val("");
                                                            $("#prix_unitaire").val("");
                                                            $("#quantite").val("");
                                                            // $("#devise").val("");
                                                            $("#taux").val("");
                                                            $("#libelle").val("");
                                                            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> sortie ajoutée avec succès');
                                                            $('#msg').css("color", '#32c787');
                                                            $("#content_utilisateur").html(response);
                                                            $.get("{{ url('/get_sortie') }}", {
                                                            }, function(response)
                                                            {
                                                                $("#content_sortie").html(response);
                                                            });
                                                            setTimeout(() => {
                                                                $('#msg').html("");
                                                            }, 9000);
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    }
                                }
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
            $.ajax({
                type: 'POST',
                url: '/upload_fichier_sortie',
                data: {
                    name: name,
                    request: 2
                },
                sucess: function(data)
                {
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
    $.get("{{ url('/get_numero_facture_1') }}", {
    }, function(response)
    {
        $("#numero_facture").html(response);
    });
    // $('.commodites').selectMultiple();
</script>
@endsection
@endsection
