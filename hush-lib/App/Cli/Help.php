<?php
/**
 * App Cli
 *
 * @category   App
 * @package    App_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package App_Cli
 */
class App_Cli_Help extends App_Cli
{
	public function helpAction ()
	{
		// app command description
		$cliLibPath = __LIB_DIR . '/App/Cli';
		foreach (glob($cliLibPath . '/*.php') as $classFile) {
			$className = 'App_Cli_' . basename($classFile, '.php');
			if (!strcasecmp($className, 'App_Cli_Help'))
				continue; 
			if ($classFile && $className) {
				require_once $classFile;
				$class = new $className();
				$class->helpAction();
			}
		}
		// lib command description
		$cliLibPath = dirname(__FILE__);
		foreach (glob($cliLibPath . '/*.php') as $classFile) {
		    $className = 'App_Cli_' . basename($classFile, '.php');
		    if (!strcasecmp($className, 'App_Cli_Help'))
		        continue;
		        if ($classFile && $className) {
		            require_once $classFile;
		            $class = new $className();
		            $class->helpAction();
		        }
		}
	}
}
