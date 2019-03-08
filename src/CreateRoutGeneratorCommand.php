<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/8
 * Time: 3:41 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Console\Command;
use Illuminate\Routing\Router;

class CreateRoutGeneratorCommand extends Command
{
    protected $name = "route-generator:create";

    protected $signature = "route-generator:create {name} {--router}";

    protected $description = "创建新的路由生成器";

    public function handle()
    {
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    public function fire()
    {
        $name = $this->argument('name');
        $router = $this->option('router') ?: Router::class;
        $router = '\\'.trim($router, '\\');
        $namespace = config('routes.namespace');
        $root = config('routes.root');
        $authRoutes = '';
        $normalRoutes = '';
        if($name) {
            $path = "$root/{$name}Generator.php";
            $content =file_get_contents(__DIR__.'/../stub/RouteGenerator.stub');

            $classContent = preg_replace(['/\$NAMESPACE\$/', '/\$ROUTEGENERATOR\$/', '/\$AUTHROUTES\$/', '/\$NORMALROUTES\$/', '/\$ROUTERCLASS\$/'],
                [$namespace, "{$name}Generator", $authRoutes, $normalRoutes, $router], $content);
            file_put_contents($path, $classContent);
        }else{

        }
    }
}
