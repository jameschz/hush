<?php
/**
 * MySQL config file
 */
require_once 'Hush/Db/Config.php';

class App_Db_Config extends Hush_Db_Config
{
    protected $_shard_log_tables = array('log_account_reg', 'log_account_login', 'log_account_online');
    
    // 确认必须有 shard id
    private function _checkShardId ($shardId)
    {
        if (!$shardId) {
            throw new Exception('ShardDbError::shardId');
            return false;
        }
        return true;
    }
    
    // 重写分库逻辑
    public function doShardDb ($dbName, $tbName, $shardId)
    {
        // 运行基类逻辑
        parent::doShardDb($dbName, $tbName, $shardId);
        
        // 分库日志表处理逻辑
        if (in_array($tbName, $this->_shard_log_tables)) {
            $this->_checkShardId($shardId);
            //
            if($shardId)
            {
                $dbName = 'hush_log_'.date('Y', strtotime($shardId));
            } else {
                $dbName = 'hush_log_'.date('Y');
            }



            $this->setDb($dbName, 0); // 设置分库名，并选择 cluster 0
        }
    }
    
    // 重写分表逻辑
    public function doShardTable ($dbName, $tbName, $shardId)
    {
        // 运行基类逻辑
        parent::doShardTable($dbName, $tbName, $shardId);
        
        // 分库日志表处理逻辑
        if (in_array($tbName, $this->_shard_log_tables)) {
            $this->_checkShardId($shardId);
            $tbName = $tbName.'_'.$shardId;
            $this->setTable($tbName); // 设置分表名
        }
    }
}
