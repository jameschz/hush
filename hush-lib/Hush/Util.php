<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Util
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Hush_Util
 */
class Hush_Util
{
	/**
	 * Md5 encode triple
	 * @static
	 * @param mixed $var
	 * @return void
	 */
	public static function md5 ($str) 
	{
		return md5(md5(md5($str)));
	}
	
	/**
	 * Print data structure in better format
	 * @static
	 * @param mixed $var
	 * @return void
	 */
	public static function dump ($var) 
	{
		echo '<pre>';
		if (is_array($var)) print_r($var); 
		elseif (is_string($var)) echo $var;
		else var_dump($var);
		echo '</pre>';
	}
	
	/**
	 * Run shell and return results into an array
	 * @static
	 * @param string $cmd
	 * @return array
	 */
	public static function shell ($cmd)
	{
		$default_time_limit = ini_get("max_execution_time");
		$results = null;
		set_time_limit(0);
		exec($cmd, $results);
		set_time_limit($default_time_limit);
		return $results;
	}
	
	/**
	 * Trace exception for more readable
	 * @static
	 * @param Exception $e
	 * @return void
	 */
	public static function trace (Exception $e) 
	{
		echo '<pre>';
		echo 'Caught Hush Exception at ' . date('Y-m-d H:i:s') . ' : '.$e->getMessage() . "\n";
		echo $e->getTraceAsString() . "\n";
		echo '</pre>';
	}
	
	/**
	 * Get all http request
	 * @static
	 * @see Hush_Page
	 * @param string $pname request name
	 * @param mixed $value
	 * @return mixed
	 */
	public static function param ($pname, $value = null) 
	{
		// set into $_REQUEST array
		if ($value !== null) $_REQUEST[$pname] = $value;
		// get from $_REQUEST array
		if (array_key_exists($pname, $_REQUEST)) {
			return is_string($_REQUEST[$pname]) ? self::str_strip($_REQUEST[$pname]) : $_REQUEST[$pname];
		}
		return null;
	}
	
	/**
	 * Get all cookies
	 * @static
	 * @see Hush_Page
	 * @param string $cname cookie name
	 * @param mixed $value
	 * @return mixed
	 */
	public static function cookie ($cname, $value = null) 
	{
		// set into $_COOKIE array
		if ($value === '') unset($_COOKIE[$cname]);
		// set into $_COOKIE array
		if ($value !== null) $_COOKIE[$cname] = $value;
		// get from $_COOKIE array
		if (array_key_exists($cname, $_COOKIE)) {
			return is_string($_COOKIE[$cname]) ? self::str_strip($_COOKIE[$cname]) : $_COOKIE[$cname];
		}
		return null;
	}
	
	/**
	 * Get all sessions
	 * @static
	 * @see Hush_Page
	 * @param string $sname session name
	 * @param mixed $value
	 * @return mixed
	 */
	public static function session ($sname, $value = null) 
	{
		// start session first
		if ($_SESSION === null) session_start();
		// set into $_SESSION array
		if ($value === '') unset($_SESSION[$sname]);
		// set into $_SESSION array
		if ($value !== null) $_SESSION[$sname] = $value;
		// get from $_SESSION array
		if ($_SESSION !== null && array_key_exists($sname, $_SESSION)) {
			return is_string($_SESSION[$sname]) ? trim($_SESSION[$sname]) : $_SESSION[$sname];
		}
		return null;
	}
	
	/**
	 * Decode session data
	 * @param string $data session data
	 * @return mixed
	 */
	function session_decode($data) {
		$vars = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
			$data,-1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		for($i = 0; isset($vars[$i]); $i++) {
			$result[$vars[$i++]]=unserialize($vars[$i]);
		}
		return $result;
	}
	
	/**
	 * Client Ip address
	 * @static
	 * @return string
	 */
	public static function clientip () 
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	 * Get server hostname
	 * Usually be used in cli script
	 * @static
	 * @return string
	 */
	public static function hostname ()
	{
		if (isset($_SERVER['HOST'])) return $_SERVER['HOST'];
		if (isset($_SERVER['HOSTNAME'])) return $_SERVER['HOSTNAME'];
		if (isset($_SERVER['SERVER_NAME'])) return $_SERVER['SERVER_NAME'];
		if (isset($_SERVER['SERVER_ADDR'])) return $_SERVER['SERVER_ADDR'];
		return 'localhost';
	}
	
