<?php
require_once 'Core/Cache.php';

class App_Cache_Demo extends Core_Cache
{
    public function __construct ($url)
    {
        $exp = 60 * 30; // expired in 30 minutes
        parent::__construct('hush_app_demo_' . $url, $exp);
    }
}