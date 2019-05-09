<?php
// check
class Core_Util_Image
{
	public static function compress($imgsrc,$imgdst){
		list($width,$height,$type)=getimagesize($imgsrc);
		$scale = $width/$height;
		$new_width = $width>1080?1080:$width;
		$new_height = $new_width/$scale;
		switch($type){
			case 1:
				$giftype=self::is_dyn_graph($imgsrc);
				if(!$giftype){
					$image_wp=imagecreatetruecolor($new_width, $new_height);
					$image = imagecreatefromgif($imgsrc);
					imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					imagejpeg($image_wp, $imgdst,80);
					imagedestroy($image_wp);
				}
				return true;
			case 2:
				$image_wp=imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromjpeg($imgsrc);
				imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagejpeg($image_wp, $imgdst,80);
				imagedestroy($image_wp);
				return true;
			case 3:
				$image_wp=imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefrompng($imgsrc);
				imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagejpeg($image_wp, $imgdst,80);
				imagedestroy($image_wp);
				return true;
		}
		
		return false;
	}
	
	// 检查是否是动图
	public static function is_dyn_graph($image_file){
		$fp = fopen($image_file,'rb');
		$image_head = fread($fp,1024);
		fclose($fp);
		return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?true:false;
	}
}