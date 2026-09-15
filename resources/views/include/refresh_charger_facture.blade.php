<?php

use App\Models\Mois;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Facturesnormalisees;
use App\Models\Writes;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0" id="facturesTable">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Client</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Mois</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Fichier</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($facturesnormalisees as $data)
                @php
                    /* === Récupération du nom du client pour cette ligne === */
                    $clientNom = '';
                    if (!empty($data->client_id)) {
                        foreach ($clients as $c) {
                            if ($c->id == $data->client_id) { $clientNom = $c->name; break; }
                        }
                    }
                    /* === Récupération du libellé mois/année === */
                    $moisNom  = Mois::where(['id' => $data->moi_id])->first()['nom'] ?? '';
                    $anneeNom = Annees::where(['id' => $data->annee_id])->first()['annees'] ?? '';
                @endphp
                <tr class="facture-row"
                    data-client="{{ strtolower($clientNom) }}"
                    data-mois="{{ strtolower($moisNom) }}"
                    data-annee="{{ strtolower($anneeNom) }}"
                    data-fichier="{{ strtolower($data->fichier_original) }}">
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        {{ $i }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;" class="client-cell">
                        {{ $clientNom !== '' ? $clientNom : '—' }}
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;" class="mois-cell">
                        {{ $moisNom }}
                        {{ $anneeNom }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;" class="fichier-cell">
                        <i class="zmdi zmdi-collection-pdf text-danger"></i>
                        {{ $data->fichier_original }}
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        {{-- ===== ICÔNE 1 : VOIR LE FICHIER (OEIL) ===== --}}
                        <a id="view_<?= $i ?>" href="#" title="Voir le fichier">
                            <i class="zmdi zmdi-eye text-info"></i>
                        </a>
                        &nbsp;&nbsp;
                        {{-- ===== ICÔNE 2 : VOIR LE Modifier (OEIL) ===== --}}
                        <a id="edit_<?= $i ?>" href="#" title="Modifier">
                            <i class="zmdi zmdi-edit text-success"></i>
                        </a>
                        &nbsp;&nbsp;
                        {{-- ===== ICÔNE 3 : SUPPRIMER (ROUGE) ===== --}}
                        <a id="delete_<?= $i ?>" href="#" title="Supprimer">
                            <i class="zmdi zmdi-delete text-danger"></i>
                        </a>

                        <script>
                        /* ===== VOIR LE FICHIER DANS LA MODALE (avec détails) ===== */
                        $("#view_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            var fileUrl = "{{ asset($data->lien) }}";
                            var fileName = "{{ $data->fichier_original }}";
                            var moisNom = "<?= $moisNom ?>";
                            var anneeNom = "<?= $anneeNom ?>";
                            var clientNom = "<?= $clientNom ?>";

                            $("#modal_view_file .file-name").html(
                                '<i class="zmdi zmdi-collection-pdf"></i> ' + fileName
                            );

                            /* ==== Entête détails de la ligne ==== */
                            $("#view_file_details").html(
                                '<span class="detail-item"><i class="zmdi zmdi-calendar"></i> Mois : ' + (moisNom || '—') + ' ' + (anneeNom || '') + '</span>' +
                                '<span class="detail-item"><i class="zmdi zmdi-account"></i> Client : ' + (clientNom || '—') + '</span>' +
                                '<span class="detail-item"><i class="zmdi zmdi-collection-pdf"></i> Fichier : ' + (fileName || '—') + '</span>'
                            );

                            $("#modal_view_file iframe").attr("src", fileUrl);
                            $("#modal_view_file").modal("show");
                        });

                        /* ===== MODIFIER ===== */
                        $("#edit_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/refresh_editfacturesnormalisees') }}", {
                                facturesnormalise_id: <?= $data->id ?>,
                            }, function(refresh_editutilisateur) {
                                $("#bloc_1").hide();
                                $("#bloc_2").hide();
                                $("#bloc_3").show();
                                $("#bloc_3").html(refresh_editutilisateur);
                            });
                        });

                        /* ===== SUPPRIMER (avec détails) ===== */
                        $("#delete_<?= $i ?>").click(function(e) {
                            e.preventDefault();
                            $("#element").html(
                                '<div class="del-title"><?= $moisNom ?> <?= $anneeNom ?></div>' +
                                '<div class="del-line"><i class="zmdi zmdi-account"></i> Client : <?= $clientNom !== '' ? $clientNom : '—' ?></div>' +
                                '<div class="del-line"><i class="zmdi zmdi-collection-pdf"></i> <?= $data->fichier_original ?></div>'
                            );
                            $("#data_id").html("<?= $data->id ?>");
                            $("#btn_sup").trigger("click");
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
