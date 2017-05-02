<?php
// session_start();
// if (!isset($_SESSION['login']) && $_SESSION['login'] != "admin") { // test si l'utilisateur est bien passé par le formulaire
//     header ('Location: login.php'); // sinon retour page login
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="fr">

<?php
include 'head.php';
include 'db.php';
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
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="vet">Veterinaire</label>
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="cher">Chercheur</label>
                    <label class="radio-inline"><input type="radio" name="typeStaff" value="tech">Technicien</label><br>
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
    <script type="text/javascript">
        function myAdd(){
            $.post(
                'script/addStaff_script.php',
                {   nom: $("input[name=nom]").val(),
                    prenom: $("input[name=prenom]").val(),
                    typeStaff: $("input[name=typeStaff]").val(),
                    pwd1: $("input[name=pwd1]").val(),
                    pwd2 : $("input[name=pwd2]").val()
                },
                function(data) {
                    if (data == 1) {
                        $("#alert").html("<div class='alert alert-success alert-dismissable'>\
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>\
<strong>Succès!</strong> Indicates a successful or positive action.</div>");
                        $("input[name=nom]").val('');
                        $("input[name=prenom]").val('');
                        $("input[name=pwd1]").val('');
                        $("input[name=pwd2]").val('');
                        $("input[name=typeStaff]").prop('checked', false);
                    } else if (data == 0) {
                        $("#alert").html("<div class='alert alert-warning alert-dismissable'>\
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>\
<strong>Attention!</strong> les mots de passe ne correspondent pas.</div>");
                    } else {
                        $("#alert").html("<div class='alert alert-warning alert-dismissable'>\
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>\
<strong>Attention!</strong> Au moins un champ est vide!</div>");
                    }
                }
            );
            // alert(p1);
        }

        function myDelete(id_msg){
            $.post(
                'script/addStaff_script.php',
                {id: id_msg}
            );
        }
    </script>
</body>
