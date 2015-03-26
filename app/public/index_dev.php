<?php
    /**
     * Yaf 入口文件
     * @author : 刘辉
     */

    define('MODE', 'dev');//运行环境
    define('INAPP', true);
    date_default_timezone_set('PRC');

    if(MODE == 'dev' || MODE == 'test') {
        error_reporting(E_ALL);
        define('XHPROF', true);//开启xhprof
    } else {
        error_reporting(0);
        define('XHPROF', false);
    }

    if (phpversion() >= "5.3") {
        define("PUBLIC_PATH", __DIR__);
    } else {
        define("PUBLIC_PATH", dirname(__FILE__));
    }

    define('APPLICATION_PATH', realpath(PUBLIC_PATH . '/../')); //app dir
    define('ROOT_PATH', realpath(APPLICATION_PATH . '/../')); //root dir

    if (!extension_loaded('yaf')) {
        include(APPLICATION_PATH . '/application/library/framework/loader.php');
    }

    //------xhprof--------//
    if(XHPROF)
    {
        //xhprof_enable();
    }

    $app = new Yaf_Application(APPLICATION_PATH . "/application/configs/application.ini", 'production');
    $app->bootstrap()->run();

    //------xhprof--------//
    if(XHPROF)
    {
        //结束，然后写入文件，注意目录
        //$xhprof_data = xhprof_disable();
        //$XHPROF_ROOT = sprintf('%s%s', ROOT_PATH, '/library/xhprof');
        //include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
        //include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
        //$xhprof_runs = new XHProfRuns_Default();
        //$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
        //echo "<a href='http://localhost/myaf/library/xhprof/xhprof_html/?run=$run_id&source=xhprof_foo'>分析</a>";
    }
