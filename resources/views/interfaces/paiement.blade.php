@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
<?php
// ID de la facture à payer (doit être défini par votre logique métier)
$facture_id = $facture_id ?? 123; // Remplacez par la vraie variable
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

    <!-- Google Fonts + Font Awesome (style moderne) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}">
    <title>{{ $nom_app }} - PAIEMENT</title>

    <style>
        /* ===== STYLE MODERNE (identique à la page login) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .logo h1 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .logo a {
            text-decoration: none;
            color: #0F172A;
        }

        .logo a i {
            color: #3B82F6;
            margin-right: 6px;
        }

        .logo p {
            font-size: 0.7rem;
            color: #64748B;
            letter-spacing: 1px;
        }

        #footer {
            padding: 1rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            font-size: 0.7rem;
            color: #64748B;
        }

        .login {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

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

        .amounts-container {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

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

        .amount-card:hover {
            transform: scale(1.02);
        }

        .amount-icon {
            font-size: 1.4rem;
            color: #3B82F6;
        }

        .amount-card h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
            word-break: break-word;
            color: #1E293B;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #334155;
        }

        .form-group label i {
            margin-right: 8px;
            color: #3B82F6;
            width: 18px;
        }

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

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }

        .dynamic-field {
            display: none;
            animation: slideDown 0.4s ease-out;
        }

        .dynamic-field.show {
            display: block;
        }

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

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login i {
            font-size: 1rem;
        }

        #msg {
            margin-top: 1.5rem;
            padding: 0.75rem;
            border-radius: 16px;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            display: none;
        }

        #msg.show {
            display: flex;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .login-container { padding: 1.5rem; }
            .login-container h5 { font-size: 1.5rem; }
            .amounts-container { flex-direction: column; }
            .amount-card { width: 100%; }
        }

        @media (max-width: 480px) {
            .login { padding: 1rem; }
            .login-container { padding: 1.2rem; border-radius: 24px; }
        }
    </style>
</head>
<body>

    <!-- ===== HEADER MODERNE ===== -->
    <header class="header">
        <div class="logo">
            <h1><a href="#"><i class="fas fa-cubes"></i> {{ $nom_app }}</a></h1>
            <p><strong>PAIEMENT MOBILE</strong></p>
        </div>
    </header>

    <!-- ===== FORMULAIRE ===== -->
    <div class="login">
        <div class="login-container">
            <h5>Effectuer un paiement</h5>

            <form id="form_paiement" method="POST">
                @csrf

                <input type="hidden" id="cdf_montant" name="cdf_montant" value="{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }}">
                <input type="hidden" id="usd_montant" name="usd_montant" value="{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }}">
                <input type="hidden" id="facture_id" name="facture_id" value="{{ base64_decode($facture_id) ?? '' }}">

                <div class="amounts-container">
                    <div class="amount-card">
                        <span class="amount-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h4>{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }} CDF</h4>
                    </div>
                    <div class="amount-card">
                        <span class="amount-icon"><i class="fas fa-dollar-sign"></i></span>
                        <h4>{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }} USD</h4>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-credit-card"></i> Mode de paiement</label>
                    <select id="mode_paiement" name="mode_paiement">
                        <option value="mobile_money" selected><i class="fas fa-mobile-alt"></i> Mobile Money</option>
                        <option value="bank"><i class="fas fa-university"></i> Virement bancaire</option>
                    </select>
                </div>

                <div id="devise_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-exchange-alt"></i> Devise de paiement</label>
                        <select id="devise" name="devise_select">
                            <option value="">-- Choisissez la devise --</option>
                            <option value="USD">💵 USD (Dollar américain)</option>
                            <option value="CDF">💰 CDF (Franc congolais)</option>
                        </select>
                    </div>
                </div>

                <div id="mobile_money_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-mobile-alt"></i> Numéro Mobile Money</label>
                        <input type="tel" id="numero_mobile" name="numero_mobile" placeholder="Ex: 0812345678" autocomplete="off">
                    </div>
                </div>

                <div id="bank_field" class="dynamic-field">
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Numéro de compte bancaire</label>
                        <input type="text" id="numero_compte" name="numero_compte" placeholder="Numéro complet du compte" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nom du titulaire du compte</label>
                        <input type="text" id="nom_titulaire" name="nom_titulaire" placeholder="Nom complet" autocomplete="off">
                    </div>
                </div>

                <button class="btn-login" id="btn_payer" type="button">
                    <i class="fas fa-hand-holding-usd"></i> Payer
                </button>
                <div id="msg"></div>
            </form>
        </div>
    </div>

    <!-- ===== FOOTER MODERNE ===== -->
    <div id="footer">{{ $nom_app }} © 2026 - Paiement sécurisé</div>

    <!-- ===== SCRIPTS ===== -->
    <script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('./assets/js/app.min.js') }}"></script>

    <script>
        // ===== LOGIQUE MÉTIER (inchangée) =====
        function convertirEnNombre(valeur) {
            if (!valeur && valeur !== 0) return NaN;
            let str = String(valeur).trim();
            str = str.replace(/\s/g, '');
            str = str.replace(',', '.');
            return parseFloat(str);
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
            $('#msg').html('').removeClass('show');
        }

        $('#mode_paiement').change(function() { toggleFields(); });

        $(document).ready(function() {
            toggleFields();
        });

        function showMessage(message, type) {
            var icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
            var color = type === 'error' ? '#EF4444' : '#10B981';
            var bgColor = type === 'error' ? '#FEF2F2' : '#ECFDF5';
            $('#msg').html('<i class="fas ' + icon + '"></i> ' + message);
            $('#msg').css({color: color, background: bgColor}).addClass('show');
            setTimeout(function() {
                $('#msg').html('').removeClass('show').css('background', '');
            }, 5000);
        }

        function processPayment(mode, devise_texte, montant, devise_code, extra, btn) {
            var montantAffichage = (devise_texte === 'USD') ? montant + ' USD' : montant + ' CDF';
            $('#msg').html('<i class="fas fa-spinner fa-spin"></i> Paiement de ' + montantAffichage + ' en cours...');
            $('#msg').css({color: '#2563EB', background: '#EFF6FF'}).addClass('show');

            var postData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                mode_de_paiement: mode,
                devise_recu: devise_code,
                montant_recu: montant,
                facture_id: $('#facture_id').val(),
                ...extra
            };

            $.ajax({
                type: "POST",
                url: "/process_payment",
                data: postData,
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    if (response.success) {
                        showMessage('Paiement réussi ! ');
                    } else {
                        showMessage('Erreur : ' + (response.message || 'Paiement refusé'), 'error');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    console.error(xhr);
                    showMessage('Erreur de connexion. Veuillez réessayer.', 'error');
                }
            });
        }

        $("#btn_payer").click(async function(e) {
            e.preventDefault();
            var btn = $(this);
            var factureId = $('#facture_id').val();

            if (!factureId || factureId == 0 || factureId === "0") {
                showMessage('Facture introuvable', 'error');
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner"></span> Vérification...');

            try {
                const response = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
                if (response == 1) {
                    showMessage('Facture déjà payée', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
            } catch (err) {
                console.error(err);
                showMessage('Erreur lors de la vérification de la facture', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                return;
            }

            var mode_paiement = $('#mode_paiement').val();
            var devise_texte = $('#devise').val();

            if (!mode_paiement) {
                showMessage('Veuillez sélectionner un mode de paiement', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                return;
            }
            if (!devise_texte) {
                showMessage('Choisissez une devise (USD ou CDF)', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                return;
            }

            var rawCdf = $('#cdf_montant').val();
            var rawUsd = $('#usd_montant').val();
            var cdf_numerique = convertirEnNombre(rawCdf);
            var usd_numerique = convertirEnNombre(rawUsd);

            if (isNaN(cdf_numerique) || cdf_numerique <= 0 || isNaN(usd_numerique) || usd_numerique <= 0) {
                showMessage('Les montants CDF et USD doivent être supérieurs à zéro', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                return;
            }

            var montant_a_envoyer = null;
            var devise_code = '';

            if (devise_texte === 'USD') {
                montant_a_envoyer = usd_numerique;
                devise_code = '0';
            } else if (devise_texte === 'CDF') {
                montant_a_envoyer = cdf_numerique;
                devise_code = '1';
            } else {
                showMessage('Devise non reconnue', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                return;
            }

            var extraData = {};
            if (mode_paiement === 'mobile_money') {
                var numero_mobile = $('#numero_mobile').val();
                if (!numero_mobile.trim()) {
                    showMessage('Veuillez saisir votre numéro Mobile Money', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
                var mobileRegex = /^(0[1-9][0-9]{8}|[1-9][0-9]{8})$/;
                if (!mobileRegex.test(numero_mobile)) {
                    showMessage('Numéro Mobile Money invalide (ex: 0812345678)', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
                extraData = { numero_mobile: numero_mobile };
            }
            else if (mode_paiement === 'bank') {
                var numero_compte = $('#numero_compte').val();
                var nom_titulaire = $('#nom_titulaire').val();
                if (!numero_compte.trim()) {
                    showMessage('Veuillez saisir votre numéro de compte', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
                if (!nom_titulaire.trim()) {
                    showMessage('Veuillez saisir le nom du titulaire', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
                if (numero_compte.length < 10) {
                    showMessage('Numéro de compte trop court (min. 10 caractères)', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-hand-holding-usd"></i> Payer');
                    return;
                }
                extraData = { numero_compte: numero_compte, nom_titulaire: nom_titulaire };
            }

            btn.html('<span class="spinner"></span> Paiement en cours...');
            processPayment(mode_paiement, devise_texte, montant_a_envoyer, devise_code, extraData, btn);
        });
    </script>

</body>
</html>
