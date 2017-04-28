<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "head.php";

function create_fields_array($columns)
{
    /* Creates the list of fields and they display labels
     * if the field has nothing special, it is only capitalised
     * if the field is a foreign key, the displayed field is the capitalised
     * table name to which the key refers
     * columns: result of show columns from (e.g. Followed);
     */
    $displayed_fields = array();  //field => displayed
    $keys_tables = main_tables_from_keys();
    foreach ($columns as $col_specs)
    {
        $field = $col_specs['Field'];
        if ($col_specs['Key'] == 'MUL')
        {
            $displayed_fields[$field] = $keys_tables[$field];
        }
        else $displayed_fields[$field] = $field;
    }
    return $displayed_fields;
}

function create_choice_list($disp_fields)
{
    /* Creates a choice list
     * disp_fields array (column name => displayed name)
     */
    foreach ($disp_fields as $col => $disp)
    {
        echo "<option value=\"$col\">" . ucfirst($disp) . "</option>";
    }
}

function create_tablehead($search_field, $colnames, $disp_fields)
{
    /* $search_field must be a columns name
     * $displayed fields array ($col_name => $displayed_value
     */
    echo '<tr>';
    foreach ($colnames as $colname)
    {
        echo "<th data-filed=\"$colname\" data-sortable=\"true\">";
        echo ucfirst($disp_fields[$colname]);
        echo '</th>';
    }
    echo '</tr>';

}

function create_tablebody($colnames)
{
    /* colnames array containing column names, with
     * search_field first
     */
    //$viewname = 'followedsearch';
    //$tables = array('Followed' => 'idSpecies', 'Species' => 'idSpecies');
    //$columns = joined_view($viewname, $tables);
    $search_res = get_values($colnames, 'Followed');
    // Creates array without search field
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
<?php //Creates list of choices

$columns = get_columns('Followed');
$disp_fields = create_fields_array($columns);
create_choice_list($disp_fields);
?>
        </select>
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>

<?php
if (array_key_exists('search_field', $_POST))
{
    $colnames = array_keys($disp_fields);
    if ($colnames[0] != $search_field)
    {
        $keysf = array_search($search_field, $colnames);
        $buf = $colnames[0];
        $colnames[0] = $search_field;
        $colnames[$keysf] = $buf;
    }
    echo '<table class="table" data-toggle="table" data-search="true">';
    echo '<thead>';
    create_tablehead($_POST['search_field'],$colnames, $disp_fields);
    echo '</thead>';
    echo '<tbody>';
    create_tablebody($_POST['search_field'], $colnames);
    echo '</tbody>';
    echo '</table>';
}
?>
<?php include "footer.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table-locale-all.min.js"
    type="text/javascript" charset="utf-8"></script>
</body>
</html>
