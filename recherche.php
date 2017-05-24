<?php
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
head('Recherche', $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div class="row">
                <a href="search_species.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12" id="espece">Recherche espèce</a>
                <a href="search_followed.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12 " id="individu">Recherche individu</a>
                <a href="search_facility.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12" id="batiment">Recherche bâtiment</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
