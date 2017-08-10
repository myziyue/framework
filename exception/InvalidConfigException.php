<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午10:48
 * @since 1.0
 */

namespace ziyue\exception;

use ziyue\base\Exception;

class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Invalid Configuration';
    }
}