<?php
include('db.php');
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['password']) && !empty($_POST['password']))) {

        $test_conn = verify_login($_POST['login'], $_POST['password']); // on verfie login et pwd (bool)

        if ($test_conn) { // si ok -> next page
            session_start(); // debut de la session
            $_SESSION['login'] = $_POST['login'];
            header('Location: membre_index.php'); // redirection vers la 'page index de session'
            exit();
        } else {
            $erreur = 'Login ou mot de passe incorrect.';
        }
    }
}
?>
<html>
    <head>
        <title>Accueil</title>
    </head>

    <body>
        Connexion à l'espace membre :<br />
        <?php //afficher une éventuelle erreur
            if (isset($erreur)) {echo $erreur."<br>";}
        ?>
        <form action="login.php" method="post" autofocus>
            <input type="text" name="login" required>
            <input type="password" name="password" required>
            <input type="submit" name="connexion" value="Connexion">
        </form>
    </body>
</html>
