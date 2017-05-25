<?php // TODO javascript to check identicity if pw client side (disable button)

session_start ();
if (!isset($_SESSION['login'])) { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}

if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/perso_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/perso_en_UK.php');
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
        $where['str'] = 'idStaff=?';
        $where['valtype'] = array(
            array('value' => $_SESSION['idstaff'], 'type' => PDO::PARAM_INT)
        );
        $update['str'] = 'pwhash=?';
        $update['valtype'] = array(
            array('value' => $pwhash, 'type' => PDO::PARAM_STR)
        );
        update_line('Staff', $update, $where);
    }
}

head('Your account', $lang);

?>
<body>
<?php
include 'nav.php';
echo isset($err) ? $err : null
?>
<div class="container" id="change-pwd">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
        <div class="outer description">
            <div class="middle">
                <form action="perso.php" method="post" accept-charset="utf-8"
                    enctype="multipart/form-data" style="margin: auto; width: 400px;">
                    <?php echo $title ?>
                    <div class="input-group">
                        <input class="form-control" type="password" name="old_pw"
                               id="old_pw" placeholder="<?php echo $old_pwd; ?>">
                        <input class="form-control" type="password" name="new_pw"
                               id="new_pw" placeholder="<?php echo $new_pwd1; ?>">
                        <input class="form-control" type="password" name="conf_pw"
                               id="conf_pw" placeholder="<?php echo $new_pwd2; ?>">
                    </div>
                    <button type="submit" class="btn btn-default"><?php echo $confirm; ?></button>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include 'footer.php' ?>
</body>
</html>
