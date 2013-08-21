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
 * @see Hush_Html_Form_Element
 */
require_once 'Hush/Html/Form/Element.php';

/**
 * @abstract
 * @package Hush_Html_Form
 */
class Hush_Html_Form_Select extends Hush_Html_Form_Element
{
	/**
	 * Form element tag
	 * @var string
	 */
	protected $tag = 'select';
	
	/**
	 * Form element attr
	 * @var array
	 */
	protected $attrs = array();
	
	/**
	 * Closure html element
	 * @var boolean
	 */
	protected $closure = false;
	
	/**
	 * Multiple select size
	 * @var int
	 */
	protected $multiple = 0;
	
	/**
	 * Set select to be mutiple or not
	 * @param boolean $mutiple
	 */
	public function setMultiple ($multiple)
	{
		$this->multiple = $multiple;
		return $this;
	}
	
	/**
	 * Fill options by key => value (adapt with Smarty)
	 */
	protected function decorate ()
	{
		$body = "";
		$defaults = $this->values; // array
		foreach ((array) $this->options as $v => $t) {
			$selected = in_array($v, $defaults) ? " selected" : "";
			$body .= "<option value=\"{$v}\"{$selected}>{$t}</option>";
		}
		$this->setBody($body);
		return $this;
	}
	
	/**
	 * Overload render method
	 */
	public function render ()
	{
		if ($this->multiple) {
			$this->setAttrs(array(
				'multiple' => '',
				'size' => $this->multiple
			));
		}
		return parent::render();
	}
}