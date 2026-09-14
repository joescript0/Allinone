@php
    $nom_app = \App\Models\appnames::where('etat', 1)->value('nom') ?? 'LES300HOMMESDEGEDEON';
@endphp
@extends('layouts.main')

@section('title', $nom_app)
@section('name', 'CARTE DES PLATS')

@section('body')

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
/* ============================================================
   FULL BODY
   ============================================================ */
html, body {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow-x: hidden;
}
body > * { max-width: none !important; }
body > .container,
body > .container-fluid,
body > main,
body > .content,
body > .main-content {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* ============================================================
   RESET & VARIABLES
   ============================================================ */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --navy: #0a1f38;
    --navy-soft: #1e3a5f;
    --navy-mid: #2c5380;
    --blue: #5b9bd5;
    --blue-light: #a8cef0;
    --blue-pale: #eaf3fb;
    --red: #dc3545;
    --red-dark: #b02a37;
    --red-pale: #fdecee;
    --gold: #d4af37;
    --gold-light: #f4e5b1;
    --ink: #1a2a3a;
    --muted: #64788d;
    --bg: #f5faff;
    --white: #ffffff;
    --glass: rgba(255, 255, 255, 0.72);
    --glass-strong: rgba(255, 255, 255, 0.92);
    --border-soft: rgba(123, 163, 201, 0.18);
    --shadow-sm: 0 2px 8px rgba(30, 58, 95, 0.04);
    --shadow-md: 0 12px 40px rgba(30, 58, 95, 0.08);
    --shadow-lg: 0 24px 70px rgba(30, 58, 95, 0.14);
    --ease: cubic-bezier(0.22, 1, 0.36, 1);
    --radius-sm: 12px;
    --radius-md: 20px;
    --radius-lg: 28px;
    --radius-pill: 999px;
}

html { scroll-behavior: smooth; }
html, body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: var(--ink);
    background: var(--bg);
    -webkit-font-smoothing: antialiased;
}

::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, var(--blue), var(--navy));
    border-radius: 10px;
    border: 2px solid var(--bg);
}

/* ============================================================
   CONTAINER PRINCIPAL
   ============================================================ */
.menu-wrapper {
    width: 100vw;
    max-width: 100vw;
    min-height: 100vh;
    margin-left: calc(-50vw + 50%);
    position: relative;
    display: flex;
    flex-direction: column;
    background: var(--bg);
}

/* ============================================================
   HERO
   ============================================================ */
.hero {
    position: relative;
    width: 100%;
    min-height: 640px;
    height: 88vh;
    max-height: 900px;
    overflow: hidden;
    isolation: isolate;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}
.hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('{{ asset('images/entete.jpg') }}');
    background-size: cover;
    background-position: center;
    filter: brightness(0.5) saturate(1.15);
    transform: scale(1.08);
    z-index: -3;
    animation: heroZoom 20s ease-in-out infinite alternate;
}
@keyframes heroZoom {
    from { transform: scale(1.05); }
    to   { transform: scale(1.15); }
}
.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 50% 45%, rgba(10, 31, 56, 0.30) 0%, rgba(10, 31, 56, 0.88) 100%),
        linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(10, 31, 56, 0.70) 55%, rgba(10, 31, 56, 0.98) 100%);
    z-index: -2;
}
.hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(2px 2px at 15% 25%, rgba(212, 175, 55, 0.6), transparent 100%),
        radial-gradient(1px 1px at 78% 35%, rgba(255, 255, 255, 0.65), transparent 100%),
        radial-gradient(2px 2px at 42% 78%, rgba(212, 175, 55, 0.5), transparent 100%),
        radial-gradient(1px 1px at 88% 68%, rgba(255, 255, 255, 0.55), transparent 100%),
        radial-gradient(1.5px 1.5px at 25% 60%, rgba(212, 175, 55, 0.45), transparent 100%),
        radial-gradient(1px 1px at 62% 20%, rgba(255, 255, 255, 0.6), transparent 100%),
        radial-gradient(1.5px 1.5px at 8% 85%, rgba(212, 175, 55, 0.5), transparent 100%),
        radial-gradient(1px 1px at 95% 45%, rgba(255, 255, 255, 0.5), transparent 100%);
    background-size: 400px 400px;
    animation: sparkFloat 30s linear infinite;
    opacity: 0.9;
    z-index: -1;
    pointer-events: none;
}
@keyframes sparkFloat {
    from { background-position: 0 0; }
    to   { background-position: 400px 400px; }
}

