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
                        if ($taux <= 0) $taux = 1;

                        $ent = Achats::where('facture_id', $data->id)->get();

                        // ============================================================
                        // MONTANT DÛ RÉEL = Σ (total − reduction) par achat
                        // (réduction retranchée sur chaque achat, dans SA devise)
                        // Sert à déterminer si la facture est IMPAYÉE.
                        // ============================================================
                        $total_original = 0;
                        foreach ($ent as $e)
                        {
                            $devise_achat_orig = $e->devise_achat ?? $data->devise;
                            $reduction_orig = (isset($e->reduction) && $e->reduction > 0) ? $e->reduction : 0;
                            $net_orig = $e->total - $reduction_orig;

                            if ($devise_achat_orig == $data->devise) {
                                $total_original += $net_orig;
                            } elseif ($data->devise == 0) {
                                $total_original += ($taux > 0) ? ($net_orig / $taux) : 0;
                            } else {
                                $total_original += $net_orig * $taux;
                            }
                        }

                        $paiements = detailpaiessachats::where('facture_id', $data->id)->get();
                        $montant_usd_paye = 0;
                        $montant_cdf_paye = 0;
                        foreach ($paiements as $paiement)
                        {
                            if ($paiement->devise_recu == 0) {
                                $montant_usd_paye += $paiement->montant_recu;
                                $montant_cdf_paye += $paiement->montant_recu * $taux;
                            } else {
                                $montant_cdf_paye += $paiement->montant_recu;
                                $montant_usd_paye += ($taux > 0) ? ($paiement->montant_recu / $taux) : 0;
                            }
                        }
                        if ($data->devise == 0)
                        {
                            $total_original_usd = $total_original;
                            $total_original_cdf = $total_original * $taux;
                        }
                        else
                        {
                            $total_original_cdf = $total_original;
                            $total_original_usd = ($taux > 0) ? ($total_original / $taux) : 0;
                        }
                        $est_impayee = ($montant_usd_paye < $total_original_usd) || ($montant_cdf_paye < $total_original_cdf);
                        $date_creation_facture = strtotime($data->created_at);
                        $delai_1h = 3600;
                        $delai_depasse = (time() - $date_creation_facture) > $delai_1h;

                        // ============================================================
                        // CALCUL DU TOTAL FINAL (avec frais crédit)
                        // Net achat = (total − réduction + frais_credit)
                        // Calculé dans la devise de CHAQUE achat, puis converti
                        // vers la devise de la facture.
                        // ============================================================
                        $total = 0;
                        $achat_total_usd = 0;
                        $achat_total_cdf = 0;
                        foreach ($ent as $e)
                        {
                            $devise_achat = $e->devise_achat ?? $data->devise;
                            $reduction_achat = (isset($e->reduction) && $e->reduction > 0) ? $e->reduction : 0;

                            $frais_credit_achat = 0;
                            if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                $frais_credit_achat = $e->frais_credit;
                            } else {
                                if ($est_impayee && $delai_depasse) {
                                    $frais_credit_achat = $e->total * 0.05;
                                    $e->frais_credit = $frais_credit_achat;
                                    $e->save();
                                }
                            }

                            // Net de CET achat dans SA devise
                            $net_achat_devise = $e->total - $reduction_achat + $frais_credit_achat;

                            // Conversion vers la devise de la facture
                            if ($devise_achat == $data->devise) {
                                $total += $net_achat_devise;
                            } elseif ($data->devise == 0) {
                                $total += ($taux > 0) ? ($net_achat_devise / $taux) : 0;
                            } else {
                                $total += $net_achat_devise * $taux;
                            }

                            // Bénéfice : prix d'achat converti par devise
                            $prix_achat = $e->prix_achat ?? 0;
                            $quantite = $e->quantite ?? 1;
                            $prix_achat_total = $prix_achat * $quantite;
                            if ($devise_achat == 0) {
                                $achat_total_usd += $prix_achat_total;
                                $achat_total_cdf += $prix_achat_total * $taux;
                            } else {
                                $achat_total_cdf += $prix_achat_total;
                                $achat_total_usd += ($taux > 0) ? ($prix_achat_total / $taux) : 0;
                            }
                        }

                        if ($data->devise == 0)
                        {
                            $montant_usd = $total;
                            $montant_cdf = $total * $taux;
                            $montant_affichage = number_format($total, 2, ',', ' ') . ' USD (' . number_format($montant_cdf, 2, ',', ' ') . ' CDF)';
                        } else {
                            $montant_cdf = $total;
                            $montant_usd = ($taux > 0) ? ($total / $taux) : 0;
                            $montant_affichage = number_format($total, 2, ',', ' ') . ' CDF (' . number_format($montant_usd, 2, ',', ' ') . ' USD)';
                        }
                        $benefice_usd = $montant_usd - $achat_total_usd;
                        $benefice_cdf = $montant_cdf - $achat_total_cdf;

                        // CRÉDIT = total final (avec réduction + frais) − paiements
                        $reste_usd = $montant_usd - $montant_usd_paye;
                        $reste_cdf = $montant_cdf - $montant_cdf_paye;

                        $paye_affichage = number_format($montant_usd_paye, 2, ',', ' ') . ' USD (' . number_format($montant_cdf_paye, 2, ',', ' ') . ' CDF)';
                        $reste_affichage = number_format($reste_usd, 2, ',', ' ') . ' USD (' . number_format($reste_cdf, 2, ',', ' ') . ' CDF)';
                        $statut_text = $reste_usd > 0 ? 'Impayé' : 'Payé';
                        $client_name = $data->client_id == 0 ? $data->libelle : (Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A');

                        if ($reste_usd <= 0) {
                            $statut_code = 'paid';
                        } elseif ($montant_usd_paye > 0) {
                            $statut_code = 'partiel';
                        } else {
                            $statut_code = 'unpaid';
                        }

                        $modeLabels = [1 => 'CASH', 2 => 'Mobile money', 3 => 'Bank'];

                        $articles_json = [];
                        foreach ($ent as $e) {
                            $art = null;
                            foreach (['article_id', 'entre_id', 'entree_id', 'produit_id', 'id_article'] as $field) {
                                if (isset($e->$field) && $e->$field) {
                                    $art = $articles->firstWhere('id', $e->$field);
                                    if ($art) break;
                                }
                            }

                            $nom_article_aff = $art->nom_article
                                            ?? $e->nom_article
                                            ?? $e->nom
                                            ?? $art->name
                                            ?? ('Article #' . $e->id);

                            $pa = $e->prix_achat ?? 0;
                            $qt = $e->quantite ?? 1;
                            $prix_unit = $e->prix_vente
                                        ?? ($art->prix_detail ?? null)
                                        ?? ($e->total / max($qt, 1));

                            $devise_achat_json = $e->devise_achat ?? $data->devise;
                            $reduction_ligne   = $e->reduction ?? 0;

                            $articles_json[] = [
                                'id'               => $e->id,
                                'nom'              => $nom_article_aff,
                                'quantite'         => $qt,
                                'prix_unitaire'    => $prix_unit,
                                'prix_achat'       => $pa,
                                'prix_achat_total' => $pa * $qt,
                                'total'            => $e->total,
                                'total_net'        => $e->total - $reduction_ligne,
                                'frais_credit'     => $e->frais_credit ?? 0,
                                'reduction'        => $reduction_ligne,
                                'devise_achat'     => $devise_achat_json,
                            ];
                        }

                        $paiements_json = [];
                        foreach ($paiements as $p) {
                            $isUSD = ($p->devise_recu == 0);
                            $paiements_json[] = [
                                'id'               => $p->id,
                                'date'             => date('d/m/Y à H:i', strtotime($p->created_at)),
                                'payer'            => $p->payer,
                                'montant_recu'     => $p->montant_recu,
                                'devise_label'     => $isUSD ? 'USD' : 'CDF',
                                'mode_de_paiement' => $p->mode_de_paiement,
                                'mode_label'       => $modeLabels[$p->mode_de_paiement] ?? 'N/A',
                                'taux'             => $p->taux,
                                'reste'            => $p->reste,
                                'montant_effectif' => $p->montant_effectif,
                            ];
                        }

                        $frais_credit_total = 0;
                        foreach ($ent as $e) { $frais_credit_total += ($e->frais_credit ?? 0); }

                        $statut_html = $reste_usd > 0
                            ? '<span class="text-danger"><i class="zmdi zmdi-close-circle"></i> Impayé</span>'
                            : '<span class="text-success"><i class="zmdi zmdi-check-circle"></i> Payé</span>';

                        $mode_paiement_label = $modeLabels[$data->mode_de_paiement] ?? 'N/A';
                    @endphp
                    <tr id="row_{{ $data->id }}"
                        data-facture-id="{{ $data->id }}"
                        data-numero="{{ $data->numero }}"
                        data-client="{{ $client_name }}"
                        data-client-id="{{ $data->client_id }}"
                        data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}"
                        data-table="{{ $data->table_id == 0 ? 'Aucune' : (Tables::where('id', $data->table_id)->first()['nom'] ?? 'N/A') }}"
                        data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                        data-devise="{{ $data->devise }}"
                        data-devise-label="{{ $data->devise == 0 ? 'USD' : 'CDF' }}"
                        data-taux="{{ $taux }}"
                        data-mode-paiement="{{ $mode_paiement_label }}"
                        data-statut-html="{{ $statut_html }}"
                        data-montant-usd="{{ $montant_usd }}"
                        data-montant-cdf="{{ $montant_cdf }}"
                        data-paye-usd="{{ $montant_usd_paye }}"
                        data-paye-cdf="{{ $montant_cdf_paye }}"
                        data-credit-usd="{{ $reste_usd }}"
                        data-credit-cdf="{{ $reste_cdf }}"
                        data-benefice-usd="{{ $benefice_usd }}"
                        data-benefice-cdf="{{ $benefice_cdf }}"
                        data-frais-credit-total="{{ $frais_credit_total }}"
                        data-pdf-url="{{ $data->lien ?? '' }}"
                        data-articles='@json($articles_json)'
                        data-paiements='@json($paiements_json)'>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="numero-cell" data-numero="{{ $data->numero }}">{{ $data->numero }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="user-cell" data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}">
                            {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell" data-client="{{ $client_name }}">
                            @if ($data->client_id == 0)
                                {{ $data->libelle }}
                            @else
                                <?= Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A' ?>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="table-cell" data-table="{{ $data->table_id == 0 ? 'Aucune' : (Tables::where('id', $data->table_id)->first()['nom'] ?? 'N/A') }}">
                            @if ($data->table_id == 0)
                                Aucune
                            @else
                                <?php  $table = Tables::where('id', $data->table_id)->first() ?? 'N/A' ?>
                                @if ($table->occupee == 1)
                                    <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger"> {{ $table->nom }}</span>
                                @endif
                                @if ($table->occupee == 0)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success"> {{ $table->nom }}</span>
                                @endif
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="{{ $total }}">
                            {{ $montant_affichage }}
                        </td>
                        <td class="paye-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $paye_affichage }}
                        </td>
                        <td class="reste-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $reste_affichage }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="date-cell" data-date="{{ $data->created_at }}">
                            <?php
                                $date = $data->created_at;
                                $date_1 = explode(' ', $date);
                                echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                            ?>
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell" data-statut="{{ $statut_code }}">
                            @if ($statut_code == 'partiel')
                                <i class="zmdi zmdi-time text-warning"></i> <span class="text-warning">Partiel</span>
                            @elseif ($statut_code == 'unpaid')
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Impayé</span>
                            @else
                                @if ($data->mode_de_paiement == 1)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">CASH</span>
                                @endif
                                @if ($data->mode_de_paiement == 2)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Mobile money</span>
                                @endif
                                @if ($data->mode_de_paiement == 3)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Bank</span>
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
                                    <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                @else
                                    <a id="detail_{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                @endif
                            <?php } else { ?>
                                @if ($reste_usd > 0)
                                    <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-money text-danger"></i></a> &nbsp;
                                @else
                                    <a id="detail_r{{ $i }}" href="#"><i class="zmdi zmdi-eye text-success"></i></a> &nbsp;
                                @endif
                            <?php } ?>

                            <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (Auth::user()->role == 0)) { ?>
                                <a href="#" class="param-facture-btn"
                                    data-id="{{ $data->id }}"
                                    title="Paramètres de la facture">
                                    <i class="zmdi zmdi-settings text-info"></i>
                                </a>
                            <?php } ?>

                            <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (Auth::user()->role == 0)) { ?>
                                <a href="#" class="delete-facture-btn"
                                    data-id="{{ $data->id }}"
                                    data-numero="{{ $data->numero }}"
                                    data-client="{{ $client_name }}"
                                    data-montant="{{ $montant_affichage }}"
                                    data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                                    data-statut="{{ $statut_text }}"
                                    title="Supprimer cette facture">
                                    <i class="zmdi zmdi-delete text-danger"></i>
                                </a>
                            <?php } ?>
                            <script>
                                $("#detail_{{ $i }}").click(function(e) {
                                    e.preventDefault();
                                    var payer = "<?= $data->payer ?>";
                                    if(payer == 0)
                                    {
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
                                    if(payer == 1)
                                    {
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
