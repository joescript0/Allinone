<?php
  use App\Models\Writes;
  use Illuminate\Support\Facades\Auth;
  function Activer()
  {
    return 1;
  }
  function Desactiver()
  {
    return 0;
  }
  // In on one
 //   $menueapp = "Tout";

  // Divine
  $menueapp = "Tout";

  if($menueapp == "Tout")
  {
    $menu_1 = Activer();
    $menu_2 = Desactiver();
    $menu_3 = Desactiver();
    $menu_4 = Desactiver();
    $menu_5 = Desactiver();
    $menu_6 = Activer();
    $menu_7 = Activer();
    $menu_8 = Activer();
    $menu_9 = Activer();
    $menu_10 = Desactiver();
    $menu_11 = Desactiver();
    $menu_12 = Desactiver();
    $menu_13 = Activer();
    $menu_14 = Desactiver(); // ici
    // Divine
    $menu_15 = Desactiver();
    $menu_16 = Desactiver();
    $menu_17 = Activer();
    $menu_18 = Activer();
    $menu_19 = Activer();
    $menu_20 = Activer();
    $menu_21 = Activer();
    $menu_22 = Desactiver();
    $menu_23 = Activer();
    $menu_24 = Activer();
    $menu_25 = Activer();
    $menu_26 = Desactiver();
    $menu_27 = Activer();
    $menu_28 = Activer();
    $menu_29 = Desactiver();
    $menu_30 = Activer();
    $menu_31 = Desactiver();
    $menu_32 = Activer();
    $menu_33 = Activer();
    $menu_34 = Desactiver();
    $menu_35 = Desactiver();
    $menu_36 = Desactiver();
    $menu_37 = Desactiver();
    $menu_38 = Desactiver();
    $menu_39 = Desactiver();
    $menu_40 = Activer();
    $menu_41 = Activer();
    $menu_42 = Activer();
    $menu_43 = Activer();
    $menu_44 = Activer();

  }
  elseif($menueapp == "Divine")
  {
    $menu_1 = Activer();
    $menu_2 = Desactiver();
    $menu_3 = Desactiver();
    $menu_4 = Desactiver();
    $menu_5 = Desactiver();
    $menu_6 = Desactiver();
    $menu_7 = Desactiver();
    $menu_8 = Desactiver();
    $menu_9 = Desactiver();
    $menu_10 = Desactiver();
    $menu_11 = Desactiver();
    $menu_12 = Desactiver();
    $menu_13 = Desactiver();
    $menu_14 = Desactiver();

    // Divine
    $menu_15 = Activer();
    $menu_16 = Activer();
    $menu_17 = Activer();
    $menu_18 = Activer();
    $menu_19 = Activer();
    $menu_20 = Desactiver();
    $menu_21 = Desactiver();


    $menu_22 = Desactiver();
    $menu_23 = Desactiver();
    $menu_24 = Desactiver();
    $menu_25 = Desactiver();
    $menu_26 = Desactiver();
    $menu_27 = Desactiver();
    $menu_28 = Desactiver();
    $menu_29 = Desactiver();
    $menu_30 = Desactiver();
    $menu_31 = Desactiver();
    $menu_32 = Desactiver();
    $menu_33 = Activer();
    $menu_34 = Desactiver();
    $menu_35 = Desactiver();
    $menu_36 = Activer();
    $menu_37 = Desactiver();
    $menu_38 = Desactiver();
    $menu_39 = Desactiver();
    $menu_40 = Activer();
    $menu_41 = Activer();
    $menu_42 = Activer();
    $menu_43 = Activer();
    $menu_44 = Activer();
  }
?>

<style>
/* ========== SIDEBAR GRIS CLAIR (#E7F5FE) + ACTIF ENVELOPPÉ ========== */
.sidebar {
  background: #E7F5FE !important;
  border-right: 1px solid #d4e2f0 !important;
  box-shadow: none !important;
}

/* Zone utilisateur */
.sidebar .user {
  padding: 1.25rem 1rem;
}
.sidebar .user__info {
  background: #ffffff !important;
  border-radius: 14px !important;
  padding: 0.75rem !important;
  border: 1px solid #e2edf5 !important;
}
.sidebar .user__name {
  color: #000000 !important;
  font-weight: 700 !important;
  font-size: 0.9rem !important;
}
.sidebar .user__email {
  color: #1e2a3e !important;
  font-size: 0.75rem !important;
}