.hero-top-ornament {
    position: absolute;
    top: 2rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 1.2rem;
    z-index: 3;
    opacity: 0;
    animation: fadeDown 1s 0.2s var(--ease) forwards;
}
.hero-top-ornament .line {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.hero-top-ornament i {
    color: var(--gold);
    font-size: 0.85rem;
    letter-spacing: 8px;
    opacity: 0.85;
}
@keyframes fadeDown {
    from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    to   { opacity: 1; transform: translateX(-50%) translateY(0); }
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 4.5rem 1.5rem 6rem;
    max-width: 1200px;
    width: 100%;
    animation: heroIn 1.2s var(--ease) both;
}
@keyframes heroIn {
    from { opacity: 0; transform: translateY(40px); }
    to   { opacity: 1; transform: translateY(0); }
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.5rem 1.6rem;
    background: rgba(212, 175, 55, 0.14);
    border: 1px solid rgba(212, 175, 55, 0.45);
    border-radius: var(--radius-pill);
    color: var(--gold-light);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 8px;
    text-transform: uppercase;
    margin-bottom: 1.6rem;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
}
.hero-badge i { color: var(--gold); font-size: 0.7rem; }

/* ============================================================
   TITRE HERO — ADAPTÉ POUR LES NOMS LONGS
   ============================================================ */
.hero-title {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-size: clamp(1.4rem, 4.2vw, 3.6rem);
    letter-spacing: 2px;
    line-height: 1.05;
    text-transform: uppercase;
    margin: 0 auto 1rem;
    color: #fff;
    text-shadow:
        0 4px 30px rgba(0, 0, 0, 0.6),
        0 0 60px rgba(212, 175, 55, 0.15);
    position: relative;
    display: block;
    max-width: 100%;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    padding: 0 0.5rem;
    hyphens: auto;
}
.hero-title::before,
.hero-title::after {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.hero-title::before { top: -20px; }
.hero-title::after { bottom: -20px; width: 140px; }
.hero-title .accent {
    background: linear-gradient(135deg, #ffffff 0%, #f4e5b1 50%, #ffffff 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: clamp(0.95rem, 1.6vw, 1.35rem);
    color: rgba(255, 255, 255, 0.92);
    letter-spacing: 3px;
    margin-top: 1.5rem;
    font-weight: 400;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
}
.hero-subtitle .dot {
    width: 5px;
    height: 5px;
    background: var(--gold);
    border-radius: 50%;
    box-shadow: 0 0 12px var(--gold);
    flex-shrink: 0;
}

/* Bande info */
.hero-info-band {
    margin-top: 2.8rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.4rem 0.6rem;
    padding: 0.7rem 0.9rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: var(--radius-pill);
    backdrop-filter: blur(14px) saturate(1.3);
    -webkit-backdrop-filter: blur(14px) saturate(1.3);
    box-shadow:
        0 12px 40px rgba(0, 0, 0, 0.25),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
    opacity: 0;
    animation: fadeUp 1s 0.6s var(--ease) forwards;
    max-width: 100%;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
.hero-info-band .info-item {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.55rem 1.1rem;
    color: rgba(255, 255, 255, 0.95);
    font-size: 0.82rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: var(--radius-pill);
    transition: all 0.3s var(--ease);
    text-decoration: none;
    white-space: nowrap;
}
.hero-info-band .info-item:hover {
    background: rgba(212, 175, 55, 0.15);
    color: #fff;
    transform: translateY(-2px);
}
.hero-info-band .info-item i {
    color: var(--gold);
    font-size: 0.95rem;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    transition: transform 0.3s var(--ease);
}
.hero-info-band .info-item:hover i { transform: scale(1.15); }
.hero-info-band .info-divider {
    width: 1px;
    height: 22px;
    background: rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}

/* Réseaux sociaux */
.hero-socials {
    margin-top: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.9rem;
    opacity: 0;
    animation: fadeUp 1s 0.9s var(--ease) forwards;
}
.hero-socials a {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(10px);
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.35s var(--ease);
}
.hero-socials a:hover {
    transform: translateY(-5px) rotate(6deg);
    color: var(--gold);
    border-color: var(--gold);
    box-shadow: 0 12px 30px rgba(212, 175, 55, 0.35);
    background: rgba(212, 175, 55, 0.15);
}

/* Extra pills */
.hero-extras {
    margin-top: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.7rem;
    opacity: 0;
    animation: fadeUp 1s 1.1s var(--ease) forwards;
}
.hero-extras .extra-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1.1rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: var(--radius-pill);
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.9);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 500;
    backdrop-filter: blur(8px);
    transition: all 0.3s var(--ease);
}
.hero-extras .extra-pill i {
    color: var(--gold);
    font-size: 0.78rem;
}
.hero-extras .extra-pill:hover {
    background: rgba(212, 175, 55, 0.15);
    border-color: var(--gold);
    color: #fff;
    transform: translateY(-2px);
}

/* Scroll + wave */
.hero-scroll {
    position: absolute;
    bottom: 110px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 4;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.3rem;
    animation: bounceDown 2s ease-in-out infinite;
    cursor: pointer;
    transition: color 0.3s;
    text-decoration: none;
}
.hero-scroll:hover { color: var(--gold); }
@keyframes bounceDown {
    0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.6; }
    50%      { transform: translateX(-50%) translateY(10px); opacity: 1; }
}
.hero-wave {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 80px;
    z-index: 3;
    pointer-events: none;
}
.hero-wave svg { display: block; width: 100%; height: 100%; }

/* ============================================================
   CONTENU
   ============================================================ */
.menu-content {
    flex: 1;
    padding: 2rem 2.5rem 3rem;
    position: relative;
    background:
        radial-gradient(circle at 12% 8%, rgba(91, 155, 213, 0.10), transparent 45%),
        radial-gradient(circle at 88% 92%, rgba(220, 53, 69, 0.06), transparent 45%),
        linear-gradient(180deg, #f5faff 0%, #eaf3fb 100%);
    overflow: hidden;
}
.menu-content::after {
    content: "🍽️ 🥗 🍔 🍟 🧅 🍕 🍝 🧁 🍫 🥤 🍋 🍊 🍮 🥑 🌶️ 🧀 🥓 🍳 🥞 🧇 🥨 🥖 🧄 🍇 🍉 🍌 🍎 🍐 🍑 🍒 🍓 🥝 🍅 🥥 🍆 🥔 🥕 🌽 🥒 🥬 🥦 🍄 🥜 🌰 🍞 🥐 🥯 🧈 🥩 🥪 🥙 🧆 🥘 🍲 🍛 🍜 🍣 🍤 🍥 🥮 🍢 🍡 🍧 🍨 🍩 🍪 🎂 🍰 🍬 🍭 🍯 🍼 🥛 🍵 🍺 🍻 🥂 🍷 🥃 🍸 🍹 🍾 🧊 🥄 🍴 🥢 🧂";
    position: absolute;
    inset: 0;
    font-size: 4.5rem;
    line-height: 2.2;
    letter-spacing: 3rem;
    word-break: break-all;
    white-space: pre-wrap;
    color: rgba(30, 58, 95, 0.03);
    transform: rotate(-4deg) scale(1.2);
    pointer-events: none;
    z-index: 0;
    display: flex;
    flex-wrap: wrap;
    align-content: space-around;
    justify-content: space-around;
    padding: 2rem;
    opacity: 0.7;
}
.menu-content > * { position: relative; z-index: 1; }

/* ============================================================
   BARRE DE FILTRES
   ============================================================ */
.filter-bar {
    background: var(--glass-strong);
    backdrop-filter: blur(20px) saturate(1.4);
    -webkit-backdrop-filter: blur(20px) saturate(1.4);
    border-radius: var(--radius-pill);
    padding: 0.6rem 0.9rem 0.6rem 1.5rem;
    margin-bottom: 2.5rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.7rem 1.2rem;
    border: 1px solid rgba(255, 255, 255, 0.9);
    box-shadow:
        0 8px 32px rgba(30, 58, 95, 0.08),
        0 0 0 1px rgba(123, 163, 201, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    flex-shrink: 0;
}
.filter-bar .filter-group {
    flex: 1 1 180px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 0;
}
.filter-bar .filter-group label {
    font-weight: 600;
    color: var(--navy-soft);
    font-size: 0.82rem;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.filter-bar .filter-group select {
    flex: 1;
    min-width: 0;
    padding: 0.55rem 1.9rem 0.55rem 0.9rem;
    border-radius: var(--radius-pill);
    border: 1px solid var(--border-soft);
    background: var(--white);
    font-family: inherit;
    font-size: 0.82rem;
    color: var(--ink);
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%230a1f38' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.85rem center;
    background-size: 10px;
    transition: all 0.25s var(--ease);
    box-shadow: 0 1px 3px rgba(30, 58, 95, 0.04);
}
.filter-bar .filter-group select:hover { border-color: var(--blue); }
.filter-bar .filter-group select:focus {
    border-color: var(--navy-soft);
    box-shadow: 0 0 0 4px rgba(91, 155, 213, 0.15);
}
.filter-bar .search-group {
    flex: 2 1 220px;
    display: flex;
    align-items: center;
    background: var(--white);
    border-radius: var(--radius-pill);
    padding: 0 1rem 0 1.2rem;
    border: 1px solid var(--border-soft);
    transition: all 0.25s var(--ease);
    min-width: 0;
    box-shadow: 0 1px 3px rgba(30, 58, 95, 0.04);
}
.filter-bar .search-group:hover { border-color: var(--blue); }
.filter-bar .search-group:focus-within {
    border-color: var(--navy-soft);
    box-shadow: 0 0 0 4px rgba(91, 155, 213, 0.15);
}
.filter-bar .search-group .icon {
    color: var(--blue);
    font-size: 0.9rem;
    margin-right: 0.6rem;
    transition: 0.25s;
}
.filter-bar .search-group:focus-within .icon { color: var(--navy-soft); }
.filter-bar .search-group input {
    border: none;
    padding: 0.65rem 0;
    font-size: 0.88rem;
    background: transparent;
    width: 100%;
    outline: none;
    color: var(--ink);
    font-family: inherit;
}
.filter-bar .search-group input::placeholder { color: #8aadc9; font-weight: 300; }
.filter-bar .clear-btn {
    background: linear-gradient(135deg, var(--navy-soft), var(--navy));
    color: #fff;
    border: none;
    padding: 0.65rem 2rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    font-family: inherit;
    white-space: nowrap;
    flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(10, 31, 56, 0.22);
    letter-spacing: 1px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
}
.filter-bar .clear-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    opacity: 0;
    transition: opacity 0.3s var(--ease);
    z-index: -1;
}
.filter-bar .clear-btn:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 10px 28px rgba(220, 53, 69, 0.30);
}
.filter-bar .clear-btn:hover::before { opacity: 1; }
.filter-bar .clear-btn:active { transform: translateY(0) scale(0.98); }

/* ============================================================
   SECTIONS
   ============================================================ */
.menu-section {
    margin-bottom: 2.5rem;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.45);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 1.8rem 1.6rem 0.8rem;
    border-radius: var(--radius-lg);
    border: 1px solid rgba(255, 255, 255, 0.75);
    box-shadow:
        0 4px 32px rgba(30, 58, 95, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.9rem;
    color: var(--navy-soft);
    padding-bottom: 0.6rem;
    margin-bottom: 1.6rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    flex-wrap: wrap;
    position: relative;
}
.section-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--red), var(--gold));
    border-radius: 4px;
}
.section-title span {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    font-size: 0.62rem;
    font-family: 'Inter', sans-serif;
    padding: 0.25rem 1.1rem;
    border-radius: var(--radius-pill);
    letter-spacing: 1.8px;
    text-transform: uppercase;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(220, 53, 69, 0.22);
}

