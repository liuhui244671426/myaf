<?php
/**
 *
 * Test
 * author liuhui9<liuhui9@staff.sina.com.cn>
 * @version 15/11/6
 * @copyright copyright(2015) weibo.com all rights reserved
 */
class TestController extends \Our\Controller\admin
{
    public function indexAction(){
        $this->_view->display('Admin/test.phtml', array());
    }
    public function test1Action(){
        try{
            $file = $_FILES['file'];

            $uploader = new \Our\Util\Upload\Uploader($file);

            $uploader->process(PUBLIC_PATH.'/statics/uploads/images/origin/');

            if($uploader->uploaded){
                dump($uploader->file_dst_path);
            } else {
                dump($uploader->error);
                dump($uploader->file_src_error);
            }

            die;
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
    }
}