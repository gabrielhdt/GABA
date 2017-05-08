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
    include 'nav.php';
    ?>
    <style media="screen">
        #espece, #individu, #batiment, #chercheur {
            padding: 200px 0;
            font-size: 5em;
            font-family: 'Linux Biolinum sc';
            color: rgb(85, 172, 59);
            text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
        }
    </style>
    <body>
        <div class="container-fluid">
            <div id="add" class="row">
                    <a href="addspecies.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="espece">Ajout d'une espèce</a>
                    <a href="addfollowed.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Ajout d'un individu</a>
                    <a href="addfacility.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="batiment">Ajout d'un bâtiment</a>
                    <a href="perso.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="chercheur">Informations personnelles</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
