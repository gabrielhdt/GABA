<style media="screen">
    th{
        border: 1px solid black;
    }
</style>

<?php

include 'db.php';

function table_type($idFollowed, $type){
    $result = get_values(
        array('MiscQuantity.value, MiscQuantity.unit, Measure.date_measure'),
        'MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure = Measure.idMeasure',
        $where=array(array('binrel' => '=', 'field' => 'Measure.idFollowed', 'value' =>  "$idFollowed", 'type' => PDO::PARAM_STR),
                     array('binrel' => '=', 'field' => 'MiscQuantity.type', 'value' =>  "$type", 'type' => PDO::PARAM_STR))); // + and id =, order by date_measure
    $tableType = "<h1>$type</h1>\n<table>\n";
    $val = array();
    $unit = array();
    $date_measure = array();
    foreach ($result as $key => $value) {
        $val[] = $value["value"];
        $unit[] = $value["unit"];
        $date_measure[] = $value["date_measure"];
    }
    $tableType .= "<tr>\n";
    $tableType .= "<th></th>\n";
    for ($i = 0; $i < count($date_measure); $i++) {
        $tableType .= "<th>$date_measure[$i]</th>\n";
    }
    $tableType .= "</tr>\n<tr>\n";
    $tableType .= "<th>$type</th>\n";
    for ($i = 0; $i < count($val); $i++) {
        $tableType .= "<th>$val[$i] $unit[$i]</th>\n";
    }
    $tableType .= "</tr>\n</table>";
    echo $tableType;
}

function table ($idFollowed){
    $result = get_values(
        array('DISTINCT MiscQuantity.type'),
        'MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure = Measure.idMeasure',
        $where=array(array('binrel' => '=', 'field' => 'Measure.idFollowed', 'value' =>  $idFollowed, 'type' => PDO::PARAM_STR)));
    $table = "";
    foreach ($result as $key => $value) {
        table_type($idFollowed, $value['type']);
    }
}
table(1);
?>
