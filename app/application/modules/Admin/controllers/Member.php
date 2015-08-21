<?php

/**
 * @Desc:会员管理
 * @User: liuhui
 * @Date: 15-4-11 下午11:56
 */

class MemberController extends \Our\Controller\admin
{
    /**
     * 获取所用会员的列表
     * */
    public function getAllMembersAction()
    {
        $db = new Admin_MemberModel();
        $data = $db->getAll();
        $table = TableBuilder::allMemberHtml($data);

        $this->_view->display('Admin/getAllMember.phtml', array('table' => $table));
    }
}