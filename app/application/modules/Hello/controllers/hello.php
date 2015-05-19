<?php

/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午12:20
 */
class helloController extends BaseController
{
    public function helloAction()
    {
        $redis = DataCenter::getFactory('redis', 'local');
        $isBool = $redis->mset(array('key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3'));
        dump($isBool);

        $result = $redis->mget(array('key1', 'key2', 'key3'));
        dump($result);
    }
}