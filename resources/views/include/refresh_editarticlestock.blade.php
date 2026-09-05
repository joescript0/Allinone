<?php

use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Articlestocks;
use Illuminate\Support\Facades\Auth;

// --- Calcul des champs selon le stock ---
$prixDetail  = $articles->prix_detail;
$prixGros    = $articles->prix_gros;
$tailleLot   = $articles->taille_lot;
$devise      = $articles->devise;
$avoirStock  = $articles->avoir_stock;

if ($stock_id != 0) {
    $stock = Articlestocks::where(['stock_id' => $stock_id, 'article_id' => $articles->id])->first();
    if ($stock) {
        $prixDetail  = $stock->prix_detail;
        $prixGros    = $stock->prix_gros;
        $tailleLot   = $stock->taille_lot;
        $devise      = $stock->devise;
        $avoirStock  = $stock->avoir_stock;
    }
}
// ----------------------------------------------
?>
<!-- Vendor styles -->
<link rel="stylesheet" href="{{ asset('assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/animate.css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/fullcalendar/fullcalendar.min.css') }}">
<link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/nouislider/nouislider.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendors/rateyo/jquery.rateyo.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('connexion/vendor/animate/animate.css') }}">
<!-- App styles -->
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select-multiple.css') }}">
<link rel="stylesheet" href="{{ asset('assets/demo/css/demo.css') }}">
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form style="padding-bottom: 100px;" id="form_edit" action="#" method="post">
    @csrf
    <!-- Champ ID caché -->
    <div style="display: none;" class="col-6">
        <div class="form-group">
            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                Nom </span></label>
            <input type="text" id="id" name="id"
                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                class="form-control" placeholder="ID" value="<?= $articles->id ?>">
                <input type="text" id="stock_id" name="stock_id"
                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                class="form-control" placeholder="STOCK_ID" value="<?= $stock_id ?>">
        </div>
    </div>

    <!-- LIGNE 1 : Catégorie + Nom -->
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Il
                    s'agit de quelle catégorie ?</span></label>
                <select id="edit_categorie_id" name="edit_categorie_id" class="form-control"
                    data-placeholder="Selectionnez une catégorie">
                    <option selected value="">Selectionnez une catégorie</option>
                    @foreach ($societes as $data)
                        @if ($data->id == $articles->societe_id)
                            <option selected value="{{ $data->id }}"><?= $data->nom ?></option>
                        @else
                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    Nom</span></label>
                <input id="edit_nom_article" name="edit_nom_article" type="text" class="form-control input-mask"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Nom (Ex : Eau pure)" value="<?= $articles->nom_article ?>">
            </div>
        </div>
    </div>

    <!-- LIGNE 2 : Prix détail + Prix gros -->
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Prix de détail </span></label>
                <input id="edit_prix_detail" name="edit_prix_detail" type="text" class="form-control input-mask"
                    data-mask="00000000000000000000000000000000000000"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Prix de détail (Ex : 10)" value="<?= $prixDetail ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Prix de gros </span></label>
                <input id="edit_prix_gros" name="edit_prix_gros" type="text" class="form-control input-mask"
                    data-mask="00000000000000000000000000000000000000"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Prix de gros (Ex : 10)" value="<?= $prixGros ?>">
            </div>
        </div>
    </div>

    <!-- LIGNE 3 : Taille lot + Devise -->
    <div style="margin-top: -20px;" class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Prix </span></label>
                <input id="edit_prix" name="edit_prix" type="text" class="form-control input-mask"
                    data-mask="00000000000000000000000000000000000000"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Prix (Ex : 10)" value="<?= $articles->prix ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    Taille du lot </span></label>
                <input id="edit_taille_lot" name="edit_taille_lot" type="text" class="form-control input-mask"
                    data-mask="00000000000000000000000000000000000000"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Taille du lot (Ex : 10)" value="<?= $tailleLot ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                    devise </span></label>
                <select id="edit_devise" name="edit_devise" class="form-control"
                    data-placeholder="Selectionnez une devise">
                    <option class="form-control" value="">Selectionnez une devise</option>
                    <option class="form-control" value="0" <?= $devise == 0 ? 'selected' : '' ?>> USD</option>
                    <option class="form-control" value="1" <?= $devise == 1 ? 'selected' : '' ?>> CDF</option>
                </select>
            </div>
        </div>
    </div>

    <!-- LIGNE 4 : Unité de Mesure + Type de stockage -->
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    Unité de Mesure </span></label>
                <select id="edit_mesure_id" name="edit_mesure_id" class="form-control"
                    data-placeholder="Selectionnez une mesure">
                    <option class="form-control" value="">Aucune</option>
                    @foreach ($mesures as $data)
                        @if ($data->id == $articles->mesure_id)
                            <option selected value="{{ $data->id }}"><?= $data->nom ?></option>
                        @else
                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i>
                    Selectionnez un type de stockage </span></label>
                <select id="edit_avoir_stock" name="edit_avoir_stock" class="form-control"
                    data-placeholder="Selectionnez un type de stockage">
                    <option class="form-control" value="1" <?= $avoirStock == 1 ? 'selected' : '' ?>>Déterminé</option>
                    <option class="form-control" value="0" <?= $avoirStock == 0 ? 'selected' : '' ?>>Indeterminé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- LIGNE 5 : Seuils (conditionnel) -->
    <div id="edit_seuilsGroup" style="display: none; margin-top: -20px;">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                        Seuil minimum </span></label>
                    <input id="edit_seuil_minimum" name="edit_seuil_minimum" type="text"
                        class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                        placeholder="Seuil minimum (Ex : 10)" value="<?= $articles->seuil_minimum ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i>
                        Seuil maximum </span></label>
                    <input id="edit_seuil_maximum" name="edit_seuil_maximum" type="text"
                        class="form-control input-mask" data-mask="00000000000000000000000000000000000000"
                        style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                        placeholder="Seuil maximum (Ex : 100)" value="<?= $articles->seuil_maximum ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- LIGNE 6 : Date expiration + Description -->
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-calendar"></i> Date d'expiration</span></label>
                <input id="edit_date_expiration" name="edit_date_expiration" type="text"
                    class="form-control input-mask" data-mask="00/00/0000"
                    style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                    placeholder="Date d'expiration (Ex : 00/00/0000)"
                    value="<?= $articles->date_expiration ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-comment"></i> Description </span></label>
                <textarea id="edit_libelle" name="edit_libelle"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Description" cols="2" rows="2"><?= $articles->description ?></textarea>
            </div>
        </div>
    </div>

    <!-- LIGNE 7 : Activités (seul, avec colonne vide) -->
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-toll"></i> Activites </span></label>
                <select id="edit_activite_id" name="edit_activite_id" class="form-control"
                    data-placeholder="Selectionnez une activité">
                    <option class="form-control" value="0">Aucune</option>
                    @foreach ($activites as $data)
                        @if ($data->id == $articles->activite_id)
                            <option selected value="{{ $data->id }}"><?= $data->nom ?></option>
                        @else
                            <option value="{{ $data->id }}"><?= $data->nom ?></option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <!-- colonne vide pour équilibre -->
        </div>
    </div>

    <!-- Boutons -->
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

