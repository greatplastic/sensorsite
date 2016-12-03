<?php
/*
CREATE DATABASE sensors;
USE sensors;

CREATE TABLE nodes(
    latitude       DECIMAL(7,4) NOT NULL,
    longitude      DECIMAL(7,4) NOT NULL,
    mac_addr       TEXT NOT NULL,
    ip_addr        TEXT NOT NULL,
    node_id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY);

CREATE TABLE data(
    dust         SMALLINT UNSIGNED NOT NULL,
    temperature  DECIMAL(5,2) NOT NULL,
    humidity     DECIMAL(5,2) NOT NULL,
    t_collected  DATETIME NOT NULL,
    t_received   DATETIME NOT NULL,
    node_id      SMALLINT UNSIGNED NOT NULL,
    event_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY);

CREATE TABLE monitor(
    dust         SMALLINT UNSIGNED NOT NULL,
    temperature  DECIMAL(5,2) NOT NULL,
    humidity     DECIMAL(5,2) NOT NULL,
    t_collected  DATETIME NOT NULL,
    t_received   DATETIME NOT NULL,
    node_id      SMALLINT UNSIGNED NOT NULL,
    event_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    action       SMALLINT UNSIGNED NOT NULL);

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
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE node_id = ?");
				$query->bind_param("i", $search_params->node_id);
				break;
			case 2: // Dust
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE dust BETWEEN ? and ?");
				$query->bind_param("ii", $search_params->from_dust, $search_params->to_dust);
				break;
			case 3: // Dust and Node
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE dust BETWEEN ? and ? AND node_id = ?");
				$query->bind_param("iii", $search_params->from_dust, 
					$search_params->to_dust, $search_params->node_id);
				break;
			case 4: // Time
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE t_collected BETWEEN ? AND ?");
				$query->bind_param("ss", $search_params->from_time, $search_params->to_time);
				break;
			case 5: // Time and Node
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE t_collected BETWEEN ? AND ? AND node_id = ?");
				$query->bind_param("ssi", $search_params->from_time, $search_params->to_time, 
					$search_params->node_id);
				break;
			case 6: // Time and Dust
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE t_collected BETWEEN ? AND ? AND dust BETWEEN ? and ?");
				$query->bind_param("ssii", $search_params->from_time, $search_params->to_time, 
					$search_params->from_dust, $search_params->to_dust);
				break;
			case 7: // Time and Dust and Node
				$query = $this->con->prepare("SELECT dust, humidity, temperature, t_collected, node_id FROM data WHERE t_collected BETWEEN ? AND ? AND dust BETWEEN ? and ? AND node_id = ?");
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
	
	function get_node_locs() {
		$result = $this->con->query("SELECT node_id, latitude, longitude FROM nodes");
		echo $this->con->error;
		return $result;
		
	}

}

?>