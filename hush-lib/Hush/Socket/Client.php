<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Socket
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Exception
 */
require_once 'Hush/Exception.php';

/**
 * @see Hush_Exception
 */
require_once 'Hush/Socket.php';

/**
 * @package Hush_Socket
 * @example Client.php Example for using Hush_Socket_Client class
 */
class Hush_Socket_Client extends Hush_Socket
{
	/**
	 * Contruct
	 */
	public function __construct ($host = '', $port = 0) 
	{
		$this->__initialize(trim($host), $port);
	}
	
	/**
	 * Magic method
	 */
	public function __call ($method, $params)
	{		
		return $this->send(serialize(array(
			'method' => $method,
			'params' => $params,
		)));
	}
	
	/**
	 * Send message package to the socket server
	 * Basic layer method
	 * 
	 * @return mixed
	 */
	public function send ($msg, $is_block = false) 
	{
		if (!$this->host ||
			!$this->port) {
				throw new Hush_Socket_Exception("Please set server's host and port first");
			}

		/* Create a TCP/IP socket. */
		$this->sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->sock < 0) {
			echo "socket_create() failed.\nReason: " . socket_strerror($this->sock) . "\n";
		}

		$result = @socket_connect($this->sock, $this->host, $this->port);
		if ($result < 0) {
			echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
		}

		if ($is_block) {
			@socket_set_nonblock($this->sock);
		}

		// add suffix for socket msg
		$msg = trim($msg) . "\r\n";

		@socket_write($this->sock, $msg, strlen($msg));
		
		$result = @socket_read($this->sock, 2048);
		
		// unserialize data from socket server
		$result = unserialize(trim($result));
		
		return $result;
		
	}
	
	/**
	 * Quit current connection
	 * Use non-block mode
	 * 
	 * @return void
	 */
	public function quit ()
	{
		$this->send('quit', true);
	}
	
	/**
	 * Close current socket
	 * 
	 * @return void
	 */
	public function close ()
	{
		@socket_close($this->sock);
	}
	
	/**
	 * Shutdown socket server
	 * Use non-block mode
	 * 
	 * @return void
	 */
	public function shutdown ()
	{
		$this->send('shutdown', true);
	}
}
