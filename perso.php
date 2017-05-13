<?php //TODO javascript to check identicity if pw client side (disable button)
session_start();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}
include 'head.php';
include 'db.php';
if (isset($_POST['old_pw'], $_POST['new_pw'], $_POST['conf_pw']))
{
    if ($_POST['new_pw'] != $_POST['conf_pw'])
    {
        $err = 'Confirmation failed, data might has been manipulated.';
    }
    elseif (!verify_login($_SESSION['login'], $_POST['old_pw']))
    {
        $err = 'Old password wrong or data manipulated.';
    }
    else
    {
        $pwhash = password_hash($_POST['new_pw'], PASSWORD_DEFAULT);
        $where = array(
            array(
                'field' => 'idStaff', 'value' => $_SESSION['idstaff'],
                'binrel' => '=', 'type' => PDO::PARAM_INT
            )
        );
        update_line('Staff', array('pwhash' => $pwhash), $where);
    }
}
?>
<html>
<?php
head('Your account');
include 'nav.php';
?>
<body>
<?php echo isset($err) ? $err : null ?>
<div class="row">
    <form action="perso.php" method="post" accept-charset="utf-8"
        enctype="multipart/form-data">
        <div class="input-group">
            <label for="old_pw">Enter current password</label>
            <input type="text" name="old_pw" id="old_pw">
            <label for="old_pw">Enter new password</label>
            <input type="text" name="new_pw" id="new_pw">
            <label for="old_pw">Confirm password</label>
            <input type="text" name="conf_pw" id="conf_pw">
        </div>
        <button type="submit" class="btn btn-default">Confirm</button>
    </form>
</div>
<?php include 'footer.php' ?>
</body>
</html>
