<?php

use App\Models\Factureas;
use App\Models\Articles;
use App\Models\Type_frais;
use App\Models\User;

?>
<?php
$t = 0;
foreach ($remboursements as $e)
{
    $t = $t + $e->entree;
}
?>
<h4>Remboursement de {{ $credits["nom_credit"] }} : <strong class="text-info">{{ $t }} / {{ $credits["entree"] }}$</strong></h4>
@if ($remboursements->count() != 0)
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th style="padding-top: 5px;padding-bottom: 5px;">Tranche</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Date de remboursement</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Montant remboursé</th>
            </tr>
        </thead>
        <tbody>
            {{! $i = 1; }}
            @foreach ($remboursements as $data)
            <tr>
                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $i }}</td>
                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_r }}</td>
                <td style="padding-top: 5px;padding-bottom: 5px;">
                    {{ number_format($data->entree, 2, ',', ' ') .'$'; }}
                </td>
            </tr>
            {{! $i++; }}
            @endforeach
        </tbody>
    </table>
</div>
@endif

