<!DOCTYPE html>
<html lang="fr">
<?php include 'db.php' ?>
<?php include "head.php" ?>
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
                    <form action="addfollowed.html" method="post">
                        <input type="text" name="species" placeholder="Espèce*">
                        <input type="text" name="gender" placeholder="Sexe*">
                        <input type="text" name="birth" placeholder="Date de naissance*">
                        <input type="text" name="health" placeholder="Etat de santé*">
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br><br>

<?php include "footer.php" ?>
</body>
</html>
