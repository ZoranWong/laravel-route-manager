<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/8
 * Time: 11:47 AM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property-read string $domain
 * @property-read Collection|null $gateways
 * @property-read GatewayManager $gatewayManager
 * @property-read Request $request
 * @property-read Router $router
 * @property-read array|null $middleware
 * @property-read string $path
 * @property-read  string $serverName
 * @property-read string $root
 * @property-read string $namespace
 * @property-read AdapterContainer $routerAdapters
 * */
class Domain
{
    protected $domain = null;

    /**
     * @var ServiceProvider[]|Collection $providers
     * */
    protected $providers = [];

    protected $middleware = [];

    /**
     * @var Container|Application|null $app
     * */
    protected $app  = null;

    /**
     * @var Request $request
     * */
    protected $request = null;

    /**
     * @var Router $router
     * */
    protected $router = null;

    /**
     * @var GatewayManager $gatewayManager
     * */
    protected $gatewayManager = null;

    /**
     * @var DomainManager $manager
     * */
    protected $manager = null;

    /**
     * @var Collection|[]|null $gateways
     * */
    protected $gateways = null;

    /**
     * @var string $serverName 访问主机域名
     * */
    protected $serverName = null;

    /**
     * @var string $path 访问路径
     * */
    protected $path = null;

    /**
     * @var boolean $inited 是否初始化
     * */
    protected $inited = false;

    protected $protocols = ['http', 'https'];

    protected $ports = ['80'];

    protected $protocol = null;

    protected $port = null;

    /**
     * @param Container $app
     * @param DomainManager $manager
     * @param string $domain
     * @param string $router
     * @param string $request
     * @param array|null $middleware
     * @param array|null $gateways
     * @param array|null $providers
     * @param string|null $serverName
     * @param string|null $path
     * @param array|null $protocols
     * @param array|null $ports
     * @param string $protocol
     * @param string $port
     */
    public function __construct(Container $app, DomainManager $manager, string $domain, string $router, string $request, ?array $middleware, ?array $gateways, ?array $providers,
                                ?string $serverName, ?string $path, ?array $protocols = null, ?array $ports = null, string $protocol = 'http', string $port = '80')
    {
        $this->app = $app;
        $this->manager = $manager;
        $this->domain = $domain;
        $this->router = $this->app->get($router);
        $this->request = $this->app->get($request);
        $this->middleware = $middleware;
        $this->gateways = collect($gateways);
        $this->providers = collect($providers);
        $this->serverName = $serverName;
        $this->path = $path;
        $this->protocols = $protocols ?: $this->protocols;
        $this->ports = $ports ?: $this->ports;
        $this->protocol = $protocol;
        $this->port = $port;
        $this->initialization();
    }

    public function initialization()
    {
        if($this->active() && !$this->inited || $this->app->runningInConsole()) {
            $this->inited = false;
            $this->providers->map(function ($provider) {
                $this->app->register($provider);
            });
            $this->gatewayManager = new GatewayManager($this->app, $this, $this->gateways);
        }
    }

    /**
     * @throws
     * */
    public function boot()
    {
        $this->gatewayManager->boot();
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return isset($this->{$name}) ? $this->{$name} : $this->manager->{$name};
    }

    public function active()
    {
        return !in_array($this->protocol, $this->protocols) && !in_array($this->port, $this->ports) && $this->domain === $this->serverName;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->domain;
    }
}
