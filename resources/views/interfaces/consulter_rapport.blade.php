<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Mois;
use App\Models\Annees;
use App\Models\Articles;
use App\Models\Postes;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Utilisateurs;
use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Paiesfactures;
use App\Models\Paiementsfactures;
use App\Models\Clients;

?>
@extends('layouts.main')
@section('title', 'CONTROLAPP')
@section('name', 'CONSULTER RAPPORT')
@section('body')
@include('composants.preload')
@include('composants.header')
@include('composants.sidebar')
@include('composants.chat')
<style>
/* =============================================
   DESIGN PREMIUM
   ============================================= */

body {
    margin: 0;
    padding: 0;
    background: #f0f4f8;
}

.content .container {
    max-width: 100% !important;
    width: 100%;
    padding: 0.5rem 1.5rem !important;
    margin: 0 auto;
    background: #f8fafc;
}

:root {
    --bleu-nuit: #0a192f;
    --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --border-radius-xl: 20px;
    --border-radius-lg: 16px;
}

#bloc_1 {
    background: rgba(255, 255, 255, 0.96);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-premium);
    padding: 1rem 1.5rem !important;
    margin-bottom: 1rem;
}

h4 {
    font-weight: 700;
    border-left: 6px solid #e31b23;
    padding-left: 18px;
    margin-bottom: 16px;
    color: var(--bleu-nuit);
}

.table-responsive {
    overflow-x: auto;
    overflow-y: visible !important;
    border-radius: var(--border-radius-lg);
}

.table {
    width: 100%;
    min-width: 800px;
    background: white;
    border-collapse: collapse;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    table-layout: auto;
}

/* ========== EN-TÊTE AVEC COULEUR D'ORIGINE ========== */
.table thead th {
    background: #E7F5FE !important;
    color: #0a192f;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 14px 12px !important;
    border-bottom: 2px solid #cbd5e1 !important;
    border-right: 1px solid #d0e2f2;
    white-space: normal;
    word-break: break-word;
}

.table thead th:last-child {
    border-right: none;
}

/* --- Lignes du tableau : padding réduit --- */
.table tbody tr {
    transition: all 0.15s ease;
    border-bottom: 1px solid #eef2f6;
}


.table tbody tr:hover {
    background: #f0f5fe !important;
}

.table tbody td {
    padding: 6px 8px !important;
    vertical-align: middle !important;
    font-weight: 500;
    font-size: 0.8rem;
    color: #1e2a3e;
    word-break: break-word;
    border-bottom: 1px solid #eef2f6;
    line-height: 1.3;
}

.table tbody td:last-child {
    text-align: center;
    vertical-align: middle;
    overflow: visible !important;
}

