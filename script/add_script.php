<?php

$geostack = array();
if (isset($_POST['geoloc']) && !empty($_POST['geoloc']))
{
    array_push($geostack, $_POST['geoloc']);
}

function get_geoloc()
{
    /* Get last entered geo location */
    return array_pop($geostack);
}
?>
