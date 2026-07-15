<?php
use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Entres;
use Illuminate\Support\Facades\Auth;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N° Opération</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Solde</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date d'opération</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($factures as $data)
                    @if (Entres::where('facture_id', $data->id)->get()->count() != 0)
                        <tr id="row_{{ $data->id }}">
                            <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell"
                                data-numero="{{ $data->numero }}">{{ $data->numero }}
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell"
                                data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                                @if (Auth::user()->id == $data->user_id)
                                    Vous
                                @else
                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;" class="solde-cell"
                                data-solde="<?php
                                $t = 0;
                                $ent = Entres::where('facture_id', $data->id)->get();
                                foreach ($ent as $e) {
                                    if ($e->type == 0) {
                                        $t = $t + $e->total;
                                    } else {
                                        $t = $t - $e->total;
                                    }
                                }
                                if ($t < 0) {
                                    $t = $t * -1;
                                }
                                echo $t;
                                ?>">
                                <?php
                                $t = 0;
                                $ent = Entres::where('facture_id', $data->id)->get();
                                foreach ($ent as $e) {
                                    if ($e->type == 0) {
                                        $t = $t + $e->total;
                                    } else {
                                        $t = $t - $e->total;
                                    }
                                }
                                if ($t < 0) {
                                    $t = $t * -1;
                                }
                                echo number_format($t, 2, ',', ' ') . '$';
                                ?>
                            </td>
                            <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell"
                                data-date="{{ $data->date_creation }}">
                                {{ $data->date_creation }}
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $edit = 0;
                                $delete = 0;
                                $display = 0;
                                if (
                                    Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                        ->get()
                                        ->count() != 0
                                ) {
                                    $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                    $display = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->display;
                                }
                                ?>
                                <?php } ?>
                                <?php if ((($display == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display == 0) && (Auth::user()->role == 0))) { ?>
                                <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a>
                                &nbsp;
                                <?php } else { ?>
                                <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a>
                                &nbsp;
                                <?php } ?>
                                <script>
                                    $("#detail_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $.get("{{ url('/refresh_detailfactures') }}", {
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
                                </script>
                            </td>
                        </tr>
                    @endif
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
