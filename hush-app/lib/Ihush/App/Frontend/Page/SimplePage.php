<?php
/**
 * Ihush App
 *
 * @category   Ihush
 * @package    Ihush_App_Frontend
 * @author     James.Huang <james@ihush.com>
 * @copyright  Copyright (c) iHush Technologies Inc. (http://www.ihush.com)
 * @version    $Id$
 */

/**
 * Default view engine is smarty
 * TODO : please change this to true if you want to use view
 */
Hush_App::setPageView(false);

/**
 * @package Ihush_App_Frontend
 */
class SimplePage
{
	public function indexAction () 
	{
		$n = 0;
		for ($i = 0; $i < 100; $i++) {
			$n = $n + 1;
		}
		echo $n;
	}
}