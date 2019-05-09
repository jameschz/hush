<?php
class Core_App_Context
{
	private $debug = false;
	private $debug_t1 = 0;
	private $debug_t2 = 0;
	private $page = null;
	
	private $err = '';
	private $msg = '';
	private $data = array();
	
	private $token = '';
	private $header = array();
	private $cipher = '';
	private $params = array();
	private $randkey = '';
	private $autokey = '';
	
	public $phone = '';
	public $member_id = 0;
	
	public function __construct ($page)
	{
		if (!$page instanceof Hush_Page) {
			throw new Exception('Context needs a Hush_App instance');
		}
		
		// start time
		$this->debug_t1 = microtime(true);
		
		// init page
		$this->page = $page;
		
		// init error
		$this->setErr(ERR_OK);
		
		// init header
		if (!$this->header) {
			$this->header = array(
                'X-GAME' => $_SERVER['HTTP_X_GAME'],
				'X-OS' => $_SERVER['HTTP_X_OS'],
				//'X-SDK' => $_SERVER['HTTP_X_SDK'],
				//'X-APPID' => $_SERVER['HTTP_X_APPID'],
				'X-TOKEN' => $_SERVER['HTTP_X_TOKEN'],
				'X-DEVICE' => $_SERVER['HTTP_X_DEVICE'],
				'X-CHANNEL' => $_SERVER['HTTP_X_CHANNEL'],
				'X-SIGNATURE' => $_SERVER['HTTP_X_SIGNATURE'],
				'X-VERSION' => $_SERVER['HTTP_X_VERSION'],
				'X-SYSTEM' => $_SERVER['HTTP_X_SYSTEM'],
				'X-SCREEN' => $_SERVER['HTTP_X_SCREEN'],
				'X-MODEL' => $_SERVER['HTTP_X_MODEL'],
                'X-SDK-VER' => $_SERVER['HTTP_X-SDK-VER'],
                'X-ADDRESS-MAC' => $_SERVER['HTTP_X_ADDRESS_MAC'],
                'X-ADDRESS-IP' => $_SERVER['HTTP_X_ADDRESS_IP'],
                'X-DEVICE-IDFA' => $_SERVER['HTTP_X_DEVICE_IDFA'],
			);
		}

		// init token
		$this->token = $this->header['X-TOKEN'] ? $this->header['X-TOKEN'] : Hush_Util::param('token');
        $this->_set_token($this->token);

        // init randkey
//		if ($this->token) {
//			$tokenObj = new Core_Cache_Token($this->token);
//			$tokenObj->set('_tt', time());
//			// get member id for auth
//			$this->member_id = $tokenObj->get('aid');
//		}
	}
	
	public function checkauth ()
	{
		// check randkey or autokey or user info
		if (!$this->member_id) {
			$this->setErr(ERR_CACHE_EXP);
			return false;
		}
		
		return true;
	}
	
