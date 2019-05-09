<?php
/**
 * App Cli
 *
 * @category   App
 * @package    App_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

////////////////////////////////////////////////////////////////////////////////////////////////////
// Constants definition

define('__MYSQL_IMPORT_TOOL', 'mysql');
define('__MYSQL_DUMPER_TOOL', 'mysqldump');
define('__MYSQL_IMPORT_COMMAND', __MYSQL_IMPORT_TOOL . ' {PARAMS} < {SQLFILE} --default-character-set=utf8');
define('__MYSQL_DUMPER_COMMAND', __MYSQL_DUMPER_TOOL . ' {PARAMS} --add-drop-database > {SQLFILE}');
define('__MYSQL_EXECUTE_QUOTE', __OS_WIN ? '"' : "'");
define('__MYSQL_EXECUTE_COMMAND', __MYSQL_IMPORT_TOOL . ' {PARAMS} -e '.__MYSQL_EXECUTE_QUOTE.'{SQLEXEC}'.__MYSQL_EXECUTE_QUOTE);

/**
 * @package App_Cli
 */
class App_Cli_Db extends App_Cli
{
    private $db_sql_files;
    private $table_sql_files;
    private $shard_sql_files;
    
	public function __init ()
	{
		parent::__init();
		$this->_printHeader();
		$this->db_config = new MysqlConfig();
	}
	
	public function helpAction ()
	{
		// command description
		echo "hush db init [create|drop] {db_name}\n";
		echo "hush db table [create|drop] {db_name}\n";
		echo "hush db shard [create|drop] {db_name}\n";
		echo "hush db update {version_name}\n";
	}
	
	private function _load_env ($act = '')
	{
	    $this->_env = __HUSH_ENV ? __HUSH_ENV : 'local';
	    if (!in_array($this->_env, array('local','dev','test','beta','rc','release'))) {
	        echo "Bad env value, please fill [local|dev|test|beta|rc|release]\n";
	        exit;
	    }
	    if ($act == 'drop' && in_array($this->_env, array('rc','release'))) {
	        echo "Can not run drop command in release !!!\n";
	        exit;
	    }
	    switch ($this->_env) {
	        case 'rc':
	        case 'local':
	        case 'release':
                $this->_db_suffix = '';
                break;
	        default:
	            $this->_db_suffix = '_' . $this->_env;
	            break;
	    }
	}
	
	private function _load_db_sqls ($act = '', $db_name = '')
	{
	    $db_sql_dir = __SQL_DIR.'/db';
	    if (is_dir($db_sql_dir)) {
	        $db_name = $db_name ? $db_name : '*';
	        $this->db_sql_files = (array) glob($db_sql_dir."/{$act}_{$db_name}.sql");
	    }
	    if (!$this->db_sql_files) {
	        echo "Can not find sql for {$act} {$db_name}\n";
	        exit;
	    }
	}
	
	private function _load_table_sqls ($act = '', $db_name = '')
	{
	    $table_sql_dir = __SQL_DIR.'/table';
	    if (is_dir($table_sql_dir) && $act) {
	        $db_name = $db_name ? $db_name : '*';
	        $this->table_sql_files = (array) glob($table_sql_dir."/{$act}_{$db_name}.sql");
	    }
	    if (!$this->table_sql_files) {
	        echo "Can not find sql for {$act} {$db_name}\n";
	        exit;
	    }
	}
	
	private function _load_shard_sqls ($act = '', $db_name = '')
	{
	    $shard_sql_dir = __SQL_DIR.'/shard';
	    if (is_dir($shard_sql_dir) && $act) {
	        $db_name = $db_name ? $db_name : '*';
	        $this->shard_sql_files = (array) glob($shard_sql_dir."/{$act}_{$db_name}.sql");
	    }
	    if (!$this->shard_sql_files) {
	        echo "Can not find sql for {$act} {$db_name}\n";
	        exit;
	    }
	}
	
	private function _load_update_sqls ($act = 'update', $ver_name = '')
	{
	    $table_sql_dir = __SQL_DIR.'/update';
	    if (is_dir($table_sql_dir) && $act) {
	        $ver_name = $ver_name ? $ver_name . '*' : '*';
	        $this->update_sql_files = (array) glob($table_sql_dir."/{$act}_{$ver_name}.sql");
	    }
	    if (!$this->update_sql_files) {
	        echo "Can not find sql for {$act} {$ver_name}\n";
	        exit;
	    }
	}
	
	private function _get_db_name_from_sql_db ($sql_file)
	{
	    $db_sql_str = file_get_contents($sql_file);
	    preg_match('/`([^`]+?)`/i', $db_sql_str, $db_name_arr);
	    if (!isset($db_name_arr[1])) {
	        throw new Exception('Can not found init db names');
	        return false;
	    }
	    return $db_name_arr[1]; // string
	}
	
