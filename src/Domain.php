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
 * @property-read Collection|null $middleware
 * @property-read string $path
 * @property-read  string $serverName
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

    public function __construct($app, DomainManager $manager, string $domain, string $router, string $request, array $gateways, array $providers, string $serverName, string $path)
    {
        $this->app = $app;
        $this->manager = $manager;
        $this->domain = $domain;
        $this->router = $this->app->get($router);
        $this->request = $this->app->get($request);
        $this->gateways = collect($gateways);
        $this->providers = collect($providers);
        $this->serverName = $serverName;
        $this->path = $path;
        $this->initialization();
    }

    public function initialization()
    {
        if($this->active() && !$this->inited) {
            $this->inited = false;
            $this->providers->map(function ($provider) {
                $this->app->register($provider);
            });
            $this->gatewayManager = new GatewayManager($this->app, $this);
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
        return $this->{$name};
    }

    public function active()
    {
        return $this->domain === $this->serverName;
    }
}