	/**
	 * Redirect page request by javascript
	 * @static
	 * @param string $url
	 * @return void
	 */
	public static function jsRedirect ($url) 
	{
		echo "<script type=\"text/javascript\">location.href='".str_replace("'", "\'", $url)."'</script>";
		exit;
	}
	
	/**
	 * Redirect page request by meta
	 * You could set your timeout seconds
	 * @static
	 * @param string $url
	 * @param int $sec
	 * @return void
	 */
	public static function metaRedirect ($url, $sec = 0) 
	{
		echo "<meta http-equiv=\"refresh\" content=\"{$sec};url={$url}\" />";
		exit;
	}
	
	/**
	 * Redirect page request by header status
	 * Allow setting http status by yourself
	 * @static
	 * @param string $url
	 * @param int $status Http status such as 302, 301, 404, 500 etc.
	 * @return void
	 */
	public static function headerRedirect ($url, $status = 302) 
	{
		if ($status) self::HTTPStatus($status);
		header("Location: {$url}");
		exit;
	}

	/**
	 * HTTP Protocol defined status codes
	 * @static
	 * @param int $num
	 * @return void
	 */
	public static function HTTPStatus ($num) 
	{
	
		static $http = array (
			100 => "HTTP/1.1 100 Continue",
			101 => "HTTP/1.1 101 Switching Protocols",
			200 => "HTTP/1.1 200 OK",
			201 => "HTTP/1.1 201 Created",
			202 => "HTTP/1.1 202 Accepted",
			203 => "HTTP/1.1 203 Non-Authoritative Information",
			204 => "HTTP/1.1 204 No Content",
			205 => "HTTP/1.1 205 Reset Content",
			206 => "HTTP/1.1 206 Partial Content",
			300 => "HTTP/1.1 300 Multiple Choices",
			301 => "HTTP/1.1 301 Moved Permanently",
			302 => "HTTP/1.1 302 Found",
			303 => "HTTP/1.1 303 See Other",
			304 => "HTTP/1.1 304 Not Modified",
			305 => "HTTP/1.1 305 Use Proxy",
			307 => "HTTP/1.1 307 Temporary Redirect",
			400 => "HTTP/1.1 400 Bad Request",
			401 => "HTTP/1.1 401 Unauthorized",
			402 => "HTTP/1.1 402 Payment Required",
			403 => "HTTP/1.1 403 Forbidden",
			404 => "HTTP/1.1 404 Not Found",
			405 => "HTTP/1.1 405 Method Not Allowed",
			406 => "HTTP/1.1 406 Not Acceptable",
			407 => "HTTP/1.1 407 Proxy Authentication Required",
			408 => "HTTP/1.1 408 Request Time-out",
			409 => "HTTP/1.1 409 Conflict",
			410 => "HTTP/1.1 410 Gone",
			411 => "HTTP/1.1 411 Length Required",
			412 => "HTTP/1.1 412 Precondition Failed",
			413 => "HTTP/1.1 413 Request Entity Too Large",
			414 => "HTTP/1.1 414 Request-URI Too Large",
			415 => "HTTP/1.1 415 Unsupported Media Type",
			416 => "HTTP/1.1 416 Requested range not satisfiable",
			417 => "HTTP/1.1 417 Expectation Failed",
			500 => "HTTP/1.1 500 Internal Server Error",
			501 => "HTTP/1.1 501 Not Implemented",
			502 => "HTTP/1.1 502 Bad Gateway",
			503 => "HTTP/1.1 503 Service Unavailable",
			504 => "HTTP/1.1 504 Gateway Time-out"
		);
	
		header($http[$num]);
	}
	
