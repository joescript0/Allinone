<link rel="stylesheet" href="{{ asset('assets/vendors/dropzone/dropzone_frais.css') }}">
<?php

use App\Models\Contentieurs;
use App\Models\Groupes;
use App\Models\numdeclarations;
use App\Models\Payer;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Writes;
use Illuminate\Support\Facades\Auth;

?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-folder text-info"></i> Dossier </h4>
<div style="margin-bottom: 100px;">
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
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($decisions->budget, 2, ',', ' ') ?>$</td>
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
                    <td style="padding-top: 5px;padding-bottom: 5px;"><?= number_format($contentieux->budget, 2, ',', ' ') ?>$</td>
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
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">DEPENSES</h4>
        </div>
    </div>
    @if ($contentieux->cloturer == 0)
    <div class="row">
        <div class="col-12">
            <form id="add_frais" action="#" method="post">
                @csrf
                <div>
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-plus-circle text-info"></i> Ajouter</h4>
                    <div style="margin-top: 30px;" id="content_groupe" class="row">
                        <div style="margin-top: -30px;" class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Type de depenses </span></label>
                                <select id="frais" name="frais" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Selectionnez un type depense</option>
                                    @foreach ($type_frais as $data)
                                    <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="margin-top: -30px;" class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Montant </span></label>
                                <input type="text" id="montant" name="montant" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Montant">
                            </div>
                        </div>
                        <div style="margin-top: -30px;" class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Devise </span></label>
                                <select id="_devise" name="_devise" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                    <option class="form-control" value="">Selectionnez une devise</option>
                                    <option class="form-control" value="0"> $</option>
                                    <option class="form-control" value="1"> Fc</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin-top: -30px;" class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Taux </span></label>
                                <input type="text" id="_taux" name="_taux" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Taux">
                            </div>
                        </div>
                        <div style="margin-top: -30px;" class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Libelle </span></label>
                                <textarea id="lib" name="lib" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libelle" cols="2" rows="2"></textarea>
                            </div>
                        </div>
                        <div style="margin-top: -30px;display:none;" class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> C id </span></label>
                                <input type="text" id="c_id" name="c_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libelle" value="<?= $contentieux->id ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez la preuve de paiment dans ce cadre </span></label>
            <form method="post" style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;padding: 20px;" action="{{ route('upload') }}" class="dropzone_frais" id="dropzonewidget_2">
                @csrf
            </form>
            <form action="">
                <div style="margin-top: 20px;" class="row">
                    <div class="col-lg-12 col-sm-12">
                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
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
                            <button id="save_frais" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                        <?php } else { ?>
                            <button id="save_r" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button>
                        <?php } ?>
                    </div>
                    <div class="col-lg-12" style="text-align: center;">
                        <span style="font-weight: bold;" id="frais_msg">
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <hr>
    <div class="row">
        <div id="bloc_frais" style="margin-top: 12px;" class="col-lg-12">
            <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Liste de dépenses</h4>
            <?php
            $total_1 = 0;
            $total_2 = 0;
            foreach ($frais as $f) {
                if ($f->paye == 1) {
                    if ($f->devise == 0) {
                        $total_1 =  $total_1 + $f->montant;
                    } else {
                        $total_1 =  ($total_1 + ($f->montant / $f->taux));
                    }
                }
            }
            foreach ($frais as $f) {
                if ($f->paye == 0) {
                    if ($f->devise == 0) {
                        $total_2 =  $total_2 + $f->montant;
                    } else {
                        $total_2 =  round($total_2 + ($f->montant / $f->taux));
                    }
                }
            }
            ?>
            <h6 style="text-align: right;font-weight: bold;"><span><i class="zmdi zmdi-close-circle text-danger"></i> Non approuvés : <span id="nb_total_1"><?= number_format($total_2, 2, ',', ' ') ?></span>$</span>, <span> <i class="zmdi zmdi-check-circle text-success"></i> Approuvés : <span id="nb_total_1"><?= number_format($total_1, 2, ',', ' ') ?></span>$</span></h6>
            <div id="content_frais" class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat de payement</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Date de paiement</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Date de validation</th>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">Preuve de paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{! $i = 1; }}
                                @foreach ($frais as $data)
                                <tr>
                                    <td style="padding-top: 5px;padding-bottom: 5px;"> <?= Type_frais::where('id', $data->type_frai_id)->first()["nom"]; ?></td>
                                    <th style="padding-top: 5px;padding-bottom: 5px;">
                                        @if ($data->devise == 0)
                                            {{ $data->montant }}$
                                        @else
                                            <?= number_format(($data->montant / $data->taux), 2, ',', ' ') ?>$
                                        @endif
                                    </th>

                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                        @if ((Auth::user()->id == $data->user_id))
                                            Vous
                                        @else
                                            <?= User::where('id', $data->user_id)->first()["name"]; ?>
                                        @endif
                                    </td>
                                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                        @if ($data->paye == 0)
                                            @if ((Auth::user()->role == 0))
                                                <a id="non_valider_<?= $i ?>" href=""><i class="zmdi zmdi-close-circle text-danger"></i></a>
                                                <script>
                                                    $("#non_valider_<?= $i ?>").click(function(e) {
                                                        e.preventDefault();
                                                        $("#data_frais_id").html("<?= $data->id ?>");
                                                        $("#element_1").html("<?= Type_frais::where('id', $data->type_frai_id)->first()["nom"]; ?>");
                                                        $("#btn_frais").trigger("click");
                                                    });
                                                </script>
                                            @else
                                                <i class="zmdi zmdi-block text-danger"></i> <span class="text-danger">En attente </span>
                                            @endif
                                        @else
                                            <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Valider </span>
                                        @endif
                                    </td>
                                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_paye }}</td>
                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                        @if ($data->paye == 0)
                                        -
                                        @else
                                        {{ $data->date_paye_valider }}
                                        @endif
                                    </td>
                                    <td style="padding-top: 5px;padding-bottom: 5px;">
                                        <a href="#" id="preuve_<?= $i ?>"><i class="zmdi zmdi-eye"></i></a>
                                        <script>
                                            $("#preuve_<?= $i ?>").click(function(e) {
                                                e.preventDefault();
                                                var frais = "<?= $data->frais_link ?>";
                                                var f = frais.split("____________________");
                                                var link = "";
                                                for (let index = 0; index < f.length; index++) {
                                                    if (f[index].trim().length != 0) {
                                                        link = f[index];
                                                        break;
                                                    }
                                                }
                                                $("#btn_preuve").trigger("click");
                                                $("#f_preuve").attr("src", link);
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
            </div>
        </div>
    </div>
    @if ((Auth::user()->role == 0))
    <hr>

        <div class="row">
            <div class="col-12">
                <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">TRAVAILLEURS</h4>
            </div>
        </div>
        @if ($contentieux->cloturer == 0)
        <div class="row">
            <div class="col-12">
                <form id="add_frais_" action="#" method="post">
                    @csrf
                    <div>
                        <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-plus-circle text-info"></i> Travailleur</h4>
                        <div style="margin-top: 30px;" id="content_groupe" class="row">
                            <div style="margin-top: -30px;" class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Travailleur </span></label>
                                    <select style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" id="user" name="user" class="select2" data-placeholder="Selectionnez un travailleur">
                                        <option selected value="">Selectionnez un travail</option>
                                        @foreach ($utilisateurs as $data)
                                        <option value="{{ $data->id }}"><?= 'Nom : ' .  $data->name . ', Role : ' . Groupes::where('id', $data->role)->first()["nom"] . ', Numero : ' . $data->phone ?>.</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="margin-top: -30px;" class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Paiement </span></label>
                                    <input type="text" id="montant_" name="montant_" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Paiement">
                                </div>
                            </div>
                            <div style="margin-top: -30px;" class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Devise </span></label>
                                    <select id="_devise_" name="_devise_" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                                        <option class="form-control" value="">Selectionnez une devise</option>
                                        <option class="form-control" value="0"> $</option>
                                        <option class="form-control" value="1"> Fc</option>
                                    </select>
                                </div>
                            </div>
                            <div style="margin-top: -30px;" class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Taux </span></label>
                                    <input type="text" id="_taux_" name="_taux_" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Taux">
                                </div>
                            </div>
                            <div style="margin-top: -30px;" class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> libelle </span></label>
                                    <textarea id="lib_" name="lib_" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libelle" cols="2" rows="2"></textarea>
                                </div>
                            </div>
                            <div style="margin-top: -30px;display:none;" class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> C id </span></label>
                                    <input type="text" id="c_id_" name="c_id_" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libelle" value="<?= $contentieux->id ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <label class="text-info" style="font-weight: bold;display: none;"><i class="zmdi zmdi-info"></i> Déposez les preuves de paiment dans ce cadre </span></label>
                <form method="post" style="display: none;background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;padding: 20px;" action="{{ route('upload') }}" class="dropzone_frais" id="dropzonewidget_2">
                    @csrf
                </form>
                <form action="">
                    <div style="margin-top: 20px;" class="row">
                        <div class="col-lg-12 col-sm-12">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
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
                                <button id="save_frais_" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } else { ?>
                                <button id="save_r_" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button>
                            <?php } ?>
                        </div>
                        <div class="col-lg-12" style="text-align: center;">
                            <span style="font-weight: bold;" id="frais_msg_">
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        @endif
        <div class="row">
            <div id="bloc_frais_" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Liste de travailleur</h4>
                <?php
                $total_3 = 0;
                $total_4 = 0;
                $total_5 = 0;
                foreach ($travailleurs as $f) {
                    if ($f->paye == 1) {
                        if ($f->devise == 0) {
                            $total_3 =  $total_3 + $f->paie;
                        } else {
                            $total_3 =  ($total_3 + ($f->paie / $f->taux));
                        }
                    }
                }
                foreach ($travailleurs as $f) {
                    if ($f->paye == 0) {
                        if ($f->devise == 0) {
                            $total_4 =  $total_4 + $f->paie;
                        } else {
                            $total_4 =  round($total_4 + ($f->paie / $f->taux));
                        }
                    }
                }
                ?>
                <?php $total_5 = $total_3 + $total_4 ?>
                <h6 style="text-align: right;font-weight: bold;"><span> <i class="zmdi zmdi-check-circle text-success"></i> Paiement total : <span id="nb_total_1"><?= number_format($total_5, 2, ',', ' ') ?></span>$</span></h6>
                <div id="content_frais" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Role</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                        <th style="padding-top: 5px;padding-bottom: 5px;">Etat de payement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{! $i = 1; }}
                                    @foreach ($travailleurs as $data)
                                    <tr>
                                        <td style="padding-top: 5px;padding-bottom: 5px;"> <?= User::where('id', $data->user_id)->first()["name"]; ?></td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;"> <?php $role_id = User::where('id', $data->user_id)->first()["role"];
                                                                                            echo Groupes::where('id', $role_id)->first()["nom"]; ?></td>
                                        <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;">
                                            @if ($data->devise == 0)
                                                {{ number_format($data->paie, 2, ',', ' ') }}$ / {{ number_format($data->montant, 2, ',', ' ') }}$
                                            @else
                                            <?php
                                            $payers = Payer::where(["user_id" => $data->user_id, "contentieur_id" => $data->contentieux_id])->get();
                                            $t_p = 0;
                                            foreach ($payers as $p)
                                            {
                                                $t_p = $t_p + $p->montant;
                                            }
                                            ?>
                                            <?= number_format($t_p, 2, ',', ' ') ?> / <?= number_format($data->montant / $data->taux, 2, ',', ' ') ?>$
                                            @endif
                                        </th>
                                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                            @if ($data->montant != $data->paie)
                                            <a id="payer_valider_travailleur<?= $i ?>" href=""><i class="zmdi zmdi-close-circle text-danger"></i></a>
                                            <script>
                                                $("#payer_valider_travailleur<?= $i ?>").click(function(e) {
                                                    e.preventDefault();
                                                    $("#data_frais_id").html("<?= $data->id ?>");
                                                    $.get("{{ url('/get_detail_p') }}", {
                                                        id: "<?= $data->id ?>",
                                                        contentieux_id: "<?= $data->contentieur_id ?>",
                                                        projet_id: "<?= Contentieurs::where('id', $data->contentieur_id)->first()["decision_id"]; ?>",
                                                        user_id: "<?= $data->user_id ?>",
                                                    }, function(get_detail_p) {
                                                        $("#nom_p").html(get_detail_p.split("______________________________")[0]);
                                                        $("#role_p").html(get_detail_p.split("______________________________")[1]);
                                                        if (get_detail_p.split("______________________________")[2] == 0) {
                                                            $("#devise_p").html("$");
                                                        } else {
                                                            $("#devise_p").html("Fc");
                                                        }
                                                        $("#reste_p").html(get_detail_p.split("______________________________")[3]);
                                                        $("#total_p").html(get_detail_p.split("______________________________")[4]);
                                                        $("#data_frais_id").html("<?= $data->id ?>");
                                                        $("#btn_sup_").trigger("click");
                                                    });
                                                });
                                            </script>
                                            @else
                                            <i class="zmdi zmdi-check-circle text-success"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    {{! $i++; }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script src="{{ asset('assets/vendors/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<br><br><br>
<script>
    $("#type_objet").change(function(e) {
        var type_objet = $("#type_objet").val();
        if (type_objet == 0) {
            $("#bloc_numero_declaration").hide();
            $("#bloc_periode").show();
        }
        if (type_objet == 1) {
            $("#bloc_numero_declaration").show();
            $("#bloc_periode").hide();
        }
    });
    $("#save_frais").click(function(e) {
        e.preventDefault();
        var frais = $("#frais").val();
        var montant = $("#montant").val();
        var devise = $("#_devise").val();
        var taux = $("#_taux").val();
        var libelle = $("#lib").val();
        var data = $("#add_frais").serialize();
        if (frais.trim().length == 0) {
            $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le type de frais');
            $('#frais_msg').css("color", "#ff6b68");
            setTimeout(() => {
                $('#frais_msg').html("");
            }, 9000);
        } else {
            if (montant.trim().length == 0) {
                $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant');
                $('#frais_msg').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#frais_msg').html("");
                }, 9000);
            } else {
                if (!Number(montant) || Number(montant) <= 0) {
                    $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du montant');
                    $('#frais_msg').css("color", "#ff6b68");
                    setTimeout(() => {
                        $('#frais_msg').html("");
                    }, 9000);
                } else {
                    if (devise.trim().length == 0) {
                        $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                        $('#frais_msg').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#frais_msg').html("");
                        }, 9000);
                    } else {
                        if (taux.trim().length == 0) {
                            $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                            $('#frais_msg').css("color", "#ff6b68");
                            setTimeout(() => {
                                $('#frais_msg').html("");
                            }, 9000);
                        } else {
                            if (!Number(taux) || Number(taux) <= 0) {
                                $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du taux');
                                $('#frais_msg').css("color", "#ff6b68");
                                setTimeout(() => {
                                    $('#frais_msg').html("");
                                }, 9000);
                            } else {
                                if (libelle.trim().length == 0) {
                                    $('#frais_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle');
                                    $('#frais_msg').css("color", "#ff6b68");
                                    setTimeout(() => {
                                        $('#frais_msg').html("");
                                    }, 9000);
                                } else {
                                    $("#save_frais").attr("disabled", true);
                                    $.ajax({
                                        type: "POST",
                                        url: "/add_frais_contentieux",
                                        data: data,
                                        success: function(response) {
                                            $("#save_frais").attr("disabled", false);
                                            Dropzone.forElement('#dropzonewidget_2').removeAllFiles(true)
                                            $("#frais").val("");
                                            $("#montant").val("");
                                            $("#devise").val("");
                                            $("#taux").val("");
                                            $("#lib").val("");
                                            $('#frais_msg').html('<i class="zmdi zmdi-check-circle"></i> dépense ajouté succès');
                                            $('#frais_msg').css("color", '#32c787');
                                            $("#bloc_frais").html(response);
                                            setTimeout(() => {
                                                $('#frais_msg').html("");
                                            }, 9000);
                                        }
                                    });
                                }
                            }
                        }
                    }
                }
            }
        }
    });

    $("#save_frais_").click(function(e) {
        e.preventDefault();
        var frais = $("#user").val();
        var montant = $("#montant_").val();
        var devise = $("#_devise_").val();
        var taux = $("#_taux_").val();
        var libelle = $("#lib_").val();
        var data = $("#add_frais_").serialize();
        if (frais.trim().length == 0) {
            $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez d\'abord un travailleur');
            $('#frais_msg_').css("color", "#ff6b68");
            setTimeout(() => {
                $('#frais_msg_').html("");
            }, 9000);
        } else {
            $.ajax({
                type: "POST",
                url: "/check_user",
                data: data,
                success: function(rep) {
                    if (rep != 0) {
                        $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Ce travailleur existe déjà');
                        $('#frais_msg_').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#frais_msg_').html("");
                        }, 9000);
                    } else {
                        if (montant.trim().length == 0) {
                            $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant');
                            $('#frais_msg_').css("color", "#ff6b68");
                            setTimeout(() => {
                                $('#frais_msg_').html("");
                            }, 9000);
                        } else {
                            if (!Number(montant) || Number(montant) <= 0) {
                                $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du montant');
                                $('#frais_msg_').css("color", "#ff6b68");
                                setTimeout(() => {
                                    $('#frais_msg_').html("");
                                }, 9000);
                            } else {
                                if (devise.trim().length == 0) {
                                    $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Completez la devise');
                                    $('#frais_msg_').css("color", "#ff6b68");
                                    setTimeout(() => {
                                        $('#frais_msg_').html("");
                                    }, 9000);
                                } else {
                                    if (taux.trim().length == 0) {
                                        $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux');
                                        $('#frais_msg_').css("color", "#ff6b68");
                                        setTimeout(() => {
                                            $('#frais_msg_').html("");
                                        }, 9000);
                                    } else {
                                        if (!Number(taux) || Number(taux) <= 0) {
                                            $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du taux');
                                            $('#frais_msg_').css("color", "#ff6b68");
                                            setTimeout(() => {
                                                $('#frais_msg_').html("");
                                            }, 9000);
                                        } else {
                                            if (libelle.trim().length == 0) {
                                                $('#frais_msg_').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle');
                                                $('#frais_msg_').css("color", "#ff6b68");
                                                setTimeout(() => {
                                                    $('#frais_msg_').html("");
                                                }, 9000);
                                            } else {
                                                $("#save_frais_").attr("disabled", true);
                                                $.ajax({
                                                    type: "POST",
                                                    url: "/add_frais_contentieux_",
                                                    data: data,
                                                    success: function(response) {
                                                        $("#save_frais_").attr("disabled", false);
                                                        $("#user").val("");
                                                        $("#montant_").val("");
                                                        $("#devise_").val("");
                                                        $("#taux_").val("");
                                                        $("#lib_").val("");
                                                        $('#frais_msg_').html('<i class="zmdi zmdi-check-circle"></i> utilisateur ajouté succès');
                                                        $('#frais_msg_').css("color", '#32c787');
                                                        $("#bloc_frais_").html(response);
                                                        setTimeout(() => {
                                                            $('#frais_msg_').html("");
                                                        }, 9000);
                                                    }
                                                });
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            });


        }
    });

    $("#oui_frais").click(function(e) {
        e.preventDefault();
        var id = $("#data_frais_id").html();
        $.get("{{ url('/refresh_approuver_frais') }}", {
            id: id,
            dossier_contentieux_id: "<?= $contentieux->id ?>"
        }, function(refresh_editutilisateur) {
            $("#bloc_frais").html(refresh_editutilisateur);
            $("#non_frais").trigger("click");
        });
    });

    $(".dropzone_frais").dropzone({
        addRemoveLinks: true,
        removedfile: function(file) {
            var name = file.name;
            $.ajax({
                type: 'POST',
                url: '/upload',
                data: {
                    name: name,
                    request: 2
                },
                sucess: function(data) {
                    console.log('success: ' + data);
                }
            });
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });
    $("#save_paie").click(function(e) {
        e.preventDefault();
        var id = $("#data_frais_id").html();
        var montant_p = $("#montant_p").val();
        var reste_p = $("#reste_p").html();
        var total_p = $("#total_p").html();
        if ((((Number(reste_p))) == Number((total_p)))) {
            $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> ce paiement est déjà cloturé');
            $('#m_paie').css("color", "#ff6b68");
            setTimeout(() => {
                $('#m_paie').html("");
            }, 9000);
        } else {
            if (montant_p.trim().length == 0) {
                $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant à payer');
                $('#m_paie').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#m_paie').html("");
                }, 9000);
            } else {
                if (montant_p <= 0) {
                    $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez une bonne valeur du montant à payer');
                    $('#m_paie').css("color", "#ff6b68");
                    setTimeout(() => {
                        $('#m_paie').html("");
                    }, 9000);
                } else {
                    if ((((Number(montant_p)) + (Number(reste_p))) > Number((total_p)))) {
                        $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> le montant à payer dois être inferieur ou egal à ' + (total_p - reste_p));
                        $('#m_paie').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#m_paie').html("");
                        }, 9000);
                    } else {
                        var id = $("#data_frais_id").html();
                        $("#save_paie").attr("disabled", true);
                        $.get("{{ url('/save_p') }}", {
                            id: id,
                            montant_p: (Number(montant_p)),
                        }, function(savep)
                        {
                            $("#save_paie").attr("disabled", false);
                            $("#montant_p").val("");
                            $("#reste_p").html((Number(montant_p)) + (Number(reste_p)));
                            $('#m_paie').html('<i class="zmdi zmdi-check-circle"></i> paiement effectué avec succès');
                            $('#m_paie').css("color", '#32c787');
                            $("#bloc_frais_").html(savep);
                            setTimeout(() => {
                                $('#m_paie').html("");
                            }, 9000);
                        });
                    }
                }
            }
        }
    });
</script>
