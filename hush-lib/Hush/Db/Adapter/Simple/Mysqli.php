<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package	Hush_Db
 * @author	 James.Huang <shagoo@gmail.com>
 * @license	http://www.apache.org/licenses/LICENSE-2.0
 * @version	$Id: james $
 */

/**
 * @see Hush_Db_Adapter_Exception
 */
require_once 'Hush/Db/Adapter/Exception.php';

/**
 * @see Hush_Db_Adapter_Simple_Abstract
 */
require_once 'Hush/Db/Adapter/Simple/Abstract.php';

/**
 * @package Hush_Db
 */
class Hush_Db_Adapter_Simple_Mysqli extends Hush_Db_Adapter_Simple_Abstract
{
	/**
	 * @var resource|object The driver level statement object/resource
	 */
	protected $_stmt = null;

	/**
	 * @var array
	 */
	protected $_meta = null;

	/**
	 * Fetched result values.
	 *
	 * @var array
	 */
	protected $_keys;

	/**
	 * Column names.
	 *
	 * @var array
	 */
	protected $_values;

	/**
	 * Query parameter bindings; covers bindParam() and bindValue().
	 *
	 * @var array
	 */
	protected $_bindParam = array();

	/**
	 * Quote a raw string.
	 *
	 * @param mixed $value Raw string
	 *
	 * @return string Quoted string
	 */
	protected function _quote($value)
	{
		if (is_int($value) || is_float($value)) {
			return $value;
		}
		$this->_connect();
		return "'" . $this->_connection->real_escape_string($value) . "'";
	}

	/**
	 * Returns the symbol the adapter uses for delimiting identifiers.
	 *
	 * @return string
	 */
	public function getQuoteIdentifierSymbol()
	{
		return "`";
	}

	/**
	 * Creates a connection to the database.
	 *
	 * @return void
	 */
	protected function _connect()
	{
		if ($this->_connection) {
			return;
		}
		
		if (!extension_loaded('mysqli')) {
			throw new Hush_Db_Adapter_Exception('The Mysqli extension is required for this adapter but the extension is not loaded');
		}
		
		if (isset($this->_config['port'])) {
			$port = (integer) $this->_config['port'];
		} else {
			$port = null;
		}
		
		$this->_connection = mysqli_init();
		
		if(!empty($this->_config['driver_options'])) {
			foreach($this->_config['driver_options'] as $option=>$value) {
				if(is_string($option)) {
					// Suppress warnings here
					// Ignore it if it's not a valid constant
					$option = @constant(strtoupper($option));
					if($option === null) continue;
				}
				mysqli_options($this->_connection, $option, $value);
			}
		}
		
		// Suppress connection warnings here.
		// Throw an exception instead.
		$_isConnected = @mysqli_real_connect(
			$this->_connection,
			$this->_config['host'],
			$this->_config['username'],
			$this->_config['password'],
			$this->_config['dbname'],
			$port
		);
		
		if ($_isConnected === false || mysqli_connect_errno()) {
			$this->closeConnection();
			throw new Hush_Db_Adapter_Exception(mysqli_connect_error());
		}
		
		if (!empty($this->_config['charset'])) {
			mysqli_set_charset($this->_connection, $this->_config['charset']);
		}
	}

	/**
	 * Test if a connection is active
	 *
	 * @return boolean
	 */
	public function isConnected()
	{
		return ((bool) ($this->_connection instanceof mysqli));
	}

	/**
	 * Force the connection to close.
	 *
	 * @return void
	 */
	public function closeConnection()
	{
		if ($this->isConnected()) {
			$this->_connection->close();
		}
		$this->_connection = null;
	}

