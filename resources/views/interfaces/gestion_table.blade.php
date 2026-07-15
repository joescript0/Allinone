<?php
    use App\Models\Pointdeventes;
?>
@extends('layouts.main')
@section('title', 'AFRICTECHAPP')
@section('name', 'GESTION DE TABLE')
@section('body')
    @include('composants.preload')
    @include('composants.header')
    @include('composants.sidebar')
    @include('composants.chat')
    <style>
        /* =============================================
           DESIGN PREMIUM - VERSION FINALE
           BOUTONS MODERNES & RESPONSIFS
           LIGNES DE TABLEAU RÉDUITES ET ÉQUILIBRÉES
           MESSAGE D'ERREUR/SUCCÈS TOTALEMENT CACHÉ PAR DÉFAUT
           PRISE EN CHARGE DU FORMULAIRE D'ÉDITION
           ============================================= */

        /* --- Reset des marges pour occuper tout l'écran --- */
        body {
            margin: 0;
            padding: 0;
            background: #f0f4f8;
        }

        .content .container {
            max-width: 100% !important;
            width: 100%;
            padding: 1rem 2rem !important;
            margin: 0 auto;
            background: #f8fafc;
        }

        .content .container .row {
            margin-left: 0;
            margin-right: 0;
        }

        .content .container [class*="col-"] {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        /* --- Variables --- */
        :root {
            --bleu-nuit: #0a192f;
            --bleu-nuit-clair: #112240;
            --bleu-nuit-gradient: linear-gradient(135deg, #0a192f, #1e3a5f);
            --rouge-feu: #e31b23;
            --rouge-fonce: #b91c1c;
            --rouge-gradient: linear-gradient(135deg, #dc2626, #b91c1c);
            --vert-succes: #10b981;
            --shadow-premium: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius-xl: 20px;
            --border-radius-lg: 16px;
        }

        /* --- Cartes principales --- */
        #bloc_1,
        #bloc_2,
        #bloc_3 {
            background: rgba(255, 255, 255, 0.96);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-premium);
            padding: 2rem 1.8rem !important;
            margin-bottom: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* --- En-têtes --- */
        h4 {
            font-weight: 700;
            border-left: 6px solid var(--rouge-feu);
            padding-left: 18px;
            margin-bottom: 28px;
            color: var(--bleu-nuit);
        }

        h4 i.zmdi {
            background: var(--bleu-nuit-gradient);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent !important;
        }

        /* ========== TABLEAU ÉQUILIBRÉ ========== */
        .table-responsive {
            border-radius: var(--border-radius-lg);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            min-width: 600px;
            background: white;
            border-collapse: collapse;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .table thead th {
            background: var(--bleu-nuit-gradient);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 10px !important;
            border-bottom: none;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #eef2f6;
        }

        .table tbody tr:hover {
            background: #f0f5fe !important;
        }

        .table tbody td {
            padding: 8px 10px !important;
            vertical-align: middle;
            font-weight: 500;
            font-size: 0.85rem;
            color: #1e2a3e;
            word-break: break-word;
        }

        /* ========== BOUTONS RONDS POUR LA COLONNE CONTROL ========== */
        .table tbody td a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50% !important;
            background: #f1f5f9;
            transition: all 0.2s ease;
            text-decoration: none;
            margin: 0 2px;
        }

        .table tbody td a i.zmdi {
            font-size: 1.1rem;
            margin: 0;
        }

        .table tbody td a i.zmdi-edit {
            color: #2c7da0;
        }

        .table tbody td a:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }

        .table tbody td a i.zmdi-delete {
            color: var(--rouge-feu);
        }

        .table tbody td a:hover i.zmdi-delete {
            color: var(--rouge-fonce);
        }

        .table tbody td a:hover {
            background: #ffe5e5;
        }

        /* ========== BOUTONS PRINCIPAUX ========== */
        #liste,
        #add,
        #print,
        #add_r,
        #print_r,
        .btn-primary,
        .btn-primary.btn-sm,
        a.btn-primary,
        .btn-info,
        .btn-info.btn-sm,
        .btn-danger,
        .btn-danger.btn-sm,
        #edit_save,
        #edit_annuler {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 18px !important;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 40px !important;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: var(--shadow-light);
            white-space: nowrap;
        }

        #liste {
            background: linear-gradient(135deg, #0a192f, #1e3a5f) !important;
            color: white !important;
        }

        #liste:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(10, 25, 47, 0.3);
        }

        #add,
        a#add {
            background: linear-gradient(135deg, #0f4c5f, #1e6f5c) !important;
            color: white !important;
        }

        #add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
        }

        #print {
            background: linear-gradient(135deg, #4b6e8a, #2c4f6e) !important;
            color: white !important;
        }

        #print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(43, 76, 108, 0.3);
        }

        #add_r,
        #print_r {
            background: #cbd5e1 !important;
            color: #475569 !important;
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        #add_r:hover,
        #print_r:hover {
            transform: none;
            box-shadow: none;
        }

        #save,
        #save_r,
        #annuler,
        #edit_save,
        #edit_annuler {
            padding: 8px 24px !important;
            font-weight: 700;
        }

        #save,
        #edit_save {
            background: linear-gradient(95deg, #0f4c5f, #0e6b5e) !important;
            color: white;
        }

        #save:hover,
        #edit_save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 76, 95, 0.3);
        }

        #annuler,
        #edit_annuler {
            background: #64748b !important;
            color: white;
        }

        #annuler:hover,
        #edit_annuler:hover {
            background: #475569 !important;
            transform: translateY(-2px);
        }

        /* ========== FORMULAIRES : AJOUT ET MODIFICATION ========== */
        #form_add .row,
        #form_edit .row {
            display: flex;
            flex-wrap: wrap;
        }

        #form_add .col-6,
        #form_edit .col-6 {
            margin-bottom: 1rem;
        }

        .form-group {
            width: 100%;
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: var(--bleu-nuit);
            margin-bottom: 6px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-group label i {
            color: var(--rouge-feu);
            margin-right: 6px;
        }

        .form-control,
        input.form-control,
        select.form-control,
        textarea.form-control,
        .input-mask {
            width: 100% !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            padding: 10px 14px !important;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
            box-sizing: border-box;
            height: 42px !important;
            line-height: 1.4;
        }

        textarea.form-control {
            resize: vertical;
            height: 42px !important;
        }

        .form-control:focus,
        select.form-control:focus,
        textarea.form-control:focus {
            border-color: var(--bleu-nuit) !important;
            box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15) !important;
            transform: translateY(-1px);
        }

        select.form-control {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        .input-mask {
            font-family: monospace;
            background: #fff9ef !important;
        }

        /* ========== MESSAGES MODERNES - TOTALEMENT INVISIBLE PAR DÉFAUT (ajout & édition) ========== */
        #msg,
        #edit_msg {
            display: none !important;
            /* Caché quoi qu'il arrive */
            visibility: hidden !important;
            /* Invisible même s'il prend de la place */
            opacity: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            min-height: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        #msg:not(:empty),
        #edit_msg:not(:empty) {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            margin-top: 16px !important;
            padding: 10px 18px !important;
            background: white !important;
            border-radius: 50px !important;
            box-shadow: var(--shadow-light) !important;
            gap: 10px;
            font-weight: 600;
            font-size: 0.8rem;
            animation: slideInMsg 0.3s ease-out;
        }

        #msg:not(:empty):has(i.zmdi-check-circle),
        #edit_msg:not(:empty):has(i.zmdi-check-circle) {
            background: linear-gradient(95deg, #d1fae5, #a7f3d0) !important;
            color: #065f46;
            border-left: 4px solid var(--vert-succes);
        }

        #msg:not(:empty):has(i.zmdi-close-circle),
        #edit_msg:not(:empty):has(i.zmdi-close-circle) {
            background: linear-gradient(95deg, #fee2e2, #fecaca) !important;
            color: #991b1b;
            border-left: 4px solid var(--rouge-feu);
        }

        @keyframes slideInMsg {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========== BARRE D'ACTIONS EN HAUT ========== */
        [style*="background-color: rgba(0, 0, 0, 0.1)"] {
            background: #eef3fc !important;
            border-radius: 60px;
            padding: 10px 24px !important;
            margin-bottom: 20px;
            display: flex !important;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-start;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .content .container {
                padding: 0.8rem 1rem !important;
            }

            #bloc_1,
            #bloc_2,
            #bloc_3 {
                padding: 1.2rem !important;
            }

            #liste,
            #add,
            #print,
            #add_r,
            #print_r,
            .btn-primary,
            .btn-info,
            .btn-danger,
            #edit_save,
            #edit_annuler {
                padding: 6px 14px !important;
                font-size: 0.75rem;
                white-space: nowrap;
            }

            [style*="background-color: rgba(0, 0, 0, 0.1)"] {
                justify-content: center;
                gap: 8px;
            }

            #form_add .col-6,
            #form_edit .col-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .form-control,
            input.form-control,
            select.form-control,
            textarea.form-control {
                height: 40px !important;
                font-size: 0.8rem;
            }

            .table thead th {
                font-size: 0.7rem;
                padding: 6px 6px !important;
            }

            .table tbody td {
                padding: 6px 6px !important;
                font-size: 0.75rem;
            }

            .table tbody td a {
                width: 28px;
                height: 28px;
            }

            .table tbody td a i.zmdi {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .content .container {
                padding: 0.5rem !important;
            }

            .btn,
            .btn-sm,
            #liste,
            #add,
            #print,
            #edit_save,
            #edit_annuler {
                padding: 4px 10px !important;
                font-size: 0.7rem;
            }

            .form-group label {
                font-size: 0.7rem;
            }

            [style*="background-color: rgba(0, 0, 0, 0.1)"] {
                gap: 6px;
                padding: 8px 12px !important;
            }

            .table thead th {
                font-size: 0.6rem;
                padding: 4px 4px !important;
            }

            .table tbody td {
                font-size: 0.7rem;
                padding: 5px 4px !important;
            }
        }

        /* ========== ANIMATIONS & DÉTAILS ========== */
        @keyframes glow {
            0% {
                box-shadow: 0 0 0 0 rgba(227, 27, 35, 0.2);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(227, 27, 35, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(227, 27, 35, 0);
            }
        }

        .btn-danger:active {
            animation: glow 0.3s ease-out;
        }

        .modal-header {
            background: var(--bleu-nuit-gradient);
        }

        input[required],
        select[required],
        textarea[required] {
            border-left: 3px solid var(--rouge-feu) !important;
        }
    </style>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div style="background-color: rgba(0, 0, 0, 0.1);padding-top: 10px;padding-bottom: 10px;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn-primary btn-sm" id="liste" href="">
                                        <i class="zmdi zmdi-money"></i> Liste
                                    </a>
                                    &nbsp;
                                    <a class="btn-primary btn-sm" id="add" href="">
                                        <i class="zmdi zmdi-plus-circle"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 30px;" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6 style="color:rgba(0, 0, 0, 0.6);">{{ strtoupper(Auth::user()->name) }}&nbsp; <i
                            class="zmdi zmdi-chevron-right"></i> &nbsp; Gestion de table</h6>
                </div>
                <div id="bloc_1" style="margin-top: 12px;padding-bottom: 50px" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-money text-info"></i>
                        Liste</h4>
                    <div id="content_groupe" class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Etat</th>
                                            <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{ !($i = 1) }}
                                        @foreach ($tables as $data)
                                            <tr>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    {{ $data->pointdeventes_id != null ? Pointdeventes::where('id', $data->pointdeventes_id)->first()->nom : 'Aucun point de vente' }}
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data->occupee == 0)
                                                        <i class="zmdi zmdi-check-circle text-success"></i> <span
                                                            class="text-success">{{ 'Libre' }} </span>
                                                    @elseif ($data->occupee == 1)
                                                        <i class="zmdi zmdi-check-circle text-danger"></i> <span
                                                            class="text-danger"> Occupee</span>
                                                    @else
                                                        <i class="zmdi zmdi-close-circle text-warning"></i> <span
                                                            class="text-warning">Aucun detail</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                                    <a id="edit_<?= $i ?>" href="#"><i
                                                            class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                                    <a id="delete_<?= $i ?>" href="#"><i
                                                            class="zmdi zmdi-delete text-danger"></i></a>
                                                    <script>
                                                        $("#edit_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $.get("{{ url('/refresh_edit_tables') }}", {
                                                                table_id: <?= $data->id ?>,
                                                            }, function(refresh_edit_tables) {
                                                                $("#bloc_1").hide();
                                                                $("#bloc_2").hide();
                                                                $("#bloc_3").show();
                                                                $("#bloc_3").html(refresh_edit_tables);
                                                            });
                                                        });
                                                        $("#delete_<?= $i ?>").click(function(e) {
                                                            e.preventDefault();
                                                            $("#element").html("<?= $data->nom ?>");
                                                            $("#data_id").html("<?= $data->id ?>");
                                                            $("#btn_sup").trigger("click");
                                                        });
                                                    </script>
                                                </td>
                                            </tr>
                                            {{ !$i++ }}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bloc_2" style="margin-top: 12px;display: none;" class="col-lg-12">
                    <h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;"
                            class="zmdi zmdi-plus-circle text-info"></i> Ajouter une table</h4>
                    <form id="form_add" action="#" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-info"></i> Nom </span></label>
                                    <input type="text" id="nom" name="nom"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Nom (Ex : Table 1)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-storage text-danger"></i> Point de vente </span></label>
                                    <select id="point_vente_id" name="point_vente_id"
                                        style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control">
                                        <option class="form-control" value="">Table</option>
                                        @foreach ($point_ventes as $data)
                                            <option class="form-control" value="{{ $data->id }}"> {{ $data->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="text-info" style="font-weight: bold;margin-top: 16px;"><i
                                            class="zmdi zmdi-comment"></i> Description </span></label>
                                    <textarea style="font-weight: bold;border-radius:5px;padding-left: 5px;border: 1px solid rgba(0, 0, 0, 0.2);"
                                        class="form-control" placeholder="Description" name="description" id="description" cols="2"
                                        rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button id="save" class="btn btn-info btn-sm">Enregister <i
                                        class="zmdi zmdi-save"></i></button> <button id="annuler"
                                    class="btn btn-danger btn-sm">Annuler <i class="zmdi zmdi-close-circle"></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="text-align: center;">
                                <span style="font-weight: bold;" id="msg">
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="bloc_3" style="margin-top: 12px;display: none;" class="col-lg-12">

                </div>
                <div id="bloc_4" style="margin-top: 12px;display: none;" class="col-lg-12">

                </div>
            </div>
        </div>
    </section>
    <span id="data_id" style="display: none;"></span>
    <button style="display: none;" data-toggle="modal" data-target="#suppression" id="btn_sup">Sup</button>
    <div class="modal fade" id="suppression" tabindex="-1">
        <div class="modal-dialog modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left text-center" style="font-weight: bold;font-size: 16px;">Voulez-vous
                        vous supprimez ? </h5>
                </div>
                <div class="modal-body">
                    <p id="element" style="text-align: center;">

                    </p>
                </div>
                <div style="font-weight: bold;text-align: center;">
                    <p class="text-center" style="font-weight: bold;text-align: center;">
                        <a style="color: white;font-weight: bold;" id="oui" href="#"
                            class="btn btn-info btn-sm">Oui</a>
                        <button style="font-weight: bold;" id="non" class="btn btn-danger btn-sm"
                            data-dismiss="modal">Non</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
@section('js-code')
    <script src="{{ asset('assets/vendors/flot/jquery.flot.js') }} "></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.orderbars/jquery.flot.orderBars.js') }} "></script>
    <script src="{{ asset('assets/demo/js/flot-charts/curved-line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/line.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/bar.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/dynamic.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/pie.js') }}"></script>
    <script src="{{ asset('assets/demo/js/flot-charts/chart-tooltips.js') }}"></script>
    <script>
        $("#link_38").css("border-left", "1px solid rgb(33, 150, 243)");
        $("#text_38").addClass("text-info");
        $("#icone_38").css("color", "rgb(33, 150, 243)");
        $("#upload").click(function(e) {
            e.preventDefault();
            $("#dropzone-upload").trigger("click");
        })
        $("#liste").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
        });
        $("#add").click(function(e) {
            e.preventDefault();
            $("#bloc_1").hide();
            $("#bloc_2").show();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
        });
        $("#annuler").click(function(e) {
            e.preventDefault();
            $("#bloc_1").show();
            $("#bloc_2").hide();
            $("#bloc_3").hide();
            $("#bloc_4").hide();
        });
        $("#save").click(function(e) {
            e.preventDefault();
            var nom = $("#nom").val();
            var point_vente_id = $("#point_vente_id").val();
            var description = $("#description").val();
            var data = $("#form_add").serialize();
            if (nom.trim().length == 0) {
                $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Completez le nom de la table');
                $('#msg').css('color', "#ff6b68");
                setTimeout(() => {
                    $('#msg').html("");
                }, 9000);
            } else {
                if(point_vente_id.trim().length == 0){
                    $('#msg').html('<i class="zmdi zmdi-close-circle"></i> Selectionnez le point de vente');
                    $('#msg').css('color', "#ff6b68");
                    setTimeout(() => {
                        $('#msg').html("");
                    }, 9000);
                }else{
                        $.ajax({
                        type: "POST",
                        url: "/check_tables",
                        data: data,
                        success: function(response) {
                            if (response == 1) {
                                $('#msg').html(
                                    '<i class="zmdi zmdi-close-circle"></i> Cette table existe déjà'
                                );
                                $('#msg').css('color', "#ff6b68");
                                setTimeout(() => {
                                    $('#msg').html("");
                                }, 9000);
                            } else
                            {
                                $("#save").attr("disabled", true);
                                $.ajax({
                                    type: "POST",
                                    url: "/add_table",
                                    data: data,
                                    success: function(response) {
                                        $("#save").attr("disabled", false);
                                        $("#nom").val("");
                                        $("#description").val("");
                                        $('#msg').html(
                                            '<i class="zmdi zmdi-check-circle"></i> Table ajoutée avec succès'
                                        );
                                        $('#msg').css("color", '#32c787');
                                        $("#content_groupe").html(response);
                                        setTimeout(() => {
                                            $('#msg').html("");
                                        }, 9000);
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });

        $("#oui").click(function(e) {
            e.preventDefault();
            var id = $("#data_id").html();
            $.get("{{ url('/refresh_delete_tables') }}", {
                id: id,
            }, function(refresh_editverbalisateur) {
                $("#content_groupe").html(refresh_editverbalisateur);
                $("#non").trigger("click");
            });
        });
    </script>
@endsection
@endsection
