<?php
/**
 * rsa & xxtea
 */
Class Crypt_RSAX
{
	public $_randkey = '';
	
	function __construct()
	{
	    $this->pubkey = cfg('core.publickey');
	    $this->prikey = cfg('core.privatekey');
	}
	
	public function encrypt_post ($pa)
	{
		return $this->encrypt(json_encode($pa));
	}
	
	public function decrypt_post ($ps)
	{
		return json_decode($this->decrypt($ps), 1);
	}
	
	public function encrypt_data ($s)
	{
		if (!$this->_randkey) return false;
		$ps = base64_encode(xxtea_encrypt(json_encode($s), $this->_randkey));
		return $ps;
	}
	
	public function decrypt_data ($e)
	{
		if (!$this->_randkey) return false;
		$pe = json_decode(xxtea_decrypt(base64_decode($e), $this->_randkey),1);
		return $pe;
	}
	
	public function encrypt ($s)
	{
		// init pack
		$ps = '';
		// get rand key
		$this->_randkey = rand(100000,999999);
		// encrypt key by rsa
// 		require_once "Crypt/RSA.php";
// 		$rsa = new Crypt_RSA();
// 		$rsa->loadKey($this->pubkey);
// 		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
// 		$ps = base64_encode($rsa->encrypt($this->_randkey));
		// encrypt key by openssl
		$pubkey = Core_Util::str_format_pubkey($this->pubkey);
		openssl_public_encrypt($this->_randkey, $ps, $pubkey, OPENSSL_PKCS1_PADDING);
		$ps = base64_encode($ps);
		// xxtea encrypt
		$ps .= '$$'.base64_encode(xxtea_encrypt($s, $this->_randkey));
		// return str
		return $ps;
	}
	
	public function decrypt ($e)
	{
		// decode pack
		$pa = explode('$$', $e);
		// decrypt key by rsa
// 		require_once "Crypt/RSA.php";
// 		$rsa = new Crypt_RSA();
// 		$rsa->loadKey($this->prikey);
// 		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
// 		$this->_randkey = $rsa->decrypt(base64_decode($pa[0]));
		// decrypt key by openssl
		$prikey = Core_Util::str_format_prikey($this->prikey);
		openssl_private_decrypt(base64_decode($pa[0]), $this->_randkey, $prikey, OPENSSL_PKCS1_PADDING);
		// xxtea decrypt
		$res = xxtea_decrypt(base64_decode($pa[1]), $this->_randkey);
		// return object
		return $res;
	}
}

/**
 * xxtea
 */
if (!extension_loaded('xxtea')) {
    class XXTEA {
        const DELTA = 0x9E3779B9;
        private static function long2str($v, $w) {
            $len = count($v);
            $n = $len << 2;
            if ($w) {
                $m = $v[$len - 1];
                $n -= 4;
                if (($m < $n - 3) || ($m > $n)) return false;
                $n = $m;
            }
            $s = array();
            for ($i = 0; $i < $len; $i++) {
                $s[$i] = pack("V", $v[$i]);
            }
            if ($w) {
                return substr(join('', $s), 0, $n);
            }
            else {
                return join('', $s);
            }
        }

        private static function str2long($s, $w) {
            $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
            $v = array_values($v);
            if ($w) {
                $v[count($v)] = strlen($s);
            }
            return $v;
        }

        private static function int32($n) {
            return ($n & 0xffffffff);
        }

        private static function mx($sum, $y, $z, $p, $e, $k) {
            return ((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ (($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        }

        private static function fixk($k) {
            if (count($k) < 4) {
                for ($i = count($k); $i < 4; $i++) {
                    $k[$i] = 0;
                }
            }
            return $k;
        }
        // $str is the string to be encrypted.
        // $key is the encrypt key. It is the same as the decrypt key.
        public static function encrypt($str, $key) {
            if ($str == "") {
                return "";
            }
            $v = self::str2long($str, true);
            $k = self::fixk(self::str2long($key, false));
            $n = count($v) - 1;
            $z = $v[$n];
            $q = floor(6 + 52 / ($n + 1));
            $sum = 0;
            while (0 < $q--) {
                $sum = self::int32($sum + self::DELTA);
                $e = $sum >> 2 & 3;
                for ($p = 0; $p < $n; $p++) {
                    $y = $v[$p + 1];
                    $z = $v[$p] = self::int32($v[$p] + self::mx($sum, $y, $z, $p, $e, $k));
                }
                $y = $v[0];
                $z = $v[$n] = self::int32($v[$n] + self::mx($sum, $y, $z, $p, $e, $k));
            }
            return self::long2str($v, false);
        }

        // $str is the string to be decrypted.
        // $key is the decrypt key. It is the same as the encrypt key.
        public static function decrypt($str, $key) {
            if ($str == "") {
                return "";
            }
            $v = self::str2long($str, false);
            $k = self::fixk(self::str2long($key, false));
            $n = count($v) - 1;

            $y = $v[0];
            $q = floor(6 + 52 / ($n + 1));
            $sum = self::int32($q * self::DELTA);
            while ($sum != 0) {
                $e = $sum >> 2 & 3;
                for ($p = $n; $p > 0; $p--) {
                    $z = $v[$p - 1];
                    $y = $v[$p] = self::int32($v[$p] - self::mx($sum, $y, $z, $p, $e, $k));
                }
                $z = $v[$n];
                $y = $v[0] = self::int32($v[0] - self::mx($sum, $y, $z, $p, $e, $k));
                $sum = self::int32($sum - self::DELTA);
            }
            return self::long2str($v, true);
        }
    }

    // public functions
    // $str is the string to be encrypted.
    // $key is the encrypt key. It is the same as the decrypt key.
    function xxtea_encrypt($str, $key) {
        return XXTEA::encrypt($str, $key);
    }

    // $str is the string to be decrypted.
    // $key is the decrypt key. It is the same as the encrypt key.
    function xxtea_decrypt($str, $key) {
        return XXTEA::decrypt($str, $key);
    }
}