/* ============================================================
   CARTES PLATS
   ============================================================ */
.menu-item {
    display: flex;
    gap: 1.3rem;
    align-items: center;
    padding: 1rem 1rem;
    margin-bottom: 0.7rem;
    border-radius: var(--radius-md);
    transition: all 0.4s var(--ease);
    background: var(--glass);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.75);
    box-shadow:
        0 2px 12px rgba(30, 58, 95, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    position: relative;
    overflow: hidden;
}
.menu-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(180deg, var(--blue), var(--red));
    opacity: 0;
    transition: opacity 0.4s var(--ease);
    border-radius: var(--radius-md) 0 0 var(--radius-md);
}
.menu-item.hidden { display: none !important; }
.menu-item:hover {
    background: var(--glass-strong);
    transform: translateY(-3px);
    box-shadow:
        0 16px 40px rgba(30, 58, 95, 0.10),
        0 0 0 1px rgba(91, 155, 213, 0.12),
        inset 0 1px 0 rgba(255, 255, 255, 0.95);
    border-color: rgba(91, 155, 213, 0.25);
}
.menu-item:hover::before { opacity: 1; }

.item-image {
    flex: 0 0 130px;
    height: 100px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(30, 58, 95, 0.12);
    border: 2px solid rgba(255, 255, 255, 0.9);
    background: linear-gradient(135deg, var(--blue-pale), #dbeaf7);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.item-image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 60%, rgba(10, 31, 56, 0.08) 100%);
    pointer-events: none;
}
.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s var(--ease);
}
.menu-item:hover .item-image img { transform: scale(1.1); }
.item-image .placeholder { color: var(--blue); font-size: 2rem; opacity: 0.55; }

.item-content {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 0.8rem;
    min-width: 0;
}
.item-left { flex: 2 1 240px; min-width: 0; }
.item-name {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--navy);
    margin-bottom: 0.15rem;
    line-height: 1.25;
    transition: color 0.3s var(--ease);
}
.menu-item:hover .item-name { color: var(--red); }
.item-desc {
    font-size: 0.8rem;
    color: var(--muted);
    margin-top: 0.35rem;
    font-weight: 400;
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    line-height: 1.4;
}
.item-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.55rem; }
.tag {
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-pill);
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: linear-gradient(135deg, rgba(91, 155, 213, 0.12), rgba(91, 155, 213, 0.06));
    color: var(--navy-soft);
    border: 1px solid rgba(91, 155, 213, 0.18);
}
.tag i { font-size: 0.55rem; color: var(--blue); }
.tag-activite {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.10), rgba(220, 53, 69, 0.05));
    color: var(--red-dark);
    border-color: rgba(220, 53, 69, 0.18);
}
.tag-activite i { color: var(--red); }

.item-right {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-left: auto;
}
.item-price {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.4rem;
    color: var(--red);
    white-space: nowrap;
    background: linear-gradient(135deg, var(--red-pale), rgba(255, 255, 255, 0.6));
    padding: 0.35rem 1rem;
    border-radius: var(--radius-pill);
    border: 1px solid rgba(220, 53, 69, 0.12);
    box-shadow: 0 4px 14px rgba(220, 53, 69, 0.08);
    transition: all 0.35s var(--ease);
}
.menu-item:hover .item-price {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 8px 24px rgba(220, 53, 69, 0.25);
    transform: scale(1.04);
}
.item-controls { display: flex; align-items: center; gap: 0.5rem; }

.qty-wrapper {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: var(--radius-pill);
    border: 1px solid var(--border-soft);
    overflow: hidden;
    height: 36px;
    box-shadow: 0 2px 8px rgba(30, 58, 95, 0.05);
    transition: all 0.25s var(--ease);
}
.qty-wrapper:hover { border-color: var(--blue); box-shadow: 0 4px 16px rgba(91, 155, 213, 0.12); }
.qty-wrapper button {
    background: transparent;
    border: none;
    width: 32px;
    height: 36px;
    cursor: pointer;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--muted);
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: inherit;
}
.qty-wrapper button:hover { background: var(--red); color: #fff; }
.qty-wrapper input {
    width: 36px;
    height: 36px;
    border: none;
    text-align: center;
    font-family: inherit;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--navy);
    background: transparent;
    outline: none;
    -moz-appearance: textfield;
}
.qty-wrapper input::-webkit-outer-spin-button,
.qty-wrapper input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.add-btn {
    background: linear-gradient(135deg, var(--blue), var(--navy-soft));
    border: none;
    color: #fff;
    padding: 0 1.2rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.78rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-family: inherit;
    box-shadow: 0 4px 16px rgba(91, 155, 213, 0.25);
    height: 36px;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}
.add-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    opacity: 0;
    transition: opacity 0.3s var(--ease);
}
.add-btn > * { position: relative; z-index: 1; }
.add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(220, 53, 69, 0.28);
}
.add-btn:hover::before { opacity: 1; }
.add-btn:active { transform: translateY(0) scale(0.98); }
.add-btn i { font-size: 0.68rem; transition: transform 0.3s var(--ease); }
.add-btn:hover i { transform: rotate(90deg); }
.add-btn.added {
    background: linear-gradient(135deg, #27ae60, #1e8449);
    box-shadow: 0 10px 28px rgba(39, 174, 96, 0.35);
}
.add-btn.added::before { opacity: 0; }

/* ============================================================
   PANIER FLOTTANT
   ============================================================ */
.cart-float {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
    background: linear-gradient(135deg, var(--navy-soft), var(--navy));
    color: #fff;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow:
        0 12px 36px rgba(10, 31, 56, 0.35),
        0 0 0 4px rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.35s var(--ease);
    border: none;
}
.cart-float:hover {
    transform: translateY(-5px) scale(1.08);
    box-shadow:
        0 20px 48px rgba(10, 31, 56, 0.4),
        0 0 0 4px rgba(255, 255, 255, 0.75);
    background: linear-gradient(135deg, var(--red), var(--red-dark));
}
.cart-float:active { transform: translateY(-2px) scale(1.03); }
.cart-float .badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    border-radius: var(--radius-pill);
    min-width: 26px;
    height: 26px;
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    border: 2px solid #fff;
    transform: scale(0);
    transition: transform 0.4s var(--ease);
}
.cart-float .badge.show { transform: scale(1); }

/* ============================================================
   MODAL PANIER
   ============================================================ */
.cart-modal {
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(10, 31, 56, 0.5);
    backdrop-filter: blur(10px) saturate(1.2);
    -webkit-backdrop-filter: blur(10px) saturate(1.2);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}
.cart-modal.open { display: flex; animation: modalFade 0.35s var(--ease); }
@keyframes modalFade { from { opacity: 0; } to { opacity: 1; } }

