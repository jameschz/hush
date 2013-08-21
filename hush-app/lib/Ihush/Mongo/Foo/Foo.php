<?php
/**
 * Lbs Dao
 *
 * @category   Lbs
 * @package    Lbs_Dao_Mongo
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'Ihush/Mongo.php';

/**
 * @package Lbs_Dao_Mongo
 */
class Foo_Foo extends Ihush_Mongo
{
	/**
	 * Stores the connection
	 * 
	 * @var string
	 */
	protected $_database = 'foo';

	/**
	 * Stores the mongo db
	 * 
	 * @var string
	 */
	protected $_collection = 'foo';
	
	/**
	 * Shard key
	 * 
	 * @var string
	 */
	protected $_dbShardKey = 'foo';
	
	/**
	 * Shard key
	 * 
	 * @var string
	 */
	protected $_colShardKey = 'foo';
	
	/**
	 * whether we're supporting replicaSet
	 * 
	 * @var string
	 */
	protected $_replicaSet = false;
	
	/**
	 * Create Index for shard key
	 * Do this action only for master connection
	 * 
	 * @return bool
	 */
	public function doForMasterOnly()
	{
		return $this->_initCollection(array(
			array(
				'indexKeys' => array('foo' => 1),
				'indexOpts' => array()
			)
		));
	}
	
	/**
	 * Constructor
	 * Must be implemented
	 */
	public function __construct()
	{
		parent::__construct(MongoConfig::getInstance());
	}
}