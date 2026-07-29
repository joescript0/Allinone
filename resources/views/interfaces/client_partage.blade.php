<?php
use App\Models\appnames;
use Illuminate\Support\Facades\Auth;

$nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
$user = Auth::user();

// Récupération du client (transmis par le contrôleur)
$clientNom   = $client['nom'] ?? 'Client';
$clientAdr   = $client['adresse'] ?? '';
$clientLat   = $client['lat'] ?? -4.4419;
$clientLng   = $client['lng'] ?? 15.2663;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $nom_app }} - POSITION PARTAGÉE - {{ $clientNom }}</title>

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
    <meta name="description" content="{{ $nom_app }} : Position partagée de {{ $clientNom }}." />
    <meta property="og:image" content="{{ asset('controlapp_1.png') }}" />
    <meta property="og:description" content="Consultez la position de {{ $clientNom }} sur la carte." />
    <meta property="og:url" content="{{ url()->current() }}" />
    <!-- Modification ici : ajout de $nom_app -->
    <meta property="og:title" content="{{ $nom_app }} - Position partagée - {{ $clientNom }}" />
    <meta name="theme-color" content="#000000">

    <link rel="manifest" href="{{ asset('/manifest-admin.json') }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ===== STYLES (strictement identiques à l'authentification) ===== */
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

        .user-info-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: #E2E8F0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #1E293B;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-dropdown-trigger {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #1E293B;
            padding: 4px 10px;
            border-radius: 30px;
            background: rgba(0,0,0,0.03);
            transition: 0.2s;
            cursor: pointer;
        }

        .user-dropdown-trigger:hover {
            background: rgba(0,0,0,0.06);
        }

        .dropdown-menu {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .dropdown-menu .dropdown-item {
            font-weight: 500;
            color: #1E293B;
        }

        .dropdown-menu .dropdown-item i {
            margin-right: 8px;
        }

        .online-dot {
            width: 8px;
            height: 8px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(46,204,113,0.5); }
            70% { box-shadow: 0 0 0 3px rgba(46,204,113,0); }
            100% { box-shadow: 0 0 0 0 rgba(46,204,113,0); }
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

        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .breadcrumb {
            font-size: 0.85rem;
            color: #64748B;
            margin-bottom: 1.5rem;
        }

        .breadcrumb i {
            margin: 0 6px;
        }

        .card-partage {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #E2E8F0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header h4 {
            font-weight: 700;
            color: #0F172A;
            margin: 0;
        }

        .card-header i {
            font-size: 2rem;
            color: #3B82F6;
        }

        .client-info p {
            font-size: 0.95rem;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1e2a3e;
            font-weight: 500;
        }

        .client-info p strong {
            color: #0a192f;
            font-weight: 700;
            min-width: 100px;
        }

        .client-info i {
            color: #3B82F6;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        #sharedMap {
            width: 100%;
            height: 350px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: #e9ecef;
            margin-top: 0.5rem;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .action-buttons .btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px !important;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 40px !important;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            white-space: nowrap;
            line-height: 1.5;
            color: white !important;
        }

        .btn-google {
            background: #3B82F6 !important;
        }
        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
            background: #2563eb !important;
        }
        .btn-osm {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }
        .btn-osm:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #059669, #047857) !important;
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.3);
        }
        .btn-share {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        }
        .btn-share:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.3);
        }
        .btn-copy {
            background: #8B5CF6 !important;
        }
        .btn-copy:hover {
            transform: translateY(-2px);
            background: #7C3AED !important;
            box-shadow: 0 8px 18px rgba(139, 92, 246, 0.3);
        }

        .footer-partage {
            margin-top: 1.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .footer-partage i {
            color: #3B82F6;
            margin-right: 6px;
        }

        .alert-custom {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            background: #fee2e2;
            color: #991b1b;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .toast-copy {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #0a192f;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);
            opacity: 0;
            transition: all 0.4s ease;
            z-index: 9999;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .toast-copy.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast-copy i {
            margin-right: 8px;
            color: #10b981;
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
            }
            .main-content {
                padding: 1rem;
            }
            .card-partage {
                padding: 1rem;
            }
            .client-info p {
                font-size: 0.85rem;
                flex-wrap: wrap;
            }
            .client-info p strong {
                min-width: 80px;
            }
            .action-buttons .btn {
                padding: 6px 16px !important;
                font-size: 0.75rem;
            }
            #sharedMap {
                height: 250px;
            }
        }

        @media (max-width: 480px) {
            .card-header h4 {
                font-size: 1.2rem;
            }
            .client-info p {
                font-size: 0.75rem;
            }
            .action-buttons .btn {
                font-size: 0.7rem;
                padding: 4px 12px !important;
            }
            #sharedMap {
                height: 200px;
            }
        }
    </style>
