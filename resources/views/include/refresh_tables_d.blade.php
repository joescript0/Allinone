<?php
use App\Models\Pointdeventes;
use App\models\affectationstables;
?>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th style="padding-top: 5px;padding-bottom: 5px;">N°</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Nom</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Description</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Point de vente</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Propreté</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $user = Auth::user();
                    $i = 1;
                @endphp
                @foreach ($tables as $data)
                    @php
                        $affecte = false;
                        if ($user->role != 0) {
                            $affecte = affectationstables::where('user_id', $user->id)
                                ->where('table_id', $data->id)
                                ->exists();
                        }
                    @endphp
                    @if ($user->role == 0 || $affecte)
                        <tr id="table_row_{{ $data->id }}" data-propre="{{ $data->propre }}"
                            data-nom="{{ $data->nom }}" data-desc="{{ $data->description }}"
                            data-pointvente="{{ $data->pointdeventes_id }}">
                            <td class="row-num" style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                            <td class="nom-cell" data-nom="{{ $data->nom }}"
                                style="padding-top: 5px;padding-bottom: 5px;">{{ $data->nom }}</td>
                            <td class="desc-cell" data-desc="{{ $data->description }}"
                                style="padding-top: 5px;padding-bottom: 5px;">{{ $data->description }}</td>
                            <td class="pointvente-cell" data-pointvente-id="{{ $data->pointdeventes_id }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                {{ $data->pointdeventes_id != null ? \App\Models\Pointdeventes::where('id', $data->pointdeventes_id)->first()->nom : 'Aucun point de vente' }}
                            </td>
                            <td class="propre-cell" data-propre="{{ $data->propre }}"
                                style="padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->propre == 1)
                                    <span class="status-badge sale">
                                        <i class="zmdi zmdi-close-circle"></i> Sale
                                    </span>
                                @else
                                    <span class="status-badge propre">
                                        <i class="zmdi zmdi-check-circle"></i> Propre
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;padding-top: 5px;padding-bottom: 5px;">
                                @if ($data->propre == 1)
                                    <a href="#" class="clean-link" data-table-id="{{ $data->id }}"
                                        data-propre="1">
                                        <i class="zmdi zmdi-close-circle"></i>
                                    </a>
                                @else
                                    <a href="#" class="clean-link disabled-link"
                                        data-table-id="{{ $data->id }}" data-propre="0">
                                        <i class="zmdi zmdi-check-circle"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @php $i++; @endphp
                    @endif
                @endforeach
                <tr id="noResultRow" style="display: none;">
                    <td colspan="6">
                        <i class="zmdi zmdi-info-outline"></i> Aucune table ne correspond à vos critères.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
