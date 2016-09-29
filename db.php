<?php
// Database parameters
$db_user = "";
$db_pass = "";
$db_host = localhost;
$db_port = 27017;
$db_name = "CDCDB";
$collect_name = "nodeinfo";

$db_server = "mongodb://" . $db_user . (empty($db_pass) ? "" : ($db_pass . "@")) . $db_host . ":" . $db_port;
$db_con = new MongoClient($db_server);
$db = $db_con->$db_name;
$nodeinfo = $db->$collect_name;

?>