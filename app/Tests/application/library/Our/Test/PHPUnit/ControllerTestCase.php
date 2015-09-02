<?php

namespace Our\Test\PHPUnit;

require_once APPLICATION_PATH . '/tests/application/library/Our/Test/PHPUnit/TestCase.php';

class ControllerTestCase extends \Our\Test\PHPUnit\TestCase
{

    //测试 JsonAction UID存在
    public function testJsonUidAction() {
        $response = $this->requestActionAndParseBody('Test', 'Action', 'json', array('uid' => 1));
        $data     = json_decode($response, TRUE);

        $this->assertInternalType('array', $data);
        $this->assertEquals('0', $data['code']);
        $this->assertInternalType('string', $data['data']['username']);
        $this->assertRegExp('/^\d+$/', $data['data']['groupid']);
        $this->assertRegExp('/^\d+$/', $data['data']['adminid']);
        $this->assertRegExp('/^\d+$/', $data['data']['regdate']);
    }

    //测试 JsonAction UID不存在，UID不存在返回的code应该是-1
    public function testJsonUidNotFoundAction() {
        $response = $this->requestActionAndParseBody('Test', 'Action', 'Json', array('uid' => 1));

        $data     = json_decode($response, TRUE);

        $this->assertInternalType('array', $data);
        $this->assertEquals('0', $data['code']);
    }

    public function testIndexIndexIndexAction(){
        $response = $this->requestActionAndParseBody('index', 'index', 'index', array());

        $this->assertEmpty($response);
    }

}