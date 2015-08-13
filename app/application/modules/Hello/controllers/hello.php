<?php

/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午12:20
 */
class helloController extends \Our\Controller\hello
{
    public function helloAction()
    {
        $redis = \Our\Halo\HaloFactory::getFactory('redis', 'local');

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

    public function testAction(){

        $targets = array('memcached01', 'memcached02', 'memcached03',
            'memcached04', 'memcached05', 'memcached06',
            'memcached07', 'memcached08', 'memcached09',
            'memcached10', 'memcached11', 'memcached12',
            'memcached13', 'memcached14', 'memcached15'
        );
        //$targets = array('redis01', 'redis02');

        $manager = new \Our\Util\Canoma\Manager(new \Our\Util\Canoma\HashAdapter\Crc32(), 30);
        $manager->addNodes($targets);

        $md5HashArr = array();
        for($i = 0; $i < 1000; $i++){
            $lookup = $manager->getNodeForString($i);
            array_push($md5HashArr, $lookup);
        }

        rsort($md5HashArr);
        print_r(array_count_values($md5HashArr));
    }

    public function testSessionAction(){
        unset($_SESSION['aaa']);

        $_SESSION['bbb'] = 'hellosssss222222world';
    }
}