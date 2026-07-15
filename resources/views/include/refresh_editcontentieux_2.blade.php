<?php

use App\Models\numdeclarations;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-folder text-info"></i> Dossier </h4>
<form id="form_edit" action="#" method="post">
    @csrf
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">PROJET</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr style="display: none;">
                    <th>Nom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Numero</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->num_projet ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Nom</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->nom_projet ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Création</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->date_creation ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->budget ?>$</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->nombre_personne ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Début</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->debut ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Fin</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->fin ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Target</td>
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
                        if (strlen(trim($decisions->date_cloture)) != 0) {
                            $__d1 = explode("/", $decisions->date_cloture)[0];
                            $__m1 = explode("/", $decisions->date_cloture)[1];
                            $__y1 = explode("/", $decisions->date_cloture)[2];
                        }

                        // date fin
                        $__d2 = explode("/", $decisions->fin)[0];
                        $__m2 = explode("/", $decisions->fin)[1];
                        $__y2 = explode("/", $decisions->fin)[2];

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
                        $date_fin = $decisions->fin;
                        $date_en_cours = date("d/m/Y");
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $decisions->debut)[0];
                        $__m1 = explode("/", $decisions->debut)[1];
                        $__y1 = explode("/", $decisions->debut)[2];
                        // date fin
                        $__d2 = explode("/", $decisions->fin)[0];
                        $__m2 = explode("/", $decisions->fin)[1];
                        $__y2 = explode("/", $decisions->fin)[2];

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
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-alarm text-info"></i> Jours en cours</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                        $target = 0;
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $decisions->debut)[0];
                        $__m1 = explode("/", $decisions->debut)[1];
                        $__y1 = explode("/", $decisions->debut)[2];
                        // date fin
                        $__d2 = date("d");
                        $__m2 = date("m");
                        $__y2 = date("y");
                        if (strlen(trim($decisions->date_cloture)) != 0) {
                            $__d2 = explode("/", $decisions->date_cloture)[0];
                            $__m2 = explode("/", $decisions->date_cloture)[1];
                            $__y2 = explode("/", $decisions->date_cloture)[2];
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
                                <span class="text-success"><?= $target ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                <span class="text-info"><?= $target ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                <span class="text-warning"><?= $target ?> Jour</span>
                            <?php } else if ($postes_a == 0) { ?>
                                <span class="text-danger"><?= $target ?> Jour</span>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($target <= $duree) { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= $target ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= $target ?> Jours</span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= $target ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= $target ?> Jours</span>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-comment text-info"></i> Description du projet</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->description ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;"><?= strtoupper($contentieux->nom_projet) ?></h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr style="display: none;">
                    <th>Nom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Numero</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->num_projet ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Nom</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->nom_projet ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Création</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->date_creation ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->budget ?>$</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->nombre_personne ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Début</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->debut ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Fin</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->fin ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Target</td>
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
                        if (strlen(trim($contentieux->date_cloture)) != 0) {
                            $__d1 = explode("/", $contentieux->date_cloture)[0];
                            $__m1 = explode("/", $contentieux->date_cloture)[1];
                            $__y1 = explode("/", $contentieux->date_cloture)[2];
                        }

                        // date fin
                        $__d2 = explode("/", $contentieux->fin)[0];
                        $__m2 = explode("/", $contentieux->fin)[1];
                        $__y2 = explode("/", $contentieux->fin)[2];

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
                        $date_fin = $decisions->fin;
                        $date_en_cours = date("d/m/Y");
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $contentieux->debut)[0];
                        $__m1 = explode("/", $contentieux->debut)[1];
                        $__y1 = explode("/", $contentieux->debut)[2];
                        // date fin
                        $__d2 = explode("/", $contentieux->fin)[0];
                        $__m2 = explode("/", $contentieux->fin)[1];
                        $__y2 = explode("/", $contentieux->fin)[2];

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
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-alarm text-info"></i> Jours en cours</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                        $target = 0;
                        $semaine = ["Dimanche", "Lundi", " Mardi ", "Mercredi ", "Jeudi", "Vendredi", "Samedi"];
                        $mois = array(1 => "Janvier", "Février ", "Mars ", "Avril ", "Mai ", "Juin", "Juillet", "Août ", "Septembre", "Octobre", "Novembre", "Décembre");
                        // date debut
                        $__d1 = explode("/", $contentieux->debut)[0];
                        $__m1 = explode("/", $contentieux->debut)[1];
                        $__y1 = explode("/", $contentieux->debut)[2];
                        // date fin
                        $__d2 = date("d");
                        $__m2 = date("m");
                        $__y2 = date("y");
                        if (strlen(trim($contentieux->date_cloture)) != 0) {
                            $__d2 = explode("/", $contentieux->date_cloture)[0];
                            $__m2 = explode("/", $contentieux->date_cloture)[1];
                            $__y2 = explode("/", $contentieux->date_cloture)[2];
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
                                <span class="text-success"><?= $target ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                <span class="text-info"><?= $target ?> Jour</span>
                            <?php } ?>
                            <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                <span class="text-warning"><?= $target ?> Jour</span>
                            <?php } else if ($postes_a == 0) { ?>
                                <span class="text-danger"><?= $target ?> Jour</span>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($target <= $duree) { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= $target ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= $target ?> Jours</span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ((($postes_a <= (($duree * 100) / 100)) && ($postes_a >= (($duree * 48) / 100))) || (($postes_a >= (($duree * 100) / 100)))) { ?>
                                    <span class="text-success"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 48) / 100)) && ($postes_a >= (($duree * 23) / 100))) { ?>
                                    <span class="text-info"><?= $target ?> Jours</span>
                                <?php } ?>
                                <?php if (($postes_a <= (($duree * 23) / 100)) && ($postes_a >= (($duree * 1) / 100))) { ?>
                                    <span class="text-warning"><?= $target ?> Jours</span>
                                <?php } else if ($postes_a == 0) { ?>
                                    <span class="text-danger"><?= $target ?> Jours</span>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-comment text-info"></i> Description du projet</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->description ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <div style="margin-top: -20px;" class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $contentieux->id ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Résolution </span></label>
                <select id="resolution" name="resolution" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    <option class="form-control" value="">Selectionnez une résolution</option>
                    <option class="form-control" value="0">Classement </option>
                    <option class="form-control" value="1">Accord transactionnel</option>
                </select>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Conclusion </span></label>
                <textarea id="note_1" name="note_1" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Conclusion" cols="2" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Note 1 </span></label>
                <textarea id="note_2" name="note_2" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Note 1" cols="2" rows="2"></textarea>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Note 2 </span></label>
                <textarea id="note_3" name="note_3" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Note 2" cols="2" rows="2"></textarea>
            </div>
        </div>
    </div>
</form>
<form action="" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-12">
            <?php

            use App\Models\Writes;
            use Illuminate\Support\Facades\Auth;

            if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $edit = 0;
                $delete = 0;
                $add = 0;
                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                }
                ?>
            <?php } ?>
            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                <button id="_cloturer" class="btn btn-info btn-sm">Cloturer <i class="zmdi zmdi-check-circle"></i></button>
            <?php } else { ?>
                <button id="edit_save_r" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-save"></i></button>
            <?php } ?>
            <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <span style="font-weight: bold;" id="cloture_msg">
            </span>
        </div>
    </div>
