<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-2-13
 * Time: 下午6:06
 */
class TableBuilder{

    /**
     * 管理员列表
     * @param array $data
     * @return string html
     * */
    public static function listAdminHtml(array $data){
        $table = '';

        foreach($data as $k){
            $tr = '
        <tr>
            <td>' . $k['id'] . '</td>
            <td class="center">' . $k['name'] . '</td>
            <td class="center">
                ' . $k['rolename'] . '
            </td>
            <td class="center">
                <a class="btn btn-info btn-setting" href="#">
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    Edit
                </a>
                <a class="btn btn-danger" href="#">
                    <i class="glyphicon glyphicon-trash icon-white"></i>
                    Delete
                </a>
            </td>
        </tr>';
            $table .= $tr;
        }

        return $table;
    }

    /**
     * 文章列表
     * @param array $data
     * @return string html
     * */
    public static function listArticlesHtml(array $data){
        $table = '';
        foreach($data as $k){
            $tr = '
        <tr>
            <td>' . $k['id'] . '</td>
            <td class="center">' . $k['title'] . '</td>
            <td class="center">
                ' . $k['publish'] . '
            </td>
            <td class="center">
                <a class="btn btn-success" href="/admin/articles/view?id=' . $k['id'] . '">
                    <i class="glyphicon glyphicon-eye-open icon-white"></i>
                    Read
                </a>
                <a class="btn btn-info btn-setting" href="/admin/articles/edit">
                    <i class="glyphicon glyphicon-edit icon-white"></i>
                    Edit
                </a>
                <a class="btn btn-danger myModal" data-attr="' . $k['id'] . '">
                    <i class="glyphicon glyphicon-trash icon-white"></i>
                    Delete
                </a>

            </td>
        </tr>';
            $table .= $tr;
        }

        return $table;
    }

    /**
     * 生成th头部
     * @param array $thead
     * @return string html
     * */
    public static function thHtml(array $thead){
        $th = '';
        foreach($thead as $k){
            $th .= sprintf('<th>%s</th>', $k);
        }
        return $th;
    }

    /**
     * 生成分页
     * @param string $link 链接地址
     * @param integer $maxPage 最大的页码数
     * @param integer $currentPage 当前页码数
     * @return string html
     * */
    public static function pageHtml($link, $maxPage, $currentPage){
        //dump($maxPage);
        //dump($currentPage);

        $lis = $class = '';
        $li = '<li><a href="%s" class="%s">%s</a></li>';
        $aPrev = $link.'0';
        $aNext = $link.$maxPage;
        $pagePrev = sprintf($li, $aPrev, '', 'Prev');
        if($maxPage >= 10){
            $p = 10;
        } else {
            $p = $maxPage;
        }
        for($i = 0;$i < $p; $i++){
            if($currentPage == $i){
                $class = 'active';
                $num = 'Curr';
            } else {
                $num = $i;
            }
            $a = $link.$i;
            $lis .= sprintf($li, $a, $class, $num);
        }
        $pageNext = sprintf($li, $aNext, '', 'Next');
        $lis = $pagePrev . $lis . $pageNext;
        $html = sprintf('<ul class="pagination pagination-centered">%s</ul>', $lis);
        return $html;
    }
}