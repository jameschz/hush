<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Html
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Html_Element
 */
require_once 'Hush/Html/Element.php';

/**
 * @abstract
 * @package Hush_Html
 */
class Hush_Html_Form extends Hush_Html_Element
{
	/**
	 * Form element tag
	 * @var string
	 */
	protected $tag = 'form';
	
	/**
	 * Form element name
	 * @var string
	 */
	protected $name = '';
	
	/**
	 * 
	 */
	protected $elements = array();
	
	/**
	 * Empty Construct
	 */
	public function __construct () {}
	
	/**
	 * Set form name
	 * @param string $name
	 * @return Hush_Html_Form
	 */
	public function setName ($name)
	{
		$this->name = $name;
		$this->setAttrs(array(
			'name' => (string) $name
		));
		return $this;
	}
	
	/**
	 * Set form action
	 * @param string $action
	 * @return Hush_Html_Form
	 */
	public function setAction ($action)
	{
		$this->setAttrs(array(
			'action' => (string) $action
		));
		return $this;
	}
	
	/**
	 * Add Hush_Html_Form_Element
	 * @param Hush_Html_Form_Element $element
	 */
	public function addElement ($element)
	{
		if (!($element instanceof Hush_Html_Form_Element)) {
			die('error');
		}
		$this->elements[] = $element;
	}
	
	/**
	 * Build form 
	 */
	public function buildForm ()
	{
		static $forms;
		if (!isset($forms[$this->name])) {
			$body = "<table>\n";
			foreach ((array) $this->elements as $element) {
				$body .= "<tr><td>" . $element->render() . "</td></tr>\n";
			}
			$body .= "</table>\n";
			$forms[$this->name] = $body;
		}
		$this->setBody($forms[$this->name]);
	}
	
	/**
	 * Reload saveJson for checking body first
	 */
	public function saveJson ()
	{
		if (!$this->body) {
			require_once 'Hush/Html/Form/Exception.php';
			throw new Hush_Html_Form_Exception("please call Hush_Html_Form::buildForm method first");
		}
		return parent::saveJson();
	}
}