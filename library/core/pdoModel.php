<?php

    /**
     * @Create Author : huiliu//刘辉
     * @Create Time: 14-9-11 下午9:34
     * @Desc :
     */
    class pdoModel
    {
        public $dbhost = null;
        public $dbport = null;
        public $dbname = null;
        public $dbuser = null;
        public $dbpass = null;

        public $stmt = null;//steam
        public $dbLink = null;//database link

        /**
         * 构造函数
         */
        public function __construct($option)
        {
            $this->dbhost = $option['host'];
            $this->dbport = $option['port'];
            $this->dbname = $option['dbname'];
            $this->dbuser = $option['user'];
            $this->dbpass = $option['pass'];

            //self::connect();
            try {
                $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $this->dbhost, $this->dbport, $this->dbname);
                yafDebug::log(sprintf('connect var $dsn: %s', $dsn));
                $this->dbLink = new PDO ($dsn, $this->dbuser, $this->dbpass, array(
                    PDO::ATTR_PERSISTENT => false //是否长连接
                ));
            } catch (PDOException $e) {
                //die ("Connect Error Infomation:" . $e->getMessage());
                yafDebug::log('Connect Error Infomation: ' . $e->getMessage());
                throw new Yaf_Exception($e->getMessage());

                die();
            }

            $this->dbLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $this->dbLink->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->execute('SET NAMES UTF8');
        }

        /**
         * 析构函数
         */
        public function __destruct()
        {
            $this->close();
        }

        /**
         * *******************基本方法开始********************
         */
        /**
         * 作用:连結数据库
         */
        public function connect()
        {

        }

        /**
         * 关闭数据连接
         */
        public function close()
        {
            $this->dbLink = null;
        }

        /**
         * 對字串進行转義
         */
        public function quote($str)
        {
            return $this->dbLink->quote($str);
        }

        /**
         * 作用:获取数据表里的欄位
         * 返回:表字段结构
         * 类型:数组
         */
        public function getFields($table)
        {
            $this->stmt = $this->dbLink->query("DESCRIBE $table");
            $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->stmt = null;
            return $result;
        }

        /**
         * 作用:获得最后INSERT的主鍵ID
         * 返回:最后INSERT的主鍵ID
         * 类型:数字
         */
        public function getLastId()
        {
            return $this->dbLink->lastInsertId();
        }

        /**
         * 作用:執行INSERT\UPDATE\DELETE
         * 返回:执行語句影响行数
         * 类型:数字
         */
        public function execute($sql)
        {
            yafDebug::log(sprintf('execute sql: %s', $sql));
            //var_dump($sql);

            $result = $this->dbLink->exec($sql);
            yafDebug::log(sprintf('affected rows: %s', $result));
            if($result){
                return $result;
            } else {
                yafDebug::log($this->dbLink->errorInfo());
                //print_r($this->dbLink->errorCode());
                //print_r($this->dbLink->errorInfo());
                return $result;
            }
        }

        /**
         * 获取要操作的数据
         * 返回:合併后的SQL語句
         * 类型:字串
         * fix filter int 0 bug
         */
        private function getCode($table, $args)
        {
            $code = '';
            if (is_array($args)) {
                foreach ($args as $k => $v) {
                    //if ($v == '') {//fix a bug 2014.09.23
                    if (is_null($v)) {
                        continue;
                    }
                    $code .= "`$k`='" . $v . "',";
                }
            }

            $code = substr($code, 0, -1);
            //print_r($args);
            return $code;
        }

        public function optimizeTable($table)
        {
            $sql = "OPTIMIZE TABLE $table";
            $this->execute($sql);
        }

        /**
         * 执行具体SQL操作
         * 返回:运行結果
         * 类型:数组
         */
        private function _fetch($sql, $type)
        {
            $result = array();
            $this->stmt = $this->dbLink->query($sql);
            //self::$stmt = self::$DB->prepare($sql);

            yafDebug::log(sprintf('%s var $sql: %s, $type: %s', __METHOD__, $sql, $type));

            if($this->stmt == false){
                //print_r($this->dbLink->errorInfo());
                yafDebug::log($this->dbLink->errorInfo());
            }

            //var_dump($this->stmt);
            $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
            switch ($type) {
                case '0' :
                    $result = $this->stmt->fetch();
                    break;
                case '1' :
                    $result = $this->stmt->fetchAll();
                    break;
                case '2' :
                    $result = $this->stmt->rowCount();
                    break;
            }
            $this->stmt = null;
            return $result;
        }
        /*
         * Return: [queryString] => 'select * from xxx;'
         * */
        public function _query($sql)
        {
            $result = $this->dbLink->query($sql);
            return $result;
        }
        /**
         * *******************基本方法結束********************
         */

        /**
         * *******************Sql操作方法开始********************
         */
        /**
         * 作用:插入数据
         * 返回:表內記录
         * 类型:数组
         * 參数:$db->insert('$table',array('title'=>'Zxsv'))
         */
        public function add($table, $args)
        {
            $sql = "INSERT INTO $table SET ";

            $code = $this->getCode($table, $args);
            $sql .= $code;
            yafDebug::log(sprintf('add sql: %s', $sql));
            return $this->execute($sql);
        }

        /**
         * 修改数据
         * 返回:記录数
         * 类型:数字
         * 參数:$db->update($table,array('title'=>'Zxsv'),array('id'=>'1'),$where
         * ='id=3');
         */
        public function update($table, $args, $where)
        {
            $code = $this->getCode($table, $args);
            $sql = "UPDATE `$table` SET ";
            $sql .= $code;
            $sql .= " Where $where";
            yafDebug::log(sprintf('%s $sql: %s', __METHOD__, $sql));

            return $this->execute($sql);
        }

        /**
         * 作用:刪除数据
         * 返回:表內記录
         * 类型:数组
         * 參数:$db->delete($table,$condition = null,$where ='id=3')
         */
        public function delete($table, $where)
        {
            $sql = "DELETE FROM `$table` Where $where";
            return $this->execute($sql);
        }

        /**
         * 作用:获取單行数据
         * 返回:表內第一条記录
         * 类型:数组
         * 參数:$db->fetOne($table,$condition = null,$field = '*',$where ='')
         */
        public function fetOne($table, $field = '*', $where = false, $orderby = false)
        {
            $sql = "SELECT {$field} FROM {$table}";
            $sql .= ($where) ? " WHERE $where" : '';
            $sql .= ($orderby) ? " ORDER BY $orderby" : '';
            return $this->_fetch($sql, $type = '0');
        }

        /**
         * 作用:获取所有数据
         * 返回:表內記录
         * 类型:二維数组
         * 參数:$db->fetAll('$table',$condition = '',$field = '*',$orderby = '',$limit
         * = '',$where='')
         */
        public function fetAll($table, $field = '*', $orderby = false, $where = false)
        {
            $sql = "SELECT {$field} FROM {$table}";
            $sql .= ($where) ? " WHERE $where" : '';
            $sql .= ($orderby) ? " ORDER BY $orderby" : '';
            //print_r($sql);
            return $this->_fetch($sql, $type = '1');
        }

        //fetch all by sql
        public function fetSql($sql)
        {
            return $this->_fetch($sql, 1);
        }
        //todo fix
        //limit sql
        public function fetLimit($table, $field = '*', $orderby = false, $offset = 0, $length = 20)
        {
            $sql = "SELECT {$field} FROM {$table}";
            $sql .= ($orderby) ? " ORDER BY $orderby" : '';
            $sql .= sprintf(' limit %s, %s', $offset, $length);

            yafDebug::log(sprintf('fetLimit sql: %s', $sql));
            return $this->_fetch($sql, $type = 1);
        }

        /**
         * 作用:获取單行数据
         * 返回:表內第一条記录
         * 类型:数组
         * 參数:select * from table where id='1'
         */
        public function getOne($sql)
        {
            return $this->_fetch($sql, $type = '0');
        }

        /**
         * 作用:获取所有数据
         * 返回:表內記录
         * 类型:二維数组
         * 參数:select * from table
         */
        public function getAll($sql)
        {
            return $this->_fetch($sql, $type = '1');
        }

        /**
         * 作用:获取首行首列数据
         * 返回:首行首列欄位值
         * 类型:值
         * 參数:select `a` from table where id='1'
         */
        public function scalar($sql, $fieldname)
        {
            $row = $this->_fetch($sql, $type = '0');
            return $row [$fieldname];
        }

        /**
         * 获取記录总数
         * 返回:記录数
         * 类型:数字
         * 參数:$db->fetRow('$table',$condition = '',$where ='');
         */
        public function fetRowCount($table, $field = '*', $where = false)
        {
            $sql = "SELECT COUNT({$field}) AS num FROM $table";
            $sql .= ($where) ? " WHERE $where" : '';
            return $this->_fetch($sql, $type = '0');
        }

        /**
         * 获取記录总数
         * 返回:記录数
         * 类型:数字
         * 參数:select count(*) from table
         */
        public function getRowCount($sql)
        {
            return $this->_fetch($sql, $type = '2');
        }

        /**
         * *******************Sql操作方法結束********************
         */
    }