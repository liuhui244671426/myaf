<?php
/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-2-13
 * @Time: 上午11:37
 * @Desc: CMS管理
 */

class ArticlesController extends BaseController{

    public function doInit(){
        //设置layout模版
        $this->_view->setLayout('Admin');
    }

    /**
     * 文章列表
     * @uri /admin/articles/list?p=xxx
     * @method GET
     * @param integet $p 页码数
     * */
    public function listAction(){
        if($this->_request->isPost()){

        } else {
            $p = $this->getLegalParam('p', 'int', array(), '0');

            $db = new ArticlesModel();
            //$dbArticle = new AdminModel();

            $page = $db->page('cms_articles');
            $articles = $db->listArticles($p);

            $page = TableBuilder::pageHtml('/admin/articles/list?p=', $page['pageNum'], $p);

            $table = TableBuilder::listArticlesHtml($articles);
            $th = TableBuilder::thHtml(array('id', 'title', 'publish', 'action'));

            $this->_view->display('Admin/table.phtml',
                array(
                    'page' => $page,
                    'table' => $table,
                    'th' => $th
                )
            );
        }
    }
    /**
     * 通过id查看文章
     * @uri /admin/articles/view?id=xxx
     *
     * @param integer $id 文章id
     * */
    public function viewAction(){
        //$id = $this->_request->getQuery('id');
        $id = $this->getLegalParam('id', 'int');
        $this->_view->setLayout('');
        $db = new AdminModel();
        $content = $db->viewArticle($id);
        //dump($content);
        $this->_view->display('Article/view.phtml',
            array(
                'content' => $content['content']
            )
        );
    }
    /**
     * 通过id获取或修改文章详细
     * @uri /admin/articles/edit
     * ==============================
     * 图片格式: jpeg,gif,png
     * 图片命名: MY_xxxxxx.jpg
     *
     * @method POST 通过id修改文章详细
     * @param array post
     * @return redirect
     * ==============================
     * @method GET 通过id获取文章详细
     * @param integer id 文章id
     * @return display
     * */
    public function editAction(){
        if($this->_request->isPost()){
            dump($_FILES);
            //---upload file start
            $destPath = Yaf_Registry::get('config')['image']['upload'];
            $storage = new \Upload\Storage\FileSystem($destPath);
            $file = new \Upload\File('upload', $storage);
            $newFilename = uniqid('MY_');
            $file->setName($newFilename);
            $file->addValidations(array(
                new \Upload\Validation\Mimetype('image/jpeg', 'image/gif', 'image/png'),
                new \Upload\Validation\Size('5M')
            ));

            try {
                $file->upload();
                $iterator = $file->getIterator();
                $validations = $file->getValidations();
                dump($iterator);
                dump($validations);
            } catch (\Exception $e) {
                $errors = $file->getErrors();
                throw new \Upload\Exception($e->getMessage(), $e->getCode());
            }
            //---upload file end

            //post data
            $post = $this->_request->getPost();
            $db = new AdminModel();
            $res = $db->upArticle($post);
            if($res){
                $this->redirect('/admin/articles/list');
            } else {
                //
            }
        } else {
            $db = new AdminModel();
            $article = $db->viewArticle('146');

            $this->_view->display('Article/edit.phtml', array('article' => $article));
        }
    }

    /**
     * 通过id删除文章详细
     * @uri /admin/articles/del?id=xxx
     *
     * @method POST ajax
     * @param integer $id 文章id
     * @return string json
     * */
    public function delAction(){
        if($this->_request->isXmlHttpRequest()){

            $id = $this->_request->getPost('id');
            Yaflog(__METHOD__ . $id);
            //---
            $db = new AdminModel();
            $isDel = $db->delArticle($id);
            //$isDel = true;
            //---
            echo formatData(0, array('isDel' => $isDel));
            return false;

        }
    }

    public function getMothodsAction(){
        $actions = getActions(__CLASS__);
        dump($actions);
    }

    /**
     * kindeditor编辑器图片上传接口
     * @uri /admin/articles/upload
     *
     * @method POST
     * @param array $imgFile
     * @return mixed json|throw
     * */
    public function uploadAction(){
        if($this->_request->isPost()){
            $destPath = Yaf_Registry::get('config')['image']['upload'];

            $storage = new \Upload\Storage\FileSystem($destPath);
            $file = new \Upload\File('imgFile', $storage);
            $newFilename = uniqid('MY_');
            $file->setName($newFilename);
            $file->addValidations(array(
                new \Upload\Validation\Mimetype('image/jpeg'),
                new \Upload\Validation\Size('5M')
            ));

            try {
                $file->upload();
                $loadedName = $file->getNameWithExtension();
                $newFileUrl = substr($destPath, 1) . $loadedName;
                Yaflog($newFileUrl);
                echo json_encode(array('error' => 0, 'url' => $newFileUrl));
            } catch (\Exception $e) {
                $errors = $file->getErrors();
                throw new \Upload\Exception($e->getMessage(), $e->getCode());
            }
        }
    }

    public function testAction(){
        /*if($this->_request->isPost()){
            $destPath = Yaf_Registry::get('config')['image']['upload'];

            $storage = new \Upload\Storage\FileSystem($destPath);
            $file = new \Upload\File('upload', $storage);
            $newFilename = uniqid('MY_');
            $file->setName($newFilename);
            $file->addValidations(array(
                new \Upload\Validation\Mimetype('image/jpeg'),
                new \Upload\Validation\Size('5M')
            ));

            try {
                $file->upload();
                $iterator = $file->getIterator();
                $validations = $file->getValidations();
                dump($iterator);
                dump($validations);
            } catch (\Exception $e) {
                $errors = $file->getErrors();
                throw new \Upload\Exception($e->getMessage(), $e->getCode());
            }
        } else {
            $this->_view->display('Article/test.phtml', array());
        }*/
        /*dump(getActions(__CLASS__));
        dump(__METHOD__);
        dump($this->_module);*/

        $db = new AdminModel();
        $db->getUserPremission($_SESSION['user']['roleid']);
    }
}