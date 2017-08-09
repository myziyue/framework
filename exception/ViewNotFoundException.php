<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/8/9  下午10:31
 * @since 1.0
 */

namespace ziyue\exception;

use \ziyue\base\Exception;

class ViewNotFoundException extends Exception
{
    public function getName()
    {
        return 'View Not Found';
    }
}