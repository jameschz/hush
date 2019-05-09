<?php
/**
 * App Console
 *
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

define('__HUSH_CLI', 1);
define('__APP_SITE', '');

ini_set('default_socket_timeout', -1);

require_once dirname(__FILE__) . '/../etc/global.config.php';
require_once dirname(__FILE__) . '/../etc/global.appcfg.php';

require_once 'Core/Util.php';
require_once 'Core/Service.php';

////////////////////////////////////////////////////////////////////////////////////////////////////
// Main process

try {
	require_once 'App/Cli.php';
	$cli = new App_Cli();
	$cli->run();
	
} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}