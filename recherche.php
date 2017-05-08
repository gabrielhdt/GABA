<html>

    <?php
    include 'head.php';
    head('Recherche');
    include 'nav.php';
    ?>
    <style>
    </style>

    <body>
        <div class="container-fluid">
            <div class="row">
                    <a href="search_followed.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="espece">Recherez espèce</a>
                    <a href="search_species.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12 " id="individu">Recherez individu</a>
                    <a href="search_facility.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="batiment">Recherez bâtiment</a>
                    <a href="chercheur.php" class="col-lg-3 col-md-6 col-sm-12 col-xs-12" id="chercheur">Recherez chercheur</a>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
