<?php
session_start ();

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/search_followed_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/search_followed_en_UK.php');
}

include "script/db.php";
include "script/form_func.php";
include "head.php";
head('Recherche individu', $lang);

$dateregex = "\d{4}[-.\/][01]?\d[-.\/][0-3]?\d";

$id_biname = array();
$lines = get_values('idSpecies, binomial_name', 'Species',
    $orderby='binomial_name');
foreach ($lines as $line)
{
    $id_biname[$line['idSpecies']] = $line['binomial_name'];
}
$lines = get_values('idFacility, name', 'Facility', $orderby='name');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}

$where = array();
$wherestrfrags = array();
$where['valtype'] = array();
if (isset($_POST['lowbirth']) & !empty($_POST['lowbirth']))
{
    if (preg_match("/$dateregex/", $_POST['lowbirth']))
    {
        array_push($wherestrfrags, 'birth>=?');
        array_push($where['valtype'], array(
                'value' => $_POST['lowbirth'], 'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['upbirth']) && !empty($_POST['upbirth']))
{
    if (preg_match("/$dateregex/", $_POST['upbirth']))
    {
        array_push($wherestrfrags, 'birth<=?');
        array_push($where['valtype'],
            array(
                'value' => $_POST['upbirth'], 'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['lowdeath']) & !empty($_POST['lowdeath']))
{
    if (preg_match("/$dateregex/", $_POST['lowdeath']))
    {
        array_push($wherestrfrags, 'death>=?');
        array_push($where['valtype'],
            array(
                'value' => $_POST['lowdeath'], 'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['updeath']) && !empty($_POST['updeath']))
{
    if (preg_match("/$dateregex/", $_POST['updeath']))
    {
        array_push($wherestrfrags, 'updeath<=?');
        array_push($where['valtype'],
            array(
                'value' => $_POST['updeath'], 'type' => PDO::PARAM_STR
            )
        );
    }
}
if (isset($_POST['gender']) && !empty($_POST['gender']))
{
    array_push($wherestrfrags, 'gender=?');
    array_push($where['valtype'],
        array(
            'value' => $_POST['gender'], 'type' => PDO::PARAM_STR
        )
    );
}
if (isset($_POST['idspecies']) && !empty($_POST['idspecies']))
{
    $numsp = count($_POST['idspecies']);
    $in_str = '(' . implode(', ', array_fill(0, $numsp, '?')) . ')';
    array_push($wherestrfrags, 'Followed.idSpecies IN ' . $in_str);
    foreach ($_POST['idspecies'] as $idsp)
    {
        array_push($where['valtype'],
            array(
                'value' => $idsp, 'type' => PDO::PARAM_INT
            )
        );
    }
}
if (isset($_POST['idfacility']) && !empty($_POST['idfacility']))
{
    $numfa = count($_POST['idfacility']);
    $in_str = '(' . implode(', ', array_fill(0, $numfa, '?')) . ')';
    array_push($wherestrfrags, 'Followed.idFacility IN ' . $in_str);
    foreach ($_POST['idfacility'] as $idfa)
    {
        array_push($where['valtype'],
            array(
                'value' => $idfa, 'type' => PDO::PARAM_INT
            )
        );
    }
}
if ($wherestrfrags)
{
    $where['str'] = implode(' AND ', $wherestrfrags);
}
if (!isset($where['str']))
{
    unset($where);
}
$tables = <<<TBL
Followed INNER JOIN Species ON Followed.idSpecies=Species.idSpecies
INNER JOIN Facility ON Followed.idFacility=Facility.idFacility
TBL;
$colfoll = array('idFollowed', 'idSpecies', 'idFacility', 'gender', 'birth',
    'death', 'health');
$labels = array('Identifier', 'Species', 'Facility', 'Gender', 'Birth',
    'Death', 'Health');
$fields = <<<FLD
idFollowed, binomial_name, name, gender, birth, death, health
FLD;
$search_res = get_values($fields, $tables, $where);
?>
<body>
<?php include "nav.php"; ?>
<?php echo $title ?>
<form action="search_followed.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="sel_species"><?php echo $species ?></label>
        <select name="idspecies[]" id="sel_species" class="form-control" multiple>
        <?php create_choice_list($id_biname); ?>
        </select>
        <label for="sel_facility"><?php echo $facility ?></label>
        <select name="idfacility[]" id="sel_facility" class="form-control" multiple>
        <?php create_choice_list($id_faname); ?>
        </select>
        <br>
        <label for="lowbirth"><?php echo $birth_a ?></label>
        <input type="date" name="lowbirth" class="form-control" id="lowbirth"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <label for="upbirth"><?php echo $birth_b ?></label>
        <input type="date" name="upbirth" class="form-control" id="upbirth"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <br>
        <label for="lowdeath"><?php echo $death_a ?></label>
        <input type="date" name="lowdeath" class="form-control" id="lowdeath"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <label for="updeath"><?php echo $death_b ?></label>
        <input type="date" name="updeath" class="form-control" id="updeath"
            pattern="<?php echo $dateregex; ?>" placeholder="yyyy-mm-dd">
        <br>
        <label class="radio-inline"><input type="radio" name="gender" value="m"><?php echo $m ?></label>
        <label class="radio-inline"><input type="radio" name="gender" value="f"><?php echo $f ?></label>
        <label class="radio-inline"><input type="radio" name="gender" value="h"><?php echo $h ?></label><br>
    </div>
    <button type="submit" class="btn btn-default"><?php echo $search ?></button>
</form>

<hr>
<?php echo $result ?>
<div class="result-table">
<?php
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
create_tablebody(
    explode(', ', $fields), $search_res, 'followed.php', 'idFollowed'
);
echo '</tbody>';
echo '</table>';
?>
</div>
<?php include "footer.php"; ?>
</body>
</html>
