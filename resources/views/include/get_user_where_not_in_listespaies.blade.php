<?php
    use App\Models\Listespaies;
    use App\Models\Paiements;
    use App\Models\Groupes;
?>
<option selected value="">Selectionnez un travailleur</option>
@foreach ($utilisateurs as $data)
    <?php if (Paiements::where(["user_id" => $data->id, "listespaie_id" => $listespaie_id])->get()->count() == 0) { ?>
        <option value="{{ $data->id }}"><?= 'Nom : ' .  $data->name . ', Role : ' . Groupes::where('id', $data->role)->first()["nom"] . ', Numero : ' . $data->phone ?>.</option>
    <?php } ?>
@endforeach

