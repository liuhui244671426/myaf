<?php
/**
 * @Desc: 注册session存储方式
 * 一致hash
 * @User: liuhui
 * @Date: 15-4-27 下午5:56 
 */
namespace Our;

class SessionHandler extends \SessionHandler{
    private static $lifetime = null;
    private static $_handler = null;

    public function __construct(){
        self::$lifetime = ini_get("session.gc_maxlifetime");
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
        return self::$_handler->get(self::session_key($session_id));
    }

    public function write($session_id,$data){
        self::$_handler->set(self::session_key($session_id), $data, self::$lifetime);

        return true;
    }

    public function destroy($session_id){
        self::$_handler->del(self::session_key($session_id));
        return true;
    }

    public function gc($lifetime){
        return true;
    }

    public function session_key($session_id){
        return 'session_'.$session_id;
    }
}