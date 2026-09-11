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
                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Payée</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date de paie</th>
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
                        $total_frais_credit = 0;

                        /* ============================================================
                            MONTANT DÛ RÉEL = Σ (total − réduction) par achat
                            (réduction retranchée par achat, dans SA devise)
                            ============================================================ */
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

                            if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                $total_frais_credit += $e->frais_credit;
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

                        $dernier_paiement = detailpaiessachats::where('facture_id', $data->id)
                            ->orderBy('created_at', 'desc')
                            ->first();

                        if ($dernier_paiement && $dernier_paiement->created_at) {
                            $date_paie = date('d/m/Y à H:i', strtotime($dernier_paiement->created_at));
                        } else {
                            $date_paie = 'Aucune';
                        }

                        $date_paie_ymd = ($dernier_paiement && $dernier_paiement->created_at)
                            ? date('Y-m-d', strtotime($dernier_paiement->created_at))
                            : '';

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

                        /* ============================================================
                            TOTAL FINAL = Σ (total − réduction + frais_credit) par achat
                            (chaque montant dans SA devise, puis converti)
                            ============================================================ */
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

                            // Net de CET achat = total − réduction + frais (devise achat)
                            $net_achat_devise = $e->total - $reduction_achat + $frais_credit_achat;

                            if ($devise_achat == $data->devise) {
                                $total += $net_achat_devise;
                            } elseif ($data->devise == 0) {
                                $total += ($taux > 0) ? ($net_achat_devise / $taux) : 0;
                            } else {
                                $total += $net_achat_devise * $taux;
                            }

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

                        $total_frais_credit_final = 0;
                        foreach ($ent as $e) {
                            if ($e->frais_credit != 0 && $e->frais_credit !== null) {
                                $total_frais_credit_final += $e->frais_credit;
                            }
                        }
                        $a_frais_credit = ($total_frais_credit_final > 0);

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

                        $reste_usd = $montant_usd - $montant_usd_paye;
                        $reste_cdf = $montant_cdf - $montant_cdf_paye;

                        $paye_affichage = number_format($montant_usd_paye, 2, ',', ' ') . ' USD (' . number_format($montant_cdf_paye, 2, ',', ' ') . ' CDF)';
                        $reste_affichage = number_format($reste_usd, 2, ',', ' ') . ' USD (' . number_format($reste_cdf, 2, ',', ' ') . ' CDF)';
                        $statut_text = $reste_usd > 0 ? 'Impayée' : 'Payée';
                        $client_name = $data->client_id == 0 ? $data->libelle : (Clients::where('id', $data->client_id)->first()['name'] ?? 'N/A');

                        $est_partiel = ($reste_usd > 0 && $montant_usd_paye > 0);
                        $est_solde = ($reste_usd <= 0);

                        if ($est_partiel) {
                            $statut_data = 'partial';
                        } elseif ($est_solde) {
                            $statut_data = 'paid';
                        } else {
                            $statut_data = 'unpaid';
                        }

                        $date_creation_date = date('Y-m-d', strtotime($data->created_at));
                        $date_aujourdhui   = date('Y-m-d');

                        if ($est_solde && $a_frais_credit && $dernier_paiement) {
                            $date_comparaison = date('Y-m-d', strtotime($dernier_paiement->created_at));
                        } else {
                            $date_comparaison = $date_aujourdhui;
                        }

                        $nb_jours_retard = (int) ((strtotime($date_comparaison) - strtotime($date_creation_date)) / 86400);
                        if ($nb_jours_retard < 0) {
                            $nb_jours_retard = 0;
                        }

                        $show_delay_badge = false;

                        if (!$dernier_paiement) {
                            $show_delay_badge = true;
                        } elseif ($est_partiel) {
                            $show_delay_badge = true;
                        } elseif ($est_solde && $a_frais_credit && $dernier_paiement) {
                            $show_delay_badge = true;
                        }

                        $delay_badge_type = 'success';
                        if ($nb_jours_retard >= 7 && $nb_jours_retard <= 15) {
                            $delay_badge_type = 'warning';
                        } elseif ($nb_jours_retard > 15) {
                            $delay_badge_type = 'danger';
                        }

                        $nb_tranches = $paiements->count();
                        $tranches_details = [];
                        foreach ($paiements as $p) {
                            $tranches_details[] = [
                                'date' => date('d/m/Y à H:i', strtotime($p->created_at)),
                                'montant_recu' => number_format($p->montant_recu, 2, ',', ' '),
                                'devise' => $p->devise_recu == 0 ? 'USD' : 'CDF',
                                'mode' => $p->mode_de_paiement == 1 ? 'CASH' : ($p->mode_de_paiement == 2 ? 'Mobile Money' : 'Bank'),
                                'taux' => number_format($p->taux, 2, ',', ' '),
                                'montant_effectif' => number_format($p->montant_effectif, 2, ',', ' '),
                                'reste' => number_format($p->reste, 2, ',', ' ')
                            ];
                        }
                        $tranches_json = json_encode($tranches_details, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP);

                        /* ===== DONNÉES POUR MODALE PARAMÈTRES ===== */
                        $modeLabels = [1 => 'CASH', 2 => 'Mobile money', 3 => 'Bank'];

                        $articles_json = [];
                        foreach ($ent as $e) {
                            $nom_article_aff = $e->nom_article
                                            ?? $e->nom
                                            ?? $e->name
                                            ?? ('Article #' . $e->id);

                            $pa = $e->prix_achat ?? 0;
                            $qt = $e->quantite ?? 1;
                            $prix_unit = $e->prix_vente ?? ($qt > 0 ? ($e->total / $qt) : $e->total);

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

                        $statut_html_param = $reste_usd > 0
                            ? '<span class="text-danger"><i class="zmdi zmdi-close-circle"></i> Impayé</span>'
                            : '<span class="text-success"><i class="zmdi zmdi-check-circle"></i> Payé</span>';

                        $mode_paiement_label = $modeLabels[$data->mode_de_paiement] ?? 'N/A';

                        $table_name_param = 'Aucune';
                        if (isset($data->table_id) && $data->table_id != 0) {
                            $tbl = Tables::where('id', $data->table_id)->first();
                            if ($tbl) { $table_name_param = $tbl->nom; }
                        }
                    @endphp

                    @if ($reste_usd > 0 || $total_frais_credit_final > 0)
                    <tr id="row_{{ $data->id }}"
                        data-paie-date-ymd="{{ $date_paie_ymd }}"
                        data-montant-usd="{{ $montant_usd }}"
                        data-montant-cdf="{{ $montant_cdf }}"
                        data-paye-usd="{{ $montant_usd_paye }}"
                        data-paye-cdf="{{ $montant_cdf_paye }}"
                        data-credit-usd="{{ $reste_usd }}"
                        data-credit-cdf="{{ $reste_cdf }}"
                        data-benefice-usd="{{ $benefice_usd }}"
                        data-benefice-cdf="{{ $benefice_cdf }}"
                        data-jours-retard="{{ $nb_jours_retard }}"
                        data-facture-id="{{ $data->id }}"
                        data-numero="{{ $data->numero }}"
                        data-client="{{ $client_name }}"
                        data-client-id="{{ $data->client_id }}"
                        data-user="{{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}"
                        data-table="{{ $table_name_param }}"
                        data-date="{{ date('d/m/Y à H:i', strtotime($data->created_at)) }}"
                        data-devise="{{ $data->devise }}"
                        data-devise-label="{{ $data->devise == 0 ? 'USD' : 'CDF' }}"
                        data-taux="{{ $taux }}"
                        data-mode-paiement="{{ $mode_paiement_label }}"
                        data-statut-html="{{ $statut_html_param }}"
                        data-frais-credit-total="{{ $total_frais_credit_final }}"
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
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="montant-cell" data-montant="{{ $total }}">
                            {{ $montant_affichage }}
                        </td>

                        <td class="paye-cell {{ $reste_usd > 0 ? 'text-danger' : 'text-success' }}">
                            <div class="paye-cell-content">
                                <span>{{ $paye_affichage }}</span>
                                <span class="badge-delay {{ $delay_badge_type }} badge-tranches"
                                        data-tranches="{{ $tranches_json }}"
                                        data-numero="{{ $data->numero }}"
                                        data-client="{{ $client_name }}"
                                        data-total="{{ $montant_affichage }}"
                                        data-paye="{{ $paye_affichage }}"
                                        data-reste="{{ $reste_affichage }}"
                                        data-nb-tranches="{{ $nb_tranches }}"
                                        data-date-debut="{{ $data->created_at }}"
                                        data-date-fin="{{ $dernier_paiement ? $dernier_paiement->created_at : date('Y-m-d H:i:s') }}"
                                        data-jours-retard="{{ $nb_jours_retard }}"
                                        data-statut="{{ $statut_data }}"
                                        title="Cliquez pour voir les détails des tranches">
                                    <i class="zmdi zmdi-layers"></i> {{ $nb_tranches }}
                                </span>
                            </div>
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
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="date-paie-cell" data-date-paie="{{ $date_paie }}">
                            @if (!$dernier_paiement)
                                <span class="text-danger"><i class="zmdi zmdi-time-restore"></i> {{ $date_paie }}</span>
                            @elseif ($est_partiel)
                                <span class="text-warning"><i class="zmdi zmdi-time"></i> {{ $date_paie }}</span>
                            @else
                                <span class="text-success"><i class="zmdi zmdi-check-circle"></i> {{ $date_paie }}</span>
                            @endif

                            @if ($show_delay_badge)
                                <span class="badge-delay {{ $delay_badge_type }}" title="Nombre de jours">
                                    <i class="zmdi zmdi-alarm"></i> {{ $nb_jours_retard }}j
                                </span>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="statut-cell" data-statut="{{ $statut_data }}">
                            @if ($est_partiel)
                                <i class="zmdi zmdi-time text-warning"></i> <span class="text-warning">Partielle</span>
                            @elseif ($est_solde)
                                @if ($data->mode_de_paiement == 1)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">CASH</span>
                                @endif
                                @if ($data->mode_de_paiement == 2)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Mobile money</span>
                                @endif
                                @if ($data->mode_de_paiement == 3)
                                    <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Bank</span>
                                @endif
                            @else
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Impayée</span>
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

                            <?php if ((($delete == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($delete == 0) && (Auth::user()->role == 0))) { ?>
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
                    @endif
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div> 
</div>
