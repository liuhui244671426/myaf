<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 14-9-14
 * @Time: 下午1:51
 */
class stringUtil
{
    static public function randString($len = 4)
    {
        $string = self::coreString();
        $stringLen = strlen($string) - 1;
        $newString = '';
        for($i = 1; $i <= $len; $i++)
        {
            $pos = rand(0, $stringLen);
            $newString .= $string[$pos];
        }

        return $newString;
    }

    //有效字符
    static public function coreString()
    {
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return $string;
    }

    /*
     * 打乱顺序的字符串对比
     * @Return:(bool) true(真)|false(否)
     * */
    static public function shuffleStringCmp($needleString, $haystackString = 'DAT'){
        $length = strlen($needleString);
        for ($i=0; $i <$length ; $i++) {
            if(strpos($haystackString, $needleString[$i]) === false){
                return false;
            }
        }
        return true;
    }
}