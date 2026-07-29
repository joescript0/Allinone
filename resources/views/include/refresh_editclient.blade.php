<!-- Styles pour la carte -->
<style>
    #map_edit {
        height: 350px;
        width: 100%;
        border-radius: 16px;
        margin-bottom: 15px;
        z-index: 1;
        background: #e9ecef;
    }

    #map-container-edit {
        position: relative;
    }

    /* Styles supplémentaires pour cohérence */
    .leaflet-popup-content {
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .leaflet-popup-content strong {
        color: #0a192f;
    }
</style>

<h4 style="color:rgba(0, 0, 0, 0.6);">
    <i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier
</h4>
<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf
    <!-- Champ caché ID -->
    <div style="display: none;" class="col-6">
        <div class="form-group">
            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom
                </span></label>
            <input type="text" id="id" name="id"
                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                class="form-control" placeholder="Nom (Ex : Mr ILUNGA KASONGO Heritier, Kamoa etc...)"
                value="<?= $clients->id ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                    Nom </span></label>
                <input type="text" id="edit_nom" name="edit_nom"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $clients->name ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i>
                    E-mail </span></label>
                <input type="text" id="edit_email" name="edit_email"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Email (Ex : mgm@gmail.com)" value="<?= $clients->email ?>">
            </div>
        </div>
    </div>

    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i>
                    Telephone </span></label>
                <input type="text" id="edit_phone" name="edit_phone"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Telephone (Ex : +243974743675)" value="<?= $clients->phone ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Type de client</span></label>
                <select id="edit_type_edit" name="edit_type_client"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    @if ($clients->type == 0)
                        <option selected class="form-control" value="0">Privé</option>
                        <option class="form-control" value="1">Entreprise</option>
                    @endif
                    @if ($clients->type == 1)
                        <option class="form-control" value="0">Privé</option>
                        <option selected class="form-control" value="1">Entreprise</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Paiment </span></label>
                <input type="text" id="edit_paiement" name="edit_paiement"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="input-mask form-control" data-mask="00000000000000000000000000000000000000"
                    placeholder="Paiement" value="<?= $clients->paiement ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Devise</span></label>
                <select id="edit_devise" name="edit_devise"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    @if ($clients->devise == 0)
                        <option selected class="form-control" value="0">USD</option>
                        <option class="form-control" value="1">CDF</option>
                    @endif
                    @if ($clients->devise == 1)
                        <option class="form-control" value="0">USD</option>
                        <option selected class="form-control" value="1">CDF</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-settings"></i> Activité </span></label>
                <select id="edit_activite_id" name="edit_activite_id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    @foreach ($activites as $data)
                        @if ($data->id == $clients->activite_id)
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-map"></i>
                    Adresse </span></label>
                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Adresse" name="edit_adresse" id="edit_adresse" cols="2"
                    rows="1"><?= $clients->adresse ?></textarea>
            </div>
        </div>
    </div>

    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    Description </span></label>
                <input type="text" id="edit_description" name="edit_description"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : VIDAGE POUBELLE)"
                    value="<?= $clients->description ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Modele de facture </span></label>
                <select id="edit_facture" name="edit_facture"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control">
                    @if ($clients->factures == 0)
                        <option selected class="form-control" value="0">Africtech</option>
                        <option class="form-control" value="1">Fqsmm</option>
                        <option class="form-control" value="2">Beforward</option>
                    @endif
                    @if ($clients->factures == 1)
                        <option class="form-control" value="0">Africtech</option>
                        <option selected class="form-control" value="1">Fqsmm</option>
                        <option class="form-control" value="2">Beforward</option>
                    @endif
                    @if ($clients->factures == 2)
                        <option class="form-control" value="0">Africtech</option>
                        <option class="form-control" value="1">Fqsmm</option>
                        <option selected class="form-control" value="2">Beforward</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <!-- ===== CHAMPS LATITUDE / LONGITUDE ===== -->
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i>
                    Latitude </span></label>
                <input type="text" id="edit_latitude" name="edit_latitude"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Latitude (Ex : -4.4419)"
                    value="<?= $clients->latitude && $clients->latitude != 0 ? $clients->latitude : '' ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i>
                    Longitude </span></label>
                <input type="text" id="edit_longitude" name="edit_longitude"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Longitude (Ex : 15.2663)"
                    value="<?= $clients->longitude && $clients->longitude != 0 ? $clients->longitude : '' ?>">
            </div>
        </div>
    </div>

    <!-- ===== BARRE D'OUTILS ===== -->
    <div class="map-toolbar">
        <button type="button" id="btnCurrentLocationEdit">
            <i class="zmdi zmdi-my-location"></i> Position actuelle
        </button>
    </div>

    <!-- ===== CARTE ===== -->
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 0;">
                    <i class="zmdi zmdi-pin-drop"></i> Cliquez sur la carte pour choisir une position
                </label>
                <div id="map-container-edit" style="position: relative;">
                    <div id="map_edit"></div>
                    <div class="map-overlay-buttons">
                        <button type="button" id="btnClassicEdit" class="map-btn" title="Classique">
                            <i class="zmdi zmdi-map"></i>
                        </button>
                        <button type="button" id="btnSatelliteEdit" class="map-btn" title="Satellite">
                            <i class="zmdi zmdi-satellite"></i>
                        </button>
                        <button type="button" id="btnResetViewEdit" class="map-btn" title="Réinitialiser">
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
            <span style="font-weight: bold;" id="edit_msg"></span>
        </div>
    </div>
