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

//script d'origine
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/membre_index_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/membre_index_en_UK.php');
}
?>

<?php
include 'head.php';
head($title_head, $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div id="add" class="row">
                <a href="addspecies.php"
                    class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    id="addspecies"><?php echo $species ?></a>
                <a href="addfollowed.php"
                    class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    id="addfollowed"><?php echo $followed ?></a>
                <a href="addfacility.php"
                    class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    id="addfacility"><?php echo $facility ?></a>
                <a href="perso.php"
                    class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    id="perso"><?php echo $perso_infos ?></a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
