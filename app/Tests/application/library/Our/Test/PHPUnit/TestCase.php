<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/8/30
 * @Time: 21:22
 * @Desc: 测试单元基类
 *  命令行模式需要开启yaf命名空间
 *  例如:
 *  cd Tests
 *  phpunit --bootstrap ./bootstrap.php application/library/Our/Test/PHPUnit/ControllerTestCase.php
 */

namespace Our\Test\PHPUnit;

class TestCase extends \PHPUnit_Framework_TestCase {
    private $_app;

    /**
     * 构造方法，调用application实例化方法
     */
    public function __construct() {
        $this->getApplication();
        parent::__construct();
    }

    /**
     * 设置application
     *
     * @return \Yaf\Application
     */
    public function setApplication() {
        $this->_app = new \Yaf\Application(APPLICATION_PATH . "/application/configs/application.ini", 'production');
        $this->_app->bootstrap();
        \Yaf\Registry::set('application', $this->_app);

        return $this->_app;
    }

    /**
     * 获取application
     *
     * @return \Yaf\Application
     */
    public function getApplication() {
        $application = \Yaf\Registry::get('application');
        if (!$application) {
            $application = $this->setApplication();
        }

        return $application;
    }

    /**
     * 创建一个简单请求，并利用调度器接受Repsonse信息，指定分发请求。
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param array $params
     *
     * @return mixed
     * */
    public function requestActionAndParseBody($module = 'Test',
                                                $controller = 'Test',
                                                $action = 'Test',
                                                $params = array()) {

        $request = new \Yaf\Request\Simple("CLI", $module, $controller, $action, $params);
        $response = $this->getApplication()->getDispatcher()->returnResponse(TRUE)->dispatch($request);
        return $response->getBody();
    }




    //======================================================
    //暂时不用
    //======================================================
    protected function _dispatch($request) {
        try {
            $response = $this->getApplication()->getDispatcher()
                ->catchException(false)
                ->returnResponse(true)
                ->dispatch($request);
            $content  = $response->getBody();
        } catch (Exception $exc) {
            $content = json_encode(array('errno' => $exc->getCode()));
        }

        return json_decode($content, true);
    }

    protected function _test($listTestData) {
        foreach ($listTestData as $testData) {
            if (isset($testData['cookie'])) {
                $_COOKIE = $testData['cookie'];
            }
            if (isset($testData['post'])) {
                $_POST = $testData['post'];
            }
            if (isset($testData['get'])) {
                $_GET = $testData['get'];
            }
            $request = new \Yaf\Request\Simple("CLI", $testData['request'][0], $testData['request'][1], $testData['request'][2], $_GET);
            $data    = $this->_dispatch($request);
            $this->assertSame($testData['code'], $data['errno']);
            if (isset($testData['data'])) {
                $this->assertEquals($testData['data'], $data['data']);
            }
        }
    }
    //======================================================
}