<?php

// regroupant les fonctions nécessaire au bon afficheage de la page foolowed.php

function simple_table($lines)
{
    /* Makes a simple table body
     */
    $table = '';
    foreach ($lines as $line)
    {
        $table .= '<tr>';
        foreach ($line as $value)
        {
            $table .= '<td>';
            $table .= ucfirst($value);
            $table .= '</td>';
        }
        $table .= '</tr>';
    }
    return($table);
}

function edi_table($lines, $modal, $arg_edit)
{
    /* js_func the javascript function called for editing,
     * edit_arg, key of line (in lines) passed to js_func, in addition to
     * the idfollowed
     */
    $table = '';
    foreach ($lines as $line)
    {
        $table .= '<tr>';
        foreach ($line as $value)
        {
            $table .= '<td>';
            $table .= ucfirst($value);
            $table .= '</td>';
        }
        $table .= '<td class="edit">';
        $edit = $line['type'];
        $table .= <<<GLPH
<span title="Add a new entry" class="glyphicon glyphicon-plus"
data-toggle="modal" data-target="#$modal"></span>
GLPH;
        $table .= '</td>';
        $table .= '</tr>';
    }
    return($table);
}

function meas_table($idfollowed)
{
    $measures = latest_meas_of($idfollowed);
    $table = simple_table($measures);
    return($table);
}

function relation_table($idfollowed)
{
    // Getting relationship information
    $fields = 'idfollowed1, idfollowed2, type_relation, begin, end';
    $table = 'Relation';
    $where = array();
    $where['str'] = 'idFollowed1=? OR idFollowed2=?';
    $where['valtype'] = array(
        array('value' => $idfollowed, 'type' => PDO::PARAM_INT),
        array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
    );
    $relationships = get_values($fields, $table, $where);
    $relships_noself = array();
    foreach ($relationships as $relationship)
    {
        $redundant_id = $relationship['idfollowed1'] == $idfollowed ?
            'idfollowed1' : 'idfollowed2';
        unset($relationship[$redundant_id]);
        array_push($relships_noself, $relationship);
    }
    $table = edi_table($relships_noself, 'edit_relship', $idfollowed);
    return($table);
}
?>
