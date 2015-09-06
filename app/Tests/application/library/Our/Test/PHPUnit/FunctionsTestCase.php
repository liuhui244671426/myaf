<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/2
 * @Time: 17:13
 * @Desc: test unit functions\functions.php
 */
namespace Our\Test\PHPUnit;


class FunctionsTestCase extends \Our\Test\PHPUnit\TestCase
{
    public function test_randStringAction(){
        $str = randString(8);

        $this->assertInternalType('string', $str);
        $this->assertRegExp('/^\d{8}$/', $str);
    }

    public function test_getMemoryLimitAction(){
        $mem = \Our\Util\Tools::getMemoryLimit();

        $this->assertInternalType('string', $mem);
        $this->assertRegExp('/^\d+(m)$/i',$mem);

        $int = \Our\Util\Tools::getOctets($mem);
        //var_dump($int);
        $this->assertInternalType('int', $int);

    }
}