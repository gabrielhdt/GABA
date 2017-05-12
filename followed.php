<?php
include 'db.php';
session_start();
// Autoriastion de l'edition pour un membre mais pas l'admin
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';
$idstaff = $_SESSION['idstaff'];
$idfollowed = $_GET['id'];

function edi_table($lines, $js_func, $edit_arg='')
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
        $table .= '</tr>';
    }
    return($table);
}

function meas_table($idfollowed)
{
    $measures = latest_meas_of($idfollowed);
    $table = edi_table($measures, 'add_measure', 'type');
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
    foreach ($relationships as $relationship)
    {
        $redundant_id = $relationship['idfollowed1'] == $idfollowed ?
            'idfollowed1' : 'idfollowed2';
        unset($relationship[$redundant_id]);
    }

    $table = edi_table($relationships, 'edit_relation');
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
<button class="btn btn-default btn-xs" type="button"
onclick="write_geoloc($idfollowed, $idstaff)"
aria-label="Update with current position">
    <span class="glyphicon glyphicon-map-marker"></span>Update
</button>
BTN;
            }
            ?>
        </p>
        <p class="annotation">
            <?php echo $search_res['annotation'] ?
            $search_res['annotation'] : 'Write something about this animal!'; ?>
        </p>
<?php if ($edit) { ?>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#annotationModal">Modifier la description</button>
<?php } ?>
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
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#addModal">Ajouter une mesure</button>
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
          <h5>Votre commantaire:</h5>
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
function write_geoloc(idfoll, idstaff)
{
    $.post(
        'script/scriptAjax.php',
        {idfollowed: idfoll, geoloc: document.cookie, idstaff: idstaff}
    );
}
</script>
</html>