	/**
	 * Upload file from $_FILES variable
	 * @static
	 * @param string $formCol Form file column's name
	 * @param string $targetPath The directory file upload to 
	 * @param string $targetFile Upload file's name, default to be original name
	 * @return bool
	 */
	public static function upload ($formCol, $targetPath, $targetFile = '')
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES[$formCol]['tmp_name'];
			$targetPath = is_dir($targetPath) ? $targetPath : '/tmp';
			$targetFile = strlen($targetFile) ? $targetFile : $_FILES[$formCol]['name'];
			return move_uploaded_file($tempFile, $targetPath . '/' . $targetFile);
		}
		return false;
	}
	
	/**
	 * Flush output buffer
	 * @static
	 */
	public static function flush() 
	{
		ob_flush();
		flush();
	}
	
	/**
	 * Get remote url content by curl client
	 * @static
	 * @param string $file
	 * @param int $timeout
	 * @return string
	 */
	public static function curl ($file = '', $timeout = 0) 
	{
		if (!$file && !strlen($file)) return "";
		$to = isset($timeout) ? $timeout : 5;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, $to);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $file);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	/**
	 * 
	 * Ping whether server's port is open
	 * @param string $host
	 * @param string $port
	 * @return bool
	 */
	public static function ping ($host, $port)
	{
		$ip = (!preg_match('/^\d/i', $host)) ? gethostbyname($host) : $host;
		$fp = @fsockopen($ip, $port, $errno, $errstr, 1);
		if(!$fp) return false;
		@fclose($fp);
		return true;
	}
	
	/**
	 * Get string hash code
	 * Each string has different hash code
	 * @param string $str
	 * @return int
	 */
	public static function str_hash ($str)
	{
		$hc = $si = 0;
		$cs = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
		while ($c = array_shift($cs)) {
			$hc += ord($c) ^ $si;
			$si++;
		}
		return $hc;
	}
	
	/**
	 * Strip all slashed string
	 * @static
	 * @param string $str
	 * @return string
	 */
	public static function str_strip ($str)
	{
		$str = trim($str);
		if (get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return $str;
	}
	
	/**
	 * Get array deepest level
	 * @static
	 * @param array $array
	 * @return int
	 */
	public static function array_depth ($array) 
	{
		$max_depth = 1;
		foreach ((array) $array as $value) {
			if (is_array($value)) {
				$depth = self::array_depth($value) + 1;
				if ($depth > $max_depth) {
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	}
	
	/**
	 * Sort hash array by value's key
	 * @static
	 * @param array $array
	 * @param string $key
	 * @param bool $rev
	 * @return array
	 */
	public static function array_sort ($array, $key = '', $rev = false, $flags = SORT_REGULAR)
	{
		// sort functions
		$asort_func = $rev ? 'arsort' : 'asort';
		$ksort_func = $rev ? 'krsort' : 'ksort';
		
		// sort value
		if (!$key) {
			@$asort_func($array, $flags);
			return $array;
		}
		
		// sort key value
		$sort_array = array();
		foreach ((array) $array as $index => $value) {
			if (isset($value[$key])) $sort_array[$value[$key] . ' ' . $index] = $value;
		}
		
		@$ksort_func($sort_array, $flags);
		
		return $sort_array;
	}
	
	/**
	 * Count array value's sum amount
	 * @static
	 * @param array $array
	 * @param string $key
	 * @return int
	 */
	public static function array_sum ($array, $key = '') 
	{
		// count value sum
		if (!$key) return array_sum($array);
		// count key value sum
		$sum_array = array();
		foreach ((array) $array as $value) {
			if (isset($value[$key])) $sum_array[] = intval($value[$key]);
		}
		return array_sum($sum_array);
	}
	
	/**
	 * Insert array item into specific position
	 * @static
	 * @param array $array
	 * @param int $pos
	 * @param mixed $val
	 * @param int $times insert times
	 * @return array
	 */
	public static function array_insert ($array, $pos, $val, $times = 1)
	{
		$array2 = array_splice($array, $pos);
		for ($i = 0; $i < $times; $i++) $array[] = $val;
		$array = array_merge($array, $array2);
		return $array;
	}
	
	/**
	 * Remove some item from the array
	 * @static
	 * @param array $array
	 * @param string $key
	 * @return array
	 */
	public static function array_remove ($array, $key)
	{
		$array2 = array();
		foreach ((array) $array as $k => $v) {
			if ($k == $key || $v == $key) continue;
			$array2[$k] = $v;
		}
		return $array2;
	}
	
	/**
	 * Chop one section from an array
	 * @static
	 * @param array $array
	 * @param int $start
	 * @param int $end
	 * @return array
	 */
	public static function array_chop ($array, $start, $end) 
	{
		if (is_array($array)) {
			return array_slice($array, $start, $end - $start);
		}
		return false;
	}
	
	/**
	 * Turnover a two-dimensional array
	 * @static
	 * @param array $array
	 * @return array
	 */
	public static function array_turn ($arr)
	{
		$res = array();
		for ($i = 0; $i < count($arr); $i++) {
			for ($j = 0; $j < count($arr[$i]); $j++) {
				$res[$j][$i] = $arr[$i][$j];
			}
		}
		return $res;
	}
	
	/**
	 * Remove dir
	 * @static
	 * @param string $str
	 */
	public static function dir_remove ($dir)
	{
		if (is_file($dir)) {
			return @unlink($dir);
		}
		if (is_dir($dir)) {
			$scan = glob(rtrim($dir, '/') . '/*');
			foreach($scan as $id => $path){
				self::dir_remove($path);
			}
			return @rmdir($dir);
		}
	}
	
	/**
	 * Clean all under dir
	 * @static
	 * @param string $str
	 */
	public static function dir_clean ($dir)
	{
		if (is_dir($dir)) {
			$scan = glob(rtrim($dir, '/') . '/*');
			foreach($scan as $id => $path){
				self::dir_remove($path);
			}
		}
	}

	/**
	 * Recursive copy
	 * @static
	 * @param string $src
	 * @param string $dst
	 * @param array $escape_dir
	 * @param mixed $callback_func
	 * @return void
	 */
	public static function dir_copy ($src, $dst, $escape_dir = array(), $callback_func = null)
	{
		// default callback
		$default_callback_func = 'dir_copy_wrapper';
		if (!$callback_func && function_exists($default_callback_func)) {
			$callback_func = $default_callback_func;
		}
		// remove first
		if (file_exists($dst)) {
			self::dir_remove($dst);
		}
		// copy dir
		if (is_dir($src)) {
			@mkdir($dst, 0777, 1);
			$files = scandir($src);
			foreach ($files as $file) {
				if ($escape_dir && in_array($file, $escape_dir)) {
					continue;
				}
				if ($file != "." && $file != "..") {
					// call copy recursively
					self::dir_copy("$src/$file", "$dst/$file", $escape_dir, $callback_func);
				}
			}
		// copy file
		} else if (file_exists($src)) {
			if ($escape_dir && in_array($src, $escape_dir)) {
				return;
			}
			// do copy file
			copy($src, $dst);
			// copy callback function
			if ($callback_func != null) {
				call_user_func_array($callback_func, array($src, $dst));
			}
		}
	}
	
	/**
	 * Check if item is json string
	 * @static
	 * @param string|object $str
	 * @return bool
	 */
	public static function is_json ($str) 
	{
		return (is_string($str) && is_object(json_decode($str))) ? true : false;
	}
	
	/**
	 * Read data from csv file
	 * @static
	 * @param string $csvText Csv file's text
	 * @param string $csvSP Csv each column's separator, default is '\t'
	 * @return array
	 */
	public static function parse_csv ($csvText, $csvSP = "\t")
	{
		$results = array();
		$lines = explode("\n", $csvText);
		foreach ((array) $lines as $line) {
			$line = trim($line);
			if (!strlen($line)) continue;
			$results[] = explode($csvSP, trim($line));
		}
		return $results;
	}
	
	/**
	 * Get UUID with PHP
	 * @static
	 * @return string
	 */
	public static function uuid ()
	{
		$chars = md5(uniqid(mt_rand(), true));
		$uuid = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return strtoupper($uuid);
	}

    /**
     * get millisecond
     * @return float
     */
    public static function getMillisecond(){
        list($s,$time) = explode(' ', microtime());

        $ms = (float)sprintf('%.0f', (($time + $s) * 1000));
        return $ms;
    }
}