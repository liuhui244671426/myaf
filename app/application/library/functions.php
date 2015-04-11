<?php
/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-1-16
 * @Time: 上午12:00
 * @Desc: 公共函数库
 */

/**
 * http状态
 * @param integer $code http代码(404)
 * @return string
 */
function httpStatus($code)
{
    $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if (array_key_exists($code, $_status)) {
        //return sprintf('Status: %s %s', $code, $_status[$code]);//确保FastCGI模式下正常
        return sprintf('%s %s %s', $_SERVER['SERVER_PROTOCOL'], $code, $_status[$code]);
    }
}

/**
 * 是否晚上
 * @return bool
 */
function isNight()
{
    $h = date('H', TODAY);
    //echo $h;
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
 * 获取当前url
 * @return string
 */
function getCurrentUri()
{
    return getDomain() . $_SERVER['REQUEST_URI'];
}

/**
 * 获取当前域名
 * @return string
 */
function getDomain()
{
    return 'http://' . $_SERVER['HTTP_HOST'];
}

/**
 * 放弃使用正则校验
 * @param string $var 邮箱
 */
function isEmail($var)
{
    return (filter_var($var, FILTER_VALIDATE_EMAIL) !== false) ? true : false;
}

/**
 * 校验密码是否符合规则长度
 * @param string $password 密码
 * @return bool
 */
function isPassword($password)
{
    $weakArray = weakPassword();
    if (in_array($password, $weakArray)) {
        return false;//弱密码
    }

    $strlen = strlen($password);
    if ($strlen >= 6 && $strlen <= 20) return true;
    return false;
}

/**
 * 弱密码集合
 * @return array
 */
function weakPassword()
{
    return $weakArray = array(
        0 => '000000', 1 => '111111', 2 => '11111111', 3 => '112233', 4 => '123123',
        5 => '123321', 6 => '123456', 7 => '12345678', 8 => '654321', 9 => '666666',
        10 => '888888', 11 => 'abcdef', 12 => 'abcabc', 13 => 'abc123', 14 => 'a1b2c3',
        15 => 'aaa111', 16 => '123qwe', 17 => 'qwerty', 18 => 'qweasd', 19 => 'admin',
        20 => 'password', 21 => 'p@ssword', 22 => 'passwd', 23 => 'iloveyou', 24 => '5201314',
        30 => 'monkey', 31 => '1234567', 32 => 'letmein', 33 => 'trustno1', 34 => 'dragon',
        35 => 'baseball', 38 => 'master', 39 => 'sunshine', 40 => 'ashley',
        41 => 'bailey', 42 => 'passw0rd', 43 => 'shadow', 46 => 'superman', 47 => 'qazwsx',
        48 => 'michael', 49 => 'football', 52 => 'xiaoming', 56 => 'qq123456', 57 => 'taobao',
        58 => 'root', 59 => 'wang1234',
    );
}

/**
 * 取得文件扩展
 * @param string $filename 文件名(例如：test.jpg)
 * @return string (jpg)
 */
function fileext($filename)
{
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 随机字符串
 * @param integer $len 字符长度(default=4)
 * @return string
 */
function randString($len = 4)
{
    $string = appString();
    $stringLen = strlen($string) - 1;
    $newString = '';
    for ($i = 1; $i <= $len; $i++) {
        $pos = rand(0, $stringLen);
        $newString .= $string[$pos];
    }

    return $newString;
}

/**
 * 有效字符
 * @return string
 */
function appString()
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    return $string;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else
        return $output;
}

/**
 * 记录日志,路径/logs/年－月－日/时.log
 * @param mixed $var
 */
function Yaflog($var)
{
    $config = Yaf_Registry::get('config');
    $logLevel = $config['log']['level'];
    $logPath = $config['log']['path'];
    //是否开启debug模式
    if ($logLevel > 0) {
        $debugInfo = debug_backtrace();
        $lineNum = $debugInfo[0]['line'];
        $filename = $debugInfo[0]['file'];

        $time = TODAY;
        $timeH = date('H', $time);
        $timeHIS = date('H:i:s', $time);
        $timeYMD = date('Y-m-d', $time);

        $todayDir = sprintf('%s%s/', $logPath, $timeYMD);
        if (!file_exists($todayDir)) {
            mkdir($todayDir);
        }

        $logFile = sprintf('%s%s.log', $todayDir, $timeH);
        $msg = sprintf('%s-%s-%s:%s %s%s', $timeHIS, '[debug]', $filename, $lineNum, var_export($var, true), PHP_EOL);
        file_put_contents($logFile, $msg, FILE_APPEND);
    }
}

/**
 * 获取浏览器的ip地址
 * @return string
 */
function IP()
{
    $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos) unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

function EchoJsonString(array $data)
{
    header('Content-Type:application/json;charset=utf8');
    echo json_encode($data);
    exit;
}

function formatData($code, $data = null, $dataType = 'json')
{
    header('Content-type: application/json;charset=utf-8');
    if ($dataType == 'json') {
        echo json_encode(array(
            'code' => $code,
            'msg' => $code,
            'data' => $data
        ));
        return;
    }
}

/**
 * error handler
 * @param integer $errno 错误代码
 * @param string $errstr 错误提示
 * @param string $errfile 错误文件名
 * @param string $errline 错误行数
 * @return void
 */
function sysErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    $config = Yaf_Registry::get('config');
    if ($config['sysError']['catch']) {
        $errMsg = sprintf('<?php exit;?>%s | code: %s | msg: %s | file: %s | line: %s' . PHP_EOL,
            date('Y-m-d H:i:s', TODAY), $errno, str_pad($errstr, 45), $errfile, $errline);
        error_log($errMsg, 3, ROOT_PATH . '/logs/sysErrorHandler.log');
    }
}

