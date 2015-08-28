<?php
/**
 * @Desc: 注册session存储方式
 * 一致hash
 * @User: liuhui
 * @Date: 15-4-27 下午5:56 
 */
namespace Our;

class SessionHandler extends \SessionHandler{
    private static $_lifetime = null;
    private static $_handler = null;

    public function __construct(){
        self::$_lifetime = ini_get("session.gc_maxlifetime");
        self::$_handler = \Our\Halo\HaloFactory::getFactory('memcached', 'session');

        \Our\Halo\HaloLogger::INFO(__CLASS__.__METHOD__);
    }

    public function open($save_path, $session_id){
        return true;
    }

    public function close(){
        return false;
    }

    public function read($session_id){
        return self::$_handler->get(self::sessionKey($session_id));
    }

    public function write($session_id,$data){
        self::$_handler->set(self::sessionKey($session_id), $data, self::$_lifetime);

        return true;
    }

    public function destroy($session_id){
        self::$_handler->del(self::sessionKey($session_id));
        return true;
    }

    public function gc($lifetime){
        return true;
    }

    protected function sessionKey($session_id){
        $key = 'session_'.$session_id;
        \Our\Halo\HaloLogger::INFO('sessionKey: '.$key);
        return $key;
    }
}