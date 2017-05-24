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

// TODO: faire la traduction

//script d'origine
// if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
//     include('i18n/fr_FR/index_fr_FR.php');
// } elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
//     include('i18n/en_UK/index_en_UK.php');
// }
//fin du script d'origine

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
<div class="container" style="background-color:rgba(170, 170, 170, 0.5);">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
        <div class="outer description">
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
