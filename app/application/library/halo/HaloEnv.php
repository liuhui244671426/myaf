<?php

class HaloEnv
{
    public static $env = array();
//    public static function instance ( $configFileName , $section  )
//    {
//        require_once 'HaloConfigIni.php';
//        $ini = new HaloConfigIni( $configFileName ,$section );
//        HaloEnv::$env['config'] = $ini->config;
//    }

    public static function instance($configData)
    {
        HaloEnv::$env['config'] = $configData;
    }

    public static function isRegistered($key)
    {
        return isset(HaloEnv::$env[$key]);
    }

    public static function getConfig()
    {
        return HaloEnv::$env['config'];
    }

    public static function set($key, $value)
    {
        HaloEnv::$env[$key] = $value;
    }

    public static function get($index)
    {
        if (isset (HaloEnv::$env[$index]))
            return HaloEnv::$env[$index];
        else
            return null;
    }

    public static function logLevel()
    {
        $config = HaloEnv::get('config');
        $logLevel = $config['log_level'];
        if ($logLevel == null) {
            return 100;
        }
        return $logLevel;
    }

    public static function isDebugVersion()
    {
        $config = HaloEnv::get('config');
        return (1 == $config['debug']);
    }

    public static function androidVersion()
    {
        $version = null;
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $reg = '/(.*)[a|A]ndroid(\s)*(\d*).(.*)$/';
        if (preg_match($reg, $ua, $matches)) {
            $version = $matches[3];
        }
        return $version;
    }

    public static function isMobileDevice()
    {
        $mobileUa = array('iPhone', 'Android', 'MIDP', 'Opera Mobi', 'Opera Mini', 'BlackBerry', 'HP iPAQ', 'IEMobile', 'Samsung',
            'MSIEMobile', 'Windows Phone', 'HTC', 'LG', 'MOT', 'Nokia', 'Symbian', 'Fennec', 'Maemo', 'Tear', 'Midori',
            'Windows CE', 'WindowsCE', 'Smartphone', '240x320', '176x220', '320x320', '160x160', 'webOS', 'Palm',
            'armv', 'Sagem', 'SGH', 'SonyEricsson', 'MMP', 'UCWEB');

        $ua = $_SERVER['HTTP_USER_AGENT'];
        if ($_REQUEST['from'] == 'mobile')
            return true;
        foreach ($mobileUa as $v) {
            if (strpos($ua, $v) !== false) {
                return true;
            }
        }

        $config = HaloEnv::get('config');
        if ($config['mobile_dev']) {
            return true;
        }

        return false;
    }

    public static function webHost()
    {
        $config = HaloEnv::get('config');
        return $config['web_host'];
    }

    public static function mobileHost()
    {
        $config = HaloEnv::get('config');
        return $config['mobile_host'];
    }

    public static function checkAccessType()
    {
        if (isset($_REQUEST['acc_type'])) {
            $_COOKIE['acc_type'] = $_REQUEST['acc_type'];
            header('Set-Cookie:acc_type=' . $_REQUEST['acc_type']);
        }
        Logger::DEBUG($_REQUEST['acc_type']);
        Logger::DEBUG($_COOKIE['acc_type']);
    }

    public static function isAccessSite($type)
    {
        HaloEnv::checkAccessType();
        return (isset($_COOKIE['acc_type']) && ($_COOKIE['acc_type'] == $type));
    }

    public static function isReqFromiOSDevice()
    {
        $iOSValidUserAgents = array('iPad', 'iPhone');

        $ua = $_SERVER['HTTP_USER_AGENT'];

        foreach ($iOSValidUserAgents as $v) {
            if (strpos($ua, $v) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function isReqFromAndroidDevice()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($ua, "Android")) {
            return true;
        }
        return false;
    }

    public static function recmdOppZmqHost()
    {
        $config = HaloEnv::get('config');
        return $config['zmq']['opp'];
    }

    public static function recmdUserZmqHost()
    {
        $config = HaloEnv::get('config');
        return $config['zmq']['user'];
    }

    public static function recmdCompanyZmqHost()
    {
        $config = HaloEnv::get('config');
        return $config['zmq']['company'];
    }

    public static function smartRecmdZmqHost()
    {
        $config = HaloEnv::get('config');
        return $config['zmq']['smart'];
    }

    public static function searchZmqHost()
    {
        $config = HaloEnv::get('config');
        return $config['zmq']['search'];
    }

    public static function serviceAddr()
    {
        $config = HaloEnv::get('config');
        return $config['service']['addr'];
    }


    public static function productModel()
    {
        $config = HaloEnv::get('config');
        return $config['product']['model'];
    }

}