	private function _get_db_name_from_sql_table ($sql_file)
	{
	    $db_name_str = str_replace(array('create_','drop_','update_'), '', basename($sql_file, ".sql"));
	    $db_name_str = $db_name_str . $this->_db_suffix;
	    if (!$db_name_str) {
	        throw new Exception('Can not found table db names');
	        return false;
	    }
	    return $db_name_str; // string
	}
	
	private function _get_db_config_masters ($db_name_str)
	{
	    if ($this->db_config) {
	        return $this->db_config->getClusterMasters($db_name_str);
	    }
	    return array();
	}
	
	private function _get_sql_from_db ($db_sql_file, $db_name_str)
	{
	    $db_sql_str = trim(file_get_contents($db_sql_file));
	    $db_name_str_new = $db_name_str . $this->_db_suffix;
	    return str_replace($db_name_str, $db_name_str_new, $db_sql_str);
	}
	
	private function _get_sql_from_update ($db_sql_file)
	{
	    $update_sql_arr = array();
	    $db_sql_str_all = trim(file_get_contents($db_sql_file));
	    $db_sql_str_arr = array_filter(explode(";", $db_sql_str_all));
	    if ($db_sql_str_arr) {
    	    foreach ($db_sql_str_arr as $k => $v) {
    	        $s = trim($v);
    	        // escape comment
    	        $s_t = str_replace(array("\r","\n"), "$$", $s);
    	        $s_r = explode("$$", $s_t);
    	        $s = ''; // clean
    	        foreach ($s_r as $s_1) {
    	            $s .= preg_replace('/#(.*)$/', '', $s_1);
    	        }
    	        // get db name
    	        $r = preg_split('/use/i', $s);
    	        if (isset($r[1]) && !empty($r[1])) {
    	            $db_name_str = trim(str_replace('`', '', $r[1]));
    	            $db_name_str = $db_name_str . $this->_db_suffix;
    	            $db_use_str = "use {$db_name_str}";
    	            if (!$db_name_str || !$db_use_str) {
    	                throw new Exception('Can not parse update db name');
    	                return false;
    	            }
    	        }
    	        // get db sqls
    	        else {
    	            if (!$db_name_str || !$db_use_str) {
    	                throw new Exception('Can not found update db name');
    	                return false;
    	            }
    	            // only for linux
    	            if (!__OS_WIN) $s = str_replace("'", '"', $s);
    	            $update_sql_arr[$db_name_str][] = "{$db_use_str}; {$s};";
    	        }
    	    }
	    }
	    return $update_sql_arr;
	}
	
	public function initAction ()
	{
	    // get db info
	    $args = func_get_args();
	    $action = isset($args[0]) ? $args[0] : null;
	    $dbname = isset($args[1]) ? $args[1] : null;
// 	    if (!$dbname) return $this->helpAction();
	    
	    // load env config
	    $this->_load_env($action);
	    
	    // load db sql files
	    $this->_load_db_sqls($action, $dbname);
	    
	    // execute db files
	    foreach ($this->db_sql_files as $db_sql_file) {
	        $db_name_str = $this->_get_db_name_from_sql_db($db_sql_file);
	        $db_masters = $this->_get_db_config_masters($db_name_str);
	        // execute db for every master
	        foreach ($db_masters as $db_name_str => $db_configs) {
	            foreach ($db_configs as $c) {
	                // execute sql from string
    	            $db_sql_str = $this->_get_sql_from_db($db_sql_file, $db_name_str);
            	    $sql_cmd = str_replace(
            	        array('{PARAMS}', '{SQLEXEC}'),
            	        array($this->_getCmdParams('mysql',
            	            $c['host'],
            	            $c['port'],
            	            $c['user'],
            	            $c['pass']), $db_sql_str),
            	        __MYSQL_EXECUTE_COMMAND);
            	    echo "Execute Command : $sql_cmd\n";
            	    system($sql_cmd, $sql_res);
            	    if (!$sql_res) {
            	        echo "Init db ok.\n\n";
            	    } else {
            	        echo "Init db failed.\n\n";
            	    }
	            }
            }
	    }
	}
	
