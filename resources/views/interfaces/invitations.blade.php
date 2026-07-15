<?php

use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'PROJETS')
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
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                                }
                                ?>
                                <?php if (($add ==  1) || (Auth::user()->role == 0)) { ?>
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
                                <p style="text-align: left;"><button id="upload" type="button" class="btn btn-primary btn-sm"><i class="zmdi zmdi-download"></i> Telecharger</button></p>
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
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste</h4>
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
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($invitations as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->numero_invitation }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_invitation }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->heure_invitation }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_document }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            <?= Verbalisateurs::where('id', $data->verbalisateur_id)->first()["nom"]; ?>
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
                <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;" action="{{ route('upload') }}" class="dropzone" id="dropzonewidget">
                    @csrf
                </form>
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
    $("#link_2").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_2").addClass("text-info");
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
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            if (heure_invitation.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'heure de l\'invitation');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if (date_document.trim().length == 0) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date du document');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                } else {
                    if (verbalisateur.trim().length == 0) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le verbalisateur');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        if (libelle.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le libelle');
                            $('#msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg').html("");
                            }, 9000);
                        } else {
                            if (signer.trim().length == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez la signature');
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            } else {
                                if (signer.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le status');
                                    $('#msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#msg').html("");
                                    }, 9000);
                                } else {
                                    if (statut.trim().length == 0) {
                                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le status');
                                        $('#msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    } else {
                                        if (numero_invitation.trim().length == 0) {
                                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> completez le numero de l\'invitation');
                                            $('#msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#msg').html("");
                                            }, 9000);
                                        } else {
                                            $("#save").attr("disabled", true);
                                            $.ajax({
                                                type: "POST",
                                                url: "/add_invitations",
                                                data: data,
                                                success: function(response) {
                                                    $("#save").attr("disabled", false);
                                                    Dropzone.forElement('#dropzonewidget').removeAllFiles(true)
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
    });
    $("#oui").click(function(e) {
        e.preventDefault();
        var id = $("#data_id").html();
        $.get("{{ url('/refresh_deleteinvitation') }}", {
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
    // $('.commodites').selectMultiple();
</script>
@endsection
@endsection
