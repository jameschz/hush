<?php
/**
 * Ihush Page
 *
 * @category   Ihush
 * @package    Ihush_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Backend/Page.php';

/**
 * @package Ihush_App_Backend
 */
class TestPage extends Ihush_App_Backend_Page
{
	public function __init ()
	{
		parent::__init(); // overload parent class's method
	}
	
	private function _getApiConfigList ()
	{
		require_once 'Hush/Document.php';
		$apiConfigList = array();
		foreach (glob(__LIB_PATH_REMOTE . '/*.php') as $classFile) {
			$className = basename($classFile, '.php');
			if ($classFile && $className) {
				require_once $classFile;
				$rClass = new ReflectionClass($className);
				$methodList = $rClass->getMethods();
				$doc = new Hush_Document($classFile);
				foreach ($methodList as $method) {
					$config = $doc->getAnnotation($className, $method->name);
					if ($config && preg_match('/Action$/', $method->name)) {
						$apiConfigList[$className][$method->name] = $config;
					}
				}
			}
		}
		return $apiConfigList;
	}
	
	public function indexAction () 
	{
		$this->view->welcome = 'Welcome to Hush Framework (Backend) !';
	}
	
	public function apiListAction ()
	{
		$this->view->apiConfigList = $this->_getApiConfigList();
	}
	
	public function apiTestAction ()
	{
		$serviceName = $this->param('serviceName');
		$actionName = $this->param('actionName');
		$apiConfigList = $this->_getApiConfigList();
		$apiConfigItem = $apiConfigList[$serviceName][$actionName];
		if (!$apiConfigItem) {
			throw new Hush_Exception("Non-existed API '$serviceName::$actionName'");
		}
		$this->view->config = $apiConfigItem;
	}
}
