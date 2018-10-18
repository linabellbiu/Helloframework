<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/17
 * Time: 17:40
 */
namespace Core\Db;
use PDO;
abstract  class Driver{
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
    protected $error = '';
    // 数据库连接ID 支持多个连接
    protected $linkID = array();
    // 当前连接ID
    protected $_linkID = null;
    // 数据库连接参数配置
    protected $config = array(
        'type'           => '', // 数据库类型
        'hostname'       => '127.0.0.1', // 服务器地址
        'database'       => '', // 数据库名
        'username'       => '', // 用户名
        'password'       => '', // 密码
        'hostport'       => '', // 端口
        'dsn'            => '', //
        'params'         => array(), // 数据库连接参数
        'charset'        => 'utf8', // 数据库编码默认采用utf8
        'prefix'         => '', // 数据库表前缀
        'debug'          => false, // 数据库调试模式
        'deploy'         => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'rw_separate'    => false, // 数据库读写是否分离 主从式有效
        'master_num'     => 1, // 读写分离后 主服务器数量
        'slave_no'       => '', // 指定从服务器序号
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
        PDO::ATTR_CASE              => PDO::CASE_LOWER,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    );
    protected $bind = array(); // 参数绑定

    /**
     * 架构函数 读取数据库配置信息
     * @access public
     * @param array $config 数据库配置数组
     */
    public function __construct()
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
            if (is_array($this->config['params'])) {
                $this->options = $this->config['params'] + $this->options;
            }
        }
    }

    /**
     * 连接数据库方法
     * @param string $config
     * @param int $linkNum
     * @param bool $autoConnection
     * @return mixed
     */
    public function connect($config = '', $linkNum = 0, $autoConnection = false)
    {
        if (!isset($this->linkID[$linkNum])) {
            if (empty($config)) {
                $config = $this->config;
            }

            try {
                if (empty($config['dsn'])) {
                    $config['dsn'] = $this->parseDsn($config);
                }
                if (version_compare(PHP_VERSION, '5.3.6', '<=')) {
                    // 禁用模拟预处理语句
                    $this->options[PDO::ATTR_EMULATE_PREPARES] = false;
                }
                $this->linkID[$linkNum] = new PDO($config['dsn'], $config['username'], $config['password'], $this->options);
            } catch (\PDOException $e) {
                if ($autoConnection) {
                    trace($e->getMessage(), '', 'ERR');
                    return $this->connect($autoConnection, $linkNum);
                } elseif ($config['debug']) {
                    E($e->getMessage());
                }
            }
        }
        return $this->linkID[$linkNum];
    }

    public function pareDsn(){}
}