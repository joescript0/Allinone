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
                    <th style="padding-top: 5px;padding-bottom: 5px;">Mois</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($listespaies as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if ($data->etat == 0)
                            <i class="zmdi zmdi-block text-danger"></i> <span class="text-danger">Désactivé </span>
                        @endif
                        @if ($data->etat == 1)
                            <i class="zmdi zmdi-block text-info"></i> <span class="text-info">Activé </span>
                        @endif
                        @if ($data->etat == 2)
                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Cloturé </span>
                        @endif
                    </td>
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
                        <a id="listespaies_<?= $i ?>" href="#"><i class="zmdi zmdi-money text-info"></i></a> &nbsp;&nbsp;
                        <a id="print_<?= $i ?>" href="#"><i class="zmdi zmdi-print text-dark"></i></a> &nbsp;&nbsp;
                        <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>

                        <script>
                            $("#delete_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element").html("{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}");
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
                                    $("#bloc_4").hide();
                                    $("#bloc_3").html(refresh_editinvitations);
                                });
                            });
                            $("#print_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/get_print_paie') }}", {
                                    invitation_id: <?= $data->id ?>,
                                }, function(response) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").hide();
                                    $("#bloc_4").hide();
                                    $("#bloc_5").show();
                                    $("#data_liste").attr('src', '{{ asset("")  }}' + response);
                                });
                            });
                            $("#activer_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element_1").html("{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}");
                                $("#data_id").html("<?= $data->id ?>");
                                $("#btn_ac").trigger("click");
                            });
                            $("#cloturer_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element_2").html("{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}");
                                $("#data_id").html("<?= $data->id ?>");
                                $("#btn_cl").trigger("click");
                            });
                            $("#cloturerr_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element_3").html("{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}");
                                $("#data_id").html("<?= $data->id ?>");
                                $("#btn_cll").trigger("click");
                            });
                            $("#attente_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element_4").html("{{ Mois::where(["id" => $data->moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $data->annee_id])->first()["annees"]; }}");
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
