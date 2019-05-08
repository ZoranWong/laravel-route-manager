<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/12
 * Time: 5:17 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Container\Container;

class AdapterContainer extends Container
{
    public function registerAdapters(array $adapters)
    {
        foreach ($adapters as $class => $adapter) {
            $this->bind($class, $adapter);
        }
    }
}
