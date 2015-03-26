<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 14-9-14
 * @Time: 下午3:01
 */
class codeUtil
{
    static function resultCode($pos)
    {
        $codeList = array(
            0 => 'Success',
            1 => 'Failed',
            2 => '您没有该操作权限',
            3 => '温馨提示:必须要设置封面图片哦!',
            4 => 'Weatber ProtoBuf解析失败',
            5 => '图片宽度不符合规则',
            6 => '图片高度不符合规则',
            7 => '获取图片数据失败'
        );

        return $codeList[$pos];
    }
}