.cart-modal-content {
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(245, 250, 255, 0.97));
    backdrop-filter: blur(30px) saturate(1.4);
    -webkit-backdrop-filter: blur(30px) saturate(1.4);
    max-width: 720px;
    width: 100%;
    max-height: 90vh;
    border-radius: var(--radius-lg);
    padding: 2.2rem 2rem 1.6rem;
    box-shadow:
        0 40px 90px rgba(0, 0, 0, 0.22),
        0 0 0 1px rgba(255, 255, 255, 0.9) inset,
        0 0 0 1px rgba(123, 163, 201, 0.12);
    overflow-y: auto;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.6);
    animation: modalContentIn 0.4s var(--ease);
}
@keyframes modalContentIn {
    from { opacity: 0; transform: translateY(30px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.cart-modal-content::-webkit-scrollbar { width: 6px; }
.cart-modal-content::-webkit-scrollbar-thumb { background: var(--blue); border-radius: 6px; border: none; }

.cart-modal-content .close-modal {
    position: absolute;
    top: 1.2rem;
    right: 1.2rem;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(123, 163, 201, 0.15);
    width: 42px;
    height: 42px;
    border-radius: 50%;
    font-size: 1.3rem;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}
.cart-modal-content .close-modal:hover {
    background: var(--red);
    color: #fff;
    transform: rotate(90deg);
    border-color: transparent;
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
}
.cart-modal-header {
    text-align: center;
    padding-bottom: 1.6rem;
    margin-bottom: 1.6rem;
    position: relative;
}
.cart-modal-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 140px;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--red), var(--gold), var(--red), transparent);
    border-radius: 4px;
}
.cart-modal-header .cart-ornament {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    background: var(--glass);
    backdrop-filter: blur(6px);
    padding: 0.3rem 1.2rem;
    border-radius: var(--radius-pill);
    border: 1px solid rgba(220, 53, 69, 0.15);
    margin-bottom: 0.7rem;
    font-size: 0.68rem;
    color: var(--red);
    letter-spacing: 4px;
    font-weight: 600;
}
.cart-modal-header .cart-ornament i { font-size: 0.6rem; color: var(--red); }
.cart-modal-header .cart-modal-title {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-size: clamp(1rem, 2.6vw, 1.8rem);
    color: var(--navy);
    letter-spacing: 2px;
    line-height: 1.15;
    display: inline-block;
    text-transform: uppercase;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    max-width: 100%;
    padding: 0 0.5rem;
}
.cart-modal-header .cart-modal-title i {
    color: var(--red);
    font-size: clamp(0.85rem, 1.6vw, 1.2rem);
    margin: 0 0.3rem;
}
.cart-modal-header .cart-modal-sub {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: clamp(0.8rem, 1.1vw, 0.95rem);
    color: var(--navy-soft);
    letter-spacing: 1.5px;
    margin-top: 0.3rem;
    opacity: 0.9;
}
.cart-modal-header .cart-subline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.7rem;
    margin-top: 0.6rem;
}
.cart-modal-header .cart-subline .line { width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--red)); }
.cart-modal-header .cart-subline .line:last-child { background: linear-gradient(90deg, var(--red), transparent); }
.cart-modal-header .cart-subline .dot { width: 4px; height: 4px; background: var(--red); border-radius: 50%; opacity: 0.75; }

.cart-items { list-style: none; padding: 0; margin: 0 0 1rem; }
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 1rem;
    border-radius: var(--radius-sm);
    margin-bottom: 0.5rem;
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    transition: all 0.3s var(--ease);
    flex-wrap: wrap;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(30, 58, 95, 0.04);
}
.cart-item:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(91, 155, 213, 0.25);
    transform: translateX(4px);
    box-shadow: 0 8px 20px rgba(30, 58, 95, 0.08);
}
.cart-item-info { display: flex; align-items: center; gap: 0.9rem; flex-wrap: wrap; }
.cart-item-name { font-weight: 600; color: var(--navy); font-size: 0.95rem; }
.cart-item-qty {
    display: flex;
    align-items: center;
    gap: 3px;
    background: rgba(255, 255, 255, 0.9);
    padding: 0.1rem 0.2rem;
    border-radius: var(--radius-pill);
    border: 1px solid var(--border-soft);
}
.cart-item-qty button {
    background: transparent;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-item-qty button:hover { background: var(--red); color: #fff; }
.cart-item-qty span { min-width: 26px; text-align: center; font-weight: 700; font-size: 0.95rem; color: var(--navy); }
.cart-item-right { display: flex; align-items: center; gap: 0.7rem; flex-wrap: wrap; }
.cart-item-price {
    font-weight: 700;
    color: var(--red);
    font-size: 0.92rem;
    min-width: 90px;
    text-align: right;
    background: var(--red-pale);
    padding: 0.3rem 0.9rem;
    border-radius: var(--radius-pill);
    border: 1px solid rgba(220, 53, 69, 0.1);
}
.remove-item-btn {
    background: transparent;
    border: none;
    color: #a8b8c8;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}
.remove-item-btn:hover {
    background: var(--red);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
}
.empty-cart {
    color: var(--muted);
    font-style: italic;
    text-align: center;
    padding: 2.5rem 0;
    font-size: 1rem;
    background: rgba(235, 245, 252, 0.4);
    border-radius: var(--radius-md);
    border: 2px dashed rgba(123, 163, 201, 0.18);
}
.empty-cart i { margin-right: 10px; color: var(--blue); font-size: 1.3rem; }
.cart-total {
    text-align: right;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--navy-soft);
    padding: 1rem 0;
    border-top: 2px solid rgba(123, 163, 201, 0.12);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}
.cart-total .total-amount {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    padding: 0.4rem 1.6rem;
    border-radius: var(--radius-pill);
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    letter-spacing: 0.5px;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
    font-weight: 700;
}
.cart-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}
.clear-cart {
    background: rgba(220, 53, 69, 0.08);
    color: var(--red);
    border: 1px solid rgba(220, 53, 69, 0.15);
    padding: 0.65rem 1.6rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
}
.clear-cart:hover {
    background: var(--red);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(220, 53, 69, 0.28);
    border-color: transparent;
}
.generate-btn {
    background: linear-gradient(135deg, var(--blue), var(--navy-soft));
    color: #fff;
    border: none;
    padding: 0.65rem 1.6rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(91, 155, 213, 0.25);
}
.generate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(91, 155, 213, 0.4);
    background: linear-gradient(135deg, var(--navy-soft), var(--navy));
}
.generated-recap {
    margin-top: 1rem;
    padding: 1.3rem;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.75), rgba(245, 250, 255, 0.75));
    backdrop-filter: blur(12px);
    border-radius: var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.9);
    box-shadow:
        0 8px 32px rgba(30, 58, 95, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    display: none;
    position: relative;
    animation: fadeInRecap 0.4s var(--ease);
}
.generated-recap.visible { display: block; }
@keyframes fadeInRecap {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.generated-recap h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    color: var(--navy-soft);
    margin-bottom: 0.9rem;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
}
.generated-recap h3 i { color: var(--blue); }
.generated-recap table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
.generated-recap th {
    text-align: left;
    padding: 0.5rem 0.3rem;
    border-bottom: 2px solid rgba(123, 163, 201, 0.12);
    color: var(--navy-soft);
    font-weight: 600;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.generated-recap td { padding: 0.55rem 0.3rem; border-bottom: 1px solid rgba(123, 163, 201, 0.06); color: var(--ink); }
.generated-recap tr:hover td { background: rgba(91, 155, 213, 0.04); }
.generated-recap .total-recap {
    font-weight: 700;
    font-size: 1rem;
    color: var(--navy);
    border-top: 2px solid rgba(123, 163, 201, 0.12);
    padding-top: 0.8rem;
    margin-top: 0.5rem;
    text-align: right;
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    flex-wrap: wrap;
    align-items: center;
}
.generated-recap .total-recap span {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    padding: 0.3rem 1.2rem;
    border-radius: var(--radius-pill);
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    box-shadow: 0 4px 14px rgba(220, 53, 69, 0.2);
}
.generated-recap .close-recap {
    background: transparent;
    border: none;
    color: #a8b8c8;
    font-size: 1.1rem;
    cursor: pointer;
    position: absolute;
    top: 1rem;
    right: 1.1rem;
    transition: all 0.25s var(--ease);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.generated-recap .close-recap:hover {
    color: #fff;
    background: var(--red);
    transform: rotate(90deg);
}
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.8rem;
    margin-top: 1rem;
    padding-top: 0.8rem;
    border-top: 1px solid rgba(123, 163, 201, 0.12);
}
.action-buttons .pdf-btn {
    background: linear-gradient(135deg, var(--navy-soft), var(--navy));
    color: #fff;
    border: none;
    padding: 0.7rem 1.8rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(10, 31, 56, 0.22);
}
.action-buttons .pdf-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(10, 31, 56, 0.32);
}
.action-buttons .order-btn {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    border: none;
    padding: 0.7rem 1.8rem;
    border-radius: var(--radius-pill);
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
}
.action-buttons .order-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(220, 53, 69, 0.35);
}

/* ============================================================
   TOAST
   ============================================================ */
