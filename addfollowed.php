<?php
session_start ();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) ||
    $_SESSION['login'] == 'admin') { // Get through login?
    header ('Location: login.php'); // sinon retour page login
    exit();
}

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/addfollowed_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/addfollowed_en_UK.php');
}

include "script/db.php";
include "script/form_func.php";
include "head.php";

$id_biname = array();
$lines = get_values('idSpecies, binomial_name', 'Species',
    array('orderby' => 'binomial_name'));
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
$lines = get_values('idFacility, name', 'Facility', array('orderby' => 'name'));
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}

// If form info, fill database
if (isset($_POST['species']))
{
    //Check values
    $valid = TRUE;
    foreach (array('facility', 'species') as $intfld) {
        $valid = $valid && filter_var($_POST[$intfld], FILTER_VALIDATE_INT);
    }
    foreach (array('gender', 'birth', 'health') as $strfld) {
        $valid = $valid && filter_var($_POST[$strfld],
            FILTER_VALIDATE_REGEXP,
            array('options' => array('regexp' => $filt_pattern))
        );
    }

    if ($valid) {
        $values = array(
            'gender' => array(
                'value' => $_POST['gender'], 'type' => PDO::PARAM_STR
            ),
            'birth' => array(
                'value' => $_POST['birth'], 'type' => PDO::PARAM_STR
            ),
            'health' => array(
                'value' => $_POST['health'], 'type' => PDO::PARAM_STR
            ),
            'annotation' => array(
                'value' => $_POST['annotation'], 'type' => PDO::PARAM_STR
            ),
            'idFacility' => array(
                'value' => $_POST['facility'], 'type' => PDO::PARAM_INT
            ),
            'idSpecies' => array(
                'value' => $_POST['species'], 'type' => PDO::PARAM_INT
            ),
        );
        $added_id = add_line('Followed', $values);
        if ($added_id) {
            add_line('FollowedEdition',
                array(
                    'idStaff' => array(
                        'value' => $_SESSION['idstaff'], 'type' => PDO::PARAM_INT
                    ),
                    'idFollowed' => array(
                        'value' => $added_id, 'type' => PDO::PARAM_INT
                    ),
                    'type' => array(
                        'value' => 'addition', 'type' => PDO::PARAM_STR
                    )
                )
            );
            if (isset($_POST['use_geoloc']) && $_POST['use_geoloc'] == 'on')
            {
                $idmeasure = add_line('Measure',
                    array(
                        'idFollowed' => array(
                            'value' => $added_id, 'type' => PDO::PARAM_INT
                        ),
                        'idStaff' => array(
                            'value' => $_SESSION['idstaff'],
                            'type' => PDO::PARAM_INT
                        )
                    )
                );
                $coords = explode(',', $_COOKIE['geoloc']);
                add_line('Location',
                    array(
                        'latitude' => array(
                            'value' => (float) $coords[0],
                            'type' => PDO::PARAM_STR
                        ),
                        'longitude' => array(
                            'value' => (float) $coords[1],
                            'type' => PDO::PARAM_STR
                        ),
                        'idMeasure' => array(
                            'value' => $idmeasure, 'type' => PDO::PARAM_INT
                        )
                    )
                );
            }
        }
    }
}

head("Ajouter un individu", $lang);
?>
<body>
<?php
include "nav.php";
if (isset($added_id, $valid) && $added_id && $valid) {
?>
<div class="alert alert-success" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_succes ?>
</div>
<?php }
elseif (isset($added_id) && !$added_id || isset($valid) && !$valid) { ?>
<div class="alert alert-danger" role="alert">
    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
    <?php echo $alert_danger ?>
</div>
<?php } ?>
<div class="container" style="background-image: url('data/pics/unordered/mada.jpg');">
    <div class="add-form col-lg-5 col-md-5 col-sm-6 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-5">
        <div class="formulaire">
            <div class="middle">
                <?php echo $title_form ?>
                <?php echo $paragraph_form ?>
                <form action="addfollowed.php" method="post">
                    <div class="form-group">
                        <select name="species" class="form-control">
                            <?php create_choice_list($id_biname); ?>
                        </select>
                        <select name='facility' class='form-control'>
                            <?php create_choice_list($id_faname, $defsel='1'); ?>
                        </select>
                        <label class="radio-inline"><input type="radio" name="gender" value="m"><?php echo $sex_m ?></label>
                        <label class="radio-inline"><input type="radio" name="gender" value="f"><?php echo $sex_f ?></label>
                        <label class="radio-inline"><input type="radio" name="gender" value="h"><?php echo $sex_h ?></label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="use_geoloc" name="use_geoloc" value="on"><?php echo $pos ?>
                        </label>
                        <br>
                        <input type="date" name="birth" class="form-control" placeholder="<?php echo $birth_date ?>">
                        <input type="text" name="health" class="form-control" placeholder="<?php echo $health ?>">
                        <textarea class="form-control" name="annotation" rows="4" cols="80"
                                  placeholder="Commentaire sur L'individu"></textarea>
                    </div>
                    <button class="btn btn-success" id="submitbtn" type="submit" name="add_followed"><?php echo $save ?></button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
<script>
document.getElementById('use_geoloc').onclick = function()
{
    if (this.checked)
    {
        document.getElementById('submitbtn').disabled = true;
        navigator.geolocation.getCurrentPosition(coord2cookies);
        document.getElementById('submitbtn').disabled = false;
    }
    else
    {
        document.getElementById('submitbtn').disabled = false;
    }
}

function coord2cookies(position)
{
    document.cookie = 'geoloc='+position.coords.latitude+','+position.coords.longitude;
}
</script>
</html>
