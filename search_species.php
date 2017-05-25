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
    include('i18n/fr_FR/search_species_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/search_species_en_UK.php');
}

include "script/db.php";
include "script/form_func.php";
include "head.php";
head('Recherche espèce', $lang);

$lines = get_values('idFacility, name', 'Facility', $orderby='name');
$id_faname = array();
foreach ($lines as $line)
{
    $id_faname[$line['idFacility']] = $line['name'];
}

$fields = 'Species.idSpecies, binomial_name, common_name';
$species = get_values($fields, 'Species');
$fields = 'Species.idSpecies, COUNT(idFollowed) as nfoll';
$tables = 'Species INNER JOIN Followed ON Species.idSpecies=Followed.idSpecies';
// FETCH_KEY_PAIR -> array(idSpecies1 => nfoll1, idSpecies2 => nfoll2, ...)
$spfollcount = get_values($select=$fields, $tables=$tables,
    $groupby='Species.idSpecies', $fetch_style=PDO::FETCH_KEY_PAIR
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
    $len = count($species);
    for ($i = 0 ; $i < $len ; $i++) {
        if ($species[$i]['nfoll'] < $_POST['low_nfoll']) {
            unset($species[$i]);
        }
    }
    $species = array_values($species);  //Resets indexes
}
if (isset($_POST['up_nfoll']) && !empty($_POST['up_nfoll']))
{
    $len = count($species);
    for ($i = 0 ; $i < $len ; $i++) {
        if ($species[$i]['nfoll'] > $_POST['up_nfoll']) {
            unset($species[$i]);
        }
    }
    $species = array_values($species);  //Resets indexes
}
// colsp are column fields used by tables (columns in array)
$colsp = array('idSpecies', 'binomial_name', 'nfoll');

?>
<body>
<?php include "nav.php"; ?>
<?php echo $title ?>
<div class="research">
    <form action="search_species.php" method="post" accept-charset="utf-8"
        class="form-inline" enctype="multipart/form-data">
        <div class="form-group">
            <label for="low_nfoll"><?php echo $more_than ?></label>
            <input type="number" name="low_nfoll" class="form-control"
                   id="low_nfoll" placeholder="1, 3, ...">
            <label for="up_nfoll"><?php echo $less_than ?></label>
            <input type="number" name="up_nfoll" class="form-control"
            id="up_nfoll" placeholder="15, 54, ...">
        </div><br>
        <button type="submit" class="btn btn-default"><?php echo $research ?></button>
    </form>
</div>
<hr class="rslt">
<?php echo $result; ?>
<div class="result-table">
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
echo "<tbody>";
create_tablebody($colsp, $species, 'species.php', 'idSpecies');
echo'</tbody>';
echo '</table>';
?>
</div>
<?php include "footer.php"; ?>
</body>
</html>
