<?php
require_once 'Hush/Cache.php';

class Core_Cache extends Hush_Cache
{
    public function __construct ($tag, $exp = '')
    {
        parent::__construct($tag, $exp);
    }
    
    public function initConnection ()
    {
        if ($this->tid) {
            $conf = cfg('core.cache.redis');
            require_once 'Hush/Cache/Redis.php';
            $this->conn = Hush_Cache_Redis::getConn($conf, $this->tid);
        }
    }
}