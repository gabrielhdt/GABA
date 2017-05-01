<?php
// TODO: penser à mettre à jour les liens des pages
?>

<style media="screen">
@media (max-width: 1320px) {
    .navbar-header {
        float: none;
    }
    .navbar-toggle {
        display: block;
    }
    .navbar-collapse {
        border-top: 1px solid transparent;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
    }
    .navbar-collapse.collapse {
        display: none!important;
    }
    .navbar-nav {
        float: none!important;
        margin: 7.5px -15px;
    }
    .navbar-nav>li {
        float: none;
    }
    .navbar-nav>li>a {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .navbar-text {
        float: none;
        margin: 15px 0;
    }
    /* since 3.1.0 */
    .navbar-collapse.collapse.in {
        display: block!important;
    }
    .collapsing {
        overflow: hidden!important;
    }
}
</style>

<?php
session_start();

function current_nav() {
    /************************
    fonction qui gère la les différentes navbar en fonction de la
    page consultée et de la connexion ou non de l'utilisateur
    ************************/
    // definition des liens et du texte a afficher dans la navbar
    $links = array('index.php', 'labo.php', 'recherche.php', 'help.php', 'espece.php', 'individu.php',
                    'batiment.php', 'chercheur.php', 'index.php#contact', 'login.php', 'membre_index.php',
                    'deconnexion.php', 'admin_index.php');
    $text = array('Accueil', 'Notre Labo', 'Recherche', 'Help', 'Espèce', 'Individu', 'Bâtiment', 'Chercheur',
                    'Conctact', 'Connexion', 'Espace Perso', 'Déconnexion');

    // on cherche le nom de la page en cours
    $page_name = substr( $_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strrpos($_SERVER['PHP_SELF'],'.php')-1);
    // debut du nav
    $nav = "<ul class='nav navbar-nav'>\n";

    for ($i=0; $i < 4; $i++) {
        // cas du dropdown-menu, actif si l'une de ses pages est consultée
        if ($i == 2) {
            // test si l'une des pages de la liste recherche est en cours de consultation
            if ($page_name == $links[2] || $page_name == $links[4]
            || $page_name == $links[5] ||$page_name == $links[6] ||$page_name == $links[7]) {
                $nav .= "<li class='active'> <a href='$links[2]'>$text[2]</a>
<ul class='dropdown-menu'>";
            } else {
                $nav .= "<li> <a href='$links[2]'>$text[2]</a>
        <ul class='dropdown-menu'>";
            }
            for ($j=4; $j < 8; $j++) {
                $nav .= "<li><a href='$links[$j]'>$text[$j]</a></li>\n";
            }
            $nav .= "</ul>
</li>
<li><a href='$links[8]'>Contact</a></li>\n"; //cas de l'ancre 'contact'
        // autres liens
        } else {
            if ($page_name == $links[$i]){
                $nav .= "<li class='active'><a href='$links[$i]'>$text[$i]</a></li>\n";
            } else {
                $nav .= "<li><a href='$links[$i]'>$text[$i]</a></li>\n";
            }
        }
    }
    // test si l'utilisateur n'est pas connecté, seulement onglet 'connexion' à droite
    $nav .= "</ul>
<ul class='nav navbar-nav navbar-right'>
<li ";
    if (!isset($_SESSION['login'])) {
        $nav .= ($page_name == $links[9]) ? "class='active'" : "";
        $nav .= "><a href='$links[9]'><span class='glyphicon glyphicon-log-in'></span> $text[9]</a></li>
</ul>";
    // test si l'utilisateur est connecté, onglets 'espace perso' et 'déconnexion' à droite
    } else {
        if ($_SESSION['login'] == 'admin'){
            $nav .= ($page_name == 'admin_index.php') ? "class='active'" : "";
            $nav .= "><a href='$links[12]'><span class='glyphicon glyphicon-log-in'></span> $text[10]</a></li>
<li><a href='$links[11]'><span class='glyphicon glyphicon-log-out'></span> $text[11]</a></li>
</ul>";
        } else {
            $nav .= ($page_name == 'membre_index.php') ? "class='active'" : "";
            $nav .= "><a href='$links[10]'><span class='glyphicon glyphicon-log-in'></span> $text[10]</a></li>
<li><a href='$links[11]'><span class='glyphicon glyphicon-log-out'></span> $text[11]</a></li>
</ul>";
        }
    }
    return $nav;
}
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
            <?php echo current_nav(); ?>
        </div>
    </div>
</nav>