	public function prepare ($encode = null)
	{
		// decode logic
		switch ($encode) {
			case '3des' :
				// decode 3des params
				if (!$this->cipher) {
					// get cipher
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$this->cipher = file_get_contents("php://input");
					} else {
						$data = $_SERVER["REQUEST_URI"];
						$cipher = strrchr($data, "?");
						$this->cipher = $cipher ? substr($cipher, 1) : '';
					}
					// get randkey
					if ($this->token) {
						$tokenObj = new Core_Cache_Token($this->token);
						$this->randkey = $tokenObj->get('randkey');
					}
					// check randkey
					if (!$this->randkey) {
						$this->setErr(ERR_TOKEN);
						return false;
					}
					// do decipher logic
					if ($this->cipher) {
						$req = Core_Util::crypt_3des_decrypt($this->cipher, $this->randkey);
						parse_str($req, $this->params);
						// merge back to system vars
						$_REQUEST = array_merge($_REQUEST, $this->params);
					}
				}
				break;
			case 'rsa' :
				// decode rsa params
				if (!$this->cipher) {
					// get cipher
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$this->cipher = file_get_contents("php://input");
					} else {
						$data = $_SERVER["REQUEST_URI"];
						$cipher = strrchr($data, "?");
						$this->cipher = $cipher ? substr($cipher, 1) : '';
					}
					// do decipher logic
					if ($this->cipher) {
						$req = Core_Util::crypt_rsa_decrypt($this->cipher);
						parse_str($req, $this->params);
						// merge back to system vars
						$_REQUEST = array_merge($_REQUEST, $this->params);
					}
				}
				break;
			case 'rsax' :
				// decode rsax params
				if (!$this->cipher) {
					// get cipher
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$this->cipher = file_get_contents("php://input");
					} else {
						$data = $_SERVER["REQUEST_URI"];
						$cipher = strrchr($data, "?");
						$this->cipher = $cipher ? substr($cipher, 1) : '';
					}
					// do decipher logic
					if ($this->cipher) {
						require_once 'Crypt/RSAX.php';
						$this->rsax = new Crypt_RSAX();
						$this->params = $this->rsax->decrypt_post($this->cipher);
						// merge back to system vars
						if ($this->params && is_array($this->params)) {
							$_REQUEST = array_merge($_REQUEST, $this->params);
						}
					}
				}
				break;
			default :
				// get common params
				$this->params = $_REQUEST;
				break;
		}

		//token在body中，重新 set token
        $this->_set_token($_REQUEST['token']);

		return true;
	}
	
	public function setDebug ($debug = true)
	{
		$this->debug = $debug;
		return $this;
	}
	
	public function setErr ($err = ERR_OK, $msg = true)
	{
		$this->err = $err;
		if ($msg) {
			$this->msg = err($err);
		}
		return $this;
	}
	
	public function setMsg ($msg = '')
	{
		$this->msg = $msg;
		return $this;
	}
	
	public function setData ($data = array())
	{
		$this->data = $data;
		return $this;
	}
	
	public function getHeader ()
	{
		return $this->header;
	}
	
	public function getCipher ()
	{
		return $this->cipher;
	}
	
	public function getParams ()
	{
		return $this->params;
	}
	
	public function getRandkey ()
	{
		return $this->randkey;
	}
	
	public function getToken ()
	{
		return $this->token;
	}

	private function _set_token ($token)
    {
        if(!empty($token))
        {
            $this->token = $token;

            $tokenObj = new Core_Cache_Token($this->token);
            $tokenObj->set('_tt', time());
            // get member id for auth
            $this->member_id = $tokenObj->get('aid');
        }
    }
	
	public function render ($encode = null, $debug = array())
	{
		$ret = array(
			'err' => $this->err,
			'msg' => $this->msg,
			'data' => $this->data,
		);
		switch ($encode) {
			case '3des' :
				if ($this->data) {
					$json_data = json_encode($this->data); // to string
					$ret['data'] = Core_Util::crypt_3des_encrypt($json_data, $this->randkey);
				}
				break;
			case 'rsa' :
				if ($this->data) {
					$json_data = json_encode($this->data); // to string
					$ret['data'] = Core_Util::crypt_rsa_encrypt($json_data);
				}
				break;
			case 'rsax' :
				if ($this->data && $this->rsax) {
					$ret['data'] = $this->rsax->encrypt_data($this->data);
				}
				break;
			default:
				break;
		}
		if ($this->debug) {
			// end time
			$this->debug_t2 = microtime(true);
			// debug info
			$ret['debug'] = array(
				'token' => $this->token,
				'randkey' => $this->randkey,
				'request' => json_encode($this->getParams()),
				'rawdata' => json_encode($this->data),
				'costsec' => $this->debug_t2 - $this->debug_t1,
			);
			// additional debug message
			$ret['debug'] = array_merge($ret['debug'], $debug);
		}
		$json = json_encode($ret);
		// support jsonp
		$callback = Hush_Util::param('callback');
		if ($callback) {
			$json = "{$callback}({$json})";
		}
		// set header
		if ($this->header['X-OS'] == 1) {
			//header('Content-Type:application/json; charset=UTF-8');
            header('Content-Type:text/plain; charset=UTF-8');
		} else {
			header('Content-Type:text/plain; charset=UTF-8');
		}
		echo $json;
// 		exit;
	}
}