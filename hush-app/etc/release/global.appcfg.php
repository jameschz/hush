<?php
//////////////////////////////////////////////////////////////////////////////////////////
// session config

if (__HUSH_ENV == 'release') {
	// TODO : add session handler for release
    require_once 'Core/Session.php';
    Core_Session::init(array(
        'type' => 'redis',
        'name' => 'hush_sid',
        'life' => 24 * 3600,
    ));
}

//////////////////////////////////////////////////////////////////////////////////////////
// server config

$_APPCFG['core.cache.redis'] = array(
    'authkey' => '', // pass
	'timeout' => 10,
	'cluster' => array(
	    'default' => array('127.0.0.1:6379:1'),
	    '^token_*' => array('127.0.0.1:6379:0'),
	),
);

//////////////////////////////////////////////////////////////////////////////////////////
// hosts config

// app host
$_APPCFG['app.host'] = 'http://hush-app.focusgames.cn';
$_APPCFG['app.host.sdk'] = 'http://hush-app-sdk.focusgames.cn';
$_APPCFG['app.host.admin'] = 'http://hush-app-admin.focusgames.cn';

// static host
$_APPCFG['app.host.s'] = '//hush-app.focusgames.cn';

// cdn host
$_APPCFG['app.cdn.url'] = '//hush-cdn.focusgames.cn/hush-app/'.__APP_SITE;
$_APPCFG['app.cdn.dir'] = __CDN_DIR.'/'.__APP_SITE;

// upload host
$_APPCFG['app.upload.host'] = $_APPCFG['app.host.admin'];
$_APPCFG['app.upload.pics.dir'] = $_APPCFG['app.cdn.dir'] . '/upload/';
$_APPCFG['app.upload.pics.url'] = $_APPCFG['app.cdn.url'] . '/upload/';
$_APPCFG['app.upload.pack.dir'] = $_APPCFG['app.cdn.dir'] . '/pack/';
$_APPCFG['app.upload.pack.url'] = $_APPCFG['app.cdn.url'] . '/pack/';
