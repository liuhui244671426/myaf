<?php
/**
 * @Desc: 文章
 * @User: liuhui
 * @Date: 15-5-5 下午3:58 
 */
class ArticleController extends BaseController{
    public function doInit(){
        $this->_view->setLayout('Admin');
    }

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
        //dump($this->_request->getPost('content'));
        Yaflog(__METHOD__);
        Yaflog($title);
        Yaflog($content);
        $data = array(
            'title' => $title,
            'content' => $content,
            'create_time' => TODAY,
            'update_time' => null,
            'author' => 'admin',
            'category_id' => 1
        );
        $db = DataCenter::getFactory('db', 'myaf');
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
        $ckeFuncNum = is_int($this->getLegalParam('CKEditorFuncNum', 'int'));

        $upload = new upload();
        $upload->maxSize = 3145728;
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = $this->_config['image']['upload']['path'] . 'origin/';
        if(!$upload->upload()){
            echo 'error: ';
            dump($upload->getErrorMsg());
        } else {
            Yaflog($upload->getUploadFileInfo());

            $url = getDomain() . substr($upload->savePath, 1) . $upload->getUploadFileInfo()[0]['savename'];

            echo '<script type="text/javascript">';
            $msg = sprintf('window.parent.CKEDITOR.tools.callFunction(%s, \'%s\', \'\');', $ckeFuncNum, $url);
            echo $msg;
            echo '</script>';
            return;
        }
    }
}