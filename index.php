<?php
session_start();

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/index_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/index_en_UK.php');
}
//fin du script d'origine

include 'head.php';
head("OWL", $lang);
?>


<body>
    <?php include 'nav.php'; ?>
    <div class="container" style="background-image: url('data/pics/unordered/owl3.jpg');">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
            <div class="description outer">
                <div class="middle">
                    <?php echo $about_us_title; ?>
                    <?php echo $about_us; ?>
                    <a href="nous.php" type="button" class="btn btn-success"><?php echo $know_more; ?></a>
                </div>

            </div>
        </div>
    </div>

    <!-- Define the Caroussel -->
    <div id="theCarousel" class="carousel slide" data-ride="carousel">
        <!-- Define how many slides to put in the carousel -->
        <ol class="carousel-indicators">
            <li data-target="#theCarousel" data-slide-to="0" class="active"> </li>
            <li data-target="#theCarousel" data-slide-to="1"> </li>
            <li data-target="#theCarousel" data-slide-to="2"> </li>
        </ol>

        <!-- Define the text to place over the image -->
        <div class="carousel-inner">
            <div class="item active">
                <div class="slide1"></div>
                <div class="carousel-caption">
                    <?php echo $didyouknow ?>
                    <?php echo $carousel_1 ?>
                    <form action="search_followed.php" method="post">
                        <input type="hidden" name="lowbirth"  readonly
                            value="<?php echo(date('Y') . '-00-00') ?>">
                        <button type="submit" class="btn btn-success">
                            <?php echo $btn_crs1 ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="item">
                <div class="slide2"></div>
                <div class="carousel-caption">
                    <?php echo $didyouknow ?>
                    <?php echo $carousel_2 ?>
                    <form action="search_species.php" method="post">
                        <button type="submit" class="btn btn-success">
                            <?php echo $btn_crs2 ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="item">
                <div class="slide3"></div>
                <div class="carousel-caption">
                    <?php echo $didyouknow ?>
                    <?php echo $carousel_3 ?>
                    <form action="followed.php?id=1" method="post">
                        <button type="submit" class="btn btn-success">
                            <?php echo $btn_crs3 ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Set the actions to take when the arrows are clicked -->
        <a class="left carousel-control" href="#theCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"> </span>
        </a>
        <a class="right carousel-control" href="#theCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>
    <div class="container" style="background-image: url('data/pics/unordered/livre.jpg');">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
            <div class="description outer">
                <div class="middle">
                    <?php echo $needhelp_title; ?>
                    <?php echo $needhelp; ?>
                    <a href="help.php" type="button" class="btn btn-success"><?php echo $help; ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class='row'>
            <div id="contact" class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <?php echo $contactus_tilte; ?>
                <?php echo $contactus; ?>
                <form>
                    <input type="text" name="name"
                        placeholder="<?php echo $ctc_name ?>">
                    <input type="email" name="email"
                        placeholder="<?php echo $ctc_email ?>">
                    <textarea name="msg" rows="8" cols="80"
                        placeholder="<?php echo $ctc_mess ?>"></textarea>
                </form>
                <button onclick="addMsg()" class="btn btn-success"
                    name="submit_contact">
                    <?php echo $ctc_send ?>
                </button>
                <div id='res_msg'>
                    <!--message envoi de msg-->
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
