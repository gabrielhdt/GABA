<?php
// TODO: penser à mettre à jour les liens des pages
// $page_name = substr( $_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strrpos($_SERVER['PHP_SELF'],'.php')-1);
$page_name = substr( $_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/')+1, strrpos($_SERVER['REQUEST_URI'],'.php')-1);

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}
$text_fr = array('Accueil', 'Le projet', 'Recherche', 'Aide', 'Espèce', 'Individu', 'Bâtiment', 'Chercheur',
                'Contact', 'Connexion', 'Espace Perso', 'Déconnexion');
$text_en = array('Home', 'The Project', 'Research', 'Help', 'Species', 'Followed', 'Facility', 'Chercheur',
                'Contact', 'LogIn', 'Your Space', 'LogOut');

function current_nav() {
    /************************
    fonction qui gère la les différentes navbar en fonction de la
    page consultée et de la connexion ou non de l'utilisateur
    ************************/
    // definition des liens et du texte a afficher dans la navbar
    $links = array('index.php', 'nous.php', 'recherche.php', 'help.php', 'search_species.php', 'search_followed.php',
                    'search_facility.php', 'chercheur.php', 'index.php#contact', 'login.php', 'membre_index.php',
                    'logout.php', 'admin_index.php');

    $format = "<li %s><a href='%s'>%s %s</a></li>\n";
    // on cherche le nom de la page en cours
    global $page_name, $lang, $text_fr, $text_en;
    if ($lang == 'fr') {
        $text = $text_fr;
    } elseif ($lang == 'en') {
        $text = $text_en;
    }
    // debut du nav
    $nav = "<ul class='nav navbar-nav'>\n";

    for ($i=0; $i < 4; $i++) {
        // cas du dropdown-menu, actif si l'une de ses pages est consultée
        if ($i == 2) {
            // test si l'une des pages de la liste recherche est en cours de consultation
            $cond = ($page_name == $links[2] || $page_name == $links[4] ||
                     $page_name == $links[5] ||$page_name == $links[6] || $page_name == $links[7]);
            $nav .= "<li ".($cond ? "class='active'" : "")."> <a href='$links[2]'>$text[2]</a>
<ul class='dropdown-menu'>\n";

            for ($j=4; $j < 7; $j++) {
                $nav .= sprintf($format,
                                "",
                                $links[$j],
                                "",
                                $text[$j]
                            );
            }
            $nav .= "</ul>
</li>
<li><a href='$links[8]'>$text[8]</a></li>\n"; //cas de l'ancre 'contact'
        // autres liens
        } else {
            $nav .= sprintf($format,
                            $page_name == $links[$i] ? "class='active'" : "",
                            $links[$i],
                            "",
                            $text[$i]
                        );
        }
    }
    // test si l'utilisateur n'est pas connecté, seulement onglet 'connexion' à droite
    $nav .= "</ul>
<ul class='nav navbar-nav navbar-right'>\n";
    if (!isset($_SESSION['login'])) {
        $nav .= sprintf($format, ($page_name == $links[9]) ? "class='active'" : "",
                        $links[9], "<span class='glyphicon glyphicon-log-in'></span>", $text[9])."</ul>";
    // test si l'utilisateur est connecté, onglets 'espace perso' et 'déconnexion' à droite
    } else {
        if ($_SESSION['login'] == 'admin'){
            $nav .= sprintf($format,
                            $page_name == 'admin_index.php' ? "class='active'" : "",
                            $links[12], "",
                            $text[10]);
        } else {
            $nav .= sprintf($format,
                            $page_name == 'membre_index.php' ? "class='active'" : "",
                            $links[10],
                            "",
                            $text[10]
                        );
        }
        $nav .= sprintf($format,
                        "",
                        $links[11],
                        "<span class='glyphicon glyphicon-log-out'></span>",
                        $text[11]
                    )."</ul>";
    }
    return $nav;
}
?>
<div id="flags">
    <a href="#" onclick="language('fr', '<?php echo $page_name; ?>')"><img class="flag" src="image/drapeau_fr.jpg" alt=""></a>
    <a href="#" onclick="language('en', '<?php echo $page_name; ?>')"><img class="flag" src="image/drapeau_gb.jpg" alt=""></a>
</div>

<nav class="navbar">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
<span class="glyphicon glyphicon-chevron-down"></span>
</button>
<a class="navbar-brand" href="index.php"><img id="logo" src="image/EagleIkon.png" alt=""></a>
</div>
<div class="collapse navbar-collapse" id="myNavbar">
<?php echo current_nav(); ?>
</div>
</div>
</nav>
