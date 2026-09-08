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
use App\Models\Articles;
use App\Models\articlestocks;
use App\Models\Typeventes;
use Illuminate\Support\Facades\Auth;
use App\Models\Ressources;
?>

<style>
    /* Vos styles inchangés */
    .filters-container-stock { display: flex; flex-wrap: nowrap; gap: 12px; overflow-x: auto; padding: 12px 16px; background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 20px; align-items: flex-end; }
    .filters-container-stock .filter-group { flex: 1 1 0; min-width: 120px; }
    .filters-container-stock .filter-group label { font-weight: 600; font-size: 0.7rem; text-transform: uppercase; color: #0a192f; display: flex; align-items: center; gap: 5px; margin-bottom: 4px; }
    .filters-container-stock .filter-group .form-control { width: 100%; height: 36px; border-radius: 14px; border: 1px solid #e2e8f0; padding: 6px 12px; font-size: 0.85rem; }
    .filters-container-stock .filter-group .form-control:focus { border-color: #0a192f; box-shadow: 0 0 0 3px rgba(10,25,47,0.15); }
    .filters-container-stock .filter-group .btn-reset { background: #64748b; color: white; border: none; border-radius: 40px; padding: 8px 18px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.25s; white-space: nowrap; }
    .filters-container-stock .filter-group .btn-reset:hover { background: #475569; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(100,116,139,0.3); }
    @media (max-width: 768px) { .filters-container-stock .filter-group { min-width: 150px; flex: 0 0 auto; } .filters-container-stock { padding: 10px 12px; } }
    .stock-dropdown { width: 100%; }
    .stock-dropdown .dropdown-toggle { height: 46px !important; border-radius: 14px !important; border: 1px solid #e2e8f0 !important; padding: 10px 36px 10px 16px !important; font-size: .95rem; background: #fff; transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.02); width: 100%; text-align: left; display: flex; align-items: center; color: #1e2a3e; font-weight: 500; position: relative; }
    .stock-dropdown .dropdown-toggle:focus { border-color: #0a192f !important; box-shadow: 0 0 0 4px rgba(10,25,47,.1) !important; transform: translateY(-2px); }
    .stock-dropdown .dropdown-toggle span { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .stock-dropdown .dropdown-toggle .caret { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: .8rem; color: #94a3b8; flex-shrink: 0; }
    .stock-dropdown .dropdown-toggle::after { display: none; }
    .stock-dropdown .dropdown-menu { width: 100%; border-radius: 14px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2); border: 1px solid #e2e8f0; margin-top: 5px; padding: 10px; max-height: 200px; overflow-y: auto; }
    .stock-dropdown .dropdown-menu .checkbox { padding: 5px 0; }
    .stock-dropdown .dropdown-menu .checkbox label { font-weight: 500; margin: 0; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: .9rem; }
    .stock-dropdown .dropdown-menu .checkbox input[type="checkbox"] { margin: 0; width: 16px; height: 16px; accent-color: #e31b23; flex-shrink: 0; }
    #transferModal .form-control, #transferModal input.form-control, #transferModal select.form-control, #transferModal textarea.form-control { height: 46px !important; padding: 10px 16px !important; font-size: 0.95rem; border-radius: 14px !important; }
    #transferModal textarea.form-control { height: 46px !important; resize: vertical; }
    #transferModal .stock-dropdown .dropdown-toggle { height: 46px !important; padding: 10px 36px 10px 16px !important; }
    #transfer_msg { display: none !important; visibility: hidden !important; opacity: 0 !important; margin: 0 !important; padding: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important; min-height: 0 !important; height: 0 !important; overflow: hidden !important; }
    #transfer_msg:not(:empty) { display: inline-flex !important; visibility: visible !important; opacity: 1 !important; height: auto !important; margin-top: 16px !important; padding: 10px 18px !important; background: white !important; border-radius: 50px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important; gap: 10px; font-weight: 600; font-size: 0.8rem; animation: slideInMsg 0.3s ease-out; align-items: center; justify-content: center; width: fit-content; margin-left: auto; margin-right: auto; }
    #transfer_msg:not(:empty):has(i.zmdi-check-circle) { background: linear-gradient(95deg,#d1fae5,#a7f3d0) !important; color: #065f46; border-left: 4px solid #10b981; }
    #transfer_msg:not(:empty):has(i.zmdi-close-circle) { background: linear-gradient(95deg,#fee2e2,#fecaca) !important; color: #991b1b; border-left: 4px solid #ef4444; }
    @keyframes slideInMsg { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
</style>

<h4 style="color:rgba(0, 0, 0, 0.6);">
    <i style="font-size: 40px;" class="zmdi zmdi-settings text-info"></i> Liste d'article du stock
    <span class="text-info">({{ $nom }})</span>
</h4>

<!-- FILTRES -->
<div class="filters-container-stock">
    <div class="filter-group">
        <label><i class="zmdi zmdi-label text-danger"></i> Nom</label>
        <input type="text" id="filterNomStock" class="form-control" placeholder="Rechercher...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-folder text-danger"></i> Catégorie</label>
        <select id="filterCategorieStock" class="form-control">
            <option value="all">Toutes</option>
            @foreach ($societes as $categorie) <option value="cat_{{ $categorie->id }}">{{ $categorie->nom }}</option> @endforeach
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
                    @if ($activite->id == Auth::user()->activite_id) <option value="act_{{ $activite->id }}" selected>{{ $activite->nom }}</option> @endif
                @endif
            @endforeach
        </select>
    </div>
    <!-- Filtre Utilisateur supprimé -->
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
        <label>&nbsp;</label>
        <button id="resetFiltersStock" class="btn-reset"><i class="zmdi zmdi-refresh"></i> Réinitialiser</button>
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
                        <!-- Colonne Utilisateur supprimée -->
                        <th style="padding-top: 5px;padding-bottom: 5px;">Date d'expiration</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    {{ !($i = 1) }}
                    @if ($stock_id == 0)
                        @foreach ($articles as $data)
                            <tr id="row_{{ $data->id }}">
                                <td class="row-num" style="padding-top:5px;padding-bottom:5px;">{{ $i }}</td>
                                <td class="nom-cell" data-nom="{{ $data->nom_article }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ $data->nom_article }} ({{ Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A' }})
                                </td>
                                <td class="categorie-cell" data-categorie-id="{{ $data->societe_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }}
                                </td>
                                <td class="activite-cell" data-activite-id="{{ $data->activite_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    @if ($data->activite_id == 0 || $data->activite_id == '0') Aucune
                                    @else {{ Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune' }} @endif
                                </td>
                                <td class="prix-cell" data-prix="{{ $data->prix }}" data-devise="{{ $data->devise }}" style="padding-top:5px;padding-bottom:5px;">
                                    <?php
                                    if ($data->devise == 0) {
                                        echo '<span class="text-success">D : </span>' . number_format($data->prix_detail, 2, ',', ' ') . '(USD), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . 'USD';
                                    } else {
                                        echo '<span class="text-success">D : </span>' . number_format($data->prix_detail, 2, ',', ' ') . '(CDF), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . '(CDF)';
                                    }
                                    ?>
                                </td>
                                <td class="stock-cell" data-stock="{{ $data->stock }}" style="padding-top:5px;padding-bottom:5px;">
                                    @if ($data->avoir_stock == 1)
                                        <?php if($data->stock <= $data->seuil_minimum){ ?><span class="text-danger">{{ $data->stock }}</span><?php } ?>
                                        <?php if($data->stock > $data->seuil_minimum){ ?><span>{{ $data->stock }}</span><?php } ?>
                                    @else - @endif
                                </td>
                                <td class="seuil-cell" data-seuil-min="{{ $data->seuil_minimum }}" data-seuil-max="{{ $data->seuil_maximum }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ $data->seuil_minimum . ' - ' . $data->seuil_maximum }}
                                </td>
                                <!-- Cellule Utilisateur supprimée -->
                                <td class="date-cell" data-date-expiration="{{ $data->date_expiration }}" style="padding-top:5px;padding-bottom:5px;">
                                    <?php if($data->date_expiration  == "00/00/0000"){ ?>
                                    <span class="text-info">{{ $data->date_expiration }} (N'expire pas)</span>
                                    <?php }else{ ?>
                                    <?php
                                    $target = 0;
                                    $semaine = ['Dimanche','Lundi',' Mardi ','Mercredi ','Jeudi','Vendredi','Samedi'];
                                    $mois = [1=>'Janvier','Février ','Mars ','Avril ','Mai ','Juin','Juillet','Août ','Septembre','Octobre','Novembre','Décembre'];
                                    $__d1 = date('d'); $__m1 = date('m'); $__y1 = date('Y');
                                    $__d2 = explode('/', $data->date_expiration)[0];
                                    $__m2 = explode('/', $data->date_expiration)[1];
                                    $__y2 = explode('/', $data->date_expiration)[2];
                                    $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                                    $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                                    while (strtotime($date_1) <= strtotime($date_2)) {
                                        $jours = 1;
                                        $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                                        if ($semaine[date('w', $valeur_date)] != '') { $target++; }
                                        $datedd = date('m/d/Y', strtotime(date('' . explode('/', $date_1)[0] . '/' . explode('/', $date_1)[1] . '/' . explode('/', $date_1)[2] . '') . ' + ' . $jours . ' days'));
                                        $date_1 = explode('/', $datedd)[1] . '/' . explode('/', $datedd)[0] . '/' . explode('/', $datedd)[2];
                                        $date_1 = explode('/', $datedd)[0] . '/' . explode('/', $datedd)[1] . '/' . explode('/', $datedd)[2];
                                    }
                                    if ($target == 0) { echo "<span class='text-danger'>Expiré depuis $data->date_expiration </span>"; }
                                    else { echo "<span class='text-success'>$data->date_expiration (Dans $target jours) </span>"; }
                                    ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            {{ !$i++ }}
                        @endforeach
                        <tr id="noResultRow" style="display: none;"><td colspan="8"><i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de recherche.</td></tr>
                    @else
                        @foreach ($articles as $dataa)
                            <?php $data = Articles::where('id', $dataa->article_id)->first(); ?>
                            <tr id="row_{{ $data->id }}">
                                <td class="row-num" style="padding-top:5px;padding-bottom:5px;">{{ $i }}</td>
                                <td class="nom-cell" data-nom="{{ $data->nom_article }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ $data->nom_article }} ({{ Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A' }})
                                </td>
                                <td class="categorie-cell" data-categorie-id="{{ $data->societe_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }}
                                </td>
                                <td class="activite-cell" data-activite-id="{{ $data->activite_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    @if ($data->activite_id == 0 || $data->activite_id == '0') Aucune
                                    @else {{ Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune' }} @endif
                                </td>
                                <td class="prix-cell" data-prix="{{ $dataa->prix }}" data-devise="{{ $dataa->devise }}" style="padding-top:5px;padding-bottom:5px;">
                                    <?php
                                    if ($dataa->devise == 0) {
                                        echo '<span class="text-success">D : </span>' . number_format($dataa->prix_detail, 2, ',', ' ') . '(USD), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . 'USD';
                                    } else {
                                        echo '<span class="text-success">D : </span>' . number_format($dataa->prix_detail, 2, ',', ' ') . '(CDF), <span class="text-success">G : </span> ' . number_format($data->prix_gros, 2, ',', ' ') . '(CDF)';
                                    }
                                    ?>
                                </td>
                                <td class="stock-cell" data-stock="{{ $dataa->stock }}" style="padding-top:5px;padding-bottom:5px;text-align:center;">
                                    @if ($dataa->avoir_stock == 1)
                                        <?php if($dataa->stock == 0){ ?><span class="text-danger">{{ $dataa->stock }}</span><?php } ?>
                                        <?php if($dataa->stock > 0){ ?><span>{{ $dataa->stock }}</span><?php } ?>
                                    @else <i class="zmdi zmdi-close-circle text-danger"></i> @endif
                                </td>
                                <td style="text-align:center;" class="seuil-cell" data-seuil-min="{{ $data->seuil_minimum }}" data-seuil-max="{{ $data->seuil_maximum }}" style="padding-top:5px;padding-bottom:5px;">
                                    <i class="zmdi zmdi-close-circle text-danger"></i>
                                </td>
                                <!-- Cellule Utilisateur supprimée -->
                                <td class="date-cell" data-date-expiration="{{ $data->date_expiration }}" style="padding-top:5px;padding-bottom:5px;">
                                    <?php if($data->date_expiration  == "00/00/0000"){ ?>
                                    <span class="text-info">{{ $data->date_expiration }} (N'expire pas)</span>
                                    <?php }else{ ?>
                                    <?php
                                    $target = 0;
                                    $semaine = ['Dimanche','Lundi',' Mardi ','Mercredi ','Jeudi','Vendredi','Samedi'];
                                    $mois = [1=>'Janvier','Février ','Mars ','Avril ','Mai ','Juin','Juillet','Août ','Septembre','Octobre','Novembre','Décembre'];
                                    $__d1 = date('d'); $__m1 = date('m'); $__y1 = date('Y');
                                    $__d2 = explode('/', $data->date_expiration)[0];
                                    $__m2 = explode('/', $data->date_expiration)[1];
                                    $__y2 = explode('/', $data->date_expiration)[2];
                                    $date_1 = date('' . $__m1 . '/' . $__d1 . '/' . $__y1 . '');
                                    $date_2 = date('' . $__m2 . '/' . $__d2 . '/' . $__y2 . '');
                                    while (strtotime($date_1) <= strtotime($date_2)) {
                                        $jours = 1;
                                        $valeur_date = strtotime(explode('/', $date_1)[2] . '-' . explode('/', $date_1)[0] . '-' . explode('/', $date_1)[1]);
                                        if ($semaine[date('w', $valeur_date)] != '') { $target++; }
                                        $datedd = date('m/d/Y', strtotime(date('' . explode('/', $date_1)[0] . '/' . explode('/', $date_1)[1] . '/' . explode('/', $date_1)[2] . '') . ' + ' . $jours . ' days'));
                                        $date_1 = explode('/', $datedd)[1] . '/' . explode('/', $datedd)[0] . '/' . explode('/', $datedd)[2];
                                        $date_1 = explode('/', $datedd)[0] . '/' . explode('/', $datedd)[1] . '/' . explode('/', $datedd)[2];
                                    }
                                    if ($target == 0) { echo "<span class='text-danger'>Expiré depuis $data->date_expiration </span>"; }
                                    else { echo "<span class='text-success'>$data->date_expiration (Dans $target jours) </span>"; }
                                    ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            {{ !$i++ }}
                        @endforeach
                        <tr id="noResultRow" style="display: none;"><td colspan="8"><i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de recherche.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // ========== FILTRES (modifié) ==========
    (function() {
        var stockId = {{ $stock_id }};

        function filterArticlesStock() {
            var filterNom = $('#filterNomStock').val().toLowerCase().trim();
            var filterCategorie = $('#filterCategorieStock').val();
            var filterActivite = $('#filterActiviteStock').val();
            // Filtre Utilisateur supprimé
            var filterStock = $('#filterStockStock').val();
            var filterExpiration = $('#filterExpirationStock').val();

            var visibleCount = 0, newIndex = 1;
            $('#noResultRow').hide();

            $('#articlesTableBody tr:not(#noResultRow)').each(function() {
                var $row = $(this), showRow = true;
                var nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                if (filterNom && !nomValue.includes(filterNom)) showRow = false;

                if (showRow && filterCategorie !== 'all') {
                    var categorieId = $row.find('.categorie-cell').data('categorie-id');
                    var currentCat = categorieId != null ? String(categorieId) : '';
                    if (filterCategorie.startsWith('cat_')) {
                        var targetCat = filterCategorie.replace('cat_', '');
                        if (currentCat !== targetCat) showRow = false;
                    }
                }

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

                // Filtre Utilisateur supprimé

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

                if (showRow && filterExpiration !== 'all') {
                    var dateStr = $row.find('.date-cell').data('date-expiration');
                    var daysLeft = null;
                    if (dateStr && dateStr !== '00/00/0000') {
                        var parts = dateStr.split('/');
                        var expDate = new Date(parts[2], parts[1] - 1, parts[0]);
                        var today = new Date(); today.setHours(0,0,0,0);
                        var diffTime = expDate - today;
                        daysLeft = Math.ceil(diffTime / (1000*60*60*24));
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

            var badge = $('#articleCountBadge');
            if (badge.length) badge.find('span').text(visibleCount);
            if (visibleCount === 0) $('#noResultRow').show();
        }

        function saveFiltersStock() {
            var filters = {
                nom: $('#filterNomStock').val(),
                categorie: $('#filterCategorieStock').val(),
                activite: $('#filterActiviteStock').val(),
                // user: ... supprimé
                stock: $('#filterStockStock').val(),
                expiration: $('#filterExpirationStock').val()
            };
            localStorage.setItem('articleStockFilters_' + stockId, JSON.stringify(filters));
        }

        function loadFiltersStock() {
            var key = 'articleStockFilters_' + stockId;
            var saved = localStorage.getItem(key);
            if (saved) {
                var filters = JSON.parse(saved);
                $('#filterNomStock').val(filters.nom || '');
                $('#filterCategorieStock').val(filters.categorie || 'all');
                $('#filterActiviteStock').val(filters.activite || 'all');
                // filterUser supprimé
                $('#filterStockStock').val(filters.stock || 'all');
                $('#filterExpirationStock').val(filters.expiration || 'all');
                return true;
            }
            return false;
        }

        function resetFiltersStock() {
            $('#filterNomStock').val('');
            $('#filterCategorieStock').val('all');
            $('#filterActiviteStock').val('all');
            // filterUser supprimé
            $('#filterStockStock').val('all');
            $('#filterExpirationStock').val('all');
            saveFiltersStock();
            filterArticlesStock();
            var msg = $('#msg');
            if (msg.length) {
                msg.html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés');
                msg.css('display','flex');
                setTimeout(function() { msg.html(''); msg.css('display','none'); }, 3000);
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
            // Seul l'événement 'keyup' est conservé (le 'change' a été supprimé)
            $('#filterNomStock, #filterCategorieStock, #filterActiviteStock, #filterStockStock, #filterExpirationStock')
                .on('keyup', function() { debouncedFilterStock(); });
            $('#resetFiltersStock').click(function(e) {
                e.preventDefault();
                resetFiltersStock();
            });
        });
    })();
</script>