.toast {
    position: fixed;
    bottom: 2.5rem;
    left: 50%;
    transform: translateX(-50%) translateY(120px);
    background: linear-gradient(135deg, var(--navy-soft), var(--navy));
    backdrop-filter: blur(16px);
    color: #fff;
    padding: 1rem 2.2rem;
    border-radius: var(--radius-pill);
    font-size: 0.9rem;
    font-weight: 500;
    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.28),
        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    opacity: 0;
    transition: all 0.5s var(--ease);
    pointer-events: none;
    z-index: 3000;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    max-width: 90vw;
}
.toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
.toast i { color: #2ecc71; font-size: 1.15rem; }

/* ============================================================
   NO RESULTS
   ============================================================ */
.no-results {
    display: none;
    text-align: center;
    padding: 6rem 1rem;
    color: var(--muted);
}
.no-results.show { display: block; animation: fadeIn 0.5s var(--ease); }
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.no-results i { font-size: 4rem; display: block; margin-bottom: 1.2rem; opacity: 0.18; color: var(--blue); }
.no-results strong {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: var(--navy-soft);
    margin-bottom: 0.5rem;
    font-weight: 700;
}
.no-results span { font-size: 0.88rem; opacity: 0.7; }

/* ============================================================
   FOOTER
   ============================================================ */
.menu-footer {
    background: linear-gradient(180deg, var(--navy) 0%, #050f1e 100%);
    color: rgba(255, 255, 255, 0.75);
    padding: 3.5rem 2.5rem 2rem;
    position: relative;
    overflow: hidden;
}
.menu-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--gold), var(--red), var(--gold), transparent);
}
.menu-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 2.5rem;
    position: relative;
    z-index: 1;
}
.menu-footer .footer-col h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    color: var(--gold);
    margin-bottom: 1.1rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    overflow-wrap: break-word;
    word-break: break-word;
}
.menu-footer .footer-col h4 i { font-size: 0.85rem; }
.menu-footer .footer-col p,
.menu-footer .footer-col li {
    font-size: 0.85rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.72);
    list-style: none;
}
.menu-footer .footer-col a {
    color: rgba(255, 255, 255, 0.72);
    text-decoration: none;
    transition: color 0.25s var(--ease);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.menu-footer .footer-col a:hover { color: var(--gold); }
.menu-footer .footer-col a i { color: var(--gold); font-size: 0.8rem; }
.menu-footer .footer-socials {
    display: flex;
    gap: 0.7rem;
    margin-top: 0.5rem;
}
.menu-footer .footer-socials a {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    font-size: 0.95rem;
    transition: all 0.3s var(--ease);
}
.menu-footer .footer-socials a:hover {
    background: var(--gold);
    color: var(--navy);
    transform: translateY(-4px) rotate(6deg);
    border-color: var(--gold);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
}
.menu-footer .footer-bottom {
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.45);
    letter-spacing: 1px;
    overflow-wrap: break-word;
    word-break: break-word;
}
.menu-footer .footer-bottom i { color: var(--red); }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .hero { min-height: 560px; height: 80vh; }
    .hero-info-band { padding: 0.5rem 0.6rem; gap: 0.2rem 0.4rem; }
    .hero-info-band .info-item { padding: 0.45rem 0.9rem; font-size: 0.75rem; }
    .hero-socials a { width: 40px; height: 40px; font-size: 0.9rem; }
    .menu-content { padding: 2rem 1.5rem 2.5rem; }
    .item-image { flex: 0 0 110px; height: 90px; }
    .item-price { font-size: 1.2rem; padding: 0.3rem 0.8rem; }
    .section-title { font-size: 1.6rem; }
    .menu-footer { padding: 2.5rem 1.5rem 1.5rem; }
}
@media (max-width: 768px) {
    .hero { min-height: 520px; height: 75vh; }
    .hero-title { letter-spacing: 1.5px; font-size: clamp(1.2rem, 4vw, 2.4rem); }
    .hero-subtitle { letter-spacing: 1.5px; }
    .hero-scroll { bottom: 85px; }
    .hero-info-band {
        flex-direction: column;
        padding: 0.6rem;
        gap: 0.15rem;
        border-radius: var(--radius-md);
    }
    .hero-info-band .info-item { padding: 0.55rem 1rem; font-size: 0.78rem; width: 100%; justify-content: center; }
    .hero-info-band .info-divider { display: none; }
    .hero-extras { gap: 0.5rem; margin-top: 1.5rem; }
    .hero-extras .extra-pill { font-size: 0.65rem; padding: 0.35rem 0.9rem; letter-spacing: 1px; }
    .menu-content { padding: 1.5rem 1rem 2rem; }
    .menu-content::after { font-size: 3rem; letter-spacing: 1.8rem; line-height: 2.4; transform: rotate(-2deg) scale(1.1); }
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
        border-radius: var(--radius-md);
        padding: 1rem;
        gap: 0.7rem;
    }
    .filter-bar .filter-group { flex-wrap: wrap; flex: 1 1 auto; }
    .filter-bar .filter-group select { flex: 1; }
    .filter-bar .search-group { flex: 1 1 auto; }
    .filter-bar .clear-btn { align-self: stretch; text-align: center; padding: 0.75rem 1rem; font-size: 0.85rem; }
    .menu-item {
        flex-direction: column;
        align-items: stretch;
        gap: 0.9rem;
        padding: 1rem;
    }
    .item-image { flex: 0 0 auto; height: 180px; width: 100%; }
    .item-right {
        align-items: stretch;
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-left: 0;
        gap: 0.7rem;
    }
    .item-price { font-size: 1.3rem; }
    .item-controls { flex: 1; justify-content: flex-end; }
    .section-title { font-size: 1.45rem; }
    .menu-section { padding: 1.2rem 1rem 0.6rem; border-radius: var(--radius-md); }
    .cart-modal-content { padding: 1.6rem 1.2rem; border-radius: var(--radius-md); }
    .action-buttons { flex-direction: column; }
    .action-buttons .pdf-btn, .action-buttons .order-btn { justify-content: center; width: 100%; }
    .cart-float { width: 54px; height: 54px; font-size: 1.2rem; bottom: 1.3rem; right: 1.3rem; }
    .menu-footer-inner { grid-template-columns: 1fr; gap: 1.8rem; text-align: center; }
    .menu-footer .footer-col h4 { justify-content: center; }
    .menu-footer .footer-socials { justify-content: center; }
}
@media (max-width: 480px) {
    .hero { min-height: 480px; height: 72vh; }
    .hero-badge { font-size: 0.7rem; letter-spacing: 6px; padding: 0.4rem 1.4rem; }
    .hero-badge i { font-size: 0.6rem; }
    .hero-title { letter-spacing: 1px; font-size: clamp(1rem, 5vw, 1.6rem); }
    .hero-title::before, .hero-title::after { width: 70px; }
    .hero-subtitle { font-size: 0.82rem; gap: 0.5rem; letter-spacing: 1px; }
    .hero-info-band .info-item { font-size: 0.72rem; padding: 0.5rem 0.8rem; }
    .hero-info-band .info-item i { font-size: 0.85rem; }
    .hero-socials a { width: 36px; height: 36px; font-size: 0.85rem; }
    .hero-top-ornament { top: 1.2rem; }
    .hero-top-ornament .line { width: 40px; }
    .menu-content { padding: 1rem 0.8rem 1.5rem; }
    .menu-content::after { font-size: 2.2rem; letter-spacing: 1.2rem; line-height: 2.6; }
    .item-image { height: 150px; }
    .item-name { font-size: 1.1rem; }
    .item-price { font-size: 1.15rem; }
    .cart-float { width: 48px; height: 48px; font-size: 1.05rem; bottom: 1rem; right: 1rem; }
    .cart-float .badge { width: 20px; height: 20px; font-size: 0.62rem; }
    .menu-section { padding: 1rem 0.8rem 0.4rem; }
    .section-title { font-size: 1.25rem; gap: 0.5rem; }
    .section-title span { font-size: 0.55rem; padding: 0.18rem 0.8rem; }
    .filter-bar .clear-btn { font-size: 0.78rem; padding: 0.6rem 0.8rem; }
    .cart-modal-content { padding: 1.2rem 0.9rem; border-radius: var(--radius-md); }
    .cart-modal-header .cart-modal-title { font-size: 0.95rem; letter-spacing: 1px; }
    .item-controls { width: 100%; }
    .add-btn { flex: 1; justify-content: center; }
    .menu-footer { padding: 2rem 1rem 1.2rem; }
}
</style>

