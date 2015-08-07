<?php
namespace Our\Halo;
class HaloMethod{
    public static function randId($maxId)
    {
        if ($maxId == null || $maxId < 100000)
            $maxId = 100000;

        return $maxId + rand(1, 15);
    }

    public static function checkName($name)
    {
        $blackListUnicode = array(
            '0000-001F',//C0控制符	C0 Controls
            '0021-002F',//!"#$%&'()*+,-./
            '003A-0040',//:;<=>?@
            '005B-0060',//[\]^_`
            '007B-007F',//{|}~DEL
            '0080-009F',//	C1控制符	C1 Controls
            '2200-22FF',//	数学运算符	Mathematical Operator
            '25A0-25FF',//	几何图形	Geometric Shapes
            'FF00-FFEF',//	半角及全角	Halfwidth and Fullwidth Forms
            'FFF0-FFFF',//	特殊	Specials
        );

        $len = mb_strlen($name, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($name, $i, 1, 'UTF-8');
            $o = unpack('N', mb_convert_encoding($c, 'UCS-4BE', 'UTF-8'));

            foreach ($blackListUnicode as $v) {
                $pos = strpos($v, '-');
                $min = hexdec(substr($v, 0, $pos));
                $max = hexdec(substr($v, $pos + 1));

                if ($o[1] >= $min && $o[1] <= $max) {
                    return -1;
                    break;
                }
            }
        }
        return 0;
    }

