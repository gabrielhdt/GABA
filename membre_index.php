<?php // TODO: ajouter des photos pour le menu ?>

<?php
session_start ();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

//script d'origine
// if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
//     include('i18n/fr_FR/index_fr_FR.php');
// } elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
//     include('i18n/en_UK/index_en_UK.php');
// }
//fin du script d'origine
?>

<?php
include 'head.php';
head("Votre compte", $lang);
?>

<body>
    <?php include 'nav.php'; ?>
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