/**
 * fatal error handler
 * 记录fatal错误
 * */
function sysShutdown()
{
    $err = error_get_last();
    $errno = $err['type'];
    $errstr = $err['message'];
    $errfile = $err['file'];
    $errline = $err['line'];
    $errMsg = sprintf('<?php exit;?>%s | code: %s | msg: %s | file: %s | line: %s' . PHP_EOL,
        date('Y-m-d H:i:s', TODAY), $errno, str_pad($errstr, 45), $errfile, $errline);
    error_log($errMsg, 3, ROOT_PATH . '/logs/sysShutdown.log');
}

function getActions($class)
{
    $reflection = new ReflectionClass($class);
    $methods = $reflection->getMethods();
    $actions = array();
    foreach ($methods as $k => $v) {
        if (false != stripos($v->name, 'action')) {
            $arr = array('name' => $v->name, 'class' => $v->class);
            array_push($actions, $arr);
        }
    }
    return $actions;
}

/**
 * 低版本array_column
 *
 * @param array $input 待查询数组
 * @param string $columnKey 需查询的列
 * @param string $indexKey 索引
 */
function i_array_column($input, $columnKey, $indexKey = null)
{
    if (!function_exists('array_column')) {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
        $result = array();
        foreach ((array)$input as $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if (!$indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key)) ? current($key) : null;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    } else {
        return array_column($input, $columnKey, $indexKey);
    }
}

/**
 * 时间过期
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
 * aes解密
 * @param string $val
 * @param string $key
 * @return string
 * */
function aesDecrypt($val, $key)
{
    $mode = MCRYPT_MODE_ECB;
    $enc = MCRYPT_RIJNDAEL_128;
    return preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', mcrypt_decrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM)));
    //return mcrypt_decrypt($enc, $key, $val, $mode, mcrypt_create_iv( mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
}

/**
 * aes加密
 * @param string $val
 * @param string $key
 * @return string
 * */
function aesEncrypt($val, $key)
{
    $mode = MCRYPT_MODE_ECB;
    $enc = MCRYPT_RIJNDAEL_128;
    $val = str_pad($val, (16 * (floor(strlen($val) / 16) + 1)), chr(16 - (strlen($val) % 16)));
    return mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
}