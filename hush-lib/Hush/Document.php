<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Document
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Document_Parser
 */
require_once 'Hush/Document/Parser.php';

/**
 * @package Hush_Document
 */
class Hush_Document
{
	/**
	 * @var Hush_Document_Parser 
	 */
	protected $_parser = null;
	
	/**
	 * Construct
	 * @param Hush_Debug_Writer $writer
	 */
	public function __construct($classFile, $parser = 'PhpDoc')
	{
		if (!file_exists($classFile)) {
			require_once 'Hush/Document/Exception.php';
			throw new Hush_Document_Exception("Non-exists class file '$classFile'.");
		}
		
		$this->_parser = Hush_Document_Parser::factory($parser);
		
		$this->_parser->parseCode($classFile);
	}
    
	/**
	 * Get annotations
	 * @param $classFile ClassName you want
	 * @param $methodName MethodName you want
	 * @return array
	 */
	public function getAnnotation($className = '', $methodName = '')
	{
		return $this->_parser->getAnnotation($className, $methodName);
	}
}