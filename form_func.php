<?php
function create_choice_list($disp_fields, $defsel=null)
{
    /* Creates a choice list
     * disp_fields array (column name => displayed name)
     * defsel: if a value should be selected by default
     * must be a key of $disp_fields
     * Doesn't include the <select name= id= class=> </select>
     */
    foreach ($disp_fields as $col => $disp)
    {
        if ($col == $defsel)
        {
            echo "<option selected value=\"$col\">".ucwords($disp).'</option>';
        }
        else
        {
            echo "<option value=\"$col\">" . ucwords($disp) . "</option>";
        }
    }
}

function create_tablehead($colid, $labels)
{
    /* $search_field must be a columns name
     * $displayed fields array ($col_name => $displayed_value
     */
    echo '<tr>';
    for ($i = 0 ; $i < count($colid) ; $i++)
    {
        echo '<th data-field="'.$colid[$i].'" data-sortable="true">';
        echo $labels[$i];
        echo '</th>';
    }
    echo '</tr>';

}

function create_tablebody($search_res)
{
    /* basically, a get_values with table creation,
     * refer to db.php, get_values doc for more info
     */
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
?>
