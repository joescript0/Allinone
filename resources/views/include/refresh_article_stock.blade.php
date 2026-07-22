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

<style>
    /* Conteneur des filtres : horizontal et scrollable */
    .filters-container-stock {
        display: flex;
        flex-wrap: nowrap;
        gap: 12px;
        overflow-x: auto;
        padding: 12px 16px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        align-items: flex-end;
    }
    .filters-container-stock .filter-group {
        flex: 1 1 0;
        min-width: 120px;
    }
    .filters-container-stock .filter-group label {
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #0a192f;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 4px;
    }
    .filters-container-stock .filter-group .form-control {
        width: 100%;
        height: 36px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    .filters-container-stock .filter-group .form-control:focus {
        border-color: #0a192f;
        box-shadow: 0 0 0 3px rgba(10,25,47,0.15);
    }
    .filters-container-stock .filter-group .btn-reset {
        background: #64748b;
        color: white;
        border: none;
        border-radius: 40px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.25s;
        white-space: nowrap;
    }
    .filters-container-stock .filter-group .btn-reset:hover {
        background: #475569;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(100,116,139,0.3);
    }

    @media (max-width: 768px) {
        .filters-container-stock .filter-group {
            min-width: 150px;
            flex: 0 0 auto;
        }
        .filters-container-stock {
            padding: 10px 12px;
        }
    }
</style>

<h4 style="color:rgba(0, 0, 0, 0.6);">
    <i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Article du stock
    <span class="text-info">({{ $nom }})</span> au point de vente
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

<!-- FILTRES HORIZONTAUX -->
<div class="filters-container-stock">
    <div class="filter-group">
        <label><i class="zmdi zmdi-label text-danger"></i> Nom</label>
        <input type="text" id="filterNomStock" class="form-control" placeholder="Rechercher...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-folder text-danger"></i> Catégorie</label>
        <select id="filterCategorieStock" class="form-control">
            <option value="all">Toutes</option>
            @foreach ($societes as $categorie)
                <option value="cat_{{ $categorie->id }}">{{ $categorie->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-chart text-danger"></i> Activité</label>
        <select id="filterActiviteStock" class="form-control">
            <option value="all">Toutes</option>
            <option value="none">Aucune</option>
            @foreach ($activites as $activite)
                @if (Auth::user()->role == 0)
                    <option value="act_{{ $activite->id }}">{{ $activite->nom }}</option>
                @else
                    @if ($activite->id == Auth::user()->activite_id)
                        <option value="act_{{ $activite->id }}" selected>{{ $activite->nom }}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-accounts text-danger"></i> Utilisateur</label>
        <select id="filterUserStock" class="form-control">
            <option value="all">Tous</option>
            @php
                $uniqueUsers = [];
            @endphp
            @foreach ($utilisateurs as $data)
                @if(!in_array($data->id, $uniqueUsers))
                    @php
                        $uniqueUsers[] = $data->id;
                        $userName = User::where('id', $data->id)->first()['name'] ?? 'N/A';
                    @endphp
                    @if ($data->id == Auth::user()->id)
                        <option value="{{ $data->id }}" selected>(Vous)</option>
                    @else
                        <option value="{{ $data->id }}">{{ $userName }}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-storage text-danger"></i> Stock</label>
        <select id="filterStockStock" class="form-control">
            <option value="all">Tous</option>
            <option value="in">En stock (>0)</option>
            <option value="out">Rupture (=0)</option>
            <option value="critical">Seuil critique</option>
        </select>
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-calendar text-danger"></i> Expiration</label>
        <select id="filterExpirationStock" class="form-control">
            <option value="all">Tous</option>
            <option value="expired">Expiré</option>
            <option value="soon">≤ 30 jours</option>
            <option value="valid">> 30 jours</option>
        </select>
    </div>
    <div class="filter-group">
        <label>&nbsp;</label> <!-- pour aligner verticalement avec les autres -->
        <button id="resetFiltersStock" class="btn-reset">
            <i class="zmdi zmdi-refresh"></i> Réinitialiser
        </button>
    </div>
</div>

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
                                @if ($data->avoir_stock == 1)
                                    <?php if($data->stock <= $data->seuil_minimum){ ?>
                                    <span class="text-danger">{{ $data->stock }}</span>
                                    <?php } ?>
                                    <?php if($data->stock > $data->seuil_minimum){ ?>
                                    <span>{{ $data->stock }}</span>
                                    <?php } ?>
                                @else
                                    -
                                @endif
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
                                <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                <a id="edit_a_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>
                                &nbsp;
                                <?php } else { ?>
                                <a id="edit_r_a_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>
                                &nbsp;
                                <?php } ?>
                                <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                <a id="delete_a_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                &nbsp;
                                <?php } else { ?>
                                <a id="delete_r_a_<?= $i ?>" href="#"><i
                                        class="zmdi zmdi-delete text-danger"></i></a> &nbsp;
                                <?php } ?>
                                <script>
                                    $("#edit_a_<?= $i ?>").click(function(e) {
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
                                    $("#edit_r_a_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                    $("#delete_r_a_<?= $i ?>").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                    $("#delete_a_<?= $i ?>").click(function(e) {
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
                    <!-- Ligne pour aucun résultat -->
                    <tr id="noResultRow" style="display: none;">
                        <td colspan="10">
                            <i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de
                            recherche.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Gestion du changement de stock (existant)
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

    // ========== GESTION DES FILTRES AVEC PERSISTANCE ==========
    (function() {
        var stockId = {{ $stock_id }};

        function filterArticlesStock() {
            var filterNom = $('#filterNomStock').val().toLowerCase().trim();
            var filterCategorie = $('#filterCategorieStock').val();
            var filterActivite = $('#filterActiviteStock').val();
            var filterUser = $('#filterUserStock').val();
            var filterStock = $('#filterStockStock').val();
            var filterExpiration = $('#filterExpirationStock').val();

            var visibleCount = 0;
            var newIndex = 1;

            $('#noResultRow').hide();

            $('#articlesTableBody tr:not(#noResultRow)').each(function() {
                var $row = $(this);
                var showRow = true;

                // Nom
                var nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                // Catégorie
                if (showRow && filterCategorie !== 'all') {
                    var categorieId = $row.find('.categorie-cell').data('categorie-id');
                    var currentCat = categorieId != null ? String(categorieId) : '';
                    if (filterCategorie.startsWith('cat_')) {
                        var targetCat = filterCategorie.replace('cat_', '');
                        if (currentCat !== targetCat) showRow = false;
                    }
                }

                // Activité
                if (showRow && filterActivite !== 'all') {
                    var activiteId = $row.find('.activite-cell').data('activite-id');
                    var currentAct = activiteId != null ? String(activiteId) : '';
                    if (filterActivite === 'none') {
                        if (currentAct !== '0' && currentAct !== '') showRow = false;
                    } else if (filterActivite.startsWith('act_')) {
                        var targetAct = filterActivite.replace('act_', '');
                        if (currentAct !== targetAct) showRow = false;
                    }
                }

                // Utilisateur
                if (showRow && filterUser !== 'all') {
                    var userId = $row.find('.user-cell').data('user-id');
                    var currentUser = userId != null ? String(userId) : '';
                    if (currentUser !== filterUser) showRow = false;
                }

                // Statut stock
                if (showRow && filterStock !== 'all') {
                    var stock = parseInt($row.find('.stock-cell').data('stock')) || 0;
                    var seuilMin = parseInt($row.find('.seuil-cell').data('seuil-min')) || 0;
                    var matchesStock = false;
                    switch (filterStock) {
                        case 'in': matchesStock = (stock > 0); break;
                        case 'out': matchesStock = (stock === 0); break;
                        case 'critical': matchesStock = (stock > 0 && stock <= seuilMin); break;
                        default: matchesStock = true;
                    }
                    if (!matchesStock) showRow = false;
                }

                // Expiration
                if (showRow && filterExpiration !== 'all') {
                    var dateStr = $row.find('.date-cell').data('date-expiration');
                    var daysLeft = null;
                    if (dateStr && dateStr !== '00/00/0000') {
                        var parts = dateStr.split('/');
                        var expDate = new Date(parts[2], parts[1] - 1, parts[0]);
                        var today = new Date();
                        today.setHours(0,0,0,0);
                        var diffTime = expDate - today;
                        daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    }
                    var matchesExp = false;
                    switch (filterExpiration) {
                        case 'expired': matchesExp = (daysLeft !== null && daysLeft < 0); break;
                        case 'soon': matchesExp = (daysLeft !== null && daysLeft >= 0 && daysLeft <= 30); break;
                        case 'valid': matchesExp = (daysLeft !== null && daysLeft > 30); break;
                        default: matchesExp = true;
                    }
                    if (!matchesExp) showRow = false;
                }

                if (showRow) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            // Mise à jour du compteur (si l'élément existe)
            var badge = $('#articleCountBadge');
            if (badge.length) {
                badge.find('span').text(visibleCount);
            }

            if (visibleCount === 0) {
                $('#noResultRow').show();
            }
        }

        // Sauvegarde des filtres dans localStorage
        function saveFiltersStock() {
            var filters = {
                nom: $('#filterNomStock').val(),
                categorie: $('#filterCategorieStock').val(),
                activite: $('#filterActiviteStock').val(),
                user: $('#filterUserStock').val(),
                stock: $('#filterStockStock').val(),
                expiration: $('#filterExpirationStock').val()
            };
            localStorage.setItem('articleStockFilters_' + stockId, JSON.stringify(filters));
        }

        // Chargement depuis localStorage
        function loadFiltersStock() {
            var key = 'articleStockFilters_' + stockId;
            var saved = localStorage.getItem(key);
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterNomStock').val(filters.nom || '');
                $('#filterCategorieStock').val(filters.categorie || 'all');
                $('#filterActiviteStock').val(filters.activite || 'all');
                $('#filterUserStock').val(filters.user || 'all');
                $('#filterStockStock').val(filters.stock || 'all');
                $('#filterExpirationStock').val(filters.expiration || 'all');
                return true;
            }
            return false;
        }

        // Réinitialisation
        function resetFiltersStock() {
            $('#filterNomStock').val('');
            $('#filterCategorieStock').val('all');
            $('#filterActiviteStock').val('all');
            $('#filterUserStock').val('all');
            $('#filterStockStock').val('all');
            $('#filterExpirationStock').val('all');
            saveFiltersStock();
            filterArticlesStock();
            // Message
            var msg = $('#msg');
            if (msg.length) {
                msg.html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
                msg.css('display', 'flex');
                setTimeout(function() {
                    msg.html('');
                    msg.css('display', 'none');
                }, 3000);
            }
        }

        var filterTimeout;
        function debouncedFilterStock() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                filterArticlesStock();
                saveFiltersStock();
            }, 300);
        }

        $(document).ready(function() {
            loadFiltersStock();
            filterArticlesStock();

            $('#filterNomStock, #filterCategorieStock, #filterActiviteStock, #filterUserStock, #filterStockStock, #filterExpirationStock')
                .on('change keyup', function() {
                    debouncedFilterStock();
                });

            $('#resetFiltersStock').click(function(e) {
                e.preventDefault();
                resetFiltersStock();
            });
        });
    })();
</script>
