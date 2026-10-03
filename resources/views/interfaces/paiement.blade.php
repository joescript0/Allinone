@php
    use App\Models\appnames;
    use App\Models\Factureass;
    use App\Models\detailpaiessachats;
    use App\Models\Achats; // ⚠️ Adapte le nom du modèle des achats à ton projet
    use Illuminate\Support\Facades\DB;

    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';

    // --- Récupération de l'ID de la facture ---
    $facture_id = $facture_id ?? null;
    $factureId  = base64_decode($facture_id) ? base64_decode($facture_id) : null;
    $facture    = base64_decode($facture_id) ? Factureass::find(base64_decode($facture_id)) : null;

    // --- Initialisation des variables ---
    $montant_usd_1 = 0;
    $montant_cdf_1 = 0;
    $montant_usd_2 = 0;
    $montant_cdf_2 = 0;
    $usd_montant_total_a_payer = 0;
    $cdf_montant_total_a_payer = 0;

    $devise_facture = $facture->devise ?? 0; // 0 = USD, 1 = CDF
    $numero_facture = $facture->numero
        ?? ('FAC-' . str_pad($factureId ?? '0000', 6, '0', STR_PAD_LEFT));
    $devise_defaut  = ($devise_facture == 1) ? 'CDF' : 'USD';

    if ($facture) {
        $taux = $facture->taux ?? 1;
        if ($taux <= 0) $taux = 1;

        // --- Récupération des achats liés à la facture ---
        $achats = Achats::where('facture_id', $factureId)->get();

        // --- 1. Calcul du total original (sans frais) avec réduction ---
        $total_original = 0;
        foreach ($achats as $a) {
            $devise_achat_orig = $a->devise_achat ?? $devise_facture;
            $reduction_orig    = (isset($a->reduction) && $a->reduction > 0) ? $a->reduction : 0;
            $net_orig          = $a->total - $reduction_orig;

            if ($devise_achat_orig == $devise_facture) {
                $total_original += $net_orig;
            } elseif ($devise_facture == 0) {
                $total_original += ($taux > 0) ? ($net_orig / $taux) : 0;
            } else {
                $total_original += $net_orig * $taux;
            }
        }

        // --- 2. Calcul des paiements déjà effectués ---
        $paiements = Detailpaiessachats::where('facture_id', $factureId)->get();
        $montant_usd_paye = 0;
        $montant_cdf_paye = 0;
        foreach ($paiements as $paiement) {
            if ($paiement->devise_recu == 0) { // paiement en USD
                $montant_usd_paye += $paiement->montant_recu;
                $montant_cdf_paye += $paiement->montant_recu * $taux;
            } else { // paiement en CDF
                $montant_cdf_paye += $paiement->montant_recu;
                $montant_usd_paye += $paiement->montant_recu / $taux;
            }
        }

        // --- 3. Conversion du total original en USD et CDF selon devise de la facture ---
        if ($devise_facture == 0) {
            $total_original_usd = $total_original;
            $total_original_cdf = $total_original * $taux;
        } else {
            $total_original_cdf = $total_original;
            $total_original_usd = $total_original / $taux;
        }

        // --- 4. Déterminer si la facture est impayée (sans tolérance) ---
        $est_impayee = ($montant_usd_paye < $total_original_usd)
                    || ($montant_cdf_paye < $total_original_cdf);

        // --- 5. Vérifier le délai d'1 heure depuis la création de la facture ---
        $date_creation_facture = strtotime($facture->created_at);
        $delai_1h      = 3600;
        $delai_depasse = (time() - $date_creation_facture) > $delai_1h;

        // --- 6. Appliquer les frais de crédit sur chaque achat si conditions remplies ---
        foreach ($achats as $achat) {
            if (($achat->frais_credit == 0 || $achat->frais_credit === null)
                && $est_impayee && $delai_depasse) {
                $frais = $achat->total * 0.05; // 5 %
                $achat->frais_credit = $frais;
                $achat->save();
            }
        }

        // --- 7. Recalculer le total incluant les frais de crédit et réduction ---
        $total_avec_frais = 0;
        foreach ($achats as $achat) {
            $devise_achat    = $achat->devise_achat ?? $devise_facture;
            $reduction_achat = (isset($achat->reduction) && $achat->reduction > 0) ? $achat->reduction : 0;
            $frais_achat     = $achat->frais_credit ?? 0;

            $net_achat_devise = $achat->total - $reduction_achat + $frais_achat;

            if ($devise_achat == $devise_facture) {
                $total_avec_frais += $net_achat_devise;
            } elseif ($devise_facture == 0) {
                $total_avec_frais += ($taux > 0) ? ($net_achat_devise / $taux) : 0;
            } else {
                $total_avec_frais += $net_achat_devise * $taux;
            }
        }

        // --- 8. Montant total de la facture en USD et CDF (incluant les frais) ---
        if ($devise_facture == 0) {
            $montant_usd_1 = $total_avec_frais;
            $montant_cdf_1 = $total_avec_frais * $taux;
        } else {
            $montant_cdf_1 = $total_avec_frais;
            $montant_usd_1 = $total_avec_frais / $taux;
        }

        // --- 9. Montants déjà payés ---
        $montant_usd_2 = $montant_usd_paye;
        $montant_cdf_2 = $montant_cdf_paye;

        // --- 10. Solde restant ---
        $usd_montant_total_a_payer = max(0, $montant_usd_1 - $montant_usd_2);
        $cdf_montant_total_a_payer = max(0, $montant_cdf_1 - $montant_cdf_2);

        // --- On surcharge les variables attendues par la vue (format base64 conservé) ---
        $usd_montant = base64_encode($usd_montant_total_a_payer);
        $cdf_montant = base64_encode($cdf_montant_total_a_payer);
    }

    // --- Montant par défaut selon devise ---
    if ($devise_defaut === 'USD') {
        $montant_defaut = number_format(abs(base64_decode($usd_montant)), 2, ',', ' ');
    } else {
        $montant_defaut = number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ');
    }
