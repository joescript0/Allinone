<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post">
    @csrf
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $invitations->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date invitation </span></label>
                <input id="edit_date_invitation" name="edit_date_invitation" type="text" class="form-control input-mask" data-mask="00/00/0000"  style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date invitation (Ex : <?= date("d/m/Y"); ?>)" value="<?= $invitations->date_invitation ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-alarm"></i> Heure invitation </span></label>
                <input id="edit_heure_invitation" name="edit_heure_invitation" type="text" class="form-control time-picker hidden-sm-down" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control input-mask" data-mask="00:00:00" placeholder="Heure invitation (Ex : <?= date("h:m:s"); ?>)" value="<?= $invitations->heure_invitation ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date document </span></label>
                <input id="edit_date_document" name="edit_date_document" type="text" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Date du document (Ex : <?= date("d/m/Y"); ?>)" value="<?= $invitations->date_document ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Verbalisateur </span></label>
                <select id="edit_verbalisateur" name="edit_verbalisateur" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @foreach ($verbalisateurs as $data)
                    @if ($data->id == $invitations->verbalisateur_id)
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Objet </span></label>
                <input type="text" id="edit_libelle" name="edit_libelle" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Objet (Ex : mgm)" value="<?= $invitations->libelle ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-edit"></i> Signer par </span></label>
                <input type="text" id="edit_signer" name="edit_signer" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Signer par" value="<?= $invitations->signer_par ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-library"></i> Statut </span></label>
                <input type="text" id="edit_statut" name="edit_statut" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Statut" value="<?= $invitations->statut ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Numero de l'invitation </span></label>
                <input type="text" id="edit_numero_invitation" name="edit_numero_invitation" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Numero de l'invitation" value="<?= $invitations->numero_invitation ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Description </span></label>
                <textarea id="edit_description" name="edit_description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description" cols="2" rows="2"><?= $invitations->description ?></textarea>
            </div>
        </div>
    </div>

</form>
<form action="" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-12">
            <?php

            use App\Models\Writes;
            use Illuminate\Support\Facades\Auth;

            if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $edit = 0;
                $delete = 0;
                $add = 0;
                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                    $add = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->add;
                }
                ?>
            <?php } ?>
            <?php if (($add == 1) || (Auth::user()->role == 0)) { ?>
                <button id="edit_save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button>
            <?php } else { ?>
                <button id="edit_save_r" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-save"></i></button>
            <?php } ?>
            <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
        var date_invitation = $("#edit_date_invitation").val();
        var heure_invitation = $("#edit_heure_invitation").val();
        var date_document = $("#edit_date_document").val();
        var verbalisateur = $("#edit_verbalisateur").val();
        var libelle = $("#edit_libelle").val();
        var description = $("#edit_description").val();
        var signer = $("#edit_signer").val();
        var statut = $("#edit_statut").val();
        var numero_invitation = $("#edit_numero_invitation").val();
        var data = $("#form_edit").serialize();
        if (date_invitation.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date de l\'invitation');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        } else {
            if (heure_invitation.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez l\'heure de l\'invitation');
                $('#edit_msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            } else {
                if (date_document.trim().length == 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date du document');
                    $('#edit_msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#edit_msg').html("");
                    }, 9000);
                } else {
                    if (verbalisateur.trim().length == 0) {
                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez le verbalisateur');
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        if (libelle.trim().length == 0) {
                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez le libelle');
                            $('#edit_msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#edit_msg').html("");
                            }, 9000);
                        } else {
                            if (signer.trim().length == 0) {
                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez la signature');
                                $('#edit_msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#edit_msg').html("");
                                }, 9000);
                            } else {
                                if (signer.trim().length == 0) {
                                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez le status');
                                    $('#edit_msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#edit_msg').html("");
                                    }, 9000);
                                } else {
                                    if (statut.trim().length == 0) {
                                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez le status');
                                        $('#edit_msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#edit_msg').html("");
                                        }, 9000);
                                    } else {
                                        if (numero_invitation.trim().length == 0) {
                                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> completez le numero de l\'invitation');
                                            $('#edit_msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#edit_msg').html("");
                                            }, 9000);
                                        } else {
                                            $("#edit_save").attr("disabled", true);
                                            $.ajax({
                                                type: "POST",
                                                url: "/edit_invitations",
                                                data: data,
                                                success: function(response) {
                                                    $("#edit_save").attr("disabled", false);
                                                    $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Invitation modifié avec succès');
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
            }
        }
    });
</script>
