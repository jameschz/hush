<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Paging
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Exception
 */
require_once 'Hush/Exception.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @package Hush_Paging
 */
class Hush_Paging
{
	public $item;
	public $page;
	public $total;
	public $totalNum;
	public $totalPage;
	public $frNum;
	public $toNum;
	public $frStr;
	public $toStr;
	public $pageStr;
	public $pageUrl;
	public $prev;
	public $next;
	
	public $pagArr		= array();
	public $pagStr		= 'p';		// page id param in url
	public $dsPageOne	= false;	// if display first page
	public $firstPage	= false;	// from which first page
	
	public $pattern		= array(	// default paging options
		'Prev'	=> '<',
		'Next'	=> '>',
	);
	
	/**
	 * Constructor ; initialize the Paging class
	 *
	 * @param int $items		All Items should be paged
	 * @param int $each			Items every page
	 * @param int $page			Current Page
	 * @param mix $pattern		Display pattern (include paging/prev/next field)
	 * 
	 * Pattern Intro >
	 * Href		: paging href link style. eg : /xxx.php?pag={page}
	 * Prev		: prev button word.
	 * Next		: next button word.
	 * Mode		: 1 is google style paging ; 2 is my style paging
	 * ModeArg	: is a array() ; args used for different Mode (used when Mode is 2)
	 * PageTag	: now page style includes. eg : array('<span>','</span>')
	 * Spacing	: spacing between each page string
	 * TrimPag	: whether show prev or next string when they don't need to be shown
	 * Suffix	: followed 
	 */
	public function __construct($items, $each, $page, $pattern = null) 
	{
		// initialize the attributes
		$this->__initialize($items, $each, $page, $pattern);
		
		// do paging
		$this->__paging();
	}

	/**
	 * initialize the Paging
	 *
	 * @param int $items		All Items should be paged
	 * @param int $each			Items every page
	 * @param int $page			Current Page
	 * @param array $pattern	Display pattern (include paging/prev/next field)
	 * @return void
	 */
	protected function __initialize ($items = null, $each = null, $page = null, $pattern = null) 
	{
		
		// prepare pattern
		$this->pattern = ($pattern) ? $pattern : $this->pattern;
		$this->pageUrl = $this->defaultPageUrl(); // default page url
		if (array_key_exists('Href', $this->pattern)) {
			$this->pageUrl = $this->pattern['Href'];
		}
		
		// prepare common vars
		$this->items		= $items;
		$this->each			= is_null($each) ? 5 : $each;
		$this->page			= $this->pageInUrl($page); // get current page no
		$this->totalNum		= is_array($items) ? count($items) : $items;
		$this->totalPage	= 0;
		$this->frNum		= 0;
		$this->toNum		= 0;
		$this->frStr		= 0;
		$this->toStr		= 0;
		$this->pageStr		= '';
		$this->prevStr		= 'Prev';
		$this->nextStr		= 'Next';
	}
	
