<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 1:27 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Router;

abstract class RouteGenerator
{
    /**
     * @var string $router
     * */
    protected $router  = null;

    /**
     * @var [] $middleware 中间件
     * */
    protected $middleware = [];

    /**
     * @var string $auth 认证方式
     * */
    protected $auth = null;

    /**
     * @var string $gateway 路由网关（域名后面的）
     * */
    protected $gateway = null;

    /**
     * @var string $version 路由版本
     * */
    protected $version = null;

    /**
     * @var string $namespace 命名空间
     * */
    protected $namespace = null;

    /**
     * @var Container $app
     * */
    protected $app = null;

    /**
     * @var string $domain 域名
     * */
    protected $domain = null;

    public function __construct($app, string  $domain, string $namespace, string $version, string $gateway, string $auth, array $middleware, string $router)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->namespace = $namespace;
        $this->version = $version;
        $this->gateway = $gateway;
        $this->middleware = $middleware;
        $this->router = $router;
        $this->auth = $auth;
    }

    /**
     * 路由规则生成方法（加载路由规则）
     * @throws RouterNotFoundException
     * */
    public function generateRoutes()
    {
        /** @var Router $router */
        try {
            $router = $this->app->make($this->router);
            $router->group(['domain' => $this->domain, 'version' => $this->version], function ($router) {
                $this->normal($router);
                $this->auth($router);
            });

        } catch (BindingResolutionException $e) {
            throw new RouterNotFoundException();
        }
    }

    /**
     * 需要认证权限的路由规则定义
     * @param Router $router
     * */
    abstract protected function auth($router);

    /**
     * 普通路由规则定义
     * @param Router $router
     * */
    abstract protected function normal($router);
}
