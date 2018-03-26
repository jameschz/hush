<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Mongo
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

// Check if mongo extension is installed
if (!class_exists('Mongo')) {
	die('Please install mongo extension first.');
}

/**
 * @see Hush_Mongo_Exception
 */
require_once 'Hush/Mongo/Exception.php';
require_once 'Pdt/Libs.php';

/**
 * @package Hush_Mongo
 */
class Hush_Mongo
{
	/**
	 * Constants for replica set / master / slave
	 * 
	 * @var string
	 */
	const REPLSET = 0;
	const MASTER = 1;
	const SLAVE = 2;
	
	/**
	 * Stores the connection
	 * 
	 * @var string
	 */
	protected $_conn;
	
	/**
	 * Stores the db link
	 * 
	 * @var string
	 */
	protected $_link;
	
	/**
	 * MongoDB collection
	 * 
	 * @var string
	 */
	protected $_mongo;
	
	/**
	 * Name of MongoDB database
	 * 
	 * @var string
	 */
	protected $_database;
	
	/**
	 * Name of MongoDB collection
	 * 
	 * @var string
	 */
	protected $_collection;
	
	/**
	 * Persistent connection to DB
	 * 
	 * @var string
	 */
	protected $_persistent;
	
	/**
	 * Name of persistent connection
	 * 
	 * @var string
	 */
	protected $_persistentId;
	
	/**
	 * Whether supporting replicaSet
	 * 
	 * @var string
	 */
	protected $_replicaSet;
	
	/**
	 * Default mongodb configs
	 * 
	 * @var array
	 */
	protected $_config = array(
		'database'		=> null,	// name of MongoDB database
		'collection'	=> null,	// name of MongoDB collection
		'persistent'	=> false,	// persistent connection to DB
		'persistentId'	=> null,	// name of persistent connection
		'replicaSet'	=> null,	// whether we're supporting replicaSet
		
		// array of mongo db servers ; should be set
        'servers'		=> array()
	);
	
	/**
	 * Default config servers
	 * 
	 * @var array
	 */
	protected $_configClass = null;
	
	/**
	 * Default capped configs
	 * 
	 * @var array
	 */
	protected $_cappedConfig = array(
		'capped'		=> true,	// if capped collection
		'size'			=> 10000,	// max size of each rowset
		'max'			=> 10000	// max size of capped collection
	);
	
	/**
	 * Database Shard key
	 * 
	 * @var string
	 */
	protected $_dbShardKey;
	
	/**
	 * Database Shard value
	 * 
	 * @var string
	 */
	protected $_dbShardVal;
	
	/**
	 * Collecton Shard key
	 * 
	 * @var string
	 */
	protected $_colShardKey;
	
	/**
	 * Collecton Shard value
	 * 
	 * @var string
	 */
	protected $_colShardVal;
	
	/**
	 * Constructor
	 * Initialize mongo db connection and collection object
	 * 
	 * @param array $servers
	 * @return void
	 */
	public function __construct($configClass = null)
	{
		if ($configClass != null) {
			if (!($configClass instanceof Hush_Mongo_Config)) {
				throw new Hush_Mongo_Exception('MongoDB config class error.');
			}
			$this->_configClass = $configClass;
		}
		
		// get db configs from subclasses
		$this->_config['database'] = $this->_database;
		$this->_config['collection'] = $this->_collection;
		$this->_config['persistent'] = $this->_persistent;
		$this->_config['persistentId'] = $this->_persistentId;
		$this->_config['replicaSet'] = $this->_replicaSet;
	}
	
