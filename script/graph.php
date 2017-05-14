<?php

function draw_graphs($idFollowed) {
    $types_measures = distinct_measure($idFollowed);
    foreach ($types_measures as $key) {
        graph_type($idFollowed, $key['type'], 'rgba(255, 0, 0, 1)', 'rgba(255, 0, 0, 0.5)');
    }
}

function graph_type($idFollowed, $type, $col1, $col2) {
    // col1 : couleur du trait
    // col 2 : couleur de l'aire sous la courbe
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
    <div class='col-lg-6'>
    <canvas id='$type' width='400' height='400'></canvas>
    </div>
    <script>
    var ctx = document.getElementById('$type');
    var scatterChart = new Chart(ctx, {
        type: 'line',
        data: {datasets: [
            {borderColor: '$col1',
             backgroundColor: '$col2',
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
