<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "form_func.php";
include "head.php";

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
                    <label for="low_nfoll">Having more followed individuals than:
                    <input type="number" name="low_nfoll" class="form-control" id="low_nfoll">
                    <label for="up_nfoll">Having fewer followed individuals than:
                    <input type="number" name="up_nfoll" class="form-control" id="up_nfoll">
                </div>
                <button type="submit" class="btn btn-default">Rechercher animal</button>
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

if (isset($_POST['low_nfoll']) && !empty($_POST['low_nfoll']))
{
    $having['str'] = 'COUNT(Followed.idFollowed)>=?';
    $having['valtype'] = array(
        array('value' => $_POST['low_nfoll'], 'type' => PDO::PARAM_STR)
    );
}
if (isset($_POST['up_nfoll']) && !empty($_POST['up_nfoll']))
{
    $tmp_str = 'COUNT(Followed.idFollowed)<=?';
    $having['str'] = isset($having['str']) ?
        $having['str'] . ' AND '.$tmp_str : $tmp_str; 
    $tmp_hv = array(
        array('value' => $_POST['low_nfoll'], 'type' => PDO::PARAM_STR)
    );
    $having['valtype'] = isset($having['valtype']) ?
        array_merge($having['valtype'], $tmp_hv) : $tmp_hv;
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
