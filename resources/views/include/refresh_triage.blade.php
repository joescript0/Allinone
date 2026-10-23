<?php

use App\Models\Writes;
use App\Models\Postes;
use App\Models\Mois;
use App\Models\Groupes;
use App\Models\Clients;
use App\Models\Lieux;
use App\Models\Patients;
use Illuminate\Support\Facades\Auth;

?>
<div class="col-12">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Code</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Téléphone</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Nature</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
            </tr>
        </thead>
        <tbody>
            {{! $i = 1; }}
            @foreach ($patients as $data)
                @php
                    $natureLabel = (($data->type ?? null) === 0) ? 'Privé' : 'Entreprise';
                @endphp
                <tr id="row_{{ $data->id }}"
                    data-patient-id="{{ $data->id }}"
                    data-patient-code="{{ $data->code ?? '' }}"
                    data-patient-name="{{ $data->name ?? '' }}"
                    data-patient-phone="{{ $data->phone ?? '' }}"
                    data-patient-type="{{ $data->type ?? 0 }}"
                    data-patient-image="{{ !empty($data->image) ? asset($data->image) : '' }}"
                    data-patient-genre="{{ $data->genre ?? 0 }}"
                    data-patient-email="{{ $data->email ?? '' }}"
                    data-patient-date="{{ $data->date_de_naissance ?? '' }}"
                    data-patient-address="{{ $data->adresse ?? '' }}">

                    <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>

                    <td class="code-cell" data-code="{{ $data->code ?? '' }}" style="padding-top: 5px;padding-bottom: 5px;">
                        @if(!empty($data->code))
                            <span class="code-badge"><i class="zmdi zmdi-label"></i> {{ $data->code }}</span>
                        @else
                            <span style="color:#94a3b8;font-style:italic;">—</span>
                        @endif
                    </td>

                    <td class="align-middle nom-cell" data-nom="{{ $data->name }}" style="padding-top: 5px;padding-bottom: 5px;">
                        @if(!empty($data->image))
                            <a id="voir_profil_<?= $i ?>" href="#" data-img="{{ asset($data->image) }}" data-name="{{ $data->name }}" title="Agrandir la photo">
                                <img src="{{ asset($data->image) }}" alt="avatar" class="profile-thumb">
                            </a>
                        @else
                            <a id="voir_profil_<?= $i ?>" href="#" style="display:none;"></a>
                        @endif
                        {{ $data->name }}
                    </td>

                    <td class="phone-cell-display" data-phone="{{ $data->phone }}" style="padding-top: 5px;padding-bottom: 5px;">
                        @if(!empty($data->phone))
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $data->phone) }}"
                                class="phone-call-link" title="Appeler {{ $data->phone }}">
                                <i class="zmdi zmdi-phone-in-talk"></i>
                                <span class="phone-num">{{ $data->phone }}</span>
                            </a>
                        @else
                            <span style="color:#94a3b8;font-style:italic;">Non renseigné</span>
                        @endif
                    </td>

                    <td class="nature-cell" data-nature="{{ $data->type ?? '' }}" style="padding-top: 5px;padding-bottom: 5px;">
                        @if(($data->type ?? null) === 0)
                            <span class="badge-nature badge-prive"><i class="zmdi zmdi-account"></i> Privé</span>
                        @else
                            <span class="badge-nature badge-entreprise"><i class="zmdi zmdi-city-alt"></i> Entreprise</span>
                        @endif
                    </td>

                    <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                        <?php if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0) || (Auth::user()->role == 0)) { ?>
                            <?php
                            $edit = 0;
                            $delete = 0;
                            if ((Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()->count() != 0)) {
                                $edit = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->edit;
                                $delete = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->get()[0]->delete;
                            }
                            ?>
                        <?php } ?>

                        <?php if ((($edit == 1) && (($data->user_id ?? null) == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                            <a id="edit_<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit"></i></a>
                        <?php } else { ?>
                            <a id="edit_r<?= $i ?>" href="#" title="Modifier"><i class="zmdi zmdi-edit"></i></a>
                        <?php } ?>

                        <a class="btn-fiche" href="#" data-fiche-id="<?= $data->id ?>" title="Voir la fiche du patient">
                            <i class="zmdi zmdi-file-text"></i>
                        </a>

                        <?php if (($delete == 1 && (($data->user_id ?? null) == Auth::user()->id)) || (Auth::user()->role == 0)) { ?>
                            <a id="delete_<?= $i ?>" href="#" title="Supprimer"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <?php } else { ?>
                            <a id="delete_r<?= $i ?>" href="#" title="Supprimer"><i class="zmdi zmdi-delete text-danger"></i></a>
                        <?php } ?>

                        <script>
                            $("#edit_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $.get("{{ url('/refresh_edittriage') }}", {
                                    user_id: <?= $data->id ?>,
                                    page: <?= $ressource_id_1 ?>,
                                }, function(refresh_edittriage) {
                                    $("#bloc_1").hide();
                                    $("#bloc_2").hide();
                                    $("#bloc_3").show();
                                    $("#bloc_3").html(refresh_edittriage);
                                });
                            });
                            $("#edit_r<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#btn_refus").trigger("click");
                            });
                            $("#delete_r<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#btn_refus").trigger("click");
                            });

                            $("#delete_<?= $i ?>").click(function(e) {
                                e.preventDefault();
                                $("#data_id").html("<?= $data->id ?>");
                                $("#sup_code").html(<?= json_encode(!empty($data->code) ? '<span class="code-badge"><i class="zmdi zmdi-label"></i> ' . $data->code . '</span>' : '<span class="empty">Non renseigné</span>') ?>);

                                var nom = <?= json_encode($data->name ?: 'Non renseigné') ?>;
                                var genre = <?= json_encode($data->genre) ?>;
                                if(genre == 0) { $("#sup_genre").html('<span style="color:#94a3b8;font-style:italic;">Non précisé</span>'); }
                                else if(genre == 1) { $("#sup_genre").html('<span>Homme</span>'); }
                                else if(genre == 2) { $("#sup_genre").html('<span>Femme</span>'); }

                                $("#sup_nom").text(nom);

                                var phone = <?= json_encode($data->phone ?: '') ?>;
                                if (phone && phone.trim() !== '') {
                                    $("#sup_phone").html('<a href="tel:' + phone.replace(/[^\d+]/g, '') + '" class="phone-call-link" style="padding:3px 10px;font-size:0.75rem;"><i class="zmdi zmdi-phone-in-talk"></i> ' + phone + '</a>');
                                } else {
                                    $("#sup_phone").html('<span style="color:#94a3b8;font-style:italic;">Non renseigné</span>');
                                }

                                var adresse = <?= json_encode($data->adresse ?? '') ?>;
                                if (adresse && adresse.trim() !== '') {
                                    $("#sup_adresse").text(adresse);
                                } else {
                                    $("#sup_adresse").html('<span style="color:#94a3b8;font-style:italic;">Non renseigné</span>');
                                }

                                var nature = <?= json_encode($natureLabel) ?>;
                                if (nature === 'Privé') {
                                    $("#sup_nature").html('<span class="badge-nature badge-prive"><i class="zmdi zmdi-account"></i> Privé</span>');
                                } else {
                                    $("#sup_nature").html('<span class="badge-nature badge-entreprise"><i class="zmdi zmdi-city-alt"></i> Entreprise</span>');
                                }

                                $("#btn_sup").trigger("click");
                            });
                        </script>
                    </td>
                </tr>
            {{! $i++; }}
            @endforeach
        </tbody>
    </table>
</div>
