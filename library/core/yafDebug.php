<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-9-11 下午8:56
 * @Desc : 
 */

class yafDebug
{
    static public $_logPath;
    static public $_logLv;

    static public function log($var)
    {
        //是否开启debug模式
        if(self::$_logLv > 0)
        {
            $debugInfo = debug_backtrace();
            //$filename = explode(DIRECTORY_SEPARATOR, $debugInfo[0]['file']);
            //$filename = end($filename);//get end element
            $lineNum = $debugInfo[0]['line'];
            $filename = $debugInfo[0]['file'];

            $time = TODAY;
            $timeH = date('H', $time);
            $timeHIS = date('H:i:s', $time);
            $timeYMD = date('Y-m-d', $time);

            $todayDir = sprintf('%s%s/', self::$_logPath, $timeYMD);
            //print_r($todayDir);
            if(!file_exists($todayDir))
            {
                mkdir($todayDir);
            }

            $logFile = sprintf('%s%s.log', $todayDir, $timeH);
            $msg = sprintf('%s-%s-%s:%s %s%s', $timeHIS, '[debug]', $filename, $lineNum, var_export($var, true), PHP_EOL);
            file_put_contents($logFile, $msg, FILE_APPEND);
        }
    }

    /*
     * 页面dump
     * 输出不同的页面颜色
     * */
    static public function dump($var)
    {
        echo '<pre style="background-color:' . self::dumpColor() . ';">';
        var_dump($var);
        echo '</pre>';
    }

    /*
     * 不同的颜色
     * */
    static private function dumpColor()
    {
        $color = array(
            'blue', 'green', 'purple', 'yellow', 'glob', 'pink', 'palegoldenrod', 'palegreen',
            'yellowgreen', 'yellow', 'wheat', 'violet', 'tomato', 'steelblue', 'skyblue', 'sienna', 'seashell',
            'seagreen', 'sandybrown', 'peru', 'peachpuff', 'papayawhip', 'thistle', 'slategray'
        );
        $countColor = count($color) - 1;
        $rand = rand(0, $countColor);
        return $color[$rand];
    }
}