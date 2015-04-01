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
    public function __construct(){
        $this->_db = DataCenter::getDb('myaf');
    }

    public function hello(){
        $sql = 'select * from `adm_users` where `id`=1;';
        $result = $this->_db->get_row($sql);
        dump($result);
    }
}