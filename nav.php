<?php
session_start();

function current_nav() {
    $links = array('index.php', 'labo.php', 'recherche.php', 'index.php/#contact', 'help.php');
    $menu_text = array('Accueil', 'Notre Labo', 'Recherche', 'Contact', 'Help');

    $nav = "<ul class='nav navbar-nav'>\n";
    $nom_page = substr( $_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strrpos($_SERVER['PHP_SELF'],'.php')-1);
    $i = 0;
    for ($i=0; $i < 5; $i++) {
        if ($i == 2) {
            if ($nom_page == 'recherche.php' || $nom_page == 'especes.php' || $nom_page == 'individu.php' ||$nom_page == 'batiment.php' ||$nom_page == 'chercheur.php') {
                $nav .= "    <li class='active'> <a href='#'>Recherche</a>
        <ul class='dropdown-menu'>
            <li class='rechercher'><a href='#'>Espèce</a></li>
            <li class='rechercher'><a href='#'>individu</a></li>
            <li class='rechercher'><a href='#'>bâtiment</a></li>
            <li class='rechercher'><a href='#'>chercheur</a></li>
        </ul>
    </li>\n";
            } else {
                $nav .= "    <li> <a href='#'>Recherche</a>
        <ul class='dropdown-menu'>
            <li class='rechercher'><a href='#'>Espèce</a></li>
            <li class='rechercher'><a href='#'>individu</a></li>
            <li class='rechercher'><a href='#'>bâtiment</a></li>
            <li class='rechercher'><a href='#'>chercheur</a></li>
        </ul>
    </li>\n";
            }
        } else {
            if ($nom_page == $links[$i]){
                $nav .= "    <li class='active'><a href='".$links[$i]."'>".$menu_text[$i]."</a></li>\n";
            } else {
                $nav .= "    <li><a href='".$links[$i]."'>".$menu_text[$i]."</a></li>\n";
            }
        }
    }
    if (!isset($_SESSION['login'])) { // test si l'utilisateur est connecté (navbar différente sinon)
        if ($nom_page == 'login.php'){
            $nav .=
"</ul>
<ul class='nav navbar-nav navbar-right'>
    <li class='active'><a href='login.php'><span class='glyphicon glyphicon-log-in'></span> Connexion</a></li>
</ul>";
        } else {
            $nav .=
"</ul>
<ul class='nav navbar-nav navbar-right'>
    <li><a href='login.php'><span class='glyphicon glyphicon-log-in'></span> Connexion</a></li>
</ul>";
        }
    } else {
        if ($nom_page == 'membre_index.php'){
            $nav .=
"</ul>
<ul class='nav navbar-nav navbar-right'>
    <li class='active'><a href='membre_index.php'><span class='glyphicon glyphicon-log-in'></span> Espace Perso</a></li>
    <li><a href='membre_index.php'><span class='glyphicon glyphicon-log-out'></span> Déconnexion</a></li>
</ul>";
        } else {
            $nav .=
"</ul>
<ul class='nav navbar-nav navbar-right'>
    <li><a href='membre_index.php'><span class='glyphicon glyphicon-log-in'></span> Espace Perso</a></li>
    <li><a href='membre_index.php'><span class='glyphicon glyphicon-log-out'></span> Déconnexion</a></li>
</ul>";
        }
    }
    return $nav;
}
echo nav();
?>


<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="glyphicon glyphicon-chevron-down"></span>
      </button>
            <a class="navbar-brand" href="#"><img id="logo" src="image/logo.png" alt=""></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <?php current_nav() ?>
        </div>
    </div>
</nav>
