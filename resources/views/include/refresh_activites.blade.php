<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($activites as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            <a id="voir_profil_<?= $i ?>" href="#">
                                <img src="{{ asset($data->logo) }}" alt="avatar" class="profile-thumb">
                            </a> {{ $data->nom }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <script>
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_editactivite') }}", {
                                        activite_id: <?= $data->id ?>,
                                    }, function(refresh_edit_activite) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_edit_activite);
                                    });
                                });
                                $("#delete_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#element").html("<?= $data->nom ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_sup").trigger("click");
                                });
                                $("#voir_profil_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#nom_profil").html("<?= $data->nom ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    var url = "<?= $data->logo ?>";
                                    $("#contenu_voir_profil").html('<img src="' + url +
                                        '" class="img-fluid" style="max-height:100%;width: 100%;" />'
                                    );
                                    $("#btn_voir_profil").trigger("click");
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
