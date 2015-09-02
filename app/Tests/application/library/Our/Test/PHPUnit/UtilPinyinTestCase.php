<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/2
 * @Time: 14:58
 * @Desc: test \Our\Util\Pinyin.php
 */
namespace Our\Test\PHPUnit;
//require_once APPLICATION_PATH . '/tests/application/library/Our/Test/PHPUnit/TestCase.php';

class UtilPinyinTestCase extends \Our\Test\PHPUnit\TestCase
{
    public function testPinyinAction(){
        $chinese = '刘慧';
        $string = \Our\Util\Pinyin::get($chinese);
        $this->assertInternalType('string', $string);
        $this->assertEquals('liuhui', $string);

        $chinese = '何胖胖';
        $string = \Our\Util\Pinyin::get($chinese);
        $this->assertInternalType('string', $string);
        $this->assertEquals('hepangpang', $string);
    }
}