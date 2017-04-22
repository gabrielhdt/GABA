<!DOCTYPE html>
<?php include 'db.php' ?>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <title>GABA V1</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
        integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
         integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
         crossorigin=""></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="glyphicon glyphicon-chevron-down"></span>
          </button>
                <a class="navbar-brand" href="#"><img id="logo" src="image/weblogo.png" alt=""></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Accueil</a></li>
                    <li><a href="#">Notre labo</a></li>
                    <li>
                        <div class="dropdown">
                            <button class="dropbtn">Recherche</button>
                            <div class="dropdown-content">
                                <a href="#">Espèces</a>
                                <a href="#">Individus</a>
                                <a href="#">Bâtiments</a>
                                <a href="#">Chercheurs</a>
                            </div>
                        </div>
                    </li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-7 col-md-offset-7 col-sm-offset-5">
            <div class="description">
                <h1>A propos de nous...</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna
                aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint
                occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</p>
                <div id="labmap" style = "height: 180px"></div>
<?php $facoord = get_values('Facility', 'gnss_coord'); ?>
                <script type="text/javascript" charset="utf-8">
                    var labmap = L.map('labmap').setView([43.13093, -0.45336], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                            subdomain: ['a', 'b', 'c']}).addTo(labmap);
<?php
foreach ($facoord as $coord) {
    $latlong = explode(',', $coord);
    echo "var marker = L.marker([$latlong[0], $latlong[1]]).addTo(labmap);";
}
?>
                </script>
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
                    <p>Au cours de l'année, 3 faons sont nés au sein de notre laboratoire.</p>
                    <p class="legende">Notre site vous permet de découvrir le nombre d'individus de l'espèce de votre choix qui sont nés au sein de notre laboratoire durant la période demandée.</p>
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres naissances.</button>
                </div>
            </div>
            <div class="item">
                <div class="slide2"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans notre laboratoire, les lions pèsent en moyenne 103 kilos.</p>
                    <p class="legende">Notre site vous permet de découvrir le poids moyen des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres statistiques.</button>
                </div>
            </div>
            <div class="item">
                <div class="slide3"></div>
                <div class="carousel-caption">
                    <h1>Le saviez-vous ?</h1>
                    <p>Dans notre laboratoire, les manchots mesurent en moyenne 1,30m.</p>
                    <p class="legende">Notre site vous permet de découvrir la taille moyenne des individus de notre laboratoire de l'espèce de votre choix.</p>
                    <button type="button" class="btn btn-primary btn-sm">Chercher d'autres statistiques.</button>
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
                <p>Afin de cous faire profiter pleinement de toutes les possibilités qu'offre notre site, nous avons créer un manuel d'utilisation pour vous permettre de découvrir et de manipuler avec plus d'aisance toutes les fonctionnalités mises en ligne.</p>
                <p>C'est aussi ici que vous trouverez la page Webmaster, donnant de plus amples informations sur les constructeurs du site.</p>
            </div>
        </div>
    </div>
    <div class="row">

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
    <footer>
        <ul>
            <li><a href="#">Accueil</a> | </li>
            <li><a href="#">Notre labo</a> | </li>
            <li>Recherche :
                <ul>
                    <li><a href="#">Espèces</a></li>
                    <li><a href="#">Individus</a></li>
                    <li><a href="#">Bâtiments</a></li>
                    <li><a href="#">Chercheurs</a></li>
                </ul>
            </li>
            <li><a href="#contact">Contact</a> | </li>
            <li><a href="#">Help</a> | </li>
            <li><a href="#">Connexion</a></li>
        </ul>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
