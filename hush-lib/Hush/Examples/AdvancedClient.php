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

require_once 'Hush/Socket/Client.php';
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
class Examples_AdvancedClient
{
	public function __construct ($fr, $to)
	{
		$this->ports = range($fr, $to);
	}
	
	public function __call ($method, $params)
	{
		$key = array_rand($this->ports);
		$client = new Examples_Client(SERVER_HOST, $this->ports[$key]);
		return call_user_func_array(array($client, $method), $params);
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// implement class

/**
 * @ignore
 * @package Examples
 */
class Examples_Client extends Hush_Socket_Client
{
	
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// run demo

try {
	
	$client = new Examples_AdvancedClient(11111, 11115);
	echo $client->test();
	
//	$client->shutdown();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}
