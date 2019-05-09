<?php
require_once 'Hush/Util.php';

class Core_Util
{
	public static function md5 ($str)
	{
		return Hush_Util::md5($str);
	}
	
	public static function dump ($var)
	{
		echo '<pre style="font-size:12px;color:green">';
		if (is_array($var)) print_r($var);
		elseif (is_string($var)) echo $var;
		else var_dump($var);
		echo '</pre>';
	}
	
	public static function tail ($file, $lines = 10)
	{
	    $sarr = array();
	    $fp = fopen($file, "r");
	    $pos = -2;
	    $eof = "";
	    $head = false;
	    while ($lines > 0) {
	        while ($eof != "\n") {
	            if (fseek($fp, $pos, SEEK_END) == 0) {
	                $eof = fgetc($fp);
	                $pos--;
	            } else {
	                fseek($fp,0,SEEK_SET);
	                $head = true;
	                break;
	            }
	        }
	        array_unshift($sarr, fgets($fp));
	        if ($head) break;
	        $eof = "";
	        $lines--;
	    }
	    fclose($fp);
	    return $sarr;
	}
	
	public static function rand_num ($num)
	{
		$str = '';
		for ($i = 0; $i < $num; $i++) {
			$str .= rand(0, 9);
		}
		return $str;
	}
	
	public static function rand_str ($num)
	{
		$str = '';
		for ($i = 0; $i < $num; $i++) {
			$str .= chr(rand(97, 122));
		}
		return $str;
	}
	
	public static function rand_key ($seed = 0)
	{
		return md5(md5(time().rand(100000,999999)));
	}
	
	public static function rand_code ($seed = 0)
	{
		return rand(123456, 987654);
	}
	
	public static function rand_link ($link = '')
	{
		$base32 = "abcdefghijklmnopqrstuvwxyz0123456789"; 
		
		$hex = md5($link);
		$hexLen = strlen($hex);
		$subHexLen = $hexLen / 8;
		$output = array();
		
		for ($i = 0; $i < $subHexLen; $i++) {
			$subHex = substr($hex, $i * 8, 8);
			$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
			$out = '';
			for ($j = 0; $j < 6; $j++) {
				$val = 0x0000001F & $int;
				$out .= $base32[$val];
				$int = $int >> 5;
			}
			$output[] = $out;
		}
		return $output[0];
	}
	
	public static function rand_string ($length = 6)
	{
	    $length = intval($length);
	    if($length <= 0) return '';
	    
	    $r_string = '';
	    $str = '0123456789ABCDEFGHJKMNPQRSTUVWXYZ';
	    for($i=0; $i<$length; $i++){
	        
	        $tmp = str_shuffle($str);
	        $d = rand(0, 32);
	        
	        $r_string .= $tmp[$d];
	    }
	    
	    return $r_string;
	}
	
	public static function microtime_float ()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	public static function get_ipaddr ()
	{
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if (@$_SERVER["HTTP_CLIENT_IP"]) $ip = $_SERVER["HTTP_CLIENT_IP"];
		else if (@$_SERVER["REMOTE_ADDR"]) $ip = $_SERVER["REMOTE_ADDR"];
		else if (@getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (@getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
		else if (@getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
		else $ip = '';
		return $ip;
	}

	public static function get_http_type ()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';

        return $http_type;
    }

    public static function get_basic_auth ()
    {
        $UserName = '';
        $PassWord = '';
        // Apache服务器
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $UserName = $_SERVER['PHP_AUTH_USER'];
            $PassWord = $_SERVER['PHP_AUTH_PW'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { // 其他服务器如 Nginx Authorization
            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0) {
                $Authorization = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
                $UserName = isset($Authorization[0]) ? $Authorization[0] : '';
                $PassWord = isset($Authorization[1]) ? $Authorization[1] : 0;
            }
        }
        return array(
            $UserName,
            $PassWord
        );
    }

	function urlsafe_b64encode ($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
	
	function urlsafe_b64decode ($data)
	{
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// array function
	
	function array_depth ($array)
	{
		$max_depth = 1;
		foreach ($array as $value) {
			if (is_array($value)) {
				$depth = array_depth($value) + 1;
				if ($depth > $max_depth) {
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	}
	
	function array_sort ($array, $key, $order=SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();
		
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $key) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
			
			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}
	
			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}
		
		return $new_array;
	}
	
	public static function array_sum ($array, $key)
	{
		$total = 0;
		
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $key) {
							$total += $v2;
						}
					}
				} else {
					$total += $v;
				}
			}
		}
		
		return $total;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// str function
	
	public static function str_len ($str)
	{
		return mb_strlen($str, 'utf-8');
	}
	
	public static function str_cut ($str, $len, $suf = ' ...')
	{
		$slen = mb_strlen($str, 'utf-8');
		if ($slen > $len) {
			return mb_substr($str, 0, $len, 'utf-8') . $suf;
		} else {
			return $str;
		}
	}
	
