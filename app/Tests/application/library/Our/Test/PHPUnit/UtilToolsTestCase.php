<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/2
 * @Time: 15:17
 * @Desc: test \Our\Util\Tools.php
 */
namespace Our\Test\PHPUnit;
//require_once APPLICATION_PATH . '/tests/application/library/Our/Test/PHPUnit/TestCase.php';

class UtilToolsTestCase extends \Our\Test\PHPUnit\TestCase
{

    public function test_strReplaceFirstAction(){
        $str = \Our\Util\Tools::strReplaceFirst('s', 'S', 'asHellow');

        $this->assertInternalType('string', $str);
        $this->assertEquals('aSHellow', $str);
    }

    public function test_tmspanAction(){
        $str = \Our\Util\Tools::tmspan('1441210246');

        $this->assertRegExp('/^\d+(分钟前|小时前|刚刚|天前)$/', $str);
    }

    public function test_getRandomAction(){
        //for($i = 200; $i <= 300; $i++){
            $int = \Our\Util\Tools::getRandom(1, $i);

            $this->assertInternalType('integer', $int);
        //}

    }


    public function test_cleanUrlAction(){
        $url = 'http://www.moji.com';
        $url = \Our\Util\Tools::cleanUrl($url, false);

        $this->assertNotEmpty($url);
        $this->assertEquals('www.moji.com', $url);
    }

    public function test_transCaseAction(){
        $str = 'ｘ';
        $str = \Our\Util\Tools::transCase($str);


    }
}