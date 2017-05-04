<?php

$geostack = array();
if (isset($_POST['geoloc_lat'], $_POST['geoloc_long'], $_POST['geoloc_time']) &&
    !empty($_POST['geoloc_lat']))
{
    array_push($geostack,
        array(
            'lat' => $_POST['geoloc_lat'],
            'long' => $_POST['geoloc_long'],
            'time' => $_POST['geoloc_time']
        )
    );
}

function get_geoloc()
{
    /* Get last entered geo location */
    return array_pop($geostack);
}
?>
