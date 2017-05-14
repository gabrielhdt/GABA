<!DOCTYPE html>
<html lang="fr">

<?php include 'script/db.php';
include 'script/form_func.php';
include "head.php";
head("Ajouter un bâtiment");
?>

<body>

<?php include "nav.php" ?>

<div class="container5">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un centre</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfacility.php" method="post">
                        <div class="input-goup">
                            <input class="form-control" type="text" name="fname" placeholder="Nom du centre*">
                            <input class="form-control" type="text" name="type" placeholder="Type">
                            <input class="form-control" type="number" name="latitude" step="0.00000000000000001" placeholder="Latitude">
                            <input class="form-control" type="number" name="longitude" step="0.00000000000000001" placeholder="Longitude">
                        </div>
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$table = 'Facility';
$add = array();
if (isset($_POST['latitude'], $_POST['longitude']))
{
    $add['gnss_coord'] = $_POST['latitude'] . ',' . $_POST['longitude'];
}
if (isset($_POST['fname']))
{
    $add['name'] = $_POST['fname'];
    $add['type'] = $_POST['type'];
    add_line($table, $add);
}
?>
<?php include "footer.php" ?>
</body>
</html>
