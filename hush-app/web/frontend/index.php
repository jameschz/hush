<?php
 
require_once '../../etc/frontend.config.php';

require_once 'Hush/App.php';

$app = new Hush_App();

$app->setTplDir(__TPL_SMARTY_PATH)
	->setErrorPage('html/404.htm', 404)
	->setErrorPage('html/500.htm', 500)
	->addMapFile(__MAP_INI_FILE)
	->addAppDir(__LIB_PATH_PAGE);

/**
 * skip 404 page and trace exception
 * TODO : should be commented in www environment
 */
$app->setDebug(true);

/**
 * set page's debug level
 * Hush_Debug::DEBUG
 * TODO : should be changed to Hush_Debug::FATAL in www environment
 */
if ($_GET['debug']) {
	$app->setDebugLevel(Hush_Debug::DEBUG);
} else {
	$app->setDebugLevel(Hush_Debug::FATAL);
}

$app->run();
