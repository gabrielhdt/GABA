<!DOCTYPE html>
<html lang="fr">
<?php include 'db.php';
include "head.php"; ?>


<body>
<?php include "nav.php" ?>

<div class="container4">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter une espèce</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addspecies.php" method="post">
                        <input type="text" name="species" placeholder="Nom de l'espèce*">
                        <input type="text" name="kingdom" placeholder="Royaume*">
                        <input type="text" name="phylum" placeholder="Phylum*">
                        <input type="text" name="class" placeholder="Classe*">
                        <input type="text" name="order" placeholder="Ordre*">
                        <input type="text" name="family" placeholder="Famille*">
                        <input type="text" name="genus" placeholder="Genus*">
                        <input type="text" name="status" placeholder="pwet*status">
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_POST['species']))
{
    add_line('Species',
        array('binomial_name' => $_POST['species'],
        'kingdom' => $_POST['kingdom'],
        'phylum' => $_POST['phylum'],
        'class' => $_POST['class'],
        'order_s' => $_POST['order'],
        'family' => $_POST['family'],
        'genus' => $_POST['genus'],
        'conservation_status' => $_POST['status'])
    );
}
?>
<br><br><br>

<?php include "footer.php" ?>
</body>
</html>
