<?php

    /**
     * @Create Author : huiliu//刘辉
     * @Create Time: 14-9-12 下午3:28
     * @Desc :
     */
    class redisModel
    {
        /**
         * 单例模式实例化对象
         *
         * @var object
         */
        //protected static $_instance = null;

        /**
         * Redis连接ID
         *
         * @var obiect
         */
        protected $dbLink = null;

        /**
         * 构造方法
         *
         * @return void
         */
        public function __construct($config = null)
        {
            if (!extension_loaded('redis')) {
                exit('Does not support the Redis extension');
            }

            //加载Redis配置
            if (is_null($config)) {
                if (empty($config)) {
                    exit('The Redis configuration failed to load');
                }
            }
            //print_r($config);
            //连接redis数据库
            $this->dbLink = new Redis();
            $timeout = empty($config['timeout']) ? 3 : $config['timeout'];
            $this->dbLink->connect($config['host'], $config['port'], $timeout);
            //print_r($config);
            if (!$this->dbLink) {
                exit('The Redis connection failed');
            }

            //需要密码操作
            if (!empty($config['pass'])) {
                $this->dbLink->auth($config['pass']);
            }
        }

        /**
         * 设置key
         *
         * @param string $key
         * @param string $value
         * @param int $timeOut
         *
         * @return boolen
         */
        public function set($key, $value, $timeOut = 0)
        {
            $result = $this->dbLink->set($key, $value);
            if ($timeOut > 0) {
                $this->dbLink->setTimeout($key, $timeOut);
            }

            //Debug::addMessage("设置缓存 缓存名称：" . $key . " 值：" . $value . " 过期时间：" . $timeOut);

            return $result;
        }

        /**
         * 获取key
         *
         * @param string $key
         *
         * @return string
         */
        public function get($key)
        {
            return $this->dbLink->get($key);
        }

        /**
         * 删除key key可以是数组 也可以有多个参数
         *
         * @param string $key
         *
         * @return boolen
         */
        public function delete($key)
        {
            if (is_array($key)) {
                return $this->dbLink->delete($key);
            }

            $num = func_num_args(); // 获取函数参数个数


            if ($num > 1) {
                return $this->dbLink->delete(func_get_args());
            } else {
                return $this->dbLink->delete($key);
            }
        }

        /**
         * 清空所有数据（慎用）
         */
        public function flushAll()
        {
            return $this->dbLink->flushAll();
        }

        /**
         * 数据入队列
         *
         * @param string $key
         * @param string $value
         * @param boolen $right
         *
         * @return boolen
         */
        public function push($key, $value, $right = true)
        {
            return $right ? $this->dbLink->rPush($key, $value) : $this->dbLink->lPush($key, $value);
        }

        /**
         * 数据出队列
         *
         * @param string $key
         * @param boolen $left
         *
         * @return boolen
         */
        public function pop($key, $left = true)
        {
            return $val = $left ? $this->dbLink->lPop($key) : $this->dbLink->rPop($key);
        }

        /**
         * 数据自增
         *
         * @param string $key
         * @param int $num
         *
         * @return boolen
         */
        public function increment($key, $num = 1)
        {
            return $this->dbLink->incr($key, $num);
        }

        /**
         * 数据自减
         *
         * @param string $key
         * @param int $num
         *
         * @return boolen
         */
        public function decrement($key, $num = 1)
        {
            return $this->dbLink->decr($key, $num);
        }

        /**
         * 判断key是否存在
         *
         * @param string $key
         *
         * @return boolen
         */
        public function exists($key)
        {
            return $this->dbLink->exists($key);
        }

        /**
         * 设置过期时间
         *
         * @param string $key
         * @param int $time
         * @param boolen $flag
         *
         * @return boolen
         */
        public function expire($key, $time, $flag = false)
        {
            return $flag === false ? $this->dbLink->expire($key, $time) : $this->dbLink->expireAt($key, $time);
        }

        /**
         * 数据入哈希队列
         *
         * @param string $key
         * @param string $field
         * @param string $value
         *
         * @return boolen
         */
        public function hSet($key, $field, $value)
        {
            $result = $this->dbLink->hSet($key, $field, $value);
            //Debug::addMessage("设置哈希缓存 缓存名称：" . $key . " 域：" . $field . " 值：" . $value);
            return $result;
        }

        /**
         * 从哈希队列取数据
         *
         * @param string $key
         * @param string $field
         *
         * @return string
         */
        public function hGet($key, $field)
        {
            return $this->dbLink->hGet($key, $field);
        }

        /**
         * 返回名称为key的hash中所有的键（field）及其对应的value
         *
         * @param string $key
         *
         * @return array
         */
        public function hGetAll($key)
        {
            return $this->dbLink->hGetAll($key);
        }

        /**
         * 从哈希队列中删除数据
         *
         * @param string $key
         * @param string $field
         *
         * @return boolen
         */
        public function hDel($key, $field)
        {
            return $this->dbLink->hDel($key, $field);
        }

        /**
         * 向set类型的数组中存入数据
         *
         * @param string $key
         * @param string $value
         *
         * @return boolen
         */
        public function sAdd($key, $value)
        {
            $result = $this->dbLink->sAdd($key, $value);
            //Debug::addMessage("设置SET缓存 缓存名称：" . $key . " 值：" . $value);
            return $result;
        }

        /**
         * 删除名称为key的set中的元素value
         *
         * @param string $key
         * @param string $value
         * * @return boolen
         */
        public function sRemove($key, $value)
        {
            return $this->dbLink->sRem($key, $value);
        }

        /**
         * 返回名称为key的set的所有元素
         *
         * @param string $key
         *
         * @return array
         */
        public function sMembers($key)
        {
            return $this->dbLink->sMembers($key);
        }

        /**
         * 向名称为key的zset中添加元素member，score用于排序。
         *
         * @param string $key
         * @param int $score
         * @param string $member
         *
         * @return boolen
         */
        public function zAdd($key, $score, $member)
        {
            $result = $this->dbLink->zAdd($key, $score, $member);
            //Debug::addMessage("设置ZSET缓存 缓存名称：" . $key . " 值：" . $member);
            return $result;
        }

        /**
         * 返回名称为key的zset（元素已按score从小到大排序）中的index从start到end的所有元素
         *
         * @param string $key
         * @param int $start
         * @param int $end
         * @param boolen $withscores
         *
         * @return array
         */
        public function zRange($key, $start, $end, $withscores = false)
        {
            return $this->dbLink->zRange($key, $start, $end, $withscores);
        }

        /**
         * 删除名称为key的zset中的元素value
         *
         * @param string $key
         * @param string $member
         *
         * @return boolen
         */
        public function zRemove($key, $member)
        {
            return $this->dbLink->zRem($key, $member);
        }

        /**
         * 返回名称为key的zset中元素member的score
         *
         * @param string $key
         * @param string $member
         *
         * @return int
         */
        public function zScore($key, $member)
        {
            return $this->dbLink->zScore($key, $member);
        }

        /**
         * 返回Redisd对象
         *
         * @return object
         */
        public function redis()
        {
            return $this->dbLink;
        }

        /**
         * 关闭数据库连接
         *
         * @return object
         */
        public function close()
        {
            return $this->dbLink->close();
        }

        /**
         * 析构函数
         */
        public function __destruct()
        {
            $this->close();
        }

        public function info(){
            return $this->dbLink->info();
        }
        /**
         * 单例模式
         *
         * @param mixed $params
         *
         * @return object
         */
//        public static function getInstance($params = null)
//        {
//            if (!self::$_instance) {
//                var_dump($params);
//                self::$_instance = new self($params);
//            }
//            print_r($params);
//            return self::$_instance;
//        }

    }