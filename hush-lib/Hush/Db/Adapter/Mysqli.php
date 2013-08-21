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
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @see Zend_Db_Adaptor_Mysqli
 */
require_once 'Zend/Db/Adapter/Mysqli.php';

/**
 * @package Hush_Db
 */
class Hush_Db_Adapter_Mysqli extends Zend_Db_Adapter_Mysqli
{
	/**
	 * @var string
	 */
	public $_sql = '';
	
	/**
	 * For debug mode
	 * @var bool
	 */
	public $_debug = true; // for trace sql with 'Hush_Debug' lib
	
	/**
	 * Replace table rows with specified data based on a WHERE clause.
	 *
	 * @param  mixed $table The table to update.
	 * @param  array $bind  Column-value pairs.
	 * @param  mixed $where UPDATE WHERE clause(s).
	 * @return int   The number of affected rows.
	 */
	public function replace($table, array $bind, $debug = false)
	{
		/**
		 * Build "col = ?" pairs for the statement,
		 * except for Zend_Db_Expr which is treated literally.
		 */
		$set = array();
		$i = 0;
		foreach ($bind as $col => $val) {
			$val = '?';
			$set[] = $this->quoteIdentifier($col, true) . ' = ' . $val;
		}
		
		// Build the UPDATE statement
		$sql = 'REPLACE INTO '
			 . $this->quoteIdentifier($table, true)
			 . ' SET ' . implode(', ', $set);
		
		if ($debug) return $sql;
		
		// Execute the statement and return the number of affected rows
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
	public function insertMultiRow ($table, $cols, $vals, $debug = false)
	{
		// param exception
		if (!$vals || !$cols) {
			require_once 'Zend/Db/Adapter/Exception.php';
			throw new Zend_Db_Adapter_Exception("For table '{$table}' columns and values can not be empty");
		}
		
		// extract and quote vals names from the array keys
		$cols_num = count($cols);
		$vals_sql = array();
		foreach ($vals as $bind) {
			if (!is_array($bind) || $cols_num != count($bind)) {
				continue;
			}
			foreach ($bind as $k => $v) {
				$bind[$k] = $this->quote($v);
			}
			$vals_sql[] = '(' . implode(', ', $bind) . ')';
		}
		
		// build the statement
		$sql = "INSERT INTO "
			 . $this->quoteIdentifier($table, true)
			 . ' (' . implode(', ', $cols) . ') VALUES ' . implode(', ', $vals_sql);
		
		if ($debug) return $sql;
		
		// execute the statement and return the number of affected rows
		$stmt = $this->query($sql);
		
		return $stmt->rowCount();
	}
	
	/**
	 * Get all columns' name of specific table
	 * @param string $table
	 * @return array
	 */
	public function getAllColumnName ($table)
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
	 * So we can debug the sql message
	 * @see Hush_Debug
	 * @param string $sql
	 * @param array $bind
	 * @return Zend_Db_Statement_Interface
	 */
	public function query($sql, $bind = array()) 
	{
		if ($this->_debug) {
			
			require_once 'Hush/Debug.php';
			$debug = Hush_Debug::getInstance();
			
			if (!($debug instanceof Hush_Debug)) {
				require_once 'Zend/Db/Adapter/Exception.php';
				throw new Zend_Db_Adapter_Exception("Can not initialize 'Hush_Debug' instance");
			}
			
			if ($sql instanceof Zend_Db_Select) {
				$sql = $sql->__toString();
			}
			
			if (sizeof($bind) > 0) {
				$label = 'Prepared Sql >>>';
			} else {
				$label = 'Query Sql >>>';
			}
			
			$debug->debug($sql, '<font style="color:red">' . $label . '</font>');
		}
		
		return parent::query($sql, $bind);
	}
}