<?php

use App\Models\Verbalisateurs;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
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
