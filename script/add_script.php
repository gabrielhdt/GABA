<?php

if (isset($_POST['geoloc_lat'], $_POST['geoloc_long']) &&
    !empty($_POST['geoloc_lat']))
{
    $_SESSION['location'] =
        array(
            'lat' => $_POST['geoloc_lat'],
            'long' => $_POST['geoloc_long'],
        );
}

function get_geoloc()
{
    /* Get last entered geo location */
    return array_pop($geostack);
}
?>
