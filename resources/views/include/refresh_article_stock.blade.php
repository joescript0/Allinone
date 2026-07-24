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
    <select class="form-control" style="border-color: transparent;padding-top: 0px;padding-bottom: 0px;font-size: 17px;color:rgba(0, 0, 0, 0.6);margin-top:10px;" name="stock_select" id="stock_select">
        @if ($stock_id == 0)
            <option selected value="0"> Stock principal {{ $articles->count() }}</option>
            @foreach ($stocks as $data) <option value="{{ $data->id }}">{{ strtolower($data->nom) }}</option> @endforeach
        @else
            <option selected value="0"> Stock principal {{ $articles->count() }}</option>
            @foreach ($stocks as $data)
                @if ($data->id == $stock_id) <option selected value="{{ $data->id }}">{{ strtolower($data->nom) }}</option>
                @else <option value="{{ $data->id }}">{{ strtolower($data->nom) }}</option> @endif
            @endforeach
        @endif
    </select>
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
    <div class="filter-group">
        <label><i class="zmdi zmdi-accounts text-danger"></i> Utilisateur</label>
        <select id="filterUserStock" class="form-control">
            <option value="all">Tous</option>
            @php $uniqueUsers = []; @endphp
            @foreach ($utilisateurs as $data)
                @if (!in_array($data->id, $uniqueUsers))
                    @php $uniqueUsers[] = $data->id; $userName = User::where('id', $data->id)->first()['name'] ?? 'N/A'; @endphp
                    @if ($data->id == Auth::user()->id) <option value="{{ $data->id }}" selected>(Vous)</option>
                    @else <option value="{{ $data->id }}">{{ $userName }}</option> @endif
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
                        <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Date d'expiration</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
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
                                <td class="user-cell" data-user-id="{{ $data->user_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                </td>
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
                                <td style="text-align: center;padding-top:5px;padding-bottom:5px;">
                                    <?php
                                    $edit = 0; $delete = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) {
                                        if (Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()->count() != 0) {
                                            $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                            $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                        }
                                    }
                                    ?>
                                    <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>&nbsp;
                                    <?php } else { ?>
                                    <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>&nbsp;
                                    <?php } ?>

                                    <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="transfer_<?= $i ?>" href="#" data-id="<?= $data->id ?>"
                                        data-article='<?= json_encode([
                                            'id' => $data->id,
                                            'nom_article' => $data->nom_article,
                                            'categorie_nom' => Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A',
                                            'prix_detail' => $data->prix_detail,
                                            'prix_gros' => $data->prix_gros,
                                            'devise' => $data->devise,
                                            'stock' => $data->stock,
                                            'seuil_minimum' => $data->seuil_minimum,
                                            'taille_lot' => $data->taille_lot,
                                            'activite_id' => $data->activite_id,
                                            'avoir_stock' => $data->avoir_stock,
                                            'activite_nom' => $data->activite_id == 0 || $data->activite_id == '0' ? 'Aucune' : Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune',
                                            'user_id' => $data->user_id,
                                            'user_nom' => User::where('id', $data->user_id)->first()['name'] ?? 'N/A',
                                        ]) ?>'
                                        class="transfer-btn" data-stock-id="{{ $stock_id }}">
                                        <i class="zmdi zmdi-swap" style="color:#333;"></i>
                                    </a>&nbsp;
                                    <?php } else { ?>
                                    <a id="transfer_r<?= $i ?>" href="#" class="transfer-disabled"><i class="zmdi zmdi-swap" style="color:#999;"></i></a>&nbsp;
                                    <?php } ?>

                                    <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;
                                    <?php } else { ?>
                                    <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;
                                    <?php } ?>
                                    <script>
                                        $("#edit_<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $.get("{{ url('/refresh_editarticle') }}", { user_id: <?= $data->id ?> }, function(refresh_editarticle) {
                                                $("#bloc_1").hide(); $("#bloc_2").hide(); $("#bloc_3").show(); $("#bloc_3").html(refresh_editarticle);
                                            });
                                        });
                                        $("#edit_r<?= $i ?>").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
                                        $("#delete_r<?= $i ?>").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
                                        $("#delete_<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#element").html("<?= $data->nom_article . '(' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>");
                                            $("#data_id").html("<?= $data->id ?>");
                                            $("#btn_sup").trigger("click");
                                        });
                                    </script>
                                </td>
                            </tr>
                            {{ !$i++ }}
                        @endforeach
                        <tr id="noResultRow" style="display: none;"><td colspan="10"><i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de recherche.</td></tr>
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
                                <td class="user-cell" data-user-id="{{ $data->user_id }}" style="padding-top:5px;padding-bottom:5px;">
                                    {{ User::where('id', $data->user_id)->first()['name'] ?? 'N/A' }}
                                </td>
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
                                <td style="text-align: center;padding-top:5px;padding-bottom:5px;">
                                    <?php
                                    $edit = 0; $delete = 0;
                                    if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) {
                                        if (Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()->count() != 0) {
                                            $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                            $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                        }
                                    }
                                    ?>
                                    <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="edit_<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>&nbsp;
                                    <?php } else { ?>
                                    <a id="edit_r<?= $i ?>" href="#"><i class="zmdi zmdi-edit text-success"></i></a>&nbsp;
                                    <?php } ?>

                                    <?php if (($edit == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="transfer_<?= $i ?>" href="#" data-id="<?= $data->id ?>"
                                        data-article='<?= json_encode([
                                            'id' => $data->id,
                                            'nom_article' => $data->nom_article,
                                            'categorie_nom' => Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A',
                                            'prix_detail' => $dataa->prix_detail,
                                            'prix_gros' => $dataa->prix_gros,
                                            'devise' => $dataa->devise,
                                            'stock' => $dataa->stock,
                                            'seuil_minimum' => $data->seuil_minimum,
                                            'taille_lot' => $dataa->taille_lot,
                                            'activite_id' => $data->activite_id,
                                            'avoir_stock' => $dataa->avoir_stock,
                                            'activite_nom' => $data->activite_id == 0 || $data->activite_id == '0' ? 'Aucune' : Activites::where('id', $data->activite_id)->first()['nom'] ?? 'Aucune',
                                            'user_id' => $dataa->user_id,
                                            'user_nom' => User::where('id', $data->user_id)->first()['name'] ?? 'N/A',
                                        ]) ?>'
                                        class="transfer-btn" data-stock-id="{{ $stock_id }}">
                                        <i class="zmdi zmdi-swap" style="color:#333;"></i>
                                    </a>&nbsp;
                                    <?php } else { ?>
                                    <a id="transfer_r<?= $i ?>" href="#" class="transfer-disabled"><i class="zmdi zmdi-swap" style="color:#999;"></i></a>&nbsp;
                                    <?php } ?>

                                    <?php if (($delete == 1) || (Auth::user()->role == 0)) { ?>
                                    <a id="delete_<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;
                                    <?php } else { ?>
                                    <a id="delete_r<?= $i ?>" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>&nbsp;
                                    <?php } ?>
                                    <script>
                                        $("#edit_<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $.get("{{ url('/refresh_editarticle') }}", { user_id: <?= $data->id ?> }, function(refresh_editarticle) {
                                                $("#bloc_1").hide(); $("#bloc_2").hide(); $("#bloc_3").show(); $("#bloc_3").html(refresh_editarticle);
                                            });
                                        });
                                        $("#edit_r<?= $i ?>").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
                                        $("#delete_r<?= $i ?>").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
                                        $("#delete_<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#element").html("<?= $data->nom_article . '(' . Societes::where('id', $data->societe_id)->first()['nom'] . ')' ?>");
                                            $("#data_id").html("<?= $data->id ?>");
                                            $("#btn_sup").trigger("click");
                                        });
                                    </script>
                                </td>
                            </tr>
                            {{ !$i++ }}
                        @endforeach
                        <tr id="noResultRow" style="display: none;"><td colspan="10"><i class="zmdi zmdi-info-outline"></i> Aucun article ne correspond à vos critères de recherche.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TRANSFERT -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0a192f, #1e3a5f); border-radius: 20px 20px 0 0;">
                <h5 class="modal-title text-white"><i class="zmdi zmdi-swap"></i> Transférer l'article</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <form id="form_transfert" action="#" method="post">
                    @csrf
                    <input type="hidden" id="transfer_article_id" name="transfer_article_id" value="">
                    <input type="hidden" id="transfer_source_stock_id" name="transfer_source_stock_id" value="{{ $stock_id }}">

                    <!-- EN-TÊTE -->
                    <div style="background: #f7faff; padding: 18px; border-radius: 16px; margin-bottom: 25px; border-left: 6px solid #0a192f; border: 1px solid #e2e8f0;">
                        <div style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #0a192f; letter-spacing: 0.5px; margin-bottom: 15px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                            <i class="zmdi zmdi-info text-info"></i> INFORMATIONS DE L'ARTICLE (non modifiables)
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-label text-danger"></i> Nom :</span> <span id="transfer_nom" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-store text-danger"></i> Catégorie :</span> <span id="transfer_categorie" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-toll text-danger"></i> Activité actuelle :</span> <span id="transfer_activite_actuelle" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-money text-danger"></i> Prix détail :</span> <span id="transfer_prix_detail" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-money text-danger"></i> Prix gros :</span> <span id="transfer_prix_gros" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                            <div class="col-md-4"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-storage text-danger"></i> Stock actuel :</span> <span id="transfer_stock" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                        </div>
                        <div class="row" style="margin-bottom:0;">
                            <div class="col-md-12"><span style="font-weight:600;color:#2d3748;font-size:0.8rem;"><i class="zmdi zmdi-accounts text-danger"></i> Utilisateur actuel :</span> <span id="transfer_user_actuel" style="font-weight:500;color:#0a192f;margin-left:8px;">-</span></div>
                        </div>
                    </div>

                    <!-- DESTINATION -->
                    <div style="font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #0a192f; letter-spacing: 0.5px; margin-bottom: 15px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                        <i class="zmdi zmdi-swap text-warning"></i> DESTINATION DU TRANSFERT
                    </div>

                    <!-- Ligne 1 -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-view-list text-danger"></i> Liste(s) de stock <span class="text-danger">*</span></label>
                                <div class="dropdown stock-dropdown">
                                    <button class="form-control dropdown-toggle" type="button" id="dropdownStock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align:left;background:white;border:1px solid #e2e8f0;border-radius:14px;height:46px;display:flex;align-items:center;justify-content:space-between;">
                                        <span id="selectedStockText">Aucun stock sélectionné</span> <span class="caret">▼</span>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownStock" style="width:100%;padding:10px;max-height:200px;overflow-y:auto;">
                                        <div class="checkbox" style="padding:5px 0;"><label><input type="checkbox" class="stock-checkbox" value="none" checked> Aucun</label></div>
                                        @if($stock_id != 0)
                                            <div class="checkbox" style="padding:5px 0;"><label><input type="checkbox" class="stock-checkbox" value="0"> Stock principal</label></div>
                                        @endif
                                        @foreach ($stocks as $st)
                                            @if ($st->id != $stock_id)
                                                <div class="checkbox" style="padding:5px 0;"><label><input type="checkbox" class="stock-checkbox" value="{{ $st->id }}"> {{ $st->nom }}</label></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div id="selectedStockContainer"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-storage text-danger"></i> Quantité <span style="font-weight:normal;font-size:0.7rem;color:#6b7a8f;">(par stock sélectionné)</span></label>
                                <input type="number" id="transfert_quantite" name="transfert_quantite" class="form-control" value="1" step="1" placeholder="Ex: 5" autocomplete="off">
                                <small id="quantite_info" style="display:block;margin-top:4px;font-size:0.7rem;color:#6b7a8f;"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 2 -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-comment text-danger"></i> Motif du transfert <span class="text-danger">*</span></label>
                                <textarea id="transfert_commentaire" name="transfert_commentaire" class="form-control" rows="1" placeholder="Raison du transfert..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-money text-danger"></i> Prix de détail <span class="text-danger">*</span></label>
                                <input type="number" step="1" id="transfert_prix_detail_dest" name="transfert_prix_detail_dest" class="form-control" placeholder="Ex: 500">
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 3 -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-money text-danger"></i> Prix de gros <span class="text-danger">*</span></label>
                                <input type="number" step="1" id="transfert_prix_gros_dest" name="transfert_prix_gros_dest" class="form-control" placeholder="Ex: 300">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-storage text-danger"></i> Taille du lot <span class="text-danger">*</span></label>
                                <input type="number" step="1" id="transfert_taille_lot_dest" name="transfert_taille_lot_dest" class="form-control" placeholder="Ex: 12">
                            </div>
                        </div>
                    </div>

                    <!-- Ligne 4 -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700;font-size:0.75rem;text-transform:uppercase;color:#2d3748;"><i class="zmdi zmdi-money text-danger"></i> Devise <span class="text-danger">*</span></label>
                                <select id="transfert_devise_dest" name="transfert_devise_dest" class="form-control">
                                    <option value="">Sélectionnez une devise</option>
                                    <option value="0">USD</option>
                                    <option value="1">CDF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>

                    <!-- Message -->
                    <div class="row"><div class="col-lg-12" style="text-align:center;"><span id="transfer_msg"></span></div></div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:none;">
                <button id="transfer_annuler" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="zmdi zmdi-close-circle"></i> Annuler</button>
                <button id="transfer_submit" class="btn btn-info btn-sm"><i class="zmdi zmdi-swap"></i> Transférer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Gestion du changement de stock
    $("#stock_select").change(function(e) {
        e.preventDefault();
        $.get("{{ url('/refresh_article_stock') }}", { stock_id: $("#stock_select").val() }, function(liste_r) {
            $("#bloc_1").hide(); $("#bloc_2").hide(); $("#bloc_3").show(); $("#bloc_3").html(liste_r);
        });
    });

    // ========== FILTRES (inchangés) ==========
    (function() {
        var stockId = {{ $stock_id }};

        function filterArticlesStock() {
            var filterNom = $('#filterNomStock').val().toLowerCase().trim();
            var filterCategorie = $('#filterCategorieStock').val();
            var filterActivite = $('#filterActiviteStock').val();
            var filterUser = $('#filterUserStock').val();
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

                if (showRow && filterUser !== 'all') {
                    var userId = $row.find('.user-cell').data('user-id');
                    var currentUser = userId != null ? String(userId) : '';
                    if (currentUser !== filterUser) showRow = false;
                }

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
                user: $('#filterUserStock').val(),
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
                $('#filterUserStock').val(filters.user || 'all');
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
            $('#filterUserStock').val('all');
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
            $('#filterNomStock, #filterCategorieStock, #filterActiviteStock, #filterUserStock, #filterStockStock, #filterExpirationStock')
                .on('change keyup', function() { debouncedFilterStock(); });
            $('#resetFiltersStock').click(function(e) {
                e.preventDefault();
                resetFiltersStock();
            });
        });
    })();

    // =====================================================
    // GESTION DU TRANSFERT - RÉINITIALISATION TOTALE
    // =====================================================

    var stockActuelGlobal = 0;
    var seuilMinimumGlobal = 0;
    var avoirStockGlobal = 0;
    var sourceStockId = {{ $stock_id }};
    var isPrincipal = (sourceStockId == 0);

    // ---- Fonctions multi-select ----
    function updateStockSelection() {
        var checked = $('.stock-checkbox:not([value="none"]):checked');
        var noneChecked = $('.stock-checkbox[value="none"]').prop('checked');

        if (noneChecked) {
            $('.stock-checkbox:not([value="none"])').prop('checked', false);
            $('#selectedStockText').text('Aucun stock sélectionné');
            $('#selectedStockContainer').empty();
            updateQuantiteInfo();
            return;
        }

        var names = [], ids = [];
        checked.each(function() {
            var $cb = $(this);
            var label = $cb.closest('label').text().trim();
            names.push(label);
            ids.push($cb.val());
        });

        if (ids.length === 0) {
            $('#selectedStockText').text('Aucun stock sélectionné');
            $('.stock-checkbox[value="none"]').prop('checked', true);
        } else {
            $('#selectedStockText').text(names.join(', '));
            $('.stock-checkbox[value="none"]').prop('checked', false);
        }

        $('#selectedStockContainer').empty();
        ids.forEach(function(id) {
            $('<input>', { type: 'hidden', name: 'transfert_stock_id[]', value: id }).appendTo('#selectedStockContainer');
        });
        updateQuantiteInfo();
    }

    function updateQuantiteInfo() {
        var nbStocks = $('input[name="transfert_stock_id[]"]').length;
        var qte = parseInt($('#transfert_quantite').val()) || 0;
        var total = qte * nbStocks;
        if (nbStocks > 0) {
            $('#quantite_info').text('Total retiré du stock source : ' + total + ' unités (quantité × ' + nbStocks + ' stocks)');
        } else {
            $('#quantite_info').text('');
        }
    }

    $(document).on('change', '.stock-checkbox', function() {
        var $this = $(this);
        var val = $this.val();

        if (val === 'none') {
            if ($this.prop('checked')) {
                $('.stock-checkbox:not([value="none"])').prop('checked', false);
            }
        } else {
            if ($this.prop('checked')) {
                $('.stock-checkbox[value="none"]').prop('checked', false);
            }
            if ($('.stock-checkbox:not([value="none"]):checked').length === 0) {
                $('.stock-checkbox[value="none"]').prop('checked', true);
            }
        }
        updateStockSelection();
    });

    $(document).on('input', '#transfert_quantite', function() {
        updateQuantiteInfo();
    });

    // ---- Réinitialisation complète (tous les champs) ----
    function resetTransferModalCompletely() {
        // 1. Message
        $('#transfer_msg').html('').css('display', 'none');
        // 2. Quantité à 1 (via JS natif + événement)
        var qteInput = document.getElementById('transfert_quantite');
        if (qteInput) {
            qteInput.value = 1;
            qteInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        // 3. Commentaire
        $('#transfert_commentaire').val('');
        // 4. Prix
        $('#transfert_prix_detail_dest').val('');
        $('#transfert_prix_gros_dest').val('');
        // 5. Taille lot
        $('#transfert_taille_lot_dest').val('');
        // 6. Devise
        $('#transfert_devise_dest').val('');
        // 7. Sélection des stocks : "Aucun" coché
        $('.stock-checkbox[value="none"]').prop('checked', true);
        $('.stock-checkbox:not([value="none"])').prop('checked', false);
        $('#selectedStockText').text('Aucun stock sélectionné');
        $('#selectedStockContainer').empty();
        // 8. Info quantité
        $('#quantite_info').text('');
        // 9. Bouton submit
        $('#transfer_submit').prop('disabled', false).html('<i class="zmdi zmdi-swap"></i> Transférer');
        // 10. Champs cachés (ils seront écrasés à l'ouverture)
    }

    // ---- Ouverture du modal ----
    $(document).on('click', '.transfer-btn', function(e) {
        e.preventDefault();
        var articleData = $(this).data('article');
        if (!articleData) {
            alert('Données de l\'article manquantes');
            return;
        }

        // Récupération des données
        stockActuelGlobal = articleData.stock;
        seuilMinimumGlobal = articleData.seuil_minimum;
        avoirStockGlobal = articleData.avoir_stock;
        sourceStockId = $(this).data('stock-id') || 0;
        isPrincipal = (sourceStockId == 0);

        // Réinitialisation totale avant remplissage
        resetTransferModalCompletely();

        // Remplissage des informations de l'article
        var deviseLabel = (articleData.devise == 0) ? 'USD' : 'CDF';
        $('#transfer_nom').text(articleData.nom_article);
        $('#transfer_categorie').text(articleData.categorie_nom);
        $('#transfer_prix_detail').text(articleData.prix_detail + ' ' + deviseLabel);
        $('#transfer_prix_gros').text(articleData.prix_gros + ' ' + deviseLabel);
        $('#transfer_stock').text(articleData.stock);
        $('#transfer_activite_actuelle').text(articleData.activite_nom);
        $('#transfer_user_actuel').text(articleData.user_nom);

        // Champs cachés
        $('#transfer_article_id').val(articleData.id);
        $('#transfer_source_stock_id').val(sourceStockId);

        // Pré-remplir les champs de destination
        $('#transfert_prix_detail_dest').val(articleData.prix_detail);
        $('#transfert_prix_gros_dest').val(articleData.prix_gros);
        $('#transfert_taille_lot_dest').val(articleData.taille_lot);
        $('#transfert_devise_dest').val(articleData.devise);
        // La quantité reste à 1

        updateQuantiteInfo();

        // Ouvrir le modal
        $('#transferModal').modal('show');
    });

    // ---- Soumission ----
    $(document).on('click', '#transfer_submit', function(e) {
        e.preventDefault();

        // Éviter les soumissions multiples
        if ($(this).prop('disabled')) return;
        $(this).prop('disabled', true);

        $('#transfer_msg').html('').css('display', 'none');

        // Récupérer les stocks sélectionnés
        var stockIds = [];
        $('input[name="transfert_stock_id[]"]').each(function() { stockIds.push($(this).val()); });
        var nbStocks = stockIds.length;

        if (nbStocks === 0) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Sélectionnez au moins un stock de destination.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }
        if (stockIds.includes(sourceStockId.toString())) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Vous ne pouvez pas transférer vers le même stock.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }

        var commentaire = $('#transfert_commentaire').val().trim();
        if (commentaire == '' || commentaire.length < 3) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez saisir un motif de transfert (minimum 3 caractères).');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }

        var qte = parseInt($('#transfert_quantite').val());
        if (isNaN(qte) || qte < 0) { qte = 0; $('#transfert_quantite').val(0); }
        var totalTransfert = qte * nbStocks;

        // Contrôle du stock
        if (avoirStockGlobal == 1) {
            if (qte < 1) {
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être un nombre entier positif (car le stock est limité).');
                setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
                $(this).prop('disabled', false);
                return;
            }
            var maxTransferable;
            if (isPrincipal) {
                maxTransferable = stockActuelGlobal - seuilMinimumGlobal;
                if (totalTransfert > maxTransferable) {
                    $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité totale à transférer (' + totalTransfert + ') dépasse le stock disponible après seuil (' + maxTransferable + ').');
                    setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
                    $(this).prop('disabled', false);
                    return;
                }
            } else {
                maxTransferable = stockActuelGlobal;
                if (totalTransfert > maxTransferable) {
                    $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité totale à transférer (' + totalTransfert + ') dépasse le stock disponible (' + maxTransferable + ').');
                    setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
                    $(this).prop('disabled', false);
                    return;
                }
            }
        }

        var prix_detail = parseInt($('#transfert_prix_detail_dest').val());
        if (isNaN(prix_detail) || prix_detail < 0) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Le prix de détail doit être un nombre entier positif.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }
        var prix_gros = parseInt($('#transfert_prix_gros_dest').val());
        if (isNaN(prix_gros) || prix_gros < 0) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Le prix de gros doit être un nombre entier positif.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }
        var taille_lot = parseInt($('#transfert_taille_lot_dest').val());
        if (isNaN(taille_lot) || taille_lot < 1) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> La taille du lot doit être un nombre entier positif.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }
        var devise = $('#transfert_devise_dest').val();
        if (devise == '' || devise == null) {
            $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez sélectionner une devise.');
            setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
            $(this).prop('disabled', false);
            return;
        }

        // Tout est valide → soumettre
        var $btn = $(this);
        var originalText = $btn.html();
        $btn.html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Transfert en cours...');

        var formData = $('#form_transfert').serialize();

        $.ajax({
            url: "{{ url('/transfer_article') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#transfer_msg').html('<i class="zmdi zmdi-check-circle"></i> ' + response.message);
                    // Recharger la liste
                    $.get("{{ url('/refresh_article_stock') }}", { stock_id: sourceStockId }, function(html) {
                        $("#bloc_3").html(html);
                        // Fermer le modal après la mise à jour
                        // On utilise un court délai pour laisser le message de succès visible
                        setTimeout(function() {
                            // Fermeture robuste
                            try {
                                $('#transferModal').modal('hide');
                                // Nettoyer le backdrop s'il persiste
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                            } catch(e) {
                                // Fallback en cas d'erreur
                                $('#transferModal').removeClass('show');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                            }
                        }, 800);
                    });
                } else {
                    $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + response.message);
                    setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || 'Erreur lors du transfert.';
                $('#transfer_msg').html('<i class="zmdi zmdi-close-circle"></i> ' + msg);
                setTimeout(() => { $('#transfer_msg').html(''); }, 9000);
                $btn.prop('disabled', false).html(originalText);
            },
            complete: function() {
                // Si la requête a échoué ou si le modal est toujours visible, réactiver le bouton
                if ($('#transferModal').hasClass('show')) {
                    $btn.prop('disabled', false).html(originalText);
                }
            }
        });
    });

    // ---- Fermeture du modal : réinitialisation après fermeture complète ----
    $('#transferModal').on('hidden.bs.modal', function () {
        resetTransferModalCompletely();
        // Suppression supplémentaire du backdrop (au cas où)
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });

    // Nettoyage si le modal est fermé par d'autres moyens (ex: clic sur le fond)
    $(document).on('click', '.modal-backdrop', function() {
        // Le backdrop est géré par Bootstrap, mais on s'assure que la réinitialisation a lieu
        // L'événement hidden.bs.modal sera déclenché automatiquement.
    });
</script>
