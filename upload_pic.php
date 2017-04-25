<?php
include 'db.php';
/* POST must contain 'table', which specifies what is the image about
 * (e.g. followed or species) and 'id' of the thing; fields which won't be
 * entered by the user, and therefore parameters must be passed via hidden
 * input in forms
 * http://php.net/manual/en/features.file-upload.post-method.php
 * Input file must be a picture (managed in input of form, see
 * musicbrainz for example)
 */
$picprop = getimagesize($_FILES['userpic']['tmp_name']);
$ext = $picprop['mime'];
$fname = mb_substr($_POST['table'], 0, 2) . $_POST['id'] . ".$ext";
$uploaddir = "/var/www/html/data/pics/";
$uploadfile = $uploaddir . basename($fname);

if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile)) {
    echo "Picture is valid, and successfully uploaded.\n";
    $id = 'id' . ucfirst($table);
    update_line($_POST['table'], array('pic_path' => $uploadfile),
        $id, $_POST['id']);
}
else {
    echo "Possible file upload attack.\n";
}
?>