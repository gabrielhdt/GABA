<?php
if (!isset($_SESSION['login']) && $_SESSION['login'] != "admin") { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
include 'head.php';
include 'script/db.php';
head("Administrateur");
?>
<body>
    <?php include "nav.php" ?>
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="add_staff">
                <h1>Ajout de personnel :</h1>
                <form>
                    <div id="alert">
                    </div>
                    <input class='form-control' name="nom" placeholder="Nom">
                    <input class='form-control' name="prenom" placeholder="Prenom">
                    <input class='form-control' id="p1" type="password" name="pwd1" placeholder="Mot de passe">
                    <input class='form-control' id="p2" type="password" name="pwd2" placeholder="Confirmation mot de passe">
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="veterinary">Veterinaire</label>
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="researcher">Chercheur</label>
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="technician">Technicien</label><br>
                    <input onclick="myAdd()" class="btn btn-success" name="submit_contact" value="Enregistrer">
                </form>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="messages">
                <h1>Messages :</h1>
                <div class='alert alert-danger alert-dismissable'>
                    <strong>Attention!</strong> La suppression d'un message est irréversible.
                </div>
                <?php list_msg(); ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
