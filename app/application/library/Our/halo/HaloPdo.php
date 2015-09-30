<?php
/**
 * PDO操作库
 * fetch_style
 * @url: http://php.net/manual/zh/pdostatement.fetch.php
 * PDO::FETCH_ASSOC：返回一个索引为结果集列名的数组
 * PDO::FETCH_BOTH（默认）：返回一个索引为结果集列名和以0开始的列号的数组
 * PDO::FETCH_BOUND：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
 * PDO::FETCH_CLASS：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
 * PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
 * PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
 * PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组
 * PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象
 *
 * error_mode
 * @url: http://php.net/manual/zh/pdo.error-handling.php
 * PDO::ERRMODE_SILENT（0） ：默认 不提示任何错误
 * PDO::ERRMODE_WARNING（1） ： 警告
 * PDO::ERRMODE_EXCEPTION（2）：异常（推荐使用） 用try catch捕获，也可以手动抛出异常 new PDOException($message, $code, $previous)
 *
 * 预处理语句与存储过程
 * @url: http://php.net/manual/zh/pdo.prepared-statements.php
 * */
namespace Our\Halo;

class HaloPdo
{
    protected static $_instance = null;
    private $_dbh;
    private $_error;
    protected $_transLevel = 0;

    /**
     * 实例
     * @param array $config
     * @return mixed handle
     * */
    static public function getInstance($config){
        if(self::$_instance === null){
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }
    /**
     * 私有化构造函数，防止外界实例化对象
     * @param array $config 配置项
     * */
    private function __construct($config)
    {
        $port = isset($config['port']) ? $config['port'] : 3306;
        $dsn = sprintf('mysql:host=%s;dbname=%s;port=%d', $config['host'], $config['dbname'], $port);
        try {
            $this->_dbh = new \PDO($dsn, $config['user'], $config['pass'],
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::ATTR_EMULATE_PREPARES => true,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ));
        } catch (\Exception $e) {
            if ($this) $this->_error = $e->getMessage();
        }
    }

    /**
     * 私有化克隆函数，防止外界克隆对象
     * */
    private function __clone(){}

    /**
     * 调用不存在的方法 throw BadMethodCallException
     * @thorw mixed BadMethodCallException
     */
    public function __call($methodName, $methodArguments){
        throw new \BadMethodCallException('BadMethodCallException, called HaloPdo\'s method ' . $methodName . ' not found', EXC_CODE_HALO_PDO_METHOD_NOT_FOUND);
    }

    /**
     * 按条件生成SQL
     * */
    public static function condition($sql, $_ = null)
    {
        $args = func_get_args();

        if (count($args) > 1) {
            array_shift($args);
        } else {
            $args = null;
        }

        return array($sql, $args);
    }
    /**
     * ===================================================
     * 事务
     * ===================================================
     * */
    protected function transactionNestable()
    {
        return true;
    }

    public function transaction($call)
    {
        $this->beginTransaction();
        $ret = false;
        try {
            $ret = call_user_func($call);
        } catch (\Exception $e) {
            $this->rollBack();
        }

        if ($ret) {
            $this->commit();
        } else {
            $this->rollBack();
        }

        return $ret;
    }

    public function transactionRbn($call, $param)
    {
        $this->beginTransaction();
        $ret = false;
        try {
            $ret = call_user_func_array($call, $param);
        } catch (\Exception $e) {
            $this->rollBack();
        }

        if ($ret) {
            $this->commit();
        } else {
            $this->rollBack();
        }

        return $ret;
    }

    public function beginTransaction()
    {
        if (!$this->transactionNestable() || $this->_transLevel == 0) {
            $this->_dbh->beginTransaction();
        } else {
            $this->_dbh->exec(sprintf('SAVEPOINT LEVEL%d', $this->_transLevel));
        }

        $this->_transLevel++;
    }

    public function commit()
    {
        $this->_transLevel--;
        if (!$this->transactionNestable() || $this->_transLevel == 0) {
            $this->_dbh->commit();
        } else {
            $this->_dbh->exec(sprintf("RELEASE SAVEPOINT LEVEL%d", $this->_transLevel));
        }
    }

    public function rollBack()
    {
        $this->_transLevel--;

        if (!$this->transactionNestable() || $this->_transLevel == 0) {
            $this->_dbh->rollBack();
        } else {
            $this->_dbh->exec(sprintf("ROLLBACK TO SAVEPOINT LEVEL%d", $this->_transLevel));
        }
    }
    /**
     * ===================================================
     * 事务
     * ===================================================
     * */

