<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Db
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Db
 */
require_once 'Hush/Db.php';

/**
 * @see Hush_Db_Exception
 */
require_once 'Hush/Db/Exception.php';

/**
 * @package Hush_Db
 */
class Hush_Db_Dao
{
	/**
	 * @var Hush_Db
	 */
	private $_dbr = null;
	
	/**
	 * @var Hush_Db
	 */
	private $_dbw = null;
	
	/**
	 * @var bool
	 */
	private $_debug = false;
	
	/**
	 * @var string
	 */
	public $dbName = null;
	
	/**
	 * @var string
	 */
	public $tableName = null;
	
	/**
	 * @var string
	 */
	public $shardDbName = null;
	
	/**
	 * @var string
	 */
	public $shardTableName = null;
	
	/**
	 * @var string
	 */
	public $primkey = 'id';
	
	/**
	 * @var string
	 */
	public $charset = 'utf8';
	
	/**
	 * @var Hush_Db_Config
	 */
	protected $_config = null;
	
	/**
	 * @var array
	 */
	protected $_configs = array();
	
	/**
	 * @var int|string
	 */
	protected $_shardId = 0;
	
	/**
	 * @var array
	 */
	protected $_trans = array();
	
	/**
	 * Construct
	 * Init the target db link
	 * 
	 * @param $type 'READ' or 'WRITE'
	 * @return void
	 */
	public function __construct ($configClass = null)
	{
		// check config
		if ($configClass != null) {
			if (!($configClass instanceof Hush_Db_Config)) {
				throw new Hush_Db_Exception('Bad DB config class');
			}
		}
		
		// set config
		$this->_config = $configClass;
		
		// implemented in subclass
		$this->__init();
	}
	
	/**
	 * Destruct
	 * Release the db link
	 */
	public function __destruct ()
	{
		if ($this->dbr) {
			$this->dbr->closeConnection();
		}
		if ($this->dbw) {
			$this->dbw->closeConnection();
		}
	}
	
	/**
	 * Do some preparation after construct
	 * Should be implemented by subclass
	 */
	protected function __init () {}
	
	/**
	 * Set database name
	 * 
	 * @param string $dbName
	 */
	protected function _bindDb ($dbName = '', $charset = '') 
	{
	    if ($dbName) $this->dbName = $dbName;
		if ($charset) $this->charset = $charset;
	}
	
	/**
	 * Bind table for CRUD method
	 * 
	 * @param string $tableName Binded table name
	 */
	protected function _bindTable ($tableName = '', $primkey = '')
	{
		if ($tableName) $this->tableName = $tableName;
		if ($primkey) $this->primkey = $primkey;
	}
	
	/**
	 * Set db configs by hand
	 * 
	 * @param array $configs DB configs array
	 */
	protected function _setDbConfigs ($configs = array())
	{
		if ($configs) {
			$this->_configs['host'] = isset($configs['host']) ? trim($configs['host']) : '';
			$this->_configs['port'] = isset($configs['port']) ? trim($configs['port']) : '';
			$this->_configs['user'] = isset($configs['user']) ? trim($configs['user']) : '';
			$this->_configs['pass'] = isset($configs['pass']) ? trim($configs['pass']) : '';
			$this->_configs['name'] = isset($configs['name']) ? trim($configs['name']) : '';
		}
	}
	
	/**
	 * Get database link string
	 * 
	 * @param string 
	 */
	protected function _getDbLink ($dbc = null)
	{
	    if ($dbc) {
	        $configs = $dbc->getConfig();
	        $keyId = $configs['host'].':'.$configs['port'].'/'.$configs['dbname'];
	        return $keyId;
	    }
	    return '';
	}
	
	/**
	 * Do sharding database by shardId
	 * 
	 * @param int $shardId
	 */
	private function _doShardDb ($shardId = 0)
	{
		if (!$this->dbName) {
			throw new Hush_Db_Exception('Please bind db first');
		}
		// if using default shardId
		$shardId = $shardId ? $shardId : $this->_shardId;
		// running callback function, set db name and cluster
		$this->_config->doShardDb($this->dbName, $this->tableName, $shardId);
		// set sharded table name for dao
		$this->shardDbName = $this->_config->getDbName();
	}
	
	/**
	 * Do sharding table by shardId
	 * 
	 * @param int $shardId
	 */
	private function _doShardTable ($shardId = 0)
	{
		if (!$this->tableName) {
			throw new Hush_Db_Exception('Please bind table first');
		}
		// if using default shardId
		$shardId = $shardId ? $shardId : $this->_shardId;
		// running callback function
		$this->_config->doShardTable($this->dbName, $this->tableName, $shardId);
		// set sharded table name for dao
		$this->shardTableName = $this->_config->getTable();
	}
	
	/**
	 * Get specific master db from config file
	 * 
	 * @param int $cid Cluster index number
	 * @param int $sid Server index number
	 * @return bool
	 */
	private function _getMaster ($cid = null, $sid = null)
	{
		// get master db
		$configs = array();
		if (!is_null($cid) && !is_null($sid)) {
			$configs = $this->_config->getDb($this->dbName, $cid, 'master', $sid);
		}
		return $configs;
	}
	
