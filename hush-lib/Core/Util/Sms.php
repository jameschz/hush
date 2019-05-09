<?php
//use Qcloud\Sms\SmsSingleSender;

require_once 'Core/Util.php';

class Core_Util_Sms
{
	public static $channel = 'tencent';

    public static function send_code_text ($phone, $vcode, $tplid = 't1')
    {
        switch (self::$channel)
        {
            case 'tencent' :
                $res = self::tet_send_code_text ($phone, $vcode, $tplid = 't1');
                break;

            case 'ytx' :
                $res = self::ytx_send_code_text ($phone, $vcode, $tplid = 't1');
                break;

        }

        return $res;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    // 腾讯云接口
    /**
     * 发送文字验证码(腾讯云)
     * @param string $phone
     * @param string $vcode
     * @param string $tplid
     * @throws Exception
     * @return boolean
     */
    public static function tet_send_code_text ($phone, $vcode, $tplid = 't1')
    {
        require_once 'Core/Util/Sms/tencent/SmsSingleSender.php';
        require_once 'Core/Util/Sms/tencent/Tet.Config.php';

        // 指定模板ID单发短信
        try {
            //$params = array($vcode,'5');
            $params = [$vcode,TetConfig::$sms_timeout];
            $smsSign = "";
            $ssender = new SmsSingleSender(TetConfig::$appid, TetConfig::$appkey);
            $result = $ssender->sendWithParam("86", $phone, TetConfig::$template[$tplid],
                $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result, true);
            //echo $result;
            if(isset($rsp['result']) && $rsp['result'] == 0)
            {
                return true;
            } else {
                return false;
            }
        } catch(\Exception $e) {
            //echo var_dump($e);
            throw new Exception($e->getMessage());
            return false;
        }
    }

    /**
     * 发送文字验证码(腾讯云)
     * @param string $phone
     * @param string $vcode
     * @throws Exception
     * @return boolean
     */
    public static function tet_send_code_text2 ($phone, $vcode)
    {
        require_once 'Core/Util/Sms/tencent/SmsSingleSender.php';
        require_once 'Core/Util/Sms/tencent/Tet.Config.php';

        // 单发短信
        try {
            $ssender = new SmsSingleSender(TetConfig::$appid, TetConfig::$appkey);
            $result = $ssender->send(0, "86", $phone,
                "【腾讯云】您的验证码是: {$vcode}", "", "");
            $rsp = json_decode($result);
            var_dump($rsp);
            if(isset($rsp['result']) && $rsp['result'] == 0)
            {
                return true;
            } else {
                return false;
            }
        } catch(\Exception $e) {
            //echo var_dump($e);
            throw new Exception($e->getMessage());
            return false;
        }
    }


	////////////////////////////////////////////////////////////////////////////////////////////////
	// 云通讯接口
	
	/**
	 * 发送文字验证码(云通讯)
	 * @param string $phone
	 * @param string $vcode
	 * @param string $tplid
	 * @throws Exception
	 * @return boolean
	 */
	public static function ytx_send_code_text ($phone, $vcode, $tplid = '')
	{
		require_once 'Core/Util/Sms/yuntongxun/CCPRestSDK.php';
        require_once 'Core/Util/Sms/yuntongxun/Ytx.Config.php';

		// initialize
		$rest = new CCPRestSDK(YtxConfig::$serverIP, YtxConfig::$serverPort, YtxConfig::$softVersion);
		$rest->setAccount(YtxConfig::$accountSid, YtxConfig::$accountToken);
		$rest->setAppId(YtxConfig::$appId);
		
		// sending
		$data = array($vcode);
		$result = $rest->sendTemplateSMS($phone, $data, YtxConfig::$template[$tplid]);
		
		//Core_Util::core_log('[send_code_text] params:'.json_encode($result));
		
		if (!$result || $result->statusCode != 0) {
			throw new Exception($result->statusCode.':'.$result->statusMsg);
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 发送语音验证码(云通讯)
	 * @param string $phone
	 * @param string $vcode
	 * @param string $lang zh|en
	 * @throws Exception
	 * @return boolean
	 */
	public static function ytx_send_code_voice ($phone, $vcode, $lang = '')
	{
        require_once 'Core/Util/Sms/yuntongxun/CCPRestSDK.php';
        require_once 'Core/Util/Sms/yuntongxun/Ytx.Config.php';
	
		// initialize
		$rest = new CCPRestSDK(YtxConfig::$serverIP, YtxConfig::$serverPort, YtxConfig::$softVersion);
		$rest->setAccount(YtxConfig::$accountSid, YtxConfig::$accountToken);
		$rest->setAppId(YtxConfig::$appId);
	
		// sending
		$result = $rest->voiceVerify($vcode, 2, $phone, '', '', $lang, '');
		if (!$result || $result->statusCode != 0) {
			throw new Exception($result->statusCode.':'.$result->statusMsg);
			return false;
		} else {
			return true;
		}
	}
}