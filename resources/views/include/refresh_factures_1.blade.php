<?php
    use App\Models\Contrevenants;
    use App\Models\Groupes;
    use App\Models\Verbalisateurs;
    use App\Models\Writes;
    use App\Models\User;
    use App\Models\Factures;
    use App\Models\Entres;
    use App\Models\Sorties;
    use Illuminate\Support\Facades\Auth;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N° Facture</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date de sortie</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($factures as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->numero }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ User::where('id', $data->user_id)->first()["name"]; }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        <?php
                            $t = 0;
                            $ent = Sorties::where('facture_id', $data->id)->get();
                            foreach ($ent as $e)
                            {
                                $t = $t + $e->total;
                            }
                            echo number_format($t, 2, ',', ' ') .'$';
                        ?>
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $data->date_creation }}
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
                            <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                        <?php } else { ?>
                            <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
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
                                $.get("{{ url('/refresh_detailfacturess') }}", {
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
                </tr>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
