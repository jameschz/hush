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
 
/**
 * @package Hush_Mongo
 */
abstract class Hush_Mongo_Config
{
	/**
	 * 默认Mongo参数
	 * @static
	 */
	const DEFAULT_HOST = '127.0.0.1';
	const DEFAULT_PORT = '27017';
	const DEFAULT_USER = null;
	const DEFAULT_PASS = null;
	
	/**
	 * 必须实现单例
	 * @param string $dbName
	 * @param string $tbName
	 */
	abstract public function getInstance();
	
	/**
	 * 默认策略
	 * @param string $dbName
	 * @param string $tbName
	 */
	abstract public function getDefault($dbName, $tbName);
	
	/**
	 * ReplicaSet策略
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	abstract public function getReplicaSet($dbName, $tbName);
	
	/**
	 * 分库策略
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	abstract public function getShardDatabase($dbName, $tbName, $shardId);
	
	/**
	 * 分表策略
	 * @param string $dbName
	 * @param string $tbName
	 * @param int|string $shardId
	 */
	abstract public function getShardCollection($dbName, $tbName, $shardId);
}