<?php
/**
 * Created by PhpStorm.
 * User: Yaxiang He
 * Date: 14-1-17
 * Time: 上午9:50
 */
/**
 * file_name HaloMongo.php
 * @desc mongo
 * @company mojichina.com
 *
 */

/**
 * Class HaloMongo
 */
class HaloMongo
{
    private $config = null;
    private $connection = null;
    private $collection = null;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * 连接数据库
     */
    public function connect()
    {
        try {
            if (isEmptyString($this->config['username']) || isEmptyString($this->config['password'])) {
                $this->connection = new MongoClient(sprintf('mongodb://%s:%d', $this->config['host'], $this->config['port']));
            } else {
                $this->connection = new MongoClient(sprintf('mongodb://%s:%d', $this->config['host'], $this->config['port']),
                    array(
                        'username' => $this->config['username'],
                        'password' => $this->config['password'],
                        'db' => $this->config['db'],
                    ));
            }
            $this->connection->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED, array());
        } catch (Exception $e) {
            $this->collection = null;
            $this->connection = null;
            Logger::ERROR('Connect mongodb error [' . $e->getMessage() . ']', __FILE__, __LINE__, ERROR_LOG_FILE);
        }
    }

    /**
     * 注：作此判断前须先执行setDbCollection，否则永远返回错误《qiang.zou@mojichina.com》
     * @return bool
     */
    public function isConnected()
    {
        return $this->connection && $this->collection;
    }

    /**
     * @desc 关闭连接
     */
    public function disconnect()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }


    /**
     * @brief  获取$db下所有集合
     *
     * @params $db
     *
     * @return MongoCollection objects
     */
    public function listCollections($db)
    {
        $db = $this->connection->selectDB($db);
        $list = $db->listCollections();
        return $list;
    }

    /**
     * @brief 获取$db下所有集合
     *
     * @params $db
     *
     * @return names of the all the collections
     */
    public function getCollectionNames($db)
    {
        $collections = $this->connection->selectDB($db)->getCollectionNames();
        return $collections;
    }

    /**
     * @desc 设置数据库和集合
     * @param $db
     * @param $collection
     * @return null
     */
    public function setDBCollection($db, $collection)
    {
        if (!$this->isConnected()) {
            list($usec, $sec) = explode(" ", microtime());
            $begin = ((float)$usec + (float)$sec);
            $this->connect();
            list($usec, $sec) = explode(" ", microtime());
            $end = ((float)$usec + (float)$sec);

            if ($end - $begin >= 0.2) {
                Logger::traceUser(0, 3002, array('time' => ($end - $begin) * 1000, 'host' => $this->config['host']));
            }
        }
        if ($this->connection) {
            $this->collection = $this->connection->$db->$collection;
            return $this->collection;
        }
        return;
    }

    public function count(array $query = array(), $limit = 0, $skip = 0)
    {
        return $this->collection->count($query, $limit, $skip);
    }

    /**
     * @param array $query
     * @param array $fields
     * @return MongoCursor
     */
    public function find(array $query = array(), array $fields = array())
    {
        $cursor = $this->collection->find($query, $fields);
        return $cursor;
    }

    /**
     * @param array $query
     * @param array $fields
     * @return MongoCursor
     */
    public function findOne(array $query = array(), array $fields = array())
    {
        $cursor = $this->collection->findOne($query, $fields);
        return $cursor;
    }

    /**
     * @param $a
     * @param array $options
     * @return array|boolean
     */
    public function Save($a, array $options = array())
    {
        $result = $this->collection->save($a, $options);
        return $result;
    }

    public function group($keys, array $initial, $reduce, array $options = array())
    {
        $result = $this->collection->group($keys, $initial, $reduce, $options);
        return $result;
    }

    /**
     * @param $db
     * @param array $command ,如 array('distinct' => 'collection_name') 求不重复。
     * @param $options
     */
    public function cmd($db, array $command, array $options = array())
    {
        $db = $this->connection->selectDB($db);
        return $db->command($command, $options);
    }

}



