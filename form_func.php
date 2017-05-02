<?php
include 'db.php';
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

function create_species_list()
{
    $id_biname = array();
    $lines = get_values(array('idSpecies', 'binomial_name'), 'Species');
    foreach ($lines as $line)
    {
        $id_biname[$line['idSpecies']] = $line['binomial_name'];
    }
    create_choice_list($id_biname);
}

function create_facility_list()
{
    $lines = get_values(array('idFacility', 'name'), 'Facility');
    $id_faname = array();
    foreach ($lines as $line)
    {
        $id_faname[$line['idFacility']] = $line['name'];
    }
    create_choice_list($id_faname);
}
?>
