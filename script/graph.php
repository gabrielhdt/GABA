<?php

function draw_graphs($idFollowed)
{
    /* fonction qui dessine su besoin les graphiques du followed $idFollowed,
     * suivant les différents types de mesures se trouvant dans la bd
     */
    $colors = array('rgba(255, 0, 0, 1)', 'rgba(255, 0, 0, 0.5)',
                    'rgba(0, 255, 0, 1)', 'rgba(0, 255, 0, 0.5)',
                    'rgba(0, 0, 255, 1)', 'rgba(0, 0, 255, 0.5)'); // 3 couleurs pour les graphes
    $types_measures = distinct_measure($idFollowed);
    echo "
    <div class='container-fluid'>
    <div class='row'>";
    $i = 0;
    foreach ($types_measures as $key) {
        graph_type(
            $idFollowed,
            $key['type'],
            $colors[$i % 6],
                   $colors[($i + 1) % 6]
        );
        $i += 2;
    }
    echo "</div></div>";
}

function graph_type($idFollowed, $type, $col1, $col2)
{
    // col1 : couleur du trait
    // col 2 : couleur de l'aire sous la courbe
    $where['str'] = 'Measure.idFollowed=? AND MiscQuantity.type=?';
    $where['valtype'] = array(
        array('value' => $idFollowed, 'type' => PDO::PARAM_INT),
        array('value' => $type, 'type' => PDO::PARAM_STR)
    );
    $tables = <<<TBL
MiscQuantity INNER JOIN Measure ON MiscQuantity.idMeasure=Measure.idMeasure
TBL;
    $result = get_values(
        'DISTINCT MiscQuantity.value, MiscQuantity.unit,Measure.date_measure',
        $tables,
        array('where' => $where)
    );
    $val = array();
    $unit = array();
    $date_measure = array();
    foreach ($result as $key => $value) {
        $val[] = $value["value"];
        $unit[] = $value["unit"];
        $date_measure[] = $value["date_measure"];
    }
    if (count($val) > 2) { // si il n'y a moins de 3 measure pour le $type, on ne dessine pas le graphe
        $chart = "
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <canvas id='$type' width='100%' height='100%'></canvas>
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
        for ($i = 0; $i < count($val); $i++) {
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
}
