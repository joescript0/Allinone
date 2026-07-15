<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i> Liste de depense</h4>
<?php

use App\Models\Type_frais;

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
                                <?= number_format($data->montant / $data->taux, 2, ',', ' ')   ?>$
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