	/**
	 * Get specific slave db from config file
	 * 
	 * @param int $cid Cluster index number
	 * @param int $sid Server index number
	 * @return bool
	 */
	private function _getSlave ($cid = null, $sid = null)
	{
		// get slave db
		$configs = array();
		if (!is_null($cid) && !is_null($sid)) {
			$configs = $this->_config->getDb($this->dbName, $cid, 'slave', $sid);
		}
		return $configs;
	}
	
	/**
	 * Set debug mode
	 *
	 * @return Hush_Db_Dao
	 */
	public function debug ($debug)
	{
	    $this->_debug = $debug;
	    return $this;
	}
	
	/**
	 * Get read db link
	 * 
	 * @param int $cid Cluster index number
	 * @param int $sid Server index number
	 * @return Hush_Db_Dao
	 */
	public function dbr ($cid = 0, $sid = 0)
	{
		$db = null;
		
		// try to get specific db
		$configs = $this->_getSlave($cid, $sid);
		
		// try to get sharded db config
		if (!$configs || $this->_shardId) {
		    // do sharding & set db cluster
			$this->_doShardDb();
			// get random slave server from db cluster
			$configs = $this->_config->getSlaveDb();
		}
		
		// try to init db
		if ($configs) {
			// specific db ?
			if ($this->_configs) {
				$configs = array_merge($configs, $this->_configs);
			} else {
				$configs['name'] = $this->shardDbName ? $this->shardDbName : $this->dbName;
			}
			$db = Hush_Db::dbPool($configs, $this->charset);
			$db->_debug = $this->_debug;
		}
		
		// try to shard table
		$this->_doShardTable();
		
		return $db;
	}
	
	/**
	 * Get write db link
	 * 
	 * @param int $cid Cluster index number
	 * @param int $sid Server index number
	 * @return Hush_Db_Dao
	 */
	public function dbw ($cid = 0, $sid = 0)
	{
		$db = null;
		
		// try to get specific db
		$configs = $this->_getMaster($cid, $sid);
		
		// try to get sharded db config
		if (!$configs || $this->_shardId) {
			// do sharding & set db cluster
			$this->_doShardDb();
			// get random master server from db cluster
			$configs = $this->_config->getMasterDb();
		}
		
		// try to init db
		if ($configs) {
			// specific db ?
			if ($this->_configs) {
				$configs = array_merge($configs, $this->_configs);
			} else {
				$configs['name'] = $this->shardDbName ? $this->shardDbName : $this->dbName;
			}
			$db = Hush_Db::dbPool($configs, $this->charset);
			$db->_debug = $this->_debug;
		}
		
		// try to shard table
		$this->_doShardTable();
		
		return $db;
	}
	
	/**
	 * Set sharding Id
	 * 
	 * @param int $shardId
	 * @return Hush_Db_Dao
	 */
	public function shard ($shardId = 0)
	{
		$this->_shardId = $shardId;
		return $this;
	}
	
	/**
	 * Get sharding table name
	 * 
	 * @return string
	 */
	public function table ()
	{
		if (!$this->tableName) {
			throw new Hush_Db_Exception('Please bind table first');
		}
		return $this->shardTableName ? $this->shardTableName : $this->tableName;
	}
	
	/**
	 * Rewrite select method
	 * 
	 * @return mixed
	 */
	public function select ()
	{
		return $this->dbr()->select();
	}
	
	/**
	 * Create data by insert method
	 * 
	 * @param array $data
	 * @return mixed
	 */
	public function create ($data)
	{
		if ($this->dbw()->insert($this->table(), $data)) {
			return $this->dbw()->lastInsertId();
		}
		return false;
	}
	
	/**
	 * batch create
	 * 
	 * @param array $cols
	 * @param array $vals
	 */
	public function batchCreate ($cols, $vals)
	{
	    return $this->dbw()->insertMulti($this->table(), $cols, $vals);
	}
	
	/**
	 * Check data exists by primary key id
	 * 
	 * @param mixed $id Primary key value
	 * @param string $primkey Primary key name
	 * @param string $wheresql Where sql expr
	 * @return array
	 */
	public function exist ($id, $primkey = '', $wheresql = '')
	{
		$primkey = $primkey ? $primkey : $this->primkey;
		if ($wheresql) {
			$wheresql = $this->dbr()->select()->from($this->table(), '(1)')->where($wheresql);
		} else {
			$wheresql = $this->dbr()->select()->from($this->table(), '(1)')->where("$primkey = ?", $id);
		}
		return $this->dbr()->fetchOne($wheresql);
	}
	
	/**
	 * Check data exists by primary key id
	 *
	 * @param mixed $id Primary key value
	 * @param string $primkey Primary key name
	 * @param string $wheresql Where sql expr
	 * @return array
	 */
	public function count ($id, $primkey = '', $wheresql = '')
	{
		$primkey = $primkey ? $primkey : $this->primkey;
		if ($wheresql) {
			$wheresql = $this->dbr()->select()->from($this->table(), 'count(*)')->where($wheresql);
		} else {
			$wheresql = $this->dbr()->select()->from($this->table(), 'count(*)')->where("$primkey = ?", $id);
		}
		return $this->dbr()->fetchOne($wheresql);
	}
	
