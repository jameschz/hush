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
	 * TYPE : Pdo_Mysql / Mysqli
	 * @static
	 */
	const DEFAULT_TYPE = 'Pdo_Mysql';
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
	 * @var string
	 */
	private $_dbName = null;
	
	/**
	 * @var string
	 */
	private $_table = null;
	
	/**
	 * @var Hush_Db_Config
	 */
	private static $_instance = null;
	
	/**
	 * 获取所有 clusters
	 * @return array
	 */
	public function getAllClusters ()
	{
        return $this->_clusters;
	}
	
	/**
	 * 获取对应数据库的 masters
	 * 用于操作 Db 的后台脚本
	 * @return array
	 */
	public function getClusterMasters ($dbName = '')
	{
	    $masters = array();
	    // TODO : default get all masters
	    if (!$dbName) {
	        throw new Hush_Db_Exception('Please specify db name');
	    }
	    // find specific db cluster
	    if (isset($this->_clusters[$dbName])) {
	        foreach ((array) $this->_clusters[$dbName] as $db_cluster) {
	            $masters[$dbName] = $db_cluster['master']; // get all masters
	        }
	    }
	    // search matched db cluster
	    if (!$masters) {
	        foreach (array_keys($this->_clusters) as $rule) {
	            $pattern = preg_quote($rule, '/');
	            $pattern = str_replace('\*', '(.*?)', $pattern);
	            if (preg_match('/^' .$pattern . '$/i', $dbName)) {
	                foreach ((array) $this->_clusters[$rule] as $db_cluster) {
	                    $masters[$dbName] = $db_cluster['master']; // get all masters
	                }
	            }
	        }
	    }
	    // use default db cluster
	    if (!$masters) {
	        foreach ((array) $this->_clusters['default'] as $db_cluster) {
	            $masters[$dbName] = $db_cluster['master']; // get all masters
	        }
	    }
	    return $masters;
	}
	
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
		if (!$clusters) {
    		foreach (array_keys($this->_clusters) as $rule) {
    		    $pattern = preg_quote($rule, '/');
    		    $pattern = str_replace('\*', '(.*?)', $pattern);
    		    if (preg_match('/^' .$pattern . '$/i', $dbName)) {
    				$clusters = (array) $this->_clusters[$rule];
    				break;
    			}
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
	    $this->setDbName($dbName);
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
	 * 设置 db name
	 * @return string
	 */
	public function setDbName ($dbName)
	{
	    $dbSuffix = defined('__HUSH_ENV') 
	       && strlen(__HUSH_ENV) 
	       && strcmp(__HUSH_ENV, 'rc') 
	       && strcmp(__HUSH_ENV, 'local') 
	       && strcmp(__HUSH_ENV, 'release') ? '_' . __HUSH_ENV : '';
	    return $this->_dbName = $dbName . $dbSuffix;
	}
	
	/**
	 * 获取 db name
	 * @return string
	 */
	public function getDbName ()
	{
		return $this->_dbName;
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
	 * 分库策略（子类重写）
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	public function doShardDb ($dbName, $tbName, $shardId)
	{
		return $this->setDb($dbName, 0);
	}
	
	/**
	 * 分表策略（子类重写）
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	public function doShardTable ($dbName, $tbName, $shardId)
	{
		return $this->setTable($tbName);
	}
	
	/**
	 * 必须实现单例
	 * @param string $dbName
	 * @param string $tbName
	 */
//	abstract public function getInstance();
}