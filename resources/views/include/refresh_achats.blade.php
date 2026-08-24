<?php

use App\Models\Factureass;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\Achats;
use App\Models\Clients;
use App\Models\Mesures;
use App\Models\User;
use App\Models\detailpaiessachats; // Ajout pour les paiements

// --- Calcul du total des achats (existant) ---
$t = 0;
foreach ($achats as $ee) 
{
    $t = $t + $ee->total;
}

// --- Récupération de la facture et calcul des paiements ---
$factureId = $factures['id'] ?? null;
$facture = Factureass::find($factureId);


$montant_usd_1 = 0;
$montant_cdf_1 = 0;
$montant_usd_2 = 0;
$montant_cdf_2 = 0;
$usd_montant_total_a_payer = 0;
$cdf_montant_total_a_payer = 0;

if ($facture) {
    $taux = $facture->taux ?? 1;
    $deviseFacture = $facture->devise; // 0 = USD, 1 = CDF

    // Montant total en USD et CDF
    if ($deviseFacture == 0) {
        $montant_usd_1 = $t;
        $montant_cdf_1 = $t * $taux;
    } else {
        $montant_cdf_1 = $t;
        $montant_usd_1 = $t / $taux;
    }

    // Montant déjà payé
    $paiements = Detailpaiessachats::where('facture_id', $factureId)->get();
    foreach ($paiements as $paiement) {
        if ($paiement->devise_recu == 0) { // USD
            $montant_usd_2 += $paiement->montant_recu;
            $montant_cdf_2 += $paiement->montant_recu * $taux;
        } else { // CDF
            $montant_cdf_2 += $paiement->montant_recu;
            $montant_usd_2 += $paiement->montant_recu / $taux;
        }
    }

    // Soldes restants
    $usd_montant_total_a_payer = $montant_usd_1 - $montant_usd_2;
    $cdf_montant_total_a_payer = $montant_cdf_1 - $montant_cdf_2;
}
?>
<div class="col-12">
    <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">FACTURE N° {{ strtoupper($numero) }}</h4>
</div>

<!-- BLOC CLIENT -->
<div class="col-12 mb-3">
    <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px;">
        <div class="row">
            <div class="col-md-6 text-left text-dark">
                <strong style="font-size: 16px;font-weight: bold">LIBELLE / CLIENT :</strong>
                <span style="font-size: 16px; font-weight: bold;">
                    @if ($factures["client_id"] == 0)
                        {{ $factures["libelle"] }}
                    @else
                        <?= Clients::where('id', $factures["client_id"])->first()['name'] ?? 'N/A' ?>
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<!-- BLOC : MONTANT DÉJÀ PAYÉ (VERT) ET RESTE À PAYER (ROUGE) -->
<div class="col-12 mb-3">
    <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px;">
        <div class="row">
            <div class="col-md-6 text-left">
                <strong style="font-size: 15px; color: #333;">Montant déjà payé :</strong>
                <span style="font-size: 15px; font-weight: bold; color: #28a745;">
                    <?= number_format($montant_usd_2, 2, ',', ' ') ?> USD / 
                    <?= number_format($montant_cdf_2, 2, ',', ' ') ?> CDF
                </span>
            </div>
            <div class="col-md-6 text-right">
                <strong style="font-size: 15px; color: #333;">Reste à payer :</strong>
                <span style="font-size: 15px; font-weight: bold; color: #dc3545;">
                    <?= number_format($usd_montant_total_a_payer, 2, ',', ' ') ?> USD / 
                    <?= number_format($cdf_montant_total_a_payer, 2, ',', ' ') ?> CDF
                </span>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <!-- Bouton Payer seul, sans affichage du total -->
    <h6 style="text-align: right;font-weight: bold;">
        <span>
            <button id="imprimerfacture_2" class="btn btn-primary btn-sm"
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
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
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
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->quantite }}</td>
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

<!-- Champs cachés pour le JavaScript (montants restants) -->
<input type="hidden" id="cdf_montant_payer" value="<?= number_format($cdf_montant_total_a_payer, 2, '.', '') ?>">
<input type="hidden" id="usd_montant_payer" value="<?= number_format($usd_montant_total_a_payer, 2, '.', '') ?>">
<input type="hidden" id="payer" value="<?= number_format($usd_montant_total_a_payer, 2, '.', '') ?>">

<script>
    // Variable pour stocker l'URL du PDF
    var currentPdfUrl = "";
    var cdf_montant_payer = parseFloat($("#cdf_montant_payer").val()) || 0;
    var usd_montant_payer = parseFloat($("#usd_montant_payer").val()) || 0;
    var payer = parseFloat($("#payer").val()) || 0;

    $("#imprimerfacture_2").click(function(e) {
        e.preventDefault(); // Empêcher tout comportement par défaut

        // Mise à jour des champs du formulaire de paiement (s'ils existent)
        $("#n_fac").html("{{ $factures['numero'] ?? '' }}");
        $("#id_fac").val("{{ $factures['id'] ?? '' }}");
        // On réaffecte les montants depuis les variables JS pour être sûr
        $("#cdf_montant_payer").val(cdf_montant_payer);
        $("#usd_montant_payer").val(usd_montant_payer);
        $("#payer").val(payer);

        var pdfUrl = "{{ isset($factures['lien']) ? $factures['lien'] : '' }}";

        if (pdfUrl && pdfUrl !== '') {
            currentPdfUrl = pdfUrl;
            $("#pdfIframe").attr("src", pdfUrl);
            $("#pdfModal").modal("show");
        } else {
            // Requête AJAX pour récupérer le PDF
            $.get("{{ url('/print_facture') }}", {
                "facture_id": "{{ $factures['id'] ?? '' }}"
            }, function(response) {
                if (response && response[0] && response[0][0]) {
                    currentPdfUrl = response[0][0];
                    $("#pdfIframe").attr("src", response[0][0]);
                    // Mise à jour éventuelle des montants (si la réponse les fournit)
                    if (response[0][1] !== undefined) {
                        $("#cdf_montant_payer").val(response[0][1]);
                        cdf_montant_payer = parseFloat(response[0][1]) || 0;
                    }
                    if (response[0][2] !== undefined) {
                        $("#usd_montant_payer").val(response[0][2]);
                        usd_montant_payer = parseFloat(response[0][2]) || 0;
                    }
                    if (response[0][5] !== undefined) {
                        $("#payer").val(response[0][5]);
                        payer = parseFloat(response[0][5]) || 0;
                    }
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