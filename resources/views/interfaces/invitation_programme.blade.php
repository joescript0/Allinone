<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Stone & Divayne</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Dancing+Script:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Petit+Formal+Script&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <meta name="description" content="Invitation de mariage" />
    <meta property="og:image" content="{{ asset('/photo_font.jpeg') }}" />
    <meta property="og:description" content="Invitation de mariage" />
    <meta property="og:url" content="{{ url('') }}" />
    <meta property="og:title" content="Stone & Divayne" />
    <style>
        /* --- Styles généraux inchangés --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1533167649158-6d5080b0e0e6?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #3b7ba5;
            background-blend-mode: multiply;
            font-family: 'Playfair Display', serif;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* --- Conteneur des fleurs flottantes --- */
        .flowers-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            overflow: hidden;
        }

        .floating-flower {
            position: absolute;
            font-size: 2.2rem;
            color: rgba(255, 255, 255, 0.7);
            animation: floatFlower 18s infinite linear;
            text-shadow: 0 0 20px rgba(255, 215, 215, 0.3);
            opacity: 0.7;
            will-change: transform;
            user-select: none;
        }

        @keyframes floatFlower {
            0% {
                transform: translateY(110vh) rotate(0deg) scale(0.8);
                opacity: 0.6;
            }
            10% {
                opacity: 0.9;
            }
            90% {
                opacity: 0.9;
            }
            100% {
                transform: translateY(-10vh) rotate(720deg) scale(1.2);
                opacity: 0.2;
            }
        }

        /* --- Hero image --- */
        .hero-image {
            width: calc(100% + 40px);
            max-width: none;
            margin: -20px -20px 20px -20px;
            position: relative;
            line-height: 0;
            overflow: hidden;
        }

        .hero-image img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .hero-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(to top, rgba(59, 123, 165, 0.9) 0%, rgba(59, 123, 165, 0) 100%);
            pointer-events: none;
        }

        .programme-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 620px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border-radius: 50px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .deco-rose {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at 30% 30%, #ffffff, #6a9fd6 40%, #1f4a6b 70%, transparent 70%);
            border-radius: 50%;
            box-shadow: 0 10px 20px rgba(31, 74, 107, 0.4);
            z-index: 4;
            opacity: 0.9;
            pointer-events: none;
        }

        .names {
            font-family: 'Great Vibes', cursive;
            font-size: 75px;
            color: #1f4a6b;
            line-height: 1;
            text-shadow: 3px 3px 8px rgba(255, 255, 255, 0.9);
            margin-bottom: 5px;
        }

        .subtitle {
            font-family: 'Dancing Script', cursive;
            font-size: 32px;
            color: #4a8bc2;
            margin-bottom: 35px;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .separator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 20px auto;
            width: 80%;
        }
        .separator .line {
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #6a9fd6, transparent);
        }
        .separator .heart {
            color: #6a9fd6;
            font-size: 22px;
        }

        .section-title {
            font-family: 'Petit Formal Script', cursive;
            font-size: 45px;
            color: #1f4a6b;
            margin: 25px 0 15px 0;
            letter-spacing: 2px;
        }

        .event-block {
            background: rgba(106, 159, 214, 0.15);
            border-radius: 30px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid rgba(106, 159, 214, 0.4);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .date {
            font-family: 'Dancing Script', cursive;
            font-size: 40px;
            font-weight: 700;
            color: #1f4a6b;
        }

        .event-info {
            font-size: 20px;
            color: #2c5a7a;
            margin-top: 5px;
            font-weight: 500;
        }

        .romantic-text {
            font-style: italic;
            font-size: 19px;
            color: #1f4a6b;
            line-height: 1.8;
            margin-bottom: 5px;
        }

        .welcome-icons {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .icon-circle {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #1f4a6b;
            text-decoration: none;
            transition: transform 0.3s ease;
            cursor: pointer;
            background: none;
            border: none;
            font-family: inherit;
        }

        .icon-circle:hover {
            transform: translateY(-5px);
        }

        .icon-circle:hover .icon {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 15px 30px rgba(31, 74, 107, 0.3);
        }

        .icon {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, #ffffff 0%, #8bb9e8 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin-bottom: 10px;
            border: 2px solid #6a9fd6;
            box-shadow: 0 8px 20px rgba(31, 74, 107, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .icon-circle span {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 14px;
            color: #1f4a6b;
        }

        .rsvp-section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px dashed #6a9fd6;
        }

        .rsvp-title {
            font-family: 'Great Vibes', cursive;
            font-size: 50px;
            color: #1f4a6b;
            margin-bottom: 5px;
        }

        .rsvp-date {
            font-family: 'Dancing Script', cursive;
            font-size: 26px;
            color: #4a8bc2;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #1f4a6b 0%, #6a9fd6 100%);
            color: white;
            padding: 18px 50px;
            border: none;
            border-radius: 60px;
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(31, 74, 107, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 2px;
        }

        .btn-confirm:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 40px rgba(31, 74, 107, 0.5);
        }

        /* --- STYLES COMMUNS AUX MODALES --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
            padding: 20px;
            animation: fadeIn 0.4s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .modal-content {
            position: relative;
            width: 100%;
            max-width: 750px;
            max-height: 90vh;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            border-radius: 40px;
            padding: 40px 30px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.8);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            0% { transform: translateY(40px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 32px;
            color: #1f4a6b;
            background: rgba(255,255,255,0.7);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .modal-close:hover {
            background: #1f4a6b;
            color: white;
            transform: rotate(90deg);
        }

        /* --- STYLES POUR LA MODALE PROGRAMME --- */
        .modal-content .names {
            font-size: 64px;
            margin-bottom: 0;
        }
        .modal-content .subtitle {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .modal-content .main-title {
            font-family: 'Petit Formal Script', cursive;
            font-size: 46px;
            color: #1f4a6b;
            margin: 5px 0 20px 0;
        }
        .modal-content .day-title {
            font-family: 'Dancing Script', cursive;
            font-size: 34px;
            font-weight: 700;
            color: #1f4a6b;
            margin: 25px 0 12px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .modal-content .day-title i {
            color: #4a8bc2;
            font-size: 28px;
        }
        .modal-content .event-block {
            background: rgba(106, 159, 214, 0.12);
            border-radius: 30px;
            padding: 20px 22px;
            margin-bottom: 16px;
            border: 1px solid rgba(106, 159, 214, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .modal-content .event-block:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(31, 74, 107, 0.12);
        }
        .modal-content .event-info {
            font-size: 22px;
            color: #2c5a7a;
            font-weight: 600;
        }
        .modal-content .event-info i {
            color: #4a8bc2;
            margin-right: 8px;
            width: 28px;
        }
        .modal-content .event-location {
            font-size: 18px;
            color: #3b7ba5;
            margin-top: 4px;
            font-weight: 500;
            font-style: italic;
        }
        .modal-content .event-location i {
            color: #4a8bc2;
            margin-right: 6px;
            width: 20px;
        }
        .modal-content .event-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
        }
        .modal-content .event-tags .tag {
            background: rgba(106, 159, 214, 0.15);
            border: 1px solid rgba(106, 159, 214, 0.25);
            border-radius: 40px;
            padding: 5px 18px;
            font-size: 14px;
            font-weight: 600;
            color: #1f4a6b;
            letter-spacing: 0.04em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .modal-content .event-tags .tag i {
            color: #4a8bc2;
            font-size: 13px;
        }
        .modal-content .footer-message {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px dashed rgba(106, 159, 214, 0.4);
            font-family: 'Dancing Script', cursive;
            font-size: 26px;
            color: #1f4a6b;
            letter-spacing: 1px;
        }
        .modal-content .footer-message i {
            color: #4a8bc2;
            margin: 0 8px;
        }

        /* --- STYLES SPÉCIFIQUES À LA MODALE CADEAUX --- */
        #giftModal .modal-content {
            max-width: 600px;
        }

        .gift-message {
            font-size: 17px;
            color: #1f4a6b;
            line-height: 1.9;
            text-align: center;
            margin-bottom: 28px;
            font-style: italic;
            background: rgba(106, 159, 214, 0.08);
            padding: 18px 20px;
            border-radius: 20px;
            border-left: 4px solid #6a9fd6;
            border-right: 4px solid #6a9fd6;
        }

        .gift-options {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 20px;
        }

        .gift-option {
            display: flex;
            align-items: center;
            gap: 18px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            padding: 16px 22px;
            border: 2px solid rgba(106, 159, 214, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease, background 0.3s ease;
            cursor: pointer;
            user-select: none;
        }

        .gift-option:hover {
            transform: translateX(6px);
            box-shadow: 0 8px 25px rgba(31, 74, 107, 0.1);
            border-color: #6a9fd6;
        }

        .gift-option.selected {
            border-color: #1f4a6b;
            background: rgba(106, 159, 214, 0.15);
            box-shadow: 0 0 0 3px rgba(106, 159, 214, 0.3);
            transform: translateX(6px);
        }

        .gift-icon-box {
            flex-shrink: 0;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #e8f0ff 0%, #c5ddf5 100%);
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            border: 1px solid rgba(106, 159, 214, 0.3);
        }

        .gift-text {
            flex: 1;
            text-align: left;
        }
        .gift-text h4 {
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            color: #1f4a6b;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .gift-text p {
            font-size: 15px;
            color: #3b6a8a;
            line-height: 1.5;
            margin: 0;
        }

        .btn-validate {
            background: linear-gradient(135deg, #1f4a6b 0%, #6a9fd6 100%);
            color: white;
            padding: 14px 35px;
            border: none;
            border-radius: 60px;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(31, 74, 107, 0.4);
            transition: opacity 0.4s ease, transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
            letter-spacing: 1px;
            margin-top: 10px;
            width: 100%;
            opacity: 0.5;
            pointer-events: none;
        }

        .btn-validate.active {
            opacity: 1;
            pointer-events: auto;
        }

        .btn-validate.active:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 35px rgba(31, 74, 107, 0.5);
        }

        .confirmation-message {
            display: none;
            text-align: center;
            padding: 20px 10px;
        }

        .confirmation-message .check-icon {
            font-size: 70px;
            color: #4caf50;
            margin-bottom: 10px;
        }

        .confirmation-message h3 {
            font-family: 'Great Vibes', cursive;
            font-size: 40px;
            color: #1f4a6b;
            margin-bottom: 8px;
        }

        .confirmation-message p {
            font-size: 18px;
            color: #2c5a7a;
            line-height: 1.6;
        }

        .confirmation-message .btn-close-confirm {
            margin-top: 20px;
            background: #1f4a6b;
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 60px;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 8px 20px rgba(31, 74, 107, 0.3);
        }

        .confirmation-message .btn-close-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(31, 74, 107, 0.4);
        }

        .gift-footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 20px;
            border-top: 1px solid rgba(106, 159, 214, 0.25);
        }

        .gift-footer .small-note {
            font-size: 14px;
            color: #5a7f9a;
            font-style: italic;
        }

        .gift-footer .small-note span {
            color: #1f4a6b;
            font-weight: 700;
        }

        /* --- STYLES MODALE CONTACT --- */
        #contactModal .modal-content {
            max-width: 500px;
            text-align: center;
        }

        .contact-heart {
            font-size: 50px;
            color: #e74c6f;
            margin-bottom: 10px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .contact-title {
            font-family: 'Great Vibes', cursive;
            font-size: 44px;
            color: #1f4a6b;
            margin-bottom: 5px;
        }

        .contact-sub {
            font-family: 'Dancing Script', cursive;
            font-size: 22px;
            color: #4a8bc2;
            margin-bottom: 25px;
        }

        .contact-numbers {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px 0 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            background: rgba(106, 159, 214, 0.10);
            border-radius: 20px;
            padding: 16px 22px;
            border: 1px solid rgba(106, 159, 214, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            animation: slideInItem 0.5s ease forwards;
            opacity: 0;
            transform: translateX(-20px);
            text-decoration: none;
            color: inherit;
        }

        .contact-item:nth-child(1) { animation-delay: 0.1s; }
        .contact-item:nth-child(2) { animation-delay: 0.25s; }

        @keyframes slideInItem {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .contact-item:hover {
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 8px 25px rgba(31, 74, 107, 0.12);
            background: rgba(106, 159, 214, 0.18);
            border-color: #6a9fd6;
        }

        .contact-item .icon-circle-small {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ffffff 0%, #8bb9e8 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            color: #1f4a6b;
            border: 2px solid #6a9fd6;
            flex-shrink: 0;
        }

        .contact-item .phone-number {
            font-size: 22px;
            font-weight: 700;
            color: #1f4a6b;
            letter-spacing: 1px;
            font-family: 'Playfair Display', serif;
        }

        .contact-item .phone-label {
            font-size: 14px;
            color: #4a8bc2;
            font-weight: 500;
            display: block;
            margin-top: 2px;
        }

        .contact-note {
            font-size: 16px;
            color: #3b6a8a;
            font-style: italic;
            margin-top: 10px;
            padding-top: 18px;
            border-top: 1px solid rgba(106, 159, 214, 0.2);
        }

        .contact-note i {
            color: #e74c6f;
        }

        /* --- MODALE LOCALISATION avec Leaflet --- */
        #localisationModal .modal-content {
            max-width: 750px;
        }

        #mapContainer {
            width: 100%;
            height: 380px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            margin: 18px 0;
            background: #e8edf2;
            border: 2px solid rgba(106, 159, 214, 0.3);
            position: relative;
        }

        #mapContainer .leaflet-control-attribution {
            font-size: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 4px;
            padding: 0 6px;
        }

        .loc-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 16px 0 8px;
        }

        .loc-btn {
            background: linear-gradient(135deg, #1f4a6b 0%, #6a9fd6 100%);
            color: white;
            border: none;
            border-radius: 60px;
            padding: 12px 22px;
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(31, 74, 107, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 1 0 auto;
            justify-content: center;
            min-width: 120px;
        }

        .loc-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(31, 74, 107, 0.35);
        }

        .loc-btn i {
            font-size: 17px;
        }

        .loc-btn-outline {
            background: transparent;
            border: 2px solid #6a9fd6;
            color: #1f4a6b;
            box-shadow: none;
        }

        .loc-btn-outline:hover {
            background: rgba(106, 159, 214, 0.1);
            box-shadow: 0 6px 18px rgba(31, 74, 107, 0.1);
        }

        .loc-btn.active-loc {
            background: #1f4a6b;
            border-color: #1f4a6b;
            color: white;
            box-shadow: 0 6px 20px rgba(31, 74, 107, 0.4);
            transform: scale(1.02);
        }

        .loc-note {
            font-size: 14px;
            color: #3b6a8a;
            text-align: center;
            font-style: italic;
            margin-top: 8px;
        }

        .loc-note i {
            color: #6a9fd6;
        }

        @media (max-width: 600px) {
            .loc-btn { min-width: 90px; padding: 10px 14px; font-size: 13px; }
            #mapContainer { height: 280px; }
        }

        @media (max-width: 400px) {
            #mapContainer { height: 220px; }
            .loc-btn { min-width: 70px; padding: 8px 10px; font-size: 12px; gap: 4px; }
        }

        /* --- Responsive modales --- */
        @media (max-width: 600px) {
            .modal-content {
                padding: 30px 16px;
                border-radius: 30px;
            }
            .modal-content .names { font-size: 48px; }
            .modal-content .subtitle { font-size: 22px; }
            .modal-content .main-title { font-size: 36px; }
            .modal-content .day-title { font-size: 28px; }
            .modal-content .event-info { font-size: 18px; }
            .modal-content .event-location { font-size: 16px; }
            .modal-content .event-tags .tag { font-size: 12px; padding: 4px 12px; }
            .modal-content .footer-message { font-size: 20px; }
            .modal-close {
                top: 10px;
                right: 12px;
                width: 40px;
                height: 40px;
                font-size: 26px;
            }
            #giftModal .modal-content { padding: 30px 16px; }
            .gift-option { padding: 12px 16px; gap: 14px; }
            .gift-icon-box { width: 46px; height: 46px; font-size: 22px; }
            .gift-text h4 { font-size: 17px; }
            .gift-text p { font-size: 13px; }
            .btn-validate { font-size: 16px; padding: 12px 20px; }
            .confirmation-message .check-icon { font-size: 50px; }
            .confirmation-message h3 { font-size: 32px; }

            #contactModal .modal-content { padding: 30px 16px; }
            .contact-title { font-size: 34px; }
            .contact-item { padding: 12px 16px; gap: 12px; flex-wrap: wrap; }
            .contact-item .phone-number { font-size: 18px; }
            .contact-item .icon-circle-small { width: 42px; height: 42px; font-size: 20px; }
        }

        @media (max-width: 400px) {
            .modal-content { padding: 28px 14px 24px; border-radius: 24px; }
            .modal-content .names { font-size: 38px; }
            .gift-option { flex-wrap: wrap; justify-content: center; text-align: center; }
            .gift-text { text-align: center; }
            .contact-item { flex-direction: column; text-align: center; }
        }

        @media (max-width: 480px) {
            .programme-card {
                padding: 30px 18px;
                border-radius: 30px;
            }
            .names {
                font-size: 50px;
            }
            .subtitle {
                font-size: 24px;
            }
            .section-title {
                font-size: 34px;
            }
            .date {
                font-size: 30px;
            }
            .event-info {
                font-size: 17px;
            }
            .icon {
                width: 60px;
                height: 60px;
                font-size: 26px;
            }
            .welcome-icons {
                gap: 15px;
            }
            .rsvp-title {
                font-size: 38px;
            }
            .btn-confirm {
                padding: 14px 30px;
                font-size: 17px;
            }
            .deco-rose {
                width: 60px;
                height: 60px;
                bottom: 10px;
                left: 10px;
            }
            .hero-image {
                width: calc(100% + 40px);
                margin: -20px -20px 20px -20px;
            }
            .hero-image::after {
                height: 50%;
            }
        }
    </style>
</head>
<body>

    <!-- Conteneur des fleurs flottantes -->
    <div class="flowers-container" id="flowersContainer"></div>

    <!-- Image avec dégradé -->
    <div class="hero-image">
        <img src="{{ asset('/photo_font.jpeg') }}" alt="Stone & Divayne - Mariage" />
    </div>

    <div class="programme-card">
        <div class="deco-rose"></div>
        <div class="names">Stone & Divayne</div>
        <div class="subtitle">se disent OUI</div>

        <div class="separator">
            <div class="line"></div>
            <span class="heart">❦</span>
            <div class="line"></div>
        </div>

        <div class="section-title">Il était une fois</div>

        <div class="event-block">
            <p class="romantic-text">
                Il était une fois deux âmes sœurs qui se sont trouvées. Aujourd'hui, notre frère Stone et notre soeur Divayne unissent leurs destins pour l'éternité. Que leur amour soit aussi pur que le ciel de Lubumbashi, et que leur bonheur soit infini.
            </p>
        </div>

        <div class="event-block">
            <div class="date">24 Septembre</div>
            <div class="event-info">💒 Cérémonie Civile</div>
            <div class="event-info" style="font-style: italic;">Jardin du Bonheur</div>
        </div>
        <div class="event-block">
            <div class="date">25 Septembre</div>
            <div class="event-info">💃 Bénédiction Nuptiale</div>
            <div class="event-info">et Soirée Dansante</div>
        </div>

        <div class="section-title">Bienvenue</div>
        <div class="welcome-icons">
            <button class="icon-circle" id="openProgramBtn">
                <div class="icon">✏️</div>
                <span>Programme</span>
            </button>
            <button class="icon-circle" id="openGiftBtn">
                <div class="icon">💝</div>
                <span>Cadeaux</span>
            </button>
            <button class="icon-circle" id="openLocalisationBtn">
                <div class="icon">📍</div>
                <span>Localisation</span>
            </button>
            <button class="icon-circle" id="openContactBtn">
                <div class="icon">📞</div>
                <span>Contact</span>
            </button>
        </div>

        <div class="rsvp-section">
            <div class="rsvp-title">R.S.V.P</div>
            <div class="event-info" style="font-style: italic; color: #1f4a6b;">Merci de confirmer votre présence</div>
            <div class="rsvp-date">Avant le 05 Septembre 2026</div>

            <a href="{{ route('invitation_formulaire') }}" class="btn-confirm">CONFIRMER MA PRÉSENCE</a>
        </div>
    </div>

    <!-- ========== MODALE PROGRAMME ========== -->
    <div class="modal-overlay" id="programModal">
        <div class="modal-content">
            <button class="modal-close" id="closeProgramBtn">
                <i class="fas fa-times"></i>
            </button>
            <div class="names">Stone & Divayne</div>
            <div class="subtitle">se disent OUI</div>
            <div class="separator">
                <div class="line"></div>
                <span class="heart">❦</span>
                <div class="line"></div>
            </div>
            <div class="main-title">✨ Programme ✨</div>
            <div class="day-title">
                <i class="fas fa-calendar-day"></i> 24 Septembre
            </div>
            <div class="event-block">
                <div class="event-info">
                    <i class="fas fa-ring"></i> Cérémonie Civile
                </div>
                <div class="event-location">
                    <i class="fas fa-location-dot"></i> JARDIN DU BONHEUR
                </div>
                <div class="event-location" style="font-size:16px; font-style:normal; margin-top:0; color:#2c5a7a;">
                    <i class="fas fa-map-pin"></i> MÉTÉO ARRÊT CABINE — AVENUE RUBI
                </div>
                <div class="event-tags">
                    <span class="tag"><i class="fas fa-clock"></i> 7h30</span>
                    <span class="tag"><i class="fas fa-users"></i> Arrivée de tout le monde</span>
                </div>
            </div>
            <div class="day-title">
                <i class="fas fa-calendar-day"></i> 25 Septembre
            </div>
            <div class="event-block">
                <div class="event-info">
                    <i class="fas fa-church"></i> Bénédiction Nuptiale a l'eglise man
                </div>
                <div class="event-location">
                    <i class="fas fa-location-dot"></i> Avenue Kapenda, coin Kimbangu
                </div>
                <div class="event-location" style="font-size:16px; font-style:normal; margin-top:0; color:#2c5a7a;">
                    <i class="fas fa-map-pin"></i> à côté de la rue Chen
                </div>
                <div class="event-tags">
                    <span class="tag"><i class="fas fa-clock"></i> 14h00</span>
                    <span class="tag"><i class="fas fa-candle"></i> Début du culte </span>
                </div>
            </div>
            <div class="event-block" style="border-color: #6a9fd6; background: rgba(106, 159, 214, 0.18);">
                <div class="event-info">
                    <i class="fas fa-music"></i> Soirée Dansante &amp; Réception
                </div>
                <div class="event-location">
                    <i class="fas fa-location-dot"></i> LE CHAPITEAU LA SHEKINAH · FAUSTIN MÉTÉO LA KATANGAISE
                </div>
                <div class="event-location" style="font-size:16px; font-style:normal; margin-top:0; color:#2c5a7a;">
                    <i class="fas fa-map-pin"></i> première parcelle à gauche
                </div>
                <div class="event-tags">
                    <span class="tag"><i class="fas fa-clock"></i> 18h00</span>
                    <span class="tag"><i class="fas fa-users"></i> Arrivée des invités</span>
                    <span class="tag"><i class="fas fa-glass-cheers"></i> Cocktail</span>
                    <span class="tag"><i class="fas fa-utensils"></i> Dîner</span>
                    <span class="tag"><i class="fas fa-dance"></i> Bal</span>
                    <span class="tag"><i class="fas fa-star"></i> Tenue de soirée</span>
                </div>
            </div>
            <div class="footer-message">
                <i class="fas fa-heart"></i> Dans l'attente de vous retrouver <i class="fas fa-heart"></i>
            </div>
        </div>
    </div>

    <!-- ========== MODALE CADEAUX ========== -->
    <div class="modal-overlay" id="giftModal">
        <div class="modal-content">
            <button class="modal-close" id="closeGiftBtn">
                <i class="fas fa-times"></i>
            </button>

            <div class="names" style="font-size: 52px;">Stone & Divayne</div>
            <div class="subtitle" style="font-size: 24px; margin-bottom: 10px;">se disent OUI</div>
            <div class="separator" style="margin: 10px auto 20px;">
                <div class="line"></div>
                <span class="heart">❦</span>
                <div class="line"></div>
            </div>

            <div id="giftSelection">
                <h2 style="font-family: 'Great Vibes', cursive; font-size: 42px; color: #1f4a6b; text-align: center; margin-bottom: 5px;">Cadeaux de mariage</h2>
                <p style="font-family: 'Dancing Script', cursive; font-size: 22px; color: #4a8bc2; text-align: center; margin-bottom: 20px; font-weight: 700;">Pour Stone &amp; Divayne</p>

                <div class="gift-message">
                    Votre présence est déjà le plus beau des cadeaux. Si vous souhaitez nous offrir un présent, choisissez ci‑dessous votre préférence.
                </div>

                <div class="gift-options">
                    <div class="gift-option" data-value="liquide">
                        <div class="gift-icon-box">💶</div>
                        <div class="gift-text">
                            <h4>Cadeau en liquide</h4>
                            <p>Une enveloppe ou un virement.</p>
                        </div>
                    </div>
                    <div class="gift-option" data-value="nature">
                        <div class="gift-icon-box">🎁</div>
                        <div class="gift-text">
                            <h4>Cadeau en nature</h4>
                            <p>Un objet de votre choix (non emballé de préférence).</p>
                        </div>
                    </div>
                </div>

                <button class="btn-validate" id="btnValidateGift" disabled>Valider mon choix</button>

                <div class="gift-footer">
                    <p class="small-note">
                        Pour toute question, contactez-nous au <span>+243 99 333 5178</span>
                    </p>
                </div>
            </div>

            <div class="confirmation-message" id="giftConfirmation">
                <div class="check-icon">✔️</div>
                <h3>Merci !</h3>
                <p>Votre choix a bien été enregistré.<br>Nous avons hâte de vous retrouver pour célébrer notre amour.</p>
                <button class="btn-close-confirm" id="btnCloseGiftConfirm">Fermer</button>
            </div>

        </div>
    </div>

    <!-- ========== MODALE CONTACT ========== -->
    <div class="modal-overlay" id="contactModal">
        <div class="modal-content">
            <button class="modal-close" id="closeContactBtn">
                <i class="fas fa-times"></i>
            </button>

            <div class="contact-heart">❤️</div>
            <div class="contact-title">Contactez-nous</div>
            <div class="contact-sub">Stone & Divayne</div>

            <div class="contact-numbers">
                <a href="tel:+243993335178" class="contact-item">
                    <div class="icon-circle-small">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <span class="phone-number">+243 99 333 5178</span>
                        <span class="phone-label">Stone</span>
                    </div>
                </a>
                <a href="tel:+243850533660" class="contact-item">
                    <div class="icon-circle-small">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <span class="phone-number">+243 850 533 660</span>
                        <span class="phone-label">Divayne</span>
                    </div>
                </a>
            </div>

            <div class="contact-note">
                <i class="fas fa-heart"></i> N'hésitez pas à nous appeler pour toute information <i class="fas fa-heart"></i>
            </div>
        </div>
    </div>

    <!-- ========== MODALE LOCALISATION avec Leaflet ========== -->
    <div class="modal-overlay" id="localisationModal">
        <div class="modal-content">
            <button class="modal-close" id="closeLocalisationBtn">
                <i class="fas fa-times"></i>
            </button>
            <div class="names" style="font-size: 48px;">Stone & Divayne</div>
            <div class="subtitle" style="font-size: 22px; margin-bottom: 5px;">Plan des lieux</div>
            <div class="separator" style="margin: 8px auto 12px;">
                <div class="line"></div>
                <span class="heart">❦</span>
                <div class="line"></div>
            </div>

            <div id="mapContainer"></div>

            <div class="loc-buttons">
                <button class="loc-btn" data-location="civile"><i class="fas fa-ring"></i> Civile</button>
                <button class="loc-btn" data-location="religieux"><i class="fas fa-church"></i> Religieux</button>
                <button class="loc-btn" data-location="soiree"><i class="fas fa-music"></i> Soirée</button>
                <button class="loc-btn loc-btn-outline" data-location="all"><i class="fas fa-map"></i> Tous</button>
            </div>

            <div class="loc-note">
                <i class="fas fa-location-dot" style="color:#1f4a6b;"></i> Cliquez sur un bouton pour zoomer sur le lieu
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        (function() {
            'use strict';

            /* ---- FLEURS FLOTTANTES ---- */
            const flowersContainer = document.getElementById('flowersContainer');
            const flowerEmojis = ['🌸', '🌼', '🌺', '🌻', '🌷', '🌹', '🌸', '🌸', '🌼', '🌺'];
            const numberOfFlowers = 22;

            function createFloatingFlowers() {
                for (let i = 0; i < numberOfFlowers; i++) {
                    const flower = document.createElement('div');
                    flower.classList.add('floating-flower');
                    const emoji = flowerEmojis[Math.floor(Math.random() * flowerEmojis.length)];
                    flower.textContent = emoji;

                    const left = Math.random() * 100;
                    flower.style.left = left + '%';

                    const size = 1.5 + Math.random() * 2.2;
                    flower.style.fontSize = size + 'rem';

                    const duration = 15 + Math.random() * 25;
                    flower.style.animationDuration = duration + 's';

                    const delay = Math.random() * 20;
                    flower.style.animationDelay = delay + 's';

                    flower.style.opacity = 0.3 + Math.random() * 0.5;

                    const rotation = Math.random() * 360;
                    flower.style.transform = `rotate(${rotation}deg)`;

                    flowersContainer.appendChild(flower);
                }
            }

            createFloatingFlowers();

            /* ---- Modale Programme ---- */
            const programModal = document.getElementById('programModal');
            const openProgramBtn = document.getElementById('openProgramBtn');
            const closeProgramBtn = document.getElementById('closeProgramBtn');

            function openProgram() {
                programModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeProgram() {
                programModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            openProgramBtn.addEventListener('click', openProgram);
            closeProgramBtn.addEventListener('click', closeProgram);
            programModal.addEventListener('click', function(e) {
                if (e.target === programModal) closeProgram();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && programModal.classList.contains('active')) closeProgram();
            });

            /* ---- Modale Cadeaux ---- */
            const giftModal = document.getElementById('giftModal');
            const openGiftBtn = document.getElementById('openGiftBtn');
            const closeGiftBtn = document.getElementById('closeGiftBtn');
            const giftSelection = document.getElementById('giftSelection');
            const giftConfirmation = document.getElementById('giftConfirmation');
            const giftOptions = document.querySelectorAll('.gift-option');
            const btnValidate = document.getElementById('btnValidateGift');
            const btnCloseConfirm = document.getElementById('btnCloseGiftConfirm');

            let selectedValue = null;

            function openGift() {
                giftModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                resetGiftModal();
            }

            function closeGift() {
                giftModal.classList.remove('active');
                document.body.style.overflow = '';
                resetGiftModal();
            }

            function resetGiftModal() {
                giftSelection.style.display = 'block';
                giftConfirmation.style.display = 'none';
                giftOptions.forEach(opt => opt.classList.remove('selected'));
                selectedValue = null;
                btnValidate.disabled = true;
                btnValidate.classList.remove('active');
            }

            giftOptions.forEach(option => {
                option.addEventListener('click', function() {
                    if (giftConfirmation.style.display === 'block') return;
                    giftOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedValue = this.dataset.value;
                    btnValidate.disabled = false;
                    btnValidate.classList.add('active');
                });
            });

            btnValidate.addEventListener('click', function() {
                if (!selectedValue) return;
                giftSelection.style.display = 'none';
                giftConfirmation.style.display = 'block';
            });

            btnCloseConfirm.addEventListener('click', closeGift);

            openGiftBtn.addEventListener('click', openGift);
            closeGiftBtn.addEventListener('click', closeGift);

            giftModal.addEventListener('click', function(e) {
                if (e.target === giftModal) closeGift();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && giftModal.classList.contains('active')) closeGift();
            });

            /* ---- Modale Contact ---- */
            const contactModal = document.getElementById('contactModal');
            const openContactBtn = document.getElementById('openContactBtn');
            const closeContactBtn = document.getElementById('closeContactBtn');

            function openContact() {
                contactModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeContact() {
                contactModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            openContactBtn.addEventListener('click', openContact);
            closeContactBtn.addEventListener('click', closeContact);

            contactModal.addEventListener('click', function(e) {
                if (e.target === contactModal) closeContact();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && contactModal.classList.contains('active')) closeContact();
            });

            /* ---- MODALE LOCALISATION avec Leaflet ---- */
            const localisationModal = document.getElementById('localisationModal');
            const openLocalisationBtn = document.getElementById('openLocalisationBtn');
            const closeLocalisationBtn = document.getElementById('closeLocalisationBtn');
            const mapContainer = document.getElementById('mapContainer');
            const locButtons = document.querySelectorAll('.loc-btn');

            let map = null;
            let markers = [];
            let mapInitialized = false;

            const locations = {
                civile: {
                    lat: -11.6478,
                    lng: 27.4790,
                    label: 'JARDIN DU BONHEUR · Avenue Rubi',
                    icon: 'ring'
                },
                religieux: {
                    lat: -11.6600,
                    lng: 27.4808,
                    label: 'Avenue Kapenda, coin Kimbangu',
                    icon: 'church'
                },
                soiree: {
                    lat: -11.6328,
                    lng: 27.4316,
                    label: 'SHEKINAH · Faustin Météo La Katangaise',
                    icon: 'music'
                }
            };

            const centerLubumbashi = { lat: -11.6645, lng: 27.4800 };

            function initMap() {
                if (mapInitialized) return;
                
                map = L.map('mapContainer', {
                    center: [centerLubumbashi.lat, centerLubumbashi.lng],
                    zoom: 14,
                    zoomControl: true,
                    fadeAnimation: true,
                    attributionControl: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | Team Lefleat',
                    maxZoom: 19,
                    minZoom: 10
                }).addTo(map);

                addMarkers();
                mapInitialized = true;

                setTimeout(() => {
                    if (map) map.invalidateSize();
                }, 300);
            }

            function addMarkers() {
                markers.forEach(m => map.removeLayer(m));
                markers = [];

                const iconColors = {
                    civile: '#1f4a6b',
                    religieux: '#6a9fd6',
                    soiree: '#4a8bc2'
                };

                Object.keys(locations).forEach(key => {
                    const loc = locations[key];
                    const color = iconColors[key] || '#1f4a6b';
                    
                    const customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color:${color}; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:3px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.3); font-size:14px; color:white;">${key === 'civile' ? '💒' : key === 'religieux' ? '⛪' : '🎵'}</div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                        popupAnchor: [0, -20]
                    });

                    const marker = L.marker([loc.lat, loc.lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`<strong>${loc.label}</strong>`);
                    
                    markers.push(marker);
                });
            }

            function zoomToLocation(locationKey) {
                if (!map) return;

                if (locationKey === 'all') {
                    map.flyTo([centerLubumbashi.lat, centerLubumbashi.lng], 13, {
                        duration: 1.2,
                        easeLinearity: 0.25
                    });
                    markers.forEach(m => m.closePopup());
                    setTimeout(() => {
                        markers.forEach(m => m.openPopup());
                    }, 500);
                    return;
                }

                const loc = locations[locationKey];
                if (!loc) return;

                map.flyTo([loc.lat, loc.lng], 17, {
                    duration: 1.2,
                    easeLinearity: 0.25
                });

                markers.forEach((m, index) => {
                    const keys = Object.keys(locations);
                    if (keys[index] === locationKey) {
                        setTimeout(() => {
                            m.openPopup();
                        }, 600);
                    } else {
                        m.closePopup();
                    }
                });
            }

            function openLocalisation() {
                localisationModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                if (!mapInitialized) {
                    setTimeout(() => {
                        initMap();
                    }, 100);
                } else {
                    zoomToLocation('all');
                    updateButtons('all');
                }
            }

            function closeLocalisation() {
                localisationModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            function updateButtons(activeLocation) {
                locButtons.forEach(btn => {
                    btn.classList.remove('loc-btn-outline', 'active-loc');
                    if (btn.dataset.location === activeLocation) {
                        btn.classList.add('active-loc');
                    }
                });
                if (activeLocation === 'all') {
                    document.querySelector('.loc-btn[data-location="all"]')?.classList.add('loc-btn-outline');
                }
            }

            openLocalisationBtn.addEventListener('click', openLocalisation);
            closeLocalisationBtn.addEventListener('click', closeLocalisation);

            localisationModal.addEventListener('click', function(e) {
                if (e.target === localisationModal) closeLocalisation();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && localisationModal.classList.contains('active')) closeLocalisation();
            });

            locButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const location = this.dataset.location;
                    if (!location) return;
                    
                    zoomToLocation(location);
                    updateButtons(location);
                });
            });

            const closeBtn = document.getElementById('closeLocalisationBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeLocalisation);
            }

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.target.classList.contains('active') && map) {
                        setTimeout(() => {
                            if (map) map.invalidateSize();
                        }, 300);
                    }
                });
            });

            observer.observe(localisationModal, { attributes: true, attributeFilter: ['class'] });

        })();
        document.addEventListener('DOMContentLoaded', function() {
            const audio = new Audio("{{ asset('/stone_et_divayne.aac') }}");
            audio.loop = true;
            audio.volume = 0.5;
        
            function demarrer() {
                audio.play().catch(() => {});
            }
        
            // On tente de jouer tout de suite
            demarrer();
        
            // Si la lecture a échoué, on prépare un déclencheur au premier geste
            // (mais on ne le fait que si l'audio n'a pas déjà commencé)
            let aJoue = false;
            audio.addEventListener('play', () => { aJoue = true; });
        
            function demarrerAuGestuel() {
                if (!aJoue) {
                    audio.play().catch(() => {});
                }
                // On retire les écouteurs après le premier geste
                document.removeEventListener('touchstart', demarrerAuGestuel);
                document.removeEventListener('click', demarrerAuGestuel);
                document.removeEventListener('scroll', demarrerAuGestuel);
                document.removeEventListener('keydown', demarrerAuGestuel);
            }
        
            // On attache les écouteurs, mais ils ne feront rien si l'audio tourne déjà
            document.addEventListener('touchstart', demarrerAuGestuel, { once: true });
            document.addEventListener('click', demarrerAuGestuel, { once: true });
            document.addEventListener('scroll', demarrerAuGestuel, { once: true });
            document.addEventListener('keydown', demarrerAuGestuel, { once: true });
        });
    </script>

</body>
</html>