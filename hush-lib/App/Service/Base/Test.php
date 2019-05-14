<?php
require_once 'App/Service.php';

class Base_Test extends App_Service
{
    public function hello ()
    {
        return 'Method "' . __METHOD__ . '" say hello !!!';
    }
}