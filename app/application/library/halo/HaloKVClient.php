<?php
/**
 * WContactLog
 * @author frank.xu
 * @date Dec 27, 2012 5:04:43 PM
 * @copyright  Copyright (c) 2012 Youlu
 * @version    1.0.0
 */

defined('ERROR_LOG_FILE') || define('ERROR_LOG_FILE', 'error');

class  HaloKVClient
{
    private $config = array();
    protected $connection = null;
    protected $collection = null;

    public function __construct($config)
    {
        $this->config = $config;
        $this->port = empty($port) ? 27017 : $port ;
    }
    public function __destruct()
    {
        if($this->connection)
        {
            $this->connection->close();
        }
    }
    public function isConnected()
    {
        return $this->connection && $this->collection;
    }

    public function setDBCollection($db,$collection)
    {
        if (!$this->isConnected())
        {
            list($usec, $sec) = explode(" ", microtime());
            $begin = ((float)$usec + (float)$sec);
            $this->connect();
            list($usec, $sec) = explode(" ", microtime());
            $end = ((float)$usec + (float)$sec);

            if ($end - $begin >= 0.2)
            {
                Logger::traceUser(0,3002, array('time'=>($end - $begin)*1000, 'host'=>$this->config['host']));
            }
        }
        if($this->connection)
        {
            $this->collection =  $this->connection->$db->$collection;
            return $this->collection;
        }
        return ;
    }

    public function connect()
    {
        try{
            if(isEmptyString($this->config['username']) || isEmptyString($this->config['password']))
            {
                $this->connection = new MongoClient(sprintf('mongodb://%s:%d', $this->config['host'], $this->config['port']));
            }
            else
            {
                $this->connection = new MongoClient(sprintf('mongodb://%s:%d', $this->config['host'], $this->config['port']),
                    array(
                        'username'=>$this->config['username'],
                        'password'=>$this->config['password'],
                        'db'=>$this->config['db'],
                    ));
            }
        }
        catch (Exception $e)
        {
            $this->collection = null;
            $this->connection = null;
            Logger::ERROR('Connect mongodb error ['.$e->getMessage().']',__FILE__,__LINE__,ERROR_LOG_FILE);
        }
    }
    public function disconnect()
    {
        if($this->connection)
        {
            $this->connection->close();
        }
    }
    public function set($key, $value)
    {
        if (!$this->isConnected())
        {
            $this->connect();

        }
        if (!$this->collection)
            return ;

        $ret = $this->get($key);
        if($ret)
            $this->collection->update(array('key'=>$key), array('$set'=>array('value'=>$value)));
        else
            $this->collection->insert(array('key'=>$key, 'value'=>$value));
    }
    public function get($key)
    {
        if (!$this->isConnected())
        {
            $this->connect();
        }
        if (!$this->collection)
            return null;
        $ret =  $this->collection->findOne(array('key'=>$key));
        return $ret ? $ret['value'] : null;
    }
    public function remove($key)
    {
        if (!$this->isConnected())
        {
            $this->connect();
        }
        if (!$this->collection)
            return ;
        $this->collection->remove(array('key'=>$key));
    }
}