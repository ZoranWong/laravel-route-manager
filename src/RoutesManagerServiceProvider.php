<?php
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
        $this->app->singleton('domain', function ($app) {
            return new DomainManager($app, config('routes'));
        });
    }

    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([realpath(__DIR__.'/../config/routes.php') => config_path('routes.php')]);
        }

        $this->app->get('domain')->boot();
    }
}
