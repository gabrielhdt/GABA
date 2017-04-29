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

function create_fields_array($columns)
{
    /* Creates the list of fields and they display labels
     * if the field has nothing special, it is only capitalised
     * if the field is a foreign key, the displayed field is the capitalised
     * table name to which the key refers
     * columns: result of show columns from (e.g. Followed);
     * returns: array (column name => displayed name)
     * and array (foreign key column number => other table name)
     */
    $displayed_fields = array();
    $forkey = array();
    $keys_tables = main_tables_from_keys();
    $i = 0;
    foreach ($columns as $col_specs)
    {
        $field = $col_specs['Field'];
        if ($col_specs['Key'] == 'MUL')
        {
            $displayed_fields[$field] = $keys_tables[$field];
            $forkey[$i] = $keys_tables[$field];
        }
        else $displayed_fields[$field] = $field;
        $i++;
    }
    return(array($displayed_fields, $forkey));
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
        'death');
    $labels = array('Identifier', 'Species', 'Facility', 'Gender', 'Birth',
        'Death');
    $colview = array('idFollowed', 'binomial_name', 'common_name', 'gender',
        'birth', 'death');
    if ($colfoll[0] != $_POST['search_field'])
    {
        $keysf = array_search($_POST['search_field'], $colfoll);
        swap($colfoll, 0, $keysf);
        swap($labels, 0, $keysf);
        swap($colview, 0, $keysf);
    }
    echo '<table class="table" data-toggle="table" data-search="true">';
    echo '<thead>';
    create_tablehead($colfoll, $labels);
    echo '</thead>';
    $colfoll['idSpecies'] = 'binomial_name';
    echo '<tbody>';
    create_tablebody($colview, 'search_foll');
    echo '</tbody>';
    echo '</table>';
}
?>
<?php include "footer.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table-locale-all.min.js"
    type="text/javascript" charset="utf-8"></script>
</body>
</html>
