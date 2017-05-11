<html>

    <?php
    include 'head.php';
    head('Recherche');
    include 'nav.php';
    ?>
    <body>
        <div class="container-fluid">
            <div class="row">
                    <a href="search_followed.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="espece">Recherchez espèce</a>
                    <a href="search_species.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Recherchez individu</a>
                    <a href="search_facility.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="batiment">Recherchez bâtiment</a>
                    <a href="chercheur.php" class="photo_menu col-lg-3 col-md-6 col-sm-12 col-xs-12" id="chercheur">Recherchez chercheur</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
