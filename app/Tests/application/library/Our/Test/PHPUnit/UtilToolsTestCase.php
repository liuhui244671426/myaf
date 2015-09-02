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
    public function test_PasswdGenAction(){
        $passwd = \Our\Util\Tools::passwdGen();

        $this->assertRegExp('/\w{8}/', $passwd);
    }


    public function test_strReplaceFirstAction(){

        $str = \Our\Util\Tools::strReplaceFirst('s', 'S', 'asHellow');

        $this->assertInternalType('string', $str);
        $this->assertEquals('aSHellow', $str);
    }
}