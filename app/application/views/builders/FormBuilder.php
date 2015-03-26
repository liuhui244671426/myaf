<?php
/**
 * Created by PhpStorm.
 * @User: liuhui
 * @Date: 15-2-15
 * @Time: 下午5:13
 * @Desc: 表单构建器
 */
class FormBuilder{
    /**
     * 生成option html
     * @param array $data
     * @return string html
     */
    public static function optionsHtml($data){
        $html = '<option value="%s">%s</option>';
        $option = '';
        foreach($data as $v){
            $option .= sprintf($html, $v['id'], $v['rolename']);
        }
        return $option;
    }
}