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
 * @see Hush_Message_Exception
 */
require_once 'Hush/Message/Handler.php';

/**
 * @see Hush_Message_Exception
 */
require_once 'Hush/Message/Queue.php';

/**
 * @abstract
 * @package Hush_Message
 * @example Message.php Example for using Hush_Message class
 */
class Hush_Message
{
	/**
	 * @var int
	 */
	public $type = 0;
	
	/**
	 * @var mixed
	 */
	public $data = null;
	
	/**
	 * Set message type
	 * 
	 * @param int $type
	 * @return void
	 */
	public function setType ($type)
	{
		$this->type = intval($type);
	}
	
	/**
	 * Get message type
	 * 
	 * @return int
	 */
	public function getType ()
	{
		return $this->type;
	}
	
	/**
	 * Set message data
	 * 
	 * @param mixed $data
	 * @return void
	 */
	public function setData ($data)
	{
		$this->data = $data;
	}
	
	/**
	 * Get message data
	 * 
	 * @return mixed
	 */
	public function getData ()
	{
		return $this->data;
	}
	
}