<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/22
 * Time: 15:41
 */

namespace Core;

use Core\Hello\Middleware\Auth;
use Core\Hello\MiddlewareInterFace;

class Middleware
{

    /**
     * @var array
     */
    private $allMiddleware = [];


    private $allMiddlewareClosure;


    /**
     * 系统自带的中间件路径
     * @var string
     */
    private $sysMiddlewarePath = 'Core\Hello\Middleware\\';


    /**
     * 用来添加中间件
     * @param $middleware
     */
    public function middleware($middleware)
    {
        strpos($middleware, '\\') !== false ?
            $this->addMiddleware($middleware) :
            $this->addMiddleware($this->sysMiddlewarePath . $middleware);
    }


    public function run()
    {
        return $this->allMiddlewareClosure = array_reduce($this->allMiddleware, function ($next, $middleware) {
            return function () use ($next, $middleware) {
                new $middleware($next);
            };
        })();
    }

    private function handle()
    {

    }


    /**
     * @param $middleware
     */
    private function addMiddleware($middleware)
    {
        $this->allMiddleware[] = $middleware;
    }
}