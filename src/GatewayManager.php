<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 2:39 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

class GatewayManager
{
    /**
     * @var Container $app
     * */
    protected $app = null;

    /**
     * @var Gateway[]|Collection
     * */
    protected $gateways = [];

    /**
     * @var Domain $domain
     * */
    protected $domain = null;

    public function __construct($app, Domain $domain, Collection $gateways)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->gateways = collect();
        $gateways->map(function ($config) {
            $gateway = new Gateway();
            $this->gateways->put($config['gateway'], $gateway);
        });
    }

    /**
     * @param string $gateway
     * @return Gateway|null
     * */
    public function get(string $gateway)
    {
        return $this->gateways->get($gateway) ?: null;
    }

    public function gatewayValid(string $gateway)
    {
        return $this->get($gateway) !== null;
    }

    /**
     * @param string $path
     * @throws
     */
    public function generateRoutes(string $path)
    {
        if(($gateway = $this->get($path))) {
            $gateway->loadRoutes();
        }else{
            throw new GatewayNotFoundException();
        }
    }
}
