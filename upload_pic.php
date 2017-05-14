<?php
include 'script/db.php';
/* POST must contain 'table', which specifies what is the image about
 * (e.g. followed or species) and 'id' of the thing; fields which won't be
 * entered by the user, and therefore parameters must be passed via hidden
 * input in forms
 * http://php.net/manual/en/features.file-upload.post-method.php
 * Input file must be a picture (managed in input of form, see
 * musicbrainz for example)
 */
$picprop = getimagesize($_FILES['userpic']['tmp_name']);
$ext = basename($picprop['mime']);
$fname = mb_strtolower(mb_substr($_POST['table'], 0, 2).$_POST['id']).".$ext";
$uploaddir = "data/pics/";
$uploadfile = $uploaddir . $fname;

if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile)) {
    echo "Picture is valid, and successfully uploaded.\n";
    $where = array(
        array(
            'field' => 'id' . ucfirst($_POST['table']),
            'value' => $_POST['id'],
            'binrel' => '=',
            'type' => PDO::PARAM_INT
        )
    );
    update_line($_POST['table'], array('pic_path' => $uploadfile),
        $where);
}
else {
    echo "Possible file upload attack.\n";
}
?>