<div class="menu-wrapper">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <header class="hero">
        <div class="hero-bg"></div>

        <div class="hero-top-ornament">
            <span class="line"></span>
            <i class="fas fa-star"></i>
            <i class="fas fa-circle" style="font-size:0.4rem;"></i>
            <i class="fas fa-star"></i>
            <span class="line"></span>
        </div>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-crown"></i>
                Maison
                <i class="fas fa-crown"></i>
            </div>

            <h1 class="hero-title">
                <span class="accent">{{ $nom_app }}</span>
            </h1>

            <div class="hero-subtitle">
                <span>Cuisine maison</span>
                <span class="dot"></span>
                <span>Fraîcheur &amp; Élégance</span>
                <span class="dot"></span>
                <span>Service raffiné</span>
            </div>

            <!-- ===== BANDE INFO ===== -->
            <div class="hero-info-band">
                <a href="tel:+243123456789" class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>+243 123 456 789</span>
                </a>
                <span class="info-divider"></span>

                <a href="https://maps.google.com/?q=Kinshasa,RDC" target="_blank" rel="noopener" class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Kinshasa, RDC</span>
                </a>
                <span class="info-divider"></span>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Mar–Sam · 12h–14h30 &amp; 19h–22h30</span>
                </div>
                <span class="info-divider"></span>

                <a href="mailto:contact@bistrobl.eu" class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span>contact@bistrobl.eu</span>
                </a>
            </div>

            <!-- ===== RÉSEAUX SOCIAUX ===== -->
            <div class="hero-socials">
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="#" aria-label="TripAdvisor"><i class="fab fa-tripadvisor"></i></a>
            </div>

            <!-- ===== ACCESSOIRES & SERVICES ===== -->
            <div class="hero-extras">
                <span class="extra-pill"><i class="fas fa-gift"></i> Accessoires disponibles</span>
                <span class="extra-pill"><i class="fas fa-motorcycle"></i> Livraison à domicile</span>
                <span class="extra-pill"><i class="fas fa-calendar-check"></i> Réservation en ligne</span>
                <span class="extra-pill"><i class="fas fa-credit-card"></i> Paiement mobile</span>
                <span class="extra-pill"><i class="fas fa-users"></i> Salle privée</span>
            </div>
        </div>

        <a href="#menu-content" class="hero-scroll" aria-label="Descendre">
            <i class="fas fa-chevron-down"></i>
        </a>

        <div class="hero-wave">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#f5faff" d="M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,80 L0,80 Z"/>
            </svg>
        </div>
    </header>

    <!-- ============================================================
         CONTENU
         ============================================================ -->
    <main class="menu-content" id="menu-content">

        <!-- ========== FILTRES ========== -->
        <div class="filter-bar">
            <div class="filter-group">
                <label for="categorySelect">📋 Catégorie</label>
                <select id="categorySelect">
                    <option value="all">Toutes</option>
                    @foreach ($societes as $categorie)
                        <option value="cat_{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="activiteSelect">📊 Activité</label>
                <select id="activiteSelect">
                    <option value="all">Toutes</option>
                    <option value="none">Aucune</option>
                    @foreach ($activites as $activite)
                        <option value="act_{{ $activite->id }}">{{ $activite->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="search-group">
                <span class="icon"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" placeholder="Rechercher un plat..." autocomplete="off" />
            </div>

            <button class="clear-btn" id="clearFiltersBtn" type="button">✕ Effacer</button>
        </div>

        <!-- ========== MENU PAR CATÉGORIE ========== -->
        @php $grouped = $articles->groupBy('societe_id'); @endphp

        @foreach ($grouped as $categorieId => $articlesOfCategorie)
            @php
                $categorieNom = optional($societes->firstWhere('id', $categorieId))->nom ?? 'Non catégorisé';
            @endphp

            <section class="menu-section" data-section-cat="cat_{{ $categorieId }}">
                <div class="section-title">
                    🍽️ {{ $categorieNom }}
                    <span>{{ count($articlesOfCategorie) }} {{ count($articlesOfCategorie) > 1 ? 'plats' : 'plat' }}</span>
                </div>

                @foreach ($articlesOfCategorie as $data)
                    @php
                        $deviseLabel = ($data->devise == 0) ? 'USD' : 'CDF';
                        $imagePath   = $data->image ? asset('storage/'.$data->image) : null;
                        $prixDetail  = number_format($data->prix_detail, 0, ',', ' ') . ' ' . $deviseLabel;
                        $desc        = 'Stock : ' . ($data->avoir_stock == 1
                                        ? $data->stock . ' (seuil ' . $data->seuil_minimum . '-' . $data->seuil_maximum . ')'
                                        : 'Indéterminé');
                        $activiteNom = ($data->activite_id == 0 || $data->activite_id === '0')
                                        ? null
                                        : optional($activites->firstWhere('id', $data->activite_id))->nom;
                        $activiteId  = $data->activite_id ?? '0';
                    @endphp

                    <div class="menu-item"
                         data-category="cat_{{ $categorieId }}"
                         data-activite="{{ $activiteId }}"
                         data-id="{{ $data->id }}"
                         data-name="{{ $data->nom_article }}"
                         data-price="{{ $data->prix_detail }}"
                         data-devise="{{ $deviseLabel }}">

                        <div class="item-image">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $data->nom_article }}" loading="lazy" />
                            @else
                                <i class="fas fa-utensils placeholder"></i>
                            @endif
                        </div>

                        <div class="item-content">
                            <div class="item-left">
                                <div class="item-name">{{ $data->nom_article }}</div>
                                <div class="item-desc">{{ $desc }}</div>
                                <div class="item-tags">
                                    <span class="tag"><i class="fas fa-tag"></i> {{ $categorieNom }}</span>
                                    @if ($activiteNom)
                                        <span class="tag tag-activite"><i class="fas fa-chart-pie"></i> {{ $activiteNom }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="item-right">
                                <div class="item-price">{{ $prixDetail }}</div>
                                <div class="item-controls">
                                    <div class="qty-wrapper">
                                        <button class="qty-minus" type="button" aria-label="Diminuer">−</button>
                                        <input class="qty-input" type="number" value="1" min="1" step="1" aria-label="Quantité" />
                                        <button class="qty-plus" type="button" aria-label="Augmenter">+</button>
                                    </div>
                                    <button class="add-btn" type="button"
                                            data-name="{{ $data->nom_article }}"
                                            data-price="{{ $data->prix_detail }}"
                                            data-devise="{{ $deviseLabel }}">
                                        <i class="fas fa-plus"></i> <span>Ajouter</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        @endforeach

        <div class="no-results" id="noResults">
            <i class="fas fa-search-minus"></i>
            <strong>Aucun résultat</strong>
            <span>Aucun plat ne correspond à votre recherche.</span>
        </div>

    </main>

    <!-- ============================================================
         FOOTER
         ============================================================ -->
    <footer class="menu-footer">
        <div class="menu-footer-inner">
            <div class="footer-col">
                <h4><i class="fas fa-utensils"></i> {{ $nom_app }}</h4>
                <p>Une maison de saveurs où la fraîcheur, l'élégance et le savoir-faire se rencontrent pour vous offrir une expérience culinaire unique.</p>
            </div>

            <div class="footer-col">
                <h4><i class="fas fa-address-book"></i> Contact</h4>
                <p><a href="tel:+243123456789"><i class="fas fa-phone-alt"></i> +243 123 456 789</a></p>
                <p><a href="mailto:contact@bistrobl.eu"><i class="fas fa-envelope"></i> contact@bistrobl.eu</a></p>
                <p><a href="https://maps.google.com/?q=Kinshasa,RDC" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt"></i> Kinshasa, RDC</a></p>
            </div>

            <div class="footer-col">
                <h4><i class="fas fa-clock"></i> Horaires</h4>
                <ul>
                    <li>Mardi – Samedi</li>
                    <li>12h00 – 14h30</li>
                    <li>19h00 – 22h30</li>
                    <li style="opacity:0.6; margin-top:0.5rem;">Dimanche &amp; Lundi · Fermé</li>
                </ul>
            </div>

            <div class="footer-col">
                <h4><i class="fas fa-share-alt"></i> Suivez-nous</h4>
                <div class="footer-socials">
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} <strong style="color:var(--gold);">{{ $nom_app }}</strong> · Tous droits réservés · Fait avec <i class="fas fa-heart"></i> à Kinshasa
        </div>
    </footer>
