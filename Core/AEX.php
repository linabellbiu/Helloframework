<?php
// URL 模式定义
const URL_COMMON = 0; //普通模式
const URL_PATHINFO = 1; //PATHINFO模式
const URL_REWRITE = 2; //REWRITE模式

// 类文件后缀
const EXT = '.class.php';

//配置文件后缀
const CONFIG = '.conf.php';

define('INDEX_CONTROLLER', 'IndexController');                          //默认的控制器
define('INDEX_METHOD', 'index');                                        //默认的方法
define('UTF_8', true);                                                  //开启utf8输出
define('APP_PATH', PATH . '/' . APP_NAME . '/');                        //定义应用目录
define('APP_INTI', APP_PATH . 'init.php');                              //应用初始化加载
define('APP_CONFIG', 'app_config');                                     //应用配置名
define('APP_CONFIG_PATH', APP_PATH . 'conf/');                            //应用配置名路径
define('APP_ROUTE', APP_PATH . 'rule/route.php');                       //应用路由配置
define('APP_RULE', APP_PATH . 'rule/validate.php');                     //应用验证规则
define('APP_LANGUAGE_PATH', APP_PATH . 'language/');                    //应用语言包路径
define('APP_LANGUAGE', 'language');                                    //应用语言包配置名

define('CORE_PATH', PATH . '/Core/');                                   //核心目录
define('CORE_COMMON_PATH', CORE_PATH . 'common/');                      //公共目录
define('SYS_CONFIG', 'system' . CONFIG);                                //系统配置文件名
define('SYS_CONFIG_PATH', CORE_PATH . 'conf/' . SYS_CONFIG);            //系统配置文件

define('CONTROLLER_METHOD_DELIMIT', '@');                               //控制器和方法的分隔符
define('__CONTEROLLERINFO__', '\\' . APP_NAME . '\\controller\\');      //控制器的命名空间

define('__VIEW__', PATH . '/View');      //模板引擎位置

require_once PATH . '/vendor/autoload.php';
\Core\Core::run();