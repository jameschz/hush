<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Debug
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Zend_Debug
 */
require_once 'Zend/Debug.php';

/**
 * @see Hush_Debug_Writer
 */
require_once 'Hush/Debug/Writer.php';

/**
 * @package Hush_Debug
 */
class Hush_Debug
{
	
	/**
	 * @static
	 */
	const DEBUG	= 0;
	
	/**
	 * @static
	 */
	const INFO	= 1;
	
	/**
	 * @static
	 */
	const WARN	= 2;
	
	/**
	 * @static
	 */
	const ERROR	= 3;
	
	/**
	 * @static
	 */
	const FATAL	= 4;
	
	/**
	 * @var int Debug level
	 */
	private $_level = null;
	
	/**
	 * @deprecated
	 * @var array
	 */
	private $_levels = array();
	
	/**
	 * @var Hush_Debug_Writer
	 */
	private $_writers = array();
	
	/**
	 * @static
	 * @var bool
	 */
	private static $_debug = null;
	
	/**
	 * Construct
	 * @param Hush_Debug_Writer $writer
	 */
    public function __construct(Hush_Debug_Writer $writer = null)
    {
        $r = new ReflectionClass($this);
        $this->_levels = array_flip($r->getConstants());
		
        if ($writer !== null) {
            $this->addWriter($writer);
        }
        
        $this->setDebugLevel(self::DEBUG);
    }
	
	/**
	 * Singleton method
	 * @return Hush_Debug
	 */
	public static function getInstance () 
	{
		if (self::$_debug === null) {
			self::$_debug = new Hush_Debug();
		}
		return self::$_debug;
	}
	
	/**
	 * Set debug level
	 * Default level is self::FATAL, which means the debug message could be shown if the message level is under self::FATAL
	 * @param int $level
	 * @return void
	 */
	public function setDebugLevel ($level = self::FATAL)
	{
		$this->_level = $level;
	}
	
	/**
	 * Check debug can be showed
	 * Judge by url parameter named 'debug'
	 * @param string $level
	 * @return void
	 */
	public static function showDebug ($str)
	{
		$debug_str = Hush_Util::param('debug');
		if ($debug_str) {
			$debug_arr = explode(',', $debug_str);
			return in_array($str, $debug_arr);
		}
		return false;
	}
	
	/**
	 * Set one writer for debug
	 * @param Hush_Debug_Writer $writer
	 * @return void
	 */
	public function setWriter($writer = null)
	{
		if (!$writer instanceof Hush_Debug_Writer) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception('Writer must be an instance of Hush_Debug_Writer');
		}
	
		// escape repeated writer class
		$writer_class_name = get_class($writer);
		$this->_writers = array($writer_class_name => $writer);
	}
	
	/**
	 * Add writer for debug
	 * @param Hush_Debug_Writer $writer
	 * @return void
	 */
	public function addWriter($writer = null)
	{
		if (!$writer instanceof Hush_Debug_Writer) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception('Writer must be an instance of Hush_Debug_Writer');
		}
		
		// escape repeated writer class
		$writer_class_name = get_class($writer);
		if (!array_key_exists($writer_class_name , $this->_writers)) {
			$this->_writers[$writer_class_name] = $writer;
		}
	}
	
	/**
	 * Main debug process
	 * Have writers save or show log
	 * @param string $msg
	 * @param string $label
	 * @param int $level
	 * @return Hush_Debug
	 */
	public function debug ($msg = null, $label = null, $level = self::DEBUG) 
	{
		if (!count($this->_writers)) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception('Please specify a debug writer first');
		}
		
		if (!isset($this->_levels[$level])) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception(sprintf('Invalid debug level "%s"; cannot retrieve', $level));
		}
		
        // send to each writer
        foreach ($this->_writers as $writer) {
        	$writer->level($this->_level);
            $writer->debug($msg, $label, $level);
        }
        
        return $this;
	}
	
	/**
	 * Main debug process
	 * Have writers save or show log
	 * @return void
	 */
	public function write ()
	{
		if (!count($this->_writers)) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception('Please specify a debug writer first');
		}
		
        // send to each writer
        foreach ($this->_writers as $writer) {
            $writer->write(true);
        }
	}
	
	/**
	 * Using Zend debug dumper to dump php varibles
	 *
	 * @param mixed $msg
	 * @param mixed $label
	 * @return void
	 */
	public function dump($msg = null, $label = null)
	{
		Zend_Debug::dump($msg, $label, true);
	}
}
