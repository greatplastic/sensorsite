<?php

class SearchParameters {
	// Bools to determine which parameters are being searched
	private $search_time;
	private $search_dust;
	private $search_node;
	
	// Specific values/ranges for each parameter
	private $from_time;
	private $to_time;
	private $from_dust;
	private $to_dust;
	private $node_id;
	
	function __construct() {
		$this->search_time = false;
		$this->search_dust = false;
		$this->search_node = false;
		$this->from_time = NULL;
		$this->to_time = NULL;
		$this->from_dust = NULL;
		$this->to_dust = NULL;
		$this->node_id = NULL;
	}
	
	function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	function set_time_params($from, $to) {
		$this->from_time = $from;
		$this->to_time = $to;
		$this->search_time = true;
	}
	
	function set_dust_params($from, $to) {
		$this->from_dust = abs((int) $from);
		$this->to_dust = abs((int) $to);
		$this->search_dust = true;
	}
	
	function set_node_params($id) {
		$this->node_id = abs((int) $id);
		$this->search_node = true;
	}
	
}

?>