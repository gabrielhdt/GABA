<!DOCTYPE html>
<html lang="fr">

<?php include 'db.php';
include 'form_func.php';
include "head.php"; ?>

<body>

<?php include "nav.php" ?>

<?php

$lines = get_values_light("kingdom", "Species");
$kingdoms = array();
foreach ($lines as $line)
{
    array_push($kingdoms, $line['kingdom']);
}
$lines = get_values_light("phylum", "Species");
foreach ($lines as $line)
{
    array_push($phylae, $line['phylum']);
}
$phylae = array();
$lines = get_values_light("class", "Species");
foreach ($lines as $line)
{
    array_push($classes, $line['class']);
}
$classes = array();
$lines = get_values_light("order_s", "Species");
foreach ($lines as $line)
{
    array_push($orders, $line['order_s']);
}
$orders = array();
$lines = get_values_light("family", "Species");
foreach ($lines as $line)
{
    array_push($families, $line['family']);
}
$families = array();
$lines = get_values_light("genus", "Species");
foreach ($lines as $line)
{
    array_push($genuses, $line['genus']);
}
$genuses = array();
?>

<div class="container4">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter une espèce</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addspecies.php" method="post">
                        <input type="text" name="species" placeholder="Nom de l'espèce*">
                        <input type="text" name="kingdom" placeholder="Royaume*" list="kingdom_sugg">
                            <datalist id="kingdom_sugg">
                                <?php create_autocplt_list($kingdoms); ?>
                            </datalist>
                        <input type="text" name="phylum" placeholder="Phylum*" list="phylum_sugg">
                            <datalist id="phylum_sugg">
                                <?php create_autocplt_list($phylae); ?>
                            </datalist>
                        <input type="text" name="class" placeholder="Classe*" list="class_sugg">
                            <datalist id="class_sugg">
                                <?php create_autocplt_list($classes); ?>
                            </datalist>
                        <input type="text" name="order" placeholder="Ordre*" list="order_sugg">
                            <datalist id="order_sugg">
                                <?php create_autocplt_list($orders); ?>
                            </datalist>
                        <input type="text" name="family" placeholder="Famille*" list="family_sugg">
                            <datalist id="family_sugg">
                                <?php create_autocplt_list($families); ?>
                            </datalist>
                        <input type="text" name="genus" placeholder="Genus*" list="genus_sugg">
                            <datalist id="genus_sugg">
                                <?php create_autocplt_list($genuses); ?>
                            </datalist>
                        <input type="text" name="status" placeholder="pwet*status">
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_POST['species']))
{
    add_line('Species',
        array('binomial_name' => $_POST['species'],
        'kingdom' => $_POST['kingdom'],
        'phylum' => $_POST['phylum'],
        'class' => $_POST['class'],
        'order_s' => $_POST['order'],
        'family' => $_POST['family'],
        'genus' => $_POST['genus'],
        'conservation_status' => $_POST['status'])
    );
}
?>

<?php include "footer.php" ?>

</body>
</html>
