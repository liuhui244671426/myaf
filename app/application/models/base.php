<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 下午5:50
 */

class BaseModel{

    public function __construct(){
        if(\Yaf_Registry::get('config_db')){
            $this->initMysql();
        }
    }

    public function initMysql(){
        $dbConf = \Yaf_Registry::get('config_db');
        $hd = $dbConf->database->myaf->toArray();

        $dbAdapter = new \Zend\Db\Adapter\Adapter($hd);
        $res = $dbAdapter->query("select * from `adm_users`;", array());
        dump($res);
    }
}