</head>

<body>

    <!-- ===== HEADER (identique à l'authentification) ===== -->
    <header class="header">
        <div class="logo">
            <h1><a href="{{ url('/') }}"><i class="fas fa-cubes"></i> {{ $nom_app }}</a></h1>
            <p><strong>ALL IN ONE</strong></p>
        </div>
        <div class="user-info-header">
            @if($user)
                <div class="dropdown">
                    <a href="#" class="user-dropdown-trigger" data-toggle="dropdown">
                        <div class="user-avatar">
                            <i class="zmdi zmdi-account"></i>
                        </div>
                        <span class="user-name">{{ $user->name }}</span>
                        <span class="online-dot"></span>
                        <i class="zmdi zmdi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" data-toggle="modal" data-target="#deconnexion" href="#">
                            <i class="zmdi zmdi-power"></i> Quitter
                        </a>
                    </div>
                </div>
            @else
                <div class="user-dropdown-trigger" style="cursor: default;">
                    <div class="user-avatar">
                        <i class="zmdi zmdi-account"></i>
                    </div>
                    <span class="user-name">Invité</span>
                    <span class="online-dot" style="background-color: #94a3b8;"></span>
                </div>
            @endif
        </div>
    </header>

    <!-- ===== CONTENU PRINCIPAL ===== -->
    <section class="main-content">
        <div class="breadcrumb">
            {{ $user ? strtoupper($user->name) : 'INVITÉ' }}
            <i class="zmdi zmdi-chevron-right"></i>
            Position partagée
        </div>

        <div class="card-partage">
            <div class="card-header">
                <i class="zmdi zmdi-pin"></i>
                <h4>Détails du client partagé</h4>
            </div>

            @if(isset($client) && is_array($client))
                <div class="row">
                    <div class="col-md-6">
                        <div class="client-info">
                            <p><i class="zmdi zmdi-account"></i> <strong>Nom :</strong> {{ $client['nom'] ?? 'N/A' }}</p>
                            <p><i class="zmdi zmdi-map"></i> <strong>Adresse :</strong> {{ $client['adresse'] ?? 'N/A' }}</p>
                            <p><i class="zmdi zmdi-phone"></i> <strong>Téléphone :</strong> {{ $client['phone'] ?? 'N/A' }}</p>
                            <p><i class="zmdi zmdi-email"></i> <strong>Email :</strong> {{ $client['email'] ?? 'N/A' }}</p>
                            <p><i class="zmdi zmdi-pin"></i> <strong>Coordonnées :</strong> {{ $client['lat'] ?? 'N/A' }}, {{ $client['lng'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="sharedMap"></div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="https://www.google.com/maps?q={{ $client['lat'] ?? '' }},{{ $client['lng'] ?? '' }}"
                       target="_blank" class="btn btn-google">
                        <i class="zmdi zmdi-open-in-browser"></i> Google Maps
                    </a>
                    <a href="https://www.openstreetmap.org/?mlat={{ $client['lat'] ?? '' }}&mlon={{ $client['lng'] ?? '' }}&zoom=15"
                       target="_blank" class="btn btn-osm">
                        <i class="zmdi zmdi-map"></i> OpenStreetMap
                    </a>
                    <button type="button" class="btn btn-share" id="btnShareAgain">
                        <i class="zmdi zmdi-share"></i> Repartager via WhatsApp
                    </button>
                    <button type="button" class="btn btn-copy" id="btnCopyLink">
                        <i class="zmdi zmdi-copy"></i> Copier le lien
                    </button>
                </div>
            @else
                <div class="alert-custom">
                    <i class="zmdi zmdi-alert-circle"></i> Aucune donnée valide n'a été trouvée.
                </div>
            @endif

            <div class="footer-partage">
                <i class="zmdi zmdi-share"></i> Position partagée via le système de gestion
            </div>
        </div>
    </section>

    <!-- ===== TOAST ===== -->
    <div id="toastCopy" class="toast-copy">
        <i class="zmdi zmdi-check-circle"></i> Lien copié dans le presse-papier !
    </div>

    <!-- ===== FOOTER ===== -->
    <div id="footer">{{ $nom_app }} © {{ date('Y') }}</div>

    <!-- ===== SCRIPTS ===== -->
    <script src="{{ asset('./assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/popper.js/popper.min.js') }}"></script>
    <script src="{{ asset('./assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('./assets/js/app.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @if(isset($client) && is_array($client))
    <script>
        $(document).ready(function() {
            // ===== CARTE LEAFLET =====
            var lat = parseFloat("{{ $client['lat'] ?? -4.4419 }}");
            var lng = parseFloat("{{ $client['lng'] ?? 15.2663 }}");

            var map = L.map('sharedMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup('<strong>{{ $client["nom"] ?? "Client" }}</strong><br>{{ $client["adresse"] ?? "" }}').openPopup();

            // ===== DONNÉES CLIENT =====
            var clientData = {
                nom: "{{ addslashes($client['nom'] ?? '') }}",
                adresse: "{{ addslashes($client['adresse'] ?? '') }}",
                phone: "{{ addslashes($client['phone'] ?? '') }}",
                email: "{{ addslashes($client['email'] ?? '') }}",
                lat: "{{ $client['lat'] ?? '' }}",
                lng: "{{ $client['lng'] ?? '' }}"
            };

            // ===== REPARTAGER VIA WHATSAPP =====
            $('#btnShareAgain').on('click', function() {
                function utf8ToBase64(str) {
                    const encoder = new TextEncoder();
                    const uint8Array = encoder.encode(str);
                    let binaryString = '';
                    for (let i = 0; i < uint8Array.length; i++) {
                        binaryString += String.fromCharCode(uint8Array[i]);
                    }
                    return btoa(binaryString);
                }

                var jsonString = JSON.stringify({
                    nom: clientData.nom,
                    adresse: clientData.adresse,
                    phone: clientData.phone,
                    email: clientData.email,
                    lat: clientData.lat,
                    lng: clientData.lng
                });

                var encodedData = utf8ToBase64(jsonString);
                var shareUrl = "{{ route('client_partager') }}?data=" + encodeURIComponent(encodedData);

                var message = '*Position du client*\n\n' +
                            'Nom: ' + clientData.nom + '\n' +
                            'Adresse: ' + clientData.adresse + '\n' +
                            'Téléphone: ' + clientData.phone + '\n' +
                            'Email: ' + clientData.email + '\n' +
                            'Coordonnées: ' + clientData.lat + ', ' + clientData.lng + '\n\n' +
                            'Voir sur la carte: ' + shareUrl;

                var whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(message);
                window.open(whatsappUrl, '_blank');
            });

            // ===== COPIER LE LIEN =====
            $('#btnCopyLink').on('click', function() {
                var url = window.location.href;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function() {
                        showToast('Lien copié dans le presse-papier !');
                    }).catch(function() {
                        fallbackCopy(url);
                    });
                } else {
                    fallbackCopy(url);
                }
            });

            function fallbackCopy(text) {
                var textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast('Lien copié dans le presse-papier !');
                } catch (err) {
                    alert('Impossible de copier le lien. Veuillez le sélectionner manuellement : ' + text);
                }
                document.body.removeChild(textArea);
            }

            function showToast(message) {
                var toast = document.getElementById('toastCopy');
                toast.innerHTML = '<i class="zmdi zmdi-check-circle"></i> ' + message;
                toast.classList.add('show');
                setTimeout(function() {
                    toast.classList.remove('show');
                }, 3000);
            }
        });
    </script>
    @endif

</body>
</html>
