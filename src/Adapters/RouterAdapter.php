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

    public function __construct($router, ?Collection $routes = null)
    {
        $this->router = $router;
        $this->routes = $routes;
    }

    /**
     * @param string $domain
     * @param string|array|null $middleware
     * @return RouterAdapter
     */
    public function domain(string $domain, ?array $middleware = null)
    {
        // TODO: Implement domain() method.
        $this->routeDomain = $domain;
        $this->domainMiddleware = (array)$middleware;
        return $this;
    }

    public function gateway(string $gateway, ?array $middleware)
    {
        // TODO: Implement gateway() method.
        $this->routeGateway = $gateway;
        $this->gatewayMiddleware = $middleware;
        return $this;
    }

    public function version(string $version, ?array $middleware)
    {

        // TODO: Implement version() method.
        $this->routeVersion = $version;
        $this->versionMiddleware = $middleware;
        return $this;
    }

    public function active() {
        $active = false;
        $this->routes->map(function ($generator) use(&$active){

                /** @var RouteGenerator $generator */
                if($generator->active()){
                    $active = true;
                }
        });
        return $active;
    }

    abstract public function loadRoutes() ;
}
