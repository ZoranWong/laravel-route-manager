<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 2:39 PM
 */

namespace ZoranWang\LaraRoutesManager;


use function Clue\StreamFilter\fun;
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

    public function __construct($app, Domain $domain, Collection $gateways)
    {
        $this->app = $app;
        $this->domain = $domain;
        $this->path = $this->domain->path;
        $this->gateways = collect();
        if(!empty($gateways)) {
            $gateways->map(function ($config) {
                $gateway = new Gateway($this->app, $config['gateway'],
                    !empty($config['middleware']) ? $config['middleware'] : null,
                    !empty($config['providers']) ? $config['providers'] : null,
                    $this->domain, $this, collect($config['routes']));
                $this->gateways->add($gateway);
            });
        }
    }

    /**
     * @param string $gateway
     * @return Gateway[]|Collection
     * */
    public function get(string $gateway)
    {
        return $this->gateways->where('gateway', '=', $gateway) ?: null;
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
        $booted = false;
        $this->gateways->map(function (Gateway $gateway) use(&$booted){
            $gateway->boot();
            if($gateway->inited) {
                $booted = true;
            }
        });
        if(!$booted){
            throw new GatewayNotFoundException();
        }
    }
}
