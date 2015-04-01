<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午11:30
 */
namespace Hello;


use \BaseModel;
use Zend\Db\Sql\Select;

class worldModel extends BaseModel{

    public function getWord($pos){
        $adapter = \Yaf_Registry::get('adapter');
        $sql = 'select * from `adm_users`';

        $select = new Select();
        $res = $select->from('adm_users');
        print_r($res);
    }

    public function select(){
        //$dbAdapter = new \Zend\Db\Adapter\Adapter()
        //$db = new \Zend\Db\Sql\Sql();
    }
}