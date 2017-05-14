<!DOCTYPE HTML>
<html>
<?php include "script/db.php";
include "script/form_func.php";
include "head.php";
head('Recherche individu');

$dateregex = "\d{4}[-.\/][01]?\d[-.\/][0-3]?\d";

$id_biname = array();
$lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
$lines = get_values(array('idFacility', 'name'), 'Facility');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}
?>
<body>
<?php include "nav.php"; ?>
<form action="search_followed.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="sel_species">Of species:</label>
        <select name="idspecies[]" id="sel_species" class="form-control" multiple>
        <?php create_choice_list($id_biname); ?>
        </select>
        <label for="sel_facility">In facilities:</label>
        <select name="idfacility[]" id="sel_facility" class="form-control" multiple>
        <?php create_choice_list($id_faname); ?>
        </select>
        <br>
        <label for="lowbirth">Born after:</label>
        <input type="date" name="lowbirth" class="form-control" id="lowbirth"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <label for="upbirth">Born before:</label>
        <input type="date" name="upbirth" class="form-control" id="upbirth"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <br>
        <label for="lowdeath">Died after:</label>
        <input type="date" name="lowdeath" class="form-control" id="lowdeath"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <label for="updeath">Died before:</label>
        <input type="date" name="updeath" class="form-control" id="updeath"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <br>
        <label class="radio-inline"><input type="radio" name="gender" value="m">Male</label>
        <label class="radio-inline"><input type="radio" name="gender" value="f">Female</label>
        <label class="radio-inline"><input type="radio" name="gender" value="h">Hermaphrodite</label><br>
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>
<?php
$where = array();
if (isset($_POST['lowbirth']) & !empty($_POST['lowbirth']))
{
    if (preg_match("/$dateregex/", $_POST['lowbirth']))
    {
        array_push($where,
            array(
                'binrel' => '>=',
                'field' => 'birth',
                'value' => $_POST['lowbirth'],
                'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['upbirth']) && !empty($_POST['upbirth']))
{
    if (preg_match("/$dateregex/", $_POST['upbirth']))
    {
        array_push($where,
            array(
                'binrel' => '<=',
                'field' => 'birth',
                'value' => $_POST['upbirth'],
                'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['lowdeath']) & !empty($_POST['lowdeath']))
{
    if (preg_match("/$dateregex/", $_POST['lowdeath']))
    {
        array_push($where,
            array(
                'binrel' => '>=',
                'field' => 'death',
                'value' => $_POST['lowdeath'],
                'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['updeath']) && !empty($_POST['updeath']))
{
    if (preg_match("/$dateregex/", $_POST['updeath']))
    {
        array_push($where,
            array(
                'binrel' => '<=',
                'field' => 'death',
                'value' => $_POST['updeath'],
                'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['gender']) && !empty($_POST['gender']))
{
    array_push($where,
        array(
            'binrel' => '=',
            'field' => 'gender',
            'value' => $_POST['gender'],
            'type' => PDO::PARAM_STR
        )
    );
}
if (isset($_POST['idspecies']) && !empty($_POST['idspecies']))
{
    array_push($where,
        array(
            'binrel' => 'IN',
            'field' => 'Followed.idSpecies',
            'value' => $_POST['idspecies'],
            'type' => PDO::PARAM_INT
        )
    );
}
if (isset($_POST['idfacility']) && !empty($_POST['idfacility']))
{
    array_push($where,
        array(
            'binrel' => 'IN',
            'field' => 'Followed.idFacility',
            'value' => $_POST['idfacility'],
            'type' => PDO::PARAM_INT
        )
    );
}
$tables = array('Followed', 'Species', 'Facility');
$constraints = array(
    'Followed.idSpecies' => 'Species.idSpecies',
    'Followed.idFacility' => 'Facility.idFacility'
);
$colfoll = array('idFollowed', 'idSpecies', 'idFacility', 'gender', 'birth',
    'death', 'health');
$labels = array('Identifier', 'Species', 'Facility', 'Gender', 'Birth',
    'Death', 'Health');
$fields = array(
    'idFollowed',
    'binomial_name',
    'name',
    'gender',
    'birth', 'death', 'health'
);
echo "<table id='table'
    class='table'
    data-toggle='table'
    data-search='true'
    data-pagination='true'
    data-page-list='[10, 25, 50, 100, ALL]'
    data-pagination='true'>";
echo '<thead>';
create_tablehead($colfoll, $labels);
echo '</thead>';
echo '<tbody>';
$search_res = get_values($fields, $tables, $where, $constraints);
create_tablebody($fields, $search_res, 'followed.php', 'idFollowed');
echo '</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
</html>
