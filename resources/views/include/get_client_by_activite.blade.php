<?php
    use App\Models\Listesfactures;
?>
<option selected value="">Selectionnez un client</option>
<option value="0">Tout les clients</option>
<?php foreach ($clients as $m) { ?>
    <option value="{{ $m->id }}"><?= 'Nom: ' . $m->name ?>   </option>
<?php } ?>

