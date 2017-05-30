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
    $relationships = get_values($fields, $table, array('where' => $where));
    $relships_noself = array();
    foreach ($relationships as $relationship)
    {
        if ($relationship['idfollowed1'] == $idfollowed) {
            $redundant_id = 'idfollowed1';
            $valid_id = 'idfollowed2';
        } else {
            $redundant_id = 'idfollowed2';
            $valid_id = 'idfollowed1';
        }
        unset($relationship[$redundant_id]);
        $relationship[$valid_id] = '<a href="followed.php?id=' .
            $relationship[$valid_id] . '">' . $relationship[$valid_id] .
            '</a>';
        array_push($relships_noself, $relationship);
    }
    $table = edi_table($relships_noself, 'edit_relship', $idfollowed);
    return($table);
}

function get_all_locations($idfollowed) {
    $fields = 'latitude, longitude, date_measure';
    $tables = <<<TBL
Location INNER JOIN Measure ON Location.idMeasure=Measure.idMeasure
TBL;
    $where['str'] = 'Measure.idFollowed=?';
    $where['valtype'] = array(
        array('value' => $idfollowed, 'type' => PDO::PARAM_INT)
    );
    $params = array('where' => $where, 'orderby' => 'date_measure');
    $locations = get_values($fields, $tables, $params);
    return($locations);
}

function diff_locations($locations) {
    /* Differs visually locations, based on timestamp
     * locations must be sorted
     */
    //Parameters
    $minop = 0.2;  //Minimum opacity
    $maxop = 1;  //Maximum opacity
    $mind = date_create($locations[0]['date_measure']);
    $maxd = date_create(end($locations)['date_measure']);
    reset($locations);
    $mint = (int) date_format($mind, 'U'); //Min time (seconds since epoch)
    $maxt = (int) date_format($maxd, 'U'); //Max as above

    function opfct($t, $mint, $maxt, $minop, $maxop) {
        return($minop + ($maxop-$minop)*($t - $mint)/($maxt - $mint));
    }
    $opacities = array();
    for ($i = 0; $i < count($locations); $i++) {
        $date = date_create($locations[$i]['date_measure']);
        $t = date_format($date, 'U');
        $opacities[$i] = opfct($t, $mint, $maxt, $minop, $maxop);
    }
    return($opacities);
}
?>
