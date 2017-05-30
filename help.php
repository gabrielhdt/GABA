<?php
session_start ();

$staff = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

//script d'origine
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/help_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/help_en_UK.php');
}
//fin du script d'origine

include 'head.php';
head($title_head, $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div class="jumbotron">

            <div class="row  row-help">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo $feature_title; ?>
                    <hr>
                </div>
            </div>

            <div class="row  row-help">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                    <img src="data/pics/unordered/login.png" class="img-responsive" alt="Espace login" />
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p1; ?>
                </div>
            </div>

            <div class="row row-help">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p2; ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/recherche.png" class="img-responsive" alt="Espace Recherche" />
                </div>

            </div>

            <div class="row row-help">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/projet_screenshot.png" class="img-responsive" alt="Carousel" />
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p3; ?>
                </div>
            </div>

            <div class="row row-help">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p4; ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/contact_us.png" class="img-responsive" alt="Nous contacter" />
                </div>
            </div>
            <?php if ($staff) {?>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <img src="data/pics/unordered/followed_sceenshot.png" class="img-responsive" alt="Espace login" />
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?php echo $help_staff; ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

    <div class="container-fluid">

        <div class="row row-help">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="jumbotron" style="margin-top: 100px;">
                    <?php echo $com_video; ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <video height="400px" width="600px" controls src="data/videos/cut.webm">Ici la description alternative</video>
            </div>
        </div>

    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
