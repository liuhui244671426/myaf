<?php
/**
 * @Desc:
 * @User: liuhui
 * @Date: 15-4-21 下午12:09 
 */
class Admin_MainModel extends BaseModel{
    protected $redis;
    protected $mcd;
    public function __construct(){
        $this->redis = \Our\halo\HaloFactory::getFactory('redis', 'local');
        $this->mcd = \Our\halo\HaloFactory::getFactory('memcached', 'cms');
    }

    public function testRedis(){
        $this->redis->delete('hello');
        $this->redis->set('hello', 'world');
        $val = $this->redis->get('hello');
        return $val;
    }

    public function testMCD(){
        $this->mcd->set('mem', 'asdsadb23132131231111111');

        $val = $this->mcd->get('mem');
        print_r($this->mcd->getStats());
        print_r($val);
        $this->mcd->del('mem');

    }
}