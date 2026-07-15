<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>

<form id="form_edit" action="#" method="post">
    @csrf
    <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $verbalisateurs->id ?>">
            </div>
        </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom </span></label>
                <input type="text" id="edit_nom" name="edit_nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Admin)" value="<?= $verbalisateurs->nom ?>">
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
        var data = $("#form_edit").serialize();
        if (nom.trim().length == 0) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom');
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg').html("");
            }, 9000);
        } else {
            $.ajax({
                type: "POST",
                url: "/check_verbalisateur_1",
                data: data,
                success: function(response) {
                    if (response == 1) {
                        $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> Ce contrevenant existe déjà');
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        $("#edit_save").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "/edit_verbalisateur",
                            data: data,
                            success: function(response) {
                                $("#edit_save").attr("disabled", false);
                                $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> verbalisateur modifié avec succès');
                                $('#edit_msg').css("color", '#32c787');
                                $("#content_groupe").html(response);
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