/* Menu principal */
.sidebar .navigation {
  list-style: none;
  padding: 0.5rem 0.75rem;
  margin: 0;
}
.sidebar .navigation li {
  margin-bottom: 0.25rem;
}
.sidebar .navigation li a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0.7rem 1rem;
  color: #000000 !important;
  font-weight: bold !important;
  font-size: 0.9rem;
  background: transparent;
  border-radius: 12px;
  transition: all 0.2s ease;
  text-decoration: none;
}
.sidebar .navigation li a:hover {
  background: #d9eaf5 !important;
  color: #000000 !important;
  transform: translateX(4px);
}

/* === LIEN ACTIF (class active sur le li) === */
.sidebar .navigation li.active a {
  background: rgba(255, 255, 255, 0.85) !important;
  color: #000000 !important;
  font-weight: bold !important;
  border-radius: 14px !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04) !important;
  backdrop-filter: blur(2px) !important;
  transform: translateX(2px) !important;
}

/* Titre administration */
.admin-tools-title {
  color: #1a1a1a !important;
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 0.75rem 1rem 0.25rem 1.5rem;
  margin-top: 0.5rem;
  border-top: 1px solid #d4e2f0;
}

/* Dropdown menu */
.dropdown-menu {
  background: #ffffff !important;
  border: 1px solid #e2edf5 !important;
  border-radius: 16px !important;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
  padding: 0.5rem 0;
}
.dropdown-item {
  color: #000000 !important;
  font-size: 0.85rem;
  padding: 0.5rem 1.25rem;
  transition: all 0.15s;
}
.dropdown-item:hover {
  background: #E7F5FE !important;
  color: #000000 !important;
}

/* Scrollbar */
.scrollbar-inner::-webkit-scrollbar {
  width: 5px;
}
.scrollbar-inner::-webkit-scrollbar-track {
  background: #e2edf5;
  border-radius: 10px;
}
.scrollbar-inner::-webkit-scrollbar-thumb {
  background: #c0d4e4;
  border-radius: 10px;
}
.scrollbar-inner::-webkit-scrollbar-thumb:hover {
  background: #9bb8ce;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar .navigation li a {
    padding: 0.6rem 0.8rem;
    font-size: 0.85rem;
  }
}
/* ========== MODALES RESPONSIVES ========== */
.modal-content {
  background: #ffffff !important;
  border: none !important;
  border-radius: 24px !important;
  box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.1) !important;
  overflow: hidden;
}

.modal-header {
  background: #3B82F6 !important;
  border-bottom: none !important;
  padding: 1.2rem 1.5rem !important;
}

.modal-header .modal-title {
  font-weight: 700 !important;
  color: #ffffff !important;
  font-size: 1.2rem !important;
}

.modal-header .close {
  opacity: 0.9 !important;
  transition: all 0.2s ease;
  color: #ffffff !important;
  text-shadow: none !important;
}

.modal-header .close:hover {
  opacity: 1 !important;
  transform: scale(1.1);
  color: #ffffff !important;
}

.modal-body {
  background: #ffffff !important;
  padding: 1.8rem !important;
  color: #1e2a3e !important;
  font-size: 0.95rem;
}

.modal-footer {
  border-top: 1px solid #eef2f6 !important;
  background: #fafcff !important;
  padding: 1rem 1.5rem !important;
  justify-content: center !important;
  gap: 12px;
}

.modal-footer .btn,
.modal-footer a.btn {
  border-radius: 40px !important;
  padding: 8px 24px !important;
  font-weight: 600 !important;
  font-size: 0.85rem !important;
  transition: all 0.2s ease;
  border: none !important;
}

.modal-footer .btn-info,
.modal-footer .btn-info:active,
.modal-footer .btn-info:focus {
  background: #3B82F6 !important;
  color: white !important;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.modal-footer .btn-info:hover {
  background: #2563eb !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(59, 130, 246, 0.35);
}

.modal-footer .btn-danger {
  background: #e03a3a !important;
  color: white !important;
}

.modal-footer .btn-danger:hover {
  background: #c32e2e !important;
  transform: translateY(-1px);
}

.modal-body i.zmdi,
.modal-body img {
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.05));
}

