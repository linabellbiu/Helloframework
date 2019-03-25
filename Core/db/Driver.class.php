<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/17
 * Time: 17:40
 */

namespace Core\db;

use Core\Error;
use PDO;

//底层驱动,用来查询和执行sql
abstract class Driver
{
    // PDO操作实例
    protected $PDOStatement = null;

    // 当前SQL指令
    protected $queryStr = '';
    protected $modelSql = array();
    // 最后插入ID
    protected $lastInsID = null;
    // 返回或者影响记录数
    protected $numRows = 0;
    // 事物操作PDO实例
    protected $transPDO = null;
    // 事务指令数
    protected $transTimes = 0;
    // 错误信息
    public $error = '';
    // 数据库连接ID 支持多个连接
    protected $linkID = array();
    // 当前连接ID
    protected $_linkID = null;
    // 数据库连接参数配置
    protected $config = array(
        'type' => '', // 数据库类型
        'hostname' => '127.0.0.1', // 服务器地址
        'database' => '', // 数据库名
        'username' => '', // 用户名
        'password' => '', // 密码
        'hostport' => '', // 端口
        'dsn' => '', //
        'params' => array(), // 数据库连接参数
        'charset' => 'utf8', // 数据库编码默认采用utf8
        'prefix' => '', // 数据库表前缀
        'debug' => false, // 数据库调试模式
        'deploy' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'rw_separate' => false, // 数据库读写是否分离 主从式有效
        'master_num' => 1, // 读写分离后 主服务器数量
        'slave_no' => '', // 指定从服务器序号
        'db_like_fields' => '',
    );

    // 数据库表达式
    protected $exp = array('eq' => '=', 'neq' => '<>', 'gt' => '>', 'egt' => '>=', 'lt' => '<', 'elt' => '<=', 'notlike' => 'NOT LIKE', 'like' => 'LIKE', 'in' => 'IN', 'notin' => 'NOT IN', 'not in' => 'NOT IN', 'between' => 'BETWEEN', 'not between' => 'NOT BETWEEN', 'notbetween' => 'NOT BETWEEN');
    // 查询表达式
    protected $selectSql = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%FORCE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%LOCK%%COMMENT%';
    // 查询次数
    protected $queryTimes = 0;
    // 执行次数
    protected $executeTimes = 0;
    // PDO连接参数
    protected $options = array(
        PDO::ATTR_CASE => PDO::CASE_LOWER,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    );
    protected $bind = array(); // 参数绑定

