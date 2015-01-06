<?php

require_once 'global.config.php';

/**
 * URL relative constants
 * TODO : could be changed by yourself's settings !!!
 */
define('__HTTP_HOST', 'http://' . $_SERVER['HTTP_HOST']);
define('__HTTP_ROOT', '/');

/**
 * MVC url mapping ini file
 * TODO : could be changed by yourself's settings !!!
 */
define('__MAP_INI_FILE', realpath(__ETC . '/backend.mapping.ini'));

/**
 * Error's messages ini file
 * TODO : could be changed by yourself's settings !!!
 */
define('__MSG_INI_FILE', realpath(__ETC . '/backend.message.ini'));

/**
 * PHP libraries for pages
 * TODO : could be changed by yourself's settings !!!
 */
define('__LIB_PATH_PAGE', realpath(__LIB_DIR . '/Ihush/App/Backend/Page'));
define('__LIB_PATH_REMOTE', realpath(__LIB_DIR . '/Ihush/App/Backend/Remote'));

/**
 * Template relevant settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__TPL_ENGINE', 'Smarty');
define('__TPL_LIB_PATH', 'Smarty_3');
define('__TPL_SMARTY_PATH', realpath(__TPL_DIR . '/backend'));

/**
 * Cache's settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__FILECACHE_DIR', realpath(__DAT_DIR . '/cache'));

/**
 * Acl's settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__ACL_SA', 'sa');