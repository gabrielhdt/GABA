<?php
// TODO: penser à mettre à jour les liens des pages
?>

<?php
session_start();

function current_nav() {
    /************************
    fonction qui gère la les différentes navbar en fonction de la
    page consultée et de la connexion ou non de l'utilisateur
    (indentation pour une meilleur lisibilité du code HTML)
    ************************/
    $links = array('index.php', 'labo.php', 'recherche.php', 'index.php/#contact', 'help.php');
    $menu_text = array('Accueil', 'Notre Labo', 'Recherche', 'Contact', 'Help');

    // on cherche le nom de la page en cours
    $page_name = substr( $_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strrpos($_SERVER['PHP_SELF'],'.php')-1);
    // debut du nav
    $nav = "<ul class='nav navbar-nav'>\n";

    for ($i=0; $i < 5; $i++) {
        if ($i == 2) {
            // test si l'une des pages de la liste recherche est en cours de consultation
            if ($page_name == 'recherche.php' || $page_name == 'especes.php'
            || $page_name == 'individu.php' ||$page_name == 'batiment.php' ||$page_name == 'chercheur.php') {
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
            if ($page_name == $links[$i]){
                $nav .= "    <li class='active'><a href='".$links[$i]."'>".$menu_text[$i]."</a></li>\n";
            } else {
                $nav .= "    <li><a href='".$links[$i]."'>".$menu_text[$i]."</a></li>\n";
            }
        }
    }
    // test si l'utilisateur n'est pas connecté, seulement onglet 'connexion' à droite
    if (!isset($_SESSION['login'])) {
        if ($page_name == 'login.php'){
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
    // test si l'utilisateur est connecté, onglets 'espace perso' et 'déconnexion' à droite
    } else {
        if ($page_name == 'membre_index.php'){
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
