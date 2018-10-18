<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/17
 * Time: 17:41
 */

namespace Core\Db\driver;

use Core\Db\Driver;

class Mysql extends Driver{

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
    protected function parseDsn($config)
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
}