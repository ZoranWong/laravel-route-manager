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

    protected $manager = nulll;

    public function loadRoutes()
    {
        $routeGeneratorClass = $this->routeGenerator;
        $routeGeneratorClass = preg_replace('\\', '/', $routeGeneratorClass);
        $namespace = preg_replace('\\', '/', $this->namespace);
        if(!class_exists($this->routeGenerator)) {
            $path = trim($this->root, '/').'/'.trim(substr($routeGeneratorClass, strlen($namespace)), '/').'.php';
            /** @noinspection PhpIncludeInspection */
            include_once base_path($path);
        }
        /** @var RouteGenerator $generator */
        $generator = new $this->routeGenerator();
        $generator->generateRoutes();
    }
}