</form>
<script>
    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });
    $("#_cloturer").click(function(e) {
        e.preventDefault();
        var resolution = $("#resolution").val();
        var note_1 = $("#note_1").val();
        var note_2 = $("#note_2").val();
        var note_3 = $("#note_3").val();
        if (resolution.trim().length == 0) {
            $('#cloture_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le type de résolution de cette étape');
            $('#cloture_msg').css("color", "#ff6b68");
            setTimeout(() => {
                $('#cloture_msg').html("");
            }, 9000);
        } else {
            if (note_1.trim().length == 0) {
                $('#cloture_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la conclusion');
                $('#cloture_msg').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#cloture_msg').html("");
                }, 9000);
            } else {
                if (note_2.trim().length == 0) {
                    $('#cloture_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la note 1');
                    $('#cloture_msg').css("color", "#ff6b68");
                    setTimeout(() => {
                        $('#cloture_msg').html("");
                    }, 9000);
                } else {
                    if (note_3.trim().length == 0) {
                        $('#cloture_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la note 2');
                        $('#cloture_msg').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#cloture_msg').html("");
                        }, 9000);
                    } else {
                        $("#cloturer_").attr("disabled", true);
                        $.get("{{ url('/cloturer_proces') }}", {
                            id: "<?= $contentieux->id ?>",
                            resolution: resolution,
                            note_1: note_1,
                            note_2: note_2,
                            note_3: note_3
                        }, function(refresh_editutilisateur) {
                            $("#cloturer_").attr("disabled", false);
                            $('#cloture_msg').html('<i class="zmdi zmdi-check-circle"></i> proces verbal cloturé avec succès');
                            $('#cloture_msg').css("color", '#32c787');
                            $("#content_utilisateur").html(refresh_editutilisateur);
                            $("#resolution").val("");
                            $("#note_1").val("");
                            $("#note_2").val("");
                            $("#note_3").val("");
                            $("#_cloturer").attr("disabled", true);
                            setTimeout(() => {
                                $('#cloture_msg').html("");
                            }, 9000);
                        });
                    }

                }
            }

        }
    });
    $("#type_objet").change(function(e) {
        var type_objet = $("#type_objet").val();
        if (type_objet == 0) {
            $("#bloc_date").hide();
        }
        if (type_objet == 1) {
            $("#bloc_date").show();
        }
    });
</script>
