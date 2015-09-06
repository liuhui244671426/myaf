<?php
/**
 * @Created by PhpStorm.
 * @User: liuhui
 * @Date: 15/9/1
 * @Time: 21:09
 * @Desc: PDO test case
 */
namespace Our\Test\PHPUnit;


class PdoTestCase extends \Our\Test\PHPUnit\TestCase
{
    // 只实例化 pdo 一次，供测试的清理和装载基境使用
    static private $pdo = null;

    // 对于每个测试，只实例化 PHPUnit_Extensions_Database_DB_IDatabaseConnection 一次
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    public function testConnectionAction(){
        $this->getConnection();
    }
}