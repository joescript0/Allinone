<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Factures;
use App\Models\Type_frais;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


$t = 0;
foreach ($entres as $ee)
{
    if($ee->type == 0)
    {
        $t = $t + $ee->total;
    }else
    {
        $t = $t - $ee->total;
    }
}

?>
<div class="col-12">
    <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">JOURNAL DE CAISSE : {{ strtoupper($numero) }}</h4>
</div>
<div class="col-12">
    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-info text-info"></i> Details </h4>
    <h6 style="text-align: right;font-weight: bold;"><span><span> <?php if($t <= 0){ ?> <i class="zmdi zmdi-check-circle text-danger"></i> Solde : <?php }else { ?> <i class="zmdi zmdi-check-circle text-success"></i> Solde : <?php } ?>
        <span id="total_1">
            <?php if($t <= 0){ ?>
                <span class="text-danger">{{ number_format(($t * -1), 2, ',', ' ') }}$</span>
            <?php } else{ ?>
                <span class="text-success">{{ number_format($t, 2, ',', ' ') }}$</span>
            <?php } ?>
        </span>
    </span>
    </h6>
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
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Preuve</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                <?php $sl = 0; ?>
                @foreach ($entres as $data)
                <?php
                    if($data->type == 0)
                    {
                        $sl = $sl + $data->total;
                    }else
                    {
                        $sl = $sl - $data->total;
                    }
                ?>
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
                    <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
                        <?php if($data->entree == 0){ ?>
                            {{ "-" }}
                        <?php }else{ ?>
                            <?php if($data->devise == 0){ ?>
                                {{ number_format($data->entree, 2, ',', ' ') }}$
                            <?php }else{ ?>
                                {{ number_format($data->entree, 2, ',', ' ') }}Fc
                            <?php }?>
                        <?php }?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
                        <?php if($data->sortie == 0){ ?>
                            {{ "-" }}
                        <?php }else{ ?>
                            <?php if($data->devise == 0){ ?>
                                {{ number_format($data->sortie, 2, ',', ' ') }}$
                            <?php }else{ ?>
                                {{ number_format($data->sortie, 2, ',', ' ') }}Fc
                            <?php }?>
                        <?php }?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if ($data->type  == 0)
                            @if ($sl <= 0)
                                <span class="text-success">{{ number_format($sl * (-1), 2, ',', ' ') }}$</span>
                            @else
                                <span class="text-success">{{ number_format($sl, 2, ',', ' ') }}$</span>
                            @endif
                        @else
                            @if ($sl <= 0)
                                <span class="text-danger">{{ number_format($sl * (-1), 2, ',', ' ') }}$</span>
                            @else
                                <span class="text-danger">{{ number_format($sl, 2, ',', ' ') }}$</span>
                            @endif
                        @endif
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        @if ((Auth::user()->id == $data->user_id))
                            Vous
                        @else
                            <?= User::where('id', $data->user_id)->first()["name"]; ?>
                        @endif
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <a href="#" id="preuve_<?= $i ?>"><i class="zmdi zmdi-eye"></i></a>
                            <script>
                                $("#preuve_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    var frais = "<?= $data->preuve_de_sortie ?>";
                                    var f = frais.split("____________________");
                                    var link = "";
                                    for (let index = 0; index < f.length; index++)
                                    {
                                        if (f[index].trim().length != 0)
                                        {
                                            link = f[index];
                                            break;
                                        }
                                    }
                                    $("#btn_preuve").trigger("click");
                                    $("#f_preuve").attr("src", link);
                                });
                            </script>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;text-align: center;">
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
                        <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($delete == 0) && (Auth::user()->role == 0))) { ?>
                            <a href="#" id="sup_op_<?= $i ?>"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                        <?php } else { ?>
                            <a href="#" id="sup_op_r<?= $i ?>"><i class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                        <?php } ?>
                        <script>
                            $("#sup_op_<?= $i ?>").click(function(e){
                                e.preventDefault();
                                $("#btn_sup_op").trigger("click");
                                $("#data_id").html("{{ $data->id }}");
                                $("#facture_id").html("{{ $facture_id }}");
                                var devise = "{{ $data->devise }}";
                                var type = "{{ $data->type }}";
                                if(type == 0)
                                {
                                    if(devise == 0)
                                    {
                                        $("#element_s_op").html("{{ $data->libelle }} du {{ $data->date_creation }} avec un montant de : {{ number_format($data->entree, 2, ',', ' ') }}$ ?");
                                    }else{
                                        $("#element_s_op").html("{{ $data->libelle }} du {{ $data->date_creation }} avec un montant de : {{ number_format($data->entree, 2, ',', ' ') }}$ ?");
                                    }
                                }else
                                {
                                    if(devise == 0)
                                    {
                                        $("#element_s_op").html("{{ $data->libelle }} du {{ $data->date_creation }} avec un montant de : {{ number_format($data->sortie, 2, ',', ' ') }}$ ?");
                                    }else{
                                        $("#element_s_op").html("{{ $data->libelle }} du {{ $data->date_creation }} avec un montant de : {{ number_format($data->sortie, 2, ',', ' ') }}$ ?");
                                    }
                                }
                            });
                            $("#sup_op_r<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#btn_refus").trigger("click");
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
