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
 * @see Hush_Socket_Exception
 */
require_once 'Hush/Socket/Exception.php';

/**
 * @package Hush_Socket
 */
class Hush_Socket
{
	/**
	 * @var string
	 */
	public $host = '0.0.0.0';
	
	/**
	 * @var string
	 */
	public $port = '12345';
	
	/**
	 * @var resource
	 */
	public $sock;
	
	/**
	 * Check runtime enviornment and initlialize resource for process
	 * Resource include Mutex resource and shared variables
	 * Call by construct
	 * 
	 * @return void
	 */
	protected function __initialize ($host, $port)
	{
		if (!extension_loaded("sockets")) {
			throw new Hush_Socket_Exception("You need to open sockets extensions");
		}
		
		if ($host) $this->host = $host;
		if ($port) $this->port = $port;
	}
	
	/**
	 * Set socket from outside (after init)
	 * 
	 * @param resource $socket
	 * @return void
	 */
	public function setSocket ($socket)
	{
		$this->sock = $socket;
	}
	
	/**
	 * Get socket resource
	 * 
	 * @return resource
	 */
	public function getSocket ($socket)
	{
		$this->sock = $socket;
	}
}

