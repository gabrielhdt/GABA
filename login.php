<?php
include('db.php');
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
?>
<html>

    <?php
    include 'head.php';
    head("login");
    include 'nav.php';
    ?>
    <link rel="stylesheet" href="css/login.css">

    <body>
        <div class="mybg">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="image/logo_login.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" action="login.php" method="post">
                <?php //afficher une éventuelle erreur
                if (isset($erreur)) {echo $erreur."<br>";}
                ?>
                <input type="text" class="form-control" name="login" placeholder="Login" required autofocus>
                <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                <input type="submit" class="btn btn-lg btn-primary btn-block btn-signin" name="connexion" value="Connexion">
            </form>
        </div><!-- /card-container -->
    </div>
    <!-- </div> -->
        <?php include 'footer.php'; ?>
    </body>
</html>
