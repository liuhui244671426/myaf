<?php

/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-2-9
 * @Time: 下午3:30
 * @Desc: 登录
 */
class IndexController extends \Our\Controller\YafController
{
    public function indexAction()
    {
        $this->forward('admin', 'index', 'login');
    }

    /**
     * 登陆入口页面
     */
    public function loginAction()
    {
        try{
            if($this->_request->isGet()){
                $this->_view->display('Admin/login.phtml', array());
            } else {
                $user = $this->getLegalParam('user', 'str');
                $pass = $this->getLegalParam('pass', 'str');
                $this->auth($user, $pass);
            }
        } catch(\Exception $e){
            \Our\Halo\HaloLogger::FATAL($e->getMessage());
        }

    }

    /**
     * 登陆验证
     * @method POST
     * @param string $user 用户名
     * @param string $pass 用户密码
     *
     * @return redirect
     */
    private function auth($user, $pass)
    {
        $pass = md5($pass);

        $db = new Admin_IndexModel();
        $uid = $db->checkUserPass($user, $pass);
        if (empty($uid)) {
            $this->redirect('/admin/index/login');
            exit;
        }
        \Our\Halo\HaloLogger::INFO('user: ' . $user);
        \Our\Halo\HaloLogger::INFO('pass: ' . $pass);
        \Our\Halo\HaloLogger::INFO('uid: ' . $uid);

        if ($uid >= 1) {

            $isWrite = $db->insLoginLog($uid, 1);
            \Our\Halo\HaloLogger::INFO($isWrite);
            //---------
            $_SESSION['user']['uid'] = $uid;
            $_SESSION['user']['uname'] = $user;
            //---------
            $this->redirect('/admin/main/main');
        } else {
            $this->redirect('/admin/index/login');
        }

    }

    /**
     * 注销登陆,删除session的东东
     */
    public function logoutAction()
    {
        $uid = $_SESSION['user']['uid'];

        $db = new Admin_IndexModel();
        $db->insLoginLog($uid, 0);

        unset($_SESSION['user']);
        $this->redirect('/admin/index/login');
    }
}
