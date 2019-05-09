<?php
class Core_Paging
{
	/**
	 * default page number
	 */
	public static $pageNum = 10;
	
	/**
	 * get single instance
	 */
    public static function getInstance ()
	{
// 		static $_page = null;
// 		if (!$_page) {
			require_once 'Hush/Paging.php';
			$_page = new Hush_Paging(0, self::$pageNum, Hush_Util::param('p'), array(
				'Prev' => '上一页',
				'Next' => '下一页',
			));
// 		}
		$_page->each = self::$pageNum;
		$_page->limitNum = self::$pageNum + 1;
		$_page->offsetNum = $_page->frNum;
		return $_page;
	}
	
	/**
	 * simple paging strategy
	 */
	public static function simplePaging (&$page, &$list)
	{
		// checking
		if (!$page) {
			require_once 'Hush/Exception.php';
			throw new Hush_Exception('invalid paging object');
		}
		// paging logic
		$list = (array) $list;
		$listNum = sizeof($list);
		if ($listNum == 0) {
			// no data
			$page->pageStr = 0;
			$page->nextStr = '下页';
		} else if ($listNum < $page->limitNum) {
			// set from to
			$page->frStr = $page->frNum + 1;
			$page->toStr = $page->toNum = $page->frNum + $listNum;
			// only one page
			if ($page->frNum == 0) {
				$page->pageStr = 0;
				$page->nextStr = '下一页';
			// last page
			} else {
				$page->nextStr = '下一页';
				$page->pageStr = -1;
			}
		} else {
			// common page
			$page->pageStr = Hush_Util::param('p') ? Hush_Util::param('p') : 1;
			array_pop($list); // drop last element
		}
	}
	
	/**
	 * page by offset
	 */
	public static function pageByOffset (&$page, &$sql)
	{
		// checking
		if (!$page) {
			require_once 'Hush/Exception.php';
			throw new Hush_Exception('invalid paging object');
		}
		if (!($sql instanceof Zend_Db_Select)) {
			throw new Hush_Exception('invalid sql object');
		}
		if (!is_array($data)) {
			
		}
		// limit logic
		$sql->limit($page->limitNum, $page->offsetNum);
	}
	
	/**
	 * page by primary key
	 */
	public static function pageByPrimaryKey (&$page, &$sql, $data)
	{
		// checking
		if (!$page) {
			require_once 'Hush/Exception.php';
			throw new Hush_Exception('invalid paging object');
		}
		if (!($sql instanceof Zend_Db_Select)) {
			throw new Hush_Exception('invalid sql object');
		}
		if (!is_array($data)) {
			throw new Hush_Exception('invalid page data');
		}
		// limit logic
		$pkey = isset($data['pkey']) ? $data['pkey'] : 'id';
		$pact = isset($data['pact']) ? $data['pact'] : Hush_Util::param('pact');
		$pfrom = isset($data['pfrom']) ? $data['pfrom'] : Hush_Util::param('pfrom');
		if ($pact && $pfrom) {
			switch ($pact) {
				case 1: // up
					$sql->where("{$pkey}>?", $pfrom);
					break;
				case 2: // down
					$sql->where("{$pkey}<?", $pfrom);
					break;
				default:
					break;
			}
			$sql->limit($page->limitNum);
		}
	}
}
