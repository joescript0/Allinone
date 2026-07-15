<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Listesfactures;
use App\Models\Mois;
use App\Models\Annees;
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
        <form id="add_user" action="#" method="post">
            @csrf
            <div>
                <div style="margin-top: 30px;" id="content_groupe" class="row">
                    <div style="display: none;" style="margin-top: -30px;" class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-info"></i> Travailleur </span></label>
                            <select
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                id="user" name="user" class="select2"
                                data-placeholder="Selectionnez un travailleur">
                                <option selected value="">Selectionnez un client</option>
                                @foreach ($utilisateurs as $data)
                                    <option value="{{ $data->id }}">
                                        <?= 'Nom : ' . $data->name . ', Role : ' . Groupes::where('id',
                                        $data->role)->first()['nom'] . ', Numero : ' . $data->phone ?>.
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="listesfactures_id" name="listesfactures_id"
                                value="{{ $listesfactures['id'] }}">
                            <input type="hidden" id="moi_id" name="moi_id" value="{{ $listesfactures['moi_id'] }}">
                            <input type="hidden" id="annee_id" name="annee_id"
                                value="{{ $listesfactures['annee_id'] }}">
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-info"></i> Activité </span></label>
                            <select
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                id="activite_id_f" name="activite_id_f" class="select2"
                                data-placeholder="Selectionnez une activité">
                                <option selected value="">Selectionnez une activité</option>
                                @foreach ($activites as $data)
                                    <option value="{{ $data->id }}"><?= $data->nom ?>.</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: -30px;" class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                    class="zmdi zmdi-accounts"></i> Voulez-vous générer la facture pour quel client ?
                                </span></label>
                            <select
                                style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                id="client_id_f" name="client_id_f" class="select2"
                                data-placeholder="Selectionnez un client">
                                <option selected value="">Selectionnez un client</option>
                                <option value="0">Tout les clients</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12" style="margin-top: 15px;">
        <form action="">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <button id="save_t" class="btn btn-info btn-sm">Ajouter <i
                            class="zmdi zmdi-plus-circle"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="text-align: center;">
    <span style="font-weight: bold;" id="msg_r"></span>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-12">
        <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">LISTE DE FACTURE
            {{ strtoupper(Mois::where(['id' => $listesfactures['moi_id']])->first()['nom']) }}
            {{ Annees::where(['id' => $listesfactures['annee_id']])->first()['annees'] }} <span
                id="nom_activite"></span></h4>
    </div>
