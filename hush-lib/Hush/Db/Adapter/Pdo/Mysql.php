<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Db
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: james $
 */
 
/**
 * @see Hush_Db_Extend
 */
require_once 'Hush/Db/Extend.php';

/**
 * @see Zend_Db_Adaptor_Pdo_Mysql
 */
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';

/**
 * @package Hush_Db
 */
class Hush_Db_Adapter_Pdo_Mysql extends Zend_Db_Adapter_Pdo_Mysql
{
    /**
     * @var string
     */
    public $_sql = '';
    
    /**
     * For debug mode
     * @var bool
     */
    public $_debug = false; // for trace sql
    
    /**
     * Set debug flag
     * 
     * @param bool $debug
     * @return void
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
    }
    
    /**
     * Replace table rows with specified data based on a WHERE clause.
     *
     * @param  mixed $table The table to update.
     * @param  array $bind  Column-value pairs.
     * @param  mixed $where UPDATE WHERE clause(s).
     * @return int   The number of affected rows.
     */
    public function replace($table, array $bind)
    {
        $sql = Hush_Db_Extend::replaceSql($this, $table, $bind);
        $stmt = $this->query($sql, array_values($bind));
        return $stmt->rowCount();
    }
    
    /**
     * Extend the insert method of Zend Db
     * We can use this method to insert multiple line's data oncely
     * @param string $table
     * @param array $cols
     * @param array $vals
     * @param bool $debug
     * @return mixed
     */
    public function insertMulti ($table, array $cols, array $vals)
    {
        $sql = Hush_Db_Extend::insertMultiSql($this, $table, $cols, $vals);
        $stmt = $this->query($sql);
        return $stmt->rowCount();
    }
    
    /**
     * Extend the insert method of Zend Db
     * We can use this method to insert multiple line's data oncely
     * @param string $table
     * @param array $cols
     * @param array $vals
     * @return mixed
     */
    public function insertMultiRow ($table, $cols, $vals)
    {
        return $this->insertMulti($table, $cols, $vals);
    }
    
    /**
     * Get all columns' name of specific table
     * @param string $table
     * @return array
     */
    public function showColumns ($table)
    {
        $cols = array();
        $res = $this->fetchAll('show columns from ' . $this->quoteIdentifier($table, true));
        foreach ($res as $col) {
            $cols[] = $col['Field'];
        }
        return $cols;
    }
    
    /**
     * Overload the query method of Zend Db
     * @param string $sql
     * @param array $bind
     * @return Zend_Db_Statement_Pdo
     */
    public function query($sql, $bind = array())
    {
        if ($this->_debug) {
            if ($sql instanceof Zend_Db_Select) {
                $sql = $sql->__toString();
            }
            Core_Util::core_log('Query Sql : '.$sql);
            $t1 = Core_Util::microtime_float();
        }
        
        try {
            $result = parent::query($sql, $bind);
        } catch (Exception $e) {
            // close connection if server has gone away
            if (stripos($e->getMessage(), 'mysql server has gone away') !== false) {
                parent::closeConnection();
            }
            throw $e;
        }
        
        if ($this->_debug) {
            $t2 = Core_Util::microtime_float();
            Core_Util::core_log('Query Time : '.($t2-$t1));
        }
        
        return $result;
    }
    
    /**
     * Overload the exec method of Zend Db
     * @param string $sql
     * @return integer Number of rows affected
     */
    public function exec($sql)
    {
        if ($this->_debug) {
            if ($sql instanceof Zend_Db_Select) {
                $sql = $sql->__toString();
            }
            Core_Util::core_log('Exec Sql : '.$sql);
            $t1 = Core_Util::microtime_float();
        }
        
        try {
            $result = parent::exec($sql);
        } catch (Exception $e) {
            // close connection if server has gone away
            if (stripos($e->getMessage(), 'mysql server has gone away') !== false) {
                parent::closeConnection();
            }
            throw $e;
        }
        
        if ($this->_debug) {
            $t2 = Core_Util::microtime_float();
            Core_Util::core_log('Exec Time : '.($t2-$t1));
        }
        
        return $result;
    }
}