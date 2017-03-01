<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午4:21
 * @since 1.0
 */

namespace ziyue\exception;

class UnknownMethodException extends \Exception
{
    public function getName()
    {
        return 'Unknown Method';
    }
}