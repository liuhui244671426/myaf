<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-3-4
 * Time: 下午5:04
 */
class LeftBuilder{

    /*
     * <li class="accordion">
            <a href="#"><i class="glyphicon glyphicon-edit"></i><span>文章管理</span></a>
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/admin/articles/list">列表</a></li>
            </ul>
        </li>
     **/
    static public function menuHtml(){
        //----
        /*$db = new AclModel();
        $db->getUserPrem($_SESSION['user']['roleid']);*/
        //----
        $leftMenu = self::leftMenu();

        $html = $ul = '';

        foreach($leftMenu as $k => $v){
            $a = '<a href="#"><i class="glyphicon glyphicon-chevron-right"></i><span>' . $v['title'] . '</span></a>';
            $ul = '<ul class="nav nav-pills nav-stacked">';

            foreach($v['subItem'] as $kk => $vv){
                $ul .= '<li><a href="' . $vv['subLink'] . '">' . $vv['subTitle'] . '</a></li>';
            }
            $ul .= '</ul>';
            $html .= '<li class="accordion">' . $a . $ul . '</li>';
        }

        return $html;
    }

    static public function leftMenu(){
        $menu = array(
            array(
                'title'=>'文章管理',
                'name'=>'articles_list',
                'subItem' => array(
                    array(
                        'subTitle'=>'列表',
                        'subLink'=>'/admin/articles/list'
                    )
                )
            ),
            array(
                'title'=>'角色管理',
                'name'=>'role_list',
                'subItem' => array(
                    array(
                        'subTitle'=>'列表',
                        'subLink'=>'/admin/role/list',
                    ),
                    array(
                        'subTitle'=>'新加',
                        'subLink'=>'/admin/role/insnew'
                    )
                )
            )
        );
        return $menu;
    }
}