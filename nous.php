<?php
include 'script/db.php';
include 'head.php';
head('Notre Labo');
?>

<body>
<?php include 'nav.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id='map-container'>
            <div id="labmap"></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="jumbotron" id='verbotron'>
                <h1>A propos de nous</h1>
                <hr>
            <p>&emsp; Le laboratoire science exchange étudie les animaux depuis
                1987 et se situe au ... Nos chercheurs avaient l'habitude
                d'échanger leur données avec des confrères d'autres laboratoires
                mais aussi de zoos ou autres complexe animaliers. Ceci impliquait
                un travail fastidieux, le format des données n'étant pas
                forcément adapté aux échanges par mail par exemple.
            </p>
            <p>&emsp;Ils ont alors fait appel à notre équipe de webmasters pour
                leur construire un site capable de faciliter cet échange et de
                rendre la restitution des données la plus simple possible à
                travers son interface graphique. Seuls les chercheurs acrédités
                peuvent rajouter, supprimer ou simplement modifier la base de
                données. Mais les informations sont accessibles à tous ! Ainsi
                vous pouvez par exemple chercher tous les individus appartenant
                à l'espèce singe, ou bien découvrir que le poids moyen
                d'un lion faisant parti de nos laboratoires est de 203 kilos !
            </p>
            <p>
                &emsp;N'hésitez pas à découvrir toutes les fonctionnalités
                qu'offre notre site et profitez au maximum de votre expérience
                grâce à la section aide !
            </p>
                <a href="help.php" type="button" class="btn btn-success"
                style="text-align:center;">Help !</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript" charset="utf-8">
    var contwidth = $('#map-container').width();
    var contheight = $('#verbotron').height();
    document.getElementById('labmap').style.width = contwidth;
    document.getElementById('labmap').style.height = contheight;
    var labmap = L.map('labmap').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    subdomain: ['a', 'b', 'c']
    }).addTo(labmap);
<?php
$facspecs = get_values(array('name', 'gnss_coord', 'type'), 'Facility');
foreach ($facspecs as $facility) {
    if ($facility['gnss_coord'] != null)
    {
        $latlong = explode(',', $facility['gnss_coord']);
        $type = $facility['type'];
        $name = $facility['name'];
        echo "var marker = L.marker([$latlong[0], $latlong[1]]).addTo(labmap);";
        echo "marker.bindPopup(\"<b>$name</b><br>$type\");";
    }
}
?>
</script>
</body>
