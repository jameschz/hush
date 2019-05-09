<?php
require_once 'Core/Cache.php';

class Core_Cache_Ecode extends Core_Cache
{
    public function __construct ($url)
    {
        $exp = 60 * 30; // expired in 30 minutes
        parent::__construct('ecode_' . $url, $exp);
    }
}