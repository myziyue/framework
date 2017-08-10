<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/1  下午11:49
 * @since 1.0
 */

namespace ziyue\base;


class Exception extends \Exception
{
    public function getName(){
        return 'Exception';
    }
}