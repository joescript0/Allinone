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

<!-- Badge compteur + totaux USD / CDF -->
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
$total_3 = 0;
$total_4 = 0;
$total_5 = 0;
foreach ($paiementsfactures as $f) {
    if ((Clients::where('id', $f->client_id)->first()['activite_id'] == $activite_id && Clients::where('id', $f->client_id)->first()['user_id'] == Auth::user()->id) || Auth::user()->role == 0) {
        if ($f->paye == 1) {
            if ($f->devise == 0) {
                $total_3 = $total_3 + $f->paie;
            } else {
                $total_3 = $total_3 + $f->paie / $f->taux;
            }
        }
    }
}
foreach ($paiementsfactures as $f) {
    if ((Clients::where('id', $f->client_id)->first()['activite_id'] == $activite_id && Clients::where('id', $f->client_id)->first()['user_id'] == Auth::user()->id) || Auth::user()->role == 0) {
        if ($f->paye == 0) {
            if ($f->devise == 0) {
                $total_4 = $total_4 + $f->paie;
            } else {
                $total_4 = round($total_4 + $f->paie / $f->taux);
            }
        }
    }
}
?>
<?php $total_5 = $total_3 + $total_4; ?>
<h6 style="text-align: right;font-weight: bold;display:none;"><span> <i class="zmdi zmdi-check-circle text-success"></i> Paiement
        total
        : <span id="nb_total_1"><?= number_format($total_5, 2, ',', ' ') ?></span>$</span></h6>

<div id="content_frais" class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Contact</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Paiment</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Date de paiement</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody">
                    {{ !($i = 1) }}
                    @foreach ($paiementsfactures as $data)
                        @if (Clients::where('id', $data->client_id)->first()['activite_id'] == $activite_id)
                            <tr id="row_{{ $data->id }}" class="client-row"
                                data-paie="{{ $data->paie }}"
                                data-devise="{{ $data->devise }}">
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="row-num">
                                    <?= $i ?>
                                </td>
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="nom-cell"
                                    data-nom="<?= Clients::where('id', $data->client_id)->first()['name'] ?>">
                                    <?= Clients::where('id', $data->client_id)->first()['name'] ?>
                                </td>
                                <td style="padding-top: 5px;padding-bottom: 5px;" class="contact-cell"
                                    data-email="<?= Clients::where('id', $data->client_id)->first()['email'] ?>"
                                    data-phone="<?= Clients::where('id', $data->client_id)->first()['phone'] ?>">
                                    @if (strlen(trim(Clients::where('id', $data->client_id)->first()['email'])) != 0)
                                        <a href="#"><i class="zmdi zmdi-email"></i>
                                            <?= Clients::where('id', $data->client_id)->first()['email'] ?></a>
                                    @endif
                                    @if (strlen(trim(Clients::where('id', $data->client_id)->first()['phone'])) != 0)
                                        <a href="#"><i class="zmdi zmdi-phone"></i>
                                            <?= Clients::where('id', $data->client_id)->first()['phone'] ?></a>
                                    @endif
                                </td>
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"
                                    class="paiement-cell"
                                    data-statut="<?= $data->montant != $data->paie ? 'unpaid' : 'paid' ?>">
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
                                <!-- Cellule Date avec data-date pour le filtrage -->
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"
                                    class="date-cell"
                                    data-date="<?= date('Y-m-d', strtotime($data->created_at)) ?>">
                                    <?php
                                    $date = $data->created_at;
                                    $date_1 = explode(' ', $date);
                                    echo explode('-', $date_1[0])[2] . '/' . explode('-', $date_1[0])[1] . '/' . explode('-', $date_1[0])[0] . ' à ' . $date_1[1];
                                    ?>
                                </th>
                                <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                    @if ($data->montant != $data->paie)
                                        <a id="payer_valider_travailleur<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-close-circle text-danger"></i></a>
                                    @else
                                        <a id="detail_p_t<?= $i ?>" href="#"><i
                                                class="zmdi zmdi-check-circle text-success"></i></a>
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

                var nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                var emailValue = ($row.find('.contact-cell').data('email') || '').toLowerCase();
                var phoneValue = ($row.find('.contact-cell').data('phone') || '').toLowerCase();
                var statutValue = $row.find('.paiement-cell').data('statut') || '';
                var dateValue = $row.find('.date-cell').data('date') || '';

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
        // 4. MISE À JOUR DES TOTAUX (USD et CDF)
        // =================================================================
        function updateTotalPaid() {
            var totalUSD = 0;
            var totalCDF = 0;

            $('#clientsTableBody tr.client-row:visible').each(function() {
                var $row = $(this);
                var statutValue = $row.find('.paiement-cell').data('statut');
                // Ne compter que les paiements "payé"
                if (statutValue === 'paid') {
                    var paie = parseFloat($row.data('paie')) || 0;
                    var devise = parseInt($row.data('devise')) || 0; // 0=USD, 1=CDF
                    if (devise === 0) {
                        totalUSD += paie;
                    } else {
                        totalCDF += paie;
                    }
                }
            });

            // Mise à jour des badges
            $('#totalUsdClients').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
            $('#totalCdfClients').text(totalCDF.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));

            // Mise à jour de l'ancien total (USD uniquement) si vous le souhaitez
            // $('#nb_total_1').text(totalUSD.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' '));
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
        // 7. INITIALISATION DU DATE RANGE PICKER
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
                        'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')],
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

                var today = moment();
                $('#filterDateRange').data('daterangepicker').setStartDate(today);
                $('#filterDateRange').data('daterangepicker').setEndDate(today);
                $('#filterDateRange').val(today.format('DD/MM/YYYY') + ' - ' + today.format('DD/MM/YYYY'));
            }
        }

        // =================================================================
        // 8. INITIALISATION GÉNÉRALE
        // =================================================================

        var totalClients = $('#clientsTableBody tr.client-row').length;
        $('#clientCount').text(totalClients);
        updateTotalPaid();

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
