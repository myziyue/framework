<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午4:58
 * @since 1.0
 */

namespace ziyue\base;


class Exception extends \Exception
{
    public function getName(){
        return 'Exception';
    }
}