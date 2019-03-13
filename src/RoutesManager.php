<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 2:30 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use ZoranWang\LaraRoutesManager\Adapters\RouterAdapter;
use ZoranWang\LaraRoutesManager\Exception\AdapterNotFoundException;

class RoutesManager
{
    /**
     * @var Collection|RouterAdapter[] $routes
     * */
    protected $routes = [];

    /**
     * @var Domain $domain
     * */
    protected $domain = null;

    /**
     * @var Gateway $gateway
     * */
    protected $gateway = null;

    /**
     * @var Request $request
     * */
    protected $request = null;
    /**
     * @var Container $app
     * */
    protected $app = null;
    protected $rootNamespace = null;
    protected $root = null;

    /**
     * @var RouterAdapter $adapter
     * */
    protected $adapter = null;

    protected $routerAdapters = null;
    /**
     * @param Container $app
     * @param AdapterContainer $routerAdapters
     * @param Request $request
     * @param Domain $domain
     * @param Gateway $gateway
     * @param Collection $routes
     * @throws
     */
    public function __construct(Container $app, AdapterContainer $routerAdapters, Request $request, Domain $domain, Gateway $gateway, Collection $routes)
    {
        $this->app = $app;
        $this->root = $domain->root;
        $this->routerAdapters = $routerAdapters;
        $this->domain = $domain;
        $this->gateway = $gateway;
        $this->request = $request;
        $this->rootNamespace = $domain->namespace;
        $this->routes = $routes->map(function ($routeConfig) {

            if(!class_exists($routeConfig['generator'])) {
                /** @var RouteGenerator $routeGenerator */
                $routeGeneratorClass = trim($routeConfig['generator'], '\\');
                $rootNamespace = trim($this->rootNamespace, '\\');
                $routeGeneratorClass = str_replace($rootNamespace, '', $routeGeneratorClass);
                $routeGeneratorClass = trim($routeGeneratorClass, '\\');
                $routeGeneratorClass = str_replace("\\", "/", $routeGeneratorClass);
                $path = trim($this->root, '/').'/'.trim($routeGeneratorClass, '/').'.php';
                /** @noinspection PhpIncludeInspection */
                include_once base_path($path);
            }
            /**
             * @param Container $app
             * @param Domain $domain
             * @param Gateway $gateway
             * @param string $namespace
             * @param string $version
             * @param string $auth
             * @param array $middleware
             * @param Request $request
             */
             /** @var RouteGenerator $routeGenerator */
            $routeGenerator = new $routeConfig['generator']($this->app, $this->domain, $this->gateway, $routeConfig['namespace'],
                $routeConfig['version'], $routeConfig['auth'], $routeConfig['middleware'], $this->request);

            return $routeGenerator;
        });
        $routeClass = get_class($this->domain->router);
        if($routeClass) {
            $this->adapter =new  $this->routerAdapters[$routeClass]($this->domain->router, $this->routes);
        }else{
            throw new AdapterNotFoundException("{$routeClass}没有查找到对应的是配置器");
        }

    }



    public function boot()
    {
        $booted = false;

        if($this->adapter->active() || $this->app->runningInConsole()) {
            $booted = true;
            $this->adapter->domain($this->domain, $this->domain->middleware ?: null)
                ->gateway($this->gateway, $this->gateway->middleware)
                ->loadRoutes();
        }
        if(!$booted) {
            throw new RouteNotFoundException();
        }
    }

    /**
     * @return boolean
     * */
    public function active()
    {
        /** @var RouterAdapter $adapter */
        return $this->adapter->active();
    }
}
