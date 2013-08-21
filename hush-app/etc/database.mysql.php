<?php
/**
 * MySQL config file
 */
require_once 'Hush/Db/Config.php';

class MysqlConfig extends Hush_Db_Config
{
	// 配置名：必须和类名一致
	protected static $_configName = 'MysqlConfig';
	
	// 手动配置数据库服务器
	protected $_clusters = array
	(
		// 默认分库集群策略
		'default' => array(
			// cluster 0
			array(
				'master' => array(
					array(
						'type' => self::DEFAULT_TYPE, 
						'host' => self::DEFAULT_HOST, 
						'port' => self::DEFAULT_PORT, 
						'user' => self::DEFAULT_USER, 
						'pass' => self::DEFAULT_PASS
					)
				),
				'slave'  => array(
					array(
						'type' => self::DEFAULT_TYPE, 
						'host' => self::DEFAULT_HOST, 
						'port' => self::DEFAULT_PORT, 
						'user' => self::DEFAULT_USER, 
						'pass' => self::DEFAULT_PASS
					)
				)
			),
			// cluster 1
			array(
				'master' => array(
					array(
						'type' => self::DEFAULT_TYPE, 
						'host' => self::DEFAULT_HOST, 
						'port' => self::DEFAULT_PORT, 
						'user' => self::DEFAULT_USER, 
						'pass' => self::DEFAULT_PASS
					)
				),
				'slave'  => array(
					array(
						'type' => self::DEFAULT_TYPE, 
						'host' => self::DEFAULT_HOST, 
						'port' => self::DEFAULT_PORT, 
						'user' => self::DEFAULT_USER, 
						'pass' => self::DEFAULT_PASS
					)
				)
			)
			// cluster N
			// ...
		)
	);
	
	// 重写分库策略
	public function doShardDb ($dbName, $tbName, $shardId)
	{
		// Product 数据平均分配到 2 组 cluster
		if (!strcasecmp($tbName, 'product')) {
			$this->setClusterDb($dbName, $shardId % 2);
		}
		// 默认使用 cluster 0
		else {
			$this->setClusterDb($dbName, 0);
		}
	}
	
	// 重写分表策略
	public function doShardTable ($dbName, $tbName, $shardId)
	{
		// Product 数据平均分配到 2 张 table
		if (!strcasecmp($tbName, 'product')) {
			return $this->setTable($tbName.'_'.($shardId % 2));
		}
		// 默认使用原始表名
		else {
			$this->setTable($tbName);
		}
	}
	
	/**
	 * 获取配置单例对象
	 * @return Hush_Db_Config
	 */
	public static function getInstance () 
	{
		static $dbConfig;
		if ($dbConfig == null) {
			$dbConfig = new MysqlConfig();
		}
		return $dbConfig;
	}
}
