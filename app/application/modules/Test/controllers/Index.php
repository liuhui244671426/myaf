<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-8-18 下午7:01
 * @Desc :
 */

class IndexController extends BaseController
{
    public function doInit()
    {
        //print_r($this->_appConfig);
        //pc_base::load_config('system','errorlog') ? set_error_handler('my_error_handler') : error_reporting(E_ERROR | E_WARNING | E_PARSE);
    }

    public function IndexAction()
    {
        //$htmlVarArray = array('a' => 'iphone', 'b' => '出6plus了');
        //$this->_view->display('index/index.phtml', $htmlVarArray);
        //默认跳到官网去
        $this->redirect('http://www.moji.com');
    }

    public function TestMonologAction()
    {
        echo 'test';
        $arg = $_REQUEST;
        $log = new Monolog\Logger(MODE);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_PATH . '/logs/test.log'));
        $log->addWarning($arg);
        $log->addError('logger error');
    }

    public function TestTwigAction(){
        $a = 'hello';
        $b = 'world';
        $c = APP_NAME;
        haha();

        $this->getView()->display('TestTwig.phtml', array('a' => $a, 'b' => $b, 'c' => $c));
    }

    public function clearCacheAction(){

        $handle = opendir(CACHE_PATH);
        while(($file = readdir($handle)) !== false){
            if($file != '.' && $file != '..') {
                print_r($file);

                if(rmdir(CACHE_PATH . $file . '/')){
                    echo 'delete file:'.$file.' success!' . PHP_EOL;
                } else {
                    throw new RuntimeException('Delete Directory but not empty', '1');
                }
            }
        }
        closedir($handle);
    }

    public function curlAction(){

        $curl = new Buzz\Browser();

        //$curl->get('http://www.tuicool.com/search', array('kw' => 'php最佳实践', 't' => 1));
        //print_r($curl->getLastRequest());
        //dump($curl);
        //dumpTP($curl);

        $url = 'http://grade.myaf.com/index/index/testmonolog';
        $headers = get_headers($url, 1);
        dump($headers);
    }

    public function trigger_errorAction(){
        trigger_error('liuhui trigger_error', E_USER_ERROR);
        echo 111;
    }

    public function jsonAction(){
        formatData(0, $_SERVER);
    }

    public function testAction(){
        echo 111111;
    }

    public function spotAction(){

        $mysqlCfg = Yaf_Registry::get('config_db');

        $user = $mysqlCfg['mysql']['cms']['user'];
        $pass = $mysqlCfg['mysql']['cms']['pass'];
        $host = $mysqlCfg['mysql']['cms']['host'];
        $dbname = $mysqlCfg['mysql']['cms']['dbname'];

        $dsn = sprintf('mysql://%s:%s@%s/%s', $user, $pass, $host, $dbname);
        $cfg = new \Spot\Config();
        $cfg->addConnection('mysql', $dsn);

        $spot = new \Spot\Locator($cfg);

        try{
            $mapper = $spot->mapper('Entity\Post');
        } catch(Exception $e){
            dump($e->toString());
        }
        //$id = $mapper->mostRecentPostsForSidebar();
    }

    public function liveAction(){
        $db = new indexModel();

        $result = $db->liveIndex();
        dump($result);
    }

    public function avatarAction(){
        $db = new indexModel();
        $result = $db->deyDetail();
        dump($result);
    }
}