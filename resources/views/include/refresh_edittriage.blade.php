<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>

<style>
    /* ============================================================
       STYLES DU FORMULAIRE (Calqué sur la capture d'écran)
       ============================================================ */
    .custom-form-group label {
        color: #00b0ff !important; /* Le bleu cyan vif de votre image */
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase; /* Tout en majuscules */
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .custom-form-group label i {
        color: #ef4444 !important; /* Icônes rouges */
        margin-right: 6px;
        font-size: 14px;
    }

    .custom-form-group label small {
        text-transform: none;
        font-weight: 400;
        color: #6c757d;
        margin-left: 4px;
    }

    /* Cible les inputs ET les selects */
    .custom-form-group .form-control,
    .custom-form-group select {
        font-weight: 400;
        font-size: 14px;
        color: #4a5568;
        border-radius: 8px; /* Bords arrondis */
        padding: 11px 15px;
        border: 1px solid #e2e8f0; /* Gris très clair */
        width: 100%;
        box-sizing: border-box;
        background-color: #fff;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        height: auto !important;
    }

    .custom-form-group .form-control:focus,
    .custom-form-group select:focus {
        border-color: #00b0ff;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(0, 176, 255, 0.1);
    }

    .custom-form-group .form-control::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    /* Supprime la flèche par défaut du select */
    .custom-form-group select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 20px;
        padding-right: 40px;
    }

    .custom-form-group .form-control[readonly] {
        background: #f8fafc;
        cursor: not-allowed;
    }

    /* Espacement entre les lignes */
    .form-row-spacing {
        margin-bottom: 20px;
    }

    /* Image responsive */
    .profile-img-container {
        text-align: center;
        margin-bottom: 25px;
    }
    .profile-img-container img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #f1f1f1;
    }

    /* ============================================================
       DESIGN DES MESSAGES (SUCCÈS / ERREUR)
       ============================================================ */
    .custom-success-badge {
        display: inline-flex;
        align-items: center;
        background-color: #d1fae5;
        color: #065f46;
        padding: 8px 24px 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        border-left: 6px solid #10b981;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        animation: slideDownFade 0.3s ease-out;
    }
    .custom-success-badge i {
        margin-right: 8px;
        font-size: 18px;
        color: #059669;
    }

    .custom-error-badge {
        display: inline-flex;
        align-items: center;
        background-color: #fee2e2;
        color: #991b1b;
        padding: 8px 24px 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        border-left: 6px solid #ef4444;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        animation: slideDownFade 0.3s ease-out;
    }
    .custom-error-badge i {
        margin-right: 8px;
        font-size: 18px;
        color: #dc2626;
    }

    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<h4 style="color:rgba(0, 0, 0, 0.6); margin-bottom: 20px;">
    <i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier
</h4>

