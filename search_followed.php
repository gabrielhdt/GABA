<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "form_func.php";
include "head.php";

$dateregex = "\d{4}[-.\/][01]?\d[-.\/][0-3]?\d";

function create_tablehead($colfoll, $labels)
{
    /* $search_field must be a columns name
     * $displayed fields array ($col_name => $displayed_value
     */
    echo '<tr>';
    for ($i = 0 ; $i < count($colfoll) ; $i++)
    {
        echo '<th data-field="'.$colfoll[$i].'" data-sortable="true">';
        echo $labels[$i];
        echo '</th>';
    }
    echo '</tr>';

}

function create_tablebody($fields, $tables, $where, $constraints)
{
    /* basically, a get_values with table creation,
     * refer to db.php, get_values doc for more info
     */
    $search_res = get_values($fields, $tables, $where, $constraints);
    foreach ($search_res as $line)
    {
        echo '<tr>';
        foreach ($fields as $field)
        {
            echo '<td>';
            echo ucwords($line[$field]);
            echo '</td>';
        }
        echo '</tr>';
    }
}
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
    'Followed.idFollowed',
    'Species.binomial_name',
    'Facility.name',
    'Followed.gender',
    'Followed.birth', 'Followed.death', 'Followed.health'
);
echo "<table id='table'
    class='table'
    data-toggle='table'
    data-search='true'
    data-pagination='true'
    data-detail-view='true'
    data-detail-formatter='detail_formatter'
    data-show-footer='true'>";
echo '<thead>';
create_tablehead($colfoll, $labels);
echo '</thead>';
echo '<tbody>';
create_tablebody($fields, $tables, $where, $constraints);
echo '</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
<script>
var $table = $('#table');
function detail_formatter(index, row) {
    var html = [];
    var picpath = '';
    $.ajax({url: 'script/search_script.php',
        type: 'post',
        data: {
            id: row['idFollowed'],
            table: 'Followed'
        },
        success: function(output) {
            if (output) {
                html.push('<img src="'+output+'" class="img-responsive>');
            }
        },
    });
    $.ajax({url: 'script/search_script.php',
        type: 'post',
        data: { id: row['idFollowed'],
            action: 'location'
        },
        success: function(output)
        {
            /* output ought to be geolocation data
             */
            html.push('Yet to come...');
        },
    });
    return html.join('');
}
</script>
</html>
