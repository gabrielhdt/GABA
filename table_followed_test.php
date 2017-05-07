<?php
include 'db.php';

function type_measure ($idFollowed) {
    $result = get_values(
        array('DISTINCT MiscQuantity.type'),
        'MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure = Measure.idMeasure',
        $where=array(array('binrel' => '=', 'field' => 'Measure.idFollowed', 'value' =>  $idFollowed, 'type' => PDO::PARAM_STR)));
    $unique_type = array();
    foreach ($result as $key => $value) {
        $unique_type[] = $value['type'];
    }
    return unique_type;
}

function info_followed_table ($id) {
    $where['str'] = 'idFollowed=?';
    $where['valtype'] = array(array('value' => $id, 'type' => PDO::PARAM_INT));
    $infos = get_values_light("gender, birth, death, health, idSpecies, idFacility, annotation",
    "Followed", $where);
    $table = "<table>\n";
    foreach ($infos[0] as $key => $value) {
        $table .= "<tr><td>$key</td><td>".($value ? $value : 'null')."</td></tr>\n";
    }
    $table .= "</table>\n";
    echo $table;
}

info_followed_table(14);
?>
