<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Process
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Process_Exception
 */
require_once 'Hush/Process/Exception.php';

/**
 * @see Hush_Process_Storage
 */
require_once 'Hush/Process/Storage.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * Set basic environment
 * Give us eternity to execute the script
 */
ini_set("max_execution_time", "0");
ini_set("max_input_time", "0");
@set_time_limit(0);

/**
 * PAY ATTENTION !!!
 * There is still some problem on PHP's shm_* methods in Multi-CPUs environment
 * So please use the shared variables carefully !!!
 * FYI : http://bugs.php.net/bug.php?id=8985
 * 
 * @abstract
 * @package Hush_Process
 * @example Process.php Example for using Hush_Process class
 */
abstract class Hush_Process
{
	/**
	 * @staticvar string [sysv|file]
	 */
	public static $storage = 'sysv';
	
	/**
	 * @staticvar int
	 */
	public static $nowStatus = null;
	
	/**
	 * @staticvar int
	 */
	public static $sumProcessNum = 0;
	
	/**
	 * @staticvar int
	 */
	public static $maxProcessNum = 5;
	
	/**
	 * @staticvar int
	 */
	public static $parentPid = 0;

	/**
	 * @staticvar bool
	 */
	public static $stopAfterRun = true;

	/**
	 * @staticvar bool
	 */
	public static $getLock = false;

	/**
	 * @staticvar bool
	 */
	public static $setLock = false;

	/**
	 * @var int
	 */
	public $ftbm = 0;
	
	/**
	 * @var int
	 */
	public $ftbr = 0;
	
	/**
	 * @var resource
	 */
	public $mutex = null;
	
	/**
	 * @var object
	 */
	public $global = null;
	
	/**
	 * @var object
	 */
	public $shared = null;
	
	/**
	 * @var string
	 */
	public $name = '';
	
	/**
	 * @var int
	 */
	public $gid = 0;
	
	/**
	 * @var int
	 */
	public $pid = 0;
	
	/**
	 * Construct
	 */
	public function __construct ($name = '')
	{
		// ftok base id
		$this->ftbm = ftok(__FILE__, 'm');
		$this->ftbr = ftok(__FILE__, 'r');
		
		// get global process id
		$this->name = get_class($this);
		$this->gid = $this->__hashcode($this->name);
		
		// get process id by $name
		$this->name .= '_' . $name;
		$this->pid = $this->__hashcode($this->name);
		
		// create shared space for variables
		$this->global = Hush_Process_Storage::factory(self::$storage, array('name' => $this->gid));
		$this->shared = Hush_Process_Storage::factory(self::$storage, array('name' => $this->pid));
		
		// release all resource
		$this->__release();
		
		// initialization
		$this->__initialize();
	}
	
	/**
	 * Destruct
	 */
	public function __destruct ()
	{
		$this->__release();
	}
	
	/**
	 * Set shared variables
	 */
	public function __set ($k, $v)
	{
		// set shared variables
		if (self::$setLock) $this->lock();
		$res = $this->shared->set($k, $v);
		if (self::$setLock) $this->unlock();
		return $res;
	}
	
	/**
	 * Get shared variables
	 */
	public function __get ($k)
	{
		// get shared variables
		if (self::$getLock) $this->lock();
		$res = $this->shared->get($k);
		if (self::$getLock) $this->unlock();
		return $res;
	}
	
	/**
	 * Get & set global variables
	 */
	public function __global ($k, $v = null)
	{
		// get global variables
		if (!isset($v)) {
			if (self::$getLock) $this->lock();
			$res = $this->global->get($k);
			if (self::$getLock) $this->unlock();
			return $res;
		}
		
		// set global variables
		if (self::$setLock) $this->lock();
		$res = $this->global->set($k, $v);
		if (self::$setLock) $this->unlock();
		return $res;
	}
	
