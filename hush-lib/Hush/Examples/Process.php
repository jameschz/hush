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

require_once 'Hush/Process.php';
require_once 'Hush/Util.php';

/**
 * @ignore
 * @package Examples
 */
class Examples_Process extends Hush_Process
{
	/**
	 * This method could be overridden by sub classes
	 * Parent process init logic
	 * 
	 * @return void
	 */
	public function __init () 
	{
		echo "isparent : " . $this->isParent() . " ; " .
			 "parent pid : " . self::$parentPid . "\n";
	}

	/**
	 * This method must be implemented from the parent class
	 * Sub process main logic
	 * 
	 * @return void
	 */
	public function run ()
	{
//		$this->testCommon();
		$this->testShared();
	}
	
	public function testCommon ()
	{
		$count = 0;
		while ($count < 5) {
			$sleep_time = rand(1000000, 2000000);
			echo "isparent : " . $this->isParent() . " ; " .
				 "process : " . $this->getName() . " ; " .
				 "status : $this->getStatus() ; " .
				 "count : " . ++$count . " ; " .
				 "sleep : $sleep_time ; " .
				 "pid : " . getmypid() . "\n";
//			$this->randStop($sleep_time);
			$this->sleep($sleep_time);
		}
	}
	
	public function testShared ()
	{
		while ($this->count < 10) {
			$sleep_time = rand(1000000, 2000000);
			echo "isparent : " . $this->isParent() . " ; " .
				 "process : " . $this->getName() . " ; " .
				 "status : $this->getStatus() ; " .
				 "count : " . ++$this->count . " ; " .
				 "sleep : $sleep_time ; " .
				 "pid : " . getmypid() . "\n";
//			$this->randStop($sleep_time);
			$this->sleep($sleep_time);
		}
	}
	
	public function randStop ($sleep_time)
	{
		if ($sleep_time > 1500000) {
			echo "process stopped : " . posix_getpid() . "\n";
			$this->stop();
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// run demo

try {
	
	$processA = new Examples_Process('A');
	$processB = new Examples_Process('B');
	
	$processA->lock(); // lock A process
	$processB->lock(); // lock B process
	
	$processA->start();
	$processB->start();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}
