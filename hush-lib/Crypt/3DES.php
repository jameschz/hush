<?php
/**
 3DES加密规则：
 模式：ECB
 填充模式：PKCS7Padding
 数据块大小：8字节（64位）
 初始化向量：无
 密钥：XXXXXXXXXXXXXXXXX
 * @author liaojunling.leo
 *
 */
class Crypt_3DES {
	
	public $key = "";
	
	function __construct($key=""){
		if ($key) {
			$this->key=$key;
		}
	}
	function encrypt($input){//数据加密
		if (empty($input)) return false;
		
		$size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
		$input = $this->pkcs5_pad($input, $size);
		$key = str_pad($this->key,24,'0');
		$td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		$data = base64_encode($data);
		return $data;
	}

	function decrypt($encrypted){//数据解密
		if ( empty($encrypted) ) return false;
		
		$encrypted = base64_decode($encrypted);
		$key = str_pad($this->key,24,'0');
		$td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		$decrypted = mdecrypt_generic($td, $encrypted);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$y=$this->pkcs5_unpad($decrypted);
		return $y;
	}
	function pkcs5_pad ($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	function pkcs5_unpad($text){
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text)) {
			return false;
		}
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
			return false;
		}
		return substr($text, 0, -1 * $pad);
	}

	function PaddingPKCS7($data) {
		$block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
		$padding_char = $block_size - (strlen($data) % $block_size);
		$data .= str_repeat(chr($padding_char),$padding_char);
		return $data;
	}
}