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
 * @property-read string $middleware
 *
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

    public function __construct($app, Domain $domain, GatewayManager $manager, Collection $routes)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->manager = $manager;
        $this->routes = $routes->map(function ($routeConfig) {
            /** @var RouteGenerator $routeGenerator */
            $routeGenerator = new $routeConfig['generator']();
            if($routeGenerator->isActive()) {
                $this->activeRouteGenerator = $routeGenerator;
            }
        });
    }

    public function isActive()
    {
        return preg_match("/^{$this->gateway}/", trim($this->domain->path, '/')) || $this->activeRouteGenerator;
    }

    /**
     * @throws
     * */
    public function boot()
    {
        $routeGeneratorClass = $this->routeGenerator;
        $routeGeneratorClass = preg_replace($this->namespace, '', $routeGeneratorClass);
        $routeGeneratorClass = preg_replace('\\', '/', $routeGeneratorClass);
        if(!class_exists($this->routeGenerator)) {
            $path = trim($this->root, '/').'/'.trim($routeGeneratorClass, '/').'.php';
            /** @noinspection PhpIncludeInspection */
            include_once base_path($path);
        }
        /** @var RouteGenerator $generator */
        $generator = new $this->routeGenerator();
        $generator->generateRoutes();
    }
}
