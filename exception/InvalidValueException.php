<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/3  下午2:53
 * @since 1.0
 */

namespace ziyue\exception;

use ziyue\base\Exception;

class InvalidValueException extends Exception
{
    public function getName()
    {
        return 'Invalid Value';
    }
}