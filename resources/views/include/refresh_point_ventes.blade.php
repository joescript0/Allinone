<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Stock utilise</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($point_ventes as $data)
                    <tr>
                        <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td class="nom-cell" data-nom="{{ $data->nom }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                        <td class="desc-cell" data-desc="{{ $data->description }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                        <td class="stock-cell" data-stock-id="{{ $data->stock_id }}"
                            style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->stock_id == -1)
                                <i class="zmdi zmdi-close-circle text-danger"></i> <span
                                    class="text-danger">{{ 'Aucun' }} </span>
                            @elseif ($data->stock_id == 0)
                                <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">
                                    Principal</span>
                            @else
                                <i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">
                                    {{ Stocks::where('id', $data->stock_id)->first()['nom'] ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_edit_point_ventes') }}", {
                                        point_vente_id: <?= $data->id ?>,
                                    }, function(refresh_edit_point_ventes) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_edit_point_ventes);
                                    });
                                });
                                $("#delete_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element").html("<?= $data->nom ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_sup").trigger("click");
                                });
                            </script>
                        </td>
                    </tr>
                    {{ !$i++ }}
                @endforeach
                <!-- Ligne pour aucun résultat -->
                <tr id="noResultRow" style="display: none;">
                    <td colspan="6">
                        <i class="zmdi zmdi-info-outline"></i> Aucun point de vente ne correspond à vos critères.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
