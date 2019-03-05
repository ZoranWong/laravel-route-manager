<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 1:51 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Throwable;

class RouterNotFoundException extends \Exception
{
    public function __construct(string $message = "服务路由无法启动", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
