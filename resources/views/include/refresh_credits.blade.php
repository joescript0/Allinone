<?php
    use App\Models\Contrevenants;
    use App\Models\Remboursements;
    use App\Models\Groupes;
    use App\Models\Verbalisateurs;
    use App\Models\Writes;
    use App\Models\User;
    use App\Models\Factures;
    use App\Models\Entres;
    use Illuminate\Support\Facades\Auth;
?>
<?php
$credit_total = 0;
$r_total = 0;
foreach ($credits as $c_t)
{
    $rts = Remboursements::where(['credit_id' => $c_t->id])->get();
    $t_entree = 0;
    foreach ($rts as $c_tt)
    {
        $t_entree = $t_entree + $c_tt->entree;
    }
    if($c_t->entree != $t_entree)
    {
        $credit_total = $credit_total + $c_t->entree;
        foreach ($rts as $c_tt)
        {
            $r_total = $r_total + $c_tt->entree;
        }
    }
}

?>

<div class="col-12">
    <?php
        $credit_total = 0;
        $r_total = 0;
        foreach ($credits as $c_t)
        {
            $rts = Remboursements::where(['credit_id' => $c_t->id])->get();
            $t_entree = 0;
            foreach ($rts as $c_tt)
            {
                $t_entree = $t_entree + $c_tt->entree;
            }
            if($c_t->entree != $t_entree)
            {
                $credit_total = $credit_total + $c_t->entree;
                foreach ($rts as $c_tt)
                {
                    $r_total = $r_total + $c_tt->entree;
                }
            }
        }

    ?>
    <h6 style="color:rgba(0, 0, 0, 0.6);text-align: right;font-weight: bold;"><span class="text-info">Crédit total : {{ number_format($credit_total, 2, ',', ' ') .'$'; }}</span>,  <span class="text-success">Remboursement total : {{ number_format($r_total, 2, ',', ' ') .'$'; }}</span>,  <span class="text-danger">Reste total : {{ number_format($credit_total - $r_total, 2, ',', ' ') .'$'; }}</span></h6>
</div>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date crédit</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Type</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($credits as $data)
                <tr>
                    <?php
                        $t = 0;
                        $cd = Remboursements::where(['credit_id' => $data->id])->get();
                        foreach ($cd as $e)
                        {
                            $t = $t + $e->entree;
                        }
                    ?>
                    <?php if(true){ ?>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_credit }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom_credit }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->type }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ number_format($t, 2, ',', ' ') .'$'; }} / {{ number_format($data->entree, 2, ',', ' ') .'$'; }}
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
                                <a class="text-success" id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-success"></i> Rembourser</a> &nbsp;
                            <?php } else { ?>
                                <a class="text-success" id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-success"></i> Rembourser</a> &nbsp;
                            <?php } ?>
                            <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                <a class="text-danger" id="detaill_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-danger"></i> Créditer</a> &nbsp;
                            <?php } else { ?>
                                <a class="text-danger" id="detail_rr<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-danger"></i> Créditer</a> &nbsp;
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
                                    $.get("{{ url('/refresh_detailcredits') }}", {
                                        invitation_id: <?= $data->id ?>,
                                    }, function(refresh_editinvitations) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_editinvitations);
                                    });
                                });
                                $("#detaill_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_detailcredits_3') }}", {
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
                                $("#detail_rr<?= $i ?>").click(function(e) {
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
                    <?php } ?>
                </tr>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
