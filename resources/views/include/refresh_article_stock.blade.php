<?php
use App\Models\Contrevenants;
use App\Models\Groupes;
use App\Models\Verbalisateurs;
use App\Models\Writes;
use App\Models\User;
use App\Models\Factures;
use App\Models\Entres;
use App\Models\Societes;
use App\Models\Mesures;
use App\Models\Activites;
use App\Models\Typeventes;
use Illuminate\Support\Facades\Auth;
use App\Models\Ressources;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Article du
    stock <span class="text-info">({{ $nom }})</span> au point de vente
    <select class="form-control"
        style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);margin-top:10px;"
        name="stock_select" id="stock_select">
        @if ($stock_id == 0)
            <option selected value="0"> {{ 'Stock principal' }}</option>
            @foreach ($stocks as $data)
                <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
            @endforeach
        @else
            <option selected value="0"> {{ 'Stock principal' }}</option>
            @foreach ($stocks as $data)
                @if ($data->id == $stock_id)
                    <option selected value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
                @else
                    <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
                @endif
            @endforeach
        @endif
    </select>
</h4>
<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="articlesTable">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Catégorie</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Activité</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Prix</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Stock</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Seuils</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Date d'expiration</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    {{ !($i = 1) }}
                    @foreach ($articles as $data)
                        <tr id="row_{{ $data->id }}">
                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                            <td class="nom-cell" data-nom="{{ $data->nom_article }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->nom_article }}
                                ({{ Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A' }})
                            </td>
                            <td class="categorie-cell" data-categorie-id="{{ $data->societe_id }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                {{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }}
                            </td>
                            <td class="activite-cell" data-activite-id="{{ $data->activite_id }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->activite_id == 0 || $data->activite_id == '0')
                                    Aucune
                                @else
                                    {{ Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune' }}
                                @endif
                            </td>
                            <td class="prix-cell" data-prix="{{ $data->prix }}" data-devise="{{ $data->devise }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                <?php
                                if ($data->devise == 0) {
                                    echo '<span class="text-success">D : </span>' . number_format($data->prix_detail, 2, ',', ' ') . '(USD), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . 'USD';
                                } else {
                                    echo '<span class="text-success">D : </span>' . number_format($data->prix_detail, 2, ',', ' ') . '(CDF), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . '(CDF)';
                                }
                                ?>
                            </td>
                            <td class="stock-cell" data-stock="{{ $data->stock }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                <?php if($data->stock <= $data->seuil_minimum){ ?>
                                <span class="text-danger">{{ $data->stock }}</span>
                                <?php } ?>
                                <?php if($data->stock > $data->seuil_minimum){ ?>
                                <span>{{ $data->stock }}</span>
                                <?php } ?>
                            </td>
                            <td class="seuil-cell" data-seuil-min="{{ $data->seuil_minimum }}"
                                data-seuil-max="{{ $data->seuil_maximum }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->seuil_minimum . ' - ' . $data->seuil_maximum }}
                            </td>
                            <td class="user-cell" data-user-id="{{ $data->user_id }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                            </td>
                            <td class="date-cell" data-date-expiration="{{ $data->date_expiration }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                <?php if($data->date_expiration  == "00/00/0000"){ ?>
                                <span class="text-info">{{ $data->date_expiration }} (N'expire pas)</span>
                                <?php }else{ ?>
                                <?php
                                $target = 0;
                                $semaine = ['Dimanche', 'Lundi', ' Mardi ', 'Mercredi ', 'Jeudi', 'Vendredi', 'Samedi'];
                                $mois = [1 => 'Janvier', 'Février ', 'Mars ', 'Avril ', 'Mai ', 'Juin', 'Juillet', 'Août ', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                $__d1 = date('d');
                                $__m1 = date('m');
                                $__y1 = date('Y');
                                $__d2 = explode('/', $data->date_expiration)[0];
                                $__m2 = explode('/', $data->date_expiration)[1];
                                $__y2 = explode('/', $data->date_expiration)[2];

                                $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                                $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                                while (strtotime($date_1) <= strtotime($date_2)) {
                                    $jours = 1;
                                    $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                                    if ($semaine[date('w', $valeur_date)] != '') {
                                        $target++;
                                    }
                                    $datedd = date('m/d/Y', strtotime(date('' . explode('/', $date_1)[0] . '/' . explode('/', $date_1)[1] . '/' . explode('/', $date_1)[2] . '') . ' + ' . $jours . ' days'));
                                    $date_1 = explode('/', $datedd)[1] . '/' . explode('/', $datedd)[0] . '/' . explode('/', $datedd)[2];
                                    $date_1 = explode('/', $datedd)[0] . '/' . explode('/', $datedd)[1] . '/' . explode('/', $datedd)[2];
                                }
                                if ($target == 0) {
                                    echo "<span class='text-danger'>Expiré depuis $data->date_expiration </span>";
                                } else {
                                    echo "<span class='text-success'>$data->date_expiration (Dans $target jours) </span>";
                                }
                                ?>
                                <?php } ?>
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $edit = 0;
                                $delete = 0;
                                if (
                                    Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                        ->get()
                                        ->count() != 0
                                ) {
                                    $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                    $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                }
                                ?>
                                <?php } ?>
                                <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                &nbsp;
                                <?php } else { ?>
                                <a id="delete_r<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                <?php } ?>
                                <script>
                                    $("#edit_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $.get("{{ url('/refresh_editarticle') }}", {
                                            user_id: <?= $data->id ?>,
                                        }, function(refresh_editarticle) {
                                            $("#bloc_1").hide();
                                            $("#bloc_2").hide();
                                            $("#bloc_3").show();
                                            $("#bloc_3").html(refresh_editarticle);
                                        });
                                    });
                                    $("#edit_r<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                    $("#delete_r<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                    $("#delete_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#element").html(
                                            "<?= $data->nom_article . '(' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>"
                                        );
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
</div>
<script>
    $("#stock_select").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/refresh_article_stock') }}", {
            stock_id: $("#stock_select").val(),
        }, function(liste_r) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").show();
            $("#bloc_3").html(liste_r);
        });
    });
</script>
