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
        if ($this->app->runningInConsole()) {
            if (!($config = config('routes', null))) {
                $this->mergeConfigFrom(realpath(__DIR__ . '/../config/routes.php'), 'routes');
                $this->publishes([realpath(__DIR__ . '/../config/routes.php') => config_path('routes.php')]);
            }
            $path = base_path(config('routes.root'));
            if (!file_exists($path)) {
                @mkdir($path);
            }

            $this->commands(CreateRoutGeneratorCommand::class);
        }
        $this->app->singleton('adapterContainer', function () {
            return new AdapterContainer();
        });
        /** @var AdapterContainer $adapterContainer */
        $adapterContainer = $this->app->get('adapterContainer');
        $adapters = $adapterContainer;
        $adapterContainer->registerAdapters($adapters);
        $this->app->singleton('domain', function ($app) use ($adapterContainer) {
            return new DomainManager($app,
                $adapterContainer,
                config('routes.domains'),
                config('routes.root'),
                config('routes.namespace'));
        });

    }

    public function boot()
    {
        $this->app->get('domain')->boot();
    }
}
