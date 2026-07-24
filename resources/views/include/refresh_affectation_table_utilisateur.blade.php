<?php

use App\Models\Ressources;
use App\Models\Pointdeventes;
use App\Models\affectationstables;
use App\Models\Groupes;
?>

<!-- ==================================================== -->
<!-- EN-TÊTE AVEC CHAMP DE RECHERCHE ET LISTE PERSONNALISÉE -->
<!-- ==================================================== -->
<div class="header-affectation">
    <div class="header-left">
        <i class="zmdi zmdi-settings text-info header-icon"></i>
        <div>
            <h1 class="header-title">Affectation des utilisateurs</h1>
            <div class="header-infos">
                <span class="badge badge-stock">{{ $nom }}</span>
                <span class="header-separator">/</span>
                <span class="badge badge-point">{{ $nom_point_vente }}</span>
            </div>
        </div>
    </div>
    <div class="header-right">
        <div class="stock-search-wrapper">
            <div class="stock-search-input-group">
                <i class="zmdi zmdi-search search-icon"></i>
                <input type="text" class="form-control header-select" id="stock_search"
                       placeholder="Rechercher une table d'un point de vente..."
                       value="{{ strtolower($nom) }}" autocomplete="off">
                <input type="hidden" id="table_id" value="{{ $table_id }}">
                <!-- Liste personnalisée des tables -->
                <ul id="stock-list-custom" class="stock-list-custom">
                    @foreach ($tables as $data)
                        <li data-id="{{ $data->id }}" data-name="{{ strtolower($data->nom) }}">
                            <span class="table-name">{{ $data->nom }}</span>
                            <span class="table-pdv">{{ pointdeventes::where('id', $data->pointdeventes_id )->first()["nom"] ?? 'N/A'; }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ==================================================== -->
<!-- SECTION FILTRES HORIZONTAUX                          -->
<!-- ==================================================== -->
<div class="filters-container-stock">
    <div class="filter-group">
        <label><i class="zmdi zmdi-account text-primary"></i> Nom utilisateur</label>
        <input type="text" id="filterNomUtilisateur" class="form-control" placeholder="Rechercher un nom...">
    </div>

    <div class="filter-group">
        <label><i class="zmdi zmdi-accounts text-warning"></i> Fonction</label>
        <select id="filterFonction" class="form-control">
            <option value="all">Toutes</option>
            @foreach (\App\Models\Groupes::orderBy('nom')->get() as $groupe)
                <option value="{{ $groupe->nom }}">{{ $groupe->nom }}</option>
            @endforeach
        </select>
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

<!-- ==================================================== -->
<!-- TABLEAU DES UTILISATEURS                             -->
<!-- ==================================================== -->
<div style="margin-bottom: 100px;" id="content_groupe" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Fonction</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Affecter</th>
                    </tr>
                </thead>
                <tbody id="affectationTableBody">
                    {{! $i = 0; }}
                    @foreach ($utilisateurs as $data)
                        @if ($data->role == 0) @continue @endif
                        {{! $i++; }}
                        @php
                            $fonction = \App\Models\Groupes::where('id', $data->role)->first()["nom"] ?? 'N/A';
                            $affecte = affectationstables::where(["user_id" => $data->id, "table_id" => $table_id])->count() != 0 ? 1 : 0;
                        @endphp
                        <tr data-nom="{{ strtolower($data->name) }}"
                            data-fonction="{{ $fonction }}"
                            data-affecte="{{ $affecte }}">
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $i ?></td>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $data->name ?></td>
                            <td style="padding-top: 5px;padding-bottom: 5px;"><?= $fonction ?></td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                <a href="#" id="affectation__<?= $i ?>" style="font-size: 1.4rem; color: #0a192f;">
                                    @if ($affecte == 1)
                                        <i class="zmdi zmdi-check-square text-info"></i>
                                    @else
                                        <i class="zmdi zmdi-square-o text-info"></i>
                                    @endif
                                </a>
                            </td>
                        </tr>
                        <script>
                            $("#affectation__<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/etat_affectation_table_utilisateur') }}", {
                                    user_id: <?= $data->id ?>,
                                    table_id: $("#table_id").val(),
                                }, function(etat) {
                                    if (etat == 1) {
                                        $("#affectation__<?= $i ?>").html('<i class="zmdi zmdi-check-square text-info"></i>');
                                    } else {
                                        $("#affectation__<?= $i ?>").html('<i class="zmdi zmdi-square-o text-info"></i>');
                                    }
                                    $.get("{{ url('/get_all_table') }}", {}, function(refresh_editutilisateur) {
                                        $("#content_groupe").html(refresh_editutilisateur);
                                        filterAffectation();
                                    });
                                });
                            });
                        </script>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================================================== -->
