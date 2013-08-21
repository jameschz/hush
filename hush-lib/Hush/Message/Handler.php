<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Message
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Hush_Message_Exception
 */
require_once 'Hush/Message/Exception.php';

/**
 * @see Hush_Message
 */
require_once 'Hush/Message.php';

/**
 * @package Hush_Message
 */
abstract class Hush_Message_Handler
{
	/**
	 * @var Hush_Message
	 */
	public $message = null;
	
	/**
	 * Set message object
	 * 
	 * @param Hush_Message $message
	 * @return void
	 */
	public function setMessage ($message)
	{
		$this->message = $message;
	}
	
	/**
	 * Get message object
	 * 
	 * @return Hush_Message
	 */
	public function getMessage ()
	{
		return $this->message;
	}
	
	/**
	 * @abstract
	 */
	abstract protected function doSend ();
	
	/**
	 * @abstract
	 */
	abstract protected function doRecv ();
}