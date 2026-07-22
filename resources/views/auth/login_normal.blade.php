<?php
    use App\Models\appnames;
    $nom_app  = appnames::where('etat',  1)->first()["nom"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $nom_app }} - AUTHENTIFICATION</title>

    <!-- Google Fonts + Font Awesome -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Vendor styles d'origine -->
    <link rel="stylesheet"
        href="{{ asset('./assets/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('./assets/vendors/fullcalendar/fullcalendar.min.css') }}">
    <link rel="icon" type="image/png"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233B82F6'%3E%3Cpath d='M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z'/%3E%3C/svg%3E" />
    <meta name="description" content="{{ $nom_app }} : Gestion des activités." />
    <meta property="og:image" content="{{ asset('controlapp_1.png') }}" />
    <meta property="og:description" content="{{ $nom_app }} : Gestion des activités." />
    <meta property="og:url" content="{{ url('') }}" />
    <meta property="og:title" content="Authentification - Utilisateurs" />
    <meta name="theme-color" content="#000000">

    <link rel="manifest" href="{{ asset('/manifest-admin.json') }}">

    <style>
        /* ===== STYLE MODERNE (votre code CSS inchangé) ===== */
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
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .form-side {
            padding: 2.5rem;
        }

        .form-side h5 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 1.5rem;
            text-align: center;
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

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid #CBD5E1;
            border-radius: 16px;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: white;
        }

        .input-wrapper .validation-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
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

        .btn-login i {
            font-size: 1rem;
        }

        .field-error {
            font-size: 0.75rem;
            color: #EF4444;
            margin-top: 0.25rem;
            display: none;
        }

        .field-error.show {
            display: block;
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
        }

        .illustration-side {
            background: linear-gradient(145deg, #EFF6FF, #F8FAFC);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
        }

        .illustration-side i {
            font-size: 7rem;
            color: #3B82F6;
            margin-bottom: 1.5rem;
        }

        .illustration-side h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .illustration-side {
                display: none;
            }

            .form-side {
                padding: 1.5rem;
            }

            .header,
            #footer {
                padding: 0.75rem 1rem;
            }

            .logo h1 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .form-side h5 {
                font-size: 1.5rem;
            }

            .btn-login {
                padding: 0.7rem;
            }
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
            to {
                transform: rotate(360deg);
            }
        }

        /* ===== MODAL PWA ===== */
        #pwa-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        #pwa-modal {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 25px;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
            position: relative;
        }

        #pwa-modal .logo-app {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 10px;
            display: inline-block;
        }

        #pwa-modal h2 {
            font-size: 22px;
            margin: 0 0 10px 0;
            color: #1a1a1a;
            font-family: Arial, sans-serif;
        }

        #pwa-modal p {
            font-size: 16px;
            color: #555;
            margin: 0 0 20px 0;
            line-height: 1.5;
            font-family: Arial, sans-serif;
        }

        #pwa-modal .btn-install {
            background: #000000;
            color: #ffffff;
            border: none;
            padding: 14px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
        }

        #pwa-modal .btn-install:hover {
            background: #333333;
        }

        #pwa-modal .btn-later {
            background: #7f1a1a;
            color: #ffffff;
            border: none;
            padding: 14px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
            font-family: Arial, sans-serif;
        }

        #pwa-modal .btn-later:hover {
            background: #a02424;
        }

        #pwa-modal .btn-close-modal {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            color: #999;
            cursor: pointer;
            padding: 0 8px;
        }

        #pwa-modal .btn-close-modal:hover {
            color: #333;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>

