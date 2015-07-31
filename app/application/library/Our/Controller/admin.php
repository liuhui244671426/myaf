<?php
/**
 * @Desc:
 * @User: liuhui
 * @Date: 15-7-31 上午11:10 
 */
namespace Our\Controller;

//use Controllers;

abstract class admin extends \Our\Controller\YafController{
    public function init(){
        $this->_view->setLayout('Admin');
    }
}