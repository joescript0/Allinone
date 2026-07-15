<?php

use App\Models\Contentieurs;
use App\Models\Frais;
use App\Models\Travailleurs;

$contentieurs = Contentieurs::where('decision_id', $decisions->id)->get();
$user_total = 0;
$user_paie = 0;
$total_1 = 0;
$total_2 = 0;
foreach ($contentieurs as $c)
{
    $travailleurs = Travailleurs::where('contentieur_id', $c->id)->get();
    foreach ($travailleurs as $tv)
    {
        $user_total++;
        $user_paie = $user_paie + $tv->paie;
    }
    $frais = Frais::where('contentieur_id', $c->id)->get();
    foreach ($frais as $f) {
        if ($f->paye == 1)
        {
            if ($f->devise == 0) {
                $total_1 =  $total_1 + $f->montant;
            } else {
                $total_1 =  ($total_1 + ($f->montant / $f->taux));
            }
        }
    }
    foreach ($frais as $f)
    {
        if ($f->paye == 0) {
            if ($f->devise == 0) {
                $total_2 =  $total_2 + $f->montant;
            } else {
                $total_2 =  round($total_2 + ($f->montant / $f->taux));
            }
        }
    }
}
?>





<div style="margin-bottom: 100px;">
    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-eye text-info"></i> Rapports </h4>
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">PROJET <?= strtoupper($decisions->nom_projet) ?></h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
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
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-book text-info"></i> Dépenses total</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($total_1, 2, ',', ' ') ?>$</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne initial</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $decisions->nombre_personne ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne total</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= $user_total ?></td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Paiement de personne</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($user_paie, 2, ',', ' ') ?>$</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget initial</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($decisions->budget, 2, ',', ' ') ?>$</td>
                </tr>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget total du projet</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format(($total_1 + $user_paie), 2, ',', ' ') ?>$</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php $i = 1;
    foreach ($contentieurs as $contentieux) { ?>
        <hr>
        <div class="row">
            <div class="col-12">
                <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">ETAPE <?= $i ?> : <?= strtoupper($contentieux->nom_projet) ?></h4>
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
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-info text-info"></i> Nom</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->nom_projet ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-calendar text-info"></i> Création</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->date_creation ?></td>
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
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Dépenses</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                            $fr = Frais::where('contentieur_id', $contentieux->id)->get();
                            $total_1_ = 0;
                            $total_2_ = 0;
                            foreach ($fr as $f) {
                                if ($f->paye == 1)
                                {
                                    if ($f->devise == 0) {
                                        $total_1_ =  $total_1_ + $f->montant;
                                    } else {
                                        $total_1_ =  ($total_1_ + ($f->montant / $f->taux));
                                    }
                                }
                            }
                            foreach ($fr as $f)
                            {
                                if ($f->paye == 0) {
                                    if ($f->devise == 0) {
                                        $total_2_ =  $total_2_ + $f->montant;
                                    } else {
                                        $total_2_ =  round($total_2_ + ($f->montant / $f->taux));
                                    }
                                }
                            }
                            echo number_format($total_1_, 2, ',', ' ');;
                        ?>$
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne initial</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><?= $contentieux->nombre_personne ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-accounts text-info"></i> Nombre de personne total</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                            $travailleurs = Travailleurs::where('contentieur_id', $contentieux->id)->get();
                            $u_total = 0;
                            $u_paie = 0;
                            foreach ($travailleurs as $t)
                            {
                                $u_total++;
                                $u_paie = $u_paie + $t->paie;
                            }
                            echo $u_total;
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Paiement de personne</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                            $travailleurs = Travailleurs::where('contentieur_id', $contentieux->id)->get();
                            $u_total = 0;
                            $u_paie = 0;
                            foreach ($travailleurs as $t)
                            {
                                $u_total++;
                                $u_paie = $u_paie + $t->paie;
                            }
                            echo number_format($u_paie, 2, ',', ' ');
                        ?>$
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget initial</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($contentieux->budget, 2, ',', ' ') ?>$</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;"><i class="zmdi zmdi-money text-info"></i> Budget total de l'etape</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                            $travailleurs = Travailleurs::where('contentieur_id', $contentieux->id)->get();
                            $u_total = 0;
                            $u_paie = 0;
                            foreach ($travailleurs as $t)
                            {
                                $u_total++;
                                $u_paie = $u_paie + $t->paie;
                            }
                            $fr = Frais::where('contentieur_id', $contentieux->id)->get();
                            $total_1_ = 0;
                            $total_2_ = 0;
                            foreach ($fr as $f) {
                                if ($f->paye == 1)
                                {
                                    if ($f->devise == 0) {
                                        $total_1_ =  $total_1_ + $f->montant;
                                    } else {
                                        $total_1_ =  ($total_1_ + ($f->montant / $f->taux));
                                    }
                                }
                            }
                            foreach ($fr as $f)
                            {
                                if ($f->paye == 0) {
                                    if ($f->devise == 0) {
                                        $total_2_ =  $total_2_ + $f->montant;
                                    } else {
                                        $total_2_ =  round($total_2_ + ($f->montant / $f->taux));
                                    }
                                }
                            }
                            echo number_format(($u_paie + $total_1_), 2, ',', ' ');
                        ?>$
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php $i++;
    } ?>

</div>
