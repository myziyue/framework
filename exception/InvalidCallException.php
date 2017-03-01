<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:17
 * @since 1.0
 */

namespace ziyue\exception;

use ziyue\core\Exception;

class InvalidCallException extends Exception
{
    public function getName()
    {
        return 'Invalid Call';
    }
}