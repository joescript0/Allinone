<?php

use App\Models\Factureas;
use App\Models\Listespaies;
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

?>
<div class="row">
    <div class="col-12">
        <form id="add_user" action="#" method="post">
            @csrf
            <div>
                <div style="margin-top: 30px;" id="content_groupe" class="row">
                    <div style="margin-top: -30px;" class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i class="zmdi zmdi-info"></i> Travailleur </span></label>
                            <select style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" id="user" name="user" class="select2" data-placeholder="Selectionnez un travailleur">
                                <option selected value="">Selectionnez un travail</option>
                                @foreach ($utilisateurs as $data)
                                    <option value="{{ $data->id }}"><?= 'Nom : ' .  $data->name . ', Role : ' . Groupes::where('id', $data->role)->first()["nom"] . ', Numero : ' . $data->phone ?>.</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="listespaie_id" name="listespaie_id" value="{{ $listespaies["id"] }}">
                            <input type="hidden" id="moi_id" name="moi_id" value="{{ $listespaies["moi_id"] }}">
                            <input type="hidden" id="annee_id" name="annee_id" value="{{ $listespaies["annee_id"] }}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12" style="margin-top: -10px;">
        <form action="">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <button id="save_t" class="btn btn-info">Ajouter <i class="zmdi zmdi-plus-circle"></i></button>
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
        <h4 style="text-align: center;color: white;background-color: rgb(0, 0, 0);padding: 15px;">LISTE DE PAIE : {{ strtoupper(Mois::where(["id" => $listespaies["moi_id"]])->first()["nom"]); }} {{ Annees::where(["id" => $listespaies["annee_id"]])->first()["annees"]; }}</h4>
    </div>
