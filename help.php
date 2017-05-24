<?php
session_start ();

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

//script d'origine
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/help_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/help_en_UK.php');
}
//fin du script d'origine

include 'head.php';
head("Desoin d'aide ?", $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="container-fluid">
        <div class="jumbotron">

            <div class="row  row-help">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo $feature_title; ?>
                    <hr>
                </div>
            </div>

            <div class="row  row-help">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                    <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Espace login" />
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p1; ?>
                </div>
            </div>

            <div class="row row-help">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p2; ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Espace Recherche" />
                </div>

            </div>

            <div class="row row-help">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Carousel" />
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p3; ?>
                </div>
            </div>

            <div class="row row-help">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php echo $feature_p4; ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Nous contacter" />
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid" style="text-align:center">

        <div class="jumbotron">
            <div class="row row-help">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <p>
                            &emsp;Vous trouverez dans la vidéo ci-contre un aperçu des fonctionnalités citées précedemment
                            et comment les utiliser au mieux.
                        </p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <iframe height="400px" width="600px" src="https://www.youtube.com/embed/EreZNkWzBAw" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <div class="jumbotron">
            <div class="row row-help">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h1>Webmaster</h1>
                        <p id="citation">
                            <i>"Rome ne s’est pas faite en un jour."</i>
                        </p>
                        <p>
                            &emsp;Passez votre souris sur les têtes de nos Webmasters pour voir apparaître leur prénoms et cliquez
                            dessus afin d'en apprendre plus sur les personnes ayant participer à la création de ce site !
                        </p>

                        <p>
                            &emsp;Notre équipe vous souhaite de passer un agréable moment sur notre site et espère réveiller l'animal
                            qui est en vous !
                        </p>
                    </div>
            </div>

            <div class="row row-help">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <a href="#" data-toggle="modal" data-target="#modalGabriel"
                               class="photo_cv col-lg-3 col-md-3 col-sm-6 col-xs-12"
                               style="background: url('data/pics/unordered/Au.jpg') center/cover no-repeat;">Gabriel</a>
                            <a href="#" data-toggle="modal" data-target="#modalAdrien"
                               class="photo_cv col-lg-3 col-md-3 col-sm-6 col-xs-12"
                               style="background: url('data/pics/unordered/Au.jpg') center/cover no-repeat;">Adrien</a>
                        <!-- </div> -->
                        <!-- <div class="row"> -->
                            <a href="#" data-toggle="modal" data-target="#modalBenoit"
                               class="photo_cv col-lg-3 col-md-3 col-sm-6 col-xs-12"
                               style="background: url('data/pics/unordered/Au.jpg') center/cover no-repeat;">Benoit</a>
                            <a href="#" data-toggle="modal" data-target="#modalAurelie"
                               class="photo_cv col-lg-3 col-md-3 col-sm-6 col-xs-12"
                               style="background: url('data/pics/unordered/Au.jpg') center/cover no-repeat;">Aurélie</a>
                        <!-- </div> -->
                    </div>
                </div>
            </div>


    </div>
    </div>

        <!-- </div> -->

        <!-- Nos modals de présentations -->

        <div id="modalGabriel" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Gabriel" />
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                            <h4 class="modal-title">
                                <p>
                                    <b>Nom commun :</b> Hondet <br>
                                    <b>Prénom :</b> Gabriel <br>
                                    <b>Surnom :</b> <i>Gaby</i>,
                                    <i>mihomme-mithé</i>, <i>Le managé</i>
                                    <br><b>Date naissance :</b> 21/08/96 <br>
                                    <b>Date décés :</b> 12/03/2017 (intoxication suite à l'ingestion
                                    d'un paupiette de veau périmée depuis 1970)
                                </p>

                                <p>
                                    <b>Citation préférée :</b> "Tout à fait", "Chépô".
                                </p>
                            </h4>
                        </div>
                    </div>

                    <div class="modal-body">
                        <h1>Description</h1>
                        <p>
                            Expert en jeux de mots, il mettra moins de temps pour vous sortir un palindrome
                            qu'à vous dire s'il veut ou non qu'on lui serve de l'eau. Eau qui est d'ailleurs
                            servie à la cantine dans des carafes, information qu'il faut retenir car si vous utilisez
                            un autre terme pour ce contenant vous risquez de mettre notre Gaby dans une colère noire.
                            Pourtant cet homme est très calme la plupart du temps, le flegme anglais l'a surement envahi
                            avec tout le thé qu'il boit. Très charitable,
                            vous pourrez toujours passer prendre un teatime au QG sous une seule condition : savoir si
                            vous preférez le thé indien ou chinois.
                        </p>
                        <p>
                            La vie serait beaucoup plus éclatante selon lui si tout était comme un terminal d'ordinateur.
                            Les interfaces graphiques le répugnent, pourquoi cliquer sur des icônes quand une commande peut
                            le faire ? Ne lui parlez surtout pas de Windows, ça serait comme
                            parler du libéralisme à un communiste.
                        </p>
                        <p>
                            Grand adepte de la cantine (il a tellement de pouvoir dans ce lieu sacré que les cuistots l'ayant
                            contrarié n'ont plus été aperçus depuis ...), il n'a pas trouvé utile d'allumer son frigo, ses réserves
                            de nourritures se limitant a 12 pots de miel, 34
                            paquets de &copy;LU Thé et bien sur ses precieux bocaux de thé. Devenu équilibriste depuis qu'Aurélie lui
                            a cassé son tabouret (sans faire exprès hein..) il peut manger sur trois pieds et même ranger tous ses
                            paquets de gateaux
                            sans tomber.
                        </p>
                        <p>
                            Un de ses seuls défauts est de prôner un Dieu mystique et inconnu dont la forme spirituelle serait celle
                            d'un chameau (ceci explique en grande partie tous les chameaux se trouvant sur le site, l'équipe lui
                            ayant accordé ce droit avant qu'il ne pique une
                            crise). Parler d'un dromadaire devant lui est blasphématoire. Mais il ne faut pas lui en vouloir, il est
                            sous la "grande" influence du Manager qui pervertit son esprit, Gaby boit ses paroles avec autant de délice
                            que son précieux
                            thé.
                        </p>
                        <p>
                            Principal travail pour le site : Faire les trucs pas jolis (en tout cas pas du cosmétique ...).
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="modalAdrien" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Adrien" />
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                            <h4 class="modal-title">
                                <p>
                                    <b>Nom commun :</b> Lancelon <br>
                                    <b>Prénom :</b> Adrien <br>
                                    <b>Surnom :</b> <i>DriDri</i>, <i>Le codeur inexpressif</i> <br>
                                    <b>Date naissance :</b> 17/10/96 <br>
                                    <b>Date décés :</b> 07/05/2017 (attaqué par une friteuse à churros lors des campagnes assos)
                                </p>
                                <p>
                                    <b>Citation préférée :</b> "Sé donde vives..."
                                </p>
                            </h4>
                        </div>
                    </div>

                    <div class="modal-body">
                        <h1>Description</h1>
                        <p>
                            Issu des montagnes Grenobloises, la légende dit qu'il dort parfois dans la neige.
                            Ce petit DriDri comme on le surnomme affectueusement a des horaires décalés : il vit la
                            nuit et se repose le jour, ce qui lui vaut souvent d'être "pile à l'heure" en cours.
                            Son rythme particulier lui a permis d'avoir trouvé une astuce imparable durant les campagnes
                            assos : ne pas dormir et enchaîner les perms, rien ne lui fait peur, pas même Aurélie (et c'est
                            pas faute d'avoir essayé pourtant
                            ...).
                        </p>
                        <p>
                            Handballeur hors pair, il aime le contact et ses adversaires se souvienennt longtemps de ses
                            "câlins". Polyglotte reconnu (grâce aux 172828 séries qu'il regarde), il ne sait même plus en
                            quelle langue il vous parle, n'hésitez pas à lui rappeler de revenir
                            au français lorsqu'il commence à philosopher en espagnol. Un de ses (nombreux) talents cachés est
                            le dessin. Vous serez surpris par la poésie qu'il en ressort ainsi que la ressemblance avec la réalité,
                            même si au lieu de signer
                            ses oeuvres le "codeur inexpressif" préfère mettre un peu de lui dans ses dessins.
                        </p>
                        <p>
                            Vivant en symbiose avec l'homme mihomme-mithé, les séparer ? personne n'y est arrivé : ensemble en
                            td, en anglais, en be_stats, en be_web, en projet python,... une histoire évidente aux yeux de tous.
                            Cet homme a besoin de protéines pour nourrir ses muscles
                            en acier qu'il n'hésitera pas à vous montrer à la moindre occasion, comme ses blessures de guerre du
                            Handball, presque aussi grosses que les bleus d'Aurélie.
                        </p>
                        <p>
                            Orateur exceptionnel (enfin si Gaby est là pour traduire...) il sait aussi se taire et trouvera toujours
                            le gif adapté à chaque situation. En effet, il possède beaucoup d'imagination et peut s'inventer plusieurs
                            vies, Malek ancien bénévole d'Airexpo pourra
                            en témoigner. Son imagination lui a permis de poster une magnifique affiche pour le concours organisé par
                            Airexpo , concours qu'il aurait d'ailleurs sûrement gagné s'il n'y avait pas eu de triche. Vous pouvez
                            d'ailleurs venir
                            manifester avec nous devant l'ENAC le 10 mai pour que l'affiche du petit DriDri soit rétablie à sa juste
                            valeur.

                        </p>
                        <p>
                            Principal travail pour le site : Trouver des jolies images.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="modalBenoit" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <img src="data/pics/unordered/owl3.jpg" class="img-responsive" alt="Benoit" />
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                            <h4 class="modal-title">
                                <p>
                                    <b>Nom commun :</b> Viry <br>
                                    <b>Prénom :</b> Benoît <br>
                                    <b>Surnom :</b> <i>Le manager</i><br>
                                    <b>Date naissance :</b> 20/08/96 <br>
                                    <b>Date décés :</b> 21/03/2017 (a voulu rejoindre le grand chameau plus tôt que prévu)
                                </p>
                                <p>
                                    <b>Citation préférée :</b> "On verra on verra", "D'accord d'accord", "Trés bien trés bien", "Ok ok",...
                                    (et toutes autres mots évoquant l'agrément répétés 2 fois)
                                </p>
                            </h4>
                        </div>
                    </div>

                    <div class="modal-body">
                        <h1>
                        Description
                    </h1>
                        <p>
                            Premier (et unique ?) grand fervent adepte du (pseudo-dieu) Grand Chameau,
                            il serait le messi qui récupère l'argent des fidèles du GC. Pour l'instant seul
                            Le Managé a donné sa côtisation, mais d'après ses dires les adeptes devraient affluer d'ici quelques
                            temps (vous trouverez d'ailleurs plus bas un formulaire de don...).
                        </p>
                        <p>
                            D'un calme inébranlable, il sait rester impassible devant toutes les grimaces d'Aurélie
                            (pourtant extrêmement étudiées). Toutefois, une chose peut le contrarier fortement : son
                            reveil le matin (surtout lorsqu'il y a un cours d'anglais à 8h). En effet,
                            pas moins de 12h de sommeil sont requises pour ce grand bébé, chaque heure non complétée
                            devra être compensée par un litre de café sinon.
                        </p>
                        <p>
                            Seulement 3 choses sont nécessaires pour lui dans la vie : dormir, respirer et manger. Pouvant
                            se nourrir exclusivement de kébabs (le patron de son kébab préféré lui dit même bonjour) et de
                            pizza de la Grande Pizzeria, Dridri a d'ailleurs eu le regret
                            de le voir manger plus de pizzas que lui, il est le client qui a commandé le plus de nourriture
                            à la Tex'asso lors des campagnes.
                        </p>
                        <p>
                            La légende raconte qu'il aurait quand même un coeur. Sa relation avec Le Managé est très fusionnelle,
                            même si DriDri veille au grain. Il a deja tenté de le corrompre en parlant du GC, mais a échoué à briser
                            leur petit couple. Sa nouvelle astuce, qui consiste
                            à parler en php avec Gaby, semble mieux fonctionner : cet être innocent compte le suivre dans son parcours
                            ENAC en se dirigeant vers le Master RO.
                        </p>
                        <p>
                            Ses muscles saillants ne sont pas son seul atout, il est aussi pourvu d'un humour de qualité supérieure
                            ("comme le jambon") qu'il sait bien utiliser lorsque la petite Sudiste commence à s'emporter, faisant
                            descendre la tension d'un seul coup.
                        </p>
                        <p>
                            Principal travail pour le site : On cherche encore... (en tout cas il fait des commits d'exception)
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="modalAurelie" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <img src="data/pics/unordered/Au.jpg" class="img-responsive" alt="Aurélie" />
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                            <h4 class="modal-title">
                                <p>
                                    <b>Nom commun :</b> Bornot <br>
                                    <b>Prénom :</b> Aurélie <br>
                                    <b>Surnom :</b> <i>La Sudiste</i>, <i>La Chef</i><br>
                                    <b>Date naissance :</b> 08/04/96 <br>
                                    <b>Date décés :</b> 06/02/2017 (est tombée de la rembarde en voulant suivre un papillon)
                                </p>
                                <p>
                                    <b>Citation préférée :</b> "Putaing", "Heing ?" (et tout autre locution rappelant
                                    son environnement naturel)
                                </p>
                            </h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <h1>
                        Description
                    </h1>
                        <p>
                            Issue des contrées sauvages de Béziers, elle vous rappelera qu'elle vient de là-bas
                            au moins - à lire moinSSSS - 3000 fois par jour. Aucune exagération bien sur, même si
                            elle possède un "petit" côté marseillais, ne lui dites surtout pas que son accent
                            (si charmant soit dit en passant) provient de cette ville, elle commencera à piquer une
                            crise. De toute façon elle pique une crise pour tout et n'importe quoi, son sang chaud en
                            est la cause principal.
                        </p>
                        <p>
                            Mais cet emportement est aussi dû au fait que cette Sudiste ne contrôle pas du tout ses
                            passions et son gradient d'émotion est très élevé : elle peut passer de la colère, à la
                            tristesse, au dégoût, à la joie, à la peur en moins -moinSSSS- de 10 secondes.
                            Sûrement des résidus de ses années passées à faire du théâtre dans sa jeunesse, années qui
                            ne lui auront tout de même pas permises de feindre l'intimidation, ressemblant plus à Bambi
                            qu'à Rocky.
                        </p>
                        <p>
                            Durant les grandes périodes de froid (qui s'étendent selon elle de septembre à juin), son
                            chauffage d'appoint (appelé affectueusement "soufflant") est son plus grand ami. Ainsi lors
                            de ces périodes impossible de lui faiee faire le moindre effort physique
                            elle restera emmitouflée dans ses 12 couches de vêtements sous la couette. Et en août il fait
                            trop chaud pour faire du sport. Ainsi si vous la voyez faire une activité physique c'est surêment
                            dû à une grave maladie, merci par
                            avance de prévenir le SAMU.
                        </p>
                        <p>
                            Maladroite invétérée, ne lui confiez jamais un objet en verre, en carton, en plastique, en bois
                            ou même en acier, elle parviendra toutjours à le casser "sans faire exprès", le tabouret et la
                            théière de Gaby en sont des preuves irréfutables... Féministe
                            par principe, elle n'hésitera pas à défendre la cause féminine même lorsqu'il n'y a aucun affront,
                            juste par principe et pour rappeler dans quel monde machiste nous vivons.
                        </p>
                        <p>
                            Principal travail pour le site : Les magnifiques textes si savamment étudiés (c'était forcément un
                            travail de femmes...)
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
