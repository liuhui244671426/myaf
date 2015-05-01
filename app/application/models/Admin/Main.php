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
        $this->redis = DataCenter::getFactory('db', 'local');
        $this->mcd = DataCenter::getFactory('memcached', 'cms');
    }

    public function testRedis(){
        $this->redis->delete('hello');
        $this->redis->set('hello', 'world');
        $val = $this->redis->get('hello');
        print_r($val);
    }

    public function testMCD(){
        $this->mcd->mcdSet('mem', 'asdsadb23132131231111111');
        $val = $this->mcd->mcdGet('mem');
        print_r($val);
        $this->mcd->mcdDel('mem');
    }
}