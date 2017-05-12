/*******************************
fonctions de admin_index.php
*******************************/

function myAdd() {
    // fonction ajout de staff par admin, et affichage d'un msg d'information
    $.post(
        'script/scriptAjax.php',
        {
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
        'script/scriptAjax.php',
        {
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
        'script/scriptAjax.php',
        {
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
    var the_id = '#contact';

    $('html, body').animate({
        scrollTop: $(the_id).offset().top
    }, 'slow');
    return false;
});

/*******************************
fonctions de followed.php
*******************************/

function addMeasure(id_Followed, id_Staff) {
    // permet l'ajout de measure par le staff idStaff sur le folloxed idfollowed
    $.post(
        'script/scriptAjax.php',
        {
            idFollowed: id_Followed,
            idStaff: id_Staff,
            type: $("input[name=type]").val(),
            value: $("input[name=value]").val(),
            unit: $("input[name=unit]").val()
        },
        function(data) {
            $("input[name=type]").val('');
            $("input[name=unit]").val('');
            $("input[name=value]").val('');
        }
    );
}

function modifyAnnotation(id_Followed) {
    // permet l'ajout de measure par le staff idStaff sur le folloxed idfollowed
    $.post(
        'script/scriptAjax.php',
        {
            idFollowed: id_Followed,
            annotation: $("textarea[name=annotation]").val()
        },
        function(data) {
            $(".annotation").html(data);
        }
    );
}
/******************************************************************************
 * For species.php
 *****************************************************************************/
function wikintro(title)
{
    $.ajax( {
        url: 'https://en.wikipedia.org/w/api.php',
        data: 'prop=extracts&exintro=&format=json&action=query&titles='+title,
        dataType: 'json',
        type: 'POST',
        /*headers: { 'Api-User-Agent': 'GABA Owl/0.1' },*/
        success: function(data)
        {
            return(data.query.pages);
        }
    } );
}
