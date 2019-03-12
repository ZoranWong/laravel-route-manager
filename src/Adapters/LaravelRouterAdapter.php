<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 3:03 PM
 */

namespace ZoranWang\LaraRoutesManager\Adapters;


use function Clue\StreamFilter\fun;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use ZoranWang\LaraRoutesManager\RouteGenerator;

class LaravelRouterAdapter extends RouterAdapter
{
    /**
     * @var Router|RouteRegistrar $router
     * */
    protected $router  = null;


    public function loadRoutes()
    {
        // TODO: Implement group() method.
        $this->router->domain($this->routeDomain)->middleware($this->domainMiddleware)->group(function ($router) {
            /** @var Router $router */
            $router->group([
                'prefix' => $this->routeVersion,
                'middleware' => $this->versionMiddleware
            ], function ($router) {
                /** @var Router $router */
                $router->group([
                    'prefix' => $this->routeGateway,
                    'middleware' => $this->gatewayMiddleware
                ], function ($router) {
                    /** @var Router $router */
                    $this->routes->map(function ($routeGenerator) use($router) {
                        /** @var RouteGenerator $routeGenerator */
                        $routeGenerator->generateRoutes($router);
                    });
                });
            });
        });
        return $this;
    }
}
