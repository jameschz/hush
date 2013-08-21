<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Bpm
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Hush_Bpm
 */
abstract class Hush_Bpm_Flow
{
	/**
	 * @var int
	 */
	protected $id = 0;
	
	/**
	 * Key => Value : from id => to id
	 * @var array
	 */
	protected $path = array();
	
	/**
	 * @var Hush_Bpm_Lang
	 */
	protected $lang = null;
	
	/**
	 * Empty Construct
	 */
	public function __construct ($flowId = 0)
	{
		if ($flowId) {
			$this->setFlowId($flowId);
			$this->_loadFromDb($flowId);
		}
	}
	
	/**
	 * Set current flow id
	 * @param int $id
	 */
	public function setFlowId ($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get current flow id
	 */
	public function getFlowId ()
	{
		return $this->id;
	}
	
	/**
	 * Set bpm language for executing node code 
	 * @param Hush_Bpm_Lang $lang
	 */
	public function setLang ($lang)
	{
		if (!($lang instanceof Hush_Bpm_Lang)) {
			require_once 'Hush/Bpm/Lang/Exception.php';
			throw Hush_Bpm_Lang_Exception('Language can not be recognized');
		}
		$this->lang = $lang;
		return $this;
	}
	
	/**
	 * Get bpm language
	 */
	public function getLang ()
	{
		return $this->lang;
	}
	
	/**
	 * Load from database
	 * Should be implemented by subclasses
	 */
	abstract protected function _loadFromDb($flowId);
}