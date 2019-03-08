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

/**
 * @property-read GatewayManager $gatewayManager
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
     * @var Collection|null $gateways
     * */
    protected $gateways = null;

    protected $path = null;

    public function __construct($app, DomainManager $manager, string $domain, string $router, string $request, array $gateways, array $providers, string $path)
    {
        $this->app = $app;
        $this->manager = $manager;
        $this->domain = $domain;
        $this->router = $this->app->get($router);
        $this->request = $this->app->get($request);
        $this->gateways = collect($gateways);
        $this->providers = collect($providers);
        $this->path = $path;
        $this->initialization();
    }

    public function initialization()
    {
        $this->providers->map(function ($provider) {
            $this->app->register($provider);
        });
        $this->gatewayManager = new GatewayManager($this->app, $this, $this->gateways, $this->router, $this->path);
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
}
