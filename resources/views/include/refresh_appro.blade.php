<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post">
    @csrf
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
                <input type="text" id="edit_email" name="edit_email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="email (Ex : mgm@gmail.com)" value="<?= $utilisateurs->email ?>">
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Role </span></label>
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
    <div class="row">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info">Modifier <i class="zmdi zmdi-edit"></i></button> <button id="edit_annuler" class="btn btn-danger">Annuler <i class="zmdi zmdi-close-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <span style="font-weight: bold;" id="edit_msg">
            </span>
        </div>
    </div>
</form>
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
</script>
