<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-2-13
 * Time: 上午10:42
 */
class MainController extends BaseController{

    public function doInit(){
        $this->_view->setLayout('Admin');
    }

    /**
     * 登陆后主页
     * @uri /admin/main/main
     * */
    public function mainAction(){
        //dump($_SESSION);
        $this->_view->display('Admin/main.phtml');
    }

    /**
     * 报表数据
     * @uri /admin/main/charts
     * @return string json
     * */
    public function chartsAction(){
        $db = new AdminModel();
        $result = $db->charts();

        EchoJsonString($result);
    }
}