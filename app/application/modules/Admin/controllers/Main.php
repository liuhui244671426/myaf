<?php

class MainController extends BaseController
{
    public function doInit()
    {
        $this->_view->setLayout('Admin');
    }

    /**
     * 后台主页
     */
    public function mainAction()
    {
        $info = $this->sysInfo();

        $this->_view->display('Admin/main.phtml', $info);
    }

    public function testAction(){
        $db = new Admin_MainModel();
        $db->testRedis();

        $db->testMCD();

        dump(isMojiApp());
    }
    /**
     * 后台基本信息
     * @return array
     */
    protected function sysInfo()
    {
        $sys = array(
            'os' => PHP_OS,
            'runEnv' => $_SERVER['SERVER_SOFTWARE'],
            'runPhp' => php_sapi_name(),
            'phpVer' => phpversion(),
            'yafVer' => phpversion('yaf'),
            'serverTime' => date('Y年n月j日 H:i:s', TODAY),
            'memoryUsage' => round(memory_get_usage() / 1048576, 2) . ' MB',
            'diskUsage' => round((@disk_free_space(".") / (1024 * 1024)), 2) . ' MB',
            'maxUploadSize' => ini_get('upload_max_filesize'),
            'maxExecutionTime' => ini_get('max_execution_time') . ' sec',
            'loginIP' => IP(),
        );
        return $sys;
    }
}