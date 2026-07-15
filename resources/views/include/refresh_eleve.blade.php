<?php

use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Postes;
use App\Models\User;
use App\Models\Mois;
use App\Models\Clients;
use App\Models\districts;
use App\Models\classes;
use App\Models\communes;
use App\Models\Lieux;
use App\Models\ecoles;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Encodeur</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Eleve</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Genre / Sexe</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Classe</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Parent</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Telephone</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                {{ !($i = 1) }}
                @foreach ($beneficiaires as $data)
                    <tr id="row_{{ $data->id }}">
                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            @if (Auth::user()->id == $data->user_id)
                                Vous
                            @else
                                {{ User::where('id', $data->user_id)->first()['name'] }}
                            @endif
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            <?php
                            $date = $data->created_at;
                            $date_1 = explode(' ', $date);
                            echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                            ?>
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->nom_eleve }}
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;"
                            class="text-center">
                            @if ($data->genre == 0)
                                <span>F</span>
                            @else
                                <span>M</span>
                            @endif
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ classes::where('id', $data->classe_id)->first()['nom'] }}
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->nom_parent }}
                        </td>
                        <td data-nom="{{ $data->nom }}" style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->telephone }}
                        </td>
                        <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                            <a id="detail_<?= $i ?>" href="#"><i class="zmdi zmdi-eye text-info"></i></a>
                            &nbsp;&nbsp;
                            <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>
                            &nbsp;&nbsp;
                            <a id="delete_<?= $i ?>" href="#"><i
                                    class="zmdi zmdi-delete text-danger"></i></a>&nbsp;&nbsp;
                        </td>
                        <script>
                            $("#delete_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#element").html(
                                    "<span style='color:black;'>Nom : </span>{{ $data->nom_eleve }}, <span style='color:black;'>Ecole : </span>{{ ecoles::where('id', $data->ecole_id)->first()['nom'] }}, <span style='color:black;'>District école : </span>{{ districts::where('id', ecoles::where('id', $data->ecole_id)->first()['district_id'])->first()['nom'] }}, <span style='color:black;'>Commune école : </span>{{ communes::where('id', ecoles::where('id', $data->ecole_id)->first()['commune_id'])->first()['nom'] }}, <span style='color:black;'>Adresse école : </span>{{ ecoles::where('id', $data->ecole_id)->first()['adresse'] }}."
                                );
                                $("#data_id").html("<?= $data->id ?>");
                                $("#btn_sup").trigger("click");
                            });
                            $("#edit_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_editeleve') }}", {
                                    eleve_id: <?= $data->id ?>,
                                }, function(refresh_editeleve) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").show();
                                    $("#bloc_3").html(refresh_editeleve);
                                });
                            });
                            $("#detail_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                var $row = $(this).closest('tr');
                                $('#modalDetailEcole').modal('show');
                                var nom_eleve = "{{ $data->nom_eleve }}";
                                <?php if(Auth::user()->id == $data->user_id){?>
                                var nom_encadreur = "Vous";
                                <?php }else{ ?>
                                var nom_encadreur = "{{ User::where('id', $data->user_id)->first()['name'] }}"
                                <?php } ?>
                                <?php if($data->genre == 0){?>
                                var genre = "F";
                                <?php }else{ ?>
                                var genre = "M"
                                <?php } ?>
                                var date = "<?php $date = $data->created_at;
                                $date_1 = explode(' ', $date);
                                echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1]; ?>";
                                var classe = "{{ classes::where('id', $data->classe_id)->first()['nom'] }}";
                                var parent = "{{ $data->nom_parent }}";
                                var telephone = "{{ $data->telephone }}";
                                var ecole = "{{ ecoles::where('id', $data->ecole_id)->first()['nom'] }}";
                                var district =
                                    "{{ districts::where('id', ecoles::where('id', $data->ecole_id)->first()['district_id'])->first()['nom'] }}";
                                var commune =
                                    "{{ communes::where('id', ecoles::where('id', $data->ecole_id)->first()['commune_id'])->first()['nom'] }}";
                                var adresse = "{{ ecoles::where('id', $data->ecole_id)->first()['adresse'] }}";
                                $('#detail_nom_eleve').text(nom_eleve);
                                $('#detail_encodeur').text(nom_encadreur + ", Le " + date);
                                $('#detail_genre').text(genre);
                                $('#detail_classe').text(classe);
                                $('#detail_parent').text(parent);
                                $('#detail_telephone').text(telephone);
                                $('#detail_ecole').text(ecole);
                                $('#detail_district').text(district);
                                $('#detail_commune').text(commune);
                                $('#detail_adresse').text(adresse);
                            });
                        </script>
                    </tr>
                    {{ !$i++ }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
