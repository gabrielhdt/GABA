<?php
if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

//script d'origine
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/index_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/index_en_UK.php');
}
//fin du script d'origine

//définition de la durée du cookie (1 an)
$expire = 365*24*3600;

//enregistrement du cookie au nom de lang
setcookie('lang', $lang, time() + $expire);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include 'head.php';
    head("GABA");?>
</head>


<body>
    <?php include 'nav.php'; ?>
    <div class="container" style="background-image: url('data/pics/unordered/owl3.jpg');">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
            <div class="description">
                <div class="middle">
                    <h1>A propos de nous ...</h1>
                    <?php echo $about_us ?>
                    <a href="nous.php" type="button" class="btn btn-success">En savoir plus</a>
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
                    <h1>Le saviez-vous ?</h1>
                    <p>Au cours de l'année, 3 faons sont nés au sein de nos laboratoires.</p>
                    <p class="legende">Notre site vous permet de découvrir le nombre d'individus de l'espèce de votre choix qui sont nés au sein de notre laboratoire durant la période demandée.</p>
                    <a href="#" type="button" class="btn btn-success">Chercher d'autres naissances</a>
                </div>
            </div>
            <div class="item">
                <div class="slide2"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans nos laboratoires, les lions pèsent en moyenne 103 kilos.</p>
                    <p class="legende">Notre site vous permet de découvrir le poids moyen des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-success">Chercher d'autres statistiques</button>
                </div>
            </div>
            <div class="item">
                <div class="slide3"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans nos laboratoires, les manchots mesurent en moyenne 1,30m.</p>
                    <p class="legende">Notre site vous permet de découvrir la taille moyenne des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-success">Chercher d'autres statistiques</button>
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
            <div class="description">
                <div class="middle">
                    <h1>Besoin d'aide ?</h1>
                    <p>Afin de vous faire profiter pleinement de toutes les possibilités qu'offre notre site, nous avons créer un manuel d'utilisation pour vous permettre de découvrir et de manipuler avec plus d'aisance toutes les fonctionnalités mises
                        en ligne.</p>
                    <p>C'est aussi ici que vous trouverez la page Webmaster, donnant de plus amples informations sur les constructeurs du site.</p>
                    <a href="help.php" type="button" class="btn btn-success">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class='row'>
            <div id="contact" class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <h1>Nous contacter</h1>
                <p>Vous voulez communiquer avec un de nos chercheurs ? Lui poser des questions sur ses recherches et données ? Vous n'avez qu'à communiquer vos contacts et nous vous mettrons en relation rapidemment.</p>
                <p>N'hésitez pas non plus à faire part de vos remarques et suggestions dans le but d'améliorer l'utilisation du site.</p>
                <p>(* champs obligatoires)</p>
                <form>
                    <input type="text" name="name" placeholder="Votre nom*">
                    <input type="email" name="email" placeholder="Votre e-mail*">
                    <textarea name="msg" rows="8" cols="80" placeholder="Votre message*"></textarea>
                </form>
                <button onclick="addMsg()" class="btn btn-success" name="submit_contact">Envoyer le message</button>
                <div id='res_msg'>
                    <!--message envoi de msg-->
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
