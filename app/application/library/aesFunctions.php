<?php
/**
 * @Desc:
 * @User: liuhui
 * @Date: 15-6-3 下午9:21 
 */

//-------------------------------------
//crypt 系列函数
//-------------------------------------
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
//-------------------------------------
//crypt 系列函数
//-------------------------------------