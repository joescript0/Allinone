<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Matricule</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Salaire</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Email</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Role / Fonction</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Poste / Lieux</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($utilisateurs as $data)
                    <tr id="row_{{ $data->id }}">
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="matricule-cell"
                            data-matricule="{{ $data->matricule }}">{{ $data->matricule }}</td>
                        <td class="align-middle nom-cell" data-nom="{{ $data->name }}"
                            style="padding-top: 5px;padding-bottom: 5px;">
                            <a id="voir_profil_<?= $i ?>" href="#">
                                <img src="{{ asset($data->image) }}" alt="avatar" class="profile-thumb">
                            </a> {{ $data->name }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="salaire-cell"
                            data-salaire="{{ $data->salaire }}" data-devise="{{ $data->devise }}">
                            @if ($data->devise == 0)
                                {{ number_format($data->salaire, 2, ',', ' ') . '$' }}
                            @else
                                {{ number_format($data->salaire, 2, ',', ' ') . 'Fc' }}
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="email-cell"
                            data-email="{{ $data->email }}">{{ $data->email }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="phone-cell"
                            data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="role-cell"
                            data-role="{{ $data->role }}">
                            @if ($groupes->count() != 0)
                                <?= Groupes::where('id', $data->role)->first()['nom'] ?? 'N/A' ?>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="poste-cell"
                            data-poste="{{ $data->poste_id }}">
                            <?php
                            $potess = Postes::where('id', $data->poste_id)->first();
                            ?>
                            @if ($data->poste_id == 0)
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                    class="text-danger">{{ 'Aucun' }} </span>
                            @else
                                <i class="zmdi zmdi-check-circle text-success"></i> <span
                                    class="text-success"><?= $potess['nom'] ?? 'N/A' ?>,
                                    <?= Lieux::where(['id' => $potess['lieuxe_id'] ?? 0])->first()['nom'] ?? 'N/A' ?>.</span>
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
                                    $.get("{{ url('/refresh_editutilisateur') }}", {
                                        user_id: <?= $data->id ?>,
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
                                $("#voir_profil_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#nom_profil").html("<?= $data->name ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    var url = "<?= $data->image ?>";
                                    $("#contenu_voir_profil").html('<img src="' + url +
                                        '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                    );
                                    $("#btn_voir_profil").trigger("click");
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
