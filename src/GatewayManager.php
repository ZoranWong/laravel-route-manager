<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 2:39 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Router;
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
     * @var Router $router
     * */
    protected $router = null;

    /**
     * @var Domain $domain
     * */
    protected $domain = null;

    protected $path = null;

    public function __construct($app, Domain $domain)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->path = $this->domain->path;
        $this->gateways = collect();
        if(!empty($this->domain->gateways)) {
            $this->domain->gateways->map(function ($config) {
                $gateway = new Gateway();
                $this->gateways->put($config['gateway'], $gateway);
            });
        }
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
     * @throws
     */
    public function boot()
    {
        if(($gateway = $this->get($this->path))) {
            $gateway->boot();
        }else{
            throw new GatewayNotFoundException();
        }
    }
}
