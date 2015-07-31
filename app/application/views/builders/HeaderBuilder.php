<?php

/**
 * @Desc:
 * @User: liuhui
 * @Date: 15-4-11 下午11:10
 */
class HeaderBuilder
{
    /**
     * 获取用户名或uid
     * @return array
     * */
    static public function getUserName()
    {
        if (empty($_SESSION['user']))
            exit(MSG_USER_INFO_EMPTY);
        else
            return $_SESSION['user'];
    }
}