<?php
$coordfile = fopen('/tmp/coord.txt', 'w');
fwrite($coordfile, implode($_POST, ','));
fclose($coordfile);
?>