@endphp
<?php
$facture_id = $facture_id ?? 123;
?>
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
    <link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}">
    <title>{{ $nom_app }} - PAIEMENT</title>

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
            transition: transform 0.2s ease;
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
        .form-group input[readonly] {
            background: #EFF6FF;
            color: #1D4ED8;
            font-weight: 700;
            border-color: #BFDBFE;
            cursor: not-allowed;
            letter-spacing: 0.5px;
        }
        .form-group input[readonly]:focus {
            outline: none;
            border-color: #BFDBFE;
            box-shadow: none;
            background: #EFF6FF;
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
        .btn-login:hover {
            background: #2563EB;
            transform: scale(1.01);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        .btn-login:active { transform: scale(0.98); }
        .btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .btn-login i { font-size: 1rem; }

        /* ============================================================
           BOUTON ANNULER
           ============================================================ */
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
        .btn-cancel:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Conteneur flex pour les boutons Payer / Annuler */
        .btn-actions {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }
        .btn-actions .btn-login,
        .btn-actions .btn-cancel {
            flex: 1;
        }

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

        /* ============================================================
           SPINNER POUR LES BOUTONS (Payer / Annuler)
           ============================================================ */
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
        .spinner-border-sm {
            width: 0.9rem;
            height: 0.9rem;
            border-width: 0.15em;
        }
        @keyframes spinner-border-anim {
            to { transform: rotate(360deg); }
        }
        .btn-login .spinner-border,
        .btn-cancel .spinner-border {
            color: #fff;
            margin-right: 8px;
        }

        /* Indice d'aide sous le champ mobile money */
        .field-hint {
            display: block;
            font-size: 0.75rem;
            color: #64748B;
            margin-top: 6px;
            font-style: italic;
        }
        .field-hint i { color: #3B82F6; margin-right: 4px; }

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

            <form id="form_paiement" method="POST">
                @csrf

                <input type="hidden" id="cdf_montant" name="cdf_montant" value="{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }}">
                <input type="hidden" id="usd_montant" name="usd_montant" value="{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }}">
                <input type="hidden" id="facture_id" name="facture_id" value="{{ base64_decode($facture_id) ?? '' }}">
                <input type="hidden" id="devise_facture" name="devise_facture" value="{{ $devise_facture }}">

                <div class="form-group">
                    <label><i class="fas fa-file-invoice"></i> Numéro de facture</label>
                    <input type="text" id="numero_facture" name="numero_facture"
                           value="{{ $numero_facture }}"
                           readonly
                           tabindex="-1"
                           onfocus="this.blur()"
                           style="user-select: none;">
                </div>

                <div class="amounts-container">
                    <div class="amount-card {{ $devise_defaut === 'CDF' ? 'active' : '' }}">
                        <span class="amount-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h4>{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }} CDF</h4>
                    </div>
                    <div class="amount-card {{ $devise_defaut === 'USD' ? 'active' : '' }}">
                        <span class="amount-icon"><i class="fas fa-dollar-sign"></i></span>
                        <h4>{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }} USD</h4>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-credit-card"></i> Mode de paiement <span class="required-star">*</span></label>
                    <select id="mode_paiement" name="mode_paiement">
                        <option value="mobile_money" selected>Mobile Money</option>
                        <option value="bank">Virement bancaire</option>
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
                    <button class="btn-login" id="btn_payer" type="button">
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

    <span style="display: none;" id="v_mode_abonnement"></span>

    <script>
        // ===== CONFIGURATION GLOBALE CSRF POUR TOUTES LES REQUÊTES AJAX =====
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ===== OUTILS =====
        function convertirEnNombre(valeur) {
            if (!valeur && valeur !== 0) return NaN;
            let str = String(valeur).trim();
            str = str.replace(/\s/g, '');
            str = str.replace(',', '.');
            return parseFloat(str);
        }

        /**
         * Normalise un numéro mobile money vers le format +243XXXXXXXXX
         * Accepte : +243812345678, 243812345678, 0812345678, 812345678
         * Retourne null si le format est invalide.
         */
        function normaliserNumeroMobile(numero) {
            if (!numero) return null;
            var clean = String(numero).replace(/[\s\-\.\(\)]/g, '');

            if (/^\+243[0-9]{9}$/.test(clean)) {
                return clean;
            }
            if (/^243[0-9]{9}$/.test(clean)) {
                return '+' + clean;
            }
            if (/^0[1-9][0-9]{8}$/.test(clean)) {
                return '+243' + clean.substring(1);
            }
            if (/^[1-9][0-9]{8}$/.test(clean)) {
                return '+243' + clean;
            }
            return null;
        }

        var DEVISE_DEFAUT = "{{ $devise_defaut }}";

        // ============================================================
        // ===== MESSAGE : PAS DE SPINNER — icône statique ============
        // ============================================================
        function showMessage(message, type) {
            var icons = {
                processing: 'fa-hourglass-half',   // ✅ icône statique, PAS de spin
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle'
            };
            var classes = { processing: 'processing', success: 'success', error: 'error' };
            var icon = icons[type] || 'fa-info-circle';
            var cls = classes[type] || 'processing';

            $('#msg')
                .removeClass('processing success error')
                .addClass(cls + ' show')
                .html('<i class="fas ' + icon + '"></i> <span>' + message + '</span>');
        }

        function clearMessage() {
            $('#msg').removeClass('processing success error show').html('');
        }

        // ===== AFFICHAGE PROGRESSIF (typewriter — icône statique) =====
        function typeMessage(message, type, duration) {
            var icons = {
                processing: 'fa-hourglass-half',   // ✅ statique
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle'
            };
            var classes = { processing: 'processing', success: 'success', error: 'error' };
            var icon = icons[type] || 'fa-info-circle';
            var cls = classes[type] || 'processing';
            duration = duration || 40;

            $('#msg').removeClass('processing success error').addClass(cls + ' show').html('<i class="fas ' + icon + '"></i> <span></span>');
            var target = $('#msg span');
            var i = 0;
            var timer = setInterval(function() {
                if (i < message.length) {
                    target.text(target.text() + message[i]);
                    i++;
                } else {
                    clearInterval(timer);
                }
            }, duration);
        }

        // ===== TOGGLE DES CHAMPS =====
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

        // ===== SYNCHRO MONTANT ↔ DEVISE =====
        $('#devise').change(function() {
            var devise = $(this).val();
            var cdf = $('#cdf_montant').val();
            var usd = $('#usd_montant').val();

            if (devise === 'USD') {
                $('#montant_payer').val(usd);
            } else if (devise === 'CDF') {
                $('#montant_payer').val(cdf);
            }

            $('.amount-card').removeClass('active');
            if (devise === 'USD') {
                $('.amount-card:last').addClass('active');
            } else if (devise === 'CDF') {
                $('.amount-card:first').addClass('active');
            }
        });

        // ============================================================
        // ===== FONCTIONS DE GESTION DES BOUTONS =====================
        // ============================================================
        function resetPayerButton() {
            $("#btn_payer")
                .prop("disabled", false)
                .html('<i class="fas fa-hand-holding-usd"></i> Payer');
        }

        function resetAnnulerButton() {
            $("#btn_annuler")
                .prop("disabled", false)
                .html('<i class="fas fa-times-circle"></i> Annuler')
                .show();
        }

        // ✅ Helper : met le bouton Payer en mode "chargement" (spinner + texte)
        function setPayerLoading(texte) {
            $("#btn_payer").prop("disabled", true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + texte
            );
        }

        // ✅ Helper : met le bouton Annuler en mode "chargement" (spinner + texte)
        function setAnnulerLoading(texte) {
            $("#btn_annuler").prop("disabled", true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + texte
            );
        }

        $(document).ready(function() {
            toggleFields();

            var cdfDefaut = $('#cdf_montant').val();
            var usdDefaut = $('#usd_montant').val();

            if (DEVISE_DEFAUT === 'USD' && usdDefaut) {
                $('#montant_payer').val(usdDefaut);
            } else if (DEVISE_DEFAUT === 'CDF' && cdfDefaut) {
                $('#montant_payer').val(cdfDefaut);
            }

            // Filtre de saisie mobile : chiffres, +, espaces, tirets, points
            $('#numero_mobile').on('input', function() {
                this.value = this.value.replace(/[^\d\+\s\-\.]/g, '');
            });

            resetAnnulerButton();
        });

        $('#montant_payer, #numero_mobile, #numero_compte, #nom_titulaire, #devise').on('input change', function() {
            $(this).removeClass('error');
        });

        // ============================================================
        // ===== LOGIQUE PAIEMENT =====================================
        // ============================================================
        var xhr = [];
        var interv = null;
        REF = '';
        var mode_abonnement = 1;

        // ===== CALLBACK de polling =====
        var callback = function() {
            var x = $.ajax({
                url: "{{ url('/check_payment') }}",
                data: {
                    myref: REF,
                    mode_abonnement: mode_abonnement,
                },
                success: function(res) {
                    try {
                        var mode_abonnement = $("#v_mode_abonnement").html();
                        var parsed = (typeof res === 'string') ? JSON.parse(res) : res;
                        var trans = parsed.transaction;
                        var status = trans?.status;

                        if (mode_abonnement == 1) {
                            if (status === 'success') {
                                clearInterval(interv);
                                interv = null;
                                $(xhr).each(function(i, xh) { try { xh.abort(); } catch (e) {} });
                                xhr = [];
                                resetPayerButton();
                                resetAnnulerButton();
                                typeMessage(parsed.message || 'Paiement effectué avec succès !', 'success');
                                setTimeout(function() {
                                    location.assign("{{ url('/') }}");
                                }, 5000);
                            } else if (status === 'failed') {
                                clearInterval(interv);
                                interv = null;
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
                error: function(xhrErr) {
                    console.error('Erreur AJAX check_payment:', xhrErr);
                }
            });
            xhr.push(x);
        };

        // ============================================================
        // ===== CLIC SUR LE BOUTON ANNULER ===========================
        // ✅ Spinner + texte "Annulation..." conservés
        // ============================================================
        $("#btn_annuler").click(function(e) {
            e.preventDefault();

            // 1) Bouton Annuler en mode chargement
            setAnnulerLoading("Annulation...");

            // 2) Stopper le polling
            if (interv) {
                clearInterval(interv);
                interv = null;
            }

            // 3) Abandonner toutes les requêtes AJAX en cours
            $(xhr).each(function(i, xh) {
                try { xh.abort(); } catch (err) { /* noop */ }
            });
            xhr = [];
            REF = '';

            // 4) Remettre le bouton Payer à son état initial
            resetPayerButton();

            // 5) Message d'annulation (sans spinner)
            typeMessage("Paiement annulé par l'utilisateur.", 'error');

            // 6) Remettre le bouton Annuler à son état normal
            setTimeout(function() {
                resetAnnulerButton();
            }, 2500);
        });

        // ============================================================
        // ===== CLIC SUR LE BOUTON PAYER =============================
        // ✅ Spinner + texte de chargement conservés sur le bouton
        // ✅ Aucun spinner dans le message
        // ============================================================
        $("#btn_payer").click(async function(e) {
            e.preventDefault();
            var btn = $(this);
            var factureId = $('#facture_id').val();

            $('.form-group input:not([readonly]), .form-group select').removeClass('error');
            clearMessage();

            // ===== VÉRIF 1 : facture présente =====
            if (!factureId || factureId == 0 || factureId === "0") {
                showMessage('Facture introuvable.', 'error');
                return;
            }

            // ===== VÉRIF 2 : appel check_paie_facture =====
            // ✅ Bouton Payer : spinner + texte "Vérification..."
            setPayerLoading("Vérification...");
            showMessage('Vérification de la facture en cours...', 'processing');

            var response;
            try {
                response = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
            } catch (err) {
                console.error('Erreur check_paie_facture:', err);
                resetPayerButton();
                showMessage('Erreur de connexion lors de la vérification de la facture. Veuillez réessayer.', 'error');
                return;
            }

            if (response == 1) {
                showMessage('Cette facture a déjà été payée.', 'error');
                resetPayerButton();
                return;
            } else if (response != 0 && response != "" && response !== undefined && response !== null) {
                if (typeof response === 'string' && response.length > 0 && isNaN(response) === false && response != 0) {
                    showMessage('Cette facture a déjà été payée.', 'error');
                    resetPayerButton();
                    return;
                }
            }

            // ===== VÉRIF 3 : mode de paiement =====
            var mode_paiement = $('#mode_paiement').val();
            var devise_texte = $('#devise').val();
            var montant_payer = $('#montant_payer').val();

            if (!mode_paiement) {
                $('#mode_paiement').addClass('error');
                showMessage('Veuillez sélectionner un mode de paiement.', 'error');
                resetPayerButton();
                return;
            }

            // ===== VÉRIF 4 : montant obligatoire =====
            if (!montant_payer || !montant_payer.trim()) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Veuillez saisir le montant à payer.', 'error');
                resetPayerButton();
                return;
            }

            // ===== VÉRIF 5 : montant numérique =====
            var montant_numerique = convertirEnNombre(montant_payer);
            if (isNaN(montant_numerique)) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Le montant saisi est invalide.', 'error');
                resetPayerButton();
                return;
            }

            // ===== VÉRIF 6 : montant > 0 =====
            if (montant_numerique <= 0) {
                $('#montant_payer').addClass('error').focus();
                showMessage('Le montant doit être supérieur à zéro.', 'error');
                resetPayerButton();
                return;
            }

            // ===== VÉRIF 7 : devise =====
            if (!devise_texte) {
                $('#devise').addClass('error');
                showMessage('Veuillez choisir une devise (USD ou CDF).', 'error');
                resetPayerButton();
                return;
            }

            // ===== VÉRIF 8 : montant ≤ facture =====
            var rawCdf = $('#cdf_montant').val();
            var rawUsd = $('#usd_montant').val();
            var cdf_numerique = convertirEnNombre(rawCdf);
            var usd_numerique = convertirEnNombre(rawUsd);

            if (devise_texte === 'USD') {
                if (montant_numerique > usd_numerique) {
                    $('#montant_payer').addClass('error').focus();
                    showMessage('Le montant saisi dépasse le montant USD de la facture (' + rawUsd + ' USD).', 'error');
                    resetPayerButton();
                    return;
                }
            } else if (devise_texte === 'CDF') {
                if (montant_numerique > cdf_numerique) {
                    $('#montant_payer').addClass('error').focus();
                    showMessage('Le montant saisi dépasse le montant CDF de la facture (' + rawCdf + ' CDF).', 'error');
                    resetPayerButton();
                    return;
                }
            }

            var montant_a_envoyer = montant_numerique;
            var devise_code = devise_texte;

            // ===== VÉRIF 9 : champs spécifiques au mode =====
            var mobile_money = "";
            var numero_compte = "";
            var nom_titulaire = "";

            if (mode_paiement === 'mobile_money') {
                var numero_mobile = $('#numero_mobile').val();

                if (!numero_mobile || !numero_mobile.trim()) {
                    $('#numero_mobile').addClass('error').focus();
                    showMessage('Veuillez saisir votre numéro Mobile Money.', 'error');
                    resetPayerButton();
                    return;
                }

                var numero_normalise = normaliserNumeroMobile(numero_mobile);

                if (!numero_normalise) {
                    $('#numero_mobile').addClass('error').focus();
                    showMessage('Numéro Mobile Money invalide. Utilisez le format +243XXXXXXXXX (ex: +243812345678).', 'error');
                    resetPayerButton();
                    return;
                }

                $('#numero_mobile').val(numero_normalise);
                mobile_money = numero_normalise;

            } else if (mode_paiement === 'bank') {
                numero_compte = $('#numero_compte').val();
                nom_titulaire = $('#nom_titulaire').val();

                if (!numero_compte.trim()) {
                    $('#numero_compte').addClass('error').focus();
                    showMessage('Veuillez saisir votre numéro de compte bancaire.', 'error');
                    resetPayerButton();
                    return;
                }
                if (!nom_titulaire.trim()) {
                    $('#nom_titulaire').addClass('error').focus();
                    showMessage('Veuillez saisir le nom du titulaire du compte.', 'error');
                    resetPayerButton();
                    return;
                }
                if (numero_compte.length < 10) {
                    $('#numero_compte').addClass('error').focus();
                    showMessage('Numéro de compte trop court (min. 10 caractères).', 'error');
                    resetPayerButton();
                    return;
                }
                mobile_money = numero_compte;
            }

            // ===== POST vers save_paiement_facture_1 =====
            $("#v_mode_abonnement").html(1);

            // ✅ Bouton Payer : spinner + texte "Traitement..."
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
                    try {
                        r = (typeof rep === 'string') ? JSON.parse(rep) : rep;
                    } catch (err) {
                        console.error('Réponse non-JSON:', rep);
                        resetPayerButton();
                        showMessage('Réponse invalide du serveur. Veuillez réessayer.', 'error');
                        return;
                    }

                    if (r && r.success) {
                        // ✅ Le bouton Payer reste en mode chargement pendant le polling
                        typeMessage(
                            r.message || 'Transaction initialisée avec succès. Veuillez saisir votre Pin Mobile Money pour confirmer la transaction.',
                            'success'
                        );
                        clearInterval(interv);
                        REF = r.myref;
                        interv = setInterval(callback, 3000);

                        // Le bouton Annuler reste visible et actif pendant le polling
                        resetAnnulerButton();
                    } else {
                        resetPayerButton();
                        resetAnnulerButton();
                        typeMessage(
                            (r && r.message) ? r.message : 'Erreur lors de l\'initialisation du paiement.',
                            'error'
                        );
                    }
                },
                error: function(xhrErr) {
                    console.error('Erreur AJAX save_paiement_facture_1:', xhrErr);
                    resetPayerButton();
                    resetAnnulerButton();
                    var errMsg = 'Erreur de connexion au serveur. Veuillez réessayer.';
                    if (xhrErr.status === 419) errMsg = 'Session expirée. Veuillez recharger la page.';
                    if (xhrErr.status === 500) errMsg = 'Erreur interne du serveur. Veuillez réessayer plus tard.';
                    if (xhrErr.status === 404) errMsg = 'Service de paiement introuvable.';
                    showMessage(errMsg, 'error');
                }
            });
        });
    </script>

</body>
</html>
