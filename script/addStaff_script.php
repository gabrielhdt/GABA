<?php
include '../db.php';

if( isset($_POST['nom'], $_POST['prenom'] ,$_POST['pwd1'] ,$_POST['pwd2'] ,$_POST['typeStaff'])){
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pwd1']) && !empty($_POST['pwd2']) && !empty($_POST['typeStaff'])) {
        if ($_POST['pwd1'] == $_POST['pwd2']) {
            add_staff($_POST['pwd1'], $_POST['typeStaff'], $_POST['prenom'], $_POST['nom']);
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo -1;
    }
}
elseif (isset($_POST['id'])) {
    delete_msg($_POST['id']);
}
elseif (isset($_POST['nom'], $_POST['email'], $_POST['msg'])) {
    add_line('messages', array('name' => $_POST['nom'], 'email' => $_POST['email']));
    echo 1;
}
?>
