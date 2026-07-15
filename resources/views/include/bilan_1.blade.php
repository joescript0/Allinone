<?php

    use App\Models\Mois;
    use App\Models\Annees;
    use App\Models\Soldes;
    use App\Models\Entres;
    use App\Models\User;
    use App\Models\Type_frais;

    $mois = Mois::get();
    $dd = Entres::where(["annee_id" => $annees->id, "type" => 1])->get();
    $total_general = 0;
    foreach ($dd as $d)
    {
        if($d->type == 0)
        {
            $total_general = $total_general + $d->total;
        }else{
            $total_general = $total_general - $d->total;
        }
    }
    if($total_general <= 0)
    {
        $total_general = $t * (-1);
    }
?>
<div style="margin-bottom: 100px;">
    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;font-weight: bold;" class="zmdi zmdi-book text-info"></i> Bilan sociale </h4>
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">BILAN SOCIALE : {{ $annees->annees }}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: right;color: black;font-weight: bold;">SORTIES TOTAL {{ $annees->annees }} : <?= number_format($total_general, 2, ',', ' ') ?>$</h4>
        </div>
    </div>
    <hr style="margin-top: -2px;border: 1px solid black">
    <?php foreach ($mois as  $m) {?>
        <?php if(Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 1])->get()->count() != 0){ ?>
            <?php
                $t = 0;
                $ddd = Entres::where(["annee_id" => $annees->id, "moi_id" => $m->id, "type" => 1])->get();
                foreach ($ddd as $dd)
                {
                    $t = $t + $dd->total;
                }
            ?>
            <div class="row">
                <div class="col-12">
                    <h6 style="text-align: left;color: white;background-color: rgb(0, 0, 0);padding: 5px;font-weight: bold;">JOURNAL DE CAISSE {{ strtoupper($m->nom) }}</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h6 style="text-align: right;color: black;font-weight: bold;"><span>SOLDE INITIAL : {{ number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_initial"], 2, ',', ' ') }}$</span>, <span class="text-danger">SORTIES FINAL {{ strtoupper($m->nom) }} : <?= number_format($t, 2, ',', ' ') ?>$</span>.</h4>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">DATE</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">N°PIECES</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Libellé</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Entrée</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Sortie</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Solde</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Utilisateur</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Preuve de d'entre</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{! $i = 1; }}
                        @foreach ($ddd as $data)
                        <tr>
                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->date_creation }}
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->n_piece }}
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->libelle }}
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                <?php if($data->entree == 0){ ?>
                                    <div style="text-align: center">{{ "-" }}</div>
                                <?php }else{ ?>
                                    <?php if($data->devise == 0){ ?>
                                        {{ number_format($data->entree, 2, ',', ' ') }}$
                                    <?php }else{ ?>
                                        {{ number_format($data->entree, 2, ',', ' ') }}Fc
                                    <?php }?>
                                <?php }?>
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;">
                                <?php if($data->sortie == 0){ ?>
                                    <div style="text-align: center">{{ "-" }}</div>
                                <?php }else{ ?>
                                    <?php if($data->devise == 0){ ?>
                                        {{ number_format($data->sortie, 2, ',', ' ') }}$
                                    <?php }else{ ?>
                                        {{ number_format($data->sortie, 2, ',', ' ') }}Fc
                                    <?php }?>
                                <?php }?>
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;">{{ number_format($data->total, 2, ',', ' ') }}$</td>
                            <td style="padding-top: 5px;padding-bottom: 5px;display: none;">
                                @if ((Auth::user()->id == $data->user_id))
                                    Vous
                                @else
                                    <?= User::where('id', $data->user_id)->first()["name"]; ?>
                                @endif
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;display: none;">
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
            <div class="row" style="margin-top: 10px;">
                <div class="col-12">
                    <h6 style="text-align: right;color: black;font-weight: bold;"><span>SOLDE FINAL : {{ number_format(Soldes::where(["annee_id" => $annees->id, "moi_id" => $m->id])->first()["solde_actuel"], 2, ',', ' ') }}$</span></h4>
                </div>
            </div>
            <hr style="margin-top: -2px;border: 1px solid black">
        <?php } ?>
    <?php } ?>
</div>
