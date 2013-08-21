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
class Hush_Html_Form_Textarea extends Hush_Html_Form_Element
{
	/**
	 * Form element tag
	 * @var string
	 */
	protected $tag = 'textarea';
	
	/**
	 * Closure html element
	 * @var array
	 */
	protected $closure = false;
	
	/**
	 * Fill value & options
	 */
	protected function decorate ()
	{
		if ($this->value) {
			$this->setBody((string) $this->value);
		}
		return $this;
	}
}