</div>

<!-- ========== PANIER FLOTTANT ========== -->
<button class="cart-float" id="cartFloatBtn" aria-label="Ouvrir le panier" type="button">
    <i class="fas fa-shopping-bag"></i>
    <span class="badge" id="cartBadge">0</span>
</button>

<!-- ========== MODAL PANIER ========== -->
<div class="cart-modal" id="cartModal">
    <div class="cart-modal-content">
        <button class="close-modal" id="closeModalBtn" type="button"><i class="fas fa-times"></i></button>

        <div class="cart-modal-header">
            <div class="cart-ornament"><i class="fas fa-star"></i> ✦ <i class="fas fa-star"></i></div>
            <div class="cart-modal-title">
                {{ $nom_app }}
            </div>
            <div class="cart-modal-sub">✦ Cuisine maison · Fraîcheur &amp; Élégance ✦</div>
            <div class="cart-subline">
                <span class="line"></span>
                <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                <span class="line"></span>
            </div>
        </div>

        <ul class="cart-items" id="cartItemsModal"></ul>
        <div class="cart-total" id="cartTotalModal">Total <span class="total-amount">0 CDF</span></div>

        <div class="cart-actions">
            <button class="generate-btn" id="generateBtnModal" type="button">
                <i class="fas fa-file-invoice"></i> Récapitulatif
            </button>
            <button class="clear-cart" id="clearCartBtnModal" type="button">
                <i class="fas fa-trash-alt"></i> Vider
            </button>
        </div>

        <div class="generated-recap" id="generatedRecapModal">
            <button class="close-recap" id="closeRecapModalBtn" type="button"><i class="fas fa-times"></i></button>
            <h3><i class="fas fa-receipt"></i> Récapitulatif de la commande</h3>
            <div id="recapContentModal"></div>
            <div class="action-buttons">
                <button class="pdf-btn" id="pdfBtn" type="button"><i class="fas fa-file-pdf"></i> Générer un proforma</button>
                <button class="order-btn" id="orderBtn" type="button"><i class="fas fa-check-circle"></i> Passer la commande</button>
            </div>
        </div>
    </div>
</div>

<!-- ========== TOAST ========== -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Ajouté au panier !</span>
</div>

