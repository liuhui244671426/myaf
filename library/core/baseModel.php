<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-9-12 下午2:09
 * @Desc : 
 */
class baseModel
{
    static private $_config;
    static private $_connections = array('db' => array(), 'redis' => array(), 'memcache' => array());

    public function __construct()
    {
        //self::$_config = Yaf_Registry::get('config');
        //print_r(self::$_config);
    }

    static public function mysqlModel($flag)
    {
        self::$_config = YafRegistry('config_db');
        //print_r(self::$_config['mysql'][$flag]);

        if(isset(static::$_connections['db'][$flag]))
        {
            return static::$_connections['db'][$flag];
        }
        //print_r($flag);
        $pdoModel = new pdoModel(self::$_config['mysql'][$flag]);
        static::$_connections['db'][$flag] = $pdoModel;
        //print_r(static::$_connections);
        return static::$_connections['db'][$flag];
    }

    static public function redisModel($flag)
    {
        self::$_config = Yaf_Registry::get('config_redis');

        if(isset(static::$_connections['redis'][$flag]))
        {
            return static::$_connections['redis'][$flag];
        }

        //print_r(self::$_config['redis'][$flag]);
        //$redisModel = redisModel::getInstance(self::$_config['redis'][$flag]);
        $redisModel = new redisModel(self::$_config['redis'][$flag]);
        static::$_connections['redis'][$flag] = $redisModel;
        //print_r(static::$_connections['redis']);

        return static::$_connections['redis'][$flag];
    }

    static public function memcacheModel($flag)
    {
        self::$_config = YafRegistry('config_memcache');

        if(isset(static::$_connections['memcache'][$flag]))
        {
            return static::$_connections['memcache'][$flag];
        }

        //print_r(self::$_config['memcache'][$flag]['host']);
        $redisModel = new memcachedModel(self::$_config['memcache'][$flag]['host'], self::$_config['memcache'][$flag]['port'], self::$_config['memcache'][$flag]['timeout']);
        static::$_connections['memcache'][$flag] = $redisModel;
        //print_r(static::$_connections['redis']);

        return static::$_connections['memcache'][$flag];
    }
}