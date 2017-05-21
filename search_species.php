<!DOCTYPE HTML>
<html>
<?php include "script/db.php";
include "script/form_func.php";
include "head.php";
head('Recherche espèce');

$lines = get_values(array('idFacility', 'name'), 'Facility');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}

$fields = 'Species.Species.idSpecies, binomial_name, common_name';
$species = get_values_light($fields, 'Species');
$fields = 'Species.idSpecies, COUNT(idFollowed) as nfoll';
$tables = 'Species INNER JOIN Followed ON Species.idSpecies=Followed.idSpecies';
// FETCH_KEY_PAIR -> array(idSpecies1 => nfoll1, idSpecies2 => nfoll2, ...)
$spfollcount = get_values_light($fields, $tables, array(), 'Species.idSpecies',
    array(), '', PDO::FETCH_KEY_PAIR
);
foreach ($species as &$spline)
{
    if (isset($spfollcount[$spline['idSpecies']]))
    {
        $spline['nfoll'] = $spfollcount[$spline['idSpecies']];
    }
    else
    {
        $spline['nfoll'] = 0;
    }
}
if (isset($_POST['low_nfoll']) && !empty($_POST['low_nfoll']))
{
    for ($i = 0 ; $i < count($species) ; $i++) {
        if ($species[$i]['nfoll'] < $_POST['low_nfoll']) {
            unset($species[$i]);
        }
    }
    $species = array_values($species);  //Resets indexes
}
if (isset($_POST['up_nfoll']) && !empty($_POST['up_nfoll']))
{
    for ($i = 0 ; $i < count($species) ; $i++) {
        if ($species[$i]['nfoll'] > $_POST['up_nfoll']) {
            unset($species[$i]);
        }
    }
    $species = array_values($species);  //Resets indexes
}
$colsp = array('idSpecies', 'binomial_name', 'nfoll');
$labels = array('Identifier', 'Name', 'Num. of followed individuals');
?>
<body>
<?php include "nav.php"; ?>
<div class="research">
    <form action="search_species.php" method="post" accept-charset="utf-8"
        class="form-inline" enctype="multipart/form-data">
        <div class="form-group">
            <input type="number" name="low_nfoll" class="form-control"
                   id="low_nfoll" placeholder="Nombre d'individu suivi min">
            <input type="number" name="up_nfoll" class="form-control"
            id="up_nfoll" placeholder="Nombre d'individu suivi max">
        </div><br>
        <button type="submit" class="btn btn-default">Rechercher animal</button>
    </form>
</div>
<?php
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
create_tablebody($colsp, $species, 'species.php', 'idSpecies');
echo'</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
</html>
