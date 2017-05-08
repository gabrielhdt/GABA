<?php
$coordfile = fopen('/tmp/coord.txt', 'w');
fwrite($coordfile, implode($_GET, ','));
fclose($coordfile);
?>
