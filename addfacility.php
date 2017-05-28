<?php
session_start ();

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/addfacility_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/addfacility_en_UK.php');
}


include 'script/db.php';
include 'script/form_func.php';
include "head.php";
head($title_head, $lang);
?>

<body>

<?php include "nav.php" ?>
<div class="container" style="background-image: url('data/pics/unordered/facility2.jpg');">
    <div class="add-form col-lg-5 col-md-5 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="formulaire">
            <div class="middle">
                <?php echo $title_form ?>
                <?php echo $paragraph_form ?>
                <form action="addfacility.php" method="post">
                    <div class="input-goup">
                        <input class="form-control" type="text" name="fname" placeholder="<?php echo $placeholder_fname ?>">
                        <input class="form-control" type="text" name="type" placeholder="<?php echo $placeholder_type ?>">
                        <input class="form-control" type="number" name="latitude"
                               step="0.00000000000000001" placeholder="?php echo $placeholder_latidude ?>">
                        <input class="form-control" type="number" name="longitude"
                               step="0.00000000000000001" placeholder="?php echo $placeholder_longitude ?>">
                    </div>
                    <button class="btn btn-success" type="submit" name="submit_contact"><?php echo $submit ?></button>
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
