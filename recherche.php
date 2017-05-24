<?php
if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/research_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/research_en_UK.php');
}
?>

<?php
include 'head.php';
head('Recherche', $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div class="row">
                <a href="search_species.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12" id="espece"><?php echo $species ?></a>
                <a href="search_followed.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12 " id="individu"><?php echo $followed ?></a>
                <a href="search_facility.php" class="photo_menu col-lg-4 col-md-4 col-sm-12 col-xs-12" id="batiment"><?php echo $facility ?></a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
