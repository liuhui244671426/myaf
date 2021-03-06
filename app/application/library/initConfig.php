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
        if (!YafRegistryHas('config')) {
            self::setConfig();
        }
        self::$_config = YafRegistry('config');
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
            'constant', 'status', 'Our/Controller/YafController', 'Our/Halo/HaloFactory', 'Our/Halo/HaloLogger'
        );
        foreach ($libraryFiles as $file) {
            $file = APPLICATION_PATH . '/application/library/' . $file . '.php';

            import($file);
        }
    }

    /**
     * 触发异常而不是错误
     * @param string $class 文件类名
     * */
    public function autoLoader($class)
    {

        if (strpos($class, 'Builder')) {
            $file = sprintf('%s/application/views/builders/%s.php', APPLICATION_PATH, $class);

            import($file);
        }
    }

    /**
     * 设置配置文件,首先设置config,然后设置扩展config
     */
    static public function setConfig()
    {
        $appIni = new \Yaf\Config\Ini(sprintf('%s/config/%s', ROOT_PATH, 'app.ini'), MODE);
        YafRegistry('config', $appIni);
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
                    $extendIni = new \Yaf\Config\Ini(sprintf('%s/config/%s.ini', ROOT_PATH, $v), MODE);
                    YafRegistry(sprintf('config_%s', $v), $extendIni);
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
            $config = YafRegistry('config');
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
