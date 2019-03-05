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
     * @var Collection|null $gateways 网关数组
     * */
    protected $gateways = null;

    /**
     * @var string $domain
     * */
    protected $domain = null;

    /**
     * @var string $request
     * */
    protected $request = null;

    /**
     * @var string $router
     * */
    protected $router = null;

    /**
     * @var Container $app
     * */
    protected $app = null;

    protected $gateway = null;

    /**
     * @var DomainManager $domainManager
     * */
    protected $domainManager = null;

    public function __construct($app, DomainManager $domainManager)
    {
        $this->app = $app;
        $this->domainManager  = $domainManager;
        $this->domain = $this->domainManager->domain;
    }

    public function setRouter(string $router)
    {
        $this->router = $router;
        return $this;
    }

    public function setRequest(string $request)
    {
        $this->$request = $request;
        return $this;
    }

    public function setGateways(array $gateways)
    {
        $this->gateways = collect($gateways);
        return $this;
    }

    public function get(string $gateway)
    {
        return ($key = $this->gateways->search(function (array $item) use ($gateway) {
            return preg_match("/^{$item['gateway']}(\/.*)/" , $gateway);
        })) !== false ? $this->gateways->get($key) : null;
    }

    public function gatewayValid(string $gateway)
    {
        return $this->get($gateway) !== null;
    }

    /**
     * @param string $gateway
     * @throws GatewayNotFoundException
     * @throws PortInvalidException
     * @throws ProtocolInvalidException
     * @throws RouterNotFoundException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function generateRoutes(string $gateway)
    {
        if(($gatewayConfig = $this->get($gateway))) {
            $this->gateway = $gatewayConfig['gateway'];
            $route = $gatewayConfig['route'];
            if(isset($gatewayConfig['protocol']) && preg_match("/{$gatewayConfig['protocol']}/", $this->domainManager->protocol) === false) {
                throw new ProtocolInvalidException("服务不支持{$gatewayConfig['protocol']}协议");
            }

            if(isset($gatewayConfig['port']) && preg_match("/{$gatewayConfig['port']}/", $this->domainManager->port) === false) {
                throw new PortInvalidException("服务没有部署在{$gatewayConfig['port']}端口下");
            }

            /** @var RouteGenerator $generator */
            $generator = $this->app->make($route['generator'], [$this->app, $this->domain,
                $gatewayConfig['namespace'], $gatewayConfig['version'], $this->gateway,
                $gatewayConfig['auth'], $gatewayConfig['middleware'], $this->router]);
            $generator->generateRoutes();
        }else{
            throw new GatewayNotFoundException();
        }
    }
}
