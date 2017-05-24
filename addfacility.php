<?php
session_start ();

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

//script d'origine
// if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
//     include('i18n/fr_FR/index_fr_FR.php');
// } elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
//     include('i18n/en_UK/index_en_UK.php');
// }
//fin du script d'origine

include 'script/db.php';
include 'script/form_func.php';
include "head.php";
head("Ajouter un bâtiment", $lang);
?>

<body>

<?php include "nav.php" ?>
<div class="container" style="background-image: url('data/pics/unordered/facility2.jpg');">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="formulaire">
            <div class="middle">
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
<?php
$table = 'Facility';
$add = array();
if (isset($_POST['latitude'], $_POST['longitude']))
{
    $add['gnss_coord'] = array(
        'value' => $_POST['latitude'] . ',' . $_POST['longitude'],
        'type' => PDO::PARAM_STR
    );
}
if (isset($_POST['fname']))
{
    $add['name'] = array('value' => $_POST['fname'], 'type' => PDO::PARAM_STR);
    $add['type'] = array('value' => $_POST['type'], 'type' => PDO::PARAM_STR);
    add_line($table, $add, TRUE);
}
?>
<?php include "footer.php" ?>
</body>
</html>
