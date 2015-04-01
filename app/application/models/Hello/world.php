<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午11:30
 */
namespace Hello;

use \BaseModel;
use \DataCenter;

class worldModel extends BaseModel{

    protected $_db;
    protected $_db1;

    protected $_rd;

    public function __construct(){
        $this->_db = DataCenter::getDb('myaf');
        $this->_db1 = DataCenter::getDb('deydetail');

        $this->_rd = DataCenter::getRedis('dayDetail');
    }

    public function hello(){
        $sql = 'select * from `adm_users` where `id`=1;';
        $sql1 = 'select * from `avatar_kind` where `kind_id`=1;';
        $result = $this->_db->get_row($sql);
        $result1 = $this->_db1->get_row($sql1);
        dump($result);
        dump($result1);


    }
}