</div>
<div class="row" style="padding-bottom: 50px;">
    <div id="bloc_user" style="margin-top: 12px;" class="col-lg-12">

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
        var activite_id_f = $("#activite_id_f").val();
        var client_id_f = $("#client_id_f").val();
        var data = $("#add_user").serializeArray();
        data.push({
            name: "activite_id",
            value: $("#activite_id_f").val()
        });
        activite_id: $("#activite_id_f").val()
        if (activite_id_f.trim().length == 0) {
            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez d\'abord une activité');
            $('#msg_r').css("color", "#ff6b68");
            setTimeout(() => {
                $('#msg_r').html("");
            }, 9000);
        } else {
            if (client_id_f.trim().length == 0) {
                $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez un client');
                $('#msg_r').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#msg_r').html("");
                }, 9000);
            } else {
                $("#save_t").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "/check_client_in_listesfactures",
                    data: data,
                    success: function(rep) {
                        $("#save_t").attr("disabled", false);
                        if (rep != 0) {
                            $('#msg_r').html(
                                '<i class="zmdi zmdi-close-circle"></i> Ce client existe déjà'
                            );
                            $('#msg_r').css("color", "#ff6b68");
                            setTimeout(() => {
                                $('#msg_r').html("");
                            }, 9000);
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "/add_client_in_listesfactures",
                                data: data,
                                success: function(response) {
                                    get_user_where_not_in_listesfactures();
                                    if (client_id_f == 0) {
                                        $('#msg_r').html(
                                            '<i class="zmdi zmdi-check-circle"></i> Clients ajoutés succès'
                                        );
                                    } else {
                                        $('#msg_r').html(
                                            '<i class="zmdi zmdi-check-circle"></i> Client ajouté succès'
                                        );
                                    }
                                    $('#msg_r').css("color", '#32c787');
                                    $("#bloc_user").html(response);
                                    setTimeout(() => {
                                        $('#msg_r').html("");
                                    }, 9000);
                                }
                            });
                        }
                    }
                });
            }
        }
    });

    function get_user_where_not_in_listesfactures() {
        $.get("{{ url('/get_user_where_not_in_listesfactures') }}", {
            listesfactures_id: {{ $listesfactures['id'] }},
            activite_id: $("#activite_id_f").val(),
        }, function(response) {
            $("#client_id_f").html(response);
        });
    }

    function get_refresh_paiementsfactures() {
        $.get("{{ url('/get_refresh_paiementsfactures') }}", {
            listesfactures_id: {{ $listesfactures['id'] }},
            activite_id: $("#activite_id_f").val(),
        }, function(response) {
            $("#bloc_user").html(response);
        });
    }


    get_user_where_not_in_listesfactures();
    get_refresh_paiementsfactures();

    $("#save_paie").click(function(e) {
        e.preventDefault();
        var id = $("#data_frais_id").html();
        var devise = $("#devise_paie_id").html();
        var montant_p = $("#montant_p").val();
        var taux_p = $("#taux_p").val();
        var reste_p = $("#reste_p").html();
        var total_p = $("#total_p").html();
        if ((((Number(reste_p))) == Number((total_p)))) {
            $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> ce paiement est déjà cloturé');
            $('#m_paie').css("color", "#ff6b68");
            setTimeout(() => {
                $('#m_paie').html("");
            }, 9000);
        } else {
            if (montant_p.trim().length == 0) {
                $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant à payer');
                $('#m_paie').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#m_paie').html("");
                }, 9000);
            } else {
                if (montant_p <= 0) {
                    $('#m_paie').html(
                        '<i class="zmdi zmdi-close-circle"></i> Completez une bonne valeur du montant à payer'
                    );
                    $('#m_paie').css("color", "#ff6b68");
                    setTimeout(() => {
                        $('#m_paie').html("");
                    }, 9000);
                } else {
                    if (devise == 1) 
                    {
                        // montant_p = montant_p / taux_p;
                    }
                    if (taux_p.trim().length == 0) {
                        $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux actuel');
                        $('#m_paie').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#m_paie').html("");
                        }, 9000);
                    } else {
                        if (taux_p <= 0) {
                            $('#m_paie').html(
                                '<i class="zmdi zmdi-close-circle"></i> Completez une bonne valeur du taux à payer'
                            );
                            $('#m_paie').css("color", "#ff6b68");
                            setTimeout(() => {
                                $('#m_paie').html("");
                            }, 9000);
                        } else {
                            if ((((Number(montant_p)) + (Number(reste_p))) > Number((total_p)))) {
                                $('#m_paie').html(
                                    '<i class="zmdi zmdi-close-circle"></i> le montant à payer dois être inferieur ou egal à ' +
                                    (total_p - reste_p));
                                $('#m_paie').css("color", "#ff6b68");
                                setTimeout(() => {
                                    $('#m_paie').html("");
                                }, 9000);
                            } else {
                                var id = $("#data_frais_id").html();
                                $("#save_paie").attr("disabled", true);
                                $.get("{{ url('/save_p_2') }}", {
                                    id: id,
                                    montant_p: (Number(montant_p)),
                                    taux_p: taux_p,
                                    activite_id: $("#activite_id_f").val(),
                                    activite_id_f: $("#activite_id_f").val(),
                                }, function(savep) {
                                    $("#save_paie").attr("disabled", false);
                                    $("#montant_p").val(0);
                                    $("#taux_p").val(0);
                                    $("#reste_p").html((Number(montant_p)) + (Number(reste_p)));
                                    $('#m_paie').html(
                                        '<i class="zmdi zmdi-check-circle"></i> Paiement effectué avec succès'
                                    );
                                    $('#m_paie').css("color", '#32c787');
                                    $("#bloc_user").html(savep);
                                    setTimeout(() => {
                                        $('#m_paie').html("");
                                    }, 9000);
                                });
                            }
                        }
                    }
                }
            }
        }
    });

    $("#activite_id_f").change(function(e) {
        e.preventDefault();
        var activite_id = $("#activite_id_f").val();
        $.get("{{ url('/get_client_by_activite') }}", {
            activite_id: activite_id,
        }, function(response) {
            $("#client_id_f").html(response);
            get_refresh_paiementsfactures()
            get_user_where_not_in_listesfactures();
        });
    });
</script>