	/**
	 * Initialize MongoDB. There is currently no support for persistent
	 * connections. It would be very easy to implement, I just didn't need it.
	 * 
	 * @param array $config
	 * @return void
	 */
	protected function _connect($config)
	{
		// ensure they supplied a database
		if (empty($config['database'])) {
			throw new Hush_Mongo_Exception('You must specify a MongoDB database.');
		}
		
		if (empty($config['collection'])) {
			throw new Hush_Mongo_Exception('You must specify a MongoDB collection.');
		}
		
		// generate server connection strings
		$connDsnArr = array();
		if (!empty($config['servers'])) {
			foreach ((array) $config['servers'] as $server) {
				$str = '';
				if (!empty($server['username']) && !empty($server['password'])) {
					$str .= $server['username'] . ':' . $server['password'] . '@';
				}
				$str .= !empty($server['host']) ? $server['host'] : Mongo::DEFAULT_HOST;
				$str .= ':' . (!empty($server['port']) ? (int) $server['port'] : Mongo::DEFAULT_PORT);
				array_push($connDsnArr, $str);
			}
		} else {
			// use default connection settings
			array_push($connDsnArr, Mongo::DEFAULT_HOST . ':' . Mongo::DEFAULT_PORT);
		}
		
		// add immediate connection
		$opts = array('connect' => true);
		
		// support persistent connections
		if ($config['persistent'] && !empty($config['persistentId'])) {
			$opts['persist'] = $config['persistentId'];
		}
		
		// support replica sets
		if ($config['replicaSet']) {
			$opts['replicaSet'] = true;
		}
		
		// init connection pool
		static $connPool = array();
		$dsn = 'mongodb://' . implode(',', $connDsnArr);
		if (isset($connPool[$dsn])) {
			if (!($connPool[$dsn] instanceof Mongo)) {
				throw new Hush_Mongo_Exception("Connection pool instance error.");
			}
			$this->_conn = $connPool[$dsn];
		} else {
			// do connection
			try {
				$this->_conn = new Mongo($dsn, $opts);
			} catch (Exception $e) {
				throw new Hush_Mongo_Exception("Can not connect to MongoDB server '$dsn'.");
			}
			// push into connection pool
			$connPool[$dsn] = $this->_conn;
		}
		
		// init mongo link
		try {
			$this->_link = $this->_conn->selectDB($config['database']);
		} catch (InvalidArgumentException $e) {
			throw new Hush_Mongo_Exception('MongoDB database does not exist.');
		}
		
		// init mongo collection
		try {
			$this->_mongo = $this->_link->selectCollection($config['collection']);
		} catch(Exception $e) {
			throw new Hush_Mongo_Exception('MongoDB collection does not exist.');
		}
	}
	
	/**
	 * Return protected config variable
	 *
	 * @return array
	 */
	public function getConfig()
	{
		return $this->_config;
	}
	
	/**
	 * Return specific MongoDB API
	 * We can use this function to use the native API
	 *
	 * @return MongoDB
	 */
	public function getLink()
	{
		return $this->_link;
	}
	
	/**
	 * Get mongo config
	 * 
	 * @param int $type self::REPLSET / self::MASTER / self::SLAVE
	 * @return array
	 */
	protected function _getMongoConfig($type = self::REPLSET)
	{
		// copy mongo config
		$mongoConfig = $this->_config;
		
		// rebuild mongo config
		if ($this->_configClass != null) {
		
			// init default db servers
			$dbServers = $this->_configClass->getDefault($this->_database, $this->_collection);
			
			// force sharding db servers
			if ($this->_dbShardKey) {
				$dbServers = $this->_configClass->getShardDatabase($this->_database, $this->_collection, $this->_dbShardVal);		
			}
			
			// force to be replica set
			if ($this->_replicaSet) {
				$type = self::REPLSET;
				$dbServers = $this->_configClass->getReplicaSet();
			}
			
			// check mongo servers format
			if (!isset($dbServers['master']) || !isset($dbServers['slave'])) {
				throw new Hush_Mongo_Exception('MongoDB servers must contain master and slave keys.');
			}
			
			// get db config servers
			switch ($type) {
				case self::MASTER :
					$mongoConfig['servers'] = (array) $dbServers['master'];
					break;
				case self::SLAVE :
					$mongoConfig['servers'] = (array) $dbServers['slave'];
					break;
				case self::REPLSET :
				default :
					$mongoConfig['servers'] = (array) $dbServers;
					break;
			}
			
			// get sharding collection
			if ($this->_colShardKey) {
				$mongoConfig['collection'] = $this->_configClass->getShardCollection($this->_database, $this->_collection, $this->_colShardVal);
			}
		}
		
		return $mongoConfig;
	}
	
