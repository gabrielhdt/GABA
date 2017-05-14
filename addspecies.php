<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) ||
    $_SESSION['login'] == 'admin') { // utilisateur passé par le formulaire?
    header ('Location: login.php'); // sinon retour page login
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php include 'db.php';
include 'form_func.php';
include "head.php";
head("Ajouter une espèce");
?>

<body>

<?php include "nav.php" ?>

<?php
// If called after search fill database
if (isset($_POST['species']))
{
    $added_id = add_line('Species',
        array('binomial_name' => mb_strtolower($_POST['species']),
        'kingdom' => mb_strtolower($_POST['kingdom']),
        'phylum' => mb_strtolower($_POST['phylum']),
        'class' => mb_strtolower($_POST['class']),
        'order_s' => mb_strtolower($_POST['order']),
        'family' => mb_strtolower($_POST['family']),
        'genus' => mb_strtolower($_POST['genus']),
        'conservation_status' => mb_strtolower($_POST['status']))
    );
    if ($added_id) {
        add_line('SpeciesEdition',
            array('idStaff' => $_SESSION['idstaff'],
            'idSpecies' => $added_id,
            'type' => 'addition')
        );
    }
}

// Getting info for form
$lines = get_values_light("DISTINCT kingdom", "Species");
$kingdoms = array();
foreach ($lines as $line)
{
    array_push($kingdoms, ucfirst($line['kingdom']));
}
$lines = get_values_light("DISTINCT phylum", "Species");
$phylae = array();
foreach ($lines as $line)
{
    array_push($phylae, ucfirst($line['phylum']));
}
$lines = get_values_light("DISTINCT class", "Species");
$classes = array();
foreach ($lines as $line)
{
    array_push($classes, ucfirst($line['class']));
}
$lines = get_values_light("DISTINCT order_s", "Species");
$orders = array();
foreach ($lines as $line)
{
    array_push($orders, ucfirst($line['order_s']));
}
$lines = get_values_light("DISTINCT family", "Species");
$families = array();
foreach ($lines as $line)
{
    array_push($families, ucfirst($line['family']));
}
$lines = get_values_light("DISTINCT genus", "Species");
$genuses = array();
foreach ($lines as $line)
{
    array_push($genuses, ucfirst($line['genus']));
}
?>
<div class="container" style="background-image: url('data/pics/unordered/herd.jpg');">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="formulaire">
            <div class="middle">
                <h1>Ajouter une espèce</h1>
                <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                <form action="addspecies.php" method="post">
                    <input class='form-control' type="text" name="species" placeholder="Nom de l'espèce*">
                    <input class='form-control' type="text" name="kingdom" placeholder="Royaume*" list="kingdom_sugg">
                        <datalist id="kingdom_sugg">
                            <?php create_autocplt_list($kingdoms); ?>
                        </datalist>
                    <input class='form-control' type="text" name="phylum" placeholder="Phylum*" list="phylum_sugg">
                        <datalist id="phylum_sugg">
                            <?php create_autocplt_list($phylae); ?>
                        </datalist>
                    <input class='form-control' type="text" name="class" placeholder="Classe*" list="class_sugg">
                        <datalist id="class_sugg">
                            <?php create_autocplt_list($classes); ?>
                        </datalist>
                    <input class='form-control' type="text" name="order" placeholder="Ordre*" list="order_sugg">
                        <datalist id="order_sugg">
                            <?php create_autocplt_list($orders); ?>
                        </datalist>
                    <input class='form-control' type="text" name="family" placeholder="Famille*" list="family_sugg">
                        <datalist id="family_sugg">
                            <?php create_autocplt_list($families); ?>
                        </datalist>
                    <input class='form-control' type="text" name="genus" placeholder="Genus*" list="genus_sugg">
                        <datalist id="genus_sugg">
                            <?php create_autocplt_list($genuses); ?>
                        </datalist>
                    <input class='form-control' type="text" name="status" placeholder="pwet*status">
                    <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html>
