<div class="prestations-calendar">
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

    <?php
        $prestations = json_decode($details, true);
        $date_date = [];
        foreach ($prestations as $ligne) {
            $date = $ligne['date'];
            if (!in_array($date, $date_date)) {
                $date_date[] = $date;
            }
        }
    ?>

    <!-- Filtres multiples : Date, Utilisateur, Groupe -->
    <div class="filters-container">
        <div class="filter-group">
            <label><i class="zmdi zmdi-calendar"></i> Date</label>
            <select id="filterDate">
                <option value="all">📅 Toutes les dates</option>
                @foreach ($date_date as $date)
                    <?php $dateFormatee = explode("-", $date)[2] . '/' . explode("-", $date)[1] . '/' . explode("-", $date)[0]; ?>
                    <option value="{{ $date }}">{{ $dateFormatee }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label><i class="zmdi zmdi-account"></i> Utilisateur</label>
            <select id="filterUser">
                <option value="all">👥 Tous</option>
                <?php
                    $usersIds = array_unique(array_column($prestations, 'user_id'));
                    foreach ($usersIds as $uid) {
                        $user = User::find($uid);
                        if ($user) {
                            echo "<option value='{$uid}'>" . htmlspecialchars($user->name) . " (" . htmlspecialchars($user->matricule ?? '') . ")</option>";
                        }
                    }
                ?>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="zmdi zmdi-group"></i> Fonction (Groupe)</label>
            <select id="filterGroupe">
                <option value="all">👥 Tous les groupes</option>
                <?php
                    $groupes = Groupes::all();
                    foreach ($groupes as $groupe) {
                        echo "<option value='{$groupe->id}'>" . htmlspecialchars($groupe->nom) . "</option>";
                    }
                ?>
            </select>
        </div>

        <button class="reset-filters" id="resetAllFilters"><i class="zmdi zmdi-refresh"></i> Réinitialiser</button>
        <button class="reset-filters" id="exportPDF" style="background: #e31b23;display:none;"><i class="zmdi zmdi-print"></i> Exporter PDF</button>
    </div>

    <?php foreach ($date_date as $cle => $valeur) {?>
        <div class="date-block" data-date="<?= $valeur ?>">
            <?php if(explode("-", $valeur)[2] . '/' . explode("-", $valeur)[1] . '/' . explode("-", $valeur)[0] == date("d/m/Y")){ ?>
                <div class="col-12">
                    <h4 class="text-success" style="color:rgb(0, 0, 0);"><i style="font-size: 40px;" class="zmdi zmdi-calendar text-success"></i> DATE : <?= explode("-", $valeur)[2] . '/' . explode("-", $valeur)[1] . '/' . explode("-", $valeur)[0] ?></h4>
                </div>
            <?php }else{ ?>
                <div class="col-12">
                    <h4 style="color:rgb(0, 0, 0);"><i style="font-size: 40px;" class="zmdi zmdi-calendar text-success"></i> DATE : <?= explode("-", $valeur)[2] . '/' . explode("-", $valeur)[1] . '/' . explode("-", $valeur)[0] ?></h4>
                </div>
            <?php } ?>
            <div class="col-12" style="padding-bottom:60px;margin-top:-20px;">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Matricule</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Fonction / Groupe</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Prestation</th>
                                <th style="padding-top: 5px;padding-bottom: 5px;">Horaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($prestations as $data)
                                @if ($valeur == $data['date'])
                                    <?php $users = User::where(['id' => $data['user_id']])->first(); ?>
                                    <?php
                                        $groupeId = $users->role ?? null;
                                    ?>
                                    <tr data-user-id="<?= $data['user_id'] ?>" data-groupe-id="<?= $groupeId ?>">
                                        <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ $users->matricule ?? '' }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ $users->name ?? '' }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            {{ Groupes::where(["id" => $users->role])->first()["nom"] ?? '' }}
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            @if ($data['service'] == "journée")
                                                <i class="zmdi zmdi-info text-success"></i> <span class="text-success">{{ $data['service'] }} </span>
                                            @endif
                                            @if ($data['service'] == "nuit")
                                                <i class="zmdi zmdi-info text-info"></i> <span class="text-info">{{ $data['service'] }} </span>
                                            @endif
                                            @if ($data['service'] == "repos")
                                                <i class="zmdi zmdi-info text-danger"></i> <span class="text-danger">{{ $data['service'] }} </span>
                                            @endif
                                        </td>
                                        <td style="padding-top: 5px;padding-bottom: 5px;">
                                            @if ($data['horaire'] == "06h00 - 18h00")
                                                <i class="zmdi zmdi-time text-success"></i> <span class="text-success">{{  ucfirst($data['horaire']) }} </span>
                                            @endif
                                            @if ($data['horaire'] == "18h00 - 06h00")
                                                <i class="zmdi zmdi-time text-info"></i> <span class="text-info">{{ ucfirst($data['horaire']) }} </span>
                                            @endif
                                            @if ($data['horaire'] == "repos")
                                                <i class="zmdi zmdi-time text-danger"></i> <span class="text-danger">{{ ucfirst($data['horaire']) }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php  } ?>
</div>

<script>
    const filterDate = document.getElementById('filterDate');
    const filterUser = document.getElementById('filterUser');
    const filterGroupe = document.getElementById('filterGroupe');
    const resetBtn = document.getElementById('resetAllFilters');
    const exportBtn = document.getElementById('exportPDF');
    const dateBlocks = document.querySelectorAll('.date-block');

    function applyFilters() {
        const selectedDate = filterDate.value;
        const selectedUser = filterUser.value;
        const selectedGroupe = filterGroupe.value;

        let anyVisibleRow = false;

        dateBlocks.forEach(block => {
            const blockDate = block.getAttribute('data-date');
            let blockHasVisibleRows = false;

            const rows = block.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const rowUserId = row.getAttribute('data-user-id');
                const rowGroupeId = row.getAttribute('data-groupe-id');

                const matchUser = (selectedUser === 'all' || rowUserId === selectedUser);
                const matchGroupe = (selectedGroupe === 'all' || (rowGroupeId && rowGroupeId === selectedGroupe));

                if (matchUser && matchGroupe) {
                    row.style.display = '';
                    blockHasVisibleRows = true;
                    anyVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const dateMatch = (selectedDate === 'all' || blockDate === selectedDate);
            if (dateMatch && blockHasVisibleRows) {
                block.style.display = '';
            } else {
                block.style.display = 'none';
            }
        });

        let noResultsMsg = document.querySelector('.prestations-calendar .no-results');
        if (!anyVisibleRow && dateBlocks.length > 0) {
            if (!noResultsMsg) {
                const msg = document.createElement('div');
                msg.className = 'no-results';
                msg.innerHTML = '<i class="zmdi zmdi-info-outline"></i> Aucune prestation ne correspond aux filtres sélectionnés.';
                document.querySelector('.prestations-calendar').appendChild(msg);
            }
        } else {
            if (noResultsMsg) noResultsMsg.remove();
        }
    }

    filterDate.addEventListener('change', applyFilters);
    filterUser.addEventListener('change', applyFilters);
    filterGroupe.addEventListener('change', applyFilters);
    resetBtn.addEventListener('click', () => {
        filterDate.value = 'all';
        filterUser.value = 'all';
        filterGroupe.value = 'all';
        applyFilters();
    });

    applyFilters();

    // Export PDF : on envoie les données brutes (toutes les prestations)
    exportBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log("Print");
    });
</script>
