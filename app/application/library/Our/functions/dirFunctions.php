<?php
/**
 * @Desc: 文件夹操作
 * @User: liuhui
 * @Date: 15-8-9 下午3:15 
 */

/**
 * 删除文件夹内容
 * */
function deleteDir($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file)
        {
            deleteDir(realpath($path) . '/' . $file);
        }
        return rmdir($path);
    }
    else if (is_file($path) === true)
    {
        return unlink($path);
    }
    return false;
}
/**
 * 在一个目录中列出所有文件和文件夹
 * */
function listFiles($dir)
{
    if(is_dir($dir))
    {
        if($handle = opendir($dir))
        {
            while(($file = readdir($handle)) !== false)
            {
                /*pesky windows, images..*/
                if($file != "." && $file != ".." && $file != "Thumbs.db")
                {
                    echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
                }
            }
            closedir($handle);
        }
    }
}