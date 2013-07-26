<?php

/**
 * Created: 2012/10/11
 *
 * Author: Jeff Lee
 */

require "Packet.php";

class Client {

	private $socket;

	private $host;

	private $port;

	public function __construct($host = '127.0.0.1', $port = '8000') {
		$this->host = $host;
		$this->port = $port;
	}

	public function run() {
		$this->createSocket();

		$data = new Packet("
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
			abcdefghijklmnopqrstuvwxyz\n
		");

		fwrite($this->socket, serialize($data));
		while (!feof($this->socket)) {
			echo fgets($this->socket, 1024);
			sleep(1);
		}

		fclose($this->socket);
	}

	private function createSocket() {
		$this->socket = stream_socket_client("tcp://" . $this->host . ":" . $this->port, $errno, $errstr, 30);

		if (!$this->socket) {
			$this->socket = null;
			echo "stream_socket_client failed: $errstr ($errno)\n";
			return false;
		}

		echo "Connected to server\n";
		return true;
	}
}

$client = new Client();
$client->run();
