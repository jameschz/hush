<?php
///////////////////////////////////////////////////////////////////////////////////////////////
// config settings

$GLOBALS['core.lang'] = 'cn'; // [cn|en]

///////////////////////////////////////////////////////////////////////////////////////////////
// config error codes

define('ERR_OK', '0');
define('ERR_SYS', '1');
define('ERR_NULL', '-1');
define('ERR_PARAMS', '1001');
define('ERR_DATABASE', '1002');
define('ERR_CACHE_EXP', '1003');

define('ERR_AUTH_PHONE', '2001');
define('ERR_AUTH_PASS', '2002');
define('ERR_AUTH_PHONEPASS', '2003');
define('ERR_AUTH_GESTURE', '2004');
define('ERR_AUTH_VCODE', '2005');
define('ERR_AUTH_VCODE_SEND', '2006');
define('ERR_AUTH_SOURCE', '2007');
define('ERR_AUTH_ACCOUNT_CREATE', '2008');
define('ERR_AUTH_ACCOUNT_EXIST', '2009');
define('ERR_AUTH_ACCOUNT_NOT_EXIST', '2010');
define('ERR_AUTH_INVITER_CODE', '2011');
define('ERR_AUTH_INVITER_CODE_EXIST', '2012');
define('ERR_AUTH_LOGIN_FORBID', '2013');
define('ERR_AUTH_LOGIN_FAIL', '2014');
define('ERR_AUTH_REGISTER_FAIL', '2015');
define('ERR_AUTH_SEND_SMS_FAIL', '2016');

define('ERR_USER_REALNAME', '3001');
define('ERR_USER_OLD_PASS', '3002');
define('ERR_USER_BANK_BIND', '3003');

define('ERR_HOME_PRODUCT_NOT_EXIST', '4001');
define('ERR_HOME_CONTRACT_TYPE', '4002');
define('ERR_HOME_EXPIRE_TYPE', '4003');
define('ERR_HOME_PAY_MONEY', '4004');
define('ERR_HOME_PAY_AREA', '4005');
define('ERR_HOME_PAY_LEFT', '4006');

define('ERR_ADDRESS_NOT_EXIST', '5001');
define('ERR_ADDRESS_MAX', '5002');

define('ERR_PRODUCT_STATE_ERR', '5101');
define('ERR_PRODUCT_OUT_OF_STOCK', '5102');
define('ERR_PRODUCT_STOCK_ERR', '5103');
define('ERR_PRODUCT_NOT_EXIST', '5104');
define('ERR_PRODUCT_LIKE_EXIST', '5105');

define('ERR_CART_MAX', '5201');
define('ERR_NUM_MIN', '5202');
define('ERR_EXPRESS_NOT_EXIST', '5203');

define('ERR_SHOP_NOT_EXIST', '5301');
define('ERR_SHOP_LIKE_ERR', '5302');
define('ERR_SHOP_LIKE_NOT', '5303');

define('ERR_ORDER_ALREADY_PAID', '5401');
define('ERR_ORDER_EXPIRED', '5402');

define('ERR_MEMBER_INVITED', '5501');
define('ERR_MEMBER_INVITECODE_NOT_EXIST', '5502');
define('ERR_MEMBER_INVITECODE_ERR', '5503');

define('ERR_VOTE_ERR', '5601');
define('ERR_VOTE_EXIST', '5602');

define('ERR_QUAN_FORMAT', '5701');
define('ERR_QUAN_TIME', '5702');
define('ERR_QUAN_EXIST', '5703');
define('ERR_QUAN_END', '5704');
define('ERR_QUAN_TIME_EXIST', '5705');
define('ERR_QUAN_OVER_TIME', '5706');
define('ERR_QUAN_ERR', '5707');

define('ERR_UPLOAD_FAIL', '9001');
define('ERR_UPLOAD_FILEEXT', '9002');
define('ERR_UPLOAD_FILESIZE', '9003');
define('ERR_UPLOAD_COPY', '9004');

define('ERR_ADDRESS_NOT_EMPTY', '9100');
define('ERR_REAPPLIED', '9101');
define('ERR_REAPPLY_OVER_TIME', '9102');
define('ERR_CANCEL_PR_EXIST', '9103');

define('ERR_WANT_EXIST', '9203');
define('ERR_DONOT_WANT_EXIST', '9203');

define('ERR_DONOT_LIKE_TUICOM_MYSELF', '9301');
define('ERR_DONOT_COM_MYSELF', '9302');
define('ERR_DONOT_JOIN_MYSELF', '9303');
define('ERR_YOU_NOT_COLLECT', '9304');
define('ERR_DONOT_COLLECT_MYSELF','9305');
define('ERR_IMG_TO_BIG','9306');

///////////////////////////////////////////////////////////////////////////////////////////////
// config error msgs

