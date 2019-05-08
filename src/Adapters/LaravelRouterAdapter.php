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
    const ROUTER_CLASS = Router::class;
    /**
     * @var Router|RouteRegistrar $router
     * */
    protected $router = null;


    public function loadRoutes()
    {
        /** @var RouteGenerator $routeGenerator */
        $routeGenerator = $this->generator;
        $version = $routeGenerator->version;
        $router = $routeGenerator->router;
        /** @var Router $router */
        $router = $router->domain($this->routeDomain);
        if (!empty($this->domainMiddleware))
            $router = $router->middleware($this->domainMiddleware);
        $router->prefix($version)->group(function ($router) use($routeGenerator) {
            /** @var Router $router */
            $gatewayGroup = [
                'prefix' => $this->routeGateway,
            ];
            if (!empty($this->gatewayMiddleware))
                $gatewayGroup['middleware'] = $this->gatewayMiddleware;
            $router->group($gatewayGroup, function ($router) use ($routeGenerator) {
                $routeGenerator->generateRoutes($router);
            });
        });
        return $this;
    }
}
