<?php

use App\Models\Ressources;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Affectation du stock <span class="text-info">({{ $nom }})</span> au point de vente
    <select class="form-control" style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);margin-top:10px;" name="stock_select" id="stock_select">
        @if ($stock_id == 0)
            <option selected value="0"> {{ "Stock principal" }}</option>
            @foreach ($stocks as $data)
                <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
            @endforeach
        @else
            <option selected value="0"> {{ "Stock principal" }}</option>
            @foreach ($stocks as $data)
                @if ($data->id == $stock_id)
                    <option selected value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
                @else
                    <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
                @endif
            @endforeach
        @endif
    </select>
</h4>
<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Affecter</th>
                    </tr>
                </thead>
                <tbody>
                    {{! $i = 1; }}
                    @foreach ($pointdeventes as $data)
                        <tr>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $i ?></td>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $data->nom ?></td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->display == 1)
                                <a id="display__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square"></i></a>
                                @else
                                <a id="display__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
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
<script>
    $("#stock_select").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/refresh_affectation_stock_vente') }}", {
            stock_id: $("#stock_select").val(),
        }, function(liste_r) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").show();
            $("#bloc_3").html(liste_r);
        });
    });
</script>
