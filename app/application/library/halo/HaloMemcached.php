<?php

/**
 * @desc memcahed  sinleton
 * @date 下午5:27:22
 * @modify
 * @instance
 * @todo:优化成memcached
 */
class HaloMemcached
{

    private static $_connect_type = 'connect';
    public $_memcache;

    public function __construct($host, $port, $timeout = null)
    {
        if (!class_exists('Memcache')) {
            throw new Exception('Class Memcache not exists');
        }
        $this->_memcache = new Memcache();
        if (self::$_connect_type == 'connect') {
            if (isset($timeout)) {
                $this->_memcache->connect($host, $port, $timeout);
            } else {
                $this->_memcache->connect($host, $port);
            }

        } else if (self::$_connect_type == 'pconnect') {
            $this->_memcache->pconnect($host, $port, $timeout);
        }

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
            $this->_memcache->set($key, $value, 0, $expire_time);
        } else {
            $this->_memcache->set($key, $value);
        }
    }

    /**
     * 从缓存读取数据
     * @param string|array|int... $key
     */
    public function get($key)
    {
        return $this->_memcache->get($key);
    }

    /**
     * 从缓存删除数据
     * @param string|array|int... $key
     */
    public function del($key)
    {
        $this->_memcache->delete($key);
    }


}