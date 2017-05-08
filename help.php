<html lang="fr">


<?php include 'head.php';
head("Help");
?>
<style media="screen">
    .row {
        margin: 30px 0;
    }
    .jumbotron {
        vertical-align: middle;
    }
</style>
<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div class="jumbotron">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1>Fonctionnalités du site</h1>
                    <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Espace login" />
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <p>&emsp;Ce site a été conçu dans l'espoir d'aider les chercheurs de laboratoires animaliers à partager les données des individus dont ils s'occupent. Seuls les chercheurs de nos laboratoires sont acrédités à saisir les informations sur
                        une espèce ou un individu en particulier, ou bien des informations sur les lieux où peuvent se rencontrer ces animaux, dans la base de données. Pour cela, ils doivent rentrer leur identifiant et mot de passe sur la page <a href="login.php">Login</a>.
                    </p>
            </div>
        </div>

        <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <p>
                            &emsp;Notre site a donc un aspect pratique mais aussi un côté ludique pour les personnes qui ne sont pas chercheurs, mais simplement curieuses et qui souhaitent en savoir plus sur les animaux étudiés ! Ces personnes là, même si elles ne peuvent pas la
                            modifier, ont elles aussi accès à la base de données de nos centres animaliers et peuvent rechercher des données d'une espèce, d'un individus, des batiments ou encore des informations sur nos chercheurs grâce à l'onglet
                            <a href="recherche.php">Recherche</a>. Si vous ne savez pas execatement quelles informations regarder, laissez vous happer par les activités proposées au fil des pages !
                        </p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Espace Recherche" />
                </div>

        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Carousel" />
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <p>
                        &emsp;Par exemple, notre site propose de découvrir des statistiques sur le nombre d'individus d'une espèce nés au cours d'une certaine période, ou encore le poids moyen des individus d'une espèce etudiée dans nos laboratoires. Cette activité et proposée
                        en cliquant sur des liens de notre
                        <a href="theCarousel">carrousel</a> en page d'acceuil ! Vous pouvez aussi en apprendre un peu plus sur notre objectif de base, le <i>"pourquoi du comment"</i> du site et le laboratoire à l'origine de tout cela sur notre page
                        <a href="nous.php">Notre Labo</a>.
                    </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <p>
                        &emsp;Enfin, n'hésitez pas à laissez des commentaires sur vos impressions grâce à la section <a href="contact">Contact</a> de la page d'accueil, ou à demander à prendre contact avec certains de nos chercheurs afin d'en apprendre
                        plus sur ses recherches ou sur le metier lui même. Vos coordonnées leur seront transmis par l'intermédiaire du gérant du site pour qu'il puisse vous joindre personnelllement. Cette section commentaire vous permet aussi de nous
                        faire part de vos remarques ou améliorations que l'on pourrait apporter au site pour toujours plus de plaisir et d'aisance lors de la manipulation des pages.
                    </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Nous contacter" />
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="jumbotron" id='verbotron'>
                    <p>
                        &emsp;Vous trouverez dans la vidéo ci-contre un aperçu des fonctionnalités citées précedemment et comment les utiliser au mieux. Notre équipe vous souhaite de passer un agréable moment sur notre site et espère réveiller l'animal qui est en vous !
                    </p>
                </div>
            </div>
        </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="jumbotron" id='verbotron'>
            <h1>Webmaster</h1>
            <p>Le laboratoire science exchange a pour mission de regrouper les informations de multiples laboratoires de recherche animale et de mettre à disposition les données sur nottre plateforme. <br>L'objectif est de faciliter le travail des chercheurs
                mais le site permet aussi d'assouvir votre curiosité de manière ludique ! </p>
        </div>
    </div>
</div>
    <?php include 'footer.php'; ?>
</body>
