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

?>
