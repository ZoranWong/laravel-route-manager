<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 3:23 PM
 */

namespace ZoranWang\LaraRoutesManager;


use function Clue\StreamFilter\fun;
use function foo\func;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

/**
 * @property-read string $path
 * @property-read string $domain
 * @property-read string $port
 * @property-read string $protocol
 * @property-read string $root
 * @property-read string $namespace
 * @property-read AdapterContainer $routerAdapters
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
    protected $serverName = null;

    /**
     * @var string $path
     * */
    protected $path = null;

    /**
     * @var string|[] $protocol 协议
     * */
    protected $protocol = 'http';

    /**
     * @var string $port 端口
     * */
    protected $port = '80';

    protected $root = null;

    protected $namespace = null;

    protected $routerAdapters = null;

    public function __construct(Container $app, AdapterContainer $adapterContainer, array $domains, string $root = 'app/Routes', string $namespace = 'App\\Routes')
    {
        $this->app = $app;

        $this->domains = collect();

        $this->routerAdapters = $adapterContainer;

        if(isset($_SERVER['SERVER_NAME'])) {
            $this->serverName = $_SERVER['SERVER_NAME'];
        }

        if(isset($_SERVER['PATH_INFO'])) {
            $this->path = $_SERVER['PATH_INFO'];
        }

        if(isset($_SERVER['SERVER_PROTOCOL'])) {
            $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        }
        if(isset($_SERVER['SERVER_PORT'])) {
            $this->port = $_SERVER['SERVER_PORT'];
        }

        $this->root = $root;
        $this->namespace = $namespace;

        collect($domains)->map(function ($config) {
            $domain = new Domain($this->app,
                $this,
                $config['domain'] ?: null,
                !empty($config['router']) ? $config['router']: 'router',
                !empty($config['request']) ? $config['request'] : 'request',
                !empty($config['middleware']) ? $config['middleware'] : null,
                $config['gateways'] ?: null,
                !empty($config['providers']) ? $config['providers'] : null,
                $this->serverName,
                $this->path,
                !empty($config['protocols']) ? $config['protocols'] : null,
                !empty($config['ports']) ? $config['ports']: null,
                $this->protocol ?: null,
                $this->port ?: null);

            $this->domains->add($domain);
        });
    }

    public function domainValid(string $domain)
    {
        return $this->get($domain) !== null;
    }

    /**
     * @param string $domain
     * @return Domain[]|Collection
     * */
    public function get($domain)
    {
        return $this->domains->when('domain', '=', $domain) ?: null;
    }

    /**
     * 启动路由服务
     * @throws
     */
    public function boot()
    {
        $booted = false;
        $this->domains->map(function (Domain $domain) use(& $booted) {
            if($domain->active() || $this->app->runningInConsole()) {
                $booted = true;
                $domain->boot();
            }
        });
        if(!$booted){
            throw new DomainNotFoundException();
        }
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return isset($this->{$name}) ? $this->{$name} : null;
    }

}
