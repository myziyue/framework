<?php
/**
 * Created by PhpStorm.
 *
 * @author Bi Zhiming <evan2884@gmail.com>
 * @created 2017/3/2  下午5:09
 * @since 1.0
 */

namespace ziyue\log\adapter;

use ziyue\log\Logger;

class DBLog implements Logger
{

    /**
     * 写日志
     * @param $log
     * @return mixed
     */
    public function write($log, $level, $category)
    {
        // TODO: Implement write() method.
    }
}