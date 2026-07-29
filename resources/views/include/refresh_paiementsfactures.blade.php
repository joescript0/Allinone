<?php

use App\Models\Factureas;
use App\Models\Listespaies;
use App\Models\Mois;
use App\Models\Annees;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\User;
use App\Models\Utilisateurs;
use App\Models\Groupes;
use App\Models\Writes;
use App\Models\Paiesfactures;
use App\Models\Paiementsfactures;
use App\Models\Clients;

?>

<style>
    /* ========== STYLES DES FILTRES ========== */
    .filters-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 160px;
    }

    .filter-group label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #0a192f;
        font-size: 0.75rem;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group .form-control {
        height: 42px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 14px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .filter-group .form-control:focus {
        border-color: #0a192f;
        box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.15);
        outline: none;
    }

    .filter-group select.form-control {
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e31b23" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>');
        background-repeat: no-repeat;
        background-position: right 14px center;
    }

    .client-count-badge {
        background: linear-gradient(135deg, #e31b23, #b91c1c);
        color: white;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr.highlight {
        background-color: #fff3cd !important;
        animation: highlightFlash 1s ease;
    }

    @keyframes highlightFlash {
        0% {
            background-color: #fff3cd;
        }
        100% {
            background-color: transparent;
        }
    }

    @media (max-width: 768px) {
        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            width: 100%;
        }
    }

    /* Style pour le date range picker (s'il est chargé) */
    .daterangepicker {
        z-index: 9999 !important;
    }
    .daterangepicker .calendar-table {
        border-radius: 8px !important;
    }
    .daterangepicker td.active,
    .daterangepicker td.active:hover {
        background-color: #e31b23 !important;
    }
    .daterangepicker .ranges li.active {
        background-color: #e31b23 !important;
    }

    /* ===== TABLEAU RESPONSIVE GLOBAL ===== */
    .table {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
    }
    .table th, .table td {
        word-break: break-word;
        overflow-wrap: break-word;
        vertical-align: middle;
    }

    /* Définition des largeurs de colonnes (adaptées) */
    .table th:nth-child(1), .table td:nth-child(1) { width: 5%; }   /* N° */
    .table th:nth-child(2), .table td:nth-child(2) { width: 18%; }  /* Nom */
    .table th:nth-child(3), .table td:nth-child(3) { width: 12%; }  /* Contact */
    .table th:nth-child(4), .table td:nth-child(4) { width: 22%; }  /* Paiement */
    .table th:nth-child(5), .table td:nth-child(5) { width: 18%; }  /* Date */
    .table th:nth-child(6), .table td:nth-child(6) { width: 15%; }  /* Control */

    /* Colonne Contact */
    .contact-cell {
        text-align: center;
    }
    .contact-cell .btn-contact {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #0a192f;
        transition: transform 0.2s;
        cursor: pointer;
        padding: 0 5px;
    }
    .contact-cell .btn-contact:hover {
        transform: scale(1.1);
        color: #e31b23;
    }

    /* ===== RESPONSIVE : MOBILE ===== */
    @media (max-width: 767.98px) {
        .table {
            table-layout: fixed;
            font-size: 0.7rem;
        }
        .table th, .table td {
            padding: 4px 2px !important;
            font-size: 0.65rem;
        }

        .table th:nth-child(1), .table td:nth-child(1) { width: 6%; }
        .table th:nth-child(2), .table td:nth-child(2) { width: 20%; }
        .table th:nth-child(3), .table td:nth-child(3) { width: 12%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 20%; }
        .table th:nth-child(5), .table td:nth-child(5) { width: 16%; }
        .table th:nth-child(6), .table td:nth-child(6) { width: 16%; }

        .contact-cell .btn-contact {
            font-size: 1.2rem;
        }
        /* Colonne Paiement */
        .paiement-cell span {
            font-size: 0.6rem !important;
            display: inline-block;
            word-break: break-all;
        }

        /* Colonne Control */
        .control-cell {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 2px 3px !important;
            padding: 4px 2px !important;
        }
        .control-cell a {
            display: inline-flex !important;
            align-items: center !important;
            gap: 1px !important;
            font-size: 0.5rem !important;
            padding: 1px 2px !important;
            white-space: nowrap !important;
        }
        .control-cell a i {
            font-size: 0.7rem !important;
        }
        .control-cell a .text-info,
        .control-cell a .text-success {
            font-size: 0.45rem !important;
        }
        .control-cell a .zmdi {
            margin: 0 !important;
        }
    }

    @media (max-width: 480px) {
        .table th, .table td {
            font-size: 0.55rem !important;
            padding: 3px 1px !important;
        }
        .table th:nth-child(3), .table td:nth-child(3) { width: 14%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 22%; }
        .table th:nth-child(6), .table td:nth-child(6) { width: 18%; }
        .contact-cell .btn-contact {
            font-size: 1rem;
        }
        .paiement-cell span {
            font-size: 0.5rem !important;
        }
        .control-cell a {
            font-size: 0.45rem !important;
            padding: 1px 1px !important;
        }
        .control-cell a i {
            font-size: 0.6rem !important;
        }
        .control-cell a .text-info,
        .control-cell a .text-success {
            font-size: 0.4rem !important;
        }
    }

    /* ===== STYLE UNIFORMISÉ DES MODALES ===== */
    .modal-custom .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .modal-custom .modal-header {
        border-bottom: 2px solid #e31b23;
        padding: 20px 25px 10px 25px;
        background: #fafafa;
        border-radius: 20px 20px 0 0;
    }
    .modal-custom .modal-header .modal-title {
        font-weight: 700;
        color: #0a192f;
        font-size: 1.2rem;
    }
    .modal-custom .modal-header .close {
        color: #e31b23;
        opacity: 0.8;
        font-size: 1.8rem;
        outline: none;
    }
    .modal-custom .modal-header .close:hover {
        opacity: 1;
    }
    .modal-custom .modal-body {
        padding: 25px;
        background: #ffffff;
        font-size: 1rem;
        color: #333;
    }
    .modal-custom .modal-footer {
        border-top: 1px solid #eee;
        padding: 15px 25px 20px;
        background: #fafafa;
        border-radius: 0 0 20px 20px;
    }
    .modal-custom .modal-footer .btn {
        border-radius: 40px;
        padding: 8px 24px;
        font-weight: 600;
    }
    .modal-custom .modal-footer .btn-secondary {
        background: #64748b;
        border-color: #64748b;
        color: white;
    }
    .modal-custom .modal-footer .btn-secondary:hover {
        background: #475569;
        border-color: #475569;
    }
    .modal-custom .contact-detail {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.1rem;
        margin-bottom: 12px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .modal-custom .contact-detail i {
        width: 30px;
        color: #e31b23;
        font-size: 1.3rem;
    }
    .modal-custom .contact-detail a {
        color: #0a192f;
        text-decoration: none;
        font-weight: 500;
    }
    .modal-custom .contact-detail a:hover {
        color: #e31b23;
        text-decoration: underline;
    }
    .modal-custom .message-success {
        text-align: center;
        padding: 15px;
    }
    .modal-custom .message-success i {
        font-size: 4rem;
        color: #28a745;
        display: block;
        margin-bottom: 15px;
    }
    .modal-custom .message-success h5 {
        font-weight: 700;
        color: #0a192f;
    }
    .modal-custom .message-success p {
        color: #6c757d;
        font-size: 0.95rem;
    }

    @media (max-width: 576px) {
        .modal-custom .modal-body {
            padding: 15px;
        }
        .modal-custom .contact-detail {
            font-size: 0.9rem;
            flex-wrap: wrap;
        }
        .modal-custom .contact-detail i {
            width: 24px;
        }
        .modal-custom .message-success i {
            font-size: 3rem;
        }
    }
</style>

<h4 style="color:rgba(0, 0, 0, 0.6);"><i style="font-size: 40px;" class="zmdi zmdi-accounts text-info"></i> Liste de
    client <span style="display: none;" id="nb_client">{{ $nb_client }}</span>
</h4>

<!-- SECTION FILTRES -->
<div class="filters-container">
    <div class="filter-group">
        <label><i class="zmdi zmdi-label text-danger"></i> Nom du client</label>
        <input type="text" id="filterNom" class="form-control" placeholder="Rechercher par nom...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-email text-danger"></i> Email</label>
        <input type="text" id="filterEmail" class="form-control" placeholder="Rechercher par email...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-phone text-danger"></i> Téléphone</label>
        <input type="text" id="filterPhone" class="form-control" placeholder="Rechercher par téléphone...">
    </div>
    <div class="filter-group">
        <label><i class="zmdi zmdi-money text-danger"></i> Statut paiement</label>
        <select id="filterStatut" class="form-control">
            <option value="all">Tous</option>
            <option value="paid">Payé</option>
            <option value="unpaid">Impayé</option>
        </select>
    </div>
    <!-- Plage de dates -->
    <div class="filter-group">
        <label><i class="zmdi zmdi-calendar text-danger"></i> Période</label>
        <input type="text" id="filterDateRange" class="form-control" placeholder="Sélectionner une période" value="">
    </div>
    <div class="filter-group" style="flex: 0 0 auto;">
        <button id="resetFilters" class="btn btn-secondary btn-sm"
            style="border-radius: 40px; padding: 8px 18px; background: #64748b; color: white; border: none; cursor: pointer; height: 42px;">
            <i class="zmdi zmdi-refresh"></i> Réinitialiser
        </button>
    </div>
</div>

<!-- Badge compteur + totaux USD / CDF (avec conversion) -->
<div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 15px; flex-wrap: wrap;">
    <span class="client-count-badge">
        <i class="zmdi zmdi-view-list"></i> Total clients : <span id="clientCount">0</span>
    </span>
    <span class="client-count-badge" style="background: linear-gradient(135deg, #0f4c5f, #1e6f5c);">
        <i class="zmdi zmdi-money"></i> Total USD : <span id="totalUsdClients">0,00</span> $
    </span>
    <span class="client-count-badge" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
        <i class="zmdi zmdi-money-box"></i> Total CDF : <span id="totalCdfClients">0,00</span> Fc
    </span>
</div>

<?php
// Calcul des totaux globaux (avec conversion)
$total_usd_global = 0;
$total_cdf_global = 0;
foreach ($paiementsfactures as $f) {
    $client = Clients::find($f->client_id);
    if (!$client) continue;
    if (($client->activite_id == $activite_id && $client->user_id == Auth::user()->id) || Auth::user()->role == 0) {
        if ($f->paye == 1) { // seulement les payés
            if ($f->devise == 0) {
                $total_usd_global += $f->paie;
                $total_cdf_global += $f->paie * $f->taux;
            } else {
                $total_usd_global += $f->paie / $f->taux;
                $total_cdf_global += $f->paie;
            }
        }
    }
}
$total_usd_formatted = number_format($total_usd_global, 2, ',', ' ');
$total_cdf_formatted = number_format($total_cdf_global, 2, ',', ' ');
?>

<div id="content_frais" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Contact</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Paiement</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Date de paiement</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody">
                    {{ !($i = 1) }}
                    @foreach ($paiementsfactures as $data)
                        @if (Clients::where('id', $data->client_id)->first()['activite_id'] == $activite_id)
                            @php
                                $client = Clients::where('id', $data->client_id)->first();
                                $email = $client['email'] ?? '';
                                $phone = $client['phone'] ?? '';
                                $nom = $client['name'] ?? '';
                                // Formater le téléphone pour l'affichage
                                $phoneRaw = preg_replace('/\s+/', '', $phone);
                                if (!empty($phoneRaw)) {
                                    if (preg_match('/^\+243/', $phoneRaw)) {
                                        $prefix = '+243';
                                        $rest = substr($phoneRaw, 4);
                                        $formattedPhone = $prefix . ' ' . implode(' ', str_split($rest, 2));
                                    } else {
                                        $formattedPhone = implode(' ', str_split($phoneRaw, 2));
                                    }
                                } else {
                                    $formattedPhone = '';
                                    $phoneRaw = '';
                                }
                            @endphp
                            <tr id="row_{{ $data->id }}" class="client-row"
                                data-paie="{{ $data->paie }}"
                                data-devise="{{ $data->devise }}"
                                data-taux="{{ $data->taux }}"
                                data-nom="{{ $nom }}"
                                data-email="{{ $email }}"
                                data-phone="{{ $phoneRaw }}"
                                data-statut="{{ $data->montant != $data->paie ? 'unpaid' : 'paid' }}"
                                data-date="{{ date('Y-m-d', strtotime($data->created_at)) }}">
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">
                                    <?= $i ?>
                                </td>
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="nom-cell">
                                    {{ $nom }}
                                </td>
                                <!-- Colonne Contact avec bouton pour modal -->
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="contact-cell">
                                    <button class="btn-contact" data-toggle="modal" data-target="#contactModal"
                                        data-nom="{{ $nom }}"
                                        data-email="{{ $email }}"
                                        data-phone="{{ $formattedPhone }}"
                                        data-phone-raw="{{ $phoneRaw }}">
                                        <i class="zmdi zmdi-account"></i>
                                    </button>
                                </td>
                                <!-- Colonne Paiement -->
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"
                                    class="paiement-cell">
                                    @if ($data->montant != $data->paie)
                                        <span class="text-danger" style="font-weight: bold">
                                            @if ($data->devise == 0)
                                                {{ number_format($data->paie, 0, ',', ' ') }}USD /
                                                {{ number_format($data->montant, 0, ',', ' ') }}USD
                                            @else
                                                {{ number_format($data->paie, 0, ',', ' ') }}CDF /
                                                {{ number_format($data->montant, 0, ',', ' ') }}CDF
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-success" style="font-weight: bold">
                                            @if ($data->devise == 0)
                                                {{ number_format($data->paie, 0, ',', ' ') }}USD /
                                                {{ number_format($data->montant, 0, ',', ' ') }}USD
                                            @else
                                                {{ number_format($data->paie, 0, ',', ' ') }}CDF /
                                                {{ number_format($data->montant, 0, ',', ' ') }}CDF
                                            @endif
                                        </span>
                                    @endif
                                </th>
                                <!-- Cellule Date -->
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"
                                    class="date-cell">
                                    <?php
                                    $date = $data->created_at;
                                    $date_1 = explode(' ', $date);
                                    echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                    ?>
                                </th>
                                <!-- Colonne Control -->
                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;" class="control-cell">
                                    @if ($data->montant != $data->paie)
                                        <a id="payer_valider_travailleur<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-close-circle text-danger"></i></a>
                                    @else
                                        <a id="detail_p_t<?= $i ?>" href="#" data-toggle="modal" data-target="#paidModal"
                                           data-nom="{{ $nom }}" data-montant="{{ $data->paie }}" data-devise="{{ $data->devise ? 'CDF' : 'USD' }}">
                                            <i class="zmdi zmdi-check-circle text-success"></i>
                                        </a>
                                    @endif
                                    &nbsp;&nbsp;
                                    <a id="send_e<?= $i ?>" href="#"><i class="zmdi zmdi-email text-info"></i> :
                                        <?= $data->send_e ?></a>
                                    &nbsp;&nbsp;
                                    <a class="text-success" id="send_w<?= $i ?>" href="#"><i
                                            class="zmdi zmdi-whatsapp text-success"></i> : <?= $data->send_w ?></a>
                                    <script>
                                        $("#payer_valider_travailleur<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#data_frais_id").html("<?= $data->id ?>");
                                            $("#devise_paie_id").html("<?= $data->devise ?>");
                                            $.get("{{ url('/get_detail_p_2') }}", {
                                                id: "<?= $data->id ?>",
                                                client_id: "<?= $data->client_id ?>",
                                            }, function(get_detail_p) {
                                                $("#nom_p").html(get_detail_p.split("______________________________")[0]);
                                                $("#role_p").html(get_detail_p.split("______________________________")[1]);
                                                if (get_detail_p.split("______________________________")[2] == 0) {
                                                    $("#devise_p").html("$");
                                                } else {
                                                    $("#devise_p").html("Fc");
                                                }
                                                $("#reste_p").html(get_detail_p.split("______________________________")[3]);
                                                $("#total_p").html(get_detail_p.split("______________________________")[4]);
                                                $("#data_frais_id").html("<?= $data->id ?>");
                                                $("#btn_sup_").trigger("click");
                                            });
                                        });

                                        // Gestion du modal de paiement déjà effectué
                                        $(document).on('show.bs.modal', '#paidModal', function(event) {
                                            var link = $(event.relatedTarget);
                                            var nom = link.data('nom') || 'Client';
                                            var montant = link.data('montant') || 0;
                                            var devise = link.data('devise') || 'USD';
                                            var modal = $(this);
                                            modal.find('#paidModalNom').text(nom);
                                            modal.find('#paidModalMontant').text(
                                                Number(montant).toLocaleString('fr-FR') + ' ' + devise
                                            );
                                        });

                                        $("#send_e<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#send_e<?= $i ?>").html("<i class='zmdi zmdi-refresh zmdi-hc-spin'></i>");
                                            $.get("{{ url('/send_factures_e') }}", {
                                                paiementsfactures_id: "<?= $data->id ?>",
                                                client_id: "<?= $data->client_id ?>",
                                                activite_id: $("#activite_id_f").val(),
                                            }, function(_response) {
                                                var response = _response.split("_____________________")[0]
                                                if (response == 0) {
                                                    $("#send_e<?= $i ?>").html("<i class='zmdi zmdi-close-circle text-danger'></i>");
                                                }
                                                if (response == 1) {
                                                    $("#send_e<?= $i ?>").html("<i class='zmdi zmdi-check-circle text-success'></i>");
                                                }
                                                setTimeout(() => {
                                                    $("#send_e<?= $i ?>").html("<i class='zmdi zmdi-email text-info'></i> : " +
                                                        _response.split("_____________________")[1]);
                                                }, 5000);
                                            });
                                        });

                                        $("#send_w<?= $i ?>").click(function(e) {
                                            e.preventDefault();
                                            $("#send_w<?= $i ?>").html("<i class='zmdi zmdi-refresh zmdi-hc-spin text-success'></i>");
                                            $.get("{{ url('/send_factures_w') }}", {
                                                paiementsfactures_id: "<?= $data->id ?>",
                                                client_id: "<?= $data->client_id ?>",
                                                activite_id: $("#activite_id_f").val(),
                                            }, function(_response) {
                                                var response = _response.split("_____________________")[0]
                                                if (response == 0) {
                                                    $("#send_w<?= $i ?>").html("<i class='zmdi zmdi-close-circle text-danger'></i>");
                                                }
                                                if (response == 1) {
                                                    $("#send_w<?= $i ?>").html("<i class='zmdi zmdi-check-circle text-success'></i>");
                                                }
                                                setTimeout(() => {
                                                    $("#send_w<?= $i ?>").html(
                                                        "<i class='zmdi zmdi-whatsapp text-success'></i> : <span class='text-success'>" +
                                                        _response.split("_____________________")[1] + "</span>");
                                                    const fileName = _response.split("_____________________")[4];
                                                    const baseUrl = _response.split("_____________________")[3];
                                                    const fullUrl = _response.split("_____________________")[4];
                                                    var recipientName = _response.split("_____________________")[3];
                                                    var phoneNumber = _response.split("_____________________")[2];
                                                    const cleanPhone = phoneNumber.replace(/[^0-9]/g, '');
                                                    let message = fileName;
                                                    message += "\n\n" + _response.split("_____________________")[7] + "\n\n";
                                                    if (recipientName) {
                                                        message += `Bonjour Mr/Mme/Ent. ${recipientName},\n\n`;
                                                    } else {
                                                        message += `Bonjour,\n\n`;
                                                    }
                                                    message += `Cordialement.`;
                                                    const encodedMessage = encodeURIComponent(message);
                                                    window.open(`https://wa.me/${cleanPhone}?text=${encodedMessage}`, '_blank');
                                                }, 5000);
                                            });
                                        });
                                    </script>
                                </td>
                            </tr>
                        @endif
                        {{ !$i++ }}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================= -->
