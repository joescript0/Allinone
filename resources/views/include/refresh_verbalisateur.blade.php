<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($verbalisateurs as $data)
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                        <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <script>
                            $("#edit_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_editverbalisateur') }}", {
                                    verbalisateur_id: <?= $data->id ?>,
                                }, function(refresh_editverbalisateur) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").show();
                                    $("#bloc_3").html(refresh_editverbalisateur);
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
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
