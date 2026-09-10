<?php
use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factureas;
use App\Models\Entres;
use App\Models\Approvisionnements;
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
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date d'entré</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($factures as $data)
                    @php
                        $t = 0;
                        $ent = Approvisionnements::where('facture_id', $data->id)->get();
                        $montant_usd = 0;
                        $montant_cdf = 0;

                        foreach ($ent as $e)
                        {
                            $t = $t + $e->total;

                            // Taux de la facture (si disponible, sinon 1 pour éviter division par 0)
                            $tauxFacture = $e->taux ?? 1;
                            if ($tauxFacture == 0) {
                                $tauxFacture = 1;
                            }

                            if ($data->devise == 0)
                            {
                                // Facture en USD : le total est en USD, on convertit en CDF
                                $montant_usd = $t;
                                $montant_cdf = $t * $tauxFacture;
                            }
                            else
                            {
                                // Facture en CDF : le total est en CDF, on convertit en USD
                                $montant_cdf = $t;
                                $montant_usd = $t / $tauxFacture;
                            }
                        }

                        // Construction de l'affichage en deux devises (comme sur la page Factures / Achats)
                        if ($data->devise == 0) {
                            $montant_affichage = number_format($montant_usd, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                        } else {
                            $montant_affichage = number_format($montant_cdf, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                        }
                    @endphp
                    <tr id="row_{{ $data->id }}"
                        data-montant-usd="{{ $montant_usd }}"
                        data-montant-cdf="{{ $montant_cdf }}">
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                            {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="<?php echo $t; ?>">
                            {{ $montant_affichage }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ $data->created_at }}">
                            <?php
                                $date = $data->created_at;
                                $date_1 = explode(' ', $date);
                                echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                            ?>
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
                            <a id="detail_<?= $i ?>" href="#" title="Voir détails"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                            <?php } else { ?>
                            <a id="detail_r<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a> &nbsp;
                            <?php } ?>

                            {{-- ===== NOUVEAU : ICÔNE DE SUPPRESSION ===== --}}
                            <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (Auth::user()->role == 0)) { ?>
                                <a href="#" class="delete-app-btn"
                                    data-id="{{ $data->id }}"
                                    data-numero="{{ $data->numero }}"
                                    data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}"
                                    data-montant="{{ $montant_affichage }}"
                                    data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                                    title="Supprimer cet approvisionnement">
                                    <i class="zmdi zmdi-delete text-danger"></i>
                                </a>
                            <?php } ?>

                            <script>
                                $("#detail_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_detailfactureas') }}", {
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
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
