<?php
namespace Our\Halo;

//todo test unit
class HaloMemcached
{
    protected static $instance = null;
    private $_mcd = null;
    protected $_type = 1;//1:普通连接 2:长链接


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
        throw new \BadMethodCallException('BadMethodCallException, called HaloMemcached\'s method ' . $methodName . ' not found', EXC_CODE_HALO_MEMCACHED_METHOD_NOT_FOUND);
    }

    /**
     * 构造函数
     * */
    private function __construct($config)
    {
        if (!class_exists('Memcached')) {
            throw new \LogicException('Class Memcached not found', EXC_CODE_HALO_MEMCACHED_CLASS_NOT_FOUND);
        }

        $this->_mcd = new \Memcached();
        $isConnected = $this->_mcd->addServer($config['host'], $config['port']);

        if($isConnected == false){
            \Our\Halo\HaloLogger::INFO($this->_mcd->getResultMessage());
            exit;
        }
        return $this->_mcd;
    }
    /**
     * md5加密key值
     * @param string $key
     * @return string
     * */
    public function keyName($key){
        return md5(strtolower($key));
    }
    /**
     * 获取服务器状态
     * @return Possible: "reset, malloc, maps, cachedump, slabs, items, sizes"
     * */
    public function getStats(){
        return $this->_mcd->getStats();
    }
    /**
     * 获取服务器版本
     * @return int
     * */
    public function getVersion(){
        return $this->_mcd->getVersion();
    }

    /**
     * 清空记录
     * */
    public function flush(){
        return $this->_mcd->flush();
    }
    /**
     * 把数据添加到缓存
     * @param string $key 缓存的key
     * @param string|array|int... $value 缓存的数据 array('key1' => 'value1','key2' => 'value2','key3' => 'value3')
     * @param int $expireTime 缓存时间
     * @return bool
     */
    public function set($key, $value, $expireTime = null)
    {
        $expireTime = is_null($expireTime)?120:$expireTime;
        if(is_array($value)){
            return $this->_mcd->setMulti($value, $expireTime);
        } else {
            return $this->_mcd->set($key, $value, $expireTime);
        }
    }

    /**
     * 从缓存读取数据
     * @param string|array|int... $key
     * @return array|false
     */
    public function get($key)
    {
        if(is_array($key)){
            return $this->_mcd->getMulti($key);
        } else {
            return $this->_mcd->get($key);
        }
    }

    /**
     * 从缓存删除数据
     * @param mixed(string|array|int) $key
     * @return mixed
     */
    public function del($key)
    {
        if(is_array($key)){
            return $this->_mcd->deleteMulti($key);
        } else {
            return $this->_mcd->delete($key);
        }
    }
    /**
     * 是否连接
     * @return bool
     * */
    public function isConnected(){
        foreach ($this->getStats() as $key => $server) {
            if($server['pid'] == -1){
                return false;
            }
            return true;
        }
    }
}