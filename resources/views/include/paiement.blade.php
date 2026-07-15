<?php
 $nom_app_1 = "AFRICTECHAPP";
 $nom_app_2 = "ILAINAPP";
 $nom_app_3 = "CONTROLAPP";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('./assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/fullcalendar/fullcalendar.min.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('connexion/images/icons/top_icone_1.ico') }}" />
    <title>AFRICTECHAPP - PAIEMENT</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fond bleu nuit profond */
        body {
            background: #0a192f;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Styles - Rouge bordeaux élégant */
        .header {
            background: #800020 !important;
            padding: 12px 20px;
            border-bottom: 3px solid #6c757d !important; /* Gris */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header__logo h1 {
            margin: 0;
            font-size: 1.2rem;
        }

        .header__logo h1 a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .header__logo h1 a:hover {
            opacity: 0.9;
        }

        .header__logo h1 i {
            color: #d4af37;
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .header__logo p {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 3px;
            letter-spacing: 1.5px;
        }

        /* Login Container */
        .login {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login form {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login form h5 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .login form h5:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #800020;
            border-radius: 2px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #1a1a2e;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .form-group label i {
            color: #2c3e50;
            margin-right: 8px;
            font-size: 1.1rem;
            vertical-align: middle;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            font-weight: 500;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2c3e50;
            background: white;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
        }

        .form-group input::placeholder {
            color: #adb5bd;
            font-weight: normal;
        }

        /* Button Styles */
        .btn-login {
            width: 100%;
            background: #800020 !important;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: #5a0017 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(128, 0, 32, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Message Styles - Caché par défaut */
        #msg {
            display: none !important;
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            animation: fadeIn 0.3s ease;
        }

        #msg.show {
            display: block !important;
        }

        #msg i {
            margin-right: 8px;
            font-size: 1.1rem;
            vertical-align: middle;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer Styles - Rouge bordeaux élégant */
        #footer {
            background: #800020 !important;
            padding: 12px 20px;
            border-top: 3px solid #6c757d !important; /* Gris */
            text-align: center;
            color: white;
            font-size: 0.7rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 10px 15px;
            }

            .header__logo h1 {
                font-size: 1rem;
            }

            .header__logo h1 i {
                font-size: 1rem;
            }

            .header__logo p {
                font-size: 0.6rem;
            }

            .login {
                padding: 20px 15px;
            }

            .login form {
                padding: 30px 25px;
                margin: 0 10px;
            }

            .login form h5 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }

            .form-group input {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .btn-login {
                padding: 10px;
                font-size: 0.95rem;
            }

            #footer {
                padding: 10px 15px;
                font-size: 0.65rem;
            }

            #msg {
                padding: 10px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 8px 12px;
            }

            .header__logo h1 {
                font-size: 0.9rem;
            }

            .header__logo h1 i {
                font-size: 0.9rem;
            }

            .header__logo p {
                font-size: 0.55rem;
            }

            .login form {
                padding: 25px 20px;
            }

            .login form h5 {
                font-size: 1.3rem;
            }

            .form-group label {
                font-size: 0.85rem;
            }

            .form-group input {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .btn-login {
                padding: 8px;
                font-size: 0.9rem;
            }

            #msg {
                padding: 8px;
                font-size: 0.8rem;
            }

            #footer {
                padding: 8px 12px;
                font-size: 0.6rem;
            }
        }

        /* Tablet Styles */
        @media (min-width: 769px) and (max-width: 1024px) {
            .login form {
                max-width: 450px;
                padding: 35px 30px;
            }
        }

        /* Small Height Screens */
        @media (max-height: 600px) {
            .login {
                padding: 20px;
            }

            .login form {
                padding: 25px 30px;
            }

            .form-group {
                margin-bottom: 15px;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a192f;
        }

        ::-webkit-scrollbar-thumb {
            background: #800020;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #5a0017;
        }
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
            <p>ALL IN ONE</p>
        </div>

        <ul class="top-nav"></ul>
    </header>

    <div class="login">
        <form id="form_login" action="" method="POST">
            @csrf
            <h5>Se connecter</h5>

            <div class="form-group">
                <label><i class="zmdi zmdi-email"></i> E-mail</label>
                <input type="text" id="email_01" name="email_01" placeholder="Exemple@gmail.com" autocomplete="off">
            </div>

            <div class="form-group">
                <label><i class="zmdi zmdi-lock"></i> Mot de passe</label>
                <input type="password" id="mdp_01" name="mdp_01" placeholder="Votre mot de passe">
            </div>

            <button class="btn-login" id="btn_login" type="button">Se connecter</button>

            <div id="msg"></div>
        </form>
    </div>

    <div id="footer">{{ $nom_app_3 }} © 2026</div>

    <!-- Javascript -->
    <script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('./assets/js/app.min.js') }}"></script>

    <script>
        $("#btn_login").click(function(e) {
            e.preventDefault();

            var email_01 = $('#email_01').val();
            if (email_01.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est obligatoire');
                $('#msg').css('color', "#800020");
                $('#msg').css('background', "#ffe6e6");
                $('#msg').addClass('show');
                setTimeout(() => {
                    $('#msg').html("");
                    $('#msg').removeClass('show');
                    $('#msg').css('background', "");
                }, 9000);
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if (!regex.test(email_01)) {
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est invalide');
                    $('#msg').css('color', "#800020");
                    $('#msg').css('background', "#ffe6e6");
                    $('#msg').addClass('show');
                    setTimeout(() => {
                        $('#msg').html("");
                        $('#msg').removeClass('show');
                        $('#msg').css('background', "");
                    }, 9000);
                } else {
                    var data = $("#form_login").serialize();
                    $.ajaxSetup({
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    });
                    $.ajax({
                        type: "POST",
                        url: "/check_email",
                        data: data,
                        success: function(response) {
                            if (response == 0) {
                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> L\'email est introuvable');
                                $('#msg').css('color', "#800020");
                                $('#msg').css('background', "#ffe6e6");
                                $('#msg').addClass('show');
                                setTimeout(() => {
                                    $('#msg').html("");
                                    $('#msg').removeClass('show');
                                    $('#msg').css('background', "");
                                }, 9000);
                            } else {
                                var mdp_01 = $('#mdp_01').val();
                                if (mdp_01.trim().length == 0) {
                                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez votre mot de passe');
                                    $('#msg').css('color', "#800020");
                                    $('#msg').css('background', "#ffe6e6");
                                    $('#msg').addClass('show');
                                    setTimeout(() => {
                                        $('#msg').html("");
                                        $('#msg').removeClass('show');
                                        $('#msg').css('background', "");
                                    }, 9000);
                                } else {
                                    $.ajaxSetup({
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    });
                                    $.ajax({
                                        type: "POST",
                                        url: "/check_mdp",
                                        data: data,
                                        success: function(rep) {
                                            if (rep == 0)
                                            {
                                                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Mot de passe introuvable');
                                                $('#msg').css('color', "#800020");
                                                $('#msg').css('background', "#ffe6e6");
                                                $('#msg').addClass('show');
                                            } else {
                                                $('#msg').html('<i class="zmdi zmdi-check-circle"></i> Connexion en cours...');
                                                $('#msg').css("color", '#28a745');
                                                $('#msg').css('background', "#e6ffe6");
                                                $('#msg').addClass('show');
                                                setTimeout(() => {
                                                    window.location.replace('/home');
                                                }, 1000);
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
</body>

</html>
