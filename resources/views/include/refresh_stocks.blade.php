@php
    use App\Models\appnames;
    use App\Models\pointdeventes;
@endphp
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ 1 }}</td>
                    <td style="padding-top: 5px;padding-bottom: 5px;">{{ 'Stock principal' }}</td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <span class="badge badge-info">
                            <?= pointdeventes::where(['stock_id' => 0])
                                                        ->get()
                                                        ->count() ?>
                        </span>
                    </td>
                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <a id="edit_1" href="#"><i class="zmdi zmdi-edit text-secondary"></i></a> &nbsp;
                        <a id="article_1" href="#"><i class="zmdi zmdi-storage text-dark"></i></a> &nbsp;
                        <a id="affectation_1" href="#"><i class="zmdi zmdi-settings text-info"></i></a> &nbsp;

                        <a id="delete_1" href="#"><i class="zmdi zmdi-delete text-secondary"></i>
                        </a>
                    </td>
                    <script>
                        $("#affectation_1").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/refresh_affectation_stock_vente') }}", {
                                stock_id: 0,
                            }, function(refresh_affectation_stock_vente) {
                                $("#bloc_1").hide();
                                $("#bloc_2").hide();
                                $("#bloc_3").show();
                                $("#bloc_3").html(refresh_affectation_stock_vente);
                            });
                        });
                        $("#edit_1").click(function(e) {
                            e.preventDefault();
                            console.log("C'est interdit de modifier");
                        });
                        $("#delete_1").click(function(e) {
                            e.preventDefault();
                            console.log("C'est interdit de supprimer");
                        });
                        $("#article_1").click(function(e) {
                            e.preventDefault();
                            $.get("{{ url('/refresh_article_stock') }}", {
                                stock_id: 0,
                            }, function(refresh_article_stock) {
                                $("#bloc_1").hide();
                                $("#bloc_2").hide();
                                $("#bloc_3").show();
                                $("#bloc_3").html(refresh_article_stock);
                            });
                        });
                    </script>
                </tr>
                {{ !($i = 2) }}
                @foreach ($stocks as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <span class="badge badge-info">
                                <?= pointdeventes::where(['stock_id' => $data->id])
                                                            ->get()
                                                            ->count() ?>
                            </span>
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                            <a id="article_<?= $i ?>" href="#"><i class="zmdi zmdi-storage text-dark"></i></a>
                            &nbsp;
                            <a id="affectation_<?= $i ?>" href="#"><i
                                    class="zmdi zmdi-settings text-info"></i></a> &nbsp;

                            <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                            <script>
                                $("#affectation_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_affectation_stock_vente') }}", {
                                        stock_id: <?= $data->id ?>,
                                    }, function(refresh_affectation_stock_vente) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_affectation_stock_vente);
                                    });
                                });
                                $("#article_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_article_stock') }}", {
                                        stock_id: <?= $data->id ?>,
                                    }, function(refresh_article_stock) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_article_stock);
                                    });
                                });
                                $("#edit_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $.get("{{ url('/refresh_edit_stocks') }}", {
                                        stock_id: <?= $data->id ?>,
                                    }, function(refresh_edit_stocks) {
                                        $("#bloc_1").hide();
                                        $("#bloc_2").hide();
                                        $("#bloc_3").show();
                                        $("#bloc_3").html(refresh_edit_stocks);
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
