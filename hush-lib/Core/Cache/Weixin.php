<?php
require_once 'Core/Cache.php';

class Core_Cache_Weixin extends Core_Cache
{
    public function __construct ($url, $exp=3600)
    {
        if(!$exp) $exp=3600;

        parent::__construct('wxtk_'.$url, $exp);
    }
}