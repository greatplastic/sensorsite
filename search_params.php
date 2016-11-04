<?php
date_default_timezone_set('America/New_York'); // EST for date conversions

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
	
	// Offset value for limiting results
	private $offset;
	const OFFSET_STEP = 30;
	
	function __construct() {
		$this->search_time = false;
		$this->search_dust = false;
		$this->search_node = false;
		$this->from_time = NULL;
		$this->to_time = NULL;
		$this->from_dust = NULL;
		$this->to_dust = NULL;
		$this->node_id = NULL;
		$this->offset = 0;
	}
	
	function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	function set_time_params($from, $to) {
		if (empty($from)) {
			$from = date("Y-m-d H:i:s", strtotime('@0'));
		}
		if (empty($to)) {
			$to = date("Y-m-d H:i:s", time());
		}
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
	
	function increase_offset() {
		$this->offset += self::OFFSET_STEP;
	}
	
	function decrease_offset() {
		$this->offset -= self::OFFSET_STEP;
		if ($this->offset < 0) {
			$this->offset = 0;
		}
	}
}

?>