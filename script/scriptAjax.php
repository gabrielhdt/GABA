<?php
include '../db.php';

if( isset($_POST['nom'], $_POST['prenom'] ,$_POST['pwd1'] ,$_POST['pwd2'] ,$_POST['typeStaff'])){
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pwd1']) && !empty($_POST['pwd2']) && !empty($_POST['typeStaff'])) {
        if ($_POST['pwd1'] == $_POST['pwd2']) {
            add_staff($_POST['pwd1'], $_POST['typeStaff'], $_POST['prenom'], $_POST['nom']);
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo -1;
    }
}
elseif (isset($_POST['id'])) {
    delete_msg($_POST['id']);
}
elseif (isset($_POST['nom'], $_POST['email'], $_POST['msg'])) {
    add_line('messages',
        array(
            'name' => $_POST['nom'], 'email' => $_POST['email'],
            'message' => $_POST['msg']
        ));
    echo 1;
}
elseif (isset($_POST['idFollowed'], $_POST['idStaff'], $_POST['type'], $_POST['unit'], $_POST['value'])) {
    $infosMeasure = array('idFollowed' => $_POST['idFollowed'], 'idStaff' => $_POST['idStaff']);
    $idMeasure = add_line('Measure', $infosMeasure);
    $infosMiscQuantity = array('idMeasure' => $idMeasure, 'type' => $_POST['type'],
                               'value' => $_POST['value'], 'unit' => $_POST['unit']);
    add_line('MiscQuantity', $infosMiscQuantity);
}
elseif (isset($_POST['idFollowed'], $_POST['idStaff'], $_POST['type'],
    $_POST['other_followed']))
{
    $info_relationship = array(
        'idFollowed1' => $_POST['idFollowed'],
        'idFollowed2' => $_POST['other_followed'],
        'relation_type' => $_POST['type']
    );
    add_line('Relation', info_relationship);
    return(true);
}
elseif (isset($_POST['idFollowed'], $_POST['annotation'])) {
    $change = array('annotation' => $_POST['annotation']);
    $where = array(array("field" => "idFollowed", "value" => $_POST['idFollowed'], "binrel" => "=", PDO::PARAM_INT));
    update_line('Followed', $change, $where);
    echo $_POST['annotation'];
}
elseif (isset($_POST['geoloc'], $_POST['idfollowed'], $_POST['idstaff']))
{
    $idmeasure = add_line('Measure',
        array(
            'idFollowed' => $_POST['idfollowed'],
            'idStaff' => $_POST['idstaff']
        )
    );
    $coords = explode(',', $_POST['geoloc']);
    add_line('Location',
        array(
            'latitude' => (float) $coords[0],
            'longitude' => (float) $coords[1],
            'idMeasure' => $idmeasure
        )
    );
}
?>
