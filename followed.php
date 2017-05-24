<?php
session_start ();
include 'script/db.php';
include 'script/form_func.php';
include 'script/graph.php';
include 'script/functionsFollowed.php';

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

//script d'origine
// if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
//     include('i18n/fr_FR/index_fr_FR.php');
// } elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
//     include('i18n/en_UK/index_en_UK.php');
// }
//fin du script d'origine

// Autoriastion de l'edition pour un membre mais pas l'admin
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';
$idstaff = $_SESSION['idstaff'];
$idfollowed = $_GET['id'];

// les fonctions ont été bougé dans script/fucntionsFollewed.php pour plus de place

// Getting information
$fields = <<<FLD
binomial_name, common_name, gender, birth, health, death,
Followed.pic_path AS pic_path, Facility.name AS fa_name,
Followed.annotation, Facility.gnss_coord AS fa_gnss_coord
FLD;
$table = <<<TAB
Followed, Species, Facility
TAB;
$where = array();
$where['str'] = <<<WH
Followed.idSpecies = Species.idSpecies AND
Followed.idFacility = Facility.idFacility AND
Followed.idFollowed=?
WH;
$where['valtype'] = array(
    array('value' => $idfollowed, 'type' => PDO::PARAM_STR)
);
$search_res = get_values($fields, $table, $where)[0];


// Getting last known location
$tables = <<<TBL
Location INNER JOIN Measure ON Measure.idMeasure=Location.idMeasure
TBL;
$where['str'] = 'idFollowed=?';
$where['valtype'] = array(
    array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
);
$last_meas_date = get_values(
    'MAX(date_measure) AS last_meas', $tables, $where
)[0]['last_meas'];

$fields = "latitude, longitude, date_measure";
$table = "Location INNER JOIN Measure ON Location.idMeasure=Measure.idMeasure";
$where = array();
$where['str'] = 'Measure.idFollowed=? AND date_measure=?';
$where['valtype'] = array(
    array('value' => $idfollowed, 'type' => PDO::PARAM_INT),
    array('value' => $last_meas_date, 'type' => PDO::PARAM_STR)
);
$loc = get_values($fields, $table, $where)[0];
$loc_str = $search_res['fa_name'] == 'gaia' ?
    "Last known location: " . $loc['latitude'] .'W ' .
    $loc['longitude'] .'N' : 'At ' . $search_res['fa_name'];
$loc4js = $search_res['fa_name'] == 'gaia' ?
    $loc['latitude'] . ',' . $loc['longitude'] :
    $search_res['fa_gnss_coord'];

// Getting types of measur
$meas_gen = get_values('DISTINCT type, unit', 'MiscQuantity');
function f($line) { return($line['type']); }
function g($line) { return($line['unit']); }
$meas_types = array_map('f', $meas_gen);
$meas_units = array_map('g', $meas_gen);
?>


<?php
include 'head.php';
head(ucfirst($search_res['binomial_name']), $lang);
echo $edit ? '<body onload="get_coords()">' : '<body>';
include 'nav.php';
?>
<div class="container-fluid">
<div class="row">
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <!-- éventuelle photo du followed -->
        <div class="pic">
            <?php
            echo '<img src="'.$search_res['pic_path'].
                '" class = "img-responsive">';
            ?>
        </div>
        <?php if ($edit) { ?>
            <form action="upload_pic.php" method="post" enctype="multipart/form-data" id="upload_pic">
                <input type="hidden" name="id" readonly value="<?php echo $idfollowed ?>">
                <input type="hidden" name="table" readonly value="Followed">
                <input type="file" name="userpic">
                <button type="submit" class="btn btn-default">Upload pic</button>
            </form>
        <?php } ?>
    </div>

<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
    <div id="intel">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="foll_data">
        <!-- informations sur le $idfollowed -->
        <?php
        if ($search_res['common_name'])
        {
            echo '<h1>'.ucfirst($search_res['common_name']).'</h1>';
            echo '<h2>'.ucfirst($search_res['binomial_name']).'</h2>';
        }
        else
        {
            echo '<h1>'.ucfirst($search_res['binomial_name']).'</h1>';
        }
        ?>
        <p>
            <?php echo $search_res['gender'] == 'm' ? 'Male' : 'Female'; ?>
            born on <?php echo date_format(date_create($search_res['birth']), 'jS F, Y') ?>
        <p>
            <?php echo $loc_str;
            if ($edit)
            {
                echo <<<BTN
<div class="btn-group btn-group-xs" role="group"
    aria-label="Update last known location">
    <button class="btn btn-default" type="button"
    onclick="write_geoloc($idfollowed, $idstaff)"
    aria-label="Update with current position">
        <span class="glyphicon glyphicon-map-marker"></span>
    </button>
    <button class="btn btn-default" type="button"
        aria-label="Input new position" data-toggle="modal"
        data-target="#geolocModal">
        <span class="glyphicon glyphicon-pencil"></span>
    </button>
</div>
BTN;
            }
            ?>
        </p>
        <p class="annotation">
            <?php echo $search_res['annotation'] ?
            $search_res['annotation'] : 'Write something about this animal!'; ?>
        </p>
<?php if ($edit) { ?>
        <button type="button" class="btn btn-info btn-lg"
            data-toggle="modal" data-target="#infoModal">
            Modifier la description
        </button>
<?php } ?>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="map-container">
        <!-- Map de localisation -->
        <div id="followed_map"></div>
    </div>

        <h1>Data:</h1>
        <table>
            <thead>
                <tr>
                    <th>Desc.</th>
                    <th>Value</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Author</th>
                </tr>
            </thead>
            <tbody>
                <?php echo meas_table($idfollowed); ?>
            </tbody>
        </table>
<?php if ($edit) { ?>
        <button type="button" class="btn btn-info btn-lg"
            data-toggle="modal" data-target="#addModal">
            Ajouter une mesure
        </button>
<?php } ?>

        <h1>Relationships</h1>
        <table>
            <thead>
                <tr>
                    <th>Identifier</th>
                    <th>Rel. type</th>
                    <th>Begin</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                <?php echo relation_table($idfollowed); ?>
            </tbody>
        </table>
<?php if ($edit) { ?>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal"
            data-target="#addRelationModal">Add a relationship</button>
<?php } ?>
    </div>
</div>
</div>
</div>

<!-- insertion des graphiques pour les measures -->
<?php draw_graphs($idfollowed); ?>

<?php $edit ? include 'script/followed_modals.php' : null; ?>

<?php include 'footer.php'; ?>
<script>
// Getting and setting coordinates in cookie
function get_coords()
{
    navigator.geolocation.getCurrentPosition(coord2cookies);
}
function coord2cookies(position)
{
    document.cookie = 'geoloc=' + position.coords.latitude + ',' +
        position.coords.longitude;
}
function write_geoloc(idfoll, idstaff)
{
    $.post(
        'script/scriptAjax.php',
        {idfollowed: idfoll, geoloc: document.cookie, idstaff: idstaff}
    );
}

// Map management
var contwidth = $('#map-container').width();
var contheight = $('#foll_data').height();
document.getElementById('followed_map').style.width = contwidth;
document.getElementById('followed_map').setAttribute("style",
    "height:" + contheight + "px");
var followed_map = L.map('followed_map').setView(
    [<?php echo $loc4js ?>], 4);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
subdomain: ['a', 'b', 'c']
}).addTo(followed_map);
</script>
</body>
</html>
