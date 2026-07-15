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
    <div class="filter-group">
        <button id="resetFilters" class="btn btn-secondary btn-sm"
            style="border-radius: 40px; padding: 8px 18px; background: #64748b; color: white; border: none; cursor: pointer;">
            <i class="zmdi zmdi-refresh"></i> Réinitialiser
        </button>
    </div>
</div>

<!-- Badge compteur -->
<div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
    <span class="client-count-badge">
        <i class="zmdi zmdi-view-list"></i> Total clients : <span id="clientCount">0</span>
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
<h6 style="text-align: right;font-weight: bold;"><span> <i class="zmdi zmdi-check-circle text-success"></i> Paiement
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
                            <tr id="row_{{ $data->id }}" class="client-row">
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
                                <th style="padding-top: 5px;padding-bottom: 5px;text-align: right;"
                                    class="paiement-cell"
                                    data-statut="<?= $data->montant != $data->paie ? 'unpaid' : 'paid' ?>">
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
    // Fonctions de filtrage pour les clients
    (function() {
        // Variables locales pour éviter les conflits globaux
        var clientFilterTimeout = null;

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

        function filterClients() {
            var filterNom = $('#filterNom').val().toLowerCase();
            var filterEmail = $('#filterEmail').val().toLowerCase();
            var filterPhone = $('#filterPhone').val().toLowerCase();
            var filterStatut = $('#filterStatut').val();

            var visibleCount = 0;
            var newIndex = 1;

            $('#clientsTableBody tr.client-row').each(function() {
                var $row = $(this);
                var showRow = true;

                var nomValue = ($row.find('.nom-cell').data('nom') || '').toLowerCase();
                var emailValue = ($row.find('.contact-cell').data('email') || '').toLowerCase();
                var phoneValue = ($row.find('.contact-cell').data('phone') || '').toLowerCase();
                var statutValue = $row.find('.paiement-cell').data('statut') || '';

                if (filterNom && nomValue.indexOf(filterNom) === -1) showRow = false;
                if (showRow && filterEmail && emailValue.indexOf(filterEmail) === -1) showRow = false;
                if (showRow && filterPhone && phoneValue.indexOf(filterPhone) === -1) showRow = false;
                if (showRow && filterStatut !== 'all' && statutValue !== filterStatut) showRow = false;

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

            if (visibleCount === 0 && (filterNom || filterEmail || filterPhone || filterStatut !== 'all')) {
                var $msgDiv = $('#msg');
                if ($msgDiv.length) {
                    $msgDiv.html(
                        '<i class="zmdi zmdi-info"></i> Aucun client ne correspond aux critères de recherche');
                    $msgDiv.css('display', 'flex');
                    setTimeout(function() {
                        $msgDiv.html('');
                        $msgDiv.css('display', 'none');
                    }, 3000);
                }
            }
        }

        function updateTotalPaid() {
            var totalPaid = 0;
            $('#clientsTableBody tr.client-row:visible').each(function() {
                var $row = $(this);
                var statutValue = $row.find('.paiement-cell').data('statut');
                if (statutValue === 'paid') {
                    var paiementText = $row.find('.paiement-cell').text();
                    var match = paiementText.match(/([\d\s,]+)(?:USD|CDF)/);
                    if (match) {
                        var amount = parseFloat(match[1].replace(/\s/g, '').replace(',', '.'));
                        if (!isNaN(amount)) totalPaid += amount;
                    }
                }
            });
            $('#nb_total_1').text(totalPaid.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        function resetClientFilters() {
            $('#filterNom').val('');
            $('#filterEmail').val('');
            $('#filterPhone').val('');
            $('#filterStatut').val('all');

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

        function debouncedClientFilter() {
            if (clientFilterTimeout) {
                clearTimeout(clientFilterTimeout);
            }
            clientFilterTimeout = setTimeout(function() {
                filterClients();
                saveClientFiltersToStorage();
            }, 300);
        }

        // Initialisation quand le document est prêt
        $(document).ready(function() {
            var totalClients = $('#clientsTableBody tr.client-row').length;
            $('#clientCount').text(totalClients);

            var hasSavedFilters = loadClientFiltersFromStorage();

            $('#filterNom, #filterEmail, #filterPhone, #filterStatut').off('input change').on(
                'input change',
                function() {
                    debouncedClientFilter();
                });

            $('#resetFilters').off('click').on('click', function(e) {
                e.preventDefault();
                resetClientFilters();
            });

            if (hasSavedFilters) {
                setTimeout(function() {
                    filterClients();
                }, 100);
            }
        });

        // Mise à jour après chaque chargement AJAX
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

        // Sauvegarder les filtres avant de quitter
        window.addEventListener('beforeunload', function() {
            saveClientFiltersToStorage();
        });
    })();

    var nom_activite = "<?= $nom_activite ?>";
    if (nom_activite != 0) {
        $("#nom_activite").html("DE L'ACTIVITE " + "<?= $nom_activite ?>");
    }
</script>
