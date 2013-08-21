<?php
/**
 * Hush Framework
 *
 * @ignore
 * @category   Examples
 * @package    Examples
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'config.inc';

require_once 'Hush/Socket/Server.php';
require_once 'Hush/Process.php';
require_once 'Hush/Util.php';

/**
 * @ignore
 * Server host constant
 */
define('SERVER_HOST', '127.0.0.1');

/**
 * @ignore
 * @package Examples
 */
class Examples_AdvancedServer extends Hush_Process
{
	public function __construct ($fr, $to)
	{
		parent::__construct(); // init shared space
		
		$this->ports = range($fr, $to);
	}
	
	public function __init ()
	{
		$this->__release();
	}
	
	public function run ()
	{
		$ports = $this->ports;
		$port = array_pop($ports);
		$this->ports = $ports;
		
		echo "Listening on : " . $port . "\n";
		
		$server = new Examples_Server(SERVER_HOST, $port);
		$server->daemon();
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// implement class

/**
 * @ignore
 * @package Examples
 */
class Examples_Server extends Hush_Socket_Server
{
	/**
	 * This method could be called from the client
	 * 
	 * @return void
	 */
	public function test ()
	{
		return 'advanced server:ok';
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// run demo

try {
	
	$advancedServer = new Examples_AdvancedServer(11111, 11115);
	$advancedServer->start();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}
