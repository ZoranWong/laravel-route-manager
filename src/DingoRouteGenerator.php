<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 1:45 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Dingo\Api\Routing\Router;

class DingoRouteGenerator extends RouteGenerator
{
    /**
     * @param Router $router
     * */
    protected function addDomainToRouter($router)
    {
        $router->version($this->version, ['domain' => $this->domain->domain, 'middleware' > $this->domain->middleware], function (Router $router) {
            $this->addGatewayToRouter($router);
        });
    }

    /**
     * @param Router $router
     * */
    protected function addGatewayToRouter($router)
    {
        $router->group(['prefix' => $this->gateway->gateway, 'middleware' => $this->gateway->middleware], function ($router) {
            $this->auth($router);
            $this->normal($router);
        });
    }

    protected function auth($router)
    {

    }

    protected function normal($router)
    {

    }
}
