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
$columns = get_columns('Followed');
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
    echo "<option value=\"$field\">" . ucfirst($displayed_fields[$field]) .
        "</option>";
}
?>
        </select>
    </div>
    <button type="submit" class="btn btn-default">Rechercher animal</button>
</form>

<?php //Creates array
// Create first row containing fields
if (array_key_exists('search_field', $_POST))
{
    $search_field = $_POST['search_field'];
    echo '<table class="table" data-toggle="table" data-search="true">';
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

    // Create body containing results
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