<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf

    <div class="profile-img-container">
        <a href="#">
            <img id="edit_user_img_profil" class="user__img"
                 src="{{ !empty($patient->image) ? asset($patient->image) : asset('storage/images/user/profil_defaut.png') }}"
                 alt="Profil">
        </a>
    </div>

    <!-- Barre de progression -->
    <div class="editprogress-container" style="display:none; margin-top: 10px; text-align: center;">
        <div style="background: #eee; border-radius: 5px; overflow: hidden; width: 100%; max-width: 300px; margin: 0 auto;">
            <div class="editprogress-bar" style="width:0%; height:8px; background-color:#32c787; transition: width 0.3s;"></div>
        </div>
        <span class="editprogress-text" style="font-size:12px; display: block; margin-top: 5px;">0%</span>
    </div>

    <input type="file" name="edit_input_user_img_profil" id="edit_input_user_img_profil" style="display:none;">
    <input type="text" name="edit_image" id="edit_image" value="<?= $patient->image ?>" style="display:none;">

    {{-- ⚠️ Mot de passe conservé en caché (valeur actuelle) --}}
    <input type="hidden" id="edit_mdp"  name="edit_mdp"  value="<?= $patient->mdp ?>">
    <input type="hidden" id="edit_cmdp" name="edit_cmdp" value="<?= $patient->mdp ?>">

    <!-- Première ligne : Nom et Email -->
    <div class="row form-row-spacing">
        <div style="display: none;" class="col-12">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-account text-danger"></i> ID </label>
                <input type="text" id="id" name="id" class="form-control" value="<?= $patient->id ?>">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-account text-danger"></i> Nom complet </label>
                <input type="text" id="edit_nom" name="edit_nom" class="form-control" placeholder="Nom (Ex : Jean Kabila)" value="<?= $patient->name ?>">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-email text-danger"></i> E-mail </label>
                <input type="email" id="edit_email" name="edit_email" class="form-control" placeholder="Email (Ex : patient@gmail.com)" value="<?= $patient->email ?>">
            </div>
        </div>
    </div>

    <!-- Deuxième ligne : Téléphone et Nature -->
    <div class="row form-row-spacing">
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-phone text-danger"></i> Téléphone <small>(optionnel)</small> </label>
                <input type="text" id="edit_phone" name="edit_phone" class="form-control" placeholder="Téléphone (Ex : +243974743675)" value="<?= $patient->phone ?>">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-city-alt text-danger"></i> Nature </label>
                <select id="edit_type" name="edit_type" class="form-control">
                    @if ($patient->type == 0)
                        <option selected value="0">Privé</option>
                        <option value="1">Entreprise</option>
                    @else
                        <option value="0">Privé</option>
                        <option selected value="1">Entreprise</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <!-- Troisième ligne : Date de naissance et Code patient -->
    <div class="row form-row-spacing">
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-calendar-note text-danger"></i> Date de naissance </label>
                <?php
                    $dateNaissanceValue = !empty($patient->date_de_naissance)
                        ? \Carbon\Carbon::parse($patient->date_de_naissance)->format('Y-m-d')
                        : '';
                    $dateNaissanceDisplay = !empty($patient->date_de_naissance)
                        ? \Carbon\Carbon::parse($patient->date_de_naissance)->format('d/m/Y')
                        : '';
                ?>
                <input type="hidden" id="edit_date_naissance" name="edit_date_naissance" value="<?= $dateNaissanceValue ?>">
                <input type="text" id="edit_date_naissance_picker" class="form-control flatpickr-input"
                       placeholder="Sélectionner la date de naissance"
                       value="<?= $dateNaissanceDisplay ?>" readonly>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group custom-form-group">
                <label class="text-info"><i class="zmdi zmdi-label text-danger"></i> Code patient </label>
                <input type="text" id="edit_code" name="edit_code" class="form-control" value="<?= $patient->code ?>" readonly>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row" style="gap: 15px;">
                <button type="button" id="edit_save" class="btn btn-info btn-sm mb-2 mb-sm-0" style="padding: 10px 20px; font-weight: bold;">
                    Modifier <i class="zmdi zmdi-edit"></i>
                </button>
                <button type="button" id="edit_annuler" class="btn btn-danger btn-sm" style="padding: 10px 20px; font-weight: bold;">
                    Annuler <i class="zmdi zmdi-close-circle"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Zone de message -->
    <div class="text-center" style="min-height: 50px; margin-top: 20px;">
        <span id="edit_msg"></span>
    </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
    /* ============================================================
       FLATPICKR : DATE DE NAISSANCE
       ============================================================ */
    (function initEditDateNaissance() {
        if (typeof flatpickr === 'undefined') return;
        if (flatpickr.l10ns && flatpickr.l10ns.fr) flatpickr.localize(flatpickr.l10ns.fr);

        var initialDate = $("#edit_date_naissance").val() || null;

        flatpickr("#edit_date_naissance_picker", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            maxDate: "today",
            disableMobile: true,
            allowInput: false,
            defaultDate: initialDate,
            onChange: function(d, s) {
                $("#edit_date_naissance").val(s);
            }
        });
    })();

    /* ============================================================
       ANNULER
       ============================================================ */
    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });

    /* ============================================================
       ENREGISTRER LES MODIFICATIONS
       ============================================================ */
    $("#edit_save").click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var originalHtml = 'Modifier <i class="zmdi zmdi-edit"></i>';

        if (btn.data('loading')) return;

        var nom    = $("#edit_nom").val();
        var email  = $("#edit_email").val();
        var phone  = $("#edit_phone").val();
        var page   = "<?= $ressource_id_1 ?>";
        var formData = $("#form_edit").serialize() + "&page=" + encodeURIComponent(page);

        /* ---- Validations ---- */
        if (nom.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Complétez le nom')
                           .removeClass('custom-success-badge').addClass('custom-error-badge');
            setTimeout(() => { $('#edit_msg').html("").removeClass('custom-error-badge'); }, 9000);
            return;
        }

        if (email.trim().length > 0) {
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!regex.test(email)) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide')
                               .removeClass('custom-success-badge').addClass('custom-error-badge');
                setTimeout(() => { $('#edit_msg').html("").removeClass('custom-error-badge'); }, 9000);
                return;
            }
        }

        /* État de chargement */
        btn.data('loading', true).prop('disabled', true)
           .html('<span class="spinner-border spinner-border-sm" style="width:.9rem;height:.9rem;border-width:.15em;"></span> Modification...');

        function resetBtn() {
            btn.data('loading', false).prop('disabled', false).html(originalHtml);
        }

        $.ajax({
            type: "POST",
            url: "/edit_triage",
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                resetBtn();

                /* ✅ AFFICHER LE MESSAGE DE SUCCÈS */
                $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Patient modifié avec succès')
                               .removeClass('custom-error-badge').addClass('custom-success-badge');

                /* Rafraîchir la liste */
                if (typeof response === 'string' && response.indexOf('<') !== -1) {
                    $("#content_utilisateur").html(response);
                }

                if (typeof savePatientFiltersToStorage === 'function') {
                    savePatientFiltersToStorage();
                }
                setTimeout(function() {
                    if (typeof loadPatientFiltersFromStorage === 'function') loadPatientFiltersFromStorage();
                    if (typeof filterPatients === 'function') filterPatients();
                }, 100);

                setTimeout(function() { $('#edit_msg').html("").removeClass('custom-success-badge'); }, 9000);
            },
            error: function(xhr) {
                resetBtn();
                var msg = 'Erreur lors de la modification';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;

                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + msg)
                               .removeClass('custom-success-badge').addClass('custom-error-badge');
                setTimeout(function() { $('#edit_msg').html("").removeClass('custom-error-badge'); }, 9000);
            }
        });
    });

    /* ============================================================
       UPLOAD IMAGE
       ============================================================ */
    $("#edit_user_img_profil").click(function(e){
        e.preventDefault();
        $("#edit_input_user_img_profil").trigger("click");
    });

    $("#edit_input_user_img_profil").change(function(e){
        e.preventDefault();
        var formData = new FormData();
        formData.append('edit_input_user_img_profil', $('#edit_input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $('.editprogress-container').show();
        $('.editprogress-bar').css('width', '0%');
        $('.editprogress-text').text('0%');

        $.ajax({
            type: "POST",
            url: "/upload_profil_edit",
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
                setTimeout(function() { $('.editprogress-container').hide(); }, 1000);

                $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Profil téléchargé avec succès')
                               .removeClass('custom-error-badge').addClass('custom-success-badge');

                $('#edit_user_img_profil').attr('src', response);
                $("#edit_image").val(response);
                setTimeout(() => { $('#edit_msg').html("").removeClass('custom-success-badge'); }, 9000);
            },
            error: function(xhr) {
                $('.editprogress-container').hide();
                var errMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erreur upload';

                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + errMsg)
                               .removeClass('custom-success-badge').addClass('custom-error-badge');
                setTimeout(() => { $('#edit_msg').html("").removeClass('custom-error-badge'); }, 9000);
            }
        });
    });
</script>
