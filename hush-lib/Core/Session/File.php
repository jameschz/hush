<?php

class Core_Session_File implements SessionHandlerInterface
{
    private $savePath;
    
    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        return true;
    }
    
    public function close()
    {
        return true;
    }
    
    public function read($id)
    {
        $json = @file_get_contents("$this->savePath/sess_$id");
        if ($json) {
            $_SESSION = json_decode($json, true);
            if (isset($_SESSION) && !empty($_SESSION) && $_SESSION != null) {
                return session_encode();
            }
        }
        return '';
    }
    
    public function write($id, $data)
    {
        $json = json_encode($_SESSION); // save by json
        return file_put_contents("$this->savePath/sess_$id", $json) === false ? false : true;
    }
    
    public function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }
        
        return true;
    }
    
    public function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }
        
        return true;
    }
}