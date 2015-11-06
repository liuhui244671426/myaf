<?php
/**
 * @Desc: array functions
 * @User: liuhui
 * @Date: 15-6-3 下午9:19 
 */
/**
 * 低版本array_column
 *
 * @param array $input 待查询数组
 * @param string $columnKey 需查询的列
 * @param string $indexKey 索引
 * @return array
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

function arrayUnique($array)
{
    if (version_compare(phpversion(), '5.2.9', '<'))
        return array_unique($array);
    else
        return array_unique($array, SORT_REGULAR);
}

function arrayUnique2d($array, $keepkeys = true)
{
    $output = array();
    if (!empty($array) && is_array($array)) {
        $stArr = array_keys($array);
        $ndArr = array_keys(end($array));

        $tmp = array();
        foreach ($array as $i) {
            $i = join("¤", $i);
            $tmp[] = $i;
        }

        $tmp = array_unique($tmp);

        foreach ($tmp as $k => $v) {
            if ($keepkeys)
                $k = $stArr[$k];
            if ($keepkeys) {
                $tmpArr = explode("¤", $v);
                foreach ($tmpArr as $ndk => $ndv) {
                    $output[$k][$ndArr[$ndk]] = $ndv;
                }
            } else {
                $output[$k] = explode("¤", $v);
            }
        }
    }

    return $output;
}

/**
 * 遍历数组
 *
 * @param      $array
 * @param      $function
 * @param bool $keys
 */
function walkArray(&$array, $function, $keys = false)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            self::walkArray($array[$key], $function, $keys);
        } elseif (is_string($value)) {
            $array[$key] = $function($value);
        }

        if ($keys && is_string($key)) {
            $newkey = $function($key);
            if ($newkey != $key) {
                $array[$newkey] = $array[$key];
                unset($array[$key]);
            }
        }
    }
}

/**
 * 从array中取出指定字段
 *
 * @param $array
 * @param $key
 *
 * @return array|null
 */
function simpleArray($array, $key)
{
    if (!empty($array) && is_array($array)) {
        $result = array();
        foreach ($array as $k => $item) {
            $result[$k] = $item[$key];
        }

        return $result;
    }

    return null;
}