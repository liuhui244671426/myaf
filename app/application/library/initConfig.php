<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-9-11 下午5:39
 * @Desc : 初始化app
 */
initConfig::init();

class initConfig
{
    static private $_config;
    static private $_extendConfig;

    /**
     * 初始化
     * */
    static public function init()
    {
        if (!Yaf_Registry::has('config')) {
            self::setConfig();
        }
        self::$_config = Yaf_Registry::get('config');
        //先加载文件
        self::initLoad();
        //注册惰性加载器
        spl_autoload_register(array(new self(), 'autoLoader'));
    }

    /**
     * 初始化进程时加载必要文件
     * */
    static public function initLoad()
    {
        $libraryFiles = array(
            'constant', 'status', 'functions', 'YafController', 'DataCenter/DataCenter'
        );
        foreach ($libraryFiles as $file) {
            $file = APPLICATION_PATH . '/application/library/' . $file . '.php';
            if (file_exists($file)) {
                Yaf_Loader::import($file);
            } else {
                $msg = 'load library/' . $file . ' file is not exists';
                throw new LogicException($msg, EXC_CODE_LIBRARY_NOT_FOUND);
            }
        }
    }

    /**
     * 触发异常而不是错误
     * @param string $class 文件类名
     * */
    public function autoLoader($class)
    {
        if (strpos($class, 'Builder')) {
            $path = sprintf('%s/application/views/builders/%s.php', APPLICATION_PATH, $class);

            if (file_exists($path)) {
                Yaf_Loader::import($path);
            } else {
                $msg = 'load builder/' . $class . ' file is not exists';
                throw new LogicException($msg, EXC_CODE_BUILDER_NOT_FOUND);
            }
        }
    }

    /**
     * 设置配置文件,首先设置config,然后设置扩展config
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
        if (!empty($optionArr)) {
            foreach ($optionArr as $k => $v) {
                $v = trim($v);
                if (!empty($v))//非空
                {
                    $extendIni = new Yaf_Config_Ini(sprintf('%s/config/%s.ini', ROOT_PATH, $v), MODE);
                    Yaf_Registry::set(sprintf('config_%s', $v), $extendIni);
                }
            }
        }
    }

    /**
     * 是否存在某项扩展配置
     * @param string $iniName (例如: twig)
     * @return bool 存在(true)|不存在(false)
     * */
    static public function isSupportExtendConfig($iniName)
    {
        $optionArr = self::getExtendConfigs();
        //扩展配置项 --非空
        if (!empty($optionArr)) {
            if (!empty($optionArr) && in_array($iniName, $optionArr)) {
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
    static public function getExtendConfigs()
    {
        if(empty(self::$_extendConfig)){
            $config = Yaf_Registry::get('config');
            $option = $config->extend->config;
            if (!empty($option)) {
                $optionArr = explode(',', $option);
                self::$_extendConfig = $optionArr;
                return $optionArr;
            } else {
                return false;
            }
        } else {
            return self::$_extendConfig;
        }
    }
}
