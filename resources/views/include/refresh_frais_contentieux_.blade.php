<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Liste de travailleur</h4>
<?php

use App\Models\Contentieurs;
use App\Models\Groupes;
use App\Models\Payer;
use App\Models\User;

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
<?php $total_5 = $total_3 + $total_4; ?>
<h6 style="text-align: right;font-weight: bold;"><span> <i class="zmdi zmdi-check-circle text-success"></i> Paiement total : <span id="nb_total_1"><?= number_format($total_5, 2, ',', ' '); ?></span>$</span></h6>
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
                            foreach ($payers as $p) {
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
