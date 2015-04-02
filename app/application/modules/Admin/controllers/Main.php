<?php
class MainController extends BaseController{
    public function doInit(){
        $this->_view->setLayout('Admin');
    }

    public function mainAction(){
        $this->_view->display('Admin/main.phtml', array());
    }
}