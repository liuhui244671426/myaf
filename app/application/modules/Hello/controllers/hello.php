<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-12
 * Time: 上午12:20
 */
class helloController extends BaseController{

    public function phpAction(){
        echo 'ni hao PHP';
    }

    public function helloAction(){
        $db = new Hello\worldModel();
        $db->hello();
    }
}