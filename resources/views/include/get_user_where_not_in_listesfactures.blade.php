<?php
    use App\Models\Listesfactures;
    use App\Models\Paiementsfactures;
    use App\Models\Groupes;
?>
<option selected value="">Selectionnez un client</option>
<option value="0">Tout les clients</option>
@foreach ($clients as $data)
    <?php if (Paiementsfactures::where(["client_id" => $data->id, "listesfactures_id" => $listesfactures_id])->get()->count() == 0) { ?>
            <option value="{{ $data->id }}"><?= 'Nom : ' . $data->name ?></option>
    <?php } ?>
@endforeach

