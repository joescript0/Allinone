<?php

use App\Models\Factureas;
use App\Models\Articles;
use App\Models\Mesures;
use App\Models\Type_frais;
use App\Models\User;


$t = 0;
foreach ($approvisionnements as $ee)
{
    $t = $t + $ee->total;
}
?>
<div class="col-12">
    <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">DETAIL FACTURE APPROVISIONNEMENT : {{ strtoupper($numero) }}</h4>
</div>
<div class="col-12">
    @if ($devise == 0)
        <h6 style="text-align: right;font-weight: bold;"><span><span> <i class="zmdi zmdi-check-circle text-success"></i> Total : <span id="total_1"><?= number_format($t, 2, ',', ' ') ?></span>USD</span></h6>
    @endif
    @if ($devise == 1)
        <h6 style="text-align: right;font-weight: bold;"><span><span> <i class="zmdi zmdi-check-circle text-success"></i> Total : <span id="total_1"><?= number_format($t, 2, ',', ' ') ?></span>CDF</span></h6>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Article</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Prix d'achat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Quantité</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Total</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Libelle</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Preuve d'entré</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($approvisionnements as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?= Articles::where('id', $data->article_id)->first()["nom_article"]; ?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php if($data->devise == 0){ ?>
                            {{ number_format($data->prix_unitaire, 2, ',', ' ') }}USD
                        <?php }else{ ?>
                            {{ number_format($data->prix_unitaire, 2, ',', ' ') }}CDF
                        <?php }?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $data->quantite }}({{ Mesures::where('id', Articles::where('id', $data->article_id)->first()["mesure_id"])->first()['nom'] }})
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php if($data->devise == 0){ ?>
                            {{ number_format($data->total, 2, ',', ' ') }}USD
                        <?php }else{ ?>
                            {{ number_format($data->total, 2, ',', ' ') }}CDF
                        <?php }?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->libelle }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if ((Auth::user()->id == $data->user_id))
                            Vous
                        @else
                            <?= User::where('id', $data->user_id)->first()["name"]; ?>
                        @endif
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
                        <a href="#" id="preuve_<?= $i ?>"><i class="zmdi zmdi-eye"></i></a>
                        <script>
                            $("#preuve_<?= $i ?>").click(function(e){
                                e.preventDefault();
                                $("#btn_preuve").trigger("click");
                                $("#f_preuve").attr("src", "{{ $data->preuve_de_sortie }}");
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
