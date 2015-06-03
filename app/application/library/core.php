<?php
/**
 * @Desc: 核心
 * @User: liuhui
 * @Date: 15-6-3 下午8:43 
 */

/**
 * 加载文件
 * @param string|array $files
 * */
function import($path){
    if(is_array($path)){
        //todo
    } else {
        //$path = APPLICATION_PATH . '/application/library/' . $files . '.php';
        if(file_exists($path)){
            $isTrue = Yaf_Loader::import($path);
            if(!$isTrue){
                $msg = 'load ' . $path . ' file return false';
                throw new LogicException($msg);
            }
        } else {
            $msg = 'load ' . $path . ' file is not found';
            throw new LogicException($msg);
        }
    }
}