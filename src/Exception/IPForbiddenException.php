<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 4:41 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Throwable;

class IPForbiddenException extends \Exception
{
    public function __construct(string $message = "禁止IP直接访问服务器", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
