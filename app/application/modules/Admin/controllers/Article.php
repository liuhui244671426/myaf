<?php
/**
 * @Desc: 文章
 * @User: liuhui
 * @Date: 15-5-5 下午3:58 
 */

class ArticleController extends \Our\Controller\admin{

    /**
     * 列表
     * */
    public function listAction(){
        $this->_view->display('Admin/article/list.phtml', array());
    }

    public function addAction(){
        $this->_view->display('Admin/article/add.phtml', array());
    }
    /**
     * 提交数据
     */
    public function postDataAction(){
        $title = $this->getLegalParam('title', 'str');
        $content = $this->getLegalParam('content', 'str');

        \Our\halo\HaloLogger::INFO(__METHOD__);
        \Our\halo\HaloLogger::INFO($title);
        \Our\halo\HaloLogger::INFO($content);
        $data = array(
            'title' => $title,
            'content' => $content,
            'create_time' => TODAY,
            'update_time' => null,
            'author' => 'admin',
            'category_id' => 1
        );
        $db = \Our\halo\HaloFactory::getFactory('db', 'myaf');
        $lastId = $db->insertTable('art_content', $data);
        if($lastId >= 1){
            echo echoJsonString(0, array('id' => $lastId));
        } else {
            echo echoJsonString(1, array('id' => $lastId));
        }
    }

    /**
     * 上传文件
     * */
    public function postUploadImageAction(){
        $ckeFuncNum = $this->getLegalParam('CKEditorFuncNum', 'int');

        $upload = new \Our\Util\upload();
        $upload->maxSize = 3145728;
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = YafRegistry('config')['image']['upload']['path'] . 'origin/';

        if(!$upload->upload()){
            echo 'error: ';
            dump($upload->getErrorMsg());
        } else {
            \Our\halo\HaloLogger::DEBUG($upload->getUploadFileInfo());

            importFunc('netFunctions');
            $url = getDomain() . substr($upload->savePath, 1) . $upload->getUploadFileInfo()[0]['savename'];

            echo '<script type="text/javascript">';
            $msg = sprintf('window.parent.CKEDITOR.tools.callFunction(%s, \'%s\', \'\');', $ckeFuncNum, $url);
            echo $msg;
            echo '</script>';
            return;
        }
    }
}