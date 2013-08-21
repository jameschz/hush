<?php
/**
 * Global php settings
 */
error_reporting(E_ERROR | E_WARNING);

/**
 * Global timezone
 */
date_default_timezone_set('PRC');

/**
 * App Name
 */
define('__APP_NAME', 'Ihush');

/**
 * Windows OS
 * Perhaps useful in some case
 */
define('__OS_WIN', !strncasecmp(PHP_OS, 'win', 3));

/**
 * Enviornment settings
 * Include 'dev', 'test', 'www'
 * Impact some variables and debug infomation
 * TODO : should be changed by enviornment change !!!
 */
define('__ENV', 'dev');

/**
 * Common Directories
 */
define('__ETC', dirname(__FILE__));
define('__ROOT', realpath(__ETC . '/../'));
define('__LIB_DIR', realpath(__ROOT . '/lib'));
define('__ETC_DIR', realpath(__ROOT . '/etc'));
define('__BIN_DIR', realpath(__ROOT . '/bin'));
define('__WEB_DIR', realpath(__ROOT . '/web'));
define('__TPL_DIR', realpath(__ROOT . '/tpl'));
define('__DAT_DIR', realpath(__ROOT . '/dat'));
define('__DOC_DIR', realpath(__ROOT . '/doc'));
define('__CACHE_DIR', realpath(__DAT_DIR . '/cache'));

/**
 * Global functions
 */
require_once __ETC . '/global.funcs.php';

/**
 * Common libraries paths
 */
define('__COMM_LIB_DIR', _hush_realpath(__ROOT . '/../../phplibs'));

/**
 * Hush libraries paths
 */
define('__HUSH_LIB_DIR', _hush_realpath(__ROOT . '/../hush-lib'));

/**
 * Include path setting
 */
set_include_path('.' . PATH_SEPARATOR . __LIB_DIR . PATH_SEPARATOR . __COMM_LIB_DIR . PATH_SEPARATOR . __HUSH_LIB_DIR . PATH_SEPARATOR . get_include_path());

/**
 * Global init logics
 */
require_once __ETC . '/global.init.php';

/**
 * Data source configs
 */
require_once __ETC . '/database.mysql.php';
require_once __ETC . '/database.mongo.php';