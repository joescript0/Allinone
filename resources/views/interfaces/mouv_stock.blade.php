@php
use App\Models\appnames;
use App\Models\Articles;
use App\Models\Stocks;
use App\Models\Pointdeventes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';

$articles_list = DB::table('articles')->where('supprimer', 0)->orderBy('nom_article', 'asc')->get();

$stock_ids_autorises = null;
if (Auth::user()->role != 0) {
    $pointdeventes_ids = DB::table('affectationspointventes')->where('user_id', Auth::id())->pluck('pointdeventes_id')->toArray();
    $stock_ids_autorises = Pointdeventes::whereIn('id', $pointdeventes_ids)->where('supprimer', 0)->whereNotNull('stock_id')->pluck('stock_id')->unique()->values()->toArray();
}

$stocks_query = DB::table('stocks')->where('supprimer', 0);
if ($stock_ids_autorises !== null) {
    if (empty($stock_ids_autorises)) { $stocks_query->whereRaw('1 = 0'); }
    else { $stocks_query->whereIn('id', $stock_ids_autorises); }
}
$stocks_list = $stocks_query->orderBy('nom', 'asc')->get();

$query = DB::table('transfertstocks')
    ->join('articles', 'transfertstocks.article_id', '=', 'articles.id')
    ->leftJoin('stocks as s1', 'transfertstocks.stock_1', '=', 's1.id')
    ->leftJoin('stocks as s2', 'transfertstocks.stock_2', '=', 's2.id')
    ->select('transfertstocks.*', 'articles.nom_article as article_nom', 's1.nom as stock_1_nom', 's2.nom as stock_2_nom')
    ->where('transfertstocks.supprimer', 0)
    ->where('articles.supprimer', 0);

if ($stock_ids_autorises !== null) {
    if (empty($stock_ids_autorises)) { $query->whereRaw('1 = 0'); }
    else { $query->whereIn('transfertstocks.stock_2', $stock_ids_autorises); }
}

$transferts = $query->orderBy('transfertstocks.id', 'asc')->get();
$transfert_ids = $transferts->pluck('id')->toArray();

$sommes_achats_par_transfert = [];
if (!empty($transfert_ids)) {
    $sommes_achats_par_transfert = DB::table('achats')
        ->leftJoin('factureasses', 'achats.facture_id', '=', 'factureasses.id')
        ->whereIn('achats.transfert_id', $transfert_ids)
        ->where(function($q) { $q->where('factureasses.etat', 0)->orWhereNull('factureasses.id'); })
        ->groupBy('achats.transfert_id')
        ->select('achats.transfert_id', DB::raw('SUM(achats.quantite) as total_qte'))
        ->pluck('total_qte', 'achats.transfert_id')->toArray();
}

$envois_par_cle = [];

if (!empty($transferts)) {
    $stock_1_ids = $transferts->pluck('stock_2')->unique()->values()->toArray();
    $article_ids = $transferts->pluck('article_id')->unique()->values()->toArray();

    if (!empty($stock_1_ids) && !empty($article_ids)) {
        $envois = DB::table('transfertstocks')
            ->join('articles', 'transfertstocks.article_id', '=', 'articles.id')
            ->leftJoin('stocks as s1', 'transfertstocks.stock_1', '=', 's1.id')
            ->leftJoin('stocks as s2', 'transfertstocks.stock_2', '=', 's2.id')
            ->select(
                'transfertstocks.id','transfertstocks.article_id','transfertstocks.stock_1','transfertstocks.stock_2',
                'transfertstocks.qte','transfertstocks.qte_trouve','transfertstocks.commentaire','transfertstocks.created_at',
                'articles.nom_article as article_nom','s1.nom as stock_1_nom','s2.nom as stock_2_nom'
            )
            ->where('transfertstocks.supprimer', 0)
            ->whereIn('transfertstocks.article_id', $article_ids)
            ->whereIn('transfertstocks.stock_1', $stock_1_ids)
            ->orderBy('transfertstocks.created_at', 'asc')
            ->get();

        foreach ($envois as $e) {
            $cle = $e->article_id . '|' . $e->stock_1;
            if (!isset($envois_par_cle[$cle])) { $envois_par_cle[$cle] = []; }
            $envois_par_cle[$cle][] = $e;
        }
    }
}

$achats_par_transfert = [];
$pdv_par_transfert = [];
$stock_actuel_par_transfert = [];
$qte_apres_transfert = [];
$sortie_totale_par_transfert = [];
$transfert_jour_par_transfert = [];
$transferts_jour_details = [];