	public static function str_desc ($str, $len, $suf = ' ...')
	{
		$str = self::str_strip_tags($str);
		$slen = mb_strlen($str, 'utf-8');
		if ($slen > $len) {
			return mb_substr($str, 0, $len, 'utf-8') . $suf;
		} else {
			return $str;
		}
	}
	
	public static function str_from_argv ($key)
	{
	    global $argv;
	    if ($argv) {
	        foreach ($argv as $arg) {
	            $arg_arr = explode('=', $arg);
	            if (sizeof($arg_arr) == 2 && $arg_arr[0] == $key) {
	                return $arg_arr[1];
	            }
	        }
	    }
	    return '';
	}
	
	public static function str_strip_tags ($str)
	{
		$str = strip_tags($str);
		$str = preg_replace('/&[^;]+;|\r|\n|\s/i','',$str);
		$str = trim($str);
		return $str;
	}
	
	public static function str_strip_mce_text ($str)
	{
		// for code
		$str = preg_replace('/\r|\n/i','',$str);
		// for images
		$str = preg_replace('/width:[\d\s]+px;/i','',$str);
		$str = preg_replace('/height:[\d\s]+px;/i','',$str);
		// for iframe
		$str = preg_replace('/width="[^"]+"/i','width="100%"',$str);
		$str = preg_replace('/height="[^"]+"/i','height="auto"',$str);
		$str = trim($str);
		return $str;
	}
	
	// 专为文章准备
	public static function str_strip_story ($str, $lazyload = false)
	{
		// for code
		$str = preg_replace('/\r|\n/i','',$str);
		// for images
		$str = preg_replace('/width:[\d\s]+px;/i','',$str);
		$str = preg_replace('/height:[\d\s]+px;/i','',$str);
		// for iframe
		$str = preg_replace('/width="[^"]+"/i','width="100%"',$str);
		$str = preg_replace('/height="[^"]+"/i','height="auto"',$str);
		$str = preg_replace('/iframe\s+src="http:/i','iframe src="https:',$str);
		// for iframe
		if ($lazyload) {
			preg_match_all('/<iframe[^>]*src="([^"]*)"[^>]*\/?>/i', $str, $matches);
			if (isset($matches[1]) && sizeof($matches[1]) > 0) {
				$replace_old = array();
				$replace_new = array();
				foreach ($matches[1] as $k => $v) {
					$replace_old[] = $matches[0][$k];
					$replace_new[] = '<iframe data-src="'.$matches[1][$k].'" frameborder="0" allowfullscreen="true" width="100%" height="auto"></iframe>';
				}
				$str = str_replace($replace_old, $replace_new, $str);
			}
		}
		// for lazyload
		if ($lazyload) {
			preg_match_all('/<img[^>]*src="([^"]*)"[^>]*\/?>/i', $str, $matches);
			if (isset($matches[1]) && sizeof($matches[1]) > 0) {
				$replace_old = array();
				$replace_new = array();
				foreach ($matches[1] as $k => $v) {
					$replace_old[] = $matches[0][$k];
					$replace_new[] = '<img class="lazy" data-original="'.$matches[1][$k].'" />';
				}
				$str = str_replace($replace_old, $replace_new, $str);
			}
		}
		$str = trim($str);
		return $str;
	}
	
	public static function str_pub_image ($str)
	{
		preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/Uims', $str, $images);
		$old_img_arr = array();
		$new_img_arr = array();
		if (isset($images[1]) && sizeof($images[1]) > 0) {
			foreach ($images[1] as $img_url) {
				if (preg_match("/^data:image/i", $img_url)) {
					$res = self::core_file_save($img_url);
					if ($res) {
						$old_img_arr[] = $img_url;
						$new_img_arr[] = $res['url'];
					}
				}
			}
		}
		if ($old_img_arr && $new_img_arr) {
			$str = str_replace($old_img_arr, $new_img_arr, $str);
		}
		return $str;
	}
	
	public static function str_pub_clean ($str)
	{
		if ($str) {
			// 去外链
			preg_match_all('/<a[^>]*href="([^"]*)"[^>]*>(.*)<\/a>/i', $str, $matches);
			if (isset($matches[1]) && sizeof($matches[1]) > 0) {
				$replace_old = array();
				$replace_new = array();
				foreach ($matches[1] as $k => $v) {
					if (!Core_Util::str_test_mce_link($v)) {
						$replace_old[] = $matches[0][$k];
						$replace_new[] = '';
					}
				}
				$str = str_replace($replace_old, $replace_new, $str);
			}
			// 去嵌入
			preg_match_all('/<iframe[^>]*src="([^"]*)"[^>]*>(.*)<\/iframe>/i', $str, $matches);
			if (isset($matches[1]) && sizeof($matches[1]) > 0) {
				$replace_old = array();
				$replace_new = array();
				foreach ($matches[1] as $k => $v) {
					if (!Core_Util::str_test_mce_video($v)) {
						$replace_old[] = $matches[0][$k];
						$replace_new[] = '';
					}
				}
				$str = str_replace($replace_old, $replace_new, $str);
			}
			// 去空行
			$str = preg_replace("/(\r?\n)+/i", "\n", $str);
		}
		return trim($str);
	}
	
