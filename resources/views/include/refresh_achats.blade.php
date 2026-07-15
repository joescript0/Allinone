<?php

use App\Models\Factureas;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\Achats;
use App\Models\Clients;
use App\Models\Mesures;
use App\Models\User;

$t = 0;
foreach ($achats as $ee) {
    $t = $t + $ee->total;
}
?>
<div class="col-12">
    <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">FACTURE N°
        {{ strtoupper($numero) }}</h4>
</div>

<!-- NOUVEAU BLOC POUR LE NOM DU CLIENT -->
<div class="col-12 mb-3">
    <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px;">
        <div class="row">
            <div class="col-md-6 text-left text-dark">
                <strong style="font-size: 16px;font-weight: bold">LIBELLE / CLIENT :</strong>
                <span style="font-size: 16px; font-weight: bold;">
                    @if ($factures['client_id'] == 0)
                        {{ $factures['libelle'] }}
                    @else
                        <?= Clients::where('id', $factures['client_id'])->first()['name'] ?? 'N/A' ?>
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
<!-- FIN BLOC NOM CLIENT -->
<div class="col-12">
    <h6 style="text-align: right;font-weight: bold;">
        <span>
            <span>
                <i class="zmdi zmdi-check-circle text-success"></i>
                @if ($devise == 0)
                    Total : <span id="total_1"><?= number_format($t, 2, ',', ' ') ?></span>USD
                @else
                    Total : <span id="total_1"><?= number_format($t, 2, ',', ' ') ?></span>CDF
                @endif
            </span>
            <button id="imprimerfacture_2" class="btn btn-primary btn-sm ml-3"
                style="margin-left: 15px; padding: 5px 10px; border: none; border-radius: 3px; background-color: #007bff; color: white; cursor: pointer;">
                <i class="zmdi zmdi-money"></i> Payer
            </button>
        </span>
    </h6>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">#</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">ITEM</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">PRIX</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">QTE</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">MONTANT</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($achats as $data)
                    <tr>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $i }}
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            <?= Articles::where('id', $data->article_id)->first()['nom_article'] ?>
                            ({{ Mesures::where('id', Articles::where('id', $data->article_id)->first()['mesure_id'])->first()['nom'] }})
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            <?php if($data->devise == 0){ ?>
                            {{ number_format($data->prix_unitaire, 2, ',', ' ') }}USD
                            <?php }else{ ?>
                            {{ number_format($data->prix_unitaire, 2, ',', ' ') }}CDF
                            <?php }?>
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->quantite }}
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            <?php if($data->devise == 0){ ?>
                            {{ number_format($data->total, 2, ',', ' ') }}USD
                            <?php }else{ ?>
                            {{ number_format($data->total, 2, ',', ' ') }}CDF
                            <?php }?>
                        </td>
                    </tr>
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Variable pour stocker l'URL du PDF
    var currentPdfUrl = "";
    var cdf_montant_payer = $("#cdf_montant_payer").val();
    var usd_montant_payer = $("#usd_montant_payer").val();
    var payer = $("#payer").val();

    $("#imprimerfacture_2").click(function(e) {
        $("#n_fac").html("{{ $data['numero'] ?? '' }}");
        $("#id_fac").val("{{ $data['facture_id'] ?? '' }}");
        var pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";

        if (pdfUrl && pdfUrl !== '') {
            // Afficher le PDF dans l'iframe de la modale
            currentPdfUrl = pdfUrl;
            $("#pdfIframe").attr("src", pdfUrl);
            $("#pdfModal").modal("show");
        } else {
            // Alternative : faire la requête AJAX
            $.get("{{ url('/print_facture') }}", {
                "facture_id": "{{ $data['facture_id'] ?? '' }}"
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
    });

    // Nettoyer l'iframe quand la modale est fermée
    $("#pdfModal").on("hidden.bs.modal", function() {
        $("#pdfIframe").attr("src", "");
    });
</script>
