<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 3:03 PM
 */

namespace ZoranWang\LaraRoutesManager\Adapters;


use Illuminate\Support\Collection;
use ZoranWang\LaraRoutesManager\RouteGenerator;

abstract class RouterAdapter
{
    /**
     * @var RouteGenerator $routeGenerator
     * */
    protected $routeGenerator = null;
    protected $router = null;
    protected $routeVersion = null;
    protected $versionMiddleware = null;
    protected $routeDomain = null;
    protected $domainMiddleware = null;

    protected $routeGateway = null;
    protected $gatewayMiddleware = null;

    /**
     * @var Collection|RouteGenerator[]
     * */
    protected $routes = [];

    public function __construct($router, $routes = null)
    {
        $this->router = $router;
        $this->routes = $routes;
    }

    /**
     * @param string $domain
     * @param string|array|null $middleware
     * @return RouterAdapter
     */
    public function domain(string $domain, $middleware = null)
    {
        // TODO: Implement domain() method.
        $this->routeDomain = $domain;
        $this->domainMiddleware = (array)$middleware;
        return $this;
    }

    public function gateway(string $gateway, $middleware)
    {
        // TODO: Implement gateway() method.
        $this->routeGateway = $gateway;
        $this->gatewayMiddleware = $middleware;
        return $this;
    }

    public function version(string $version, $middleware)
    {

        // TODO: Implement version() method.
        $this->routeVersion = $version;
        $this->versionMiddleware = $middleware;
        return $this;
    }

    public function active() {
        return $this->routeGenerator->active();
    }

    abstract public function loadRoutes() ;
}
