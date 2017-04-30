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
        echo '<th data-filed="'.$colfoll[$i].'" data-sortable="true">';
        echo $labels[$i];
        echo '</th>';
    }
    echo '</tr>';

}

function create_tablebody($colnames, $view)
{
    /* colnames array containing column names, with
     * search_field first
     */
    if (!view_exists($view))
    {
        update_view($view);
    }
    $search_res = get_values($colnames, $view);
    foreach ($search_res as $line)
    {
        echo '<tr>';
        foreach ($colnames as $colname)
        {
            echo '<td>';
            echo $line[$colname];
            echo '</td>';
        }
        echo '</tr>';
    }
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
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>
<?php
if (array_key_exists('search_field', $_POST))
{
    $colfoll = array('idFollowed', 'idSpecies', 'idFacility', 'gender', 'birth',
        'death', 'health');
    $labels = array('Identifier', 'Species', 'Facility', 'Gender', 'Birth',
        'Death', 'Health');
    $colview = array('idFollowed', 'sp_binomial_name', 'fa_name', 'gender',
        'birth', 'death', 'health');
    if ($colfoll[0] != $_POST['search_field'])
    {
        $keysf = array_search($_POST['search_field'], $colfoll);
        swap($colfoll, 0, $keysf);
        swap($labels, 0, $keysf);
        swap($colview, 0, $keysf);
    }
    echo "<table class='table' data-toggle='table' data-search='true' data-pagination='true'>";
    echo '<thead>';
    create_tablehead($colfoll, $labels);
    echo '</thead>';
    echo '<tbody>';
    create_tablebody($colview, 'vSearchFoll');
    echo '</tbody>';
    echo '</table>';
}
?>
<?php include "footer.php"; ?>
</body>
</html>
