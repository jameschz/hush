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
 * @abstract
 * @package Hush_Html
 */
class Hush_Html_Element
{
	/**
	 * Html element tag
	 * @var string
	 */
	protected $tag = '';
	
	/**
	 * Html element tag
	 * @var string
	 */
	protected $body = '';
	
	/**
	 * Html element attr
	 * @var array
	 */
	protected $attrs = array();
	
	/**
	 * Closure html element
	 * @var array
	 */
	protected $closure = false;
	
	/**
	 * Construct
	 * Create a new element
	 * @param string $tag
	 */
	public function __construct ($tag, $closure = false)
	{
		$this->tag = (string) $tag;
		$this->closure = $closure;
	}
	
	/**
	 * Add element body
	 * @param string $body
	 * @return Hush_Html_Element
	 */
	public function setBody ($body)
	{
		$this->body = (string) $body;
		return $this;
	}
	
	/**
	 * Add element attr
	 * @param array $attr
	 * @return Hush_Html_Element
	 */
	public function setAttrs ($attrs = array())
	{
		foreach ((array) $attrs as $k => $v) {
			// unset when value is null
			if ($v !== null) {
				$this->attrs[$k] = (string) $v;
			} else {
				unset($this->attrs[$k]);
			}
		}
		return $this;
	}
	
	/**
	 * Render element
	 * @var string
	 */
	public function render ()
	{
		$html = "<{$this->tag}";
		foreach ($this->attrs as $k => $v) {
			if ($k && $v) {
				$html .= " {$k}=\"{$v}\"";
			} elseif ($k) {
				$html .= " {$k}";
			}
		}
		if (!$this->closure) {
			$html .= ">{$this->body}</$this->tag>";
		} else {
			$html .= "/>";
		}
		return $html;
	}
	
	/**
	 * Serialize with json
	 * @return string
	 */
	public function saveJson ()
	{
		$serialized = array(
			"className" => get_class($this),
			"classProp" => get_object_vars($this),
		);
		return json_encode($serialized);
	}
	
	/**
	 * Unserialize with json 
	 * @return Hush_Html_Element
	 */
	public function loadJson ($jsonStr)
	{
		$unserialized = json_decode($jsonStr);
		$htmlElement = new $unserialized->className;
		foreach ($unserialized->classProp as $k => $v) {
			// convert object to array for compatibility
			if (is_object($v)) $v = (array) $v;
			$htmlElement->$k = $v;
		}
		return $htmlElement;
	}
}