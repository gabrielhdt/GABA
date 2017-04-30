<!DOCTYPE html>
<html lang="fr">
<?php include 'db.php';
include 'form_func.php';
include "head.php"; ?>
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
<div class="container3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-4">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un individu</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfollowed.php" method="post">
                        <div class="input-group">
                            <select name="species" class="form-control input-sm">
                                <?php create_choice_list($id_biname); ?>
                            </select>
                            <select name='facility' class='form-control input-sm'>
                                <?php create_choice_list($id_faname, $defsel='1'); ?>
                            </select>
                         </div>
                         <div class="radio">
                            <label>
                                <input type="radio" name="gender" values="m">
                                Male
                            </label>
                            <label>
                                <input type="radio" name="gender" values="f">
                                Female
                            </label>
                            <label>
                                <input type="radio" name="gender" values="h">
                                Hermaphrodite
                            </label>
                        </div>
                        <div class="input-group">
                            <input type="date" name="birth" class="form-control" placeholder="Date de naissance*">
                            <input type="text" name="health" class="form-control" placeholder="Etat de santé*">
                        </div>
                        <button class="btn btn-success" type="submit" name="add_followed">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['species']))
{
    add_line('Followed',
        array('idSpecies' => $_POST['species'],
        'gender' => mb_strtolower($_POST['gender']),
        'birth' => $_POST['birth'],
        'health' => mb_strtolower($_POST['health']),
        'idFacility' => $_POST['facility'])
    );
    update_view('vSearchFoll');
}
?>
<?php include "footer.php" ?>
</body>
</html>
