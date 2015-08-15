<?php

/**
 * Bootstrap类, 在这个类中, 所以以_init开头的方法
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 都会被调用, 调用次序和申明次序相同
 * @author  Laruence
 * @date    2011-05-13 15:24
 * @version $Id$
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract
{

    /**
     * 初始化init配置
     */
    public function _initConfig(\Yaf\Dispatcher $dispatcher)
    {

        require APPLICATION_PATH . '/application/library/Our/functions/functions.php';
        import(APPLICATION_PATH . '/application/library/initConfig.php');
        \Our\Halo\HaloLogger::$logLevel = 0;

        set_error_handler('\Our\Halo\HaloLogger::sysError');
        set_exception_handler('\Our\Halo\HaloLogger::sysException');

        //register_shutdown_function('sysShutdown');

        $session = new \Our\SessionHandler();
        session_set_save_handler(
            array($session, 'open'),
            array($session, 'close'),
            array($session, 'read'),
            array($session, 'write'),
            array($session, 'destroy'),
            array($session, 'gc')
        );
        session_start();

    }

    public function _initNamespaces()
    {
        //申明, 凡是以Zend,Local开头的类, 都是本地类
        //Yaf_Loader::getInstance()->registerLocalNameSpace(array("Zend", "Local"));
    }

    /**
     * 注册一个插件
     */
    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        $auth = new AuthPlugin();
        $dispatcher->registerPlugin($auth);
    }

    /**
     * 初始化模版
     */
    public function _initView(\Yaf\Dispatcher $dispatcher)
    {
        \Yaf\Dispatcher::getInstance()->autoRender(false);

        $isLayout = true;
        if ($isLayout) {
            $layout = new Our\layout(PHTML_PATH);
            $layout->setScriptPath(PHTML_PATH);
            $dispatcher->setView($layout);
        } else {
            $dispatcher->initView(PHTML_PATH);//使用yaf引擎,普通方式
        }
    }

    /**
     * 初始化路由器
     */
    public function _initRouter(\Yaf\Dispatcher $dispatcher)
    {
        //获取分发的路由实例
        $router = $dispatcher->getInstance()->getRouter();
        //default router
        //$router->addRoute('Test', new Yaf_Route_Rewrite('/test/:id', array('controller' => 'Index', 'action' => 'Test')));
        //$router->addRoute('Test1', new Yaf_Route_Regex( '#^/test/([0-9]*)$#', array('controller' => 'Index', 'action' => 'Test'), array( 1 => 'id')) );

        $router->addRoute('login',
            new \Yaf\Route\Rewrite('/login', array(
                'module' => 'Admin',
                'controller' => 'Index',
                'actio' => 'login'
            ))
        );


    }
}
