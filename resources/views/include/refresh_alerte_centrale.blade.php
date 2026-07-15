<?php
use App\Models\Mois;
use App\Models\Postes;
use App\Models\Annees;
use App\Models\Soldes;
use App\Models\Listespaies;
use App\Models\Writes;
use App\Models\Alertes;
use App\Models\Listesfactures;
use App\Models\User;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Poste</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Officier</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Motif</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Alerte</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Transferer par</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($alertes as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $i }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ Postes::where(['id' => $data->poste_id])->first()['nom'] }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ User::where(['id' => $data->user_id])->first()['name'] }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->motif }}
                        </td>
                        <td style="text-align: center; padding-top: 5px; padding-bottom: 5px;">
                            @if ($data->etat_1 == 1)
                                <i class="zmdi zmdi-notifications-active alert-bell-active" title="Alerte activée"></i>
                            @else
                                <i class="zmdi zmdi-notifications-off alert-bell-inactive"
                                    title="Alerte désactivée"></i>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->user_id_transfert == 0)
                                <i class="zmdi zmdi-account text-danger"></i> <span class="text-danger">Aucune personne
                                </span>
                            @else
                                @if ($data->user_id_transfert == Auth::user()->id)
                                    <i class="zmdi zmdi-account text-success"></i> <span
                                        class="text-success">Vous</span>
                                @else
                                    <i class="zmdi zmdi-account text-success"></i> <span
                                        class="text-success">{{ User::where(['id' => $data->user_id_transfert])->first()['name'] }}</span>
                                @endif
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->etat_1 == 0)
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Désactivé
                                </span>
                            @endif
                            @if ($data->etat_1 == 1)
                                <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">Activé
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->etat_1 == 1)
                                <a id="activer_<?= $i ?>" title="Activé" href="#"><i
                                        class="zmdi zmdi-settings text-success"></i> <span
                                        class="text-warning"></span></a>
                            @endif
                            @if ($data->etat_1 == 0)
                                <a id="activer__<?= $i ?>" title="Désactivé" href="#"><i
                                        class="zmdi zmdi-settings text-danger"></i> <span
                                        class="text-danger"></span></a>
                            @endif
                            @if ($data->user_id_transfert == 0)
                                <a id="transferer_<?= $i ?>" title="Envoyer" href="#"><i
                                        class="zmdi zmdi-mail-send text-danger"></i> <span
                                        class="text-warning"></span></a>
                            @endif
                            @if ($data->user_id_transfert != 0)
                                <a id="transferer__<?= $i ?>" title="Deja envoyé" href="#"><i
                                        class="zmdi zmdi-mail-send text-success"></i> <span
                                        class="text-danger"></span></a>
                            @endif
                            <a id="map_<?= $i ?>" title="Voir sur la carte" href="#"><i
                                    class="zmdi zmdi-map text-info"></i> <span class="text-danger"></span></a>
                            <script>
                                $("#activer_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element_1").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_ac").trigger("click");
                                });
                                $("#activer__<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element_3").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_cll").trigger("click");
                                });
                                $("#transferer_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element_transfert").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_tra").trigger("click");
                                });
                                $("#transferer__<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element_transfert_e").html("{{ User::where(['id' => $data->user_id])->first()['name'] }}");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_tra_e").trigger("click");
                                });
                                $("#map_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    var latitude = "{{ $data->latitude }}";
                                    var longitude = "{{ $data->longitude }}";
                                    var titre = "{{ addslashes($data->motif) }}";

                                    // Vérifier si les coordonnées sont valides
                                    if (!latitude || !longitude || latitude == 0 || longitude == 0) {
                                        $("#mapError").show();
                                        $("#mapPreview").hide();
                                        $("#mapModal").modal('show');
                                        return;
                                    }

                                    $("#mapError").hide();
                                    $("#mapPreview").show();

                                    // Attendre que le modal soit ouvert pour initialiser la carte
                                    $("#mapModal").one('shown.bs.modal', function() {
                                        // Si une carte existe déjà, on la détruit
                                        if (window.alertMapInstance) {
                                            window.alertMapInstance.remove();
                                        }

                                        // Créer une nouvelle carte
                                        var map = L.map('mapPreview').setView([latitude, longitude], 15);
                                        window.alertMapInstance = map;

                                        // Ajouter le fond de carte (OpenStreetMap avec style CartoDB)
                                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB',
                                            subdomains: 'abcd',
                                            maxZoom: 19,
                                            minZoom: 3
                                        }).addTo(map);

                                        // Ajouter un marqueur
                                        var marker = L.marker([latitude, longitude]).addTo(map);
                                        marker.bindPopup(`
                                                                    <b>Alerte :</b> ${titre}<br>
                                                                    <b>Coordonnées :</b><br>
                                                                    Lat: ${latitude}<br>
                                                                    Lng: ${longitude}
                                                                `).openPopup();

                                        // Ajouter un cercle rouge clair d'un rayon de 10 mètres
                                        L.circle([latitude, longitude], {
                                            color: '#ff4444',
                                            fillColor: '#ff8888',
                                            fillOpacity: 0.4,
                                            radius: 50
                                        }).addTo(map);

                                        // Redimensionner la carte après ouverture (correction d'affichage)
                                        setTimeout(function() {
                                            map.invalidateSize();
                                        }, 200);
                                    });

                                    $("#mapModal").modal('show');
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
<script>
    $("#alerte_active").html("{{ Alertes::where(['supprimer' => 0, 'etat_1' => 1])->get()->count() }}");
</script>