</form>

<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script>
    // ============================================================
    // Gestion des messages d'édition avec showEditMsg
    // Note : showEditMsg est définie dans la page parente
    // ============================================================

    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
        $("#bloc_4").hide();
        if (typeof mapEdit !== 'undefined' && mapEdit) {
            mapEdit.remove();
            mapEdit = null;
        }
        if (window.mapEditInterval) {
            clearInterval(window.mapEditInterval);
            window.mapEditInterval = null;
        }
        // Supprimer le message éventuel
        $('#edit_msg').html('').removeClass('msg-success msg-error msg-info');
    });

    $("#edit_save").click(function(e) {
        e.preventDefault();
        var nom = $("#edit_nom").val();
        var data = $("#form_edit").serialize();
        if (nom.trim().length == 0) {
            showEditMsg('error', '<i class="zmdi zmdi-close-circle"></i> Completez le nom', 9000);
        } else {
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/edit_client",
                data: data,
                success: function(response) {
                    // On conserve la carte, on ne la recrée pas
                    var currentLat = $('#edit_latitude').val();
                    var currentLng = $('#edit_longitude').val();

                    $("#edit_save").attr("disabled", false);
                    showEditMsg('success',
                        '<i class="zmdi zmdi-check-circle"></i> Client modifié avec succès',
                        9000);

                    // Recharger le contenu pour mettre à jour les autres champs
                    $("#content_utilisateur").html(response);

                    // Restaurer les valeurs de latitude/longitude
                    $('#edit_latitude').val(currentLat);
                    $('#edit_longitude').val(currentLng);

                    if (window.mapEditInterval) {
                        clearInterval(window.mapEditInterval);
                        window.mapEditInterval = null;
                    }

                    if (typeof filterClients === 'function') {
                        setTimeout(filterClients, 300);
                    }
                }
            });
        }
    });

    // ============================================================
    // Initialisation de la carte – avec l'icône par défaut
    // ============================================================
    (function initEditMap() {
        var defaultLat = -4.4419;
        var defaultLng = 15.2663;
        var defaultZoom = 13;

        var latVal = $('#edit_latitude').val().trim();
        var lngVal = $('#edit_longitude').val().trim();

        function isValidCoordinate(val) {
            if (val === '') return false;
            var num = parseFloat(val);
            return !isNaN(num) && num !== 0;
        }

        var hasCoordinates = isValidCoordinate(latVal) && isValidCoordinate(lngVal);
        var initialLat = hasCoordinates ? parseFloat(latVal) : defaultLat;
        var initialLng = hasCoordinates ? parseFloat(lngVal) : defaultLng;

        var container = document.getElementById('map_edit');
        if (!container) {
            console.warn("Conteneur map_edit introuvable");
            return;
        }

        function updateLocation(map, marker, lat, lng) {
            $('#edit_latitude').val(lat.toFixed(6));
            $('#edit_longitude').val(lng.toFixed(6));
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<strong>Position choisie</strong><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng
                    .toFixed(6));
            }
            map.setView([lat, lng], 15);
            return marker;
        }

        function createMap() {
    if (typeof mapEdit !== 'undefined' && mapEdit) {
        mapEdit.remove();
        mapEdit = null;
    }

    // Coordonnées par défaut (Kinshasa)
    var defaultLat = -4.4419;
    var defaultLng = 15.2663;
    var defaultZoom = 13;

    var latVal = $('#edit_latitude').val().trim();
    var lngVal = $('#edit_longitude').val().trim();

    function isValidCoordinate(val) {
        if (val === '') return false;
        var num = parseFloat(val);
        return !isNaN(num) && num !== 0;
    }

    var hasCoordinates = isValidCoordinate(latVal) && isValidCoordinate(lngVal);
    var initialLat = hasCoordinates ? parseFloat(latVal) : defaultLat;
    var initialLng = hasCoordinates ? parseFloat(lngVal) : defaultLng;
    var initialZoom = hasCoordinates ? 15 : defaultZoom;

    // Création de la carte centrée sur la position pertinente (coordonnées existantes ou défaut)
    mapEdit = L.map('map_edit').setView([initialLat, initialLng], initialZoom);

    var tileLayerClassic = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    });
    var tileLayerSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri'
    });

    var currentLayer = tileLayerClassic.addTo(mapEdit);
    var markerEdit = null;

    // ============================================================
    // 1. Si des coordonnées valides existent → afficher le marqueur
    // 2. Sinon, tenter la géolocalisation pour centrer sur l'utilisateur
    // ============================================================
    if (hasCoordinates) {
        // On place le marqueur
        markerEdit = L.marker([initialLat, initialLng]).addTo(mapEdit);
        markerEdit.bindPopup("<strong>Position actuelle</strong><br>Lat: " + initialLat.toFixed(6) + "<br>Lng: " + initialLng.toFixed(6));
        mapEdit.setView([initialLat, initialLng], 15);
        showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Position chargée', 3000);
    } else {
        // Pas de coordonnées → on tente de récupérer la position actuelle
        if (navigator.geolocation) {
            showEditMsg('info', '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Récupération de votre position...', 8000);
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    // On centre la carte sur la position, sans ajouter de marqueur
                    mapEdit.setView([lat, lng], 15);
                    showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Carte centrée sur votre position', 4000);
                    // Optionnel : pré-remplir les champs ? (on peut les laisser vides)
                    $('#edit_latitude').val(lat.toFixed(6));
                    $('#edit_longitude').val(lng.toFixed(6));
                },
                function(error) {
                    // Échec → on reste sur Kinshasa
                    var errMsg = error.message || "Erreur inconnue";
                    mapEdit.setView([defaultLat, defaultLng], defaultZoom);
                    showEditMsg('warning', '<i class="zmdi zmdi-alert-triangle"></i> Géolocalisation impossible : ' + errMsg + ' - Position par défaut', 6000);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            // Pas de géolocalisation → Kinshasa
            mapEdit.setView([defaultLat, defaultLng], defaultZoom);
            showEditMsg('info', '<i class="zmdi zmdi-info"></i> Géolocalisation non supportée - Position par défaut', 5000);
        }
    }

    // ============================================================
    // Événements (clic, boutons, etc.) – inchangés
    // ============================================================
    mapEdit.on('click', function(e) {
        markerEdit = updateLocation(mapEdit, markerEdit, e.latlng.lat, e.latlng.lng);
        showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Position choisie sur la carte', 3000);
    });

    $('#edit_latitude, #edit_longitude').off('change').on('change', function() {
        var lat = parseFloat($('#edit_latitude').val());
        var lng = parseFloat($('#edit_longitude').val());
        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            markerEdit = updateLocation(mapEdit, markerEdit, lat, lng);
        }
    });

    $("#btnCurrentLocationEdit").off('click').on('click', function(e) {
        e.preventDefault();
        if (!navigator.geolocation) {
            showEditMsg('error', '<i class="zmdi zmdi-close-circle"></i> Géolocalisation non supportée', 5000);
            return;
        }
        showEditMsg('info', '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Récupération...', 10000);
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                markerEdit = updateLocation(mapEdit, markerEdit, lat, lng);
                showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Position actuelle enregistrée', 4000);
            },
            function(error) {
                var errMsg = "";
                switch(error.code) {
                    case error.PERMISSION_DENIED: errMsg = "Permission refusée."; break;
                    case error.POSITION_UNAVAILABLE: errMsg = "Position indisponible."; break;
                    case error.TIMEOUT: errMsg = "Délai dépassé."; break;
                    default: errMsg = "Erreur inconnue.";
                }
                showEditMsg('error', '<i class="zmdi zmdi-close-circle"></i> ' + errMsg, 5000);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    $("#btnClassicEdit").off('click').on('click', function() {
        if (currentLayer) mapEdit.removeLayer(currentLayer);
        currentLayer = tileLayerClassic.addTo(mapEdit);
        showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Mode classique', 2000);
    });
    $("#btnSatelliteEdit").off('click').on('click', function() {
        if (currentLayer) mapEdit.removeLayer(currentLayer);
        currentLayer = tileLayerSatellite.addTo(mapEdit);
        showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Mode satellite', 2000);
    });
    $("#btnResetViewEdit").off('click').on('click', function() {
        mapEdit.setView([defaultLat, defaultLng], defaultZoom);
        showEditMsg('success', '<i class="zmdi zmdi-check-circle"></i> Vue réinitialisée', 2000);
    });

    setTimeout(function() { if (mapEdit) mapEdit.invalidateSize(); }, 300);
    setTimeout(function() { if (mapEdit) mapEdit.invalidateSize(); }, 600);
    setTimeout(function() { if (mapEdit) mapEdit.invalidateSize(); }, 1000);

    console.log("Carte créée avec succès");
}

        // Intervalle de vérification pour attendre que le conteneur soit visible
        var checkInterval = setInterval(function() {
            var rect = container.getBoundingClientRect();
            if (rect.height > 0 && rect.width > 0) {
                createMap();
                clearInterval(checkInterval);
                window.mapEditInterval = null;
            }
        }, 200);
        window.mapEditInterval = checkInterval;
    })();
</script>
