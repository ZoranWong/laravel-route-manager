<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/5
 * Time: 4:43 PM
 */

namespace ZoranWang\LaraRoutesManager;


use Throwable;

class DomainNotFoundException extends \Exception
{
    public function __construct(string $message = "服务未部署在此域名下", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
