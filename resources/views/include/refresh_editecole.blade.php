<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\User;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\districts;
use App\Models\communes;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                    Nom </span></label>
                <input type="text" id="id" name="id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $ecoles->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    District </span></label>
                <select id="edit_district_id" name="edit_district_id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    <option class="form-control" value="">Selectionnez une district</option>
                    @foreach ($districts as $data)
                        @if ($data->id == $ecoles->district_id)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}
                            </option>
                        @else
                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    Commune </span></label>
                <select id="edit_commune_id" name="edit_commune_id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    @foreach (communes::where(['district_id' => $ecoles->district_id])->get() as $data)
                        @if ($data->id == $ecoles->commune_id)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}
                            </option>
                        @else
                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom
                    de l'école </span></label>
                <input value="{{ $ecoles->nom }}" type="text" id="edit_nom_ecole" name="edit_nom_ecole"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Mike alfa)">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i>
                    Adresse </span></label>
                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Adresse (Ex : Quartier, Av, N° etc....)" name="edit_adresse" id="edit_adresse"
                    cols="2" rows="1">{{ $ecoles->adresse }}</textarea>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom
                    du directeur </span></label>
                <input value="{{ $ecoles->nom_directeur }}" type="text" id="edit_nom_directeur"
                    name="edit_nom_directeur"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom directeur(Ex : Jean paul)">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i>
                    Nombre d'élèves </span></label>
                <input value="{{ $ecoles->nombre_eleve }}" type="text" id="edit_nombre_eleve"
                    name="edit_nombre_eleve"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                    placeholder="Nombre d'élèves (Ex : 100)">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Nombre d'enseignants </span></label>
                <input value="{{ $ecoles->nombre_enseignant }}" type="text" id="edit_nombre_enseignant"
                    name="edit_nombre_enseignant"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                    placeholder="Nombre d'enseignants (Ex : 10)">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Nombre de classe </span></label>
                <input value="{{ $ecoles->nombre_classe }}" type="text" id="edit_nombre_classe"
                    name="edit_nombre_classe"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nombre de classe (Ex : 10)">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Téléphone </span></label>
                <input value="{{ $ecoles->telephone }}" type="text" id="edit_telephone" name="edit_telephone"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Numéro de téléphone (Ex : 0123456789)">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Annnée </span></label>
                <select id="edit_annee_id" name="edit_annee_id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    <option class="form-control" value="">Année</option>
                    @foreach ($annees as $data)
                        @if ($data->id == $ecoles->annee_id)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->annees }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Mois </span></label>
                <select id="edit_mois_id" name="edit_mois_id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    <option class="form-control" value="">Mois</option>
                    @foreach ($mois as $data)
                        @if ($data->id == $ecoles->moi_id)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-accounts"></i> Date de création </span></label>
                <input value="{{ $ecoles->annee_creation }}" type="text" id="edit_date_creation"
                    name="edit_date_creation"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Date de création (Ex : 00/00/0000)">

            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i>
                    Latitude </span></label>
                <input type="text" id="edit_latitude" name="edit_latitude"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Latitude (Ex : 48.8566)" value="{{ $ecoles->latitude }}">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i>
                    Longitude </span></label>
                <input type="text" id="edit_longitude" name="edit_longitude"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Longitude (Ex : 2.3522)" value="{{ $ecoles->longitude }}">
            </div>
        </div>
    </div>
    <!-- Barre d'outils : uniquement le bouton Position actuelle -->
    <div class="edit-map-toolbar">
        <button type="button" id="editbtnCurrentLocation">
            <i class="zmdi zmdi-my-location"></i> Position actuelle
        </button>
    </div>

    <!-- Conteneur de la carte avec overlay pour les trois autres boutons -->
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 0;">
                    <i class="zmdi zmdi-pin-drop"></i> Cliquez sur la carte pour choisir une position
                </label>
                <div id="edit-map-container" style="position: relative;">
                    <div id="editmap"></div>
                    <div class="map-overlay-buttons">
                        <button type="button" id="editbtnClassic" class="map-btn" title="Classique">
                            <i class="zmdi zmdi-map"></i>
                        </button>
                        <button type="button" id="editbtnSatellite" class="map-btn" title="Satellite">
                            <i class="zmdi zmdi-satellite"></i>
                        </button>
                        <button type="button" id="editbtnResetView" class="map-btn" title="Réinitialiser">
                            <i class="zmdi zmdi-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button>
            <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i
                    class="zmdi zmdi-close-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <span style="font-weight: bold;" id="edit_msg">
            </span>
        </div>
    </div>