	/**
	 * Return specific MongoCollection API
	 * We can use this function to use the native API
	 *
	 * @param int $type self::REPLSET / self::MASTER / self::SLAVE
	 * @return MongoCollection
	 */
	protected function _getMongo($type)
	{
		try {
			$this->_connect($this->_getMongoConfig($type));
		} catch (Exception $e) {
			die(__CLASS__ . " : " . $e->getMessage() . "\n");
		}
	}
	
	/**
	 * Return master database's MongoCollection API
	 *
	 * @return MongoCollection
	 */
	public function getMaster()
	{
		$this->_getMongo(self::MASTER);
		return $this->doForMasterOnly();
	}
	
	/**
	 * Return slave database's MongoCollection API
	 *
	 * @return MongoCollection
	 */
	public function getSlave()
	{
		$this->_getMongo(self::SLAVE);
		return $this->doForSlaveOnly();
	}
	
	/**
	 * Reserved interface for task only for master database
	 *
	 * @return void
	 */
	public function doForMasterOnly()
	{
		return true;
	}
	
	/**
	 * Reserved interface for task only for slave database
	 *
	 * @return void
	 */
	public function doForSlaveOnly()
	{
		return true;
	}
	
	/**
	 * CRUD : Create
	 *
	 * @param array $data Insert data
	 * @param array $options Includeing safe / fsync / timeout
	 * @return mixed
	 */
	public function create($data = array(), $options = array())
	{
		// auto sharding
		$data = $this->_doAutoSharding($data);
		// insert into master
		if ($this->getMaster()) {
			return $this->_mongo->insert($data, $options);
		}
		return false;
	}
	
	/**
	 * CRUD : Batch Create
	 *
	 * @param array $dataArr Insert data array
	 * @param array $options Includeing safe / fsync / timeout
	 * @return mixed
	 */
	public function batchCreate($dataArr = array(), $options = array())
	{
		// auto sharding
		foreach ($dataArr as $id => $data) {
			$dataArr[$id] = $this->_doAutoSharding($data);
		}
		// insert into master
		if ($this->getMaster()) {
			return $this->_mongo->batchInsert($dataArr, $options);
		}
		return false;
	}
	
	/**
	 * CRUD : Read
	 *
	 * @param array $query Querying data
	 * @param array $fields Fetching fields
	 * @return mixed
	 */
	public function read($query = array(), $fields = array())
	{
		// auto sharding
		$query = $this->_doAutoSharding($query);
		// read from slave
		if ($this->getSlave()) {
			return $this->_mongo->find($query, $fields);
		}
		return false;
	}
	
	/**
	 * CRUD : Read one
	 *
	 * @param array $query Querying data
	 * @param array $fields Fetching fields
	 * @return mixed
	 */
	public function readOne($query = array(), $fields = array())
	{
		// auto sharding
		$query = $this->_doAutoSharding($query);
		// read from slave
		if ($this->getSlave()) {
			return $this->_mongo->findOne($query, $fields);
		}
		return false;
	}
	
	/**
	 * CRUD : Update
	 *
	 * @param array $query Querying data
	 * @param array $data Update data array
	 * @param array $options Includeing safe : false / fsync : false / timeout : false
	 * @return bool
	 */
	public function update($query = array(), $data = array(), $options = array())
	{
		// auto sharding
		$query = $this->_doAutoSharding($query);
		$data = $this->_doAutoSharding($data);
		// update into master
		if ($this->getMaster()) {
			$options = $options ? $options : array('upsert' => true);
			return $this->_mongo->update($query, array('$set' => $data), $options);
		}
		return false;
	}
	
