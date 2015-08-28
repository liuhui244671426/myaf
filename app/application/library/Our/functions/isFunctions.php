<?php
/**
 * @Desc: is series functions
 * 返回值只有Bool
 * @User: liuhui
 * @Date: 15-6-3 下午9:20 
 */

//-------------------------------------
//is 系列函数
//-------------------------------------
/**
 * 是否是墨迹客户端的UA
 * @return bool
 * */
function isMojiApp(){
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if(preg_match('/mojia|mojii/i', $ua) > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * 时间是否过期
 * @param integer $time 获取到的时间
 * @param integer $offset 过期时间(秒)
 * @return bool
 */
function isTimeExpire($time, $offset = '120')
{
    $sTime = TODAY - $offset;
    $eTime = TODAY + $offset;
    if ($time >= $sTime && $time <= $eTime) {
        return true;
    }
    return false;
}

/**
 * 放弃使用正则校验
 * @param string $var 邮箱
 * @return bool
 */
function isEmail($var)
{
    return (filter_var($var, FILTER_VALIDATE_EMAIL) !== false) ? true : false;
}

/**
 * 校验密码是否符合规则长度
 * @param string $password 密码
 * @return bool false(弱密码)|true(合格)
 */
function isPassword($password)
{
    $weakArray = weakPassword();
    if (in_array($password, $weakArray, true)) {
        return false;
    }

    $strlen = strlen($password);
    if ($strlen >= 6 && $strlen <= 20)
        return true;

    return false;
}

/**
 * 是否晚上
 * @return bool
 */
function isNight()
{
    $h = date('H', TODAY);
    if ($h >= 7 && $h < 19) {
        return false;
    }
    return true;
}

/**
 * 是否夏天
 * @return bool
 */
function isSummer()
{
    $month = date('m', TODAY);
    if ($month >= 6 && $month <= 9) {
        return true;
    }
    return false;
}
/**
 * 是否正确来源
 * todo 完善
 * @param array $urls 待检查的url地址
 * */
function isReferer(array $urls){
    $referer = $_SERVER['HTTP_REFERER'];
}
/**
 * POST是否同源
 * todo test
 * @param string $domain 正确域名
 * */
function isOrigin($domain){
    $origin = $_SERVER['HTTP_ORIGIN'];
    importFunc('netFunctions');
    return (stripos($origin, getDomain()) === false)?
        false:
        true;
}

//-------------------------------------
//is 系列函数
//-------------------------------------