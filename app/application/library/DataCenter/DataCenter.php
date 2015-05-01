<?php

/**
 * file_name DataCenter.php
 * @desc Data base provider
 */
abstract class DataCenter
{
    private static $connections = array('db' => array(), 'redis' => array(), 'mongo' => array(), 'mc' => array(), 'memcached' => array());
    private static $HaloMap = array('db' => 'HaloPdo', 'redis' => 'HaloRedis', 'mongo' => 'HaloMongo', 'mc' => 'HaloMemcache', 'memcached' => 'HaloMemcached');

    /**
     * 数据工厂
     * @param string $type 数据类型
     * @param string $name 数据连接名
     * @return object/exception
     * */
    public static function getFactory($type, $name){
        if (isset(static::$connections[$type][$name])) {
            return static::$connections[$type][$name];
        }

        $configKey = sprintf('config_%s', $type);
        $config = Yaf_Registry::get($configKey);
        $config = $config->{$type}->{$name};

        if (empty($config)) {
            throw new Exception(sprintf('config of %s %s is not found', $type, $name), -9999);
        }

        $file = sprintf('%shalo/%s.php', LIBRARY_PATH, self::$HaloMap[$type]);
        if (file_exists($file)) {
            Yaf_Loader::import($file);
        } else {
            throw new Exception( self::$HaloMap[$type] . '.php is not found', -9998);
        }

        switch ($type){
            case 'db':
                $connectionType = HaloPdo::getInstance(array('host' => $config->host, 'port' => $config->port, 'user' => $config->user, 'pass' => $config->pass, 'dbname' => $config->dbname));
                break;
            case 'redis':
                $connectionType = HaloRedis::getInstance(array('host' => $config->host, 'port' => $config->port, 'pass' => $config->pass, 'timeout' => $config->timeout));
                break;
            case 'memcached':
                $connectionType = HaloMemcached::getInstance(array('host' => $config['host'], 'port' => $config['port'], 'timeout' => $config['timeout']));
                break;
            case 'memcache':
                $connectionType = HaloMemcache::getInstance(array('host' => $config['host'], 'port' => $config['port'], 'timeout' => $config['timeout']));
                break;
            default:
                throw new LogicException('this type is empty: ' . $type . ', but can try other type!', -9997);
                break;
        }
        return self::$connections[$type][$name] = $connectionType;
    }

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @thorw mixed BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new BadMethodCallException('BadMethodCallException, called class DataCenter\'s method ' . $methodName . ' not exsits!', -9996);
    }
}


