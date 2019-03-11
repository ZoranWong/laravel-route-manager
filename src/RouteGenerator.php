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

/**
 * @property-read string $version
 * */
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
     * @var Gateway $gateway 路由网关（域名后面的）
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
     * @var Domain $domain 域名
     * */
    protected $domain = null;

    public function __construct($app, Domain  $domain, Gateway $gateway, string $namespace, string $version, string $auth, array $middleware, string $router)
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
            /** @var Router $router */
            $router = $this->app->make($this->router);

            $router->domain($this->domain->domain)->prefix("$this->version/$this->gateway");
        } catch (BindingResolutionException $e) {
            throw new RouterNotFoundException();
        }
    }

    public function isActive()
    {
        return false;
    }

    /**
     * @param Router $router
     * */
    protected function addDomainToRouter($router)
    {
        $router->domain($this->domain->domain)->middleware($this->domain->middleware)->group(function (Router $router) {
            $this->addGatewayToRouter($router);
        });
    }

    /**
     * @param Router $router
     * */
    protected function addGatewayToRouter($router)
    {
        $prefix = $this->version ? "{$this->version}/{$this->gateway->gateway}" : $this->gateway->gateway;
       $router->prefix($prefix)->middleware($this->gateway->middleware)->group(function ($router) {
           $this->auth($router);
           $this->normal($router);
       });
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->{$name};
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
