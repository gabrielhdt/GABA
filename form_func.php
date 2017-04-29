<?php
function create_choice_list($disp_fields)
{
    /* Creates a choice list
     * disp_fields array (column name => displayed name)
     * Doesn't include the <select name= id= class=> </select>
     */
    foreach ($disp_fields as $col => $disp)
    {
        echo "<option value=\"$col\">" . ucfirst($disp) . "</option>";
    }
}

?>
