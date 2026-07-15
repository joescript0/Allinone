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
@if ($remboursements_2->count() != 0)
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th style="padding-top: 5px;padding-bottom: 5px;">Date de crédit</th>
                <th style="padding-top: 5px;padding-bottom: 5px;">Montant credité</th>
            </tr>
        </thead>
        <tbody>
            {{! $i = 1; }}
            @foreach ($remboursements_2 as $data)
            <tr>
                <td style="padding-top: 5px;padding-bottom: 5px;">{{ $data->date_credit }}</td>
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

