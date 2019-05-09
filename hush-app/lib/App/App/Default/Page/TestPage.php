<?php
/**
 * App Page
 *
 * @category   App
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/App/Default/Page.php';

/**
 * @package App_App_Default
 */
class TestPage extends App_App_Default_Page
{
	public function __init ()
	{
		parent::__init(); // overload parent class's method
// 		$this->authenticate();
	}
	
	private function _getApiConfigList ()
	{
		require_once 'Hush/Document.php';
		$apiConfigList = array();
		foreach (glob(__LIB_PATH_HTTP . '/*.php') as $classFile) {
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
		$this->forward('apiList');
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
		// load upload test page
		if ($apiConfigItem['upload']) {
			$this->render('test/apitest_upload.tpl');
		}
	}
	
	public function apiSignAction ()
	{
		$postData = $_POST;
		$encode = isset($_POST['encode']) ? $_POST['encode'] : '';
		unset($postData['encode']);
	
		// get original header
		$headerJsonString = urldecode($postData['header']);
		$headerJsonObject = json_decode($headerJsonString, true);
		unset($postData['header']);
		
		// reset crypt string
		switch ($encode) {
			case '3des' :
				$token = isset($_POST['token']) ? $_POST['token'] : '';
				$headerJsonObject['X-TOKEN'] = $token;
				$tao = new Core_Cache_Token($token);
				$randkey = $tao->get('randkey');
				$plaintext = http_build_query($postData);
				$paramsEncodeString = Core_Util::crypt_3des_encrypt($plaintext, $randkey);
				break;
			case 'rsa' :
				$plaintext = http_build_query($postData);
				$paramsEncodeString = Core_Util::crypt_rsa_encrypt($plaintext);
				break;
			case 'rsax' :
				require_once 'Crypt/RSAX.php';
				$rsax = new Crypt_RSAX();
				$paramsEncodeString = $rsax->encrypt_post($postData);
				break;
			default :
				$paramsEncodeString = '';
				break;
		}
		
		echo json_encode(array(
			'header' => $headerJsonObject ? $headerJsonObject : '',
			'params' => $paramsEncodeString ? $paramsEncodeString : '',
		));
		exit;
	}
}
