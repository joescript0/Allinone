<?php
use App\Models\Pointdeventes;
use App\models\affectationstables;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Affectation</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($tables as $data)
                    <tr>
                        <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td class="nom-cell" data-nom="{{ $data->nom }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                        <td class="desc-cell" data-desc="{{ $data->description }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                        <td class="pointvente-cell" data-pointvente-id="{{ $data->pointdeventes_id }}"
                            style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->pointdeventes_id != null ? Pointdeventes::where('id', $data->pointdeventes_id)->first()->nom : 'Aucun point de vente' }}
                        </td>
                        <td class="affectation-cell" style="padding-top: 5px;padding-bottom: 5px;text-align:center;">
                            <a style="font-weight: bold" id="affectation_<?= $i ?>" href="#">
                                @if (affectationstables::where(['supprimer' => 0, 'table_id' => $data->id])->count() == 0)
                                    <span style="font-weight: bold;" class="badge badge-danger">
                                        <i class="zmdi zmdi-accounts"></i> <?= affectationstables::where(['table_id' => $data->id])
                                                                    ->get()
                                                                    ->count() ?>
                                    </span>
                                @else
                                    <span style="font-weight: bold;" class="badge badge-info">
                                        <i class="zmdi zmdi-accounts"></i>
                                        <?= affectationstables::where(['supprimer' => 0, 'table_id' => $data->id])->count() ?>
                                    </span>
                                @endif
                            </a>
                        </td>
                        <td class="etat-cell" data-occupee="{{ $data->occupee }}"
                            style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->occupee == 0)
                                <i class="zmdi zmdi-check-circle text-success"></i> <span
                                    class="text-success">{{ 'Libre' }} </span>
                            @elseif ($data->occupee == 1)
                                <i class="zmdi zmdi-check-circle text-danger"></i> <span class="text-danger">
                                    Occupée</span>
                            @else
                                <i class="zmdi zmdi-close-circle text-warning"></i> <span class="text-warning">Aucun
                                    detail</span>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_edit_tables') }}", {
                                        table_id: <?= $data->id ?>,
                                    }, function(refresh_edit_tables) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_edit_tables);
                                    });
                                });
                                $("#delete_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element").html("<?= $data->nom ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_sup").trigger("click");
                                });
                                $("#affectation_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_affectation_table_utilisateur') }}", {
                                        table_id: <?= $data->id ?>,
                                    }, function(refresh_affectation_stock_vente) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_affectation_stock_vente);
                                    });
                                });
                            </script>
                        </td>
                    </tr>
                    {{ !$i++ }}
                @endforeach
                <!-- Ligne pour aucun résultat -->
                <tr id="noResultRow" style="display: none;">
                    <td colspan="6">
                        <i class="zmdi zmdi-info-outline"></i> Aucune table ne correspond à vos critères.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