	/**
	 * CRUD : Delete
	 *
	 * @param array $query Querying data
	 * @param array $options Includeing safe / fsync / timeout
	 * @return mixed
	 */
	public function delete($query, $options = array())
	{
		// auto sharding
		$query = $this->_doAutoSharding($query);
		// delete from master
		if ($this->getMaster()) {
			return $this->_mongo->remove($query, $options);
		}
		return false;
	}
	
	/**
	 * Replace database sharding key
	 *
	 * @param array $shardKey Sharding key name
	 * @return $this
	 */
	public function setDbShardKey($shardKey)
	{
		$this->_dbShardKey = (string) $shardKey;
		return $this;
	}
	
	/**
	 * Replace database sharding value
	 *
	 * @param array $shardVal Sharding key value
	 * @return $this
	 */
	public function setDbShardVal($shardVal)
	{
		$this->_dbShardVal = (int) $shardVal;
		return $this;
	}
	
	/**
	 * Replace table sharding key
	 *
	 * @param array $shardKey Sharding key name
	 * @return $this
	 */
	public function setTableShardKey($shardKey)
	{
		$this->_dbShardKey = (string) $shardKey;
		return $this;
	}
	
	/**
	 * Replace table sharding value
	 *
	 * @param array $shardVal Sharding key value
	 * @return $this
	 */
	public function setTableShardVal($shardVal)
	{
		$this->_dbShardVal = (int) $shardVal;
		return $this;
	}
	
	/**
	 * Auto-set shard key & value
	 *
	 * @param array $data Auto-set shard key & value into data or query
	 * @return $this
	 */
	protected function _doAutoSharding($data)
	{
		// force set db sharding value
		if (isset($data[$this->_dbShardKey])) {
			$this->_dbShardVal = $data[$this->_dbShardKey];
		}
		// force set collection sharding value
		if (isset($data[$this->_colShardKey])) {
			$this->_colShardVal = $data[$this->_colShardKey];
		}
		return $data;
	}
	
	/**
	 * Check if collection exists
	 *
	 * @param string $collectionName Collection name
	 * @return bool
	 */
	public function collectionExists ($collectionName) {
		$ret = $this->_link->$collectionName->validate();
		return ($ret && $ret['ok']) ? true : false;
	}
	
	/**
	 * Initialize common collections
	 * 
	 * @return bool
	 */
	protected function _initCollection($indexSets = array())
	{
		if (!$this->_collection) {
			throw new Hush_Mongo_Exception('Please init capped collection first.');
		}
		if (!$this->collectionExists($this->_collection)) {
			$mongo = $this->_link->createCollection($this->_collection);
			foreach ($indexSets as $indexSet) {
				if (is_array($indexSet['indexKeys']) && is_array($indexSet['indexOpts'])) {
					$mongo->ensureIndex($indexSet['indexKeys'], $indexSet['indexOpts']);
				}
			}
		}
		return true;
	}
	
	/**
	 * Initialize capped collections
	 * 
	 * @return bool
	 */
	protected function _initCappedCollection($indexSets = array())
	{
		if (!$this->_collection) {
			throw new Hush_Mongo_Exception('Please init capped collection first.');
		}
		if (!$this->_cappedConfig) {
			throw new Hush_Mongo_Exception('Please init capped collection\'s configs.');
		}
		if (!$this->collectionExists($this->_collection)) {
			$mongo = $this->_link->createCollection($this->_collection, $this->_cappedConfig['capped'], $this->_cappedConfig['size'], $this->_cappedConfig['max']);
			foreach ($indexSets as $indexSet) {
				if (is_array($indexSet['indexKeys']) && is_array($indexSet['indexOpts'])) {
					$mongo->ensureIndex($indexSet['indexKeys'], $indexSet['indexOpts']);
				}
			}
		}
		return true;
	}
}