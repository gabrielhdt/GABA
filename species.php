<?php
include 'db.php';
session_start();
// Autoriastion de l'edition pour un membre mais pas l'admin
$edit = isset($_SESSION['login']) && $_SESSION['login'] != 'admin';
$idstaff = isset($_SESSION['idstaff']) ? $_SESSION['idstaff'] : null;
$idspecies = $_GET['id'];

// Getting information
$fields = <<<FLD
binomial_name, common_name, kingdom, phylum, class, order_s, family, genus
FLD;
$table = <<<TAB
Species
TAB;
$where = array();
$where['str'] = <<<WH
idSpecies=?
WH;
$where['valtype'] = array(
    array('value' => $idspecies, 'type' => PDO::PARAM_INT)
);
$search_res = get_values_light($fields, $table, $where)[0];
$nfoll = get_values_light(
    'COUNT(idFollowed) AS nfoll',
    'Followed',
    array(
        'str' => 'idSpecies=?',
        'valtype' => array(
            array('value' => $idspecies, 'type' => PDO::PARAM_INT)
        )
    )
)[0]['nfoll'];

// Getting a random picture
$where['str'] = 'idSpecies=?';
$where['valtype'] = array(array('value' => $idspecies,
    'type' => PDO::PARAM_INT));
$pic_paths_qu = get_values_light('pic_path', 'Followed', $where);
function g($ppassoc) { return($ppassoc['pic_path']); }
$pic_paths_null = array_map("g", $pic_paths_qu);
function h($ppnull) { return($ppnull && true); } // ppnull seems false?
$pic_paths = array_filter("h", $pic_paths_null);
$pic_path = $pic_paths[rand(0, count($pic_paths)-1)];

?>

<!DOCTYPE html>
<html lang="fr">
<?php
include 'head.php';
head(ucfirst($search_res['binomial_name']));
?>
<body>
<?php
include 'nav.php';
?>
<div class="row">
<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
    <div class="pic">
        <?php
        echo '<img src="'.$pic_path.
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
        We currently have <?php echo $nfoll ?> individuals.
        <p>
            Data from wikipedia soon
        </p>
    </div>
</div>
</div>
<?php
include 'footer.php';
?>
</body>
</html>
