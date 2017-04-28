<!DOCTYPE html>
<html lang="fr">
<?php include 'db.php' ?>
<?php include "head.php" ?>
<body>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </button>
            <a class="navbar-brand" href="#"><img id="logo" src="data/pics/unordered/logo.png" alt=""></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Accueil</a></li>
                <li><a href="#">Notre labo</a></li>
                <li> <a href="#">Recherche</a>
                    <ul class="dropdown-menu">
                        <li class='rechercher'><a href="#">Espèce</a></li>
                        <li class='rechercher'><a href="#">individu</a></li>
                        <li class='rechercher'><a href="#">bâtiment</a></li>
                        <li class='rechercher'><a href="#">chercheur</a></li>
                    </ul>
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

<div class="container3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-4">
        <div class="description">
            <div class="form">
                <div id="contact">
                    <h1>Ajouter un individu</h1>
                    <p>Remplissez le formulaire ci-dessous pour compléter notre base de donnée.</p>
                    <p>Chaque contribution nous permet de vous offrir un service de meilleur qualité.</p>
                    <form action="addfollowed.html" method="post">
                        <input type="text" name="species" placeholder="Espèce*">
                        <input type="text" name="gender" placeholder="Sexe*">
                        <input type="text" name="birth" placeholder="Date de naissance*">
                        <input type="text" name="health" placeholder="Etat de santé*">
                        <button class="btn btn-success" type="submit" name="submit_contact">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br><br>

<footer>
    <ul>
        <li class="active"><a href="#">Accueil</a></li>|
        <li><a href="#">Notre labo</a></li>|
        <li><a href="#">Espèce</a></li>|
        <li><a href="#">Individu</a></li>|
        <li><a href="#">Bâtiment</a></li>|
        <li><a href="#">Chercheur</a></li>|
        <li><a href="#">Help</a></li>
    </ul>
    &copy All rights reserved GABA
    <br>
    <div class="footer-up">
        <a href="#mynav">Up <span class="glyphicon glyphicon-chevron-up"></span></a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php include "footer.php" ?>
</body>
</html>