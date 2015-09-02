<?php
/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-1-16 上午12:00
 * @Desc: 公共函数库,程序启动就会加载
 */
/**
 * 加载文件
 * @param string $path
 * @return \Yaf_Exception_LoadFailed
 * */

function import($path){
    $isTrue = \Yaf\Loader::import($path);
    if(!$isTrue){
        $msg = 'load ' . $path . ' file return false';
        throw new \Yaf_Exception_LoadFailed($msg);
    }
}
/**
 * 加载函数文件
 * */
function importFunc($file){
    import(LIBRARY_PATH . 'Our/functions/' . $file . '.php');
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
 * 随机字符串
 *
 * @param integer $len 字符长度(default=4)
 * @param integer $type 随即串的类型
 * @return string
 */
function randString($len = 4, $type = 1)
{
    $string = appString($type);
    $stringLen = strlen($string) - 1;
    $newString = '';
    for ($i = 1; $i <= $len; $i++) {
        $pos = mt_rand(0, $stringLen);
        $newString .= $string[$pos];
    }

    return $newString;
}

/**
 * 有效字符
 *
 * @param integer $type
 * @return string
 */
function appString($type)
{
    switch($type){
        case 1:
            $string = '0123456789';
            break;
        case 2:
            $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        default:
            $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
    }
    return $string;
}

//-------------------------------------
//debug 系列函数
//-------------------------------------

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
 * todo 已废弃
 */
function Yaflog($var)
{
    $config = YafRegistry('config');
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
        $isWrited = file_put_contents($logFile, $msg, FILE_APPEND);
        if($isWrited === false){
            $msg = 'log file: ' . $logFile . ' write-disable';
            throw new \LogicException($msg);
        }
    }
}
//-------------------------------------
//debug 系列函数
//-------------------------------------


//-------------------------------------
//response 系列函数
//-------------------------------------
/**
 * 输出格式化的JSON串
 * 后续版本将遗弃它
 * @param int $code
 * @param array $data
 * @param string $jsonp 跨域
 * @return string
 * */
function echoJsonString($code, array $data, $jsonp = '')
{
    header('Content-Type:application/json;charset=utf8');
    $out = json_encode(array(
        'code' => $code,
        'msg' => stringMsg($code),
        'data' => $data
    ));
    if(empty($jsonp)){
        echo $out;
    } else {
        echo $jsonp . '(' . $out . ')';
    }

    exit;
}

/**
 * JSON使用的信息
 * @param int $code
 * @return string
 * */
function stringMsg($code){
    $arr = array(
        0 => 'Success',
        1 => 'Failed',
        2 => '您没有该操作权限'
    );
    return $arr[$code];
}
//-------------------------------------
//response 系列函数
//-------------------------------------


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
 * 获取文件扩展名
 *
 * @param string $file
 *
 * @return mixed|string
 */