    /**
     * 架构函数 读取数据库配置信息
     * @access public
     * @param array $config 数据库配置数组
     */
    public function __construct($config)
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
            if (is_array($this->config['params'])) {
                $this->options = $this->config['params'] + $this->options;
            }
        }
    }

    abstract function parseDsn($config);

    abstract function insert($dataList, $name);

    abstract function update($dataList, $name);

    abstract function select($dataList, $name);

    abstract function delete($name);

    /**
     * 连接数据库方法
     * @param string $config
     * @param int $linkNum
     * @param bool $autoConnection
     * @return bool|mixed
     * @throws Error
     */
    public function connect($config = '', $linkNum = 0, $autoConnection = false)
    {
        if (!isset($this->linkID[$linkNum])) {
            if (empty($config)) {
                $config = $this->config;
            }
            try {

                $config['dsn'] = $this->parseDsn($this->config);

                if (empty($config['dsn'])) {
                    throw new Error("dsn is null");
                }
                if (version_compare(PHP_VERSION, '5.3.6', '<=')) {
                    // 禁用模拟预处理语句
                    throw new Error("php_versoin must >= 5.4");
                }
                $this->linkID[$linkNum] = new PDO($config['dsn'], $config['username'], $config['password'], $this->options);
            } catch (\PDOException $e) {
                if ($autoConnection) {
                    $this->error = $e->getMessage();
                    return $this->connect($autoConnection, $linkNum);
                } elseif ($config['debug']) {
                    $this->error = $e->getMessage();
                    return false;
                }
            }
        }
        return $this->linkID[$linkNum];
    }

    /**
     * 释放查询结果
     */
    public function free()
    {
        $this->PDOStatement = null;
    }

    /**
     * 执行查询 返回数据集
     * @access public
     * @param string $str sql指令
     * @param boolean $fetchSql 不执行只是获取SQL
     * @param boolean $master 是否在主服务器读操作
     * @return array|bool|string
     * @throws Error
     */
    public function query($str, $fetchSql = false, $master = false)
    {
        $this->initConnect($master);
        if (!$this->_linkID) {
            return false;
        }

        $this->queryStr = $str;
        if (!empty($this->bind)) {
            $that = $this;
            $this->queryStr = strtr($this->queryStr, array_map(function ($val) use ($that) {
                return '\'' . $that->escapeString($val) . '\'';
            }, $this->bind));
        }
        if ($fetchSql) {
            return $this->queryStr;
        }

        //释放前次的查询结果
        if (!empty($this->PDOStatement)) {
            $this->free();
        }

        $this->queryTimes++;

        $this->PDOStatement = $this->_linkID->prepare($str);
        if (false === $this->PDOStatement) {
            $this->error();
            return false;
        }
        foreach ($this->bind as $key => $val) {
            if (is_array($val)) {
                $this->PDOStatement->bindValue($key, $val[0], $val[1]);
            } else {
                $this->PDOStatement->bindValue($key, $val);
            }
        }
        $this->bind = array();
        try {
            $result = $this->PDOStatement->execute();

            if (false === $result) {
                $this->error();
                return false;
            } else {
                return $this->getResult();
            }
        } catch (\PDOException $e) {
            $this->error();
            return false;
        }
    }

    /**
     * 获得所有的查询数据
     * @access private
     * @return array
     */
    private function getResult()
    {
        //返回数据集
        $result = $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        $this->numRows = count($result);
        return $result;
    }

    /**
     * 关闭数据库
     * @access public
     */
    public function close()
    {
        $this->_linkID = null;
    }

    /**
     * SQL指令安全过滤
     * @access public
     * @param string $str SQL字符串
     * @return string
     */
    public function escapeString($str)
    {
        return addslashes($str);
    }

    /**
     * 参数绑定
     * @access protected
     * @param string $name 绑定参数名
     * @param mixed $value 绑定值
     * @return void
     */
    protected function bindParam($name, $value)
    {
        $this->bind[':' . $name] = $value;
    }

    /**
     * 获得执行次数
     * @return int
     */
    public function getExecuteTimes()
    {
        return $this->executeTimes;
    }

    /**
     * 初始化数据库连接
     * @param bool $master
     * @throws Error
     */
    protected function initConnect($master = true)
    {
        if (!$this->_linkID) {
            $this->_linkID = $this->connect();
        }
    }


    //执行sql语句
    protected function execute($str, $fetchSql = false)
    {
        $this->initConnect();
        if (!$this->_linkID) {
            return false;
        }
        $this->queryStr = $str;
        if (!empty($this->bind)) {
            $that = $this;
            $this->queryStr = strtr($this->queryStr, array_map(function ($val) use ($that) {
                return '\'' . $that->escapeString($val) . '\'';
            }, $this->bind));
        }

        if ($fetchSql) {
            return $this->queryStr;
        }

        if (!empty($this->PDOStatement))
            $this->free();

        $this->executeTimes++;

        $this->PDOStatement = $this->_linkID->prepare($str);
        if (false === $this->PDOStatement) {
            $this->error();
            return false;
        }
        foreach ($this->bind as $key => $val) {
            if (is_array($val)) {
                $this->PDOStatement->bindValue($key, $val[0], $val[1]);
            } else {
                $this->PDOStatement->bindValue($key, $val);
            }
        }
        $this->bind = array();

        try {
            $result = $this->PDOStatement->execute($vales);
            if (false === $result) {
                $this->error();
                return false;
            } else {
                $this->numRows = $this->PDOStatement->rowCount();
                if (preg_match("/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $str)) {
                    $this->lastInsID = $this->_linkID->lastInsertId();
                }
                return $this->numRows;
            }
        } catch (\PDOException $e) {
            $this->error();
            return false;
        }
    }

    //批量执行插入语句
    protected function executeInsertAll($str, $vales, $fetchSql = false)
    {
        $this->initConnect();
        if (!$this->_linkID) {
            return false;
        }
        $this->queryStr = $str;

        if ($fetchSql) {
            return $this->queryStr;
        }

        if (!empty($this->PDOStatement))
            $this->free();

        $this->executeTimes++;

        $this->PDOStatement = $this->_linkID->prepare($str);
        if (false === $this->PDOStatement) {
            $this->error();
            return false;
        }
        try {
            $result = $this->PDOStatement->execute($vales);
            if (false === $result) {
                $this->error();
                return false;
            } else {
                $this->numRows = $this->PDOStatement->rowCount();
                if (preg_match("/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $str)) {
                    $this->lastInsID = $this->_linkID->lastInsertId();
                }
                return $this->numRows;
            }
        } catch (\PDOException $e) {
            $this->error();
            return false;
        }
    }
    /**
     * 参数绑定分析
     * @access protected
     * @param array $bind
     * @return array
     */
    protected function parseBind($bind)
    {
        $this->bind = array_merge($this->bind, $bind);
    }

    private function error()
    {
        if ($this->PDOStatement) {
            $error = $this->PDOStatement->errorInfo();
            $this->error = $error[1] . ':' . $error[2];
        } else {
            $this->error = '';
        }

        if ('' != $this->queryStr) {
            $this->error .= "\n.[SQL 语句]:" . $this->queryStr;
        }

        if ($this->config['debug']) {
            return $this->error;
        }
        return false;
    }

    /**
     * 析构方法
     * @access public
     */
    public function __destruct()
    {
        // 释放查询
        if ($this->PDOStatement) {
            $this->free();
        }
        // 关闭连接
        $this->close();
    }
}


