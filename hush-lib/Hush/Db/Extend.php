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
class Hush_Db_Extend
{
    public static function debugSql ($sql)
    {
        require_once 'Hush/Debug.php';
        $debug = Hush_Debug::getInstance();
        $debug->setWriter(new Hush_Debug_Writer_Html()); // default can be override
        
        if (!($debug instanceof Hush_Debug)) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("Can not initialize 'Hush_Debug' instance");
        }
        
        if (sizeof($bind) > 0) {
            $label = 'Prepared Sql >>>';
        } else {
            $label = 'Query Sql >>>';
        }
        
        $debug->debug($sql, '<font style="color:red">' . $label . '</font>');
    }
    
    public static function replaceSql ($adapter, $table, array $bind)
    {
        /**
         * Build "col = ?" pairs for the statement,
         * except for Zend_Db_Expr which is treated literally.
         */
        $set = array();
        $i = 0;
        foreach ($bind as $col => $val) {
            $val = '?';
            $set[] = $adapter->quoteIdentifier($col, true) . ' = ' . $val;
        }
        
        // Build the UPDATE statement
        $sql = 'REPLACE INTO '
            . $adapter->quoteIdentifier($table, true)
            . ' SET ' . implode(', ', $set);
        
        return $sql;
    }
    
    public static function insertMultiSql ($adapter, $table, array $cols, array $vals)
    {
        // param exception
        if (!$vals || !$cols) {
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception("For table '{$table}' columns and values can not be empty");
        }
        
        // extract and quote vals names from the array keys
        $cols_num = count($cols);
        $vals_sql = array();
        foreach ($vals as $bind) {
            if (!is_array($bind) || $cols_num != count($bind)) {
                continue;
            }
            foreach ($bind as $k => $v) {
                $bind[$k] = $adapter->quote($v);
            }
            $vals_sql[] = '(' . implode(', ', $bind) . ')';
        }
        
        // build the statement
        $sql = "INSERT INTO "
            . $adapter->quoteIdentifier($table, true)
            . ' (' . implode(', ', $cols) . ') VALUES ' . implode(', ', $vals_sql);
        
        return $sql;
    }
}