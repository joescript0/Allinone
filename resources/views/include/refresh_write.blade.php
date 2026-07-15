<?php

use App\Models\Ressources;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Rôles
    <select class="form-control" style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);margin-top:10px;" name="groupe_select" id="groupe_select">
        @foreach ($groupes as $data)
        @if ($data->id == $groupe_id)
        <option selected value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
        @else
        <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
        @endif
        @endforeach
    </select>
</h4>
<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Interfaces</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Voir</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Ajouter</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Modifier</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    {{! $i = 1; }}
                    @foreach ($writes as $data)
                     @if (Ressources::where('id', $data->ressource_id)->first()['visible'] == 1)
                            <tr>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $i ?></td>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= Ressources::where('id', $data->ressource_id)->first()['nom'] ?></td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->display == 1)
                                <a id="display__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                                @else
                                <a id="display__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                                @endif
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->add == 1)
                                <a id="add__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                                @else
                                <a id="add__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                                @endif
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->edit == 1)
                                <a id="edit__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                                @else
                                <a id="edit__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                                @endif
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->delete == 1)
                                <a id="delete__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                                @else
                                <a id="delete__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                                @endif
                            </td>
                        </tr>
                        <script>
                            $("#display__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_display') }}", {
                                    write_id: <?= $data->id ?>,
                                }, function(etat) {
                                    if (etat == 1)
                                    {
                                        $("#display__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                    } else {
                                        $("#display__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                    }
                                });
                            });
                            $("#add__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_add') }}", {
                                    write_id: <?= $data->id ?>,
                                }, function(etat) {
                                    if (etat == 1) {
                                        $("#add__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                    } else {
                                        $("#add__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                    }
                                });
                            });
                            $("#edit__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_edit') }}", {
                                    write_id: <?= $data->id ?>,
                                }, function(etat) {
                                    if (etat == 1) {
                                        $("#edit__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                    } else {
                                        $("#edit__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                    }
                                });
                            });
                            $("#delete__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_delete') }}", {
                                    write_id: <?= $data->id ?>,
                                }, function(etat) {
                                    if (etat == 1) {
                                        $("#delete__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                    } else {
                                        $("#delete__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                    }
                                });
                            });
                        </script>
                    {{! $i++; }}
                     @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $("#groupe_select").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/refresh_write') }}", {
            groupe_id: $("#groupe_select").val(),
        }, function(liste_r) {
            $("#bloc_1").hide();
            $("#bloc_4").show();
            $("#bloc_4").html(liste_r);
        });
    });
</script>
