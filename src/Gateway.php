<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/8
 * Time: 11:47 AM
 */

namespace ZoranWang\LaraRoutesManager;
use Illuminate\Support\Collection;
use ZoranWang\LaraRoutesManager\Adapters\LaravelRouterAdapter;
use ZoranWang\LaraRoutesManager\Adapters\LumenRouterAdapter;


/**
 * @property-read string $gateway
 * @property-read string $middleware
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

    /**
     * @var Collection|RouteGenerator[] $routes
     * */
    protected $routes = [];

    protected $manager = null;

    protected $activeRouteGenerator = null;

    /**
     * @var RoutesManager $routesManager
     * */
    protected $routesManager = null;

    protected $inited = false;

    public function __construct($app, Domain $domain, GatewayManager $manager, Collection $routes)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->manager = $manager;
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
        if($this->active()) {
            $this->routesManager->boot();
            $this->inited = true;
        }
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->gateway;
    }
}
