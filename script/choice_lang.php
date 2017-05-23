<?php

if (isset($_POST['lang'])) {
    //définition de la durée du cookie (1 an)
    $expire = 365*24*3600;
    setcookie('lang', $_POST['lang'], time() + $expire, "/");
}

?>
