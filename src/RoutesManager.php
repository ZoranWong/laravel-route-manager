<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 2:30 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Support\Collection;
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

    protected $rootNamespace = null;
    protected $root = null;

    /**
     * @param Collection $routes
     * */
    public function __construct($routes)
    {
        $this->routes = $routes->map(function ($routeConfig) {
            /** @var RouteGenerator $routeGenerator */
            $routeGeneratorClass = $routeConfig['generator'];
            $routeGeneratorClass = preg_replace($this->rootNamespace, '', $routeGeneratorClass);
            $routeGeneratorClass = preg_replace('\\', '/', $routeGeneratorClass);
            if(!class_exists($routeConfig['generator'])) {
                $path = trim($this->root, '/').'/'.trim($routeGeneratorClass, '/').'.php';
                /** @noinspection PhpIncludeInspection */
                include_once base_path($path);
            }
             /** @var RouteGenerator $routeGenerator */
            $routeGenerator = new $routeConfig['generator']();
            switch (get_class($routeGenerator)) {
                case "\Dingo\Api\Routing\Router" : {
                    return new DingoRouterAdapter($routeGenerator);
                    break;
                }
                case "\Illuminate\Routing\Router" : {
                    return new LaravelRouterAdapter($routeGenerator);
                    break;
                }
                case "\Laravel\Lumen\Routing\Router" : {
                    return new LumenRouterAdapter($routeGenerator);
                    break;
                }
            }
            return null;
        });
    }

    public function boot()
    {
        $booted = false;
        $this->routes->map(function ($adapter) use(&$booted){
            /** @var RouterAdapter $adapter */
            if($adapter->active()) {
                $booted = true;
                $adapter->loadRoutes();
            }
        });
        if(!$booted) {
            throw new RouteNotFoundException();
        }
    }

    /**
     * @return boolean
     * */
    public function active()
    {
        return $this->routes->search(function ($adapter) {
            /** @var RouterAdapter $adapter */
            return $adapter->active();
        }) !== false;
    }
}
