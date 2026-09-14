<?php
    use App\Models\facturesnormalisees;
?>
<option selected value="">Selectionnez un mois</option>
<?php foreach ($mois as $m) { ?>
    <?php if (facturesnormalisees::where(["annee_id" => $annee_id, "moi_id" => $m->id])->get()->count() == 0) { ?>
        <option value="{{ $m->id }}"><?= $m->nom ?></option>
    <?php } ?>
<?php } ?>

