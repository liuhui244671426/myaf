<?php
/**
getResultCode() code:
00 = MEMCACHED_SUCCESS
01 = MEMCACHED_FAILURE
02 = MEMCACHED_HOST_LOOKUP_FAILURE // getaddrinfo() and getnameinfo() only
03 = MEMCACHED_CONNECTION_FAILURE
04 = MEMCACHED_CONNECTION_BIND_FAILURE // DEPRECATED see MEMCACHED_HOST_LOOKUP_FAILURE
05 = MEMCACHED_WRITE_FAILURE
06 = MEMCACHED_READ_FAILURE
07 = MEMCACHED_UNKNOWN_READ_FAILURE
08 = MEMCACHED_PROTOCOL_ERROR
09 = MEMCACHED_CLIENT_ERROR
10 = MEMCACHED_SERVER_ERROR // Server returns "SERVER_ERROR"
11 = MEMCACHED_ERROR // Server returns "ERROR"
12 = MEMCACHED_DATA_EXISTS
13 = MEMCACHED_DATA_DOES_NOT_EXIST
14 = MEMCACHED_NOTSTORED
15 = MEMCACHED_STORED
16 = MEMCACHED_NOTFOUND
17 = MEMCACHED_MEMORY_ALLOCATION_FAILURE
18 = MEMCACHED_PARTIAL_READ
19 = MEMCACHED_SOME_ERRORS
20 = MEMCACHED_NO_SERVERS
21 = MEMCACHED_END
22 = MEMCACHED_DELETED
23 = MEMCACHED_VALUE
24 = MEMCACHED_STAT
25 = MEMCACHED_ITEM
26 = MEMCACHED_ERRNO
27 = MEMCACHED_FAIL_UNIX_SOCKET // DEPRECATED
28 = MEMCACHED_NOT_SUPPORTED
29 = MEMCACHED_NO_KEY_PROVIDED //Deprecated. Use MEMCACHED_BAD_KEY_PROVIDED!
30 = MEMCACHED_FETCH_NOTFINISHED
31 = MEMCACHED_TIMEOUT
32 = MEMCACHED_BUFFERED
33 = MEMCACHED_BAD_KEY_PROVIDED
34 = MEMCACHED_INVALID_HOST_PROTOCOL
35 = MEMCACHED_SERVER_MARKED_DEAD
36 = MEMCACHED_UNKNOWN_STAT_KEY
37 = MEMCACHED_E2BIG
38 = MEMCACHED_INVALID_ARGUMENTS
39 = MEMCACHED_KEY_TOO_BIG
40 = MEMCACHED_AUTH_PROBLEM
41 = MEMCACHED_AUTH_FAILURE
42 = MEMCACHED_AUTH_CONTINUE
43 = MEMCACHED_PARSE_ERROR
44 = MEMCACHED_PARSE_USER_ERROR
45 = MEMCACHED_DEPRECATED
46 = MEMCACHED_IN_PROGRESS
47 = MEMCACHED_SERVER_TEMPORARILY_DISABLED
48 = MEMCACHED_SERVER_MEMORY_ALLOCATION_FAILURE
49 = MEMCACHED_MAXIMUM_RETURN // Always add new error code before
11 = MEMCACHED_CONNECTION_SOCKET_CREATE_FAILURE = MEMCACHED_ERROR


Memcached::OPT_COMPRESSION - -1001
Memcached::OPT_SERIALIZER - -1003
Memcached::SERIALIZER_PHP - 1
Memcached::SERIALIZER_IGBINARY - 2
Memcached::SERIALIZER_JSON - 3
Memcached::OPT_PREFIX_KEY - -1002
Memcached::OPT_HASH - 2
Memcached::HASH_DEFAULT - 0
Memcached::HASH_MD5 - 1
Memcached::HASH_CRC - 2
Memcached::HASH_FNV1_64 - 3
Memcached::HASH_FNV1A_64 - 4
Memcached::HASH_FNV1_32 - 5
Memcached::HASH_FNV1A_32 - 6
Memcached::HASH_HSIEH - 7
Memcached::HASH_MURMUR - 8
Memcached::OPT_DISTRIBUTION - 9
Memcached::DISTRIBUTION_MODULA - 0
Memcached::DISTRIBUTION_CONSISTENT - 1
Memcached::OPT_LIBKETAMA_COMPATIBLE - 16
Memcached::OPT_BUFFER_WRITES - 10
Memcached::OPT_BINARY_PROTOCOL - 18
Memcached::OPT_NO_BLOCK - 0
Memcached::OPT_TCP_NODELAY - 1
Memcached::OPT_SOCKET_SEND_SIZE - 4
Memcached::OPT_SOCKET_RECV_SIZE - 5
Memcached::OPT_CONNECT_TIMEOUT - 14
Memcached::OPT_RETRY_TIMEOUT - 15
Memcached::OPT_SEND_TIMEOUT - 19
Memcached::OPT_RECV_TIMEOUT - 15
Memcached::OPT_POLL_TIMEOUT - 8
Memcached::OPT_CACHE_LOOKUPS - 6
Memcached::OPT_SERVER_FAILURE_LIMIT - 21
Memcached::HAVE_IGBINARY - #&UNDEFINED;#
Memcached::HAVE_JSON - #&UNDEFINED;#
Memcached::GET_PRESERVE_ORDER - 1
Memcached::RES_SUCCESS - 0
Memcached::RES_FAILURE - 1
Memcached::RES_HOST_LOOKUP_FAILURE - 2
Memcached::RES_UNKNOWN_READ_FAILURE - 7
Memcached::RES_PROTOCOL_ERROR - 8
Memcached::RES_CLIENT_ERROR - 9
Memcached::RES_SERVER_ERROR - 10
Memcached::RES_WRITE_FAILURE - 5
Memcached::RES_DATA_EXISTS - 12
Memcached::RES_NOTSTORED - 14
Memcached::RES_NOTFOUND - 16
Memcached::RES_PARTIAL_READ - 18
Memcached::RES_SOME_ERRORS - 19
Memcached::RES_NO_SERVERS - 20
Memcached::RES_END - 21
Memcached::RES_ERRNO - 26
Memcached::RES_BUFFERED - 32
Memcached::RES_TIMEOUT - 31
Memcached::RES_BAD_KEY_PROVIDED - 33
Memcached::RES_CONNECTION_SOCKET_CREATE_FAILURE - 11
Memcached::RES_PAYLOAD_FAILURE - -1001
 * */
namespace Our\Halo;

//todo 1.testunit 2.addServers
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
        $this->_mcd->addServer($config['host'], $config['port']);

        if($this->_mcd->getResultCode() != 0){
            \Our\Halo\HaloLogger::ERROR($this->_mcd->getResultMessage());
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