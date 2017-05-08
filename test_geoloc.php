<?php
session_start();
if (!isset($_SESSION['login'], $_SESSION['idstaff']) || $_SESSION['login'] == 'admin') { // test si l'utilisateur est bien passé par le formulaire
    header ('Location: login.php'); // sinon retour page login
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php
include "db.php";
include "form_func.php";
include "head.php";
include "script/add_script.php"
?>
<body>
<label class="checkbox-inline">
    <input type="checkbox" id="use_geoloc" name="use_geoloc" value="on">
    Use current position as animal's
</label>
<button class="btn btn-success" id="submitbtn" type="submit" name="add_followed">Enregistrer</button>
<?php
    echo "Latitude & long: ".$_SESSION['location']['lat'].' & '.$_SESSION['location']['long'];
?>
<?php include "footer.php" ?>
</body>
<script>
document.getElementById('use_geoloc').onclick = function() {
    if (this.checked) {
        document.getElementById('submitbtn').disabled = true;
        navigator.geolocation.getCurrentPosition(function(position) {
            console.log("Sending data: " + position.coords.latitude +
                position.coords.longitude);
            $.ajax({
                url: 'script/add_script.php',
                type: 'post',
                data: {
                    geoloc_lat: position.coords.latitude,
                    geoloc_long: position.coords.longitude,
                },
                success: function(data) {
                    console.log("Data sent.");
                    document.getElementById('submitbtn').disabled = false;
                }
            });
        });
    }
    else {
        document.getElementById('submitbtn').disabled = false;
    }
}
</script>
</html>
