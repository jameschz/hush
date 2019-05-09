<?php
/**
 * App Page
 *
 * @category   App
 * @package    App_App_Default_Http
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/App/Default/Http.php';

/**
 * @package App_App_Default_Http
 */
class TestApi extends App_App_Default_Http
{
	/**
	 * @title /test/crypt
	 * @action /test/crypt
	 * @method post
	 */
	public function cryptAction ()
	{
		$text = '我爱北京天安门Baby2016!!!';
		require_once 'Crypt/RSAX.php';
		$rsax = new Crypt_RSAX();
		$encode = $rsax->encrypt($text);
		var_dump($encode);
		$decode = $rsax->decrypt($encode);
		var_dump($decode);
	}
	
	/**
	 * @title /test/sendx
	 * @action /test/sendx
	 * @method post
	 * @params x1 10 string
	 * @params x2 25 string
	 * @encode rsax
	 */
	public function sendxAction ()
	{
		// decipher
		$this->prepare('rsax');
		
		$params = $this->ctx->getParams();
// 		Hush_Util::dump($params);
		
		$data = array('sum' => $params['x1'] * $params['x2']);
		$this->ctx->setData($data);
		
		$this->ctx->render('rsax');
	}
}