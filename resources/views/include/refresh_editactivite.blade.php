<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf
    <p style="color:rgba(0, 0, 0, 0.6);" class="text-center"><a href="#"><img id="edit_user_img_profil"
                class="user__img" src="{{ asset($activites->logo) }}" alt=""
                style="width: 100px; height: 100px; object-fit: cover;"></a></p>
    <!-- Barre de progression -->
    <div class="editprogress-container" style="display:none; margin-top: 10px;">
        <div class="editprogress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;">
        </div>
        <span class="editprogress-text" style="font-size:12px;">0%</span>
    </div>

    <input type="file" name="edit_input_user_img_profil" id="edit_input_user_img_profil" style="display:none;">
    <input type="text" name="edit_image" id="edit_image" value="<?= $activites->logo ?>" style="display:none;">
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                    Nom </span> <span style="color:red;">*</span></label>
                <input type="text" id="id" name="id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $activites->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                    Nom </span> <span style="color:red;">*</span></label>
                <input type="text" id="edit_nom" name="edit_nom"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Noryang)" value="<?= $activites->nom ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i>
                    Description </span></label>
                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Description" name="edit_description" id="edit_description" cols="2"
                    rows="1"><?= $activites->description ?></textarea>
            </div>
        </div>
    </div>

    <!-- NOUVEAUX CHAMPS : Taux facture et TVA (obligatoires, entiers) -->
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Taux facture (CDF) <span style="color:red;">*</span></label>
                <input type="number" id="edit_taux_facture" name="edit_taux_facture" class="form-control" placeholder="Ex: 2200" step="1" min="0" value="<?= $activites->taux ?>" required>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-percent"></i> TVA (%) <span style="color:red;">*</span></label>
                <input type="number" id="edit_tva" name="edit_tva" class="form-control" placeholder="Ex: 16" step="1" min="0" value="<?= $activites->tva ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button> <button
                id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });

    $("#edit_save").click(function(e) {
        e.preventDefault();

        var nom = $("#edit_nom").val().trim();
        var description = $("#edit_description").val();
        var taux_facture = $("#edit_taux_facture").val();
        var tva = $("#edit_tva").val();

        // Validation du nom
        if (nom.length === 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez saisir le nom');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
            return;
        }

        // Validation du taux facture : obligatoire et entier >= 0
        if (taux_facture === '' || isNaN(taux_facture) || !Number.isInteger(parseFloat(taux_facture)) || parseFloat(taux_facture) < 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Le taux facture est obligatoire et doit être un nombre entier (ex: 2200)');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
            return;
        }

        // Validation de la TVA : obligatoire et entier >= 0
        if (tva === '' || isNaN(tva) || !Number.isInteger(parseFloat(tva)) || parseFloat(tva) < 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> La TVA est obligatoire et doit être un nombre entier (ex: 16)');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
            return;
        }

        // Vérification de l'unicité du nom (hors activité en cours)
        var data = $("#form_edit").serialize();
        $.get("{{ url('/check_edit_nom_activiter') }}", {
            id: "{{ $activites->id }}",
            nom: nom,
        }, function(rep) {
            if (rep != 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Cette activité existe déjà');
                $('#edit_msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            } else {
                $("#edit_save").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "/edit_activiter",
                    data: data,
                    success: function(response) {
                        $("#edit_save").attr("disabled", false);
                        $('#edit_msg').html(
                            '<i class="zmdi zmdi-check-circle"></i> Activité modifiée avec succès'
                            );
                        $('#edit_msg').css("color", '#32c787');
                        $("#bloc_1").html(response);
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    }
                });
            }
        });
    });

    $("#edit_user_img_profil").click(function(e) {
        e.preventDefault();
        $("#edit_input_user_img_profil").trigger("click");
    });

    $("#edit_input_user_img_profil").change(function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('edit_input_user_img_profil', $('#edit_input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('.editprogress-container').show();
        $('.editprogress-bar').css('width', '0%');
        $('.editprogress-text').text('0%');

        $.ajax({
            type: "POST",
            url: "/upload_logo_edit",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('.editprogress-bar').css('width', percentComplete + '%');
                        $('.editprogress-text').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                $('.editprogress-bar').css('width', '100%');
                $('.editprogress-text').text('100%');
                setTimeout(function() {
                    $('.editprogress-container').hide();
                }, 1000);

                $('#edit_msg').html('Logo téléchargé avec succès');
                $('#edit_msg').css("color", '#32c787');
                $('#edit_user_img_profil').attr('src', response);
                $("#edit_image").val(response);
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            },
            error: function(xhr) {
                $('.editprogress-container').hide();
                $('#edit_msg').html(xhr.responseJSON?.message || 'Erreur lors de l\'upload');
                $('#edit_msg').css("color", 'red');
            }
        });
    });
</script>