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
use ZoranWang\LaraRoutesManager\Adapters\DingoRouterAdapter;
use ZoranWang\LaraRoutesManager\Adapters\LaravelRouterAdapter;
use ZoranWang\LaraRoutesManager\Adapters\LumenRouterAdapter;
use ZoranWang\LaraRoutesManager\Adapters\RouterAdapter;

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

    /**
     * @param Container $app
     * @param Request $request
     * @param Domain $domain
     * @param Gateway $gateway
     * @param Collection $routes
     */
    public function __construct($app, $request, Domain $domain, Gateway $gateway, Collection $routes)
    {
        $this->app = $app;
        $this->root = $domain->root;
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

        switch (get_class($this->domain->router)) {
            case "Dingo\Api\Routing\Router" : {
                $this->adapter =new  DingoRouterAdapter($this->domain->router, $this->routes);
                break;
            }
            case "Illuminate\Routing\Router" : {
                $this->adapter = new  LaravelRouterAdapter($this->domain->router, $this->routes);
                break;
            }
            case "Laravel\Lumen\Routing\Router" : {
                $this->adapter = new LumenRouterAdapter($this->domain->router, $this->routes);
                break;
            }
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
