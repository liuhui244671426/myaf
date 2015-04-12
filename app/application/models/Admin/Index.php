<?php

/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-4-2
 * Time: 下午3:32
 */
class Admin_IndexModel extends BaseModel
{
    protected $_db;

    public function __construct()
    {
        $this->_db = DataCenter::getDb('myaf');
    }

    /**
     * 检测用户名和密码
     * @param string $user
     * @param string $pass
     * @return integer
     * */
    public function checkUserPass($user, $pass)
    {
        $sql = 'select `id` from `adm_users` where `name`=? and `pass`=?';
        $result = $this->_db->get_row($sql, array($user, $pass));
        return $result['id'];
    }
    /**
     * 添加登录日志
     * */
    public function insLoginLog($uid, $op)
    {
        $data = array(
            'uid' => $uid,
            'time' => TODAY,
            'ip' => ip2long(IP()),
            'op' => $op
        );
        $this->_db->insertTable('adm_login_log', $data);
    }
}