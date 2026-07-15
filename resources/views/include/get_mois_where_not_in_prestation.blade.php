<?php
    use App\Models\Listesfactures;
    use App\Models\Paiementsfactures;
    use App\Models\Groupes;
    use App\Models\Prestations;
?>
<option selected value="">Selectionnez un mois</option>
<?php if ($annee_id != 0) { ?>
    @foreach ($mois as $data)
        <?php if (Prestations::where(["moi_id" => $data->id, "annee_id" => $annee_id])->get()->count() == 0) { ?>
            <option value="{{ $data->id }}"><?= $data->nom ?></option>
        <?php } ?>
    @endforeach
<?php } ?>