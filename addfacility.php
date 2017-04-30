<!DOCTYPE html>
<html lang="fr">

<?php include 'db.php';
include 'form_func.php';
include "head.php"; ?>

<body>

<?php include "nav.php" ?>

<div class="container5">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un centre</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfacility.html" method="post">
                        <input type="text" name="fname" placeholder="Nom du centre*">
                        <input type="text" name="country" placeholder="Pays*">
                        <input type="text" name="state" placeholder="Etat / Région*">
                        <input type="text" name="address" placeholder="Adresse*">
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>