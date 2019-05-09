<?php

class Core_Session
{
    /**
     * Session Type
     */
    static $type = null;
    
    /**
     * Session Handler
     */
    static $handler = null;
    
    /**
     * Initialize session system
     * TYPE : files / redis
     * @param array $config
     * @return App_Dao
     */
    public static function init(array $config)
    {
        // init conf
        $sessionType = isset($config['type']) ? $config['type'] : 'files';
        $sessionName = isset($config['name']) ? $config['name'] : 'hush_sid';
        $sessionLife = isset($config['life']) ? $config['life'] : 1440;
        $sessionPath = isset($config['path']) ? $config['path'] : '';
        
        // init type
        if (!self::$type) {
            self::$type = $sessionType;
        }
        
        // init handler
        switch (self::$type) {
            case 'files':
                if ($sessionPath) {
                    if (!is_dir($sessionPath)) {
                        mkdir($sessionPath, 0777);
                    }
                    ini_set("session.save_path", $sessionPath);
                }
                ini_set("session.name", $sessionName);
                ini_set('session.gc_maxlifetime', $sessionLife);
                if (!self::$handler) {
                    require_once 'Core/Session/File.php';
                    self::$handler = new Core_Session_File();
                }
                break;
            case 'redis':
                ini_set("session.name", $sessionName);
                ini_set('session.gc_maxlifetime', $sessionLife);
                if (!self::$handler) {
                    require_once 'Core/Session/Redis.php';
                    self::$handler = new Core_Session_Redis();
                }
                break;
        }
        
        if (self::$handler) {
            return session_set_save_handler(self::$handler, true);
        }
        
        return false;
    }

    /**
     * Session start logic
     * @return boolean
     */
    public static function start()
    {
        return session_start();
    }
}