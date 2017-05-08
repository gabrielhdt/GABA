<?php

if (isset($_POST['lat'], $_POST['longi']) &&
    !empty($_POST['lat']))
{
    $_SESSION['location'] =
        array(
            'lat' => $_POST['lat'],
            'long' => $_POST['longi'],
        );
}
?>
