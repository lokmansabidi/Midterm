<?php
require 'dbconnect.php';

$db = new dbconnect();
$services = $db->getServices();
echo json_encode($services);
?>