    /**
     * 获取制定字段值
     * @param string $table
     * @param string $condition
     * @param string $varName
     * @return string
     * */
    public function getVarByCondition($table, $condition, $varName)
    {
        list($condition, $values) = $this->getConditionPair($condition);
        $sql = sprintf('SELECT %s FROM %s', $varName, $table);
        if (!empty($condition))
            $sql .= ' WHERE ' . $condition;

        return $this->get_var($sql, $values);
    }

    /**
     * 去重统计
     * @param string $table 表名
     * @param string $condition 条件
     * @param string $countPara 统计字段
     * @return int
     * */
    public function getDistinctCountByCondition($table, $condition = '', $countPara = '')
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($countPara))
            return $this->getCountByCondition($table, $condition, $values);
        else {
            if (empty($condition))
                $sql = sprintf('SELECT COUNT(DISTINCT %s) FROM %s', $countPara, $table);
            else
                $sql = sprintf('SELECT COUNT(DISTINCT %s) FROM %s WHERE %s', $countPara, $table, $condition);
        }

        return intval($this->get_var($sql, $values));
    }

    /**
     * 获取解释后的统计
     * @param string $table 表名
     * @param string $condition 条件
     * @return array
     * */
    public function getExplainCountByCondition($table, $condition)
    {
        if (empty($condition)) {
            return 0;
        }
        list($condition, $values) = $this->getConditionPair($condition);
        $sql = sprintf('EXPLAIN SELECT COUNT(*) FROM %s WHERE %s', $table, $condition);
        $explain = $this->get_row($sql, $values);
        return $explain['rows'];
    }

    /**
     * 获取count统计
     * @param string $table 表名
     * @param string $condition 条件
     * @return int
     * */
    public function getCountByCondition($table, $condition = '')
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($condition)){
            $sql = sprintf('SELECT COUNT(*) FROM %s', $table);
        } else {
            $sql = sprintf('SELECT COUNT(*) FROM %s WHERE %s', $table, $condition);
        }
        return intval($this->get_var($sql, $values));
    }
    /**
     * 获取去重后的数据
     * @param string $table 表名
     * @param string $condition 条件
     * @param string $distinct 去重字段
     * */
    public function getDistinctByCondition($table, $condition, $distinct)
    {
        list($condition, $values) = $this->getConditionPair($condition);
        $sql = sprintf('SELECT DISTINCT %s FROM %s', $distinct, $table);
        if (!empty($condition)){
            $sql .= ' WHERE ' . $condition;
        }
        return $this->get_col($sql, $values);
    }

    /**
     * 通过条件查找一行数据
     * @param string $table 表名
     * @param mixed $condition 条件 array('`id`=1', null)|'`id`=1'
     * @param string $fields 字段
     * @return array
     * */
    public function getRowByCondition($table, $condition, $fields = '')
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($fields))
            $sql = sprintf('SELECT * FROM %s WHERE %s LIMIT 1', $table, $condition);
        else
            $sql = sprintf('SELECT %s FROM %s WHERE %s LIMIT 1', $fields, $table, $condition);
        return $this->get_row($sql, $values);
    }
    /**
     * 通过条件查找多列数据
     * @param string $table 表名
     * @param mixed $condition 条件
     * @param string $fields 字段
     * @return array
     * */
    public function getColByCondition($table, $condition, $colName)
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($condition))
            $sql = sprintf('SELECT %s FROM %s', $colName, $table);
        else
            $sql = sprintf('SELECT %s FROM %s WHERE %s', $colName, $table, $condition);
        return $this->get_col($sql, $values);
    }

    public function getResultsByCondition($table, $condition = '', $fields = '')
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($fields)) {
            if (empty($condition))
                $sql = sprintf('SELECT * FROM %s', $table);
            else
                $sql = sprintf('SELECT * FROM %s WHERE %s', $table, $condition);
        } else {
            if (empty($condition))
                $sql = sprintf('SELECT %s FROM %s', $fields, $table);
            else
                $sql = sprintf('SELECT %s FROM %s WHERE %s', $fields, $table, $condition);
        }
        return $this->get_results($sql, $values);
    }

    public function getResultsBySql($sql)
    {
        return $this->get_results($sql);
    }

    public function getResultsByConditionAndFields($table, $condition = '', $fields)
    {
        list($condition, $values) = $this->getConditionPair($condition);
        if (empty($fields)) {
            if (empty($condition))
                $sql = sprintf('SELECT * FROM %s', $table);
            else
                $sql = sprintf('SELECT * FROM %s WHERE %s', $table, $condition);
        } else {
            if (empty($condition)) {
                $sql = sprintf('SELECT %s FROM %s', $fields, $table);
            } else
                $sql = sprintf('SELECT %s FROM %s WHERE %s', $fields, $table, $condition);
        }
        return $this->get_results($sql, $values);
    }
    /**
     * 将数据插入到表中
     * @param string $table 表名
     * @param array $data 待插入的关联数组
     * @return mixed 成功返回最后的Id,失败返回false
     * */
    public function insertTable($table, $data)
    {
        if (!is_array($data))
            return false;

        list($fields, $values) = $this->getConditionArray($data);

        if (count($values)) {
            $sql = sprintf('INSERT INTO %s SET %s', $table, $fields);
            $this->query($sql, $values);
            //write log
            \Our\Halo\HaloLogger::INFO('插入语句 '.$sql);

            return intval($this->_dbh->lastInsertId());
        }

        return false;
    }
    /**
     * 批量插入数据
     * @param string $table 表名
     * @param string $fields 字段
     * @param array $valueData 带插入的数据
     * @return bool
     * */
    public function batchInsertData($table, $fields, $valueData)
    {
        if (empty($fields) || empty($valueData)) {
            return null;
        }

        $rows = array();
        $values = array();
        $count = count($valueData);
        for ($index = 0; $index < $count; $index++) {
            $padArray = array_pad(array(), count($valueData[$index]), '?');
            $rows[] = '(' . implode(',', $padArray) . ')';
            foreach ($valueData[$index] as $v)
                $values[] = $v;
        }

        $sql = "INSERT IGNORE INTO %s (%s) VALUES %s";
        $query = sprintf($sql, $table, implode(',', $fields), implode(',', $rows));

        return $this->query($query, $values);
    }

    public function updateTable($table, $data, $condition)
    {
        list($condition, $conditionValues) = $this->getConditionPair($condition);
        if (is_array($data)) {
            list ($fields, $values) = $this->getConditionArray($data);
            if (count($values) > 0) {
                $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $fields, $condition);
                if (count($conditionValues))
                    $values = array_merge($values, $conditionValues);
                return $this->query($sql, $values)->rowCount();
            }
        }
        return false;
    }

    public function updateFieldByIncrease($table, $field, $condition, $diff = 1)
    {
        list($where, $values) = $this->getConditionPair($condition);

        if ($where) {
            $where = 'WHERE ' . $where;
        }

        $sql = sprintf('UPDATE %s SET %s=%s+%d %s', $table, $field, $field, $diff, $where);
        $this->query($sql, $values);
    }

    public function updateFieldByIncrease2($table, $field, $data, $condition, $diff = 1)
    {
        list($condition, $conditionValues) = $this->getConditionPair($condition);
        if ($condition) {
            $condition = 'WHERE ' . $condition;
        }
        if (is_array($data)) {
            list ($fields, $values) = $this->getConditionArray($data);
            $fields .= ',' . sprintf('%s=%s+%d', $field, $field, $diff);
            if (count($values) > 0) {
                $sql = sprintf('UPDATE %s SET %s  %s', $table, $fields, $condition);
                if (count($conditionValues))
                    $values = array_merge($values, $conditionValues);
                return $this->query($sql, $values);
            }

        }
        return false;
    }

    /**
     * 更新递增数据(多字段)
     * */
    public function updateFieldsByIncrease($table, $data, $condition)
    {
        list($condition, $conditionValues) = $this->getConditionPair($condition);
        if (is_array($data)) {
            list ($fields, $values) = $this->getConditionArray2($data);
            if (count($values) > 0) {
                $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $fields, $condition);
                if (count($conditionValues))
                    $values = array_merge($values, $conditionValues);
                return $this->query($sql, $values);
            }
        }
        return false;
    }

    /**
     * 更新或添加记录
     * @param string $table
     * @param array $data
     * @param string $condition
     * @param string $idField
     * @return mixed
     * */
    public function insertOrUpdateTable($table, $data, $condition, $idField = 'Fid')
    {
        $row = $this->getRowByCondition($table, $condition, $idField);
        if ($row) {
            $this->updateTable($table, $data, $condition);
            return $row[$idField];
        } else {
            return $this->insertTable($table, $data);
        }
    }
    /**
     * 不存在该记录则添加
     * @return int
     * */
    public function insertIfNotExist($table, $data, $condition, $keyField = 'Fid')
    {
        $rowId = 0;
        $row = $this->getRowByCondition($table, $condition, $keyField);
        if (!$row) {
            $rowId = $this->insertTable($table, $data);
        } else if (!empty($keyField)) {
            $rowId = $row[$keyField];
        }

        return $rowId;
    }
    /**
     * 替换表数据
     * @param string $table
     * @param array $data
     * @return mixed false
     * */
    public function replaceTable($table, $data)
    {
        if (is_array($data)) {
            list($fields, $values) = $this->getConditionArray($data);
            if (count($values) > 0) {
                $sql = sprintf('REPLACE INTO %s SET %s', $table, $fields);
                return $this->query($sql, $values);
            }
        }
        return false;
    }

    public function delRowByCondition($table, $map)
    {
        list($condition, $values) = $this->getConditionPairFromMap($map);
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $condition);
        $stmt = $this->query($sql, $values);
        return $stmt->errorCode() == \PDO::ERR_NONE;
    }


    public function delRowByCondition2($table, $condition)
    {
        list($condition, $values) = $this->getConditionPair($condition);
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $condition);
        return $this->query($sql, $values);
    }

    /**
     * 生成特定数据结构
     * @param array $data 数组数据 array('name' => 'liuhui', 'vote' => 'y')
     * @return array 生成如下结构体
     * Array(
     *      [0] => name=?,vote=?
     *      [1] => Array(
     *          [0] => liuhui
     *          [1] => y
     *      )
     *  )
     * */
    public function getConditionArray($data)
    {
        if (count($data) == 0)
            return array(null, null);

        $fields = array();
        $values = array();
        foreach ($data as $k => $v) {
            $fields[] = sprintf('%s=?', $k);
            $values[] = $v;
        }

        return array(implode(',', $fields), $values);
    }
    public function getConditionArray2($data)
    {
        if (count($data) == 0)
            return array(null, null);

        $fields = array();
        $values = array();
        foreach ($data as $k => $v) {
            $fields[] = sprintf('%s=%s+?', $k, $k);
            $values[] = $v;
        }

        return array(implode(',', $fields), $values);
    }
    public function getPlaceHolders($cnt)
    {
        return implode(',', array_pad(array(), $cnt, '?'));
    }
    /**
     * 谨慎使用,清空表内容
     * @param string $table 表名
     * */
    public function truncateTable($table)
    {
        $sql = sprintf('TRUNCATE TABLE %s', $table);
        $this->query($sql);
    }

    /**
     * 执行一条预处理语句,有错误将记录在日志里面
     * @param string $sql exm:'select * from `adm_users` where `name`=? and `pass`=?'
     * @param array $values exm:array('admin', 'pass');
     * @return string 返回SQL语句
     * */
    public function query($sql, $values = null)
    {
        //预处理SQL
        $stmt = $this->_dbh->prepare($sql);
        $stmt->execute($values);
        //有错误
        if ($stmt->errorCode() != \PDO::ERR_NONE) {
            if (count($values)){
                $msg = sprintf('%s | (%s)', $sql, implode(',', $values));
            } else {
                $msg = $sql;
            }

            trigger_error($stmt->errorInfo()[2]);
            //write log
            \Our\Halo\HaloLogger::ERROR($msg, __FILE__, __LINE__, ' ERROR-SQL');
            \Our\Halo\HaloLogger::ERROR($stmt->errorInfo());
        }

        return $stmt;
    }

    public function get_var($sql, $values = null)
    {
        return $this->query($sql, $values)->fetchColumn(0);
    }

    /**
     * 获取一行结果
     * @param string $sql
     * @param array $values
     * @return mixed 结果集
     * */
    public function get_row($sql, $values = null)
    {
        return $this->query($sql, $values)->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     *
     * */
    public function get_col($sql, $values = null, $offset = 0)
    {
        return $this->query($sql, $values)->fetchAll(\PDO::FETCH_COLUMN, $offset);
    }
    /**
     *
     * */
    public function get_results($sql, $values = null)
    {
        return $this->query($sql, $values)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * 转义用户输入的特殊字符
     * @param string $str
     * @return string
     * */
    function escape($str)
    {
        return $this->_dbh->quote($str);
    }
    /**
     * 将数据还原成数组
     * @param mixed $condition 条件
     * @return array
     * */
    public function getConditionPair($condition)
    {
        if (is_array($condition)) {
            return $condition;
        }

        if (empty($condition) || is_string($condition)) {
            return array($condition, null);
        }
    }
    /**
     * 从Map格式还原成数组
     * @param array $map array('id' => 12, 'city' => '北京')
     * @return array
     * Array
     *   (
     *       [0] => id=? AND city=?
     *       [1] => Array
     *       (
     *           [0] => 12
     *           [1] => 北京
     *       )
     *   )
     * */
    protected function getConditionPairFromMap($map)
    {
        $placeHolders = array();
        $values = array();
        foreach ($map as $k => $v) {
            array_push($placeHolders, sprintf('%s=?', $k));
            array_push($values, $v);
        }

        $sql = implode(' AND ', $placeHolders);
        return array($sql, $values);
    }
    /**
     * 销毁
     * */
    public function __destruct(){
        $this->_dbh = null;
    }
}
