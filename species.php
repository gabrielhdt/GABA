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
    include('i18n/fr_FR/species_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/species_en_UK.php');
}
//fin du script d'origine

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
$search_res = get_values($fields, $table, array('where' => $where))[0];
$nfoll = get_values(
    'COUNT(idFollowed) AS nfoll',
    'Followed',
    array(
        'where' =>
        array(
            'str' => 'idSpecies=?',
            'valtype' => array(
                array('value' => $idspecies, 'type' => PDO::PARAM_INT)
            )
        )
    )
)[0]['nfoll'];

// Getting a random picture
$where['str'] = 'idSpecies=?';
$where['valtype'] = array(array('value' => $idspecies,
    'type' => PDO::PARAM_INT));
$pic_paths_qu = get_values('pic_path', 'Followed', array('where' => $where));
function g($ppassoc) { return($ppassoc['pic_path']); }
$pic_paths_null = array_map("g", $pic_paths_qu);
function h($ppnull) { return($ppnull && true); } // ppnull seems false?
$pic_paths = array_filter($pic_paths_null, "h");
$pic_path = $pic_paths[array_rand($pic_paths)];

// Getting wikipedia intro
$ch = curl_init();
if ($lang == 'fr') {
    $wikiurl = 'https://fr.wikipedia.org/w/api/php';
} else {
    $wikiurl = 'https://en.wikipedia.org/w/api.php';
}
curl_setopt($ch, CURLOPT_URL, $wikiurl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Owl/0.1 GABA');
curl_setopt($ch, CURLOPT_POSTFIELDS,
    'action=query&prop=extracts&exintro=&format=json&formatversion=2&titles=' .
    ucwords($search_res['common_name'])
);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$wikijson = curl_exec($ch);
$wikiarr = json_decode($wikijson, TRUE);
$wikintro = $wikiarr['query']['pages'][0]['extract'];

//Getting last location of followed
$tables = <<<TBL
Location INNER JOIN Measure ON Measure.idMeasure=Location.idMeasure
INNER JOIN Followed ON Measure.idFollowed=Followed.idFollowed
TBL;
$where['str'] = 'Followed.idSpecies=?';
$where['valtype'] = array(
    array('value' => $idspecies, 'type' => PDO::PARAM_INT)
);
$fields = 'Followed.idFollowed';
$folls_located = get_values($fields, $tables, array('where' => $where));
function f($line) {return($line['idFollowed']);}
$folls_located = array_map('f', $folls_located);
$last_locs = array();
foreach ($folls_located as $follocated){
    $fields = 'MAX(date_measure) AS lastm_date';
    $tables = <<<TBL
Location INNER JOIN Measure ON Location.idMeasure=Measure.idMeasure
INNER JOIN Followed ON Followed.idFollowed=Measure.idFollowed
TBL;
    $where['str'] = 'Followed.idFollowed=?';
    $where['valtype'] = array(
        array('value' => $follocated, 'type' => PDO::PARAM_INT)
    );
    $lastm_date = get_values($fields, $tables,
        array('where' => $where))[0]['lastm_date'];
    $fields = 'Followed.idFollowed, latitude, longitude';
    $where['str'] = 'date_measure=?';
    $where['valtype'] = array(
        array('value' => $lastm_date, 'type' => PDO::PARAM_STR)
    );
    array_push($last_locs, get_values($fields, $tables,
        array('where' => $where))[0]);
}

//Statistics (mean in a first place)
$where['str'] = 'idSpecies=?';
$where['valtype'] = array(
    array('value' => $idspecies, 'type' => PDO::PARAM_INT)
);
$folls = get_values('idFollowed', 'Followed', array('where' => $where));
$folls = array_map('f', $folls);
$folls_mtype = array(); //array(idFollowed => array of measure types)
function ht($line) {return($line['type']);}
foreach ($folls as $foll) {
    $folls_mtype[$foll] = array_map('ht', distinct_measure($foll));
}
$mtypes = array();  //All types
foreach ($folls_mtype as $types_for_foll) {
    $mtypes = array_merge($mtypes, $types_for_foll);
}
$mtypes = array_unique($mtypes);
$mtypes_count = array();  //array(mtype => number of followed having measure)
$mtypes_sum = array();
$mtypes_unit = array();  //TODO: manage units (poorly done here)
foreach ($folls_mtype as $idf => $meas_ofoll) {
    foreach ($meas_ofoll as $fmtype) {
        $lmt = latest_meas_type($idf, $fmtype);
        if (isset($mtypes_count[$fmtype])) {
            $mtypes_count[$fmtype]++;
            $mtypes_sum[$fmtype] += $lmt['value'];
        } else {
            $mtypes_count[$fmtype] = 1;
            $mtypes_sum[$fmtype] = $lmt['value'];
        }
        $mtypes_unit[$fmtype] = $lmt['unit'];
    }
}
$mean_meas = array();  // array(mtype => mean)
foreach ($mtypes as $mtype) {
    $mean_meas[$mtype] = $mtypes_sum[$mtype]/$mtypes_count[$mtype];
}

include 'head.php';
head(ucfirst($search_res['binomial_name']), $lang);
?>

<body>
<?php include 'nav.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <?php
            echo '<img src="'.$pic_path.
                '" class = "pic img-responsive">';
            ?>

            <table>
                <?php
                foreach ($mean_meas as $meas => $mean) {
                    echo '<tr><td>' . ucfirst($meas) . '</td><td>' . $mean .
                        '</td><td>' . $mtypes_unit[$meas] . '</td></tr>';
                }
                ?>
            </table>
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
                        <tr>
                            <td><?php echo $sp_data[0] ?></td>
                            <td><?php echo ucfirst($search_res['kingdom'])?></td>
                        </tr>
                        <tr>
                            <td><?php echo $sp_data[1] ?></td>
                            <td><?php echo ucfirst($search_res['phylum'])?></td>
                        </tr>
                        <tr>
                            <td><?php echo $sp_data[2] ?></td>
                            <td><?php echo ucfirst($search_res['class'])?></td>
                        </tr>
                        <tr>
                            <td><?php echo $sp_data[3] ?></td>
                            <td><?php echo ucfirst($search_res['order_s'])?></td>
                        </tr>
                        <tr>
                            <td><?php echo $sp_data[4] ?></td>
                            <td><?php echo ucfirst($search_res['family'])?></td>
                        </tr>
                        <tr>
                            <td><?php echo $sp_data[5] ?></td>
                            <td><?php echo ucfirst($search_res['genus'])?></td>
                        </tr>
                    </table>
                    <?php echo show_individuals($nfoll) ?>
                    <form action="search_followed.php#result" method="post">
                        <input type="hidden" name="idspecies[]" readonly
                        value="<?php echo $idspecies ?>">
                        <button type="submit" class="data-modif btn btn-default btn-xs">
                            <?php echo $see_them ?>
                        </button>
                    </form>
                    <p id="wikintro">
                        <?php echo $wikintro?>
                    </p>
                    <?php if ($edit) { ?>
                    <button type="button" class="data-modif btn btn-info btn-lg" data-toggle="modal"
                        data-target="#editSpeciesModal">
                        <?php echo $edit_info ?>
                    </button>
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="map-container">
                    <div id="foll_of_sp_map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($edit) { ?>
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
              <label for="iMod_biname">Binomial name</label>
              <input type="text" name="binomial_name" id="iMod_biname"
                class="form-control"
                value="<?php echo ucfirst($search_res['binomial_name']) ?>">
                <label for="iMod_kingdom"><?php echo $sp_data[0] ?></label>
              <input type="text" name="kingdom" id="iMod_kingdom"
                class="form-control"
                value="<?php echo ucfirst($search_res['kingdom']) ?>">
                <label for="iMod_phylum"><?php echo $sp_data[1] ?></label>
              <input type="text" name="phylum" id="iMod_phylum"
                class="form-control"
                value="<?php echo ucfirst($search_res['phylum']) ?>">
                <label for="iMod_class"><?php echo $sp_data[2] ?></label>
              <input type="text" name="class" id="iMod_class"
                class="form-control"
                value="<?php echo ucfirst($search_res['class']) ?>">
                <label for="iMod_order"><?php echo $sp_data[3] ?></label>
              <input type="text" name="order_s" id="iMod_order"
                class="form-control"
                value="<?php echo ucfirst($search_res['order_s']) ?>">
                <label for="iMod_family"><?php echo $sp_data[4] ?></label>
              <input type="text" name="family" id="iMod_family"
                class="form-control"
                value="<?php echo ucfirst($search_res['family']) ?>">
                <label for="iMod_genus"><?php echo $sp_data[5] ?></label>
              <input type="text" name="genus" id="iMod_genus"
                class="form-control"
                value="<?php echo ucfirst($search_res['genus']) ?>">
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"
            onclick="editSpecies(<?php echo $idspecies ?>)"
            data-dismiss="modal"><?php echo $updatei18n ?></button>
      </div>
    </div>

  </div>
</div>
<?php } ?>
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
<?php
foreach ($last_locs as $follocation) {
    echo 'var marker = L.marker([' . $follocation['latitude'] . ', ' .
        $follocation['longitude'] . ']).addTo(foll_of_sp_map);';
    echo 'marker.bindPopup("<b><a href=followed.php?id=' .
        $follocation['idFollowed'] . '>' .
        $follocation['idFollowed'] . '</a></b>");';
}
?>
</script>
</html>
