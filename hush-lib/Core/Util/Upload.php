<?php
require_once 'Core/Util.php';

class Core_Util_Upload
{
	public static function pic4mce ($from = '')
	{
// 		$upload_host = cfg('app.upload.host');
		$upload_size = 1024 * 1024 * 8; // 原图不能大于8M，否则压缩了也很大
		$upload_size_gif = 3 * 1024 * 1024; // GIF图不能大于3M
		$upload_pics_dir = cfg('app.upload.pics.dir');
		$upload_pics_url = cfg('app.upload.pics.url');
		if (!is_dir($upload_pics_dir)) {
			mkdir($upload_pics_dir, 0777, true);
		}
		
		// init upload params
		$upload_file = $_FILES['Filedata']['tmp_name'];
		$upload_file_name = $_FILES['Filedata']['name'];
		$upload_file_ext = Core_Util::core_file_ext($upload_file_name);
		
		// init result
		$result['err'] = 0;
		$result['msg'] = '';
		
		// validate file
		if (!$upload_file) {
			$result['err'] = 2;
			$result['msg'] = '图片不存在';
		}
		if ($_FILES['Filedata']['size'] > $upload_size ||
			$upload_file_ext == 'gif' && $_FILES['Filedata']['size'] > $upload_size_gif) {
			$result['err'] = 3;
			$result['msg'] = '图片大小超限';
		}
		if (!$upload_file_ext) {
			$result['err'] = 4;
			$result['msg'] = '图片上传失败';
		}
		
		// rename file and upload
		if ($result['err'] == 0) {
			$upload_file_data = Core_Util::core_file_init($upload_pics_dir, $upload_pics_url, $upload_file_ext);
			if ($upload_file_data) {
				if (!Core_Util::core_file_move($upload_file, $upload_file_data['dir'])) {
					$result['err'] = 5;
					$result['msg'] = '图片上传失败';
				} else {
					$result['err'] = 0;
					$result['msg'] = $upload_file_data['url'];
				}
			}
		}
		
		// print result
		if ($result['err']) {
			exit("<script>alert('{$result['msg']}');</script>");
		} else {
			exit("<script>parent.$('.mce-btn.mce-open').parent().find('.mce-textbox').val('{$result['msg']}');</script>");
		}
	}
	
	public static function pic4web ($from = '')
	{
// 		$upload_host = cfg('app.upload.host');
		$upload_size = cfg('app.upload.filesize');
		$upload_pics_dir = cfg('app.upload.pics.dir');
		$upload_pics_url = cfg('app.upload.pics.url');
		if (!is_dir($upload_pics_dir)) {
			mkdir($upload_pics_dir, 0777, true);
		}
		
		// init upload params
		$upload_file = $_FILES['Filedata']['tmp_name'];
		$upload_file_name = $_FILES['Filedata']['name'];
		$upload_file_ext = Core_Util::core_file_ext($upload_file_name);
		
		// init result
		$result['err'] = 0;
		$result['msg'] = '';
		
		// validate file
		if (!$upload_file) {
			$result['err'] = 2;
			$result['msg'] = '图片不存在';
		}
		if ($_FILES['Filedata']['size'] > $upload_size) {
			$result['err'] = 3;
			$result['msg'] = '图片大小超限';
		}
		if (!$upload_file_ext) {
			$result['err'] = 4;
			$result['msg'] = '图片上传失败';
		}
		
		// rename file and upload
		if ($result['err'] == 0) {
			$upload_file_data = Core_Util::core_file_init($upload_pics_dir, $upload_pics_url, $upload_file_ext);
			if ($upload_file_data) {
				if (!Core_Util::core_file_move($upload_file, $upload_file_data['dir'])) {
					$result['err'] = 5;
					$result['msg'] = '图片上传失败';
				} else {
					$result['err'] = 0;
					$result['msg'] = $upload_file_data['url'];
				}
			}
		}
		
		// print result
		ajax($result['err'], $result['msg']);
	}
	
	/**
	 * Upload Logic for sdk
	 */
	public static function pic4sdk ($filename='file0', $from = '')
	{
// 		$upload_host = cfg('app.upload.host');
		$upload_size = cfg('app.upload.filesize');
		$upload_pics_dir = cfg('app.upload.pics.dir');
		$upload_pics_url = cfg('app.upload.pics.url');
		if (!is_dir($upload_pics_dir)) {
			mkdir($upload_pics_dir, 0777, true);
		}
		
		// init upload params
		$upload_file = $_FILES[$filename]['tmp_name'];
		$upload_file_name = $_FILES[$filename]['name'];
		$upload_file_ext = Core_Util::core_file_ext($upload_file_name);
		
		// validate file
		if (!$upload_file) {
			throw new Exception(ERR_UPLOAD_FAIL);
			return false;
		}
		if ($_FILES[$filename]['size'] > $upload_size) {
			throw new Exception(ERR_UPLOAD_FILESIZE);
			return false;
		}
		if (!$upload_file_ext) {
			throw new Exception(ERR_UPLOAD_FILEEXT);
			return false;
		}
		
		// rename file and upload
		$upload_file_data = Core_Util::core_file_init($upload_pics_dir, $upload_pics_url, $upload_file_ext);
		if ($upload_file_data) {
			if (!Core_Util::core_file_move($upload_file, $upload_file_data['dir'])) {
				throw new Exception(ERR_UPLOAD_COPY);
				return false;
			} else {
				return array(
					'url' => $upload_file_data['url'],
				);
			}
		}
		
		return false;
	}
}