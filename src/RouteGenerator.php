<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 1:27 PM
 */

namespace ZoranWang\LaraRoutesManager;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Symfony\Component\HttpFoundation\Request;


/**
 * @property-read string $version
 * @property-read Domain $domain
 * @property-read Router $router
 * @property-read Application $app
 * @property-read Request $request
 * @property-read bool $versionInHeader
 * */
abstract class RouteGenerator
{
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

    /**
     * @var Request $request
     * */
    protected $request = null;

    /**
     * @var Router $router
     * */
    protected $router = null;

    /**
     * @var bool $versionInHeader
     * */
    protected $versionInHeader = false;

    /**
     * @param Container $app
     * @param Domain $domain
     * @param Gateway $gateway
     * @param string|null $namespace
     * @param array $version
     * @param string|null $auth
     * @param array $middleware
     * @param string $request
     * @param string $router
     */
    public function __construct(Container $app, Domain $domain, Gateway $gateway, ?string $namespace,
                                 $version, ?string $auth, array $middleware, string $request,
                                string $router)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->namespace = $namespace;
        $this->version = is_string($version) ? $version : $version['value'];
        $this->gateway = $gateway;
        $this->middleware = $middleware;
        $this->auth = $auth;
        $this->request = $app->get($request);
        $this->router = $app->get($router);
        $this->versionInHeader = is_array($version) ? isset($version['in_header']) && $version['in_header'] : false;
    }

    /**
     * 路由规则生成方法（加载路由规则）
     * @param Router $router
     * @throws RouterNotFoundException
     */
    public function generateRoutes($router)
    {
        /** @var Router $router */
        try {
            $this->router->group(['namespace' => $this->namespace, 'middleware' => $this->middleware], function ($router) {
                /** @var Router $router */
                $router->group(['auth' => $this->auth], function ($router) {
                    $this->auth($router);
                });
                $this->normal($router);
            });
        } catch (\Exception $e) {
            throw new RouterNotFoundException();
        }
    }

    public function active()
    {
        $path = $this->gateway;
        $path = '/^' . preg_replace('/\//', '\\/', trim($path, '/')) . '/';
        return preg_match($path, trim($this->domain->path, '/')) !== false;
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
