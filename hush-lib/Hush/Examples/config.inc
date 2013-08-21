<?php
/**
 * Hush Framework
 *
 * @ignore
 * @category   Examples
 * @package    Examples
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

define('__HUSH_LIB_DIR', realpath(dirname(__FILE__) . '/../../'));

/**
 * This vars should be pointed to Zend Framework library path
 */
define('__ZEND_LIB_DIR', realpath(__HUSH_LIB_DIR . '/../phplibs'));

set_include_path('.' . PATH_SEPARATOR . __ZEND_LIB_DIR . PATH_SEPARATOR . __HUSH_LIB_DIR . PATH_SEPARATOR . get_include_path());