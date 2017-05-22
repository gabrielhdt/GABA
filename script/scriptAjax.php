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
    add_line_smart('messages',
        array(
            'name' => array(
                'value' => $_POST['nom'], 'type' => PDO::PARAM_STR
            ),
            'email' => array(
                'value' => $_POST['email'], 'type' => PDO::PARAM_STR
            ),
            'message' => array(
                'value' => $_POST['msg'], 'type' => PDO::PARAM_STR
            )
        )
    );
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
    $infosMeasure = array(
        'idFollowed' => array(
            'value' => $_POST['idFollowed'], 'type' => PDO::PARAM_INT
        ),
        'idStaff' => array(
            'value' => $_POST['idStaff'], 'type' => PDO::PARAM_INT
        )
    );
    $idMeasure = add_line_smart('Measure', $infosMeasure);
    $infosMiscQuantity = array(
        'idMeasure' => array(
            'value' => $idMeasure,
            'type' => PDO::PARAM_INT
        ),
        'type' => array(
            'value' => $_POST['type'],
            'type' => PDO::PARAM_STR
        ),
        'value' => array(
            'value' => $_POST['value'],
            'type' => PDO::PARAM_STR
        ),
        'unit' => array(
            'value' => $_POST['unit'],
            'type' => PDO::PARAM_STR
        )
    );
    add_line_smart('MiscQuantity', $infosMiscQuantity);
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
        'idFollowed1' => array(
            'value' => $_POST['idFollowed'],
            'type' => PDO::PARAM_INT
        ),
        'idFollowed2' => array(
            'value' => $_POST['other_followed'],
            'type' => PDO::PARAM_INT
        ),
        'type_relation' => array(
            'value' => $_POST['type_relation'],
            'type' => PDO::PARAM_STR
        )
    );
    if (isset($_POST['begin']) && !empty($_POST['begin']))
    {
        $info_relationship['begin'] = $_POST['begin'];
    }
    add_line_smart('Relation', $info_relationship);
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
        $idmeasure = add_line_smart('Measure',
            array(
                'idFollowed' => array(
                    'value' => $_POST['idfollowed'],
                    'type' => PDO::PARAM_INT
                ),
                'idStaff' => array(
                    'value' => $_POST['idstaff'],
                    'type' => PDO::PARAM_INT
                )
            )
        );
        add_line_smart('Location',
            array(
                'latitude' => array(
                   'value' => (float) $coords[0],
                   'type' => PDO::PARAM_STR
               ),
               'longitude' => array(
                   'value' => (float) $coords[1],
                   'type' => PDO::PARAM_STR
               ),
               'idMeasure' => array(
                   'value' => $idmeasure,
                   'type' => PDO::PARAM_STR
               )
            )
        );
    }
}
?>
