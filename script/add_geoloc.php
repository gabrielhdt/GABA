<?php
include 'db.php';
$idmeasure = add_line('Measure',
    array(
        'idFollowed' => $_POST['idfollowed'],
        'idStaff' => $_POST['idstaff']
    )
);
$coords = explode(',', $_POST['geoloc']);
add_line('Location',
    array(
        'latitude' => (float) $coords[0],
        'longitude' => (float) $coords[1],
        'idMeasure' => $idmeasure
    )
);
