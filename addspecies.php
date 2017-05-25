<?php
session_start ();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) ||
    $_SESSION['login'] == 'admin') { // utilisateur passé par le formulaire?
    header ('Location: login.php'); // sinon retour page login
    exit();
}

if(isset($_COOKIE['lang'])) {
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
// If called after search fill database
if (isset($_POST['species']))
{
    $added_id = add_line('Species',
        array(
            'binomial_name' => array(
                'value' => $_POST['species'], 'type' => PDO::PARAM_STR
            ),
            'kingdom' => array(
                'value' => $_POST['kingdom'], 'type' => PDO::PARAM_STR
            ),
            'phylum' => array(
                'value' => $_POST['phylum'], 'type' => PDO::PARAM_STR
            ),
            'class' => array(
                'value' => $_POST['class'], 'type' => PDO::PARAM_STR
            ),
            'order_s' => array(
                'value' => $_POST['order'], 'type' => PDO::PARAM_STR
            ),
            'family' => array(
                'value' => $_POST['family'], 'type' => PDO::PARAM_STR
            ),
            'genus' => array(
                'value' => $_POST['genus'], 'type' => PDO::PARAM_STR
            ),
            'conservation_status' => array(
                'value' => $_POST['status'], 'type' => PDO::PARAM_STR
            )
        )
    );
    if ($added_id) {
        $added_id = add_line('SpeciesEdition',
            array(
                'idStaff' => array(
                    'value' => $_SESSION['idstaff'], 'type' => PDO::PARAM_INT
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

// Getting info for form
$lines = get_values('DISTINCT kingdom', 'Species',
    array('orderby' => 'kingdom'));
$kingdoms = array();
foreach ($lines as $line)
{
    array_push($kingdoms, ucfirst($line['kingdom']));
}
$lines = get_values('DISTINCT phylum', 'Species', array('orderby' => 'phylum'));
$phylae = array();
foreach ($lines as $line)
{
    array_push($phylae, ucfirst($line['phylum']));
}
$lines = get_values('DISTINCT class', 'Species', array('orderby' => 'class'));
$classes = array();
foreach ($lines as $line)
{
    array_push($classes, ucfirst($line['class']));
}
$lines = get_values("DISTINCT order_s", 'Species',
    array('$orderby' => 'order_s'));
$orders = array();
foreach ($lines as $line)
{
    array_push($orders, ucfirst($line['order_s']));
}
$lines = get_values('DISTINCT family', 'Species', array('orderby' => 'family'));
$families = array();
foreach ($lines as $line)
{
    array_push($families, ucfirst($line['family']));
}
$lines = get_values('DISTINCT genus', 'Species', array('orderby' => 'genus'));
$genuses = array();
foreach ($lines as $line)
{
    array_push($genuses, ucfirst($line['genus']));
}

head("Ajouter une espèce", $lang);

?>
<body>
<?php
include "nav.php";
if (isset($added_id) && $added_id)
{ //TODO: pre fill followed.php page
?>
<div class="alert alert-success" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_succes ?>
</div>
<?php }
elseif (isset($added_id) && !$added_id) {?>
<div class="alert alert-danger" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_danger ?>
</div>
<?php } ?>

<div class="container" style="background-image: url('data/pics/unordered/herd.jpg');">
    <div class="add-form col-lg-5 col-md-5 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="formulaire">
            <div class="middle">
                <?php echo $title ?>
                <?php echo $paragraph_form ?>
                <form action="addspecies.php" method="post">
                    <input class='form-control' type="text" name="species" placeholder="<?php echo $species; ?>">
                    <input class='form-control' type="text" name="kingdom" placeholder="<?php echo $kingdom; ?>" list="kingdom_sugg">
                        <datalist id="kingdom_sugg">
                            <?php create_autocplt_list($kingdoms); ?>
                        </datalist>
                    <input class='form-control' type="text" name="phylum" placeholder="<?php echo $phylum; ?>" list="phylum_sugg">
                        <datalist id="phylum_sugg">
                            <?php create_autocplt_list($phylae); ?>
                        </datalist>
                    <input class='form-control' type="text" name="class" placeholder="<?php echo $class; ?>" list="class_sugg">
                        <datalist id="class_sugg">
                            <?php create_autocplt_list($classes); ?>
                        </datalist>
                    <input class='form-control' type="text" name="order" placeholder="<?php echo $order; ?>" list="order_sugg">
                        <datalist id="order_sugg">
                            <?php create_autocplt_list($orders); ?>
                        </datalist>
                    <input class='form-control' type="text" name="family" placeholder="<?php echo $family; ?>" list="family_sugg">
                        <datalist id="family_sugg">
                            <?php create_autocplt_list($families); ?>
                        </datalist>
                    <input class='form-control' type="text" name="genus" placeholder="<?php echo $genus; ?>" list="genus_sugg">
                        <datalist id="genus_sugg">
                            <?php create_autocplt_list($genuses); ?>
                        </datalist>
                    <input class='form-control' type="text" name="status" placeholder="<?php echo $status; ?>">
                    <button class="btn btn-success" type="submit" name="submit_contact"><?php echo $submit; ?></button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html>
