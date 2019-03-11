<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 2:30 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Illuminate\Support\Collection;

class RoutesManager
{
    /**
     * @var Collection|RouteGenerator[] $routes
     * */
    protected $routes = [];

    public function __construct()
    {
    }
}
