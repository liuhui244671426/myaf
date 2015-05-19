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

        $isDelete = $redis->delete(array('key1', 'key2', 'key3', 'car'));
        dump($isDelete);

        $isSet = $redis->hashHSet('car', 'name', 'BMW');
        dump($isSet);

        $isSet = $redis->hashHSet('car', 'name', 'QQ');
        dump($isSet);

        $isSet = $redis->hashHSet('car', 'price', 2000);
        dump($isSet);

        $isGet = $redis->hashGet('car', array('name', 'price'));
        dump($isGet);

        $keys = $redis->hashGet('car', array(), 2);
        dump($keys);

        //dump($redis->hDel('car', 'name', 'price'));//todo fix
    }
}