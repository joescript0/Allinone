<?php
    $option  = 5;
    $nom_app = "";
    if($option == 1)
    {
        $nom_app = "AFRICTECHAPP";
    }elseif ($option == 2) {
        $nom_app = "ILAINAPP";
    }
    elseif ($option == 3) {
        $nom_app = "CONTROLAPP";
    }
    elseif ($option == 4) {
        $nom_app = "EDIPASERVICE";
    }
     elseif ($option == 5)
    {
        $nom_app = "LES300HOMMES";
    }

?>
<header style="border-bottom: 3px solid rgb(251, 187, 27); background-color: black;" class="header">
    <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>

    <div class="header__logo">
        <h1><a href="#"><i style="color: rgb(251, 187, 27);" class="zmdi zmdi-home"></i> {{ $nom_app }} </a></h1>
        <p style="font-size: 10px;">ALL IN ONE</p>
    </div>

    <ul class="top-nav">
        <li style="display: none;" class="hidden-xl-up"><a href="#" data-ma-action="search-open"><i class="zmdi zmdi-search"></i></a></li>
        <li style="display: none;" class="dropdown hidden-xs-down">
            <a href="#" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-item theme-switch">
                    Theme Switch
                    <div class="btn-group btn-group-toggle btn-group--colors" data-toggle="buttons">
                        <label class="btn bg-green active"><input type="radio" value="green" autocomplete="off" checked></label>
                        <label class="btn bg-blue"><input type="radio" value="blue" autocomplete="off"></label>
                        <label class="btn bg-red"><input type="radio" value="red" autocomplete="off"></label>
                        <label class="btn bg-orange"><input type="radio" value="orange" autocomplete="off"></label>
                        <label class="btn bg-teal"><input type="radio" value="teal" autocomplete="off"></label>
                        <div class="clearfix mt-2"></div>
                        <label class="btn bg-cyan"><input type="radio" value="cyan" autocomplete="off"></label>
                        <label class="btn bg-blue-grey"><input type="radio" value="blue-grey" autocomplete="off"></label>
                        <label class="btn bg-purple"><input type="radio" value="purple" autocomplete="off"></label>
                        <label class="btn bg-indigo"><input type="radio" value="indigo" autocomplete="off"></label>
                        <label class="btn bg-brown"><input type="radio" value="brown" autocomplete="off"></label>
                    </div>
                </div>
                <a href="#" class="dropdown-item">Fullscreen</a>
                <a href="#" class="dropdown-item">Clear Local Storage</a>
            </div>
        </li>
    </ul>

    <!-- Zone utilisateur simplifiée -->
    <div class="dropdown user-dropdown">
        <a href="#" data-toggle="dropdown">
            <span id="user__email_1">{{ strtoupper(Auth::user()->name) }}</span>
            <i class="zmdi zmdi-account"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a data-toggle="modal" data-target="#deconnexion" href="#" class="deconnexion-link">
                <i class="zmdi zmdi-power"></i> Quitter
            </a>
        </div>
    </div>
</header>
