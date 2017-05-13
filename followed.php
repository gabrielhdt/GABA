<?php
include 'db.php';
session_start();
// Autoriastion de l'edition pour un membre mais pas l'admin
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';
$idstaff = $_SESSION['idstaff'];
$idfollowed = $_GET['id'];

function graph_type($idFollowed, $type, $idCanevas) {
    $result = get_values(
        array('DISTINCT MiscQuantity.value, MiscQuantity.unit, Measure.date_measure'),
        'MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure = Measure.idMeasure',
        $where=array(array('binrel' => '=', 'field' => 'Measure.idFollowed', 'value' =>  "$idFollowed", 'type' => PDO::PARAM_STR),
                     array('binrel' => '=', 'field' => 'MiscQuantity.type', 'value' =>  "$type", 'type' => PDO::PARAM_STR))); // + and id =, order by date_measure
    $val = array();
    $unit = array();
    $date_measure = array();
    foreach ($result as $key => $value) {
        $val[] = $value["value"];
        $unit[] = $value["unit"];
        $date_measure[] = $value["date_measure"];
    }
    $chart = "
    <script>
    var ctx = document.getElementById('$idCanevas');
    var scatterChart = new Chart(ctx, {
        type: 'line',
        data: {datasets: [
            {borderColor: 'rgba(19, 179, 9, 0.8)',
             backgroundColor: 'rgba(19, 179, 9, 0.3)',
             fill: true,
             label: '$type ($unit[0])',
             data: [";
    for ($i = 0; $i < count($val); $i++){
        $chart .= "{x: '$date_measure[$i]', y: $val[$i]}, ";
    }
    $chart = rtrim($chart, ', ');
    $chart .=  "]}]},
        options: {
            responsive: true,
            responsiveAnimationDuration: 500,
            scales: {xAxes: [{type: 'time'}]}
        }
        });
        </script>";
    echo $chart;
}

function simple_table($lines)
{
    /* Makes a simple table body
     */
    $table = '';
    foreach ($lines as $line)
    {
        $table .= '<tr>';
        foreach ($line as $value)
        {
            $table .= '<td>';
            $table .= ucfirst($value);
            $table .= '</td>';
        }
        $table .= '</tr>';
    }
    return($table);
}

function edi_table($lines, $modal, $arg_edit)
{
    /* js_func the javascript function called for editing,
     * edit_arg, key of line (in lines) passed to js_func, in addition to
     * the idfollowed
     */
    $table = '';
    foreach ($lines as $line)
    {
        $table .= '<tr>';
        foreach ($line as $value)
        {
            $table .= '<td>';
            $table .= ucfirst($value);
            $table .= '</td>';
        }
        $table .= '<td class="edit">';
        $edit = $line['type'];
        $table .= <<<GLPH
<span title="Add a new entry" class="glyphicon glyphicon-plus"
data-toggle="modal" data-target="#$modal"></span>
GLPH;
        $table .= '</td>';
        $table .= '</tr>';
    }
    return($table);
}

function meas_table($idfollowed)
{
    $measures = latest_meas_of($idfollowed);
    $table = simple_table($measures);
    return($table);
}

function relation_table($idfollowed)
{
    // Getting relationship information
    $fields = 'idfollowed1, idfollowed2, type_relation, begin, end';
    $table = 'Relation';
    $where = array();
    $where['str'] = 'idFollowed1=? OR idFollowed2=?';
    $where['valtype'] = array(
        array('value' => $idfollowed, 'type' => PDO::PARAM_INT),
        array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
    );
    $relationships = get_values_light($fields, $table, $where);
    $relships_noself = array();
    foreach ($relationships as $relationship)
    {
        $redundant_id = $relationship['idfollowed1'] == $idfollowed ?
            'idfollowed1' : 'idfollowed2';
        unset($relationship[$redundant_id]);
        array_push($relships_noself, $relationship);
    }
    $table = edi_table($relships_noself, 'edit_relship');
    return($table);
}

// Getting information
$fields = <<<FLD
binomial_name, common_name, gender, birth, health, death,
Followed.pic_path AS pic_path, Facility.name AS fa_name,
Followed.annotation
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
$search_res = get_values_light($fields, $table, $where)[0];


// Getting last known location
$fields = "latitude, longitude";
$table = "Location INNER JOIN Measure ON Location.idMeasure=Measure.idMeasure";
$wherestr = "Measure.idFollowed=?";
$where = array();
$where['str'] = 'Measure.idFollowed=?';
$where['valtype'] = array(
    array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
);
$groupby = 'date_measure';
$having = array(
    'str' => 'date_measure=MAX(date_measure)'
);
$loc = get_values_light($fields, $table, $where, $groupby, $having)[0];
?>

<!DOCTYPE html>
<html lang="fr">
<?php
include 'head.php';
head(ucfirst($search_res['binomial_name']));
echo $edit ? '<body onload="get_coords()">' : '<body>';
include 'nav.php';
?>
<div class="row">
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <div class="pic">
        <?php
        echo '<img src="'.$search_res['pic_path'].
            '" class = "img-responsive">';
        ?>
    </div>
    <form action="upload_pic.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" readonly value="<?php echo $idfollowed ?>">
    <input type="hidden" name="table" readonly value="Followed">
    <input type="file" name="userpic">
    <button type="submit" class="btn btn-default">Upload pic</button>
    </form>
</div>

<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
    <div class="intel">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="foll_data">
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
            <?php
            $loc_str = 'Last known location:';
            $loc_str .= $loc['latitude'] . 'W ' . $loc['longitude'] . 'N';
            echo $search_res['fa_name'] == 'gaia' ?
                $loc_str : 'At ' . $search_res['fa_name'];
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
            data-toggle="modal" data-target="#annotationModal">
            Modifier la description
        </button>
<?php } ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="map-container">
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


<div id="addModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Ajouter une nouvelle mesure</h2>
        <p>(Id individu <?php echo $idfollowed; ?>: , Id Staff: <?php echo $idstaff; ?>)</p>
      </div>
      <div class="modal-body">
          <form>
              <input type="text" class="form-control" name="type" placeholder="Type de mesure (Ex : taille)" required>
              <input type="number" step="0.01" class="form-control" name="value" placeholder="Valeur (Ex : 1,64)" required>
              <input type="text" class="form-control" name="unit" placeholder="Unité (Ex : cm)" required>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="addMeasure(<?php echo $idfollowed.', '.$idstaff; ?>)" data-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div id="addRelationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Add a new relationship</h2>
        <p>(Id individu <?php echo $idfollowed; ?>: , Id Staff: <?php echo $idstaff; ?>)</p>
      </div>
      <div class="modal-body">
          <form>
            <div class='input-group'>
            <label>Relation type</label>
            <input type="text" class="form-control" name="type_rel"
                placeholder="Pack, flamboyance, pride, ..." required>
            <label>With followed identified by</label>
            <input type="number" class="form-control" name="other_followed"
                placeholder="1, 12, ..." required>
            <label>Relation began on the
            <input type="date" class="form-control" name="begin"
                placeholder="yyyy-mm-dd">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"
            onclick="addRelationship(<?php echo $idfollowed.', '.$idstaff; ?>)"
            data-dismiss="modal">Valider</button>
      </div>
    </div>
  </div>
</div>
<div id="annotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Modifier la description du <?php echo ucfirst($search_res['binomial_name']); ?> </h2>
      </div>
      <div class="modal-body">
          <h5>Votre commentaire:</h5>
          <form>
              <textarea name="annotation"><?php echo ($search_res['annotation'] ? $search_res['annotation'] : ""); ?></textarea>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="modifyAnnotation(<?php echo $idfollowed ?>)" data-dismiss="modal">Valider</button>
      </div>
    </div>

  </div>
</div>
<div id="geolocModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Add a location for <?php echo ucfirst($search_res['binomial_name']); ?> </h2>
      </div>
      <div class="modal-body">
          <h5>Location</h5>
          <form>
            <div class="input-group">
              <label for="mod_latitude">Latitude</label>
              <input type="number" step="0.0000000001" name="mod_latitude"
                placeholder="41.02938" required id="mod_latitude">
              <label for="mod_longitude">Longitude</label>
              <input type="number" step="0.0000000001" name="mod_longitude"
                placeholder="0.651098" required id="mod_longitude">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"
            onclick="write_geoloc_fromodal(<?php echo $idfollowed.','.$idstaff ?>)"
            data-dismiss="modal">Update
        </button>
      </div>
    </div>
  </div>
</div>

<canvas id="myChart" width="800" height="800"></canvas>
<?php graph_type(14, 'weight', 'myChart'); ?>

<?php
include 'footer.php';
?>
</body>
<script>
function get_coords()
{
    navigator.geolocation.getCurrentPosition(coord2cookies);
}
function coord2cookies(position)
{
    document.cookie = 'geoloc='+position.coords.latitude+','+position.coords.longitude;
}
function write_geoloc_fromodal(idfoll, idstaff)
{
    var latitude = $("input[name=mod_latitude]").val();
    var longitude = $("input[name=mod_longitude]").val();
    var geoloc = latitude + ',' + longitude;
    $.post(
        'script/scriptAjax.php',
    {idfollowed: idfoll, geoloc: geoloc, idstaff: idstaff},
    function(data)
    {
        $("input[name=mod_latitude]").val('');
        $("input[name=mod_longitude]").val('');
    }
    );
}
function write_geoloc(idfoll, idstaff)
{
    $.post(
        'script/scriptAjax.php',
        {idfollowed: idfoll, geoloc: document.cookie, idstaff: idstaff}
    );
}

var contwidth = $('#map-container').width();
var contheight = $('#foll_data').height();
document.getElementById('followed_map').style.width = contwidth;
document.getElementById('followed_map').setAttribute("style",
    "height:" + contheight + "px");
var followed_map = L.map('followed_map').setView([0, 0], 2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
subdomain: ['a', 'b', 'c']
}).addTo(followed_map);
</script>
</html>
