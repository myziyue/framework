<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午4:32
 * @since 1.0
 */

namespace ziyue\exception;

use ziyue\base\Exception;

class UnknownClassException extends Exception
{
    public function getName()
    {
        return 'Unknown Class';
    }
}