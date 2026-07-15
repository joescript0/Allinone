<?php
    use App\Models\Listesfactures;
?>
<option selected value="">Selectionnez une commune</option>
<?php foreach ($communes as $m) { ?>
    <option value="{{ $m->id }}"><?= $m->nom ?>   </option>
<?php } ?>

