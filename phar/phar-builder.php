<?php
/**
 * @Desc: 构建phar包
 * @User: liuhui
 * @Date: 15-4-29 下午2:19 
 */
if(php_sapi_name() != 'cli'){
    exit('must is CLI mode');
}
$name = 'demo.phar';
$directoryPath = __DIR__.'/../library';
$indexFile = 'index.php';

$phar = new Phar($name);
$phar->buildFromDirectory($directoryPath, '/\.php$/');
$phar->compressFiles(Phar::BZ2);
$phar->stopBuffering();
$phar->setStub($phar->createDefaultStub($indexFile));