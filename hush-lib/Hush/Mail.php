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
	 * @var Zend_Mail_Transport_Abstract
	 */
	public $_transport;	// transport object
	
	/**
	 * Construct
	 * @param array $config
	 */
	public function __construct($config)
	{	
		$charset = isset($config['charset']) ? $config['charset'] : 'gbk';
		$type = isset($config['type']) ? $config['type'] : 'sendmail';
		$host = isset($config['host']) ? $config['host'] : 'localhost';
		
		unset($config['charset']);
		unset($config['type']);
		unset($config['host']);
		
		if (!strcasecmp($type, 'smtp')) {
			require_once 'Zend/Mail/Transport/Smtp.php';
			$this->_transport = new Zend_Mail_Transport_Smtp($host, $config);
		} else {
			require_once 'Zend/Mail/Transport/Sendmail.php';
			$this->_transport = new Zend_Mail_Transport_Sendmail($config);
		}
		
		$this->setDefaultTransport($this->_transport);
		
		$this->_charset = strtoupper($charset);
	}
	
	/**
	 * Override Zend_Mail function
	 * @param string $subject
	 */
	public function setSubject ($subject) {
		$encode_subject = "=?" . $this->_charset . "?B?" . base64_encode($subject) . "?=";
		return parent::setSubject($encode_subject);
	}
}