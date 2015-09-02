<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/2
 * @Time: 17:13
 * @Desc: test unit functions\functions.php
 */
namespace Our\Test\PHPUnit;
require_once APPLICATION_PATH . '/tests/application/library/Our/Test/PHPUnit/TestCase.php';

class FunctionsTestCase extends \Our\Test\PHPUnit\TestCase
{
    public function test_randStringAction(){
        $str = randString(8);

        $this->assertInternalType('string', $str);
        $this->assertRegExp('/^\d{8}$/', $str);
    }
}