<?php
namespace Our\Halo;

class HaloMemcache
{
    protected $_type = 1;//1:普通连接 2:长链接
    protected $_mc;
    public static $instance = null;

    public static function getInstance($config){
        if(self::$instance === null){
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    private function __clone(){}

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @thorw mixed BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new \BadMethodCallException('BadMethodCallException, called HaloMemcache\'s method ' . $methodName . ' not exsits!');
    }

    private function __construct($config)
    {
        if (!class_exists('Memcache')) {
            throw new \Exception('Class Memcache not exists');
        }

        $this->_mc = new \Memcache();
        $timeout = isset($config['timeout'])?$config['timeout'] : 2000;

        if ($this->_type == 1) {
            if (isset($config['timeout'])) {
                $this->_mc->connect($config['host'], $config['port'], $timeout);
            } else {
                $this->_mc->connect($config['host'], $config['port']);
            }
        } else if ($this->_type == 2) {
            if(isset($config['timeout'])){
                $this->_mc->pconnect($config['host'], $config['port'], $timeout);
            } else {
                $this->_mc->pconnect($config['host'], $config['port']);
            }
        }
        return $this->_mc;
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
            $this->_mc->set($key, $value, 0, $expire_time);
        } else {
            $this->_mc->set($key, $value);
        }
    }

    /**
     * 从缓存读取数据
     * @param string|array|int... $key
     */
    public function get($key)
    {
        return $this->_mc->get($key);
    }

    /**
     * 从缓存删除数据
     * @param string|array|int... $key
     */
    public function del($key)
    {
        $this->_mc->delete($key);
    }


}