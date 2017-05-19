<?php
include 'db.php';

// script pour l'ajout d'un nouveau staff par l'admin
if (
    isset(
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['pwd1'],
        $_POST['pwd2'],
        $_POST['typeStaff']
    )
) {

    if (
        !empty($_POST['nom']) &&
        !empty($_POST['prenom']) &&
        !empty($_POST['pwd1']) &&
        !empty($_POST['pwd2']) &&
        !empty($_POST['typeStaff']
    )
) {
        if ($_POST['pwd1'] == $_POST['pwd2']) {
            add_staff($_POST['pwd1'], $_POST['typeStaff'],
                      $_POST['prenom'], $_POST['nom']);
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo -1;
    }
}
// script pour la suppression de msg
elseif (isset($_POST['id'])) {
    delete_msg($_POST['id']);
}
// script pour l'ajout d'un msg
elseif (
    isset(
        $_POST['nom'],
        $_POST['email'],
        $_POST['msg']
    )
) {
    add_line('messages',
        array(
            'name' => $_POST['nom'], 'email' => $_POST['email'],
            'message' => $_POST['msg']
        ));
    echo 1;
}
// script pour l'ajout une mesure
elseif (
    isset(
        $_POST['idFollowed'],
        $_POST['idStaff'],
        $_POST['type'],
        $_POST['unit'],
        $_POST['value']
    )
) {
    $infosMeasure = array('idFollowed' => $_POST['idFollowed'],
                          'idStaff' => $_POST['idStaff']);
    $idMeasure = add_line('Measure', $infosMeasure);
    $infosMiscQuantity = array('idMeasure' => $idMeasure,
                               'type' => $_POST['type'],
                               'value' => $_POST['value'],
                               'unit' => $_POST['unit']
                              );
    add_line('MiscQuantity', $infosMiscQuantity);
}
// script pour l'ajout d'une relation entre 2 followeds
elseif (
    isset(
        $_POST['idFollowed'],
        $_POST['idStaff'],
        $_POST['type_relation'],
        $_POST['other_followed']
    )
) {
    $info_relationship = array(
        'idFollowed1' => $_POST['idFollowed'],
        'idFollowed2' => $_POST['other_followed'],
        'type_relation' => $_POST['type_relation']
    );
    if (isset($_POST['begin']) && !empty($_POST['begin']))
    {
        $info_relationship['begin'] = $_POST['begin'];
    }
    add_line('Relation', $info_relationship);
    return(true);
}
// script pour la modifiction des caractèristqiues d'une espèce
elseif (
    isset(
        $_POST['idSpecies'], $_POST['common_name'],
        $_POST['binomial_name'],
        $_POST['kingdom'],
        $_POST['phylum'],
        $_POST['class_s'],
        $_POST['order_s'],
        $_POST['family'],
        $_POST['genus']
    )
)
{
    $where = array(
        array(
            'field' => 'idSpecies', 'value' => $_POST['idSpecies'],
            'binrel' => '=', 'type' => PDO::PARAM_INT
        )
    );
    $change = array(
        'common_name' => $_POST['common_name'],
        'binomial_name' => $_POST['binomial_name'],
        'kingdom' => $_POST['kingdom'],
        'phylum' => $_POST['phylum'],
        'class' => $_POST['class_s'],
        'order_s' => $_POST['order_s'],
        'family' => $_POST['family'],
        'genus' => $_POST['genus']
    );
    update_line('Species', $change, $where);
}
// script de mise a jour des informations d'un followed
elseif (
    isset(
        $_POST['idFollowed'],
        $_POST['annotation'],
        $_POST['death'],
        $_POST['birth'],
        $_POST['health']
    )
) {
    $change = array('annotation' => $_POST['annotation'],
                    'birth' => $_POST['birth'],
                    'death' => $_POST['death'], 'health' => $_POST['health']);
    $where = array(
        array(
            "field" => "idFollowed", "value" => $_POST['idFollowed'],
            "binrel" => "=", 'type' => PDO::PARAM_INT
        )
    );
    update_line('Followed', $change, $where);
}
// script de mise a jour de la localisation
elseif (
    isset(
        $_POST['geoloc'],
        $_POST['idfollowed'],
        $_POST['idstaff']
    )
) {
    $coords = explode(',', $_POST['geoloc']);
    if (count($coords) < 2)
    {
        return(false);
    }
    else
    {
        $idmeasure = add_line('Measure',
            array(
                'idFollowed' => $_POST['idfollowed'],
                'idStaff' => $_POST['idstaff']
            )
        );
        add_line('Location',
            array(
                'latitude' => (float) $coords[0],
                'longitude' => (float) $coords[1],
                'idMeasure' => $idmeasure
            )
        );
    }
}
?>
