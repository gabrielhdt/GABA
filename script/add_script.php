<?php
$coordfile = fopen('/tmp/coord.txt', 'w');
fwrite($coordfile, $_POST['lat'].','.$_POST['longi']);
fclose($coordfile);
?>
