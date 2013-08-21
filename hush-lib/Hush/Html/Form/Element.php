<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Html_Form
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
 * @package Hush_Html_Form
 */
abstract class Hush_Html_Form_Element extends Hush_Html_Element
{
	/**
	 * Form default name
	 * @var string
	 */
	protected $name = '';
	
	/**
	 * Form default value
	 * @var string
	 */
	protected $value = '';
	
	/**
	 * Form default values for multiple elements
	 * @var array
	 */
	protected $values = array();
	
	/**
	 * Form default value
	 * @var array
	 */
	protected $options = array();
	
	/**
	 * Empty Construct
	 */
	public function __construct () {}
	
	/**
	 * Set element name
	 * @param string $name
	 * @return Hush_Html_Form_Button
	 */
	public function setName ($name)
	{
		$this->setAttrs(array(
			'name' => (string) $name
		));
		return $this;
	}
	
	/**
	 * Set element value
	 * @param string $action
	 * @return Hush_Html_Form
	 */
	public function setValue ($value)
	{
		$this->values[] = $this->value = $value;
		return $this;
	}
	
	/**
	 * Set element value
	 * @param string $action
	 * @return Hush_Html_Form
	 */
	public function setValues ($values = array())
	{
		$this->values = $values;
		return $this;
	}
	
	/**
	 * Set element option
	 * @param string $action
	 * @return Hush_Html_Form
	 */
	public function setOptions ($options = array())
	{
		$this->options = $options;
		return $this;
	}
	
	/**
	 * Decorate element bofore render
	 * @var string
	 */
	public function render ()
	{
		$this->decorate(); // decorate bofore render
		return parent::render();
	}
	
	/**
	 * Decorate element 
	 */
	abstract protected function decorate ();
}