// TODO: ajouter des comments pour les fonctions : write_geoloc_fromodal, addRelationship

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

function modifyInfos(id_Followed) {
    // permet l'ajout de measure par le staff idStaff sur le folloxed idfollowed
    $.post(
        'script/scriptAjax.php',
        {
            idFollowed: id_Followed,
            birth : $("input[name=birth]").val(),
            death : $("input[name=death]").val(),
            health : $("input[name=health]").val(),
            annotation: $("textarea[name=annotation]").val()
        }
    );
}

function editSpecies(id_species)
{
    /* class_s because class is a reserved word
     * function called by modal of species.php
     */
    $.post(
        'script/scriptAjax.php',
        {
            idSpecies: id_species,
            common_name: $("input[name=common_name]").val(),
            binomial_name: $("input[name=binomial_name]").val(),
            kingdom: $("input[name=kingdom]").val(),
            phylum: $("input[name=phylum]").val(),
            class_s: $("input[name=class]").val(),
            order_s: $("input[name=order_s]").val(),
            family: $("input[name=family]").val(),
            genus: $("input[name=genus]").val()
        }
    );
}

function addRelationship(id_followed, id_staff)
{
    $.post(
        'script/scriptAjax.php',
        {
            idFollowed: id_followed,
            idStaff: id_staff,
            type_relation: $("input[name=type_rel]").val(),
            other_followed: $("input[name=other_followed]").val(),
            begin: $("input[name=begin]").val()
        },
        function(data)
        {
            $("input[name=type_rel]").val('');
            $("input[name=other_followed]").val('');
            $("input[name=begin]").val('');
        }
    );
}

function write_geoloc_fromodal(idfoll, idstaff)
{
    var latitude = $("input[name=mod_latitude]").val();
    var longitude = $("input[name=mod_longitude]").val();
    var geoloc = latitude + ',' + longitude;
    $.post(
        'script/scriptAjax.php',
        {idfollowed: idfoll, geoloc: geoloc, idstaff: idstaff},
        function(data)
        {
            $("input[name=mod_latitude]").val('');
            $("input[name=mod_longitude]").val('');
        }
    );
}
/*******************************
gestion du choix des langues
*******************************/

function language(lg) {
    $.post(
        'script/scriptAjax.php',
        {
            lang: lg
        },
        success: function(){
            document.location.href='' // on recherge la page actuelle
        }
    );
}
