<?php

use App\Models\Factureas;
use App\Models\Factureass;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\Achats;
use App\Models\Clients;
use App\Models\Mesures;
use App\Models\User;
use App\Models\detailpaiessachats;

// --- Récupération de l'ID de la facture ---
$factureId = $factures['id'] ?? null;
$facture = Factureass::find($factureId);

// --- Initialisation des variables ---
$montant_usd_1 = 0;
$montant_cdf_1 = 0;
$montant_usd_2 = 0;
$montant_cdf_2 = 0;
$usd_montant_total_a_payer = 0;
$cdf_montant_total_a_payer = 0;

if ($facture) {
    $taux = $facture->taux ?? 1;
    $deviseFacture = $facture->devise; // 0 = USD, 1 = CDF

    // --- 1. Calcul du total original (sans frais) ---
    $total_original = 0;
    foreach ($achats as $a) {
        $total_original += $a->total;
    }

    // --- 2. Calcul des paiements déjà effectués ---
    $paiements = Detailpaiessachats::where('facture_id', $factureId)->get();
    $montant_usd_paye = 0;
    $montant_cdf_paye = 0;
    foreach ($paiements as $paiement) {
        if ($paiement->devise_recu == 0) { // paiement en USD
            $montant_usd_paye += $paiement->montant_recu;
            $montant_cdf_paye += $paiement->montant_recu * $taux;
        } else { // paiement en CDF
            $montant_cdf_paye += $paiement->montant_recu;
            $montant_usd_paye += $paiement->montant_recu / $taux;
        }
    }

    // --- 3. Conversion du total original en USD et CDF selon devise de la facture ---
    if ($deviseFacture == 0) {
        $total_original_usd = $total_original;
        $total_original_cdf = $total_original * $taux;
    } else {
        $total_original_cdf = $total_original;
        $total_original_usd = $total_original / $taux;
    }

    // --- 4. Déterminer si la facture est impayée (sans tolérance) ---
    $est_impayee = ($montant_usd_paye < $total_original_usd) || ($montant_cdf_paye < $total_original_cdf);

    // --- 5. Vérifier le délai d'1 heure depuis la création de la facture ---
    $date_creation_facture = strtotime($facture->created_at);
    $delai_1h = 3600; // 1 heure en secondes
    $delai_depasse = (time() - $date_creation_facture) > $delai_1h;

    // --- 6. Appliquer les frais de crédit sur chaque achat si conditions remplies ---
    foreach ($achats as $achat) {
        if (($achat->frais_credit == 0 || $achat->frais_credit === null) && $est_impayee && $delai_depasse) {
            $frais = $achat->total * 0.05; // 5 %
            $achat->frais_credit = $frais;
            $achat->save();
        }
    }

    // --- 7. Recalculer le total incluant les frais de crédit ---
    $total_avec_frais = 0;
    foreach ($achats as $achat) {
        $total_avec_frais += $achat->total + ($achat->frais_credit ?? 0);
    }

    // --- 8. Montant total de la facture en USD et CDF (incluant les frais) ---
    if ($deviseFacture == 0) {
        $montant_usd_1 = $total_avec_frais;
        $montant_cdf_1 = $total_avec_frais * $taux;
    } else {
        $montant_cdf_1 = $total_avec_frais;
        $montant_usd_1 = $total_avec_frais / $taux;
    }

    // --- 9. Montants déjà payés (inchangés) ---
    $montant_usd_2 = $montant_usd_paye;
    $montant_cdf_2 = $montant_cdf_paye;

    // --- 10. Solde restant ---
    $usd_montant_total_a_payer = $montant_usd_1 - $montant_usd_2;
    $cdf_montant_total_a_payer = $montant_cdf_1 - $montant_cdf_2;
}

// Récupération du nom du client
$nomClient = isset($data['client_nom']) ? $data['client_nom'] : "";
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
    <!-- Bouton Payer -->
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
                    <th style="padding-top: 5px;padding-bottom: 5px;">FRAIS CRÉDIT</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">TOTAL AVEC FRAIS</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($achats as $data)
                    @php
                        $frais = $data->frais_credit ?? 0;
                        $total_avec_frais_ligne = $data->total + $frais;
                        $deviseSymbole = $data->devise == 0 ? 'USD' : 'CDF';
                    @endphp
                    <tr>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            <?= Articles::where('id', $data->article_id)->first()['nom_article'] ?>
                            ({{ Mesures::where('id', Articles::where('id', $data->article_id)->first()['mesure_id'])->first()['nom'] }})
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ number_format($data->prix_unitaire, 2, ',', ' ') }} {{ $deviseSymbole }}
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->quantite }}</td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ number_format($data->total, 2, ',', ' ') }} {{ $deviseSymbole }}
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ number_format($frais, 2, ',', ' ') }} {{ $deviseSymbole }}
                        </td>
                        <td class="text-truncate" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ number_format($total_avec_frais_ligne, 2, ',', ' ') }} {{ $deviseSymbole }}
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
    // Variables locales pour les montants
    var cdf_montant_payer = parseFloat($("#cdf_montant_payer").val()) || 0;
    var usd_montant_payer = parseFloat($("#usd_montant_payer").val()) || 0;
    var payer = parseFloat($("#payer").val()) || 0;

    var currentPdfUrl = "";

    $("#imprimerfacture_2").click(function(e) {
        e.preventDefault();
        $("#n_fac").html("{{ strtoupper($numero) }}");
        $("#id_fac").val("{{ $data['facture_id'] ?? '' }}");

        // Mettre à jour les champs du formulaire de paiement avec les soldes réels
        $("#cdf_montant_payer").val(cdf_montant_payer);
        $("#usd_montant_payer").val(usd_montant_payer);
        $("#payer").val(payer);

        // Récupération du PDF
        var pdfUrl = "{{ isset($data['lien']) ? $data['lien'] : '' }}";
        if (pdfUrl && pdfUrl !== '') {
            currentPdfUrl = pdfUrl;
            $("#pdfIframe").attr("src", pdfUrl);
            $("#pdfModal").modal("show");
        } else {
            $.get("{{ url('/print_facture') }}", {
                "facture_id": "{{ $data['facture_id'] ?? '' }}"
            }, function(response) {
                if (response && response[0][0]) {
                    currentPdfUrl = response[0][0];
                    $("#pdfIframe").attr("src", response[0][0]);
                    $("#pdfModal").modal("show");
                } else if (response && typeof response[0][0] === 'string') {
                    currentPdfUrl = response[0][0];
                    $("#pdfIframe").attr("src", response[0][0]);
                    $("#pdfModal").modal("show");
                } else {
                    alert("Aucun PDF disponible pour cette facture.");
                }
            }).fail(function() {
                alert("Erreur lors de la récupération du PDF.");
            });
        }
    });

    $("#pdfModal").on("hidden.bs.modal", function() {
        $("#pdfIframe").attr("src", "");
    });
</script>
