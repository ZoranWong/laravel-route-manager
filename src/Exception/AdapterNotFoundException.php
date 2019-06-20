<?php
/**
 * Created by PhpStorm.
 * User: wangzaron
 * Date: 2019/3/12
 * Time: 5:30 PM
 */

namespace ZoranWang\LaraRoutesManager\Exception;


use Throwable;

class AdapterNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
