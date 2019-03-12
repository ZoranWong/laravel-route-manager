<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/8
 * Time: 11:47 AM
 */

namespace ZoranWang\LaraRoutesManager;
use Illuminate\Support\Collection;


/**
 * @property-read string $gateway
 * @property-read array|null $middleware
 * @property-read boolean $inited
 * */
class Gateway
{
    protected $app = null;

    protected $domain = null;

    protected $routeGenerator = null;

    protected $namespace = null;

    protected $root = null;

    protected $gateway = null;

    protected $middleware = null;

    protected $providers = null;

    /**
     * @var Collection|RouteGenerator[] $routes
     * */
    protected $routes = [];

    protected $manager = null;

    /**
     * @var RoutesManager $routesManager
     * */
    protected $routesManager = null;

    protected $inited = false;

    public function __construct($app, string $gateway, $middleware, $providers, Domain $domain, GatewayManager $manager, Collection $routes)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->manager = $manager;
        $this->gateway = $gateway;
        $this->middleware = $middleware;
        $this->providers  = collect($providers);
        $this->routes = $routes;
        $this->root = $domain->root;
        $this->namespace = $domain->namespace;
        $this->routesManager = new RoutesManager($app,$domain->request, $domain, $this, $routes);
    }

    public function active()
    {
        return preg_match("/^{$this->gateway}/", trim($this->domain->path, '/')) || $this->routesManager->active();
    }

    /**
     * @throws
     * */
    public function boot()
    {
        if($this->active() || $this->app->runningInConsole()) {
            $this->routesManager->boot();
            $this->inited = true;
        }
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->gateway;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->{$name};
    }
}
