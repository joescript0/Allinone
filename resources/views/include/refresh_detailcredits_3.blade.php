<?php

use App\Models\Factureas;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\User;

?>
<form  id="form_add_r" action="">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">DATE</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Libelle</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Montant($)</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;">Taux(CDF)</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Sortie($)</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;font-weight: bold;display: none;">Solde($)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;w">
                        <input id="date_r" name="date_r" type="text" class="input-mask" data-mask="00/00/0000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="<?= date("d/m/Y") ?>">
                        <input id="credit_id" name="credit_id" type="hidden" class="input-mask" data-mask="00/00/0000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="{{ $credits["id"]; }}">
                    </td>
                    <td style="padding-top: 5px;padding-bottom: 5px;w">
                        <input id="libelle_r" name="libelle_r" type="text" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);" placeholder="">
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <input id="entree_r" name="entree_r" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="0">
                        <select id="devise_r" name="devise_r">
                            <option value="0">USD</option>
                            <option value="1">CDF</option>
                        </select>
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <input id="taux_r" name="taux_r" type="text" class="input-mask" data-mask="00000000000000000000000000000000000000" style="padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);width: 100px;" placeholder="" value="2800">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<br>
<button id="add_r" class="btn btn-info">Enregister <i class="zmdi zmdi-save"></i></button> <button id="annuler_r" class="btn btn-danger">Annuler <i class="zmdi zmdi-close-circle"></i></button>
<br>
<div style="text-align: center;">
    <span style="font-weight: bold;" id="msg_r"></span>
</div>
<div id="block_r" style="margin-bottom: 50px;">
    <?php
        $t = 0;
        foreach ($remboursements as $e)
        {
            $t = $t + $e->entree;
        }
    ?>
    <h4>Credit de {{ $credits["nom_credit"] }} : <strong class="text-info">{{ $t }} / {{ $credits["entree"] }}$</strong></h4>
    @if ($remboursements_2->count() != 0)
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date de crédit</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Montant credité</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($remboursements_2 as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_credit }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">
                        {{ number_format($data->entree, 2, ',', ' ') .'$'; }}
                    </td>
                </tr>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<script src="{{ asset('assets/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script>
    $("#annuler_r").click(function(e) {
        e.preventDefault();
        $("#bloc_1").show();
        $("#bloc_2").hide();
        $("#bloc_3").hide();
    });

    $("#add_r").click(function(e) {
        e.preventDefault();
        var date_r = $("#date_r").val();
        var entree_r = $("#entree_r").val();
        var devise_r = $("#devise_r").val();
        var taux_r = $("#taux_r").val();
        var libelle_r = $("#libelle_r").val();
        var data = $("#form_add_r").serialize();
        if(date_r.trim().length == 0)
        {
            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Completez la date du remboursement');
            $('#msg_r').css('color', "#ff6b68");
            setTimeout(() => {
                $('#msg_r').html("");
            }, 9000);
        }
        else
        {
            if(libelle_r.trim().length == 0){
                $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Completez le libelle');
                $('#msg_r').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg_r').html("");
                }, 9000);
            }else{
                if(entree_r.trim().length == 0)
                {
                    $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Completez le montant à rembourser');
                    $('#msg_r').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg_r').html("");
                    }, 9000);
                }
                else
                {
                    if(entree_r.trim() <= 0)
                    {
                        $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Le montant à rembourser doit être supérieur à 0');
                        $('#msg_r').css('color', "#ff6b68");
                        setTimeout(() => {
                            $('#msg_r').html("");
                        }, 9000);
                    }
                    else
                    {
                        if(taux_r.trim().length == 0)
                        {
                            $('#msg_r').html('<i class="zmdi zmdi-close-circle"></i> Completez le taux de remboursement');
                            $('#msg_r').css('color', "#ff6b68");
                            setTimeout(() => {
                                $('#msg_r').html("");
                            }, 9000);
                        }
                        else
                        {
                            $("#save_r").attr("disabled", true);
                            $.get("{{ url('/check_remboursement_1') }}",
                            {
                                credit_id : $("#credit_id").val(),
                                entree_r : entree_r,
                                taux_r : taux_r,
                                devise_r : devise_r,
                                date_r : date_r,
                                libelle_r : libelle_r,
                            }, function(remboursement)
                            {
                                $("#save_r").attr("disabled", false);
                                $('#msg_r').html('<i class="zmdi zmdi-check-circle"></i> Crédit effectué avec succès');
                                $('#msg_r').css("color", '#32c787');
                                $("#entree_r").val(0);
                                $("#libelle_r").val("");
                                $.get("{{ url('/refresh_detailcredits_4') }}", {
                                    invitation_id: $("#credit_id").val(),
                                }, function(refresh_editinvitations) {
                                    $("#block_r").html(refresh_editinvitations);
                                    $.get("{{ url('/get_credit') }}", {

                                    }, function(credit) {
                                        $("#bloc_1").html(credit);
                                    });
                                    $("#bloc_1").html("");
                                });
                                setTimeout(() => {
                                    $('#msg_r').html("");
                                }, 9000);
                            });
                        }
                    }
                }
            }
        }
    });
</script>
