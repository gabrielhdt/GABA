<?php
if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// TODO: faire la traduction

//script d'origine
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier index_fr_FR.php
    include('i18n/fr_FR/login_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier index_en_GB.php
    include('i18n/en_UK/login_en_UK.php');
}
//fin du script d'origine

include('script/db.php');
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['password']) && !empty($_POST['password']))) {

        $test_conn = verify_login($_POST['login'], $_POST['password']); // on verfie qui se connecte (admin/staff/invalide)

        if ($test_conn == 'staff') { // si ok -> next page
            session_start(); // debut de la session
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['idstaff'] = id_from_login($_POST['login']);
            header('Location: membre_index.php'); // redirection vers la 'page index de session'
            exit();
        } elseif ($test_conn == 'admin') {
            session_start(); // debut de la session
            $_SESSION['login'] = $_POST['login'];
            header('Location: admin_index.php'); // redirection vers la 'page index de session'
            exit();
        } elseif (!$test_conn) {
            $erreur = 'Login ou mot de passe incorrect';
        }
    }
}



include 'head.php';
head("login", $lang);
?>

<body>
    <?php include 'nav.php'; ?>
    <div class="mybg">
    <div class="card card-container">
        <img id="profile-img" class="profile-img-card" src="image/logo_login.png" />
        <p id="profile-name" class="profile-name-card"></p>
        <form class="form-signin" action="login.php" method="post">
            <?php //afficher une éventuelle erreur
            if (isset($erreur)) {echo $erreur."<br>";}
            ?>
            <input type="text" class="form-control" name="login"
                   placeholder="<?php echo $login; ?>" required autofocus>
            <input type="password" class="form-control" name="password"
                   placeholder="<?php echo $pwd; ?>" required>
            <input type="submit" class="btn btn-lg btn-primary btn-block btn-signin"
                   name="connexion" value=<?php echo $conn; ?>>
        </form>
    </div><!-- /card-container -->
</div>
<!-- </div> -->
    <?php include 'footer.php'; ?>
</body>
</html>
