<?php
 
require_once '../../etc/backend.config.php';

require_once 'Hush/App.php';

$app = new Hush_App();

$app->setTplDir(__TPL_SMARTY_PATH)
	->setErrorPage('html/404.htm')
	->addMapFile(__MAP_INI_FILE)
	->addAppDir(__LIB_PATH_PAGE)
	->addAppDir(__LIB_PATH_REMOTE);

/**
 * skip 404 page and trace exception
 * TODO : should be commented in www environment
 */
$app->setDebug(true);

/**
 * set page's debug level
 * TODO : should be changed to Hush_Debug::FATAL in www environment
 */
if ($_GET['debug']) {
	$app->setDebugLevel(Hush_Debug::DEBUG);
} else {
	$app->setDebugLevel(Hush_Debug::FATAL);
}

$app->run();
