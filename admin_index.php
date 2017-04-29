<?php
// session_start();
// if (!isset($_SESSION['login']) && $_SESSION['login'] != "admin") { // test si l'utilisateur est bien passé par le formulaire
//     header ('Location: login.php'); // sinon retour page login
//     exit();
// }
include('db.php');
if (isset($_POST['submit_contact']) && $_POST['submit_contact'] == 'Enregistrer') {
    if (isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['pwd1']) && !empty($_POST['pwd1']) &&
        isset($_POST['prenom']) && !empty($_POST['prenom']) && isset($_POST['pwd2']) && !empty($_POST['pwd2'])) {

        $pwd_test = $_POST['pwd1'] == $_POST['pwd2'];

        if ($pwd_test) { // si ok -> next page
            add_staff($_POST['pwd1'], $_POST['pwd1'], $_POST['nom'], $_POST['prenom']);
            $msg = "Staff ajouté !";
        }
        elseif (!$pwd_test) {
            $msg = "Erreur !"; // debut de la session
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php include "head.php" ?>
<style>
    #msg {
        background-color: lightgrey;
        padding: 20px;
        border-radius: 10px;
        margin: 15px;
    }
    #messages {
        border-left: 2px solid black;
        border-top: 1px solid rgb(150, 150, 150);
    }
    #messages h1 {
        text-align: center;
    }
    #add_staff {
        text-align: center;
        padding: 20px 0;
        border-top: 1px solid rgb(150, 150, 150);
    }
    #add_staff input[name='nom'],
    #add_staff input[name='prenom'],
    #add_staff input[name='pwd1'],
    #add_staff input[name='pwd2'],
    #add_staff input[name='submit_contact'] {
        margin: 15px 0;
    }
    #add_staff h1{
        padding-bottom: 35px;
    }
    #add_staff {
        padding: 0 15px;
    }
    #add_staff form button{
        margin: 15px 0;
    }
    #add_staff p {
        color : red;
    }
</style>
<body>
    <?php include "nav.php" ?>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="add_staff">
                <h1>Ajout de personnel :</h1>
                <form action="admin_index.php" method="post">
                    <p><?php if (isset($msg)){echo $msg;} ?></p>
                    <input class='form-control' name="nom" placeholder="Nom" required>
                    <input class='form-control' name="prenom" placeholder="Prenom" required>
                    <input class='form-control' name="pwd1" placeholder="Mot de passe" required>
                    <input class='form-control' name="pwd2" placeholder="Confirmation mot de passe" required>
                    <label class="checkbox-inline"><input type="checkbox" value="vet">Veterinaire</label>
                    <label class="checkbox-inline"><input type="checkbox" value="">Chercheur</label>
                    <label class="checkbox-inline"><input type="checkbox" value="">Technicien</label><br>
                    <input class="btn btn-success" type="submit" name="submit_contact" value="Enregistrer">
                </form>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="messages">
                <h1>Messages :</h1>
                <div id="msg">
                    Nom :
                    <hr>
                    E-mail :
                    <hr>
                    Message :
                </div>
                <div id="msg">
                    Nom :
                    <hr>
                    E-mail :
                    <hr>
                    Message :
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
