<?php

use App\Models\Contrevenants;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom du projet</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Budget</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Target</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Jours en cours</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($decisions as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_projet }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ number_format($data->budget, 2, ',', ' ') }}$</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">Du {{ $data->debut }} Au {{ $data->fin }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                        $postes_a = 0;
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        // date fin
                        $__d1 = date("d");
                        $__m1 = date("m");
                        $__y1 = date("y");
                        if (strlen(trim($data->date_cloture)) != 0) {
                            $__d1 = explode("/", $data->date_cloture)[0];
                            $__m1 = explode("/", $data->date_cloture)[1];
                            $__y1 = explode("/", $data->date_cloture)[2];
                        }

                        // date fin
                        $__d2 = explode("/", $data->fin)[0];
                        $__m2 = explode("/", $data->fin)[1];
                        $__y2 = explode("/", $data->fin)[2];

                        $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                        $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                        while (strtotime($date_1) <= strtotime($date_2)) {
                            $jours = 1;
                            $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                            if ($semaine[date('w', $valeur_date)] != "") {
                                $postes_a++;
                            }
                            $datedd = date("m/d/Y", strtotime(date('' . explode("/", $date_1)[0] . '/' . explode("/", $date_1)[1] . '/' . explode("/", $date_1)[2] . '') . ' + ' . $jours . ' days'));
                            $date_1 = explode("/", $datedd)[1] . '/' . explode("/", $datedd)[0] . '/' . explode("/", $datedd)[2];
                            $date_1 = explode("/", $datedd)[0] . '/' . explode("/", $datedd)[1] . '/' . explode("/", $datedd)[2];
                        }
                        ?>


                        <?php
                        $duree = 0;
                        $nb_abonnement = 0;
                        $date_fin = $data->fin;
                        $date_en_cours = date("d/m/Y");
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $data->debut)[0];
                        $__m1 = explode("/", $data->debut)[1];
                        $__y1 = explode("/", $data->debut)[2];
                        // date fin
                        $__d2 = explode("/", $data->fin)[0];
                        $__m2 = explode("/", $data->fin)[1];
                        $__y2 = explode("/", $data->fin)[2];

                        $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                        $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                        while (strtotime($date_1) <= strtotime($date_2)) {
                            $jours = 1;
                            $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                            if ($semaine[date('w', $valeur_date)] != "") {
                                $duree++;
                            }
                            $datedd = date("m/d/Y", strtotime(date('' . explode("/", $date_1)[0] . '/' . explode("/", $date_1)[1] . '/' . explode("/", $date_1)[2] . '') . ' + ' . $jours . ' days'));
                            $date_1 = explode("/", $datedd)[1] . '/' . explode("/", $datedd)[0] . '/' . explode("/", $datedd)[2];
                            $date_1 = explode("/", $datedd)[0] . '/' . explode("/", $datedd)[1] . '/' . explode("/", $datedd)[2];
                        }

                        ?>
                        <?php if ($duree <= 1) { ?>
                            <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                <span class="text-success"><?= $postes_a ?></span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                <span class="text-info"><?= $postes_a ?></span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                <span class="text-warning"><?= $postes_a ?></span>
                            <?php } else if ($postes_a == 0) { ?>
                                <span class="text-danger"><?= $postes_a ?></span>
                            <?php } ?>
                            / <?= $duree ?> Jour
                        <?php } else { ?>
                            <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                <span class="text-success"><?= $postes_a ?></span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                <span class="text-info"><?= $postes_a ?></span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                <span class="text-warning"><?= $postes_a ?></span>
                            <?php } else if ($postes_a == 0) { ?>
                                <span class="text-danger"><?= $postes_a ?></span>
                            <?php } ?>
                            / <?= $duree ?> Jours
                        <?php } ?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                        $target = 0;
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $data->debut)[0];
                        $__m1 = explode("/", $data->debut)[1];
                        $__y1 = explode("/", $data->debut)[2];
                        // date fin
                        $__d2 = date("d");
                        $__m2 = date("m");
                        $__y2 = date("y");
                        if (strlen(trim($data->date_cloture)) != 0) {
                            $__d2 = explode("/", $data->date_cloture)[0];
                            $__m2 = explode("/", $data->date_cloture)[1];
                            $__y2 = explode("/", $data->date_cloture)[2];
                        }

                        $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                        $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                        while (strtotime($date_1) <= strtotime($date_2)) {
                            $jours = 1;
                            $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                            if ($semaine[date('w', $valeur_date)] != "") {
                                $target++;
                            }
                            $datedd = date("m/d/Y", strtotime(date('' . explode("/", $date_1)[0] . '/' . explode("/", $date_1)[1] . '/' . explode("/", $date_1)[2] . '') . ' + ' . $jours . ' days'));
                            $date_1 = explode("/", $datedd)[1] . '/' . explode("/", $datedd)[0] . '/' . explode("/", $datedd)[2];
                            $date_1 = explode("/", $datedd)[0] . '/' . explode("/", $datedd)[1] . '/' . explode("/", $datedd)[2];
                        }
                        ?>
                        <?php if ($target <= 1) { ?>
                            <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                <span class="text-success"><?= ($target - 1) ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                <span class="text-info"><?= ($target - 1) ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                <span class="text-warning"><?= ($target - 1) ?> Jour</span>
                            <?php } else if ($postes_a == 0) { ?>
                                <span class="text-danger"><?= ($target - 1) ?> Jour</span>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($target <= $duree) { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= ($target - 1) ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= ($target - 1) ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= ($target - 1) ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= ($target-1) ?> Jours</span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= ($target - 1) ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= ($target - 1) ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= ($target - 1) ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= ($target - 1) ?> Jours</span>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                            <?php
                            $edit = 0;
                            $delete = 0;
                            $display = 0;
                            if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                $display = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->display;
                            }
                            ?>
                        <?php } ?>
                        <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                            <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                        <?php } else { ?>
                            <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                        <?php } ?>
                        <?php if ((($edit == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($edit == 0) && (Auth::user()->role == 0))) { ?>
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                        <?php } else { ?>
                            <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                        <?php } ?>
                        <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($delete == 0) && (Auth::user()->role == 0))) { ?>
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <?php } else { ?>
                            <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <?php } ?>
                        <script>
                            $("#edit_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_editdecisions') }}", {
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
                            $("#detail_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_detaildecisions') }}", {
                                    invitation_id: <?= $data->id ?>,
                                }, function(refresh_editinvitations) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").show();
                                    $("#bloc_3").html(refresh_editinvitations);
                                });
                            });
                            $("#detail_r<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#btn_refus").trigger("click");
                            });
                            $("#delete_r<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#btn_refus").trigger("click");
                            });
                            $("#delete_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element").html("<?= $data->numero_decision ?>");
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
