<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/11
 * Time: 3:03 PM
 */

namespace ZoranWang\LaraRoutesManager\Adapters;


interface RouterAdapter
{
    function domain(string $domain);
    function version(string $version);
    function gateway(string $gateway);
    function group(\Closure $callback);
}