	public static function str_test_mce_link ($str)
	{
		return preg_match('/^https?:\/\/(dev-www|www|m)\.(17yangyu|bilibili)\.com/i', $str);
	}
	
	public static function str_test_mce_video ($str)
	{
		return preg_match('/(yangyu|youku|iqiyi|qq)\.com/i', $str);
	}
	
	public static function str_test_empty ($str)
	{
		if (is_numeric($str)) return false; // escape number
		if (is_string($str)) $str = trim($str);
		return empty($str);
	}
	
	public static function str_test_mail ($str)
	{
		return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $str);
	}
	
	public static function str_test_number ($str)
	{
		return is_numeric($str);
	}

	/**
	 * 验证身份证号
	 * @param $vStr
	 * @return bool
	 */
	public static function str_test_id_card ($vStr)
	{
		$vCity = array(
			'11','12','13','14','15','21','22',
			'23','31','32','33','34','35','36',
			'37','41','42','43','44','45','46',
			'50','51','52','53','54','61','62',
			'63','64','65','71','81','82','91'
		);

		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

		if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

		$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
		$vLength = strlen($vStr);

		if ($vLength == 18)
		{
			$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
		} else {
			$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
		}

		if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		if ($vLength == 18)
		{
			$vSum = 0;

			for ($i = 17 ; $i >= 0 ; $i--)
			{
				$vSubStr = substr($vStr, 17 - $i, 1);
				$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}

			if($vSum % 11 != 1) return false;
		}

		return true;
	}
	
	public static function str_test_positive_int ($str, $max_length=20)
	{
	    $max_length -= 1;
	    return preg_match("/^[1-9]\d{0,$max_length}$/",$str);
	}
	
	public static function str_test_exchange_code ($str, $max_length=15)
	{
	    return preg_match("/^[a-zA-Z0-9]{1,$max_length}$/",$str);
	}
	
	public static function str_test_money ($str)
	{
		return preg_match('/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/', $str);
	}
	
	public static function str_test_phone ($str)
	{
		return preg_match('/^1\d{10}$/i', $str);
	}
	
	public static function str_test_passwd ($str)
	{
		//return preg_match('/^[A-Za-z0-9]{6,15}$/i', $str);
		return preg_match('/^.{5,25}$/', $str);
	}
	
	public static function str_test_version ($str)
	{
		return preg_match('/^v\w+\.*/i', $str);
	}
	
	public static function str_test_package ($str)
	{
		return preg_match('/\w+\.+/i', $str);
	}
	
	public static function str_test_url ($str)
	{
		return preg_match('/^(http:\/\/|https:\/\/)/i', $str);
	}
	
	public static function str_test_rid ($rid)
	{
		if (in_array($rid, array(1,3)) || $rid > 9) {
			return false;
		}
		return true;
	}

    public static function str_test_real_name ($str)
    {
        return preg_match("/^[\x{4e00}-\x{9fa5}]{2,7}$/u",$str);
    }

    public static function str_test_idnum ($str)
    {
        $id = strtoupper($str);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return false;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return false;
            } else {
                return true;
            }
        }
        else      //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return false;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }

    public static function str_test_acode ($str)
    {
        return preg_match("/^[a-zA-Z0-9]{12}$/",$str);
    }

	public static function str_parse_uri ($uri = '')
	{
		$uri = $uri ? $uri : $_SERVER['REQUEST_URI'];
		
		return parse_url(preg_replace('/\/{2,}/i', '/', trim($uri)));
	}
	
	public static function str_current_url ()
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") $pageURL .= "s";
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	public static function str_json_encode ($value)
	{
		if (version_compare(PHP_VERSION,'5.4.0','<')) {
			$str = json_encode($value);
			$str = preg_replace_callback(
				"#\\\u([0-9a-f]{4})#i",
				function ($matchs) {
					return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
				},
				$str
			);
			return $str;
		} else {
			return json_encode($value, JSON_UNESCAPED_UNICODE);
		}
	}
	
	public static function str_sec2min ($sec)
	{
		return floor($sec/60)."' ".($sec%60);
	}
	
	/**
	 * 20150304 -> 2015-03-04
	 */
	public static function str_format_date($str)
	{
		return date('Y-m-d',strtotime($str));
	}
	
	public static function str_format_video($str)
	{
		$str = trim($str);
		if (preg_match('/^<iframe/i', $str)) {
			preg_match('/src\s*=\s*[\'"]([^\'"]+)[\'"]/i', $str, $res);
			$video_url = isset($res[1]) ? trim($res[1]) : '';
			if ($video_url) {
				return $video_url;
			}
		}
		if (preg_match('/^https?:/i', $str)) {
			$video_url = trim($str);
			return $video_url;
		}
		return false;
	}
	
	public static function str_format_pubkey($str)
	{
	    return "-----BEGIN PUBLIC KEY-----\r\n" . chunk_split($str, 64) . "-----END PUBLIC KEY-----";
	}
	
	public static function str_format_prikey($str)
	{
	    return "-----BEGIN RSA PRIVATE KEY-----\r\n" . chunk_split($str, 64) . "-----END RSA PRIVATE KEY-----";
	}
	
	public static function validate_mail ($email)
	{
	    $res = dns_get_record(array_pop(explode("@",$email)), DNS_MX);
	    return $res ? true : false;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// file function
	
	public static function copy_dir ($src_dir, $dst_dir)
	{
	    if (is_file($dst_dir)) {
	        echo $dst_dir . ' is not a directory.';
	        return;
	    }
	    if (!file_exists($dst_dir)) {
	        mkdir($dst_dir, 0777, true);
	    }
	    
	    if ($handle = opendir($src_dir)) {
	        while ($filename = readdir($handle)) {
	            if ($filename !='.' && $filename!='..') {
	                $subsrcfile = $src_dir . '/' . $filename;
	                $subtofile = $dst_dir . '/' . $filename;
	                if (is_dir($subsrcfile)) {
	                    self::copy_dir($subsrcfile, $subtofile); // recursive
	                }
	                if (is_file($subsrcfile)) {
	                    copy($subsrcfile, $subtofile);
	                }
	            }
	        }
	        closedir($handle);
	    }
	}
	
	public function copy_file ($src_path, $dst_path)
	{
	    $dst_dir =  dirname($dst_path);
	    if (!is_dir($dst_dir)) {
	        mkdir($dst_dir, 0777, true);
	    }
	    if (copy($src_path, $dst_path)) {
	        return true;
	    }
	    return false;
	}
	
	public static function cdn_url2dir ($path)
	{
		$cdn_dir = cfg('app.upload.pics.dir');
		$cdn_url = cfg('app.upload.pics.url');
		return str_replace($cdn_url, $cdn_dir, $path);
	}
	
	public static function cdn_dir2url ($dir)
	{
		$cdn_dir = cfg('app.upload.pics.dir');
		$cdn_url = cfg('app.upload.pics.url');
		return str_replace($cdn_dir, $cdn_url, $path);
	}
	
	public static function cdn_rename_file ($file)
	{
		$suffix = date('YmdHis');
		$pinfo = pathinfo($file);
		return $pinfo['filename'].'_'.$suffix.'.'.$pinfo['extension'];
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// time function
	
	public static function range_days ($sday, $eday)
	{
		$days = array();
		$stime = strtotime($sday);
		$etime = strtotime($eday);
		if ($stime && $etime) {
			while ($stime <= $etime) {
				$days[] = date('Ymd', $stime);
				$stime = $stime + 24 * 3600;
			}
		}
		return $days;
	}
	
	public static function range_months ($smonth, $emonth)
	{
		$months = array();
		$stime = strtotime($smonth);
		$etime = strtotime($emonth);
		if ($stime && $etime) {
			while ($stime <= $etime) {
				$months[] = date('Ym', $stime);
				$stime = strtotime('+1 month' ,$stime);
			}
		}
		return $months;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// curl function
	
	public static function curl_get ($params = array())
	{
		$uri = isset($params['uri']) ? $params['uri'] : '';
		$header = isset($params['header']) ? $params['header'] : '';
		$cookie = isset($params['cookie']) ? $params['cookie'] : '';
		$timeout = isset($params['timeout']) ? $params['timeout'] : 10;
		$debug = isset($params['debug']) ? $params['debug'] : false;
		
		if (!$uri) return false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');
		
		if (is_array($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        
		if ($debug) {
		    curl_setopt($ch, CURLOPT_HEADER, TRUE);
		    curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
		}
		
		$res = curl_exec($ch);
		
		if ($debug) {
		    $request_header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		    print_r($request_header);
		}
		
		curl_close($ch);
		
		return $res;
	}
	
	public static function curl_post ($params = array())
	{
		$uri = isset($params['uri']) ? $params['uri'] : '';
		$data = isset($params['data']) ? $params['data'] : '';
		$header = isset($params['header']) ? $params['header'] : '';
		$cookie = isset($params['cookie']) ? $params['cookie'] : '';
		$timeout = isset($params['timeout']) ? $params['timeout'] : 10;
		$debug = isset($params['debug']) ? $params['debug'] : false;
		
		if (!$uri) return false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');
		
		if (is_array($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        
		if (is_array($data)) $data = http_build_query($data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		if ($debug) {
		    curl_setopt($ch, CURLOPT_HEADER, TRUE);
		    curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
		}
		
		$res = curl_exec($ch);
		
		if ($debug) {
		    $request_header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		    print_r($request_header);
		}
		
		curl_close($ch);
		
		return $res;
	}
	
	public static function curl_rest ($params = array())
	{
	    $uri = isset($params['uri']) ? $params['uri'] : '';
	    $data = isset($params['data']) ? $params['data'] : '';
	    $header = isset($params['header']) ? $params['header'] : '';
	    $cookie = isset($params['cookie']) ? $params['cookie'] : '';
	    $method = isset($params['method']) ? $params['method'] : 'GET';
	    $timeout = isset($params['timeout']) ? $params['timeout'] : 10;
	    $debug = isset($params['debug']) ? $params['debug'] : false;
	    
	    if (!$uri) return false;
	    
	    if (is_array($data)) $data = http_build_query($data);
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $uri);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');
        
	    if (is_array($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        
	    switch($method) {
	        case 'GET':
	            break;
	        case 'POST':
	            if (is_array($data)) $data = http_build_query($data);
	            curl_setopt($ch, CURLOPT_POST, true);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	            break;
	        case 'PUT':
	            if (is_array($data)) $data = http_build_query($data);
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	            break;
	        case 'DELETE':
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	            break;
	    }
	    
	    if ($debug) {
	        curl_setopt($ch, CURLOPT_HEADER, TRUE);
	        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
	    }
	    
	    $res = curl_exec($ch);
	    
	    if ($debug) {
	        $request_header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
	        print_r($request_header);
	    }
	    
	    curl_close($ch);
	    
	    return $res;
	}
	
	public function curl_image ($src, $dst)
	{
		if (!$src || !$dst) return false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $src);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');
		curl_setopt($ch, CURLOPT_REFERER, '');
		
		$image_data = curl_exec($ch);
		if (!$image_data) {
			self::core_log('curl_image_error:'.curl_error($ch));
		}
		curl_close($ch);
		
		if ($image_data) {
			file_put_contents($dst, $image_data);
			return true;
		}
		
		return false;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// crypt function
	
	public function crypt_3des_encrypt ($str, $key)
	{
		if ($str && $key) {
			require_once "Crypt/3DES.php";
			$des = new Crypt_3DES($key);
			return $des->encrypt($str);
		}
		return false;
	}
	
	public function crypt_3des_decrypt ($str, $key)
	{
		if ($str && $key) {
			require_once "Crypt/3DES.php";
			$des = new Crypt_3DES($key);
			return $des->decrypt($str);
		}
		return false;
	}
	
	public function crypt_rsa_encrypt ($str)
	{
		if ($str) {
			require_once "Crypt/RSA.php";
			$rsa = new Crypt_RSA();
			$rsa->loadKey(cfg('core.publickey'));
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			return base64_encode($rsa->encrypt($str));
		}
		return false;
	}
	
	public function crypt_rsa_decrypt ($str)
	{
		if ($str) {
			require_once "Crypt/RSA.php";
			$rsa = new Crypt_RSA();
			$rsa->loadKey(cfg('core.privatekey'));
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$text = $rsa->decrypt(base64_decode($str));
			return $text;
		}
		return false;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// db function
	
	public static function db_repl_wait ()
	{
		usleep(500000); // 0.5 sec
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// core function
	
	public static function core_is_weixin ()
	{
// 	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
// 	        return true;
// 	    }
// 	    return false;
	    
	    return self::core_is_weixin_h5();
	}
	
	public static function core_is_weixin_h5 ()
	{
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false &&
	        strpos($_SERVER['HTTP_USER_AGENT'], 'webview') === false
	        ){
	        return true;
	    }
	    return false;
	}
	
	public static function core_is_weixin_webview ()
	{
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false &&
	        strpos($_SERVER['HTTP_USER_AGENT'], 'webview') !== false
	        ){
	        return true;
	    }
	    return false;
	}
	
	public static function core_is_ios ()
	{
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (strpos(strtolower($agent), 'iphone') ||
			strpos(strtolower($agent), 'ipad')) {
			return true;
		}
		return false;
	}
	
	public static function core_is_app ()
	{
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (strpos(strtolower($agent), 'yangyu')) {
			return true;
		}
		return false;
	}
	
	public static function core_is_mobile()
	{
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		$mobile_browser = '0';
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
			$mobile_browser++;
		if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
			$mobile_browser++;
		if(isset($_SERVER['HTTP_PROFILE']))
			$mobile_browser++;
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
		$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda','xda-'
		);
		if(in_array($mobile_ua, $mobile_agents))
			$mobile_browser++;
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
			$mobile_browser++;
		// Pre-final check to reset everything if the user is on Windows
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
			$mobile_browser=0;
		// But WP7 is also Windows, with a slightly different characteristic
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
			$mobile_browser++;
		if($mobile_browser>0)
			return true;
		else
			return false;
	}

	public static function core_sign ($params, $key)
	{
		if (is_array($params) && $key !== '') {
			ksort($params);
			$pairs = array();
			foreach($params as $k => $v) {
				$pairs[] = $k . '=' . $v;
			}
			$str = implode('&', $pairs);

            //test
            //self::core_log('[core_sign] str:' . $str . '#' . $key);

			$sign = strtoupper(md5($str . '#' . $key));
			return $sign;
		}
		return '';
	}
	
	public static function core_uuid ()
	{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12);
		return $uuid;
	}
	
	public static function core_mtime ()
	{
		$time1 = explode(' ', microtime());
		$time1 = $time1[1] . ($time1[0] * 1000);
		$time2 = explode('.', $time1);
		return $time2[0];
	}
	
	public static function core_id_order ()
	{
		$today = date('Ymd');
		$stime = strtotime($today) * 1000;
		$ntime = microtime(true) * 1000;
		$mtime = bcsub($ntime, $stime, 0);
		$mtime = substr(str_pad($mtime, 8, 0, STR_PAD_LEFT), -8);
		return $today . $mtime . mt_rand(10,99);
	}
	
	public static function core_id_user ()
	{
	    if (!class_exists('App_Dao')) return 0;
	
	    return App_Dao::load('Login_AccountId')->generateId();
	}

	public static function core_id_fz ($aid)
    {
        if (!class_exists('App_Dao')) return 0;

        $micro_arr = explode('.', microtime(true));
        $dtime = $micro_arr[0];
        $micro = substr($micro_arr[1] . '0000', 0, 4);

        $sub_aid = substr($aid, -1);
        //$accid = App_Dao::load('Login_AccountId')->read($aid);

        $fzid = 'fz' . $dtime . $micro . $sub_aid . mt_rand(0,9);

        return  $fzid;
    }

//    public static function core_appid_wx ($gid)
//    {
//        $appid = '';
//        if (!class_exists('App_Dao')) return 0;
//        $game = App_Dao::load('Core_Game')->read($gid);
//        if($game)
//        {
//            $configs = json_decode($game['configs'], true);
//            $appid = $configs['api_wxpay_appid'];
//        }
//
//        return $appid;
//    }

//    public static function core_game_configs ($gid)
//    {
//        $configs = array();
//        if (!class_exists('App_Dao')) return 0;
//        $game = App_Dao::load('Core_Game')->read($gid);
//        if($game)
//        {
//            $configs = json_decode($game['configs'], true);
//        }
//
//        return $configs;
//    }

	public static function core_mul_factor ()
	{
		$sday = strtotime('2017-01-01');
		$snum = intval((time() - $sday) / 86400);
		return $snum;
	}

	public static function core_log ($msg)
	{
		if (is_array($msg)) $msg = json_encode($msg);
		if (!is_dir(__LOG_DIR)) mkdir(__LOG_DIR, 0777, true);
		error_log("[".date('Y-m-d H:i:s')."] ".$msg."\r\n", 3, __LOG_DIR."/core.log");
	}
	
	public static function site_log ($msg)
	{
	    if (is_array($msg)) $msg = json_encode($msg);
	    if (!is_dir(__SITE_LOG_DIR)) mkdir(__SITE_LOG_DIR, 0777, true);
	    error_log("[".date('Y-m-d H:i:s')."] ".$msg."\r\n", 3, __SITE_LOG_DIR."/core.log");
	}
	
	public static function core_autokey ()
	{
		return md5(md5(self::core_uuid()));
	}
	
	public static function core_device_id ()
	{
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		if ($ua) return md5($ua);
		return '';
	}
	
	public static function core_file_ext ($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}
	
	public static function core_file_init ($dir, $url, $ext, $file = '')
	{
		$file_dir = '';
		$file_url = '';
	
		$file_name = $file ? md5($file) : md5(time().rand(123456,999999));
		$file_path = $file_name{0} . '/' . $file_name{1} . '/' . $file_name{2};
	
		$file_pdir = $dir . '/' . $file_path;
		if (!is_dir($file_pdir)) {
			mkdir($file_pdir, 0777, true);
		}
	
		$file_dir = $dir . $file_path . '/' . $file_name . '.' . $ext;
		$file_url = $url . $file_path . '/' . $file_name . '.' . $ext;
	
		return array(
			'dir' => $file_dir,
			'url' => $file_url,
		);
	}
	
	public static function core_file_move ($src, $dst)
	{
		if (move_uploaded_file($src, $dst)) {
		    $file_ext = self::core_file_ext($src);
		    if (in_array($file_ext, array('jpg','png','gif','bmp','jpeg'))) {
		        // do compress for image
		        require_once 'Core/Util/Image.php';
		        $res = Core_Util_Image::compress($dst, $dst);
		        if ($res) {
		            return true;
		        }
		    } else {
		        // do nothing for file
		        return true;
		    }
		}
		return false;
	}
	
	public static function core_file_save ($data)
	{
		$base64_image = str_replace(' ', '+', $data);
		if (preg_match('/^data:\s*image\/(\w+);base64,/', $base64_image, $result)) {
			$image_ext = ($result[1] == 'png') ? 'png' : 'jpg';
			$image_data_arr = explode(',', $data);
			if (sizeof($image_data_arr) > 1) {
				$cdn_dir = cfg('app.upload.pics.dir');
				$cdn_url = cfg('app.upload.pics.url');
				$upload_file_data = Core_Util::core_file_init($cdn_dir, $cdn_url, $image_ext);
				$image_data_str = base64_decode($image_data_arr[1]);
				if (file_put_contents($upload_file_data['dir'], $image_data_str)) {
					return $upload_file_data;
				}
			}
		}
		return false;
	}
	
	public static function core_getid3 ($file)
	{
		if (is_file($file)) {
			require_once 'Core/Util/getid3/getid3.php';
			$getID3 = new getID3;
			$getID3->setOption(array('encoding' => 'UTF-8'));
			return $getID3->analyze($file);
		}
		return false;
	}
	
	///////////////////////////////////////////////////////////////////////////////////////
	// app function
	
	public static function app_year_list ()
	{
		// start from years 2014
		return array_reverse(range(2014, date('Y')));
	}
	
	public static function app_month_list ($y, $format = 0)
	{
		switch ($format) {
			case 0:
				$months = range(1, 12);
				return array_map(function($s){
					return sprintf("%02d", $s);
				}, $months);
			case 1:
				$smonth = $y . '01';
				$emonth = $y . '12';
				return range($smonth, $emonth);
		}
	}
	
	public static function app_day_list ($y, $m, $format = 0)
	{
		switch ($format) {
			case 0:
				$d = date('t', mktime(0, 0, 0, $m, 1, $y));
				$days = range(1, $d);
				return array_map(function($s){
					return sprintf("%02d", $s);
				}, $days);
			case 1:
				$d = date('t', mktime(0, 0, 0, $m, 1, $y));
				$sday = $y . sprintf("%02d", $m) . '01';
				$eday = $y . sprintf("%02d", $m) . sprintf("%02d", $d);
				return range($sday, $eday);
	
		}
	}
	
	public static function app_day_range ($sday, $eday)
	{
		$days = array();
		$stime = strtotime($sday);
		$etime = strtotime($eday);
		if ($stime && $etime) {
			while ($stime <= $etime) {
				$days[] = date('Ymd', $stime);
				$stime = $stime + 24 * 3600;
			}
		}
		return $days;
	}
	
	public static function app_month_range ($smonth, $emonth)
	{
		$months = array();
		$stime = strtotime($smonth);
		$etime = strtotime($emonth);
		if ($stime && $etime) {
			while ($stime <= $etime) {
				$months[] = date('Ym', $stime);
				$stime = strtotime('+1 month' ,$stime);
			}
		}
		return $months;
	}
	
	public static function app_hour_range ($shour, $ehour, $interval = 10)
	{
		$step = 60 / $interval;
		$smin = 0 + $shour * $step;
		$emin = $step * $ehour;
		return range($smin, $emin);
	}
	
	public static function app_hour_value ($dtime = 0, $interval = 10)
	{
		$stime = strtotime(date('Y-m-d'));
		$etime = $dtime ? $dtime : time();
		$value = floor(($etime - $stime) / 60 / $interval);
		return $value;
	}
	
	public static function app_ajax_result ($err, $msg, $data = '')
	{
		$result = array(
			'err'	=> $err,
			'msg'	=> $msg,
			'data'	=> $data,
		);
		echo json_encode($result);
		exit;
	}
	
	public static function app_parse_rule ($rule)
	{
		return str_replace(array('^','$','/','*'), array('','','\/','(.*?)'), $rule);
	}
	
	public static function app_parse_tags ($tags)
	{
		return array_filter(preg_split('/[,]+/i', str_replace('，',',',$tags)));
	}
	
	public static function app_icon_face ($url)
	{
		// get static host
		$shost = cfg('app.cdn.url');
		// default face
		if (is_numeric($url) && $url < 10) {
			return $shost . '/img/face/' . $url . '.png';
		}
		// unknown face
		if (!$url || !preg_match('/^http:/i', $url)) {
			return $shost . '/img/no_avatar.png';
		}
		// upload face
		return $url;
	}
	
	public static function app_icon_pic ($url)
	{
		// get static host
		$shost = cfg('app.cdn.url');
		// unknown pic
		if (!$url || !preg_match('/^http:/i', $url)) {
			return $shost . '/img/no_image.png';;
		}
		return $url;
	}
	
	/**
	 * 创建邀请码
	 * @param string $phone
	 * @return string
	 */
	public static function app_inviter_code ($phone)
	{
	    $inviter_code = substr($phone, 3);
	    if (class_exists('App_Dao')) {
	        do{
	            $member = App_Dao::load('Core_CoreMember')->read($inviter_code, 'inviter_code');
	            if(!$member){
	                $flg = 0;
	            }else{
	                $flg = 1;
	                
	                $first_code = substr($inviter_code, 0, 1);
	                if(is_numeric($first_code)){
	                    $inviter_code = 'a'.$inviter_code;
	                }else{
	                    $fc_ascii = ord($first_code);
	                    $first_code = chr($fc_ascii + 1);
	                    $inviter_code = $first_code.substr($inviter_code, 1);
	                }
	            }
	        }while($flg);
	    }
	        
		return $inviter_code;
	}
	
	/**
	 * 显示数量
	 * @param unknown $num
	 * @param number $long
	 */
	public static function show_num ($num, $long=2)
	{
	    if(strlen($num) <= $long){
	        return $num;
	        
	    }else{
	        $num = '';
	        for($i=0;$i<$long;$i++){
	            $num .= '9';
	        }
	        $num .= '+';
	        
	        return $num;
	    }
	}
	
	/**
	 * 格式化时间长度
	 * @param unknown $ctime
	 * @return string
	 */
	public static function format_time_long ($ctime)
	{
	    $long = time() - $ctime;
	    if($long < 60){//1分钟
	        $time_des = '刚刚';
	    }elseif($long < 3600){//1小时
	        $time_des = intval($long/60).'分钟前';
	    }elseif($long < 3600*24){//1天
	        $time_des = intval($long/3600).'小时前';
	    }elseif($long < 3600*24*30){//1月
	        $time_des = intval($long/(3600*24)).'天前';
	    }elseif($long < 3600*24*30*12){//1年
	        $time_des = intval($long/(3600*24*30)).'月前';
	    }else{
	        $time_des = intval($long/(3600*24*30*12)).'年前';
	    }
	     
	    return $time_des;
	}
	
	/**
	 * 格式化秒数
	 * @param unknown $seconds
	 * @return multitype:
	 */
	public static function format_second ($seconds)
	{
	    
	    $time = explode(' ', gmstrftime('%j %H %M %S', $seconds));
	    $time[0] -= 1;
	    
	    return $time;
	}
	
	/**
	 * 格式化分享文字
	 * @param unknown $str
	 */
	public static function format_share_txt ($str)
	{
		$share_txt = Core_Util::str_cut(Core_Util::str_strip_tags($str),100);
		$share_txt = str_replace(array("\r","\n"),'',$share_txt);
		return $share_txt;
	}
	
	/**
	 * 格式化手机号
	 */
	public static function mosaic_phone ($phone, $mosaic_long = 4, $offset = 0)
	{
	    $long = intval($mosaic_long);
	    
	    $mosaic = '***********';
	    if ($long <= 0) {
	        return $phone;
	    } else if ($long >= 11) {
	        return $mosaic;
	    } else {
	        if ($offset) {
	        	$head_long = $offset;
	        	$tail_long = $head_long + $mosaic_long - 11;
	        } else {
	        	$head_long = bcdiv(11 - $mosaic_long, 2, 0);
	        	$tail_long = $head_long + $mosaic_long - 11;
	        }
	        $mosaic_phone = substr($phone, 0, $head_long).substr($mosaic, 0, $mosaic_long).substr($phone, $tail_long);
	        return $mosaic_phone;
	    }
	}

	/**
     * 格式化邮箱
     */
    public static function mosaic_email ($email, $mosaic_long = 4, $offset = 0)
    {
        if(!self::str_test_mail($email)) return;

        $email_array = explode("@", $email);
        // //邮箱前缀
        if (strlen($email_array[0]) == 1) {
            $prevfix = $email_array[0];
        }elseif (strlen($email_array[0]) < 5) {
            $prevfix = substr($email, 0, 1);
        }elseif (strlen($email_array[0]) == 5){
            $prevfix = substr($email, 0, 2);
        }else{
            $prevfix = substr($email, 0, 3);
        }

        // $count = 0;
        // $str = preg_replace('/([\d\w+_.-]{0,100})@/', '***@', $email, -1, $count);
        // echo $str;die;
        $e = '***@';
        if (strlen($email_array[0]) == 3) $e = '**@';
        if (strlen($email_array[0]) == 2) $e = '*@';
        if (strlen($email_array[0]) == 1) $e = '@';
        $rs = $prevfix . $e . $email_array[1];

        return $rs;
    }

    /**
     * 格式化姓名
     */
    public static function mosaic_name ($name){
        $xing = '';
        for ( $i = 1; $i < mb_strlen($name); $i++ ){
            $xing .= '*';
        }
        return mb_substr($name, 0, 1) . $xing;
    }

    /**
     * 格式化身份证号码
     */
    public static function mosaic_id_number ($id){
        $xing = '';
        for ($i = 1; $i < strlen($id) - 7; $i ++){
            $xing .= '*';
        }
        return substr($id, 0, 4) . $xing . substr($id, -3, 3);
    }

}