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
 * @see Hush_Db_Exception
 */
require_once 'Hush/Db/Exception.php';

/**
 * @package Hush_Db
 */
abstract class Hush_Db_Config
{
	/**
	 * 默认数据库参数
	 * @static
	 */
	const DEFAULT_TYPE = 'MYSQLI';
	const DEFAULT_HOST = '127.0.0.1';
	const DEFAULT_PORT = '3306';
	const DEFAULT_USER = 'root';
	const DEFAULT_PASS = 'passwd';
	
	/**
	 * 每个 cluster 包含一个 master 和 slave 数组
	 * @var array
	 */
	protected $_clusters = array
	(
		// 默认分库集群策略
		'default' => array(
			// cluster1
			array(
				'master' => array(
					array('type' => self::DEFAULT_TYPE, 'host' => self::DEFAULT_HOST, self::DEFAULT_PORT, 'user' => '', 'pass' => '')
				),
				'slave'  => array(
					array('type' => self::DEFAULT_TYPE, 'host' => self::DEFAULT_HOST, self::DEFAULT_PORT, 'user' => '', 'pass' => '')
				)
			)
			// cluster N
			// ...
		)
	);
	
	/**
	 * @var array
	 */
	private $_cluster = null;
	
	/**
	 * @var array
	 */
	private $_table = null;
	
	/**
	 * @var Hush_Db_Config
	 */
	private static $_instance = null;
	
	/**
	 * 设置目前 cluster
	 * @param string $dbName
	 * @param int|string $clusterId
	 * @return bool
	 */
	protected function setClusterDb ($dbName, $clusterId = 0) 
	{
		$clusters = array();
		// find specific db cluster
		if (isset($this->_clusters[$dbName])) {
			$clusters = (array) $this->_clusters[$dbName];
		}
		// search matched db cluster
		foreach (array_keys($this->_clusters) as $key) {
			if (preg_match("/$key/i", $dbName)) {
				$clusters = (array) $this->_clusters[$key];
			}
		}
		// use default db cluster
		if (!$clusters) {
			$clusters = (array) $this->_clusters['default'];
		}
		// defaultly return the first cluster
		if (!isset($clusters[$clusterId])) {
			throw new Hush_Db_Exception('Can not found cluster');
		}
		$this->_cluster = $clusters[$clusterId];
		return true;
	}
	
	/**
	 * 获取目前 cluster 的主库
	 * @return array
	 */
	public function getMasterDb () 
	{
		if (!$this->_cluster || !$this->_cluster['master']) {
			throw new Hush_Db_Exception('Can not found master db');
		}
		$randId = array_rand($this->_cluster['master']);
		return $this->_cluster['master'][$randId];
	}
	
	/**
	 * 获取目前 cluster 的从库
	 * @return array
	 */
	public function getSlaveDb () 
	{
		if (!$this->_cluster || !$this->_cluster['slave']) {
			throw new Hush_Db_Exception('Can not found slave db');
		}
		$randId = array_rand($this->_cluster['slave']);
		return $this->_cluster['slave'][$randId];
	}
	
	/**
	 * 设置目前 cluster db
	 * @param string $dbName
	 * @param int $clusterId
	 * @return bool
	 */
	protected function setDb ($dbName, $clusterId = 0)
	{
		return $this->setClusterDb($dbName, $clusterId);
	}
	
	/**
	 * 获取特定 cluster db
	 * @param string $dbName
	 * @param int $clusterId
	 * @param string $dbType
	 * @param int $serverId
	 * @return array
	 */
	public function getDb ($dbName, $clusterId = 0, $dbType, $serverId = 0)
	{
		return $this->_clusters[$dbName][$clusterId][$dbType][$serverId];
	}
	
	/**
	 * 设置表名
	 * @param string $tbName
	 * @return bool
	 */
	protected function setTable ($tbName) 
	{
		$this->_table = $tbName;
		return true;
	}
	
	/**
	 * 获取表名
	 * @return string
	 */
	public function getTable () 
	{
		if (!$this->_table) {
			throw new Hush_Db_Exception('Can not found table');
		}
		return $this->_table;
	}
	
	/**
	 * 分库策略
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	public function doShardDb ($dbName, $tbName, $shardId)
	{
		$this->setClusterDb($dbName, 0);
	}
	
	/**
	 * 分表策略
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	public function doShardTable ($dbName, $tbName, $shardId)
	{
		$this->setTable($tbName);
	}
	
	/**
	 * 必须实现单例
	 * @param string $dbName
	 * @param string $tbName
	 */
//	abstract public function getInstance();
}