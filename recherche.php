<html>

    <?php
    include 'head.php';
    head('Recherche');
    include 'nav.php';
    ?>
    <body>
        <div class="container-fluid">
            <div class="row">
                    <a href="search_species.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="espece">Recherche espèce</a>
                    <a href="search_followed.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Recherche individu</a>
                    <a href="search_facility.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="batiment">Recherche bâtiment</a>
                    <a href="chercheur.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="chercheur">Recherche chercheur</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
