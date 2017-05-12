<?php

include 'db.php';

function graph_type($idFollowed, $type, $idCanevas) {
    $result = get_values(
        array('DISTINCT MiscQuantity.value, MiscQuantity.unit, Measure.date_measure'),
        'MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure = Measure.idMeasure',
        $where=array(array('binrel' => '=', 'field' => 'Measure.idFollowed', 'value' =>  "$idFollowed", 'type' => PDO::PARAM_STR),
                     array('binrel' => '=', 'field' => 'MiscQuantity.type', 'value' =>  "$type", 'type' => PDO::PARAM_STR))); // + and id =, order by date_measure
    $val = array();
    $unit = array();
    $date_measure = array();
    foreach ($result as $key => $value) {
        $val[] = $value["value"];
        $unit[] = $value["unit"];
        $date_measure[] = $value["date_measure"];
    }
    $chart = "
    <script>
    var ctx = document.getElementById('$idCanevas');
    var scatterChart = new Chart(ctx, {
        type: 'line',
        data: {datasets: [
            {borderColor: 'rgba(19, 179, 9, 0.8)',
             backgroundColor: 'rgba(19, 179, 9, 0.3)',
             fill: true,
             label: '$type ($unit[0])',
             data: [";
    for ($i = 0; $i < count($val); $i++){
        $chart .= "{x: '$date_measure[$i]', y: $val[$i]}, ";
    }
    $chart = rtrim($chart, ', ');
    $chart .=  "]}]},
        options: {
            responsive: true,
            responsiveAnimationDuration: 500,
            scales: {xAxes: [{type: 'time'}]}
        }
        });
        </script>";
    echo $chart;
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
    </head>
    <body>
    <canvas id="myChart" width="800" height="800"></canvas>
    <?php table_type(1, 'weight', 'myChart'); ?>

    </body>
</html>
