<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Document
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: james $
 */
 
/**
 * @package Hush_Document
 */
abstract class Hush_Document_Parser
{
	/**
	 * @var array 
	 */
	protected $_annotation = array(
		'_annotation' => array(),
		'_methodlist' => array(
			'_annotation' => array()
		)
	);
	
	/**
	 * Get annotations from a class file
	 * @param $classFile
	 * @return array
	 */
	public function getAnnotation($className = '', $methodName = '')
	{
		if ($className && $methodName) {
			return $this->_annotation[$className]['_methodlist'][$methodName]['_annotation'];
		} elseif ($className) {
			return $this->_annotation[$className]['_annotation'];
		} else {
			return false;
		}
	}
	
	/**
	 * Create storage object
	 * @param string $engine Engine name (sysv, file)
	 * @return object
	 */
	public static function factory ($engine)
	{
		$class_file = 'Hush/Document/Parser/' . ucfirst($engine) . '.php';
		$class_name = 'Hush_Document_Parser_' . ucfirst($engine);
		
		require_once $class_file; // include storage class
		
		return new $class_name();
	}
	
	/**
	 * Parse code
	 */
	abstract public function parseCode ($classFile);
}