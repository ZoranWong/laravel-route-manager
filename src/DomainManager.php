<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 3:23 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

/**
 * @property-read string $path
 * @property-read string $domain
 * @property-read string $port
 * @property-read string $protocol
 * */
class DomainManager
{
    /**
     * @var Collection|null $domains 域名数组
     * */
    protected $domains = null;

    /**
     * @var GatewayManager|null $gatewayManager
     * */
    protected $gatewayManager = null;

    /**
     * @var Container $app
     * */
    protected $app = null;

    /**
     * @var string $domain
     * */
    protected $domain = null;

    /**
     * @var string $path
     * */
    protected $path = null;

    /**
     * @var string $request
     * */
    protected $request = null;

    /**
     * @var string $router
     * */
    protected $router = null;

    /**
     * @var string|[] $protocol 协议
     * */
    protected $protocol = 'http';

    /**
     * @var string $port 端口
     * */
    protected $port = '80';

    public function __construct($app, array $domains)
    {
        $this->domains = collect($domains);
        $this->app = $app;
        $this->domain = $_SERVER['SERVER_NAME'];
        $this->path = $_SERVER['PATH_INFO'];
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        $this->port = $_SERVER['SERVER_PORT'];
    }

    public function domainValid(string $domain)
    {
        return $this->get($domain) !== null;
    }

    public function get($domain)
    {
        return ($key = $this->domains->search(function (array $item) use($domain){
            return $item['domain'] === $domain;
        })) !== false ? $key : null;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDomain() : string
    {
        return $this->domain;
    }

    /**
     * @return string 端口
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * 启动路由服务
     * @throws DomainNotFoundException
     * @throws GatewayNotFoundException
     * @throws PortInvalidException
     * @throws ProtocolInvalidException
     * @throws RouterNotFoundException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $gatewaysConfig = $this->get($this->domain);
        if(!$gatewaysConfig) {
            throw new DomainNotFoundException();
        }
        /** @var GatewayManager $gateway */
        $gateway = $this->app->get('gateway');
        $gateway->setRequest($gatewaysConfig['request'])
            ->setRouter($gatewaysConfig['router'])
            ->setGateways($gatewaysConfig['gateways'])
            ->generateRoutes($this->path);
    }

}
