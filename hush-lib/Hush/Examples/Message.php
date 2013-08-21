<?php
/**
 * Hush Framework
 *
 * @ignore
 * @category   Examples
 * @package    Examples
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'config.inc';

require_once 'Hush/Message.php';
require_once 'Hush/Util.php';

/**
 * @ignore
 * @package Examples
 */
class Examples_Message extends Hush_Message
{
	const MSG_ERROR		= 1;
	const MSG_NOTICE	= 2;
}

/**
 * @ignore
 * @package Examples
 */
class Examples_Message_Queue extends Hush_Message_Queue
{
	
}

/**
 * @ignore
 * @package Examples
 */
class Examples_Message_Handler extends Hush_Message_Handler
{
	public function doSend ()
	{
		// TODO : add send handler for sending message
	}
	
	public function doRecv ()
	{
		// Get message from queue
		$msg = $this->getMessage();
		
		// Do action by message type
		switch ($msg->getType()) {
			case Examples_Message::MSG_ERROR : 
				echo "[ERROR] Oh Shit ! " . $msg->getData() . "\n";
				exit;
			case Examples_Message::MSG_NOTICE : 
				echo "[NOTICE] Yeah ! " . $msg->getData() . "\n";
				break;
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// run demo

try {
	
	$mq = new Examples_Message_Queue();
	$mq->addHandler(new Examples_Message_Handler());
	
	if (!$mq->size()) {
		// init error messages
		foreach (range(1, 5) as $msg_id) {
			$msg = new Examples_Message();
			$msg->setType(Examples_Message::MSG_ERROR);
			$msg->setData("Error Message {$msg_id}");
			$msg = json_encode($msg); // json format data
			$mq->addMessage($msg);
			unset($msg);
		}
		// init notice messages
		foreach (range(1, 10) as $msg_id) {
			$msg = new Examples_Message();
			$msg->setType(Examples_Message::MSG_NOTICE);
			$msg->setData("Notice Message {$msg_id}");
			$msg = json_encode($msg); // json format data
			$mq->addMessage($msg);
			unset($msg);
		}
	}
	
	$mq->start();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}