</div>
<div class="row">
    <div id="bloc_user" style="margin-top: 12px;" class="col-lg-12">
        <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i> Liste de travailleur</h4>
        <?php
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($paiements as $f) {
            if ($f->paye == 1) {
                if ($f->devise == 0) {
                    $total_3 =  $total_3 + $f->paie;
                } else {
                    $total_3 =  ($total_3 + ($f->paie / $f->taux));
                }
            }
        }
        foreach ($paiements as $f) {
            if ($f->paye == 0) {
                if ($f->devise == 0) {
                    $total_4 =  $total_4 + $f->paie;
                } else {
                    $total_4 =  round($total_4 + ($f->paie / $f->taux));
                }
            }
        }
        ?>
        <?php $total_5 = $total_3 + $total_4 ?>
        <h6 style="text-align: right;font-weight: bold;"><span> <i class="zmdi zmdi-check-circle text-success"></i> Paiement total : <span id="nb_total_1"><?= number_format($total_5, 2, ',', ' ') ?></span>$</span></h6>
        <div id="content_frais" class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Role / Fonction</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">salaire</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Etat de payement</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{! $i = 1; }}
                            @foreach ($paiements as $data)
                            <tr>
                                <td style="padding-top: 5px;padding-bottom: 5px;"> <?= User::where('id', $data->user_id)->first()["name"]; ?></td>
                                <td style="padding-top: 5px;padding-bottom: 5px;"> <?php $role_id = User::where('id', $data->user_id)->first()["role"];
                                                                                    echo Groupes::where('id', $role_id)->first()["nom"]; ?></td>
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;">
                                    @if ($data->devise == 0)
                                        {{ number_format($data->paie, 2, ',', ' ') }}$ / {{ number_format($data->montant, 2, ',', ' ') }}$
                                    @else
                                    <?php
                                    $paies = Paies::where(["user_id" => $data->user_id, "paiement_id" => $data->paiement_id])->get();
                                    $t_p = 0;
                                    foreach ($paies as $p)
                                    {
                                        $t_p = $t_p + $p->montant;
                                    }
                                    ?>
                                    <?= number_format($t_p, 2, ',', ' ') ?> / <?= number_format($data->montant, 2, ',', ' ') ?>Fc
                                    @endif
                                </th>
                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                    @if ($data->montant != $data->paie)
                                    <a id="payer_valider_travailleur<?= $i ?>" href=""><i class="zmdi zmdi-close-circle text-danger"></i></a>
                                    <script>
                                        $("#payer_valider_travailleur<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#data_frais_id").html("<?= $data->id ?>");
                                            $("#devise_paie_id").html("<?= $data->devise ?>");
                                            $.get("{{ url('/get_detail_p_1') }}", {
                                                id: "<?= $data->id ?>",
                                                user_id: "<?= $data->user_id ?>",
                                            }, function(get_detail_p) {
                                                $("#nom_p").html(get_detail_p.split("______________________________")[0]);
                                                $("#role_p").html(get_detail_p.split("______________________________")[1]);
                                                if (get_detail_p.split("______________________________")[2] == 0) {
                                                    $("#devise_p").html("$");
                                                } else {
                                                    $("#devise_p").html("Fc");
                                                }
                                                $("#reste_p").html(get_detail_p.split("______________________________")[3]);
                                                $("#total_p").html(get_detail_p.split("______________________________")[4]);
                                                $("#data_frais_id").html("<?= $data->id ?>");
                                                $("#btn_sup_").trigger("click");
                                            });
                                        });
                                    </script>
                                    @else
                                    <i class="zmdi zmdi-check-circle text-success"></i>
                                    @endif
                                </td>
                            </tr>
                            {{! $i++; }}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
        var user = $("#user").val();
        var data = $("#add_user").serialize();
        if (user.trim().length == 0) {
            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez d\'abord un travailleur');
            $('#msg_r').css("color", "#ff6b68");
            setTimeout(() => {
                $('#msg_r').html("");
            }, 9000);
        } else {
            $("#save_t").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/check_user_in_listespaies",
                data: data,
                success: function(rep) {
                    $("#save_t").attr("disabled", false);
                    if (rep != 0) {
                        $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Ce travailleur existe déjà');
                        $('#msg_r').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#msg_r').html("");
                        }, 9000);
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/add_user_in_listespaies",
                            data: data,
                            success: function(response) {
                                get_user_where_not_in_listespaies();
                                $("#user").val("");
                                $('#msg_r').html('<i class="zmdi zmdi-check-circle"></i> utilisateur ajouté succès');
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
    });
    function get_user_where_not_in_listespaies()
    {
        $.get("{{ url('/get_user_where_not_in_listespaies') }}", {
            listespaie_id : {{ $listespaies["id"] }}
        }, function(response)
        {
            $("#user").html(response);
        });
    }
    get_user_where_not_in_listespaies();

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
            if (montant_p.trim().length == 0)
            {
                $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant à payer');
                $('#m_paie').css("color", "#ff6b68");
                setTimeout(() => {
                    $('#m_paie').html("");
                }, 9000);
            } else
            {
                if (montant_p <= 0) {
                    $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez une bonne valeur du montant à payer');
                    $('#m_paie').css("color", "#ff6b68");
                    setTimeout(() => {
                        $('#m_paie').html("");
                    }, 9000);
                } else
                {
                    if(devise == 1)
                    {
                        montant_p = montant_p / taux_p;
                    }
                    if(taux_p.trim().length == 0)
                    {
                        $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux actuel');
                        $('#m_paie').css("color", "#ff6b68");
                        setTimeout(() => {
                            $('#m_paie').html("");
                        }, 9000);
                    }
                    else
                    {
                        if((((Number(montant_p)) + (Number(reste_p))) > Number((total_p))))
                        {
                            $('#m_paie').html('<i class="zmdi zmdi-close-circle"></i> le montant à payer dois être inferieur ou egal à ' + (total_p - reste_p));
                            $('#m_paie').css("color", "#ff6b68");
                            setTimeout(() => {
                                $('#m_paie').html("");
                            }, 9000);
                        } else {
                            var id = $("#data_frais_id").html();
                            $("#save_paie").attr("disabled", true);
                            $.get("{{ url('/save_p_1') }}", {
                                id: id,
                                montant_p: (Number(montant_p)),
                                taux_p: taux_p,
                            }, function(savep)
                            {
                                $("#save_paie").attr("disabled", false);
                                $("#montant_p").val(0);
                                $("#taux_p").val(0);
                                $("#reste_p").html((Number(montant_p)) + (Number(reste_p)));
                                $('#m_paie').html('<i class="zmdi zmdi-check-circle"></i> paiement effectué avec succès');
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
    });
</script>
