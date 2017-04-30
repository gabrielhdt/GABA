<!DOCTYPE html>
<html lang="fr">

<?php include 'db.php';
include 'form_func.php';
include "head.php"; ?>

<body>

<?php include "nav.php" ?>

<div class="container3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-4">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un individu</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfollowed.php" method="post">
                        <select name="species" class="form-control input-sm">
<?php
$id_biname = array();
$lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
create_choice_list($id_biname);
?>
                        </select>
                        <select name='facility' class='form-control input-sm'>
<?php
$lines = get_values(array('idFacility', 'name'), 'Facility');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}
create_choice_list($id_faname, $defsel='1');
?>
                        </select>
                        <label class='radio-inline'>
                            <input type='radio' name='gender' value='m'>Male
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='gender' value='f'>Female
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='gender' value='h'>Hermaphrodite
                        </label>
                        <input type="date" name="birth" class="form-control" placeholder="Date de naissance*">
                        <input type="text" name="health" class="form-control" placeholder="Etat de santé*">
                        <button class="btn btn-success" type="submit" name="add_followed">Enregistrer</button>
                    </form>
<?php
if (isset($_POST['species']))
{
    add_line('Followed',
        array('idSpecies' => $_POST['species'],
        'gender' => $_POST['gender'],
        'birth' => $_POST['birth'],
        'health' => $_POST['health']));
}
?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>

</body>
</html>