.modal-body iframe {
  border-radius: 16px !important;
  border: 1px solid #e9edf2 !important;
  background: #f9fbfd;
  width: 100%;
  height: 450px; /* hauteur par défaut */
}

#excelViewerContainer {
  background: #ffffff;
  border-radius: 16px;
  padding: 8px;
  overflow-x: auto; /* pour les tableaux larges */
}

/* Fond (overlay) */
.modal-backdrop {
  background-color: #1e2a3e !important;
  opacity: 0.75 !important;
}

.modal-open .modal-backdrop.show {
  backdrop-filter: blur(4px);
}

/* ========== RESPONSIVE ========== */

/* Tablettes et petits écrans */
@media (max-width: 992px) {
  .modal-dialog {
    max-width: 90% !important;
    margin: 1.75rem auto;
  }
  .modal-content {
    border-radius: 20px !important;
  }
  .modal-body {
    padding: 1.5rem !important;
  }
  .modal-body iframe {
    height: 350px;
  }
}

/* Mobiles (largeur < 768px) */
@media (max-width: 768px) {
  .modal-dialog {
    max-width: 95% !important;
    margin: 1rem auto;
  }
  .modal-content {
    border-radius: 18px !important;
  }
  .modal-header {
    padding: 1rem 1.2rem !important;
  }
  .modal-header .modal-title {
    font-size: 1rem !important;
  }
  .modal-body {
    padding: 1.2rem !important;
    font-size: 0.9rem;
  }
  .modal-footer {
    padding: 0.8rem 1.2rem !important;
    flex-wrap: wrap; /* pour que les boutons passent à la ligne */
    gap: 8px;
  }
  .modal-footer .btn,
  .modal-footer a.btn {
    padding: 6px 16px !important;
    font-size: 0.8rem !important;
  }
  .modal-body iframe {
    height: 280px;
    border-radius: 12px !important;
  }
  #excelViewerContainer {
    padding: 4px;
  }
}

/* Très petits écrans (<= 576px) */
@media (max-width: 576px) {
  .modal-dialog {
    max-width: 98% !important;
    margin: 0.5rem auto;
  }
  .modal-content {
    border-radius: 16px !important;
  }
  .modal-header {
    padding: 0.8rem 1rem !important;
  }
  .modal-header .modal-title {
    font-size: 0.9rem !important;
  }
  .modal-body {
    padding: 1rem !important;
    font-size: 0.85rem;
  }
  .modal-footer .btn,
  .modal-footer a.btn {
    padding: 5px 14px !important;
    font-size: 0.75rem !important;
  }
  .modal-body iframe {
    height: 200px;
  }
}
</style>

