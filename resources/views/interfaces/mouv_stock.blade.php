@php
    use App\Models\appnames;
    use App\Models\Articles;
    use App\Models\Stocks;
    use App\Models\Pointdeventes;
    use App\Models\User;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';

    // ✅ Liste des articles non supprimés pour le filtre Select2
    $articles_list = DB::table('articles')
        ->where('supprimer', 0)
        ->orderBy('nom_article', 'asc')
        ->get();

    // ✅ Liste des stocks non supprimés pour les filtres Source et Destination
    $stocks_list = DB::table('stocks')
        ->where('supprimer', 0)
        ->orderBy('nom', 'asc')
        ->get();

    $query = DB::table('transfertstocks')
        ->join('articles', 'transfertstocks.article_id', '=', 'articles.id')
        ->leftJoin('stocks as s1', 'transfertstocks.stock_1', '=', 's1.id')
        ->leftJoin('stocks as s2', 'transfertstocks.stock_2', '=', 's2.id')
        ->select(
            'transfertstocks.*',
            'articles.nom_article as article_nom',
            's1.nom as stock_1_nom',
            's2.nom as stock_2_nom'
        )
        ->where('transfertstocks.supprimer', 0)
        ->where('articles.supprimer', 0);

    if (Auth::user()->role != 0) {
        $pointdeventes_ids = DB::table('affectationspointventes')
                                ->where('user_id', Auth::id())
                                ->pluck('pointdeventes_id')
                                ->toArray();

        $stock_ids = Pointdeventes::whereIn('id', $pointdeventes_ids)
                                  ->where('supprimer', 0)
                                  ->pluck('stock_id')
                                  ->unique()
                                  ->toArray();

        if (!in_array(0, $stock_ids)) {
            $stock_ids[] = 0;
        }

        $query->where(function($q) use ($stock_ids) {
            $q->whereIn('transfertstocks.stock_1', $stock_ids)
              ->orWhereIn('transfertstocks.stock_2', $stock_ids);
        });
    }

    $transferts = $query->orderBy('transfertstocks.id', 'asc')->get();

    $achats_par_transfert = [];
    $pdv_par_transfert = [];
    $stock_actuel_par_transfert = [];
    $qte_apres_transfert = [];
    $sortie_totale_par_transfert = [];

    foreach ($transferts as $transfert) {

        $qte_transferee = (float) ($transfert->qte ?? 0);
        $qte_trouvee   = (float) ($transfert->qte_trouve ?? 0);

        $qte_apres = $qte_transferee + $qte_trouvee;
        $qte_apres_transfert[$transfert->id] = $qte_apres;

        $somme_achats = DB::table('achats')
            ->where('transfert_id', $transfert->id)
            ->sum('quantite');

        $sortie_totale_par_transfert[$transfert->id] = (float) $somme_achats;

        $stock_actuel = $qte_apres - (float) $somme_achats;
        $stock_actuel_par_transfert[$transfert->id] = $stock_actuel;

        $pdv_destination_ids = DB::table('pointdeventes')
            ->where('stock_id', $transfert->stock_2)
            ->where('supprimer', 0)
            ->pluck('id')
            ->toArray();
        $pdv_par_transfert[$transfert->id] = $pdv_destination_ids;

        $achats = DB::table('achats')
            ->leftJoin('factureasses', 'achats.facture_id', '=', 'factureasses.id')
            ->leftJoin('clients', 'factureasses.client_id', '=', 'clients.id')
            ->leftJoin('users', 'factureasses.user_id', '=', 'users.id')
            ->where('achats.transfert_id', $transfert->id)
            ->where(function($q) {
                $q->where('factureasses.etat', 0)
                  ->orWhereNull('factureasses.id');
            })
            ->select(
                'achats.id',
                'achats.quantite',
                'achats.prix_unitaire',
                'achats.total',
                'achats.devise',
                'achats.taux',
                'achats.libelle',
                'achats.created_at',
                'achats.facture_id',
                'achats.pointdeventes_id as achat_pdv_id',
                'factureasses.numero as facture_numero',
                'factureasses.libelle as facture_libelle',
                'factureasses.client_id as facture_client_id',
                'factureasses.taux as facture_taux',
                'factureasses.etat as facture_etat',
                'factureasses.pointdeventes_id as facture_pdv_id',
                'clients.name as client_nom',
                'users.name as user_nom'
            )
            ->orderBy('achats.created_at', 'asc')
            ->get();

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

.content .container {
    max-width: 100% !important;
    width: 100%;
    padding: 0.5rem 1.5rem !important;
    margin: 0 auto;
    background: #f8fafc;
}
.content .container .row { margin-left: 0; margin-right: 0; }
.content .container [class*="col-"] { padding-left: 0.75rem; padding-right: 0.75rem; }

:root {
    --bleu-nuit: #0a192f;
    --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
    --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

#bloc_1, #bloc_3 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
}

h4 {
    font-weight: 700;
    border-left: 6px solid #e31b23;
    padding-left: 18px;
    margin-bottom: 16px;
    margin-top: 0;
    color: var(--bleu-nuit);
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}
h4 i.zmdi {
    background: var(--bleu-nuit-gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent !important;
}

.invoice-count-badge {
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    color: white;
    background: linear-gradient(135deg, #e31b23, #b91c1c);
    box-shadow: var(--shadow-light);
    margin-left: 10px;
}

.table-responsive { border-radius: var(--border-radius-lg); overflow-x: auto; }
.table {
    width: 100%;
    min-width: 600px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
}
.table thead th {
    background: #E7F5FE !important;
    color: #0a192f;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 14px 12px !important;
    border-bottom: 2px solid #cbd5e1 !important;
    border-right: 1px solid #d0e2f2;
    white-space: nowrap;
}
.table tbody tr { border-bottom: 1px solid #e2e8f0; }
.table tbody tr:nth-child(even) { background-color: #f8fafc; }
.table tbody tr:nth-child(odd) { background-color: #ffffff; }
.table tbody tr:hover { background: #e6f0ff !important; }
.table tbody td {
    padding: 10px 12px !important;
    vertical-align: middle !important;
    font-weight: 500;
    font-size: 0.85rem;
    color: #1e2a3e;
    border-bottom: 1px solid #eef2f6;
}
.table tbody td:last-child { text-align: center; vertical-align: middle; }

.table tbody td.qte-cell { text-align: center; }

.table tbody td.qte-cell .qte-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
    min-width: 42px;
}
.table tbody td.qte-cell .qte-badge i.zmdi { font-size: 0.85rem; line-height: 1; }
.table tbody td.qte-cell .qte-badge.qte-trouve {
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #5b21b6;
    border: 1px solid #a78bfa;
}
.table tbody td.qte-cell .qte-badge.qte-trouve i.zmdi { color: #7c3aed; }

.table tbody td.qte-cell .qte-badge.qte-transferee {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border: 1px solid #60a5fa;
}
.table tbody td.qte-cell .qte-badge.qte-transferee i.zmdi { color: #2563eb; }

.table tbody td.qte-cell .qte-badge.qte-apres {
    background: linear-gradient(135deg, #cffafe, #a5f3fc);
    color: #155e75;
    border: 1px solid #22d3ee;
}
.table tbody td.qte-cell .qte-badge.qte-apres i.zmdi { color: #0891b2; }

.table tbody td.qte-cell .qte-badge.qte-sortie {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border: 1px solid #f87171;
}
.table tbody td.qte-cell .qte-badge.qte-sortie i.zmdi { color: #dc2626; }

.table tbody td.stock-cell .stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
    min-width: 42px;
    justify-content: center;
}
.table tbody td.stock-cell .stock-badge i.zmdi { font-size: 0.85rem; line-height: 1; }
.table tbody td.stock-cell .stock-badge.stock-ok {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border: 1px solid #34d399;
}
.table tbody td.stock-cell .stock-badge.stock-ok i.zmdi { color: #059669; }
.table tbody td.stock-cell .stock-badge.stock-zero {
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    color: #334155;
    border: 1px solid #94a3b8;
}
.table tbody td.stock-cell .stock-badge.stock-zero i.zmdi { color: #475569; }
.table tbody td.stock-cell { text-align: center; }

.table tbody td a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50% !important;
    background: #f1f5f9;
    transition: all 0.2s ease;
    text-decoration: none;
    margin: 0 2px;
}
.table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
.table tbody td a i.zmdi-delete { color: #ef4444; }
.table tbody td a i.zmdi-eye { color: #3b82f6; }
.table tbody td a:hover { background: #e0f2fe; transform: translateY(-2px); }

#liste, .btn-primary, .btn-info, .btn-danger, .btn-secondary {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 16px !important;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px !important;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: var(--shadow-light);
}
#liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
#liste:hover, .btn-primary:hover { transform: translateY(-2px); background: #2563eb !important; }
.btn-info { background: var(--bleu-nuit-gradient) !important; color: white !important; }
.btn-danger { background: var(--rouge-gradient) !important; color: white; }
#resetFilters { background: #64748b !important; color: white !important; }

.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    background: white;
    padding: 0.8rem 1.2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    align-items: flex-end;
}
.filter-group { flex: 1; min-width: 150px; }
.filter-group label {
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--bleu-nuit);
    font-size: 0.7rem;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 5px;
}
.filter-group .form-control {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 8px 12px !important;
    font-weight: 500;
    font-size: 0.85rem;
    height: 38px !important;
}
.filter-group .form-control:focus {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
    outline: none !important;
}

/* ✅ Style Select2 unifié pour les 3 filtres (Article, Source, Destination) */
.filter-group .select2-container { width: 100% !important; }
.filter-group .select2-container--default .select2-selection--single {
    height: 38px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    background: #ffffff !important;
    padding: 4px 12px !important;
    font-weight: 500;
    font-size: 0.85rem;
    display: flex !important;
    align-items: center !important;
}
.filter-group .select2-container--default .select2-selection--single:hover {
    border-color: #3B82F6 !important;
}
.filter-group .select2-container--default.select2-container--focus .select2-selection--single,
.filter-group .select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--bleu-nuit) !important;
    box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
}
.filter-group .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e2a3e !important;
    line-height: 28px !important;
    padding-left: 0 !important;
    font-weight: 500 !important;
}
.filter-group .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
    right: 8px !important;
}
.filter-group .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #e31b23 transparent transparent transparent !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--bleu-nuit) !important;
    color: white !important;
}

[style*="background-color: rgba(0, 0, 0, 0.1)"] {
    background: #eef3fc !important;
    border-radius: 60px;
    padding: 10px 24px !important;
    margin-bottom: 20px;
    display: flex !important;
    flex-wrap: wrap;
    gap: 12px;
}

.modal.fade .modal-content {
    border-radius: var(--border-radius-lg);
    border: none;
    box-shadow: var(--shadow-premium);
    overflow: hidden;
}
.modal.fade .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    border-bottom: none;
    padding: 1rem 1.5rem;
}
.modal.fade .modal-header .modal-title { font-weight: 700; font-size: 1.1rem; color: white; }
.modal.fade .modal-header .close { color: white; opacity: 0.8; }
.modal.fade .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
    padding: 1rem 1.5rem;
}

#suppression #element { text-align: center; }
#suppression #element .del-title { font-weight: 700; color: #0a192f; font-size: 0.95rem; margin-bottom: 8px; }
#suppression #element .del-line {
    font-size: 0.82rem; color: #475569; margin-top: 4px;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
#suppression #element .del-line i { color: #e31b23; }

#modal_view_detail .modal-dialog {
    max-width: 1400px;
    width: 95%;
    margin: 1.75rem auto;
}
#modal_view_detail .modal-content {
    border-radius: var(--border-radius-xl);
    border: none;
    overflow: hidden;
    box-shadow: var(--shadow-premium);
}
#modal_view_detail .modal-header {
    background: var(--bleu-nuit-gradient) !important;
    color: white;
    border-bottom: none;
    padding: 1.1rem 1.5rem;
}
#modal_view_detail .modal-header .modal-title {
    font-weight: 700;
    font-size: 1.05rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 10px;
}
#modal_view_detail .modal-header .close {
    color: white;
    opacity: 0.9;
    font-size: 1.8rem;
    line-height: 1;
    margin: 0;
    padding: 0 0 0 8px;
}
#modal_view_detail .modal-body {
    background: #f8fafc;
    padding: 1.5rem;
    max-height: 82vh;
    overflow-y: auto;
}
#modal_view_detail .modal-body::-webkit-scrollbar { width: 8px; }
#modal_view_detail .modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1; border-radius: 10px;
}