	/**
	 * Convert Paging class attributes to a array.
	 *
	 * @return array
	 */
	public function toArray () 
	{
		return array(
			'totalNum'	=> $this->totalNum,
			'totalPage'	=> $this->totalPage,
			'frNum'		=> $this->frNum,
			'toNum'		=> $this->toNum,
			'frStr'		=> $this->frStr,
			'toStr'		=> $this->toStr,
			'prevStr'	=> $this->prevStr,
			'nextStr'	=> $this->nextStr,
			'pageArr'	=> $this->pageArr,
			'pageStr'	=> $this->pageStr,
		);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// main functions

	/**
	 * Cooperate with data class to fetch items for each page.
	 * Use sql LIMIT to separate page items.
	 *
	 * @return array
	 */
	protected function __paging () 
	{
		// do paging when have total count
		if ($this->totalNum > 0) {
			
			// for paging field
			$this->totalPage = $this->totalPage ? $this->totalPage : ceil($this->totalNum / $this->each);
			$this->page = ($this->page > $this->totalPage) ? $this->totalPage : $this->page;
			$this->frNum = $this->each * ($this->page - 1);
			$this->frStr = $this->frNum + 1;
			$this->toNum = ($this->page == $this->totalPage) ? $this->totalNum : $this->each * $this->page;
			$this->toStr = $this->toNum;
			for ($i = 1; $i <= $this->totalPage; $i++) {
				if ($this->page == $i) {
					if (isset($this->pattern['PageTag']) && sizeof($this->pattern['PageTag']) == 2) {
						$pages[$i] = $this->pattern['PageTag'][0].$i.$this->pattern['PageTag'][1];
					} else {
						$pages[$i] = '<b>'.$i.'</b>';
					}
				} else if ($this->page == $i && $this->totalPage == $i) {
					$pages[$i] = $this->totalPage;
				} else {
					$pages[$i] = $this->makeURL($i);
				}
			}
			
			// provide 3 paging modes
			if (isset($this->pattern['Mode'])) 
			{
				// init array for mode 3
				$pages1 = array();
				$pages2 = array();
				$pages3 = array();
				switch ($this->pattern['Mode']) {
					// Google Style Paging
					case '1' : 
						$start_pos = ($this->page - 10 < 0) ? 0 : ($this->page - 10);
						$end_pos = ($this->page + 9 > $this->totalPage) ? $this->totalPage : ($this->page + 9);
						$pages = Hush_Util::array_chop($pages, $start_pos, $end_pos);
						break;
					// My Style Paging
					case '2' : 
						// don't split pages array when total page < 10
						$limit = isset($this->pattern['ModeArg']['LIMIT']) ? $this->pattern['ModeArg']['LIMIT'] : 10;
						$half_limit = ceil($limit / 2);
						if ($this->totalPage > $limit) {
							$start_pos = ($this->page - $half_limit < 0) ? 0 : ($this->page - $half_limit);
							$start_pos = ($start_pos + $limit > $this->totalPage) ? ($this->totalPage - $limit) : $start_pos;
							$pages = array_slice($pages, $start_pos, $limit);
						}
						break;
					case '3' : 
						$split1 = 5;
						$split2 = $this->totalPage - 4;
						if ($this->totalPage <= 10) {
							$pages1 = $pages;
						} else {
							$i = $this->page;
							if ($i > 1 && $i < $this->totalPage) {
								$pages2 = array($pages[$i - 1], $pages[$i], $pages[$i + 1]);
							} else {
								$pages2 = array($pages[$i]);
							}
							$pages1 = array_slice($pages, 0, 3);
							$pages3 = array_slice($pages, -3, 3);
							if ($this->page <= $split1) {
								$pages1 = array_unique(array_merge($pages1, $pages2));
								$pages2 = $pages3;
								$pages3 = array();
							} elseif ($this->page >= $split2) {
								if ($this->page != $this->totalPage) {
									$pages2 = array_unique(array_merge($pages2, $pages3));
								} else {
									$pages2 = array_unique(array_merge($pages3, $pages2));
								}
								$pages3 = array();
							}

						}
					// Show All Paging
					default : break;
				}
				// build paging string
				$spacing = (array_key_exists('Spacing', $this->pattern)) ? $this->pattern['Spacing'] : ' ';
				if ($this->pattern['Mode'] == 3) {
					$this->pageStr = implode($spacing, $pages1);
					if (sizeof($pages2))
						$this->pageStr .= ' ... '.implode($spacing, $pages2);
					if (sizeof($pages3))
						$this->pageStr .= ' ... '.implode($spacing, $pages3);
				} else {
					$this->pageStr = implode($spacing, $pages);
				}
			}
			
			// get page array
			if (is_array($this->items)) {
				for ($i = $this->frNum; $i < $this->toNum; $i++) {
					$this->pageArr[] = $this->items[$i];
				}
			}
		}
		
		// do paging when have no total count
		else {
			
			$this->frNum = $this->each * ($this->page - 1);
			$this->frStr = $this->frNum + 1;
			$this->toNum = $this->each * $this->page;
			$this->toStr = $this->toNum;
			
		}
		
		// for prev/next field
		if (1) {
			$prevStr = (array_key_exists('Prev', $this->pattern)) ? $this->pattern['Prev'] : $this->prevStr;
			$nextStr = (array_key_exists('Next', $this->pattern)) ? $this->pattern['Next'] : $this->nextStr;
			if ($this->page != 1) {
				$page = $this->page - 1;
				$this->prevStr = $this->makeURL($page, $prevStr);
			} else {
				if (!array_key_exists('TrimPag', $this->pattern)) {
					$this->prevStr = $prevStr;
				} else {
					$this->prevStr = '';
				}
			}
			if ($this->page != $this->totalPage) {
				$page = $this->page + 1;
				$this->nextStr = $this->makeURL($page, $nextStr);
			} else {
				if (!array_key_exists('TrimPag', $this->pattern)) {
					$this->nextStr = $nextStr;
				} else {
					$this->nextStr = '';
				}
			}
		}
		
		// return builded paging array
		return $this->toArray();
	}

	/**
	 * Build search paging item string.
	 *
	 * @param int $pageNo	Current page
	 * @param int $keyword	Href Content Field
	 * @return string
	 */
	protected function makeURL ($pageNo = 1, $keyword = null) 
	{
		// get first page
		$fromPageNum = ($this->firstPage) ? $this->firstPage : 1;
		if ($pageNo < $fromPageNum) return ;
		
		// get page url.
		$pageUrl = $this->pageUrl;
		
		// make paging field
		if (preg_match("/{page}/i", $pageUrl)) {
			$pageUrl = str_replace("{page}", $pageNo, $pageUrl);
		}
		
		// display page one ?
		if (!$this->dsPageOne) {
			$pageUrl = $this->tripPageOne($pageUrl, $fromPageNum);
		}
		
		// append suffix
		if (isset($this->pattern['Suffix'])) {
			$pageUrl .= $this->pattern['Suffix'];
		}
		$pageKey = ($keyword === null) ? $pageNo : $keyword;
		$hrefStr = "<a href=\"{$pageUrl}\">{$pageKey}</a>";
		
		return $hrefStr;
	}

	/**
	 * Get page number from url
	 * 
	 * @param int $pageNo	Current page Number (Get from construct)
	 * @return int
	 */
	protected function pageInUrl ($pageNo)
	{
		// do when necessary
		if (!$pageNo) {
			
			// get page url.
			$pageUrl = $this->pageUrl;
			
			// get page number from uri
			$pattern = preg_quote($pageUrl, '/');
			$pattern = str_replace('\{page\}', '([0-9]+)', $pattern);
			if (preg_match('/' .$pattern . '/i', $_SERVER['REQUEST_URI'], $match)) {
				if (isset($match[1])) $pageNo = intval($match[1]);
			}
		}
		
		// return default page number
		return (!$pageNo || $pageNo <= 0) ? 1 : $pageNo;
	}

	/**
	 * Strip first page url.
	 *
	 * @param int $pageUrl		Page Url
	 * @param int $firstPage	First page number, for replace regexp expression
	 * @return string
	 */
	protected function tripPageOne ($pageUrl, $firstPage)
	{
		// TODO : need to be implemented in subclasses
		return trim($pageUrl);
	}
	
	/**
	 * Get default page url
	 * 
	 * @return string
	 */
	protected function defaultPageUrl ()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$pno = intval($_REQUEST['p']);
		if ($uri) {
			$arr = array();
			$url = parse_url($uri);
			if (!empty($url['query'])) {
				parse_str($url['query'], $arr);
			}
			$arr[$this->pagStr] = '{page}';
			$uri = $url['path'].'?'.http_build_query($arr);
		}
		return urldecode($uri);
	}
}
