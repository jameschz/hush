<?php
/**
 * Ihush Cli
 *
 * @category   Ihush
 * @package    Ihush_Cli
 * @author     James.Huang <huangjuanshi@snda.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Ihush_Cli
 */
class Ihush_Cli_Help extends Ihush_Cli
{
	public function helpAction ()
	{
		// all command description
		$cliLibPath = __LIB_DIR . '/Ihush/Cli';
		foreach (glob($cliLibPath . '/*.php') as $classFile) {
			$className = 'Ihush_Cli_' . basename($classFile, '.php');
			if (!strcasecmp($className, 'Ihush_Cli_Help'))
				continue; 
			if ($classFile && $className) {
				require_once $classFile;
				$class = new $className();
				$class->helpAction();
			}
		}
	}
}
