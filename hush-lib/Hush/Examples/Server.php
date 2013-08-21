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
require_once 'Hush/Util.php';

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
		return 'server:ok';
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// run demo

try {
	
	$server = new Examples_Server();
	$server->debugMode(1);
	$server->daemon();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}
