<?php
session_start ();

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}


// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/search_facility_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/search_facility_en_UK.php');
}

include "script/db.php";
include "script/form_func.php";
include "head.php";
$id_biname = array();
$lines = get_values('idSpecies, binomial_name', 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}

$col = array('idFacility', 'fa_name', 'nfoll');
$labels = array('Identifier', 'Facility name', 'Number of followed individuals');

$fields = <<<FLD
idFacility, name AS fa_name, gnss_coord
FLD;
$tables = 'Facility';

if (isset($_POST['idspecies']))
{
    $len = count($_POST['idspecies']);
    $fields = <<<FLD
Facility.idFacility, name AS fa_name, gnss_coord, Followed.idSpecies
FLD;
    $tables = <<<TBL
Facility INNER JOIN Followed ON Facility.idFacility=Followed.idFacility
INNER JOIN Species ON Followed.idSpecies=Species.idSpecies
TBL;
    $params['where']['str'] = <<<WHR
Followed.idSpecies IN
WHR;
    $params['where']['str'] .= ' ('.implode(', ', array_fill(0, $len, '?')).')';
    $params['where']['valtype'] = array();
    foreach ($_POST['idspecies'] as $idsp)
    {
        array_push($params['where']['valtype'],
            array('value' => $idsp, 'type' => PDO::PARAM_INT)
        );
    }
    $params['groupby'] = 'Facility.idFacility';
}
if (isset($_POST['low_nfoll']) && !empty($_POST['low_nfoll']))
{
    $fields = <<<FLD
Facility.idFacility, name AS fa_name, gnss_coord, Followed.idSpecies, type
FLD;
    $tables = <<<TBL
Facility INNER JOIN Followed ON Facility.idFacility=Followed.idFacility
INNER JOIN Species ON Followed.idSpecies=Species.idSpecies
TBL;
    $params['groupby'] = 'Facility.idFacility';
    $params['having'] = array();
    $params['having']['str'] = 'COUNT(Followed.idFollowed)>=?';
    $params['having']['valtype'] = array(
        array(
            'value' => $_POST['low_nfoll'], 'type' => PDO::PARAM_INT
        )
    );

}
$facspecs = get_values($fields, $tables, $params);
echo !$facspecs ? "Error while querying" : null;

head('Recherche bâtiment', $lang);
?>

<body>
<?php include "nav.php"; ?>
<hr class="top">
<div class="container-fluid">

    <div class="research row">
        <?php echo $title ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='map-container'>
            <form action="search_facility.php" method="post" accept-charset="utf-8"
                enctype="multipart/form-data"  class="form-inline">
                <div class="form-group" style="width:100%;">
                    <div class="row">
                        <label for="sel_species"><?php echo $species ?></label>
                        <select name="idspecies[]" id="sel_species" class="form-control" multiple>
                        <?php create_choice_list($id_biname); ?>
                        </select>

                        <label for="low_nfoll"><?php echo $nb_species ?></label>
                        <input class="form-control" type="number" name="low_nfoll" id="low_nfoll"
                            placeholder="5, 17, ...">
                    </div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-default"><?php echo $search ?></button>
                </div>
            </form>
        </div>
    </div>

    <hr class="rslt">

    <div class="result-table row">
        <?php echo $result ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='map-container'>
            <div id="labmap"></div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
<script type="text/javascript" charset="utf-8">
    var contwidth = $('#map-container').width();
    document.getElementById('labmap').setAttribute("style",
        "height:" + 0.33*contwidth + "px");
    var labmap = L.map('labmap').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    subdomain: ['a', 'b', 'c']
    }).addTo(labmap);
<?php
foreach ($facspecs as $facility) {
    if ($facility['gnss_coord'] != null)
    {
        $latlong = explode(',', $facility['gnss_coord']);
        $type = $facility['type'];
        $name = $facility['fa_name'];
        echo "var marker = L.marker([$latlong[0], $latlong[1]]).addTo(labmap);";
        echo "marker.bindPopup(\"<b>$name</b><br>$type\");";
    }
}
?>
</script>
</html>