	/**
	 * Get string or key's hash code
	 * @param string $s
	 * @return int
	 */
	private function __hashcode ($s)
	{
		$code = $this->ftbr + Hush_Util::str_hash($s);
		
		return $code ? $code : $this->ftbr;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// private methods
	
	/**
	 * Check runtime enviornment and initlialize resource for process
	 * Resource include Mutex resource and shared variables
	 * Call by construct
	 * 
	 * @return void
	 */
	private function __initialize ()
	{
		if (substr(php_sapi_name(), 0, 3) != 'cli') {
			throw new Hush_Process_Exception("Please use cli mode to run this script");
		}
		
		if (!extension_loaded("pcntl") ||
			!extension_loaded("posix") ||
			!extension_loaded("sysvsem")) {
				throw new Hush_Process_Exception("You need to open pcntl, posix, sysvsem extensions");
			}
		
		// init mutex for lock
		$mutex_id = $this->ftbm + $this->pid;
		$this->mutex = sem_get($mutex_id, 1);
		
		// register callback functions
		pcntl_signal(SIGTERM,	array(&$this, "__singal"));
		pcntl_signal(SIGHUP,	array(&$this, "__singal"));
		pcntl_signal(SIGUSR1,	array(&$this, "__singal"));
		
		// get parent process id
		if (!self::$parentPid) self::$parentPid = posix_getpid();
		
		// do init logic in subclasses
		$this->__init();
	}
	
	/**
	 * Protect shared variables dirty read/write
	 * Please use this method when data is important
	 * Especially when do something for database in multi-processes
	 * Pay attention that this method will have speed slower
	 * 
	 * @return void
	 */
	protected function __safewait ()
	{
		$rs = intval(10000 * sqrt(self::$maxProcessNum));
		$re = intval(30000 * sqrt(self::$maxProcessNum));
		$this->sleep(rand($rs, $re));
	}
	
	/**
	 * Release all resource after process end
	 * Call by destruct
	 * 
	 * @return void
	 */
	protected function __release ()
	{
		// release global space for vars
		$this->global->remove();

		// release shared space for vars
		$this->shared->remove();
		
		// remove mutex for lock
		@sem_remove($this->mutex);
	}
	
	/**
	 * Making processes by fork
	 * Control the number of the sub processes
	 * 
	 * @return void
	 */
	private function __process ()
	{
		$pid = pcntl_fork();
		
		if ($pid == -1) {
			die("System could not fork\n");
		}
		
		// we are the parent
		elseif ($pid) {
			
			$sumProcessNum = $this->getSumProcessNum();
			
			$sumProcessNum++;
			if ($sumProcessNum >= self::$maxProcessNum) {
				pcntl_wait(self::$nowStatus);
				$sumProcessNum--;
			}
			
			$this->setSumProcessNum($sumProcessNum);
			
			return $pid;
		}
		
		// we are the child
		else {
			declare (ticks = 1) {
				$this->run();
				// force to kill process after run
				if (self::$stopAfterRun) {
					$this->stop();
				}
			}
		}
	}
	
	/**
	 * Wait processes
	 * 
	 * @return void
	 */
	private function __waitpid ($pids)
	{
		foreach ($pids as $pid) {
			pcntl_waitpid($pid, self::$nowStatus);
		}
	}
	
	/**
	 * Callback function
	 * Do some action by the system singals
	 * 
	 * @param int $signo Constants restart_syscalls
	 * @return void
	 */
	protected function __singal ($signo)
	{
		switch ($signo) {
			case SIGTERM :
				// TODO : Do somthing when caught SIGTERM
				break;
			case SIGHUP :
				// TODO : Do somthing when caught SIGHUP
				break;
			case SIGUSR1 :
				// TODO : Do somthing when caught SIGUSR1
				break;
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// public methods
	
	private function setSumProcessNum ($num)
	{
		$this->__global('sumProcessNum', intval($num));
	}
	
	public function getSumProcessNum ()
	{
		return $this->__global('sumProcessNum');
	}
	
	/**
	 * Set max sub processes number
	 * 
	 * @param int $num
	 */
	public function setMaxProcess ($num)
	{
		self::$maxProcessNum = intval($num);
	}
	
	/**
	 * Get process priority
	 * 
	 * @return void
	 */
	public function setPriority ($priority)
	{
		pcntl_setpriority(intval($priority), posix_getpid());
	}
	
	/**
	 * Set process priority
	 * 
	 * @return int
	 */
	public function getPriority ()
	{
		return pcntl_getpriority(posix_getpid());
	}
	
	/**
	 * Return process status
	 * 
	 * @return int
	 */
	public function getStatus ()
	{
		return self::$nowStatus;
	}
	
	/**
	 * Check if current process is parent
	 * Each run only have one parent pid
	 * 
	 * @return bool
	 */
	public function isParent ()
	{
		return (posix_getpid() == self::$parentPid) ? 1 : 0;
	}
	
	/**
	 * Return process name (uniq id)
	 * 
	 * @return int
	 */
	public function getName ()
	{
		return $this->name;
	}
	
	/**
	 * Process start
	 * Should be called by instance
	 * 
	 * @return void
	 */
	public function start ()
	{
		$pids = array();
		$sumProcessNum = $this->getSumProcessNum();
		
		for ($i = $sumProcessNum; $i < self::$maxProcessNum; $i++) {
			$this->__safewait(); // stagger each process
			$pids[] = $this->__process();
		}
		
		$this->__waitpid($pids);
	}
	
	/**
	 * Process sleep
	 * Can be used in run() method in subclasses
	 * 
	 * @return void
	 */
	public function sleep ($ms)
	{
		usleep($ms);
	}
	
	/**
	 * Process stop
	 * Can be used in run() method in subclasses
	 * 
	 * @return void
	 */
	public function stop ()
	{
		$this->__release(); // must release all resource first
		
		$sumProcessNum = $this->getSumProcessNum();
		$this->setSumProcessNum(--$sumProcessNum);
		
		posix_kill(posix_getpid(), SIGKILL);
	}
	
	/**
	 * Process lock
	 * Protected current process
	 * Should be called by instance of each process
	 * 
	 * @return void
	 */
	public function lock ()
	{
		@sem_acquire($this->mutex);
	}
	
	/**
	 * Process unlock
	 * Should be called by instance
	 * 
	 * @return void
	 */
	public function unlock ()
	{
		@sem_release($this->mutex);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// abstract methods
	
	protected function __init () {} // to be overridden
	
	abstract protected function run ();
}