foreach ($transferts as $transfert) {
    $qte_transferee = (float) ($transfert->qte ?? 0);
    $qte_trouvee   = (float) ($transfert->qte_trouve ?? 0);
    $qte_apres = $qte_transferee + $qte_trouvee;
    $qte_apres_transfert[$transfert->id] = $qte_apres;

    $somme_achats = (float) ($sommes_achats_par_transfert[$transfert->id] ?? 0);
    $sortie_totale_par_transfert[$transfert->id] = $somme_achats;

    $pdv_destination_ids = DB::table('pointdeventes')->where('stock_id', $transfert->stock_2)->where('supprimer', 0)->pluck('id')->toArray();
    $pdv_par_transfert[$transfert->id] = $pdv_destination_ids;

    /* --- Cumul des envois faits APRÈS cette date (colonne "Transfert") --- */
    $cle = $transfert->article_id . '|' . $transfert->stock_2;
    $date_ligne = \Carbon\Carbon::parse($transfert->created_at);
    $cumul = 0;
    $details = collect();

    foreach (($envois_par_cle[$cle] ?? []) as $e) {
        $date_envoi = \Carbon\Carbon::parse($e->created_at);
        if ($date_envoi >= $date_ligne) {
            $cumul += (float) $e->qte;
            $details->push($e);
        }
    }

    $transfert_jour_par_transfert[$transfert->id] = $cumul;
    $transferts_jour_details[$transfert->id]      = $details;

    /* --- Solde disponible avant déduction du transfert --- */
    $solde = $qte_apres - $somme_achats;

    /* --- Si le transfert dépasse le solde, on ne soustrait rien ; sinon on soustrait --- */
    if ($cumul > $solde) {
        $stock_actuel = $solde;              // Transfert supérieur au solde → on ne fait rien
    } else {
        $stock_actuel = $solde - $cumul;     // Transfert ≤ solde → on soustrait
    }

    $stock_actuel_par_transfert[$transfert->id] = $stock_actuel;

    $achats = DB::table('achats')
        ->leftJoin('factureasses', 'achats.facture_id', '=', 'factureasses.id')
        ->leftJoin('clients', 'factureasses.client_id', '=', 'clients.id')
        ->leftJoin('users', 'factureasses.user_id', '=', 'users.id')
        ->where('achats.transfert_id', $transfert->id)
        ->where(function($q) { $q->where('factureasses.etat', 0)->orWhereNull('factureasses.id'); })
        ->select(
            'achats.id','achats.quantite','achats.prix_unitaire','achats.total',
            'achats.devise','achats.taux','achats.libelle','achats.created_at',
            'achats.facture_id','achats.pointdeventes_id as achat_pdv_id',
            'factureasses.numero as facture_numero','factureasses.libelle as facture_libelle',
            'factureasses.client_id as facture_client_id','factureasses.taux as facture_taux',
            'factureasses.etat as facture_etat','factureasses.pointdeventes_id as facture_pdv_id',
            'clients.name as client_nom','users.name as user_nom'
        )
        ->orderBy('achats.created_at', 'asc')->get();

    $achats_par_transfert[$transfert->id] = $achats;
}
@endphp
@extends('layouts.main')
@section('title', $nom_app)
@section('name', 'MOUVEMENT DE STOCK')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
body { margin: 0; padding: 0; background: #f0f4f8; }
.content .container { max-width: 100% !important; width: 100%; padding: 0.5rem 1.5rem !important; margin: 0 auto; background: #f8fafc; }
.content .container .row { margin-left: 0; margin-right: 0; }
.content .container [class*="col-"] { padding-left: 0.75rem; padding-right: 0.75rem; }
:root { --bleu-nuit: #0a192f; --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f); --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626); --shadow-premium: 0 20px 35px -12px rgba(0,0,0,0.2); --shadow-light: 0 4px 12px rgba(0,0,0,0.08); --border-radius-xl: 20px; --border-radius-lg: 16px; }
#bloc_1, #bloc_3 { background: rgba(255,255,255,0.96); border-radius: var(--border-radius-xl); box-shadow: var(--shadow-premium); padding: 1rem 1.5rem !important; margin-bottom: 1rem; }
h4 { font-weight: 700; border-left: 6px solid #e31b23; padding-left: 18px; margin-bottom: 16px; margin-top: 0; color: var(--bleu-nuit); display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
h4 i.zmdi { background: var(--bleu-nuit-gradient); background-clip: text; -webkit-background-clip: text; color: transparent !important; }
.invoice-count-badge { border-radius: 50px; padding: 4px 14px; font-size: 0.8rem; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; color: white; background: linear-gradient(135deg, #e31b23, #b91c1c); box-shadow: var(--shadow-light); margin-left: 10px; }
.table-responsive { border-radius: var(--border-radius-lg); overflow-x: auto; }
.table { width: 100%; min-width: 600px; background: white; border-collapse: collapse; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-light); }
.table thead th { background: #E7F5FE !important; color: #0a192f; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; border-right: 1px solid #d0e2f2; white-space: nowrap; }
.table tbody tr { border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd) { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; }
.table tbody td { padding: 10px 12px !important; vertical-align: middle !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; border-bottom: 1px solid #eef2f6; }
.table tbody td:last-child { text-align: center; vertical-align: middle; }
.table tbody td.qte-cell { text-align: center; }
.table tbody td.qte-cell .qte-badge { display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 4px 12px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; white-space: nowrap; min-width: 42px; }
.table tbody td.qte-cell .qte-badge i.zmdi { font-size: 0.85rem; line-height: 1; }
.table tbody td.qte-cell .qte-badge.qte-trouve { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #5b21b6; border: 1px solid #a78bfa; }
.table tbody td.qte-cell .qte-badge.qte-trouve i.zmdi { color: #7c3aed; }
.table tbody td.qte-cell .qte-badge.qte-transferee { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; border: 1px solid #60a5fa; }
.table tbody td.qte-cell .qte-badge.qte-transferee i.zmdi { color: #2563eb; }
.table tbody td.qte-cell .qte-badge.qte-apres { background: linear-gradient(135deg, #cffafe, #a5f3fc); color: #155e75; border: 1px solid #22d3ee; }
.table tbody td.qte-cell .qte-badge.qte-apres i.zmdi { color: #0891b2; }
.table tbody td.qte-cell .qte-badge.qte-sortie { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 1px solid #f87171; }
.table tbody td.qte-cell .qte-badge.qte-sortie i.zmdi { color: #dc2626; }
/* ✅ Badge Transfert : même design que les autres badges */
.table tbody td.qte-cell .qte-badge.qte-transfert-jour { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #9d174d; border: 1px solid #ec4899; cursor: pointer; text-decoration: none; transition: all 0.2s ease; }
.table tbody td.qte-cell .qte-badge.qte-transfert-jour i.zmdi { color: #db2777; }
.table tbody td.qte-cell .qte-badge.qte-transfert-jour:hover { transform: scale(1.12); box-shadow: 0 6px 16px rgba(236,72,153,0.4); background: linear-gradient(135deg, #fbcfe8, #f9a8d4); color: #831843; }
.table tbody td.qte-cell .qte-badge.qte-transfert-jour:hover i.zmdi { color: #831843; }
/* ✅ Modal danger (rouge) */
#modal_transfert_jour .modal-content { border-radius: var(--border-radius-xl); border: none; overflow: hidden; box-shadow: var(--shadow-premium); }
#modal_transfert_jour .modal-header { background: var(--rouge-gradient) !important; color: white; border-bottom: none; padding: 1.1rem 1.5rem; }
#modal_transfert_jour .modal-header .modal-title { font-weight: 700; font-size: 1.05rem; color: white; display: flex; align-items: center; gap: 10px; }
#modal_transfert_jour .modal-header .close { color: white; opacity: 0.9; font-size: 1.8rem; }
#modal_transfert_jour .modal-body { background: #f8fafc; padding: 1.5rem; max-height: 78vh; overflow-y: auto; }
#modal_transfert_jour .modal-footer { background: #f8fafc; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem; }
/* ✅ Récap danger (rouge) */
.transfert-jour-recap { background: linear-gradient(135deg, #fef2f2, #fee2e2); border-left: 5px solid #ef4444; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
.transfert-jour-recap-item { display: flex; flex-direction: column; gap: 3px; }
.transfert-jour-recap-item .lbl { font-size: 0.68rem; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.4px; display: flex; align-items: center; gap: 5px; }
.transfert-jour-recap-item .lbl i { color: #dc2626; }
.transfert-jour-recap-item .val { font-size: 0.95rem; font-weight: 800; color: #0a192f; word-break: break-word; }
/* ✅ Tableau détails danger */
.transfert-jour-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); font-size: 0.82rem; min-width: 700px; }
.transfert-jour-table thead th { background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.4px; padding: 10px 10px; text-align: left; border-bottom: 2px solid #f87171; white-space: nowrap; }
.transfert-jour-table tbody td { padding: 10px 10px; border-bottom: 1px solid #f1f5f9; color: #1e2a3e; vertical-align: middle; }
.transfert-jour-table tbody tr:hover { background: #fef2f2; }
.transfert-jour-table tfoot td { padding: 12px 10px; background: #fee2e2; font-weight: 800; color: #991b1b; font-size: 0.85rem; border-top: 2px solid #f87171; }
.transfert-jour-table .badge-qte { display: inline-flex; align-items: center; gap: 4px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 3px 10px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; }
.transfert-jour-table .badge-qte i { color: #2563eb; }
.transfert-jour-table .badge-qte-trouve { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #5b21b6; }
.transfert-jour-table .badge-qte-trouve i { color: #7c3aed; }
.transfert-jour-table .num-col { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #ef4444; color: white; border-radius: 50%; font-weight: 700; font-size: 0.72rem; }
.transfert-jour-table .commentaire-cell { color: #475569; font-style: italic; max-width: 220px; word-break: break-word; }
.transfert-jour-table .current-row { background: #fef2f2 !important; box-shadow: inset 3px 0 0 #ef4444; }
.transfert-jour-empty { padding: 30px 18px; text-align: center; color: #64748b; font-size: 0.85rem; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.transfert-jour-empty i { font-size: 2.4rem; color: #fca5a5; }
.table tbody td.stock-cell .stock-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 50px; font-size: 0.78rem; font-weight: 700; white-space: nowrap; min-width: 42px; justify-content: center; }
.table tbody td.stock-cell .stock-badge i.zmdi { font-size: 0.85rem; line-height: 1; }
.table tbody td.stock-cell .stock-badge.stock-ok { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border: 1px solid #34d399; }
.table tbody td.stock-cell .stock-badge.stock-ok i.zmdi { color: #059669; }
.table tbody td.stock-cell .stock-badge.stock-zero { background: linear-gradient(135deg, #e2e8f0, #cbd5e1); color: #334155; border: 1px solid #94a3b8; }
.table tbody td.stock-cell .stock-badge.stock-zero i.zmdi { color: #475569; }
.table tbody td.stock-cell { text-align: center; }
.table tbody td a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50% !important; background: #f1f5f9; transition: all 0.2s ease; text-decoration: none; margin: 0 2px; }
.table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a i.zmdi-eye { color: #3b82f6; }
.table tbody td a:hover { background: #e0f2fe; transform: translateY(-2px); }
#liste, .btn-primary, .btn-info, .btn-danger, .btn-secondary { display: inline-flex !important; align-items: center; justify-content: center; gap: 8px; padding: 6px 16px !important; font-weight: 600; font-size: 0.85rem; border-radius: 40px !important; border: none; cursor: pointer; text-decoration: none; box-shadow: var(--shadow-light); }
#liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
#liste:hover, .btn-primary:hover { transform: translateY(-2px); background: #2563eb !important; }
.btn-info { background: var(--bleu-nuit-gradient) !important; color: white !important; }
.btn-danger { background: var(--rouge-gradient) !important; color: white; }
#resetFilters { background: #64748b !important; color: white !important; }
.filters-container { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; background: white; padding: 0.8rem 1.2rem; border-radius: var(--border-radius-lg); box-shadow: var(--shadow-light); align-items: flex-end; }
.filter-group { flex: 1; min-width: 150px; }
.filter-group label { font-weight: 600; margin-bottom: 4px; color: var(--bleu-nuit); font-size: 0.7rem; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
.filter-group .form-control { width: 100% !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 8px 12px !important; font-weight: 500; font-size: 0.85rem; height: 38px !important; }
.filter-group .form-control:focus { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10,25,47,0.15) !important; outline: none !important; }
.filter-group .select2-container { width: 100% !important; }
.filter-group .select2-container--default .select2-selection--single { height: 38px !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; background: #ffffff !important; padding: 4px 12px !important; font-weight: 500; font-size: 0.85rem; display: flex !important; align-items: center !important; }
.filter-group .select2-container--default .select2-selection--single:hover { border-color: #3B82F6 !important; }
.filter-group .select2-container--default.select2-container--focus .select2-selection--single, .filter-group .select2-container--default.select2-container--open .select2-selection--single { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10,25,47,0.15) !important; }
.filter-group .select2-container--default .select2-selection--single .select2-selection__rendered { color: #1e2a3e !important; line-height: 28px !important; padding-left: 0 !important; font-weight: 500 !important; }
.filter-group .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; right: 8px !important; }
.filter-group .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: #e31b23 transparent transparent transparent !important; }
.select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: var(--bleu-nuit) !important; color: white !important; }
[style*="background-color: rgba(0, 0, 0, 0.1)"] { background: #eef3fc !important; border-radius: 60px; padding: 10px 24px !important; margin-bottom: 20px; display: flex !important; flex-wrap: wrap; gap: 12px; }
.modal.fade .modal-content { border-radius: var(--border-radius-lg); border: none; box-shadow: var(--shadow-premium); overflow: hidden; }
.modal.fade .modal-header { background: var(--bleu-nuit-gradient) !important; border-bottom: none; padding: 1rem 1.5rem; }
.modal.fade .modal-header .modal-title { font-weight: 700; font-size: 1.1rem; color: white; }
.modal.fade .modal-header .close { color: white; opacity: 0.8; }
.modal.fade .modal-footer { background: #f8fafc; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem; }
#suppression #element { text-align: center; }
#suppression #element .del-title { font-weight: 700; color: #0a192f; font-size: 0.95rem; margin-bottom: 8px; }
#suppression #element .del-line { font-size: 0.82rem; color: #475569; margin-top: 4px; display: flex; align-items: center; justify-content: center; gap: 6px; }
#suppression #element .del-line i { color: #e31b23; }
#modal_view_detail .modal-dialog { max-width: 1400px; width: 95%; margin: 1.75rem auto; }
#modal_view_detail .modal-content { border-radius: var(--border-radius-xl); border: none; overflow: hidden; box-shadow: var(--shadow-premium); }
#modal_view_detail .modal-header { background: var(--bleu-nuit-gradient) !important; color: white; border-bottom: none; padding: 1.1rem 1.5rem; }
#modal_view_detail .modal-header .modal-title { font-weight: 700; font-size: 1.05rem; color: white; display: flex; align-items: center; gap: 10px; }
#modal_view_detail .modal-header .close { color: white; opacity: 0.9; font-size: 1.8rem; line-height: 1; margin: 0; padding: 0 0 0 8px; }
#modal_view_detail .modal-body { background: #f8fafc; padding: 1.5rem; max-height: 82vh; overflow-y: auto; }
.detail-resume { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-bottom: 22px; }
.detail-resume-item { background: white; padding: 12px 16px; border-radius: 12px; border-left: 4px solid #17a2b8; box-shadow: 0 2px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 4px; }
.detail-resume-item.source { border-left-color: #e31b23; }
.detail-resume-item.dest { border-left-color: #10b981; }
.detail-resume-item.date { border-left-color: #f59e0b; }
.detail-resume-item.pdv { border-left-color: #0ea5e9; grid-column: span 2; }
.detail-resume-item.article { border-left-color: #e31b23; grid-column: span 2; }
.detail-resume-item.commentaire { border-left-color: #f97316; grid-column: span 2; }
.detail-resume-label { font-size: 0.68rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.4px; display: flex; align-items: center; gap: 5px; }
.detail-resume-value { font-size: 0.92rem; color: #0a192f; font-weight: 700; word-break: break-word; line-height: 1.3; }
.detail-resume-item.commentaire .detail-resume-value { font-style: italic; font-weight: 500; color: #475569; }
.detail-qtes-section { margin-bottom: 22px; }
.detail-qtes-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; }
.detail-qte-card { background: white; border-radius: 12px; padding: 14px 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; gap: 6px; border-top: 4px solid #cbd5e1; }
.detail-qte-card.trouve { border-top-color: #a78bfa; }
.detail-qte-card.transferee { border-top-color: #60a5fa; }
.detail-qte-card.apres { border-top-color: #22d3ee; }
.detail-qte-card.sortie { border-top-color: #f87171; }
.detail-qte-card.stock { border-top-color: #34d399; }
.detail-qte-card.stock-zero { border-top-color: #94a3b8; }
.detail-qte-card .qte-icon { width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.15rem; color: white; }
.detail-qte-card.trouve .qte-icon { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
.detail-qte-card.transferee .qte-icon { background: linear-gradient(135deg, #60a5fa, #2563eb); }
.detail-qte-card.apres .qte-icon { background: linear-gradient(135deg, #22d3ee, #0891b2); }
.detail-qte-card.sortie .qte-icon { background: linear-gradient(135deg, #f87171, #dc2626); }
.detail-qte-card.stock .qte-icon { background: linear-gradient(135deg, #34d399, #059669); }
.detail-qte-card.stock-zero .qte-icon { background: linear-gradient(135deg, #94a3b8, #475569); }
.detail-qte-card .qte-label { font-size: 0.68rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.4px; text-align: center; }
.detail-qte-card .qte-value { font-size: 1.3rem; font-weight: 800; line-height: 1; }
.detail-qte-card.trouve .qte-value { color: #5b21b6; }
.detail-qte-card.transferee .qte-value { color: #1e40af; }
.detail-qte-card.apres .qte-value { color: #155e75; }
.detail-qte-card.sortie .qte-value { color: #991b1b; }
.detail-qte-card.stock .qte-value { color: #065f46; }
.detail-qte-card.stock-zero .qte-value { color: #334155; }
.detail-section-title { font-weight: 700; color: #0a192f; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 12px; border-left: 4px solid #17a2b8; margin-bottom: 14px; margin-top: 8px; display: flex; align-items: center; gap: 8px; }
.detail-achats-wrapper { background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #eef2f6; }
.detail-achats-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; background: linear-gradient(135deg, #eff6ff, #e0f2fe); border-bottom: 1px solid #dbeafe; flex-wrap: wrap; gap: 8px; }
.detail-achats-header .title { font-weight: 700; color: #0a192f; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; }
.detail-achats-header .badge-count { background: linear-gradient(135deg, #3B82F6, #2563eb); color: white; padding: 4px 12px; border-radius: 50px; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
.detail-achats-table-scroll { overflow-x: auto; border-radius: 0 0 14px 14px; }
.detail-achats-table { width: 100%; min-width: 900px; border-collapse: collapse; font-size: 0.82rem; }
.detail-achats-table thead th { background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.68rem; text-transform: uppercase; padding: 10px 10px; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
.detail-achats-table tbody td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; color: #1e2a3e; }
.detail-achats-table tbody tr:hover { background: #f0f9ff; }
.detail-achats-empty { padding: 30px 18px; text-align: center; color: #64748b; font-size: 0.85rem; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.detail-achats-empty i { font-size: 2.4rem; color: #cbd5e1; }
@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .filters-container { flex-direction: column; gap: 8px; }
    .filter-group { width: 100%; min-width: 100%; }
    .detail-qtes-grid { grid-template-columns: 1fr 1fr; }
}
</style>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 10px;padding-bottom: 10px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <a class="btn-primary btn-sm" id="liste" href="">
                                    <i class="zmdi zmdi-email-open"></i> Liste des transferts
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top: 30px;padding-bottom: 50px;" class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Mouvement de stock</h6>
            </div>
            <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                <h4 style="color:rgba(0, 0, 0, 0.6);">
                    <i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                    Historique des transferts
                    <span class="invoice-count-badge">
                        <i class="zmdi zmdi-view-list" style="color:white;"></i>
                        Transferts : <span id="transfertCount">0</span>
                    </span>
                </h4>
                <div class="filters-container">
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-archive text-danger"></i> Article</label>
                        <select id="filterArticle" class="form-control select2-filter" data-placeholder="Tous les articles">
                            <option value="">Tous les articles</option>
                            @foreach($articles_list as $art)
                                <option value="{{ $art->id }}">{{ $art->nom_article }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-store text-danger"></i> Source</label>
                        <select id="filterSource" class="form-control select2-filter" data-placeholder="Toutes les sources">
                            <option value="">Toutes les sources</option>
                            <option value="0">Stock Principal</option>
                            @foreach($stocks_list as $st)
                                <option value="{{ $st->id }}">{{ $st->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-store text-danger"></i> Destination</label>
                        <select id="filterDestination" class="form-control select2-filter" data-placeholder="Toutes les destinations">
                            <option value="">Toutes les destinations</option>
                            <option value="0">Stock Principal</option>
                            @foreach($stocks_list as $st)
                                <option value="{{ $st->id }}">{{ $st->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Période</label>
                        <input type="text" id="filterDate" class="form-control" placeholder="Sélectionner une période (ou Tout)" autocomplete="off">
                    </div>
                    <div class="filter-group" style="flex: 0 0 auto;">
                        <button id="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>
                <div id="content_groupe" class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="transfertsTable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Article</th>
                                        <th>Qte trouve</th>
                                        <th>Qte transferee</th>
                                        <th>Qte apres transf.</th>
                                        <th>Sortie total</th>
                                        <th>Transfert</th>
                                        <th>Stock actuel</th>
                                        <th>Source</th>
                                        <th>Destination</th>
                                        <th>Date</th>
                                        <th>Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transferts as $data)
                                    @php
                                        $stock_actuel = $stock_actuel_par_transfert[$data->id] ?? 0;
                                        $stock_actuel_num = (float) $stock_actuel;
                                        $stock_zero = ($stock_actuel_num <= 0);
                                        $qte_apres_val = $qte_apres_transfert[$data->id] ?? 0;
                                        $qte_apres_num = (float) $qte_apres_val;
                                        $qte_trouve_val = (float) ($data->qte_trouve ?? 0);
                                        $qte_transferee_val = (float) ($data->qte ?? 0);
                                        $sortie_totale_val = $sortie_totale_par_transfert[$data->id] ?? 0;
                                        $transfert_jour_val = $transfert_jour_par_transfert[$data->id] ?? 0;
                                    @endphp
                                    <tr class="transfert-row"
                                        data-article-id="{{ $data->article_id }}"
                                        data-source-id="{{ $data->stock_1 }}"
                                        data-destination-id="{{ $data->stock_2 }}"
                                        data-article="{{ strtolower($data->article_nom ?? '') }}"
                                        data-source="{{ strtolower($data->stock_1_nom ?? 'Principal') }}"
                                        data-destination="{{ strtolower($data->stock_2_nom ?? 'Principal') }}"
                                        data-date="{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->article_nom ?? 'N/A' }}</td>
                                        <td class="qte-cell">
                                            <span class="qte-badge qte-trouve">
                                                <i class="zmdi zmdi-search"></i>
                                                {{ number_format($qte_trouve_val, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="qte-cell">
                                            <span class="qte-badge qte-transferee">
                                                <i class="zmdi zmdi-swap"></i>
                                                {{ number_format($qte_transferee_val, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="qte-cell">
                                            <span class="qte-badge qte-apres">
                                                <i class="zmdi zmdi-chart"></i>
                                                {{ number_format($qte_apres_num, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="qte-cell">
                                            <span class="qte-badge qte-sortie">
                                                <i class="zmdi zmdi-trending-down"></i>
                                                {{ number_format($sortie_totale_val, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="qte-cell">
                                            <a href="#" class="qte-badge qte-transfert-jour" id="transfert_badge_<?= $loop->iteration ?>"
                                               title="Envois faits par ce stock APRÈS son approvisionnement (même article)">
                                                <i class="zmdi zmdi-repeat"></i>
                                                {{ number_format($transfert_jour_val, 0, ',', ' ') }}
                                            </a>
                                        </td>
                                        <td class="stock-cell">
                                            <span class="stock-badge {{ $stock_zero ? 'stock-zero' : 'stock-ok' }}">
                                                @if($stock_zero)
                                                    <i class="zmdi zmdi-minus-circle"></i>
                                                @else
                                                    <i class="zmdi zmdi-check-circle"></i>
                                                @endif
                                                {{ number_format($stock_actuel_num, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td>{{ $data->stock_1_nom ?? 'Stock Principal' }}</td>
                                        <td>{{ $data->stock_2_nom ?? 'Stock Principal' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i:s') }}</td>
                                        <td style="text-align: center;">
                                            <a id="view_<?= $loop->iteration ?>" href="#" title="Voir les ventes liées">
                                                <i class="zmdi zmdi-eye text-info"></i>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a id="delete_<?= $loop->iteration ?>" href="#" title="Supprimer">
                                                <i class="zmdi zmdi-delete text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="12" class="text-center">Aucun transfert trouvé</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12"></div>
        </div>
    </div>
</section>

{{-- DÉTAILS DES TRANSFERTS DU JOUR (POUR MODALE) --}}
<div id="transferts_jour_container" style="display:none;">
    @forelse ($transferts as $data)
        @php
            $details_list = $transferts_jour_details[$data->id] ?? collect();
            $transfert_jour_val = $transfert_jour_par_transfert[$data->id] ?? 0;
        @endphp
        <div id="transfert_jour_content_{{ $loop->iteration }}">
            <div class="transfert-jour-recap">
                <div class="transfert-jour-recap-item">
                    <span class="lbl"><i class="zmdi zmdi-archive"></i> Article</span>
                    <span class="val">{{ $data->article_nom ?? 'N/A' }}</span>
                </div>
                <div class="transfert-jour-recap-item">
                    <span class="lbl"><i class="zmdi zmdi-store"></i> Stock destinataire (approvisionné)</span>
                    <span class="val">{{ $data->stock_2_nom ?? 'Stock Principal' }}</span>
                </div>
                <div class="transfert-jour-recap-item">
                    <span class="lbl"><i class="zmdi zmdi-calendar"></i> Date d'approvisionnement</span>
                    <span class="val">{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="transfert-jour-recap-item">
                    <span class="lbl"><i class="zmdi zmdi-view-list"></i> Nombre d'envois après</span>
                    <span class="val">{{ $details_list->count() }}</span>
                </div>
                <div class="transfert-jour-recap-item">
                    <span class="lbl"><i class="zmdi zmdi-repeat"></i> Total envoyé après ce stock</span>
                    <span class="val" style="color: #dc2626;">{{ number_format($transfert_jour_val, 0, ',', ' ') }}</span>
                </div>
            </div>
            @if($details_list->count() > 0)
                <div class="table-responsive">
                    <table class="transfert-jour-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Heure</th>
                                <th>Article</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Qté envoyée</th>
                                <th>Qté trouvée</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details_list as $idx => $tj)
                                <tr class="{{ $tj->id == $data->id ? 'current-row' : '' }}">
                                    <td><span class="num-col">{{ $idx + 1 }}</span></td>
                                    <td><i class="zmdi zmdi-time" style="color:#dc2626;"></i> {{ \Carbon\Carbon::parse($tj->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td><b>{{ $tj->article_nom ?? 'N/A' }}</b></td>
                                    <td>{{ $tj->stock_1_nom ?? 'Stock Principal' }}</td>
                                    <td>{{ $tj->stock_2_nom ?? 'Stock Principal' }}</td>
                                    <td><span class="badge-qte"><i class="zmdi zmdi-swap"></i> {{ number_format((float) ($tj->qte ?? 0), 0, ',', ' ') }}</span></td>
                                    <td><span class="badge-qte badge-qte-trouve"><i class="zmdi zmdi-search"></i> {{ number_format((float) ($tj->qte_trouve ?? 0), 0, ',', ' ') }}</span></td>
                                    <td class="commentaire-cell">@if(!empty($tj->commentaire) && $tj->commentaire !== '-'){{ $tj->commentaire }}@else—@endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align:right;"><i class="zmdi zmdi-repeat"></i> TOTAL ENVOYÉ APRÈS :</td>
                                <td>{{ number_format($transfert_jour_val, 0, ',', ' ') }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="transfert-jour-empty">
                    <i class="zmdi zmdi-info-outline"></i>
                    <span>Aucun envoi fait par ce stock après cette date.</span>
                </div>
            @endif
        </div>
    @empty
    @endforelse
</div>

{{-- CONTENU CACHÉ DES MODALES EXISTANTES (Ventes liées) --}}
<div id="all_details_container" style="display:none;">
    @forelse ($transferts as $data)
        @php
            $achats_lies = $achats_par_transfert[$data->id] ?? collect();
            $date_transfert = \Carbon\Carbon::parse($data->created_at);
            $stock_actuel_dest = $stock_actuel_par_transfert[$data->id] ?? 0;
            $stock_actuel_dest_num = (float) $stock_actuel_dest;
            $stock_zero = ($stock_actuel_dest_num <= 0);
            $qte_apres_val = $qte_apres_transfert[$data->id] ?? 0;
            $qte_apres_num = (float) $qte_apres_val;
            $qte_trouve_val = (float) ($data->qte_trouve ?? 0);
            $qte_transferee_val = (float) ($data->qte ?? 0);
            $sortie_totale_val = $sortie_totale_par_transfert[$data->id] ?? 0;
            $pdv_ids_affichage = $pdv_par_transfert[$data->id] ?? [];
            $pdv_noms = [];
            if (!empty($pdv_ids_affichage)) { $pdv_noms = DB::table('pointdeventes')->whereIn('id', $pdv_ids_affichage)->pluck('nom')->toArray(); }
            $pdv_affichage = !empty($pdv_noms) ? implode(', ', $pdv_noms) : '— (aucun PDV lié)';
            $total_qte = 0; $total_usd = 0; $total_cdf = 0;
            foreach ($achats_lies as $a) {
                $deviseLigne = $a->devise;
                $tauxLigne = ($a->facture_taux > 0) ? $a->facture_taux : (($a->taux > 0) ? $a->taux : 1);
                $total_qte += (float) ($a->quantite ?? 0);
                if ($deviseLigne == 0) { $total_usd += (float) ($a->total ?? 0); $total_cdf += (float) ($a->total ?? 0) * $tauxLigne; }
                else { $total_cdf += (float) ($a->total ?? 0); $total_usd += ($tauxLigne > 0) ? ((float) ($a->total ?? 0) / $tauxLigne) : 0; }
            }
        @endphp
        <div id="detail_data_{{ $loop->iteration }}">
            <div class="detail-resume">
                <div class="detail-resume-item date">
                    <span class="detail-resume-label"><i class="zmdi zmdi-calendar"></i> Date du transfert</span>
                    <span class="detail-resume-value">{{ $date_transfert->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="detail-resume-item article">
                    <span class="detail-resume-label"><i class="zmdi zmdi-archive"></i> Article concerné</span>
                    <span class="detail-resume-value">{{ $data->article_nom ?? 'N/A' }}</span>
                </div>
                <div class="detail-resume-item source">
                    <span class="detail-resume-label"><i class="zmdi zmdi-arrow-right"></i> Source</span>
                    <span class="detail-resume-value">{{ $data->stock_1_nom ?? 'Stock Principal' }}</span>
                </div>
                <div class="detail-resume-item dest">
                    <span class="detail-resume-label"><i class="zmdi zmdi-arrow-left"></i> Destination</span>
                    <span class="detail-resume-value">{{ $data->stock_2_nom ?? 'Stock Principal' }}</span>
                </div>
                <div class="detail-resume-item pdv">
                    <span class="detail-resume-label"><i class="zmdi zmdi-pin-drop"></i> PDV destinataire(s)</span>
                    <span class="detail-resume-value">{{ $pdv_affichage }}</span>
                </div>
                <div class="detail-resume-item commentaire">
                    <span class="detail-resume-label"><i class="zmdi zmdi-comment-text"></i> Commentaire</span>
                    <span class="detail-resume-value">
                        @if(!empty($data->commentaire) && $data->commentaire !== '-')"{{ $data->commentaire }}"@else Aucun commentaire @endif
                    </span>
                </div>
            </div>
            <div class="detail-qtes-section">
                <div class="detail-section-title"><i class="zmdi zmdi-assignment"></i> Récapitulatif des quantités</div>
                <div class="detail-qtes-grid">
                    <div class="detail-qte-card trouve"><span class="qte-icon"><i class="zmdi zmdi-search"></i></span><span class="qte-label">Qte trouve</span><span class="qte-value">{{ number_format($qte_trouve_val, 0, ',', ' ') }}</span></div>
                    <div class="detail-qte-card transferee"><span class="qte-icon"><i class="zmdi zmdi-swap"></i></span><span class="qte-label">Qte transferee</span><span class="qte-value">{{ number_format($qte_transferee_val, 0, ',', ' ') }}</span></div>
                    <div class="detail-qte-card apres"><span class="qte-icon"><i class="zmdi zmdi-chart"></i></span><span class="qte-label">Qte apres transf.</span><span class="qte-value">{{ number_format($qte_apres_num, 0, ',', ' ') }}</span></div>
                    <div class="detail-qte-card sortie"><span class="qte-icon"><i class="zmdi zmdi-trending-down"></i></span><span class="qte-label">Sortie total</span><span class="qte-value">{{ number_format($sortie_totale_val, 0, ',', ' ') }}</span></div>
                    <div class="detail-qte-card {{ $stock_zero ? 'stock-zero' : 'stock' }}">
                        <span class="qte-icon">@if($stock_zero)<i class="zmdi zmdi-minus-circle"></i>@else<i class="zmdi zmdi-check-circle"></i>@endif</span>
                        <span class="qte-label">Stock actuel</span><span class="qte-value">{{ number_format($stock_actuel_dest_num, 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>
            @if($achats_lies->count() > 0)
                <div class="detail-section-title"><i class="zmdi zmdi-shopping-cart"></i> Mouvements de vente associés</div>
                <div class="detail-achats-wrapper">
                    <div class="detail-achats-header">
                        <span class="title"><i class="zmdi zmdi-trending-up"></i> Sorties de <b>{{ $data->article_nom ?? 'cet article' }}</b></span>
                        <span class="badge-count"><i class="zmdi zmdi-view-list"></i> {{ $achats_lies->count() }} vente(s)</span>
                    </div>
                    <div class="detail-achats-table-scroll">
                        <table class="detail-achats-table">
                            <thead>
                                <tr>
                                    <th>#</th><th>Date & Heure</th><th>N° Facture</th><th>Client</th><th>Utilisateur</th>
                                    <th>Qté</th><th>P.U.</th><th>Total</th><th>Taux</th><th>Libellé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($achats_lies as $idx => $a)
                                    @php
                                        $dateAchat = $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i:s') : '-';
                                        $tauxLigne = ($a->facture_taux > 0) ? $a->facture_taux : (($a->taux > 0) ? $a->taux : 1);
                                        $pu = (float) ($a->prix_unitaire ?? 0);
                                        $pu_usd = ($a->devise == 0) ? $pu : (($tauxLigne > 0) ? ($pu / $tauxLigne) : 0);
                                        $pu_cdf = ($a->devise == 0) ? ($pu * $tauxLigne) : $pu;
                                        $tot = (float) ($a->total ?? 0);
                                        $tot_usd = ($a->devise == 0) ? $tot : (($tauxLigne > 0) ? ($tot / $tauxLigne) : 0);
                                        $tot_cdf = ($a->devise == 0) ? ($tot * $tauxLigne) : $tot;
                                        $clientAffiche = 'N/A'; $isClientLibre = true;
                                        if (($a->facture_client_id ?? 0) > 0 && !empty($a->client_nom)) { $clientAffiche = $a->client_nom; $isClientLibre = false; }
                                        elseif (!empty($a->facture_libelle)) { $clientAffiche = $a->facture_libelle; }
                                        elseif (!empty($a->libelle)) { $clientAffiche = $a->libelle; }
                                        $userAffiche = $a->user_nom ?? 'N/A';
                                        $libelleAffiche = ($a->facture_client_id ?? 0) > 0 && !empty($a->client_nom) ? $a->client_nom : (!empty($a->facture_libelle) ? $a->facture_libelle : '-');
                                    @endphp
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $dateAchat }}</td>
                                        <td>{{ $a->facture_numero ?? 'N/A' }}</td>
                                        <td>@if($isClientLibre)<i>{{ $clientAffiche }}</i>@else<b>{{ $clientAffiche }}</b>@endif</td>
                                        <td>{{ $userAffiche }}</td>
                                        <td><b>{{ $a->quantite }}</b></td>
                                        <td><b>{{ number_format($pu_usd, 2, ',', ' ') }} USD</b><br><small>{{ number_format($pu_cdf, 2, ',', ' ') }} CDF</small></td>
                                        <td><b>{{ number_format($tot_usd, 2, ',', ' ') }} USD</b><br><small>{{ number_format($tot_cdf, 2, ',', ' ') }} CDF</small></td>
                                        <td>{{ number_format($tauxLigne, 0, ',', ' ') }}</td>
                                        <td>{{ $libelleAffiche }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f1f5f9; font-weight:700; border-top: 2px solid #cbd5e1;">
                                    <td colspan="5" style="text-align:right;">TOTAUX :</td>
                                    <td>{{ $total_qte }}</td>
                                    <td>—</td>
                                    <td><b>{{ number_format($total_usd, 2, ',', ' ') }} USD</b><br><small>{{ number_format($total_cdf, 2, ',', ' ') }} CDF</small></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <div class="detail-section-title"><i class="zmdi zmdi-shopping-cart"></i> Mouvements de vente associés</div>
                <div class="detail-achats-wrapper">
                    <div class="detail-achats-empty">
                        <i class="zmdi zmdi-info-outline"></i>
                        <span>Aucun mouvement de vente associé.</span>
                    </div>
                </div>
            @endif
        </div>
    @empty
    @endforelse
</div>

<span id="data_id" style="display: none;"></span>

<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ce transfert ?</h5></div>
            <div class="modal-body"><div id="element"></div></div>
            <div style="font-weight: bold;text-align: center;">
                <p class="text-center" style="font-weight: bold;">
                    <a style="color: white;font-weight: bold;" id="oui" href="#" class="btn btn-info btn-sm">Oui</a>
                    <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm" data-dismiss="modal">Non</button>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_transfert_jour" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-alert-circle"></i> Envois faits par ce stock après approvisionnement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="transfert_jour_modal_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-shopping-cart"></i> Ventes liées au transfert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="detail_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fermer <i class="zmdi zmdi-close-circle"></i></button>
            </div>
        </div>
    </div>
</div>

@section('js-code')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
$("#link_57").addClass("active");

$("#liste").click(function (e) { e.preventDefault(); $("#bloc_1").show(); $("#bloc_3").hide(); });

@forelse ($transferts as $data)
    $("#view_<?= $loop->iteration ?>").click(function(e) {
        e.preventDefault();
        $("#detail_content").html($("#detail_data_<?= $loop->iteration ?>").html());
        $("#modal_view_detail").modal("show");
    });
    $("#transfert_badge_<?= $loop->iteration ?>").click(function(e) {
        e.preventDefault();
        $("#transfert_jour_modal_content").html($("#transfert_jour_content_<?= $loop->iteration ?>").html());
        $("#modal_transfert_jour").modal("show");
    });
    $("#delete_<?= $loop->iteration ?>").click(function(e) {
        e.preventDefault();
        $("#element").html(
            '<div class="del-title">Transfert du <?= \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i:s') ?></div>' +
            '<div class="del-line"><i class="zmdi zmdi-archive"></i> Article : <?= addslashes($data->article_nom ?? 'N/A') ?></div>' +
            '<div class="del-line"><i class="zmdi zmdi-n-1-square"></i> Quantité : <?= $data->qte ?></div>'
        );
        $("#data_id").html("<?= $data->id ?>");
        $("#btn_sup").trigger("click");
    });
@empty
@endforelse

$("#oui").click(function (e) {
    e.preventDefault();
    var id = $("#data_id").html();
    $.get("{{ url('/refresh_delete_transfert_stock') }}", { id: id }, function () {
        $("#non").trigger("click");
        location.reload();
    });
});

$(document).ready(function () {
    var select2Config = { allowClear: true, width: '100%', language: { noResults: function() { return 'Aucun résultat trouvé'; }, searching: function() { return 'Recherche...'; } } };
    $('#filterArticle').select2($.extend({}, select2Config, { placeholder: 'Tous les articles' }));
    $('#filterSource').select2($.extend({}, select2Config, { placeholder: 'Toutes les sources' }));
    $('#filterDestination').select2($.extend({}, select2Config, { placeholder: 'Toutes les destinations' }));

    $('#filterDate').daterangepicker({
        autoUpdateInput: false, opens: 'left',
        locale: { format: 'DD/MM/YYYY', separator: ' - ', applyLabel: 'Appliquer', cancelLabel: 'Annuler', fromLabel: 'Du', toLabel: 'Au', weekLabel: 'S', daysOfWeek: ['Di','Lu','Ma','Me','Je','Ve','Sa'], monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] },
        ranges: {
            'Tout': [moment('2000-01-01'), moment('2100-12-31')],
            'Aujourd\'hui': [moment(), moment()],
            'Hier': [moment().subtract(1,'days'), moment().subtract(1,'days')],
            '7 derniers jours': [moment().subtract(6,'days'), moment()],
            '30 derniers jours': [moment().subtract(29,'days'), moment()],
            'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
            'Mois dernier': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
            'Cette année': [moment().startOf('year'), moment().endOf('year')]
        }
    }, function(start, end, label) {
        if (label === 'Tout') { $('#filterDate').val(''); } else { $('#filterDate').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY')); }
        filterTransferts();
    });
    $('#filterDate').on('cancel.daterangepicker', function() { $(this).val(''); filterTransferts(); });
    filterTransferts();
});

function filterTransferts() {
    const fArticleId = $('#filterArticle').val();
    const fSourceId = $('#filterSource').val();
    const fDestinationId = $('#filterDestination').val();
    const fDateRange = $('#filterDate').val() || '';
    var dateDebut = null, dateFin = null;
    if (fDateRange) {
        var parts = fDateRange.split(' - ');
        if (parts.length === 2) {
            function parseDMY(str) {
                var p = str.split('/');
                if (p.length === 3 && p[0].length === 2 && p[1].length === 2 && p[2].length === 4) return p[2] + '-' + p[1] + '-' + p[0];
                return null;
            }
            dateDebut = parseDMY(parts[0]); dateFin = parseDMY(parts[1]);
        }
    }
    let visibleCount = 0;
    $('#transfertsTable tbody tr.transfert-row').each(function () {
        const $row = $(this); let show = true;
        if (fArticleId && String($row.data('article-id')) !== String(fArticleId)) show = false;
        if (show && fSourceId !== '' && fSourceId !== null) { if (String($row.data('source-id')) !== String(fSourceId)) show = false; }
        if (show && fDestinationId !== '' && fDestinationId !== null) { if (String($row.data('destination-id')) !== String(fDestinationId)) show = false; }
        if (show && dateDebut && dateFin) { var cd = String($row.data('date') || ''); if (cd < dateDebut || cd > dateFin) show = false; }
        if (show) { $row.show(); visibleCount++; } else { $row.hide(); }
    });
    $('#transfertCount').text(visibleCount);
}
$(document).on('change', '#filterArticle, #filterSource, #filterDestination', function() { filterTransferts(); });
$(document).on('click', '#resetFilters', function (e) {
    e.preventDefault();
    $('#filterArticle').val(null).trigger('change.select2');
    $('#filterSource').val(null).trigger('change.select2');
    $('#filterDestination').val(null).trigger('change.select2');
    $('#filterDate').val('');
    if ($('#filterDate').data('daterangepicker')) { $('#filterDate').data('daterangepicker').setStartDate(moment()); $('#filterDate').data('daterangepicker').setEndDate(moment()); }
    filterTransferts();
});
</script>
@endsection
@endsection
