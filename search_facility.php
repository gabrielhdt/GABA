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
<form action="search_species.php" method="post" accept-charset="utf-8"
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

        <label for="low_nvets">Having more veterinaries than:
        <input type="number" name="low_nvets" class="form-control" id="low_nvets">
        <label for="up_nvets">Having fewer veterinaries than:
        <input type="number" name="up_nvets" class="form-control" id="up_nvets">
        <br>
        <label class="radio-inline"><input type="radio" name="vets_log" value="and">and</label>
        <label class="radio-inline"><input type="radio" name="vets_log" value="or">or</label>
        <br>
        <label for="low_ntechs">Having more technicians than:
        <input type="number" name="low_ntechs" class="form-control" id="low_ntechs">
        <label for="up_ntechs">Having fewer technicians than:
        <input type="number" name="up_ntechs" class="form-control" id="up_ntechs">
        <br>
        <label class="radio-inline"><input type="radio" name="tech_log" value="and">and</label>
        <label class="radio-inline"><input type="radio" name="tech_log" value="or">or</label>
        <br>
        <label for="low_nrsch">Having more researchers than:
        <input type="number" name="low_nrsch" class="form-control" id="low_nrsch">
        <label for="up_nrsch">Having fewer researchers than:
        <input type="number" name="up_nrsch" class="form-control" id="up_nrsch">
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>
<?php
$col = array('idFacility', 'fa_name', 'nfoll');
$labels = array('Identifier', 'Facility name', 'Number of followed individuals');

$fields = <<<FLD
Facility.idFacility, Facility.name AS fa_name,
COUNT(Followed.idFollowed) as nfoll, COUNT(Staff.idStaff) as nstaff
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
create_tablehead($col, $labels);
echo '</thead>';
echo '<tbody>';
create_tablebody($col, $search_res);
echo'</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
</html>
