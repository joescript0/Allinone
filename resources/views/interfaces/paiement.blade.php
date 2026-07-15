<?php
$nom_app_1 = "AFRICTECHAPP";
$nom_app_2 = "ILAINAPP";
$nom_app_3 = "CONTROLAPP";
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
    <link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}">
    <title>AFRICTECHAPP - PAIEMENT</title>
    <style>
        /* --- Vos styles existants (inchangés) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0a192f; min-height: 100vh; display: flex; flex-direction: column; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header { background: #800020 !important; padding: 12px 20px; border-bottom: 3px solid #6c757d !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header__logo h1 { margin: 0; font-size: 1.2rem; }
        .header__logo h1 a { color: white; text-decoration: none; }
        .header__logo h1 i { color: #d4af37; margin-right: 8px; }
        .header__logo p { font-size: 0.65rem; color: rgba(255,255,255,0.9); margin-top: 3px; }
        .login { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px 15px; }
        .login form { width: 100%; max-width: 480px; background: white; border-radius: 20px; padding: 30px 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .login form h5 { font-size: 1.6rem; font-weight: 600; color: #1a1a2e; margin-bottom: 20px; text-align: center; position: relative; padding-bottom: 12px; }
        .login form h5:after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 50px; height: 3px; background: #800020; border-radius: 2px; }
        .amounts-container { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
        .amount-card { flex: 1; background: #f0f4f8; border-radius: 12px; padding: 8px 5px; text-align: center; border-left: 4px solid #800020; display: flex; align-items: center; justify-content: center; gap: 4px; transition: transform 0.2s ease; }
        .amount-icon { font-size: 1.2rem; }
        .amount-card h4 { font-size: 0.7rem; font-weight: bold; margin: 0; word-break: break-word; white-space: normal; line-height: 1.2; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #1a1a2e; font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; }
        .form-group label i { color: #2c3e50; margin-right: 8px; font-size: 1rem; vertical-align: middle; }
        .form-group input, .form-group select { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 0.9rem; background: #f8f9fa; font-weight: 500; }
        .form-group select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232c3e50' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 15px center; background-size: 16px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #2c3e50; background: white; box-shadow: 0 0 0 3px rgba(44,62,80,0.1); }
        .dynamic-field { display: none; animation: slideDown 0.4s ease-out; }
        .dynamic-field.show { display: block; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .btn-login { width: 100%; background: #800020 !important; color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; margin-top: 10px; }
        .btn-login:hover { background: #5a0017 !important; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(128,0,32,0.3); }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        #msg { display: none !important; text-align: center; margin-top: 20px; padding: 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; animation: fadeIn 0.3s ease; }
        #msg.show { display: block !important; }
        #msg i { margin-right: 8px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        #footer { background: #800020 !important; padding: 12px 20px; border-top: 3px solid #6c757d !important; text-align: center; color: white; font-size: 0.7rem; }
        @media (max-width: 600px) { .login form { padding: 20px 18px; } .login form h5 { font-size: 1.4rem; } .amounts-container { gap: 10px; } .amount-card { padding: 6px 4px; gap: 3px; } .amount-icon { font-size: 1.1rem; } .amount-card h4 { font-size: 0.75rem; } .form-group input, .form-group select { padding: 10px 12px; font-size: 0.85rem; } .btn-login { padding: 12px; font-size: 0.9rem; } }
        @media (max-width: 480px) { .amounts-container { flex-direction: column; } .amount-card { width: 100%; justify-content: center; } .login { padding: 15px 10px; } }
        @media (min-width: 601px) and (max-width: 1024px) { .login form { max-width: 450px; padding: 35px 30px; } }
        @media (max-height: 600px) { .login { padding: 10px; } .login form { padding: 15px 20px; } .form-group { margin-bottom: 12px; } }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a192f; }
        ::-webkit-scrollbar-thumb { background: #800020; border-radius: 4px; }
    </style>
</head>
<body data-ma-theme="blue">
<header class="header">
    <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>
    <div class="header__logo hidden-sm-down">
        <h1><a href="#"><i style="color:#0a192f;" class="zmdi zmdi-home"></i> {{ $nom_app_3 }}</a></h1>
        <p>PAIEMENT MOBILE</p>
    </div>
</header>

<div class="login">
    <form id="form_paiement" method="POST">
        @csrf
        <h5>Effectuer un paiement</h5>

        <input type="hidden" id="cdf_montant" name="cdf_montant" value="{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }}">
        <input type="hidden" id="usd_montant" name="usd_montant" value="{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }}">
        <input type="hidden" id="facture_id" name="facture_id" value="{{ base64_decode($facture_id) ?? '' }}">

        <div class="amounts-container">
            <div class="amount-card"><span class="amount-icon">💰</span><h4>{{ number_format(abs(base64_decode($cdf_montant)), 2, ',', ' ') }} CDF</h4></div>
            <div class="amount-card"><span class="amount-icon">💵</span><h4>{{ number_format(abs(base64_decode($usd_montant)), 2, ',', ' ') }} USD</h4></div>
        </div>

        <div class="form-group">
            <label><i class="zmdi zmdi-money-box"></i> Mode de paiement</label>
            <select id="mode_paiement" name="mode_paiement">
                <option value="mobile_money" selected>📱 Mobile Money</option>
                <option value="bank">🏦 Virement bancaire</option>
            </select>
        </div>

        <div id="devise_field" class="dynamic-field">
            <div class="form-group">
                <label><i class="zmdi zmdi-money"></i> Devise de paiement</label>
                <select id="devise" name="devise_select">
                    <option value="">-- Choisissez la devise --</option>
                    <option value="USD">💵 USD (Dollar américain)</option>
                    <option value="CDF">💰 CDF (Franc congolais)</option>
                </select>
            </div>
        </div>

        <div id="mobile_money_field" class="dynamic-field">
            <div class="form-group">
                <label><i class="zmdi zmdi-smartphone-android"></i> Numéro Mobile Money</label>
                <input type="tel" id="numero_mobile" name="numero_mobile" placeholder="Ex: 0812345678" autocomplete="off">
            </div>
        </div>

        <div id="bank_field" class="dynamic-field">
            <div class="form-group">
                <label><i class="zmdi zmdi-card"></i> Numéro de compte bancaire</label>
                <input type="text" id="numero_compte" name="numero_compte" placeholder="Numéro complet du compte" autocomplete="off">
            </div>
            <div class="form-group">
                <label><i class="zmdi zmdi-account"></i> Nom du titulaire du compte</label>
                <input type="text" id="nom_titulaire" name="nom_titulaire" placeholder="Nom complet" autocomplete="off">
            </div>
        </div>

        <button class="btn-login" id="btn_payer" type="button"><i class="zmdi zmdi-money"></i> Payer</button>
        <div id="msg"></div>
    </form>
</div>

<div id="footer">{{ $nom_app_3 }} © 2026 - Paiement sécurisé</div>

<script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
<script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('./assets/js/app.min.js') }}"></script>

<script>
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
        $('.login form').css('opacity', '0');
        setTimeout(function() { $('.login form').css('opacity', '1'); }, 100);
    });

    function showMessage(message, type) {
        var icon = type === 'error' ? 'zmdi-close-circle' : 'zmdi-check-circle';
        var color = type === 'error' ? '#800020' : '#28a745';
        var bgColor = type === 'error' ? '#ffe6e6' : '#e6ffe6';
        $('#msg').html('<i class="zmdi ' + icon + '"></i> ' + message);
        $('#msg').css({color: color, background: bgColor}).addClass('show');
        setTimeout(function() {
            $('#msg').html('').removeClass('show').css('background', '');
        }, 5000);
    }

    function processPayment(mode, devise_texte, montant, devise_code, extra, btn) {
        var montantAffichage = (devise_texte === 'USD') ? montant + ' USD' : montant + ' CDF';
        $('#msg').html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Paiement de ' + montantAffichage + ' en cours...');
        $('#msg').css({color: '#0066cc', background: '#e6f3ff'}).addClass('show');

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
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                if (response.success) {
                    showMessage('Paiement réussi ! ');
                } else {
                    showMessage('Erreur : ' + (response.message || 'Paiement refusé'), 'error');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                console.error(xhr);
                showMessage('Erreur de connexion. Veuillez réessayer.', 'error');
            }
        });
    }

    $("#btn_payer").click(async function(e) {
        e.preventDefault();
        var btn = $(this);
        var factureId = $('#facture_id').val();

        // Vérification facture introuvable
        if (!factureId || factureId == 0 || factureId === "0") {
            showMessage('Facture introuvable', 'error');
            return;
        }

        // Désactiver le bouton et afficher le chargement
        btn.prop('disabled', true).html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Vérification...');

        // Vérifier si la facture est déjà payée
        try {
            const response = await $.get("{{ url('/check_paie_facture') }}", { facture_id: factureId });
            if (response == 1) {
                showMessage('Facture déjà payée', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
        } catch (err) {
            console.error(err);
            showMessage('Erreur lors de la vérification de la facture', 'error');
            btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
            return;
        }

        // --- Validations des champs (mode, devise, montants) ---
        var mode_paiement = $('#mode_paiement').val();
        var devise_texte = $('#devise').val();

        if (!mode_paiement) {
            showMessage('Veuillez sélectionner un mode de paiement', 'error');
            btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
            return;
        }
        if (!devise_texte) {
            showMessage('Choisissez une devise (USD ou CDF)', 'error');
            btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
            return;
        }

        var rawCdf = $('#cdf_montant').val();
        var rawUsd = $('#usd_montant').val();
        var cdf_numerique = convertirEnNombre(rawCdf);
        var usd_numerique = convertirEnNombre(rawUsd);

        if (isNaN(cdf_numerique) || cdf_numerique <= 0 || isNaN(usd_numerique) || usd_numerique <= 0) {
            showMessage('Les montants CDF et USD doivent être supérieurs à zéro', 'error');
            btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
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
            btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
            return;
        }

        var extraData = {};
        if (mode_paiement === 'mobile_money') {
            var numero_mobile = $('#numero_mobile').val();
            if (!numero_mobile.trim()) {
                showMessage('Veuillez saisir votre numéro Mobile Money', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
            var mobileRegex = /^(0[1-9][0-9]{8}|[1-9][0-9]{8})$/;
            if (!mobileRegex.test(numero_mobile)) {
                showMessage('Numéro Mobile Money invalide (ex: 0812345678)', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
            extraData = { numero_mobile: numero_mobile };
        } 
        else if (mode_paiement === 'bank') {
            var numero_compte = $('#numero_compte').val();
            var nom_titulaire = $('#nom_titulaire').val();
            if (!numero_compte.trim()) {
                showMessage('Veuillez saisir votre numéro de compte', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
            if (!nom_titulaire.trim()) {
                showMessage('Veuillez saisir le nom du titulaire', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
            if (numero_compte.length < 10) {
                showMessage('Numéro de compte trop court (min. 10 caractères)', 'error');
                btn.prop('disabled', false).html('<i class="zmdi zmdi-money"></i> Payer');
                return;
            }
            extraData = { numero_compte: numero_compte, nom_titulaire: nom_titulaire };
        }

        // Mettre à jour le texte du bouton pour indiquer le paiement
        btn.html('<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Paiement en cours...');
        processPayment(mode_paiement, devise_texte, montant_a_envoyer, devise_code, extraData, btn);
    });
</script>
</body>
</html>