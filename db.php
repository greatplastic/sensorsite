<?php
/*
sensors DATABASE
node TABLE
	lat FLOAT(7,4)
	long FLOAT(7,4)
	node_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
data TABLE
	dust SMALLINT UNSIGNED
	humidity FLOAT(5,2)
	temperature FLOAT(5,2)
	timestamp TIMESTAMP PRIMARY KEY
	node_id INT UNSIGNED
*/

// Database parameters
$db_user = "root";
$db_pass = "mysql";
$db_host = "localhost";
$db_name = "sensors";
$db_port = ini_get("mysqli.default_port");

$db_con = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
if ($db_con->connect_error) {
    die("Unable to connect: " . $mysqli->connect_errno . " " . $mysqli->connect_error);
}

?>