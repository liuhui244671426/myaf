<?php

/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15-2-13
 * Time: 下午6:06
 */
class TableBuilder
{

    /**
     * 管理员列表
     * @param array $data
     * @return string html
     * */
    public static function allMemberHtml(array $data)
    {
        $tr = '';
        foreach ($data as $k => $v) {
            $tr .= sprintf('<tr>
                    <td>%d</td>
                    <td>%d</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>', $k, $v['id'], $v['name'], $v['rolename']);
        }

        $th = self::thHtml(array('id', 'uid', 'name', 'role name'));
        $html = '<table class="table mb30">
                <thead>
                    ' . $th . '
                </thead>
                <tbody>
                    ' . $tr . '
                </tbody>
            </table>';
        return $html;
    }

    /**
     * 生成th头部
     * @param array $thead
     * @return string html
     * */
    public static function thHtml(array $thead)
    {
        $th = '';
        foreach ($thead as $k) {
            $th .= sprintf('<th>%s</th>', $k);
        }
        return '<tr>' . $th . '</tr>';
    }

    /**
     * 生成分页
     * @param string $link 链接地址
     * @param integer $maxPage 最大的页码数
     * @param integer $currentPage 当前页码数
     * @return string html
     * */
    public static function pageHtml($link, $maxPage, $currentPage)
    {
        //dump($maxPage);
        //dump($currentPage);

        $lis = $class = '';
        $li = '<li><a href="%s" class="%s">%s</a></li>';
        $aPrev = $link . '0';
        $aNext = $link . $maxPage;
        $pagePrev = sprintf($li, $aPrev, '', 'Prev');
        if ($maxPage >= 10) {
            $p = 10;
        } else {
            $p = $maxPage;
        }
        for ($i = 0; $i < $p; $i++) {
            if ($currentPage == $i) {
                $class = 'active';
                $num = 'Curr';
            } else {
                $num = $i;
            }
            $a = $link . $i;
            $lis .= sprintf($li, $a, $class, $num);
        }
        $pageNext = sprintf($li, $aNext, '', 'Next');
        $lis = $pagePrev . $lis . $pageNext;
        $html = sprintf('<ul class="pagination pagination-centered">%s</ul>', $lis);
        return $html;
    }
}