<!-- App functions and actions -->
<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
    // Gestion de l'affichage conditionnel des seuils en édition
    function toggleEditSeuils() {
        var type = $('#edit_avoir_stock').val();
        if (type === '1') { // Déterminé
            $('#edit_seuilsGroup').slideDown(300);
            $('#edit_seuil_minimum').prop('disabled', false);
            $('#edit_seuil_maximum').prop('disabled', false);
        } else { // Indéterminé ou vide
            $('#edit_seuilsGroup').slideUp(300);
            $('#edit_seuil_minimum').prop('disabled', true);
            $('#edit_seuil_maximum').prop('disabled', true);
            // On vide les champs pour éviter des valeurs inutiles
            $('#edit_seuil_minimum').val('');
            $('#edit_seuil_maximum').val('');
        }
    }

    $(document).ready(function() {
        // Initialisation au chargement
        toggleEditSeuils();

        // Événement sur le changement du type de stockage
        $('#edit_avoir_stock').on('change', function() {
            toggleEditSeuils();
        });
    });

    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });

    // ========== SAUVEGARDE AVEC VALIDATION CONDITIONNELLE (identique à l'ajout) ==========
    $("#edit_save").click(function(e) {
        e.preventDefault();
        var categorie_id = $("#edit_categorie_id").val();
        var nom_article = $("#edit_nom_article").val();
        var prix = $("#edit_prix").val();
        var devise = $("#edit_devise").val();
        var seuil_minimum = $("#edit_seuil_minimum").val();
        var seuil_maximum = $("#edit_seuil_maximum").val();
        var date_expiration = $("#edit_date_expiration").val();
        var libelle = $("#edit_libelle").val();
        var mesure_id = $("#edit_mesure_id").val();
        var prix_detail = $("#edit_prix_detail").val();
        var prix_gros = $("#edit_prix_gros").val();
        var taille_lot = $("#edit_taille_lot").val();
        var activite_id = $("#edit_activite_id").val();
        var avoir_stock = $("#edit_avoir_stock").val();

        // ===== VÉRIFICATIONS OBLIGATOIRES =====
        if (categorie_id.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la catégorie de l\'article');
            setTimeout(() => { $('#edit_msg').html(""); }, 9000);
            return;
        }
        if (nom_article.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de l\'article');
            setTimeout(() => { $('#edit_msg').html(""); }, 9000);
            return;
        }
        // Vérification du nom (doublon)
        $.get("{{ url('/check_nom_article_1') }}", {
            nom: nom_article,
            id: "<?= $articles->id ?>",
        }, function(rep_nom) {
            if (rep_nom != 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Le nom de cette article existe déjà');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (prix.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de l\'article');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (prix_detail.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de détail de l\'article');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (prix_gros.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le prix de gros de l\'article');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (taille_lot.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la taille du lot de l\'article');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (devise.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez la devise de cette article');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (mesure_id.trim().length == 0) {
                // ===== UNITÉ DE MESURE OBLIGATOIRE =====
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez une mesure');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (avoir_stock.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un type de stockage');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }
            if (date_expiration.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date d\'expiration');
                setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                return;
            }

            // ===== VALIDATION CONDITIONNELLE DES SEUILS =====
            if (avoir_stock == '1') {
                if (seuil_minimum.trim().length == 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil minimum');
                    setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                    return;
                }
                if (seuil_minimum <= 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil minimum doit être supérieur à 0.');
                    setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                    return;
                }
                if (seuil_maximum.trim().length == 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le seuil maximum');
                    setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                    return;
                }
                if (seuil_maximum <= 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil maximum doit être supérieur à 0.');
                    setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                    return;
                }
            } else {
                // Type indéterminé : on force les seuils à 0
                $('#edit_seuil_minimum').val(0);
                $('#edit_seuil_maximum').val(0);
            }

            // Tout est valide, on envoie
            var data = $("#form_edit").serialize();
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/edit_article_stock",
                data: data,
                success: function(response) {
                    $("#edit_save").attr("disabled", false);
                    $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Article modifié avec succès');
                    // On recharge le tableau
                    setTimeout(() => {
                        $('#edit_msg').html("");
                        $.get("{{ url('/refresh_article_stock') }}", { stock_id: "<?= $stock_id ?>" }, function(liste_r) {
                            $("#bloc_1").hide(); $("#bloc_2").hide(); $("#bloc_3").show(); $("#bloc_3").html(liste_r);
                        });
                    }, 5000);
                    // Optionnel : retour à la liste après modification
                    // $("#bloc_1").show();
                    // $("#bloc_2").hide();
                    // $("#bloc_3").hide();
                },
                error: function() {
                    $("#edit_save").attr("disabled", false);
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de la modification');
                    setTimeout(() => { $('#edit_msg').html(""); }, 9000);
                }
            });
        });
    });
</script>