</form>
<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script>
    $("#edit_district_id").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_commune_by_district') }}", {
            district_id: $(this).val()
        }, function(response) {
            $("#edit_commune_id").html(response);
        });
    });
    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });
    $("#edit_save").click(function(e) {
        e.preventDefault();

        // Déclaration des champs
        var district_id = $("#edit_district_id").val();
        var communes_id = $("#edit_commune_id").val();
        var nom = $("#edit_nom_ecole").val();
        var nombre_eleve = $("#edit_nombre_eleve").val();
        var nombre_enseignant = $("#edit_nombre_enseignant").val();
        var nombre_classe = $("#edit_nombre_classe").val();
        var telephone = $("#edit_telephone").val();
        var annee = $("#edit_annee_id").val();
        var mois = $("#edit_mois_id").val();
        var data = $("#form_edit").serialize();

        // Fonction utilitaire pour afficher un message d'erreur
        function showError(message) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + message);
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        }

        // Validation séquentielle de tous les champs
        if (district_id.trim().length === 0) {
            showError("Sélectionnez le district");
        } else if (communes_id.trim().length === 0) {
            showError("Sélectionnez la commune");
        } else if (nom.trim().length === 0) {
            showError("Complétez le nom de l'école");
        } else if (nombre_eleve.trim().length === 0) {
            showError("Indiquez le nombre d'élèves");
        } else if (nombre_enseignant.trim().length === 0) {
            showError("Indiquez le nombre d'enseignants");
        } else if (nombre_classe.trim().length === 0) {
            showError("Indiquez le nombre de classes");
        } else if (annee.trim().length === 0) {
            showError("Sélectionnez l'année");
        } else if (mois.trim().length === 0) {
            showError("Sélectionnez le mois");
        } else {
            // Tous les champs sont remplis → on continue
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/check_ecole_existe_1",
                data: data,
                success: function(response) {
                    $("#edit_save").attr("disabled", false);
                    if (response == 1) {
                        $('#edit_msg').html(
                            '<i class="zmdi zmdi-close-circle"></i> Cet enregistrement existe déjà'
                            );
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        $("#edit_save").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "/edit_ecole",
                            data: data,
                            success: function(response) {
                                $("#edit_save").attr("disabled", false);
                                // Réinitialisation des champs (optionnel)
                                $('#edit_msg').html(
                                    '<i class="zmdi zmdi-check-circle"></i> Enregistrement modifié avec succès'
                                    );
                                $('#edit_msg').css("color", '#32c787');
                                $("#content_utilisateur").html(response);
                                setTimeout(() => {
                                    $('#edit_msg').html("");
                                }, 9000);
                            }
                        });
                    }
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        // ========== CARTE POUR L'ÉDITION ==========
        // Coordonnées par défaut (celles du poste si elles existent, sinon Kinshasa)
        var defaultEditLat = {{ $ecoles->latitude ?? -4.4419 }};
        var defaultEditLng = {{ $ecoles->longitude ?? 15.2663 }};
        var defaultEditZoom = 13;

        // Vérifier si les coordonnées sont valides
        if (isNaN(defaultEditLat) || defaultEditLat === null || defaultEditLat === "") defaultEditLat = -4.4419;
        if (isNaN(defaultEditLng) || defaultEditLng === null || defaultEditLng === "") defaultEditLng = 15.2663;

        var editMap = L.map('editmap').setView([defaultEditLat, defaultEditLng], defaultEditZoom);
        var editCurrentTileLayer;
        var editMarker = null;

        // Définition des tuiles
        var editTileLayerClassic = L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; CartoDB'
            });
        var editTileLayerSatellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

        function setEditTileLayer(layer) {
            if (editCurrentTileLayer) editMap.removeLayer(editCurrentTileLayer);
            editCurrentTileLayer = layer.addTo(editMap);
        }
        setEditTileLayer(editTileLayerClassic);

        // Fonction pour mettre à jour les champs et le marqueur
        function updateEditLocation(lat, lng) {
            $('#edit_latitude').val(lat.toFixed(6));
            $('#edit_longitude').val(lng.toFixed(6));
            if (editMarker) {
                editMarker.setLatLng([lat, lng]);
            } else {
                editMarker = L.marker([lat, lng]).addTo(editMap);
            }
            editMap.setView([lat, lng], 15);
        }

        // Si des coordonnées initiales existent, placer le marqueur
        if (defaultEditLat !== -4.4419 || defaultEditLng !== 15.2663) {
            updateEditLocation(defaultEditLat, defaultEditLng);
        } else {
            // Sinon, juste centrer sans marqueur
            editMap.setView([defaultEditLat, defaultEditLng], defaultEditZoom);
        }

        // Clic sur la carte
        editMap.on('click', function(e) {
            updateEditLocation(e.latlng.lat, e.latlng.lng);
            $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Position choisie sur la carte');
            $('#edit_msg').css('color', '#32c787');
            setTimeout(() => $('#edit_msg').html(''), 3000);
        });

        // Modification manuelle des champs latitude/longitude
        $('#edit_latitude, #edit_longitude').on('input', function() {
            var lat = parseFloat($('#edit_latitude').val());
            var lng = parseFloat($('#edit_longitude').val());
            if (!isNaN(lat) && !isNaN(lng)) {
                if (editMarker) {
                    editMarker.setLatLng([lat, lng]);
                } else {
                    editMarker = L.marker([lat, lng]).addTo(editMap);
                }
                editMap.setView([lat, lng], 15);
            }
        });

        // Bouton Position actuelle (géolocalisation)
        $("#editbtnCurrentLocation").click(function(e) {
            e.preventDefault();
            if (!navigator.geolocation) {
                $('#edit_msg').html(
                    '<i class="zmdi zmdi-close-circle"></i> Géolocalisation non supportée');
                $('#edit_msg').css('color', '#ff6b68');
                setTimeout(() => $('#edit_msg').html(''), 5000);
                return;
            }
            $('#edit_msg').html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Récupération...');
            $('#edit_msg').css('color', '#2196f3');
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    updateEditLocation(lat, lng);
                    $('#edit_msg').html(
                        '<i class="zmdi zmdi-check-circle"></i> Position actuelle enregistrée');
                    $('#edit_msg').css('color', '#32c787');
                    setTimeout(() => $('#edit_msg').html(''), 4000);
                },
                function(error) {
                    var errMsg = "";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errMsg = "Permission refusée.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errMsg = "Position indisponible.";
                            break;
                        case error.TIMEOUT:
                            errMsg = "Délai dépassé.";
                            break;
                        default:
                            errMsg = "Erreur inconnue.";
                    }
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + errMsg);
                    $('#edit_msg').css('color', '#ff6b68');
                    setTimeout(() => $('#edit_msg').html(''), 5000);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        });

        // Bouton Classique
        $("#editbtnClassic").click(function() {
            setEditTileLayer(editTileLayerClassic);
            $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Mode classique activé');
            $('#edit_msg').css('color', '#32c787');
            setTimeout(() => $('#edit_msg').html(''), 2000);
        });

        // Bouton Satellite
        $("#editbtnSatellite").click(function() {
            setEditTileLayer(editTileLayerSatellite);
            $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Mode satellite activé');
            $('#edit_msg').css('color', '#32c787');
            setTimeout(() => $('#edit_msg').html(''), 2000);
        });

        // Bouton Réinitialiser la vue
        $("#editbtnResetView").click(function() {
            editMap.setView([defaultEditLat, defaultEditLng], defaultEditZoom);
            $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Vue réinitialisée');
            $('#edit_msg').css('color', '#32c787');
            setTimeout(() => $('#edit_msg').html(''), 2000);
        });
    });
</script>
