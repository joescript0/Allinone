@php
    use App\Models\appnames;
    $nom_app = appnames::where('etat', 1)->first()['nom'] ?? 'CONTROLAPP';
@endphp
@extends('layouts.main')
@section('title', $nom_app ?? 'Restaurant')
@section('name', 'CARTE DES PLATS')
@section('body')

<style>
    /* ============================================================
       RESET & PLEIN ÉCRAN
       ============================================================ */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        width: 100%;
        min-height: 100vh;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background: #fcf8f3;
        background-image:
            radial-gradient(ellipse at 10% 20%, #f7e4d4 0%, transparent 50%),
            radial-gradient(ellipse at 90% 80%, #f0d6c4 0%, transparent 50%),
            linear-gradient(145deg, #fcf8f3 0%, #f5e6d8 100%);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 0;
        margin: 0;
    }

    .menu-wrapper {
        width: 100%;
        max-width: 1600px;
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 1.5rem 2rem 2rem;
        box-shadow: none;
        border: none;
        border-radius: 0;
        display: flex;
        flex-direction: column;
    }

    /* ============================================================
       EN-TÊTE – VERSION AMÉLIORÉE (fond image + overlay)
       ============================================================ */
    .header {
        position: relative;
        min-height: 300px;
        border-radius: 30px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        background: #2c3e50; /* fallback */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: brightness(0.5) blur(3px);
        transform: scale(1.05);
        transition: transform 0.6s ease;
    }

    .header:hover .header-bg {
        transform: scale(1);
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        color: #fff;
        text-align: center;
        padding: 2rem;
        backdrop-filter: blur(4px);
        background: rgba(0, 0, 0, 0.2);
        width: 100%;
    }

    .header .ornament {
        font-size: 1.5rem;
        letter-spacing: 15px;
        color: rgba(255, 255, 255, 0.3);
        margin-bottom: 0.5rem;
        font-weight: 300;
    }

    .header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 4.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin: 0.2rem 0 0.1rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        line-height: 1.1;
    }

    .header h1 span {
        background: none;
        -webkit-text-fill-color: #fff;
        color: #fff; /* fallback */
    }

    .header .sub {
        font-size: 1rem;
        letter-spacing: 8px;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.12);
        padding: 0.4rem 2rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        color: #f5e6d8;
        font-weight: 400;
        margin-top: 0.2rem;
    }

    .header .sub i {
        margin: 0 6px;
        color: #e67e22;
    }

    .header-separator {
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #e67e22, #c0392b, transparent);
        margin-top: 1rem;
        border-radius: 4px;
    }

    /* ============================================================
       BARRE DE RECHERCHE
       ============================================================ */
    .search-bar {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .search-wrapper {
        position: relative;
        width: 100%;
        max-width: 500px;
    }
    .search-wrapper i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #b0a8a0;
        font-size: 1rem;
        transition: color 0.3s;
    }
    .search-wrapper input {
        width: 100%;
        padding: 0.8rem 1.2rem 0.8rem 3.2rem;
        border-radius: 60px;
        border: 1px solid rgba(192,57,43,0.12);
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(4px);
        font-size: 0.95rem;
        color: #2c3e50;
        outline: none;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    .search-wrapper input::placeholder {
        color: #b0a8a0;
        font-weight: 300;
    }
    .search-wrapper input:focus {
        border-color: #e67e22;
        background: rgba(255,255,255,0.95);
        box-shadow: 0 8px 30px rgba(192,57,43,0.06);
    }
    .search-wrapper input:focus + i {
        color: #e67e22;
    }

    /* ============================================================
       FILTRES
       ============================================================ */
    .filters {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.6rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid rgba(192,57,43,0.06);
    }
    .filter-btn {
        font-family: 'Inter', sans-serif;
        background: transparent;
        border: none;
        padding: 0.4rem 1.8rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #7f8c8d;
        border-radius: 40px;
        cursor: pointer;
        transition: all 0.25s ease;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .filter-btn i {
        margin-right: 6px;
        font-size: 0.8rem;
    }
    .filter-btn:hover {
        color: #2c3e50;
        background: rgba(192,57,43,0.05);
    }
    .filter-btn.active {
        color: #fff;
        background: #c0392b;
        box-shadow: 0 8px 20px rgba(192,57,43,0.2);
    }

    /* Filtres supplémentaires */
    .extra-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        margin-bottom: 2rem;
        background: rgba(255,255,255,0.3);
        padding: 0.6rem 1.5rem;
        border-radius: 60px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.5);
    }
    .extra-filters label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .extra-filters select {
        padding: 0.3rem 1.8rem 0.3rem 0.8rem;
        border-radius: 30px;
        border: 1px solid rgba(192,57,43,0.1);
        background: rgba(255,255,255,0.6);
        font-size: 0.8rem;
        color: #2c3e50;
        outline: none;
        cursor: pointer;
    }
    .extra-filters select:focus {
        border-color: #e67e22;
    }
    #resetFilters {
        background: transparent;
        border: 1px solid rgba(192,57,43,0.12);
        border-radius: 30px;
        padding: 0.3rem 1.4rem;
        font-size: 0.8rem;
        color: #7f8c8d;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #resetFilters:hover {
        background: rgba(192,57,43,0.04);
        color: #2c3e50;
        border-color: #c0392b;
    }

    /* ============================================================
       GRILLE – CARTES
       ============================================================ */
    .grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.8rem;
        flex: 1;
    }
    .card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.8);
        box-shadow: 0 8px 32px rgba(192,57,43,0.06);
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        transform: translateY(0);
        opacity: 1;
    }
    .card.hidden {
        display: none;
    }
    .card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(192,57,43,0.10);
        background: rgba(255,255,255,0.9);
        border-color: rgba(192,57,43,0.12);
    }
    .card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
        background: #f0e6dc;
        transition: transform 0.5s ease;
    }
    .card:hover .card-img {
        transform: scale(1.04);
    }
    .card-img-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0e6dc;
        color: #b0a8a0;
        font-size: 3rem;
        height: 160px;
    }
    .card-body {
        padding: 1rem 1.2rem 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 0.6rem;
        margin-bottom: 0.3rem;
    }
    .card-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2a4f;
        letter-spacing: -0.2px;
        line-height: 1.2;
    }
    .card-price {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1rem;
        color: #c0392b;
        white-space: nowrap;
        background: rgba(192,57,43,0.06);
        padding: 0.15rem 0.8rem;
        border-radius: 30px;
    }
    .card-desc {
        font-size: 0.8rem;
        color: #5d6d7e;
        margin-top: 0.1rem;
        line-height: 1.5;
        font-weight: 300;
        flex: 1;
    }
    .card-tags {
        margin-top: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
    }
    .tag {
        font-size: 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.15rem 0.8rem;
        border-radius: 30px;
        letter-spacing: 0.4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(192,57,43,0.06);
        color: #c0392b;
        border: 1px solid rgba(192,57,43,0.06);
    }
    .tag i {
        font-size: 0.5rem;
    }
    .tag-activite {
        background: rgba(230,126,34,0.08);
        color: #d35400;
        border-color: rgba(230,126,34,0.12);
    }

    .card-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 0.8rem;
        border-top: 1px solid rgba(192,57,43,0.04);
        padding-top: 0.8rem;
    }
    .qty-wrapper {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.6);
        border-radius: 40px;
        border: 1px solid rgba(192,57,43,0.06);
        overflow: hidden;
        flex: 0 0 auto;
        height: 34px;
    }
    .qty-wrapper button {
        background: transparent;
        border: none;
        width: 30px;
        height: 34px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        color: #7f8c8d;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-wrapper button:hover {
        background: rgba(192,57,43,0.04);
        color: #2c3e50;
    }
    .qty-wrapper input {
        width: 36px;
        height: 34px;
        border: none;
        text-align: center;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1f2a4f;
        background: transparent;
        outline: none;
        padding: 0;
        -moz-appearance: textfield;
    }
    .qty-wrapper input::-webkit-outer-spin-button,
    .qty-wrapper input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .btn-order {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(145deg, #c0392b, #a93226);
        border: none;
        border-radius: 40px;
        padding: 0.3rem 1.2rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        white-space: nowrap;
        height: 34px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex: 1;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(192,57,43,0.15);
        position: relative;
        overflow: hidden;
    }
    .btn-order::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(145deg, rgba(255,255,255,0.12), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .btn-order:hover::before {
        opacity: 1;
    }
    .btn-order i {
        font-size: 0.75rem;
    }
    .btn-order:hover {
        background: linear-gradient(145deg, #a93226, #c0392b);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 24px rgba(192,57,43,0.25);
    }
    .btn-order:active {
        transform: scale(0.96);
    }

    .no-results {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 0;
        color: #7f8c8d;
        font-size: 1rem;
        display: none;
    }
    .no-results i {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.5rem;
        opacity: 0.15;
        color: #c0392b;
    }

    /* ============================================================
       PIED DE PAGE
       ============================================================ */
    .footer {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(192,57,43,0.06);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        color: #7f8c8d;
        font-size: 0.85rem;
        gap: 1rem;
    }
    .footer .info {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem 1.5rem;
    }
    .footer .info i {
        color: #e67e22;
        margin-right: 4px;
    }
    .footer .info a {
        color: #7f8c8d;
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer .info a:hover {
        color: #c0392b;
    }
    .footer .social a {
        display: inline-block;
        margin-left: 0.8rem;
        color: #7f8c8d;
        font-size: 1.2rem;
        transition: color 0.2s, transform 0.2s;
    }
    .footer .social a:hover {
        color: #c0392b;
        transform: translateY(-2px);
    }

    /* ============================================================
       TOAST
       ============================================================ */
    .toast {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: rgba(44,62,80,0.95);
        backdrop-filter: blur(16px);
        color: #fff;
        padding: 0.8rem 2.5rem;
        border-radius: 60px;
        font-size: 0.95rem;
        font-weight: 500;
        box-shadow: 0 12px 48px rgba(0,0,0,0.12);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        pointer-events: none;
        z-index: 999;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    .toast i {
        margin-right: 12px;
        color: #f1c40f;
    }

    /* ============================================================
       ANIMATION DES CARTES
       ============================================================ */
    .card {
        animation: fadeUp 0.5s ease forwards;
        opacity: 0;
    }
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .card:nth-child(1)  { animation-delay: 0.05s; }
    .card:nth-child(2)  { animation-delay: 0.10s; }
    .card:nth-child(3)  { animation-delay: 0.15s; }
    .card:nth-child(4)  { animation-delay: 0.20s; }
    .card:nth-child(5)  { animation-delay: 0.25s; }
    .card:nth-child(6)  { animation-delay: 0.30s; }
    .card:nth-child(7)  { animation-delay: 0.35s; }
    .card:nth-child(8)  { animation-delay: 0.40s; }
    .card:nth-child(9)  { animation-delay: 0.45s; }
    .card:nth-child(10) { animation-delay: 0.50s; }
    .card:nth-child(11) { animation-delay: 0.55s; }
    .card:nth-child(12) { animation-delay: 0.60s; }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 1200px) {
        .grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    }
    @media (max-width: 900px) {
        .grid { grid-template-columns: repeat(2, 1fr); gap: 1.2rem; }
        .header h1 { font-size: 3rem; }
        .menu-wrapper { padding: 1rem 1.2rem; }
        .header { min-height: 220px; }
        .header-content { min-height: 220px; }
    }
    @media (max-width: 600px) {
        .grid { grid-template-columns: 1fr; gap: 1rem; }
        .header h1 { font-size: 2.4rem; }
        .header .sub { font-size: 0.7rem; letter-spacing: 4px; padding: 0.2rem 1.2rem; }
        .extra-filters { flex-direction: column; align-items: stretch; border-radius: 30px; padding: 0.8rem 1rem; }
        .extra-filters label { justify-content: center; }
        .extra-filters select { width: 100%; }
        .footer { flex-direction: column; text-align: center; gap: 0.5rem; }
        .toast { font-size: 0.8rem; padding: 0.6rem 1.2rem; width: 90%; }
        .card-img, .card-img-placeholder { height: 140px; }
        .header { min-height: 180px; }
        .header-content { min-height: 180px; }
        .header .ornament { font-size: 1.2rem; letter-spacing: 10px; }
    }
    @media (max-width: 400px) {
        .header h1 { font-size: 1.8rem; }
        .card-name { font-size: 0.95rem; }
        .card-price { font-size: 0.85rem; }
        .card-actions { flex-wrap: wrap; }
        .qty-wrapper { flex: 1 1 70px; height: 30px; }
        .qty-wrapper input { width: 28px; }
        .btn-order { flex: 2 1 80px; height: 30px; font-size: 0.7rem; }
    }
</style>

<div class="menu-wrapper">
    <!-- ===== EN-TÊTE AMÉLIORÉ AVEC IMAGE EN FOND ===== -->
    <header class="header">
        <div class="header-bg" style="background-image: url('{{ asset('images/entete.jpg') }}');"></div>
        <div class="header-content">
            <div class="ornament">
                <i class="fas fa-leaf"></i> <i class="fas fa-utensils"></i> <i class="fas fa-wine-glass-alt"></i>
            </div>
            <h1><span>{{ $nom_app ?? 'Le Gourmet' }}</span></h1>
            <div class="sub">
                <i class="fas fa-star"></i> Carte des saisons <i class="fas fa-star"></i>
            </div>
            <div class="header-separator"></div>
        </div>
    </header>

    <!-- ===== RECHERCHE ===== -->
    <div class="search-bar">
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="Rechercher un plat, une catégorie…" />
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- ===== FILTRES CATÉGORIES ===== -->
    <div class="filters" role="tablist">
        <button class="filter-btn active" data-category="all" role="tab" aria-selected="true">
            <i class="fas fa-th"></i> Tous
        </button>
        @foreach ($societes as $categorie)
            <button class="filter-btn" data-category="cat_{{ $categorie->id }}" role="tab" aria-selected="false">
                <i class="fas fa-tag"></i> {{ $categorie->nom }}
            </button>
        @endforeach
    </div>

    <!-- ===== FILTRES SUPPLÉMENTAIRES ===== -->
    <div class="extra-filters">
        <label>
            <i class="fas fa-chart-pie"></i> Activité :
            <select id="filterActivite">
                <option value="all">Toutes</option>
                <option value="none">Aucune</option>
                @foreach ($activites as $activite)
                    <option value="act_{{ $activite->id }}">{{ $activite->nom }}</option>
                @endforeach
            </select>
        </label>
        <label>
            <i class="fas fa-user"></i> Serveur :
            <select id="filterUser">
                <option value="all">Tous</option>
                @php $uniqueUsers = []; @endphp
                @foreach ($utilisateurs as $data)
                    @if(!in_array($data->id, $uniqueUsers))
                        @php $uniqueUsers[] = $data->id; @endphp
                        <option value="{{ $data->id }}">{{ $data->name ?? 'N/A' }}</option>
                    @endif
                @endforeach
            </select>
        </label>
        <button id="resetFilters"><i class="fas fa-undo-alt"></i> Réinitialiser</button>
    </div>

    <!-- ===== GRILLE DES PLATS ===== -->
    <div class="grid" id="menuGrid">
        @php
            $grouped = $articles->groupBy('societe_id');
        @endphp

        @foreach ($grouped as $categorieId => $articlesOfCategorie)
            @php
                $categorieNom = $societes->firstWhere('id', $categorieId)->nom ?? 'Non catégorisé';
            @endphp
            <div class="categorie-section" data-categorie-id="{{ $categorieId }}" style="grid-column: 1 / -1; margin-bottom: 0.2rem;">
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #2c3e50; margin: 0 0 0.8rem 0; border-bottom: 2px solid #e67e22; padding-bottom: 0.4rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fas fa-folder-open" style="color: #e67e22;"></i> {{ $categorieNom }}
                    <span class="count-badge" style="background: #e67e22; color: #fff; border-radius: 50px; padding: 0.1rem 0.8rem; font-size: 0.7rem; font-weight: 600;">{{ count($articlesOfCategorie) }}</span>
                </h3>
            </div>
            @foreach ($articlesOfCategorie as $data)
                @php
                    $deviseLabel = ($data->devise == 0) ? 'USD' : 'CDF';
                    $imagePath = $data->image ? asset('storage/'.$data->image) : null;
                    $prixDetail = number_format($data->prix_detail, 0, ',', ' ') . ' ' . $deviseLabel;
                    $desc = 'Stock : ' . ($data->avoir_stock == 1 ? $data->stock . ' (seuil ' . $data->seuil_minimum . '-' . $data->seuil_maximum . ')' : 'Indéterminé');
                    $tag = $categorieNom;
                    $activiteNom = ($data->activite_id == 0 || $data->activite_id == '0') ? null : ($activites->firstWhere('id', $data->activite_id)->nom ?? null);
                    $activiteId = $data->activite_id ?? '0';
                    $userId = $data->user_id ?? '';
                @endphp
                <div class="card"
                     data-category="cat_{{ $categorieId }}"
                     data-activite="{{ $activiteId }}"
                     data-user="{{ $userId }}"
                     data-nom="{{ strtolower($data->nom_article) }}"
                     data-desc="{{ strtolower($desc) }}"
                     data-tag="{{ strtolower($tag) }}">
                    @if ($imagePath)
                        <img class="card-img" src="{{ $imagePath }}" alt="{{ $data->nom_article }}" loading="lazy" />
                    @else
                        <div class="card-img-placeholder">
                            <i class="fas fa-utensils"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="card-top">
                            <span class="card-name">{{ $data->nom_article }}</span>
                            <span class="card-price">{{ $prixDetail }}</span>
                        </div>
                        <div class="card-desc">{{ $desc }}</div>
                        <div class="card-tags">
                            <span class="tag"><i class="fas fa-tag"></i> {{ $tag }}</span>
                            @if ($activiteNom)
                                <span class="tag tag-activite"><i class="fas fa-chart-pie"></i> {{ $activiteNom }}</span>
                            @endif
                        </div>
                        <div class="card-actions">
                            <div class="qty-wrapper">
                                <button class="qty-minus" type="button">−</button>
                                <input class="qty-input" type="number" value="1" min="1" step="1" />
                                <button class="qty-plus" type="button">+</button>
                            </div>
                            <button class="btn-order" data-name="{{ $data->nom_article }}" data-price="{{ $data->prix_detail }}" data-devise="{{ $deviseLabel }}">
                                <i class="fas fa-cart-plus"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        <div class="no-results" id="noResults">
            <i class="fas fa-utensils"></i>
            Aucun plat ne correspond à votre recherche.
        </div>
    </div>

    <!-- ===== PIED DE PAGE ===== -->
    <footer class="footer">
        <div class="info">
            <span><i class="fas fa-clock"></i> Mar–Sam : 12h–14h30 &amp; 19h–22h30</span>
            <span><i class="fas fa-phone-alt"></i> <a href="tel:+243123456789">+243 123 456 789</a></span>
            <span><i class="fas fa-map-pin"></i> Kinshasa, RDC</span>
        </div>
        <div class="social">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-tripadvisor"></i></a>
        </div>
    </footer>
</div>

<!-- ===== TOAST ===== -->
<div class="toast" id="toast"><i class="fas fa-check-circle"></i> <span id="toastMsg">Ajouté au panier !</span></div>

@push('scripts')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script>
    (function() {
        'use strict';

        document.addEventListener('DOMContentLoaded', function() {

            console.log('🔍 Script démarré – DOM prêt');

            // Éléments DOM
            const searchInput = document.getElementById('searchInput');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.card');
            const sections = document.querySelectorAll('.categorie-section');
            const noResults = document.getElementById('noResults');
            const filterActivite = document.getElementById('filterActivite');
            const filterUser = document.getElementById('filterUser');
            const resetBtn = document.getElementById('resetFilters');

            console.log('Cartes :', cards.length, 'Sections :', sections.length);

            // --- Panier ---
            let cart = JSON.parse(localStorage.getItem('restaurantCart')) || [];

            function updateCartBadge() {
                console.log('Panier :', cart.length, 'articles');
            }

            function addToCart(name, price, devise, quantity) {
                const item = { name, price, devise, quantity: parseInt(quantity) };
                const existing = cart.find(i => i.name === name);
                if (existing) {
                    existing.quantity += item.quantity;
                } else {
                    cart.push(item);
                }
                localStorage.setItem('restaurantCart', JSON.stringify(cart));
                updateCartBadge();
                showToast(`${item.quantity} × ${name} ajouté au panier`);
            }

            // --- Toast ---
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            let toastTimer = null;

            function showToast(message) {
                toastMsg.textContent = message;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            // --- Filtrage ---
            function applyFilters() {
                console.log('🔄 applyFilters()');

                const term = searchInput.value.toLowerCase().trim();
                const activeBtn = document.querySelector('.filter-btn.active');
                const activeCategory = activeBtn ? activeBtn.dataset.category : 'all';
                const activiteVal = filterActivite.value;
                const userVal = filterUser.value;

                let visibleCount = 0;

                cards.forEach((card, index) => {
                    const category = card.dataset.category || '';
                    const activite = String(card.dataset.activite || '').trim();
                    const user = String(card.dataset.user || '').trim();
                    const name = (card.dataset.nom || '').toLowerCase();
                    const desc = (card.dataset.desc || '').toLowerCase();
                    const tag = (card.dataset.tag || '').toLowerCase();

                    const matchCategory = (activeCategory === 'all' || category === activeCategory);

                    let matchActivite = false;
                    if (activiteVal === 'all') {
                        matchActivite = true;
                    } else if (activiteVal === 'none') {
                        matchActivite = (activite === '' || activite === '0');
                    } else {
                        const activiteId = activiteVal.replace('act_', '');
                        matchActivite = (activite === activiteId);
                    }

                    const matchUser = (userVal === 'all' || user === userVal);
                    const matchSearch = !term ||
                        name.includes(term) ||
                        desc.includes(term) ||
                        tag.includes(term) ||
                        category.includes(term);

                    const show = matchCategory && matchActivite && matchUser && matchSearch;

                    if (show) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // Sections
                sections.forEach(section => {
                    const catKey = 'cat_' + section.dataset.categorieId;
                    let hasVisible = false;
                    cards.forEach(card => {
                        if (card.dataset.category === catKey && !card.classList.contains('hidden')) {
                            hasVisible = true;
                        }
                    });
                    section.style.display = hasVisible ? '' : 'none';
                });

                noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
                saveFiltersToStorage();
            }

            // --- Sauvegarde / chargement ---
            function saveFiltersToStorage() {
                const activeBtn = document.querySelector('.filter-btn.active');
                localStorage.setItem('restaurantFilters', JSON.stringify({
                    search: searchInput.value,
                    category: activeBtn ? activeBtn.dataset.category : 'all',
                    activite: filterActivite.value,
                    user: filterUser.value
                }));
            }

            function loadFiltersFromStorage() {
                const saved = localStorage.getItem('restaurantFilters');
                if (!saved) return;
                try {
                    const filters = JSON.parse(saved);
                    if (filters.search !== undefined) searchInput.value = filters.search;
                    if (filters.category) {
                        filterButtons.forEach(btn => {
                            const isActive = (btn.dataset.category === filters.category);
                            btn.classList.toggle('active', isActive);
                            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                        });
                    }
                    if (filters.activite) filterActivite.value = filters.activite;
                    if (filters.user) filterUser.value = filters.user;
                    applyFilters();
                } catch (e) {
                    console.warn('Erreur chargement filtres', e);
                }
            }

            // --- Événements ---
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => {
                        b.classList.remove('active');
                        b.setAttribute('aria-selected', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    applyFilters();
                });
            });

            searchInput.addEventListener('input', applyFilters);
            filterActivite.addEventListener('change', applyFilters);
            filterUser.addEventListener('change', applyFilters);

            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterButtons.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                const allBtn = document.querySelector('.filter-btn[data-category="all"]');
                if (allBtn) {
                    allBtn.classList.add('active');
                    allBtn.setAttribute('aria-selected', 'true');
                }
                filterActivite.value = 'all';
                filterUser.value = 'all';
                applyFilters();
            });

            // --- Quantités et panier ---
            document.querySelectorAll('.qty-wrapper').forEach(wrapper => {
                const input = wrapper.querySelector('.qty-input');
                const minus = wrapper.querySelector('.qty-minus');
                const plus = wrapper.querySelector('.qty-plus');

                minus.addEventListener('click', function() {
                    let val = parseInt(input.value) || 1;
                    if (val > 1) input.value = val - 1;
                });

                plus.addEventListener('click', function() {
                    let val = parseInt(input.value) || 1;
                    input.value = val + 1;
                });

                input.addEventListener('input', function() {
                    let val = parseInt(this.value);
                    if (isNaN(val) || val < 1) this.value = 1;
                });
            });

            document.querySelectorAll('.btn-order').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const card = this.closest('.card');
                    const input = card.querySelector('.qty-input');
                    const quantity = input ? input.value : 1;
                    const name = this.dataset.name || 'Plat';
                    const price = parseFloat(this.dataset.price) || 0;
                    const devise = this.dataset.devise || 'CDF';
                    addToCart(name, price, devise, quantity);
                });
            });

            // --- Initialisation ---
            loadFiltersFromStorage();
            updateCartBadge();
            console.log('✅ Script initialisé');
        });
    })();
</script>
@endpush

@endsection
