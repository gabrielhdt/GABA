<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) ||
    $_SESSION['login'] == 'admin') { // utilisateur passé par le formulaire?
    header('Location: login.php'); // sinon retour page login
    exit();
}

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/addspecies_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/addspecies_en_UK.php');
}


include 'script/db.php';
include 'script/form_func.php';
include "head.php";
?>


<?php
$fields_arr = array('binomial_name', 'kingdom', 'phylum', 'class', 'order_s',
    'family', 'genus', 'conservation_status');
// If called after search fill database
if (isset($_POST['binomial_name'])) {
    //Check input
    $valid = true;
    foreach ($fields_arr as $ch_field) {
        $valid = $valid && filter_var(
            $_POST[$ch_field],
            FILTER_VALIDATE_REGEXP,
            array('options' => array('regexp' => $filt_pattern))
        );
    }
    if ($valid) {
        $add_arr = array();
        foreach ($fields_arr as $fld) {
            $add_arr[$fld] = array(
                'value' => $_POST[$fld], 'type' => PDO::PARAM_STR
            );
        }
        $added_id = add_line('Species', $add_arr);
        if ($added_id) {
            $added_id = add_line(
                'SpeciesEdition',
                array(
                    'idStaff' => array(
                        'value' => $_SESSION['idstaff'],
                        'type' => PDO::PARAM_INT
                    ),
                    'idSpecies' => array(
                        'value' => $added_id, 'type' => PDO::PARAM_INT
                    ),
                    'type' => array(
                        'value' => 'addition', 'type' => PDO::PARAM_STR
                    )
                )
            );
        }
    }
}

// Getting info for form
$lines = get_values(
    'DISTINCT kingdom',
    'Species',
    array('orderby' => 'kingdom')
);
$kingdoms = array();
foreach ($lines as $line) {
    array_push($kingdoms, ucfirst($line['kingdom']));
}
$lines = get_values('DISTINCT phylum', 'Species', array('orderby' => 'phylum'));
$phylae = array();
foreach ($lines as $line) {
    array_push($phylae, ucfirst($line['phylum']));
}
$lines = get_values('DISTINCT class', 'Species', array('orderby' => 'class'));
$classes = array();
foreach ($lines as $line) {
    array_push($classes, ucfirst($line['class']));
}
$lines = get_values(
    "DISTINCT order_s",
    'Species',
    array('$orderby' => 'order_s')
);
$orders = array();
foreach ($lines as $line) {
    array_push($orders, ucfirst($line['order_s']));
}
$lines = get_values('DISTINCT family', 'Species', array('orderby' => 'family'));
$families = array();
foreach ($lines as $line) {
    array_push($families, ucfirst($line['family']));
}
$lines = get_values('DISTINCT genus', 'Species', array('orderby' => 'genus'));
$genuses = array();
foreach ($lines as $line) {
    array_push($genuses, ucfirst($line['genus']));
}

head($title_head, $lang);

?>
<body>
<?php
include "nav.php";
if (isset($valid, $added_id) && $valid && $added_id) {
    //TODO: pre fill followed.php page
?>
<div class="alert alert-success" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_succes ?>
</div>
<?php
} elseif (isset($added_id) && !$added_id || isset($valid) && !$valid) {
    ?>
<div class="alert alert-danger" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_danger ?>
</div>
<?php
} ?>

<div class="container" style="background-image: url('data/pics/unordered/herd.jpg');">
    <div class="add-form col-lg-5 col-md-5 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="formulaire">
            <div class="middle">
                <?php echo $title ?>
                <?php echo $paragraph_form ?>
                <form action="addspecies.php" method="post">
                    <input class='form-control' type="text" name="binomial_name"
                        placeholder="<?php echo $species; ?>">
                    <input class='form-control' type="text" name="kingdom"
                        placeholder="<?php echo $kingdom; ?>" list="kingdom_sugg">
                        <datalist id="kingdom_sugg">
                            <?php create_autocplt_list($kingdoms); ?>
                        </datalist>
                    <input class='form-control' type="text" name="phylum"
                        placeholder="<?php echo $phylum; ?>" list="phylum_sugg">
                        <datalist id="phylum_sugg">
                            <?php create_autocplt_list($phylae); ?>
                        </datalist>
                    <input class='form-control' type="text" name="class"
                        placeholder="<?php echo $class; ?>" list="class_sugg">
                        <datalist id="class_sugg">
                            <?php create_autocplt_list($classes); ?>
                        </datalist>
                    <input class='form-control' type="text" name="order_s"
                        placeholder="<?php echo $order; ?>" list="order_sugg">
                        <datalist id="order_sugg">
                            <?php create_autocplt_list($orders); ?>
                        </datalist>
                    <input class='form-control' type="text" name="family"
                        placeholder="<?php echo $family; ?>" list="family_sugg">
                        <datalist id="family_sugg">
                            <?php create_autocplt_list($families); ?>
                        </datalist>
                    <input class='form-control' type="text" name="genus"
                        placeholder="<?php echo $genus; ?>" list="genus_sugg">
                        <datalist id="genus_sugg">
                            <?php create_autocplt_list($genuses); ?>
                        </datalist>
                    <input class='form-control' type="text"
                        name="conservation_status"
                        placeholder="<?php echo $status; ?>">
                    <button class="btn btn-success" type="submit"
                        name="submit_contact"><?php echo $submit; ?></button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html>
