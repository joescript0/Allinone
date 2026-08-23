<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Tables;
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
use App\Models\detailpaiessachats; // ou DetailPaiessAchats selon le nom exact de votre modèle
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
                    <th style="padding-top: 5px;padding-bottom: 5px;">Table</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Payé</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
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
                        $achat_total_usd = 0;
                        $achat_total_cdf = 0;
                        $ent = Achats::where('facture_id', $data->id)->get();
                        foreach ($ent as $e) {
                            $total += $e->total;
                            // Calcul du coût d'achat
    $prix_achat = $e->prix_achat ?? 0;
    $devise_achat = $e->devise_achat ?? $data->devise; // si non défini, on prend la devise de la facture
    if ($devise_achat == 0) {
        // USD
        $achat_total_usd += $prix_achat;
        $achat_total_cdf += $prix_achat * $taux;
    } else {
        // CDF
        $achat_total_cdf += $prix_achat;
        $achat_total_usd += $prix_achat / $taux;
    }
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

// Bénéfices (calculés mais non affichés dans le tableau)
$benefice_usd = $montant_usd - $achat_total_usd;
$benefice_cdf = $montant_cdf - $achat_total_cdf;

// --- Calcul du total déjà payé ---
$paiements = detailpaiessachats::where('facture_id', $data->id)->get();
$montant_usd_paye = 0;
$montant_cdf_paye = 0;

foreach ($paiements as $paiement) {
    if ($paiement->devise_recu == 0) {
        $montant_usd_paye += $paiement->montant_recu;
        $montant_cdf_paye += $paiement->montant_recu * $taux;
    } else {
        $montant_cdf_paye += $paiement->montant_recu;
        $montant_usd_paye += $paiement->montant_recu / $taux;
    }
}

$reste_usd = $montant_usd - $montant_usd_paye;
$reste_cdf = $montant_cdf - $montant_cdf_paye;

$paye_affichage =
    number_format($montant_usd_paye, 2, ',', ' ') .
    ' USD (' .
    number_format($montant_cdf_paye, 2, ',', ' ') .
    ' CDF)';
$reste_affichage =
    number_format($reste_usd, 2, ',', ' ') .
    ' USD (' .
    number_format($reste_cdf, 2, ',', ' ') .
    ' CDF)';
                    @endphp
                    <tr id="row_{{ $data->id }}" data-montant-usd="{{ $montant_usd }}"
                        data-montant-cdf="{{ $montant_cdf }}" data-paye-usd="{{ $montant_usd_paye }}"
                        data-paye-cdf="{{ $montant_cdf_paye }}" data-credit-usd="{{ $reste_usd }}"
                        data-credit-cdf="{{ $reste_cdf }}" data-benefice-usd="{{ $benefice_usd }}"
                        data-benefice-cdf="{{ $benefice_cdf }}">
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
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="table-cell"
                            data-table="{{ $data->table_id == 0 ? 'Aucune' : Tables::where('id', $data->table_id)->first()['nom'] ?? 'N/A' }}">
                            @if ($data->table_id == 0)
                                Aucune
                            @else
                                <?php $table = Tables::where('id', $data->table_id)->first() ?? 'N/A'; ?>
                                @if ($table->occupee == 1)
                                    <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">
                                        {{ $table->nom }}</span>
                                @endif
                                @if ($table->occupee == 0)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">
                                        {{ $table->nom }}</span>
                                @endif
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell"
                            data-montant="{{ $total }}">
                            {{ $montant_affichage }}
                        </td>
                        <td class="paye-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $paye_affichage }}
                        </td>
                        <td class="reste-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $reste_affichage }}
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
                            data-statut="{{ $reste_usd > 0 ? 'unpaid' : 'paid' }}">
                            @if ($reste_usd > 0)
                                @if ($montant_usd_paye > 0)
                                    <i class="zmdi zmdi-time text-warning"></i> <span
                                        class="text-warning">Partiel</span>
                                @else
                                    <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                        class="text-danger">Impayé</span>
                                @endif
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
                            @if ($reste_usd > 0)
                                <a id="detail_{{ $i }}" href="#"><i
                                        class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                            @else
                                <a id="detail_{{ $i }}" href="#"><i
                                        class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                            @endif
                            <?php } else { ?>
                            @if ($reste_usd > 0)
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