<!-- STYLES CSS                                           -->
<!-- ==================================================== -->
<style>
    /* ----- HEADER PRINCIPAL ----- */
    .header-affectation {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        padding: 16px 24px;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        margin-bottom: 28px;
        border: 1px solid #f0f2f5;
        gap: 16px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 0 1 auto;
    }

    .header-icon {
        font-size: 44px;
        color: #0a7b8c;
        background: #e6f7fa;
        padding: 10px;
        border-radius: 16px;
        line-height: 1;
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0a192f;
        margin: 0;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .header-infos {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 4px;
        flex-wrap: wrap;
    }

    .badge-stock {
        background: #e6f7e6;
        color: #1e7e34;
        font-size: 1rem;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 40px;
        border: 1px solid #b7e0b7;
    }

    .badge-point {
        background: #e6f0fa;
        color: #0a5b8c;
        font-size: 1rem;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 40px;
        border: 1px solid #b7d4e8;
    }

    .header-separator {
        color: #b0b8c4;
        font-weight: 300;
        font-size: 1.2rem;
    }

    /* ----- CHAMP DE RECHERCHE ÉLARGI AVEC LISTE ----- */
    .header-right {
        flex: 1 1 400px;
        min-width: 250px;
        max-width: 550px;
        display: flex;
        justify-content: flex-end;
    }

    .stock-search-wrapper {
        width: 100%;
    }

    .stock-search-input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border-radius: 40px;
        border: 1px solid #e2e8f0;
        transition: border-color 0.2s, box-shadow 0.2s;
        padding: 0 16px;
        height: 44px;
        flex-wrap: wrap;
    }
    .stock-search-input-group:focus-within {
        border-color: #0a192f;
        box-shadow: 0 0 0 3px rgba(10,25,47,0.15);
    }

    .search-icon {
        color: #94a3b8;
        font-size: 1.2rem;
        margin-right: 10px;
        transition: color 0.2s;
    }
    .stock-search-input-group:focus-within .search-icon {
        color: #0a192f;
    }

    .header-select {
        border: none !important;
        background: transparent !important;
        padding: 8px 0;
        font-size: 1rem;
        font-weight: 400;
        color: #0a192f;
        width: 100%;
        height: 100%;
        outline: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        appearance: none;
        background-image: none !important;
        cursor: pointer;
        flex: 1;
    }
    .header-select::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* ----- LISTE PERSONNALISÉE ----- */
    .stock-list-custom {
        position: absolute;
        left: 0;
        top: calc(100% + 6px);
        width: 100% !important;
        max-height: 260px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        padding: 6px 0;
        z-index: 9999;
        list-style: none;
        margin: 0;
        display: none;
    }
    .stock-list-custom li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 16px;
        cursor: pointer;
        transition: background 0.15s;
        border-radius: 8px;
        margin: 2px 6px;
        font-size: 0.95rem;
        color: #0a192f;
    }
    .stock-list-custom li:hover {
        background: #f1f5f9;
    }
    .stock-list-custom li .table-name {
        font-weight: 500;
    }
    .stock-list-custom li .table-pdv {
        font-size: 0.8rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 10px;
        border-radius: 20px;
    }
    .stock-list-custom li.selected {
        background: #e6f7fa;
        border-left: 3px solid #0a7b8c;
    }
    .stock-list-custom li.hidden {
        display: none;
    }

    /* ----- FILTRES ----- */
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

    /* ----- RESPONSIVE ----- */
    @media (max-width: 768px) {
        .filters-container-stock .filter-group {
            min-width: 150px;
            flex: 0 0 auto;
        }
        .filters-container-stock {
            padding: 10px 12px;
        }
        .header-affectation {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .header-left {
            flex-wrap: wrap;
        }
        .header-title {
            font-size: 1.4rem;
        }
        .header-right {
            max-width: 100%;
            justify-content: stretch;
            flex: 1 1 auto;
            min-width: 100%;
        }
        .stock-search-input-group {
            height: 44px;
        }
    }
</style>

<!-- ==================================================== -->
<!-- SCRIPTS JAVASCRIPT                                   -->
<!-- ==================================================== -->
<script>
    // ========== GESTION DE LA LISTE PERSONNALISÉE ==========
    $(document).ready(function() {
        var stockMap = {};
        @foreach ($tables as $data)
            stockMap["{{ strtolower($data->nom) }}"] = {{ $data->id }};
        @endforeach

        var $searchInput = $('#stock_search');
        var $list = $('#stock-list-custom');
        var $hiddenId = $('#table_id');

        $searchInput.on('focus', function() {
            filterList();
            $list.show();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.stock-search-input-group').length) {
                $list.hide();
            }
        });

        $searchInput.on('input', function() {
            filterList();
            var inputVal = $(this).val().trim().toLowerCase();
            var foundId = stockMap[inputVal];
            if (foundId !== undefined) {
                $hiddenId.val(foundId);
                selectItemByValue(inputVal);
                triggerStockChange();
            }
        });

        function filterList() {
            var query = $searchInput.val().trim().toLowerCase();
            var hasVisible = false;
            $list.find('li').each(function() {
                var name = $(this).data('name') || '';
                var text = $(this).text().toLowerCase();
                if (name.includes(query) || text.includes(query)) {
                    $(this).removeClass('hidden');
                    hasVisible = true;
                } else {
                    $(this).addClass('hidden');
                }
            });
            if (hasVisible && $searchInput.is(':focus')) {
                $list.show();
            } else {
                $list.hide();
            }
        }

        $list.on('click', 'li:not(.hidden)', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $searchInput.val(name);
            $hiddenId.val(id);
            selectItemByValue(name);
            $list.hide();
            triggerStockChange();
        });

        function selectItemByValue(value) {
            $list.find('li').removeClass('selected');
            $list.find('li').each(function() {
                if ($(this).data('name') === value) {
                    $(this).addClass('selected');
                }
            });
        }

        function triggerStockChange() {
            var stockId = $hiddenId.val();
            if (!stockId) return;
            $.get("{{ url('/refresh_affectation_table_utilisateur') }}", {
                table_id: stockId,
            }, function(liste_r) {
                $("#bloc_1").hide();
                $("#bloc_2").hide();
                $("#bloc_3").show();
                $("#bloc_3").html(liste_r);
                resetFiltersAffectation();
                var newName = Object.keys(stockMap).find(key => stockMap[key] == stockId);
                if (newName) {
                    $searchInput.val(newName);
                    selectItemByValue(newName);
                }
            });
        }

        var initialId = $hiddenId.val();
        var initialName = Object.keys(stockMap).find(key => stockMap[key] == initialId);
        if (initialName) {
            $searchInput.val(initialName);
            selectItemByValue(initialName);
        }
        $list.hide();
    });

    // ========== GESTION DES FILTRES ==========
    (function() {
        var stockId = {{ $table_id }};

        window.filterAffectation = function() {
            var filterNom = $('#filterNomUtilisateur').val().toLowerCase().trim();
            var filterFonction = $('#filterFonction').val();
            var filterStatut = $('#filterStatutAffectation').val();

            var newIndex = 1;

            $('#affectationTableBody tr').each(function() {
                var $row = $(this);
                var showRow = true;

                var nomValue = ($row.data('nom') || '').toLowerCase();
                if (filterNom && !nomValue.includes(filterNom)) {
                    showRow = false;
                }

                if (showRow && filterFonction !== 'all') {
                    var fonctionValue = $row.data('fonction') || '';
                    if (fonctionValue !== filterFonction) {
                        showRow = false;
                    }
                }

                if (showRow && filterStatut !== 'all') {
                    var affecte = parseInt($row.data('affecte'), 10);
                    if (filterStatut === 'affecte' && affecte !== 1) {
                        showRow = false;
                    } else if (filterStatut === 'non_affecte' && affecte !== 0) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    $row.show();
                    $row.find('td:first-child').text(newIndex);
                    newIndex++;
                } else {
                    $row.hide();
                }
            });
        };

        function saveFiltersAffectation() {
            var filters = {
                nom: $('#filterNomUtilisateur').val(),
                fonction: $('#filterFonction').val(),
                statut: $('#filterStatutAffectation').val()
            };
            localStorage.setItem('affectationStockFilters_' + stockId, JSON.stringify(filters));
        }

        function loadFiltersAffectation() {
            var key = 'affectationStockFilters_' + stockId;
            var saved = localStorage.getItem(key);
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterNomUtilisateur').val(filters.nom || '');
                $('#filterFonction').val(filters.fonction || 'all');
                $('#filterStatutAffectation').val(filters.statut || 'all');
                return true;
            }
            return false;
        }

        window.resetFiltersAffectation = function() {
            $('#filterNomUtilisateur').val('');
            $('#filterFonction').val('all');
            $('#filterStatutAffectation').val('all');
            saveFiltersAffectation();
            filterAffectation();
            var msg = $('#msg');
            if (msg.length) {
                msg.html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
                msg.css('display', 'flex');
                setTimeout(function() {
                    msg.html('');
                    msg.css('display', 'none');
                }, 3000);
            }
        };

        var filterTimeout;
        function debouncedFilterAffectation() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                filterAffectation();
                saveFiltersAffectation();
            }, 300);
        }

        $(document).ready(function() {
            loadFiltersAffectation();
            filterAffectation();

            $('#filterNomUtilisateur, #filterFonction, #filterStatutAffectation')
                .on('change keyup', function() {
                    debouncedFilterAffectation();
                });

            $('#resetFiltersAffectation').click(function(e) {
                e.preventDefault();
                resetFiltersAffectation();
            });
        });
    })();
</script>