	public function tableAction ()
	{
	    // get db info
	    $args = func_get_args();
	    $action = isset($args[0]) ? $args[0] : null;
	    $dbname = isset($args[1]) ? $args[1] : null;
// 	    if (!$dbname) return $this->helpAction();
	    
	    // load env config
	    $this->_load_env($action);
	    
	    // load table sql files
	    $this->_load_table_sqls($action, $dbname);
	    
	    // execute db files
	    foreach ($this->table_sql_files as $table_sql_file) {
	        $db_name_str = $this->_get_db_name_from_sql_table($table_sql_file);
	        $db_masters = $this->_get_db_config_masters($db_name_str);
	        // execute db for every master
	        foreach ($db_masters as $db_name_str => $db_configs) {
	            foreach ($db_configs as $c) {
	                // execute sql from file
        	        $sql_cmd = str_replace(
        	            array('{PARAMS}', '{SQLFILE}'),
        	            array($this->_getCmdParams('mysql',
        	                $c['host'],
        	                $c['port'],
        	                $c['user'],
        	                $c['pass'],
        	                $db_name_str), $table_sql_file),
        	            __MYSQL_IMPORT_COMMAND);
        	        echo "Execute Command : $sql_cmd\n";
        	        system($sql_cmd, $sql_res);
        	        if (!$sql_res) {
        	            echo "Init db tables ok.\n\n";
        	        } else {
        	            echo "Init db tables failed.\n\n";
        	        }
	            }
	        }
	    }
	}
	
	public function shardAction ()
	{
	    // get db info
	    $args = func_get_args();
	    $action = isset($args[0]) ? $args[0] : null;
	    $dbname = isset($args[1]) ? $args[1] : null;
// 	    if (!$dbname) return $this->helpAction();

	    // load env config
	    $this->_load_env($action);
	    
	    // load table sql files
	    $this->_load_shard_sqls($action, $dbname);
	    
	    // execute db files
	    foreach ($this->shard_sql_files as $shard_sql_file) {
	        $db_name_str = $this->_get_db_name_from_sql_table($shard_sql_file);
	        $db_masters = $this->_get_db_config_masters($db_name_str);
	        // execute sql for every master
	        foreach ($db_masters as $db_name_str => $db_configs) {
	            foreach ($db_configs as $c) {
	                // execute sql from file
        	        $sql_cmd = str_replace(
        	            array('{PARAMS}', '{SQLFILE}'),
        	            array($this->_getCmdParams('mysql',
        	                $c['host'],
        	                $c['port'],
        	                $c['user'],
        	                $c['pass'],
        	                $db_name_str), $shard_sql_file),
        	            __MYSQL_IMPORT_COMMAND);
        	        echo "Execute Command : $sql_cmd\n";
        	        system($sql_cmd, $sql_res);
        	        if (!$sql_res) {
        	            echo "Init db shard ok.\n\n";
        	        } else {
        	            echo "Init db shard failed.\n\n";
        	        }
	            }
	        }
	    }
	}
	
	public function updateAction ()
	{
	    // get db info
	    $args = func_get_args();
	    $action = 'update'; // default
	    $vername = isset($args[0]) ? $args[0] : null;

	    // confirm update all ?
	    if (!$vername) {
    	    echo
    	    <<<NOTICE

Do you want to update all sqls ?

Please confirm yes or no [Y/N] :
NOTICE;
	    
    	    // check init task
    	    $scope = trim(fgets(fopen("php://stdin", "r")));
    	    if (strtoupper($scope) != 'Y') {
    	        exit("\nOperation Cancelled.\n");
    	    }
	    }
	    
	    // load env config
	    $this->_load_env($action);
	    
	    // load table sql files
	    $this->_load_update_sqls($action, $vername);
	    
	    // execute db files
	    foreach ($this->update_sql_files as $update_sql_file) {
	        $update_sql_arr = $this->_get_sql_from_update($update_sql_file);
	        echo "UPDATE {$update_sql_file} >>>\n\n";
	        foreach ($update_sql_arr as $db_name_str => $db_sql_str_arr) {
	            $db_masters = $this->_get_db_config_masters($db_name_str);
    	        // execute sql for every master
    	        foreach ($db_masters as $db_name_str => $db_configs) {
    	            foreach ($db_configs as $c) {
    	                // execute sql from string array
    	                foreach ($db_sql_str_arr as $db_sql_str) {
        	                $sql_cmd = str_replace(
        	                    array('{PARAMS}', '{SQLEXEC}'),
        	                    array($this->_getCmdParams('mysql',
        	                        $c['host'],
        	                        $c['port'],
        	                        $c['user'],
        	                        $c['pass']), $db_sql_str),
        	                    __MYSQL_EXECUTE_COMMAND);
        	                echo "Execute Command : $db_sql_str\n";
        	                system($sql_cmd, $sql_res);
        	                if (!$sql_res) {
        	                    echo "Update sql ok.\n\n";
        	                } else {
        	                    echo "Update sql failed.\n\n";
        	                }
    	                }
    	            }
    	        }
            }
	    }
	}
}
