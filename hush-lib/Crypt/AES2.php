<?php 
class AES2
{
	private $cipher = MCRYPT_RIJNDAEL_128;
	private $mode = MCRYPT_MODE_CBC;
	private $secret_key = "";
	private $iv = "L+\~f4,Ir)b\$=pkf";

	function AES2 () {}
	
	function setKey ($key)
	{
		$this->secret_key = $key;
	}
	
	function setIV ($iv)
	{
		$this->iv = $iv;
	}
	
	function encrypt ($str)
	{
		$td = mcrypt_module_open($this->cipher, "", $this->mode, $this->iv);
		mcrypt_generic_init($td, $this->secret_key, $this->iv);
		$cyper_text = mcrypt_generic($td, $this->pad2Length($str, 16));
		$r = base64_encode($cyper_text);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $r;
	}
	
	function decrypt ($str)
	{
		$td = mcrypt_module_open($this->cipher, "", $this->mode, $this->iv);
		mcrypt_generic_init($td, $this->secret_key, $this->iv);
		$decrypted_text = mdecrypt_generic($td, base64_decode($str));
		$r = $decrypted_text;
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$c=chr(8);
		$r=str_replace($c,"",$r);
		return $r;
	}
	
	function hex2bin ($hexdata)
	{
		$bindata="";
		for ($i=0;$i<strlen($hexdata);$i+=2) {
			$bindata.=chr(hexdec(substr($hexdata,$i,2)));
		}
		return $bindata;
	}
	
	function hexToStr ($hex)
	{
		$bin="";
		for($i=0; $i<strlen($hex)-1; $i+=2) {
			$bin.=chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $bin;
	}
	
	function pad2Length ($text, $padlen)
	{
		$len = strlen($text)%$padlen;
		$res = $text;
		$span = $padlen-$len;
		for($i=0; $i<$span; $i++){
			$res .= chr($span);
		}
		return $res;
	}
	
	function trimEnd ($text)
	{
		$len = strlen($text);
		$c = $text[$len-1];
		if(ord($c) <$len){
			for($i=$len-ord($c); $i<$len; $i++){
				if($text[$i] != $c){
					return $text;
				}
			}
			return substr($text, 0, $len-ord($c));
		}
		return $text;
	}
}