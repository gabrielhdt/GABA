<?php
include 'db.php';
session_start();
// Autoriastion de l'edition pour un membre mais pas l'admin
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';
$idstaff = $_SESSION['idstaff'];
$idfollowed = $_GET['id'];

function meas_table($idfollowed)
{
    $measures = latest_meas_of($idfollowed);
    foreach ($measures as $measure)
    {
        $table .= "<tr><td>" . $measure['type'] . "</td><td>" .
            $measure['value'] . "</td><td>" . $measure['unit'] .
            $measure['time'] . "</td></tr>\n";
    }
    return($table);
}

function relation_table($relationships)
{
    $table = '';
    foreach ($relationships as $relationship)
    {
        $other_id = $relationship['idfollowed1'] == $idfollowed ?
            $relationship['idfollowed2'] : $relationship['idfollowed1'];
        $table .= "<tr><td>" .
            "<a href=\"followed?id=$other_id\">$other_id</a>" . "</td><td>" .
            $relationship['relation_type'] . "</td><td>" .
            $relationship['begin'] . "</td><td>" .
            $relationship['end'] . "</td></tr>\n";
    }
    return($table);
}

// Getting information

$fields = <<<FLD
binomial_name, common_name, gender, birth, health, death,
Followed.pic_path AS pic_path, Facility.name AS fa_name
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

// Getting relationship information
$fields = 'idfollowed1, idfollowed2, type_relation, begin, end';
$table = 'Relation';
$where['str'] = 'idFollowed1=? OR idFollowed2=?';
$where['valtype'] = array(
    array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
);
$relationships = get_values_light($fields, $table, $where);

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

<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <div class="pic">
        <?php
        echo '<img src="'.$search_res['pic_path'].
            '" class = "img-responsive">';
        ?>
    </div>
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
        <p>
            <?php echo $search_res['annotation'] ?
            $search_res['annotation'] : 'Write something about this animal!'; ?>
        </p>
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

        <p>Last update Misc by Johnny on Tomorrow (Useless?)</p>
        <h1>Relationships<h1>
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
                <?php echo relation_table($relationship); ?>
            </tbody>
        </table>
    </div>
</div>
<form action="upload_pic.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" readonly value="<?php echo $idfollowed ?>">
<input type="hidden" name="table" readonly value="Followed">
<input type="file" name="userpic">
<button type="submit" class="btn btn-default">Upload pic</button>
</form>
</body>

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
        'script/add_geoloc.php',
        {idfollowed: idfoll, geoloc: document.cookie, idstaff: idstaff}
    );
}
</script>
</html>
