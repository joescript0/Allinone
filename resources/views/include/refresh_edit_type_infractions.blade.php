<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>

<form id="form_edit" action="#" method="post">
    @csrf
    <div style="display: none;" class="col-6">
        <div class="form-group">
            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
            <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $type_infractions->id ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom </span></label>
                <input type="text" id="edit_nom" name="edit_nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Libelle (Ex : MGM)" value="<?= $type_infractions->nom ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-cast-connected"></i> Code</span></label>
                <input type="text" id="edit_code" name="edit_code" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Code compatable (Ex : 001)" value="<?= $type_infractions->code ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-comment"></i> Description </span></label>
                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Description" name="edit_description" id="edit_description" cols="10" rows="2"><?= $type_infractions->description ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info btn-sm">Enregister <i class="zmdi zmdi-save"></i></button> <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
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
        var code = $("#edit_code").val();
        var data = $("#form_edit").serialize();
        if (nom.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        } else {
            $.ajax({
                type: "POST",
                url: "/check_type_infractions_1",
                data: data,
                success: function(response) {
                    if (response == 1) {
                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Ce type d\'infraction existe déjà');
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        if (code.trim().length == 0) {
                            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le code');
                            $('#edit_msg').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#edit_msg').html("");
                            }, 9000);
                        } else {
                            if (!Number(code)) {
                                $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez un bon code comptable');
                                $('#edit_msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#edit_msg').html("");
                                }, 9000);
                            } else {
                                $("#edit_save").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "/edit_type_infractions",
                                    data: data,
                                    success: function(response) {
                                        $("#edit_save").attr("disabled", false);
                                        $("#nom").val("");
                                        $("#code").val("");
                                        $("#description").val("");
                                        $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Type d\'infraction modifié avec succès');
                                        $('#edit_msg').css("color", '#32c787');
                                        $("#content_groupe").html(response);
                                        setTimeout(() => {
                                            $('#edit_msg').html("");
                                        }, 9000);
                                    }
                                });
                            }
                        }
                    }
                }
            });
        }
    });
</script>
