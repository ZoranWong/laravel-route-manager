<?php /** @noinspection PhpIncludeInspection */

/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 12:17 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Support\ServiceProvider;

class RoutesManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        if($this->app->runningInConsole()) {
            if(!($config = config('routes', null))) {
                $this->mergeConfigFrom(realpath(__DIR__.'/../config/routes.php'), 'routes');
                $this->publishes([realpath(__DIR__.'/../config/routes.php') => config_path('routes.php')]);
            }
            $path = base_path(config('routes.root'));
            if(!file_exists($path)) {
                @mkdir($path);
            }
        }

        $this->app->singleton('domain', function ($app) {
            return new DomainManager($app,
                config('routes.domains'),
                config('routes.root'),
                config('routes.namespace'));
        });
    }

    public function boot()
    {
        if(!$this->app->runningInConsole()) {
            $this->app->get('domain')->boot();
        }
    }
}
