<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Listesfactures;
use App\Models\Mois;
use App\Models\Annees;
use App\Models\Lieux;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Utilisateurs;
use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Paies;
use App\Models\Paiements;
use App\Models\Paiementsfactures;
use App\Models\Paiesfactures;
use App\Models\Clients;

?>
<div class="row">
    <div class="col-12">
        <form id="add_programmme" action="#" method="post">
            @csrf
            <div>
                <div style="margin-top: 30px;" id="content_groupe" class="row">
                    <input type="hidden" id="poste_id" name="poste_id" value="{{ $postes['id'] }}">
                    <div style="margin-top: -30px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-calendar"></i> Année </span></label>
                            <select id="annee_id" name="annee_id" class="select2"
                                data-placeholder="Selectionnez une année">
                                <option selected value="">Selectionnez une année</option>
                                @foreach ($annees as $data)
                                <option value="{{ $data->id }}"><?= $data->annees ?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-calendar"></i> Mois </span></label>
                            <select id="moi_id" name="moi_id" class="select2" data-placeholder="Selectionnez un mois">
                                <option selected value="">Selectionnez un mois</option>
                                @foreach ($mois as $data)
                                <option value="{{ $data->id }}"><?= $data->nom ?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-calendar"></i> Type prestation </span></label>
                            <select id="type_prestation" name="type_prestation" class="select2"
                                data-placeholder="Selectionnez un type de prestation">
                                <option selected value="">Selectionnez un type de prestation</option>
                                <option value="1">Journée</option>
                                <option value="3">Journée / Nuit</option>
                                <option value="2">Nuit</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-calendar"></i> Type de rotation au poste </span></label>
                            <select id="type_de_rotation" name="type_de_rotation" class="select2"
                                data-placeholder="Type de rotation au poste">
                                <option selected value="">Type de rotation au poste</option>
                                <?php for ($i=0; $i < 7 ; $i++) {?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-calendar"></i> Nombre de jour </span></label>
                            <select id="nombre_de_jour" name="nombre_de_jour" class="select2"
                                data-placeholder="Selectionnez le nombre de jour">
                                <option selected value="">Selectionnez le nombre de jour</option>
                                <?php for ($i=1; $i <= 31 ; $i++) {?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -40px;" class="col-lg-2 col-sm-2">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-timer"></i> Date </span></label>
                            <input id="date_debut" name="date_debut" type="text" class="form-control input-mask"
                                data-mask="00/00/0000"
                                style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                placeholder="Date d'expiration (Ex : <?= ("00/00/0000") ?>)"
                                value="<?= "00/00/0000" ?>">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px;" class="row">
                    <div class="col-lg-12 col-sm-12">
                        <button id="save_t" class="btn btn-info btn-sm">Ajouter <i
                                class="zmdi zmdi-plus-circle"></i></button>
                    </div>
                </div>
                <div style="margin-top: 20px;" class="row">
                    <div class="col-lg-12" style="text-align: center;">
                        <span style="font-weight: bold;" id="msg_r"></span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-12">
        <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">PROGRAMME DE
            PRESTATION DU POSTE
            {{ strtoupper($postes['nom']) }} DE
            {{ strtoupper(Lieux::where(["id" => $postes['lieuxe_id']])->first()["nom"]); }}
    </div>
</div>
<div class="row" style="padding-bottom: 50px;">
    <div id="bloc_programme" style="margin-top: 12px;" class="col-lg-12">

    </div>
</div>
<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendors/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
$("#annuler_r").click(function(e) {
    e.preventDefault();
    $("#bloc_1").show();
    $("#bloc_2").hide();
    $("#bloc_3").hide();
});

$("#save_t").click(function(e) {
    e.preventDefault();

    // Récupération des champs
    var annee_id = $("#annee_id").val();
    var moi_id = $("#moi_id").val();
    var type_prestation = $("#type_prestation").val();
    var type_de_rotation = $("#type_de_rotation").val();
    var nombre_de_jour = $("#nombre_de_jour").val();
    var date_debut = $("#date_debut").val();
    var poste_id = $("#poste_id").val();

    var data = $("#add_programmme").serializeArray();

    // Validation en cascade (comme dans le code original)
    if (annee_id.trim().length == 0) {
        $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez d\'abord une année');
        $('#msg_r').css("color", "#ff6b68");
        setTimeout(() => { $('#msg_r').html(""); }, 9000);
    } else {
        if (moi_id.trim().length == 0) {
            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez le mois');
            $('#msg_r').css("color", "#ff6b68");
            setTimeout(() => { $('#msg_r').html(""); }, 9000);
        } else {
            if (type_prestation.trim().length == 0) {
                $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Veuillez renseigner le type de prestation');
                $('#msg_r').css("color", "#ff6b68");
                setTimeout(() => { $('#msg_r').html(""); }, 9000);
            } else {
                if (type_de_rotation.trim().length == 0) {
                    $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Veuillez renseigner le type de rotation au poste');
                    $('#msg_r').css("color", "#ff6b68");
                    setTimeout(() => { $('#msg_r').html(""); }, 9000);
                } else {
                    if (nombre_de_jour.trim().length == 0) {
                        $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Veuillez renseigner le nombre de jours');
                        $('#msg_r').css("color", "#ff6b68");
                        setTimeout(() => { $('#msg_r').html(""); }, 9000);
                    } else {
                        if (date_debut.trim().length == 0) 
                        {
                            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Veuillez renseigner la date de début');
                            $('#msg_r').css("color", "#ff6b68");
                            setTimeout(() => { $('#msg_r').html(""); }, 9000);
                        } else {
                            // Tous les champs sont valides
                            $("#save_t").attr("disabled", true);

                            $.ajax({
                                type: "POST",
                                url: "/add_prestation",  // ← à adapter
                                data: data,
                                success: function(response) {
                                    get_refresh_programme();
                                    get_mois_where_not_in_prestation();
                                    // Affichage du message de succès (logique originale)
                                    // Dans l'original, il testait une variable client_id_f
                                    // Ici on peut juste considérer que l'ajout a réussi
                                    $('#msg_r').html('<i class="zmdi zmdi-check-circle"></i> Programmee de préstation ajouté avec succès');
                                    $('#msg_r').css("color", '#32c787');
                                    $("#save_t").attr("disabled", false);


                                    setTimeout(() => { $('#msg_r').html(""); }, 9000);
                                },
                                error: function() {
                                    $("#save_t").attr("disabled", false);
                                    $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'ajout');
                                    $('#msg_r').css("color", "#ff6b68");
                                    setTimeout(() => { $('#msg_r').html(""); }, 9000);
                                }
                            });
                        }
                    }
                }
            }
        }
    }
});

get_refresh_programme();
get_mois_where_not_in_prestation();


function get_refresh_programme() {
    $.get("{{ url('/get_refresh_programme') }}", {
        poste_id: "{{ $postes['id'] }}",
        nom_poste: "{{ strtoupper($postes['nom']) }}",
        nom_lieu_poste : "{{ strtoupper(Lieux::where(['id' => $postes['lieuxe_id']])->first()['nom']); }}",
    }, function(response) {
        $("#bloc_programme").html(response);
    });
}
function get_mois_where_not_in_prestation()
{
    var annee_id = $("#annee_id").val();
    $.get("{{ url('/get_mois_where_not_in_prestation') }}", {
        annee_id: annee_id,
    }, function(response) {
        $("#moi_id").html(response);
    });
}
$("#annee_id").change(function(e) {
    e.preventDefault();
    get_mois_where_not_in_prestation()
});
</script>