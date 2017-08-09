<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午10:21
 * @since 1.0
 */

namespace ziyue\exception;

class InvalidCallException extends \BadMethodCallException
{
    public function getName(){
        return "Invalid Call";
    }
}