<!-- MODAL CONTACT (avec liens cliquables)     -->
<!-- ========================================= -->
<div class="modal fade modal-custom" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel"><i class="zmdi zmdi-account-box text-danger"></i> Coordonnées du client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="contact-detail">
                    <i class="zmdi zmdi-account"></i>
                    <span id="modalContactNom">-</span>
                </div>
                <div class="contact-detail">
                    <i class="zmdi zmdi-email"></i>
                    <a href="#" id="modalContactEmailLink" target="_blank">-</a>
                </div>
                <div class="contact-detail">
                    <i class="zmdi zmdi-phone"></i>
                    <a href="#" id="modalContactPhoneLink">-</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================= -->
<!-- MODAL PAIEMENT DÉJÀ EFFECTUÉ              -->
<!-- ========================================= -->
<div class="modal fade modal-custom" id="paidModal" tabindex="-1" role="dialog" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidModalLabel"><i class="zmdi zmdi-check-circle text-success"></i> Paiement déjà effectué</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="message-success">
                    <i class="zmdi zmdi-check-circle"></i>
                    <h5>Ce paiement a déjà été validé</h5>
                    <p>
                        Le client <strong id="paidModalNom">-</strong> a déjà réglé la somme de
                        <strong id="paidModalMontant">-</strong>.
                    </p>
                    <p class="text-muted" style="font-size:0.9rem;">Aucune action supplémentaire n'est requise.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // =================================================================
        // 1. CHARGEMENT DYNAMIQUE DES DÉPENDANCES DU DATE RANGE PICKER
        // =================================================================
        function loadDateRangePickerDependencies(callback) {
            if (typeof $.fn.daterangepicker === 'function') {
                callback();
                return;
            }

            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css';
            document.head.appendChild(link);

            if (typeof moment === 'undefined') {
                var scriptMoment = document.createElement('script');
                scriptMoment.src = 'https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js';
                scriptMoment.onload = function() {
                    var scriptDRP = document.createElement('script');
                    scriptDRP.src = 'https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js';
                    scriptDRP.onload = function() {
                        callback();
                    };
                    document.head.appendChild(scriptDRP);
                };
                document.head.appendChild(scriptMoment);
            } else {
                var scriptDRP = document.createElement('script');
                scriptDRP.src = 'https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js';
                scriptDRP.onload = function() {
                    callback();
                };
                document.head.appendChild(scriptDRP);
            }
        }

        // =================================================================
        // 2. PERSISTANCE DES FILTRES (sauf la plage de dates)
        // =================================================================
        function saveClientFiltersToStorage() {
            var filters = {
                nom: $('#filterNom').val(),
                email: $('#filterEmail').val(),
                phone: $('#filterPhone').val(),
                statut: $('#filterStatut').val()
            };
            localStorage.setItem('clientFilters', JSON.stringify(filters));
        }

        function loadClientFiltersFromStorage() {
            var savedFilters = localStorage.getItem('clientFilters');
            if (savedFilters) {
                var filters = JSON.parse(savedFilters);
                $('#filterNom').val(filters.nom || '');
                $('#filterEmail').val(filters.email || '');
                $('#filterPhone').val(filters.phone || '');
                $('#filterStatut').val(filters.statut || 'all');
                return true;
            }
            return false;
        }

        // =================================================================
        // 3. FILTRAGE PRINCIPAL (avec prise en compte de la plage de dates)
        // =================================================================
        function filterClients() {
            var filterNom = $('#filterNom').val().toLowerCase().trim();
            var filterEmail = $('#filterEmail').val().toLowerCase().trim();
            var filterPhone = $('#filterPhone').val().toLowerCase().trim();
            var filterStatut = $('#filterStatut').val();
            var dateRange = $('#filterDateRange').val() || '';

            var dateDebut = null,
                dateFin = null;
            if (dateRange) {
                var parts = dateRange.split(' - ');
                if (parts.length === 2) {
                    var parseDMY = function(str) {
                        if (!str) return null;
                        var p = str.split('/');
                        if (p.length === 3) {
                            var day = p[0].padStart(2, '0');
                            var month = p[1].padStart(2, '0');
                            var year = p[2];
                            if (day && month && year && day.length === 2 && month.length === 2 && year.length === 4) {
                                return year + '-' + month + '-' + day;
                            }
                        }
                        return null;
                    };
                    dateDebut = parseDMY(parts[0]);
                    dateFin = parseDMY(parts[1]);
                }
            }

            var visibleCount = 0;
            var newIndex = 1;

            $('#clientsTableBody tr.client-row').each(function() {
                var $row = $(this);
                var showRow = true;

                var nomValue = ($row.data('nom') || '').toLowerCase();
                var emailValue = ($row.data('email') || '').toLowerCase();
                var phoneValue = ($row.data('phone') || '').toLowerCase();
                var statutValue = $row.data('statut') || '';
                var dateValue = $row.data('date') || '';

                if (filterNom && nomValue.indexOf(filterNom) === -1) showRow = false;
                if (showRow && filterEmail && emailValue.indexOf(filterEmail) === -1) showRow = false;
                if (showRow && filterPhone && phoneValue.indexOf(filterPhone) === -1) showRow = false;
                if (showRow && filterStatut !== 'all' && statutValue !== filterStatut) showRow = false;
                if (showRow && dateDebut && dateValue && dateValue < dateDebut) showRow = false;
                if (showRow && dateFin && dateValue && dateValue > dateFin) showRow = false;

                if (showRow) {
                    $row.show();
                    $row.find('.row-num').text(newIndex);
                    newIndex++;
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            $('#clientCount').text(visibleCount);
            updateTotalPaid();

            if (visibleCount === 0 && (filterNom || filterEmail || filterPhone || filterStatut !== 'all' || dateRange)) {
                var $msgDiv = $('#msg');
                if ($msgDiv.length) {
                    $msgDiv.html('<i class="zmdi zmdi-info"></i> Aucun client ne correspond aux critères de recherche');
                    $msgDiv.css('display', 'flex');
                    setTimeout(function() {
                        $msgDiv.html('');
                        $msgDiv.css('display', 'none');
                    }, 3000);
                }
            }
        }

        // =================================================================
        // 4. MISE À JOUR DES TOTAUX (USD et CDF avec conversion)
        // =================================================================
        function updateTotalPaid() {
            var totalUSD = 0;
            var totalCDF = 0;

            $('#clientsTableBody tr.client-row:visible').each(function() {
                var $row = $(this);
                var statutValue = $row.data('statut');
                if (statutValue === 'paid') {
                    var paie = parseFloat($row.data('paie')) || 0;
                    var devise = parseInt($row.data('devise')) || 0;
                    var taux = parseFloat($row.data('taux')) || 1;
                    if (devise === 0) {
                        totalUSD += paie;
                        totalCDF += paie * taux;
                    } else {
                        totalUSD += paie / taux;
                        totalCDF += paie;
                    }
                }
            });

            $('#totalUsdClients').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdfClients').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
        }

        // =================================================================
        // 5. RÉINITIALISATION COMPLÈTE
        // =================================================================
        function resetClientFilters() {
            $('#filterNom').val('');
            $('#filterEmail').val('');
            $('#filterPhone').val('');
            $('#filterStatut').val('all');
            $('#filterDateRange').val('');

            saveClientFiltersToStorage();

            var newIndex = 1;
            $('#clientsTableBody tr.client-row').each(function() {
                $(this).show();
                $(this).find('.row-num').text(newIndex);
                newIndex++;
            });
            var totalCount = $('#clientsTableBody tr.client-row').length;
            $('#clientCount').text(totalCount);
            updateTotalPaid();

            var $msgDiv = $('#msg');
            if ($msgDiv.length) {
                $msgDiv.html('<i class="zmdi zmdi-check-circle"></i> Tous les filtres ont été réinitialisés');
                $msgDiv.css('display', 'flex');
                setTimeout(function() {
                    $msgDiv.html('');
                    $msgDiv.css('display', 'none');
                }, 3000);
            }
        }

        // =================================================================
        // 6. DÉBOUNCE POUR LES CHAMPS TEXTES
        // =================================================================
        var clientFilterTimeout = null;

        function debouncedClientFilter() {
            if (clientFilterTimeout) {
                clearTimeout(clientFilterTimeout);
            }
            clientFilterTimeout = setTimeout(function() {
                filterClients();
                saveClientFiltersToStorage();
            }, 300);
        }

        // =================================================================
        // 7. INITIALISATION DU DATE RANGE PICKER (sans plage par défaut)
        // =================================================================
        function initDateRangePicker() {
            if ($('#filterDateRange').length && typeof $.fn.daterangepicker === 'function') {
                $('#filterDateRange').daterangepicker({
                    autoUpdateInput: false,
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
                        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                            'Septembre', 'Octobre', 'Novembre', 'Décembre'
                        ],
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
                    filterClients();
                });

                $('#filterDateRange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    filterClients();
                });

                // Pas de valeur par défaut
            }
        }

        // =================================================================
        // 8. GESTION DU MODAL CONTACT (avec liens cliquables)
        // =================================================================
        $('#contactModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var nom = button.data('nom') || '-';
            var email = button.data('email') || '-';
            var phoneFormatted = button.data('phone') || '-';
            var phoneRaw = button.data('phone-raw') || '';

            var modal = $(this);
            modal.find('#modalContactNom').text(nom);

            // Lien email
            var emailLink = modal.find('#modalContactEmailLink');
            if (email && email !== '-') {
                emailLink.attr('href', 'mailto:' + email).text(email);
            } else {
                emailLink.removeAttr('href').text('-');
            }

            // Lien téléphone
            var phoneLink = modal.find('#modalContactPhoneLink');
            if (phoneRaw && phoneRaw !== '') {
                phoneLink.attr('href', 'tel:' + phoneRaw).text(phoneFormatted);
            } else {
                phoneLink.removeAttr('href').text('-');
            }
        });

        // =================================================================
        // 9. INITIALISATION GÉNÉRALE
        // =================================================================

        // Initialisation des badges avec les valeurs PHP calculées
        $('#totalUsdClients').text('<?= $total_usd_formatted ?>');
        $('#totalCdfClients').text('<?= $total_cdf_formatted ?>');

        var totalClients = $('#clientsTableBody tr.client-row').length;
        $('#clientCount').text(totalClients);

        var hasSavedFilters = loadClientFiltersFromStorage();

        loadDateRangePickerDependencies(function() {
            initDateRangePicker();
            if (hasSavedFilters) {
                setTimeout(function() {
                    filterClients();
                }, 100);
            } else {
                filterClients();
            }
        });

        $('#filterNom, #filterEmail, #filterPhone, #filterStatut').on('input change', function() {
            debouncedClientFilter();
        });

        $('#filterDateRange').on('change', function() {
            if ($(this).val() === '') {
                filterClients();
            }
        });

        $('#resetFilters').on('click', function(e) {
            e.preventDefault();
            resetClientFilters();
        });

        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url && (settings.url.indexOf('refresh_') !== -1 || settings.url.indexOf(
                    'send_factures') !== -1)) {
                setTimeout(function() {
                    var totalClients = $('#clientsTableBody tr.client-row').length;
                    $('#clientCount').text(totalClients);
                    loadClientFiltersFromStorage();
                    filterClients();
                }, 200);
            }
        });

        window.addEventListener('beforeunload', function() {
            saveClientFiltersToStorage();
        });

    });

    var nom_activite = "<?= $nom_activite ?>";
    if (nom_activite != 0) {
        $("#nom_activite").html("DE L'ACTIVITE " + "<?= $nom_activite ?>");
    }
</script>
