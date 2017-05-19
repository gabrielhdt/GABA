<?php
if (!isset($_SESSION['login'], $_SESSION['idstaff']) ||
    $_SESSION['login'] == 'admin') { // Get through login?
    header ('Location: login.php'); // sinon retour page login
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php
include "script/db.php";
include "script/form_func.php";
include "head.php";
head("Ajouter un individu");
?>
<body>
<?php include "nav.php" ?>
<?php
$id_biname = array();
$lines = get_values_light('idSpecies, binomial_name', 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
$lines = get_values(array('idFacility', 'name'), 'Facility');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}

// If form info, fill database
if (isset($_POST['species']))
{
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
    $added_id = add_line_smart('Followed', $values);
    if ($added_id) {
        add_line_smart('FollowedEdition',
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
            $idmeasure = add_line_smart('Measure',
                array(
                    'idFollowed' => array(
                        'value' => $added_id, 'type' => PDO::PARAM_INT
                    ),
                    'idStaff' => array(
                        'value' => $_SESSION['idstaff'], 'type' => PDO::PARAM_INT
                    )
                )
            );
            $coords = explode(',', $_COOKIE['geoloc']);
            add_line_smart('Location',
                array(
                    'latitude' => array(
                        'value' => (float) $coords[0], 'type' => PDO::PARAM_STR
                    ),
                    'longitude' => array(
                        'value' => (float) $coords[1], 'type' => PDO::PARAM_STR
                    ),
                    'idMeasure' => array(
                        'value' => $idmeasure, 'type' => PDO::PARAM_INT
                    )
                )
            );
        }
    }
}
?>
<div class="container" style="background-image: url('data/pics/unordered/mada.jpg');">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
        <div class="formulaire">
            <div class="middle">
                <h1>Ajouter un individu</h1>
                <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                <form action="addfollowed.php" method="post">
                    <div class="form-group">
                        <select name="species" class="form-control">
                            <?php create_choice_list($id_biname); ?>
                        </select>
                        <select name='facility' class='form-control'>
                            <?php create_choice_list($id_faname, $defsel='1'); ?>
                        </select>
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="m">Male
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="f">
                            Female
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="h">
                            Hermaphrodite
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="use_geoloc" name="use_geoloc" value="on">
                            Use current position as animal's
                        </label>
                        <br>
                        <input type="date" name="birth" class="form-control" placeholder="Date de naissance*">
                        <input type="text" name="health" class="form-control" placeholder="Etat de santé*">
                        <textarea class="form-control" name="annotation" rows="4" cols="80"
                                  placeholder="Commentaire sur L'individu"></textarea>
                    </div>
                    <button class="btn btn-success" id="submitbtn" type="submit" name="add_followed">Enregistrer</button>
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
