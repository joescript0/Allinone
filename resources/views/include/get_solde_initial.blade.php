<?php
    use App\Models\Soldes;
    use App\Models\Mois;
    use App\Models\Annees;
?>
<?php
    $soldes = Soldes::get();
    $solde_id = $soldes->count() + 1;
    $solde_precedent = $solde_id - 1;
?>
<option selected value="">Selectionnez un solde initial</option>
<option value="0">Commencez avec un solde initial de 0</option>
<?php if (Soldes::where(["id" => $solde_precedent])->get()->count() != 0) { ?>
    <?php
        $annee_id = Soldes::where(["id" => $solde_precedent])->first()["annee_id"];
        $moi_id = Soldes::where(["id" => $solde_precedent])->first()["moi_id"];
    ?>
    <option value="{{ Soldes::where(["id" => $solde_precedent])->first()["solde_actuel"]; }}">Commencer avec un solde initial de {{ Soldes::where(["id" => $solde_precedent])->first()["solde_actuel"]; }} ( {{ Mois::where(["id" => $moi_id])->first()["nom"]; }} {{ Annees::where(["id" => $annee_id])->first()["annees"]; }} ) </option>
<?php } ?>
