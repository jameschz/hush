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
class DemoApi extends App_App_Default_Http
{
	/**
	 * @title /demo/setcache
	 * @action /demo/setcache
	 * @method post
	 * @params cache_id xxx string
	 * @params cache_data xxx string
	 * @method post
	 */
    public function setcacheAction ()
	{
	    $this->prepare();
	    
	    $params = $this->ctx->getParams();
	    $cache_id = isset($params['cache_id']) ? $params['cache_id'] : '';
	    $cache_data = isset($params['cache_data']) ? $params['cache_data'] : '';
	    
	    if ($cache_data) {
    	    require_once 'App/Cache/Demo.php';
    	    $cache = new App_Cache_Demo($cache_id);
    	    $cache->set('data', $cache_data);
    	    $data = array('data' => $cache_data);
    	    $this->ctx->setData($data);
	    }
	    
	    $this->ctx->render();
	}
	
	/**
	 * @title /demo/settoken
	 * @action /demo/settoken
	 * @method post
	 * @params token_id xxx string
	 * @method post
	 */
	public function settokenAction ()
	{
	    $this->prepare();
	    
	    $params = $this->ctx->getParams();
	    $token_id = isset($params['token_id']) ? $params['token_id'] : '';
	    
	    if ($token_id) {
	        $dtime = $this->dtime;
	        require_once 'Core/Cache/Token.php';
	        $cache = new Core_Cache_Token($token_id);
	        $cache->set('time', $dtime);
	        $data = array('time' => $dtime);
	        $this->ctx->setData($data);
	    }
	    
	    $this->ctx->render();
	}
	
	/**
	 * @title /demo/validateemail
	 * @action /demo/validateemail
	 * @method post
	 * @params email james@163.com string
	 * @method post
	 */
	public function validateemailAction ()
	{
	    $this->prepare();
	    
	    $params = $this->ctx->getParams();
	    $email = isset($params['email']) ? $params['email'] : '';
	    
	    if ($email) {
	        $data = Core_Util::validate_mail($email);
	        $this->ctx->setData($data);
	    }
	    
	    $this->ctx->render();
	}
}