.detail-resume {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px;
    margin-bottom: 22px;
}
.detail-resume-item {
    background: white;
    padding: 12px 16px;
    border-radius: 12px;
    border-left: 4px solid #17a2b8;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.detail-resume-item.source { border-left-color: #e31b23; }
.detail-resume-item.dest   { border-left-color: #10b981; }
.detail-resume-item.qty    { border-left-color: #3B82F6; }
.detail-resume-item.date   { border-left-color: #f59e0b; }
.detail-resume-item.pdv     { border-left-color: #0ea5e9; grid-column: span 2; }
.detail-resume-item.article { border-left-color: #e31b23; grid-column: span 2; }
.detail-resume-item.stock   { border-left-color: #a855f7; }
.detail-resume-item.qte-apres { border-left-color: #0284c7; }
.detail-resume-item.commentaire { border-left-color: #f97316; grid-column: span 2; }

.detail-resume-label {
    font-size: 0.68rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.4px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.detail-resume-label i { font-size: 0.95rem; }
.detail-resume-item.source .detail-resume-label i { color: #e31b23; }
.detail-resume-item.dest   .detail-resume-label i { color: #10b981; }
.detail-resume-item.qty    .detail-resume-label i { color: #3B82F6; }
.detail-resume-item.date   .detail-resume-label i { color: #f59e0b; }
.detail-resume-item.pdv     .detail-resume-label i { color: #0ea5e9; }
.detail-resume-item.article .detail-resume-label i { color: #e31b23; }
.detail-resume-item.stock   .detail-resume-label i { color: #a855f7; }
.detail-resume-item.qte-apres .detail-resume-label i { color: #0284c7; }
.detail-resume-item.commentaire .detail-resume-label i { color: #f97316; }

.detail-resume-value {
    font-size: 0.92rem;
    color: #0a192f;
    font-weight: 700;
    word-break: break-word;
    line-height: 1.3;
}
.detail-resume-item.stock .detail-resume-value.stock-zero { color: #334155; }
.detail-resume-item.stock .detail-resume-value.stock-ok { color: #059669; }
.detail-resume-item.qte-apres .detail-resume-value { color: #0369a1; }
.detail-resume-item.commentaire .detail-resume-value {
    font-style: italic;
    font-weight: 500;
    color: #475569;
}

.detail-qtes-section { margin-bottom: 22px; }
.detail-qtes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
}
.detail-qte-card {
    background: white;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    border-top: 4px solid #cbd5e1;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.detail-qte-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}
.detail-qte-card.trouve     { border-top-color: #a78bfa; }
.detail-qte-card.transferee { border-top-color: #60a5fa; }
.detail-qte-card.apres      { border-top-color: #22d3ee; }
.detail-qte-card.sortie     { border-top-color: #f87171; }
.detail-qte-card.stock      { border-top-color: #34d399; }
.detail-qte-card.stock-zero { border-top-color: #94a3b8; }

.detail-qte-card .qte-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    color: white;
}
.detail-qte-card.trouve     .qte-icon { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
.detail-qte-card.transferee .qte-icon { background: linear-gradient(135deg, #60a5fa, #2563eb); }
.detail-qte-card.apres      .qte-icon { background: linear-gradient(135deg, #22d3ee, #0891b2); }
.detail-qte-card.sortie     .qte-icon { background: linear-gradient(135deg, #f87171, #dc2626); }
.detail-qte-card.stock      .qte-icon { background: linear-gradient(135deg, #34d399, #059669); }
.detail-qte-card.stock-zero .qte-icon { background: linear-gradient(135deg, #94a3b8, #475569); }

.detail-qte-card .qte-label {
    font-size: 0.68rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-align: center;
}
.detail-qte-card .qte-value {
    font-size: 1.3rem;
    font-weight: 800;
    line-height: 1;
}
.detail-qte-card.trouve     .qte-value { color: #5b21b6; }
.detail-qte-card.transferee .qte-value { color: #1e40af; }
.detail-qte-card.apres      .qte-value { color: #155e75; }
.detail-qte-card.sortie     .qte-value { color: #991b1b; }
.detail-qte-card.stock      .qte-value { color: #065f46; }
.detail-qte-card.stock-zero .qte-value { color: #334155; }

.detail-qte-card .qte-sub {
    font-size: 0.7rem;
    color: #94a3b8;
    font-weight: 500;
}

@media (max-width: 768px) {
    .detail-qtes-grid { grid-template-columns: 1fr 1fr; }
    .detail-qte-card .qte-value { font-size: 1.1rem; }
    .detail-qte-card .qte-icon { width: 32px; height: 32px; font-size: 1rem; }
}

.detail-section-title {
    font-weight: 700;
    color: #0a192f;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-left: 12px;
    border-left: 4px solid #17a2b8;
    margin-bottom: 14px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-section-title i { color: #17a2b8; }

.detail-achats-wrapper {
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: 1px solid #eef2f6;
}
.detail-achats-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    background: linear-gradient(135deg, #eff6ff, #e0f2fe);
    border-bottom: 1px solid #dbeafe;
    flex-wrap: wrap;
    gap: 8px;
}
.detail-achats-header .title {
    font-weight: 700;
    color: #0a192f;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-achats-header .title i { color: #17a2b8; font-size: 1.15rem; }
.detail-achats-header .badge-count {
    background: linear-gradient(135deg, #3B82F6, #2563eb);
    color: white;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* ===== FILTRES DANS LE MODAL ===== */
.detail-achats-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    align-items: flex-end;
}
.detail-achats-filters .daf-group { flex: 1; min-width: 130px; }
.detail-achats-filters .daf-group label {
    font-size: 0.63rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.detail-achats-filters .daf-group label i { color: #e31b23; font-size: 0.85rem; }
.detail-achats-filters .form-control {
    height: 34px;
    padding: 4px 10px;
    font-size: 0.78rem;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: white;
    width: 100%;
}
.detail-achats-filters .form-control:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
    outline: none;
}
.detail-achats-filters .daf-actions { flex: 0 0 auto; min-width: auto; }
.detail-achats-filters .daf-reset {
    height: 34px;
    border-radius: 40px !important;
    font-size: 0.72rem;
    padding: 0 14px;
    background: #64748b !important;
    color: white !important;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
}
.detail-achats-filters .daf-reset:hover { background: #475569 !important; }
@media (max-width: 768px) {
    .detail-achats-filters { padding: 10px 12px; gap: 8px; }
    .detail-achats-filters .daf-group { flex: 1 1 45%; min-width: 45%; }
    .detail-achats-filters .daf-actions { flex: 1 1 100%; }
    .detail-achats-filters .daf-reset { width: 100%; justify-content: center; }
}

.detail-achats-table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 0 0 14px 14px;
}
.detail-achats-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.detail-achats-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 10px 10px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.detail-achats-table tbody td {
    padding: 9px 10px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e2a3e;
    vertical-align: middle;
}
.detail-achats-table tbody tr:last-child td { border-bottom: none; }
.detail-achats-table tbody tr:hover { background: #f0f9ff; }

.detail-achats-table .badge-devise {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 50px;
    font-size: 0.65rem;
    font-weight: 700;
}
.detail-achats-table .badge-devise.usd { background: #dbeafe; color: #1e40af; }
.detail-achats-table .badge-devise.cdf { background: #fef3c7; color: #92400e; }

.detail-achats-table .badge-facture {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #e0f2fe;
    color: #0369a1;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}

.detail-achats-table .client-libre {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    font-style: italic;
}

.detail-achats-table .dual-currency b {
    display: block;
    font-size: 0.78rem;
    color: #1e40af;
    line-height: 1.2;
}
.detail-achats-table .dual-currency small {
    display: block;
    font-size: 0.68rem;
    color: #92400e;
    font-weight: 600;
    line-height: 1.2;
}

.detail-achats-table .user-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #dbeafe;
    color: #1e40af;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}

.detail-achats-table .taux-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #fef3c7;
    color: #92400e;
    padding: 3px 9px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}

.detail-achats-table .libelle-cell {
    color: #0a192f;
    font-weight: 600;
    font-style: italic;
    max-width: 250px;
    word-break: break-word;
}
.detail-achats-table .libelle-cell.libelle-client {
    color: #1e40af;
    font-style: normal;
}
.detail-achats-table .libelle-cell.libelle-facture {
    color: #475569;
    font-style: italic;
}

.detail-achats-empty {
    padding: 30px 18px;
    text-align: center;
    color: #64748b;
    font-size: 0.85rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.detail-achats-empty i { font-size: 2.4rem; color: #cbd5e1; }

@media (max-width: 1200px) {
    #modal_view_detail .modal-dialog { max-width: 95%; }
}

@media (max-width: 992px) {
    #modal_view_detail .modal-dialog {
        max-width: 98%;
        width: 98%;
        margin: 0.5rem auto;
    }
    #modal_view_detail .modal-body { padding: 1rem; max-height: 85vh; }
    .detail-resume { grid-template-columns: 1fr 1fr; }
    .detail-resume-item.pdv,
    .detail-resume-item.article,
    .detail-resume-item.commentaire { grid-column: span 2; }
    .detail-achats-table { font-size: 0.75rem; }
    .detail-achats-table thead th { font-size: 0.62rem; padding: 8px 6px; }
    .detail-achats-table tbody td { padding: 7px 6px; }
}

@media (max-width: 768px) {
    #modal_view_detail .modal-dialog {
        max-width: 100%;
        width: 100%;
        margin: 0;
    }
    #modal_view_detail .modal-content {
        border-radius: 0;
        min-height: 100vh;
    }
    #modal_view_detail .modal-header { padding: 0.9rem 1rem; }
    #modal_view_detail .modal-header .modal-title { font-size: 0.95rem; }
    #modal_view_detail .modal-body { padding: 0.8rem; }

    .detail-resume {
        grid-template-columns: 1fr;
        gap: 8px;
        margin-bottom: 16px;
    }
    .detail-resume-item.pdv,
    .detail-resume-item.article,
    .detail-resume-item.commentaire { grid-column: span 1; }
    .detail-resume-item { padding: 10px 12px; }
    .detail-resume-label { font-size: 0.62rem; }
    .detail-resume-value { font-size: 0.85rem; }

    .detail-achats-header { padding: 10px 12px; gap: 6px; }
    .detail-achats-header .title { font-size: 0.75rem; }
    .detail-achats-header .badge-count { font-size: 0.65rem; padding: 3px 9px; }

    .detail-achats-table { min-width: 850px; font-size: 0.7rem; }
    .detail-achats-table thead th { font-size: 0.58rem; padding: 7px 5px; }
    .detail-achats-table tbody td { padding: 6px 5px; }

    .detail-achats-table .dual-currency b { font-size: 0.72rem; }
    .detail-achats-table .dual-currency small { font-size: 0.62rem; }
    .detail-achats-table .badge-facture { font-size: 0.62rem; padding: 2px 7px; }
    .detail-achats-table .badge-devise { font-size: 0.58rem; padding: 2px 6px; }
    .detail-achats-table .user-badge,
    .detail-achats-table .taux-badge { font-size: 0.62rem; padding: 2px 7px; }
}

@media (max-width: 480px) {
    #modal_view_detail .modal-header { padding: 0.7rem 0.8rem; }
    #modal_view_detail .modal-header .modal-title { font-size: 0.85rem; }
    #modal_view_detail .modal-body { padding: 0.6rem; }
    .detail-section-title { font-size: 0.75rem; padding-left: 10px; }
    .detail-achats-empty { padding: 20px 12px; font-size: 0.78rem; }
    .detail-achats-empty i { font-size: 2rem; }
    .detail-qtes-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 992px) {
    .content .container { padding: 0.5rem 1rem !important; }
}
@media (max-width: 768px) {
    .content .container { padding: 0.4rem 0.6rem !important; }
    .table thead th { font-size: 0.72rem; padding: 10px 6px !important; }
    .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; }
    .filters-container { flex-direction: column; gap: 8px; }
    .filter-group { width: 100%; min-width: 100%; }
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
                <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                        class="zmdi zmdi-chevron-right"></i> &nbsp; Mouvement de stock</h6>
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

                {{-- ================= FILTRES ================= --}}
                <div class="filters-container">

                    {{-- ✅ Article : Select2 recherchable --}}
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-archive text-danger"></i> Article</label>
                        <select id="filterArticle" class="form-control select2-filter" data-placeholder="Tous les articles">
                            <option value="">Tous les articles</option>
                            @foreach($articles_list as $art)
                                <option value="{{ $art->id }}">{{ $art->nom_article }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ✅ Source : Select2 --}}
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

                    {{-- ✅ Destination : Select2 --}}
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

                    {{-- ✅ Date : Daterangepicker --}}
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
                                    <tr>
                                        <td colspan="11" class="text-center">Aucun transfert trouvé</td>
                                    </tr>
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

{{-- ================================================================
     CONTENU CACHÉ DES MODALES
     ================================================================ --}}
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
            if (!empty($pdv_ids_affichage)) {
                $pdv_noms = DB::table('pointdeventes')
                    ->whereIn('id', $pdv_ids_affichage)
                    ->pluck('nom')
                    ->toArray();
            }
            $pdv_affichage = !empty($pdv_noms) ? implode(', ', $pdv_noms) : '— (aucun PDV lié)';

            $total_qte = 0;
            $total_usd = 0;
            $total_cdf = 0;
            foreach ($achats_lies as $a) {
                $deviseLigne = $a->devise;
                $tauxLigne = ($a->facture_taux > 0) ? $a->facture_taux : (($a->taux > 0) ? $a->taux : 1);
                $total_qte += (float) ($a->quantite ?? 0);
                if ($deviseLigne == 0) {
                    $total_usd += (float) ($a->total ?? 0);
                    $total_cdf += (float) ($a->total ?? 0) * $tauxLigne;
                } else {
                    $total_cdf += (float) ($a->total ?? 0);
                    $total_usd += ($tauxLigne > 0) ? ((float) ($a->total ?? 0) / $tauxLigne) : 0;
                }
            }

            // ✅ Listes uniques pour les filtres du modal
            $clients_uniques = $achats_lies->map(function($a) {
                if (($a->facture_client_id ?? 0) > 0 && !empty($a->client_nom)) return $a->client_nom;
                if (!empty($a->facture_libelle)) return $a->facture_libelle;
                if (!empty($a->libelle)) return $a->libelle;
                return 'N/A';
            })->unique()->sort()->values();

            $users_uniques = $achats_lies->pluck('user_nom')->filter()->unique()->sort()->values();
        @endphp
        <div id="detail_data_{{ $loop->iteration }}">
            {{-- RÉSUMÉ --}}
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
                    <span class="detail-resume-label"><i class="zmdi zmdi-pin-drop"></i> Point(s) de vente destinataire(s)</span>
                    <span class="detail-resume-value">{{ $pdv_affichage }}</span>
                </div>
                <div class="detail-resume-item commentaire">
                    <span class="detail-resume-label"><i class="zmdi zmdi-comment-text"></i> Commentaire du transfert</span>
                    <span class="detail-resume-value">
                        @if(!empty($data->commentaire) && $data->commentaire !== '-')
                            "{{ $data->commentaire }}"
                        @else
                            Aucun commentaire
                        @endif
                    </span>
                </div>
            </div>

            {{-- RÉCAPITULATIF DES QUANTITÉS --}}
            <div class="detail-qtes-section">
                <div class="detail-section-title"><i class="zmdi zmdi-assignment"></i> Récapitulatif des quantités</div>
                <div class="detail-qtes-grid">
                    <div class="detail-qte-card trouve">
                        <span class="qte-icon"><i class="zmdi zmdi-search"></i></span>
                        <span class="qte-label">Qte trouve</span>
                        <span class="qte-value">{{ number_format($qte_trouve_val, 0, ',', ' ') }}</span>
                        <span class="qte-sub">unité(s) trouvée(s)</span>
                    </div>
                    <div class="detail-qte-card transferee">
                        <span class="qte-icon"><i class="zmdi zmdi-swap"></i></span>
                        <span class="qte-label">Qte transferee</span>
                        <span class="qte-value">{{ number_format($qte_transferee_val, 0, ',', ' ') }}</span>
                        <span class="qte-sub">unité(s) transférée(s)</span>
                    </div>
                    <div class="detail-qte-card apres">
                        <span class="qte-icon"><i class="zmdi zmdi-chart"></i></span>
                        <span class="qte-label">Qte apres transf.</span>
                        <span class="qte-value">{{ number_format($qte_apres_num, 0, ',', ' ') }}</span>
                        <span class="qte-sub">= transférée + trouvée</span>
                    </div>
                    <div class="detail-qte-card sortie">
                        <span class="qte-icon"><i class="zmdi zmdi-trending-down"></i></span>
                        <span class="qte-label">Sortie total</span>
                        <span class="qte-value">{{ number_format($sortie_totale_val, 0, ',', ' ') }}</span>
                        <span class="qte-sub">unité(s) vendue(s)</span>
                    </div>
                    <div class="detail-qte-card {{ $stock_zero ? 'stock-zero' : 'stock' }}">
                        <span class="qte-icon">
                            @if($stock_zero)
                                <i class="zmdi zmdi-minus-circle"></i>
                            @else
                                <i class="zmdi zmdi-check-circle"></i>
                            @endif
                        </span>
                        <span class="qte-label">Stock actuel</span>
                        <span class="qte-value">{{ number_format($stock_actuel_dest_num, 0, ',', ' ') }}</span>
                        <span class="qte-sub">= après transf. − sortie</span>
                    </div>
                </div>
            </div>

            {{-- ACHATS --}}
            @if($achats_lies->count() > 0)
                <div class="detail-section-title"><i class="zmdi zmdi-shopping-cart"></i> Mouvements de vente associés <span style="font-size:0.7rem; font-weight:600; color:#059669; margin-left:8px;">(Factures actives)</span></div>
                <div class="detail-achats-wrapper">
                    <div class="detail-achats-header">
                        <span class="title"><i class="zmdi zmdi-trending-up"></i> Sorties de <b>{{ $data->article_nom ?? 'cet article' }}</b> liées à ce transfert</span>
                        <span class="badge-count"><i class="zmdi zmdi-view-list"></i> {{ $achats_lies->count() }} vente(s)</span>
                    </div>

                    {{-- ✅ FILTRES DU TABLEAU MODAL --}}
                    <div class="detail-achats-filters">
                        <div class="daf-group">
                            <label><i class="zmdi zmdi-calendar"></i> Période</label>
                            <input type="text" class="form-control daf-date" placeholder="Toutes les dates" autocomplete="off" readonly>
                        </div>
                        <div class="daf-group">
                            <label><i class="zmdi zmdi-account"></i> Client</label>
                            <select class="form-control daf-client">
                                <option value="">Tous les clients</option>
                                @foreach($clients_uniques as $cl)
                                    <option value="{{ $cl }}">{{ $cl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="daf-group">
                            <label><i class="zmdi zmdi-account-box"></i> Utilisateur</label>
                            <select class="form-control daf-user">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($users_uniques as $u)
                                    <option value="{{ $u }}">{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="daf-group">
                            <label><i class="zmdi zmdi-receipt"></i> N° Facture</label>
                            <input type="text" class="form-control daf-facture" placeholder="Rechercher..." autocomplete="off">
                        </div>
                        <div class="daf-group daf-actions">
                            <button type="button" class="daf-reset">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <div class="detail-achats-table-scroll">
                        <table class="detail-achats-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date & Heure</th>
                                    <th>N° Facture</th>
                                    <th>Client</th>
                                    <th>Utilisateur</th>
                                    <th>Qté</th>
                                    <th>P.U.</th>
                                    <th>Total</th>
                                    <th>Taux</th>
                                    <th>Libellé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($achats_lies as $idx => $a)
                                    @php
                                        $deviseLabel = ($a->devise == 0 || $a->devise === '0') ? 'USD' : 'CDF';
                                        $badgeClass = $deviseLabel === 'USD' ? 'usd' : 'cdf';
                                        $dateAchat = $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i:s') : '-';

                                        $tauxLigne = ($a->facture_taux > 0) ? $a->facture_taux : (($a->taux > 0) ? $a->taux : 1);

                                        $pu = (float) ($a->prix_unitaire ?? 0);
                                        if ($a->devise == 0) {
                                            $pu_usd = $pu;
                                            $pu_cdf = $pu * $tauxLigne;
                                        } else {
                                            $pu_cdf = $pu;
                                            $pu_usd = ($tauxLigne > 0) ? ($pu / $tauxLigne) : 0;
                                        }

                                        $tot = (float) ($a->total ?? 0);
                                        if ($a->devise == 0) {
                                            $tot_usd = $tot;
                                            $tot_cdf = $tot * $tauxLigne;
                                        } else {
                                            $tot_cdf = $tot;
                                            $tot_usd = ($tauxLigne > 0) ? ($tot / $tauxLigne) : 0;
                                        }

                                        if (($a->facture_client_id ?? 0) > 0 && !empty($a->client_nom)) {
                                            $clientAffiche = $a->client_nom;
                                            $isClientLibre = false;
                                        } elseif (!empty($a->facture_libelle)) {
                                            $clientAffiche = $a->facture_libelle;
                                            $isClientLibre = true;
                                        } elseif (!empty($a->libelle)) {
                                            $clientAffiche = $a->libelle;
                                            $isClientLibre = true;
                                        } else {
                                            $clientAffiche = 'N/A';
                                            $isClientLibre = true;
                                        }

                                        $userAffiche = $a->user_nom ?? 'N/A';

                                        if (($a->facture_client_id ?? 0) > 0 && !empty($a->client_nom)) {
                                            $libelleAffiche = $a->client_nom;
                                            $libelleClass = 'libelle-client';
                                        } elseif (!empty($a->facture_libelle)) {
                                            $libelleAffiche = $a->facture_libelle;
                                            $libelleClass = 'libelle-facture';
                                        } else {
                                            $libelleAffiche = '-';
                                            $libelleClass = '';
                                        }
                                    @endphp
                                    <tr
                                        data-date="{{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('Y-m-d') : '' }}"
                                        data-client="{{ $clientAffiche }}"
                                        data-user="{{ $userAffiche }}"
                                        data-facture="{{ strtolower($a->facture_numero ?? '') }}">
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $dateAchat }}</td>
                                        <td>
                                            <span class="badge-facture">
                                                <i class="zmdi zmdi-receipt"></i>
                                                {{ $a->facture_numero ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($isClientLibre)
                                                <span class="client-libre"><i class="zmdi zmdi-edit"></i> {{ $clientAffiche }}</span>
                                            @else
                                                <b>{{ $clientAffiche }}</b>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="user-badge">
                                                <i class="zmdi zmdi-account"></i> {{ $userAffiche }}
                                            </span>
                                        </td>
                                        <td><b>{{ $a->quantite }}</b></td>
                                        <td>
                                            <div class="dual-currency">
                                                <b>{{ number_format($pu_usd, 2, ',', ' ') }} USD</b>
                                                <small>{{ number_format($pu_cdf, 2, ',', ' ') }} CDF</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dual-currency">
                                                <b>{{ number_format($tot_usd, 2, ',', ' ') }} USD</b>
                                                <small>{{ number_format($tot_cdf, 2, ',', ' ') }} CDF</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="taux-badge">
                                                <i class="zmdi zmdi-chart"></i> {{ number_format($tauxLigne, 0, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="libelle-cell {{ $libelleClass }}">
                                            {{ $libelleAffiche }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f1f5f9; font-weight:700; border-top: 2px solid #cbd5e1;">
                                    <td colspan="5" style="text-align:right; color:#0a192f;">TOTAUX :</td>
                                    <td style="color:#0a192f;">{{ $total_qte }}</td>
                                    <td>—</td>
                                    <td>
                                        <div class="dual-currency">
                                            <b>{{ number_format($total_usd, 2, ',', ' ') }} USD</b>
                                            <small>{{ number_format($total_cdf, 2, ',', ' ') }} CDF</small>
                                        </div>
                                    </td>
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
                        <span>Aucun mouvement de vente associé à ce transfert pour cet article.</span>
                    </div>
                </div>
            @endif
        </div>
    @empty
    @endforelse
</div>

<span id="data_id" style="display: none;"></span>

{{-- MODALE SUPPRESSION --}}
<button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
<div class="modal fade" id="suppression" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold;font-size: 16px;">Voulez-vous supprimez ce transfert ?</h5>
            </div>
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

{{-- MODALE DÉTAILS --}}
<div class="modal fade" id="modal_view_detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-shopping-cart"></i> Ventes liées au transfert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detail_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    Fermer <i class="zmdi zmdi-close-circle"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@section('js-code')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<script src="{{ asset('assets/vendors/flot/jquery.flot.js') }} "></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/bar.js') }}"></script>
<script src="{{ asset('assets/demo/js/flot-charts/pie.js') }}"></script>
<script>
$("#link_57").addClass("active");

$("#liste").click(function (e) {
    e.preventDefault();
    $("#bloc_1").show();
    $("#bloc_3").hide();
});

@forelse ($transferts as $data)
    $("#view_<?= $loop->iteration ?>").click(function(e) {
        e.preventDefault();
        var contenu = $("#detail_data_<?= $loop->iteration ?>").html();
        $("#detail_content").html(contenu);
        $("#modal_view_detail").modal("show");
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
    $.get("{{ url('/refresh_delete_transfert_stock') }}", { id: id }, function (response) {
        $("#non").trigger("click");
        location.reload();
    });
});

/* ============================================================
   ✅ INITIALISATION DES 3 SELECT2 (Article, Source, Destination)
   ============================================================ */
$(document).ready(function () {

    // ✅ Config Select2 unifiée
    var select2Config = {
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return 'Aucun résultat trouvé'; },
            searching: function() { return 'Recherche...'; }
        }
    };

    // ✅ Article
    $('#filterArticle').select2($.extend({}, select2Config, {
        placeholder: 'Tous les articles'
    }));

    // ✅ Source
    $('#filterSource').select2($.extend({}, select2Config, {
        placeholder: 'Toutes les sources'
    }));

    // ✅ Destination
    $('#filterDestination').select2($.extend({}, select2Config, {
        placeholder: 'Toutes les destinations'
    }));

    /* ✅ DATERANGEPICKER POUR LE FILTRE DATE */
    $('#filterDate').daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Appliquer',
            cancelLabel: 'Annuler',
            fromLabel: 'Du',
            toLabel: 'Au',
            customRangeLabel: 'Personnalisé',
            weekLabel: 'S',
            daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
        },
        ranges: {
            'Tout':              [moment('2000-01-01'), moment('2100-12-31')],
            'Aujourd\'hui':      [moment(), moment()],
            'Hier':              [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 derniers jours':  [moment().subtract(6, 'days'), moment()],
            '30 derniers jours': [moment().subtract(29, 'days'), moment()],
            'Ce mois-ci':        [moment().startOf('month'), moment().endOf('month')],
            'Mois dernier':      [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Cette année':       [moment().startOf('year'), moment().endOf('year')]
        }
    }, function(start, end, label) {
        if (label === 'Tout') {
            $('#filterDate').val('');
        } else {
            $('#filterDate').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        }
        filterTransferts();
    });

    $('#filterDate').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        filterTransferts();
    });

    filterTransferts();
});

/* ============================================================
   ✅ LOGIQUE DE FILTRAGE (par ID pour Article/Source/Destination)
   ============================================================ */
let transfertFilterTimeout;

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
                if (!str) return null;
                var p = str.split('/');
                if (p.length === 3) {
                    var d = p[0], m = p[1], y = p[2];
                    if (d && m && y && d.length === 2 && m.length === 2 && y.length === 4) {
                        return y + '-' + m + '-' + d;
                    }
                }
                return null;
            }
            dateDebut = parseDMY(parts[0]);
            dateFin = parseDMY(parts[1]);
        }
    }

    let visibleCount = 0;
    $('#transfertsTable tbody tr.transfert-row').each(function () {
        const $row = $(this);
        let show = true;

        if (fArticleId && String($row.data('article-id')) !== String(fArticleId)) show = false;

        if (show && fSourceId !== '' && fSourceId !== null) {
            if (String($row.data('source-id')) !== String(fSourceId)) show = false;
        }

        if (show && fDestinationId !== '' && fDestinationId !== null) {
            if (String($row.data('destination-id')) !== String(fDestinationId)) show = false;
        }

        if (show && dateDebut && dateFin) {
            var cellDate = String($row.data('date') || '');
            if (cellDate < dateDebut || cellDate > dateFin) show = false;
        }

        if (show) { $row.show(); visibleCount++; } else { $row.hide(); }
    });
    $('#transfertCount').text(visibleCount);
}

function debouncedFilterTransferts() {
    clearTimeout(transfertFilterTimeout);
    transfertFilterTimeout = setTimeout(filterTransferts, 250);
}

$(document).on('change', '#filterArticle, #filterSource, #filterDestination', function() {
    filterTransferts();
});

$(document).on('click', '#resetFilters', function (e) {
    e.preventDefault();
    $('#filterArticle').val(null).trigger('change.select2');
    $('#filterSource').val(null).trigger('change.select2');
    $('#filterDestination').val(null).trigger('change.select2');
    $('#filterDate').val('');
    if ($('#filterDate').data('daterangepicker')) {
        $('#filterDate').data('daterangepicker').setStartDate(moment());
        $('#filterDate').data('daterangepicker').setEndDate(moment());
    }
    filterTransferts();
});

/* ============================================================
   ✅ FILTRES DU TABLEAU DANS LE MODAL DÉTAILS
   ============================================================ */
$('#modal_view_detail').on('shown.bs.modal', function () {
    initModalAchatsFilters();
});

function initModalAchatsFilters() {
    var $body = $('#detail_content');
    var $dateInput = $body.find('.daf-date');

    if ($dateInput.length === 0) return; // pas de vente liée

    // Nettoyer un ancien daterangepicker éventuel
    if ($dateInput.data('daterangepicker')) {
        $dateInput.data('daterangepicker').remove();
    }

    $dateInput.daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        drops: 'down',
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Appliquer',
            cancelLabel: 'Annuler',
            fromLabel: 'Du',
            toLabel: 'Au',
            weekLabel: 'S',
            daysOfWeek: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
            monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']
        },
        ranges: {
            'Tout':             [moment('2000-01-01'), moment('2100-12-31')],
            'Aujourd\'hui':     [moment(), moment()],
            'Hier':             [moment().subtract(1,'days'), moment().subtract(1,'days')],
            '7 derniers jours': [moment().subtract(6,'days'), moment()],
            '30 derniers jours':[moment().subtract(29,'days'), moment()],
            'Ce mois-ci':       [moment().startOf('month'), moment().endOf('month')],
            'Mois dernier':     [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
        }
    }, function(start, end, label) {
        if (label === 'Tout') {
            $dateInput.val('');
        } else {
            $dateInput.val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        }
        applyModalAchatsFilter();
    });

    $dateInput.on('cancel.daterangepicker', function () {
        $(this).val('');
        applyModalAchatsFilter();
    });

    $body.find('.daf-client, .daf-user').off('change').on('change', applyModalAchatsFilter);
    $body.find('.daf-facture').off('input').on('input', applyModalAchatsFilter);

    $body.find('.daf-reset').off('click').on('click', function (e) {
        e.preventDefault();
        $body.find('.daf-client').val('');
        $body.find('.daf-user').val('');
        $body.find('.daf-facture').val('');
        $dateInput.val('');
        if ($dateInput.data('daterangepicker')) {
            $dateInput.data('daterangepicker').setStartDate(moment('2000-01-01'));
            $dateInput.data('daterangepicker').setEndDate(moment('2100-12-31'));
        }
        applyModalAchatsFilter();
    });

    applyModalAchatsFilter();
}

function applyModalAchatsFilter() {
    var $body = $('#detail_content');
    var $rows = $body.find('.detail-achats-table tbody tr');
    if (!$rows.length) return;

    var clientVal  = ($body.find('.daf-client').val()  || '').toString().toLowerCase().trim();
    var userVal    = ($body.find('.daf-user').val()    || '').toString().toLowerCase().trim();
    var factureVal = ($body.find('.daf-facture').val() || '').toString().toLowerCase().trim();
    var dateRange  = ($body.find('.daf-date').val()    || '').trim();

    var dateDebut = null, dateFin = null;
    if (dateRange) {
        var parts = dateRange.split(' - ');
        if (parts.length === 2) {
            function parseDMY(str) {
                var p = str.split('/');
                if (p.length === 3 && p[0].length === 2 && p[1].length === 2 && p[2].length === 4) {
                    return p[2] + '-' + p[1] + '-' + p[0];
                }
                return null;
            }
            dateDebut = parseDMY(parts[0]);
            dateFin   = parseDMY(parts[1]);
        }
    }

    var visible = 0;
    $rows.each(function () {
        var $r = $(this);
        var show = true;

        var rClient  = String($r.data('client')  || '').toLowerCase();
        var rUser    = String($r.data('user')    || '').toLowerCase();
        var rFacture = String($r.data('facture') || '').toLowerCase();
        var rDate    = String($r.data('date')    || '');

        if (clientVal  && rClient  !== clientVal)             show = false;
        if (show && userVal   && rUser !== userVal)           show = false;
        if (show && factureVal && rFacture.indexOf(factureVal) === -1) show = false;
        if (show && dateDebut && dateFin) {
            if (rDate < dateDebut || rDate > dateFin) show = false;
        }

        if (show) { $r.show(); visible++; } else { $r.hide(); }
    });

    // Mettre à jour le badge "X vente(s)"
    $body.find('.detail-achats-header .badge-count').html(
        '<i class="zmdi zmdi-view-list"></i> ' + visible + ' vente(s)'
    );
}
</script>
@endsection
@endsection
