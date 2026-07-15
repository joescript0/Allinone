<?php
    use App\Models\Pointdeventes;
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
                    <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($tables as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->pointdeventes_id != null ? Pointdeventes::where('id', $data->pointdeventes_id)->first()->nom : 'Aucun point de vente' }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->occupee == 0)
                                <i class="zmdi zmdi-check-circle text-success"></i> <span
                                    class="text-success">{{ 'Libre' }} </span>
                            @elseif ($data->occupee == 1)
                                <i class="zmdi zmdi-check-circle text-danger"></i> <span class="text-danger">
                                    Occupee</span>
                            @else
                                <i class="zmdi zmdi-check-circle text-warning"></i> <span class="text-warning">Aucun
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
                            </script>
                        </td>
                    </tr>
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
