<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\User;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\districts;
use App\Models\classes;
use App\Models\communes;
use App\Models\Lieux;
use App\Models\ecoles;
use Illuminate\Support\Facades\Auth;


?>
<!-- Vendor styles -->
<link rel="stylesheet" href="{{ asset('assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/animate.css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/fullcalendar/fullcalendar.min.css') }}">
<link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/nouislider/nouislider.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendors/rateyo/jquery.rateyo.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('connexion/vendor/animate/animate.css') }}">
<!-- App styles -->
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select-multiple.css') }}">
<link rel="stylesheet" href="{{ asset('assets/demo/css/demo.css') }}">
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-edit text-info"></i> Modifier </h4>
<form id="form_edit" action="#" method="post" style="margin-bottom: 100px;">
    @csrf
    <div class="row">
        <div style="display: none;" class="col-6">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-account"></i>
                    Nom </span></label>
                <input type="text" id="id" name="id"
                    style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                    class="form-control" placeholder="Nom (Ex : Mgm congo)" value="<?= $eleves->id ?>">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-home"></i> Ecole </label>
                <select id="edit_ecole_id" name="edit_ecole_id" class="form-control select2">
                    <option value="">Selectionnez une école</option>
                    @foreach ($ecoles as $data)
                        @if ($data->id ==  $eleves->ecole_id)
                            <option selected value="{{ $data->id }}">Nom : {{ $data->nom }}, District : {{ districts::where(["id" => $data->district_id])->first()["nom"]; }}, Commune : {{ communes::where(["id" => $data->commune_id])->first()["nom"]; }}.</option>
                        @else
                            <option value="{{ $data->id }}">Nom : {{ $data->nom }}, District : {{ districts::where(["id" => $data->district_id])->first()["nom"]; }}, Commune : {{ communes::where(["id" => $data->commune_id])->first()["nom"]; }}.</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom de l'élève </label>
                <input type="text" id="edit_nom_eleve" name="edit_nom_eleve" class="form-control" placeholder="Nom (Ex : KALENGA KALALA Helène)" value="{{ $eleves->nom_eleve }}">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-home"></i> Classe </label>
                <select id="edit_classe_id" name="edit_classe_id" class="form-control">
                    <option value="">Selectionnez une classe</option>
                    @foreach ($classes as $data)
                        @if ($data->id == $eleves->classe_id)
                            <option selected value="{{ $data->id }}">{{ $data->nom }}</option>        
                        @else
                            <option value="{{ $data->id }}">{{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-male-female"></i> Genre / Sexe </label>
                <select id="edit_genre" name="edit_genre" class="form-control">
                    @if ($eleves->classe_id == 0)
                        <option selected value="0">F</option>
                        <option value="1">M</option>
                    @else
                        <option value="0">F</option>
                        <option selected value="1">M</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div style="margin-top: -20px;" class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Nom de l'élève </label>
                <input type="text" id="edit_nom_eleve" name="edit_nom_eleve" class="form-control" placeholder="Nom (Ex : KALENGA KALALA Helène)" value="{{ $eleves->nom_eleve }}">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-home"></i> Classe </label>
                <select id="edit_classe_id" name="edit_classe_id" class="form-control">
                    <option value="">Selectionnez une classe</option>
                    @foreach ($classes as $data)
                        @if ($data->id == $eleves->classe_id)
                            <option selected value="{{ $data->id }}">{{ $data->nom }}</option>        
                        @else
                            <option value="{{ $data->id }}">{{ $data->nom }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-male-female"></i> Genre / Sexe </label>
                <select id="edit_genre" name="edit_genre" class="form-control">
                    @if ($eleves->classe_id == 0)
                        <option selected value="0">F</option>
                        <option value="1">M</option>
                    @else
                        <option value="0">F</option>
                        <option selected value="1">M</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Nom parent </label>
                <input type="text" id="edit_nom_parent" name="edit_nom_parent" class="form-control" placeholder="Date de création (Ex : KOKO léonce)" value="{{ $eleves->nom_parent }}">
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-accounts"></i> Téléphone </label>
                <input type="text" id="edit_telephone" name="edit_telephone" class="form-control" placeholder="Numéro de téléphone (Ex : 0123456789)" value="{{ $eleves->telephone }}">
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-12">
            <button id="edit_save" class="btn btn-info btn-sm">Modifier <i class="zmdi zmdi-edit"></i></button>
            <button id="edit_annuler" class="btn btn-danger btn-sm">Annuler <i
                    class="zmdi zmdi-close-circle"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <span style="font-weight: bold;" id="edit_msg">
            </span>
        </div>
    </div>
</form>
<!-- App functions and actions -->
<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
    $("#edit_district_id").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/get_commune_by_district') }}", {
            district_id: $(this).val()
        }, function(response) {
            $("#edit_commune_id").html(response);
        });
    });
    $("#edit_annuler").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });
    $("#edit_save").click(function(e) {
        e.preventDefault();

        // Déclaration des champs
        var ecole = $("#edit_ecole_id").val();
        var eleve = $("#edit_nom_eleve").val();
        var classe = $("#edit_classe_id").val();
        var genre = $("#edit_genre").val();
        var nom_parent = $("#edit_nom_parent").val();
        var telephone = $("#edit_telephone").val();
        var data = $("#form_edit").serialize();

        // Fonction utilitaire pour afficher un message d'erreur
        function showError(message) {
            $('#edit_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + message);
            $('#edit_msg').css('color', "#ff6b68");
            setTimeout(() => {
                $('#edit_msg').html("");
            }, 9000);
        }

        // Validation séquentielle de tous les champs
        if (ecole.trim().length === 0) {
            showError("Selectionnez une école");
        } else if (eleve.trim().length === 0) {
            showError("Completez le nom complet de l\'élève");
        } else if (classe.trim().length === 0) {
            showError("Selectionnez une classe");
        } else if (genre.trim().length === 0) {
            showError("Completez le genre de l\'élève");
        } else if (nom_parent.trim().length === 0) {
            showError("Completez le du parent");
        } else if (telephone.trim().length === 0) {
            showError("Completez le numéro de téléphone du parent");
        } else {
            // Tous les champs sont remplis → on continue
            $("#edit_save").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/check_eleve_existe_1",
                data: data,
                success: function(response) {
                    $("#edit_save").attr("disabled", false);
                    if (response == 1) {
                        $('#edit_msg').html(
                            '<i class="zmdi zmdi-close-circle"></i> Cet enregistrement existe déjà'
                            );
                        $('#edit_msg').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#edit_msg').html("");
                        }, 9000);
                    } else {
                        $("#edit_save").attr("disabled", true);
                        $.ajax({
                            type: "POST",
                            url: "/edit_eleve",
                            data: data,
                            success: function(response) {
                                $("#edit_save").attr("disabled", false);
                                // Réinitialisation des champs (optionnel)
                                $('#edit_msg').html(
                                    '<i class="zmdi zmdi-check-circle"></i> Enregistrement modifié avec succès'
                                    );
                                $('#edit_msg').css("color", '#32c787');
                                $("#content_utilisateur").html(response);
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
