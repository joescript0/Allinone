@php
    use App\Models\appnames;
    use App\Models\Contrevenants;
    use App\Models\Groupes;
    use App\Models\Tables;
    use App\Models\Verbalisateurs;
    use App\Models\Writes;
    use App\Models\User;
    use App\Models\Factures;
    use App\Models\Approvisionnements;
    use App\Models\Achats;
    use App\Models\Societes;
    use App\Models\Clients;
    use App\Models\Mesures;
    use App\Models\Entres;
    use App\Models\detailpaiessachats;
    use Illuminate\Support\Facades\Auth;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp

@extends('layouts.main')

@section('title', $nom_app)
@section('name', 'FACTURES')

@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    
    <style>
        /* ============================================================
           DESIGN PREMIUM – UNIFIÉ AVEC LES PAGES "GESTION ARTICLE"
           ET "APPROVISIONNEMENT" – ADAPTÉ AUX FACTURES
           ============================================================ */
        body { margin: 0; padding: 0; background: #f0f4f8; }
        .content .container { max-width: 100% !important; width: 100%; padding: 0.5rem 1.5rem !important; margin: 0 auto; background: #f8fafc; }
        .content .container .row { margin-left: 0; margin-right: 0; }
        .content .container [class*="col-"] { padding-left: 0.75rem; padding-right: 0.75rem; }
        :root {
            --bleu-nuit: #0a192f; --bleu-nuit-clair: #112240; --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
            --bleu-secondaire: #2c5282; --bleu-secondaire-gradient: linear-gradient(135deg, #2c5282, #1a365d);
            --rouge-gradient: linear-gradient(135deg, #ef4444, #dc2626); --vert-gradient: linear-gradient(135deg, #10b981, #059669);
            --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2); --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius-xl: 20px; --border-radius-lg: 16px;
        }
        #bloc_1, #bloc_2, #bloc_3 { background: rgba(255, 255, 255, 0.96); border-radius: var(--border-radius-xl); box-shadow: var(--shadow-premium); padding: 1rem 1.5rem !important; margin-bottom: 1rem; transition: transform 0.2s, box-shadow 0.2s; }
        h4 { font-weight: 700; border-left: 6px solid #e31b23; padding-left: 18px; margin-bottom: 16px; margin-top: 0; color: var(--bleu-nuit); display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        h4 i.zmdi { background: var(--bleu-nuit-gradient); background-clip: text; -webkit-background-clip: text; color: transparent !important; }
        h4 .badge-invoice { background: linear-gradient(135deg, #e31b23, #b91c1c); color: white; border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; margin-left: 6px; vertical-align: middle; }
        @media (max-width: 480px) { h4 .badge-invoice { font-size: 0.65rem; padding: 2px 10px; margin-left: 0; white-space: normal; word-break: break-word; } h4 { flex-direction: column; align-items: flex-start; gap: 4px; } }
        .table-responsive { overflow-x: auto; overflow-y: visible; border-radius: var(--border-radius-lg); }
        .table { width: 100%; min-width: 800px; background: white; border-collapse: collapse; border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-light); table-layout: auto; }
        .table thead th { background: #E7F5FE !important; color: #0a192f; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 12px !important; border-bottom: 2px solid #cbd5e1 !important; border-right: 1px solid #d0e2f2; white-space: normal; word-break: break-word; }
        .table tbody tr { transition: all 0.15s ease; border-bottom: 1px solid #e2e8f0; }
        .table tbody tr:nth-child(even) { background-color: #f8fafc; }
        .table tbody tr:nth-child(odd) { background-color: #ffffff; }
        .table tbody tr:hover { background: #e6f0ff !important; cursor: default; }
        .table tbody td { padding: 10px 12px !important; vertical-align: middle !important; font-weight: 500; font-size: 0.85rem; color: #1e2a3e; word-break: break-word; border-bottom: 1px solid #eef2f6; line-height: 1.4; }
        .table tbody td:last-child { text-align: center; vertical-align: middle; }
        #bloc_1 button, #bloc_2 button, #bloc_3 button, .filters-container button, #liste, #add, #add_r, #save, #save_r, #annuler, #edit_save, #edit_annuler, #resetFilters, #rapport, #payer, .btn-primary, .btn-info, .btn-danger, .btn-secondary {
            display: inline-flex !important; align-items: center; justify-content: center; gap: 8px; padding: 6px 16px !important; font-weight: 600; font-size: 0.85rem; border-radius: 40px !important; transition: all 0.25s ease; border: none; cursor: pointer; text-decoration: none; box-shadow: var(--shadow-light); white-space: nowrap; line-height: 1.5;
        }
        #liste, .btn-primary { background: #3B82F6 !important; color: white !important; }
        #liste:hover, .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3); background: #2563eb !important; }
        #add, .btn-info { background: var(--bleu-nuit-gradient) !important; color: white !important; }
        #add:hover, .btn-info:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3); }
        #save, #edit_save { background: var(--bleu-secondaire-gradient) !important; color: white; }
        #save:hover, #edit_save:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(44, 82, 130, 0.3); }
        #annuler, #edit_annuler, .btn-danger { background: var(--rouge-gradient) !important; color: white; }
        #annuler:hover, #edit_annuler:hover, .btn-danger:hover { transform: translateY(-2px); background: linear-gradient(135deg, #dc2626, #b91c1c) !important; box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3); }
        #resetFilters { background: #64748b !important; color: white !important; }
        #resetFilters:hover { transform: translateY(-2px); background: #475569 !important; box-shadow: 0 8px 18px rgba(100, 116, 139, 0.3); }
        #add_r, #save_r, #print_r { background: #cbd5e1 !important; color: #475569 !important; cursor: not-allowed !important; opacity: 0.7; transform: none !important; box-shadow: none !important; }
        #print { background: #3B82F6 !important; color: white !important; }
        #print:hover { background: #2563eb !important; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3); }
        #rapport { background: var(--rouge-gradient) !important; color: white !important; }
        #rapport:hover { transform: translateY(-2px); background: linear-gradient(135deg, #dc2626, #b91c1c) !important; box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3); }
        #payer { background: linear-gradient(135deg, #10b981, #059669) !important; color: white !important; min-width: 130px; justify-content: center; }
        #payer:hover { transform: translateY(-2px); background: linear-gradient(135deg, #059669, #047857) !important; box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35); color: white !important; }
        #payer i.zmdi, #payer i.fas { color: white !important; }
        .filters-container { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; background: white; padding: 0.8rem 1.2rem; border-radius: var(--border-radius-lg); box-shadow: var(--shadow-light); align-items: flex-end; }
        .filter-group { flex: 1; min-width: 150px; }
        .filter-group label { font-weight: 600; margin-bottom: 4px; color: var(--bleu-nuit); font-size: 0.7rem; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
        .filter-group .form-control { height: 36px; }
        .invoice-badges-container { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px 12px; margin-bottom: 15px; }
        .invoice-count-badge { border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; color: white; }
        .invoice-count-badge.usd-badge { background: linear-gradient(135deg, #3B82F6, #2563eb); }
        .invoice-count-badge.cdf-badge { background: linear-gradient(135deg, #3B82F6, #2563eb); }
        .invoice-count-badge.paye-usd { background: linear-gradient(135deg, #0a192f, #1e3a5f); }
        .invoice-count-badge.paye-cdf { background: linear-gradient(135deg, #0a192f, #1e3a5f); }
        .invoice-count-badge.credit-usd { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .invoice-count-badge.credit-cdf { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .invoice-count-badge.benefice-usd { background: linear-gradient(135deg, #198754, #146c43); }
        .invoice-count-badge.benefice-cdf { background: linear-gradient(135deg, #198754, #146c43); }
        #form_add .row, #form_edit .row { display: flex; flex-wrap: wrap; }
        #form_add .col-6, #form_edit .col-6 { margin-bottom: 0.8rem; }
        .form-group { width: 100%; margin-bottom: 0; }
        .form-group label { display: block; font-weight: 700; color: var(--bleu-nuit); margin-bottom: 4px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.4px; }
        .form-group label i { color: #e31b23; margin-right: 6px; }
        .form-control, input.form-control, select.form-control, textarea.form-control, .input-mask { width: 100% !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 8px 12px !important; font-weight: 500; font-size: 0.85rem; transition: all 0.2s; box-sizing: border-box; height: 38px !important; line-height: 1.4; }
        textarea.form-control { resize: vertical; height: 38px !important; }
        .form-control:focus, select.form-control:focus, textarea.form-control:focus { border-color: var(--bleu-nuit) !important; box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important; transform: translateY(-1px); }
        select.form-control { appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>'); background-repeat: no-repeat; background-position: right 14px center; }
        .input-mask { font-family: monospace; background: #fff9ef !important; }
        #msg, #edit_msg { display: none !important; visibility: hidden !important; opacity: 0 !important; margin: 0 !important; padding: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important; min-height: 0 !important; height: 0 !important; overflow: hidden !important; }
        #msg:not(:empty), #edit_msg:not(:empty) { display: inline-flex !important; visibility: visible !important; opacity: 1 !important; height: auto !important; margin-top: 16px !important; padding: 10px 18px !important; background: white !important; border-radius: 50px !important; box-shadow: var(--shadow-light) !important; gap: 10px; font-weight: 600; font-size: 0.8rem; animation: slideInMsg 0.3s ease-out; }
        #msg:not(:empty):has(i.zmdi-check-circle), #edit_msg:not(:empty):has(i.zmdi-check-circle) { background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important; color: #065f46; border-left: 4px solid #10b981; }
        #msg:not(:empty):has(i.zmdi-close-circle), #edit_msg:not(:empty):has(i.zmdi-close-circle) { background: linear-gradient(95deg, #fee2e2, #fecaca) !important; color: #991b1b; border-left: 4px solid #ef4444; }
        #msg:not(:empty):has(i.zmdi-info), #edit_msg:not(:empty):has(i.zmdi-info) { background: linear-gradient(95deg, #dbeafe, #bfdbfe) !important; color: #1e3a8a; border-left: 4px solid #3b82f6; }
        @keyframes slideInMsg { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .table tbody td a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50% !important; background: #f1f5f9; transition: all 0.2s ease; text-decoration: none; margin: 0 2px; }
        .table tbody td a i.zmdi { font-size: 1.1rem; margin: 0; }
        .table tbody td a i.zmdi-eye, .table tbody td a i.zmdi-money { color: #2c7da0; }
        .table tbody td a i.zmdi-delete { color: #ef4444; }
        .table tbody td a:hover { background: #e0f2fe; transform: translateY(-2px); }
        .table tbody td a:hover i.zmdi-delete { color: #b91c1c; }
        .table tbody td a:hover i.zmdi-eye, .table tbody td a:hover i.zmdi-money { color: #1e5a7a; }
        .table tbody td a i.zmdi-settings { color: #17a2b8 !important; transition: all 0.2s ease; }
        .table tbody td a:hover i.zmdi-settings { color: #0f6674 !important; }
        [style*="background-color: rgba(0, 0, 0, 0.1)"] { background: #eef3fc !important; border-radius: 60px; padding: 10px 24px !important; margin-bottom: 20px; display: flex !important; flex-wrap: wrap; gap: 12px; justify-content: flex-start; }
        .modal.fade#pdfModal .modal-dialog { max-width: 100%; width: 60%; margin: 1.75rem auto; }
        .modal.fade#pdfModal .modal-content { border-radius: 20px; border: none; box-shadow: var(--shadow-premium); overflow: hidden; }
        .modal.fade#pdfModal .modal-header { background: var(--bleu-nuit-gradient) !important; border-bottom: none; padding: 1.2rem 1.5rem; }
        .modal.fade#pdfModal .modal-header .modal-title { font-weight: 700; font-size: 1.2rem; color: white; }
        .modal.fade#pdfModal .modal-header .close { color: white; opacity: 0.8; text-shadow: none; }
        .modal.fade#pdfModal .modal-header .close:hover { opacity: 1; }
        .modal.fade#pdfModal .modal-body { padding: 0; background: #f8fafc; }
        .modal.fade#pdfModal .modal-footer { background: white; border-top: 1px solid #eef2f6; padding: 1.2rem 1.5rem; }
        .modal.fade#pdfModal #pdfIframe { width: 100%; height: 50vh; border: none; background: white; }
        .modal.fade#pdfModal #montant_recu, .modal.fade#pdfModal #devise_recu { width: 100%; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 40px !important; padding: 10px 14px; font-weight: 500; font-size: 0.85rem; transition: all 0.2s; height: 44px; box-sizing: border-box; }
        .modal.fade#pdfModal #montant_recu:focus, .modal.fade#pdfModal #devise_recu:focus { border-color: var(--bleu-nuit); box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15); outline: none; }
        .modal.fade#pdfModal #devise_recu { appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>'); background-repeat: no-repeat; background-position: right 14px center; cursor: pointer; }
        .modal.fade#pdfModal #btn_payer { background: linear-gradient(135deg, #10b981, #059669) !important; border: none; border-radius: 40px !important; padding: 10px 24px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s ease; width: 100%; color: white; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .modal.fade#pdfModal #btn_payer:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(16, 185, 129, 0.3); }
        .modal.fade#pdfModal #msg_facture { display: inline-block !important; font-weight: 600; font-size: 0.85rem; padding: 10px 18px; border-radius: 50px; background: #f1f5f9; color: #1e2a3e; margin-top: 12px; margin-bottom: 0; text-align: center; animation: fadeInMsg 0.3s ease-out; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); max-width: 100%; word-wrap: break-word; line-height: 1.5; }
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-close-circle), .modal.fade#pdfModal #msg_facture[style*="color: #dc3545"] { background: linear-gradient(95deg, #fee2e2, #fecaca) !important; color: #991b1b !important; border-left: 4px solid #dc2626 !important; }
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-check-circle), .modal.fade#pdfModal #msg_facture[style*="color: #28a745"] { background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important; color: #065f46 !important; border-left: 4px solid #10b981 !important; }
        .modal.fade#pdfModal #msg_facture:has(i.zmdi-alert), .modal.fade#pdfModal #msg_facture[style*="color: #ffc107"] { background: linear-gradient(95deg, #fed7aa, #ffedcc) !important; color: #9b4d00 !important; border-left: 4px solid #f59e0b !important; }
        @keyframes fadeInMsg { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .modal.fade#pdfModal .btn-secondary { background: #64748b; border: none; border-radius: 40px; padding: 8px 20px; font-weight: 600; font-size: 0.8rem; transition: all 0.2s ease; color: white; }
        .modal.fade#pdfModal .btn-secondary:hover { background: #475569; transform: translateY(-2px); }
        .modal.fade#pdfModal hr { margin: 15px 0; border: 0; border-top: 1px solid #eef2f6; }
        .modal.fade#qrModal .modal-dialog { max-width: 100%; width: 60%; margin: 1.75rem auto; }
        .modal.fade#qrModal .modal-content { border-radius: 20px; border: none; box-shadow: var(--shadow-premium); overflow: hidden; }
        .modal.fade#qrModal .modal-header { background: linear-gradient(135deg, #10b981, #059669) !important; border-bottom: none; padding: 1.2rem 1.5rem; }
        .modal.fade#qrModal .modal-header .modal-title { font-weight: 700; font-size: 1.2rem; color: white; }
        .modal.fade#qrModal .modal-header .close { color: white; opacity: 0.8; text-shadow: none; }
        .modal.fade#qrModal .modal-header .close:hover { opacity: 1; }
        .modal.fade#qrModal .modal-body { padding: 0; background: #f8fafc; }
        .modal.fade#qrModal .modal-footer { background: white; border-top: 1px solid #eef2f6; padding: 1.2rem 1.5rem; }
        .modal.fade#qrModal #qrIframe { width: 100%; height: 60vh; border: none; background: white; }
        .modal.fade#qrModal .btn-secondary { background: #64748b; border: none; border-radius: 40px; padding: 8px 20px; font-weight: 600; font-size: 0.8rem; transition: all 0.2s ease; color: white; }
        .modal.fade#qrModal .btn-secondary:hover { background: #475569; transform: translateY(-2px); }
        #bloc_t { display: flex; flex-wrap: wrap; align-items: stretch; gap: 20px; width: 100%; margin: 0; }
        #bloc_2, #bloc_3 { flex: 1 1 calc(50% - 20px); min-width: 280px; margin: 0 !important; }
        @media (max-width: 768px) { #bloc_t { flex-direction: column; gap: 20px; align-items: center; } #bloc_2, #bloc_3 { flex: 1 1 100%; width: 100%; min-width: auto; } }
        @media (max-width: 992px) { .content .container { padding: 0.5rem 1rem !important; } #bloc_1, #bloc_2, #bloc_3 { padding: 1rem !important; } }
        @media (max-width: 768px) {
            .content .container { padding: 0.4rem 0.6rem !important; } #bloc_1, #bloc_2, #bloc_3 { padding: 0.8rem !important; }
            #liste, #add, #rapport, #payer, #save, #edit_save, #annuler, #edit_annuler, #resetFilters, .btn-primary, .btn-info, .btn-danger { padding: 4px 12px !important; font-size: 0.7rem; }
            #payer { min-width: 90px; flex: 1 1 auto; justify-content: center; }
            .filters-container { flex-direction: column; gap: 8px; padding: 0.6rem 0.8rem; margin-bottom: 12px; } .filter-group { width: 100%; min-width: 100%; }
            .filter-group .form-control { height: 34px !important; } .invoice-badges-container { justify-content: flex-start; gap: 6px 8px; }
            .invoice-count-badge { font-size: 0.65rem; padding: 3px 10px; } .table thead th { font-size: 0.72rem; padding: 10px 6px !important; letter-spacing: 0.05em; }
            .table tbody td { padding: 8px 10px !important; font-size: 0.75rem; line-height: 1.3; } #form_add .col-6, #form_edit .col-6 { flex: 0 0 100%; max-width: 100%; }
            .form-group label { font-size: 0.65rem; } .form-control, input.form-control, select.form-control, textarea.form-control { height: 34px !important; font-size: 0.75rem; }
            .modal.fade#pdfModal .modal-dialog { width: 95%; margin: 1rem auto; } .modal.fade#pdfModal #pdfIframe { height: 40vh; }
            .modal.fade#pdfModal #boite_de_control .col-lg-4 { margin-bottom: 10px; } .modal.fade#pdfModal #btn_payer { width: 100%; }
            .modal.fade#pdfModal .modal-footer { padding: 1rem; } .modal.fade#pdfModal #montant_recu, .modal.fade#pdfModal #devise_recu { height: 40px; font-size: 0.8rem; }
            .modal.fade#pdfModal #msg_facture { font-size: 0.75rem; padding: 8px 14px; } [style*="background-color: rgba(0, 0, 0, 0.1)"] { justify-content: center; gap: 8px; }
            .modal.fade#qrModal .modal-dialog { width: 95%; margin: 1rem auto; } .modal.fade#qrModal #qrIframe { height: 45vh; } .modal.fade#qrModal .modal-footer { padding: 1rem; }
        }
        .badge-invoice i.zmdi { color: white !important; }
        @media (max-width: 480px) {
            .content .container { padding: 0.3rem !important; } #bloc_1, #bloc_2, #bloc_3 { padding: 0.6rem !important; }
            h4 { font-size: 1.1rem; margin-bottom: 12px; } h4 i { font-size: 24px !important; }
            #liste, #add, #rapport, #payer, #save, #edit_save, #annuler, #edit_annuler, #resetFilters { padding: 3px 8px !important; font-size: 0.65rem; }
            #payer { min-width: 70px; flex: 1 1 auto; justify-content: center; } .table thead th { font-size: 0.62rem; padding: 8px 4px !important; }
            .table tbody td { padding: 6px 8px !important; font-size: 0.7rem; line-height: 1.2; } .modal.fade#pdfModal .modal-header { padding: 0.8rem 1rem; }
            .modal.fade#pdfModal .modal-header .modal-title { font-size: 1rem; } .modal.fade#pdfModal #pdfIframe { height: 35vh; }
            .modal.fade#pdfModal #btn_payer { padding: 8px 16px; font-size: 0.75rem; } .modal.fade#pdfModal .btn-secondary { padding: 6px 16px; font-size: 0.7rem; }
            .modal.fade#pdfModal #msg_facture { font-size: 0.7rem; padding: 6px 12px; } .modal.fade#qrModal .modal-header { padding: 0.8rem 1rem; }
            .modal.fade#qrModal .modal-header .modal-title { font-size: 1rem; } .modal.fade#qrModal #qrIframe { height: 40vh; }
        }
        .select2-container--bootstrap .select2-selection { height: 38px !important; border-radius: 14px !important; border: 1px solid #e2e8f0 !important; background: #ffffff !important; box-shadow: none !important; font-weight: 500; font-size: 0.85rem; padding: 0 12px; }
        .select2-container--bootstrap .select2-selection__arrow { height: 38px !important; }
        .select2-container--bootstrap .select2-selection__rendered { line-height: 38px !important; padding-left: 0; color: #1e2a3e; }
        .select2-container--bootstrap .select2-selection__placeholder { color: #6c757d; }
        .select2-dropdown { border-radius: 14px !important; border: 1px solid #e2e8f0 !important; box-shadow: var(--shadow-light); }
        .select2-results__option { padding: 8px 12px; font-size: 0.85rem; }
        .select2-results__option--highlighted { background: #e6f0ff !important; color: #0a192f !important; }
        .select2-container--bootstrap .select2-results__options { max-height: 200px !important; overflow-y: auto !important; }
        .param-nav-tabs .nav-link { border: none; color: #64748b; font-weight: 600; font-size: 0.9rem; padding: 16px 20px; border-bottom: 3px solid transparent; transition: all 0.2s; background: transparent; }
        .param-nav-tabs .nav-link i { margin-right: 6px; }
        .param-nav-tabs .nav-link:hover { color: #0a192f; border-bottom-color: #cbd5e1; }
        .param-nav-tabs .nav-link.active { color: #17a2b8; border-bottom-color: #17a2b8; background: transparent; }
        .param-section-title { font-weight: 700; color: #0a192f; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; padding-left: 12px; border-left: 4px solid #17a2b8; margin-bottom: 16px; }
        .param-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }
        .param-info-item { background: white; padding: 12px 16px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 3px; }
        .param-info-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px; }
        .param-info-label i { color: #17a2b8; margin-right: 4px; }
        .param-info-value { font-size: 0.92rem; color: #0a192f; font-weight: 700; }
        .param-finance-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; }
        .param-finance-card { padding: 14px 16px; border-radius: 14px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.12); transition: transform 0.2s; }
        .param-finance-card:hover { transform: translateY(-2px); }
        .param-finance-card.usd-card { background: linear-gradient(135deg, #3B82F6, #2563eb); }
        .param-finance-card.paye-card { background: linear-gradient(135deg, #0a192f, #1e3a5f); }
        .param-finance-card.credit-card { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .param-finance-card.benefice-card { background: linear-gradient(135deg, #198754, #146c43); }
        .param-statut-card { padding: 16px 20px; border-radius: 14px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.12); display: flex; align-items: center; gap: 12px; font-weight: 700; transition: transform 0.2s; }
        .param-statut-card:hover { transform: translateY(-2px); }
        .param-statut-card.paid { background: linear-gradient(135deg, #198754, #146c43); }
        .param-statut-card.unpaid { background: linear-gradient(135deg, #dc3545, #b02a37); }
        .param-statut-card.partiel { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .param-statut-card i { font-size: 2rem; opacity: 0.95; }
        .param-statut-card .param-statut-txt { display: flex; flex-direction: column; gap: 2px; }
        .param-statut-card .param-statut-title { font-size: 0.72rem; text-transform: uppercase; opacity: 0.9; letter-spacing: 0.4px; }
        .param-statut-card .param-statut-value { font-size: 1.3rem; font-weight: 800; }
        .param-statut-card .param-statut-sub { font-size: 0.78rem; opacity: 0.9; font-weight: 500; }
        #paramFactureModal .modal-dialog { max-width: 98vw !important; width: 98vw !important; margin: 1vh auto !important; height: 96vh; }
        #paramFactureModal .modal-content { height: 96vh; max-height: 96vh; display: flex; flex-direction: column; }
        #paramFactureModal .modal-body { flex: 1 1 auto; max-height: none !important; overflow: hidden !important; display: flex; flex-direction: column; padding: 0; }
        #paramFactureModal .tab-content { flex: 1 1 auto; max-height: none !important; overflow-y: auto !important; padding: 1.75rem 2rem !important; }
        #paramFactureModal .modal-footer { flex-shrink: 0; }
        #paramFactureModal .modal-body::-webkit-scrollbar, #paramFactureModal .tab-content::-webkit-scrollbar { width: 10px; }
        #paramFactureModal .modal-body::-webkit-scrollbar-thumb, #paramFactureModal .tab-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #paramFactureModal .param-info-grid { grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)) !important; }
        #paramFactureModal .param-finance-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important; }
        #paramFactureModal .param-totals-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important; }
        #paramFactureModal #param_articles_table thead th, #paramFactureModal #param_paiements_table thead th { font-size: 0.78rem !important; padding: 12px 10px !important; }
        #paramFactureModal #param_articles_table tbody td, #paramFactureModal #param_paiements_table tbody td { font-size: 0.82rem !important; padding: 10px 10px !important; }
        #paramFactureModal .param-inline-input { min-width: 110px !important; height: 38px !important; font-size: 0.85rem !important; }
        @media (max-width: 992px) { #paramFactureModal .modal-dialog { max-width: 100vw !important; width: 100vw !important; margin: 0 !important; height: 100vh; } #paramFactureModal .modal-content { height: 100vh; max-height: 100vh; border-radius: 0 !important; } #paramFactureModal .tab-content { padding: 1rem !important; } }
        #param_articles_table tbody tr:hover, #param_paiements_table tbody tr:hover { background: #e6f0ff !important; }
        .param-inline-input { width: 100% !important; min-width: 90px; height: 34px !important; font-size: 0.8rem !important; padding: 4px 8px !important; border-radius: 8px !important; text-align: right; font-weight: 600; }
        .param-inline-input.frais-input { border-color: #f59e0b !important; background: #fffbeb !important; color: #92400e; }
        .param-inline-input.reduction-input { border-color: #dc2626 !important; background: #fef2f2 !important; color: #991b1b; }
        .dual-currency b { display: block; font-size: 0.82rem; }
        .dual-currency small { display: block; font-size: 0.7rem; color: #64748b; font-weight: 500; }
        .param-totals-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-top: 10px; }
        .param-total-item { background: white; padding: 12px 14px; border-radius: 10px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06); border: 1px solid #eef2f6; transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .param-total-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .param-total-label { font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.4px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .param-total-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .param-total-value { font-size: 0.92rem; font-weight: 800; line-height: 1.2; }
        .param-total-sub { font-size: 0.72rem; font-weight: 600; margin-top: 3px; color: #64748b; line-height: 1.2; }
        .param-total-item.total-qte .param-total-value { color: #17a2b8; } .param-total-item.total-qte .param-total-dot { background: #17a2b8; }
        .param-total-item.total-pa .param-total-value { color: #6366f1; } .param-total-item.total-pa .param-total-dot { background: #6366f1; }
        .param-total-item.total-pv .param-total-value { color: #3B82F6; } .param-total-item.total-pv .param-total-dot { background: #3B82F6; }
        .param-total-item.total-papv .param-total-value { color: #8b5cf6; } .param-total-item.total-papv .param-total-dot { background: #8b5cf6; }
        .param-total-item.total-brut-pa .param-total-value { color: #6366f1; } .param-total-item.total-brut-pa .param-total-dot { background: #6366f1; }
        .param-total-item.total-brut-pv .param-total-value { color: #3B82F6; } .param-total-item.total-brut-pv .param-total-dot { background: #3B82F6; }
        .param-total-item.total-gen .param-total-value { color: #0a192f; } .param-total-item.total-gen .param-total-dot { background: #0a192f; } .param-total-item.total-gen .param-total-sub { color: #3B82F6; }
        .param-total-item.total-frais .param-total-value { color: #f59e0b; } .param-total-item.total-frais .param-total-dot { background: #f59e0b; } .param-total-item.total-frais .param-total-sub { color: #d97706; }
        .param-total-item.total-red .param-total-value { color: #dc2626; } .param-total-item.total-red .param-total-dot { background: #dc2626; } .param-total-item.total-red .param-total-sub { color: #b91c1c; }
        .param-total-item.total-benef .param-total-value { color: #198754; } .param-total-item.total-benef .param-total-dot { background: #198754; } .param-total-item.total-benef .param-total-sub { color: #146c43; }
        @media (max-width: 768px) { #paramFactureModal .param-finance-grid { grid-template-columns: repeat(2, 1fr); } #paramFactureModal .param-info-grid { grid-template-columns: 1fr; } #paramFactureModal .param-nav-tabs .nav-link { padding: 10px 8px; font-size: 0.72rem; } #paramFactureModal .param-statut-card { padding: 12px 14px; } #paramFactureModal .param-statut-card i { font-size: 1.6rem; } #paramFactureModal .param-statut-card .param-statut-value { font-size: 1.1rem; } #paramFactureModal .param-totals-grid { grid-template-columns: repeat(2, 1fr); } }
        #rapportModal .modal-header { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
        #rapportModal #rapport_date_range { width: 100%; height: 38px; border-radius: 14px !important; border: 1px solid #e2e8f0 !important; padding: 8px 12px; font-weight: 500; font-size: 0.85rem; background: #fff; }
        #rapportModal #rapport_date_range:focus { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important; outline: none; }
        #rapportModal #rapport_user_filter { width: 100%; height: 38px; border-radius: 14px !important; border: 1px solid #e2e8f0 !important; padding: 8px 12px; font-weight: 500; font-size: 0.85rem; background: #fff; }
        #rapportModal #rapport_user_filter:focus { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important; outline: none; }
        #rapportModal #rapport_user_filter:disabled { background: #f1f5f9 !important; cursor: not-allowed; opacity: 0.85; }
        #rapportModal #rapport_client_filter { width: 100%; height: 38px; border-radius: 14px !important; border: 1px solid #e2e8f0 !important; padding: 8px 12px; font-weight: 500; font-size: 0.85rem; background: #fff; }
        #rapportModal #rapport_client_filter:focus { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important; outline: none; }
        #rapport_table thead th { background: #FEE2E2 !important; color: #7f1d1d; border-bottom: 2px solid #fca5a5 !important; }
        #rapport_table tbody tr:hover { background: #fef2f2 !important; }
        #rapport_table tfoot td { background: #f1f5f9; border-top: 2px solid #cbd5e1 !important; font-size: 0.82rem; }
    </style>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 10px;padding-bottom: 10px;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn-primary btn-sm" id="liste" href="#">
                                        <i class="zmdi zmdi-email-open"></i> Liste
                                    </a>
                                    &nbsp;
                                    @if (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0 || Auth::user()->role == 0)
                                        @php
                                            $add = 0;
                                            if (Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()->count() != 0) {
                                                $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
                                            }
                                        @endphp
                                        @if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0)))
                                            <a class="btn-primary btn-sm" id="add" href="#">
                                                <i class="zmdi zmdi-email"></i> Ajouter
                                            </a>
                                        @else
                                            <a class="btn-primary btn-sm" id="add_r" href="#">
                                                <i class="zmdi zmdi-accounts-add"></i> Ajouter
                                            </a>
                                        @endif
                                    @endif
                                    &nbsp;
                                    <a class="btn-danger btn-sm" id="rapport" href="#">
                                        <i class="zmdi zmdi-chart"></i> Rapport
                                    </a>
                                    &nbsp;
                                    <a class="btn-sm" id="payer" href="#">
                                        <i class="fas fa-hand-holding-usd"></i> Payer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de facture</h6>
                </div>

                <div id="bloc_1" style="margin-top: 12px;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);">
                        <i style="font-size: 40px;" class="zmdi zmdi-email-open text-info"></i> Liste
                        <span class="badge-invoice">
                            <i class="zmdi zmdi-view-list" style="color: white;"></i> Factures : <span id="invoiceCount">0</span>
                        </span>
                    </h4>

                    <div class="filters-container">
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-label text-danger"></i> N° Facture</label>
                            <input type="text" id="filterNumero" class="form-control" placeholder="Rechercher par numéro...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-accounts text-danger"></i> Client / Libellé</label>
                            <input type="text" id="filterClient" class="form-control" placeholder="Rechercher par client ou libellé...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-account text-danger"></i> Utilisateur</label>
                            <input type="text" id="filterUser" class="form-control" placeholder="Rechercher par utilisateur...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-money text-danger"></i> Statut</label>
                            <select id="filterStatut" class="form-control">
                                <option value="all">Tous</option>
                                <option value="paid">Payées</option>
                                <option value="unpaid">Impayées</option>
                                <option value="partiel">Partielles</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-table text-danger"></i> Table</label>
                            <input type="text" id="filterTable" class="form-control" placeholder="Rechercher par table...">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                            <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période">
                        </div>
                        <div class="filter-group">
                            <label><i class="zmdi zmdi-chart text-danger"></i> Montant</label>
                            <input type="number" id="filterMontant" class="form-control" placeholder="Montant exact" step="0.01">
                        </div>
                        <div class="filter-group">
                            <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 8px 18px;">
                                <i class="zmdi zmdi-refresh"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <div class="invoice-badges-container">
                        <span class="invoice-count-badge usd-badge">
                            <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsd">0,00</span> USD
                        </span>
                        <span class="invoice-count-badge cdf-badge">
                            <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdf">0,00</span> CDF
                        </span>
                        <span class="invoice-count-badge paye-usd">
                            <i class="zmdi zmdi-money"></i> Payé USD : <span id="totalPaidUsd">0,00</span> USD
                        </span>
                        <span class="invoice-count-badge paye-cdf">
                            <i class="zmdi zmdi-money-box"></i> Payé CDF : <span id="totalPaidCdf">0,00</span> CDF
                        </span>
                        <span class="invoice-count-badge credit-usd">
                            <i class="zmdi zmdi-time"></i> Crédit USD : <span id="totalCreditUsd">0,00</span> USD
                        </span>
                        <span class="invoice-count-badge credit-cdf">
                            <i class="zmdi zmdi-time"></i> Crédit CDF : <span id="totalCreditCdf">0,00</span> CDF
                        </span>
                        <span class="invoice-count-badge benefice-usd">
                            <i class="zmdi zmdi-trending-up"></i> Bénéfice USD : <span id="totalBeneficeUsd">0,00</span> USD
                        </span>
                        <span class="invoice-count-badge benefice-cdf">
                            <i class="zmdi zmdi-trending-up"></i> Bénéfice CDF : <span id="totalBeneficeCdf">0,00</span> CDF
                        </span>
                    </div>

                    <div id="content_utilisateur" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="factures-table">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N° Facture</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Utilisateur</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Libelle / Client</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Table</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Montant</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Payé</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Crédit</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Date</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Mode de paiement</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody id="factures-tbody">
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bloc_t" class="row" style="width: 100%; margin: 0; display: none;">
                    <div id="bloc_2" style="margin-top: 12px; display: none;" class="col-lg-5">
                        <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-plus-circle text-info"></i>
                            Ajouter un article</h4>
                        <form id="form_add" action="#" method="post">
                            @csrf
                            <div class="row">
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Numero facture</span></label>
                                        <select id="numero_facture" name="numero_facture" class="select2"
                                            data-placeholder="Selectionnez un type de sortie">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Il s'agit de quel article ?</span></label>
                                        <select id="type_sortie" name="type_sortie" class="select2"
                                            data-placeholder="Selectionnez un article">
                                            <option selected value="">Selectionnez un article</option>
                                            @foreach ($articles as $data)
                                                @php
                                                    $devise = $data->devise ?? 0;
                                                    $prix_detail = $data->prix_detail ?? 0;
                                                    $prix_gros = $data->prix_gros ?? 0;
                                                    if ($devise == 0) {
                                                        $prixHtml = '<span class="text-success">D : </span>' . number_format($prix_detail, 2, ',', ' ') . '(USD), <span class="text-success">G : </span> ' . number_format($prix_gros, 2, ',', ' ') . 'USD';
                                                    } else {
                                                        $prixHtml = '<span class="text-success">D : </span>' . number_format($prix_detail, 2, ',', ' ') . '(CDF), <span class="text-success">G : </span> ' . number_format($prix_gros, 2, ',', ' ') . '(CDF)';
                                                    }
                                                @endphp

                                                @if (($data->activite_id != 0) && ($data->stock > $data->seuil_minimum))
                                                    <option value="{{ $data->id }}" data-devise="{{ $data->devise ?? 0 }}">
                                                        🟢 {{ $data->nom_article }}
                                                        {{ (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') }}
                                                        ({{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }})
                                                        Prix : ({!! $prixHtml !!})
                                                    </option>
                                                @else
                                                    @php
                                                        $erreurs = [];
                                                        if ($data->activite_id == 0) {
                                                            $erreurs[] = 'Activité non définie';
                                                        }
                                                        if ($data->stock <= $data->seuil_minimum) {
                                                            $erreurs[] = 'Stock insuffisant';
                                                        }
                                                        $message = implode(' et ', $erreurs);
                                                    @endphp
                                                    <option disabled value="{{ $data->id }}" data-devise="{{ $data->devise ?? 0 }}">
                                                        🔴 {{ $data->nom_article }}
                                                        {{ (Mesures::where('id', $data->mesure_id)->first()['nom'] ?? 'N/A') }}
                                                        ({{ Societes::where('id', $data->societe_id)->first()['nom'] ?? 'N/A' }})
                                                        Prix : {!! $prixHtml !!} : {{ $message }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="row">
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Il s'agit de quelle action ?</span></label>
                                        <select id="action" name="action" class="form-control"
                                            data-placeholder="Selectionnez une action">
                                            <option style="display: none;" selected value="">Selectionnez une action</option>
                                            <option selected value="1">Une vente</option>
                                            @if (Auth::user()->role == 0)
                                                <option style="display: none;" value="2">Un prêt</option>
                                                <option style="display: none;" value="3">Une perte</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-info"></i> Type de vente ?</span></label>
                                        <select id="type_vente_id" name="type_vente_id" class="form-control"
                                            data-placeholder="Selectionnez un type de vente">
                                            <option style="display: none;" selected value="">Selectionnez un type de vente</option>
                                           @foreach ($typeventes as $data)
                                                <option value="{{ $data->id }}">
                                                    <?= $data->nom ?>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-money"></i> Quantité </span></label>
                                        <input id="quantite" name="quantite" type="text" class="form-control input-mask"
                                            data-mask="00000000000000000000000000000000000000"
                                            style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                            placeholder="Quantité (Ex : 10)">
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: -8px;" class="row">
                                <div class="col-6" style="display: none;">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-money"></i> devise </span></label>
                                        <select id="devise" name="devise" class="select2"
                                            data-placeholder="Selectionnez une devise">
                                            <option selected class="form-control" value="">Selectionnez une devise
                                            </option>
                                            <option class="form-control" value="0"> USD</option>
                                            <option class="form-control" value="1"> CDF</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display: none;" class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-money"></i> Taux</span></label>
                                        <input id="taux" name="taux" type="text" class="form-control input-mask" value="2200"
                                            data-mask="00000000000000000000000000000000000000"
                                            style="font-weight: bold;padding-left: 5px;border-bottom: 1px solid rgba(0, 0, 0, 0.1);"
                                            placeholder="Taux (Ex : 10)">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-comment"></i> Libelle <span style="color:#e31b23;">(nom client passager)</span></span></label>
                                        <textarea id="libelle" name="libelle"
                                            style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                            class="form-control" placeholder="Libellé" cols="2" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                                class="zmdi zmdi-accounts"></i> Clients <span style="color:#e31b23;">(nom client existant)</span></span></label>
                                        <select id="client_id" name="client_id" class="form-control select2"
                                                data-placeholder="Rechercher un client..." style="width: 100%;"
                                                data-theme="bootstrap">
                                            <option value="">Selectionnez un client</option>
                                            @foreach ($clients as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: -8px;" class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-money"></i> Montant total <span id="montant_total_devise" style="color:#e31b23;">(USD)</span>
                                        </label>
                                        <input id="montant_total" name="montant_total" type="text"
                                               class="form-control"
                                               style="font-weight: bold; background: #e9ecef;"
                                               placeholder="Calculé automatiquement" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-minus-circle"></i> Réduction <span id="reduction_devise" style="color:#e31b23;">(USD)</span>
                                        </label>
                                        <input id="reduction" name="reduction" type="text"
                                               class="form-control input-mask"
                                               data-mask="00000000000000000000000000000000000000"
                                               value="0"
                                               placeholder="Réduction (Ex : 500)">
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: -8px;" class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-check-circle"></i> Montant total réduit <span id="montant_reduit_devise" style="color:#e31b23;">(USD)</span>
                                        </label>
                                        <input id="montant_reduit" name="montant_reduit" type="text"
                                               class="form-control"
                                               style="font-weight: bold; background: #d1fae5; color:#065f46;"
                                               placeholder="Calculé automatiquement" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="text-info" style="font-weight: bold;margin-top: 16px;">
                                            <i class="zmdi zmdi-phone"></i> Contact <span style="color:#e31b23;">(client passager - optionnel)</span>
                                        </label>
                                        <input id="contact" name="contact" type="text"
                                               class="form-control"
                                               style="font-weight: bold;"
                                               placeholder="Ex : +243 81 23 45 678">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            </div>
                        </form>
                        <div style="margin-top: 15px;display: none;" class="row">
                            <div class="col-12">
                                <label class="text-info" style="font-weight: bold;"><i class="zmdi zmdi-info"></i> Déposez
                                    votre de sortie d'article</span></label>
                                <form method="post"
                                    style="background-color: transparent;border: 4px dashed rgba(0, 0, 0, 0.2);border-radius: 10px;"
                                    action="{{ route('upload_fichier_sortie') }}" class="dropzone" id="dropzonewidget">
                                    @csrf
                                    <input type="hidden" id="n_s" name="n_s" value="">
                                </form>
                            </div>
                        </div>
                        <form action="">
                            <div style="margin-top: 20px;" class="row">
                                <div class="col-12">
                                    <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                    <?php
                                    $edit = 0;
                                    $delete = 0;
                                    $add = 0;
                                    if (
                                        Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])
                                            ->get()
                                            ->count() != 0
                                    ) {
                                        $edit = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->edit;
                                        $delete = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->delete;
                                        $add = Writes::where(['ressource_id' => $ressource_id_1, 'groupe_id' => $groupe_user_id])->get()[0]->add;
                                    }
                                    ?>
                                    <?php } ?>
                                    <?php if ((($add == 1) && (Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($add == 0) && (Auth::user()->role == 0))) { ?>
                                    <button id="save" class="btn btn-info btn-sm">Enregister <i
                                            class="zmdi zmdi-save"></i></button>
                                    <?php } else { ?>
                                    <button id="save_r" class="btn btn-info btn-sm">Enregister <i
                                            class="zmdi zmdi-save"></i></button>
                                    <?php } ?>
                                    <button id="annuler" class="btn btn-danger btn-sm">Annuler <i
                                            class="zmdi zmdi-close-circle"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" style="text-align: center;">
                                    <span style="font-weight: bold;" id="msg">
                                    </span>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="row" id="content_sortie">
                        </div>
                        <br>
                    </div>

                    <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-7">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <span id="data_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <button style="display: none;" data-toggle="modal" data-target="#pret" id="btn_pret">Sup</button>
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous supprimez ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="pret" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        confirmez la remise de ce prêt ?</h5>
                </div>
                <div class="modal-body">
                    <p id="element_pret" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_p" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_p" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <span id="data_frais_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#c_frais" id="btn_frais">Sup</button>
    <div class="modal fade" id="c_frais" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous approuvez ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element_1" style="text-align: center;"></p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui_frais" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non_frais" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteFactureModal" tabindex="-1" role="dialog" aria-labelledby="deleteFactureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold;font-size: 16px;">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer la facture ci-dessous ? Cette action est irréversible.</p>
                    <div style="background: #f8f9fa; padding: 12px 15px; border-radius: 8px; margin-top: 10px;">
                        <table style="width:100%; font-size:0.9rem; border-collapse:collapse;">
                            <tr><td style="padding:5px 0; font-weight:600;">N° facture :</td><td style="padding:5px 0;" id="delete_facture_numero">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Client / Libellé :</td><td style="padding:5px 0;" id="delete_facture_client">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Montant :</td><td style="padding:5px 0;" id="delete_facture_montant">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Date :</td><td style="padding:5px 0;" id="delete_facture_date">-</td></tr>
                            <tr><td style="padding:5px 0; font-weight:600;">Statut :</td><td style="padding:5px 0;" id="delete_facture_statut">-</td></tr>
                        </table>
                    </div>
                </div>
                <div style="font-weight: bold;text-align: center; padding-bottom: 15px;">
                    <button id="confirm_delete_facture" class="btn btn-info btn-sm" style="margin-right: 8px;">Oui, supprimer</button>
                    <button class="btn btn-danger btn-sm" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%; width: 60%;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #007bff; color: white;">
                    <h5 class="modal-title" id="pdfModalLabel">
                        <i class="zmdi zmdi-file-pdf"></i> Aperçu facture : <span id="n_fac"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <iframe id="pdfIframe" src="" style="width: 100%; height: 50vh; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <div class="container-fluid">
                        <div id="boite_de_control" class="row w-100" style="margin: 0;">
                            <div class="col-lg-4">
                                <input type="number" id="montant_recu" class="form-control" placeholder="Montant reçu"
                                    step="0.01" style="border-radius: 8px;">
                                <input type="hidden" id="cdf_montant_payer" name="cdf_montant_payer">
                                <input type="hidden" id="usd_montant_payer" name="usd_montant_payer">
                                <input type="hidden" id="payer" name="payer">
                                <input type="hidden" id="id_fac" name="id_fac">
                            </div>
                            <div class="col-lg-4">
                                <select id="devise_recu" class="form-control" style="border-radius: 8px;">
                                    <option value="">Sellectionnez une devise</option>
                                    <option value="0">USD</option>
                                    <option value="1">CDF</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" id="btn_payer" class="btn btn-success"
                                    style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 8px;">
                                    <i class="zmdi zmdi-money"></i> Payer
                                </button>
                            </div>
                        </div>
                        <div id="boite_de_message" class="row w-100" style="margin: 0;">
                            <div class="col-lg-12" style="text-align: center;">
                                <span style="font-weight: bold;" id="msg_facture"></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row w-100 mt-2">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    style="border-radius: 8px;">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 100%; width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">
                        <i class="fas fa-hand-holding-usd"></i> QR Code de paiement
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <iframe id="qrIframe" src="" style="width: 100%; height: 60vh; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paramFactureModal" tabindex="-1" role="dialog" aria-labelledby="paramFactureLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #0a192f, #1e3a5f); color: white; border-bottom: none; padding: 1.1rem 1.5rem;">
                    <h5 class="modal-title" id="paramFactureLabel" style="font-weight: 700;">
                        <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
                        Paramètres de la facture : <span id="param_numero" class="text-warning">-</span>
                    </h5>
                    <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
                        <button type="button" id="param_refresh_btn"
                                style="background: rgba(255,255,255,0.15); border: none; color: white; width: 34px; height: 34px; border-radius: 50%; cursor: pointer;"
                                title="Actualiser">
                            <i class="zmdi zmdi-refresh"></i>
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9; margin: 0; padding: 0 0 0 8px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="background: #f8fafc; padding: 0;">
                    <ul class="nav nav-tabs param-nav-tabs" id="paramTabs" role="tablist" style="border-bottom: 1px solid #e2e8f0; background: white; padding: 0 1.5rem;">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-info-tab" data-toggle="tab" href="#tab-info" role="tab">
                                <i class="zmdi zmdi-info"></i> Informations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-articles-tab" data-toggle="tab" href="#tab-articles" role="tab">
                                <i class="zmdi zmdi-shopping-cart"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-paiements-tab" data-toggle="tab" href="#tab-paiements" role="tab">
                                <i class="zmdi zmdi-money"></i> Paiements
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="padding: 1.75rem 2rem;">
                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-check-circle text-info"></i> Statut de paiement
                            </h6>
                            <div id="param_statut_card_wrapper" style="margin-bottom: 22px;">
                                <div class="param-statut-card paid" id="param_statut_card">
                                    <i class="zmdi zmdi-check-circle" id="param_statut_icon"></i>
                                    <div class="param-statut-txt">
                                        <span class="param-statut-title">Statut actuel de la facture</span>
                                        <span class="param-statut-value" id="param_statut_label">-</span>
                                        <span class="param-statut-sub" id="param_statut_sub">-</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-info text-info"></i> Informations générales
                            </h6>
                            <div class="param-info-grid">
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account"></i> Client / Libellé</span>
                                    <span class="param-info-value" id="param_client">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account-circle"></i> Utilisateur</span>
                                    <span class="param-info-value" id="param_user">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-calendar"></i> Date</span>
                                    <span class="param-info-value" id="param_date">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-table"></i> Table</span>
                                    <span class="param-info-value" id="param_table">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-money"></i> Devise</span>
                                    <span class="param-info-value" id="param_devise">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-chart"></i> Taux</span>
                                    <span class="param-info-value" id="param_taux">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-money-box"></i> Mode paiement</span>
                                    <span class="param-info-value" id="param_mode">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-account-box"></i> N° Client ID</span>
                                    <span class="param-info-value" id="param_client_id">-</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-edit"></i> ID Facture</span>
                                    <span class="param-info-value" id="param_facture_id">-</span>
                                </div>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-balance-wallet text-info"></i> Résumé financier
                            </h6>
                            <div class="param-finance-grid">
                                <div class="param-finance-card usd-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-money"></i> Montant total</div>
                                    <div class="param-finance-value" id="param_montant_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_montant_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card paye-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-check"></i> Déjà payé</div>
                                    <div class="param-finance-value" id="param_paye_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_paye_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card credit-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-time"></i> Crédit restant</div>
                                    <div class="param-finance-value" id="param_credit_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_credit_cdf">0.00 CDF</div>
                                </div>
                                <div class="param-finance-card benefice-card">
                                    <div class="param-finance-label"><i class="zmdi zmdi-trending-up"></i> Bénéfice</div>
                                    <div class="param-finance-value" id="param_benefice_usd">0.00 USD</div>
                                    <div class="param-finance-sub" id="param_benefice_cdf">0.00 CDF</div>
                                </div>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-alert-circle text-info"></i> Frais & Réductions appliqués
                            </h6>
                            <div class="param-info-grid">
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-shopping-cart"></i> Prix d'achat total</span>
                                    <span class="param-info-value" id="param_prix_achat_show" style="color:#6366f1;">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-money"></i> Prix de vente total</span>
                                    <span class="param-info-value" id="param_prix_vente_show" style="color:#3B82F6;">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-shopping-basket"></i> Montant brut (prix achat)</span>
                                    <span class="param-info-value" id="param_brut_achat_show" style="color:#6366f1;">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-shopping-basket"></i> Montant brut (prix vente)</span>
                                    <span class="param-info-value" id="param_brut_vente_show" style="color:#3B82F6;">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-alert-circle"></i> Frais de crédit total</span>
                                    <span class="param-info-value text-warning" id="param_frais_credit_show">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-minus-circle"></i> Réduction totale</span>
                                    <span class="param-info-value text-danger" id="param_reduction_show">0.00</span>
                                </div>
                                <div class="param-info-item">
                                    <span class="param-info-label"><i class="zmdi zmdi-receipt"></i> Nombre d'articles</span>
                                    <span class="param-info-value" id="param_nb_articles">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-articles" role="tabpanel">
                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-shopping-cart text-info"></i> Détails des articles
                                <span style="font-size:0.72rem; text-transform:none; font-weight:500; color:#64748b; margin-left:8px;">
                                    (Montants en USD et CDF — Frais crédit et Réduction sont modifiables)
                                </span>
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <table class="table table-sm mb-0" id="param_articles_table">
                                    <thead style="background: #E7F5FE;">
                                        <tr>
                                            <th style="font-size: 0.72rem;">#</th>
                                            <th style="font-size: 0.72rem;">Article</th>
                                            <th style="font-size: 0.72rem;">Qté</th>
                                            <th style="font-size: 0.72rem;">Prix achat</th>
                                            <th style="font-size: 0.72rem;">Prix vente</th>
                                            <th style="font-size: 0.72rem;">Montant brut (prix achat)</th>
                                            <th style="font-size: 0.72rem;">Montant brut (prix vente)</th>
                                            <th style="font-size: 0.72rem;">Total</th>
                                            <th style="font-size: 0.72rem;">Frais crédit</th>
                                            <th style="font-size: 0.72rem;">Réduction</th>
                                            <th style="font-size: 0.72rem;">Bénéfice</th>
                                        </tr>
                                    </thead>
                                    <tbody id="param_articles_body">
                                        <tr><td colspan="11" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="param-section-title" style="margin-top: 22px;">
                                <i class="zmdi zmdi-chart text-info"></i> Totaux généraux
                            </h6>
                            <div class="param-totals-grid" id="param_totals_grid">
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <button type="button" class="btn btn-info btn-sm" id="param_btn_save_lines"
                                        style="border-radius: 40px; padding: 10px 26px;">
                                    <i class="zmdi zmdi-save"></i> Enregistrer les modifications
                                </button>
                                <br><br>
                                <span id="param_lines_msg" style="font-weight: 600; font-size: 0.85rem;"></span>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-paiements" role="tabpanel">
                            <h6 class="param-section-title">
                                <i class="zmdi zmdi-money text-info"></i> Détail des tranches de paiement
                            </h6>
                            <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <table class="table table-sm mb-0" id="param_paiements_table">
                                    <thead style="background: #E7F5FE;">
                                        <tr>
                                            <th style="font-size: 0.72rem;">#</th>
                                            <th style="font-size: 0.72rem;">Date</th>
                                            <th style="font-size: 0.72rem;">Mode</th>
                                            <th style="font-size: 0.72rem;">Devise</th>
                                            <th style="font-size: 0.72rem;">Reçu</th>
                                            <th style="font-size: 0.72rem;">Reste après</th>
                                        </tr>
                                    </thead>
                                    <tbody id="param_paiements_body">
                                        <tr><td colspan="6" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>
                                    </tbody>
                                    <tfoot id="param_paiements_foot" style="background: #f1f5f9; font-weight: 700;"></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background: white; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                            style="border-radius: 40px; padding: 8px 22px;">
                        <i class="zmdi zmdi-close"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rapportModal" tabindex="-1" role="dialog" aria-labelledby="rapportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1200px;">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border-bottom: none; padding: 1.1rem 1.5rem;">
                    <h5 class="modal-title" id="rapportModalLabel" style="font-weight: 700;">
                        <i class="zmdi zmdi-chart"></i> Rapport des montants reçus
                    </h5>
                    <div style="margin-left: auto; display: flex; gap: 8px; align-items: center;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9; margin: 0; padding: 0 0 0 8px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="background: #f8fafc; padding: 1.5rem; max-height: 75vh; overflow-y: auto;">
                    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom: 15px;">
                        <div style="flex:1; min-width: 200px;">
                            <label style="font-weight:700; color:#0a192f; font-size:0.75rem; text-transform:uppercase; margin-bottom:4px; display:block;">
                                <i class="zmdi zmdi-calendar text-danger"></i> Période du rapport
                            </label>
                            <input type="text" id="rapport_date_range" class="form-control" placeholder="Sélectionner une période">
                        </div>
                        <div style="flex:1; min-width: 200px;">
                            <label style="font-weight:700; color:#0a192f; font-size:0.75rem; text-transform:uppercase; margin-bottom:4px; display:block;">
                                <i class="zmdi zmdi-account text-danger"></i> Utilisateur (payeur)
                            </label>
                            <select id="rapport_user_filter" class="form-control">
                                <option value="all">Tous les utilisateurs</option>
                            </select>
                        </div>
                        <div style="flex:1; min-width: 200px;">
                            <label style="font-weight:700; color:#0a192f; font-size:0.75rem; text-transform:uppercase; margin-bottom:4px; display:block;">
                                <i class="zmdi zmdi-accounts text-danger"></i> Client / Libellé
                            </label>
                            <input type="text" id="rapport_client_filter" class="form-control" placeholder="Rechercher un client ou libellé...">
                        </div>
                        <div style="display:flex; gap:8px; align-items:flex-end; flex-wrap:wrap;">
                            <button id="rapport_apply_btn" class="btn-primary btn-sm" style="border-radius:40px;">
                                <i class="zmdi zmdi-refresh"></i> Appliquer
                            </button>
                            <button id="rapport_reset_btn" class="btn-danger btn-sm" style="border-radius:40px;">
                                <i class="zmdi zmdi-refresh-sync"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <div id="rapport_summary_badges" style="display:flex; flex-wrap:wrap; gap:8px 12px; margin-bottom: 15px;">
                        <span class="invoice-count-badge usd-badge">
                            <i class="zmdi zmdi-money"></i> Total USD reçu : <span id="rapport_total_usd">0,00</span> USD
                        </span>
                        <span class="invoice-count-badge cdf-badge">
                            <i class="zmdi zmdi-money-box"></i> Total CDF reçu : <span id="rapport_total_cdf">0,00</span> CDF
                        </span>
                        <span class="invoice-count-badge" style="background: linear-gradient(135deg, #17a2b8, #0e7490);">
                            <i class="zmdi zmdi-receipt"></i> Transactions : <span id="rapport_nb">0</span>
                        </span>
                    </div>

                    <div style="font-weight:700; color:#0a192f; font-size:0.85rem; margin-bottom:10px;">
                        <i class="zmdi zmdi-info text-danger"></i> Période : <span id="rapport_periode_label" style="color:#dc2626;">Toutes les dates</span>
                        &nbsp;•&nbsp;
                        <i class="zmdi zmdi-account text-danger"></i> Utilisateur : <span id="rapport_user_label" style="color:#dc2626;">Tous</span>
                        &nbsp;•&nbsp;
                        <i class="zmdi zmdi-accounts text-danger"></i> Client : <span id="rapport_client_label" style="color:#dc2626;">Tous</span>
                    </div>

                    <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background:white;">
                        <table class="table table-sm mb-0" id="rapport_table">
                            <thead style="background: #FEE2E2;">
                                <tr>
                                    <th style="font-size:0.72rem;">#</th>
                                    <th style="font-size:0.72rem;">Date paiement</th>
                                    <th style="font-size:0.72rem;">N° Facture</th>
                                    <th style="font-size:0.72rem;">Date facture</th>
                                    <th style="font-size:0.72rem;">Client / Libellé</th>
                                    <th style="font-size:0.72rem;" title="Utilisateur qui a créé la facture (factures.user_id)">
                                        <i class="zmdi zmdi-edit"></i> Enregistré par
                                    </th>
                                    <th style="font-size:0.72rem;" title="Utilisateur qui a encaissé le paiement (detailpaiessachats.user_id)">
                                        <i class="zmdi zmdi-money"></i> Encaissé par
                                    </th>
                                    <th style="font-size:0.72rem;">Mode</th>
                                    <th style="font-size:0.72rem;">Devise</th>
                                    <th style="font-size:0.72rem; text-align:right;">Montant reçu</th>
                                </tr>
                            </thead>
                            <tbody id="rapport_body">
                                <tr><td colspan="10" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>
                            </tbody>
                            <tfoot id="rapport_foot" style="background: #f1f5f9; font-weight: 700;"></tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer" style="background: white; border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn-secondary btn-sm" data-dismiss="modal"
                            style="border-radius: 40px; padding: 8px 22px; background:#64748b; color:white;">
                        <i class="zmdi zmdi-close"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

    <script src="{{ asset('assets/vendors/flot/jquery.flot.js') }} "></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.orderbars/jquery.flot.orderBars.js') }} "></script>
    <script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/bar.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/dynamic.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/pie.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/chart-tooltips.js') }}"></script>
    
    <script>
        $("#link_24").addClass("active");

        // ============================================================
        // ✅ VARIABLES GLOBALES
        // ============================================================
        var USER_ROLE         = {{ Auth::user()->role ?? 1 }};
        var CURRENT_USER_ID   = {{ Auth::user()->id }};
        var CURRENT_USER_NAME = "{{ addslashes(Auth::user()->name ?? '') }}";
        
        var ALL_USERS = [
            @foreach(\App\Models\User::orderBy('name')->get() as $u)
                { id: {{ $u->id }}, name: "{{ addslashes($u->name) }}" },
            @endforeach
        ];

        // ============================================================
        // ✅ FONCTION : CHARGER LES FACTURES EN JSON
        // ============================================================
        function chargerFactures() {
            let pageParam = "{{ $ressource_id_1 ?? 2 }}";

            $.ajax({
                url: "{{ url('/get_all_facture') }}",
                type: 'GET',
                data: { page: pageParam },
                dataType: 'json',
                success: function(response) {
                    let factures = response.factures;
                    let html = '';

                    if (factures.length === 0) {
                        html = '<tr><td colspan="10" class="text-center text-muted">Aucune facture trouvée</td></tr>';
                    } else {
                        factures.forEach(function(f) {
                            // Statut
                            let statutHtml = '';
                            if (f.statut_code === 'partiel') {
                                statutHtml = '<i class="zmdi zmdi-time text-warning"></i> <span class="text-warning">Partiel</span>';
                            } else if (f.statut_code === 'unpaid') {
                                statutHtml = '<i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">Impayé</span>';
                            } else {
                                statutHtml = '<i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">' + f.mode_paiement + '</span>';
                            }

                            // Table
                            let tableHtml = f.table_name;
                            if (f.table_id != 0) {
                                tableHtml = f.table_occupee == 1 
                                    ? '<i class="zmdi zmdi-close-circle text-danger"></i> <span class="text-danger">' + f.table_name + '</span>' 
                                    : '<i class="zmdi zmdi-check-circle text-success"></i> <span class="text-success">' + f.table_name + '</span>';
                            }

                            // Boutons d'action
                            let boutonsHtml = '';
                            
                            // Bouton Détails
                            if (f.can_display) {
                                let iconeDetail = f.reste_usd > 0 ? 'zmdi-money text-danger' : 'zmdi-eye text-success';
                                boutonsHtml += `<a href="#" class="btn-detail-facture" data-id="${f.id}" data-payer="${f.payer}" data-numero="${f.numero}" data-pdf-url="${f.lien || ''}"><i class="zmdi ${iconeDetail}"></i></a> &nbsp;`;
                            } else {
                                boutonsHtml += `<a href="#" class="btn-detail-refuse"><i class="zmdi ${f.reste_usd > 0 ? 'zmdi-money text-danger' : 'zmdi-eye text-success'}"></i></a> &nbsp;`;
                            }

                            // Bouton Paramètres
                            if (f.can_edit) {
                                boutonsHtml += `<a href="#" class="param-facture-btn" data-id="${f.id}"><i class="zmdi zmdi-settings text-info"></i></a> &nbsp;`;
                            }

                            // Bouton Supprimer
                            if (f.can_delete) {
                                boutonsHtml += `<a href="#" class="delete-facture-btn" data-id="${f.id}" data-numero="${f.numero}" data-client="${f.client_name}" data-montant="${f.montant_affichage}" data-date="${f.date}" data-statut="${f.reste_usd > 0 ? 'Impayé' : 'Payé'}"><i class="zmdi zmdi-delete text-danger"></i></a>`;
                            }

                            // Échappement des apostrophes pour éviter de casser l'attribut HTML
                            let articlesSafe = JSON.stringify(f.articles).replace(/'/g, "&#39;");
                            let paiementsSafe = JSON.stringify(f.paiements).replace(/'/g, "&#39;");

                            // Ligne du tableau
                            html += `
                                <tr id="row_${f.id}"
                                    data-facture-id="${f.id}"
                                    data-numero="${f.numero}"
                                    data-client="${f.client_name}"
                                    data-client-id="${f.client_id}"
                                    data-user="${f.facture_user_nom}"
                                    data-user-id="${f.user_id}"
                                    data-table="${f.table_name}"
                                    data-date="${f.date}"
                                    data-devise="${f.devise}"
                                    data-devise-label="${f.devise == 0 ? 'USD' : 'CDF'}"
                                    data-taux="${f.taux}"
                                    data-mode-paiement="${f.mode_paiement}"
                                    data-montant-usd="${f.montant_usd}"
                                    data-montant-cdf="${f.montant_cdf}"
                                    data-paye-usd="${f.montant_usd_paye}"
                                    data-paye-cdf="${f.montant_cdf_paye}"
                                    data-credit-usd="${f.reste_usd}"
                                    data-credit-cdf="${f.reste_cdf}"
                                    data-benefice-usd="${f.benefice_usd}"
                                    data-benefice-cdf="${f.benefice_cdf}"
                                    data-frais-credit-total="${f.frais_credit_total}"
                                    data-pdf-url="${f.lien || ''}"
                                    data-articles='${articlesSafe}'
                                    data-paiements='${paiementsSafe}'>
                                    
                                    <td class="numero-cell" data-numero="${f.numero}">${f.numero}</td>
                                    <td class="user-cell" data-user="${f.facture_user_nom}">${f.facture_user_nom}</td>
                                    <td class="client-cell" data-client="${f.client_name}">${f.client_name}</td>
                                    <td class="table-cell" data-table="${f.table_name}">${tableHtml}</td>
                                    <td class="montant-cell" data-montant="${f.montant_usd}">${f.montant_affichage}</td>
                                    <td class="paye-cell ${f.reste_usd > 0 ? 'text-danger' : 'text-success'}">${f.paye_affichage}</td>
                                    <td class="reste-cell ${f.reste_usd > 0 ? 'text-danger' : 'text-success'}">${f.reste_affichage}</td>
                                    <td class="date-cell" data-date="${f.date}">${f.date}</td>
                                    <td class="statut-cell" data-statut="${f.statut_code}">${statutHtml}</td>
                                    <td style="text-align: center;">${boutonsHtml}</td>
                                </tr>
                            `;
                        });
                    }

                    $('#factures-tbody').html(html);
                    filterInvoices();
                    saveFiltersToStorage();
                },
                error: function(xhr) {
                    console.error("Erreur JSON:", xhr);
                    $('#factures-tbody').html('<tr><td colspan="10" class="text-center text-danger">Erreur lors du chargement des factures.</td></tr>');
                }
            });
        }

        // ============================================================
        // ✅ GESTION GLOBALE DES CLICS (Délégation)
        // ============================================================
        $(document).on('click', '.btn-detail-facture', function(e) {
            e.preventDefault();
            var factureId = $(this).data('id');
            var payer = $(this).data('payer');
            var numero = $(this).data('numero');

            if(payer == 0) {
                $.get("{{ url('/refresh_detailfactureass') }}", {
                    invitation_id: factureId,
                }, function(refresh_editinvitations) {
                    $("#bloc_1").hide();
                    $("#bloc_2").show();
                    $("#bloc_3").show();
                    $("#bloc_t").show();
                    $("#bloc_3").html(refresh_editinvitations);
                });
            }
            else if(payer == 1) {
                $("#n_fac").html(numero);
                $("#id_fac").val(factureId);
                
                var pdfUrl = $(this).closest('tr').data('pdf-url');
                if (pdfUrl && pdfUrl !== '') {
                    currentPdfUrl = pdfUrl;
                    $("#pdfIframe").attr("src", pdfUrl);
                    $("#pdfModal").modal("show");
                } else {
                    $.get("{{ url('/print_facture') }}", {
                        "facture_id": factureId
                    }, function(response) {
                        if (response && response[0][0]) {
                            currentPdfUrl = response[0][0];
                            $("#pdfIframe").attr("src", response[0][0]);
                            $("#cdf_montant_payer").val(response[0][1]);
                            $("#usd_montant_payer").val(response[0][2]);
                            $("#payer").val(response[0][5]);
                            $("#pdfModal").modal("show");
                        } else {
                            alert("Aucun PDF disponible pour cette facture.");
                        }
                    }).fail(function() {
                        alert("Erreur lors de la récupération du PDF.");
                    });
                }
            }
        });

        $(document).on('click', '.btn-detail-refuse', function(e) {
            e.preventDefault();
            $("#btn_refus").trigger("click");
        });

        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_t").hide();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {});
        });

        $("#add").click(function(e) {
            $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) {
                $("#numero_facture").html(response);
            });
            $.get("{{ url('/delete_facture_user_id') }}", {}, function(response) {});
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").show();
            $("#bloc_3").html('<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="fa fa-info-circle text-info"></i> Détails facture</h4>');
            $("#bloc_t").show();
        });

        $("#add_r").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });
        $("#save_r").click(function(e) { e.preventDefault(); $("#btn_refus").trigger("click"); });

        $("#annuler").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
        });

        $("#save").click(function(e) {
            e.preventDefault();
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...');
            function resetButton() { btn.prop('disabled', false).html('Enregister <i class="zmdi zmdi-save"></i>'); }

            var numero_facture = $("#numero_facture").val();
            var type_sortie = $("#type_sortie").val();
            var action = $("#action").val();
            var quantite = $("#quantite").val();
            var devise = $("#devise").val();
            var taux = $("#taux").val();
            var libelle = $("#libelle").val();
            var client = $("#client_id").val();
            var type_vente_id = $("#type_vente_id").val();
            var formData = $("#form_add").serialize() + "&page=" + encodeURIComponent("{{ $ressource_id_1 ?? 2 }}");

            if (numero_facture.trim().length == 0 || type_sortie.trim().length == 0 || action.trim().length == 0 || type_vente_id.trim().length == 0 || quantite.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Veuillez remplir tous les champs obligatoires');
                setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
            }
            if (quantite.trim() <= 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> La quantité doit être supérieur à 0');
                setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
            }

            $.get("{{ url('/get_prix_article') }}", {
                article_id: type_sortie, type_vente_id: type_vente_id,
            }, function(get_prix_article) {
                $.get("{{ url('/check_seuil_minimum') }}", {
                    article_id: type_sortie, devise: devise, quantite: quantite, taille_lot: get_prix_article[0][1], prix_unitaire: get_prix_article[0][0], taux: taux,
                }, function(repp) {
                    var data_rep = repp.split("__________");
                    if ((data_rep[0] == 0) && (data_rep[3] == 1)) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le seuil minimum est de : ' + data_rep[1] + ', sortie dispo : ' + data_rep[2]);
                        setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
                    } else if ((data_rep[0] == -1) && (data_rep[3] == 1)) {
                        $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le stock est vide');
                        setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
                    } else {
                        if (client.trim().length == 0 && libelle.trim().length == 0) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le client ou le libellé');
                            setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
                        }
                        
                        var libelleClean = libelle.trim().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]+/g, " ").trim();
                        if (/^(client|clients)( pas+agers?| pas+ageres?)?$/.test(libelleClean) || /^(libelle|nom|libelle client|nom client)$/.test(libelleClean)) {
                            $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Le libellé ne peut pas être "client" ou similaire.');
                            setTimeout(() => { $('#msg').html(""); }, 9000); resetButton(); return;
                        }

                        $.ajax({
                            type: "POST", url: "/add_achat_article", data: formData,
                            success: function(response) {
                                resetButton();
                                $("#quantite, #libelle, #contact, #montant_total, #montant_reduit").val("");
                                $("#type_sortie, #type_vente_id, #client_id").val("").trigger("change");
                                $("#reduction").val("0");
                                Dropzone.forElement('#dropzonewidget').removeAllFiles(true);
                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Achat effectué avec succès');
                                chargerFactures();
                                $.get("{{ url('/get_achat') }}", {}, function(res) { $("#bloc_3").html(res); });
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            },
                            error: function(xhr, status, error) {
                                resetButton();
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Erreur lors de l\'enregistrement');
                                setTimeout(() => { $('#msg').html(""); }, 9000);
                            }
                        });
                    }
                });
            });
        });

        $(".dropzone").dropzone({ addRemoveLinks: true, removedfile: function(file) { return void 0; } });
        $(".dropzone_2").dropzone({ addRemoveLinks: true, removedfile: function(file) { return void 0; } });

        $.get("{{ url('/get_numero_facture_b') }}", {}, function(response) { $("#numero_facture").html(response); });

        $("#oui_p").click(function(e) {
            e.preventDefault();
            $.get("{{ url('/refresh_reprise_article') }}", { id: $("#data_id").html() }, function(refresh_reprise_article) {
                $("#bloc_3").html(refresh_reprise_article);
                $("#non_p").trigger("click");
            });
        });

        let filterTimeout;
        function saveFiltersToStorage() {
            const filters = {
                numero: $('#filterNumero').val(), client: $('#filterClient').val(), user: $('#filterUser').val(),
                statut: $('#filterStatut').val(), dateRange: $('#filterDateRange').val(), montant: $('#filterMontant').val(), table: $('#filterTable').val()
            };
            localStorage.setItem('invoiceFilters', JSON.stringify(filters));
        }

        function loadFiltersFromStorage() {
            const savedFilters = localStorage.getItem('invoiceFilters');
            if (savedFilters) {
                const f = JSON.parse(savedFilters);
                $('#filterNumero').val(f.numero || ''); $('#filterClient').val(f.client || '');
                $('#filterUser').val(f.user || ''); $('#filterStatut').val(f.statut || 'all');
                $('#filterDateRange').val(f.dateRange || ''); $('#filterMontant').val(f.montant || '');
                $('#filterTable').val(f.table || '');
            }
        }

        function filterInvoices() {
            const fNum = $('#filterNumero').val().toLowerCase();
            const fCli = $('#filterClient').val().toLowerCase();
            const fUsr = $('#filterUser').val().toLowerCase();
            const fSta = $('#filterStatut').val();
            const fMon = parseFloat($('#filterMontant').val());
            const fTab = $('#filterTable').val().toLowerCase();
            var dRange = $('#filterDateRange').val() || '';
            
            var dateDeb = null, dateFin = null;
            if (dRange) {
                var parts = dRange.split(' - ');
                if (parts.length === 2) {
                    function parseD(s) { var p = s.split('/'); return (p.length === 3) ? p[2] + '-' + p[1] + '-' + p[0] : null; }
                    dateDeb = parseD(parts[0]); dateFin = parseD(parts[1]);
                }
            }

            let visCount = 0, tUSD = 0, tCDF = 0, tPUSD = 0, tPCDF = 0, tCUSD = 0, tCCDF = 0, tBUSD = 0, tBCDF = 0;

            $('#factures-tbody tr').each(function() {
                const $r = $(this);
                let show = true;
                const nV = $r.find('.numero-cell').data('numero')?.toString().toLowerCase() || '';
                const cV = $r.find('.client-cell').data('client')?.toString().toLowerCase() || '';
                const uV = $r.find('.user-cell').data('user')?.toString().toLowerCase() || '';
                const sV = $r.find('.statut-cell').data('statut') || '';
                const tV = $r.find('.table-cell').data('table')?.toString().toLowerCase() || '';
                const mV = parseFloat($r.find('.montant-cell').data('montant')) || 0;

                if (fNum && !nV.includes(fNum)) show = false;
                if (show && fCli && !cV.includes(fCli)) show = false;
                if (show && fUsr && !uV.includes(fUsr)) show = false;
                if (show && fSta !== 'all' && sV !== fSta) show = false;
                if (show && !isNaN(fMon) && Math.abs(mV - fMon) > 0.009) show = false;
                if (show && fTab && !tV.includes(fTab)) show = false;

                if (show && dateDeb && dateFin) {
                    var dtText = $r.find('.date-cell').text().trim().split(' à ')[0];
                    if (dtText) {
                        var p = dtText.split('/');
                        if (p.length === 3) {
                            var cellD = p[2] + '-' + p[1] + '-' + p[0];
                            if (cellD < dateDeb || cellD > dateFin) show = false;
                        } else show = false;
                    } else show = false;
                }

                if (show) {
                    $r.show(); visCount++;
                    tUSD += parseFloat($r.data('montant-usd')) || 0; tCDF += parseFloat($r.data('montant-cdf')) || 0;
                    tPUSD += parseFloat($r.data('paye-usd')) || 0; tPCDF += parseFloat($r.data('paye-cdf')) || 0;
                    tCUSD += parseFloat($r.data('credit-usd')) || 0; tCCDF += parseFloat($r.data('credit-cdf')) || 0;
                    tBUSD += parseFloat($r.data('benefice-usd')) || 0; tBCDF += parseFloat($r.data('benefice-cdf')) || 0;
                } else { $r.hide(); }
            });

            $('#invoiceCount').text(visCount);
            $('#totalUsd').text(tUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdf').text(tCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidUsd').text(tPUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalPaidCdf').text(tPCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditUsd').text(tCUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCreditCdf').text(tCCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeUsd').text(tBUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalBeneficeCdf').text(tBCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
        }

        function resetAllFilters() {
            var today = moment();
            $('#filterDateRange').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));
            if ($('#filterDateRange').data('daterangepicker')) { $('#filterDateRange').data('daterangepicker').setStartDate(today); $('#filterDateRange').data('daterangepicker').setEndDate(today); }
            $('#filterNumero, #filterClient, #filterUser, #filterMontant, #filterTable').val('');
            $('#filterStatut').val('all');
            saveFiltersToStorage(); filterInvoices();
            $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Filtres réinitialisés').css('display', 'flex');
            setTimeout(() => { $('#msg').html('').css('display', 'none'); }, 3000);
        }

        function debouncedFilter() { clearTimeout(filterTimeout); filterTimeout = setTimeout(() => { filterInvoices(); saveFiltersToStorage(); }, 300); }
        function formatMoney(val) { return (parseFloat(val) || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }
        function safeParseJSON(val) { if (!val) return []; if (typeof val === 'object') return val; try { return JSON.parse(val) || []; } catch (e) { return []; } }

        var currentParamFactureId = null;
        var currentParamRow = null;

        function openParamModalFromRow($row) {
            currentParamRow = $row;
            currentParamFactureId = $row.data('facture-id');
            $('#param_numero').text($row.data('numero'));
            $('#param_client').text($row.data('client'));
            $('#param_user').text($row.data('user'));
            $('#param_date').text($row.data('date'));
            $('#param_table').text($row.data('table'));
            $('#param_devise').text($row.data('devise-label'));
            $('#param_taux').text($row.data('taux'));
            $('#param_mode').text($row.data('mode-paiement'));
            $('#param_client_id').text($row.data('client-id') || '-');
            $('#param_facture_id').text($row.data('facture-id'));

            var deviseLabel = $row.data('devise-label');
            var montantUSD = parseFloat($row.data('montant-usd')) || 0;
            var montantCDF = parseFloat($row.data('montant-cdf')) || 0;
            var payeUSD = parseFloat($row.data('paye-usd')) || 0;
            var payeCDF = parseFloat($row.data('paye-cdf')) || 0;
            var creditUSD = parseFloat($row.data('credit-usd')) || 0;
            var creditCDF = parseFloat($row.data('credit-cdf')) || 0;

            var statutKey, statutLabel, statutIcon, statutSub;
            if (creditUSD <= 0) {
                statutKey = 'paid'; statutLabel = 'PAYÉE'; statutIcon = 'zmdi-check-circle'; statutSub = 'Facture entièrement réglée — ' + formatMoney(montantUSD) + ' USD / ' + formatMoney(montantCDF) + ' CDF';
            } else if (payeUSD > 0) {
                statutKey = 'partiel'; statutLabel = 'PARTIELLE'; statutIcon = 'zmdi-time'; statutSub = 'Payé : ' + formatMoney(payeUSD) + ' USD / ' + formatMoney(payeCDF) + ' CDF  •  Reste : ' + formatMoney(creditUSD) + ' USD / ' + formatMoney(creditCDF) + ' CDF';
            } else {
                statutKey = 'unpaid'; statutLabel = 'IMPAYÉE'; statutIcon = 'zmdi-close-circle'; statutSub = 'Aucun paiement enregistré — Reste dû : ' + formatMoney(creditUSD) + ' USD / ' + formatMoney(creditCDF) + ' CDF';
            }

            $('#param_statut_card').removeClass('paid unpaid partiel').addClass(statutKey);
            $('#param_statut_icon').attr('class', 'zmdi ' + statutIcon);
            $('#param_statut_label').text(statutLabel);
            $('#param_statut_sub').text(statutSub);

            $('#param_montant_usd').text(formatMoney(montantUSD) + ' USD'); $('#param_montant_cdf').text(formatMoney(montantCDF) + ' CDF');
            $('#param_paye_usd').text(formatMoney(payeUSD) + ' USD'); $('#param_paye_cdf').text(formatMoney(payeCDF) + ' CDF');
            $('#param_credit_usd').text(formatMoney(creditUSD) + ' USD'); $('#param_credit_cdf').text(formatMoney(creditCDF) + ' CDF');
            $('#param_benefice_usd').text(formatMoney($row.data('benefice-usd')) + ' USD'); $('#param_benefice_cdf').text(formatMoney($row.data('benefice-cdf')) + ' CDF');

            var articles = safeParseJSON($row.attr('data-articles'));
            var tauxFacture = parseFloat($row.data('taux')) || 1; if (tauxFacture <= 0) tauxFacture = 1;
            var deviseFacture = $row.data('devise-label');
            var isFactureUSD = (deviseFacture === 'USD');

            function formatBoth(val, deviseAchat) {
                var v = parseFloat(val) || 0; if (deviseAchat === undefined) deviseAchat = isFactureUSD ? 0 : 1;
                var usd = deviseAchat == 0 ? v : (v / tauxFacture), cdf = deviseAchat == 0 ? (v * tauxFacture) : v;
                return '<div class="dual-currency"><b>' + formatMoney(usd) + ' USD</b><small>' + formatMoney(cdf) + ' CDF</small></div>';
            }
            function formatBothShort(val, deviseAchat) {
                var v = parseFloat(val) || 0; if (deviseAchat === undefined) deviseAchat = isFactureUSD ? 0 : 1;
                if (deviseAchat == 0) return formatMoney(v) + ' USD (' + formatMoney(v * tauxFacture) + ' CDF)';
                return formatMoney(v) + ' CDF (' + formatMoney((v / tauxFacture)) + ' USD)';
            }
            function splitBoth(val, deviseAchat) {
                var v = parseFloat(val) || 0; if (deviseAchat === undefined) deviseAchat = isFactureUSD ? 0 : 1;
                if (deviseAchat == 0) return { usd: v, cdf: v * tauxFacture };
                return { usd: (v / tauxFacture), cdf: v };
            }

            var rowsHtml = '', totalQte = 0, totalPrixVente = 0, totalPrixAchat = 0, totalBrutAchat = 0, totalBrutVente = 0, totalGeneral = 0, totalBenef = 0, totalFrais = 0, totalReduction = 0;

            if (articles.length > 0) {
                articles.forEach(function(a, idx) {
                    var deviseAchat = (a.devise_achat !== undefined && a.devise_achat !== null) ? parseInt(a.devise_achat) : (isFactureUSD ? 0 : 1);
                    var totalBrutAchatLigne = (parseFloat(a.prix_achat) || 0) * (parseFloat(a.quantite) || 0);
                    var totalBrutVenteLigne = parseFloat(a.total) || 0;
                    var reductionAchat = parseFloat(a.reduction) || 0;
                    var totalNetAchat = totalBrutVenteLigne - reductionAchat;
                    var benef = totalNetAchat - totalBrutAchatLigne;

                    var sPA = splitBoth(a.prix_achat, deviseAchat), sPV = splitBoth(a.prix_unitaire, deviseAchat), sGen = splitBoth(a.total, deviseAchat);
                    var sFra = splitBoth(a.frais_credit, deviseAchat), sRed = splitBoth(a.reduction, deviseAchat), sBen = splitBoth(benef, deviseAchat);
                    var sBrutPA = splitBoth(totalBrutAchatLigne, deviseAchat), sBrutPV = splitBoth(totalBrutVenteLigne, deviseAchat);

                    totalQte += parseFloat(a.quantite) || 0; totalPrixAchat += sPA.usd; totalPrixVente += sPV.usd;
                    totalBrutAchat += sBrutPA.usd; totalBrutVente += sBrutPV.usd; totalGeneral += sGen.usd;
                    totalFrais += sFra.usd; totalReduction += sRed.usd; totalBenef += sBen.usd;

                    rowsHtml += `<tr data-achat-id="${a.id}" data-devise-achat="${deviseAchat}">
                        <td>${idx + 1}</td>
                        <td><b>${a.nom}</b> <small class="text-muted">(${deviseAchat == 0 ? 'USD' : 'CDF'})</small></td>
                        <td>${a.quantite}</td>
                        <td style="color:#6366f1; font-weight:600;">${formatBoth(a.prix_achat, deviseAchat)}</td>
                        <td>${formatBoth(a.prix_unitaire, deviseAchat)}</td>
                        <td style="color:#6366f1; font-weight:600;">${formatBoth(totalBrutAchatLigne, deviseAchat)}</td>
                        <td style="color:#3B82F6; font-weight:600;">${formatBoth(totalBrutVenteLigne, deviseAchat)}</td>
                        <td>${formatBoth(a.total, deviseAchat)}</td>
                        <td><input type="number" step="0.01" min="0" class="form-control param-inline-input frais-input" value="${(parseFloat(a.frais_credit) || 0).toFixed(2)}"></td>
                        <td><input type="number" step="0.01" min="0" class="form-control param-inline-input reduction-input" value="${reductionAchat.toFixed(2)}"></td>
                        <td class="${benef >= 0 ? 'text-success' : 'text-danger'}">${formatBoth(benef, deviseAchat)}</td>
                    </tr>`;
                });

                function buildCard(cssClass, icon, label, value, sub) {
                    return `<div class="param-total-item ${cssClass}"><div class="param-total-label"><span class="param-total-dot"></span><i class="zmdi ${icon}"></i> ${label}</div><div class="param-total-value">${value}</div>${sub ? `<div class="param-total-sub">${sub}</div>` : ''}</div>`;
                }
                function usdPair(usdValue) { return { usd: usdValue, cdf: usdValue * tauxFacture }; }

                var tPA = usdPair(totalPrixAchat), tPV = usdPair(totalPrixVente), tBPA = usdPair(totalBrutAchat), tBPV = usdPair(totalBrutVente);
                var tGen = usdPair(totalGeneral), tFrais = usdPair(totalFrais), tRed = usdPair(totalReduction), tBenef = usdPair(totalBenef);

                var cardsHtml = '';
                cardsHtml += buildCard('total-qte', 'zmdi-format-list-numbered', 'Total Qté', totalQte, null);
                cardsHtml += buildCard('total-pa', 'zmdi-shopping-cart', 'Total Prix achat', formatMoney(tPA.usd) + ' USD', formatMoney(tPA.cdf) + ' CDF');
                cardsHtml += buildCard('total-pv', 'zmdi-money', 'Total Prix vente', formatMoney(tPV.usd) + ' USD', formatMoney(tPV.cdf) + ' CDF');
                cardsHtml += buildCard('total-brut-pa', 'zmdi-shopping-basket', 'Montant brut (prix achat)', formatMoney(tBPA.usd) + ' USD', formatMoney(tBPA.cdf) + ' CDF');
                cardsHtml += buildCard('total-brut-pv', 'zmdi-shopping-basket', 'Montant brut (prix vente)', formatMoney(tBPV.usd) + ' USD', formatMoney(tBPV.cdf) + ' CDF');
                cardsHtml += buildCard('total-gen', 'zmdi-balance-wallet', 'Total Général', formatMoney(tGen.usd) + ' USD', formatMoney(tGen.cdf) + ' CDF');
                cardsHtml += buildCard('total-frais', 'zmdi-alert-circle', 'Total Frais crédit', formatMoney(tFrais.usd) + ' USD', formatMoney(tFrais.cdf) + ' CDF');
                cardsHtml += buildCard('total-red', 'zmdi-minus-circle', 'Total Réduction', formatMoney(tRed.usd) + ' USD', formatMoney(tRed.cdf) + ' CDF');
                cardsHtml += buildCard('total-benef', 'zmdi-trending-up', 'Total Bénéfice', formatMoney(tBenef.usd) + ' USD', formatMoney(tBenef.cdf) + ' CDF');
                $('#param_totals_grid').html(cardsHtml);
            } else {
                rowsHtml = '<tr><td colspan="11" class="text-center text-muted">Aucun article</td></tr>';
                $('#param_totals_grid').html('');
            }
            $('#param_articles_body').html(rowsHtml);
            $('#param_nb_articles').text(articles.length);
            $('#param_prix_achat_show').text(formatBothShort(totalPrixAchat, 0));
            $('#param_prix_vente_show').text(formatBothShort(totalPrixVente, 0));
            $('#param_brut_achat_show').text(formatBothShort(totalBrutAchat, 0));
            $('#param_brut_vente_show').text(formatBothShort(totalBrutVente, 0));
            $('#param_frais_credit_show').text(formatBothShort(totalFrais, 0));
            $('#param_reduction_show').text(formatBothShort(totalReduction, 0));
            $('#param_lines_msg').html('');

            var paiements = safeParseJSON($row.attr('data-paiements'));
            var paiHtml = '', totalRecuUSD = 0, totalRecuCDF = 0, totalResteUSD = 0, totalResteCDF = 0;

            if (paiements.length > 0) {
                paiements.forEach(function(p, idx) {
                    var isUSD = (p.devise_label === 'USD');
                    var montantRecu = parseFloat(p.montant_recu) || 0;
                    var reste = parseFloat(p.reste) || 0;
                    var recuUSD = isUSD ? montantRecu : (montantRecu / tauxFacture), recuCDF = isUSD ? (montantRecu * tauxFacture) : montantRecu;
                    var resteUSD = isUSD ? reste : (reste / tauxFacture), resteCDF = isUSD ? (reste * tauxFacture) : reste;

                    totalRecuUSD += recuUSD; totalRecuCDF += recuCDF; totalResteUSD += resteUSD; totalResteCDF += resteCDF;

                    paiHtml += `<tr>
                        <td>${idx + 1}</td>
                        <td style="font-size:0.78rem;">${p.date || '-'}</td>
                        <td><span class="badge badge-info">${p.mode_label || 'N/A'}</span></td>
                        <td><span class="badge badge-${isUSD ? 'primary' : 'warning'}">${p.devise_label || '-'}</span></td>
                        <td><div class="dual-currency"><b class="text-primary">${formatMoney(recuUSD)} USD</b><small class="text-primary">${formatMoney(recuCDF)} CDF</small></div></td>
                        <td><div class="dual-currency"><b class="text-danger">${formatMoney(resteUSD)} USD</b><small class="text-danger">${formatMoney(resteCDF)} CDF</small></div></td>
                    </tr>`;
                });
                $('#param_paiements_foot').html(`<tr><td colspan="4" class="text-right">TOTAUX (${paiements.length} tranche${paiements.length > 1 ? 's' : ''}) :</td><td class="text-primary">${formatMoney(totalRecuUSD)} USD (${formatMoney(totalRecuCDF)} CDF)</td><td class="text-danger">${formatMoney(totalResteUSD)} USD (${formatMoney(totalResteCDF)} CDF)</td></tr>`);
            } else {
                paiHtml = '<tr><td colspan="6" class="text-center text-muted">Aucun paiement enregistré</td></tr>';
                $('#param_paiements_foot').html('');
            }
            $('#param_paiements_body').html(paiHtml);
        }

        function parseFormattedNumber(str) { return (str === null || str === undefined) ? 0 : parseFloat(String(str).replace(/\s/g, '').replace(',', '.')) || 0; }
        function formatNumber(n) { return (parseFloat(n) || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }

        function calculerMontantReduit() {
            var total = parseFormattedNumber($("#montant_total").val()), reduction = parseFormattedNumber($("#reduction").val());
            var reduit = total - reduction; if (reduit < 0) reduit = 0;
            $("#montant_reduit").val(formatNumber(reduit));
        }

        function calculerMontantTotal() {
            var article_id = $("#type_sortie").val(), type_vente_id = $("#type_vente_id").val(), quantite = parseInt($("#quantite").val()) || 0;
            var $option = $("#type_sortie option:selected"), deviseArticle = $option.data('devise');
            var deviseLabel = (deviseArticle === 0 || deviseArticle === '0') ? 'USD' : (deviseArticle === 1 || deviseArticle === '1') ? 'CDF' : 'USD';
            
            $("#montant_total_devise, #reduction_devise, #montant_reduit_devise").text("(" + deviseLabel + ")");

            if (!article_id || !type_vente_id || quantite <= 0) { $("#montant_total, #montant_reduit").val(""); return; }

            $.get("{{ url('/get_prix_article') }}", { article_id: article_id, type_vente_id: type_vente_id }, function(get_prix_article) {
                var prix_unitaire = parseFloat(get_prix_article[0][0]) || 0;
                $("#montant_total").val(formatNumber(prix_unitaire * quantite));
                calculerMontantReduit();
            }).fail(function() { $("#montant_total, #montant_reduit").val(""); });
        }

        $(document).on('change', '#type_sortie', calculerMontantTotal);
        $(document).on('change', '#type_vente_id', calculerMontantTotal);
        $(document).on('input keyup', '#quantite', calculerMontantTotal);
        $(document).on('input keyup', '#reduction', calculerMontantReduit);

        $(document).ready(function() {
            $('#client_id').select2({ placeholder: "Rechercher un client...", allowClear: true, theme: 'bootstrap', width: '100%' });

            var today = moment();
            $('#filterDateRange').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));
            $('#filterDateRange').daterangepicker({
                autoUpdateInput: false, startDate: today, endDate: today,
                locale: { format: 'DD/MM/YYYY', separator: ' - ', applyLabel: 'Appliquer', cancelLabel: 'Annuler', fromLabel: 'Du', toLabel: 'Au', customRangeLabel: 'Personnalisé', weekLabel: 'S', daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'], monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] },
                opens: 'left',
                ranges: { 'Aujourd\'hui': [moment(), moment()], 'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')], '7 derniers jours': [moment().subtract(6, 'days'), moment()], '30 derniers jours': [moment().subtract(29, 'days'), moment()], 'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')], 'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')], 'Cette année': [moment().startOf('year'), moment().endOf('year')] }
            }, function(start, end) {
                $('#filterDateRange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                filterInvoices(); saveFiltersToStorage();
            });

            $('#filterDateRange').on('cancel.daterangepicker', function() { $(this).val(''); filterInvoices(); saveFiltersToStorage(); });

            loadFiltersFromStorage();
            chargerFactures();

            $('#filterNumero, #filterClient, #filterUser, #filterStatut, #filterMontant, #filterTable').on('input change', debouncedFilter);
            $('#resetFilters').click(function(e) { e.preventDefault(); resetAllFilters(); });

            $(document).on('click', '.delete-facture-btn', function(e) {
                e.preventDefault();
                $('#delete_facture_numero').text($(this).data('numero'));
                $('#delete_facture_client').text($(this).data('client'));
                $('#delete_facture_montant').text($(this).data('montant'));
                $('#delete_facture_date').text($(this).data('date'));
                $('#delete_facture_statut').text($(this).data('statut'));
                $('#deleteFactureModal').data('facture-id', $(this).data('id'));
                $('#deleteFactureModal').modal('show');
            });

            $(document).on('click', '#confirm_delete_facture', function(e) {
                e.preventDefault();
                var btn = $(this), factureId = $('#deleteFactureModal').data('facture-id');
                btn.prop('disabled', true).html('Suppression...');
                $.ajax({
                    type: 'POST', url: '{{ url("/delete_facture") }}', data: { _token: '{{ csrf_token() }}', id: factureId },
                    success: function(response) {
                        $('#deleteFactureModal').modal('hide');
                        chargerFactures();
                        $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Facture supprimée').css('display', 'flex');
                        setTimeout(() => { $('#msg').html('').css('display', 'none'); }, 3000);
                    },
                    error: function() { alert('Erreur lors de la suppression.'); },
                    complete: function() { btn.prop('disabled', false).html('Oui, supprimer'); }
                });
            });

            // ============================================================
            // ✅ NOUVEAU : MODAL PARAMÈTRES – LOADER AVANT CHARGEMENT
            // ============================================================
            $(document).on('click', '.param-facture-btn', function(e) {
                e.preventDefault();
                var $row = $(this).closest('tr');
                $('#paramTabs a[href="#tab-info"]').tab('show');

                // ✅ Loader visible AVANT le chargement des données
                $('#param_articles_body').html('<tr><td colspan="11" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>');
                $('#param_paiements_body').html('<tr><td colspan="6" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>');
                $('#param_paiements_foot').html('');
                $('#param_totals_grid').html('');

                $('#paramFactureModal').modal('show');

                // ✅ Laisse le navigateur peindre le loader avant de remplir
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        openParamModalFromRow($row);
                    });
                });
            });

            // ============================================================
            // ✅ NOUVEAU : BOUTON REFRESH PARAMÈTRES – LOADER AVANT CHARGEMENT
            // ============================================================
            $(document).on('click', '#param_refresh_btn', function(e) {
                e.preventDefault();
                if (!currentParamRow) return;

                var btn = $(this);
                btn.find('i').addClass('zmdi-hc-spin');

                // ✅ Loader avant rafraîchissement
                $('#param_articles_body').html('<tr><td colspan="11" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>');
                $('#param_paiements_body').html('<tr><td colspan="6" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>');
                $('#param_paiements_foot').html('');
                $('#param_totals_grid').html('');

                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        openParamModalFromRow(currentParamRow);
                        btn.find('i').removeClass('zmdi-hc-spin');
                    });
                });
            });

            $(document).on('click', '#param_btn_save_lines', function(e) {
                e.preventDefault();
                if (USER_ROLE != 0) { $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> Seul l\'admin peut modifier.'); setTimeout(function() { $('#param_lines_msg').html(''); }, 4000); return; }
                if (!currentParamFactureId) { alert('Identifiant facture manquant'); return; }

                var lignes = [], erreur = null;
                $('#param_articles_body tr').each(function() {
                    var achatId = $(this).data('achat-id'); if (!achatId) return;
                    var frais = parseFloat($(this).find('.frais-input').val()) || 0;
                    var reduction = parseFloat($(this).find('.reduction-input').val()) || 0;
                    if (frais < 0 || reduction < 0) { erreur = 'Valeurs positives requises'; return false; }
                    lignes.push({ id: achatId, frais_credit: frais, reduction: reduction });
                });

                if (erreur) { $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> ' + erreur); setTimeout(function() { $('#param_lines_msg').html(''); }, 4000); return; }
                if (lignes.length === 0) { $('#param_lines_msg').html('<i class="zmdi zmdi-info text-warning"></i> Aucune ligne'); setTimeout(function() { $('#param_lines_msg').html(''); }, 3000); return; }

                var btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enregistrement...');
                $('#param_lines_msg').html('<i class="zmdi zmdi-time text-info"></i> Enregistrement...');

                $.ajax({
                    url: "{{ url('/apply_param_facture') }}", type: 'POST',
                    data: { _token: "{{ csrf_token() }}", facture_id: currentParamFactureId, lignes: JSON.stringify(lignes) },
                    dataType: 'json',
                    success: function(res) {
                        btn.prop('disabled', false).html('<i class="zmdi zmdi-save"></i> Enregistrer les modifications');
                        if (res.success) {
                            $('#param_lines_msg').html('<i class="zmdi zmdi-check-circle text-success"></i> ' + res.message);
                            chargerFactures();
                            setTimeout(function() {
                                var newRow = $('#factures-tbody tr[data-facture-id="' + currentParamFactureId + '"]');
                                if (newRow.length) { openParamModalFromRow(newRow); }
                            }, 800);
                        } else { $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> ' + (res.message || 'Erreur')); }
                    },
                    error: function() { btn.prop('disabled', false).html('<i class="zmdi zmdi-save"></i> Enregistrer les modifications'); $('#param_lines_msg').html('<i class="zmdi zmdi-close-circle text-danger"></i> Erreur de connexion'); }
                });
            });

            // ==========================================
            // ✅ GESTION DU RAPPORT (CÔTÉ SERVEUR)
            // ==========================================
            
            function populateRapportUsers() {
                var $sel = $('#rapport_user_filter');
                if ($sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2('destroy');
                }
                $sel.empty();

                if (USER_ROLE == 0) {
                    $sel.append('<option value="all">Tous les utilisateurs</option>');
                    ALL_USERS.forEach(function (u) {
                        $sel.append('<option value="' + u.id + '">' + u.name + '</option>');
                    });
                    $sel.prop('disabled', false).select2({
                        placeholder: "Rechercher un utilisateur...",
                        allowClear: false,
                        theme: 'bootstrap',
                        width: '100%',
                        dropdownParent: $('#rapportModal')
                    });
                } else {
                    var nom = CURRENT_USER_NAME || 'Utilisateur';
                    $sel.append('<option value="' + CURRENT_USER_ID + '" selected>' + nom + '</option>');
                    $sel.val(String(CURRENT_USER_ID));
                    $sel.prop('disabled', true).select2({
                        theme: 'bootstrap',
                        width: '100%',
                        dropdownParent: $('#rapportModal'),
                        disabled: true
                    });
                }
            }

            function fetchRapport(dateDebutISO, dateFinISO, userFilter, clientFilter) {
                var $body = $('#rapport_body');
                var $foot = $('#rapport_foot');
                
                $body.html('<tr><td colspan="10" class="text-center text-muted"><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement des données...</td></tr>');
                $foot.html('');

                var params = {
                    date_debut: dateDebutISO,
                    date_fin: dateFinISO,
                    user_id: userFilter,
                    client: clientFilter
                };

                $.ajax({
                    url: "{{ url('/get_rapport_paiements') }}", 
                    type: 'GET',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;
                        var totalUSD = 0, totalCDF = 0, nbTransactions = data.length;
                        var html = '';

                        if (data.length === 0) {
                            html = '<tr><td colspan="10" class="text-center text-muted">Aucun paiement trouvé pour ces critères</td></tr>';
                        } else {
                            data.forEach(function (p, idx) {
                                var isUSD = (p.devise === 'USD');
                                var montantUSD = isUSD ? p.montant_recu : (p.taux > 0 ? (p.montant_recu / p.taux) : 0);
                                var montantCDF = isUSD ? (p.montant_recu * p.taux) : p.montant_recu;
                                
                                totalUSD += montantUSD;
                                totalCDF += montantCDF;

                                var badgeDevise = isUSD ? 'primary' : 'warning';
                                
                                html += '<tr>';
                                html += '<td>' + (idx + 1) + '</td>';
                                html += '<td style="font-size:0.78rem;">' + (p.date_paiement || '-') + '</td>';
                                html += '<td><b>' + (p.numero || '-') + '</b></td>';
                                html += '<td style="font-size:0.78rem;">' + (p.date_facture || '-') + '</td>';
                                html += '<td>' + (p.client || '-') + '</td>';
                                html += '<td><span class="badge badge-secondary"><i class="zmdi zmdi-edit"></i> ' + (p.enregistre_par || 'N/A') + '</span></td>';
                                html += '<td><span class="badge badge-success" style="background: linear-gradient(135deg, #10b981, #059669);"><i class="zmdi zmdi-money"></i> ' + (p.encaisse_par || 'N/A') + '</span></td>';
                                html += '<td><span class="badge badge-info">' + (p.mode_label || 'N/A') + '</span></td>';
                                html += '<td><span class="badge badge-' + badgeDevise + '">' + (p.devise || '-') + '</span></td>';
                                html += '<td style="text-align:right;">';
                                html += '<div class="dual-currency">';
                                html += '<b class="text-primary">' + formatRapportMoney(montantUSD) + ' USD</b>';
                                html += '<small class="text-warning">' + formatRapportMoney(montantCDF) + ' CDF</small>';
                                html += '</div>';
                                html += '</td>';
                                html += '</tr>';
                            });
                        }

                        $body.html(html);

                        var footHtml = '<tr>';
                        footHtml += '<td colspan="9" class="text-right">TOTAL REÇU (' + nbTransactions + ' transaction' + (nbTransactions > 1 ? 's' : '') + ') :</td>';
                        footHtml += '<td style="text-align:right;">';
                        footHtml += '<span class="text-primary">' + formatRapportMoney(totalUSD) + ' USD</span><br>';
                        footHtml += '<span class="text-warning">' + formatRapportMoney(totalCDF) + ' CDF</span>';
                        footHtml += '</td>';
                        footHtml += '</tr>';
                        $foot.html(footHtml);

                        $('#rapport_total_usd').text(formatRapportMoney(totalUSD));
                        $('#rapport_total_cdf').text(formatRapportMoney(totalCDF));
                        $('#rapport_nb').text(nbTransactions);

                        var labelUser = 'Tous';
                        if (userFilter && userFilter !== 'all') {
                            var foundUser = ALL_USERS.find(function (u) { return u.id == userFilter; });
                            labelUser = foundUser ? foundUser.name : ('ID ' + userFilter);
                        }
                        $('#rapport_user_label').text(USER_ROLE == 0 ? labelUser : CURRENT_USER_NAME + ' (vous)');
                        $('#rapport_client_label').text(clientFilter || 'Tous');
                    },
                    error: function(xhr) {
                        console.error("Erreur API Rapport:", xhr);
                        $body.html('<tr><td colspan="10" class="text-center text-danger">Erreur lors de la récupération des données.</td></tr>');
                    }
                });
            }

            function formatRapportMoney(v) {
                var n = parseFloat(v) || 0;
                return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }

            function parseDMY_to_ISO(str) {
                if (!str) return null;
                var p = String(str).trim().split('/');
                if (p.length === 3 && p[0].length === 2 && p[1].length === 2 && p[2].length === 4) {
                    return p[2] + '-' + p[1] + '-' + p[0];
                }
                return null;
            }

            $(document).on('focus', '#rapport_date_range', function () {
                if (!$(this).data('daterangepicker')) {
                    $(this).daterangepicker({
                        autoUpdateInput: false,
                        locale: { format: 'DD/MM/YYYY', separator: ' - ', applyLabel: 'Appliquer', cancelLabel: 'Annuler' },
                        opens: 'left'
                    }, function (start, end) {
                        $('#rapport_date_range').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                    });
                }
            });

            $("#rapport").click(function (e) {
                e.preventDefault();
                var filterRange = $('#filterDateRange').val() || '';
                $('#rapport_date_range').val(filterRange);

                var dateDebutISO = null, dateFinISO = null;
                if (filterRange) {
                    var parts = filterRange.split(' - ');
                    if (parts.length === 2) {
                        dateDebutISO = parseDMY_to_ISO(parts[0]);
                        dateFinISO = parseDMY_to_ISO(parts[1]);
                    }
                }

                $('#rapportModal').modal('show').off('shown.bs.modal.rapportUser').on('shown.bs.modal.rapportUser', function () {
                    populateRapportUsers();
                    var userFilter = $('#rapport_user_filter').val() || (USER_ROLE == 0 ? 'all' : String(CURRENT_USER_ID));
                    var clientFilter = $('#rapport_client_filter').val() || '';
                    $('#rapport_periode_label').text(filterRange || 'Toutes les dates');
                    
                    fetchRapport(dateDebutISO, dateFinISO, userFilter, clientFilter);
                });
            });

            $(document).on('click', '#rapport_apply_btn', function (e) {
                e.preventDefault();
                var range = $('#rapport_date_range').val() || '';
                var dateDebutISO = null, dateFinISO = null;
                if (range) {
                    var parts = range.split(' - ');
                    if (parts.length === 2) {
                        dateDebutISO = parseDMY_to_ISO(parts[0]);
                        dateFinISO = parseDMY_to_ISO(parts[1]);
                    }
                }
                var userFilter = $('#rapport_user_filter').val() || (USER_ROLE == 0 ? 'all' : String(CURRENT_USER_ID));
                var clientFilter = $('#rapport_client_filter').val() || '';
                $('#rapport_periode_label').text(range || 'Toutes les dates');
                
                fetchRapport(dateDebutISO, dateFinISO, userFilter, clientFilter);
            });

            $(document).on('change', '#rapport_user_filter', function () {
                var range = $('#rapport_date_range').val() || '';
                var dateDebutISO = null, dateFinISO = null;
                if (range) {
                    var parts = range.split(' - ');
                    if (parts.length === 2) {
                        dateDebutISO = parseDMY_to_ISO(parts[0]);
                        dateFinISO = parseDMY_to_ISO(parts[1]);
                    }
                }
                var userFilter = $(this).val() || 'all';
                var clientFilter = $('#rapport_client_filter').val() || '';
                fetchRapport(dateDebutISO, dateFinISO, userFilter, clientFilter);
            });

            var rapportClientTimeout;
            $(document).on('input', '#rapport_client_filter', function () {
                clearTimeout(rapportClientTimeout);
                rapportClientTimeout = setTimeout(function () {
                    var range = $('#rapport_date_range').val() || '';
                    var dateDebutISO = null, dateFinISO = null;
                    if (range) {
                        var parts = range.split(' - ');
                        if (parts.length === 2) {
                            dateDebutISO = parseDMY_to_ISO(parts[0]);
                            dateFinISO = parseDMY_to_ISO(parts[1]);
                        }
                    }
                    var userFilter = $('#rapport_user_filter').val() || (USER_ROLE == 0 ? 'all' : String(CURRENT_USER_ID));
                    var clientFilter = $(this).val() || '';
                    fetchRapport(dateDebutISO, dateFinISO, userFilter, clientFilter);
                }, 500);
            });

            $(document).on('click', '#rapport_reset_btn', function (e) {
                e.preventDefault();
                $('#rapport_date_range').val('');
                $('#rapport_client_filter').val('');
                if ($('#rapport_date_range').data('daterangepicker')) {
                    $('#rapport_date_range').data('daterangepicker').setStartDate(moment().subtract(1, 'year'));
                    $('#rapport_date_range').data('daterangepicker').setEndDate(moment());
                }
                if (USER_ROLE == 0) {
                    $('#rapport_user_filter').val('all').trigger('change.select2');
                } else {
                    $('#rapport_user_filter').val(String(CURRENT_USER_ID)).trigger('change.select2');
                }
                $('#rapport_periode_label').text('Toutes les dates');
                
                fetchRapport(null, null, USER_ROLE == 0 ? 'all' : CURRENT_USER_ID, '');
            });
        });

        window.addEventListener('beforeunload', function() { saveFiltersToStorage(); });

        var currentPdfUrl = "", cdf_montant_payer = $("#cdf_montant_payer").val(), usd_montant_payer = $("#usd_montant_payer").val(), payer = $("#payer").val();

        function convertirEnNombre(valeur) { if (!valeur && valeur !== 0) return NaN; let str = String(valeur).trim().replace(/\s/g, '').replace(',', '.'); return parseFloat(str); }

        document.getElementById('btn_payer').addEventListener('click', async function() {
            const btnPayer = this;
            btnPayer.disabled = true;
            const msgFacture = document.getElementById('msg_facture');
            const originalBtnText = btnPayer.innerHTML;
            btnPayer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Chargement...';
            msgFacture.innerHTML = '⏳ Traitement en cours...'; msgFacture.style.color = '#17a2b8';

            try {
                const factureId = $("#id_fac").val();
                if (!factureId || factureId === "") { msgFacture.innerHTML = '❌ Identifiant manquant'; msgFacture.style.color = '#dc3545'; setTimeout(() => { msgFacture.innerHTML = ''; }, 5000); return; }

                const checkPaie = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
                if (checkPaie == 1) { msgFacture.innerHTML = '⚠️ Facture déjà payée'; msgFacture.style.color = '#ffc107'; setTimeout(() => { msgFacture.innerHTML = ''; }, 5000); return; }

                const detailData = await $.get("{{ url('/get_all_detail_achat_paie') }}", { facture_id: factureId });
                const { usd_montant_total_a_payer, cdf_montant_total_a_payer } = detailData;

                $("#usd_montant_payer").val(usd_montant_total_a_payer); $("#cdf_montant_payer").val(cdf_montant_total_a_payer);

                const montantRecu = convertirEnNombre(document.getElementById('montant_recu').value);
                const deviseRecu = document.getElementById('devise_recu').value;

                if (isNaN(montantRecu) || montantRecu <= 0) { msgFacture.innerHTML = '❌ Montant invalide'; msgFacture.style.color = '#dc3545'; document.getElementById('montant_recu').focus(); setTimeout(() => { msgFacture.innerHTML = ''; }, 5000); return; }
                if (!deviseRecu) { msgFacture.innerHTML = '❌ Sélectionnez une devise'; msgFacture.style.color = '#dc3545'; document.getElementById('devise_recu').focus(); setTimeout(() => { msgFacture.innerHTML = ''; }, 5000); return; }

                const deviseLabel = deviseRecu === "0" ? "USD" : "CDF";
                let montantRestant = deviseRecu === "0" ? usd_montant_total_a_payer : cdf_montant_total_a_payer;

                if (isNaN(montantRestant) || montantRestant <= 0) { msgFacture.innerHTML = '❌ Aucun montant restant dans cette devise'; msgFacture.style.color = '#dc3545'; setTimeout(() => { msgFacture.innerHTML = ''; }, 15000); return; }

                let montantPaye = (montantRecu >= montantRestant) ? montantRestant : montantRecu;
                let monnaie = (montantRecu >= montantRestant) ? (montantRecu - montantRestant) : 0;

                const saveResponse = await $.post("{{ url('/save_paie_facture') }}", { _token: "{{ csrf_token() }}", facture_id: factureId, montant_recu: montantRecu, devise_recu: deviseRecu, montant_paye: montantPaye, monnaie: monnaie });

                if (saveResponse.success || saveResponse == 1) {
                    msgFacture.innerHTML = `✅ PAIEMENT ${montantPaye === montantRestant ? 'TOTAL' : 'PARTIEL'} RÉUSSI !<br>📄 Reste avant : ${montantRestant.toFixed(2)} ${deviseLabel}<br>💵 Reçu : ${montantRecu.toFixed(2)} ${deviseLabel}<br>💰 Imputé : ${montantPaye.toFixed(2)} ${deviseLabel}<br>${monnaie > 0 ? `🔄 Monnaie : ${monnaie.toFixed(2)} ${deviseLabel}` : ''}${montantPaye < montantRestant ? `<br>📌 Nouveau reste : ${(montantRestant - montantPaye).toFixed(2)} ${deviseLabel}` : '<br>✅ Facture soldée'}`;
                    msgFacture.style.color = '#28a745'; document.getElementById('montant_recu').value = '';

                    const pdfUrl = "{{ isset($data->lien) ? $data->lien : '' }}";
                    if (pdfUrl && pdfUrl !== '') { currentPdfUrl = pdfUrl; $("#pdfIframe").attr("src", pdfUrl); } else {
                        const pdfResponse = await $.get("{{ url('/print_facture') }}", { facture_id: factureId });
                        if (pdfResponse && pdfResponse[0] && pdfResponse[0][0]) { currentPdfUrl = pdfResponse[0][0]; $("#pdfIframe").attr("src", pdfResponse[0][0]); $("#cdf_montant_payer").val(pdfResponse[0][1] || 0); $("#usd_montant_payer").val(pdfResponse[0][2] || 0); $("#payer").val(pdfResponse[0][5] || ''); }
                    }
                    
                    chargerFactures();
                } else { msgFacture.innerHTML = '❌ Erreur d\'enregistrement'; msgFacture.style.color = '#dc3545'; }
                setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
            } catch (error) {
                console.error("Erreur API:", error);
                msgFacture.innerHTML = '❌ Erreur de connexion à l\'API'; msgFacture.style.color = '#dc3545';
                setTimeout(() => { msgFacture.innerHTML = ''; }, 9000);
            } finally {
                btnPayer.disabled = false; btnPayer.innerHTML = originalBtnText;
                if (msgFacture.innerHTML === '⏳ Traitement en cours...') { msgFacture.innerHTML = ''; }
            }
        });

        $("#pdfModal").on("hidden.bs.modal", function() { $("#pdfIframe").attr("src", ""); });

        $("#payer").click(function(e) {
            e.preventDefault();
            var $btn = $(this); $btn.prop('disabled', true);
            $.get("{{ url('/print_qr_code') }}", {}, function(response) {
                var url = '';
                if (response && response[0] && response[0][0]) { url = response[0][0]; }
                else if (response && typeof response.url === 'string') { url = response.url; }
                else if (typeof response === 'string' && response.trim() !== '') { url = response.trim(); }
                if (url && url !== '') { $("#qrIframe").attr("src", url); $("#qrModal").modal("show"); }
                else { alert("Aucun QR code disponible pour le moment."); }
            }).fail(function() { alert("Erreur lors de la récupération du QR code."); }).always(function() { $btn.prop('disabled', false); });
        });

        $("#qrModal").on("hidden.bs.modal", function() { $("#qrIframe").attr("src", ""); });
    </script>
@endsection