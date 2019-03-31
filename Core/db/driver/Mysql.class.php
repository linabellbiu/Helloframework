<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/17
 * Time: 17:41
 */

//mysql驱动
namespace Core\db\driver;

use Core\db\Driver;

class Mysql extends Driver
{
    /**
     * 数据库名
     * @var string
     */
    public $dbName;

    /**
     * 表名
     * @var string
     */
    public $tableName;

    public $fetchSql = false;

    private $_where = '';

    private $_field = ' * ';

    private $_limit = '';

    private $_groupBy = '';

    private $_groupByList = [];

    //========================================================================================================================================================================================================================================================================
    //如果在DSN中指定了charset, 是否还需要执行set names <charset>呢？
    //是的，不能省。set names <charset>其实有两个作用：
    //A.  告诉mysql server, 客户端（PHP程序）提交给它的编码是什么
    //B.  告诉mysql server, 客户端需要的结果的编码是什么
    //也就是说，如果数据表使用gbk字符集，而PHP程序使用UTF-8编码，我们在执行查询前运行set names utf8, 告诉mysql server正确编码即可，无须在程序中编码转换。这样我们以utf-8编码提交查询到mysql server, 得到的结果也会是utf-8编码。省却了程序中的转换编码问题，不要有疑问，这样做不会产生乱码。
    //那么在DSN中指定charset的作用是什么? 只是告诉PDO, 本地驱动转义时使用指定的字符集（并不是设定mysql server通信字符集），设置mysql server通信字符集，还得使用set names <charset>指令。
    //========================================================================================================================================================================================================================================================================
    /**
     * 解析pdo连接的dsn信息
     * @access public
     * @param array $config 连接信息
     * @return string
     */
    public function parseDsn($config)
    {
        $dsn = 'mysql:dbname=' . $config['database'] . ';host=' . $config['hostname'];
        if (!empty($config['hostport'])) {
            $dsn .= ';port=' . $config['hostport'];
        } elseif (!empty($config['socket'])) {
            $dsn .= ';unix_socket=' . $config['socket'];
        }

        if (!empty($config['charset'])) {
            //为兼容各版本PHP,用两种方式设置编码
            $this->options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $config['charset'];
            $dsn .= ';charset=' . $config['charset'];
        }
        return $dsn;
    }

    /**
     * 查询
     * @param $sql
     * @param $name
     * @return mixed
     */
    public function mysqlSelect($sql, $name)
    {
        return $this->query($sql);
    }

    public function select($field, $name)
    {
        if (!empty($field)) {
            if (is_string($field)) {
                $this->_field = $field;
            }
            if (is_array($field)) {
                $this->_field = implode(',', $field);
            }
        }
        $sql = 'SELECT' . $this->_field . 'FROM ' . $name . $this->_where . $this->_parseOrder();
        return $this->execute($sql, $this->_field);
    }

    public function field($dataList)
    {
        if (empty($dataList))
            $this->_field = ' * ';

        if (is_string($dataList))
            $this->_field = ' ' . $dataList . ' ';

        if (is_array($dataList)) {
            $this->_field = ' ' . implode(',', $dataList) . ' ';
        }
    }

    /**
     * 执行批量插入
     * @param $dataList
     * @param $name
     * @return bool|int
     */
    public function insertAll($dataList, $name)
    {
        if (empty($dataList) || empty($name))
            return false;

        $fields = array_keys($dataList[0]);
        $place_holders = implode(',', array_fill(0, count($dataList[0]), '?'));

        $values = [];
        $vals = [];
        foreach ($dataList as $index => $arr) {
            foreach (array_values($arr) as $val) {
                $vals [] = $val;
            }

            $values[] = '(' . $place_holders . ')';
        }

        $sql = 'INSERT INTO ' . $name . '(' . implode(',', $fields) . ') VALUES ' . implode(',', $values);

        //TODO WANG 批量插入不允许外部绑定，内部会自动绑定
        $this->bind = [];
        return $this->executeInsertAll($sql, $vals, $this->fetchSql);
    }

    /**
     * 执行插入
     * @param array $data
     * @param $name
     * @return bool|int
     */
    public function insert($data, $name)
    {
        $keys = [];
        if (empty($data))
            return false;

        if (!is_array($data))
            return false;

        foreach ($data as $key => $val) {
            $this->bindParam($key, $val);
            $keys[] = ':' . $key;
        }


        $sql = 'INSERT INTO ' . $name . '(' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', $keys) . ')';
        return $this->execute($sql, $this->fetchSql);
    }

    public function update($data, $name)
    {
        $keys = [];
        if (empty($data))
            return false;

        if (!is_array($data))
            return false;

        foreach ($data as $key => $val) {
            $this->bindParam($key, $val);
            $keys[] = $key . '= :' . $key;

        }
        $sql = 'UPDATE ' . $name . ' SET ' . implode(',', $keys) . $this->where;
        return $this->execute($sql, $this->fetchSql);
    }

    public function delete($name)
    {
        $sql = 'DELETE FROM ' . $name . $this->_where . $this->_limit;
        return $this->execute($sql, $this->fetchSql);
    }

    public function getError()
    {

    }

    public function bind()
    {

    }

    public function groupBy($dataList, $sort = '')
    {
        if (empty($dataList))
            return false;
        if (is_array($dataList)) {
            foreach ($dataList as $data) {
                if (!is_array($data))
                    continue;
                if (count($data) > 2)
                    continue;
                $field = $data[0];
                $sort = is_string($sort) ? $sort : 'ASC';
                $this->_groupByList[] = ' ' . $field . ' ' . $sort;
            }
        }
        if (is_string($dataList)) {
            $this->_groupByList[] = ' ' . $dataList . ' ' . is_string($sort) ? $sort . ' ' : 'ASC';
        }
        return true;
    }

    /**
     * 解析groupby句子
     * @return string
     */
    private function _parseOrder()
    {
        if (empty($this->_groupByList)) {
            return '';
        }
        if (!is_array($this->_groupByList)) {
            return '';
        }
        if (count($this->_groupByList) <= 0) {
            return '';
        }
        return ' GROUP BY ' . implode(',', $this->_groupByList) . ' ';
    }

    public function where($str, $arr)
    {
        if (is_string($str) && is_array($arr)) {

            preg_match_all('/:[a-zA-Z0-9_]+/', $str, $matches);

            if (count($matches[0]) != count($arr))
                return false;

            foreach ($matches[0] as $index => $key) {
                $this->bindParam(trim($key, ':'), $arr[$index]);
            }
        }

        $this->_where = ' WHERE ' . $str . ' ';
        return true;
    }

    public function limit($start = 1, $lenth = 0)
    {
        $this->_limit = ' LIMIT ' . $start . ',' . $lenth;
    }
}