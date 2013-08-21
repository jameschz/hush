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
require_once 'Zend/Validate.php';

/**
 * @package Ihush_App_Backend
 */
class CommonPage extends Ihush_App_Backend_Page
{
	public function __init ()
	{
		parent::__init(); // overload parent class's method
		$this->authenticate();
	}
	
	public function indexAction () 
	{
		// TODO : Display directly
	}
	
	public function personalAction () 
	{
		$aclUserDao = $this->dao->load('Core_User');
		$userId = $this->admin['id'] ? $this->admin['id'] : 0;
		$user = $aclUserDao->read($this->admin['id']);
		
		// do post
		if ($_POST) {
			// validation
			if (!$userId) {
				$this->addError('common.notempty', 'User Id');
			}
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'User name');
			}
			if ($this->noError()) {
				$data['name'] = $this->param('name');
				if ($this->param('pass')) {
					$data['pass'] = Hush_Util::md5($this->param('pass'));
				}
				// do update
				if ($userId) {
					$aclUserDao->update($data, 'id=' . $userId);
					$this->addErrorMsg('Personal Infomation updated successfully');
				}
			}
		}
		
		$this->view->user = $user;
	}
}
