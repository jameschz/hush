<?php
require_once 'Core/Cache.php';

class Core_Cache_Token extends Core_Cache
{
	public function __construct ($url)
	{
		$exp = 3600 * 24; // expired in one day
		parent::__construct('token_' . $url, $exp);
	}
}