<script>
(function () {
    'use strict';

    function init() {
        const $  = (s, c = document) => c.querySelector(s);
        const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

        const searchInput    = $('#searchInput');
        const categorySelect = $('#categorySelect');
        const activiteSelect = $('#activiteSelect');
        const clearBtn       = $('#clearFiltersBtn');
        const menuItems      = $$('.menu-item');
        const sections       = $$('.menu-section');
        const noResults      = $('#noResults');

        const cartBadge      = $('#cartBadge');
        const cartFloatBtn   = $('#cartFloatBtn');
        const cartModal      = $('#cartModal');
        const closeModalBtn  = $('#closeModalBtn');
        const cartItemsModal = $('#cartItemsModal');
        const cartTotalModal = $('#cartTotalModal');
        const clearCartBtn   = $('#clearCartBtnModal');
        const generateBtn    = $('#generateBtnModal');
        const generatedRecap = $('#generatedRecapModal');
        const recapContent   = $('#recapContentModal');
        const closeRecapBtn  = $('#closeRecapModalBtn');
        const pdfBtn         = $('#pdfBtn');
        const orderBtn       = $('#orderBtn');
        const toast          = $('#toast');
        const toastMsg       = $('#toastMsg');

        const STORE_NAME = @json($nom_app);
        const CURRENCY_DEFAULT = 'CDF';

        // Scroll smooth vers le contenu
        const heroScroll = $('.hero-scroll');
        heroScroll && heroScroll.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('menu-content')?.scrollIntoView({ behavior: 'smooth' });
        });

        // Filtrage
        function filterMenu() {
            const q   = (searchInput?.value || '').toLowerCase().trim();
            const cat = categorySelect ? categorySelect.value : 'all';
            const act = activiteSelect ? activiteSelect.value : 'all';

            let visibleCount = 0;

            menuItems.forEach(item => {
                const catAttr = item.dataset.category || '';
                const actAttr = String(item.dataset.activite || '').trim();
                const name    = (item.querySelector('.item-name')?.textContent || '').toLowerCase();
                const desc    = (item.querySelector('.item-desc')?.textContent || '').toLowerCase();

                const matchSearch   = !q || name.includes(q) || desc.includes(q);
                const matchCategory = (cat === 'all') || (catAttr === cat);

                let matchActivite = true;
                if (act === 'none') {
                    matchActivite = (actAttr === '' || actAttr === '0');
                } else if (act !== 'all') {
                    matchActivite = (actAttr === act.replace('act_', ''));
                }

                const show = matchSearch && matchCategory && matchActivite;
                item.classList.toggle('hidden', !show);
                if (show) visibleCount++;
            });

            sections.forEach(section => {
                const key = section.dataset.sectionCat;
                const has = menuItems.some(it => it.dataset.category === key && !it.classList.contains('hidden'));
                section.style.display = has ? 'block' : 'none';
            });

            if (noResults) noResults.classList.toggle('show', visibleCount === 0);
        }

        searchInput    && searchInput.addEventListener('input', filterMenu);
        categorySelect && categorySelect.addEventListener('change', filterMenu);
        activiteSelect && activiteSelect.addEventListener('change', filterMenu);
        clearBtn       && clearBtn.addEventListener('click', () => {
            if (searchInput)    searchInput.value = '';
            if (categorySelect) categorySelect.value = 'all';
            if (activiteSelect) activiteSelect.value = 'all';
            filterMenu();
        });
        filterMenu();

        // Toast
        let toastTimer = null;
        function showToast(msg) {
            if (!toast || !toastMsg) return;
            toastMsg.textContent = msg;
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
        }

        // Panier
        let cart = {};
        try { cart = JSON.parse(localStorage.getItem('restaurantCartV8')) || {}; }
        catch (e) { cart = {}; }

        const saveCart = () => {
            try { localStorage.setItem('restaurantCartV8', JSON.stringify(cart)); } catch (e) {}
        };

        function updateBadge() {
            const count = Object.values(cart).reduce((s, it) => s + (it.quantity || 0), 0);
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.classList.toggle('show', count > 0);
            }
        }

        function addToCart(id, name, price, devise, quantity = 1) {
            if (!id) id = 'item_' + name;
            if (cart[id]) cart[id].quantity += quantity;
            else cart[id] = { id, name, price: parseFloat(price) || 0, devise: devise || CURRENCY_DEFAULT, quantity };
            saveCart(); updateBadge();
            if (cartModal && cartModal.classList.contains('open')) renderCartModal();
        }
        function removeOne(id) {
            if (!cart[id]) return;
            cart[id].quantity -= 1;
            if (cart[id].quantity <= 0) delete cart[id];
            saveCart(); updateBadge();
            if (cartModal && cartModal.classList.contains('open')) renderCartModal();
        }
        function deleteItem(id) {
            if (!cart[id]) return;
            delete cart[id];
            saveCart(); updateBadge();
            if (cartModal && cartModal.classList.contains('open')) renderCartModal();
        }
        function clearCart() {
            cart = {};
            saveCart(); updateBadge();
            if (generatedRecap) generatedRecap.classList.remove('visible');
            if (cartModal && cartModal.classList.contains('open')) renderCartModal();
        }
        function formatMoney(amount, devise) {
            return (Number(amount) || 0).toLocaleString('fr-FR') + ' ' + (devise || CURRENCY_DEFAULT);
        }
        function computeTotals() {
            const totals = {};
            Object.values(cart).forEach(it => {
                const dev = it.devise || CURRENCY_DEFAULT;
                totals[dev] = (totals[dev] || 0) + (it.price * it.quantity);
            });
            return totals;
        }

        function renderCartModal() {
            if (!cartItemsModal) return;
            const items = Object.values(cart);
            cartItemsModal.innerHTML = '';
            if (items.length === 0) {
                cartItemsModal.innerHTML = `<li class="empty-cart"><i class="fas fa-shopping-bag"></i> Votre panier est vide.</li>`;
                if (cartTotalModal) cartTotalModal.innerHTML = `Total <span class="total-amount">0 ${CURRENCY_DEFAULT}</span>`;
                if (generatedRecap) generatedRecap.classList.remove('visible');
                return;
            }
            items.forEach(it => {
                const subtotal = it.price * it.quantity;
                const li = document.createElement('li');
                li.className = 'cart-item';
                li.innerHTML = `
                    <div class="cart-item-info">
                        <span class="cart-item-name">${it.name}</span>
                        <div class="cart-item-qty">
                            <button type="button" data-id="${it.id}" data-action="decr">−</button>
                            <span>${it.quantity}</span>
                            <button type="button" data-id="${it.id}" data-action="incr">+</button>
                        </div>
                    </div>
                    <div class="cart-item-right">
                        <span class="cart-item-price">${formatMoney(subtotal, it.devise)}</span>
                        <button class="remove-item-btn" type="button" data-id="${it.id}" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
                li.querySelector('[data-action="decr"]').addEventListener('click', () => removeOne(it.id));
                li.querySelector('[data-action="incr"]').addEventListener('click', () => addToCart(it.id, it.name, it.price, it.devise, 1));
                li.querySelector('.remove-item-btn').addEventListener('click', () => deleteItem(it.id));
                cartItemsModal.appendChild(li);
            });
            const totals = computeTotals();
            if (cartTotalModal) {
                const html = Object.entries(totals)
                    .map(([dev, val]) => `<span class="total-amount">${formatMoney(val, dev)}</span>`)
                    .join('');
                cartTotalModal.innerHTML = `Total ${html}`;
            }
        }

        function generateRecap() {
            const items = Object.values(cart);
            if (items.length === 0) { showToast('Votre panier est vide.'); return; }
            let html = '<table><thead><tr><th>Plat</th><th>Qté</th><th>PU</th><th>Sous-total</th></tr></thead><tbody>';
            const totals = {};
            items.forEach(it => {
                const sub = it.price * it.quantity;
                const dev = it.devise || CURRENCY_DEFAULT;
                totals[dev] = (totals[dev] || 0) + sub;
                html += `<tr><td><strong>${it.name}</strong></td><td>${it.quantity}</td><td>${formatMoney(it.price, dev)}</td><td>${formatMoney(sub, dev)}</td></tr>`;
            });
            html += '</tbody></table>';
            const totalHtml = Object.entries(totals).map(([dev, val]) => `<span>${formatMoney(val, dev)}</span>`).join(' ');
            html += `<div class="total-recap">Total : ${totalHtml}</div>`;
            recapContent.innerHTML = html;
            generatedRecap.classList.add('visible');
            generatedRecap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function generatePDF() {
            if (!recapContent || !recapContent.innerHTML.trim()) {
                showToast("Générez d'abord le récapitulatif."); return;
            }
            const headerHTML = `
                <div style="text-align:center; padding-bottom:20px; margin-bottom:20px; border-bottom:3px solid #dc3545;">
                    <div style="font-family:'Playfair Display',serif; font-weight:900; font-size:1.6rem; color:#0a1f38; letter-spacing:2px; text-transform:uppercase; word-break:break-word;">
                        ${STORE_NAME}
                    </div>
                    <div style="font-family:'Playfair Display',serif; font-style:italic; font-size:1rem; color:#1e3a5f; letter-spacing:2px; margin-top:.3rem;">
                        ✦ Cuisine maison · Fraîcheur & Élégance ✦
                    </div>
                    <div style="font-size:13px; color:#5b7a9a; margin-top:10px; font-weight:300;">
                        Proforma • ${new Date().toLocaleDateString('fr-FR')}
                    </div>
                </div>`;
            const content = `
                <div style="font-family:'Inter',sans-serif; padding:20px; max-width:700px; margin:0 auto; background:#fff; border-radius:12px;">
                    ${headerHTML}
                    ${recapContent.innerHTML}
                    <div style="margin-top:25px; padding-top:15px; border-top:1px solid #ddd; text-align:center; color:#8aa8c0; font-size:12px;">
                        Merci de votre confiance • ${STORE_NAME}
                    </div>
                </div>`;
            if (typeof html2pdf === 'function') {
                html2pdf().set({
                    margin: [10, 10, 10, 10],
                    filename: `Proforma_${STORE_NAME}_${new Date().toISOString().slice(0,10)}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, letterRendering: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                }).from(content).save();
            } else {
                showToast('Bibliothèque PDF non chargée.');
            }
        }

        function placeOrder() {
            const items = Object.values(cart);
            if (items.length === 0) { showToast('Votre panier est vide.'); return; }
            const totals = computeTotals();
            const summary = Object.entries(totals).map(([d, v]) => formatMoney(v, d)).join(' + ');
            if (confirm(`📋 Confirmer la commande pour un total de ${summary} ?`)) {
                showToast('✅ Commande validée avec succès !');
                clearCart();
            }
        }

        function openModal() {
            cartModal.classList.add('open');
            renderCartModal();
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            cartModal.classList.remove('open');
            document.body.style.overflow = '';
        }

        cartFloatBtn  && cartFloatBtn.addEventListener('click', openModal);
        closeModalBtn && closeModalBtn.addEventListener('click', closeModal);
        cartModal     && cartModal.addEventListener('click', e => { if (e.target === cartModal) closeModal(); });
        clearCartBtn  && clearCartBtn.addEventListener('click', clearCart);
        generateBtn   && generateBtn.addEventListener('click', generateRecap);
        closeRecapBtn && closeRecapBtn.addEventListener('click', () => generatedRecap.classList.remove('visible'));
        pdfBtn        && pdfBtn.addEventListener('click', generatePDF);
        orderBtn      && orderBtn.addEventListener('click', placeOrder);

        document.addEventListener('click', e => {
            const minus = e.target.closest('.qty-minus');
            const plus  = e.target.closest('.qty-plus');
            if (!minus && !plus) return;
            const wrapper = (minus || plus).closest('.qty-wrapper');
            const input   = wrapper ? wrapper.querySelector('.qty-input') : null;
            if (!input) return;
            let v = parseInt(input.value) || 1;
            if (minus) { if (v > 1) v--; } else { v++; }
            input.value = v;
        });
        document.addEventListener('input', e => {
            if (!e.target.classList.contains('qty-input')) return;
            const v = parseInt(e.target.value);
            if (isNaN(v) || v < 1) e.target.value = 1;
        });
        document.addEventListener('click', e => {
            const btn = e.target.closest('.add-btn');
            if (!btn) return;
            const card  = btn.closest('.menu-item');
            const input = card ? card.querySelector('.qty-input') : null;
            const qty   = input ? parseInt(input.value) || 1 : 1;
            const id    = card ? card.dataset.id : null;
            addToCart(id, btn.dataset.name || 'Plat', parseFloat(btn.dataset.price) || 0, btn.dataset.devise || CURRENCY_DEFAULT, qty);
            const originalHTML = btn.innerHTML;
            btn.classList.add('added');
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Ajouté</span>';
            setTimeout(() => { btn.classList.remove('added'); btn.innerHTML = originalHTML; }, 1200);
            if (input) input.value = 1;
            showToast(`${qty} × ${btn.dataset.name} ajouté`);
        });

        updateBadge();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

@endsection
