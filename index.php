<!DOCTYPE html>
<?php include 'db.php' ?>
<html lang="fr">

<?php include "head.php" ?>
<body>
    <?php include "nav.php" ?>
    <div class="container">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
            <div class="description">
                <h1>A propos de nous...</h1>
                <p>Lorem nim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                    aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <div id = "more"><button type="button" class="btn btn-primary btn-sm">En savoir plus</button></div>
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
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres naissances</button>
                </div>
            </div>
            <div class="item">
                <div class="slide2"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans nos laboratoires, les lions pèsent en moyenne 103 kilos.</p>
                    <p class="legende">Notre site vous permet de découvrir le poids moyen des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres statistiques</button>
                </div>
            </div>
            <div class="item">
                <div class="slide3"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans nos laboratoires, les manchots mesurent en moyenne 1,30m.</p>
                    <p class="legende">Notre site vous permet de découvrir la taille moyenne des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres statistiques</button>
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
    <div class="container2">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
            <div class="description">
                <h1>Besoin d'aide ?</h1>
                <p>Afin de vous faire profiter pleinement de toutes les possibilités qu'offre notre site, nous avons créer un manuel d'utilisation pour vous permettre de découvrir et de manipuler avec plus d'aisance toutes les fonctionnalités mises en ligne.</p>
                <p>C'est aussi ici que vous trouverez la page Webmaster, donnant de plus amples informations sur les constructeurs du site.</p>
            </div>
            <div><button id = 'more' type="button" class="btn btn-primary btn-sm">En savoir plus.</button></div>
        </div>
    </div>
    <div class = 'row'>
        <div id="contact" class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
            <h1>Nous contacter</h1>
            <p>Vous voulez communiquer avec un de nos chercheurs ? Lui poser des questions sur ses recherches et données ? Vous n'avez qu'à communiquer vos contacts et nous vous mettrons en relation rapidemment.</p>
            <p>N'hésitez pas non plus à faire part de vos remarques et suggestions dans le but d'améliorer l'utilisation du site.</p>
            <form action="index.html" method="post">
                <input type="text" name="name" placeholder="Votre nom*">
                <input type="text" name="email" placeholder="Votre e-mail*">
                <textarea name="msg" rows="8" cols="80" placeholder="Votre message*"></textarea>
                <button class="btn btn-primary" type="submit" name="submit_contact">Envoyer le message</button>
            </form>
        </div>
</div>
<div id="labmap" style="height: 180px"></div>
<?php $facspecs = get_values('Facility', array('name', 'gnss_coord', 'type')); ?>
                <script type="text/javascript" charset="utf-8">
                    var labmap = L.map('labmap').setView([90, 0], 2);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                            subdomain: ['a', 'b', 'c']}).addTo(labmap);
<?php
foreach ($facspecs as $facility) {
    $latlong = explode(',', $facility['gnss_coord']);
    $type = $facility['type'];
    $name = $facility['name'];
    echo "var marker = L.marker([$latlong[0], $latlong[1]]).addTo(labmap);";
    echo "marker.bindPopup(\"<b>$name</b><br>$type\");";
}
?>
                </script>
<?php include "footer.php" ?>
</body>
</html>
