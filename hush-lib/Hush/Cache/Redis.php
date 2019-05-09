<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Cache
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: james $
 */

/**
 * @package Hush_Cache
 */
class Hush_Cache_Redis
{
	/**
	 * Redis connections
	 * @var array
	 */
	public static $conn = array();
    
	/**
	 * Get redis connection instance
	 * 
	 * @param string $tag
	 * @throws Exception
	 * @return mixed
	 */
	public static function getConn (array $conf, $tag = '')
	{
		// get redis server
	    list($rhost, $rport, $dbname, $timeout, $authkey) = self::getServer($conf, $tag);

		// get connection id
		$conn_id = $rhost.':'.$rport;
		
		try {
            // connect once
		    if (!self::$conn[$conn_id]) {
		        self::$conn[$conn_id] = new Redis();
		        self::$conn[$conn_id]->pconnect($rhost, $rport, $timeout);
            }
            // check auth
            if ($authkey) {
                self::$conn[$conn_id]->auth($authkey);
            }
            // select db always
            if ($dbname !== null) {
                self::$conn[$conn_id]->select($dbname);
            }
        } catch (Exception $e) {
            throw new Exception('redis error : '.$e->getMessage());
            return false;
        }
		
        // get connection
        if (!isset(self::$conn[$conn_id])) {
            throw new Exception('redis error : bad connection');
            return false;
        }
        
        return self::$conn[$conn_id];
	}
	
	/**
	 * Get cache conn config
	 *
	 * @param array $conf
	 * @return array
	 */
	public static function getServer (array $conf, $tag = '')
	{
	    // check tag
	    if (!$tag) {
	        throw new Exception('cache error : no tag');
	    }
	    
	    // get tag group
	    $tag_arr = (array) explode('_', $tag);
	    $tag_pf = isset($tag_arr[0]) ? $tag_arr[0] : '';
	    $tag_id = isset($tag_arr[1]) ? $tag_arr[1] : '';
	    
	    // check tag info
	    if (!$tag_pf || !$tag_id) {
	        throw new Exception('cache error : bad tag');
	    }
	    
	    // get redis conn
	    $conn = array();
	    if ($conf) {
	        $authkey = $conf['authkey'];
	        $timeout = $conf['timeout'];
	        $cluster = $conf['cluster'];
	        // parse cluster to get conns
	        $conns = self::filterCluster($cluster, $tag);
	        if ($conns) {
	            require_once 'Hush/Util/Flexihash.php';
	            $pool = new Hush_Util_Flexihash();
	            $pool->addTargets($conns);
	            $conn = $pool->lookup($tag);
	        }
	    }
	    
	    // check conn
	    if (!$conn) {
	        throw new Exception('cache error : no conn');
	    }
	    
	    // check conn info
	    $conn = explode(':', $conn);
	    $rhost = isset($conn[0]) ? $conn[0] : null;
	    $rport = isset($conn[1]) ? $conn[1] : null;
	    $dbname = isset($conn[2]) ? $conn[2] : null;
	    if (!$rhost || !$rport) {
	        throw new Exception('cache error : bad conn');
	    }
	    
	    // return value
	    return array($rhost, $rport, $dbname, $timeout, $authkey);
	}
	
	/**
	 * Filter cluster config by tag
	 *
	 * @param array $cluster
	 * @param string $tag
	 * @return array
	 */
	public static function filterCluster ($cluster, $tag = '')
	{
	    $conns = array();
	    // get default
	    if (!$tag) {
	        $conns = (array) $cluster['default'];
	        return $conns;
	    }
	    // search conns
	    foreach (array_keys($cluster) as $rule) {
	        $regexp = str_replace(array('/','*'), array('\/','(.*?)'), $rule);
	        if (preg_match("/$regexp/i", $tag)) {
	            $conns = (array) $cluster[$rule];
	            break;
	        }
	    }
	    // get default
	    if (!$conns) {
	        $conns = (array) $cluster['default'];
	    }
	    return $conns;
	}
}
