<?php
require_once 'Hush/Cache/Redis.php';

class Core_Session_Redis implements SessionHandlerInterface
{
    private $lifetime;
    
    public function open($savePath, $sessionName)
    {
        $this->lifetime = intval(ini_get('session.gc_maxlifetime'));
        return true;
    }
    
    public function close()
    {
        return true;
    }
    
    public function read($id)
    {
        try {
            $conf = cfg('core.cache.redis');
            $conn = Hush_Cache_Redis::getConn($conf, "token_$id");
            $_SESSION = json_decode($conn->get("token_$id"), true);
            if (isset($_SESSION) && !empty($_SESSION) && $_SESSION != null) {
                return session_encode();
            }
        } catch (Exception $e) {
            error_log('session redis : ' . $e->getMessage());
            return '';
        }
        return '';
    }
    
    public function write($id, $data)
    {
        try {
            $json = json_encode($_SESSION); // save by json
            $conf = cfg('core.cache.redis');
            $conn = Hush_Cache_Redis::getConn($conf, "token_$id");
            $conn->set("token_$id", $json, $this->lifetime);
        } catch (Exception $e) {
            error_log('session redis : ' . $e->getMessage());
            return false;
        }
        return true;
    }
    
    public function destroy($id)
    {
        try {
            $conf = cfg('core.cache.redis');
            $conn = Hush_Cache_Redis::getConn($conf, "token_$id");
            $conn->del("token_$id");
        } catch (Exception $e) {
            error_log('session redis : ' . $e->getMessage());
            return false;
        }
        return true;
    }
    
    public function gc($maxlifetime)
    {
        return true;
    }
}