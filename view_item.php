<!DOCTYPE HTML>
<html>
<?php include "db.php";
include "head.php";
?>
<body>
<form action="view_item.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="sel_followed">Rechercher animal selon:</label>
        <select name="search_field" id="sel_followed" class="form-control">
<?php //Creates list of choices
function create_fields_array($columns)
{
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

function create_choice_list($displayed_fields)
{
    foreach ($displayed_fields as $field => $disp)
    {
        echo "<option value=\"$field\">" . ucfirst($disp) . "</option>";
    }
}
$columns = get_columns('Followed');
$displayed_fields = create_fields_array($columns);
create_choice_list($displayed_fields);
?>
        </select>
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>

<?php //Creates array
function create_tablehead($search_field, $columns, $displayed_fields)
{
    //$search_field must be a columns name
    //$columns result of SHOW columns FROM
    //$displayed fields array ($col_name => $displayed_value
    echo '<thead><tr>';
    echo '<th data-filed="s_field" data-sortable="true">' .
        ucfirst($search_field) . '</th>';
    foreach ($columns as $col_specs)
    {
        $field = $col_specs['Field'];
        $line_beg = "<th data-filed=\"$field\" data-sortable=\"true\">";
        $line_end = '</th>';
        // Ceinture & bretelles, fishy management of search_field
        // (becomes Species instead of idSpecies
        if (mb_strtolower($field) != mb_strtolower($search_field) &&
        mb_strtolower($displayed_fields[$field] != mb_strtolower($search_field)))
        {
            echo $line_beg . ucfirst($displayed_fields[$field]) . $line_end;
        }
    }
    echo '</tr></thead>';

}
if (array_key_exists('search_field', $_POST))
{
    echo '<table class="table" data-toggle="table" data-search="true">';
    create_tablehead($_POST['search_field'], $columns, $displayed_fields);
    echo '<tbody>';
    echo '</tbody>';
    echo '</table>';
}
?>
<?php include "footer.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table-locale-all.min.js"
    type="text/javascript" charset="utf-8"></script>
</body>
</html>
