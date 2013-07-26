<?php

/**
 * Created: 2012/10/11
 *
 * Author: Jeff Lee
 */

class Packet {

	// Possible types: Conn, Data, Result
	private $type;

	private $data;

	public function __construct($data, $type = 'Conn') {
		$this->data = $data;
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public function getData() {
		return $this->data;
	}

	public static function cast($obj) {
		return  unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen(get_called_class()) . ':"' . get_called_class() . '"', $obj));
	}
}
