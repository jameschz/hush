<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Mail
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Zend_Mail
 */
require_once 'Zend/Mail.php';

/**
 * @package Hush_Mail
 */
class Hush_Mail extends Zend_Mail
{
	/**
	 * Override Zend_Mail function
	 * @param string $subject
	 */
	public function setSubject ($subject) {
		$encode_subject = "=?" . $this->_charset . "?B?" . base64_encode($subject) . "?=";
		return parent::setSubject($encode_subject);
	}
}