<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-eye text-info"></i> Details </h4>
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
        <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">ATTACHE DU PROJET</h4>
    </div>
</div>
<?php
$attaches_d = explode("____________________", $decisions->decisions_link);
$v_a_d = "";
$v_a_p = "";
$attaches_p = explode("____________________", $decisions->pv_link);
foreach ($attaches_d as $ad) {
    if (strlen(trim($ad)) != 0) {
        $v_a_d = $ad;
        break;
    }
}
foreach ($attaches_p as $ap) {
    if (strlen(trim($ap)) != 0) {
        $v_a_p = $ap;
        break;
    }
}
?>
<div class="row">
    <div class="col-12">
        <iframe style="width: 100%;height: 500px;" src="<?= $v_a_d ?>" frameborder="0"></iframe>
    </div>
</div>
