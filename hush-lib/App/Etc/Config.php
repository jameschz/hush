<?php
//////////////////////////////////////////////////////////////////////////////////////////
// global config

$_APPCFG['version'] = 'v1.0.0';
$_APPCFG['passkey'] = 'dG28Uj@2';
$_APPCFG['passcode'] = '987321';
$_APPCFG['debug.ctx'] = true;

// langs
$_APPCFG['core.langs'] = array('中文','英文');
$_APPCFG['core.lang.code'] = array('cn','en');
$_APPCFG['core.lang.list'] = array('中文','English');
$_APPCFG['core.lang.select'] = array('选择语言','Language');

// app keys
$_APPCFG['core.publickey'] = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC77gPVS0W5QBCVl7k4I+cewwnwYRaOyMt/EXw8gJ3KNksymBiNkQMoAI5Hoojn35dFb0/ecSz68s9vH/LQRe+oGtsz1VpBBLZ2khh4Y8dlDzYxd7nAq9zSDJ6QtgrJsS7bmMUW9Da8oS/K65fwUH9b91+PJsJi6HMISUYZMD7VqQIDAQAB';
$_APPCFG['core.privatekey'] = 'MIICXQIBAAKBgQC77gPVS0W5QBCVl7k4I+cewwnwYRaOyMt/EXw8gJ3KNksymBiNkQMoAI5Hoojn35dFb0/ecSz68s9vH/LQRe+oGtsz1VpBBLZ2khh4Y8dlDzYxd7nAq9zSDJ6QtgrJsS7bmMUW9Da8oS/K65fwUH9b91+PJsJi6HMISUYZMD7VqQIDAQABAoGAS3/WyGUCMOddAkw/HB/IZWJj5s+KeXiP0I/cwo3FVoyzbzMNgipFA1gd0QeqSsVYB4wtoScEtBjCX1mNekDug4BY2XHGJL9ScrXZ8n2jJC1JGNtgVUas5/ZieBIdKGCnW3ZtvO6306PscuTp71KWxOrRSWkdrmmGe8tKKtmp6QECQQD1M9sUtI/MJvPUFuXcWYILO88et5QNeHd5ngab2O2nQhK3ZlAZwNX9mrDeRJi9oCA8gODKMYvzQ289vCnVnQSJAkEAxDSHRYcCwSYpEB8HtNR/JS4KrfAmqIqKPm0c9GOZJUJbUoQmIDJ1OKMQ4S8rdu1HPwPIRRBpXdFC0pnAzSdAIQJAIaWQ/YFdbhU2RjcyKY96wdHeNQfZ4BNUybJ+k6orylTNvq7idPY6JwewF3/wOa0cX1EMB5LO8n2haSZF01MtiQJBAJCZEdRYwtvsLrkV1U0cijOxcedspGDz8Rw+t+cYe+YsuDbg82QpK6C49Jiwdbzo8+fp9q+Hf8/HiBLDg4buVWECQQCFPm0lidYwtHhinQnTzWv0dXBv8y8jlRpFivAI8xA2Ty8CZJ00SVd66BcCs66pJkcuR40ZDl48QUwiudFZNONB';


//ios游戏防代充方案
$_APPCFG['app.game.ios.pay.limit'] = array(
    'small_amount' => 5,//小额档位每日充值次数(小额为<=30元)
    'device_new_limit' => 5, //一个账号可以在5台新设备登录
    'device_pay_limit' => 2, //一个账号可以有2台充值设备
    'device_used_limit' => 5, //一个账号可以有5台常用设备
    'device_bind_acc_pay' => 10, //一台新设备最多可为10个账号充值
    'device_login_days_limit' => 5, //15天之内，至少5天有登录，转为常用设备

);