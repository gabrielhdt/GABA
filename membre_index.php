<?php // TODO: ajouter des photos pour le menu ?>

<?php
session_start();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}

?>
<html>
    <style media="screen">
    </style>
    <?php
    include 'head.php';
    head("Votre compte");
    include 'nav.php';
    ?>
    <body>
        <div class="container-fluid">
            <div id="add" class="row">
                    <a href="addspecies.php"
                        class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        id="addspecies">Ajout d'une espèce</a>
                    <a href="addfollowed.php"
                        class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        id="addfollowed">Ajout d'un individu</a>
                    <a href="addfacility.php"
                        class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        id="addfacility">Ajout d'un bâtiment</a>
                    <a href="perso.php"
                        class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        id="perso">Informations personnelles</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
