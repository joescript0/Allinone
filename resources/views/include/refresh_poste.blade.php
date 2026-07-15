<?php


use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\Lieux;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Code</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Noms</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Lieux</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Clients</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Affectations</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($postes as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->code }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ Lieux::where(["id" => $data->lieuxe_id])->first()["nom"]; }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ Clients::where(["id" => $data->client_id])->first()["name"]; }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                        @if (User::where(["poste_id" => $data->id])->get()->count() == 0)
                            <a id="voir_affectation_<?= $i ?>" href="#"><i class="zmdi zmdi-accounts text-danger"></i> &nbsp;<span
                                        class="text-danger">{{ '0' }} </span></span></a>

                        @else
                            <a id="voir_affectation_<?= $i ?>" href="#"><i class="zmdi zmdi-accounts text-success"></i> &nbsp;<span
                                        class="text-success"> <?= User::where(["poste_id" => $data->id])->get()->count(); ?></span></a>
                        @endif
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if ($data->etat == 0)
                            <i class="zmdi zmdi-block text-danger"></i> <span class="text-danger">Désactivé </span>
                        @endif
                        @if ($data->etat == 1)
                            <i class="zmdi zmdi-block text-info"></i> <span class="text-info">Activé </span>
                        @endif
                        @if ($data->etat == 2)
                            <i class="zmdi zmdi-check-circle text-success"></i> <span
                                class="text-success">Cloturé </span>
                        @endif
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if (Postes::where(['etat' => 1])->get()->count() != 0)
                                    @if ($data->etat == 0)
                                        <a id="attente_<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-settings text-success"></i> <span
                                                class="text-danger"></span></a>
                                    @endif
                                    @if ($data->etat == 1)
                                        <a id="cloturer_<?= $i ?>" title="Activé" href="#"><i
                                                class="zmdi zmdi-settings text-success"></i> <span
                                                class="text-warning"></span></a>
                                    @endif
                                    @if ($data->etat == 2)
                                        <a id="cloturerr_<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-lock text-success"></i> <span
                                                class="text-success"></span></a>
                                    @endif
                                @else
                                    @if ($data->etat == 0)
                                        <a id="activer_<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-settings text-success"></i> <span
                                                class="text-danger"></span></a>
                                    @endif
                                    @if ($data->etat == 1)
                                        <a id="cloturer_<?= $i ?>" title="Activé" href="#"><i
                                                class="zmdi zmdi-settings text-success"></i> <span
                                                class="text-warning"></span></a>
                                    @endif
                                    @if ($data->etat == 2)
                                        <a id="cloturerr_<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-lock text-success"></i> <span
                                                class="text-success"></span></a>
                                    @endif
                                @endif
                                &nbsp;&nbsp;
                                <a id="listesfacures_<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-calendar text-info"></i></a> &nbsp;&nbsp;
                                <a id="print_<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-cast text-dark"></i></a> &nbsp;&nbsp;
                                <a id="delete_<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-delete text-danger"></i></a>
                                &nbsp;&nbsp;
                                <a id="edit_<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-edit text-success"></i></a> &nbsp;&nbsp;
                                <script>
                                    $("#voir_affectation_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        console.log("Voir affectation");
                                    });
                                    $("#edit_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $.get("{{ url('/refresh_editposte') }}", {
                                            poste_id: <?= $data->id ?>,
                                        }, function(refresh_editposte) {
                                            $("#bloc_1").hide();
                                            $("#bloc_2").hide();
                                            $("#bloc_3").show();
                                            $("#bloc_3").html(refresh_editposte);
                                        });
                                    });
                                    $("#delete_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element").html(
                                            "<span style='color:black;'>Code : </span>{{ $data->code }}, <span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Lieu : </span> {{ Lieux::where(['id' => $data->lieuxe_id])->first()['nom']; }}, <span style='color:black;'>Client : </span>{{ Clients::where(['id' => $data->client_id])->first()['name']; }}."
                                        );
                                        $("#data_id").html("<?= $data->id ?>");
                                        $("#btn_sup").trigger("click");
                                    });
                                    $("#listesfacures_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $.get("{{ url('/refresh_programme') }}", {
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
                                        $.get("{{ url('/get_one_qr_code') }}", {
                                            poste_id : <?= $data->id ?>,
                                        }, function(response)
                                        {
                                            $("#bloc_1").hide();
                                            $("#bloc_2").hide();
                                            $("#bloc_3").hide();
                                            $("#bloc_4").show();
                                            $("#data_liste").attr('src', '{{ asset("")  }}' + response);
                                        });
                                    });
                                    $("#activer_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element_1").html(
                                            "<span style='color:black;'>Code : </span>{{ $data->code }}, <span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Lieu : </span> {{ Lieux::where(['id' => $data->lieuxe_id])->first()['nom']; }}, <span style='color:black;'>Client : </span>{{ Clients::where(['id' => $data->client_id])->first()['name']; }}."
                                        );
                                        $("#data_id").html("<?= $data->id ?>");
                                        $("#btn_ac").trigger("click");

                                    });
                                    $("#cloturer_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element_2").html(
                                            "<span style='color:black;'>Code : </span>{{ $data->code }}, <span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Lieu : </span> {{ Lieux::where(['id' => $data->lieuxe_id])->first()['nom']; }}, <span style='color:black;'>Client : </span>{{ Clients::where(['id' => $data->client_id])->first()['name']; }}."
                                        );
                                        $("#data_id").html("<?= $data->id ?>");
                                        $("#btn_cl").trigger("click");
                                    });
                                    $("#cloturerr_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element_3").html(
                                                "<span style='color:black;'>Code : </span>{{ $data->code }}, <span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Lieu : </span> {{ Lieux::where(['id' => $data->lieuxe_id])->first()['nom']; }}, <span style='color:black;'>Client : </span>{{ Clients::where(['id' => $data->client_id])->first()['name']; }}."
                                        );
                                        $("#data_id").html("<?= $data->id ?>");
                                        $("#btn_cll").trigger("click");
                                    });
                                    $("#attente_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element_4").html(
                                                "<span style='color:black;'>Code : </span>{{ $data->code }}, <span style='color:black;'>Nom : </span>{{ $data->nom }}, <span style='color:black;'>Lieu : </span> {{ Lieux::where(['id' => $data->lieuxe_id])->first()['nom']; }}, <span style='color:black;'>Client : </span>{{ Clients::where(['id' => $data->client_id])->first()['name']; }}."
                                        );
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
