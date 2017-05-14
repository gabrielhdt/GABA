<html>

    <?php
    include 'head.php';
    head('Recherche');
    include 'nav.php';
    ?>
    <body>
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
