<?php
/**
 * Ihush Cli
 *
 * @category   Ihush
 * @package    Ihush_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Ihush_Cli
 */
class Ihush_Cli_Clean extends Ihush_Cli
{
	public function __init ()
	{
		parent::__init();
		$this->_printHeader();
	}
	
	public function helpAction ()
	{
		// command description
		echo "hush clean tpl [fe|be] [tplc|cache]\n";
		echo "hush clean cache [fe|be]\n";
		echo "hush clean all\n";
	}
	
	public function tplAction () 
	{
		$args = func_get_args();
		$env = isset($args[0]) ? $args[0] : null;
		$act = isset($args[1]) ? $args[1] : null;
		if (!$env || !$act) return $this->helpAction();
		// get template path
		switch ($env) {
			case 'fe':
				$tpl_path = realpath(__TPL_DIR . '/frontend');
				break;
			case 'be':
				$tpl_path = realpath(__TPL_DIR . '/backend');
				break;
			default:
				die("Error : Invalid input");
		}
		// get action
		switch ($act) {
			case 'tplc':
				$cleanDir = $tpl_path . DIRECTORY_SEPARATOR . 'template_c';
				break;
			case 'cache':
				$cleanDir = $tpl_path . DIRECTORY_SEPARATOR . 'cache';
				break;
			default:
				$cleanDir = null;
				break;
		}
		
		if (is_dir($cleanDir)) {
			Hush_Util::dir_clean($cleanDir);
			echo "\nclean dir '$cleanDir' ok.\n";
		} else {
			echo "\nclean dir '$cleanDir' failed.\n";
		}
	}
	
	public function cacheAction () 
	{
		$args = func_get_args();
		$env = isset($args[0]) ? $args[0] : null;
		// get template path
		switch ($env) {
			case 'fe':
				$cleanDir = realpath(__DAT_DIR . '/cache');
				break;
			case 'be':
				$cleanDir = realpath(__DAT_DIR . '/cache');
				break;
			default:
				die("Error : Invalid input");
		}
		
		if (is_dir($cleanDir)) {
			Hush_Util::dir_clean($cleanDir);
			echo "\nclean dir '$cleanDir' ok.\n";
		} else {
			echo "\nclean dir '$cleanDir' failed.\n";
		}
	}
	
	public function allAction ()
	{
		$this->tplAction('fe', 'tplc');
		$this->tplAction('be', 'tplc');
		$this->tplAction('fe', 'cache');
		$this->tplAction('be', 'cache');
		$this->cacheAction('fe');
		$this->cacheAction('be');
	}
}
