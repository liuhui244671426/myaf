<?php
/**
 * @Create Author : huiliu//刘辉
 * @Create Time: 14-9-28 下午4:21
 * @Desc : 
 */
class dateUtil
{
    static function formatDate($separator, $time)
    {
        $format = sprintf('m%sd', $separator);
        return date($format, $time);
    }

    /*
     * 获取月日
     * */
    static function getMonthDay()
    {
        $month = date('m', time()) . L('month_nuit');
        $day = date('d', time()) . L('day_unit');
        $date = sprintf('%s%s', $month, $day);
        return $date;
    }

    /*
     * 获取 星期x 中文
     * */
    static function getZhWeek()
    {
        $day = date('w', time());
        switch($day)
        {
            case 0:
                $txt = '天';
                break;
            case 1:
                $txt = '一';
                break;
            case 2:
                $txt = '二';
                break;
            case 3:
                $txt = '三';
                break;
            case 4:
                $txt = '四';
                break;
            case 5:
                $txt = '五';
                break;
            case 6:
                $txt = '六';
                break;
        }
        return $txt;
    }

    /*
     * 获取xx年xx月xx日
     * */
    static function getYearMonthDay($separator, $timeStamp)
    {
        $date = date(sprintf('Y%sm%sd', $separator, $separator, $separator), $timeStamp);
        return $date;
    }
}