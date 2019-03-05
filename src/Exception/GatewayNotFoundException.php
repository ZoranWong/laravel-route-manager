<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 4:40 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Throwable;

class GatewayNotFoundException extends \Exception
{
    public function __construct(string $message = "服务网关不存在", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
