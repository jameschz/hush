<?php
require_once 'App/Etc/Config.php';

$_APPCFG['version'] = 'v1.0.0';

//////////////////////////////////////////////////////////////////////////////////////////
// session config

if (__HUSH_ENV == 'local') {
    // TODO : add session handler here
    require_once 'Core/Session.php';
//     $sessionPath = __SYS_DIR . '/php/session';
//     Core_Session::init(array(
//         'type' => 'files',
//         'name' => 'hush_sid',
//         'path' => $sessionPath,
//         'life' => 24 * 3600,
//     ));
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
$_APPCFG['app.host'] = 'http://hush-app';

// static host
$_APPCFG['app.host.s'] = 'http://hush-app';

// cdn host
$_APPCFG['app.cdn.url'] = 'http://hush-cdn/hush-app/'.__APP_SITE;
$_APPCFG['app.cdn.dir'] = __CDN_DIR.'/'.__APP_SITE;

// upload host
$_APPCFG['app.upload.host'] = 'http://hush-app';
$_APPCFG['app.upload.filesize'] = 1024 * 1024 * 3; // 3M
$_APPCFG['app.upload.pics.dir'] = $_APPCFG['app.cdn.dir'] . '/upload/';
$_APPCFG['app.upload.pics.url'] = $_APPCFG['app.cdn.url'] . '/upload/';
$_APPCFG['app.upload.pack.dir'] = $_APPCFG['app.cdn.dir'] . '/pack/';
$_APPCFG['app.upload.pack.url'] = $_APPCFG['app.cdn.url'] . '/pack/';

//////////////////////////////////////////////////////////////////////////////////////////
// app config

