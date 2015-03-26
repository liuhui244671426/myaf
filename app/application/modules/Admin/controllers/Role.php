<?php
/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-2-13
 * @Time: 下午5:46
 * @Desc: 角色管理
 */
class RoleController extends BaseController{
    public function doInit(){
        $this->_view->setLayout('Admin');
    }
    /**
     * 角色列表
     * @uri /admin/role/list?p=x
     *
     * @param integer $p 页码
     */
    public function listAction(){

        $p = $this->getLegalParam('p', 'int', array(), 0);
        $db = new RoleModel();

        $list = $db->listAdmin($p);

        $table = TableBuilder::listAdminHtml($list);
        $th = TableBuilder::thHtml(array('id', 'name', 'rolename', 'action'));

        $page = $db->page('adm_users');
        $page = TableBuilder::pageHtml('/admin/role/list?p=', $page['pageNum'], $p);

        $this->_view->display('Admin/table.phtml',
            array(
                'th' => $th,
                'table' => $table,
                'page' => $page
            )
        );
    }
    /**
     * 添加管理员
     *
     * @uri /admin/role/insnew
     * ============================
     * @method POST
     * @param string $email
     * @param string $password
     * @param integer $roleid
     * @param string $nickname
     *
     * @return redirect
     * ============================
     * @method GET
     * @return display
     */
    public function insNewAction(){
        $db = new RoleModel();
        if($this->_request->isPost()){

            $email = $this->getLegalParam('email', 'str');
            $password = $this->getLegalParam('password', 'str');
            $roleID = $this->getLegalParam('roleid', 'int');

            $password = md5($password);

            $data = array(
                'name' => $email,
                'pass' => $password,
                'roleid' => $roleID,
            );
            $isIns = $db->insNewAdmin($data);
            //dump($isIns);
            if($isIns){
                $this->redirect('/admin/role/list');
            }
        } else {
            $listRoles = $db->listRole();
            //dump($listRoles);
            $this->_view->display('Admin/form.phtml',
                array(
                    'option' => FormBuilder::optionsHtml($listRoles)
                )
            );
        }
    }

    public function testAction(){
        dump(getActions(__CLASS__));
        dump(__METHOD__);
        dump($this->_module);
    }
}