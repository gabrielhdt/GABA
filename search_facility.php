<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "form_func.php";
include "head.php";
head('Recherche bâtiment');

$id_biname = array();
$lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
?>
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
<?php
$col = array('idFacility', 'fa_name', 'nfoll');
$labels = array('Identifier', 'Facility name', 'Number of followed individuals');

$fields = <<<FLD
Facility.idFacility, Facility.name AS fa_name,
COUNT(Followed.idFollowed) as nfoll
FLD;
$tables = 'Facility, Followed';
$where['str'] = 'Facility.idFacility=Followed.idFollowed';
$where['valtype'] = array();
$groupby = 'Facility.idFacility';
$having = array();

if (isset($_POST['idspecies']))
{
    $len = count($_POST['idspecies']);
    $where['str'] .= ' AND Followed.idSpecies IN ';
    $where['str'] .= '('.implode(', ', array_fill(0, $len, '?')).')';
    $where['valtype'] = array();
    foreach ($_POST['idspecies'] as $idsp)
    {
        array_push($where['valtype'],
            array('value' => $idsp, 'type' => PDO::PARAM_INT)
        );
    }
}

$search_res = get_values_light($fields, $tables, $where, $groupby, $having);
echo !$search_res ? "Error while querying" : null;
?>
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
if (isset($_POST['species']))
{
    $fields = 'name, gnss_coord, type, COUNT(Followed.idFollowed)';
    $tables = 'Facility, Followed, Species';
    $where['str'] = <<<WHR
Followed.idFacility = Facility.idFacility AND
Species.idSpecies = Followed.idSpecies AND
idSpecies=?
WHR;
    $where['valtype'] = array(
        array('value' => $_POST['idspecies'], 'type' => PDO::PARAM_STR)
    );
}
$facspecs = get_values_light('name, gnss_coord, type', 'Facility');
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
</html>
