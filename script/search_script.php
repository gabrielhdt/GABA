<?php
include 'db.php';
if (isset($_POST['viewname']))
{
    refresh($_POST['viewname']);
}
elseif (isset($_POST['id'], $_POST['table']))
{
    return(get_picpath($_POST['table'], $_POST['id']));
}
elseif (isset($_POST['id'], $_POST['action']) &&
    $_POST['action'] == 'geolocation')
{
    echo "Coming soon...";
}

function refresh($viewname) {
    update_view($viewname);
}

function get_picpath($table, $id)
{
    /* Returns picture path of elt $id in table $table
     * It is primarily based on the database structure as
     * the picture path is supposed to be in column 'pic_path'
     * and the column containing the primary key is named
     * idTable
     */
    $pkeycolname = 'id'.$table;  // God bless the database creator
    $line = get_values(array('pic_path'), $table, array($pkeycolname => $id));
    return($line ? $line[0]['pic_path'] : false);
}
?>
