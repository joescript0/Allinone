<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="id" name="id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mr ILUNGA KASONGO Heritier, Kamoa etc...)" value="<?= $clients->id ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i> Nom </span></label>
                <input type="text" id="edit_nom" name="edit_nom" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $clients->name ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-email"></i> E-mail </span></label>
                <input type="text" id="edit_email" name="edit_email" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Email (Ex : mgm@gmail.com)" value="<?= $clients->email ?>">
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-phone"></i> Telephone </span></label>
                <input type="text" id="edit_phone" name="edit_phone" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Telephone (Ex : +243974743675)" value="<?= $clients->phone ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Type de client</span></label>
                <select id="edit_type_edit" name="edit_type_client" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Paiment </span></label>
                <input type="text" id="edit_paiement" name="edit_paiement" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="input-mask form-control" data-mask="00000000000000000000000000000000000000" placeholder="Paiement" value="<?= $clients->paiement ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Devise</span></label>
                <select id="edit_devise" name="edit_devise" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
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
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-settings"></i> Activité </span></label>
                <select id="edit_activite_id" name="edit_activite_id" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
                    @foreach ($activites as $data)
                        @if ($data->id == $clients->activite_id)
                            <option selected class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @else
                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
         <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                        class="zmdi zmdi-map"></i> Adresse </span></label>
                <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Adresse" name="edit_adresse" id="edit_adresse" cols="2" rows="1"><?= $clients->adresse ?></textarea>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Description </span></label>
                <input type="text" id="edit_description" name="edit_description" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control" placeholder="Nom (Ex : VIDAGE POUBELLE)" value="<?= $clients->description ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-money"></i> Modele de facture </span></label>
                <select id="edit_facture" name="edit_facture" style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" class="form-control">
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
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/edit_client",
                data: data,
                success: function(response) {
                    $("#edit_save").attr("disabled", false);
                    $('#edit_msg').html('<i class="zmdi zmdi-check-circle"></i> Client modifié avec succès');
                    $('#edit_msg').css("color", '#32c787');
                    $("#content_utilisateur").html(response);
                    setTimeout(() => {
                        $('#edit_msg').html("");
                    }, 9000);
                }
            });
        }
    });
</script>
