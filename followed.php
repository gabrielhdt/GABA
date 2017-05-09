<?php
include 'db.php';
session_start();
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin'; // autoriastion de l'edition pour un membre mais pas l'admin
// $edit = false;
function info_followed_table ($id) {
    /*************************************************
    affiche les informations de l'anniaml d'id $id
    *************************************************/
    $select = <<<FLD
gender, birth, death, health, Species.binomial_name,
Facility.name, Followed.annotation
FLD;
    $from = <<<FRM
Followed INNER JOIN Species ON Species.idSpecies = Followed.idSpecies
INNER JOIN Facility ON Facility.idFacility
FRM;
    $where['str'] = 'idFollowed=?';
    $where['valtype'] = array(array('value' => $id, 'type' => PDO::PARAM_INT));
    $infos = get_values_light($select, $from, $where);
    $table = "<table>\n";
    foreach ($infos[0] as $key => $value) {
        $table .= "<tr><td>$key</td><td>".($value ? $value : 'null')."</td></tr>\n";
    }
    $table .= "</table>\n";
    echo $table;
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php
$idstaff = $_SESSION['idstaff'];
$idfollowed = $_GET['id'];
$fields = <<<FLD
binomial_name, common_name, gender, birth, health, death,
Followed.pic_path AS pic_path
FLD;
$table = <<<TAB
Followed INNER JOIN Species ON Followed.idSpecies = Species.idSpecies
TAB;
$where = array();
$where['str'] = <<<WH
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
<?php
include 'head.php';
head(ucfirst($search_res['binomial_name']));
?>
<body onload="get_coords()">
<?php
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
            born on <?php echo $search_res['birth'] ?>
        <p>
            Last known location:
            <?php
            echo $loc['latitude'] . 'W ' . $loc['longitude'] . 'N';
            if ($edit)
            {
                echo "<button onclick=\"write_geoloc($idfollowed, $idstaff)\" type=\"button\" class=\"btn btn-default btn-xs\">";
                echo 'Update with current localisation';
                echo '</button>';
            }
            ?>
        </p>
        <p>
            <?php echo $search_res['annotation'] ?
            $search_res['annotation'] : 'Write something about this animal!'; ?>
        </p>
        <h1>Informations générales :</h1>
        <?php info_followed_table($idfollowed); ?>  <!-- tableau d'informations générales -->

        <p>Last update Misc by Johnny on Tomorrow (Useless?)</p>
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
