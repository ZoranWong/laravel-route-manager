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
 * @property-read array|null $middleware
 * @property-read string $path
 * @property-read  string $serverName
 * @property-read string $root
 * @property-read string $namespace
 * @property-read AdapterContainer $routerAdapters
 * */
class Domain
{
    /**
     * @var string $domain 本实例支持的域名
     * */
    protected $domain = null;

    /**
     * @var ServiceProvider[]|Collection $providers 服务提供者
     * */
    protected $providers = [];

    /**
     * @var Collection|string[] 中间件数组
     * */
    protected $middleware = [];

    /**
     * @var Container|Application|null $app
     * */
    protected $app  = null;

    /**
     * @var GatewayManager $gatewayManager
     * */
    protected $gatewayManager = null;

    /**
     * @var DomainManager $manager 域名管理器
     * */
    protected $manager = null;

    /**
     * @var Collection|[]|null $gateways 网关数组
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

    /**
     * @var string[] $protocols 服务支持的协议
     * */
    protected $protocols = ['http/1.0', 'http/1.1', 'http/1.2'];

    /**
     * @var string[] $ports 服务部署网关
     * */
    protected $ports = ['80'];

    /**
     * @var $protocol 客户端发起访问的协议
     * */
    protected $protocol = null;

    /**
     * @var $port 客户端发起访问的端口号
     * */
    protected $port = null;

    /**
     * @param Container $app 程序原型上下文
     * @param DomainManager $manager 域名管理器
     * @param string $domain 实例支持的域名
     * @param string $router 程序使用的路由器
     * @param string $request 程序使用的请求接收器
     * @param array|null $middleware 中间件
     * @param array|null $gateways 网关
     * @param array|null $providers 服务提供者
     * @param string|null $serverName 客户端访问的域名
     * @param string|null $path 客户端访问的路径
     * @param array|null $protocols 实例支持的协议数组
     * @param array|null $ports 实例支持的端口号数组
     * @param string $protocol 访问者的协议
     * @param string $port 访问者的端口
     */
    public function __construct(Container $app, DomainManager $manager, string $domain,  ?array $middleware, ?array $gateways, ?array $providers,
                                ?string $serverName, ?string $path, ?array $protocols = null, ?array $ports = null, string $protocol = 'http', string $port = '80')
    {
        $this->app = $app;
        $this->manager = $manager;
        $this->domain = $domain;
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
        return $this->protocolIsSupport() && $this->portIsSupport() && $this->domain === $this->serverName;
    }

    protected function protocolIsSupport()
    {
        if(in_array(strtolower($this->protocol), $this->protocols)){
            return true;
        }
        throw new ProtocolInvalidException("此服务不支持{$this->protocol}协议");
    }

    protected function portIsSupport()
    {
        if(in_array($this->port, $this->ports)){
            return true;
        }
        throw new PortInvalidException("此服务未部署在{$this->port}端口");
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->domain;
    }
}
