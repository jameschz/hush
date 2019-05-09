<?php
require_once 'Core/Cache.php';

class Core_Cache_Vcode extends Core_Cache
{
    /**
     * Core_Cache_Vcode constructor.
     * @param $url
     * @param string $type ('01':注册)('02':找回密码)('1x':活动)
     */
    public function __construct ($url, $type='01')
    {
        $exp = 60 * 5; // expired in 5 minutes
        parent::__construct('vcode_' . $type . $url, $exp);
    }
}