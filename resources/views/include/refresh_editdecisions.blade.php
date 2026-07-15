<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post">
    @csrf
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $decisions->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom du projet </span></label>
                <input id="edit_nom_projet" name="edit_nom_projet" type="text" class="form-control" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom du projet (Ex : Construction école)" value="<?= $decisions->nom_projet ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Date de création </span></label>
                <input id="edit_date_creation" name="edit_date_creation" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)" value="<?= $decisions->date_creation ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Budget </span></label>
                <input id="edit_budget" name="edit_budget" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Budget (Ex : 10000)" value="<?= $decisions->budget ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nombre de personne </span></label>
                <input id="edit_nombre_personne" name="edit_nombre_personne" type="text" class="form-control input-mask" data-mask="00000000000000000000000000000000000000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nombre de personne (Ex : 10)" value="<?= $decisions->nombre_personne ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Début </span></label>
                <input id="edit_debut" name="edit_debut" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)" value="<?= $decisions->debut ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-calendar"></i> Fin</span></label>
                <input id="edit_fin" name="edit_fin" type="text" class="form-control input-mask" data-mask="00/00/0000" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="Date de création (Ex : <?= date("d/m/Y"); ?>)" value="<?= $decisions->fin ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Description </span></label>
                <textarea id="edit_description" name="edit_description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description de l'infraction" cols="2" rows="2"><?= $decisions->description ?></textarea>
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
        var nom_projet = $("#edit_nom_projet").val();
        var date_creation = $("#edit_date_creation").val();
        var budget = $("#edit_budget").val();
        var nombre_personne = $("#edit_nombre_personne").val();
        var debut = $("#edit_debut").val();
        var fin = $("#edit_fin").val();
        var description = $("#edit_description").val();
        var data = $("#form_edit").serialize();
        if (nom_projet.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom du projet');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        } else {
            if (date_creation.trim().length == 0) {
                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la date de création du projet');
                $('#edit_msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#edit_msg').html("");
                }, 9000);
            } else {
                if (budget.trim().length == 0) {
                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le budget du projet');
                    $('#edit_msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#edit_msg').html("");
                    }, 9000);
                } else {
                    if (budget <= 0) {
                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du budget de ce projet');
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        if (nombre_personne.trim().length == 0) {
                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nombre de personne qui sont sur ce projet');
                            $('#edit_msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#edit_msg').html("");
                            }, 9000);
                        } else {
                            if (nombre_personne <= 0) {
                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Entrez une bonne valeur du nombre de personne qui sont sur ce projet');
                                $('#edit_msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#edit_msg').html("");
                                }, 9000);
                            } else {
                                if (debut.trim().length == 0) {
                                    $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le début de ce projet');
                                    $('#edit_msg').css('color', "#ff6b68");
                                    setTimeout(() => {
                                        $('#edit_msg').html("");
                                    }, 9000);
                                } else {
                                    if (fin.trim().length == 0) {
                                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la fin de ce projet');
                                        $('#edit_msg').css('color', "#ff6b68");
                                        setTimeout(() => {
                                            $('#edit_msg').html("");
                                        }, 9000);
                                    } else {
                                        if (fin <= debut) {
                                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> La date de fin dois être superieur à date de début');
                                            $('#edit_msg').css('color', "#ff6b68");
                                            setTimeout(() => {
                                                $('#edit_msg').html("");
                                            }, 9000);
                                        } else {
                                            if (description.trim().length == 0) {
                                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez la description de ce projet');
                                                $('#edit_msg').css('color', "#ff6b68");
                                                setTimeout(() => {
                                                    $('#edit_msg').html("");
                                                }, 9000);
                                            } else {
                                                $("#edit_save").attr("disabled", true);
                                                $.ajax({
                                                    type: "POST",
                                                    url: "/edit_decisions",
                                                    data: data,
                                                    success: function(response) {
                                                        $("#edit_save").attr("disabled", false);
                                                        $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> projet modifié avec succès');
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
        }
    });
</script>
