@php
    use App\Models\Groupes;
    use App\Models\Writes;
    use App\Models\User;
    use App\Models\Tables;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Activites;

    $isLoggedIn = Auth::check();
    $userId = $isLoggedIn ? Auth::user()->id : null;
    $userRole = $isLoggedIn ? Auth::user()->role : null;

    // On ne calcule les droits que si l'utilisateur est connecté
    if ($isLoggedIn) {
        $hasWrite = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->count() != 0;
        if ($hasWrite) {
            $writeData = Writes::where(["ressource_id" => $ressource_id_1, "groupe_id" => $groupe_user_id])->first();
            $edit = $writeData->edit;
            $delete = $writeData->delete;
        } else {
            $edit = 0;
            $delete = 0;
        }
    }
@endphp

<div class="col-12">
<div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Téléphone</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Présence</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Table</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Relation</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Relation Autre</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Statut</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Dans la salle</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Control</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $canEdit = false;
                    $canDelete = false;
                    $isAdmin = false;
                
                    if (Auth::check()) {
                        $writes = Writes::where([
                            'ressource_id' => $ressource_id_1 ?? 0,
                            'groupe_id'    => $groupe_user_id ?? 0
                        ])->get();
                
                        if ($writes->count() > 0) {
                            $canEdit   = (bool) $writes[0]->edit;
                            $canDelete = (bool) $writes[0]->delete;
                        }
                
                        if (Auth::user()->role == 0) {
                            $canEdit = true;
                            $canDelete = true;
                            $isAdmin = true;
                        }
                    }
                @endphp
                
                @foreach ($listesdesinvites as $data)
                    @php $i = $loop->iteration; @endphp
                    <tr>
                        <td style="padding-top:5px;padding-bottom:5px;" class="row-num">{{ $i }}</td>
                        <td style="padding-top:5px;padding-bottom:5px;" class="nom-cell" data-nom="{{ $data->name }}">{{ $data->name }}</td>
                        <td style="padding-top:5px;padding-bottom:5px;" class="phone-cell" data-phone="{{ $data->phone }}">{{ $data->phone }}</td>
                
                        <td style="padding-top:5px;padding-bottom:5px;" class="presence-cell" data-presence="{{ $data->presence ?? '' }}">
                            @if($data->presence == 'oui')
                                <span class="badge badge-success" style="background:#10b981; color:white; padding:4px 10px; border-radius:20px;">Oui</span>
                            @elseif($data->presence == 'non')
                                <span class="badge badge-danger" style="background:#ef4444; color:white; padding:4px 10px; border-radius:20px;">Non</span>
                            @else
                                <span class="badge badge-secondary" style="background:#6c757d; color:white; padding:4px 10px; border-radius:20px;">-</span>
                            @endif
                        </td>
                
                        <td style="padding-top:5px;padding-bottom:5px;" class="table-cell">
                            @php
                                $nomTable = 'Aucune';
                                if ($data->table_id != 0) {
                                    $table = Tables::find($data->table_id);
                                    $nomTable = $table->nom ?? 'Aucune';
                                }
                            @endphp
                            {{ $nomTable }}
                        </td>
                
                        <td style="padding-top:5px;padding-bottom:5px;" class="relation-cell" data-relation="{{ $data->relation ?? '' }}">{{ $data->relation ?: $data->relation_autre }}</td>
                        <td style="padding-top:5px;padding-bottom:5px;" class="relation_autre-cell" data-relation_autre="{{ $data->relation_autre ?? '' }}">{{ $data->relation_autre ?? '' }}</td>
                
                        <td style="padding-top:5px;padding-bottom:5px;" class="statut-cell" data-reponse="{{ $data->reponse ?? 1 }}">
                            @php
                                $reponse = $data->reponse ?? 1;
                                $badgeColor = '#6c757d';
                                $label = 'En attente';
                                if ($reponse == 1) {
                                    $badgeColor = '#f59e0b';
                                    $label = 'En attente';
                                } elseif ($reponse == 2) {
                                    $badgeColor = '#10b981';
                                    $label = 'Confirmé';
                                } elseif ($reponse == 3) {
                                    $badgeColor = '#ef4444';
                                    $label = 'Refusé';
                                }
                            @endphp
                            <span class="badge" style="background:{{ $badgeColor }}; color:white; padding:4px 10px; border-radius:20px;">{{ $label }}</span>
                        </td>
                
                        <td style="padding-top:5px;padding-bottom:5px;" class="salle-cell" data-salle="{{ $data->dans_la_salle ?? 0 }}">
                            @if(($data->dans_la_salle ?? 0) == 1)
                                <span class="badge badge-success" style="background:#10b981; color:white; padding:4px 10px; border-radius:20px;">Oui</span>
                            @else
                                <span class="badge badge-danger" style="background:#ef4444; color:white; padding:4px 10px; border-radius:20px;">Non</span>
                            @endif
                        </td>
                
                        <td style="text-align:center; padding-top:5px; padding-bottom:5px;">
                            @auth
                                @if ($canEdit)
                                    <a id="edit_{{ $i }}" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                @else
                                    <a id="edit_r{{ $i }}" href="#"><i class="zmdi zmdi-edit text-success"></i></a> &nbsp;
                                @endif
                
                                @if ($canDelete)
                                    <a id="delete_{{ $i }}" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                @else
                                    <a id="delete_r{{ $i }}" href="#"><i class="zmdi zmdi-delete text-danger"></i></a>
                                @endif
                                &nbsp;
                
                                @php
                                    $canConfirm = ($reponse != 2 && $reponse != 3);
                                @endphp
                                <a id="confirm_{{ $i }}" href="#"
                                   data-id="{{ $data->id }}"
                                   data-reponse="{{ $reponse }}"
                                   class="confirm-btn {{ $canConfirm ? '' : 'disabled-link' }}"
                                   style="{{ $canConfirm ? '' : 'pointer-events:none; opacity:0.5;' }}"
                                   title="{{ $canConfirm ? 'Confirmer / Refuser' : 'Déjà traité' }}">
                                    <i class="zmdi zmdi-check-circle"></i>
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endauth
                        </td>
                    </tr>
                
                    @auth
                        <script>
                            (function() {
                                @if ($canEdit)
                                    $("#edit_{{ $i }}").click(function(e) {
                                        e.preventDefault();
                                        $.get("{{ url('/refresh_editinvite') }}", {
                                            listesdesinvites_id: {{ $data->id }}
                                        }, function(refresh_editutilisateur) {
                                            $("#bloc_1").hide();
                                            $("#bloc_3").show();
                                            $("#bloc_3").html(refresh_editutilisateur);
                                            $("#bloc_4").hide();
                                        });
                                    });
                                @else
                                    $("#edit_r{{ $i }}").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                @endif
                
                                @if ($canDelete)
                                    $("#delete_{{ $i }}").click(function(e) {
                                        e.preventDefault();
                                        $("#element").html("{{ $data->name }}");
                                        $("#data_id").html("{{ $data->id }}");
                                        $("#btn_sup").trigger("click");
                                    });
                                @else
                                    $("#delete_r{{ $i }}").click(function(e) {
                                        e.preventDefault();
                                        $("#btn_refus").trigger("click");
                                    });
                                @endif
                
                                $("#confirm_{{ $i }}").click(function(e) {
                                    e.preventDefault();
                                    var reponse = {{ $reponse }};
                                    if (reponse == 2 || reponse == 3) {
                                        showMsg('info', '<i class="zmdi zmdi-info"></i> Cette invitation a déjà été traitée.', 4000);
                                        return;
                                    }
                                    $("#confirm_invite_id").val({{ $data->id }});
                                    $("#confirmationModal").modal('show');
                                });
                            })();
                        </script>
                    @endauth
                @endforeach
            </tbody>
        </table>
    </div>
</div>