<?php

/**
 * @Desc:
 * @User: liuhui
 * @Date: 15-4-12 上午12:06
 */
class Admin_MemberModel extends BaseModel
{
    protected $_db;

    public function __construct()
    {
        $this->_db = DataCenter::getDb('myaf');
    }

    /**
     * 获取所有用户
     * */
    public function getAll()
    {
        $usersSql = 'select `au`.`name`,`au`.`id`,`au`.`roleid`,`ar`.`rolename` from `adm_users` as `au`,`adm_roles` as `ar` where `au`.`roleid`=`ar`.`id`;';
        $userData = $this->_db->get_results($usersSql);

        if (empty($userData)) {
            throw new LogicException('adm_users表,查询无结果');
        } else {
            return $userData;
        }
    }
}