function getFileExtension($file)
{
    if (is_uploaded_file($file)) {
        return "unknown";
    }

    return pathinfo($file, PATHINFO_EXTENSION);
}
//-------------------------------------
//errorHandler 系列函数
//-------------------------------------
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

    $config = \Yaf\Registry::get('config');
    if ($config['sysError']['catch']) {
        $errMsg = sprintf(PHP_EOL . '<?php exit;?>%s | code: %s | msg: %s | file: %s | line: %s' . PHP_EOL,
            date('Y-m-d H:i:s', TODAY), $errno, str_pad($errstr, 45), $errfile, $errline);
        error_log($errMsg, 3, ROOT_PATH . '/data/logs/sysErrorHandler.log');
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
//-------------------------------------
//errorHandler 系列函数
//-------------------------------------

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
 * 框架的错误信息
 * @param int $code 错误码
 * @URL: http://yaf.laruence.com/manual/yaf.constant.html
 * @return string
 * */
function YafErrorCode($code){
    $errorDocker = array (
        //表示启动失败
        512 => 'YAF_ERR_STARTUP_FAILED',
        //表示路由失败
        513 => 'YAF_ERR_ROUTE_FAILED',
        //表示分发失败
        514 => 'YAF_ERR_DISPATCH_FAILED',
        //表示找不到指定的模块
        515 => 'YAF_ERR_NOTFOUND_MODULE',
        //表示找不到指定的Controller
        516 => 'YAF_ERR_NOTFOUND_CONTROLLER',
        //表示找不到指定的Action
        517 => 'YAF_ERR_NOTFOUND_ACTION',
        //表示找不到指定的视图文件
        518 => 'YAF_ERR_NOTFOUND_VIEW',
        //表示调用失败
        519 => 'YAF_ERR_CALL_FAILED',
        //表示自动加载类失败
        520 => 'YAF_ERR_AUTOLOAD_FAILED',
        //表示关键逻辑的参数错误
        521 => 'YAF_ERR_TYPE_ERROR',
    );
    $errorDocker = array (
        //表示启动失败
        512 => 'YAF\ERR\STARTUP\FAILED',
        //表示路由失败
        513 => 'YAF\ERR\ROUTE\FAILED',
        //表示分发失败
        514 => 'YAF\ERR\DISPATCH\FAILED',
        //表示找不到指定的模块
        515 => 'YAF\ERR\NOTFOUND\MODULE',
        //表示找不到指定的Controller
        516 => 'YAF\ERR\NOTFOUND\CONTROLLER',
        //表示找不到指定的Action
        517 => 'YAF\ERR\NOTFOUND\ACTION',
        //表示找不到指定的视图文件
        518 => 'YAF\ERR\NOTFOUND\VIEW',
        //表示调用失败
        519 => 'YAF\ERR\CALL\FAILED',
        //表示自动加载类失败
        520 => 'YAF\ERR\AUTOLOAD\FAILED',
        //表示关键逻辑的参数错误
        521 => 'YAF\ERR\TYPE\ERROR',
    );
    return $errorDocker[$code];
}

//-------------------------------------
//\Yaf\Registry 系列函数
//-------------------------------------
function YafRegistry($name, $value = ''){
    if(empty($value)){
        if(YafRegistryHas($name) === true){
            return \Yaf\Registry::get($name);
        } else {
            return false;
        }

    } else {
        \Yaf\Registry::set($name, $value);
        return true;
    }
}

function YafRegistryHas($name){
    return \Yaf\Registry::has($name);
}

function YafRegistryDel($name){
    return \Yaf\Registry::del($name);
}
//-------------------------------------
//\Yaf\Registry 系列函数
//-------------------------------------


//-------------------------------------
//XSS 系列函数
//-------------------------------------
/**
 * XSS
 *
 * @param $str
 *
 * @return mixed
 */
function removeXSS($str)
{
    $str = str_replace('<!--  -->', '', $str);
    $str = preg_replace('~/\*[ ]+\*/~i', '', $str);
    $str = preg_replace('/\\\0{0,4}4[0-9a-f]/is', '', $str);
    $str = preg_replace('/\\\0{0,4}5[0-9a]/is', '', $str);
    $str = preg_replace('/\\\0{0,4}6[0-9a-f]/is', '', $str);
    $str = preg_replace('/\\\0{0,4}7[0-9a]/is', '', $str);
    $str = preg_replace('/&#x0{0,8}[0-9a-f]{2};/is', '', $str);
    $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);
    $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);

    $str = htmlspecialchars($str);
    //$str = preg_replace('/&lt;/i', '<', $str);
    //$str = preg_replace('/&gt;/i', '>', $str);

    // 非成对标签
    $lone_tags = array("img", "param", "br", "hr");
    foreach ($lone_tags as $key => $val) {
        $val = preg_quote($val);
        $str = preg_replace('/&lt;' . $val . '(.*)(\/?)&gt;/isU', '<' . $val . "\\1\\2>", $str);
        $str = self::transCase($str);
        $str = preg_replace_callback('/<' . $val . '(.+?)>/i', create_function('$temp', 'return str_replace("&quot;","\"",$temp[0]);'), $str);
    }
    $str = preg_replace('/&amp;/i', '&', $str);

    // 成对标签
    $double_tags = array("table", "tr", "td", "font", "a", "object", "embed", "p", "strong", "em", "u", "ol", "ul", "li", "div", "tbody", "span", "blockquote", "pre", "b", "font");
    foreach ($double_tags as $key => $val) {
        $val = preg_quote($val);
        $str = preg_replace('/&lt;' . $val . '(.*)&gt;/isU', '<' . $val . "\\1>", $str);
        $str = self::transCase($str);
        $str = preg_replace_callback('/<' . $val . '(.+?)>/i', create_function('$temp', 'return str_replace("&quot;","\"",$temp[0]);'), $str);
        $str = preg_replace('/&lt;\/' . $val . '&gt;/is', '</' . $val . ">", $str);
    }
    // 清理js
    $tags = Array(
        'javascript', 'vbscript', 'expression', 'applet',
        'meta', 'xml', 'behaviour', 'blink', 'link',
        'style', 'script', 'embed', 'object', 'iframe',
        'frame', 'frameset', 'ilayer', 'layer', 'bgsound',
        'title', 'base', 'font'
    );

    foreach ($tags as $tag) {
        $tag = preg_quote($tag);
        $str = preg_replace('/' . $tag . '\(.*\)/isU', '\\1', $str);
        $str = preg_replace('/' . $tag . '\s*:/isU', $tag . '\:', $str);
    }

    $str = preg_replace('/[\s]+on[\w]+[\s]*=/is', '', $str);

    return $str;
}

/**
 * 移除HTML中的危险代码，如iframe和script
 * @param $string $content
 * @param $string $allow
 * @return string
 */
function remove_xss($content, $allow = '')
{
    $danger = 'javascript,vbscript,expression,applet,meta,xml,blink,link,style,script,embed,object,iframe,frame,frameset,ilayer,layer,bgsound,title,base';
    $event = 'onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|' .
        'onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|' .
        'oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|' .
        'ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|' .
        'onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|' .
        'onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|' .
        'onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload';

    if (!empty($allow)) {
        $allows = explode(',', $allow);
        $danger = str_replace($allow, '', $danger);
    }
    $danger = str_replace(',', '|', $danger);
    //替换所有危险标签
    $content = preg_replace("/<\s*($danger)[^>]*>[^<]*(<\s*\/\s*\\1\s*>)?/is", '', $content);
    //替换所有危险的JS事件
    $content = preg_replace("/<([^>]*)($event)\s*\=([^>]*)>/is", "<\\1 \\3>", $content);
    return $content;
}

//-------------------------------------
//XSS 系列函数
//-------------------------------------