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

class DBManager {
	// Database parameters
	private $user;
	private $pass;
	private $host;
	private $name;
	private $port;
	// Connection handler
	private $con;
	
	function __construct() {
		$this->user = "root";
		$this->pass = "mysql";
		$this->host = "localhost";
		$this->name = "sensors";
		$this->port = ini_get("mysqli.default_port");
		$this->con = new mysqli($this->host, $this->user, 
								$this->pass, $this->name, 
								$this->port);
		if ($this->con->connect_error) {
			die("Unable to connect to DB: " . $mysqli->connect_errno . " " . $mysqli->connect_error);
		}
	}
	
	

}

?>