	/**
	 * Prepare a statement and return a PDOStatement-like object.
	 *
	 * @param string $sql SQL query
	 * @return PDOStatement
	 */
	public function prepare($sql)
	{
		$this->_connect();
		
		// clean stmt first
		if ($this->_stmt) {
			$this->_stmt->close();
			$this->_stmt = null;
		}
		
		// prepare the statement
		$mysqli = $this->getConnection();
		$this->_stmt = $mysqli->prepare($sql);
		
		if ($this->_stmt === false || $mysqli->errno) {
			throw new Hush_Db_Adapter_Exception("Mysqli prepare error: " . $this->_stmt->error, $this->_stmt->errno . " ; ");
		}
		
		return $this->_stmt;
	}

	/**
	 * Executes a prepared statement.
	 *
	 * @param array $params OPTIONAL Values to bind to parameter placeholders.
	 * @return bool
	 */
	public function execute(array $params = null)
	{
		if (!$this->_stmt) {
			return false;
		}
		
		// then default to the _bindParam array
		if ($params === null) {
			$params = $this->_bindParam;
		}
		
		// send $params as input parameters to the statement
		if ($params) {
			array_unshift($params, str_repeat('s', count($params)));
			$stmtParams = array();
			foreach ($params as $k => &$value) {
				$stmtParams[$k] = &$value;
			}
			call_user_func_array(
				array($this->_stmt, 'bind_param'),
				$stmtParams
			);
		}
		
		// execute the statement
		$retval = $this->_stmt->execute();
		if ($retval === false) {
			throw new Hush_Db_Adapter_Exception("Mysqli statement execute error : " . $this->_stmt->error, $this->_stmt->errno);
		}
		
		// retain metadata
//		if ($this->_meta === null) {
			$this->_meta = $this->_stmt->result_metadata();
			if ($this->_stmt->errno) {
				throw new Hush_Db_Adapter_Exception("Mysqli statement metadata error: " . $this->_stmt->error, $this->_stmt->errno);
			}
//		}
		
		// statements that have no result set do not return metadata
		if ($this->_meta !== false) {
			// get the column names that will result
			$this->_keys = array();
			foreach ($this->_meta->fetch_fields() as $col) {
				$this->_keys[] = (string) $col->name;
			}
			// set up a binding space for result variables
			$this->_values = array_fill(0, count($this->_keys), null);
			// set up references to the result binding space.
			// just passing $this->_values in the call_user_func_array()
			// below won't work, you need references.
			$refs = array();
			foreach ($this->_values as $i => &$f) {
				$refs[$i] = &$f;
			}
			$this->_stmt->store_result();
			// bind to the result variables
			call_user_func_array(
				array($this->_stmt, 'bind_result'),
				$this->_values
			);
		}
		
		return $retval;
	}

	/**
	 * Fetches a row from the result set.
	 *
	 * @param int $style  OPTIONAL Fetch mode for this fetch operation.
	 * @param int $cursor OPTIONAL Absolute, relative, or other.
	 * @param int $offset OPTIONAL Number for absolute or relative cursors.
	 * @return mixed Array, object, or scalar depending on fetch mode.
	 * @throws Zend_Db_Statement_Mysqli_Exception
	 */
	protected function _fetch($style = null, $cursor = null, $offset = null)
	{
		if (!$this->_stmt) {
			return false;
		}
		
		// fetch the next result
		$retval = $this->_stmt->fetch();
		switch ($retval) {
			case null: // end of data
			case false: // error occurred
				$this->_stmt->reset();
				return false;
			default:
				// fallthrough
		}

		// make sure we have a fetch mode
		if ($style === null) {
			$style = $this->_fetchMode;
		}
		
		// dereference the result values, otherwise things like fetchAll()
		// return the same values for every entry (because of the reference).
		$values = array();
		foreach ($this->_values as $key => $val) {
			$values[] = $val;
		}
		$row = false;
		switch ($style) {
			case Hush_Db::FETCH_NUM:
				$row = $values;
				break;
			case Hush_Db::FETCH_ASSOC:
				$row = array_combine($this->_keys, $values);
				break;
			case Hush_Db::FETCH_BOTH:
				$assoc = array_combine($this->_keys, $values);
				$row = array_merge($values, $assoc);
				break;
			case Hush_Db::FETCH_OBJ:
				$row = (object) array_combine($this->_keys, $values);
				break;
			default:
				throw new Hush_Db_Adapter_Exception("Invalid fetch mode '$style' specified");
				break;
		}
		return $row;
	}

