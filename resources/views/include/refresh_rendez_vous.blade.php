<?php

use App\Models\Mois;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Listespaies;
?>
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
