<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="display: none;" style="padding-top: 5px;padding-bottom: 5px;">Adresse</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody id="categoriesTableBody">
                {{ !($i = 1) }}
                @foreach ($societes as $data)
                    <tr id="row_{{ $data->id }}">
                        <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td class="nom-cell" data-nom="{{ $data->nom }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                        <td style="display: none;" style="padding-top: 5px;padding-bottom: 5px;">{{ $data->code }}
                        </td>
                        <td class="description-cell" data-description="{{ $data->description }}"
                            style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_edit_societe') }}", {
                                        societe_id: <?= $data->id ?>,
                                    }, function(refresh_edit_societe) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_edit_societe);
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
                    <td colspan="5">
                        <i class="zmdi zmdi-info-outline"></i> Aucune catégorie ne correspond à vos critères.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
