<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-8-18 下午7:00
 * @Desc : 基类
 */

class BaseController extends YafController
{
    protected $_config;
    protected $_request;

    /**
     * Yaf __construct
     */
    public function init()
    {
        defined('INAPP') or exit('No permission resources');

        //初始化配置数据
        $this->_config = Yaf_Registry::get('config');
        $this->_request = Yaf_Dispatcher::getInstance()->getRequest();

        $this->doInit();
    }

    /**
     * 继承类的__construct
     */
    public function doInit() {}

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @thorw mixed BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new BadMethodCallException('BadMethodCallException, called method ' . $methodName . ' not exsits!');
    }
}