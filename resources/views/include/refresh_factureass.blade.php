<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Approvisionnements;
use App\Models\Achats;
use App\Models\Societes;
use App\Models\Clients;
use App\Models\Mesures;
use App\Models\Entres;
use Illuminate\Support\Facades\Auth;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N° Facture</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Libelle / Client</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Mode de paiement</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($factures as $data)
                    @php
                        $taux = $data->taux;
                        $total = 0;
                        $ent = Achats::where('facture_id', $data->id)->get();
                        foreach ($ent as $e) {
                            $total += $e->total;
                        }
                        if ($data->devise == 0) {
                            $montant_usd = $total;
                            $montant_cdf = $total * $taux;
                            $montant_affichage =
                                number_format($total, 2, ',', ' ') .
                                ' USD (' .
                                number_format($montant_cdf, 2, ',', ' ') .
                                ' CDF)';
                        } else {
                            $montant_cdf = $total;
                            $montant_usd = $total / $taux;
                            $montant_affichage =
                                number_format($total, 2, ',', ' ') .
                                ' CDF (' .
                                number_format($montant_usd, 2, ',', ' ') .
                                ' USD)';
                        }
                    @endphp
                    <tr id="row_{{ $data->id }}" data-montant-usd="{{ $montant_usd }}"
                        data-montant-cdf="{{ $montant_cdf }}">
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell"
                            data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell"
                            data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                            {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell"
                            data-client="{{ $data->client_id == 0 ? $data->libelle : Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' }}">
                            @if ($data->client_id == 0)
                                {{ $data->libelle }}
                            @else
                                <?= Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' ?>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell"
                            data-montant="{{ $total }}">
                            {{ $montant_affichage }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell"
                            data-date="{{ $data->created_at }}">
                            <?php
                            $date = $data->created_at;
                            $date_1 = explode(' ', $date);
                            echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                            ?>
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell"
                            data-statut="{{ $data->payer == 0 ? 'unpaid' : 'paid' }}">
                            @if ($data->payer == 0)
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                    class="text-danger">{{ 'Aucun' }} </span>
                            @else
                                @if ($data->mode_de_paiement == 1)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span
                                        class="text-success">CASH</span>
                                @endif
                                @if ($data->mode_de_paiement == 2)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span
                                        class="text-success">Mobile money</span>
                                @endif
                                @if ($data->mode_de_paiement == 3)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span
                                        class="text-success">Bank</span>
                                @endif
                            @endif
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
                            @if ($data->payer == 0)
                                <a id="detail_{{ $i }}" href="#"><i
                                        class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                            @else
                                <a id="detail_{{ $i }}" href="#"><i
                                        class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                            @endif
                            <?php } else { ?>
                            @if ($data->payer == 0)
                                <a id="detail_r{{ $i }}" href="#"><i
                                        class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                            @else
                                <a id="detail_r{{ $i }}" href="#"><i
                                        class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                            @endif
                            <?php } ?>
                            <script>
                                $("#detail_{{ $i }}").click(function(e) {
                                    e.preventDefault();
                                    var payer = "<?= $data->payer ?>";
                                    if (payer == 0) {
                                        $.get("{{ url('/refresh_detailfactureass') }}", {
                                            invitation_id: <?= $data->id ?>,
                                        }, function(refresh_editinvitations) {
                                            $("#bloc_1").hide();
                                            $("#bloc_2").show();
                                            $("#bloc_3").show();
                                            $("#bloc_t").show();
                                            $("#bloc_3").html(refresh_editinvitations);
                                        });
                                    }
                                    if (payer == 1) {
                                        $("#n_fac").html("<?= $data->numero ?>");
                                        $("#id_fac").val("<?= $data->id ?>");
                                        var pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";
                                        if (pdfUrl && pdfUrl !== '') {
                                            currentPdfUrl = pdfUrl;
                                            $("#pdfIframe").attr("src", pdfUrl);
                                            $("#pdfModal").modal("show");
                                        } else {
                                            $.get("{{ url('/print_facture') }}", {
                                                "facture_id": "<?= $data->id ?>"
                                            }, function(response) {
                                                if (response && response[0][0]) {
                                                    currentPdfUrl = response[0][0];
                                                    $("#pdfIframe").attr("src", response[0][0]);
                                                    $("#cdf_montant_payer").val(response[0][1]);
                                                    $("#usd_montant_payer").val(response[0][2]);
                                                    $("#payer").val(response[0][5]);
                                                    $("#pdfModal").modal("show");
                                                } else if (response && typeof response[0][0] === 'string') {
                                                    currentPdfUrl = response[0][0];
                                                    $("#pdfIframe").attr("src", response[0][0]);
                                                    $("#cdf_montant_payer").val(response[0][1]);
                                                    $("#usd_montant_payer").val(response[0][2]);
                                                    $("#payer").val(response[0][5]);
                                                    $("#pdfModal").modal("show");
                                                } else {
                                                    alert("Aucun PDF disponible pour cette facture.");
                                                }
                                            }).fail(function() {
                                                alert("Erreur lors de la récupération du PDF.");
                                            });
                                        }
                                    }
                                });
                                $("#detail_r{{ $i }}").click(function(e) {
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
