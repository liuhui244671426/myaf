<?php
/**
 * Bootstrap类, 在这个类中, 所以以_init开头的方法
 * 都会被调用, 调用次序和申明次序相同
 * 
 * @author  Laruence
 * @date    2011-05-13 15:24
 * @version $Id$ 
*/

class Bootstrap extends Yaf_Bootstrap_Abstract {

    /**
     * 初始化init配置
     */
    public function _initConfig(Yaf_Dispatcher $dispatcher){
        header('content-type:text/html;charset=utf-8');
        session_start();

        Yaf_Loader::import('initConfig.php');

        set_error_handler('sysErrorHandler');
        //register_shutdown_function('sysShutdown');
    }

    public function _initNamespaces(){
        //申明, 凡是以Zend,Local开头的类, 都是本地类
        Yaf_Loader::getInstance()->registerLocalNameSpace(array("Zend", "Local"));
    }

	/**
	 * 注册一个插件
	 */
	public function _initPlugin(Yaf_Dispatcher $dispatcher)
    {
		$auth = new AuthPlugin();
		$dispatcher->registerPlugin($auth);
	}

    /**
     * 初始化模版
     */
    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $isLayout = true;
        if($isLayout){
            $layout = new layout(PHTML_PATH);
            $layout->setScriptPath(PHTML_PATH);
            $dispatcher->setView($layout);
        } else {
            $dispatcher->initView(PHTML_PATH);//使用yaf引擎,普通方式
        }
    }

    /**
     * 初始化路由器
     */
    public function _initRouter(Yaf_Dispatcher $dispatcher)
    {
        //获取分发的路由实例
        $router = $dispatcher->getInstance()->getRouter();
        //default router
        //$router->addRoute('Test', new Yaf_Route_Rewrite('/test/:id', array('controller' => 'Index', 'action' => 'Test')));
        //$router->addRoute('Test1', new Yaf_Route_Regex( '#^/test/([0-9]*)$#', array('controller' => 'Index', 'action' => 'Test'), array( 1 => 'id')) );

        //print_r($router);
    }
}
