<?php

class HaloMemcached
{
    protected $_type = 1;//1:普通连接 2:长链接
    public $_mcd = null;
    public static $instance = null;

    public static function getInstance($config){
        if(self::$instance === null){
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    /**
     * 私有化克隆函数，防止外界克隆对象
     * */
    private function __clone(){}

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @thorw mixed BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new BadMethodCallException('BadMethodCallException, called HaloMemcache\'s method ' . $methodName . ' not exsits!');
    }

    private function __construct($config)
    {
        if (!class_exists('Memcached')) {
            throw new Exception('Class Memcached not exists');
        }

        $this->_mcd = new Memcached();
        $this->_mcd->addServer($config['host'], $config['port']);

        return $this->_mcd;
    }

    /**
     * 把数据添加到缓存
     * @param string $key 缓存的key
     * @param string|array|int... $value 缓存的数据
     * @param int $expire_time 缓存时间
     */
    public function set($key, $value, $expire_time = 0)
    {
        if ($expire_time > 0) {
            return $this->_mcd->set($key, $value, 0, $expire_time);
        } else {
            return $this->_mcd->set($key, $value);
        }
    }

    /**
     * 从缓存读取数据
     * @param string|array|int... $key
     */
    public function get($key)
    {
        return $this->_mcd->get($key);
    }

    /**
     * 从缓存删除数据
     * @param string|array|int... $key
     */
    public function del($key)
    {
        return $this->_mcd->delete($key);
    }


}