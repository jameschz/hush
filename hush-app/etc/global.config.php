<?php
/**
 * Global php settings
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/**
 * Global timezone
 */
date_default_timezone_set('PRC');

/**
 * App Name
 */
define('__APP', 'hush-app');
define('__APP_NS', 'hush');
define('__APP_NAME', 'hush-app');

/**
 * Windows OS
 * Perhaps useful in some case
 */
define('__OS_WIN', !strncasecmp(PHP_OS, 'win', 3));

/**
 * System paths
 */
define('__ETC', dirname(__FILE__));
define('__ROOT', realpath(__ETC . '/../'));
define('__LIB_DIR', realpath(__ROOT . '/lib'));
define('__ETC_DIR', realpath(__ROOT . '/etc'));
define('__BIN_DIR', realpath(__ROOT . '/bin'));
define('__WEB_DIR', realpath(__ROOT . '/web'));
define('__TPL_DIR', realpath(__ROOT . '/tpl'));
define('__DOC_DIR', realpath(__ROOT . '/doc'));
define('__SYSTEM_INI', realpath(__ETC . '/system.ini'));

/**
 * Sql data paths
 */
define('__SQL_DIR', realpath(__DOC_DIR . '/sql'));

/**
 * Core data paths
 */
define('__SYS_DIR', realpath(__ROOT . '/../' . __APP_NS . '-run/sys'));
define('__CDN_DIR', realpath(__ROOT . '/../' . __APP_NS . '-run/cdn/' . __APP));
define('__DAT_DIR', realpath(__ROOT . '/../' . __APP_NS . '-run/dat/' . __APP));
define('__RUN_DIR', realpath(__ROOT . '/../' . __APP_NS . '-run/run/' . __APP));
define('__CACHE_DIR', realpath(__RUN_DIR . '/cache'));
define('__TPL_C_DIR', realpath(__RUN_DIR . '/tpl'));
define('__LOG_DIR', realpath(__RUN_DIR . '/log'));

/**
 * Init System funcs
 */
require_once __ETC . '/global.funcs.php';

/**
 * Init framework
 */
require_once __ETC . '/global.init.php';

/**
 * Include langs
 */
require_once __ETC . '/global.lang.php';

/**
 * Data source configs
 */
if (__HUSH_ENV == 'local') {
    require_once __ETC . '/database.mysql.php';
} else {
    require_once __ETC . '/' . __HUSH_ENV . '/database.mysql.php';
}