<?php if(Auth::user()->module_connected == 1){ ?>
<!-- ===================== SIDEBAR ===================== -->
  <aside class="sidebar">
    <div id="menu" class="scrollbar-inner">
      <div class="user">
        <div class="user__info" data-toggle="dropdown">
          <img id="user__img" class="user__img" src="{{ asset( Auth::user()->image ) }}" alt="">
          <div>
            @if (Auth::user()->role == 1)
            <div style="display: none;" class="user__name">{{ "OASISTECH - ADMIN" }}</div>
            @endif
            @if (Auth::user()->role == 2)
            <div style="display: none;" class="user__name">{{ "ADMINISTRATEUR" }}</div>
            @endif
            @if (Auth::user()->role == 3)
            <div style="display: none;" class="user__name">{{ "ETUDIANT" }}</div>
            @endif
            @if (Auth::user()->role == 4)
            <div style="display: none;" class="user__name">{{ "COLLABORATEUR" }}</div>
            @endif
            <div class="user__email">{{ Auth::user()->name }}</div>
          </div>
        </div>

        <div class="dropdown-menu">
          <a data-toggle="modal" data-target="#deconnexion" class="dropdown-item" href="#"><i class="zmdi zmdi-power"></i> Déconnexion</a>
          <a style="display:none;" id="btn_refus" data-toggle="modal" data-target="#refus" class="dropdown-item" href="#"><i class="zmdi zmdi-block"></i> Accès refusé</a>
          <a style="display:none;" id="btn_cloturer" data-toggle="modal" data-target="#cloturer" class="dropdown-item" href="#"><i class="zmdi zmdi-alert-circle"></i> PV clôturé</a>
          <a style="display:none;" id="btn_preuve" data-toggle="modal" data-target="#preuve" class="dropdown-item" href="#"><i class="zmdi zmdi-file-text"></i> Voir preuve</a>
          <a class="dropdown-item" href="{{ route('profils') }}"><i class="zmdi zmdi-edit"></i> Profil</a>
          <a style="display:none;" id="btn_detail_fichier" data-toggle="modal" data-target="#preuve_fichier" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
          <a style="display:none;" id="btn_detail_programme" data-toggle="modal" data-target="#modalprogramme" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
        </div>
      </div>

      <?php

      $groupe_user_id = Auth::user()->role;
      $data["ressource_id_1"] = 1;
      $data["ressource_id_2"] = 2;
      $data["ressource_id_3"] = 3;
      $data["ressource_id_4"] = 4;
      $data["ressource_id_5"] = 5;
      $data["ressource_id_6"] = 6;
      $data["ressource_id_7"] = 7;
      $data["ressource_id_8"] = 8;
      $data["ressource_id_9"] = 9;
      $data["ressource_id_10"] = 10;
      $data["ressource_id_11"] = 11;
      $data["ressource_id_12"] = 12;
      $data["ressource_id_13"] = 13;
      $data["ressource_id_14"] = 14;
      $data["ressource_id_15"] = 15;
      $data["ressource_id_16"] = 16;
      $data["ressource_id_17"] = 17;
      $data["ressource_id_18"] = 18;
      $data["ressource_id_19"] = 19;
      $data["ressource_id_20"] = 20;
      $data["ressource_id_21"] = 21;
      $data["ressource_id_22"] = 22;
      $data["ressource_id_23"] = 23;
      $data["ressource_id_24"] = 24;
      $data["ressource_id_25"] = 25;
      $data["ressource_id_26"] = 26;
      $data["ressource_id_27"] = 27;
      $data["ressource_id_28"] = 28;
      $data["ressource_id_29"] = 29;
      $data["groupe_user_id"] = $groupe_user_id;
      ?>
      <ul class="navigation">

        @if ($menu_1 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_1 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_1 = Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_1 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_1"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_1 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_1"><a href="{{ route('home') }}" id="text_1">🏠 Tableau de bord</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_2 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_2 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_2 = Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_2 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_2 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_2"><a href="{{ route('invitations') }}" id="text_2">📁 Projets</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_3 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_2 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_2 = Writes::where(["ressource_id" => $data["ressource_id_2"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_2 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_2 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_16"><a href="{{ route('decisions') }}" id="text_16">✨ Création de projet</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_4 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_4 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_4 = Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_4 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_4"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_4 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_17"><a href="{{ route('rapports') }}" id="text_17">📊 Bilan du projet</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_5 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_5"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_5 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_5"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_5 = Writes::where(["ressource_id" => $data["ressource_id_5"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_5 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_5"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_5 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_18"><a href="{{ route('entres') }}" id="text_18">🔄 Opérations</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_6 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_6"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_6 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_6"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_6 = Writes::where(["ressource_id" => $data["ressource_id_6"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_6 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_6"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_6 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_19" style="display: none;"><a href="{{ route('sorties') }}" id="text_19">⬅️ Sorties</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_29 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_7"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                $display_7 = 0;
                if ((Writes::where(["ressource_id" => $data["ressource_id_7"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_7 = Writes::where(["ressource_id" => $data["ressource_id_7"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                }
                ?>
                <?php if (((($display_7 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_7"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_7 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_22" ><a href="{{ route('bilan_sociale') }}" id="text_22">📈 Bilan sociale</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_7 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_8"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_8 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_8"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_8 = Writes::where(["ressource_id" => $data["ressource_id_8"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_8 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_8"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_8 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_25"><a href="{{ route('gestion_article') }}" id="text_25">📦 Gestion d'article</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_8 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_9"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_9 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_9"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_9= Writes::where(["ressource_id" => $data["ressource_id_9"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_9 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_9"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_9 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_23"><a href="{{ route('app_article') }}" id="text_23">🚚 Approvisionnement</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_9 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_10"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_10 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_10"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_10 = Writes::where(["ressource_id" => $data["ressource_id_10"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_10 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_10"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_10 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_24"><a href="{{ route('achat_article') }}" id="text_24">🧾 Creation facture</a></li>
                    {{-- <li id="link_24"><a href="{{ route('achat_article') }}" id="text_24">➡️ Sortie d'article</a></li> --}}
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_10 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_11"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_11 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_11"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_11 = Writes::where(["ressource_id" => $data["ressource_id_11"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_11 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_11"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_11 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_26"><a href="{{ route('gestion_credit') }}" id="text_26">💳 Gestion de crédit</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_11 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_12"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_12 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_12"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_12 = Writes::where(["ressource_id" => $data["ressource_id_12"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_12 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_12"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_12 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_27"><a href="{{ route('paie') }}" id="text_27">💰 Paiement</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_12 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_13"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_13 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_13"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_13 = Writes::where(["ressource_id" => $data["ressource_id_13"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_13 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_13"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_13 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_29"><a href="{{ route('gestion_fichier') }}" id="text_29">📁 Gestion de fichiers</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_13 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_14"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_14 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_14"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_14 = Writes::where(["ressource_id" => $data["ressource_id_14"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_14 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_14"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_14 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_30"><a href="{{ route('clients') }}" id="text_30">🧑‍🤝‍🧑 Gestion de clients</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_40 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_25"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_25 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_25"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_25 = Writes::where(["ressource_id" => $data["ressource_id_25"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_25 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_25"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_25 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_46"><a href="{{ route('prospects') }}" id="text_46">👤 Gestion de prospects</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_41 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_26"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_26 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_26"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_26 = Writes::where(["ressource_id" => $data["ressource_id_26"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_26 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_26"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_26 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_47"><a href="{{ route('suivi_credit') }}" id="text_47">🏷️  Suivi de crédit</a></li>
                <?php } ?>
            <?php } ?>
        @endif


        @if ($menu_42 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_27"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_27 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_27"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_27 = Writes::where(["ressource_id" => $data["ressource_id_27"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_27 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_27"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_27 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_48"><a href="{{ route('facture_point_vente') }}" id="text_48">🧾 Facture point vente</a></li>
                <?php } ?>
            <?php } ?>
        @endif
        
        @if ($menu_43 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_28"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_28 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_28"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_28 = Writes::where(["ressource_id" => $data["ressource_id_28"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_28 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_28"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_28 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_49"><a href="{{ route('listesdesinvites') }}" id="text_49">🧑‍🤝‍🧑  Liste des invités</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_44 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_29"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_29 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_29"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_29 = Writes::where(["ressource_id" => $data["ressource_id_29"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_29 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_29"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_29 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_50"><a href="{{ route('suivi_prospect') }}" id="text_50">👤  Suivi de prospect</a></li>
                <?php } ?>
            <?php } ?>
        @endif


        @if ($menu_33 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_19"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_19 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_19"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_19 = Writes::where(["ressource_id" => $data["ressource_id_19"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_19 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_19"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_19 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_40"><a href="{{ route('utilisateurs') }}" id="text_40">👥 Gestions utilisateurs</a></li>
                <?php } ?>
            <?php } ?>
        @endif


        @if ($menu_14 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_15"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_15 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_15"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_15 = Writes::where(["ressource_id" => $data["ressource_id_15"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_15 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_15"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_15 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_31"><a href="{{ route('listesfactures') }}" id="text_31">🧾 Gestion de facture</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_15 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_16"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_16 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_16"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_16 = Writes::where(["ressource_id" => $data["ressource_id_16"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_16 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_16"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_16 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_33"><a href="{{ route('alerte_centrale') }}" id="text_33">🔔 Alerte centralisées</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_16 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_17"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_17 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_17"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_17 = Writes::where(["ressource_id" => $data["ressource_id_17"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_17 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_17"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_17 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_34"><a href="{{ route('alerte_mobile') }}" id="text_34">📱 Alerte mobile</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_28 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_18"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_18 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_18"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_18 = Writes::where(["ressource_id" => $data["ressource_id_18"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_18 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_18"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_18 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_36"><a href="{{ route('gestion_depense') }}" id="text_36"> 📤 Dépense</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_34 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_20"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_20 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_20"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_20 = Writes::where(["ressource_id" => $data["ressource_id_20"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_20 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_20"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_20 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_41"><a href="{{ route('gestion_ecole') }}" id="text_41"> 🏫 Gestion école</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_35 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_21"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_21 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_21"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_21 = Writes::where(["ressource_id" => $data["ressource_id_21"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_21 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_21"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_21 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_42"><a href="{{ route('gestion_beneficiaire') }}" id="text_42"> 👥 Gestion bénéficiaire</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_36 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_22"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_22 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_22"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_22 = Writes::where(["ressource_id" => $data["ressource_id_22"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_22 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_22"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_22 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_43"><a href="{{ route('rapport_pointage') }}" id="text_43"> 📊 Rapport de pointage</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_38 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_23"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_23 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_23"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_23 = Writes::where(["ressource_id" => $data["ressource_id_23"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_23 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_23"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_23 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_44"><a href="{{ route('serveur_se') }}" id="text_43"> 🍽️ Serveur(se)</a></li>
                <?php } ?>
            <?php } ?>
        @endif

        @if ($menu_39 == 1)
            <?php if ((Writes::where(["ressource_id" => $data["ressource_id_24"], "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                <?php
                    $display_24 = 0;
                    if ((Writes::where(["ressource_id" => $data["ressource_id_24"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                    $display_24 = Writes::where(["ressource_id" => $data["ressource_id_24"], "groupe_id" => $groupe_user_id])->get()[0]->display;
                    }
                ?>
                <?php if (((($display_24 ==  1)) && (Writes::where(["ressource_id" => $data["ressource_id_24"], "groupe_id" => $groupe_user_id])->get()->count() != 0)) || (($display_24 ==  0) && (Auth::user()->role == 0))) { ?>
                    <li id="link_45"><a href="{{ route('debarrasseur_se') }}" id="text_43"> 🧹 Débarrasseur(se)</a></li>
                <?php } ?>
            <?php } ?>
        @endif

      </ul>

      @if (Auth::user()->role == 0)
        <div class="admin-tools-title">🔧 Administration</div>
        <ul class="navigation" style="margin-top:0">
            @if ($menu_17 == 1)
                <li id="link_11"><a href="{{ route('droits') }}" id="text_11"><i class="zmdi zmdi-accounts"></i> Rôles utilisateurs</a></li>
            @endif

            @if ($menu_18 == 1)
                <li style="display: none;" id="link_10"><a href="{{ route('utilisateurs') }}" id="text_10"><i class="zmdi zmdi-account"></i> Gestions utilisateurs</a></li>
            @endif

            @if ($menu_19 == 1)
                <li id="link_32"><a href="{{ route('postes') }}" id="text_10"><i class="zmdi zmdi-pin"></i> Gestions de poste</a></li>
                {{-- <li id="link_32"><a href="{{ route('postes') }}" id="text_10"><i class="zmdi zmdi-pin"></i> Gestions de poste</a></li> --}}
            @endif

            @if ($menu_20 == 1)
                <li id="link_12" style="display: none;"><a href="{{ route('contrevenants') }}" id="text_12"><i class="zmdi zmdi-accounts"></i> Contrevenants</a></li>
            @endif

            @if ($menu_21 == 1)
                <li id="link_13" style="display: none;"><a href="{{ route('verbalisateurs') }}" id="text_13"><i class="zmdi zmdi-accounts"></i> Verbalisateurs</a></li>
            @endif

            @if ($menu_22 == 1)
                <li id="link_28"><a href="{{ route('type_documents') }}" id="text_28"><i class="zmdi zmdi-book"></i> Types de document</a></li>
            @endif

            @if ($menu_23 == 1)
                <li id="link_14"><a href="{{ route('type_frais') }}" id="text_14"><i class="zmdi zmdi-money-box"></i> Types de dépense</a></li>
            @endif

            @if ($menu_24 == 1)
                <li id="link_15" style="display: none;"><a href="{{ route('type_infractions') }}" id="text_15"><i class="zmdi zmdi-library"></i> Types infractions</a></li>
            @endif

            @if ($menu_25 == 1)
                <li id="link_20"><a href="{{ route('gestion_societe') }}" id="text_20"><i class="zmdi zmdi-label"></i> Catégorie d'article</a></li>
            @endif

            @if ($menu_26 == 1)
                <li id="link_21"><a href="{{ route('gestion_solde') }}" id="text_21"><i class="zmdi zmdi-money"></i> Gestion de solde</a></li>
            @endif

            @if ($menu_27 == 1)
                <li id="link_35"><a href="{{ route('gestion_activiter') }}" id="text_35"><i class="zmdi zmdi-toll"></i> Gestion d'activité</a></li>
            @endif

            @if ($menu_30 == 1)
                <li id="link_37"><a href="{{ route('point_vente') }}" id="text_37"><i class="zmdi zmdi-storage"></i> Points de vente</a></li>
            @endif

            @if ($menu_32 == 1)
                <li id="link_39"><a href="{{ route('gestion_stock') }}" id="text_39"><i class="zmdi zmdi-archive"></i> Gestion de stock</a></li>
            @endif

            @if ($menu_31 == 1)
                <li id="link_38"><a href="{{ route('gestion_table') }}" id="text_38"><i class="zmdi zmdi-grid"></i> Gestion de table</a></li>
            @endif
            @if ($menu_37 == 1)
                <li id="link_44"><a href="{{ route('consulter_rapport') }}" id="text_44"><i class="fas fa-book-open"></i> Consulter rapport</a></li>
            @endif
        </ul>
      @endif
    </div>
  </aside>

  <!-- ===================== MODALES ===================== -->
  <div class="modal fade" id="deconnexion" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-power"></i> Déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center">
          <img src="{{ asset('assets/img/icons/question.png') }}" width="55" style="margin-bottom: 10px;">
          <p class="mb-0" style="font-weight: 500;">Voulez-vous vous déconnecter ?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <a id="_deconnexion" href="#" class="btn btn-info">Oui</a>
          <button class="btn btn-danger" data-dismiss="modal">Non</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="refus" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-block"></i> Autorisation</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center">
          <i class="zmdi zmdi-alert-triangle" style="font-size: 48px; color: var(--red-dark);"></i>
          <p class="mt-2 mb-0">Accès refusé</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">D'accord</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="cloturer" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-alert-circle"></i> Alerte</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center">
          <i class="zmdi zmdi-file-text" style="font-size: 48px; color: var(--red-dark);"></i>
          <p class="mt-2 mb-0">Ce procès-verbal est déjà clôturé</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">D'accord</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="preuve" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titre_modal" class="modal-title"><i class="zmdi zmdi-file-text"></i> Document</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center p-0">
          <iframe style="width:100%; height:450px; border-radius: 0;" id="f_preuve" src="" frameborder="0"></iframe>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="preuve_fichier" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titre_modal_fichier" class="modal-title"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center p-0">
          <iframe style="width:100%; height:450px; display:none;" id="src_fichier" src="" frameborder="0"></iframe>
          <div id="fichier_content" style="min-height:450px; padding:20px;"></div>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalExcelViewer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width:95%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-chart"></i> Visualisation Excel</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div id="excelViewerContainer"></div>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalprogramme" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width:95%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-calendar"></i> VISUALISATION DU DETAIL DU POSTE <span id="detail_pro"></span></h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div id="contenu_programme"></div>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-danger" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
<?php }elseif(Auth::user()->module_connected == 2){ ?>
<!-- ===================== SIDEBAR ===================== -->
  <aside class="sidebar">
    <div id="menu" class="scrollbar-inner">
      <div class="user">
        <div class="user__info" data-toggle="dropdown">
          <img id="user__img" class="user__img" src="{{ asset( Auth::user()->image ) }}" alt="">
          <div>
            @if (Auth::user()->role == 1)
            <div class="user__name">{{ "OASISTECH - ADMIN" }}</div>
            @endif
            @if (Auth::user()->role == 2)
            <div class="user__name">{{ "ADMINISTRATEUR" }}</div>
            @endif
            @if (Auth::user()->role == 3)
            <div class="user__name">{{ "ETUDIANT" }}</div>
            @endif
            @if (Auth::user()->role == 4)
            <div class="user__name">{{ "COLLABORATEUR" }}</div>
            @endif
            <div class="user__email">{{ Auth::user()->name }}</div>
          </div>
        </div>

        <div class="dropdown-menu">
          <a data-toggle="modal" data-target="#deconnexion" class="dropdown-item" href="#"><i class="zmdi zmdi-power"></i> Déconnexion</a>
          <a style="display:none;" id="btn_refus" data-toggle="modal" data-target="#refus" class="dropdown-item" href="#"><i class="zmdi zmdi-block"></i> Accès refusé</a>
          <a style="display:none;" id="btn_cloturer" data-toggle="modal" data-target="#cloturer" class="dropdown-item" href="#"><i class="zmdi zmdi-alert-circle"></i> PV clôturé</a>
          <a style="display:none;" id="btn_preuve" data-toggle="modal" data-target="#preuve" class="dropdown-item" href="#"><i class="zmdi zmdi-file-text"></i> Voir preuve</a>
          <a style="display:none;" class="dropdown-item" href="{{ route('profils') }}"><i class="zmdi zmdi-edit"></i> Profil</a>
          <a style="display:none;" id="btn_detail_fichier" data-toggle="modal" data-target="#preuve_fichier" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
          <a style="display:none;" id="btn_detail_programme" data-toggle="modal" data-target="#modalprogramme" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
        </div>
      </div>
    </div>
  </aside>

  <!-- ===================== MODALES ===================== -->
  <div class="modal fade" id="deconnexion" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-power"></i> Déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center">
          <img src="{{ asset('assets/img/icons/question.png') }}" width="55" style="margin-bottom: 10px;">
          <p class="mb-0" style="font-weight: 500;">Voulez-vous vous déconnecter ?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <a id="_deconnexion" href="#" class="btn btn-info">Oui</a>
          <button class="btn btn-danger" data-dismiss="modal">Non</button>
        </div>
      </div>
    </div>
  </div>
<?php }else{ ?>
<aside class="sidebar">
    <div id="menu" class="scrollbar-inner">
      <div class="user">
        <div class="user__info" data-toggle="dropdown">
          <img id="user__img" class="user__img" src="{{ asset( Auth::user()->image ) }}" alt="">
          <div>
            @if (Auth::user()->role == 1)
            <div class="user__name">{{ "OASISTECH - ADMIN" }}</div>
            @endif
            @if (Auth::user()->role == 2)
            <div class="user__name">{{ "ADMINISTRATEUR" }}</div>
            @endif
            @if (Auth::user()->role == 3)
            <div class="user__name">{{ "ETUDIANT" }}</div>
            @endif
            @if (Auth::user()->role == 4)
            <div class="user__name">{{ "COLLABORATEUR" }}</div>
            @endif
            <div class="user__email">{{ Auth::user()->name }}</div>
          </div>
        </div>

        <div class="dropdown-menu">
          <a data-toggle="modal" data-target="#deconnexion" class="dropdown-item" href="#"><i class="zmdi zmdi-power"></i> Déconnexion</a>
          <a style="display:none;" id="btn_refus" data-toggle="modal" data-target="#refus" class="dropdown-item" href="#"><i class="zmdi zmdi-block"></i> Accès refusé</a>
          <a style="display:none;" id="btn_cloturer" data-toggle="modal" data-target="#cloturer" class="dropdown-item" href="#"><i class="zmdi zmdi-alert-circle"></i> PV clôturé</a>
          <a style="display:none;" id="btn_preuve" data-toggle="modal" data-target="#preuve" class="dropdown-item" href="#"><i class="zmdi zmdi-file-text"></i> Voir preuve</a>
          <a style="display:none;" class="dropdown-item" href="{{ route('profils') }}"><i class="zmdi zmdi-edit"></i> Profil</a>
          <a style="display:none;" id="btn_detail_fichier" data-toggle="modal" data-target="#preuve_fichier" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
          <a style="display:none;" id="btn_detail_programme" data-toggle="modal" data-target="#modalprogramme" class="dropdown-item" style="display:none;" href="#"><i class="zmdi zmdi-attachment-alt"></i> Détail fichier</a>
        </div>
      </div>
    </div>
  </aside>

  <!-- ===================== MODALES ===================== -->
  <div class="modal fade" id="deconnexion" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="zmdi zmdi-power"></i> Déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body text-center">
          <img src="{{ asset('assets/img/icons/question.png') }}" width="55" style="margin-bottom: 10px;">
          <p class="mb-0" style="font-weight: 500;">Voulez-vous vous déconnecter ?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <a id="_deconnexion" href="#" class="btn btn-info">Oui</a>
          <button class="btn btn-danger" data-dismiss="modal">Non</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
