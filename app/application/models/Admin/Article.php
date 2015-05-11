<?php
/**
 * @Desc: 文章管理
 * @User: liuhui
 * @Date: 15-5-11 下午11:23 
 */
class Admin_ArticleModel extends BaseModel{
    protected $_db;
    public function __construct(){
        $this->_db = DataCenter::getFactory('db', 'cms');
    }
}