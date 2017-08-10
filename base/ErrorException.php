<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午11:53
 * @since 1.0
 */

namespace ziyue\base;

class ErrorException extends \ErrorException
{
    public function getName(){
        return 'Error Exception';
    }
}