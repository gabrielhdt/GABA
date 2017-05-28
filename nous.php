<?php
session_start ();
if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/aboutus_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/aboutus_en_UK.php');
}

include 'script/db.php';
include 'head.php';
head($head_title, $lang);
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
                <?php echo $about_us_title; ?>
                <hr>
                <?php echo $about_us; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script type="text/javascript" charset="utf-8">
    var contwidth = $('#map-container').width();
    var contheight = $('#verbotron').outerHeight();
    document.getElementById('labmap').style.width = contwidth;
    document.getElementById('labmap').setAttribute('style',
        'height:' + contheight + 'px');
    var labmap = L.map('labmap').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    subdomain: ['a', 'b', 'c']
    }).addTo(labmap);
<?php
$facspecs = get_values('name, gnss_coord, type', 'Facility');
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
</html>
