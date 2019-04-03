<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/25
 * Time: 11:18
 */

//模型层，用来方便的链式操作,不需要实现很复杂的逻辑
namespace Core;

use Core\db\Db;

class Model
{
    // 当前数据库操作对象
    protected $db = null;
    // 数据库对象池
    private $_db = array();
    // 主键名称
    protected $pk = 'id';
    // 主键是否自动增长
    protected $autoinc = false;
    // 数据表前缀
    protected $tablePrefix = null;
    // 模型名称
    protected $name = '';
    // 数据库名称
    protected $dbName = '';
    //数据库配置
    protected $connection = '';
    // 数据表名（不包含表前缀）
    protected $tableName = '';
    // 实际数据表名（包含表前缀）
    protected $trueTableName = '';

    // 链操作方法列表
    protected $methods = array('strict', 'order', 'alias', 'having', 'group', 'lock', 'distinct', 'auto', 'filter', 'validate', 'result', 'token', 'index', 'force', 'master');


    /**
     * Model constructor.
     * @param string $name 数据库名.表名
     * @param array $connect 连接配置信息
     * @param bool $force 是否强制重新连接
     */
    public function __construct($name, $connect, $force = false)
    {
        try {
            if (empty($name)) {
                throw new Error("name不能为空");
            }
            if (!strpos($name, '.')) {
                $name = $connect['database'] . '.' . $name;
            }
        } catch (Error $e) {
            $e->errorMessage();
        }
        list($this->dbName, $this->name) = explode('.', $name);

        //建立这个模型独有的连接信息
        $this->db(0, empty($this->connection) ? $connect : $this->connection, $force = false);
    }

    private function Db($linkNum = '', $connection = '', $force = false)
    {
        if ($linkNum === '' && $this->db) {
            return $this->db;
        }

        $this->_db[$linkNum] = Db::getInstance($connection);
        // 切换数据库连接
        $this->db = $this->_db[$linkNum];
        return $this;
    }

    /**
     * 得到当前的数据对象
     * @return string
     */
    public function getModelName()
    {
        if (empty($this->name)) {
            $name = substr(get_class($this), 0, -strlen(C('DEFAULT_M_LAYER')));
            if ($pos = strrpos($name, '\\')) {
                //有命名空间
                $this->name = substr($name, $pos + 1);
            } else {
                $this->name = $name;
            }
        }
        return $this->name;
    }

    public function find($field = null)
    {
        return $this->db->select($field, $this->name);
    }

    public function query($sql)
    {

    }

    public function addAll($datalist)
    {
        return $this->db->insertAll($datalist, $this->name);
    }

    public function save($data)
    {
        return $this->db->update($data, $this->name);
    }

    public function add($data = [])
    {
        return $this->db->insert($data, $this->name);
    }

    public function delete()
    {
        return $this->db->delete($this->name);
    }


    public function where($str, $arr)
    {
        $this->db->where($str, $arr);
        return $this;
    }

    public function limit($start = 1, $lenth = 0)
    {
        $this->db->limit($start, $lenth);
    }

    public function group($dataList)
    {
        $this->db->groupBy($dataList);
        return $this;
    }

    public function order($dataList, $sort = '')
    {
        $this->db->orderBy($dataList, $sort);
        return $this;
    }


    public function error()
    {
        return $this->db->error;
    }
}