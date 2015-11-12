<?php
/**
 * @Desc: network functions
 * @User: liuhui
 * @Date: 15-6-3 下午9:18 
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
        if (false !== $pos){
            unset($arr[$pos]);
        }

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

function getByUrl($url)
{
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION,1);
    return curl_exec($curl);
}


/**
 * @param        $url
 * @param string $method
 * @param null $postFields
 * @param null $header
 *
 * @return mixed
 * @throws \Exception
 */
function curl($url, $method = 'GET', $postFields = null, $header = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($postFields)) {
                if (is_array($postFields) || is_object($postFields)) {
                    if (is_object($postFields))
                        $postFields = Tools::object2array($postFields);
                    $postBodyString = "";
                    $postMultipart = false;
                    foreach ($postFields as $k => $v) {
                        if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                            $postBodyString .= "$k=" . urlencode($v) . "&";
                        } else { //文件上传用multipart/form-data，否则用www-form-urlencoded
                            $postMultipart = true;
                        }
                    }
                    unset($k, $v);
                    if ($postMultipart) {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    } else {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
                    }
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                }

            }
            break;
        default:
            if (!empty($postFields) && is_array($postFields))
                $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($postFields);
            break;
    }
    curl_setopt($ch, CURLOPT_URL, $url);

    if (!empty($header) && is_array($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new \Exception(curl_error($ch), 0);
    }
    curl_close($ch);

    return $response;
}


/**
 * 优化的file_get_contents操作，超时关闭
 *
 * @param      $url
 * @param bool $use_include_path
 * @param null $stream_context
 * @param int $curl_timeout
 *
 * @return bool|mixed|string
 */
function optimized_file_get_contents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 8)
{
    if ($stream_context == null && preg_match('/^https?:\/\//', $url))
        $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
    if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url))
        return @file_get_contents($url, $use_include_path, $stream_context);
    elseif (function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $opts = stream_context_get_options($stream_context);
        if (isset($opts['http']['method']) && Tools::strtolower($opts['http']['method']) == 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            if (isset($opts['http']['content'])) {
                parse_str($opts['http']['content'], $datas);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
            }
        }
        $content = curl_exec($curl);
        curl_close($curl);

        return $content;
    } else
        return false;
}

/**
 * 获取用户IP地址
 *
 * @return mixed
 */
function getRemoteAddr()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && (!isset($_SERVER['REMOTE_ADDR']) || preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR'])))) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return $ips[0];
        } else
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'];
}