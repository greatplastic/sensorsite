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
	
	function execute($search_params) {
		/* Treat search parameters as a 3-bit number:
		MSB <------> LSB
		[time,dust,node]
		 */
		$choice = 0 + (((int)$search_params->search_time << 2) |
				((int)$search_params->search_dust << 1) |
				((int)$search_params->search_node));
		$query = NULL;
		$result = NULL;
		switch ($choice) {
			case 0:
				break;
			case 1: // Node
				$query = $this->con->prepare("SELECT * FROM data WHERE node_id = ?");
				$query->bind_param("i", $search_params->node_id);
				break;
			case 2: // Dust
				$query = $this->con->prepare("SELECT * FROM data WHERE dust BETWEEN ? and ?");
				$query->bind_param("ii", $search_params->from_dust, $search_params->to_dust);
				break;
			case 3: // Dust and Node
				$query = $this->con->prepare("SELECT * FROM data WHERE dust BETWEEN ? and ? AND node_id = ?");
				$query->bind_param("iii", $search_params->from_dust, 
					$search_params->to_dust, $search_params->node_id);
				break;
			case 4: // Time
				$query = $this->con->prepare("SELECT * FROM data WHERE timestamp BETWEEN ? AND ?");
				$query->bind_param("ss", $search_params->from_time, $search_params->to_time);
				break;
			case 5: // Time and Node
				$query = $this->con->prepare("SELECT * FROM data WHERE timestamp BETWEEN ? AND ? AND node_id = ?");
				$query->bind_param("ssi", $search_params->from_time, $search_params->to_time, 
					$search_params->node_id);
				break;
			case 6: // Time and Dust
				$query = $this->con->prepare("SELECT * FROM data WHERE timestamp BETWEEN ? AND ? AND dust BETWEEN ? and ?");
				$query->bind_param("ssii", $search_params->from_time, $search_params->to_time, 
					$search_params->from_dust, $search_params->to_dust);
				break;
			case 7: // Time and Dust and Node
				$query = $this->con->prepare("SELECT * FROM data WHERE timestamp BETWEEN ? AND ? AND dust BETWEEN ? and ? AND node_id = ?");
				$query->bind_param("ssiii", $search_params->from_time, $search_params->to_time, 
					$search_params->from_dust, $search_params->to_dust, $search_params->node_id);
				break;
			default:
				break;
		}
		if (!is_null($query)) {
			$query->execute();
			$result = $query->get_result();
		}
		return $result;
	}
	

}

?>