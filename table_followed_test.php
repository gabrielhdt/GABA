<?php
include 'script/db.php';

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

function last_measure($id, $measure) {
    $where['str'] = 'idFollowed=? AND ';
    $where['valtype'] = array(array('value' => $id, 'type' => PDO::PARAM_INT));
    $measures = type_measure($id);

}


?>
