<?php
// get env
if (isset($_GET['env'])) {
	define('__HUSH_ENV', (string) $_GET['env']);
}

// require backend configs
require_once dirname(__FILE__) . '/../config.php';