<?php
/**
 * MySQL config file
 */
require_once 'Hush/Db/Config.php';

// 公用数据库配置
define('__HUSH_DB_TYPE', Hush_Db_Config::DEFAULT_TYPE);
define('__HUSH_DB_HOST', Hush_Db_Config::DEFAULT_HOST);
define('__HUSH_DB_PORT', Hush_Db_Config::DEFAULT_PORT);
define('__HUSH_DB_USER', Hush_Db_Config::DEFAULT_USER);
define('__HUSH_DB_PASS', Hush_Db_Config::DEFAULT_PASS);

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
						'type' => __HUSH_DB_TYPE, 
						'host' => __HUSH_DB_HOST, 
						'port' => __HUSH_DB_PORT, 
						'user' => __HUSH_DB_USER, 
						'pass' => __HUSH_DB_PASS
					)
				),
				'slave'  => array(
					array(
						'type' => __HUSH_DB_TYPE, 
						'host' => __HUSH_DB_HOST, 
						'port' => __HUSH_DB_PORT, 
						'user' => __HUSH_DB_USER, 
						'pass' => __HUSH_DB_PASS
					)
				)
			),
			// cluster 1
			array(
				'master' => array(
					array(
						'type' => __HUSH_DB_TYPE, 
						'host' => __HUSH_DB_HOST, 
						'port' => __HUSH_DB_PORT, 
						'user' => __HUSH_DB_USER, 
						'pass' => __HUSH_DB_PASS
					)
				),
				'slave'  => array(
					array(
						'type' => __HUSH_DB_TYPE, 
						'host' => __HUSH_DB_HOST, 
						'port' => __HUSH_DB_PORT, 
						'user' => __HUSH_DB_USER, 
						'pass' => __HUSH_DB_PASS
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
			$this->setDb($dbName, $shardId % 2);
		}
		// 默认使用 cluster 0
		else {
			$this->setDb($dbName, 0);
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
