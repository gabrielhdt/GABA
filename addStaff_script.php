<?php
// include 'db.php';

if( isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pwd1']) && isset($_POST['pwd2']) && isset($_POST['typeStaff']) &&
    !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pwd1']) && !empty($_POST['pwd2']) && !empty($_POST['typeStaff'])){
    if($_POST['pwd1'] == $_POST['pwd2']){
        // add_staff($_POST['pwd1'], $_POST['typeStaff'], $_POST['prenom'], $_POST['nom']);
        echo "Nouveau membre ajouté avec succès.";
    }
    else{
        echo "Les deux mots de passes doivent être identiques.";
    }
} else {
    echo "Tout les champs doivent être remplis !";
}
?>