	/**
	 * Returns an array containing all of the result set rows.
	 *
	 * @param int $style OPTIONAL Fetch mode.
	 * @param int $col   OPTIONAL Column number, if fetch mode is by column.
	 * @return array Collection of rows, each in a format by the fetch mode.
	 */
	protected function _fetchAll($style = null, $col = null)
	{
		$data = array();
		if ($style === Hush_Db::FETCH_COLUMN && $col === null) {
			$col = 0;
		}
		if ($col === null) {
			while ($row = $this->_fetch($style)) {
				$data[] = $row;
			}
		} else {
			while (false !== ($val = $this->_fetchColumn($col))) {
				$data[] = $val;
			}
		}
		return $data;
	}

	/**
	 * Returns a single column from the next row of a result set.
	 *
	 * @param int $col OPTIONAL Position of the column to fetch.
	 * @return string One value from the next row of result set, or false.
	 */
	protected function _fetchColumn($col = 0)
	{
		$data = array();
		$col = (int) $col;
		$row = $this->_fetch(Hush_Db::FETCH_NUM);
		if (!is_array($row)) {
			return false;
		}
		return $row[$col];
	}

	/**
	 * Returns the number of rows affected by the execution of the
	 * last INSERT, DELETE, or UPDATE statement executed by this
	 * statement object.
	 *
	 * @return int	 The number of rows affected.
	 */
	protected function _rowCount()
	{
		$mysqli = $this->getConnection();
		return $mysqli->affected_rows;
	}

	/**
	 * Gets the last ID generated automatically by an IDENTITY/AUTOINCREMENT column.
	 *
	 * As a convention, on RDBMS brands that support sequences
	 * (e.g. Oracle, PostgreSQL, DB2), this method forms the name of a sequence
	 * from the arguments and returns the last id generated by that sequence.
	 * On RDBMS brands that support IDENTITY/AUTOINCREMENT columns, this method
	 * returns the last value generated for such a column, and the table name
	 * argument is disregarded.
	 *
	 * @param string $tableName   OPTIONAL Name of table.
	 * @param string $primaryKey  OPTIONAL Name of primary key column.
	 * @return string
	 */
	public function lastInsertId($tableName = null, $primaryKey = null)
	{
		$mysqli = $this->getConnection();
		return (string) $mysqli->insert_id;
	}

	/**
	 * Begin a transaction.
	 */
	public function beginTransaction()
	{
		$this->_connect();
		$this->_connection->autocommit(false);
	}

	/**
	 * Commit a transaction.
	 */
	public function commit()
	{
		$this->_connect();
		$this->_connection->commit();
		$this->_connection->autocommit(true);
	}

	/**
	 * Roll-back a transaction.
	 */
	public function rollBack()
	{
		$this->_connect();
		$this->_connection->rollback();
		$this->_connection->autocommit(true);
	}

	/**
	 * Set the fetch mode.
	 *
	 * @param integer $mode
	 * @return void
	 */
	public function setFetchMode($mode)
	{
		switch ($mode) {
			case Hush_Db::FETCH_LAZY:
			case Hush_Db::FETCH_ASSOC:
			case Hush_Db::FETCH_NUM:
			case Hush_Db::FETCH_BOTH:
			case Hush_Db::FETCH_NAMED:
			case Hush_Db::FETCH_OBJ:
				$this->_fetchMode = $mode;
				break;
			default:
				throw new Hush_Db_Adapter_Exception("Invalid fetch mode '$mode' specified");
		}
	}
}