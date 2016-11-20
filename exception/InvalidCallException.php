<?php

/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午9:56
 * @since 1.0
 */
namespace zy\exception;


class InvalidCallException extends \zy\base\Exception
{
    public function getName()
    {
        return 'Invalid Call';
    }
}