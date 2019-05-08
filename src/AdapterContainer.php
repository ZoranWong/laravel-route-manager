<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/12
 * Time: 5:17 PM
 */

namespace ZoranWang\LaraRoutesManager;


use function Clue\StreamFilter\fun;
use function Clue\StreamFilter\register;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;

class AdapterContainer extends Container
{
    public function registerAdapters(array $adapters)
    {
        foreach ($adapters as $class => $adapter) {
          // $this[$class] = $adapter;
            //var_dump($class);
           $this->bindMethod($class, function ($generator) use ($adapter){
               return new $adapter($generator);
           });
        }
    }

    /**
     * @param RouteGenerator[]|Collection $routes
     * @return Collection
     * */
    public function adapterRoutes($routes)
    {
        return $routes->map(function (RouteGenerator $generator) {
            $class = get_class($generator->router);
            $class = trim($class, '\\');
            if(!$this->hasMethodBinding($class)){
                $class = "\\{$class}";
            }
            return $this->callMethodBinding($class, $generator);
        });
    }
}
