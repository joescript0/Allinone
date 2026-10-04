@php
    use App\Models\appnames;
    use App\Models\Factureass;
    use App\Models\detailpaiessachats;
    use App\Models\Achats;
    use App\Models\Clients;
    use Illuminate\Support\Facades\DB;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';

    // ============================================================
    // ===== PRÉ-CHARGEMENT DES CLIENTS (évite N+1) ===============
    // ============================================================
    $clients_map = clients::pluck('name', 'id')->toArray();

    // ============================================================
    // ===== HELPER : DÉTERMINER SI UNE FACTURE EST IMPAYÉE ======
    // ============================================================
    $estImpayee = function ($f) {
        $taux_f = $f->taux ?? 1;
        if ($taux_f <= 0) $taux_f = 1;
        $devise_f = $f->devise ?? 0;

        $achats_f = Achats::where('facture_id', $f->id)->get();
        if ($achats_f->isEmpty()) return false;

        $total_original_f = 0;
        foreach ($achats_f as $a) {
            $dev_a = $a->devise_achat ?? $devise_f;
            $red   = (isset($a->reduction) && $a->reduction > 0) ? $a->reduction : 0;
            $net   = $a->total - $red;

            if ($dev_a == $devise_f) {
                $total_original_f += $net;
            } elseif ($devise_f == 0) {
                $total_original_f += ($taux_f > 0) ? ($net / $taux_f) : 0;
            } else {
                $total_original_f += $net * $taux_f;
            }
        }

        $paiements_f = detailpaiessachats::where('facture_id', $f->id)->get();
        $usd_paye_f = 0;
        $cdf_paye_f = 0;
        foreach ($paiements_f as $p) {
            if ($p->devise_recu == 0) {
                $usd_paye_f += $p->montant_recu;
                $cdf_paye_f += $p->montant_recu * $taux_f;
            } else {
                $cdf_paye_f += $p->montant_recu;
                $usd_paye_f += $p->montant_recu / $taux_f;
            }
        }

        if ($devise_f == 0) {
            $orig_usd_f = $total_original_f;
            $orig_cdf_f = $total_original_f * $taux_f;
        } else {
            $orig_cdf_f = $total_original_f;
            $orig_usd_f = $total_original_f / $taux_f;
        }

        return ($usd_paye_f < $orig_usd_f) || ($cdf_paye_f < $orig_cdf_f);
    };

    // ============================================================
    // ===== HELPER : NOM DU CLIENT (client_id OU libelle) ========
    // ============================================================
    $getNomClient = function ($f) use ($clients_map) {
        if (isset($f->client_id) && $f->client_id != 0 && isset($clients_map[$f->client_id])) {
            return $clients_map[$f->client_id];
        }
        return $f->libelle ?? '';
    };

    // ============================================================
    // ===== FILTRAGE : FACTURES IMPAYÉES + etat = 0 ==============
    // ===== ORDRE NATUREL D'ENREGISTREMENT (pas de orderBy) ======
    // ============================================================
    $toutes_factures = [];
    foreach (Factureass::where('etat', 0)->get() as $f) {
        if ($estImpayee($f)) {
            $toutes_factures[] = $f;
        }
    }
    $toutes_factures = collect($toutes_factures);

    // ============================================================
    // ===== RÉCUPÉRATION DE L'ID DE LA FACTURE (URL initiale) ====
    // ============================================================
    $factureId = request()->query('facture_id');
    $factureId = is_numeric($factureId) ? (int) $factureId : null;

    $facture = null;
    if ($factureId) {
        foreach ($toutes_factures as $f) {
            if ($f->id == $factureId) { $facture = $f; break; }
        }
        if (!$facture) $factureId = null;
    }

    $montant_usd_1 = 0;
    $montant_cdf_1 = 0;
    $usd_montant   = 0;
    $cdf_montant   = 0;

    $devise_facture = $facture->devise ?? 0;
    $numero_facture = $facture->numero ?? '';
    $nom_client     = $facture ? $getNomClient($facture) : '';
    $devise_defaut  = ($devise_facture == 1) ? 'CDF' : 'USD';

    if ($facture) {
        $taux = $facture->taux ?? 1;
        if ($taux <= 0) $taux = 1;

        $achats = Achats::where('facture_id', $factureId)->get();

        $total_original = 0;
        foreach ($achats as $a) {
            $dev_a = $a->devise_achat ?? $devise_facture;
            $red   = (isset($a->reduction) && $a->reduction > 0) ? $a->reduction : 0;
            $net   = $a->total - $red;
            if ($dev_a == $devise_facture) {
                $total_original += $net;
            } elseif ($devise_facture == 0) {
                $total_original += ($taux > 0) ? ($net / $taux) : 0;
            } else {
                $total_original += $net * $taux;
            }
        }

        $paiements = detailpaiessachats::where('facture_id', $factureId)->get();
        $usd_paye = 0;
        $cdf_paye = 0;
        foreach ($paiements as $p) {
            if ($p->devise_recu == 0) {
                $usd_paye += $p->montant_recu;
                $cdf_paye += $p->montant_recu * $taux;
            } else {
                $cdf_paye += $p->montant_recu;
                $usd_paye += $p->montant_recu / $taux;
            }
        }

        if ($devise_facture == 0) {
            $orig_usd = $total_original;
            $orig_cdf = $total_original * $taux;
        } else {
            $orig_cdf = $total_original;
            $orig_usd = $total_original / $taux;
        }

        $est_impayee   = ($usd_paye < $orig_usd) || ($cdf_paye < $orig_cdf);
        $delai_depasse = (time() - strtotime($facture->created_at)) > 3600;

        foreach ($achats as $achat) {
            if (($achat->frais_credit == 0 || $achat->frais_credit === null)
                && $est_impayee && $delai_depasse) {
                $frais = $achat->total * 0.05;
                $achat->frais_credit = $frais;
                $achat->save();
            }
        }

        $total_avec_frais = 0;
        foreach ($achats as $achat) {
            $dev_a = $achat->devise_achat ?? $devise_facture;
            $red   = (isset($achat->reduction) && $achat->reduction > 0) ? $achat->reduction : 0;
            $fr    = $achat->frais_credit ?? 0;
            $net   = $achat->total - $red + $fr;
            if ($dev_a == $devise_facture) {
                $total_avec_frais += $net;
            } elseif ($devise_facture == 0) {
                $total_avec_frais += ($taux > 0) ? ($net / $taux) : 0;
            } else {
                $total_avec_frais += $net * $taux;
            }
        }

        if ($devise_facture == 0) {
            $usd_1 = $total_avec_frais;
            $cdf_1 = $total_avec_frais * $taux;
        } else {
            $cdf_1 = $total_avec_frais;
            $usd_1 = $total_avec_frais / $taux;
        }

        $usd_montant = max(0, $usd_1 - $usd_paye);
        $cdf_montant = max(0, $cdf_1 - $cdf_paye);

        $montant_defaut = number_format(
            abs($devise_defaut === 'USD' ? $usd_montant : $cdf_montant),
            2, ',', ' '
        );
    } else {
        $montant_defaut = '';
    }
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/fullcalendar/fullcalendar.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233B82F6'%3E%3Cpath d='M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z'/%3E%3C/svg%3E" />
    <title>{{ $nom_app }} - PAIEMENT GENERAL</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1E293B;
        }
        .header {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
        }
        .logo h1 { font-size: 1.4rem; font-weight: 700; }
        .logo a { text-decoration: none; color: #0F172A; }
        .logo a i { color: #3B82F6; margin-right: 6px; }
        .logo p { font-size: 0.7rem; color: #64748B; letter-spacing: 1px; }
        #footer {
            padding: 1rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            font-size: 0.7rem;
            color: #64748B;
        }
        .login { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .login-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            padding: 2.5rem;
        }
        .login-container h5 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .amounts-container { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
        .amount-card {
            flex: 1;
            background: #F8FAFC;
            border-radius: 16px;
            padding: 10px 8px;
            text-align: center;
            border-left: 4px solid #3B82F6;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s ease, opacity 0.3s ease;
        }
        .amount-card.active { border-left-color: #10B981; background: #ECFDF5; }
        .amount-card:hover { transform: scale(1.02); }
        .amount-icon { font-size: 1.4rem; color: #3B82F6; }
        .amount-card h4 { font-size: 0.9rem; font-weight: 600; margin: 0; word-break: break-word; color: #1E293B; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #334155;
        }
        .form-group label i { margin-right: 8px; color: #3B82F6; width: 18px; }
        .form-group label .required-star { color: #EF4444; margin-left: 4px; font-weight: 700; }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid #CBD5E1;
            border-radius: 16px;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: white;
        }
        .form-group input.error,
        .form-group select.error {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
            background: #FEF2F2;
        }
        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .select2-container--default .select2-selection--single {
            height: 48px;
            border: 1px solid #CBD5E1;
            border-radius: 16px;
            background: #F8FAFC;
            font-family: 'Inter', sans-serif;
            padding: 0 8px;
            transition: all 0.2s ease;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 46px;
            color: #1E293B;
            padding-left: 8px;
            font-size: 1rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94A3B8;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 12px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3B82F6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .select2-container--default .select2-selection--single.error {
            border-color: #EF4444;
            background: #FEF2F2;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        .select2-dropdown {
            border: 1px solid #CBD5E1;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        .select2-search--dropdown {
            padding: 10px;
            background: #F8FAFC;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #CBD5E1;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            outline: none;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3B82F6;
            color: white;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #EFF6FF;
            color: #1D4ED8;
            font-weight: 600;
        }
        .select2-results__option {
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .dynamic-field { display: none; animation: slideDown 0.4s ease-out; }
        .dynamic-field.show { display: block; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-login {
            width: 100%;
            background: #3B82F6;
            border: none;
            padding: 0.85rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover:not(:disabled) {
            background: #2563EB;
            transform: scale(1.01);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        .btn-login:active { transform: scale(0.98); }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-login i { font-size: 1rem; }

        .btn-cancel {
            width: 100%;
            background: #EF4444;
            border: none;
            padding: 0.85rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-cancel:hover {
            background: #DC2626;
            transform: scale(1.01);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }
        .btn-cancel:active { transform: scale(0.98); }
        .btn-cancel:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        .btn-actions { display: flex; gap: 10px; align-items: stretch; }
        .btn-actions .btn-login,
        .btn-actions .btn-cancel { flex: 1; }

        #msg {
            margin-top: 1.5rem;
            padding: 0.75rem;
            border-radius: 16px;
            font-weight: 500;
            font-size: 0.875rem;
            display: none;
            align-items: center;
            gap: 8px;
            justify-content: center;
            line-height: 1.5;
            text-align: center;
        }
        #msg.show { display: flex; }
        #msg.processing { background: #EFF6FF; color: #2563EB; }
        #msg.success { background: #ECFDF5; color: #10B981; }
        #msg.error { background: #FEF2F2; color: #EF4444; }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: -0.125em;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border-anim 0.75s linear infinite;
        }
        .spinner-border-sm { width: 0.9rem; height: 0.9rem; border-width: 0.15em; }
        @keyframes spinner-border-anim { to { transform: rotate(360deg); } }
        .btn-login .spinner-border,
        .btn-cancel .spinner-border { color: #fff; margin-right: 8px; }

        .field-hint {
            display: block;
            font-size: 0.75rem;
            color: #64748B;
            margin-top: 6px;
            font-style: italic;
        }
        .field-hint i { color: #3B82F6; margin-right: 4px; }

        .form-disabled .amounts-container,
        .form-disabled #mode_paiement,
        .form-disabled #montant_payer,
        .form-disabled .dynamic-field { opacity: 0.5; pointer-events: none; }

        @media (max-width: 600px) {
            .login-container { padding: 1.5rem; }
            .login-container h5 { font-size: 1.5rem; }
            .amounts-container { flex-direction: column; }
            .amount-card { width: 100%; }
            .btn-actions { flex-direction: column; }
        }
        @media (max-width: 480px) {
            .login { padding: 1rem; }
            .login-container { padding: 1.2rem; border-radius: 24px; }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo">
            <h1><a href="#"><i class="fas fa-cubes"></i> {{ $nom_app }}</a></h1>
            <p><strong>PAIEMENT</strong></p>
        </div>
    </header>

    <div class="login">
        <div class="login-container">
            <h5>Effectuer un paiement</h5>

            <form id="form_paiement" method="POST" class="{{ $facture ? '' : 'form-disabled' }}">
                @csrf

                <input type="hidden" id="facture_id" name="facture_id" value="{{ $factureId ?? '' }}">
                <input type="hidden" id="devise_facture" name="devise_facture" value="{{ $devise_facture }}">
                <input type="hidden" id="cdf_montant" name="cdf_montant" value="{{ $facture ? number_format(abs($cdf_montant), 2, ',', ' ') : '' }}">
                <input type="hidden" id="usd_montant" name="usd_montant" value="{{ $facture ? number_format(abs($usd_montant), 2, ',', ' ') : '' }}">

                <div class="form-group">
                    <label for="numero_facture_select">
                        <i class="fas fa-file-invoice"></i> Numéro de facture <span class="required-star">*</span>
                    </label>
                    <select id="numero_facture_select" name="numero_facture_select" autocomplete="off">
                        <option value="" disabled {{ $facture ? '' : 'selected' }}>-- Sélectionner une facture --</option>
                        @forelse($toutes_factures as $fi)
                            <option value="{{ $fi->id }}" {{ $fi->id == $factureId ? 'selected' : '' }}>
                                {{ $fi->numero ?? 'FAC-' . str_pad($fi->id, 6, '0', STR_PAD_LEFT) }}
                                @if($getNomClient($fi))
                                    — {{ $getNomClient($fi) }}
                                @endif
                            </option>
                        @empty
                            <option value="" disabled>-- Aucune facture impayée --</option>
                        @endforelse
                    </select>
                    <input type="hidden" id="numero_facture" name="numero_facture" value="{{ $numero_facture }}">
                </div>

                <div class="amounts-container">
                    <div class="amount-card {{ $devise_defaut === 'CDF' && $facture ? 'active' : '' }}" id="card_cdf">
                        <span class="amount-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h4 id="text_cdf">{{ $facture ? number_format(abs($cdf_montant), 2, ',', ' ') : '0,00' }} CDF</h4>
                    </div>
                    <div class="amount-card {{ $devise_defaut === 'USD' && $facture ? 'active' : '' }}" id="card_usd">
                        <span class="amount-icon"><i class="fas fa-dollar-sign"></i></span>
                        <h4 id="text_usd">{{ $facture ? number_format(abs($usd_montant), 2, ',', ' ') : '0,00' }} USD</h4>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-credit-card"></i> Mode de paiement <span class="required-star">*</span></label>
                    <select id="mode_paiement" name="mode_paiement">
                        <option value="mobile_money" selected>Mobile Money</option>
                        <option disabled value="bank">Virement bancaire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-coins"></i> Montant à payer <span class="required-star">*</span></label>
                    <input type="text" id="montant_payer" name="montant_payer"
                           value="{{ $montant_defaut }}"
                           placeholder="Ex: 25 000 ou 50.00" autocomplete="off" inputmode="decimal">
                </div>

                <div id="devise_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-exchange-alt"></i> Devise de paiement <span class="required-star">*</span></label>
                        <select id="devise" name="devise_select">
                            <option value="">-- Choisissez la devise --</option>
                            <option value="USD" {{ $devise_defaut === 'USD' ? 'selected' : '' }}>💵 USD (Dollar américain)</option>
                            <option value="CDF" {{ $devise_defaut === 'CDF' ? 'selected' : '' }}>💰 CDF (Franc congolais)</option>
                        </select>
                    </div>
                </div>

                <div id="mobile_money_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-mobile-alt"></i> Numéro Mobile Money <span class="required-star">*</span></label>
                        <input type="tel" id="numero_mobile" name="numero_mobile"
                               placeholder="Ex: +243812345678 ou 0812345678"
                               autocomplete="off" inputmode="tel">
                        <small class="field-hint">
                            <i class="fas fa-info-circle"></i>
                            Formats acceptés : <strong>+243XXXXXXXXX</strong>, <strong>243XXXXXXXXX</strong>, <strong>0XXXXXXXXX</strong>
                        </small>
                    </div>
                </div>

                <div id="bank_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Numéro de compte bancaire <span class="required-star">*</span></label>
                        <input type="text" id="numero_compte" name="numero_compte" placeholder="Numéro complet du compte" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom du titulaire du compte <span class="required-star">*</span></label>
                        <input type="text" id="nom_titulaire" name="nom_titulaire" placeholder="Nom complet" autocomplete="off">
                    </div>
                </div>

                <div class="btn-actions">
                    <button class="btn-login" id="btn_payer" type="button" {{ $facture ? '' : 'disabled' }}>
                        <i class="fas fa-hand-holding-usd"></i> Payer
                    </button>
                    <button class="btn-cancel" id="btn_annuler" type="button">
                        <i class="fas fa-times-circle"></i> Annuler
                    </button>
                </div>
                <div id="msg"></div>
            </form>
        </div>
    </div>

    <div id="footer">{{ $nom_app }} © 2026 - Paiement sécurisé</div>

    <script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('./assets/js/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <span style="display: none;" id="v_mode_abonnement"></span>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var URL_FACTURE_MONTANTS = "{{ route('get_facture_montants') }}";

        function convertirEnNombre(valeur) {
            if (!valeur && valeur !== 0) return NaN;
            let str = String(valeur).trim().replace(/\s/g, '').replace(',', '.');
            return parseFloat(str);
        }

        function normaliserNumeroMobile(numero) {
            if (!numero) return null;
            var clean = String(numero).replace(/[\s\-\.\(\)]/g, '');
            if (/^\+243[0-9]{9}$/.test(clean)) return clean;
            if (/^243[0-9]{9}$/.test(clean)) return '+' + clean;
            if (/^0[1-9][0-9]{8}$/.test(clean)) return '+243' + clean.substring(1);
            if (/^[1-9][0-9]{8}$/.test(clean)) return '+243' + clean;
            return null;
        }

        function showMessage(message, type) {
            var icons = { processing: 'fa-hourglass-half', success: 'fa-check-circle', error: 'fa-exclamation-circle' };
            var classes = { processing: 'processing', success: 'success', error: 'error' };
            var icon = icons[type] || 'fa-info-circle';
            var cls  = classes[type] || 'processing';
            $('#msg').removeClass('processing success error').addClass(cls + ' show')
                .html('<i class="fas ' + icon + '"></i> <span>' + message + '</span>');
        }

        function clearMessage() {
            $('#msg').removeClass('processing success error show').html('');
        }

        function typeMessage(message, type, duration) {
            var icons = { processing: 'fa-hourglass-half', success: 'fa-check-circle', error: 'fa-exclamation-circle' };
            var classes = { processing: 'processing', success: 'success', error: 'error' };
            var icon = icons[type] || 'fa-info-circle';
            var cls  = classes[type] || 'processing';
            duration = duration || 40;
            $('#msg').removeClass('processing success error').addClass(cls + ' show')
                .html('<i class="fas ' + icon + '"></i> <span></span>');
            var target = $('#msg span'), i = 0;
            var timer = setInterval(function() {
                if (i < message.length) { target.text(target.text() + message[i]); i++; }
                else clearInterval(timer);
            }, duration);
        }

        function toggleFields() {
            var selectedMode = $('#mode_paiement').val();
            $('#devise_field').removeClass('show');
            $('#mobile_money_field').removeClass('show');
            $('#bank_field').removeClass('show');

            if (selectedMode === 'mobile_money' || selectedMode === 'bank') {
                $('#devise_field').addClass('show');
            }
            if (selectedMode === 'mobile_money') {
                $('#mobile_money_field').addClass('show');
            } else if (selectedMode === 'bank') {
                $('#bank_field').addClass('show');
            }
            clearMessage();
        }

        $('#mode_paiement').change(function() { toggleFields(); });

        $('#devise').change(function() {
            var devise = $(this).val();
            var cdf = $('#cdf_montant').val();
            var usd = $('#usd_montant').val();

            if (devise === 'USD') $('#montant_payer').val(usd);
            else if (devise === 'CDF') $('#montant_payer').val(cdf);

            $('#card_cdf, #card_usd').removeClass('active');
            if (devise === 'USD') $('#card_usd').addClass('active');
            else if (devise === 'CDF') $('#card_cdf').addClass('active');
        });

        function resetPayerButton() {
            var factureId = $('#facture_id').val();
            if (!factureId || factureId == 0) {
                $("#btn_payer").prop("disabled", true).html('<i class="fas fa-hand-holding-usd"></i> Payer');
            } else {
                $("#btn_payer").prop("disabled", false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
            }
        }

        function resetAnnulerButton() {
            $("#btn_annuler").prop("disabled", false)
                .html('<i class="fas fa-times-circle"></i> Annuler').show();
        }

        function setPayerLoading(texte) {
            $("#btn_payer").prop("disabled", true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + texte
            );
        }

        function setAnnulerLoading(texte) {
            $("#btn_annuler").prop("disabled", true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + texte
            );
        }

        function chargerFacture(factureId, cb) {
            if (!factureId) { if (cb) cb(false); return; }

            $.ajax({
                type: "GET",
                url: URL_FACTURE_MONTANTS,
                data: { facture_id: factureId },
                dataType: 'json',
                success: function(r) {
                    if (!r || !r.success) {
                        showMessage(r.message || 'Impossible de charger cette facture.', 'error');
                        if (cb) cb(false);
                        return;
                    }

                    $('#facture_id').val(r.facture_id);
                    $('#devise_facture').val(r.devise);
                    $('#cdf_montant').val(r.cdf);
                    $('#usd_montant').val(r.usd);
                    $('#numero_facture').val(r.numero_facture);

                    $('#text_cdf').text(r.cdf + ' CDF');
                    $('#text_usd').text(r.usd + ' USD');

                    $('#devise').val(r.devise_defaut);

                    $('#card_cdf, #card_usd').removeClass('active');
                    if (r.devise_defaut === 'USD') $('#card_usd').addClass('active');
                    else $('#card_cdf').addClass('active');

                    $('#montant_payer').val(r.montant_defaut);

                    $('#form_paiement').removeClass('form-disabled');
                    $('#numero_facture_select').next('.select2-container')
                        .find('.select2-selection--single').removeClass('error');

                    resetPayerButton();
                    toggleFields();
                    clearMessage();

                    if (cb) cb(true);
                },
                error: function(xhrErr) {
                    console.error('Erreur get_facture_montants:', xhrErr);
                    showMessage('Erreur de connexion. Impossible de charger les données.', 'error');
                    if (cb) cb(false);
                }
            });
        }

        $(document).ready(function() {

            $('#numero_facture_select').select2({
                placeholder: "-- Sélectionner une facture --",
                allowClear: false,
                width: '100%',
                language: {
                    noResults: function() { return "Aucune facture trouvée"; },
                    searching: function() { return "Recherche..."; }
                }
            });

            toggleFields();

            var factureId = $('#facture_id').val();
            if (factureId && factureId != 0) {
                var deviseDefaut = $('#devise').val() || "{{ $devise_defaut }}";
                var cdfDefaut = $('#cdf_montant').val();
                var usdDefaut = $('#usd_montant').val();
                if (deviseDefaut === 'USD' && usdDefaut) $('#montant_payer').val(usdDefaut);
                else if (deviseDefaut === 'CDF' && cdfDefaut) $('#montant_payer').val(cdfDefaut);
            }

            $('#numero_mobile').on('input', function() {
                this.value = this.value.replace(/[^\d\+\s\-\.]/g, '');
            });

            resetAnnulerButton();

            $('#numero_facture_select').on('change', function () {
                var newId = $(this).val();
                if (!newId) return;

                showMessage('Chargement des données de la facture...', 'processing');

                chargerFacture(newId, function(ok) {
                    if (ok) {
                        $('#numero_mobile').val('');
                        $('#numero_compte').val('');
                        $('#nom_titulaire').val('');
                        clearMessage();
                    }
                });
            });
        });

        $('#montant_payer, #numero_mobile, #numero_compte, #nom_titulaire, #devise').on('input change', function() {
            $(this).removeClass('error');
        });

        var xhr = [];
        var interv = null;
        REF = '';
        var mode_abonnement = 1;

        var callback = function() {
            var x = $.ajax({
                url: "{{ url('/check_payment') }}",
                data: { myref: REF, mode_abonnement: mode_abonnement },
                success: function(res) {
                    try {
                        var mode_abonnement = $("#v_mode_abonnement").html();
                        var parsed = (typeof res === 'string') ? JSON.parse(res) : res;
                        var trans = parsed.transaction;
                        var status = trans?.status;

                        if (mode_abonnement == 1) {
                            if (status === 'success') {
                                clearInterval(interv); interv = null;
                                $(xhr).each(function(i, xh) { try { xh.abort(); } catch (e) {} });
                                xhr = [];
                                resetPayerButton();
                                resetAnnulerButton();
                                typeMessage(parsed.message || 'Paiement effectué avec succès !', 'success');
                                setTimeout(function() { location.assign("{{ url('/') }}"); }, 5000);
                            } else if (status === 'failed') {
                                clearInterval(interv); interv = null;
                                $(xhr).each(function(i, xh) { try { xh.abort(); } catch (e) {} });
                                xhr = [];
                                resetPayerButton();
                                resetAnnulerButton();
                                typeMessage(parsed.message || 'Paiement échoué. Veuillez réessayer.', 'error');
                            } else if (status === 'pending') {
                                showMessage(parsed.message || 'En attente de confirmation sur votre téléphone...', 'processing');
                            }
                        }
                    } catch (e) {
                        console.error('Erreur parsing check_payment:', e, res);
                    }
                },
                error: function(xhrErr) { console.error('Erreur AJAX check_payment:', xhrErr); }
            });
            xhr.push(x);
        };

        $("#btn_annuler").click(function(e) {
            e.preventDefault();
            setAnnulerLoading("Annulation...");
            if (interv) { clearInterval(interv); interv = null; }
            $(xhr).each(function(i, xh) { try { xh.abort(); } catch (err) {} });
            xhr = []; REF = '';
            resetPayerButton();
            typeMessage("Paiement annulé par l'utilisateur.", 'error');
            setTimeout(function() { resetAnnulerButton(); }, 2500);
        });

        $("#btn_payer").click(async function(e) {
            e.preventDefault();
            var factureId = $('#facture_id').val();

            $('.form-group input, .form-group select').removeClass('error');
            $('#numero_facture_select').next('.select2-container')
                .find('.select2-selection--single').removeClass('error');
            clearMessage();

            if (!factureId || factureId == 0 || factureId === "0") {
                $('#numero_facture_select').next('.select2-container')
                    .find('.select2-selection--single').addClass('error');
                showMessage('Veuillez sélectionner une facture.', 'error');
                return;
            }

            setPayerLoading("Vérification...");
            showMessage('Vérification de la facture en cours...', 'processing');

            var response;
            try {
                response = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
            } catch (err) {
                console.error('Erreur check_paie_facture:', err);
                resetPayerButton();
                showMessage('Erreur de connexion lors de la vérification de la facture.', 'error');
                return;
            }

            if (response == 1) {
                showMessage('Cette facture a déjà été payée.', 'error');
                resetPayerButton(); return;
            }

            var mode_paiement = $('#mode_paiement').val();
            var devise_texte = $('#devise').val();
            var montant_payer = $('#montant_payer').val();

            if (!mode_paiement) {
                $('#mode_paiement').addClass('error');
                showMessage('Veuillez sélectionner un mode de paiement.', 'error');
                resetPayerButton(); return;
            }

            if (!montant_payer || !montant_payer.trim()) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Veuillez saisir le montant à payer.', 'error');
                resetPayerButton(); return;
            }

            var montant_numerique = convertirEnNombre(montant_payer);
            if (isNaN(montant_numerique)) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Le montant saisi est invalide.', 'error');
                resetPayerButton(); return;
            }

            if (montant_numerique <= 0) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Le montant doit être supérieur à zéro.', 'error');
                resetPayerButton(); return;
            }

            if (!devise_texte) {
                $('#devise').addClass('error');
                showMessage('Veuillez choisir une devise (USD ou CDF).', 'error');
                resetPayerButton(); return;
            }

            var rawCdf = $('#cdf_montant').val();
            var rawUsd = $('#usd_montant').val();
            var cdf_numerique = convertirEnNombre(rawCdf);
            var usd_numerique = convertirEnNombre(rawUsd);

            if (devise_texte === 'USD') {
                if (montant_numerique > usd_numerique) {
                    $('#montant_payer').addClass('error').focus();
                    showMessage('Le montant dépasse le montant USD de la facture (' + rawUsd + ' USD).', 'error');
                    resetPayerButton(); return;
                }
            } else if (devise_texte === 'CDF') {
                if (montant_numerique > cdf_numerique) {
                    $('#montant_payer').addClass('error').focus();
                    showMessage('Le montant dépasse le montant CDF de la facture (' + rawCdf + ' CDF).', 'error');
                    resetPayerButton(); return;
                }
            }

            var montant_a_envoyer = montant_numerique;
            var devise_code = devise_texte;
            var mobile_money = "";
            var numero_compte = "";
            var nom_titulaire = "";

            if (mode_paiement === 'mobile_money') {
                var numero_mobile = $('#numero_mobile').val();
                if (!numero_mobile || !numero_mobile.trim()) {
                    $('#numero_mobile').addClass('error').focus();
                    showMessage('Veuillez saisir votre numéro Mobile Money.', 'error');
                    resetPayerButton(); return;
                }
                var numero_normalise = normaliserNumeroMobile(numero_mobile);
                if (!numero_normalise) {
                    $('#numero_mobile').addClass('error').focus();
                    showMessage('Numéro Mobile Money invalide (+243XXXXXXXXX).', 'error');
                    resetPayerButton(); return;
                }
                $('#numero_mobile').val(numero_normalise);
                mobile_money = numero_normalise;
            } else if (mode_paiement === 'bank') {
                numero_compte = $('#numero_compte').val();
                nom_titulaire = $('#nom_titulaire').val();
                if (!numero_compte.trim()) {
                    $('#numero_compte').addClass('error').focus();
                    showMessage('Veuillez saisir votre numéro de compte bancaire.', 'error');
                    resetPayerButton(); return;
                }
                if (!nom_titulaire.trim()) {
                    $('#nom_titulaire').addClass('error').focus();
                    showMessage('Veuillez saisir le nom du titulaire du compte.', 'error');
                    resetPayerButton(); return;
                }
                if (numero_compte.length < 10) {
                    $('#numero_compte').addClass('error').focus();
                    showMessage('Numéro de compte trop court (min. 10 caractères).', 'error');
                    resetPayerButton(); return;
                }
                mobile_money = numero_compte;
            }

            $("#v_mode_abonnement").html(1);
            setPayerLoading("Traitement...");
            showMessage('Initialisation de la transaction en cours...', 'processing');

            $.ajax({
                type: "POST",
                url: "{{ url('save_paiement_facture_1') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    facture_id: factureId,
                    numero_facture: $('#numero_facture').val(),
                    mode_de_paiement: mode_paiement,
                    devise_recu: devise_code,
                    montant_recu: montant_a_envoyer,
                    mobile_money: mobile_money,
                    numero_compte: numero_compte,
                    nom_titulaire: nom_titulaire,
                },
                success: function(rep) {
                    var r;
                    try { r = (typeof rep === 'string') ? JSON.parse(rep) : rep; }
                    catch (err) {
                        resetPayerButton();
                        showMessage('Réponse invalide du serveur.', 'error');
                        return;
                    }
                    if (r && r.success) {
                        typeMessage(r.message || 'Transaction initialisée. Saisissez votre Pin pour confirmer.', 'success');
                        clearInterval(interv);
                        REF = r.myref;
                        interv = setInterval(callback, 3000);
                        resetAnnulerButton();
                    } else {
                        resetPayerButton();
                        resetAnnulerButton();
                        typeMessage((r && r.message) ? r.message : 'Erreur lors de l\'initialisation.', 'error');
                    }
                },
                error: function(xhrErr) {
                    resetPayerButton();
                    resetAnnulerButton();
                    var errMsg = 'Erreur de connexion au serveur. Veuillez réessayer.';
                    if (xhrErr.status === 419) errMsg = 'Session expirée. Rechargez la page.';
                    if (xhrErr.status === 500) errMsg = 'Erreur interne du serveur.';
                    if (xhrErr.status === 404) errMsg = 'Service de paiement introuvable.';
                    showMessage(errMsg, 'error');
                }
            });
        });
    </script>

</body>
</html>
