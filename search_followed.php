<!DOCTYPE HTML>
<html>
<?php include "db.php";
include 'form_func.php';
include "head.php";

function swap(&$arr, $ind_a, $ind_b)
{
    $buf = $arr[$ind_b];
    $arr[$ind_b] = $arr[$ind_a];
    $arr[$ind_a] = $buf;
}

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

function create_tablebody($colnames, $view, $where)
{
    /* colnames array containing column names, with
     * search_field first
     */
    if (!view_exists($view))
    {
        update_view($view);
    }
    $search_res = get_whereplus($colnames, $view, $where);
    foreach ($search_res as $line)
    {
        echo '<tr>';
        foreach ($colnames as $colname)
        {
            echo '<td>';
            echo ucwords($line[$colname]);
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
<form action="search_followed.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="sel_followed">Rechercher animal selon:</label>
        <select name="search_field" id="sel_followed" class="form-control">
            <option value='idFollowed'>Identifier</option>
            <option value='gender'>Gender</option>
            <option value='birth'>Birth</option>
            <option value='death'>Death</option>
            <option value='idSpecies'>Species</option>
            <option value='idFacility'>Facility</option>
        </select>
        <label for="sel_species">Of species:</label>
        <select name="idspecies[]" id="sel_species" class="form-control" multiple>
        <?php create_choice_list($id_biname); ?>
        </select>
        <label for="sel_facility">In facilities:</label>
        <select name="idfacility[]" id="sel_facility" class="form-control" multiple>
        <?php create_choice_list($id_faname); ?>
        </select>
        <br>
        <label for="sel_birth">Born between:</label>
        <input type="date" name="lowbirth" class="form-control" id="lowbirth" placeholder="After the">
        <input type="date" name="upbirth" class="form-control" id="upbirth" placeholder="Before the">
        <br>
        <label class="radio-inline"><input type="radio" name="gender" value="m">Male</label>
        <label class="radio-inline"><input type="radio" name="gender" value="f">Female</label>
        <label class="radio-inline"><input type="radio" name="gender" value="h">Hermaphrodite</label><br>
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>
<?php
$where = array();
if (isset($_POST['lowbirth']))
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
elseif (isset($_POST['upbirth']))
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
elseif (isset($_POST['gender']))
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
elseif (isset($_POST['idspecies']))
{
    array_push($where,
        array(
            'binrel' => 'IN',
            'field' => 'idSpecies',
            'value' => $_POST['idspecies'],
            'type' => PDO::PARAM_INT
        )
    );
}
elseif (isset($_POST['idfacility']))
{
    array_push($where,
        array(
            'binrel' => 'IN',
            'field' => 'idFacility',
            'value' => $_POST['idfacility'],
            'type' => PDO::PARAM_INT
        )
    );
}
$colfoll = array('idFollowed', 'idSpecies', 'idFacility', 'gender', 'birth',
    'death', 'health');
$labels = array('Identifier', 'Species', 'Facility', 'Gender', 'Birth',
    'Death', 'Health');
$colview = array('idFollowed', 'sp_binomial_name', 'fa_name', 'gender',
    'birth', 'death', 'health');
echo "<table id='table'
    class='table'
    data-toggle='table'
    data-search='true'
    data-show-refresh='true'
    data-pagination='true'
    data-detail-view='true'
    data-detail-formatter='detail_formatter'
    data-show-footer='true'>";
echo '<thead>';
create_tablehead($colfoll, $labels);
echo '</thead>';
echo '<tbody>';
create_tablebody($colview, 'vSearchFoll', $where);
echo '</tbody>';
echo '</table>';
?>
<?php include "footer.php"; ?>
</body>
<script>
var $table = $('#table');
$table.on('refresh.bs.table', function (e) {
        $.ajax({
            url: 'search_script.php',
            type: 'post',
            data: {viewname: 'vSearchFoll'},
            success: function(output) {
                console.log('Refreshed');
            }
    });
});

function detail_formatter(index, row) {
    var html = [];
    var picpath = '';
    $.ajax({url: 'search_script.php',
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
    $.ajax({url: 'search_script.php',
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
