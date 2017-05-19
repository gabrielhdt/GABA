<?php // TODO javascript to check identicity if pw client side (disable button)

session_start ();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}
include 'head.php';
include 'script/db.php';
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
<div class="container" style="background-color:rgba(170, 170, 170, 0.5);">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
        <div class="description">
            <div class="middle">
                <form action="perso.php" method="post" accept-charset="utf-8"
                    enctype="multipart/form-data" style="margin: auto; width: 400px;">
                    <h3>Midifiez votre mot de passe:</h3>
                    <div class="input-group">
                        <input class="form-control" type="password" name="old_pw"
                               id="old_pw" placeholder="Mot de passe actuel">
                        <input class="form-control" type="password" name="new_pw"
                               id="new_pw" placeholder="Nouveau mot de passe">
                        <input class="form-control" type="password" name="conf_pw"
                               id="conf_pw" placeholder="Confirmation du mot de passe">
                    </div>
                    <button type="submit" class="btn btn-default">Confirm</button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include 'footer.php' ?>
</body>
</html>
