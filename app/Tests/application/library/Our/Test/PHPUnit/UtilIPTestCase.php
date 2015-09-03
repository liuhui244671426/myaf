<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/2
 * @Time: 23:29
 * @Desc: test unit \Our\Util\ip.php
 */
namespace Our\Test\PHPUnit;

class UtilIPTestCase extends \Our\Test\PHPUnit\TestCase
{
    public function test_ipAction(){
        $ip = '212.214.223.105';
        $address = \Our\Util\IP::find($ip);

        $this->assertInternalType('array', $address);
    }
}