/* Alignement image + texte */
.table tbody td .profile-thumb {
    vertical-align: middle;
    margin-right: 8px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.table tbody td .profile-thumb:hover {
    transform: scale(1.1);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr.highlight {
    background-color: #fff3cd !important;
    animation: highlightFlash 1s ease;
}

/* Menu 3 points */
.dropdown-wrapper {
    position: relative;
    display: inline-block;
    overflow: visible !important;
}

.btn-three-dots {
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-three-dots:hover {
    background: #e0e7ff;
    transform: scale(1.05);
}

.btn-three-dots i {
    font-size: 1.2rem;
    color: #475569;
}

/* Menu dropdown - position dynamique */
.custom-dropdown-menu {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    min-width: 170px;
    z-index: 999999;
    overflow-y: auto;
    max-height: 300px;
    display: none;
    padding: 6px 0;
}

.custom-dropdown-menu.show {
    display: block;
    animation: dropdownFade 0.15s ease-out;
}

@keyframes dropdownFade {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: #1e293b;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    background: white;
    width: 100%;
    text-align: left;
    transition: background 0.2s;
    text-decoration: none;
}

.dropdown-item:hover {
    background: #f1f5f9;
}

.dropdown-item i {
    font-size: 1rem;
    width: 20px;
}

.dropdown-item i.zmdi-eye { color: #3b82f6; }
.dropdown-item i.zmdi-edit { color: #eab308; }
.dropdown-item i.zmdi-delete { color: #ef4444; }
.dropdown-item i.zmdi-print { color: #10b981; }

/* Divider */
.dropdown-divider {
    height: 1px;
    background: #eef2f6;
    margin: 4px 0;
}

/* Popup détails */
.detail-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.30);
    z-index: 100000;
    justify-content: center;
    align-items: center;
    backdrop-filter: none;
}

.detail-modal.show {
    display: flex;
}

.detail-modal-content {
    background: white;
    border-radius: 28px;
    max-width: 90vw;
    max-height: 90vh;
    width: 500px;
    overflow: auto;
    animation: modalSlide 0.3s ease;
}

@keyframes modalSlide {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.detail-modal-header {
    background: #3B82F6 !important;
    padding: 18px 20px;
    color: white !important;
    border-radius: 28px 28px 0 0;
}

.detail-modal-header h3 {
    margin: 0;
    font-weight: 700;
    font-size: 1.3rem;
    color: white !important;
}

.detail-modal-body {
    padding: 20px 30px;
    background: #f8fafc;
}

.detail-info-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-light);
    border: 1px solid #eef2f6;
}

.detail-info-label {
    font-weight: 700;
    color: var(--bleu-nuit);
    font-size: 0.8rem;
}

.detail-info-value {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
}

.detail-modal-footer {
    padding: 20px 25px 28px;
    background: white;
    border-radius: 0 0 28px 28px;
    text-align: center;
}

.detail-modal-footer button {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    padding: 10px 30px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

/* Popup confirmation suppression */
.confirm-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 100000;
    justify-content: center;
    align-items: center;
}

.confirm-modal.show {
    display: flex;
}

.confirm-modal-content {
    background: white;
    border-radius: 28px;
    width: 350px;
    text-align: center;
    overflow: hidden;
}

.confirm-modal-header {
    background: #ef4444;
    padding: 18px;
    color: white;
}

.confirm-modal-body {
    padding: 20px;
}

.confirm-modal-footer {
    padding: 15px 20px 20px;
    display: flex;
    gap: 10px;
    justify-content: center;
}

.confirm-modal-footer button {
    padding: 8px 20px;
    border-radius: 40px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.btn-confirm-yes {
    background: #ef4444;
    color: white;
}

.btn-confirm-no {
    background: #e2e8f0;
    color: #475569;
}

/* Filtres */
.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    background: white;
    padding: 0.8rem 1.2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--bleu-nuit);
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-group .form-control {
    height: 36px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 10px;
}

.student-count-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
    float: right;
}

.header-with-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.header-with-buttons h6 {
    margin: 0;
    color: rgba(0, 0, 0, 0.6);
    font-size: 0.9rem;
}

/* ===== STYLES POUR LA CARTE ===== */
#detailMap {
    height: 400px;
    border-radius: 16px;
    border: 1px solid #eef2f6;
    background: #eef2f6;
    width: 100%;
}
.leaflet-popup-content {
    font-size: 0.85rem;
    line-height: 1.4;
}
.leaflet-popup-content strong {
    color: #0a192f;
}
.leaflet-popup-content img {
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}
.custom-marker {
    background: transparent;
    border: none;
}
.info-marker {
    background: transparent;
    border: none;
}

@media (max-width: 768px) {
    .content .container {
        padding: 0.4rem 0.6rem !important;
    }
    .custom-dropdown-menu {
        min-width: 150px;
        right: -10px;
    }
    #detailMap {
        height: 300px;
    }
}
</style>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-with-buttons">
                    <h6>{{ strtoupper(Auth::user()->name) }}&nbsp; <i class="zmdi zmdi-chevron-right"></i> &nbsp; Consulter rapport</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="bloc_1" class="col-lg-12">
                <h4><i style="font-size: 32px;" class="zmdi zmdi-home text-info"></i> Liste des pointages</h4>

                <div class="filters-container">
                    <!-- ========== FILTRE DATE RANGE (avec défaut aujourd'hui) ========== -->
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-calendar text-danger"></i> Période (DD/MM/YYYY)</label>
                        <input type="text" id="filterDateRange" class="form-control"
                               placeholder="Sélectionner une période" value="">
                    </div>
                    <!-- ========== FIN FILTRE DATE RANGE ========== -->
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-account-box text-danger"></i> Nom</label>
                        <input type="text" id="filterEleve" class="form-control" placeholder="Nom">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-briefcase text-danger"></i> Poste</label>
                        <input type="text" id="filterPoste" class="form-control" placeholder="Poste">
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-info text-danger"></i> Préstation</label>
                        <select id="filterPrestation" class="form-control">
                            <option value="">Toutes</option>
                            <option value="journée">Journée</option>
                            <option value="nuit">Nuit</option>
                            <option value="repos">Repos</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="zmdi zmdi-time text-danger"></i> Horaire</label>
                        <select id="filterHoraire" class="form-control">
                            <option value="">Tous</option>
                            <option value="06h00 - 18h00">06h00 - 18h00</option>
                            <option value="18h00 - 06h00">18h00 - 06h00</option>
                            <option value="repos">Repos</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button id="resetFilters" class="btn btn-secondary btn-sm" style="border-radius: 40px; padding: 6px 16px;">
                            <i class="zmdi zmdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>

                <div style="overflow: hidden; margin-bottom: 10px;">
                    <span class="student-count-badge">
                        <i class="zmdi zmdi-view-calendar"></i> Prestations : <span id="studentCount">0</span>
                    </span>
                </div>

                <div id="content_utilisateur">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Fonction</th>
                                    <th>Poste</th>
                                    <th>Préstation</th>
                                    <th>Horaire</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data_prestations as $data_p)
                                    <?php $prestations = json_decode($data_p->details, true); ?>
                                    @foreach ($prestations as $data)
                                        <?php
                                            $users = User::where(['id' => $data['user_id']])->first();
                                            $fonction = Groupes::where(["id" => $users->role])->first()["nom"] ?? '';
                                            $posteNom = Postes::where(["id" => $data_p->poste_id])->first()["nom"] ?? '';
                                            $postelatitude = Postes::where(["id" => $data_p->poste_id])->first()["latitude"] ?? '';
                                            $postelongitude = Postes::where(["id" => $data_p->poste_id])->first()["longitude"] ?? '';
                                            $postedescription = Postes::where(["id" => $data_p->poste_id])->first()["description"] ?? '';
                                            $dateFormatee = ($data['date'] == date("Y-m-d")) ? date("d/m/Y") : implode('/', array_reverse(explode('-', $data['date'])));
                                            $isToday = ($data['date'] == date("Y-m-d"));
                                            $uniqueId = 'row_' . $i . '_' . rand(1000, 9999);

                                            // ===== DÉTERMINATION DES TYPES DE POINTAGE =====
                                            if ($data["pointages"]) {
                                                // ENTRÉE
                                                $data_entree_heure = $data["pointages"]["entree"]["heure"];
                                                $data_entree_etat = $data["pointages"]["entree"]["etat"];
                                                $data_entree_latitude = $data["pointages"]["entree"]["latitude"];
                                                $data_entree_longitude = $data["pointages"]["entree"]["longitude"];
                                                if ($data_entree_etat == 0) {
                                                    $data_entree_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_entree_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_entree_resultat = "Aucun";
                                                    $type_entree = 'aucun';
                                                } else {
                                                    $data_entree_capture1 = $users->image;
                                                    $data_entree_capture2 = $data["pointages"]["entree"]["capture_2"];
                                                    $data_entree_resultat = "Succès";
                                                    if ($data_entree_capture2 == "./storage/images/user/visage_par_defaut.png") {
                                                        $type_entree = 'systeme';
                                                    } else {
                                                        $type_entree = 'utilisateur';
                                                    }
                                                }

                                                // SORTIE
                                                $data_sortie_heure = $data["pointages"]["sortie"]["heure"];
                                                $data_sortie_etat = $data["pointages"]["sortie"]["etat"];
                                                $data_sortie_latitude = $data["pointages"]["sortie"]["latitude"];
                                                $data_sortie_longitude = $data["pointages"]["sortie"]["longitude"];
                                                if ($data_sortie_etat == 0) {
                                                    $data_sortie_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_sortie_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_sortie_resultat = "Aucun";
                                                    $type_sortie = 'aucun';
                                                } else {
                                                    $data_sortie_capture1 = $users->image;
                                                    $data_sortie_capture2 = $data["pointages"]["sortie"]["capture_2"];
                                                    $data_sortie_resultat = "Succès";
                                                    if ($data_sortie_capture2 == "./storage/images/user/visage_par_defaut.png") {
                                                        $type_sortie = 'systeme';
                                                    } else {
                                                        $type_sortie = 'utilisateur';
                                                    }
                                                }

                                                // RONDE 1
                                                $data_ronde1_debut = $data["pointages"]["ronde_a_1"]["heure_debut"];
                                                $data_ronde1_fin = $data["pointages"]["ronde_a_1"]["heure_fin"];
                                                $data_ronde1_etat = $data["pointages"]["ronde_a_1"]["etat"];
                                                $data_ronde1_latitude = $data["pointages"]["ronde_a_1"]["latitude"];
                                                $data_ronde1_longitude = $data["pointages"]["ronde_a_1"]["longitude"];
                                                if ($data_ronde1_etat == 0) {
                                                    $data_ronde1_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde1_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde1_resultat = "Aucun";
                                                    $type_ronde1 = 'aucun';
                                                } else {
                                                    $data_ronde1_capture1 = $data["pointages"]["ronde_a_1"]["capture_1"];
                                                    $data_ronde1_capture2 = $data['pointages']['ronde_a_1']["capture_2"];
                                                    if ($data_ronde1_capture2 == "./storage/images/user/visage_par_defaut.png") {
                                                        $data_ronde1_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_1']['heure_reponse'] . ' (Système)';
                                                        $type_ronde1 = 'systeme';
                                                    } else {
                                                        $data_ronde1_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_1']['heure_reponse'] . ' (' . ('Utilisateur' ?? '') . ')';
                                                        $type_ronde1 = 'utilisateur';
                                                    }
                                                }

                                                // RONDE 2
                                                $data_ronde2_debut = $data["pointages"]["ronde_a_2"]["heure_debut"];
                                                $data_ronde2_fin = $data["pointages"]["ronde_a_2"]["heure_fin"];
                                                $data_ronde2_etat = $data["pointages"]["ronde_a_2"]["etat"];
                                                $data_ronde2_latitude = $data["pointages"]["ronde_a_2"]["latitude"];
                                                $data_ronde2_longitude = $data["pointages"]["ronde_a_2"]["longitude"];
                                                if ($data_ronde2_etat == 0) {
                                                    $data_ronde2_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde2_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde2_resultat = "Aucun";
                                                    $type_ronde2 = 'aucun';
                                                } else {
                                                    $data_ronde2_capture1 = $data["pointages"]["ronde_a_2"]["capture_1"];
                                                    $data_ronde2_capture2 = $data['pointages']['ronde_a_2']["capture_2"];
                                                    if ($data_ronde2_capture2 == "./storage/images/user/visage_par_defaut.png") {
                                                        $data_ronde2_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_2']['heure_reponse'] . ' (Système)';
                                                        $type_ronde2 = 'systeme';
                                                    } else {
                                                        $data_ronde2_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_2']['heure_reponse'] . ' (' . ('Utilisateur' ?? '') . ')';
                                                        $type_ronde2 = 'utilisateur';
                                                    }
                                                }

                                                // RONDE 3
                                                $data_ronde3_debut = $data["pointages"]["ronde_a_3"]["heure_debut"];
                                                $data_ronde3_fin = $data["pointages"]["ronde_a_3"]["heure_fin"];
                                                $data_ronde3_etat = $data["pointages"]["ronde_a_3"]["etat"];
                                                $data_ronde3_latitude = $data["pointages"]["ronde_a_3"]["latitude"];
                                                $data_ronde3_longitude = $data["pointages"]["ronde_a_3"]["longitude"];
                                                if ($data_ronde3_etat == 0) {
                                                    $data_ronde3_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde3_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                    $data_ronde3_resultat = "Aucun";
                                                    $type_ronde3 = 'aucun';
                                                } else {
                                                    $data_ronde3_capture1 = $data["pointages"]["ronde_a_3"]["capture_1"];
                                                    $data_ronde3_capture2 = $data['pointages']['ronde_a_3']["capture_2"];
                                                    if ($data_ronde3_capture2 == "./storage/images/user/visage_par_defaut.png") {
                                                        $data_ronde3_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_3']['heure_reponse'] . ' (Système)';
                                                        $type_ronde3 = 'systeme';
                                                    } else {
                                                        $data_ronde3_resultat = "Succès, Heure de pointage à " . $data['pointages']['ronde_a_3']['heure_reponse'] . ' (' . ('Utilisateur' ?? '') . ')';
                                                        $type_ronde3 = 'utilisateur';
                                                    }
                                                }

                                                // Alertes (inchangé)
                                                $alertes = [];
                                                foreach ($all_alertes as $al) {
                                                    $created_at_1 = explode(" ", $al->created_at);
                                                    $created_at_2 = $created_at_1[0];
                                                    if ($created_at_2 == $data['date']) {
                                                        if ($data['user_id'] == $al->user_id) {
                                                            if ($al->user_id_transfert == 0) {
                                                                $alertes[] = 'Envoyé par : ' . User::where(['id' => $al->user_id])->first()['name'] . ', Motif : ' . $al->motif . ', Date d\'envoie : ' . explode("-", $created_at_2)[2] . '/' . explode("-", $created_at_2)[1] . '/' . explode("-", $created_at_2)[0] . ' à ' . $created_at_1[1] . ', Transférée par : -' . ', Gerée par : -, Date de transfère : -';
                                                            } else {
                                                                if ($al->user_id_desactiver_etat_2 == 0) {
                                                                    $alertes[] = 'Envoyé par : ' . User::where(['id' => $al->user_id])->first()['name'] . ', Motif : ' . $al->motif . ', Date d\'envoie : ' . explode("-", $created_at_2)[2] . '/' . explode("-", $created_at_2)[1] . '/' . explode("-", $created_at_2)[0] . ' à ' . $created_at_1[1] . ', Transférée par : ' . User::where(['id' => $al->user_id_transfert])->first()['name'] . ', Date de transfère : ' . explode(" ", $al->date_transfert)[0] . ' à ' . explode(" ", $al->date_transfert)[1] . ', Gerer par : -, Date de gestion : -';
                                                                } else {
                                                                    $alertes[] = 'Envoyé par : ' . User::where(['id' => $al->user_id])->first()['name'] . ', Motif : ' . $al->motif . ', Date d\'envoie : ' . explode("-", $created_at_2)[2] . '/' . explode("-", $created_at_2)[1] . '/' . explode("-", $created_at_2)[0] . ' à ' . $created_at_1[1] . ', Transférée par : ' . User::where(['id' => $al->user_id_transfert])->first()['name'] . ', Date de transfère : ' . explode(" ", $al->date_transfert)[0] . ' à ' . explode(" ", $al->date_transfert)[1] . ', Gerer par : ' . User::where(['id' => $al->user_id_desactiver_etat_2])->first()['name'] . ', Date de gestion : ' . explode(" ", $al->date_desactiver_etat_2)[0] . ' à ' . explode(" ", $al->date_desactiver_etat_2)[1];
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                // Pas de pointage (repos)
                                                $data_entree_heure = "Repos";
                                                $data_entree_etat = "Repos";
                                                $data_entree_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                $data_entree_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                $data_entree_resultat = "Repos";
                                                $type_entree = 'aucun';

                                                $data_sortie_heure = "Repos";
                                                $data_sortie_etat = "Repos";
                                                $data_sortie_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                $data_sortie_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                $data_sortie_resultat = "Repos";
                                                $type_sortie = 'aucun';

                                                $data_ronde1_debut = "Repos";
                                                $data_ronde1_fin = "Repos";
                                                $data_ronde1_etat = "Repos";
                                                $data_ronde1_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde1_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde1_resultat = "Repos";
                                                $type_ronde1 = 'aucun';

                                                $data_ronde2_debut = "Repos";
                                                $data_ronde2_fin = "Repos";
                                                $data_ronde2_etat = "Repos";
                                                $data_ronde2_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde2_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde2_resultat = "Repos";
                                                $type_ronde2 = 'aucun';

                                                $data_ronde3_debut = "Repos";
                                                $data_ronde3_fin = "Repos";
                                                $data_ronde3_etat = "Repos";
                                                $data_ronde3_capture1 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde3_capture2 = "./storage/images/user/visage_par_defaut.png";
                                                $data_ronde3_resultat = "Repos";
                                                $type_ronde3 = 'aucun';

                                                $data_entree_latitude = null;
                                                $data_entree_longitude = null;
                                                $data_sortie_latitude = null;
                                                $data_sortie_longitude = null;
                                                $data_ronde1_latitude = null;
                                                $data_ronde1_longitude = null;
                                                $data_ronde2_latitude = null;
                                                $data_ronde2_longitude = null;
                                                $data_ronde3_latitude = null;
                                                $data_ronde3_longitude = null;

                                                $alertes = [];
                                            }
                                        ?>
                                        @if ($isToday)
                                            <tr id="{{ $uniqueId }}">
                                                <td>{{ $i }}</td>
                                                <td>{{ $users->matricule ?? '' }}</td>
                                                <td>{{ $users->name ?? '' }}</td>
                                                <td>{{ $fonction }}</td>
                                                <td>{{ $posteNom }}</td>
                                                <td>
                                                    @if($data['service'] == "journée")
                                                        <i class="zmdi zmdi-info text-success"></i> <span class="text-success">{{ $data['service'] }}</span>
                                                    @elseif($data['service'] == "nuit")
                                                        <i class="zmdi zmdi-info text-info"></i> <span class="text-info">{{ $data['service'] }}</span>
                                                    @else
                                                        <i class="zmdi zmdi-info text-danger"></i> <span class="text-danger">{{ $data['service'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data['horaire'] == "06h00 - 18h00")
                                                        <i class="zmdi zmdi-time text-success"></i> <span class="text-success">{{ $data['horaire'] }}</span>
                                                    @elseif($data['horaire'] == "18h00 - 06h00")
                                                        <i class="zmdi zmdi-time text-info"></i> <span class="text-info">{{ $data['horaire'] }}</span>
                                                    @else
                                                        <i class="zmdi zmdi-time text-danger"></i> <span class="text-danger">{{ $data['horaire'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($isToday && $data['horaire'] == "06h00 - 18h00")
                                                        <i class="zmdi zmdi-time text-success"></i> <span class="text-success">{{ $dateFormatee }}</span>
                                                    @elseif($isToday && $data['horaire'] == "18h00 - 06h00")
                                                        <i class="zmdi zmdi-time text-info"></i> <span class="text-info">{{ $dateFormatee }}</span>
                                                    @else
                                                        <i class="zmdi zmdi-time text-danger"></i> <span class="text-danger"><span>{{ "repos" }}</span></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown-wrapper">
                                                        <button class="btn-three-dots" data-target="menu-{{ $i }}">
                                                            <i class="zmdi zmdi-more-vert"></i>
                                                        </button>
                                                        <div id="menu-{{ $i }}" class="custom-dropdown-menu">
                                                            <button class="dropdown-item view-detail"
                                                            data-nom="{{ $users->name ?? '' }}"
                                                            data-matricule="{{ $users->matricule ?? '' }}"
                                                            data-fonction="{{ $fonction }}"
                                                            data-poste="{{ $posteNom }}"
                                                            data-poste-description="{{ $postedescription }}"
                                                            data-prestation="{{ $data['service'] ?? '' }}"
                                                            data-horaire="{{ $data['horaire'] ?? '' }}"
                                                            data-date="{{ $dateFormatee }}"

                                                            {{-- coordonnées du poste --}}
                                                            data-poste-lat="{{ $postelatitude ?? '' }}"
                                                            data-poste-lng="{{ $postelongitude ?? '' }}"
                                                            {{-- coordonnées entrée --}}
                                                            data-entree-lat="{{ $data_entree_latitude ?? '' }}"
                                                            data-entree-lng="{{ $data_entree_longitude ?? '' }}"
                                                            {{-- coordonnées sortie --}}
                                                            data-sortie-lat="{{ $data_sortie_latitude ?? '' }}"
                                                            data-sortie-lng="{{ $data_sortie_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 1 --}}
                                                            data-ronde1-lat="{{ $data_ronde1_latitude ?? '' }}"
                                                            data-ronde1-lng="{{ $data_ronde1_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 2 --}}
                                                            data-ronde2-lat="{{ $data_ronde2_latitude ?? '' }}"
                                                            data-ronde2-lng="{{ $data_ronde2_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 3 --}}
                                                            data-ronde3-lat="{{ $data_ronde3_latitude ?? '' }}"
                                                            data-ronde3-lng="{{ $data_ronde3_longitude ?? '' }}"

                                                            {{-- TYPES DE POINTAGE --}}
                                                            data-entree-type="{{ $type_entree }}"
                                                            data-sortie-type="{{ $type_sortie }}"
                                                            data-ronde1-type="{{ $type_ronde1 }}"
                                                            data-ronde2-type="{{ $type_ronde2 }}"
                                                            data-ronde3-type="{{ $type_ronde3 }}"

                                                            {{-- ENTRÉE --}}
                                                            data-entree-heure="{{ $data_entree_heure }}"
                                                            data-entree-etat="{{ $data_entree_etat }}"
                                                            data-entree-capture1="{{ $data_entree_capture1 }}"
                                                            data-entree-capture2="{{ $data_entree_capture2 }}"
                                                            data-entree-resultat="{{ $data_entree_resultat }}"
                                                            {{-- SORTIE --}}
                                                            data-sortie-heure="{{ $data_sortie_heure }}"
                                                            data-sortie-etat="{{ $data_sortie_etat }}"
                                                            data-sortie-capture1="{{ $data_sortie_capture1 }}"
                                                            data-sortie-capture2="{{ $data_sortie_capture2 }}"
                                                            data-sortie-resultat="{{ $data_sortie_resultat }}"
                                                            {{-- RONDE 1  --}}
                                                            data-ronde1-debut="{{ $data_ronde1_debut }}"
                                                            data-ronde1-fin="{{ $data_ronde1_fin }}"
                                                            data-ronde1-etat="{{ $data_ronde1_etat }}"
                                                            data-ronde1-capture1="{{ $data_ronde1_capture1 }}"
                                                            data-ronde1-capture2="{{ $data_ronde1_capture2 }}"
                                                            data-ronde1-resultat="{{ $data_ronde1_resultat }}"
                                                            {{-- RONDE 2  --}}
                                                            data-ronde2-debut="{{ $data_ronde2_debut }}"
                                                            data-ronde2-fin="{{ $data_ronde2_fin }}"
                                                            data-ronde2-etat="{{ $data_ronde2_etat }}"
                                                            data-ronde2-capture1="{{ $data_ronde2_capture1 }}"
                                                            data-ronde2-capture2="{{ $data_ronde2_capture2 }}"
                                                            data-ronde2-resultat="{{ $data_ronde2_resultat }}"
                                                            {{-- RONDE 3 --}}
                                                            data-ronde3-debut="{{ $data_ronde3_debut }}"
                                                            data-ronde3-fin="{{ $data_ronde3_fin }}"
                                                            data-ronde3-etat="{{ $data_ronde3_etat }}"
                                                            data-ronde3-capture1="{{ $data_ronde3_capture1 }}"
                                                            data-ronde3-capture2="{{ $data_ronde3_capture2 }}"
                                                            data-ronde3-resultat="{{ $data_ronde3_resultat }}"

                                                            {{-- ALERTES (JSON encodé) --}}
                                                            data-alertes-count="{{ count($alertes) }}"
                                                            data-alertes='{{ json_encode($alertes) }}'>
                                                                <i class="zmdi zmdi-eye"></i> Détails
                                                            </button>
                                                            <button style="display: none;" class="dropdown-item edit-item"
                                                                data-id="{{ $uniqueId }}"
                                                                data-nom="{{ $users->name ?? '' }}">
                                                                <i class="zmdi zmdi-edit"></i> Modifier
                                                            </button>
                                                            <div style="display: none;" class="dropdown-divider"></div>
                                                            <button style="display: none;" class="dropdown-item delete-item"
                                                                data-id="{{ $uniqueId }}"
                                                                data-nom="{{ $users->name ?? '' }}">
                                                                <i class="zmdi zmdi-delete"></i> Supprimer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                        <tr id="{{ $uniqueId }}">
                                                <td>{{ $i }}</td>
                                                <td>{{ $users->matricule ?? '' }}</td>
                                                <td>{{ $users->name ?? '' }}</td>
                                                <td>{{ $fonction }}</td>
                                                <td>{{ $posteNom }}</td>
                                                <td>
                                                    @if($data['service'] == "journée")
                                                        <i class="zmdi zmdi-info text-dark"></i> <span class="text-dark">{{ $data['service'] }}</span>
                                                    @elseif($data['service'] == "nuit")
                                                        <i class="zmdi zmdi-info text-dark"></i> <span class="text-dark">{{ $data['service'] }}</span>
                                                    @else
                                                        <i class="zmdi zmdi-info text-dark"></i> <span class="text-dark">{{ $data['service'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data['horaire'] == "06h00 - 18h00")
                                                        <i class="zmdi zmdi-time text-dark"></i> <span class="text-dark">{{ $data['horaire'] }}</span>
                                                    @elseif($data['horaire'] == "18h00 - 06h00")
                                                        <i class="zmdi zmdi-time text-dark"></i> <span class="text-dark">{{ $data['horaire'] }}</span>
                                                    @else
                                                        <i class="zmdi zmdi-time text-dark"></i> <span class="text-dark">{{ $data['horaire'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($isToday && $data['horaire'] == "06h00 - 18h00")
                                                        <i class="zmdi zmdi-time text-dark"></i> <span class="text-dark">{{ $dateFormatee }}</span>
                                                    @elseif($isToday && $data['horaire'] == "18h00 - 06h00")
                                                        <i class="zmdi zmdi-time text-dark"></i> <span class="text-dark">{{ $dateFormatee }}</span>
                                                    @else
                                                        <span>{{ $dateFormatee }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown-wrapper">
                                                        <button class="btn-three-dots" data-target="menu-{{ $i }}">
                                                            <i class="zmdi zmdi-more-vert"></i>
                                                        </button>
                                                        <div id="menu-{{ $i }}" class="custom-dropdown-menu">
                                                            <button class="dropdown-item view-detail"
                                                            data-nom="{{ $users->name ?? '' }}"
                                                            data-matricule="{{ $users->matricule ?? '' }}"
                                                            data-fonction="{{ $fonction }}"
                                                            data-poste="{{ $posteNom }}"
                                                            data-poste-description="{{ $postedescription }}"
                                                            data-prestation="{{ $data['service'] ?? '' }}"
                                                            data-horaire="{{ $data['horaire'] ?? '' }}"
                                                            data-date="{{ $dateFormatee }}"

                                                            {{-- coordonnées du poste --}}
                                                            data-poste-lat="{{ $postelatitude ?? '' }}"
                                                            data-poste-lng="{{ $postelongitude ?? '' }}"
                                                            {{-- coordonnées entrée --}}
                                                            data-entree-lat="{{ $data_entree_latitude ?? '' }}"
                                                            data-entree-lng="{{ $data_entree_longitude ?? '' }}"
                                                            {{-- coordonnées sortie --}}
                                                            data-sortie-lat="{{ $data_sortie_latitude ?? '' }}"
                                                            data-sortie-lng="{{ $data_sortie_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 1 --}}
                                                            data-ronde1-lat="{{ $data_ronde1_latitude ?? '' }}"
                                                            data-ronde1-lng="{{ $data_ronde1_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 2 --}}
                                                            data-ronde2-lat="{{ $data_ronde2_latitude ?? '' }}"
                                                            data-ronde2-lng="{{ $data_ronde2_longitude ?? '' }}"
                                                            {{-- coordonnées ronde 3 --}}
                                                            data-ronde3-lat="{{ $data_ronde3_latitude ?? '' }}"
                                                            data-ronde3-lng="{{ $data_ronde3_longitude ?? '' }}"

                                                            {{-- TYPES DE POINTAGE --}}
                                                            data-entree-type="{{ $type_entree }}"
                                                            data-sortie-type="{{ $type_sortie }}"
                                                            data-ronde1-type="{{ $type_ronde1 }}"
                                                            data-ronde2-type="{{ $type_ronde2 }}"
                                                            data-ronde3-type="{{ $type_ronde3 }}"

                                                            {{-- ENTRÉE --}}
                                                            data-entree-heure="{{ $data_entree_heure }}"
                                                            data-entree-etat="{{ $data_entree_etat }}"
                                                            data-entree-capture1="{{ $data_entree_capture1 }}"
                                                            data-entree-capture2="{{ $data_entree_capture2 }}"
                                                            data-entree-resultat="{{ $data_entree_resultat }}"
                                                            {{-- SORTIE --}}
                                                            data-sortie-heure="{{ $data_sortie_heure }}"
                                                            data-sortie-etat="{{ $data_sortie_etat }}"
                                                            data-sortie-capture1="{{ $data_sortie_capture1 }}"
                                                            data-sortie-capture2="{{ $data_sortie_capture2 }}"
                                                            data-sortie-resultat="{{ $data_sortie_resultat }}"
                                                            {{-- RONDE 1  --}}
                                                            data-ronde1-debut="{{ $data_ronde1_debut }}"
                                                            data-ronde1-fin="{{ $data_ronde1_fin }}"
                                                            data-ronde1-etat="{{ $data_ronde1_etat }}"
                                                            data-ronde1-capture1="{{ $data_ronde1_capture1 }}"
                                                            data-ronde1-capture2="{{ $data_ronde1_capture2 }}"
                                                            data-ronde1-resultat="{{ $data_ronde1_resultat }}"
                                                            {{-- RONDE 2  --}}
                                                            data-ronde2-debut="{{ $data_ronde2_debut }}"
                                                            data-ronde2-fin="{{ $data_ronde2_fin }}"
                                                            data-ronde2-etat="{{ $data_ronde2_etat }}"
                                                            data-ronde2-capture1="{{ $data_ronde2_capture1 }}"
                                                            data-ronde2-capture2="{{ $data_ronde2_capture2 }}"
                                                            data-ronde2-resultat="{{ $data_ronde2_resultat }}"
                                                            {{-- RONDE 3 --}}
                                                            data-ronde3-debut="{{ $data_ronde3_debut }}"
                                                            data-ronde3-fin="{{ $data_ronde3_fin }}"
                                                            data-ronde3-etat="{{ $data_ronde3_etat }}"
                                                            data-ronde3-capture1="{{ $data_ronde3_capture1 }}"
                                                            data-ronde3-capture2="{{ $data_ronde3_capture2 }}"
                                                            data-ronde3-resultat="{{ $data_ronde3_resultat }}"

                                                            {{-- ALERTES (JSON encodé) --}}
                                                            data-alertes-count="{{ count($alertes) }}"
                                                            data-alertes='{{ json_encode($alertes) }}'>
                                                                <i class="zmdi zmdi-eye"></i> Détails
                                                            </button>
                                                            <button style="display: none;" class="dropdown-item edit-item"
                                                                data-id="{{ $uniqueId }}"
                                                                data-nom="{{ $users->name ?? '' }}">
                                                                <i class="zmdi zmdi-edit"></i> Modifier
                                                            </button>
                                                            <div style="display: none;" class="dropdown-divider"></div>
                                                            <button style="display: none;" class="dropdown-item delete-item"
                                                                data-id="{{ $uniqueId }}"
                                                                data-nom="{{ $users->name ?? '' }}">
                                                                <i class="zmdi zmdi-delete"></i> Supprimer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- POPUP DETAILS -->
<div id="detailPopup" class="detail-modal">
    <div class="detail-modal-content" style="max-width: 95vw; width: 95vw; max-height: 90vh; display: flex; flex-direction: column; border-radius: 28px; overflow: hidden; background: white; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div class="detail-modal-header" style="flex-shrink: 0; background: linear-gradient(135deg, #1e3a5f, #0a192f); padding: 20px 25px; color: white !important; border-radius: 28px 28px 0 0;">
            <h3 style="margin:0; font-weight:700; font-size:1.4rem; color:white !important; display:flex; align-items:center; gap:12px;">
                <i class="zmdi zmdi-assignment" style="font-size:1.8rem;"></i>
                FICHE DE POINTAGE
                <span style="font-size:0.75rem; background:rgba(255,255,255,0.2); padding:4px 12px; border-radius:50px; margin-left:auto;">
                    <i class="zmdi zmdi-time"></i> Détails
                </span>
            </h3>
        </div>

        <div class="detail-modal-body" style="padding: 25px 30px; overflow-y: auto; flex: 1; background: #f8fafc;">

            <!-- ========== 1. INFOS GÉNÉRALES (Agent) ========== -->
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                <span style="background: #3B82F6; color:white; padding:4px 14px; border-radius:50px; font-size:0.7rem; font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">
                    <i class="zmdi zmdi-account"></i> Agent
                </span>
                <span style="font-size:0.8rem; color:#64748b;">Informations personnelles</span>
            </div>

            <div class="detail-info-grid" style="margin-bottom: 20px;">
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-account-box"></i> Matricule</span>
                    <span class="detail-info-value" id="detailMatricule" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-account"></i> Nom complet</span>
                    <span class="detail-info-value" id="detailNom" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-badge-check"></i> Fonction</span>
                    <span class="detail-info-value" id="detailFonction" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-pin"></i> Poste</span>
                    <span class="detail-info-value" id="detailPoste" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-info"></i> Préstation</span>
                    <span class="detail-info-value" id="detailPrestation" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-time"></i> Horaire</span>
                    <span class="detail-info-value" id="detailHoraire" style="font-weight:600; color:#0a192f;">-</span>
                </div>
                <div class="detail-info-row">
                    <span class="detail-info-label"><i class="zmdi zmdi-calendar"></i> Date</span>
                    <span class="detail-info-value" id="detailDate" style="font-weight:600; color:#0a192f;">-</span>
                </div>
            </div>

            <hr style="margin: 25px 0; border-top: 2px dashed #d1d5db;">

            <!-- ========== 2. SECTION POINTAGE ========== -->
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:18px;">
                <span style="background: #0a192f; color:white; padding:6px 18px; border-radius:50px; font-size:0.75rem; font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">
                    <i class="zmdi zmdi-time-restore"></i> Pointage
                </span>
                <span style="font-size:0.8rem; color:#64748b;">Détails des passages</span>
                <span id="detailDateBadge" style="margin-left:auto; font-size:0.7rem; background:#e2e8f0; padding:3px 12px; border-radius:50px; color:#475569;">
                    <i class="fa fa-info-circle"></i>
                </span>
            </div>

            <!-- 2.1 ENTRÉE et SORTIE -->
            <div class="row" style="margin-bottom:20px;">
                <div class="col-md-6">
                    <div class="card" style="border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background:#ffffff; height:100%; border:1px solid #eef2f6;">
                        <div class="card-body" style="padding:16px 18px;">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                <span style="background: #22c55e; color:white; padding:2px 10px; border-radius:50px; font-size:0.65rem; font-weight:700; text-transform:uppercase;">
                                    <i class="zmdi zmdi-arrow-right"></i> Entrée
                                </span>
                                <span style="font-size:0.7rem; color:#94a3b8;">N°1</span>
                            </div>
                            <p style="margin-bottom:6px;"><strong style="color:#475569;">Heure :</strong> <span id="detailEntreeHeure" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="margin-bottom:6px;"><strong style="color:#475569;">État :</strong> <span id="detailEntreeEtat" class="badge" style="background:#e2e8f0; padding:4px 12px; font-weight:600;">0</span></p>
                            <div class="row" style="margin-top:8px;">
                                <div class="col-6">
                                    <img id="detailEntreeCapture1" src="https://via.placeholder.com/200x267?text=Cap1" class="img-fluid" style="border-radius:8px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                                <div class="col-6">
                                    <img id="detailEntreeCapture2" src="https://via.placeholder.com/200x267?text=Cap2" class="img-fluid" style="border-radius:8px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                            </div>
                            <div style="margin-top:8px; background:#f1f5f9; padding:6px 12px; border-radius:8px;">
                                <strong style="color:#475569;">Résultat :</strong> <span id="detailEntreeResultat" style="font-weight:700; color:#0a192f;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background:#ffffff; height:100%; border:1px solid #eef2f6;">
                        <div class="card-body" style="padding:16px 18px;">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                <span style="background: #ef4444; color:white; padding:2px 10px; border-radius:50px; font-size:0.65rem; font-weight:700; text-transform:uppercase;">
                                    <i class="zmdi zmdi-arrow-left"></i> Sortie
                                </span>
                                <span style="font-size:0.7rem; color:#94a3b8;">N°2</span>
                            </div>
                            <p style="margin-bottom:6px;"><strong style="color:#475569;">Heure :</strong> <span id="detailSortieHeure" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="margin-bottom:6px;"><strong style="color:#475569;">État :</strong> <span id="detailSortieEtat" class="badge" style="background:#e2e8f0; padding:4px 12px; font-weight:600;">0</span></p>
                            <div class="row" style="margin-top:8px;">
                                <div class="col-6">
                                    <img id="detailSortieCapture1" src="https://via.placeholder.com/200x267?text=Cap1" class="img-fluid" style="border-radius:8px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                                <div class="col-6">
                                    <img id="detailSortieCapture2" src="https://via.placeholder.com/200x267?text=Cap2" class="img-fluid" style="border-radius:8px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                            </div>
                            <div style="margin-top:8px; background:#f1f5f9; padding:6px 12px; border-radius:8px;">
                                <strong style="color:#475569;">Résultat :</strong> <span id="detailSortieResultat" style="font-weight:700; color:#0a192f;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2.2 TROIS RONDES -->
            <div class="row" style="margin-bottom:10px;">
                <div class="col-md-4">
                    <div class="card" style="border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background:#ffffff; height:100%; border:1px solid #eef2f6;">
                        <div class="card-body" style="padding:14px 16px;">
                            <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                                <span style="background: #8b5cf6; color:white; padding:2px 10px; border-radius:50px; font-size:0.6rem; font-weight:700; text-transform:uppercase;">
                                    <i class="zmdi zmdi-refresh"></i> R1
                                </span>
                                <span style="font-size:0.65rem; color:#94a3b8;">Ronde A</span>
                            </div>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Début :</strong> <span id="detailRonde1Debut" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Fin :</strong> <span id="detailRonde1Fin" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">État :</strong> <span id="detailRonde1Etat" class="badge" style="background:#e2e8f0; padding:3px 10px; font-weight:600;">0</span></p>
                            <div class="row" style="margin-top:6px;">
                                <div class="col-6">
                                    <img id="detailRonde1Capture1" src="https://via.placeholder.com/120x160?text=Cap1" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                                <div class="col-6">
                                    <img id="detailRonde1Capture2" src="https://via.placeholder.com/120x160?text=Cap2" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                            </div>
                            <div style="margin-top:6px; background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.85rem;">
                                <strong style="color:#475569;">Résultat :</strong> <span id="detailRonde1Resultat" style="font-weight:700; color:#0a192f;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background:#ffffff; height:100%; border:1px solid #eef2f6;">
                        <div class="card-body" style="padding:14px 16px;">
                            <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                                <span style="background: #8b5cf6; color:white; padding:2px 10px; border-radius:50px; font-size:0.6rem; font-weight:700; text-transform:uppercase;">
                                    <i class="zmdi zmdi-refresh"></i> R2
                                </span>
                                <span style="font-size:0.65rem; color:#94a3b8;">Ronde B</span>
                            </div>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Début :</strong> <span id="detailRonde2Debut" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Fin :</strong> <span id="detailRonde2Fin" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">État :</strong> <span id="detailRonde2Etat" class="badge" style="background:#e2e8f0; padding:3px 10px; font-weight:600;">0</span></p>
                            <div class="row" style="margin-top:6px;">
                                <div class="col-6">
                                    <img id="detailRonde2Capture1" src="https://via.placeholder.com/120x160?text=Cap1" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                                <div class="col-6">
                                    <img id="detailRonde2Capture2" src="https://via.placeholder.com/120x160?text=Cap2" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                            </div>
                            <div style="margin-top:6px; background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.85rem;">
                                <strong style="color:#475569;">Résultat :</strong> <span id="detailRonde2Resultat" style="font-weight:700; color:#0a192f;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); background:#ffffff; height:100%; border:1px solid #eef2f6;">
                        <div class="card-body" style="padding:14px 16px;">
                            <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                                <span style="background: #8b5cf6; color:white; padding:2px 10px; border-radius:50px; font-size:0.6rem; font-weight:700; text-transform:uppercase;">
                                    <i class="zmdi zmdi-refresh"></i> R3
                                </span>
                                <span style="font-size:0.65rem; color:#94a3b8;">Ronde C</span>
                            </div>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Début :</strong> <span id="detailRonde3Debut" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">Fin :</strong> <span id="detailRonde3Fin" style="font-weight:600; color:#0a192f;">--:--</span></p>
                            <p style="font-size:0.85rem; margin-bottom:4px;"><strong style="color:#475569;">État :</strong> <span id="detailRonde3Etat" class="badge" style="background:#e2e8f0; padding:3px 10px; font-weight:600;">0</span></p>
                            <div class="row" style="margin-top:6px;">
                                <div class="col-6">
                                    <img id="detailRonde3Capture1" src="https://via.placeholder.com/120x160?text=Cap1" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                                <div class="col-6">
                                    <img id="detailRonde3Capture2" src="https://via.placeholder.com/120x160?text=Cap2" class="img-fluid" style="border-radius:6px; width:100%; aspect-ratio:3/4; object-fit:cover; border:1px solid #eef2f6;">
                                </div>
                            </div>
                            <div style="margin-top:6px; background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.85rem;">
                                <strong style="color:#475569;">Résultat :</strong> <span id="detailRonde3Resultat" style="font-weight:700; color:#0a192f;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== 4. SECTION CARTE ========== -->
            <hr style="margin: 25px 0; border-top: 2px dashed #d1d5db;">

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                <span style="background: #0a192f; color:white; padding:6px 18px; border-radius:50px; font-size:0.75rem; font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">
                    <i class="zmdi zmdi-pin"></i> Localisation
                </span>
                <span style="font-size:0.8rem; color:#64748b;">Positions GPS</span>
                <span id="mapLegend" style="margin-left:auto; font-size:0.65rem; background:#f1f5f9; padding:4px 12px; border-radius:50px; color:#475569;">
                    <i class="zmdi zmdi-pin"></i> Poste
                </span>
            </div>

            <div id="detailMap" style="height: 400px; border-radius: 16px; border: 1px solid #eef2f6; background: #eef2f6; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size:0.9rem;">
                <span><i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Chargement de la carte...</span>
            </div>

            <!-- ========== 3. SECTION ALERTE ========== -->
            <hr style="margin: 25px 0; border-top: 2px dashed #d1d5db;">

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                <span style="background: #ef4444; color:white; padding:6px 18px; border-radius:50px; font-size:0.75rem; font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">
                    <i class="fa fa-bell"></i> Alerte
                </span>
                <span style="font-size:0.8rem; color:#64748b;">Notifications et anomalies</span>
                <span id="alertBadgeCount" style="margin-left:auto; font-size:0.7rem; background:#fef2f2; padding:3px 12px; border-radius:50px; color:#ef4444; border:1px solid #fecaca;">
                    <i class="zmdi zmdi-notifications"></i> <span id="alertCountNumber">0</span>
                </span>
            </div>

            <div id="alertContainer" style="background:#fef2f2; border-left:4px solid #ef4444; padding:14px 18px; border-radius:12px;">
                <ul id="alertList" style="margin:0; padding-left:20px; list-style-type:disc; color:#991b1b;">
                    <li id="alert1">⚠️ Alerte 1 - Dépassement horaire constaté</li>
                    <li id="alert2">⚠️ Alerte 2 - Absence de signature à l'entrée</li>
                    <li id="alert3">⚠️ Alerte 3 - Zone non autorisée détectée</li>
                </ul>
                <div id="alertDynamicContent"></div>
            </div>

        </div> <!-- fin detail-modal-body -->

        <!-- ========== PIED DE PAGE AVEC BOUTON ROUGE ========== -->
        <div class="detail-modal-footer" style="flex-shrink: 0; padding: 15px 25px 20px; background: white; border-radius: 0 0 28px 28px; text-align: center; border-top:1px solid #eef2f6;">
            <button id="closeDetailPopup" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none; padding: 10px 35px; border-radius: 50px; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                <i class="zmdi zmdi-close"></i> Fermer
            </button>
        </div>
    </div>
</div>

<!-- POPUP CONFIRMATION SUPPRESSION -->
<div id="confirmDeleteModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-header">
            <h3><i class="zmdi zmdi-delete"></i> Confirmation</h3>
        </div>
        <div class="confirm-modal-body">
            <p>Voulez-vous vraiment supprimer cette prestation ?</p>
            <p><strong id="deleteItemName"></strong></p>
        </div>
        <div class="confirm-modal-footer">
            <button class="btn-confirm-no" id="cancelDeleteBtn">Annuler</button>
            <button class="btn-confirm-yes" id="confirmDeleteBtn">Supprimer</button>
        </div>
    </div>
</div>

@section('js-code')
<!-- ===== LIENS CDN LEAFLET + MarkerCluster ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- ===== AJOUT : CSS et JS pour le clustering ===== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- ===== AJOUT : LIBRAIRIES POUR LE DATE RANGE PICKER ===== -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>

<script>
$(document).ready(function() {
    var pendingDeleteId = null;

    // ===== VARIABLES POUR LA CARTE (avec cluster) =====
    var map = null;
    var markerCluster = null;

    // ===== INITIALISATION DE LA CARTE AVEC CLUSTERS PERSONNALISÉS =====
    function initMap() {
        if (map) {
            map.invalidateSize();
            return;
        }
        map = L.map('detailMap', {
            center: [0, 0],
            zoom: 13,
            zoomControl: true,
            fadeAnimation: true,
            attributionControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        markerCluster = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            iconCreateFunction: function(cluster) {
                var childCount = cluster.getChildCount();
                var bgColor;
                if (childCount < 10) {
                    bgColor = '#3b82f6'; // bleu
                } else if (childCount < 100) {
                    bgColor = '#f59e0b'; // orange
                } else {
                    bgColor = '#ef4444'; // rouge
                }
                return L.divIcon({
                    html: '<div style="background-color:' + bgColor + '; color:white; border-radius:50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:16px; box-shadow: 0 4px 14px rgba(0,0,0,0.4); border:3px solid white; transition: transform 0.2s;">' + childCount + '</div>',
                    className: 'custom-cluster',
                    iconSize: [44, 44],
                    iconAnchor: [22, 22]
                });
            }
        }).addTo(map);
    }

    // ===== MISE À JOUR DE LA CARTE AVEC LES ICÔNES =====
    function updateMap(latLngs, details, posteNom, posteDescription) {
        if (!map) {
            initMap();
        }

        markerCluster.clearLayers();

        var types = ['poste', 'entree', 'sortie', 'ronde1', 'ronde2', 'ronde3'];
        var colors = {
            poste: '#0a192f',
            entree: '#22c55e',
            sortie: '#ef4444',
            ronde1: '#8b5cf6',
            ronde2: '#8b5cf6',
            ronde3: '#8b5cf6'
        };
        var labels = {
            poste: 'Poste',
            entree: 'Entrée',
            sortie: 'Sortie',
            ronde1: 'Ronde 1',
            ronde2: 'Ronde 2',
            ronde3: 'Ronde 3'
        };
        var emojis = {
            poste: '🏠',
            entree: '✅',
            sortie: '❌',
            ronde1: '①',
            ronde2: '②',
            ronde3: '③'
        };

        var validPoints = [];

        types.forEach(function(type) {
            var pt = latLngs[type];
            if (pt && pt.lat && pt.lng && !isNaN(pt.lat) && !isNaN(pt.lng)) {
                var iconHtml = '<div style="background-color:' + colors[type] + '; color:white; border-radius:50%; width:34px; height:34px; display:flex; align-items:center; justify-content:center; font-size:16px; border:2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">' + emojis[type] + '</div>';
                var customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: iconHtml,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -17]
                });

                var popupContent = '<strong>' + labels[type] + '</strong><br>';
                if (type === 'poste') {
                    if (posteNom && posteNom.length > 0) {
                        popupContent += '<strong>Nom :</strong> ' + posteNom + '<br>';
                    }
                    if (posteDescription && posteDescription.length > 0) {
                        popupContent += '<strong>Description :</strong> ' + posteDescription + '<br>';
                    }
                }
                popupContent = emojis[type] + ' ' + popupContent;

                if (details[type]) {
                    var d = details[type];
                    if (d.heure) popupContent += '<br><strong>Heure :</strong> ' + d.heure;
                    if (d.etat !== undefined && d.etat !== null) popupContent += '<br><strong>État :</strong> ' + d.etat;
                    if (d.resultat) popupContent += '<br><strong>Résultat :</strong> ' + d.resultat;
                    if (d.debut) popupContent += '<br><strong>Début :</strong> ' + d.debut;
                    if (d.fin) popupContent += '<br><strong>Fin :</strong> ' + d.fin;
                    if (d.capture1 && d.capture1 !== "./storage/images/user/visage_par_defaut.png") {
                        popupContent += '<br><img src="' + d.capture1 + '" style="width:50px; height:auto; margin-top:5px; border-radius:4px;" />';
                    }
                    if (d.capture2 && d.capture2 !== "./storage/images/user/visage_par_defaut.png") {
                        popupContent += ' <img src="' + d.capture2 + '" style="width:50px; height:auto; margin-top:5px; border-radius:4px;" />';
                    }
                }

                var marker = L.marker([pt.lat, pt.lng], { icon: customIcon })
                    .bindPopup(popupContent);
                markerCluster.addLayer(marker);

                validPoints.push([pt.lat, pt.lng]);
            }
        });

        if (validPoints.length > 0) {
            if (validPoints.length > 1) {
                var bounds = L.latLngBounds(validPoints);
                map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
            } else {
                map.setView(validPoints[0], 15);
            }
        } else {
            map.setView([0, 0], 2);
            var infoMarker = L.marker([0, 0], {
                icon: L.divIcon({
                    className: 'info-marker',
                    html: '<div style="background:#f1f5f9; padding:8px 16px; border-radius:20px; font-size:14px; color:#475569; border:1px solid #cbd5e1;">Aucune position GPS disponible</div>',
                    iconSize: [0, 0],
                    iconAnchor: [0, 0]
                })
            });
            markerCluster.addLayer(infoMarker);
        }
    }

    // ========== GESTION DU MENU 3 POINTS ==========
    $('.btn-three-dots').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var targetId = $(this).data('target');
        var $menu = $('#' + targetId);
        var $btn = $(this);

        $('.custom-dropdown-menu').not($menu).removeClass('show');

        if ($menu.hasClass('show')) {
            $menu.removeClass('show');
            return;
        }

        var rect = $btn[0].getBoundingClientRect();
        var menuWidth = $menu.outerWidth();
        var menuHeight = $menu.outerHeight();
        var windowWidth = $(window).width();
        var windowHeight = $(window).height();

        var leftPos = rect.right - menuWidth;
        if (leftPos < 10) leftPos = rect.left;
        if (leftPos + menuWidth > windowWidth - 10) leftPos = windowWidth - menuWidth - 10;

        var topPos;
        var spaceBelow = windowHeight - rect.bottom;
        var spaceAbove = rect.top;

        if (spaceBelow >= menuHeight || spaceBelow > spaceAbove) {
            topPos = rect.bottom + 8;
        } else {
            topPos = rect.top - menuHeight - 8;
        }

        $menu.css({
            position: 'fixed',
            left: leftPos + 'px',
            top: topPos + 'px',
            right: 'auto',
            bottom: 'auto'
        });

        $menu.addClass('show');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.btn-three-dots').length && !$(e.target).closest('.custom-dropdown-menu').length) {
            $('.custom-dropdown-menu').removeClass('show');
        }
    });

    // ========================================================
    //  INITIALISATION DU DATE RANGE PICKER AVEC DÉFAUT AUJOURD'HUI
    // ========================================================

    var today = moment();
    var todayStr = today.format('DD/MM/YYYY');
    $('#filterDateRange').val(todayStr + ' - ' + todayStr);

    $('#filterDateRange').daterangepicker({
        autoUpdateInput: false,
        startDate: today,
        endDate: today,
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Appliquer',
            cancelLabel: 'Annuler',
            fromLabel: 'Du',
            toLabel: 'Au',
            customRangeLabel: 'Personnalisé',
            weekLabel: 'S',
            daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        },
        opens: 'left',
        ranges: {
            'Aujourd\'hui': [moment(), moment()],
            'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 derniers jours': [moment().subtract(6, 'days'), moment()],
            '30 derniers jours': [moment().subtract(29, 'days'), moment()],
            'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
            'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Cette année': [moment().startOf('year'), moment().endOf('year')]
        }
    }, function(start, end, label) {
        var startStr = start.format('DD/MM/YYYY');
        var endStr = end.format('DD/MM/YYYY');
        $('#filterDateRange').val(startStr + ' - ' + endStr);
        filterTable();
    });

    $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        filterTable();
    });

    // ============================================================
    //  FIN INITIALISATION DATE RANGE PICKER
    // ============================================================

    // ========== 1. VOIR DÉTAILS ==========
    $('.view-detail').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // --- Infos générales ---
        $('#detailMatricule').text($(this).data('matricule') || '-');
        $('#detailNom').text($(this).data('nom') || '-');
        $('#detailFonction').text($(this).data('fonction') || '-');
        $('#detailPoste').text($(this).data('poste') || '-');
        $('#detailPrestation').text($(this).data('prestation') || '-');
        $('#detailHoraire').text($(this).data('horaire') || '-');
        $('#detailDate').text($(this).data('date') || '-');

        // --- Entrée ---
        $('#detailEntreeHeure').text($(this).data('entree-heure') || '--:--');
        var entreeEtat = $(this).data('entree-etat') || 0;
        $('#detailEntreeEtat').text(entreeEtat);
        $('#detailEntreeCapture1').attr('src', $(this).data('entree-capture1') || 'https://via.placeholder.com/150x100?text=Cap1');
        $('#detailEntreeCapture2').attr('src', $(this).data('entree-capture2') || 'https://via.placeholder.com/150x100?text=Cap2');
        $('#detailEntreeResultat').text($(this).data('entree-resultat') || 0);

        // --- Sortie ---
        $('#detailSortieHeure').text($(this).data('sortie-heure') || '--:--');
        var sortieEtat = $(this).data('sortie-etat') || 0;
        $('#detailSortieEtat').text(sortieEtat);
        $('#detailSortieCapture1').attr('src', $(this).data('sortie-capture1') || 'https://via.placeholder.com/150x100?text=Cap1');
        $('#detailSortieCapture2').attr('src', $(this).data('sortie-capture2') || 'https://via.placeholder.com/150x100?text=Cap2');
        $('#detailSortieResultat').text($(this).data('sortie-resultat') || 0);

        // --- Ronde 1 ---
        $('#detailRonde1Debut').text($(this).data('ronde1-debut') || '--:--');
        $('#detailRonde1Fin').text($(this).data('ronde1-fin') || '--:--');
        var r1Etat = $(this).data('ronde1-etat') || 0;
        $('#detailRonde1Etat').text(r1Etat);
        $('#detailRonde1Capture1').attr('src', $(this).data('ronde1-capture1') || 'https://via.placeholder.com/120x80?text=Cap1');
        $('#detailRonde1Capture2').attr('src', $(this).data('ronde1-capture2') || 'https://via.placeholder.com/120x80?text=Cap2');
        $('#detailRonde1Resultat').text($(this).data('ronde1-resultat') || 0);

        // --- Ronde 2 ---
        $('#detailRonde2Debut').text($(this).data('ronde2-debut') || '--:--');
        $('#detailRonde2Fin').text($(this).data('ronde2-fin') || '--:--');
        var r2Etat = $(this).data('ronde2-etat') || 0;
        $('#detailRonde2Etat').text(r2Etat);
        $('#detailRonde2Capture1').attr('src', $(this).data('ronde2-capture1') || 'https://via.placeholder.com/120x80?text=Cap1');
        $('#detailRonde2Capture2').attr('src', $(this).data('ronde2-capture2') || 'https://via.placeholder.com/120x80?text=Cap2');
        $('#detailRonde2Resultat').text($(this).data('ronde2-resultat') || 0);

        // --- Ronde 3 ---
        $('#detailRonde3Debut').text($(this).data('ronde3-debut') || '--:--');
        $('#detailRonde3Fin').text($(this).data('ronde3-fin') || '--:--');
        var r3Etat = $(this).data('ronde3-etat') || 0;
        $('#detailRonde3Etat').text(r3Etat);
        $('#detailRonde3Capture1').attr('src', $(this).data('ronde3-capture1') || 'https://via.placeholder.com/120x80?text=Cap1');
        $('#detailRonde3Capture2').attr('src', $(this).data('ronde3-capture2') || 'https://via.placeholder.com/120x80?text=Cap2');
        $('#detailRonde3Resultat').text($(this).data('ronde3-resultat') || 0);

        // --- Alertes ---
        $('#alertCountNumber').text($(this).data('alertes-count') || 0);
        var alertes = $(this).data('alertes');
        if (alertes && Array.isArray(alertes) && alertes.length > 0) {
            var $list = $('#alertList').empty();
            alertes.forEach(function(msg, index) {
                $list.append('<li id="alert_' + index + '">' + msg + '</li>');
            });
            $('#alertDynamicContent').empty();
            $('#alertContainer').show();
        } else {
            $('#alertList').empty();
            $('#alertDynamicContent').html('<p class="text-muted" style="margin:0;">Aucune alerte</p>');
            $('#alertContainer').show();
        }

        // ===== APPLICATION DES COULEURS SUR LES BADGES D'ÉTAT (SEULEMENT) =====
        function setBadgeStyle(badgeId, type, etat) {
            var $badge = $(badgeId);
            // Supprimer les classes de couleur précédentes
            $badge.removeClass('bg-danger bg-success bg-light text-white text-dark text-danger');
            // Ajouter les classes selon le type et l'état
            if (etat == 0) {
                // Pas de pointage : fond gris clair, texte rouge
                $badge.addClass('bg-light text-danger');
            } else if (etat == 1) {
                if (type === 'systeme') {
                    // Pointage système : fond rouge, texte blanc
                    $badge.addClass('bg-danger text-white');
                } else if (type === 'utilisateur') {
                    // Pointage utilisateur : fond vert, texte blanc (modification demandée)
                    $badge.addClass('bg-success text-white');
                } else {
                    // fallback : si type indéfini mais etat=1 => fond vert par défaut, texte blanc
                    $badge.addClass('bg-success text-white');
                }
            } else {
                // Si etat a une autre valeur (ex: "Repos"), on garde neutre
                $badge.removeClass('bg-light bg-danger bg-success');
            }
            // Le texte du badge (la valeur numérique) est déjà affiché, on ne le modifie pas.
        }

        // Récupérer les types et les états
        var entreeType = $(this).data('entree-type');
        var sortieType = $(this).data('sortie-type');
        var ronde1Type = $(this).data('ronde1-type');
        var ronde2Type = $(this).data('ronde2-type');
        var ronde3Type = $(this).data('ronde3-type');

        var entreeEtat = parseInt($(this).data('entree-etat')) || 0;
        var sortieEtat = parseInt($(this).data('sortie-etat')) || 0;
        var ronde1Etat = parseInt($(this).data('ronde1-etat')) || 0;
        var ronde2Etat = parseInt($(this).data('ronde2-etat')) || 0;
        var ronde3Etat = parseInt($(this).data('ronde3-etat')) || 0;

        setBadgeStyle('#detailEntreeEtat', entreeType, entreeEtat);
        setBadgeStyle('#detailSortieEtat', sortieType, sortieEtat);
        setBadgeStyle('#detailRonde1Etat', ronde1Type, ronde1Etat);
        setBadgeStyle('#detailRonde2Etat', ronde2Type, ronde2Etat);
        setBadgeStyle('#detailRonde3Etat', ronde3Type, ronde3Etat);

        // ===== PRÉPARATION DES DONNÉES POUR LA CARTE =====
        var latLngs = {
            poste: { lat: parseFloat($(this).data('poste-lat')), lng: parseFloat($(this).data('poste-lng')) },
            entree: { lat: parseFloat($(this).data('entree-lat')), lng: parseFloat($(this).data('entree-lng')) },
            sortie: { lat: parseFloat($(this).data('sortie-lat')), lng: parseFloat($(this).data('sortie-lng')) },
            ronde1: { lat: parseFloat($(this).data('ronde1-lat')), lng: parseFloat($(this).data('ronde1-lng')) },
            ronde2: { lat: parseFloat($(this).data('ronde2-lat')), lng: parseFloat($(this).data('ronde2-lng')) },
            ronde3: { lat: parseFloat($(this).data('ronde3-lat')), lng: parseFloat($(this).data('ronde3-lng')) }
        };

        var details = {
            poste: {},
            entree: {
                heure: $(this).data('entree-heure'),
                etat: $(this).data('entree-etat'),
                resultat: $(this).data('entree-resultat'),
                capture1: $(this).data('entree-capture1'),
                capture2: $(this).data('entree-capture2')
            },
            sortie: {
                heure: $(this).data('sortie-heure'),
                etat: $(this).data('sortie-etat'),
                resultat: $(this).data('sortie-resultat'),
                capture1: $(this).data('sortie-capture1'),
                capture2: $(this).data('sortie-capture2')
            },
            ronde1: {
                debut: $(this).data('ronde1-debut'),
                fin: $(this).data('ronde1-fin'),
                etat: $(this).data('ronde1-etat'),
                resultat: $(this).data('ronde1-resultat'),
                capture1: $(this).data('ronde1-capture1'),
                capture2: $(this).data('ronde1-capture2')
            },
            ronde2: {
                debut: $(this).data('ronde2-debut'),
                fin: $(this).data('ronde2-fin'),
                etat: $(this).data('ronde2-etat'),
                resultat: $(this).data('ronde2-resultat'),
                capture1: $(this).data('ronde2-capture1'),
                capture2: $(this).data('ronde2-capture2')
            },
            ronde3: {
                debut: $(this).data('ronde3-debut'),
                fin: $(this).data('ronde3-fin'),
                etat: $(this).data('ronde3-etat'),
                resultat: $(this).data('ronde3-resultat'),
                capture1: $(this).data('ronde3-capture1'),
                capture2: $(this).data('ronde3-capture2')
            }
        };

        var posteNom = $(this).data('poste') || '';
        var posteDescription = $(this).data('poste-description') || '';

        updateMap(latLngs, details, posteNom, posteDescription);

        $('#detailPopup').addClass('show');
        setTimeout(function() {
            if (map) map.invalidateSize();
        }, 300);

        $('.custom-dropdown-menu').removeClass('show');
    });

    // ========== 2. MODIFIER ==========
    $('.edit-item').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).data('id');
        var nom = $(this).data('nom');
        alert('Modifier: ' + nom + ' (ID: ' + id + ')\n\nAjoutez votre logique ici');
        $('.custom-dropdown-menu').removeClass('show');
    });

    // ========== 3. SUPPRIMER ==========
    $('.delete-item').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        pendingDeleteId = $(this).data('id');
        var nom = $(this).data('nom');
        $('#deleteItemName').text(nom);
        $('#confirmDeleteModal').addClass('show');
        $('.custom-dropdown-menu').removeClass('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (pendingDeleteId) {
            alert('Suppression de l\'élément: ' + pendingDeleteId);
            $('#confirmDeleteModal').removeClass('show');
            pendingDeleteId = null;
        }
    });

    $('#cancelDeleteBtn').on('click', function() {
        $('#confirmDeleteModal').removeClass('show');
        pendingDeleteId = null;
    });

    $('#closeDetailPopup').on('click', function() {
        $('#detailPopup').removeClass('show');
    });

    $(document).on('click', function(e) {
        if ($(e.target).hasClass('detail-modal')) {
            $('#detailPopup').removeClass('show');
        }
        if ($(e.target).hasClass('confirm-modal')) {
            $('#confirmDeleteModal').removeClass('show');
        }
    });

    // ========== FILTRAGE AVEC PRISE EN COMPTE DE LA PLAGE DE DATES ==========
    function filterTable() {
        var dateRange = $('#filterDateRange').val() || '';
        var dateDebut = null, dateFin = null;
        if (dateRange) {
            var parts = dateRange.split(' - ');
            if (parts.length === 2) {
                function parseDMY(str) {
                    if (!str) return null;
                    var p = str.split('/');
                    if (p.length === 3) {
                        var day = p[0];
                        var month = p[1];
                        var year = p[2];
                        if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
                            return year + '-' + month + '-' + day;
                        }
                    }
                    return null;
                }
                dateDebut = parseDMY(parts[0]);
                dateFin = parseDMY(parts[1]);
            }
        }

        var nomVal = $('#filterEleve').val().toLowerCase().trim();
        var posteVal = $('#filterPoste').val().toLowerCase().trim();
        var prestationVal = $('#filterPrestation').val();
        var horaireVal = $('#filterHoraire').val();
        var visibleCount = 0;

        $('#content_utilisateur tbody tr').each(function() {
            var $row = $(this);
            var dateCellRaw = $row.find('td:eq(7)').text().trim();
            var nomCell = $row.find('td:eq(2)').text().toLowerCase().trim();
            var posteCell = $row.find('td:eq(4)').text().toLowerCase().trim();
            var prestationCell = $row.find('td:eq(5)').text().toLowerCase().trim();
            var horaireCell = $row.find('td:eq(6)').text().toLowerCase().trim();

            var match = true;

            // Filtre date
            if (dateDebut && dateFin) {
                var cellDate = null;
                if (dateCellRaw && dateCellRaw !== 'repos') {
                    var partsDate = dateCellRaw.split('/');
                    if (partsDate.length === 3) {
                        var d = partsDate[0];
                        var m = partsDate[1];
                        var y = partsDate[2];
                        if (d && m && y && d.length === 2 && m.length === 2 && y.length === 4) {
                            cellDate = y + '-' + m + '-' + d;
                        }
                    }
                }
                if (cellDate) {
                    if (cellDate < dateDebut || cellDate > dateFin) {
                        match = false;
                    }
                } else {
                    match = false;
                }
            }

            // Autres filtres
            if (nomVal && !nomCell.includes(nomVal)) match = false;
            if (posteVal && !posteCell.includes(posteVal)) match = false;
            if (prestationVal && prestationCell !== prestationVal) match = false;
            if (horaireVal && horaireCell !== horaireVal) match = false;

            if (match) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });

        $('#studentCount').text(visibleCount);
    }

    // ========== ÉVÉNEMENTS DES FILTRES ==========
    $('#filterEleve, #filterPoste, #filterPrestation, #filterHoraire').on('input change', function() {
        filterTable();
    });

    // ========== BOUTON RÉINITIALISER ==========
    $('#resetFilters').click(function(e) {
        e.preventDefault();
        var today = moment();
        var todayStr = today.format('DD/MM/YYYY');
        $('#filterDateRange').val(todayStr + ' - ' + todayStr);
        $('#filterEleve, #filterPoste').val('');
        $('#filterPrestation, #filterHoraire').val('');
        filterTable();
    });

    // ========== INITIALISATION ==========
    filterTable();
    $("#link_44").addClass("active");
});
</script>
@endsection
@endsection
