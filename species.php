<?php
include 'script/db.php';
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
$pic_paths = array_filter($pic_paths_null, "h");
$pic_path = $pic_paths[array_rand($pic_paths)];

// Getting wikipedia intro
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://en.wikipedia.org/w/api.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Owl/0.1 GABA');
curl_setopt($ch, CURLOPT_POSTFIELDS,
    'action=query&prop=extracts&exintro=&format=json&formatversion=2&titles=Bobcat'
);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$wikijson = curl_exec($ch);
$wikiarr = json_decode($wikijson, TRUE);
print_r($wikiarr);
$wikintro = $wikiarr['query']['pages'][0]['extract'];
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
        <table>
            <tr><td>Kingdom</td><td><?php echo ucfirst($search_res['kingdom'])?></td></tr>
            <tr><td>Phylum</td><td><?php echo ucfirst($search_res['phylum'])?></td></tr>
            <tr><td>Class</td><td><?php echo ucfirst($search_res['class'])?></td></tr>
            <tr><td>Order</td><td><?php echo ucfirst($search_res['order_s'])?></td></tr>
            <tr><td>Family</td><td><?php echo ucfirst($search_res['family'])?></td></tr>
            <tr><td>Genus</td><td><?php echo ucfirst($search_res['genus'])?></td></tr>
        </table>
        We currently have <?php echo $nfoll ?> individuals.
        <p id="wikintro">
            Data from wikipedia soon
            <?php echo $wikintro?>
        </p>
    </div>
</div>
</div>
<?php
include 'footer.php';
?>
</body>
</html>
