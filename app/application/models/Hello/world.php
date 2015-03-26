<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午11:30
 */
namespace Hello;


use \BaseModel;

class worldModel extends BaseModel{

    public function getWord($pos){
        $arr = array(
            'php', 'java', 'css', 'python', 'go'
        );
        return $arr[$pos];
    }

    public function select(){
        //$dbAdapter = new \Zend\Db\Adapter\Adapter()
        //$db = new \Zend\Db\Sql\Sql();
    }
}