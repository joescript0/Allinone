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
    <p style="color:rgba(0, 0, 0, 0.6);" class="text-center"><a href="#"><img id="edit_user_img_profil" class="user__img" src="{{ asset($utilisateurs->image) }}" alt="" style="width: 100px; height: 100px; object-fit: cover;"></a></p>
    <!-- Barre de progression -->
    <div class="editprogress-container" style="display:none; margin-top: 10px;">
        <div class="editprogress-bar" style="width:0%; height:5px; background-color:#32c787; transition: width 0.3s;"></div>
        <span class="editprogress-text" style="font-size:12px;">0%</span>
    </div>

    <input type="file" name="edit_input_user_img_profil" id="edit_input_user_img_profil" style="display:none;">
                    <input type="text" name="edit_image" id="edit_image" value="<?= $utilisateurs->image ?>" style="display:none;">
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $utilisateurs->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="edit_nom" name="edit_nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $utilisateurs->name ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i> E-mail </span></label>
                <input type="text" id="edit_email" name="edit_email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Email (Ex : mgm@gmail.com)" value="<?= $utilisateurs->email ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i> Telephone </span></label>
                <input type="text" id="edit_phone" name="edit_phone" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Telephone (Ex : +243974743675)" value="<?= $utilisateurs->phone ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Role / Fonction </span></label>
                <select id="edit_role" name="edit_role" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @foreach ($groupes as $data)
                        @if ($data->id == $utilisateurs->role)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @else
                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Mot de passe </span></label>
                <input type="password" id="edit_mdp" name="edit_mdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Mot de passe" value="<?= $utilisateurs->mdp ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-lock"></i> Confirmez </span></label>
                <input type="password" id="edit_cmdp" name="edit_cmdp" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Confirmez" value="<?= $utilisateurs->mdp ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Salaire </span></label>
                <input type="text" id="edit_salaire" name="edit_salaire" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="input-mask form-control" data-mask="00000000000000000000000000000000000000" placeholder="Salaire" value="<?= $utilisateurs->salaire ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Devise</span></label>
                <select id="edit_devise" name="edit_devise" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @if ($utilisateurs->devise == 0)
                        <option selected class="form-control" value="0">USD</option>
                        <option class="form-control" value="1">CDF</option>
                    @endif
                    @if ($utilisateurs->devise == 1)
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-pin"></i> Poste </span></label>
                <select id="edit_poste_id" name="edit_poste_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @if ($utilisateurs->poste_id == 0)
                        <option selected class="form-control" value="0">Aucun</option>
                        @foreach ($postes as $data)
                            <option class="form-control" value="{{ $data->id }}">Nom : {{ $data->nom }}, Lieux : {{ Lieux::where(["id" => $data->lieuxe_id])->first()["nom"];}}.</option>
                        @endforeach
                    @else
                        <option class="form-control" value="0">Aucun</option>
                        @foreach ($postes as $data)
                            @if ($data->id == $utilisateurs->poste_id)
                                    <option selected class="form-control" value="{{ $data->id }}">Nom : {{ $data->nom }}, Lieux : {{ Lieux::where(["id" => $data->lieuxe_id])->first()["nom"];}}.</option>
                                @else
                                    <option selected class="form-control" value="{{ $data->id }}">Nom : {{ $data->nom }}, Lieux : {{ Lieux::where(["id" => $data->lieuxe_id])->first()["nom"];}}.</option>
                                @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-toll"></i> Activité </span></label>
                <select id="edit_activite_id" name="edit_activite_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @if (($utilisateurs->activite_id == 0) || (strlen(trim($utilisateurs->activite_id)) == 0))
                        <option selected class="form-control" value="0">Aucun</option>
                        @foreach ($activites as $data)
                            <option class="form-control" value="{{ $data->id }}">{{ $data->nom }}</option>
                        @endforeach
                    @else
                        <option class="form-control" value="0">Aucune</option>
                        @foreach ($activites as $data)
                            @if ($data->id == $utilisateurs->activite_id)
                                    <option selected class="form-control" value="{{ $data->id }}">{{ $data->nom }}</option>
                                @else
                                    <option class="form-control" value="{{ $data->id }}">{{ $data->nom }}</option>
                                @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button> <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
        var nom = $("#edit_nom").val();
        var email = $("#edit_email").val();
        var phone = $("#edit_phone").val();
        var role = $("#edit_role").val();
        var mdp = $("#edit_mdp").val();
        var cmdp = $("#edit_cmdp").val();
        var salaire = $("#edit_salaire").val();
        var data = $("#form_edit").serialize();
        if (nom.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        } else {
            if (email.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'adresse e-mail');
                $('#edit_msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (!regex.test(email)) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
                    $('#edit_msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#edit_msg').html("");
                    }, 9000);
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/check_email_utilisateur_1",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Cette adresse e-mail existe déjà');
                                $('#edit_msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#edit_msg').html("");
                                }, 9000);
                            } else {
                                if (phone.trim().length == 0) {
                                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le numero de telephone');
                                    $('#edit_msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#edit_msg').html("");
                                    }, 9000);
                                } else {
                                    if (!Number(phone.trim())) {
                                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez un bon numero de telephone');
                                        $('#edit_msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#edit_msg').html("");
                                        }, 9000);
                                    } else {
                                        $.ajax({
                                            type: "POST",
                                            url: "/check_phone_utilisateur_1",
                                            data: data,
                                            success: function(response) {
                                                if (response == 1) {
                                                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Ce numero de telephone existe déjà');
                                                    $('#edit_msg').css('color', "#ff6b68");
                                                    setTimeout(() => {
                                                        $('#edit_msg').html("");
                                                    }, 9000);
                                                } else {
                                                    if (role.trim().length == 0) {
                                                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le role');
                                                        $('#edit_msg').css('color', "#ff6b68");
                                                        setTimeout(() => {
                                                            $('#edit_msg').html("");
                                                        }, 9000);
                                                    } else {
                                                        if (mdp.trim().length == 0) {
                                                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le mot de passe');
                                                            $('#edit_msg').css('color', "#ff6b68");
                                                            setTimeout(() => {
                                                                $('#edit_msg').html("");
                                                            }, 9000);
                                                        } else {
                                                            if (cmdp.trim().length == 0) {
                                                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Confirmez le mot de passe');
                                                                $('#edit_msg').css('color', "#ff6b68");
                                                                setTimeout(() => {
                                                                    $('#edit_msg').html("");
                                                                }, 9000);
                                                            } else {
                                                                if (cmdp != mdp) {
                                                                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Incohérance de deux mot de passe');
                                                                    $('#edit_msg').css('color', "#ff6b68");
                                                                    setTimeout(() => {
                                                                        $('#edit_msg').html("");
                                                                    }, 9000);
                                                                } else {
                                                                    if(salaire.trim().length == 0)
                                                                    {
                                                                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le salaire');
                                                                        $('#edit_msg').css('color', "#ff6b68");
                                                                        setTimeout(() => {
                                                                            $('#edit_msg').html("");
                                                                        }, 9000);
                                                                    }else
                                                                    {
                                                                        $("#edit_save").attr("disabled", true);
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "/edit_utilisateur",
                                                                            data: data,
                                                                            success: function(response) {
                                                                                $("#edit_save").attr("disabled", false);
                                                                                $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Utilisateur modifié avec succès');
                                                                                $('#edit_msg').css("color", '#32c787');
                                                                                $("#content_utilisateur").html(response);
                                                                                setTimeout(() => {
                                                                                    $('#edit_msg').html("");
                                                                                }, 9000);
                                                                            }
                                                                        });
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    });
    $("#edit_user_img_profil").click(function(e){
         e.preventDefault();
         $("#edit_input_user_img_profil").trigger("click");
    });

    $("#edit_input_user_img_profil").change(function(e){
        e.preventDefault();
        var formData = new FormData();
        formData.append('edit_input_user_img_profil', $('#edit_input_user_img_profil')[0].files[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Ajout du token
        // $("#save").attr("disabled", true);
        // Afficher la barre de progression avant l'upload
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

                // Suivre la progression
                xhr.upload.addEventListener("editprogress", function(evt) {
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

                $('#edit_msg').html('Profil teléchargé avec succès');
                $('#edit_msg').css("color", '#32c787');
                $('#edit_user_img_profil').attr('src', response);
                $("#edit_image").val(response);
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            },
            error: function(xhr) {
                // $("#save").attr("disabled", false);
                $('.editprogress-container').hide();
                $('#edit_msg').html(xhr.responseJSON.message);
                $('#edit_msg').css("color", 'red');
            }
        });
    })
</script>
