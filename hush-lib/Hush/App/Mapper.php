<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_App
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Hush_App
 */
class Hush_App_Mapper
{
	/**
	 * Mapping files array
	 * @var array
	 */
	private $_map_files = array();
	
	/**
	 * Construct
	 * @param array $map_files
	 */
	public function __construct ($map_files = array())
	{
		if (!is_array($map_files)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Parameter should be an array');
		}
		foreach ((array) $map_files as $map_file) {
			$this->addPageMap($map_file);
		}
	}
	
	/**
	 * Add mapping files
	 * @param string $map_file
	 */
	public function addPageMap ($map_file)
	{
		if ($this->_checkMapFile($map_file)) {
			$this->_map_files[] = $map_file;
		}
	}
	
	/**
	 * Get merged mapping settings
	 * @return array
	 */
	public function getPageMap ()
	{
		$page_map = array();
		if ($this->_map_files) {
			foreach ($this->_map_files as $map_file) {
				$tmp_map = parse_ini_file($map_file, false);
				if ($tmp_map) {
					$page_map = array_merge_recursive($page_map, $tmp_map);
				}
			}
		}
		return $page_map;
	}
	
	/**
	 * Check if mapping file is accessable
	 * @access private
	 * @param string $map_file
	 * @return bool
	 */
	private function _checkMapFile ($map_file) 
	{
		if (!is_file($map_file)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Could not found map file');
		}
		return true;
	}
}