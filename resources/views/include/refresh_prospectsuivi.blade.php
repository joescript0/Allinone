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
                    <!-- ===== COLONNE CLIENT AVEC BADGES ===== -->
                    <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($prospects as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="nom-cell"
                            data-nom="{{ $data->name }}">{{ $data->name }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="email-cell"
                            data-email="{{ $data->email }}">{{ $data->email }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell"
                            data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="type-cell"
                            data-type="{{ $data->type }}">
                            @if ($data->type == 0)
                                Privé
                            @else
                                Entreprise
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="activite-cell"
                            data-activite="{{ $data->activite_id }}">
                            <?= Activites::where('id', $data->activite_id)->first()['nom'] ?>
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="adresse-cell"
                            data-adresse="{{ $data->adresse }}">{{ $data->adresse }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell"
                            data-user="{{ $data->user_id }}">
                            @if (Auth::user()->id == $data->user_id)
                                Vous
                            @else
                                {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                            @endif
                        </td>
                        <!-- ===== CELLULE CLIENT AVEC BADGE ===== -->
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell"
                            data-client-id="{{ $data->client_id }}">
                            @if ($data->client_id == 0)
                                <span class="badge badge-danger">Non</span>
                            @else
                                <span class="badge badge-success">Oui</span>
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
                            <!-- ===== ÉDITION ===== -->
                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <?php } else { ?>
                            <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>
                            &nbsp;
                            <?php } ?>
                            <!-- ===== SUPPRESSION ===== -->
                            <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            &nbsp;
                            <?php } else { ?>
                            <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            &nbsp;
                            <?php } ?>
                            <!-- ===== CARTE ===== -->
                            <a id="map_<?= $i ?>" href="#" data-id="<?= $data->id ?>"
                                data-lat="<?= $data->latitude ?? '' ?>" data-lng="<?= $data->longitude ?? '' ?>"
                                data-nom="<?= htmlspecialchars($data->name) ?>"
                                data-adresse="<?= htmlspecialchars($data->adresse ?? '') ?>"
                                data-phone="<?= htmlspecialchars($data->phone ?? '') ?>"
                                data-email="<?= htmlspecialchars($data->email ?? '') ?>">
                                <i class="zmdi zmdi-pin"></i>
                            </a> &nbsp;
                            <!-- ===== TRANSFORMATION (après la carte) ===== -->
                            <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                            <a id="transform_<?= $i ?>" href="#" data-id="<?= $data->id ?>"
                                data-nom="<?= htmlspecialchars($data->name) ?>"
                                data-client-id="<?= $data->client_id ?>">
                                <i
                                    class="zmdi zmdi-save <?= $data->client_id == 0 ? 'text-danger' : 'text-success' ?>"></i>
                            </a> &nbsp;
                            <?php } else { ?>
                            <a id="transform_r<?= $i ?>" href="#">
                                <i class="zmdi zmdi-save text-muted"></i>
                            </a> &nbsp;
                            <?php } ?>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_editprospect') }}", {
                                        prospect_id: <?= $data->id ?>,
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
