<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/8
 * Time: 11:47 AM
 */

namespace ZoranWang\LaraRoutesManager;


class Gateway
{
    protected $routeGenerator = null;

    protected $namespace = null;

    protected $root = null;

    protected $gateway = null;

    protected $route = null;

    protected $manager = null;

    /**
     * @throws
     * */
    public function boot()
    {
        $routeGeneratorClass = $this->routeGenerator;
        $routeGeneratorClass = preg_replace($this->namespace, '', $routeGeneratorClass);
        $routeGeneratorClass = preg_replace('\\', '/', $routeGeneratorClass);
        if(!class_exists($this->routeGenerator)) {
            $path = trim($this->root, '/').'/'.trim($routeGeneratorClass, '/').'.php';
            /** @noinspection PhpIncludeInspection */
            include_once base_path($path);
        }
        /** @var RouteGenerator $generator */
        $generator = new $this->routeGenerator();
        $generator->generateRoutes();
    }
}