$GLOBALS['err.cn'] = array(
	ERR_OK => '',
	ERR_SYS => '系统错误',
	ERR_NULL => '未知错误',
	ERR_PARAMS => '参数错误',
	ERR_DATABASE => '数据库异常',
	ERR_CACHE_EXP => '缓存过期',
	
	ERR_AUTH_PHONE => '手机格式错误',
	ERR_AUTH_PASS => '密码错误',
	ERR_AUTH_PHONEPASS => '账号或密码错误',
	ERR_AUTH_GESTURE => '手势密码错误',
	ERR_AUTH_VCODE => '验证码错误',
	ERR_AUTH_VCODE_SEND => '验证码发送失败',
	ERR_AUTH_SOURCE => '注册来源错误',
	ERR_AUTH_ACCOUNT_CREATE => '创建账号失败',
	ERR_AUTH_ACCOUNT_EXIST => '账号已存在',
	ERR_AUTH_ACCOUNT_NOT_EXIST => '账号不存在',
	ERR_AUTH_INVITER_CODE => '邀请码错误',
	ERR_AUTH_INVITER_CODE_EXIST => '邀请码已存在',
	ERR_AUTH_LOGIN_FORBID => '账号被屏蔽',
	ERR_AUTH_LOGIN_FAIL => '登录失败',
	ERR_AUTH_REGISTER_FAIL => '注册失败',
	ERR_AUTH_SEND_SMS_FAIL => '发送短信失败',
	
	ERR_USER_REALNAME => '实名认证失败',
	ERR_USER_OLD_PASS => '原密码错误',
	ERR_USER_BANK_BIND => '已经绑定银行卡',
	
	ERR_HOME_PRODUCT_NOT_EXIST => '产品不存在',
	ERR_HOME_CONTRACT_TYPE => '合同类型错误',
	ERR_HOME_EXPIRE_TYPE => '还款选择错误',
	ERR_HOME_PAY_MONEY => '支付金额错误',
	ERR_HOME_PAY_AREA => '超出支付范围',
	ERR_HOME_PAY_LEFT => '购买金额不能大于剩余可购买额度',

	ERR_ADDRESS_NOT_EXIST => '收货地址不存在',
	ERR_ADDRESS_MAX => '地址数达上限10条',

	ERR_PRODUCT_STATE_ERR=> '周边已下架',
	ERR_PRODUCT_OUT_OF_STOCK=> '周边已售空',
	ERR_PRODUCT_STOCK_ERR=> '周边库存不足',
	ERR_PRODUCT_NOT_EXIST=> '周边不存在',
	ERR_PRODUCT_LIKE_EXIST=> '收藏周边已取消',

	ERR_CART_MAX=> '购物车已满',
	ERR_NUM_MIN=> '数量异常',
	ERR_EXPRESS_NOT_EXIST=> '包裹不存在',
	
	ERR_SHOP_NOT_EXIST=> '关注的池塘不存在',
	ERR_SHOP_LIKE_ERR=> '已关注',
	ERR_SHOP_LIKE_NOT=> '未关注',
	
	ERR_ORDER_ALREADY_PAID=> '已支付',
	ERR_ORDER_EXPIRED=> '支付已过期',
	
	ERR_MEMBER_INVITED=> '您已被邀请过',
	ERR_MEMBER_INVITECODE_NOT_EXIST=> '邀请码不存在',
	ERR_MEMBER_INVITECODE_ERR=> '邀请码错误',
	
	ERR_VOTE_ERR=> '投票已结束',
	ERR_VOTE_EXIST=> '已参与投票',
	
	ERR_QUAN_FORMAT=> '兑换码格式错误',
	ERR_QUAN_TIME=> '请明日再试',
	ERR_QUAN_EXIST=> '此兑换码已兑换',
	ERR_QUAN_END=> '此兑换码已兑完',
	ERR_QUAN_TIME_EXIST=> '本次活动您已兑换',
	ERR_QUAN_OVER_TIME=> '兑换码已过期',
	ERR_QUAN_ERR=> '兑换码错误',
	
	ERR_UPLOAD_FAIL => '上传失败',
	ERR_UPLOAD_FILEEXT => '文件格式错误',
	ERR_UPLOAD_FILESIZE => '上传文件太大',
	ERR_UPLOAD_COPY => '上传复制失败',

	ERR_ADDRESS_NOT_EMPTY => '收货信息不能有空',
	ERR_REAPPLIED => '申请已经提交',
	ERR_REAPPLY_OVER_TIME => '已超退换货期限',
	ERR_CANCEL_PR_EXIST => '已受理,不能取消',
	
	ERR_WANT_EXIST => '您已经想要过了',
	ERR_DONOT_WANT_EXIST => '您已经不想要了',
	ERR_DONOT_LIKE_TUICOM_MYSELF => '请你朋友来点赞吧',
	ERR_DONOT_COM_MYSELF => '请不要自己评论自己',
	ERR_DONOT_JOIN_MYSELF => '请不要关注自己',

	ERR_YOU_NOT_COLLECT => '请先收藏',
	ERR_DONOT_COLLECT_MYSELF => '请不要自己收藏自己',
	ERR_IMG_TO_BIG => '图片太大',
	
);

///////////////////////////////////////////////////////////////////////////////////////////////
// config text msgs

$GLOBALS['msg.cn'] = array(
    'global_welcome' => '欢迎光临',
);
