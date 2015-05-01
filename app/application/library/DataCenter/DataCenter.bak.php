<?php

/**
 * file_name DataCenter.php
 * @desc Data base provider
 */
class DataCenter
{
    private static $connections = array('db' => array(), 'redis' => array(), 'mongo' => array(), 'mc' => array(), 'memcached' => array());

    /**
     * get database connect
     * @param $name
     * @return HaloPdo
     * @throws Exception
     */
    public static function getDb($name)
    {
        if (isset(static::$connections['db'][$name])) {
            return static::$connections['db'][$name];
        }

        $config = Yaf_Registry::get('config_db');
        $dbConfig = $config->mysql->{$name};

        if (empty($dbConfig)) {
            throw new Exception(sprintf('config of db %s is not found', $name), -9999);
        }

        $file = sprintf('%shalo/HaloPdo.php', LIBRARY_PATH);
        if (file_exists($file)) {
            Yaf_Loader::import($file);
        } else {
            throw new Exception('HaloPdo.php is not found', -9999);
        }

        $db = HaloPdo::getInstance(array('host' => $dbConfig->host, 'port' => $dbConfig->port, 'user' => $dbConfig->user, 'pass' => $dbConfig->pass, 'dbname' => $dbConfig->dbname));
        return static::$connections['db'][$name] = $db;
    }

    /**
     * get mogodb connect
     * @param string $name
     * @return HaloKVClient
     * @throws Exception
     */
    public static function getMongo($name)
    {
        if (isset(static::$connections['mongo'][$name])) {
            return static::$connections['mongo'][$name];
        }

        $config = Yaf_Registry::get('config');
        $mongoConfig = $config->mongo->{$name};

        if (empty($mongoConfig)) {
            throw new Exception(sprintf('config of mongo %s is not found', $name), -9999);
        }

        $kv = new HaloKVClient (array(
            'host' => $mongoConfig->host,
            'port' => $mongoConfig->port,
        ));

        return static::$connections['mongo'][$name] = $kv;
    }

    /**
     * get mogodb connect
     * @param string $name
     * @return HaloMongo
     * @throws Exception
     */
    public static function getMongodb($name)
    {
        if (isset(static::$connections['mongo'][$name])) {
            return static::$connections['mongo'][$name];
        }

        $config = Yaf_Registry::get('config');
        $mongoConfig = $config->mongo->{$name};

        if (empty($mongoConfig)) {
            throw new Exception(sprintf('config of mongo %s is not found', $name), -9999);
        }

        $mongo = new HaloMongo(array(
            'host' => $mongoConfig->host,
            'port' => $mongoConfig->port,
        ));
        return static::$connections['mongo'][$name] = $mongo;
    }


    /**
     * @desc get redis connect
     * @createDate 2014-1-16
     * @return HaloRedis
     */
    public static function getRedis($name)
    {
        if (isset(static::$connections['redis'][$name])) {
            return static::$connections['redis'][$name];
        }

        $config = Yaf_Registry::get('config_redis');
        $redisConfig = $config->redis->{$name};

        if (empty($redisConfig)) {
            throw new Exception(sprintf('config of redis %s is not found', $name), -9998);
        }

        $file = sprintf('%shalo/HaloRedis.php', LIBRARY_PATH);
        if (file_exists($file)) {
            Yaf_Loader::import($file);
        } else {
            throw new Exception('HaloRedis.php is not found', -9999);
        }

        $redis = HaloRedis::getInstance(array('host' => $redisConfig->host, 'port' => $redisConfig->port, 'pass' => $redisConfig->pass, 'timeout' => $redisConfig->timeout));
        return static::$connections['redis'][$name] = $redis;
    }

    /**
     * @desc get memcached connect
     * @param $name
     * @return memcached
     */
    public static function getMemcached($name)
    {
        if (isset(static::$connections['memcached'][$name])) {
            return static::$connections['memcached'][$name];
        }

        $config = Yaf_Registry::get('config_memcache');
        $memcachedConfig = $config->memcache->{$name};

        if (empty($memcachedConfig)) {
            throw new Exception(sprintf('config of memcached %s is not found', $name), -9998);
        }

        $file = sprintf('%shalo/HaloMemcached.php', LIBRARY_PATH);
        if (file_exists($file)) {
            Yaf_Loader::import($file);
        } else {
            throw new Exception('HaloMemcached.php is not found', -9999);
        }

        $memcached = HaloMemcached::getInstance(array('host' => $memcachedConfig['host'], 'port' => $memcachedConfig['port'], 'timeout' => $memcachedConfig['timeout']));
        return static::$connections['memcached'][$name] = $memcached;
    }

    /**
     * get mc connect
     * @param string $name
     * @return MemCacheBase
     * @throws Exception
     */
    public static function getMc($name)
    {
        if (isset(static::$connections['mc'][$name])) {
            return static::$connections['mc'][$name];
        }

        $config = Yaf_Registry::get('config_memcache');
        $mcConfig = $config->memcache->{$name};
        if (empty($mcConfig)) {
            throw new Exception(sprintf('config of memcache %s is not found', $name), -9999);
        }

        $file = sprintf('%shalo/HaloMemcache.php', LIBRARY_PATH);
        if (file_exists($file)) {
            Yaf_Loader::import($file);
        } else {
            throw new Exception('HaloMemcache.php is not found', -9999);
        }

        $mc = HaloMemcache::getInstance(array('host' => $mcConfig['host'], 'port' => $mcConfig['port'], 'timeout' => $mcConfig['timeout']));
       /* $serverCount = intval($mcConfig->$name->count);
        $mc = new Memcache();
        for ($i = 1; $i <= $serverCount; $i++) {
            $hostKey = 'host_' . $i;
            $portKey = 'port_' . $i;
            $mc->addServer($mcConfig->$name->$hostKey, $mcConfig->$name->$portKey);
        }*/
        return static::$connections['mc'][$name] = $mc;
    }

}

