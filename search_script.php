<?php
include 'db.php';
$_POST['action'] == 'refresh' ? refresh($_POST['viewname']) : null;

function refresh($viewname) {
    update_view($viewname);
}
