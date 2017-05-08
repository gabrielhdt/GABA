<?php
session_start();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}

?>
<html>
    <style media="screen">
        .row {
            text-align: center;
        }
        a {
            vertical-align: middle;
        }
    </style>
    <?php
    include 'head.php';
    include 'nav.php';
    ?>
    <style>
    </style>

    <body>
        <div class="container-fluid">
            <div id="add" class="row">
                    <a href="addspecies.php"><div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="espece">Ajout d'une espèce</div></a>
                    <a href="addfollowed.php"><div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Ajout d'un individu</div></a>
                    <a href="addfacility.php"><div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="batiment">Ajout d'un bâtiment</div></a>
                    <a href="perso.php"><div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="chercheur">Informations personnelles</div></a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
