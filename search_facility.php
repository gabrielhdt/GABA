<?php include "db.php";
include "form_func.php";
include "head.php";
$id_biname = array();
$lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}

$col = array('idFacility', 'fa_name', 'nfoll');
$labels = array('Identifier', 'Facility name', 'Number of followed individuals');

$fields = <<<FLD
Facility.idFacility, Facility.name AS fa_name, gnss_coord
COUNT(Followed.idFollowed) as nfoll
FLD;
$tables = 'Facility, Followed';
$where['str'] = 'Facility.idFacility=Followed.idFacility';
$where['valtype'] = array();
$groupby = 'Facility.idFacility';
$having = array();

if (isset($_POST['idspecies']))
{
    $len = count($_POST['idspecies']);
    $tables = 'Facility, Followed, Species';
    $where['str'] = <<<WHR
Followed.idFacility = Facility.idFacility AND
Species.idSpecies = Followed.idSpecies AND
Followed.idSpecies IN
WHR;
    $where['str'] .= ' ('.implode(', ', array_fill(0, $len, '?')).')';
    foreach ($_POST['idspecies'] as $idsp)
    {
        array_push($where['valtype'],
            array('value' => $idsp, 'type' => PDO::PARAM_INT)
        );
    }
}

$facspecs = get_values_light($fields, $tables, $where, $groupby, $having);
echo !$facspecs ? "Error while querying" : null;
?>

<!DOCTYPE HTML>
<html>
<?php head('Recherche bâtiment'); ?>
<body>
<?php include "nav.php"; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='map-container'>
            <div id="labmap"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='map-container'>
            <form action="search_facility.php" method="post" accept-charset="utf-8"
                enctype="multipart/form-data">
                <div class="form-group">
                    <label for="sel_species">Having species:</label>
                    <select name="idspecies[]" id="sel_species" class="form-control" multiple>
                    <?php create_choice_list($id_biname); ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Search facility</button>
            </form>
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
