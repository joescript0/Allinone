<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($groupes as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                        <a id="ressource_<?= $i ?>" href="#"><i class="zmdi zmdi-settings text-info"></i></a> &nbsp;
                        <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <script>
                            $("#edit_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_editgroupe') }}", {
                                    groupe_id: <?= $data->id ?>,
                                }, function(refresh_editutilisateur) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").show();
                                    $("#bloc_3").html(refresh_editutilisateur);
                                });
                            });
                            $("#delete_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element").html("<?= $data->nom ?>");
                                $("#data_id").html("<?= $data->id ?>");
                                $("#btn_sup").trigger("click");
                            });
                            $("#ressource_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_write') }}", {
                                    groupe_id: <?= $data->id ?>,
                                }, function(liste_r) {
                                    $("#bloc_1").hide();
                                    $("#bloc_4").show();
                                    $("#bloc_4").html(liste_r);
                                });
                            });
                        </script>
                    </td>
                </tr>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
