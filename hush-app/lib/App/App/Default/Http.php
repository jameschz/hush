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
 
require_once 'App/App/Default.php';
require_once 'Core/App/Context.php';
require_once 'Core/Cache/Token.php';

/**
 * @package App_App_Default
 */
class App_App_Default_Http extends App_App_Default
{
	public function __init ()
	{
// 		parent::__init();
		
		// init dao
		$this->dao = new App_Dao();
		
		// init context
		$this->ctx = new Core_App_Context($this);
		$this->current_time = time();
// 		if(!$this->ctx->member_id){
// 			$this->ctx->member_id = '10001';
// 		}
		$this->mid = $this->ctx->member_id;
// 		$this->mid = $this->ctx->member_id = '10004';
		if (cfg('debug.ctx')) {
			$this->ctx->setDebug();
		}
	}
	
	public function __done ()
	{
// 		parent::__done();
		
		exit; // no display
	}
	
	public function authenticate ()
	{
		// needs login
		if (!$this->ctx->checkauth()) {
			$this->ctx->render();
			exit;
		}
	}
	
	public function prepare ($encode = '')
	{
		if (!$this->ctx->prepare($encode)) {
			$this->ctx->render();
			exit;
		}
	}
	
	public function err ($errcode = ERR_NULL, $encode = '')
	{
		$this->ctx->setErr($errcode);
		$this->ctx->render($encode);
		exit;
	}
	
	public function log ($msg = '')
	{
		@error_log($msg); // save to php-fpm error log
	}
	
}
