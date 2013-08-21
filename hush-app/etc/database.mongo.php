<?php
/**
 * MongoDB config file
 */
require_once 'Hush/Mongo/Config.php';

class MongoConfig extends Hush_Mongo_Config
{
	// 手动配置
	private $_servers = array(
		// cluster1
		'm1' => array('host' => '127.0.0.1', 'port' => '27017', 'username' => null, 'password' => null),
		's1' => array('host' => '127.0.0.1', 'port' => '27018', 'username' => null, 'password' => null),
		// cluster2
		'm2' => array('host' => '127.0.0.1', 'port' => '27017', 'username' => null, 'password' => null),
		's2' => array('host' => '127.0.0.1', 'port' => '27018', 'username' => null, 'password' => null),
		// replica set
		'r1' => array('host' => '127.0.0.1', 'port' => '27019', 'username' => null, 'password' => null),
		'r2' => array('host' => '127.0.0.1', 'port' => '27020', 'username' => null, 'password' => null),
		'r3' => array('host' => '127.0.0.1', 'port' => '27021', 'username' => null, 'password' => null),
	);
	
	// 主从策略（默认）
	public function getDefault($dbName, $colName) {
		return array(
			'master'	=> array($this->_servers['m1']),
			'slave'		=> array($this->_servers['s1']),
		);
	}
	
	// ReplicaSet策略（Mongo特有）
	public function getReplicaSet($dbName, $colName) {
		return array(
			$this->_servers['r1'],
			$this->_servers['r2'],
			$this->_servers['r3'],
		);
	}
	
	// 分库策略
	public function getShardDatabase($dbName, $colName, $shardId) {
		// 平均分配到2组cluster上去
		switch ($shardId % 2) {
			case 1:
				return array(
					'master'	=> array($this->_servers['m1']),
					'slave'		=> array($this->_servers['s1']),
				);
			case 0:
			default:
				return array(
					'master'	=> array($this->_servers['m2']),
					'slave'		=> array($this->_servers['s2']),
				);
		}
	}
	
	// 分表策略
	public function getShardCollection($dbName, $colName, $shardId) {
		return $colName.'_'.($shardId % 2);
	}
	
	// 获取单例
	public function getInstance () {
		static $mongoConfig;
		if ($mongoConfig == null) {
			$mongoConfig = new MongoConfig();
		}
		return $mongoConfig;
	}
}
