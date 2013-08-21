<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Crypt
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Hush_Crypt
 */
require_once 'Hush/Crypt.php';

/**
 * @see Hush_Crypt_Exception
 */
require_once 'Hush/Crypt/Exception.php';

/**
 * Usages :
 * 
 * Create server.crt and server.key by follow command:
 * # CA.sh -newcert
 * # ...
 * # mv newkey.pem server.key
 * # mv newcert.pem server.crt
 * # ...
 * And then use command 'openssl rsa -in server.key -noout -modulus' to get Modulus from private key
 * And paste Modulus to the 'PublicKey' var in javascript
 * Make sure RSA.setPublic(PublicKey, "10001");
 * 
 * Modulus :
 * 
 * D2B9E79F9A1A4AE43F4425BDDE739CA47904A3EBC80C83DB4AE8AB1E0AB322217CE9F5B27E316DCFB6FC9CF2AAAA1E3E71E41F491BE094C2459C5369DA0F9F72C75AD3D73FDB1EE07CDCE495205A2FB4C0F9E8DF0A88015E4B794513885FBCD797AE1DC59ED501362E27DCB2F267965CFC7DD0A61C3B479B36FEF39D152FB4D9
 * 
 * @package Hush_Crypt
 */
class Hush_Crypt_Rsa extends Hush_Crypt
{
	/**
	 * @static
	 */
	public static $crtFile = 'server.crt';
	
	/**
	 * @static
	 */
	public static $keyFile = 'server.key';
	
	/**
	 * @static
	 */
	public static $keyPass = '123456';
	
	/**
	 * Construct
	 */
	public function __construct ($config = array()) 
	{
		// runtime system check
		if (!function_exists('openssl_public_encrypt')) {
			throw new Hush_Crypt_Exception('Can not call openssl_* methods');
			return false;
		}
		
		// get real path of crt and key file
		self::$crtFile = realpath(dirname(__FILE__) . '/Rsa/' . self::$crtFile);
		self::$keyFile = realpath(dirname(__FILE__) . '/Rsa/' . self::$keyFile);
	}
	
	/**
	 * Rsa encrypt
	 * 
	 * @param string $source
	 * @return string
	 */
	public function encrypt ($source)
	{
		if (!file_exists(self::$crtFile)) {
			throw new Hush_Crypt_Exception('Crt file \'' . self::$crtFile . '\' is missing');
			return false;
		}
		
		// Formula : 1024/8-11 = 117
		if (strlen($source) > 117) {
			throw new Hush_Crypt_Exception('Rsa encrypt string is too long');
			return false;
		}
		
		$encrypt = '';
		$pub_key = file_get_contents(self::$crtFile);
		openssl_public_encrypt($source, $encrypt, $pub_key);
		
		return bin2hex($encrypt);
	}
	
	/**
	 * Rsa decrypt
	 * 
	 * @param string $encrypt
	 * @return string
	 */
	public function decrypt ($encrypt)
	{
		if (!file_exists(self::$keyFile)) {
			throw new Hush_Crypt_Exception('Key file \'' . self::$keyFile . '\' is missing');
			return false;
		}
		
		$priv_key = file_get_contents(self::$keyFile);
		$priv_key = openssl_get_privatekey($priv_key, self::$keyPass);
		
		$decrypt = '';
		$encrypt = pack("H*", $encrypt);
		openssl_private_decrypt($encrypt, $decrypt, $priv_key);
		
		return $decrypt;
	}
}