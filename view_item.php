<!DOCTYPE HTML>
<html>
<?php include "db.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">
    <title>GABA V1</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css"
        type="text/css" charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
        integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
         integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
         crossorigin=""></script>
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
<form action="view_item.php" method="post" accept-charset="utf-8"
    enctype="multipart/form-data">
    <div class="form-group">
        <label for="sel_followed">Rechercher animal selon:</label>
        <select name="search_field" id="sel_followed" class="form-control">
<?php //Creates list of choices
$columns = get_columns('Followed');
$displayed_fields = array();  //field => displayed
$keys_tables = tables_from_keys();
foreach ($columns as $col_specs)
{
    $field = $col_specs['Field'];
    if ($col_specs['Key'] == 'MUL')
    {
        $displayed_fields[$field] = ucfirst($keys_tables[$field]);
    }
    else $displayed_fields[$field] = ucfirst($field);
    echo "<option values=\"$field\">" . $displayed_fields[$field] . "</option>";
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
    echo <<<THEAD
<thead>
<tr>
<th data-filed="s_field" data-sortable="true">$search_field</th>
THEAD;
    foreach ($columns as $col_specs)
    {
        $field = $col_specs['Field'];
        $line_beg = "<th data-filed=\"$field\" data-sortable=\"true\">";
        $line_end = '</th>';
        echo $line_beg . ucfirst($displayed_fields) . $line_end;
    }
    echo '</tr></thead>';

    // Create body containing results
    echo '<tbody>';
    echo '</tbody>';
    echo '</table>';
}
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table-locale-all.min.js"
    type="text/javascript" charset="utf-8"></script>
</body>
</html>
