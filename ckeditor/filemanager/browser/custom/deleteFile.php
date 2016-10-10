<?php
$target_file = $_SERVER['DOCUMENT_ROOT'] . "/" . $_POST["path"];
unlink($target_file);
?>