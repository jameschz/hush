<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Auth
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @package Hush_Auth
 */
interface Hush_Auth_Interface
{
    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Exception If authentication cannot be performed
     * @return Void / boolean / an object including result
     */
    public function authenticate();
}