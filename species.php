<?php
session_start ();
include 'script/db.php';
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
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="sp_data">
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
            <?php if ($edit) { ?>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal"
                data-target="#editSpeciesModal">Edit species informations</button>
            <?php } ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="map-container">
            <div id="foll_of_sp_map"></div>
        </div>
    </div>
</div>
</div>
<div id="editSpeciesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          &times;
        </button>
        <h2 class="modal-title">
          Modify <?php echo ucfirst($search_res['binomial_name']); ?> data
        </h2>
      </div>
      <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="iMod_commoname">Common name</label>
              <input type="text" name="common_name" id="iMod_commoname"
                class="form-control"
                value="<?php echo ucfirst($search_res['common_name']) ?>">
              <label for=iMod_biname">Binomial name</label>
              <input type="text" name="binomial_name" id="iMod_biname"
                class="form-control"
                value="<?php echo ucfirst($search_res['binomial_name']) ?>">
              <label for="iMod_kingdom">Kingdom</label>
              <input type="text" name="kingdom" id="iMod_kingdom"
                class="form-control"
                value="<?php echo ucfirst($search_res['kingdom']) ?>">
              <label for="iMod_phylum">Phylum</label>
              <input type="text" name="phylum" id="iMod_phylum"
                class="form-control"
                value="<?php echo ucfirst($search_res['phylum']) ?>">
              <label for="iMod_class">Class</label>
              <input type="text" name="class" id="iMod_class"
                class="form-control"
                value="<?php echo ucfirst($search_res['class']) ?>">
              <label for="iMod_order">Order</label>
              <input type="text" name="order_s" id="iMod_order"
                class="form-control"
                value="<?php echo ucfirst($search_res['order_s']) ?>">
              <label for="iMod_family">Family</label>
              <input type="text" name="family" id="iMod_family"
                class="form-control"
                value="<?php echo ucfirst($search_res['family']) ?>">
              <label for="iMod_genus">Genus</label>
              <input type="text" name="genus" id="iMod_genus"
                class="form-control"
                value="<?php echo ucfirst($search_res['genus']) ?>">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"
            onclick="editSpecies(<?php echo $idspecies ?>)"
            data-dismiss="modal">Update</button>
      </div>
    </div>

  </div>
</div>
<?php
include 'footer.php';
?>
</body>
<script>
// Map management
var contwidth = $('#map-container').width();
var contheight = $('#sp_data').height();
document.getElementById('foll_of_sp_map').style.width = contwidth;
document.getElementById('foll_of_sp_map').setAttribute("style",
    "height:" + contheight + "px");
var foll_of_sp_map = L.map('foll_of_sp_map').setView(
    [0, 0], 2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
attributions: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
subdomain: ['a', 'b', 'c']
}).addTo(foll_of_sp_map);
</script>
</html>
