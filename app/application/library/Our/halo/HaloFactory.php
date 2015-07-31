<?php
/**
 * @Desc: origin file name DataCenter.php
 * @User: liuhui
 * @Date: 15-7-31 上午10:46
 * @desc Data base provider
 */
namespace Our\halo;

abstract class HaloFactory
{
    private static $connections = array('db' => array(), 'redis' => array(), 'mongo' => array(), 'mc' => array(), 'memcached' => array());
    private static $HaloMap = array('db' => 'HaloPdo', 'redis' => 'HaloRedis', 'mongo' => 'HaloMongo', 'mc' => 'HaloMemcache', 'memcached' => 'HaloMemcached');

    /**
     * 数据工厂
     * @param string $type 数据类型
     * @param string $name 数据连接名
     * @return mixed handle|throw
     * */
    public static function getFactory($type, $name){
        if (isset(static::$connections[$type][$name])) {
            return static::$connections[$type][$name];
        }

        $configKey = sprintf('config_%s', $type);
        $config = \Yaf\Registry::get($configKey);
        $config = $config->{$type}->{$name};

        if (empty($config)) {
            throw new \LogicException(sprintf('extend config of %s->%s not found', $type, $name), EXC_CODE_EXTEND_CONFIG_NOT_FOUND);
        }

        $file = sprintf('%sOur/halo/%s.php', LIBRARY_PATH, self::$HaloMap[$type]);
        import($file);

        switch ($type){
            case 'db':
                $connectionType = \Our\halo\HaloPdo::getInstance(array('host' => $config->host, 'port' => $config->port, 'user' => $config->user, 'pass' => $config->pass, 'dbname' => $config->dbname));
                break;
            case 'redis':
                $connectionType = \Our\halo\HaloRedis::getInstance(array('host' => $config->host, 'port' => $config->port, 'pass' => $config->pass, 'timeout' => $config->timeout));
                break;
            case 'memcached':
                $connectionType = \Our\halo\HaloMemcached::getInstance(array('host' => $config['host'], 'port' => $config['port'], 'timeout' => $config['timeout']));
                break;
            case 'memcache':
                $connectionType = \Our\halo\HaloMemcache::getInstance(array('host' => $config['host'], 'port' => $config['port'], 'timeout' => $config['timeout']));
                break;
            default:
                throw new \LogicException('this type: ' . $type . ' not found', EXC_CODE_HALO_TYPE_NOT_FOUND);
                break;
        }
        return self::$connections[$type][$name] = $connectionType;
    }

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @return \BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new \BadMethodCallException('BadMethodCallException, called class DataCenter\'s method ' . $methodName . ' not found', EXC_CODE_HALO_METHOD_NOT_FOUND);
    }
}


