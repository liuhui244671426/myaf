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

/**
 * 判断是否爬虫，范围略大
 *
 * @return bool
 */
function isSpider()
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spiders = array('spider', 'bot');
        foreach ($spiders as $spider) {
            if (strpos($ua, $spider) !== false) {
                return true;
            }
        }
    }

    return false;
}

/**
 * 判断是否命令行执行
 *
 * @return bool
 */
function isCli()
{
    if (isset($_SERVER['SHELL']) && !isset($_SERVER['HTTP_HOST'])) {
        return true;
    }

    return false;
}

/**
 * 判断是否64位架构
 *
 * @return bool
 */
function isX86_64arch()
{
    return (PHP_INT_MAX == '9223372036854775807');
}

/**
 * 判断是否为提交操作
 *
 * @param string $submit
 *
 * @return bool
 */
function isSubmit($submit)
{
    return (isset($_POST[$submit]) ||
        isset($_POST[$submit . '_x']) ||
        isset($_POST[$submit . '_y']) ||
        isset($_GET[$submit]) ||
        isset($_GET[$submit . '_x']) ||
        isset($_GET[$submit . '_y']));
}
//-------------------------------------
//is 系列函数
//-------------------------------------