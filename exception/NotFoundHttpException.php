<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/8  上午12:00
 * @since 1.0
 */

namespace ziyue\exception;

class NotFoundHttpException extends \Exception
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}