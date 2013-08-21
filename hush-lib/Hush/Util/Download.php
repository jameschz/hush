<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Util_Download
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

if (!defined('__OS_WIN')) {
	define('__OS_WIN', !strncasecmp(PHP_OS, 'win', 3));
}

/**
 * @package Hush_Util
 */
class Hush_Util_Download
{
	/**
	 * Progress Callback Method
	 * 
	 * @return unknown
	 */
	private function stream_notification_callback ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max)
	{
		static $filesize = null;
		switch($notification_code) {
			case STREAM_NOTIFY_RESOLVE:
			case STREAM_NOTIFY_AUTH_REQUIRED:
			case STREAM_NOTIFY_COMPLETED:
			case STREAM_NOTIFY_FAILURE:
			case STREAM_NOTIFY_AUTH_RESULT:
				/* Ignore */
				break;
			case STREAM_NOTIFY_REDIRECTED:
				echo "Being redirected to: ", $message, "\n";
				break;
			case STREAM_NOTIFY_CONNECT:
				echo "Connected...\n";
				break;
			case STREAM_NOTIFY_FILE_SIZE_IS:
				$filesize = $bytes_max;
				echo "Filesize: ", $filesize, "\n";
				break;
			case STREAM_NOTIFY_MIME_TYPE_IS:
				echo "Mime-type: ", $message, "\n";
				break;
			case STREAM_NOTIFY_PROGRESS:
				if ($bytes_transferred > 0) {
					if (!isset($filesize)) {
						printf("\rUnknown filesize.. %2d kb done..", $bytes_transferred/1024);
					} else {
						$length = (int)(($bytes_transferred/$filesize)*100);
						if (__OS_WIN) {
							printf("\rDownloading.. %d%% (%2d/%2d kb)", $length, ($bytes_transferred/1024), $filesize/1024);
						} else {
							printf("\r[%-100s] %d%% (%2d/%2d kb)", str_repeat("=", $length). ">", $length, ($bytes_transferred/1024), $filesize/1024);
						}
					}
				}
				break;
	    }
	}
	
	/**
	 * Get all http request
	 * 
	 * @param string $down_file Download File's URL
	 * @param string $save_file Saving File's DIR
	 * @return boolean
	 */
	public function download ($down_file, $save_file)
	{
		$ctx = stream_context_create();
		stream_context_set_params($ctx, array("notification" => array($this, "stream_notification_callback")));
		
		$fp = fopen($down_file, "r", false, $ctx);
		if (is_resource($fp) && file_put_contents($save_file, $fp)) {
		    echo "\nDone!\n";
		    return true;
		}
		
		$err = error_get_last();
		echo "\nError.. ", $err["message"], "\n";
		return false;
	}
}