<body>

    <!-- ===== HEADER ===== -->
    <header class="header">
        <div class="logo">
            <h1><a href="#"><i class="fas fa-cubes"></i> {{ $nom_app }}</a></h1>
            <p><strong>ALL IN ONE</strong></p>
        </div>
    </header>

    <!-- ===== LOGIN ===== -->
    <div class="login">
        <div class="login-container">
            <div class="form-side">
                <h5>Authentification</h5>
                <form id="form_login" method="POST">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> E-mail</label>
                        <div class="input-wrapper">
                            <input type="email" id="email_01" name="email_01" placeholder="Exemple@gmail.com"
                                autocomplete="email">
                            <span class="validation-icon" id="email-icon"></span>
                        </div>
                        <div class="field-error" id="email-error">L'email est obligatoire et doit être valide.</div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Mot de passe</label>
                        <div class="input-wrapper">
                            <input type="password" id="mdp_01" name="mdp_01" placeholder="Votre mot de passe">
                            <span class="validation-icon" id="pwd-icon"></span>
                        </div>
                        <div class="field-error" id="pwd-error">Le mot de passe ne peut pas être vide.</div>
                    </div>

                    <button class="btn-login" id="btn_login" type="button">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>

                    <div id="msg"></div>
                </form>
            </div>

            <!-- ===== ILLUSTRATION AVEC ICÔNE ERP (fa-cubes) ===== -->
            <div class="illustration-side">
                <i class="fas fa-cubes"></i>
                <h3>Accès protégé</h3>
            </div>
        </div>
    </div>

    <!-- ===== MODAL PWA ===== -->
    <div id="pwa-modal-overlay">
        <div id="pwa-modal">
            <button class="btn-close-modal" id="pwa-modal-close">✕</button>
            <img src="{{ asset('controlapp_1.png') }}" alt="Logo" class="logo-app">
            <h2>Installer l'application</h2>
            <p>
                Installez cette application sur votre téléphone, ordinateur pour un accès rapide et hors ligne.
            </p>
            <button class="btn-install" id="pwa-install-btn"><i class="fas fa-download"></i> Installer maintenant</button>
            <button class="btn-later" id="pwa-later-btn"><i class="fas fa-times-circle"></i> Installer plus tard</button>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div id="footer">{{ $nom_app }} © 2026</div>

    <!-- ===== SCRIPTS ===== -->
    <script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('./assets/js/app.min.js') }}"></script>

    <script>
        // ===== LOGIQUE D'AUTHENTIFICATION (inchangée) =====
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
                                            if (rep == 0) {
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

        // ===== NOUVEAU : DÉTECTION DE LA TOUCHE ENTREE =====
        $('#email_01, #mdp_01').on('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                $('#btn_login').click();
            }
        });

        // ===== SERVICE WORKER & PWA (inchangé) =====
        (function() {
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('✅ Application déjà installée.');
                return;
            }
            if (sessionStorage.getItem('pwa_install_later')) {
                console.log('⏳ L\'utilisateur a choisi "plus tard" pour cette session.');
                return;
            }
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js', {
                        scope: '/'
                    })
                    .then(() => console.log('✅ Service Worker enregistré'))
                    .catch(err => console.error('❌ Erreur SW :', err));
            }

            const overlay = document.getElementById('pwa-modal-overlay');
            const installBtn = document.getElementById('pwa-install-btn');
            const laterBtn = document.getElementById('pwa-later-btn');
            const closeBtn = document.getElementById('pwa-modal-close');
            let deferredPrompt;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                console.log('📱 PWA installable détectée');
                overlay.style.display = 'flex';
            });

            installBtn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(outcome === 'accepted' ? '✅ Installation acceptée' : '❌ Installation refusée');
                    deferredPrompt = null;
                    overlay.style.display = 'none';
                }
            });

            laterBtn.addEventListener('click', () => {
                overlay.style.display = 'none';
                deferredPrompt = null;
                sessionStorage.setItem('pwa_install_later', 'true');
                console.log('⏳ L\'utilisateur a choisi "plus tard"');
            });

            closeBtn.addEventListener('click', () => {
                overlay.style.display = 'none';
                deferredPrompt = null;
            });

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                    deferredPrompt = null;
                }
            });

            window.addEventListener('appinstalled', () => {
                console.log('✅ PWA installée');
                overlay.style.display = 'none';
            });
        })();
    </script>
</body>

</html>
