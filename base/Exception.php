<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2016/11/20  上午9:54
 * @since 1.0
 */

namespace zy\base;


class Exception extends \Exception
{
    public function getName()
    {
        return "Exception";
    }
}