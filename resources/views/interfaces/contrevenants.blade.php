@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'CONTREVENANTS')
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
                                    <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-accounts" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
                                    <p style="color:rgba(0, 0, 0, 0.6);text-align: center;font-size: 10px;margin-top: -18px;">Liste</p>
                                </a>
                            </div>
                            <div id="add" class="col-1">
                                <a href="">
                                    <p style="text-align: center;font-size: 12px;color:rgba(0, 0, 0, 0.6);"><i class="zmdi zmdi-accounts-add" style="color:rgba(0, 0, 0, 0.6);font-size: 25px;"></i></p>
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Contrevenants</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i> Liste</h4>
                <div id="content_groupe" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($contrevenants as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                            <script>
                                                $("#edit_<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $.get("{{ url('/refresh_editcontrevenant') }}", {
                                                        contrevenant_id: <?= $data->id ?>,
                                                    }, function(refresh_editcontrevenant) {
                                                        $("#bloc_1").hide();
                                                        $("#bloc_2").hide();
                                                        $("#bloc_3").show();
                                                        $("#bloc_3").html(refresh_editcontrevenant);
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
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts-add text-info"></i> Ajouter</h4>
                <form id="form_add" action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom </span></label>
                                <input type="text" id="nom" name="nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : MGM)">
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
    $("#link_12").css("border-left", "1px solid rgb(33, 150, 243)");
    $("#text_12").addClass("text-info");
    $("#icone_12").css("color", "rgb(33, 150, 243)");
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
        var data = $("#form_add").serialize();
        if (nom.trim().length == 0) {
            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            $('#msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            $.ajax({
                type: "POST",
                url: "/check_contrevenant",
                data: data,
                success: function(response) {
                    if (response == 1) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Ce groupe existe déjà');
                        $('#msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg').html("");
                        }, 9000);
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/add_contrevenant",
                            data: data,
                            success: function(response) {
                                $("#nom").val("");
                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Contrevenant ajouté avec succès');
                                $('#msg').css("color", '#32c787');
                                $("#content_groupe").html(response);
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
        $.get("{{ url('/refresh_deletecontrevenant') }}", {
            id: id,
        }, function(refresh_editcontrevenant) {
            $("#content_groupe").html(refresh_editcontrevenant);
            $("#non").trigger("click");
        });
    });
</script>
@endsection
@endsection
