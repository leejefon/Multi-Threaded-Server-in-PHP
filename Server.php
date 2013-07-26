<?php

/**
 * Created: 2012/10/11
 *
 * Author: Jeff Lee
 */

require "Packet.php";

class Server extends Thread {

	private $socket;

	private $shutdown;

	private $port;

	public function __construct($port = '8000') {
		$this->port = $port;
		$this->shutdown = false;
	}

	public function run() {
		$this->socket = $this->createSocket();
		if (!$this->socket) {
			echo "Could not create socket";
			exit;
		}

		// too bad global spawns doesn't work..
		$spawns = array();
		$write = array();
		$exception = array();
		while (!$this->shutdown) {
			// stream_select() alters the array, so $read has to be re-constructed in each iteration somehow
			$read = array_merge(array($this->socket), $spawns);

			if (0 !== stream_select($read, $write, $exception, 4)) {
				foreach ($read as $s) {
					if ($s === $this->socket) {
						$this->onAccept($spawns);
					} else {
						$this->onClientPacket($s, $spawns);
					}
				}
			}
		}

		return sprintf("Hello World");
	}

	public function createSocket() {
		$socket = stream_socket_server('tcp://0.0.0.0:' . $this->port, $errno, $errstr);
		if (!$socket) {
			$socket = null;
			echo "stream_socket_server failed : $errstr ($errno)\n";
			return false;
		}

		echo "Socket created.  Server listening on port " . $this->port . "\n";

		return $socket;
	}

	public function onAccept($spawns) {
		$conn = stream_socket_accept($this->socket);
		fwrite($conn, "Hello World\n");
		$spawns[] = $conn;
	}

	public function onClientPacket($s, $spawns) {
		$result = Packet::cast(fread($s, 10240));

		if (empty($result)) { // Connection closed
			echo "Connection closed\n";
			fclose($s);
			unset($spawns[array_search($s, $spawns)]);
		} else if ($result === false) {
			echo "Something went wrong\n";
			unset($spawns[array_search($s, $spawns)]);
		} else {
			echo "The client has sent: '" . $result->getData() . "'\n";
			fwrite($s, "You have sent: '" . $result->getData() . "'\n");
			fclose($s);
			unset($spawns[array_search($s, $spawns)]);
		}
	}
}

$server = new Server();
$server->start();
