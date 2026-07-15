<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Activites;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Type</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Activité</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Adresse</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($clients as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->name }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->email }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->phone }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->type == 0)
                                Privé
                            @else
                                Entreprise
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            <?= Activites::where('id', $data->activite_id)->first()['nom'] ?></td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->adresse }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if (Auth::user()->id == $data->user_id)
                                Vous
                            @else
                                {{ User::where('id', $data->user_id)->first()['name'] }}
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                            <?php
                            $edit = 0;
                            $delete = 0;
                            if (
                                Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                    ->get()
                                    ->count() != 0
                            ) {
                                $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                            }
                            ?>
                            <?php } ?>
                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <?php } else { ?>
                            <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>
                            &nbsp;
                            <?php } ?>
                            <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <?php } else { ?>
                            <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <?php } ?>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_editclient') }}", {
                                        client_id: <?= $data->id ?>,
                                    }, function(refresh_editutilisateur) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_editutilisateur);
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
                                    $("#element").html("<?= $data->name ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_sup").trigger("click");
                                });
                            </script>
                        </td>
                    </tr>
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
