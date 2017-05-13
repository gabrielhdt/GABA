<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) || $_SESSION['login'] == 'admin') { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php
include "db.php";
include "form_func.php";
include "head.php";
head("Ajouter un individu");
include "script/add_script.php";
?>
<body>
<?php include "nav.php" ?>
<?php
$id_biname = array();
$lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
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
?>
<!-- <div class="container3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-4">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un individu</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfollowed.php" method="post">
                        <div class="form-group">
                            <select name="species" class="form-control">
                                <//?php create_choice_list($id_biname); ?>
                            </select>
                            <select name='facility' class='form-control'>
                                <//?php create_choice_list($id_faname, $defsel='1'); ?>
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
                        </div>
                        <button class="btn btn-success" id="submitbtn" type="submit" name="add_followed">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="container">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
        <div class="description">
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
                        <textarea class="form-control" name="annotation" rows="8" cols="80"
                                  placeholder="Commentaire sur L'individu"></textarea>
                    </div>
                    <button class="btn btn-success" id="submitbtn" type="submit" name="add_followed">Enregistrer</button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php
if (isset($_POST['species']))
{
    $added_id = add_line('Followed',
        array('idSpecies' => $_POST['species'],
        'gender' => mb_strtolower($_POST['gender']),
        'birth' => $_POST['birth'],
        'health' => mb_strtolower($_POST['health']),
        'idFacility' => $_POST['facility'])
    );
    if ($added_id) {
        add_line('FollowedEdition',
            array('idStaff' => $_SESSION['idstaff'],
            'idFollowed' => $added_id,
            'type' => 'addition')
        );
        if (isset($_POST['use_geoloc']) && $_POST['use_geoloc'] == 'on')
        {
            $idmeasure = add_line('Measure',
                array(
                    'idFollowed' => $added_id,
                    'idStaff' => $_SESSION['idstaff']
                )
            );
            $coords = explode(',', $_COOKIE['geoloc']);
            add_line('Location',
                array(
                    'latitude' => (float) $coords[0],
                    'longitude' => (float) $coords[1],
                    'idMeasure' => $idmeasure
                )
            );
        }
    }
}

?>
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
