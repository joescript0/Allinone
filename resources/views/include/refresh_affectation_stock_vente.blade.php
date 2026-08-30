<?php

use App\Models\Ressources;
use App\Models\Stocks;
?>
<h4 style="color:rgba(0, 0, 0, 0.6);">
    <i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Affectation du stock
    <span class="text-info">({{ $nom }})</span> au point de vente
    <select class="form-control"
        style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);margin-top:10px;"
        name="stock_select" id="stock_select">
        @if ($stock_id == 0)
            <option selected value="0"> {{ "Stock principal" }}</option>
            @foreach ($stocks as $data)
                <option value="{{ $data->id }}"> {{ strtolower($data->nom) }}</option>
            @endforeach
        @else
            <option selected value="0"> {{ "Stock principal" }}</option>
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

<!-- SECTION FILTRES HORIZONTAUX -->
<div class="filters-container-stock">
    <div class="filter-group">
        <label><i class="zmdi zmdi-label text-danger"></i> Point de vente</label>
        <input type="text" id="filterNomAffectation" class="form-control" placeholder="Rechercher...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-check-square text-danger"></i> Statut</label>
        <select id="filterStatutAffectation" class="form-control">
            <option value="all">Tous</option>
            <option value="affecte">Affecté</option>
            <option value="non_affecte">Non affecté</option>
        </select>
    </div>
    <div class="filter-group">
        <label>&nbsp;</label>
        <button id="resetFiltersAffectation" class="btn-reset">
            <i class="zmdi zmdi-refresh"></i> Réinitialiser
        </button>
    </div>
</div>

<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Affecter</th>
                    </tr>
                </thead>
                <tbody id="affectationTableBody">
                    {{! $i = 1; }}
                    @foreach ($pointdeventes as $data)
                        <tr data-nom="{{ strtolower($data->nom) }}" data-affecte="{{ $data->display }}">
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $i ?></td>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $data->nom ?></td>
                            <td style="text-align: left;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->stock_id != -1)
                                    @if ($data->stock_id == $stock_id)
                                        <a id="affectation__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square text-info"></i></a>
                                    @else
                                        <a id="affectation__<?= $i ?>" href="#"><i class="zmdi zmdi-check-square text-danger"></i></a>
                                        @if ($data->stock_id == 0)
                                            <span class="text-danger"> {{ "Principal" }}</span>
                                        @else
                                            <span class="text-danger"> {{ Stocks::where('id', $data->stock_id)->first()['nom'] ?? 'N/A' }}</span>
                                        @endif
                                    @endif
                                @else
                                    <a id="affectation__<?= $i ?>" href="#"><i class="zmdi zmdi-square-o"></i></a>
                                @endif
                            </td>
                        </tr>
                        <script>
                            $("#affectation__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_affectation_pointdeventes') }}", {
                                    pointdeventes_id: <?= $data->id ?>,
                                    stock_id: $("#stock_select").val(),
                                }, function(etat) {
                                    if (etat == 1)
                                    {
                                        $("#affectation__<?= $i ?>").html('<i class="zmdi zmdi-check-square"></i></a>');
                                    } else {
                                        $("#affectation__<?= $i ?>").html('<i class="zmdi zmdi-square-o"></i></a>');
                                    }
                                    $.get("{{ url('/refresh_affectation_stock_vente') }}", {
                                        stock_id: $("#stock_select").val(),
                                    }, function(liste_r) {
                                        $.get("{{ url('/get_all_stock') }}", {}, function(
                                        refresh_editutilisateur) {
                                            $("#content_groupe").html(refresh_editutilisateur);
                                            filterCategories();
                                        });
                                    });
                                });
                            });
                        </script>
                        {{! $i++; }}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Réutilisation du style de filtres horizontaux (identique à celui de la vue stock) */
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

<script>
    // Gestion du changement de stock (existant)
    $("#stock_select").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/refresh_affectation_stock_vente') }}", {
            stock_id: $("#stock_select").val(),
        }, function(liste_r) {
            $("#bloc_1").hide();
            $("#bloc_2").hide();
            $("#bloc_3").show();
            $("#bloc_3").html(liste_r);
            // Après rechargement, réinitialiser les filtres (car nouvelle liste)
            // On peut soit réappliquer les filtres sauvegardés pour ce stock, soit les remettre à "all".
            // Ici on les remet à "all" pour éviter des incohérences.
            resetFiltersAffectation();
        });
    });

    // ========== GESTION DES FILTRES AVEC PERSISTANCE ==========
    (function() {
        var stockId = {{ $stock_id }};

        function filterAffectation() {
            var filterNom = $('#filterNomAffectation').val().toLowerCase().trim();
            var filterStatut = $('#filterStatutAffectation').val();

            var visibleCount = 0;
            var newIndex = 1;

            $('#affectationTableBody tr').each(function() {
                var $row = $(this);
                var showRow = true;

                // Filtre par nom
                var nomValue = ($row.data('nom') || '').toLowerCase();
                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                // Filtre par statut
                if (showRow && filterStatut !== 'all') {
                    var affecte = $row.data('affecte'); // 1 ou 0
                    if (filterStatut === 'affecte' && affecte != 1) {
                        showRow = false;
                    } else if (filterStatut === 'non_affecte' && affecte != 0) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    $row.show();
                    $row.find('td:first-child').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });
        }

        function saveFiltersAffectation() {
            var filters = {
                nom: $('#filterNomAffectation').val(),
                statut: $('#filterStatutAffectation').val()
            };
            localStorage.setItem('affectationStockFilters_' + stockId, JSON.stringify(filters));
        }

        function loadFiltersAffectation() {
            var key = 'affectationStockFilters_' + stockId;
            var saved = localStorage.getItem(key);
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterNomAffectation').val(filters.nom || '');
                $('#filterStatutAffectation').val(filters.statut || 'all');
                return true;
            }
            return false;
        }

        function resetFiltersAffectation() {
            $('#filterNomAffectation').val('');
            $('#filterStatutAffectation').val('all');
            saveFiltersAffectation();
            filterAffectation();
            // Message (optionnel)
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
        function debouncedFilterAffectation() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                filterAffectation();
                saveFiltersAffectation();
            }, 300);
        }

        $(document).ready(function() {
            // Charger les filtres sauvegardés
            loadFiltersAffectation();
            // Appliquer les filtres
            filterAffectation();

            // Événements sur les filtres
            $('#filterNomAffectation, #filterStatutAffectation')
                .on('change keyup', function() {
                    debouncedFilterAffectation();
                });

            // Réinitialisation
            $('#resetFiltersAffectation').click(function(e) {
                e.preventDefault();
                resetFiltersAffectation();
            });
        });
    })();
</script>
