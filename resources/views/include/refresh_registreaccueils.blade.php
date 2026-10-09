<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Type</th>
                    <th>Nature</th>
                    <th>Motif</th>
                    <th>Service</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Note</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
                {{! $i = 1; }}
                @foreach ($registreaccueils as $data)
                    @php
                        $personneRec = \App\Models\Personnes::find($data->personne_id);
                        $typeNumerique = $personneRec->type ?? null;

                        $typeLabels = [
                            0 => 'Utilisateur',
                            1 => 'Client',
                            2 => 'Patient',
                            3 => 'Visiteur',
                        ];
                        $typeLabel = $typeLabels[$typeNumerique] ?? '—';

                        $nom = ''; $email = ''; $phone = ''; $image = ''; $nature = null;

                        if ($personneRec && $personneRec->user_id) {
                            $p = null;
                            switch ($typeNumerique) {
                                case 0: $p = \App\Models\User::find($personneRec->user_id); break;
                                case 1: $p = \App\Models\Clients::find($personneRec->user_id); break;
                                case 2: $p = \App\Models\Patients::find($personneRec->user_id); break;
                                case 3: $p = \App\Models\Visiteurs::find($personneRec->user_id); break;
                            }
                            if ($p) {
                                $nom    = $p->name  ?? '';
                                $email  = $p->email ?? '';
                                $phone  = $p->phone ?? '';
                                $image  = $p->image ?? '';
                                $nature = $p->type  ?? null;
                            }
                        }

                        if (!empty($data->motif_id) && $data->motif_id != 0) {
                            $motifNom = optional(\App\Models\Motifs::find($data->motif_id))->nom ?? '';
                            if ($motifNom === '') $motifNom = 'Aucun motif';
                        } else { $motifNom = 'Aucun motif'; }

                        if (!empty($data->service_id) && $data->service_id != 0) {
                            $serviceNom = optional(\App\Models\Services::find($data->service_id))->nom ?? '';
                            if ($serviceNom === '') $serviceNom = 'Aucun service';
                        } else { $serviceNom = 'Aucun service'; }

                        $natureLabels = [0 => 'Privé', 1 => 'Entreprise'];
                        $natureLabel = ($nature !== null && isset($natureLabels[$nature])) ? $natureLabels[$nature] : 'Entreprise';

                        $heureFormatee = '';
                        if (!empty($data->heure_entree) && $data->heure_entree !== '0000/00/00 00:00' && $data->heure_entree !== '0000-00-00 00:00:00') {
                            $dt = \Carbon\Carbon::parse($data->heure_entree);
                            $heureFormatee = $dt->format('d/m/Y') . ' à ' . $dt->format('H:i');
                        }

                        $heureSortieFormatee = '';
                        $aSorti = false;
                        if (!empty($data->heure_sortie)
                            && $data->heure_sortie !== '0000/00/00 00:00'
                            && $data->heure_sortie !== '0000-00-00 00:00:00') {
                            $dtS = \Carbon\Carbon::parse($data->heure_sortie);
                            $heureSortieFormatee = $dtS->format('d/m/Y') . ' à ' . $dtS->format('H:i');
                            $aSorti = true;
                        }

                        $signatureUrl = !empty($data->signature)
                            ? asset('storage/images/fichiers/' . $data->signature)
                            : '';
                    @endphp

                    <tr id="row_{{ $data->id }}"
                        data-user-id="{{ $data->user_id ?? '' }}"
                        data-nom="{{ $nom }}"
                        data-email="{{ $email }}"
                        data-phone="{{ $phone }}"
                        data-sortie-ok="{{ $aSorti ? '1' : '0' }}">

                        <td class="row-num">{{ $i }}</td>

                        <td class="nom-cell align-middle">
                            @if(!empty($image))
                                <a id="voir_profil_<?= $i ?>" href="#">
                                    <img src="{{ asset($image) }}" alt="avatar" class="profile-thumb">
                                </a>
                            @endif
                            {{ $nom }}
                        </td>

                        <td class="phone-cell">{{ $phone }}</td>
                        <td>{{ $typeLabel }}</td>
                        <td>{{ $natureLabel }}</td>

                        <td>
                            @if($motifNom === 'Aucun motif')
                                <span style="color:#94a3b8;font-style:italic;">{{ $motifNom }}</span>
                            @else
                                {{ $motifNom }}
                            @endif
                        </td>

                        <td>
                            @if($serviceNom === 'Aucun service')
                                <span style="color:#94a3b8;font-style:italic;">{{ $serviceNom }}</span>
                            @else
                                {{ $serviceNom }}
                            @endif
                        </td>

                        <td>{{ $heureFormatee }}</td>

                        <td class="sortie-cell">
                            @if($aSorti)
                                <span class="sortie-ok">{{ $heureSortieFormatee }}</span>
                            @else
                                <span class="sortie-manquante" title="La sortie n'a pas encore été mentionnée">
                                    <i class="zmdi zmdi-alert-circle"></i>
                                    Non renseignée
                                </span>
                            @endif
                        </td>

                        <td>{{ $data->note ?? '' }}</td>

                        <td style="text-align: center; white-space: nowrap;">
                            <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                                <?php
                                $delete = 0;
                                if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                    $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                                }
                                ?>
                            <?php } ?>

                            {{-- 👁️ DÉTAILS --}}
                            <a href="#"
                                class="btn-details"
                                data-id="{{ $data->id }}"
                                data-nom="{{ $nom }}"
                                data-email="{{ $email }}"
                                data-phone="{{ $phone }}"
                                data-image="{{ !empty($image) ? asset($image) : '' }}"
                                data-type="{{ $typeLabel }}"
                                data-nature="{{ $natureLabel }}"
                                data-motif="{{ $motifNom }}"
                                data-service="{{ $serviceNom }}"
                                data-entree="{{ $heureFormatee }}"
                                data-sortie="{{ $heureSortieFormatee }}"
                                data-a-sorti="{{ $aSorti ? '1' : '0' }}"
                                data-note="{{ $data->note ?? '' }}"
                                data-signature="{{ $signatureUrl }}"
                                title="Voir les détails">
                                <i class="zmdi zmdi-eye"></i>
                            </a>

                            {{-- 🗑️ DELETE --}}
                            <?php if (($delete == 1 && $data->user_id == Auth::user()->id) || (Auth::user()->role == 0)) { ?>
                                <a id="delete_<?= $i ?>" href="#" title="Supprimer">
                                    <i class="zmdi zmdi-delete text-danger"></i>
                                </a>
                            <?php } else { ?>
                                <a id="delete_r<?= $i ?>" href="#" title="Supprimer">
                                    <i class="zmdi zmdi-delete text-danger"></i>
                                </a>
                            <?php } ?>

                            <script>
                                $("#delete_r<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#btn_refus").trigger("click");
                                });
                                $("#delete_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#sup_nom").text({!! json_encode($nom ?: 'Non renseigné') !!});
                                    $("#sup_phone").text({!! json_encode($phone ?: 'Non renseigné') !!});
                                    $("#data_id").html("<?= $data->id ?>");
                                    $("#btn_sup").trigger("click");
                                });
                                @if(!empty($image))
                                $("#voir_profil_<?= $i ?>").click(function(e) {
                                    e.preventDefault();
                                    $("#nom_profil").html("<?= $nom ?>");
                                    $("#data_id").html("<?= $data->id ?>");
                                    var url = "<?= asset($image) ?>";
                                    $("#contenu_voir_profil").html('<img src="' + url + '" class="img-fluid" style="max-height:100%;width: 100%;" />');
                                    $("#btn_voir_profil").trigger("click");
                                });
                                @endif
                            </script>
                        </td>
                    </tr>
                {{! $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
