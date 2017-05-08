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
    <style>
    #add {
        border: 2px solid red;
        display: table;
    }
    a {
        display: table-cell;
        vertical-align: middle;
    }
    </style>

    <body>
        <div class="container-fluid">
            <div id="add" class="row">
                    <a href="addspecies.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="espece">Ajout d'une espèce</a>
                    <a href="addfollowed.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Ajout d'un individu</a>
                    <a href="addfacility.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="batiment">Ajout d'un bâtiment</a>
                    <a href="perso.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="chercheur">Informations personnelles</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