	/**
	 * Load data by primary key id
	 * 
	 * @param mixed $id Primary key value
	 * @param string $primkey Primary key name
	 * @param string $wheresql Where sql expr
	 * @return array
	 */
	public function read ($id, $primkey = '', $wheresql = '')
	{
		$primkey = $primkey ? $primkey : $this->primkey;
		if ($wheresql) {
			$wheresql =  $this->dbr()->select()->from($this->table())->where($wheresql);
		} else {
			$wheresql =  $this->dbr()->select()->from($this->table())->where("$primkey = ?", $id);
		}
		return $this->dbr()->fetchRow($wheresql);
	}
	
    /**
     * Load data by where query
     * 
     * @param string $findwhat
     * @param string $wheresql
     * @return array
     */
	public function find ($findwhat = '', $wheresql = '')
	{
	    $findwhat = $findwhat ? $findwhat : '*';
	    $wheresql = $wheresql ? $wheresql : '1=1';
	    $findsql =  "select {$findwhat} from ".$this->table()." where {$wheresql}";
	    return $this->dbr()->query($findsql)->fetchAll();
	}
	
	/**
	 * Forupdate for transaction
	 * 
	 * @param int $id
	 * @param array $fields
	 * @throws Exception
	 * @return void
	 */
	public function forupdate ($id, $fields=array('*'))
	{
	    if (!$id || !is_numeric($id)) throw new Exception('primkey is not int');
	    $fields = $fields ? $fields : array("*");
	    	    
	    $sql = $this->dbw()->select()->from($this->table(), $fields);
	    $sql->where("$this->primkey = ?", $id);
	    $sql->forUpdate(true);
	    
	    return $this->dbw()->fetchRow($sql);
	}
	
	/**
	 * Update specific data by where expr
	 * 
	 * @param array $data Update data
	 * @param string $primkey Primary key name
	 * @param string $wheresql Where sql expr
	 * @return bool
	 */
	public function update ($data, $primkey = '', $wheresql = '')
	{
		if (!$wheresql) {
			$primkey = $primkey ? $primkey : $this->primkey;
			if (!isset($data[$primkey])) {
				throw new Hush_Db_Exception('Can not find primary key in data');
			}
			$wheresql = $this->dbw()->quoteInto("{$primkey} = ?", $data[$primkey]);
		}
		return $this->dbw()->update($this->table(), $data, $wheresql);
	}
	
	/**
	 * Delete data by primary key id
	 * 
	 * @param mixed $id Primary key value
	 * @param string $primkey Primary key name
	 * @param string $wheresql Where sql expr
	 * @return bool
	 */
	public function delete ($id, $primkey = '', $wheresql = '')
	{
		$primkey = $primkey ? $primkey : $this->primkey;
		$wheresql = $wheresql ? $wheresql : $this->dbw()->quoteInto("$primkey = ?", $id);
		return $this->dbw()->delete($this->table(), $wheresql);
	}
	
	/**
	 * Replace specific data by where expr
	 * 
	 * @param array $data Update data
	 * @param string $where Where sql expr
	 * @return bool
	 */
	public function replace ($data)
	{
		$affect_rows = $this->dbw()->replace($this->table(), $data);
		return ($affect_rows !== false) ? true : false;
	}
	
	/**
	 * Start transaction
	 * 
	 * @return bool
	 */
	public function beginTransaction ()
	{
	    $dbc = $this->dbw();
	    $link = $this->_getDbLink($dbc);
	    if (!$link) return false;
	    // init trans array at the first time
	    if (!isset($this->_trans[$link])) {
	        $this->_trans[$link] = 0;
	        $dbc->beginTransaction();
	    }
	    // step into trans call
	    $this->_trans[$link] += 1;
	    return true;
	}
	
	/**
	 * Commit the transaction
	 *
	 * @return bool
	 */
	public function commit ()
	{
	    $dbc = $this->dbw();
	    $link = $this->_getDbLink($dbc);
	    if (!$link) return false;
	    // must call beginTransaction firstly
	    if (isset($this->_trans[$link])) {
	        // commit only in the outermost trans
	        if ($this->_trans[$link] == 1) {
	            $dbc->commit();
	        }
	        // step out trans call
	        $this->_trans[$link] -= 1;
	    }
	    return true;
	}
	
	/**
	 * Rollback the transaction
	 *
	 * @return bool
	 */
	public function rollback ()
	{
	    $dbc = $this->dbw();
	    $link = $this->_getDbLink($dbc);
	    if (!$link) return false;
	    // must call beginTransaction firstly
	    if (isset($this->_trans[$link])) {
	        // rollback only in the outermost trans
	        if ($this->_trans[$link] == 1) {
	            $dbc->rollback();
	        }
	        // step out trans call
	        $this->_trans[$link] -= 1;
	    }
	    return true;
	}
}