    public static function haloMicroTime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return $sec * 1000 + intval($usec * 1000);
    }

    public static function getUrl()
    {
        $pathAndName = str_replace($_SERVER["DOCUMENT_ROOT"], '', $_SERVER["SCRIPT_FILENAME"]);
        $path = str_replace('/' . basename(__FILE__), '', $pathAndName);
        $url = strtolower(str_replace($path, '', $_SERVER["REQUEST_URI"]));

        if ($url[0] == '/') {
            $url = substr($url, 1);
        }
        return $url;
    }

    public static function rewrite()
    {
        $request = explode('?', $_SERVER['REQUEST_URI']);
        $paths = explode('/', $_SERVER ['SCRIPT_NAME']);//strtolower(
        $uris = explode('/', $request[0]);//strtolower()

        // remove rewrite base
        $i = 0;
        $len = min(count($paths), $uris);
        for (; $i < $len && $paths [$i] == $uris [$i]; $i++) {
        }

        // remove empty item
        for (; $i < $len && strlen($uris [$i]) == 0; $i++) {
        }

        // action and method
        $uris = array_slice($uris, $i);
        $len = count($uris);
        if (!isset ($_GET ['a'])) {
            $_GET ['a'] = $len > 0 ? $uris [0] : 'index';
        }
        if (!isset ($_GET ['m'])) {
            $_GET ['m'] = $len > 1 ? $uris [1] : 'index';
        }

        // params
        for ($i = 2; $i < $len; $i += 2) {
            $_GET [$uris [$i]] = $uris [$i + 1];
        }
    }

    public static function checkEmail($mail)
    {
        if (!empty ($mail)) {
            // 		\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*
            if (preg_match('/^[a-z0-9]+([._-]*[a-z0-9]+)*@([a-z0-9\-_]+([.\_\-][a-z0-9]+))+$/i', $mail)) {
                return true;
            }
        }
        return false;
    }

    public static function arrayTrim($array)
    {
        foreach ($array as $k => $v) {
            if (empty($v))
                unset($array[$k]);
        }
        return $array;
    }

    public static function arrayCol($array, $colName)
    {
        $newArray = array();
        foreach ($array as $v) {
            $newArray[] = $v[$colName];
            // 		YContactDebugLog::debugLog($v[$colName]);
        }
        return $newArray;
    }

    public static function arraySearchCol($array, $colName, $key)
    {
        $colArray = arrayCol($array, $colName);
        $pos = array_search($key, $colArray);
        return $pos;
    }

    public static function arraySort($array, $key, $order = SORT_ASC)
    {
        $newArray = array();
        $sortAbleArray = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $key) {
                            $sortAbleArray[$k] = $v2;
                        }
                    }
                } else {
                    $sortAbleArray[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC :
                    asort($sortAbleArray);
                    break;
                case SORT_DESC :
                    arsort($sortAbleArray);
                    break;
            }
            foreach ($sortAbleArray as $k => $v) {
                $newArray[$k] = $array[$k];
            }
        }
        return $newArray;
    }

    public static function isAssocArray($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function aesEncrypt($val, $key)
    {
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        $val = str_pad($val, (16 * (floor(strlen($val) / 16) + 1)), chr(16 - (strlen($val) % 16)));
        return mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
    }

    public static function aesDecrypt($val, $key)
    {
        $mode = MCRYPT_MODE_ECB;
        $enc = MCRYPT_RIJNDAEL_128;
        return preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', mcrypt_decrypt($enc, $key, $val, $mode, mcrypt_create_iv(mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM)));
        //return mcrypt_decrypt($enc, $key, $val, $mode, mcrypt_create_iv( mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
    }

    /**
     *根据经纬度计算距离(米)
     * @param double $lng1 ：经度
     * @param double $lat1 : 纬度
     * @param double $lng2 ：经度
     * @param double $lat2 : 纬度
     **/
    public static function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;//两纬度之差,纬度<90
        $b = $radLng1 - $radLng2;//两经度之差纬度<180
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        $distance = round($s);
        return $distance;
    }

    /*if (!function_exists('hex2bin')) {
        function hex2bin($data)
        {
            $bin = "";
            $i = 0;
            do {
                $bin .= chr(hexdec($data{$i} . $data{($i + 1)}));
                $i += 2;
            } while ($i < strlen($data));

            return $bin;
        }
    }*/

    public static function convertToArrayN($input)
    {
        if (!isset($input))
            return $input;
        if (!is_array($input) || isAssocArray($input)) {
            $val = $input;
            $input = array();
            $input[] = $val;
        }
        return $input;
    }
    /**
     * 创建目录
     * */
    public static function ensureFilePath($file_path, $is_dir = false)
    {
        if ($file_path == null || strlen($file_path) == 0) {
            return false;
        }

        if (!$is_dir) {
            $file_name = strrchr($file_path, "/");
            $dir = substr($file_path, 0, 0 - strlen($file_name));
            if (file_exists($file_path)) {
                if (is_dir($file_path)) {
                    return false;
                }
                return true;
            } else {
                if (@mkdir($dir, 0755, true)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if (file_exists($file_path)) {
                if (!is_dir($file_path)) {
                    return false;
                }
                return true;
            } else {
                if (@mkdir($file_path, 0755, true)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public static function isEmptyString($str)
    {
        settype($str, 'string');
        return (is_null($str) || ((is_string($str) && (strlen($str) == 0))));
    }

    /**
     * Return a formatted string
     * @link http://www.php.net/manual/en/function.sprintf.php
     * @param format string <p>
     * The format string is composed of zero or more directives:
     * ordinary characters (excluding %) that are
     * copied directly to the result, and conversion
     * specifications, each of which results in fetching its
     * own parameter. This applies to both sprintf
     * and printf.
     * </p>
     * <p>
     * Each conversion specification consists of a percent sign
     * (%), followed by one or more of these
     * elements, in order:
     * An optional sign specifier that forces a sign
     * (- or +) to be used on a number. By default, only the - sign is used
     * on a number if it's negative. This specifier forces positive numbers
     * to have the + sign attached as well, and was added in PHP 4.3.0.
     * @param args mixed[optional] <p>
     * </p>
     * @param _ mixed[optional]
     * @return string a string produced according to the formatting string
     * format.
     */
    public static function dbsprintf($format, $args = null, $_ = null)
    {
        $varArray = func_get_args();
        $count = count($varArray);
        if ($count > 1) {
            $v = $varArray[$count - 1];
            if ($v === true) {
                $varArray = array_slice($varArray, 0, $count - 1);
                return call_user_func_array('sprintf', $varArray);
            } else if ($v === false) {
                $varArray = array_slice($varArray, 0, $count - 1);
                $count--;
            }
            for ($index = 1; $index < $count; $index++) {
                $patterns = array('/\r/', '/\n/', '/\x00/', '/\x1a/');
                $var = preg_replace($patterns, '', $varArray[$index]);
                if ($var === false)
                    $var = 0;
                $varArray[$index] = dbencode($var);
            }
            return call_user_func_array('sprintf', $varArray);
        } else {
            return $format;
        }
    }

    /**
     * Merge one or more arrays
     * @link http://www.php.net/manual/en/function.array-merge.php
     * @param array1 array <p>
     * Initial array to merge.
     * </p>
     * @param array2 array[optional]
     * @param _ array[optional]
     * @return array the resulting array.
     */
    public static function halo_array_merge($array1, $array2 = null, $_ = null)
    {
        $varArray = func_get_args();
        $count = count($varArray);

        for ($index = 0; $index < $count; $index++) {
            if (!is_array($varArray[$index])) {
                $varArray[$index] = (array)$varArray[$index];
            }
        }
        return call_user_func_array('array_merge', $varArray);
    }

    public static function convertQuery($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    public static function connectQuery($path, $params)
    {
        if (empty($params)) {
            return $path;
        }
        $ps = '';
        foreach ($params as $key => $value) {
            if (strlen($key) > 0) {
                $ps = $ps . $key . '=' . $value . '&';
            }
        }
        if (strlen($ps) > 0) {
            $path = $path . '?' . substr($ps, 0, -1);
        }
        return $path;
    }


    public static function unsetUrlParam($url, $key)
    {
        $urlObj = parse_url($url);
        $params = convertQuery($urlObj['query']);
        $url = connectQuery($urlObj['path'], $params);
        return $url;
    }

    public static function resetUrlParam($url, $param)
    {
        $urlObj = parse_url($url);
        $params = convertQuery($urlObj['query']);
        $params = halo_array_merge($params, (array)$param);
        $url = connectQuery($urlObj['path'], $params);
        return $url;
    }

    public static function addUrlParamFromUrl($oriUrl, $toUrl)
    {
        $oriQuery = parse_url($oriUrl, PHP_URL_QUERY);
        if (!$oriQuery)
            return $toUrl;

        $toQuery = parse_url($toUrl, PHP_URL_QUERY);
        if ($toQuery)
            return $toUrl . '&' . $oriQuery;
        else
            return $toUrl . '?' . $oriQuery;
    }

    public static function getFromServer($key, $default = null)
    {
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    public static function getClientIp($checkProxy = true)
    {
        if ($checkProxy && ($ip = getFromServer('HTTP_CLIENT_IP')) != null) {
            return $ip;
        }

        if ($checkProxy && ($ip = getFromServer('HTTP_X_FORWARDED_FOR')) != null) {
            return $ip;
        }

        return getFromServer('REMOTE_ADDR');
    }

    public static function haloDie()
    {
        HaloXhprof::disable(false);
        Logger::flush();
        die();
    }

    public static function utf8_json_encode($arr)
    {
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) {
            if (is_string($item))
                $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
        });
        return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    }

    public static function generateRandomStr($len = 40)
    {
        $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
        return substr(hash('sha512', $randomData), 0, $len);
    }

    public static function generateKey($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $key;
    }
}
