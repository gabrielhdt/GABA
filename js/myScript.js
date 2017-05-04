/*******************************
fonctions de admin_index.php
*******************************/

function myAdd() {
    // fonction ajout de staff par admin, et affichage d'un msg d'information
    $.post(
        'script/addStaff_script.php', {
            nom: $("input[name=nom]").val(),
            prenom: $("input[name=prenom]").val(),
            typeStaff: $("input[name=typeStaff]").val(),
            pwd1: $("input[name=pwd1]").val(),
            pwd2: $("input[name=pwd2]").val()
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
}

function myDelete(id_msg) {
    // fonction qui efface les messages par l'admin
    $.post(
        'script/addStaff_script.php', {
            id: id_msg
        }
    );
}
/*******************************
fonctions de index.php
*******************************/
function addMsg() {
    // ajout d'un msg dans la bbd
    $.post(
        'script/addStaff_script.php', {
            nom: $("input[name=name]").val(),
            email: $("input[name=email]").val(),
            msg: $("textarea[name=msg]").val()
        },
        function(data) {
            $("#res_msg").html("<div class='alert alert-success alert-dismissable'>\
<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>\
<strong>Succès!</strong> Votre message a bien été enregistré.</div>");
            $("input[name=name]").val('');
            $("input[name=email]").val('');
            $("textarea[name=msg]").val('');
        });
}

// permet un scroll "smooth" vers la section contact
$('a[href^="index.php#"]').click(function() {
    var the_id = 'contact';

    $('html, body').animate({
        scrollTop: $(the_id).offset().top
    }, 'slow');
    return false;
});
