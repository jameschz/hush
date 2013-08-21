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
class Hush_Html_Form_Radio extends Hush_Html_Form_Element
{
	/**
	 * Form element tag
	 * @var string
	 */
	protected $tag = 'input';
	
	/**
	 * Form element attr
	 * @var array
	 */
	protected $attrs = array(
		'type' => 'radio'
	);
	
	/**
	 * Closure html element
	 * @var array
	 */
	protected $closure = true;
	
	/**
	 * See render
	 */
	protected function decorate () {}
	
	/**
	 * Overload render method
	 */
	public function render ()
	{
		$html = "";
		$default = $this->value;
		foreach ((array) $this->options as $v => $t) {
			if ($v) { // value can not be empty
				$this->setAttrs(array(
					'value' => (string) $v,
					'checked' => null
				));
				if (!strcmp($v, $default)) {
					$this->setAttrs(array(
						'checked' => ''
					));
				}
				$html .= parent::render() . "&nbsp;{$t}&nbsp;";
			}
		}
		return $html;
	}
}