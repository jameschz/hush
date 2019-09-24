<?php
require_once 'global.config.php';

/**
 * Set up from entrance file (index.php) 
 */
define('__APP_SITE_NS', ucfirst(__APP_SITE));

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
define('__MAP_INI_FILE', realpath(__ETC . '/' . __APP_SITE . '.mapping.ini'));

/**
 * Error's messages ini file
 * TODO : could be changed by yourself's settings !!!
 */
define('__MSG_INI_FILE', realpath(__ETC . '/' . __APP_SITE . '.message.ini'));

/**
 * PHP libraries for pages
 * TODO : could be changed by yourself's settings !!!
 */
define('__LIB_PATH_PAGE', realpath(__LIB_DIR . '/App/App/' . __APP_SITE_NS . '/Page'));
define('__LIB_PATH_HTTP', realpath(__LIB_DIR . '/App/App/' . __APP_SITE_NS . '/Http'));

/**
 * Template relevant settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__TPL_ENGINE', 'Smarty');
define('__TPL_LIB_PATH', 'Smarty_3');
define('__TPL_BASE_PATH', realpath(__TPL_DIR . '/' . __APP_SITE));
define('__TPL_COMPILE_PATH', realpath(__TPL_C_DIR . '/' . __APP_SITE));

/**
 * Cache's settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__SITE_CACHE_DIR', realpath(__CACHE_DIR . '/' . __APP_SITE));
define('__SITE_LOG_DIR', realpath(__LOG_DIR . '/' . __APP_SITE));


/**
 * Acl's settings
 * TODO : could be changed by yourself's settings !!!
 */
define('__ACL_SA', 'sa');

/**
 * Load app configs
 */
require_once 'global.appcfg.php';