<?php
session_start();
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin'; // autoriastion de l'edition pour un membre mais pas l'admin
// $edit = false;
function info_followed_table ($id) {
    $select = "gender, birth, death, health, Species.binomial_name,
Facility.name, Followed.annotation";
    $from = "Followed INNER JOIN Species ON Species.idSpecies = Followed.idSpecies
INNER JOIN Facility ON Facility.idFacility";
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
include 'head.php';
include 'db.php';
?>
<body>
<?php
include 'nav.php';
?>

<?php
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

<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
    <div class="pic">
        <?php
        echo '<img src="'.$search_res['pic_path'].
            '" class = "img-responsive">';
        ?>
        <p>Painting of a swedish gator hunting in his natural habitat.</p>
    </div>
</div>
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <div class="intel">
        <?php
        echo '<h1>'.ucfirst($search_res['common_name']).'</h1>';
        echo '<h2>'.ucfirst($search_res['binomial_name']).'</h2>';
        ?>
        <br>
        <p>Born on <?php echo $search_res['birth']; ?></p>
        <p>
            Last known location:
            <?php echo $loc['latitude'] . 'W ' . $loc['longitude'] . 'N'; ?>
            (Map?)
        </p>
        <br><br>
        <?php info_followed_table($idfollowed); ?>
        <br><br>
        <table>
            <tr>
                <th></th>
                <th>Value</th>
                <th>Editor</th>
                <th>Date</th>
                <?php
                if ($edit) {
                    echo "<th>Edit</th>";
                }
                ?>
            </tr>
            <tr>
                <td>Health</td>
                <td>Undead</td>
                <td>John</td>
                <td>03/05/2017</td>
                <?php
                if ($edit) {
                    echo "<td><a href='#'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                }
                ?>
            </tr>
            <tr>
                <td>Size</td>
                <td>175183770845391pm</td>
                <td>John</td>
                <td>03/05/2017</td>
                <?php
                if ($edit) {
                    echo "<td><a href='#'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                }
                ?>
            </tr>
            <tr>
                <td>Weight</td>
                <td>20lbs</td>
                <td>Me</td>
                <td>01:47</td>
                <?php
                if ($edit) {
                    echo "<td><a href='#'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                }
                ?>
            </tr>
            <tr>
                <td>Misc</td>
                <td>$59.99</td>
                <td>Johnny</td>
                <td>Tomorrow</td>
                <?php
                if ($edit) {
                    echo "<td><a href='#'><span class='glyphicon glyphicon-pencil'></span></a></td>";
                }
                ?>
            </tr>
        </table>
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
</html>
