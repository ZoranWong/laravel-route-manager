<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 3:05 PM
 */

namespace ZoranWang\LaraRoutesManager\Adapters;


use Dingo\Api\Routing\Router;
use ZoranWang\LaraRoutesManager\RouteGenerator;

class DingoRouterAdapter extends RouterAdapter
{
    /**
     * @var Router $router
     * */
    protected $router = null;

    public function loadRoutes()
    {
        // TODO: Implement group() method.
        $this->router->version($this->routeVersion, [
            'middleware' => $this->versionMiddleware
        ], function ($router){
            /** @var Router $router */
            $router->group([
                'prefix' => $this->routeGateway,
                'middleware' => $this->gatewayMiddleware
            ], function ($router) {
                $this->routes->map(function ($routeGenerator) use($router) {
                    /** @var RouteGenerator $routeGenerator */
                    $routeGenerator->generateRoutes($router);
                });
            });
        });
    }
}
