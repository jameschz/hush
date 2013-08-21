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
abstract class Hush_Bpm_Model
{
	/**
	 * @var int
	 */
	protected $id = 0;
	
	/**
	 * @var array
	 */
	private $types = array(
		1 => 'Text',
		2 => 'Radio',
		3 => 'Select',
		4 => 'Checkbox'
	);
	
	/**
	 * @var array
	 */
	private $forms = array(
		1 => 'Hush_Html_Form_Text',
		2 => 'Hush_Html_Form_Radio',
		3 => 'Hush_Html_Form_Select',
		4 => 'Hush_Html_Form_Checkbox'
	);
	
	/**
	 * Empty Construct
	 */
	public function __construct ($modelId = 0)
	{
		if ($modelId) {
			$this->setModelId($modelId);
			$this->_loadFromDb($modelId);
		}
	}
	
	/**
	 * Set current model id
	 * @param int $id
	 */
	public function setModelId ($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get current model id
	 */
	public function getModelId ()
	{
		return $this->id;
	}
	
	/**
	 * Get all model field types for building options
	 */
	public function getFieldTypes ()
	{
		return $this->types;
	}
	
	/**
	 * Build form using specific format
	 * @param string $type
	 * @param string $name
	 * @param string $attrs
	 * @param string $options
	 * @return string
	 */
	public function convertToForm ($type, $name, $attrs = '', $options = '')
	{
		$formClassName = $this->forms[$type];
		$formClassPath = str_replace('_', '/', $formClassName) . '.php';
		require_once $formClassPath;
		$formClass = new $formClassName;
		$formClass->setName($name);
		$formClass->setAttrs($this->_unserialize($attrs));
		$formClass->setOptions($this->_unserialize($options));
		return $formClass->render();
	}
	
	/**
	 * Array to string
	 */
	protected function _serialize ($data)
	{
		$result = array();
		foreach ((array) $data as $key => $val) {
			$result[] = $key . "=" . $val;
		}
		return implode("\n", $result);
	}
	
	/**
	 * String to array
	 */
	protected function _unserialize ($data)
	{
		$result = array();
		foreach ((array) explode("\n", $data) as $row) {
			$rowArr = explode("=", $row);
			$rowKey = isset($rowArr[0]) ? trim($rowArr[0]) : null;
			$rowVal = isset($rowArr[1]) ? trim($rowArr[1]) : null;
			$result[$rowKey] = $rowVal;
		}
		return $result;
	}
	
	/**
	 * Load from database
	 * Should be implemented by subclasses
	 */
	abstract protected function _loadFromDb($modelId);
}