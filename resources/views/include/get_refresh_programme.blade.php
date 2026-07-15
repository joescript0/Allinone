<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Mois;
use App\Models\Annees;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Utilisateurs;
use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Paiesfactures;
use App\Models\Paiementsfactures;
use App\Models\Clients;

?>

<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-calendar text-success"></i> Nombre
    total : <?= $nb_programme; ?></h4>
<div id="content_frais" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Mois</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Type de préstation</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Type de rotation</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Nombre de jour</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                    </tr>
                </thead>
                <tbody>
                    {{ !($i = 1) }}
                    @foreach ($prestations as $data)
                    <tr>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $i }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ Mois::where(['id' => $data->moi_id])->first()['nom'] }}
                            {{ Annees::where(['id' => $data->annee_id])->first()['annees'] }}
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            @if ($data->type_prestation == 1)
                                <i class="zmdi zmdi-info text-danger"></i> <span class="text-danger">Journée </span>
                            @endif
                            @if ($data->type_prestation == 2)
                                <i class="zmdi zmdi-info text-dark"></i> <span class="text-dark">Nuit </span>
                            @endif
                            @if ($data->type_prestation == 3)
                                <i class="zmdi zmdi-info text-info"></i> <span class="text-info">Journée / Nuit </span>
                            @endif
                        </td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->type_de_rotation }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;">
                            {{ $data->nombre_de_jour }}</td>
                        <td style="padding-top: 5px;padding-bottom: 5px;" class="text-center">
                            <a id="voir_prestation<?= $i ?>" title="Voir" href="#"><i
                                    class="zmdi zmdi-eye text-success"></i> <span class="text-warning"></span></a>
                        </td>
                        <script>
                            $("#voir_prestation<?= $i ?>").click(function(e){
                                 e.preventDefault();
                                 $.get("{{ url('/get_detail_programme') }}", {
                                    prestation_id : <?= $data->id ?>,
                                }, function(detail_programme) {
                                   $("#detail_pro").html("{{ $nom_poste }} DE {{ $nom_lieu_poste }} {{ strtoupper(Mois::where(['id' => $data->moi_id])->first()['nom']) }} {{ Annees::where(['id' => $data->annee_id])->first()['annees'] }} AVEC COMME ROTATION AU POSTE {{ $data->type_de_rotation }}");
                                   $("#contenu_programme").html(detail_programme);
                                    $("#btn_detail_programme").trigger('click'); 
                                });
                            })
                        </script>
                    </tr>
                    {{ !$i++ }}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>