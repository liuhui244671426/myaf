<?php

/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-4
 * Time: 下午5:04
 */
class LeftBuilder
{

    static public function menuHtml()
    {
        return '<h5 class="sidebartitle">Navigation</h5>
        <ul class="nav nav-pills nav-stacked nav-bracket">
            <li class="active"><a href="/admin/main/main"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

            <li class="nav-parent"><a href=""><i class="fa fa-edit"></i> <span>会员管理</span></a>
                <ul class="children">
                    <li><a href="/admin/member/getAllMembers"><i class="fa fa-caret-right"></i>所有会员</a></li>
                </ul>
            </li>
        </ul>';
    }

    static public function getLeftMenu()
    {
        $uid = $_SESSION['user']['uid'];
        $db = new Admin_IndexModel();
        $data = $db->getLeftMenu($uid);

    }
}