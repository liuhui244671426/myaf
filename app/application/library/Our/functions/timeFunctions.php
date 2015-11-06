<?php

/**
* 计算时间戳相隔时间
* */
function diff_date($date1, $date2){
    $date1 = (strlen($date1) < 14) ?
    str_pad($date1, 14, 0) : $date1;
    $date2 = (strlen($date2) < 14) ?
        str_pad($date2, 14, 0) : $date2;

    $date1 = strtotime($date1);//开始时间 时间戳
    $date2 = strtotime($date2);//结束时间 时间戳
    if($date1 < $date2){
        $two = $date2;
        $one = $date1;
    } else {
        $two = $date1;
        $one = $date2;
    }
    $cle = $two - $one; //得出时间戳差值

    /*Rming()函数，即舍去法取整*/
    $d = floor($cle/3600/24);
    $h = floor(($cle%(3600*24))/3600);  //%取余
    $m = floor(($cle%(3600*24))%3600/60);
    $s = floor(($cle%(3600*24))%60);

    return array('days' => $d, 'hours' => $h,
                 'min' => $m, 'sec' => $s,
                'start_time' => $one, 'end_time' => $two
        );
}
    /**
     * 获取间隔时间数组
     * */
function time_array($data){
    $days = $data['days'] * 24;
    $count = $days + $data['hours'];
    $arr = array();

    for($i = 0; $i <= $count; $i++ ){
        $arr[] = date('YmdH',
            strtotime(
                sprintf('+%d hours', $i), $data['start_time']));
    }
    return $arr;
}

/**
 * 计算两个时间戳之间的间距
 * */
function tmspan($timestamp, $current_time = 0)
{
    if (!$current_time) $current_time = time();
    $span = $current_time - $timestamp;
    if ($span < 60) {
        return "刚刚";
    } else if ($span < 3600) {
        return intval($span / 60) . "分钟前";
    } else if ($span < 24 * 3600) {
        return intval($span / 3600) . "小时前";
    } else if ($span < (7 * 24 * 3600)) {
        return intval($span / (24 * 3600)) . "天前";
    } else {
        return date('Y-m-d', $timestamp);
    }
}


