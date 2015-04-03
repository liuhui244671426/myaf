<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-9-11 下午5:39
 * @Desc : 初始化app
 */
initConfig::init();

class initConfig
{
    static public $_config;

    /**
     * 初始化
     * */
    static public function init()
    {
        if(!Yaf_Registry::has('config'))
        {
            self::setConfig();
        }
        self::$_config = Yaf_Registry::get('config');
        self::initLoad();

        spl_autoload_register(array(new self(), 'autoLoader'));
    }
    /**
     * 加载文件
     */
    static public function initLoad()
    {
        $libraryFiles = array(
            'constant', 'status', 'functions', 'YafController', 'DataCenter/DataCenter'
        );
        foreach($libraryFiles as $file){
            $file = APPLICATION_PATH . '/application/library/'.$file.'.php';
            if(file_exists($file)){
                Yaf_Loader::import($file);
            }
        }
    }

    /**
     * 触发异常而不是错误
     * */
    public function autoLoader($class)
    {
        if(strpos($class,'Builder')){
            $path = sprintf('%s/application/views/builders/%s.php', APPLICATION_PATH, $class);

            if(!file_exists($path)){
                $msg = 'load builder file is not exists '.$class;
                throw new LogicException($msg);
            }
            Yaf_Loader::import($path);
        }


    }

    /**
     * 设置配置文件
     */
    static public function setConfig()
    {
        $appIni = new Yaf_Config_Ini(sprintf('%s/config/%s', ROOT_PATH, 'app.ini'), MODE);
        Yaf_Registry::set('config', $appIni);
        self::setExtendConfig();
    }

    /**
     * 设置扩展配置信息存入
     * @keys array array('config', 'config_xx', 'config_xxx')
     * */
    static public function setExtendConfig()
    {
        $optionArr = self::getExtendConfigs();
        if(!empty($optionArr))
        {
            foreach($optionArr as $k => $v)
            {
                $v = trim($v);
                if(!empty($v))//非空
                {
                    $extendIni = new Yaf_Config_Ini(sprintf('%s/config/%s.ini', ROOT_PATH, $v), MODE);
                    Yaf_Registry::set(sprintf('config_%s', $v), $extendIni);
                }
            }
        }
    }

    /**
     * 是否存在某项扩展配置
     * @param string $iniName(例如: twig)
     * */
    static public function isSupportExtendConfig($iniName){

        $optionArr = self::getExtendConfigs();
        //扩展配置项 --非空
        if(!empty($optionArr))
        {
            if(!empty($optionArr) && in_array($iniName, $optionArr)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取扩展配置集合
     * @return mixed array|false
     * */
    static public function getExtendConfigs(){
        $config = Yaf_Registry::get('config');
        $option = $config->extend->config;
        if(!empty($option)){
            $optionArr = explode(',', $option);
            return $optionArr;
        } else {
            return false;
        }
    }
}
