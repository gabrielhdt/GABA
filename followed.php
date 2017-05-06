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
binomial_name, common_name, gender, birth, health, death
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
?>

<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
    <div class="pic">
        <img src="data/pics/unordered/gator.jpg" style="width:100%;height:100%;"/>
        <p>Painting of a swedish gator hunting in his natural habitat.</p>
    </div>
</div>
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <div class="intel">
        <?php
        echo '<h1>'.$search_res['common_name'].'</h1>'; 
        echo '<h2>'.$search_res['binomial_name'].'</h2>';
        ?>
        <br>
        <p>Born on January the 1st, 500 B.C.</p>
        <p>Last known location: 175°120'N 074°300'W (Map?)</p>
        <br><br>
        <table>
            <tr>
                <th></th>
                <th>Value</th>
                <th>Editor</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>Health</td>
                <td>Undead
                    <button class="btn btn-success" type="submit" name="submit_contact">Edit</button>
                </td>
                <td>John</td>
                <td>03/05/2017</td>
            </tr>
            <tr>
                <td>Size</td>
                <td>175183770845391pm
                    <button class="btn btn-success" type="submit" name="submit_contact">Edit</button>
                </td>
                <td>John</td>
                <td>03/05/2017</td>
            </tr>
            <tr>
                <td>Weight</td>
                <td>20lbs
                    <button class="btn btn-success" type="submit" name="submit_contact">Edit</button></td>
                <td>Me</td>
                <td>01:47</td>
            </tr>
            <tr>
                <td>Misc</td>
                <td>$59.99
                    <button class="btn btn-success" type="submit" name="submit_contact">Edit</button></td>
                <td>Johnny</td>
                <td>Tomorrow</td>
            </tr>
        </table>
        <p>Last update Misc by Johnny on Tomorrow (Useless?)</p>
    </div>
</div>
</body>

<?php
include 'footer.php';
?>
</body>
</html>
