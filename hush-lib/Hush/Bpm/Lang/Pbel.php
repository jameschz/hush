<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Bpm_Lang
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'Hush/Bpm/Lang.php';
require_once 'Hush/Bpm/Lang/Exception.php';

/**
 * Php Bpm Execution Language
 * @package Hush_Bpm_Lang
 */
class Hush_Bpm_Lang_Pbel extends Hush_Bpm_Lang
{
	/**
	 * @var array
	 */
	public $methods = array(
		'model_field',
		'model_form_add',
		'model_form_edit',
		'audit_by_role',
		'audit_by_user',
		'audit_check',
		'forward',
	);
	
	/**
	 * @var array
	 */
	public $returns = null;
	
	/**
	 * @var array
	 */
	public $phpfile = null;
	
	/**
	 * Set file for $this->getDocs()
	 * @param string $file
	 */
	public function setFile ($file)
	{
		$this->phpfile = (string) $file;
		return $this;
	}
	
	/**
	 * Get all docs
	 */
	public function getDocs ()
	{
		if (!file_exists($this->phpfile)) {
			throw new Hush_Bpm_Lang_Exception("Pbel class '$file' does not exists");
		}
		
		$docs = array();
		foreach ((array) file($this->phpfile) as $line) {
			if (preg_match('/@pbel(.*)/i', $line, $lres)) {
				if (empty($lres[1])) continue;
				$docs[] = array(
					'method' => trim($lres[1]),
					'intros' => trim($lres[1]),
				);
			}
		}
		
		return $docs;
	}
	
	/**
	 * Set return data for executing code
	 * @param mixed $return
	 */
	protected function setReturn ($return)
	{
		$this->returns = $return;
		return $this->returns;
	}
	
	/**
	 * Get return data after executing code
	 */
	protected function getReturn ()
	{
		return $this->returns;
	}
	
	/**
	 * Get all going next nodes for saving into db
	 * @param string $code
	 */
	public function getNextNodes ($code)
	{
		$gotoNodes = array();
		@preg_match_all('/forward\(([0-9]+)\)/i', $code, $cres);
		if ($cres[1]) $gotoNodes = (array) $cres[1];
		return $gotoNodes;
	}
	
	/**
	 * Check syntax for pbel code before executing
	 * @param string $code
	 */
	public function prepare ($code)
	{
		return ($this->_safeCheckSyntax($code) === false) ? false : true;
	}
	
	/**
	 * Executing code
	 * @param string $code
	 */
	public function execute ($code)
	{
		return $this->_safeEvalCode($code);
	}
	
	/**
	 * Private checking syntax method
	 */
	private function _safeCheckSyntax ($code)
	{
		$code = trim(str_replace("pbel.", "", $code));
		$code = "return true;\n" . $code;
		if (!$code) return false;
		
		ob_start();
		$eval = @eval($code);
		if ($eval === false) {
			ob_end_clean();
			return false;
		}
		ob_end_clean();
		
		return true;
	}
	
	/**
	 * Private eval code method
	 */
	private function _safeEvalCode ($code)
	{
		$class = (string) get_class($this);
		$lang = new $class;
		
		$code = trim(str_replace("pbel.", "\$lang->", $code));
		if (!$code) return false;
		
//		error_log($code); // for code debug
		
		ob_start();
		$return = false;
		$eval = eval($code);
		if ($eval === false) {
			ob_end_clean();
			return false;
		}
		$oput = ob_get_contents();
		ob_end_clean();
		
		return $lang->getReturn();
	}
}