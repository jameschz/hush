<?php
require_once 'Core/Cache.php';

class Core_Cache_Words extends Core_Cache
{
    public function __construct ($exp=3600)
    {
        if(!$exp) $exp=3600;

        parent::__construct('sensitive_words', $exp);
    }
}