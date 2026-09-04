<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stone & Divayne</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Dancing+Script:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet" />
    <meta name="description" content="Invitation de mariage" />
    <meta property="og:image" content="{{ asset('/photo_font.jpeg') }}" />
    <meta property="og:description" content="Invitation de mariage" />
    <meta property="og:url" content="{{ url('') }}" />
    <meta property="og:title" content="Stone & Divayne" />
    <meta name="theme-color" content="#000000">
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(145deg, #d4e9ff 0%, #b5d6f5 35%, #8bb9e8 70%, #a8d0f0 100%);
            font-family: 'Playfair Display', serif;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* ===== NUAGES DOUX ET SOLEIL ===== */
        .sky-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            font-size: 3.6rem;
            opacity: 0.15;
            color: #f0f9ff;
            text-shadow: 0 0 60px rgba(255, 255, 255, 0.3);
            animation: driftCloud 40s ease-in-out infinite alternate;
        }
        .cloud:nth-child(odd) {
            animation-duration: 48s;
            font-size: 4.2rem;
            opacity: 0.12;
        }
        .cloud:nth-child(3n) {
            font-size: 3rem;
            opacity: 0.18;
        }

        @keyframes driftCloud {
            0% {
                transform: translate(-10%, 0) scale(1);
            }
            50% {
                transform: translate(10%, -5%) scale(1.05);
            }
            100% {
                transform: translate(-5%, 5%) scale(0.95);
            }
        }

        /* ===== FLEURS CIEL (bleues, blanches, jaune pâle) ===== */
        .floral-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floral-bg .flower {
            position: absolute;
            font-size: 2.4rem;
            opacity: 0.22;
            color: #c5e0ff;
            animation: floatFlower 20s ease-in-out infinite alternate;
            text-shadow: 0 0 40px rgba(160, 200, 240, 0.3);
        }
        .floral-bg .flower:nth-child(odd) {
            animation-duration: 24s;
            color: #e0f0ff;
        }
        .floral-bg .flower:nth-child(3n) {
            color: #f5faff;
            opacity: 0.18;
        }
        .floral-bg .flower:nth-child(5n+2) {
            font-size: 3rem;
            opacity: 0.12;
            color: #fffbde;
        }

        @keyframes floatFlower {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(30px, -40px) rotate(8deg) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) rotate(-6deg) scale(0.9);
            }
            100% {
                transform: translate(15px, -30px) rotate(4deg) scale(1.05);
            }
        }

        /* ===== PÉTALES VOLANTS (ciel) ===== */
        .petals-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .petal {
            position: absolute;
            font-size: 1.2rem;
            opacity: 0.2;
            color: #d4eaff;
            animation: fallPetal linear infinite;
            text-shadow: 0 0 20px rgba(180, 215, 255, 0.2);
            transform-origin: center;
        }
        @keyframes fallPetal {
            0% {
                transform: translateY(-10vh) rotate(0deg) scale(0.8);
                opacity: 0.05;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(110vh) rotate(720deg) scale(1.2);
                opacity: 0;
            }
        }

        /* ===== CARTE PRINCIPALE : fond transparent, joie ===== */
        .invitation-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 620px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 30px 20px 40px;
            border-radius: 60px 60px 40px 40px;
            background: transparent;
            backdrop-filter: none;
            box-shadow: none;
            border: none;
        }

        /* ===== ORNEMENT FLORAL SUPÉRIEUR (ciel) ===== */
        .floral-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            margin-bottom: 10px;
            font-size: 1.8rem;
            color: #8bb9e8;
            opacity: 0.8;
        }
        .floral-divider .line {
            height: 2px;
            flex: 1;
            max-width: 100px;
            background: linear-gradient(to right, transparent, #8bb9e8, transparent);
        }

        /* ===== NOMS ===== */
        .names-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.1;
            margin-bottom: 5px;
            position: relative;
        }

        .name {
            font-family: 'Great Vibes', cursive;
            font-size: 68px;
            color: #0a2942;
            text-shadow: 0 2px 20px rgba(10, 41, 66, 0.12);
            margin: 0;
            line-height: 1.2;
            letter-spacing: 2px;
        }

        .ampersand-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #1f4a6b;
            margin: -2px 0 0;
            line-height: 1;
            font-weight: 400;
            text-shadow: 0 2px 12px rgba(31, 74, 107, 0.10);
        }

        .name-ornament {
            font-size: 2rem;
            color: #8bb9e8;
            margin-top: -2px;
            margin-bottom: 8px;
            letter-spacing: 8px;
            opacity: 0.7;
        }

        /* ===== INDICATEUR CLIC ===== */
        .click-hint {
            font-family: 'Dancing Script', cursive;
            font-size: 24px;
            color: #0a2942;
            margin: 8px 0 24px;
            animation: pulseHint 2.4s ease-in-out infinite;
            background: rgba(255, 255, 255, 0.25);
            padding: 6px 28px;
            border-radius: 60px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px rgba(160, 200, 240, 0.15);
        }
        @keyframes pulseHint {
            0%,
            100% {
                opacity: 0.7;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
                color: #05213a;
                background: rgba(255, 255, 255, 0.45);
            }
        }

        /* ===== ENVELOPPE & ANIMATIONS ===== */
        .envelope-link {
            display: inline-block;
            text-decoration: none;
            position: relative;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.4s ease;
            filter: drop-shadow(0 20px 40px rgba(10, 41, 66, 0.12));
        }
        .envelope-link:hover {
            transform: translateY(-8px) scale(1.02);
            filter: drop-shadow(0 35px 60px rgba(10, 41, 66, 0.18));
        }
        .envelope-link:active {
            transform: scale(0.96);
        }

        /* Anneaux flottants dorés (soleil) */
        .floating-rings {
            position: absolute;
            inset: -30px;
            pointer-events: none;
        }
        .f-ring {
            position: absolute;
            font-size: 20px;
            color: #f9d56e;
            text-shadow: 0 0 20px rgba(249, 213, 110, 0.5);
            opacity: 0;
            animation: riseRing var(--dur) ease-in infinite;
        }
        @keyframes riseRing {
            0% {
                opacity: 0;
                transform: translateY(0) scale(0.4) rotate(0deg);
            }
            15% {
                opacity: 0.9;
                transform: translateY(-25px) scale(1.1) rotate(15deg);
            }
            100% {
                opacity: 0;
                transform: translateY(-120px) scale(0.6) rotate(-20deg);
            }
        }

        /* Flottement de l'enveloppe */
        .svg-envelope {
            width: 300px;
            height: auto;
            animation: floatEnvelope 5s ease-in-out infinite;
            display: block;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            padding: 4px;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        @keyframes floatEnvelope {
            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-12px) rotate(1.5deg);
            }
        }

        /* Animation de la photo des bagues */
        .rings-anim {
            animation: heartbeat 1.6s ease-in-out infinite;
            transform-origin: center;
            transform-box: fill-box;
        }
        @keyframes heartbeat {
            0%,
            100% {
                transform: scale(1);
            }
            14% {
                transform: scale(1.1);
            }
            28% {
                transform: scale(1);
            }
            42% {
                transform: scale(1.06);
            }
            56% {
                transform: scale(1);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .invitation-content {
                padding: 25px 20px 30px;
                border-radius: 40px;
            }
            .name {
                font-size: 56px;
            }
            .ampersand-title {
                font-size: 40px;
            }
            .svg-envelope {
                width: 260px;
            }
            .click-hint {
                font-size: 20px;
                padding: 5px 22px;
            }
        }

        @media (max-width: 480px) {
            .invitation-content {
                padding: 20px 16px 24px;
                border-radius: 30px;
            }
            .name {
                font-size: 44px;
            }
            .ampersand-title {
                font-size: 32px;
            }
            .name-ornament {
                font-size: 1.5rem;
                letter-spacing: 4px;
            }
            .click-hint {
                font-size: 17px;
                padding: 4px 18px;
                margin: 4px 0 18px;
            }
            .svg-envelope {
                width: 220px;
            }
            .floral-divider {
                font-size: 1.4rem;
                gap: 8px;
            }
            .floral-divider .line {
                max-width: 60px;
            }
            .floral-bg .flower {
                font-size: 1.8rem;
            }
            .petal {
                font-size: 0.9rem;
            }
            .cloud {
                font-size: 2.6rem !important;
            }
        }

        @media (max-width: 360px) {
            .invitation-content {
                padding: 16px 12px 20px;
            }
            .name {
                font-size: 36px;
            }
            .ampersand-title {
                font-size: 26px;
            }
            .svg-envelope {
                width: 180px;
            }
            .click-hint {
                font-size: 15px;
                padding: 3px 14px;
            }
            .floral-divider {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- NUAGES DOUX -->
    <div class="sky-bg" id="skyBg"></div>

    <!-- FLEURS CIEL -->
    <div class="floral-bg" id="floralBg"></div>

    <!-- PÉTALES VOLANTS -->
    <div class="petals-container" id="petalsContainer"></div>

    <!-- CONTENU PRINCIPAL : joie, ciel, transparence -->
    <div class="invitation-content">

        <!-- Ornement floral supérieur -->
        <div class="floral-divider">
            <span class="line"></span>
            <span>☁️</span>
            <span>🌤️</span>
            <span>☁️</span>
            <span class="line"></span>
        </div>

        <!-- NOMS -->
        <div class="names-block">
            <div class="name">Stone</div>
            <div class="ampersand-title">&</div>
            <div class="name">Divayne</div>
        </div>

        <!-- petit ornement floral sous les noms -->
        <div class="name-ornament">✧ ✧ ✧</div>

        <!-- Texte d'invitation -->
        <div class="click-hint">Cliquez pour accéder à l'invitation</div>

        <!-- Enveloppe avec la photo des bagues -->
        <a href="{{ route('invitation_programme') }}" class="envelope-link" aria-label="Ouvrir l'invitation">
            <div class="floating-rings" id="floatingRings"></div>

            <svg class="svg-envelope" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="envGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#9ac2e8" />
                        <stop offset="50%" stop-color="#6a9fd6" />
                        <stop offset="100%" stop-color="#4a7fb5" />
                    </linearGradient>
                    <linearGradient id="sealGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f0f9ff" />
                        <stop offset="50%" stop-color="#d4e9ff" />
                        <stop offset="100%" stop-color="#b5d6f5" />
                    </linearGradient>
                    <clipPath id="photoClip">
                        <circle cx="150" cy="100" r="36" />
                    </clipPath>
                </defs>

                <!-- Corps de l'enveloppe -->
                <rect x="10" y="10" width="280" height="180" rx="12" fill="url(#envGrad)" stroke="#1f4a6b" stroke-width="2" />

                <!-- Lignes des plis -->
                <path d="M 10 10 L 150 130 L 290 10" fill="none" stroke="#1f4a6b" stroke-width="2" opacity="0.3" />
                <path d="M 10 10 L 150 130 L 10 190" fill="none" stroke="#1f4a6b" stroke-width="2" opacity="0.3" />
                <path d="M 290 10 L 150 130 L 290 190" fill="none" stroke="#1f4a6b" stroke-width="2" opacity="0.3" />

                <!-- Sceau argenté / ciel -->
                <circle cx="150" cy="100" r="42" fill="url(#sealGrad)" stroke="#ffffff" stroke-width="4" style="filter: drop-shadow(0px 6px 18px rgba(26, 67, 113, 0.20));" />
                <circle cx="150" cy="100" r="38" fill="none" stroke="rgba(26, 67, 113, 0.2)" stroke-width="2" stroke-dasharray="5 5" />

                <!-- Photo des bagues -->
                <g class="rings-anim">
                    <image href="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=600&auto=format&fit=crop"
                    x="110" y="60" width="80" height="80"
                    preserveAspectRatio="xMidYMid slice"
                    clip-path="url(#photoClip)" />
                </g>
            </svg>
        </a>
    </div>

    <script>
        (function createSky() {
            const container = document.getElementById('skyBg');
            const clouds = ['☁️', '⛅', '☁️', '🌤️', '☁️', '⛅', '☁️', '🌤️'];
            for (let i = 0; i < 25; i++) {
                const el = document.createElement('span');
                el.className = 'cloud';
                el.textContent = clouds[i % clouds.length];
                el.style.left = Math.random() * 100 + '%';
                el.style.top = Math.random() * 100 + '%';
                el.style.animationDelay = (Math.random() * 40) + 's';
                el.style.animationDuration = (30 + Math.random() * 30) + 's';
                el.style.fontSize = (2.6 + Math.random() * 3.5) + 'rem';
                el.style.opacity = 0.08 + Math.random() * 0.15;
                container.appendChild(el);
            }
        })();

        (function createFloralBg() {
            const container = document.getElementById('floralBg');
            const flowers = ['🌸', '🌼', '🌺', '🌷', '🌸', '🌿', '🍃', '🌸', '🌼', '🌸', '🌺', '🌸', '🌼', '🌸', '🌺'];
            for (let i = 0; i < 45; i++) {
                const el = document.createElement('span');
                el.className = 'flower';
                el.textContent = flowers[i % flowers.length];
                el.style.left = Math.random() * 100 + '%';
                el.style.top = Math.random() * 100 + '%';
                el.style.animationDelay = (Math.random() * 30) + 's';
                el.style.animationDuration = (18 + Math.random() * 22) + 's';
                el.style.fontSize = (1.8 + Math.random() * 2.8) + 'rem';
                el.style.opacity = 0.12 + Math.random() * 0.20;
                container.appendChild(el);
            }
        })();

        (function createPetals() {
            const container = document.getElementById('petalsContainer');
            const petalChars = ['🌸', '🌼', '🌺', '✦', '✿', '🍃', '🌸', '🌼'];
            for (let i = 0; i < 22; i++) {
                const petal = document.createElement('span');
                petal.className = 'petal';
                petal.textContent = petalChars[i % petalChars.length];
                petal.style.left = Math.random() * 100 + '%';
                petal.style.top = '-10%';
                petal.style.fontSize = (0.8 + Math.random() * 1.6) + 'rem';
                petal.style.opacity = 0.12 + Math.random() * 0.25;
                petal.style.animationDuration = (14 + Math.random() * 24) + 's';
                petal.style.animationDelay = (Math.random() * 24) + 's';
                petal.style.transform = 'rotate(' + (Math.random() * 360) + 'deg)';
                container.appendChild(petal);
            }
        })();

        (function createRings() {
            const container = document.getElementById('floatingRings');
            const positions = [
                { left: '5%', top: '10%' },
                { left: '88%', top: '15%' },
                { left: '10%', top: '70%' },
                { left: '82%', top: '75%' },
                { left: '45%', top: '90%' },
                { left: '25%', top: '92%' },
                { left: '68%', top: '5%' }
            ];
            positions.forEach((pos, index) => {
                const ring = document.createElement('div');
                ring.className = 'f-ring';
                ring.textContent = '✦';
                ring.style.left = pos.left;
                ring.style.top = pos.top;
                ring.style.setProperty('--dur', (4.5 + index * 0.8) + 's');
                ring.style.animationDelay = (index * 1.2) + 's';
                ring.style.fontSize = (16 + index * 2) + 'px';
                container.appendChild(ring);
            });
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