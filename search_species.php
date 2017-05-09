<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "form_func.php";
include "head.php";
head('Recherche espèce');

$lines = get_values(array('idFacility', 'name'), 'Facility');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}
?>
<body>
<?php include "nav.php"; ?>
<form action="search_species.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="low_nfoll">Having more followed individuals than:
        <input type="number" name="low_nfoll" class="form-control" id="low_nfoll">
        <label for="up_nfoll">Having fewer followed individuals than:
        <input type="number" name="up_nfoll" class="form-control" id="up_nfoll">
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>
<?php
$colsp = array('idSpecies', 'binomial_name', 'nfoll');
$labels = array('Identifier', 'Binomial name', 'Number of followed individuals');
$fields = array('Species.idSpecies', 'binomial_name', 'idFollowed');
$tables = array('Species', 'Followed');
$where = array();
$constraints = array('Species.idSpecies' => 'Followed.idSpecies');
$groupby = 'Species.idSpecies';
$sqlfuncs = array(2 => 'COUNT');
$having = array();
$alias = array(2 => 'nfoll');
if (isset($_POST['low_nfoll']) && !empty($_POST['low_nfoll']))
{
    array_push($having,
        array(
            'binrel' => '>=',
            'field' => 'nfoll',
            'value' => $_POST['low_nfoll'],
            'type' => PDO::PARAM_INT
        )
    );
}
if (isset($_POST['up_nfoll']) && !empty($_POST['up_nfoll']))
{
    array_push($having,
        array(
            'binrel' => '<=',
            'field' => 'nfoll',
            'value' => $_POST['up_nfoll'],
            'type' => PDO::PARAM_INT
        )
    );
}
$search_res = get_values(
    $fields,
    $tables,
    $where,
    $constraints,
    $groupby,
    $sqlfuncs,
    $alias,
    $having
);
echo !$search_res ? "Error while querying" : null;
echo <<<TH
<table id='table'
class='table'
data-toggle='table'
data-search='true'
data-pagination='true'
data-detail-view='true'
data-detail-formatter='detail_formatter'
data-show-footer='true'>
TH;
echo '<thead>';
create_tablehead($colsp, $labels);
echo '</thead>';
echo '<tbody>';
create_tablebody($colsp, $search_res);
echo'</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
</html>
