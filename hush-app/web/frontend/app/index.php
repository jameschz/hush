<?php

require_once '../../../etc/frontend.config.php';

require_once 'Ihush/App/Page.php';

class Index_Page extends Ihush_App_Page
{
	public function __process ()
	{
		$this->view->welcome = 'Welcome to Hush Framework (Frontend) !';
		$this->debug('Debug Message');
		$this->render('index/index.tpl');
	}
}

new Index_Page;

