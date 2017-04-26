<?php
include('db.php');
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') { // test si pas de champs vide
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {

        $test_conn = verify_login($_POST['login'], $_POST['password']); // on verfie login et pwd (bool)
        if ($data) { // si ok -> next page
            session_start(); // debut de la session
            $_SESSION['login'] = $_POST['login'];
            header('Location: membre_index.php'); // redirection vers la 'page index de session'
            exit();
        }
        else {
            $erreur = 'Login ou mot de passe incorrect';
        }
    }
    else {
    $erreur = 'Au moins un des champs est vide.';
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
        if (isset($erreur)) echo '<br /><br />',$erreur;
        ?>
        <form action="index.php" method="post" autofocus>
            <input type="text" name="login"
            value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>" required>
            <input type="password" name="password"
            value="<?php if (isset($_POST['password'])) echo htmlentities(trim($_POST['password'])); ?>" required>
            <input type="submit" name="connexion" value="Connexion">
        </form>
    </body>
</html>
